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
    </style>
</head>
<body>

<nav>
    <a class="brand" href="/">SINILAI</a>
    <a href="/mahasiswa">Mahasiswa</a>
</nav>

<div class="container">
    <div class="card">

