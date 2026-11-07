<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <?php if ($daftarNilai === []): ?>
            <p class="empty-state">Belum ada data nilai.</p>
        <?php else: ?>
            <section class="grid-nilai" aria-label="Daftar nilai mahasiswa">
                <?php foreach ($daftarNilai as $nilai): ?>
                    <article class="kartu-nilai">
                        <div class="nilai-ringkasan">
                            <div>
                                <p class="eyebrow"><?= htmlspecialchars($nilai['nama'], ENT_QUOTES, 'UTF-8') ?></p>
                                <h2 class="mata-kuliah"><?= htmlspecialchars($nilai['mata_kuliah'], ENT_QUOTES, 'UTF-8') ?></h2>
                                <p class="nim-nilai">NIM <?= htmlspecialchars($nilai['nim'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="nilai-akhir">
                                <span class="field-label">Nilai Akhir</span>
                                <strong><?= htmlspecialchars(number_format($nilai['nilai_akhir'], 2, '.', ''), ENT_QUOTES, 'UTF-8') ?></strong>
                                <span class="badge <?= htmlspecialchars($nilai['kelas'], ENT_QUOTES, 'UTF-8') ?>" aria-label="Grade <?= htmlspecialchars($nilai['grade'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($nilai['grade'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        </div>
                        <div class="aksi-kartu">
                            <a class="button button-secondary" href="/nilai/edit?id=<?= (int) $nilai['id'] ?>">Edit</a>
                            <form method="POST" action="/nilai/hapus" onsubmit="return confirm('Hapus nilai ini?');">
                                <input type="hidden" name="id" value="<?= (int) $nilai['id'] ?>">
                                <button class="button button-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
