<?php
/**
 * Router – mengelola pendaftaran dan pencocokan rute HTTP.
 *
 * Penggunaan:
 *   $router->get('/mahasiswa', [MahasiswaController::class, 'index']);
 *   $router->dispatch('GET', '/mahasiswa');
 */

declare(strict_types=1);

namespace UMM\Pemweb\Core;

class Router
{
    /**
     * Tabel rute: ['METHOD' => ['/path' => [Kelas::class, 'method']]]
     *
     * @var array<string, array<string, array{0: string, 1: string}>>
     */
    private array $rute = [];

    // ── Pendaftaran rute ──────────────────────────────────────────────────

    /**
     * Daftarkan rute GET.
     *
     * @param string                  $path    URI path, contoh '/mahasiswa'
     * @param array{0:string,1:string} $handler [KelasController::class, 'namaMethod']
     */
    public function get(string $path, array $handler): void
    {
        $this->rute['GET'][$path] = $handler;
    }

    /**
     * Daftarkan rute POST.
     *
     * @param string                  $path
     * @param array{0:string,1:string} $handler
     */
    public function post(string $path, array $handler): void
    {
        $this->rute['POST'][$path] = $handler;
    }

    // ── Dispatch ──────────────────────────────────────────────────────────

    /**
     * Cocokkan method + path lalu jalankan controller-nya.
     * Kirim 404 bila tidak ditemukan.
     *
     * @param string $method HTTP method ('GET', 'POST', …)
     * @param string $path   URI path yang sudah di-parse
     */
    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);

        // Cek apakah rute terdaftar untuk method dan path ini
        if (isset($this->rute[$method][$path])) {
            [$kelas, $namaMethod] = $this->rute[$method][$path];

            // Instansiasi controller lalu panggil method-nya
            $controller = new $kelas();
            $controller->$namaMethod();
            return;
        }

        // Rute tidak ditemukan – kirim respons 404
        http_response_code(404);
        echo '<h1>404 – Halaman Tidak Ditemukan</h1>';
        echo '<p>Rute <code>' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '</code> tidak terdaftar.</p>';
        exit;
    }
}

