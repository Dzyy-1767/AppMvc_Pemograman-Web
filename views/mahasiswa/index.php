<?php
/**
 * View: Daftar Mahasiswa
 *
 * Menerima variabel dari MahasiswaController::index():
 *   $judul           – string judul halaman
 *   $daftarMahasiswa – array asosiatif [id, nim, nama, prodi]
 *
 * Aturan: view TIDAK mengandung SQL maupun logika bisnis.
 *         Semua output di-escape dengan htmlspecialchars().
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <h2 class="card-title">
            <?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?>
        </h2>

        <p class="aksi-tambah">
            <a class="tombol-tambah" href="/mahasiswa/tambah">Tambah Mahasiswa</a>
        </p>

        <?php if (empty($daftarMahasiswa)): ?>
            <p class="empty-msg">Belum ada data mahasiswa.</p>
        <?php else: ?>

        <table class="tabel-mahasiswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($daftarMahasiswa as $mhs): ?>
                <tr>
                    <td class="center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($mhs['nim'],   ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($mhs['nama'],  ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($mhs['prodi'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

<style>
    /* ── Tabel ────────────────────────────────────────────────────────── */
    .tabel-mahasiswa {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        font-size: .95rem;
    }

    .tabel-mahasiswa thead tr {
        background-color: #1a56db;
        color: #fff;
    }

    .tabel-mahasiswa th,
    .tabel-mahasiswa td {
        padding: .65rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .tabel-mahasiswa tbody tr:nth-child(even) {
        background-color: #f7f9fc;
    }

    .tabel-mahasiswa tbody tr:hover {
        background-color: #eaf0fb;
        transition: background-color .15s;
    }

    .tabel-mahasiswa td.center { text-align: center; }

</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
