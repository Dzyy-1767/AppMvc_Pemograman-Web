<?php
/**
 * View: Form tambah/edit mahasiswa.
 *
 * Menerima variabel dari MahasiswaController:
 *   $judul    – string judul halaman
 *   $oldInput – nilai input sebelumnya
 *   $errors   – pesan kesalahan per field
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <section class="form-kartu">
            <form method="POST" action="<?= isset($mahasiswaId) ? '/mahasiswa/update' : '/mahasiswa/simpan' ?>">
                <?php if (isset($mahasiswaId)): ?>
                    <input type="hidden" name="id" value="<?= (int) $mahasiswaId ?>">
                <?php endif; ?>
                <div class="field <?= isset($errors['nim']) ? 'has-error' : '' ?>">
                    <label for="nim">NIM</label>
                    <input
                        type="text"
                        id="nim"
                        name="nim"
                        maxlength="20"
                        inputmode="numeric"
                        required
                        value="<?= htmlspecialchars($oldInput['nim'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        <?= isset($errors['nim']) ? 'aria-describedby="nim-error" aria-invalid="true"' : '' ?>
                    >
                    <?php if (isset($errors['nim'])): ?>
                        <p class="error" id="nim-error"><?= htmlspecialchars($errors['nim'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="field <?= isset($errors['nama']) ? 'has-error' : '' ?>">
                    <label for="nama">Nama</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        maxlength="100"
                        required
                        value="<?= htmlspecialchars($oldInput['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        <?= isset($errors['nama']) ? 'aria-describedby="nama-error" aria-invalid="true"' : '' ?>
                    >
                    <?php if (isset($errors['nama'])): ?>
                        <p class="error" id="nama-error"><?= htmlspecialchars($errors['nama'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="field <?= isset($errors['prodi']) ? 'has-error' : '' ?>">
                    <label for="prodi">Program Studi</label>
                    <input
                        type="text"
                        id="prodi"
                        name="prodi"
                        maxlength="100"
                        required
                        value="<?= htmlspecialchars($oldInput['prodi'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        <?= isset($errors['prodi']) ? 'aria-describedby="prodi-error" aria-invalid="true"' : '' ?>
                    >
                    <?php if (isset($errors['prodi'])): ?>
                        <p class="error" id="prodi-error"><?= htmlspecialchars($errors['prodi'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="aksi-form">
                    <a class="button button-secondary" href="/mahasiswa">Kembali</a>
                    <button class="button button-primary" type="submit"><?= isset($mahasiswaId) ? 'Simpan Perubahan' : 'Simpan Mahasiswa' ?></button>
                </div>
            </form>
        </section>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
