<?php require dirname(__DIR__) . '/layout/header.php'; ?>

        <?php if ($daftarMahasiswa === []): ?>
            <p class="empty-state">Belum ada data mahasiswa.</p>
        <?php else: ?>
            <section class="grid-mahasiswa" aria-label="Daftar mahasiswa" data-daftar-kartu>
                <?php foreach ($daftarMahasiswa as $mhs): ?>
                    <article class="kartu-mahasiswa kartu-flip" data-kartu-flip>
                        <div class="kartu-flip-dalam">
                            <div class="kartu-sisi kartu-sisi-depan" data-kartu-depan>
                                <div
                                    class="kartu-pemicu"
                                    data-kartu-pemicu
                                    aria-controls="mahasiswa-aksi-<?= (int) $mhs['id'] ?>"
                                    aria-label="Buka aksi untuk <?= htmlspecialchars($mhs['nama'], ENT_QUOTES, 'UTF-8') ?>"
                                >
                                    <div class="identitas-mahasiswa">
                                        <span class="avatar <?= htmlspecialchars($mhs['kelas_avatar'], ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true"><?= htmlspecialchars($mhs['inisial'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <div>
                                            <h2 class="nama-mahasiswa"><?= htmlspecialchars($mhs['nama'], ENT_QUOTES, 'UTF-8') ?></h2>
                                            <p class="nomor-induk"><?= htmlspecialchars($mhs['nim'], ENT_QUOTES, 'UTF-8') ?></p>
                                        </div>
                                    </div>
                                    <p class="badge-prodi"><?= htmlspecialchars($mhs['prodi'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <span class="petunjuk-flip">Ketuk untuk aksi</span>
                                </div>
                            </div>

                            <div class="kartu-sisi kartu-sisi-belakang" id="mahasiswa-aksi-<?= (int) $mhs['id'] ?>" data-kartu-belakang>
                                <p class="eyebrow">Aksi mahasiswa</p>
                                <p class="ringkasan-aksi-kartu"><?= htmlspecialchars($mhs['nama'], ENT_QUOTES, 'UTF-8') ?></p>
                                <div class="aksi-kartu">
                                    <a class="button button-secondary" href="/mahasiswa/edit?id=<?= (int) $mhs['id'] ?>">Edit</a>
                                    <form method="POST" action="/mahasiswa/hapus" onsubmit="return confirm('Yakin hapus data mahasiswa ini?')">
                                        <input type="hidden" name="id" value="<?= (int) $mhs['id'] ?>">
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
