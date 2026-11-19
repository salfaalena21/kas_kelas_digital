<?php

namespace Database\Seeders;

use App\Models\Iuran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Seeder/Helper (opsional): generate iuran massal berdasarkan rentang tahun.
 *
 * Contoh pakai:
 *   php artisan db:seed --class=IuranRangeSeeder
 *
 * Sesuaikan:
 * - $tahunAwal, $tahunAkhir, $nominalDefault, $jumlahMinggu sesuai kebutuhan
 * - Nama kolom tabel iuran: periode, minggu, nominal, tanggal_jatuh_tempo (jika berbeda)
 * - Format label periode/minggu jika project Anda memakai format lain
 */
class IuranRangeSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAwal      = 2020;  // ubah sesuai kebutuhan
        $tahunAkhir     = 2026;  // ubah sesuai kebutuhan
        $nominalDefault = 5000;  // nominal default (Rp)
        $jumlahMinggu   = 4;     // 4 = Minggu ke-1 s/d ke-4, 5 = s/d ke-5

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $created = 0;
        $skipped = 0;

        for ($tahun = $tahunAwal; $tahun <= $tahunAkhir; $tahun++) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $periode = $namaBulan[$bulan] . ' ' . $tahun; // contoh: "Mei 2023"
                $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;

                for ($minggu = 1; $minggu <= $jumlahMinggu; $minggu++) {
                    $labelMinggu = 'Minggu ke-' . $minggu;

                    // Minggu ke-1..4 = hari 7,14,21,28; minggu ke-5 = akhir bulan
                    $hari = ($minggu <= 4) ? ($minggu * 7) : $daysInMonth;
                    if ($hari > $daysInMonth) {
                        $hari = $daysInMonth;
                    }
                    $jatuhTempo = Carbon::create($tahun, $bulan, $hari)->toDateString();

                    // Anti-duplikat: tidak membuat ulang jika periode + minggu sudah ada
                    $record = Iuran::firstOrCreate(
                        ['periode' => $periode, 'minggu' => $labelMinggu],
                        ['nominal' => $nominalDefault, 'tanggal_jatuh_tempo' => $jatuhTempo]
                    );

                    $record->wasRecentlyCreated ? $created++ : $skipped++;
                }
            }
        }

        $this->command?->info("Generate iuran selesai: {$created} baru, {$skipped} dilewati (sudah ada).");
    }
}
