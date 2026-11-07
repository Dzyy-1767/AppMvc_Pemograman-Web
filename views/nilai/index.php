<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <?php if ($daftarNilai === []): ?>
            <p class="empty-state">Belum ada data nilai.</p>
        <?php else: ?>
            <section class="grid-nilai" aria-label="Daftar nilai mahasiswa" data-daftar-kartu>
                <?php foreach ($daftarNilai as $nilai): ?>
                    <article class="kartu-nilai kartu-flip" data-kartu-flip>
                        <div class="kartu-flip-dalam">
                            <div class="kartu-sisi kartu-sisi-depan" data-kartu-depan>
                                <div
                                    class="kartu-pemicu"
                                    data-kartu-pemicu
                                    aria-controls="nilai-aksi-<?= (int) $nilai['id'] ?>"
                                    aria-label="Buka aksi untuk <?= htmlspecialchars($nilai['nama'] . ' - ' . $nilai['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <div class="nilai-ringkasan">
                                        <div>
                                            <p class="eyebrow"><?= htmlspecialchars($nilai['nama'], ENT_QUOTES, 'UTF-8') ?></p>
                                            <h2 class="mata-kuliah"><?= htmlspecialchars($nilai['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?></h2>
                                        </div>
                                        <div class="nilai-akhir">
                                            <span class="field-label">Nilai Akhir</span>
                                            <strong><?= htmlspecialchars(number_format($nilai['nilai_akhir'], 2, '.', ''), ENT_QUOTES, 'UTF-8') ?></strong>
                                            <span class="badge <?= htmlspecialchars($nilai['kelas'], ENT_QUOTES, 'UTF-8') ?>" aria-label="Grade <?= htmlspecialchars($nilai['grade'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($nilai['grade'], ENT_QUOTES, 'UTF-8') ?></span>
                                        </div>
                                    </div>
                                    <span class="petunjuk-flip">Ketuk untuk aksi</span>
                                </div>
                            </div>

                            <div class="kartu-sisi kartu-sisi-belakang" id="nilai-aksi-<?= (int) $nilai['id'] ?>" data-kartu-belakang>
                                <p class="eyebrow">Aksi nilai</p>
                                <p class="ringkasan-aksi-kartu">
                                    <?= htmlspecialchars($nilai['nama'] . ' — ' . $nilai['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <p class="nim-nilai">NIM <?= htmlspecialchars($nilai['nim'], ENT_QUOTES, 'UTF-8') ?></p>
                                <div class="aksi-kartu">
                                    <a class="button button-secondary" href="/nilai/edit?id=<?= (int) $nilai['id'] ?>">Edit</a>
                                    <form method="POST" action="/nilai/hapus" onsubmit="return confirm('Hapus nilai ini?');">
                                        <input type="hidden" name="id" value="<?= (int) $nilai['id'] ?>">
                                        <button class="button button-danger" type="submit">Hapus</button>
                                    </form>
                                    <button class="button button-secondary" type="button" data-kartu-kembali hidden>Kembali</button>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
