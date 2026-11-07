<?php
/**
 * View: Form tambah mahasiswa.
 *
 * Menerima variabel dari MahasiswaController:
 *   $judul    – string judul halaman
 *   $oldInput – nilai input sebelumnya
 *   $errors   – pesan kesalahan per field
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <h2 class="card-title"><?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?></h2>

        <form method="POST" action="/mahasiswa/simpan">
            <div class="field">
                <label for="nim">NIM</label>
                <input
                    type="text"
                    id="nim"
                    name="nim"
                    maxlength="20"
                    inputmode="numeric"
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
                    value="<?= htmlspecialchars($oldInput['prodi'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="prodi-error"
                >
                <?php if (isset($errors['prodi'])): ?>
                    <p class="error" id="prodi-error"><?= htmlspecialchars($errors['prodi'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="aksi-form">
                <a href="/mahasiswa">Kembali</a>
                <button type="submit">Simpan Mahasiswa</button>
            </div>
        </form>

<style>
    .field { margin-bottom: 1rem; }

    .field label {
        display: block;
        margin-bottom: .35rem;
        font-weight: 600;
    }

    .field input {
        width: 100%;
        padding: .65rem .75rem;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font: inherit;
    }

    .field input:focus {
        border-color: #1a56db;
        outline: 2px solid #c7d9ff;
    }

    .error {
        margin-top: .25rem;
        color: #b42318;
        font-size: .9rem;
    }

    .aksi-form {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

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
</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
