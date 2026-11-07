<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::create([
            'nama_kelas' => 'XII PPLG',
            'wali_kelas' => 'Pak Baban',
        ]);

        Kelas::create([
            'nama_kelas' => 'XII RPL',
            'wali_kelas' => 'Bu Rina',
        ]);
    }
}
