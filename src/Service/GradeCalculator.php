<?php
/**
 * Mengonversi nilai angka (0–100) menjadi nilai huruf.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Service;

use InvalidArgumentException;

class GradeCalculator
{
    public function calculate(float $score): string
    {
        if (!is_finite($score) || $score < 0 || $score > 100) {
            throw new InvalidArgumentException('Nilai harus berada dalam rentang 0 sampai 100.');
        }

        return match (true) {
            $score >= 85 => 'A',
            $score >= 75 => 'B',
            $score >= 65 => 'C',
            $score >= 50 => 'D',
            default => 'E',
        };
    }
}
