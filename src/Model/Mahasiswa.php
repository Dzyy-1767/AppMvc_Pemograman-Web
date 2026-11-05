<?php
/**
 * Model Mahasiswa – merepresentasikan satu entitas mahasiswa.
 *
 * Kelas ini hanya berisi data dan getter; tidak mengandung logika SQL maupun HTML.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Model;

class Mahasiswa
{
    /**
     * @param ?int   $id    Primary key; null untuk data baru
     * @param string $nim   Nomor Induk Mahasiswa (unik)
     * @param string $nama  Nama lengkap mahasiswa
     * @param string $prodi Program studi
     */
    public function __construct(
        private readonly ?int   $id,
        private readonly string $nim,
        private readonly string $nama,
        private readonly string $prodi,
    ) {}

    // ── Getter ────────────────────────────────────────────────────────────

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }
}
