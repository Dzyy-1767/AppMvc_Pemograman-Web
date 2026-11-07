<?php
/**
 * Interface MahasiswaRepository – kontrak yang harus dipenuhi semua implementasi
 * repository mahasiswa.
 *
 * Memisahkan kontrak (apa yang bisa dilakukan) dari implementasi (bagaimana caranya),
 * sehingga mudah diganti (mis. dari PDO ke Eloquent) tanpa mengubah controller.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Repository;

use UMM\Pemweb\Model\Mahasiswa;

interface MahasiswaRepository
{
    /**
     * Ambil semua data mahasiswa dari sumber data.
     *
     * @return Mahasiswa[] Array objek Mahasiswa (kosong jika tidak ada data)
     */
    public function all(): array;

    public function jumlah(): int;

    public function findById(int $id): ?Mahasiswa;

    /**
     * Simpan mahasiswa baru.
     */
    public function save(Mahasiswa $m): void;

    /**
     * Periksa apakah NIM sudah digunakan.
     */
    public function existsByNim(string $nim, ?int $exceptId = null): bool;

    public function update(Mahasiswa $m): void;

    /**
     * Hapus mahasiswa jika tidak memiliki nilai terkait.
     *
     * @throws \DomainException Jika mahasiswa masih memiliki nilai.
     */
    public function delete(int $id): void;
}
