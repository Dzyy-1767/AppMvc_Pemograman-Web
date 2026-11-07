<?php
/**
 * MahasiswaController – menangani request yang berkaitan dengan data mahasiswa.
 *
 * Aturan: controller tidak boleh mengandung SQL maupun HTML.
 * Tugasnya hanya mengambil data dari repository lalu meneruskannya ke view.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Controllers;

use UMM\Pemweb\Repository\PdoMahasiswaRepository;
use UMM\Pemweb\Model\Mahasiswa;

class MahasiswaController
{
    /**
     * Menampilkan daftar seluruh mahasiswa.
     *
     * GET /mahasiswa
     */
    public function index(): void
    {
        // ── 1. Ambil data dari repository ─────────────────────────────────
        $repository  = new PdoMahasiswaRepository();
        $daftarObjek = $repository->all();   // array objek Mahasiswa

        // ── 2. Ubah ke array asosiatif siap tampil ────────────────────────
        //      View tidak perlu tahu tentang kelas Mahasiswa
        $daftarMahasiswa = array_map(
            fn($mhs) => [
                'id'    => $mhs->getId(),
                'nim'   => $mhs->getNim(),
                'nama'  => $mhs->getNama(),
                'prodi' => $mhs->getProdi(),
            ],
            $daftarObjek
        );

        // ── 3. Teruskan ke view ───────────────────────────────────────────
        $judul = 'Daftar Mahasiswa';
        $menuAktif = 'mahasiswa';
        require dirname(__DIR__, 2) . '/views/mahasiswa/index.php';
    }

    /**
     * Menampilkan form tambah mahasiswa.
     *
     * GET /mahasiswa/tambah
     */
    public function tambah(): void
    {
        $judul    = 'Tambah Mahasiswa';
        $menuAktif = 'mahasiswa-form';
        $oldInput = ['nim' => '', 'nama' => '', 'prodi' => ''];
        $errors   = [];

        require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
    }

    /**
     * Memvalidasi dan menyimpan mahasiswa baru.
     *
     * POST /mahasiswa/simpan
     */
    public function simpan(): void
    {
        $oldInput = [
            'nim'   => $this->nilaiPost('nim'),
            'nama'  => $this->nilaiPost('nama'),
            'prodi' => $this->nilaiPost('prodi'),
        ];
        $errors = [];

        if ($oldInput['nim'] === '') {
            $errors['nim'] = 'NIM wajib diisi.';
        } elseif (!ctype_digit($oldInput['nim'])) {
            $errors['nim'] = 'NIM hanya boleh berisi angka.';
        } elseif (strlen($oldInput['nim']) > 20) {
            $errors['nim'] = 'NIM maksimal 20 karakter.';
        }

        foreach (['nama' => 'Nama', 'prodi' => 'Program studi'] as $field => $label) {
            if ($oldInput[$field] === '') {
                $errors[$field] = $label . ' wajib diisi.';
            }
        }

        if ($errors === []) {
            $repository = new PdoMahasiswaRepository();
            if ($repository->existsByNim($oldInput['nim'])) {
                $errors['nim'] = 'NIM sudah digunakan.';
            }
        }

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Tambah Mahasiswa';
            $menuAktif = 'mahasiswa-form';
            require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
            return;
        }

        $repository->save(new Mahasiswa(
            id: null,
            nim: $oldInput['nim'],
            nama: $oldInput['nama'],
            prodi: $oldInput['prodi'],
        ));

        header('Location: /mahasiswa');
        exit;
    }

    /**
     * Ambil input POST berupa teks dan hapus spasi di awal serta akhir.
     */
    private function nilaiPost(string $kunci): string
    {
        $nilai = $_POST[$kunci] ?? '';

        return is_string($nilai) ? trim($nilai) : '';
    }
}
