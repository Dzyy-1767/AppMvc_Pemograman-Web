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

        <?php if ($flash !== null): ?>
            <p class="flash <?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

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
                    <th>Aksi</th>
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
                    <td class="aksi-baris">
                        <a href="/mahasiswa/edit?id=<?= htmlspecialchars((string) $mhs['id'], ENT_QUOTES, 'UTF-8') ?>">Edit</a>
                        <form method="POST" action="/mahasiswa/hapus" onsubmit="return confirm('Yakin hapus data ini?')">
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $mhs['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="jumlah-data">
            Total: <strong><?= count($daftarMahasiswa) ?></strong> mahasiswa terdaftar.
        </p>

        <?php endif; ?>

<style>
    .flash {
        margin-bottom: 1rem;
        padding: .75rem 1rem;
        border-radius: 5px;
    }

    .flash.success {
        background-color: #e8f7ed;
        color: #176b35;
    }

    .flash.error {
        background-color: #fdecec;
        color: #a32121;
    }

    .aksi-tambah {
        margin-bottom: 1rem;
        text-align: right;
    }

    .tombol-tambah {
        display: inline-block;
        padding: .55rem 1rem;
        border-radius: 5px;
        background-color: #1a56db;
        color: #fff;
        text-decoration: none;
    }

    .tombol-tambah:hover { background-color: #1648b8; }

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

    .aksi-baris {
        white-space: nowrap;
    }

    .aksi-baris a {
        margin-right: .5rem;
        color: #1a56db;
    }

    .aksi-baris form { display: inline; }

    .aksi-baris button {
        padding: .3rem .55rem;
        border: 0;
        border-radius: 4px;
        background-color: #b42318;
        color: #fff;
        cursor: pointer;
    }

    /* ── Pesan tambahan ───────────────────────────────────────────────── */
    .jumlah-data {
        color: #555;
        font-size: .9rem;
        margin-top: .5rem;
    }

    .empty-msg {
        color: #888;
        font-style: italic;
    }
</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
