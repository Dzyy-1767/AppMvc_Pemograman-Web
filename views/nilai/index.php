<?php
/**
 * View daftar nilai yang disiapkan oleh NilaiController::index().
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <h2 class="card-title"><?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?></h2>

        <?php if (isset($flash['message'])): ?>
            <p class="pesan-sukses"><?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

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
                    <td data-label="No"><?= $index + 1 ?></td>
                    <td data-label="NIM"><?= htmlspecialchars($nilai['nim'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Nama"><?= htmlspecialchars($nilai['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Mata Kuliah"><?= htmlspecialchars($nilai['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Nilai"><?= htmlspecialchars(number_format($nilai['nilai_akhir'], 2, '.', ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Grade"><?= htmlspecialchars($nilai['grade'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="aksi" data-label="Aksi">
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
        <?php endif; ?>

<style>
    .tabel-nilai { width: 100%; border-collapse: collapse; margin-bottom: 1rem; font-size: .95rem; }
    .tabel-nilai thead tr { background-color: #1a56db; color: #fff; }
    .tabel-nilai th, .tabel-nilai td { padding: .65rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .tabel-nilai tbody tr:nth-child(even) { background-color: #f7f9fc; }
    .aksi { white-space: nowrap; }
    .aksi form { display: inline; }
    .aksi button { margin-left: .5rem; border: 0; background: none; color: #b42318; font: inherit; cursor: pointer; }
    .pesan-sukses {
        margin-bottom: 1rem;
        padding: .75rem 1rem;
        border-radius: 5px;
        background-color: #eaf0fb;
        color: #1a56db;
    }
    @media (max-width: 600px) {
        .tabel-nilai,
        .tabel-nilai tbody { display: block; width: 100%; }
        .tabel-nilai thead { display: none; }
        .tabel-nilai tbody tr {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .5rem;
            margin-bottom: .75rem;
            padding: .65rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .tabel-nilai tbody tr:nth-child(even) { background-color: #f7f9fc; }
        .tabel-nilai th,
        .tabel-nilai td {
            min-width: 0;
            padding: .35rem;
            border: 0;
            overflow-wrap: anywhere;
        }
        .tabel-nilai td::before {
            display: block;
            margin-bottom: .15rem;
            color: #555;
            content: attr(data-label);
            font-size: .75rem;
            font-weight: 600;
        }
        .tabel-nilai .aksi {
            display: flex;
            grid-column: 1 / -1;
            align-items: center;
            gap: .5rem;
            white-space: normal;
        }
    }
</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
