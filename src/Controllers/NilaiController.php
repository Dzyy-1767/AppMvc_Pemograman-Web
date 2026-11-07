<?php
/**
 * Menangani daftar, form, dan penyimpanan nilai mahasiswa.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Controllers;

use UMM\Pemweb\Model\Nilai;
use UMM\Pemweb\Repository\PdoMahasiswaRepository;
use UMM\Pemweb\Repository\PdoNilaiRepository;
use UMM\Pemweb\Service\GradeCalculator;

class NilaiController
{
    public function index(): void
    {
        $repository = new PdoNilaiRepository();
        $calculator = new GradeCalculator();
        $daftarNilai = array_map(
            static fn(Nilai $nilai): array => [
                'id' => $nilai->getId(),
                'nim' => $nilai->getNim(),
                'nama' => $nilai->getNamaMahasiswa(),
                'mata_kuliah' => $nilai->getMataKuliah(),
                'nilai_akhir' => $nilai->getNilaiAkhir(),
                'grade' => $calculator->calculate($nilai->getNilaiAkhir()),
            ],
            $repository->all()
        );

        $judul = 'Daftar Nilai';
        require dirname(__DIR__, 2) . '/views/nilai/index.php';
    }

    public function tambah(): void
    {
        $judul = 'Tambah Nilai';
        $mahasiswa = (new PdoMahasiswaRepository())->all();
        $oldInput = ['mahasiswa_id' => '', 'mata_kuliah' => '', 'nilai_akhir' => ''];
        $errors = [];

        require dirname(__DIR__, 2) . '/views/nilai/tambah.php';
    }

    public function edit(): void
    {
        $id = $this->idRequest();
        $repository = new PdoNilaiRepository();
        $nilai = $repository->findById($id);
        if ($nilai === null) {
            $this->notFound();
        }

        $judul = 'Edit Nilai';
        $mahasiswa = (new PdoMahasiswaRepository())->all();
        $oldInput = [
            'mahasiswa_id' => (string) $nilai->getMahasiswaId(),
            'mata_kuliah' => $nilai->getMataKuliah(),
            'nilai_akhir' => number_format($nilai->getNilaiAkhir(), 2, '.', ''),
        ];
        $errors = [];

        require dirname(__DIR__, 2) . '/views/nilai/tambah.php';
    }

    public function simpan(): void
    {
        $oldInput = [
            'mahasiswa_id' => $this->nilaiPost('mahasiswa_id'),
            'mata_kuliah' => $this->nilaiPost('mata_kuliah'),
            'nilai_akhir' => $this->nilaiPost('nilai_akhir'),
        ];
        $mahasiswa = (new PdoMahasiswaRepository())->all();
        [$errors, $nilaiAkhir] = $this->validasi($oldInput, $mahasiswa);

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Tambah Nilai';
            require dirname(__DIR__, 2) . '/views/nilai/tambah.php';
            return;
        }

        (new PdoNilaiRepository())->save(new Nilai(
            id: null,
            mahasiswaId: (int) $oldInput['mahasiswa_id'],
            mataKuliah: $oldInput['mata_kuliah'],
            nilaiAkhir: $nilaiAkhir,
        ));

        header('Location: /nilai');
        exit;
    }

    public function update(): void
    {
        $id = $this->idPost();
        $repository = new PdoNilaiRepository();
        $nilaiLama = $repository->findById($id);
        if ($nilaiLama === null) {
            $this->notFound();
        }

        $oldInput = [
            'mahasiswa_id' => $this->nilaiPost('mahasiswa_id'),
            'mata_kuliah' => $this->nilaiPost('mata_kuliah'),
            'nilai_akhir' => $this->nilaiPost('nilai_akhir'),
        ];
        $mahasiswa = (new PdoMahasiswaRepository())->all();
        [$errors, $nilaiAkhir] = $this->validasi($oldInput, $mahasiswa);

        if ($errors !== []) {
            http_response_code(422);
            $judul = 'Edit Nilai';
            $nilaiId = $id;
            require dirname(__DIR__, 2) . '/views/nilai/tambah.php';
            return;
        }

        $repository->update(new Nilai(
            id: $id,
            mahasiswaId: (int) $oldInput['mahasiswa_id'],
            mataKuliah: $oldInput['mata_kuliah'],
            nilaiAkhir: $nilaiAkhir,
        ));

        header('Location: /nilai');
        exit;
    }

    public function hapus(): void
    {
        $id = $this->idPost();
        $repository = new PdoNilaiRepository();
        if ($repository->findById($id) === null) {
            $this->notFound();
        }

        $repository->delete($id);
        header('Location: /nilai');
        exit;
    }

    /**
     * @param array{mahasiswa_id:string,mata_kuliah:string,nilai_akhir:string} $input
     * @param \UMM\Pemweb\Model\Mahasiswa[] $mahasiswa
     * @return array{array<string,string>,float}
     */
    private function validasi(array $input, array $mahasiswa): array
    {
        $mahasiswaIds = [];
        foreach ($mahasiswa as $mhs) {
            if ($mhs->getId() !== null) {
                $mahasiswaIds[] = $mhs->getId();
            }
        }

        $errors = [];
        if ($input['mahasiswa_id'] === '' || !ctype_digit($input['mahasiswa_id'])
            || !in_array((int) $input['mahasiswa_id'], $mahasiswaIds, true)) {
            $errors['mahasiswa_id'] = 'Pilih mahasiswa yang terdaftar.';
        }

        if ($input['mata_kuliah'] === '') {
            $errors['mata_kuliah'] = 'Mata kuliah wajib diisi.';
        } else {
            $panjangMataKuliah = preg_match_all('/./us', $input['mata_kuliah']);
            if ($panjangMataKuliah === false) {
                $errors['mata_kuliah'] = 'Mata kuliah harus berupa teks UTF-8 yang valid.';
            } elseif ($panjangMataKuliah > 100) {
                $errors['mata_kuliah'] = 'Mata kuliah maksimal 100 karakter.';
            }
        }

        $nilaiAkhir = 0.0;
        if ($input['nilai_akhir'] === '' || !is_numeric($input['nilai_akhir'])) {
            $errors['nilai_akhir'] = 'Nilai akhir harus berupa angka.';
        } else {
            $nilaiAkhir = (float) $input['nilai_akhir'];
            if (!is_finite($nilaiAkhir) || $nilaiAkhir < 0 || $nilaiAkhir > 100) {
                $errors['nilai_akhir'] = 'Nilai akhir harus berada dalam rentang 0 sampai 100.';
            }
        }

        return [$errors, $nilaiAkhir];
    }

    private function nilaiPost(string $kunci): string
    {
        $nilai = $_POST[$kunci] ?? '';

        return is_string($nilai) ? trim($nilai) : '';
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
        $id = $_POST['id'] ?? '';
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            $this->notFound();
        }

        return (int) $id;
    }

    private function notFound(): never
    {
        http_response_code(404);
        echo '<h1>404 – Nilai tidak ditemukan</h1>';
        exit;
    }
}
