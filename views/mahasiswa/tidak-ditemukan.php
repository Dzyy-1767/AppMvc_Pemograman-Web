<?php
/**
 * View: Data mahasiswa tidak ditemukan.
 *
 * Menerima variabel $judul dari MahasiswaController.
 */
?>
<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <h2 class="card-title"><?= htmlspecialchars($judul ?? 'Data tidak ditemukan', ENT_QUOTES, 'UTF-8') ?></h2>
        <p>Data tidak ditemukan.</p>
        <p><a href="/mahasiswa">Kembali ke daftar mahasiswa</a></p>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
