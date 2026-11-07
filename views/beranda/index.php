<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <section class="hero">
            <h1><?= htmlspecialchars('Sistem Informasi Nilai Mahasiswa', ENT_QUOTES, 'UTF-8') ?></h1>
            <p>Aplikasi untuk mengelola data mahasiswa dan nilai akhir mata kuliah. Grade dihitung otomatis berdasarkan nilai yang tersimpan.</p>
        </section>

        <section class="ringkasan" aria-label="Ringkasan data tersimpan">
            <article class="kartu-ringkasan">
                <span class="label-ringkasan">Total Mahasiswa</span>
                <strong><?= htmlspecialchars((string) $totalMahasiswa, ENT_QUOTES, 'UTF-8') ?></strong>
                <span>data mahasiswa tersimpan</span>
            </article>
            <article class="kartu-ringkasan">
                <span class="label-ringkasan">Total Nilai</span>
                <strong><?= htmlspecialchars((string) $totalNilai, ENT_QUOTES, 'UTF-8') ?></strong>
                <span>data nilai tersimpan</span>
            </article>
        </section>

        <section class="bagian-grade">
            <h2 class="card-title">Rentang Grade A-E</h2>
            <div class="tabel-responsif">
                <table class="tabel-grade">
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Rentang Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rentangGrade as $rentang): ?>
                            <?php
                            $batasAtas = $rentang['batas_atas_inklusif']
                                ? '≤ ' . $rentang['batas_atas']
                                : '< ' . $rentang['batas_atas'];
                            $labelRentang = '≥ ' . $rentang['batas_bawah'] . ' dan ' . $batasAtas;
                            ?>
                            <tr>
                                <td><span class="lencana-grade grade-<?= htmlspecialchars(strtolower($rentang['grade']), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($rentang['grade'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td><?= htmlspecialchars($labelRentang, ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rentang['keterangan'], ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

<style>
    .hero { margin-bottom: 1.5rem; }
    .hero h1 {
        margin-bottom: .5rem;
        color: #1a56db;
        font-size: clamp(1.5rem, 4vw, 2rem);
        line-height: 1.25;
    }
    .hero p { max-width: 680px; color: #555; }
    .ringkasan {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .kartu-ringkasan {
        display: flex;
        min-width: 0;
        flex-direction: column;
        padding: 1rem 1.25rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #555;
    }
    .label-ringkasan { color: #333; font-weight: 600; }
    .kartu-ringkasan strong { color: #1a56db; font-size: 2rem; line-height: 1.25; }
    .bagian-grade .card-title { margin-bottom: .75rem; }
    .tabel-responsif { width: 100%; overflow-x: auto; }
    .tabel-grade { width: 100%; border-collapse: collapse; }
    .tabel-grade th, .tabel-grade td {
        padding: .6rem .75rem;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .tabel-grade th { color: #333; }
    .lencana-grade {
        display: inline-block;
        min-width: 2rem;
        padding: .1rem .45rem;
        border-radius: 4px;
        background-color: #e8ecf5;
        color: #1a56db;
        font-weight: 700;
        text-align: center;
    }
    @media (max-width: 480px) {
        nav { gap: 1rem; padding: 0 .75rem; }
        nav .brand { font-size: 1rem; }
        nav a { font-size: .82rem; }
        .card { padding: 1.25rem 1rem; }
        .tabel-grade th, .tabel-grade td { padding: .5rem .4rem; }
    }
</style>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
