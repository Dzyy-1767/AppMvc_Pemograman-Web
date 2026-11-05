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
use PDOException;

class MahasiswaController
{
    private ?PdoMahasiswaRepository $repository = null;

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
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

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
        $errors = $this->validasi($oldInput);

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Tambah Mahasiswa';
            require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
            return;
        }

        $this->repository()->save(new Mahasiswa(
            id: null,
            nim: $oldInput['nim'],
            nama: $oldInput['nama'],
            prodi: $oldInput['prodi'],
        ));

        header('Location: /mahasiswa');
        exit;
    }

    /**
     * Menampilkan form edit mahasiswa.
     *
     * GET /mahasiswa/edit?id=...
     */
    public function edit(): void
    {
        $id = $this->idAngka($_GET['id'] ?? null);
        $mahasiswa = $id === null ? null : $this->repository()->find($id);

        if ($mahasiswa === null) {
            $this->tampilkanTidakDitemukan();
            return;
        }

        $judul = 'Edit Mahasiswa';
        $oldInput = [
            'id'    => (string) $mahasiswa->getId(),
            'nim'   => $mahasiswa->getNim(),
            'nama'  => $mahasiswa->getNama(),
            'prodi' => $mahasiswa->getProdi(),
        ];
        $errors = [];

        require dirname(__DIR__, 2) . '/views/mahasiswa/edit.php';
    }

    /**
     * Memvalidasi dan memperbarui data mahasiswa.
     *
     * POST /mahasiswa/update
     */
    public function update(): void
    {
        $oldInput = [
            'id'    => $this->nilaiPost('id'),
            'nim'   => $this->nilaiPost('nim'),
            'nama'  => $this->nilaiPost('nama'),
            'prodi' => $this->nilaiPost('prodi'),
        ];
        $id = $this->idAngka($oldInput['id']);
        $mahasiswa = $id === null ? null : $this->repository()->find($id);

        if ($mahasiswa === null) {
            $this->tampilkanTidakDitemukan();
            return;
        }

        $errors = $this->validasi([
            'nim' => $oldInput['nim'],
            'nama' => $oldInput['nama'],
            'prodi' => $oldInput['prodi'],
        ], $id);
        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Edit Mahasiswa';
            require dirname(__DIR__, 2) . '/views/mahasiswa/edit.php';
            return;
        }

        $this->repository()->update(new Mahasiswa(
            id: $id,
            nim: $oldInput['nim'],
            nama: $oldInput['nama'],
            prodi: $oldInput['prodi'],
        ));

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Data mahasiswa berhasil diperbarui.',
        ];
        header('Location: /mahasiswa');
        exit;
    }

    /**
     * Menghapus mahasiswa tanpa menghapus nilai yang terkait.
     *
     * POST /mahasiswa/hapus
     */
    public function hapus(): void
    {
        $id = $this->idAngka($this->nilaiPost('id'));
        if ($id === null) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'ID mahasiswa tidak valid.',
            ];
            header('Location: /mahasiswa');
            exit;
        }

        $repository = $this->repository();
        if ($repository->find($id) === null) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data tidak ditemukan.',
            ];
            header('Location: /mahasiswa');
            exit;
        }

        try {
            $repository->delete($id);
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Data mahasiswa berhasil dihapus.',
            ];
        } catch (\DomainException $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Mahasiswa masih memiliki data nilai, hapus nilainya dulu',
            ];
        } catch (PDOException $e) {
            if ((string) $e->getCode() !== '23000') {
                throw $e;
            }

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Mahasiswa masih memiliki data nilai, hapus nilainya dulu',
            ];
        }

        header('Location: /mahasiswa');
        exit;
    }

    /**
     * Validasi data mahasiswa untuk proses simpan dan pembaruan.
     *
     * @param array{nim:string,nama:string,prodi:string} $input
     * @return array<string, string>
     */
    private function validasi(array $input, ?int $kecualiId = null): array
    {
        $errors = [];

        if ($input['nim'] === '') {
            $errors['nim'] = 'NIM wajib diisi.';
        } elseif (!ctype_digit($input['nim'])) {
            $errors['nim'] = 'NIM hanya boleh berisi angka.';
        } elseif (strlen($input['nim']) > 20) {
            $errors['nim'] = 'NIM maksimal 20 karakter.';
        }

        foreach (['nama' => 'Nama', 'prodi' => 'Program studi'] as $field => $label) {
            if ($input[$field] === '') {
                $errors[$field] = $label . ' wajib diisi.';
            }
        }

        if ($errors === [] && $this->repository()->existsByNim($input['nim'], $kecualiId)) {
            $errors['nim'] = 'NIM sudah digunakan.';
        }

        return $errors;
    }

    /**
     * Ambil repository, dan buat koneksi saat pertama kali dibutuhkan.
     */
    private function repository(): PdoMahasiswaRepository
    {
        if ($this->repository === null) {
            $this->repository = new PdoMahasiswaRepository();
        }

        return $this->repository;
    }

    /**
     * Ubah input ID positif menjadi integer, atau null jika tidak valid.
     */
    private function idAngka(mixed $nilai): ?int
    {
        if (!is_string($nilai) || !ctype_digit($nilai)) {
            return null;
        }

        $id = filter_var($nilai, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return is_int($id) ? $id : null;
    }

    /**
     * Tampilkan respons 404 melalui view.
     */
    private function tampilkanTidakDitemukan(): void
    {
        http_response_code(404);
        $judul = 'Data tidak ditemukan';
        require dirname(__DIR__, 2) . '/views/mahasiswa/tidak-ditemukan.php';
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
