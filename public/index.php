<?php
/**
 * Front Controller – satu-satunya pintu masuk semua request HTTP.
 *
 * Alur request:
 *   Browser → public/index.php → Router → Controller → Repository → View
 */

declare(strict_types=1);

session_start();

// ── 1. Autoload PSR-4 (semua kelas namespace UMM\Pemweb\) ─────────────────
require dirname(__DIR__) . '/vendor/autoload.php';

use UMM\Pemweb\Core\Router;
use UMM\Pemweb\Controllers\BerandaController;
use UMM\Pemweb\Controllers\MahasiswaController;
use UMM\Pemweb\Controllers\NilaiController;

// ── 2. Instansiasi router ─────────────────────────────────────────────────
$router = new Router();

// ── 3. Daftarkan rute ─────────────────────────────────────────────────────
$router->get('/', [BerandaController::class, 'index']);
$router->get('/mahasiswa', [MahasiswaController::class, 'index']);
$router->get('/mahasiswa/tambah', [MahasiswaController::class, 'tambah']);
$router->get('/mahasiswa/edit', [MahasiswaController::class, 'edit']);
$router->post('/mahasiswa/simpan', [MahasiswaController::class, 'simpan']);
$router->post('/mahasiswa/update', [MahasiswaController::class, 'update']);
$router->post('/mahasiswa/hapus', [MahasiswaController::class, 'hapus']);
$router->get('/nilai', [NilaiController::class, 'index']);
$router->get('/nilai/tambah', [NilaiController::class, 'tambah']);
$router->get('/nilai/edit', [NilaiController::class, 'edit']);
$router->post('/nilai/simpan', [NilaiController::class, 'simpan']);
$router->post('/nilai/update', [NilaiController::class, 'update']);
$router->post('/nilai/hapus', [NilaiController::class, 'hapus']);

// ── 4. Ambil URI tanpa query string, normalkan trailing slash ─────────────
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

// ── 5. Dispatch ke controller yang sesuai ─────────────────────────────────
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
