<?php

namespace Database\Seeders;

use App\Models\Iuran;
use Illuminate\Database\Seeder;

class IuranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['periode' => 'Oktober 2026', 'minggu' => 'Minggu ke-1', 'nominal' => 5000, 'tanggal_jatuh_tempo' => '2026-10-04'],
            ['periode' => 'Oktober 2026', 'minggu' => 'Minggu ke-2', 'nominal' => 5000, 'tanggal_jatuh_tempo' => '2026-10-11'],
            ['periode' => 'Oktober 2026', 'minggu' => 'Minggu ke-3', 'nominal' => 5000, 'tanggal_jatuh_tempo' => '2026-10-18'],
            ['periode' => 'Oktober 2026', 'minggu' => 'Minggu ke-4', 'nominal' => 10000, 'tanggal_jatuh_tempo' => '2026-10-25'],
            ['periode' => 'November 2026', 'minggu' => 'Minggu ke-1', 'nominal' => 5000, 'tanggal_jatuh_tempo' => '2026-11-01'],
            ['periode' => 'November 2026', 'minggu' => 'Minggu ke-2', 'nominal' => 5000, 'tanggal_jatuh_tempo' => '2026-11-08'],
        ];

        foreach ($data as $i) {
            Iuran::firstOrCreate(
                ['periode' => $i['periode'], 'minggu' => $i['minggu']],
                $i
            );
        }
    }
}
