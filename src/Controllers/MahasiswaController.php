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
        require dirname(__DIR__, 2) . '/views/mahasiswa/index.php';
    }
}

