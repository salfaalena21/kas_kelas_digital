<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan sesuai relasi ERD: users & kelas dulu, baru siswa, iuran, pembayaran, bukti
        $this->call([
            UserSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            IuranSeeder::class,
            PembayaranSeeder::class,
            BuktiPembayaranSeeder::class,
            PengeluaranSeeder::class,
        ]);
    }
}
