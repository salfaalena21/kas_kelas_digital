<?php

namespace Database\Seeders;

use App\Models\Iuran;
use Illuminate\Database\Seeder;

class IuranSeeder extends Seeder
{
    public function run(): void
    {
        // Iuran Juni - Desember 2026, 4 minggu per bulan (28 iuran)
        $bulan = [
            ['periode' => 'Juni 2026', 'year' => 2026, 'month' => 6],
            ['periode' => 'Juli 2026', 'year' => 2026, 'month' => 7],
            ['periode' => 'Agustus 2026', 'year' => 2026, 'month' => 8],
            ['periode' => 'September 2026', 'year' => 2026, 'month' => 9],
            ['periode' => 'Oktober 2026', 'year' => 2026, 'month' => 10],
            ['periode' => 'November 2026', 'year' => 2026, 'month' => 11],
            ['periode' => 'Desember 2026', 'year' => 2026, 'month' => 12],
        ];

        $data = [];
        foreach ($bulan as $b) {
            $dueDays = [7, 14, 21, 28];
            for ($w = 1; $w <= 4; $w++) {
                $data[] = [
                    'periode' => $b['periode'],
                    'minggu' => 'Minggu ke-' . $w,
                    'nominal' => 5000,
                    'tanggal_jatuh_tempo' => sprintf('%04d-%02d-%02d', $b['year'], $b['month'], $dueDays[$w - 1]),
                ];
            }
        }

        foreach ($data as $i) {
            Iuran::firstOrCreate(
                ['periode' => $i['periode'], 'minggu' => $i['minggu']],
                $i
            );
        }
    }
}
