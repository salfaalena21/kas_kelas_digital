<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use Illuminate\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['keterangan' => 'Beli spidol & penghapus papan', 'jumlah' => 45000, 'tanggal' => '2026-10-05'],
            ['keterangan' => 'Fotocopy soal latihan', 'jumlah' => 30000, 'tanggal' => '2026-10-08'],
            ['keterangan' => 'Konsumsi kerja bakti kelas', 'jumlah' => 75000, 'tanggal' => '2026-10-12'],
        ];

        foreach ($data as $p) {
            Pengeluaran::create($p);
        }
    }
}
