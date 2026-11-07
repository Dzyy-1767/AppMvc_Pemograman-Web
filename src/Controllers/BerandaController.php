<?php
/**
 * Menyiapkan ringkasan aplikasi untuk halaman beranda.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Controllers;

use UMM\Pemweb\Repository\PdoMahasiswaRepository;
use UMM\Pemweb\Repository\PdoNilaiRepository;
use UMM\Pemweb\Service\GradeCalculator;

class BerandaController
{
    public function index(): void
    {
        $totalMahasiswa = (new PdoMahasiswaRepository())->jumlah();
        $totalNilai = (new PdoNilaiRepository())->jumlah();
        $rentangGrade = array_map(
            static fn(array $rentang): array => $rentang + [
                'kelas' => 'grade-' . strtolower($rentang['grade']),
            ],
            (new GradeCalculator())->rentang()
        );
        $judul = 'Beranda';
        $deskripsiHalaman = 'Sistem informasi untuk mengelola mahasiswa, nilai akhir, dan rentang grade.';
        $menuAktif = 'beranda';

        require dirname(__DIR__, 2) . '/views/beranda/index.php';
    }
}
