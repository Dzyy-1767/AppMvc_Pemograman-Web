<?php
/**
 * View daftar nilai yang disiapkan oleh NilaiController::index().
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <h2 class="card-title"><?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?></h2>

        <p class="aksi-tambah">
            <a class="tombol-tambah" href="/nilai/tambah">Tambah Nilai</a>
        </p>

        <?php if ($daftarNilai === []): ?>
            <p class="empty-msg">Belum ada data nilai.</p>
        <?php else: ?>
        <table class="tabel-nilai">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Mata Kuliah</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarNilai as $index => $nilai): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($nilai['nim'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($nilai['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($nilai['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars(number_format($nilai['nilai_akhir'], 2, '.', ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($nilai['grade'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="aksi">
                        <a href="/nilai/edit?id=<?= (int) $nilai['id'] ?>">Edit</a>
                        <form method="POST" action="/nilai/hapus" onsubmit="return confirm('Hapus nilai ini?');">
                            <input type="hidden" name="id" value="<?= (int) $nilai['id'] ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="jumlah-data">Total: <strong><?= count($daftarNilai) ?></strong> nilai.</p>
        <?php endif; ?>

<style>
    .aksi-tambah { margin-bottom: 1rem; text-align: right; }
    .tombol-tambah {
        display: inline-block;
        padding: .55rem 1rem;
        border-radius: 5px;
        background-color: #1a56db;
        color: #fff;
        text-decoration: none;
    }
    .tabel-nilai { width: 100%; border-collapse: collapse; margin-bottom: 1rem; font-size: .95rem; }
    .tabel-nilai thead tr { background-color: #1a56db; color: #fff; }
    .tabel-nilai th, .tabel-nilai td { padding: .65rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .tabel-nilai tbody tr:nth-child(even) { background-color: #f7f9fc; }
    .aksi { white-space: nowrap; }
    .aksi form { display: inline; }
    .aksi button { margin-left: .5rem; border: 0; background: none; color: #b42318; font: inherit; cursor: pointer; }
    .jumlah-data { color: #555; font-size: .9rem; margin-top: .5rem; }
    .empty-msg { color: #888; font-style: italic; }
</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
