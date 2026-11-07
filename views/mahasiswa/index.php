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

        <?php if (isset($flash['message'])): ?>
            <p class="flash <?= htmlspecialchars($flash['type'] ?? 'success', ENT_QUOTES, 'UTF-8') ?>">
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
                    <td class="center" data-label="No"><?= $no++ ?></td>
                    <td data-label="NIM"><?= htmlspecialchars($mhs['nim'],   ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Nama"><?= htmlspecialchars($mhs['nama'],  ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Program Studi"><?= htmlspecialchars($mhs['prodi'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="aksi-baris" data-label="Aksi">
                        <a href="/mahasiswa/edit?id=<?= (int) $mhs['id'] ?>">Edit</a>
                        <form method="POST" action="/mahasiswa/hapus" onsubmit="return confirm('Yakin hapus data mahasiswa ini?')">
                            <input type="hidden" name="id" value="<?= (int) $mhs['id'] ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
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
    .aksi-baris { white-space: nowrap; }
    .aksi-baris form { display: inline; }
    .aksi-baris button {
        margin-left: .5rem;
        border: 0;
        background: none;
        color: #b42318;
        font: inherit;
        cursor: pointer;
    }
    .flash {
        margin-bottom: 1rem;
        padding: .75rem 1rem;
        border-radius: 5px;
    }
    .flash.success { background-color: #e8f7ed; color: #176b35; }
    .flash.error { background-color: #fdecec; color: #a32121; }

    @media (max-width: 600px) {
        .tabel-mahasiswa,
        .tabel-mahasiswa tbody { display: block; width: 100%; }
        .tabel-mahasiswa thead { display: none; }
        .tabel-mahasiswa tbody tr {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .5rem;
            margin-bottom: .75rem;
            padding: .65rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .tabel-mahasiswa tbody tr:nth-child(even) { background-color: #f7f9fc; }
        .tabel-mahasiswa tbody tr:hover { background-color: #f7f9fc; }
        .tabel-mahasiswa th,
        .tabel-mahasiswa td {
            min-width: 0;
            padding: .35rem;
            border: 0;
            text-align: left;
            overflow-wrap: anywhere;
        }
        .tabel-mahasiswa td::before {
            display: block;
            margin-bottom: .15rem;
            color: #555;
            content: attr(data-label);
            font-size: .75rem;
            font-weight: 600;
        }
        .tabel-mahasiswa .aksi-baris {
            display: flex;
            grid-column: 1 / -1;
            align-items: center;
            gap: .5rem;
            white-space: normal;
        }
    }

</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
