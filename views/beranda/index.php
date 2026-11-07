<?php require dirname(__DIR__) . '/layout/header.php'; ?>

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

        <section class="bagian-grade" aria-labelledby="judul-rentang-grade">
            <h2 class="section-title" id="judul-rentang-grade">Rentang Grade A-E</h2>
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
                                <td><span class="badge <?= htmlspecialchars($rentang['kelas'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($rentang['grade'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td><?= htmlspecialchars($labelRentang, ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rentang['keterangan'], ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
