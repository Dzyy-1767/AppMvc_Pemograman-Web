<?php

declare(strict_types=1);

spl_autoload_register(static function (string $kelas): void {
    $awalan = 'UMM\\Pemweb\\';

    if (strncmp($kelas, $awalan, strlen($awalan)) !== 0) {
        return;
    }

    $relatif = substr($kelas, strlen($awalan));
    $berkas = __DIR__ . DIRECTORY_SEPARATOR
        . str_replace('\\', DIRECTORY_SEPARATOR, $relatif)
        . '.php';

    if (is_file($berkas)) {
        require $berkas;
    }
});
