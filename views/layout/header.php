<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul ?? 'Sistem Informasi Nilai Mahasiswa', ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        /* ── Reset & Base ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            background-color: #f0f2f5;
            color: #333;
        }

        /* ── Navbar ───────────────────────────────────────────────────── */
        nav {
            background-color: #1a56db;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            height: 56px;
            box-shadow: 0 2px 6px rgba(0,0,0,.2);
        }

        nav .brand {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: .5px;
        }

        nav a {
            color: #c7d9ff;
            text-decoration: none;
            font-size: .9rem;
            transition: color .2s;
        }

        nav a:hover { color: #fff; }
        nav a.active {
            color: #fff;
            font-weight: 700;
        }

        /* ── Container ────────────────────────────────────────────────── */
        .container {
            max-width: 960px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* ── Card ─────────────────────────────────────────────────────── */
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
            padding: 1.75rem 2rem;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a56db;
            margin-bottom: 1.25rem;
            padding-bottom: .6rem;
            border-bottom: 2px solid #e8ecf5;
        }

        .aksi-tambah { margin-bottom: 1rem; text-align: right; }
        .tombol-tambah {
            display: inline-block;
            padding: .55rem 1rem;
            border-radius: 5px;
            background-color: #1a56db;
            color: #fff;
            text-decoration: none;
        }
        .tombol-tambah:hover { background-color: #1648b8; }

        .field { margin-bottom: 1rem; }
        .field label {
            display: block;
            margin-bottom: .35rem;
            font-weight: 600;
        }
        .field input, .field select {
            width: 100%;
            padding: .65rem .75rem;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font: inherit;
            background: #fff;
        }
        .error { margin-top: .25rem; color: #b42318; font-size: .9rem; }
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
        .empty-msg { color: #888; font-style: italic; }
        @media (max-width: 480px) {
            nav {
                height: auto;
                min-height: 56px;
                flex-wrap: wrap;
                justify-content: center;
                gap: .75rem;
                padding: .5rem .75rem;
            }
            nav .brand { font-size: 1rem; }
            nav a { font-size: .82rem; }
            .container { margin: 1rem auto; padding: 0 .75rem; }
            .card { padding: 1.25rem 1rem; }
            .aksi-form { flex-wrap: wrap; }
        }
    </style>
</head>
<body>

<nav>
    <a class="brand" href="/">SINILAI</a>
    <a class="<?= ($menuAktif ?? '') === 'beranda' ? 'active' : '' ?>" href="/" <?= ($menuAktif ?? '') === 'beranda' ? 'aria-current="page"' : '' ?>>Beranda</a>
    <?php if (($menuAktif ?? '') !== 'mahasiswa-form'): ?>
        <a class="<?= ($menuAktif ?? '') === 'mahasiswa' ? 'active' : '' ?>" href="/mahasiswa" <?= ($menuAktif ?? '') === 'mahasiswa' ? 'aria-current="page"' : '' ?>>Mahasiswa</a>
    <?php endif; ?>
    <?php if (($menuAktif ?? '') !== 'nilai-form'): ?>
        <a class="<?= ($menuAktif ?? '') === 'nilai' ? 'active' : '' ?>" href="/nilai" <?= ($menuAktif ?? '') === 'nilai' ? 'aria-current="page"' : '' ?>>Nilai</a>
    <?php endif; ?>
</nav>

<div class="container">
    <div class="card">
