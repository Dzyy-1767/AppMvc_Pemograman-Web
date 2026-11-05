<?php
/**
 * Front Controller – satu-satunya pintu masuk semua request HTTP.
 *
 * Alur request:
 *   Browser → public/index.php → Router → Controller → Repository → View
 */

declare(strict_types=1);

// ── 1. Autoload PSR-4 (semua kelas namespace UMM\Pemweb\) ─────────────────
require dirname(__DIR__) . '/vendor/autoload.php';

use UMM\Pemweb\Core\Router;
use UMM\Pemweb\Controllers\MahasiswaController;

// ── 2. Instansiasi router ─────────────────────────────────────────────────
$router = new Router();

// ── 3. Daftarkan rute ─────────────────────────────────────────────────────
$router->get('/mahasiswa', [MahasiswaController::class, 'index']);

// ── 4. Ambil URI tanpa query string, normalkan trailing slash ─────────────
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

// ── 5. Dispatch ke controller yang sesuai ─────────────────────────────────
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

