<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul ?? 'Sistem Informasi Nilai Mahasiswa', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" aria-label="Navigasi utama">
        <a class="brand" href="/">SINILAI</a>
        <nav class="nav-list" aria-label="Menu utama">
            <a class="nav-link <?= ($menuAktif ?? '') === 'beranda' ? 'active' : '' ?>"
               href="/" <?= ($menuAktif ?? '') === 'beranda' ? 'aria-current="page"' : '' ?>>Beranda</a>
            <a class="nav-link <?= in_array($menuAktif ?? '', ['mahasiswa', 'mahasiswa-form'], true) ? 'active' : '' ?>"
               href="/mahasiswa" <?= in_array($menuAktif ?? '', ['mahasiswa', 'mahasiswa-form'], true) ? 'aria-current="page"' : '' ?>>Mahasiswa</a>
            <a class="nav-link <?= in_array($menuAktif ?? '', ['nilai', 'nilai-form'], true) ? 'active' : '' ?>"
               href="/nilai" <?= in_array($menuAktif ?? '', ['nilai', 'nilai-form'], true) ? 'aria-current="page"' : '' ?>>Nilai</a>
        </nav>
    </aside>

    <div class="main-content">
        <header class="page-header">
            <div>
                <h1><?= htmlspecialchars($judul ?? 'Sistem Informasi Nilai Mahasiswa', ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (isset($deskripsiHalaman)): ?>
                    <p><?= htmlspecialchars($deskripsiHalaman, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>
            <?php if (isset($aksiUtama['label'], $aksiUtama['href'])): ?>
                <a class="aksi-utama" href="<?= htmlspecialchars($aksiUtama['href'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($aksiUtama['label'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endif; ?>
        </header>

        <?php if (isset($flash['message'])): ?>
            <div
                class="toast toast--<?= htmlspecialchars($flash['type'] ?? 'success', ENT_QUOTES, 'UTF-8') ?>"
                role="<?= ($flash['type'] ?? '') === 'error' ? 'alert' : 'status' ?>"
                data-toast
            ><?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <main class="konten" id="main-content">
