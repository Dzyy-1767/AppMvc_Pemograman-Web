<?php
/**
 * View form tambah/edit nilai yang disiapkan oleh NilaiController.
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <h2 class="card-title"><?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?></h2>

        <?php if ($mahasiswa === []): ?>
            <p class="empty-msg">Tambahkan data mahasiswa terlebih dahulu sebelum memasukkan nilai.</p>
        <?php else: ?>
        <form method="POST" action="<?= isset($nilaiId) ? '/nilai/update' : '/nilai/simpan' ?>">
            <?php if (isset($nilaiId)): ?>
                <input type="hidden" name="id" value="<?= (int) $nilaiId ?>">
            <?php endif; ?>
            <div class="field">
                <label for="mahasiswa_id">Mahasiswa</label>
                <select id="mahasiswa_id" name="mahasiswa_id" aria-describedby="mahasiswa-error">
                    <option value="">Pilih mahasiswa</option>
                    <?php foreach ($mahasiswa as $mhs): ?>
                    <option
                        value="<?= $mhs->getId() ?>"
                        <?= (string) $mhs->getId() === ($oldInput['mahasiswa_id'] ?? '') ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($mhs->getNim() . ' - ' . $mhs->getNama(), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['mahasiswa_id'])): ?>
                    <p class="error" id="mahasiswa-error"><?= htmlspecialchars($errors['mahasiswa_id'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="mata_kuliah">Mata Kuliah</label>
                <input
                    type="text"
                    id="mata_kuliah"
                    name="mata_kuliah"
                    maxlength="100"
                    value="<?= htmlspecialchars($oldInput['mata_kuliah'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="mata-kuliah-error"
                >
                <?php if (isset($errors['mata_kuliah'])): ?>
                    <p class="error" id="mata-kuliah-error"><?= htmlspecialchars($errors['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="nilai_akhir">Nilai Akhir (0–100)</label>
                <input
                    type="number"
                    id="nilai_akhir"
                    name="nilai_akhir"
                    min="0"
                    max="100"
                    step="0.01"
                    value="<?= htmlspecialchars($oldInput['nilai_akhir'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="nilai-error"
                >
                <?php if (isset($errors['nilai_akhir'])): ?>
                    <p class="error" id="nilai-error"><?= htmlspecialchars($errors['nilai_akhir'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="aksi-form">
                <a href="/nilai">Kembali</a>
                <button type="submit"><?= isset($nilaiId) ? 'Perbarui Nilai' : 'Simpan Nilai' ?></button>
            </div>
        </form>
        <?php endif; ?>

<style>
    .field { margin-bottom: 1rem; }
    .field label { display: block; margin-bottom: .35rem; font-weight: 600; }
    .field input, .field select {
        width: 100%;
        padding: .65rem .75rem;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font: inherit;
        background: #fff;
    }
    .error { margin-top: .25rem; color: #b42318; font-size: .9rem; }
    .aksi-form { display: flex; justify-content: flex-end; align-items: center; gap: 1rem; margin-top: 1.5rem; }
    .aksi-form a { color: #1a56db; }
    .aksi-form button {
        padding: .6rem 1rem;
        border: 0;
        border-radius: 5px;
        background-color: #1a56db;
        color: #fff;
        font: inherit;
        cursor: pointer;
    }
    .empty-msg { color: #888; font-style: italic; }
</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
