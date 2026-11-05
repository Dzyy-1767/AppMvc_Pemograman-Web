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
INSERT INTO mahasiswa (nim, nama, prodi) VALUES
    ('2pemweb02310370311001', 'Andi Pratama',    'Teknik Informatika'),
    ('202310370311002', 'Siti Rahayu',     'Sistem Informasi'),
    ('202310370311003', 'Budi Santoso',    'Teknik Informatika');

-- ------------------------------------------------------------
-- Data contoh nilai
-- ------------------------------------------------------------
INSERT INTO nilai (mahasiswa_id, mata_kuliah, nilai_akhir) VALUES
    (1, 'Pemrograman Web',         88.50),
    (1, 'Basis Data',              91.00),
    (2, 'Pemrograman Web',         85.00),
    (2, 'Rekayasa Perangkat Lunak',79.50),
    (3, 'Pemrograman Web',         92.75);

