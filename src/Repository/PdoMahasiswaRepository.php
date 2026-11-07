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

    public function findById(int $id): ?Mahasiswa
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nim, nama, prodi FROM mahasiswa WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $baris = $stmt->fetch();

        if ($baris === false) {
            return null;
        }

        return new Mahasiswa(
            id: (int) $baris['id'],
            nim: $baris['nim'],
            nama: $baris['nama'],
            prodi: $baris['prodi'],
        );
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
    public function existsByNim(string $nim, ?int $exceptId = null): bool
    {
        $sql = 'SELECT 1 FROM mahasiswa WHERE nim = :nim';
        if ($exceptId !== null) {
            $sql .= ' AND id <> :except_id';
        }
        $sql .= ' LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $parameter = ['nim' => $nim];
        if ($exceptId !== null) {
            $parameter['except_id'] = $exceptId;
        }
        $stmt->execute($parameter);

        return $stmt->fetchColumn() !== false;
    }

    public function update(Mahasiswa $m): void
    {
        $id = $m->getId();
        if ($id === null) {
            throw new \InvalidArgumentException('ID mahasiswa wajib tersedia untuk pembaruan.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi WHERE id = :id'
        );
        $stmt->execute([
            'nim' => $m->getNim(),
            'nama' => $m->getNama(),
            'prodi' => $m->getProdi(),
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                'SELECT id FROM mahasiswa WHERE id = :id FOR UPDATE'
            );
            $stmt->execute(['id' => $id]);
            if ($stmt->fetchColumn() === false) {
                $this->pdo->commit();
                return;
            }

            $stmt = $this->pdo->prepare(
                'SELECT 1 FROM nilai WHERE mahasiswa_id = :id LIMIT 1'
            );
            $stmt->execute(['id' => $id]);
            if ($stmt->fetchColumn() !== false) {
                throw new \DomainException('Mahasiswa masih memiliki data nilai.');
            }

            $stmt = $this->pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}
