<?php
/**
 * Mengonversi nilai angka (0–100) menjadi nilai huruf.
 */

declare(strict_types=1);

namespace UMM\Pemweb\Service;

use InvalidArgumentException;

class GradeCalculator
{
    private const BATAS_GRADE = [
        'E' => 0,
        'D' => 50,
        'C' => 65,
        'B' => 75,
        'A' => 85,
    ];

    private const KETERANGAN_GRADE = [
        'A' => 'Sangat baik',
        'B' => 'Baik',
        'C' => 'Cukup',
        'D' => 'Perlu ditingkatkan',
        'E' => 'Perlu perbaikan',
    ];

    public function calculate(float $score): string
    {
        if (!is_finite($score) || $score < 0 || $score > 100) {
            throw new InvalidArgumentException('Nilai harus berada dalam rentang 0 sampai 100.');
        }

        foreach (array_reverse(self::BATAS_GRADE, true) as $grade => $batasBawah) {
            if ($score >= $batasBawah) {
                return $grade;
            }
        }

        return 'E';
    }

    /**
     * @return array<int, array{grade:string,batas_bawah:int,batas_atas:?int,batas_atas_inklusif:bool,keterangan:string}>
     */
    public function rentang(): array
    {
        $rentang = [];
        $grades = array_keys(self::BATAS_GRADE);
        foreach ($grades as $indeks => $grade) {
            $gradeBerikutnya = $grades[$indeks + 1] ?? null;
            $rentang[] = [
                'grade' => $grade,
                'batas_bawah' => self::BATAS_GRADE[$grade],
                'batas_atas' => $gradeBerikutnya === null ? 100 : self::BATAS_GRADE[$gradeBerikutnya],
                'batas_atas_inklusif' => $gradeBerikutnya === null,
                'keterangan' => self::KETERANGAN_GRADE[$grade],
            ];
        }

        return array_reverse($rentang);
    }
}
