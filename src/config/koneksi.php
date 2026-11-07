<?php
/**
 * Konfigurasi koneksi database menggunakan PDO.
 *
 * File ini mengembalikan objek PDO yang siap dipakai.
 * Gunakan:  $pdo = require __DIR__ . '/../config/koneksi.php';
 */

declare(strict_types=1);

// ── Kredensial database ──────────────────────────────────────────────────
$host     = 'localhost';
$dbname   = 'pemweb';
$username = 'root';
$password = '';          // kosong untuk instalasi XAMPP default
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

$opsi = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // lempar exception saat error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // hasil fetch sebagai array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                   // gunakan prepared statement native
];

try {
    $pdo = new PDO($dsn, $username, $password, $opsi);
} catch (\PDOException $e) {
    error_log('Koneksi database gagal: ' . $e->getMessage());
    http_response_code(500);
    exit('Layanan database sedang tidak tersedia. Coba lagi nanti.');
}

return $pdo;
