<?php
/**
 * PdoMahasiswaRepository – implementasi MahasiswaRepository menggunakan PDO.
 *
 * Semua query SQL ada di sini; kelas lain tidak perlu tahu cara aksesnya.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Repository;

use PDO;
use UMM\Pemweb\Model\Mahasiswa;

class PdoMahasiswaRepository implements MahasiswaRepository
{
    private PDO $pdo;

    public function __construct()
    {
        // Ambil koneksi PDO dari file konfigurasi
        $this->pdo = require dirname(__DIR__) . '/config/koneksi.php';
    }

    /**
     * {@inheritdoc}
     *
     * Menggunakan prepared statement untuk keamanan (mencegah SQL Injection).
     *
     * @return Mahasiswa[]
     */
    public function all(): array
    {
        $sql  = 'SELECT id, nim, nama, prodi FROM mahasiswa ORDER BY id ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $hasil = [];

        // Mapping setiap baris hasil query ke objek Mahasiswa
        while ($baris = $stmt->fetch()) {
            $hasil[] = new Mahasiswa(
                id:    (int) $baris['id'],
                nim:   $baris['nim'],
                nama:  $baris['nama'],
                prodi: $baris['prodi'],
            );
        }

        return $hasil;
    }
}

