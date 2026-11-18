<?php

namespace Database\Seeders;

use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class LunasinIuranSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan iuran 6 bulan (Juni - November 2026) ada
        $this->call(IuranSeeder::class);

        $periode6Bulan = ['Juni 2026','Juli 2026','Agustus 2026','September 2026','Oktober 2026','November 2026'];
        $iuranList = Iuran::whereIn('periode', $periode6Bulan)->get();
        $siswaList = Siswa::all();
        $petugas = User::where('username', 'bendahara1')->first() ?? User::where('role', 'bendahara')->first();

        foreach ($siswaList as $siswa) {
            foreach ($iuranList as $iuran) {
                $pembayaran = Pembayaran::where('id_siswa', $siswa->id_siswa)
                    ->where('id_iuran', $iuran->id_iuran)
                    ->first();

                $data = [
                    'id_siswa' => $siswa->id_siswa,
                    'id_iuran' => $iuran->id_iuran,
                    'id_user' => $petugas?->id_user,
                    'nominal_bayar' => $iuran->nominal,
                    'kembalian' => 0,
                    'tanggal_bayar' => $iuran->tanggal_jatuh_tempo ?? now()->toDateString(),
                    'metode' => 'tunai',
                    'status' => 'lunas',
                ];

                if ($pembayaran) {
                    $pembayaran->update($data);
                } else {
                    Pembayaran::create($data);
                }
            }
        }
    }
}