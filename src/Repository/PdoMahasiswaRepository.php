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

    public function jumlah(): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM mahasiswa');
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Simpan mahasiswa baru menggunakan prepared statement.
     */
    public function save(Mahasiswa $m): void
    {
        $sql  = 'INSERT INTO mahasiswa (nim, nama, prodi) VALUES (:nim, :nama, :prodi)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nim'   => $m->getNim(),
            'nama'  => $m->getNama(),
            'prodi' => $m->getProdi(),
        ]);
    }

    /**
     * Periksa apakah NIM sudah digunakan menggunakan prepared statement.
     */
    public function existsByNim(string $nim): bool
    {
        $sql  = 'SELECT 1 FROM mahasiswa WHERE nim = :nim LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nim' => $nim]);

        return $stmt->fetchColumn() !== false;
    }
}
