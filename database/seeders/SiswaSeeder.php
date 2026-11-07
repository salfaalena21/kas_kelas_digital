<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = Kelas::where('nama_kelas', 'XII PPLG')->first() ?? Kelas::first();

        if (!$kelas) {
            return;
        }

        $daftar = UserSeeder::daftarSiswa();
        usort($daftar, fn($a, $b) => strcasecmp($a['nama'], $b['nama']));

        $no = 1;
        foreach ($daftar as $s) {
            $userId = User::where('username', $s['username'])->first()?->id_user;

            $siswa = Siswa::firstOrCreate(
                ['nis' => '201410' . str_pad($no, 2, '0', STR_PAD_LEFT)],
                ['nama' => $s['nama'], 'id_kelas' => $kelas->id_kelas, 'id_user' => $userId]
            );

            // Pastikan relasi akun terisi (untuk data lama yang belum punya akun)
            if (!$siswa->id_user && $userId) {
                $siswa->update(['id_user' => $userId]);
            }

            $no++;
        }
    }
}
