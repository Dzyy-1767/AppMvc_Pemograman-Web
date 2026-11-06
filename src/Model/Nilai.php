<?php
/**
 * Model Nilai – merepresentasikan satu nilai mahasiswa.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Model;

class Nilai
{
    public function __construct(
        private readonly ?int $id,
        private readonly int $mahasiswaId,
        private readonly string $mataKuliah,
        private readonly float $nilaiAkhir,
        private readonly string $nim = '',
        private readonly string $namaMahasiswa = '',
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMahasiswaId(): int
    {
        return $this->mahasiswaId;
    }

    public function getMataKuliah(): string
    {
        return $this->mataKuliah;
    }

    public function getNilaiAkhir(): float
    {
        return $this->nilaiAkhir;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNamaMahasiswa(): string
    {
        return $this->namaMahasiswa;
    }
}
