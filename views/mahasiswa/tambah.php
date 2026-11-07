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

        <h2 class="card-title"><?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?></h2>

        <form method="POST" action="<?= isset($mahasiswaId) ? '/mahasiswa/update' : '/mahasiswa/simpan' ?>">
            <?php if (isset($mahasiswaId)): ?>
                <input type="hidden" name="id" value="<?= (int) $mahasiswaId ?>">
            <?php endif; ?>
            <div class="field">
                <label for="nim">NIM</label>
                <input
                    type="text"
                    id="nim"
                    name="nim"
                    maxlength="20"
                    inputmode="numeric"
                    required
                    value="<?= htmlspecialchars($oldInput['nim'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="nim-error"
                >
                <?php if (isset($errors['nim'])): ?>
                    <p class="error" id="nim-error"><?= htmlspecialchars($errors['nim'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="nama">Nama</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    maxlength="100"
                    required
                    value="<?= htmlspecialchars($oldInput['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="nama-error"
                >
                <?php if (isset($errors['nama'])): ?>
                    <p class="error" id="nama-error"><?= htmlspecialchars($errors['nama'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="field">
                <label for="prodi">Program Studi</label>
                <input
                    type="text"
                    id="prodi"
                    name="prodi"
                    maxlength="100"
                    required
                    value="<?= htmlspecialchars($oldInput['prodi'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="prodi-error"
                >
                <?php if (isset($errors['prodi'])): ?>
                    <p class="error" id="prodi-error"><?= htmlspecialchars($errors['prodi'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="aksi-form">
                <a href="/mahasiswa">Kembali</a>
                <button type="submit"><?= isset($mahasiswaId) ? 'Simpan Perubahan' : 'Simpan Mahasiswa' ?></button>
            </div>
        </form>

<style>
    .field input:focus {
        border-color: #1a56db;
        outline: 2px solid #c7d9ff;
    }
</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
