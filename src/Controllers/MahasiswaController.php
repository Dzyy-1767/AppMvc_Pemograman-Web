<?php
/**
 * MahasiswaController – menangani request yang berkaitan dengan data mahasiswa.
 *
 * Aturan: controller tidak boleh mengandung SQL maupun HTML.
 * Tugasnya hanya mengambil data dari repository lalu meneruskannya ke view.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Controllers;

use PDOException;
use UMM\Pemweb\Model\Mahasiswa;
use UMM\Pemweb\Repository\PdoMahasiswaRepository;

class MahasiswaController
{
    private ?PdoMahasiswaRepository $repository = null;

    /**
     * Menampilkan daftar seluruh mahasiswa.
     */
    public function index(): void
    {
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
            $this->repository()->all()
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
     */
    public function tambah(): void
    {
        $judul = 'Tambah Mahasiswa';
        $deskripsiHalaman = 'Lengkapi identitas mahasiswa untuk menyimpan data baru.';
        $menuAktif = 'mahasiswa-form';
        $mahasiswaId = null;
        $oldInput = ['nim' => '', 'nama' => '', 'prodi' => ''];
        $errors = [];

        require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
    }

    /**
     * Menampilkan form edit mahasiswa.
     */
    public function edit(): void
    {
        $id = $this->idAngka($_GET['id'] ?? null);
        $mahasiswa = $id === null ? null : $this->repository()->findById($id);

        if ($mahasiswa === null) {
            $this->tampilkanTidakDitemukan();
            return;
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
     */
    public function simpan(): void
    {
        $oldInput = [
            'nim' => $this->nilaiPost('nim'),
            'nama' => $this->nilaiPost('nama'),
            'prodi' => $this->nilaiPost('prodi'),
        ];
        $errors = $this->validasi($oldInput);

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Tambah Mahasiswa';
            $deskripsiHalaman = 'Lengkapi identitas mahasiswa untuk menyimpan data baru.';
            $menuAktif = 'mahasiswa-form';
            $mahasiswaId = null;
            require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
            return;
        }

        $this->repository()->save(new Mahasiswa(
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
     * Memvalidasi dan memperbarui data mahasiswa.
     */
    public function update(): void
    {
        $id = $this->idAngka($this->nilaiPost('id'));
        $mahasiswa = $id === null ? null : $this->repository()->findById($id);

        if ($mahasiswa === null) {
            $this->tampilkanTidakDitemukan();
            return;
        }

        $oldInput = [
            'nim' => $this->nilaiPost('nim'),
            'nama' => $this->nilaiPost('nama'),
            'prodi' => $this->nilaiPost('prodi'),
        ];
        $errors = $this->validasi($oldInput, $id);

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Edit Mahasiswa';
            $deskripsiHalaman = 'Perbarui identitas mahasiswa yang dipilih.';
            $menuAktif = 'mahasiswa-form';
            $mahasiswaId = $id;
            require dirname(__DIR__, 2) . '/views/mahasiswa/tambah.php';
            return;
        }

        $this->repository()->update(new Mahasiswa(
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
     * Menghapus mahasiswa tanpa menghapus nilai yang terkait.
     */
    public function hapus(): void
    {
        $id = $this->idAngka($this->nilaiPost('id'));
        if ($id === null) {
            $this->setFlash('error', 'ID mahasiswa tidak valid.');
            header('Location: /mahasiswa');
            exit;
        }

        $repository = $this->repository();
        if ($repository->findById($id) === null) {
            $this->setFlash('error', 'Data mahasiswa tidak ditemukan.');
            header('Location: /mahasiswa');
            exit;
        }

        try {
            $repository->delete($id);
            $this->setFlash('success', 'Data mahasiswa berhasil dihapus.');
        } catch (\DomainException) {
            $this->setFlash('error', 'Mahasiswa masih memiliki data nilai. Hapus nilainya terlebih dahulu.');
        } catch (PDOException $e) {
            if ((string) $e->getCode() !== '23000') {
                throw $e;
            }

            $this->setFlash('error', 'Mahasiswa masih memiliki data nilai. Hapus nilainya terlebih dahulu.');
        }

        header('Location: /mahasiswa');
        exit;
    }

    /**
     * Validasi data mahasiswa untuk proses simpan dan pembaruan.
     *
     * @param array{nim:string,nama:string,prodi:string} $input
     * @return array<string, string>
     */
    private function validasi(array $input, ?int $exceptId = null): array
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

            $panjang = preg_match_all('/./us', $input[$field]);
            if ($panjang === false) {
                $errors[$field] = $label . ' harus berupa teks UTF-8 yang valid.';
            } elseif ($panjang > 100) {
                $errors[$field] = $label . ' maksimal 100 karakter.';
            }
        }

        if ($errors === [] && $this->repository()->existsByNim($input['nim'], $exceptId)) {
            $errors['nim'] = 'NIM sudah digunakan.';
        }

        return $errors;
    }

    private function repository(): PdoMahasiswaRepository
    {
        if ($this->repository === null) {
            $this->repository = new PdoMahasiswaRepository();
        }

        return $this->repository;
    }

    private function idAngka(mixed $nilai): ?int
    {
        if (!is_string($nilai) || !ctype_digit($nilai)) {
            return null;
        }

        $id = filter_var($nilai, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        return is_int($id) ? $id : null;
    }

    private function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function tampilkanTidakDitemukan(): void
    {
        http_response_code(404);
        $judul = 'Data tidak ditemukan';
        $menuAktif = 'mahasiswa';
        require dirname(__DIR__, 2) . '/views/mahasiswa/tidak-ditemukan.php';
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
