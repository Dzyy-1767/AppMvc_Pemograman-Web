<?php
/**
 * Kontrak akses data nilai.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Repository;

use UMM\Pemweb\Model\Nilai;

interface NilaiRepository
{
    /**
     * @return Nilai[]
     */
    public function all(): array;

    public function jumlah(): int;

    public function findById(int $id): ?Nilai;

    public function save(Nilai $nilai): void;

    public function update(Nilai $nilai): void;

    public function delete(int $id): void;
}
