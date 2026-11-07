<?php
/**
 * View form tambah/edit nilai yang disiapkan oleh NilaiController.
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <?php if ($mahasiswa === []): ?>
            <p class="empty-state">Tambahkan data mahasiswa terlebih dahulu sebelum memasukkan nilai.</p>
        <?php else: ?>
            <section class="form-kartu">
                <form method="POST" action="<?= isset($nilaiId) ? '/nilai/update' : '/nilai/simpan' ?>">
                    <?php if (isset($nilaiId)): ?>
                        <input type="hidden" name="id" value="<?= (int) $nilaiId ?>">
                    <?php endif; ?>
                    <div class="field <?= isset($errors['mahasiswa_id']) ? 'has-error' : '' ?>">
                        <label for="mahasiswa_id">Mahasiswa</label>
                        <select id="mahasiswa_id" name="mahasiswa_id"
                            <?= isset($errors['mahasiswa_id']) ? 'aria-describedby="mahasiswa-error" aria-invalid="true"' : '' ?>>
                            <option value="">Pilih mahasiswa</option>
                            <?php foreach ($mahasiswa as $mhs): ?>
                                <option
                                    value="<?= (int) $mhs->getId() ?>"
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

                    <div class="field <?= isset($errors['mata_kuliah']) ? 'has-error' : '' ?>">
                        <label for="mata_kuliah">Mata Kuliah</label>
                        <input
                            type="text"
                            id="mata_kuliah"
                            name="mata_kuliah"
                            maxlength="100"
                            value="<?= htmlspecialchars($oldInput['mata_kuliah'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            <?= isset($errors['mata_kuliah']) ? 'aria-describedby="mata-kuliah-error" aria-invalid="true"' : '' ?>
                        >
                        <?php if (isset($errors['mata_kuliah'])): ?>
                            <p class="error" id="mata-kuliah-error"><?= htmlspecialchars($errors['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="field <?= isset($errors['nilai_akhir']) ? 'has-error' : '' ?>">
                        <label for="nilai_akhir">Nilai Akhir (0–100)</label>
                        <input
                            type="number"
                            id="nilai_akhir"
                            name="nilai_akhir"
                            min="0"
                            max="100"
                            step="0.01"
                            value="<?= htmlspecialchars($oldInput['nilai_akhir'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            <?= isset($errors['nilai_akhir']) ? 'aria-describedby="nilai-error" aria-invalid="true"' : '' ?>
                        >
                        <?php if (isset($errors['nilai_akhir'])): ?>
                            <p class="error" id="nilai-error"><?= htmlspecialchars($errors['nilai_akhir'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="aksi-form">
                        <a class="button button-secondary" href="/nilai">Kembali</a>
                        <button class="button button-primary" type="submit"><?= isset($nilaiId) ? 'Perbarui Nilai' : 'Simpan Nilai' ?></button>
                    </div>
                </form>
            </section>
        <?php endif; ?>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
