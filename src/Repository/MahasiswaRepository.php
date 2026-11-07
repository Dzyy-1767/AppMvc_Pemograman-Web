<?php
/**
 * Kontrak akses data mahasiswa.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Repository;

use UMM\Pemweb\Model\Mahasiswa;

interface MahasiswaRepository
{
    /**
     * @return Mahasiswa[]
     */
    public function all(): array;

    public function jumlah(): int;

    public function findById(int $id): ?Mahasiswa;

    public function save(Mahasiswa $m): void;

    public function existsByNim(string $nim, ?int $exceptId = null): bool;

    public function update(Mahasiswa $m): void;

    /**
     * @throws \DomainException Jika mahasiswa masih memiliki nilai terkait.
     */
    public function delete(int $id): void;
}
