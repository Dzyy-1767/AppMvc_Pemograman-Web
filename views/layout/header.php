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
    <header class="sidebar">
        <a class="brand" href="/">SINILAI</a>
        <button class="menu-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="main-navigation">
            <span class="menu-toggle-icon" aria-hidden="true"></span>
        </button>
        <nav class="nav-panel" id="main-navigation" aria-label="Navigasi utama">
            <ul class="nav-list">
                <li>
                    <a class="nav-link <?= ($menuAktif ?? '') === 'beranda' ? 'active' : '' ?>"
                       href="/" <?= ($menuAktif ?? '') === 'beranda' ? 'aria-current="page"' : '' ?>>Beranda</a>
                </li>
                <li>
                    <a class="nav-link <?= in_array($menuAktif ?? '', ['mahasiswa', 'mahasiswa-form'], true) ? 'active' : '' ?>"
                       href="/mahasiswa" <?= in_array($menuAktif ?? '', ['mahasiswa', 'mahasiswa-form'], true) ? 'aria-current="page"' : '' ?>>Mahasiswa</a>
                </li>
                <li>
                    <a class="nav-link <?= in_array($menuAktif ?? '', ['nilai', 'nilai-form'], true) ? 'active' : '' ?>"
                       href="/nilai" <?= in_array($menuAktif ?? '', ['nilai', 'nilai-form'], true) ? 'aria-current="page"' : '' ?>>Nilai</a>
                </li>
            </ul>
        </nav>
    </header>
    <div class="nav-overlay" data-menu-overlay aria-hidden="true"></div>

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
