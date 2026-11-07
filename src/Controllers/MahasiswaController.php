<?php
/**
 * MahasiswaController – menangani request yang berkaitan dengan data mahasiswa.
 *
 * Aturan: controller tidak boleh mengandung SQL maupun HTML.
 * Tugasnya hanya mengambil data dari repository lalu meneruskannya ke view.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Controllers;

use UMM\Pemweb\Repository\PdoMahasiswaRepository;
use UMM\Pemweb\Model\Mahasiswa;

class MahasiswaController
{
    /**
     * Menampilkan daftar seluruh mahasiswa.
     *
     * GET /mahasiswa
     */
    public function index(): void
    {
        $repository  = new PdoMahasiswaRepository();
        $paletAvatar = ['aksen', 'grade-a', 'grade-b', 'mustard', 'grade-e'];
        $daftarMahasiswa = array_map(
            static function (Mahasiswa $mhs) use ($paletAvatar): array {
                $nama = trim($mhs->getNama());
                preg_match('/^./us', $nama, $hurufPertama);
                $inisial = strtoupper($hurufPertama[0] ?? '?');
                $warna = hexdec(substr(hash('crc32b', strtolower($inisial)), 0, 7))
                    % count($paletAvatar);

                return [
                    'id' => $mhs->getId(),
                    'nim' => $mhs->getNim(),
                    'nama' => $mhs->getNama(),
                    'prodi' => $mhs->getProdi(),
                    'inisial' => $inisial,
                    'kelas_avatar' => 'avatar--' . $paletAvatar[$warna],
                ];
            },
            $repository->all()
        );

        $judul = 'Daftar Mahasiswa';
        $deskripsiHalaman = 'Kelola data mahasiswa dan identitas akademiknya.';
        $menuAktif = 'mahasiswa';
        $aksiUtama = ['label' => 'Tambah Mahasiswa', 'href' => '/mahasiswa/tambah'];
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require dirname(__DIR__, 2) . '/views/mahasiswa/index.php';
    }

    /**
     * Menampilkan form tambah mahasiswa.
     *
     * GET /mahasiswa/tambah
     */
    public function tambah(): void
    {
        $judul    = 'Tambah Mahasiswa';
        $deskripsiHalaman = 'Lengkapi identitas mahasiswa untuk menyimpan data baru.';
        $menuAktif = 'mahasiswa-form';
        $mahasiswaId = null;
        $oldInput = ['nim' => '', 'nama' => '', 'prodi' => ''];
        $errors   = [];

        require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
    }

    /**
     * Menampilkan form edit mahasiswa.
     *
     * GET /mahasiswa/edit?id=<id>
     */
    public function edit(): void
    {
        $id = $this->idRequest();
        $mahasiswa = (new PdoMahasiswaRepository())->findById($id);
        if ($mahasiswa === null) {
            $this->notFound();
        }

        $judul = 'Edit Mahasiswa';
        $deskripsiHalaman = 'Perbarui identitas mahasiswa yang dipilih.';
        $menuAktif = 'mahasiswa-form';
        $mahasiswaId = $id;
        $oldInput = [
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'prodi' => $mahasiswa->getProdi(),
        ];
        $errors = [];

        require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
    }

    /**
     * Memvalidasi dan menyimpan mahasiswa baru.
     *
     * POST /mahasiswa/simpan
     */
    public function simpan(): void
    {
        $oldInput = [
            'nim'   => $this->nilaiPost('nim'),
            'nama'  => $this->nilaiPost('nama'),
            'prodi' => $this->nilaiPost('prodi'),
        ];
        $errors = $this->validasi($oldInput);

        if ($errors === []) {
            $repository = new PdoMahasiswaRepository();
            if ($repository->existsByNim($oldInput['nim'])) {
                $errors['nim'] = 'NIM sudah digunakan.';
            }
        }

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Tambah Mahasiswa';
            $deskripsiHalaman = 'Lengkapi identitas mahasiswa untuk menyimpan data baru.';
            $menuAktif = 'mahasiswa-form';
            $mahasiswaId = null;
            require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
            return;
        }

        $repository->save(new Mahasiswa(
            id: null,
            nim: $oldInput['nim'],
            nama: $oldInput['nama'],
            prodi: $oldInput['prodi'],
        ));

        $this->setFlash('success', 'Data mahasiswa berhasil ditambahkan.');
        header('Location: /mahasiswa');
        exit;
    }

    /**
     * Memvalidasi dan memperbarui mahasiswa.
     *
     * POST /mahasiswa/update
     */
    public function update(): void
    {
        $id = $this->idPost();
        $repository = new PdoMahasiswaRepository();
        if ($repository->findById($id) === null) {
            $this->notFound();
        }

        $oldInput = [
            'nim' => $this->nilaiPost('nim'),
            'nama' => $this->nilaiPost('nama'),
            'prodi' => $this->nilaiPost('prodi'),
        ];
        $errors = $this->validasi($oldInput);
        if ($errors === [] && $repository->existsByNim($oldInput['nim'], $id)) {
            $errors['nim'] = 'NIM sudah digunakan.';
        }

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Edit Mahasiswa';
            $deskripsiHalaman = 'Perbarui identitas mahasiswa yang dipilih.';
            $menuAktif = 'mahasiswa-form';
            $mahasiswaId = $id;
            require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
            return;
        }

        $repository->update(new Mahasiswa(
            id: $id,
            nim: $oldInput['nim'],
            nama: $oldInput['nama'],
            prodi: $oldInput['prodi'],
        ));

        $this->setFlash('success', 'Data mahasiswa berhasil diperbarui.');
        header('Location: /mahasiswa');
        exit;
    }

    /**
     * Menghapus mahasiswa tanpa data nilai terkait.
     *
     * POST /mahasiswa/hapus
     */
    public function hapus(): void
    {
        $id = $this->idPost();
        $repository = new PdoMahasiswaRepository();
        if ($repository->findById($id) === null) {
            $this->setFlash('error', 'Data mahasiswa tidak ditemukan.');
        } else {
            try {
                $repository->delete($id);
                $this->setFlash('success', 'Data mahasiswa berhasil dihapus.');
            } catch (\DomainException) {
                $this->setFlash('error', 'Mahasiswa masih memiliki data nilai. Hapus nilainya terlebih dahulu.');
            }
        }

        header('Location: /mahasiswa');
        exit;
    }

    /**
     * @param array{nim:string,nama:string,prodi:string} $input
     * @return array<string,string>
     */
    private function validasi(array $input): array
    {
        $errors = [];
        if ($input['nim'] === '') {
            $errors['nim'] = 'NIM wajib diisi.';
        } elseif (!ctype_digit($input['nim'])) {
            $errors['nim'] = 'NIM hanya boleh berisi angka.';
        } elseif (strlen($input['nim']) > 20) {
            $errors['nim'] = 'NIM maksimal 20 karakter.';
        }

        foreach (['nama' => 'Nama', 'prodi' => 'Program studi'] as $field => $label) {
            if ($input[$field] === '') {
                $errors[$field] = $label . ' wajib diisi.';
                continue;
            }

            $length = preg_match_all('/./us', $input[$field]);
            if ($length === false) {
                $errors[$field] = $label . ' harus berupa teks UTF-8 yang valid.';
            } elseif ($length > 100) {
                $errors[$field] = $label . ' maksimal 100 karakter.';
            }
        }

        return $errors;
    }

    private function idRequest(): int
    {
        $id = $_GET['id'] ?? '';
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            $this->notFound();
        }

        return (int) $id;
    }

    private function idPost(): int
    {
        $id = $this->nilaiPost('id');
        if ($id === '' || !ctype_digit($id) || (int) $id < 1) {
            $this->notFound();
        }

        return (int) $id;
    }

    private function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function notFound(): never
    {
        http_response_code(404);
        echo '<h1>404 – Mahasiswa tidak ditemukan</h1>';
        exit;
    }

    /**
     * Ambil input POST berupa teks dan hapus spasi di awal serta akhir.
     */
    private function nilaiPost(string $kunci): string
    {
        $nilai = $_POST[$kunci] ?? '';

        return is_string($nilai) ? trim($nilai) : '';
    }
}
