<?php
/**
 * Implementasi repository nilai menggunakan PDO.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Repository;

use PDO;
use UMM\Pemweb\Model\Nilai;

class PdoNilaiRepository implements NilaiRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = require dirname(__DIR__) . '/config/koneksi.php';
    }

    /**
     * @return Nilai[]
     */
    public function all(): array
    {
        $sql = 'SELECT n.id, n.mahasiswa_id, n.mata_kuliah, n.nilai_akhir, m.nim, m.nama
                FROM nilai n
                INNER JOIN mahasiswa m ON m.id = n.mahasiswa_id
                ORDER BY n.id ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $hasil = [];
        while ($baris = $stmt->fetch()) {
            $hasil[] = new Nilai(
                id: (int) $baris['id'],
                mahasiswaId: (int) $baris['mahasiswa_id'],
                mataKuliah: $baris['mata_kuliah'],
                nilaiAkhir: (float) $baris['nilai_akhir'],
                nim: $baris['nim'],
                namaMahasiswa: $baris['nama'],
            );
        }

        return $hasil;
    }

    public function findById(int $id): ?Nilai
    {
        $stmt = $this->pdo->prepare(
            'SELECT n.id, n.mahasiswa_id, n.mata_kuliah, n.nilai_akhir, m.nim, m.nama
             FROM nilai n
             INNER JOIN mahasiswa m ON m.id = n.mahasiswa_id
             WHERE n.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $baris = $stmt->fetch();

        if ($baris === false) {
            return null;
        }

        return new Nilai(
            id: (int) $baris['id'],
            mahasiswaId: (int) $baris['mahasiswa_id'],
            mataKuliah: $baris['mata_kuliah'],
            nilaiAkhir: (float) $baris['nilai_akhir'],
            nim: $baris['nim'],
            namaMahasiswa: $baris['nama'],
        );
    }

    public function save(Nilai $nilai): void
    {
        $sql = 'INSERT INTO nilai (mahasiswa_id, mata_kuliah, nilai_akhir)
                VALUES (:mahasiswa_id, :mata_kuliah, :nilai_akhir)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'mahasiswa_id' => $nilai->getMahasiswaId(),
            'mata_kuliah' => $nilai->getMataKuliah(),
            'nilai_akhir' => $nilai->getNilaiAkhir(),
        ]);
    }

    public function update(Nilai $nilai): void
    {
        if ($nilai->getId() === null) {
            throw new \InvalidArgumentException('ID nilai wajib tersedia untuk pembaruan.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE nilai
             SET mahasiswa_id = :mahasiswa_id, mata_kuliah = :mata_kuliah, nilai_akhir = :nilai_akhir
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $nilai->getId(),
            'mahasiswa_id' => $nilai->getMahasiswaId(),
            'mata_kuliah' => $nilai->getMataKuliah(),
            'nilai_akhir' => $nilai->getNilaiAkhir(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM nilai WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
