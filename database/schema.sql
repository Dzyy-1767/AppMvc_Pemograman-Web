-- ============================================================
-- Sistem Informasi Nilai Mahasiswa
-- Database: pemweb
-- ============================================================

CREATE DATABASE IF NOT EXISTS pemweb
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE pemweb;

-- ------------------------------------------------------------
-- Tabel mahasiswa
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS mahasiswa (
    id    INT          NOT NULL AUTO_INCREMENT,
    nim   VARCHAR(20)  NOT NULL,
    nama  VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_nim (nim)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabel nilai
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS nilai (
    id            INT           NOT NULL AUTO_INCREMENT,
    mahasiswa_id  INT           NOT NULL,
    mata_kuliah   VARCHAR(100)  NOT NULL,
    nilai_akhir   DECIMAL(5,2)  NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_nilai_mahasiswa
        FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa (id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Data contoh mahasiswa (3 baris)
-- ------------------------------------------------------------
INSERT IGNORE INTO mahasiswa (nim, nama, prodi) VALUES
    ('202310370311001', 'Andi Pratama',    'Teknik Informatika'),
    ('202310370311002', 'Siti Rahayu',     'Sistem Informasi'),
    ('202310370311003', 'Budi Santoso',    'Teknik Informatika');

-- ------------------------------------------------------------
-- Data contoh nilai
-- ------------------------------------------------------------
INSERT IGNORE INTO nilai (mahasiswa_id, mata_kuliah, nilai_akhir)
SELECT id, 'Pemrograman Web', 88.50
FROM mahasiswa
WHERE nim = '202310370311001'
  AND NOT EXISTS (
      SELECT 1 FROM nilai
      WHERE nilai.mahasiswa_id = mahasiswa.id
        AND nilai.mata_kuliah = 'Pemrograman Web'
  );

INSERT IGNORE INTO nilai (mahasiswa_id, mata_kuliah, nilai_akhir)
SELECT id, 'Basis Data', 91.00
FROM mahasiswa
WHERE nim = '202310370311001'
  AND NOT EXISTS (
      SELECT 1 FROM nilai
      WHERE nilai.mahasiswa_id = mahasiswa.id
        AND nilai.mata_kuliah = 'Basis Data'
  );

INSERT IGNORE INTO nilai (mahasiswa_id, mata_kuliah, nilai_akhir)
SELECT id, 'Pemrograman Web', 85.00
FROM mahasiswa
WHERE nim = '202310370311002'
  AND NOT EXISTS (
      SELECT 1 FROM nilai
      WHERE nilai.mahasiswa_id = mahasiswa.id
        AND nilai.mata_kuliah = 'Pemrograman Web'
  );

INSERT IGNORE INTO nilai (mahasiswa_id, mata_kuliah, nilai_akhir)
SELECT id, 'Rekayasa Perangkat Lunak', 79.50
FROM mahasiswa
WHERE nim = '202310370311002'
  AND NOT EXISTS (
      SELECT 1 FROM nilai
      WHERE nilai.mahasiswa_id = mahasiswa.id
        AND nilai.mata_kuliah = 'Rekayasa Perangkat Lunak'
  );

INSERT IGNORE INTO nilai (mahasiswa_id, mata_kuliah, nilai_akhir)
SELECT id, 'Pemrograman Web', 92.75
FROM mahasiswa
WHERE nim = '202310370311003'
  AND NOT EXISTS (
      SELECT 1 FROM nilai
      WHERE nilai.mahasiswa_id = mahasiswa.id
        AND nilai.mata_kuliah = 'Pemrograman Web'
  );

