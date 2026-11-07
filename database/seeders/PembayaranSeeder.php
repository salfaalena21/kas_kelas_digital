<?php

namespace Database\Seeders;

use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('username', 'bendahara1')->first() ?? User::where('role', 'bendahara')->first();
        $siswa = Siswa::orderBy('id_siswa')->get();
        $iuran = Iuran::orderBy('id_iuran')->get();

        if ($siswa->isEmpty() || $iuran->isEmpty()) {
            return;
        }

        // Simulasi: 5 siswa pertama lunas 4 iuran pertama, sisanya sebagian
        foreach ($siswa as $indexSiswa => $s) {
            foreach ($iuran as $indexIuran => $i) {
                // Budi (index 3) nunggak iuran ke-3 dst, Eko/Fitri baru bayar 1x
                if ($indexSiswa >= 5 && $indexIuran >= 1) continue;
                if ($indexSiswa === 3 && $indexIuran >= 2) continue;

                $lebih = ($indexSiswa === 1 && $indexIuran === 0) ? 5000 : 0; // Dimas bayar 10rb untuk iuran 5rb
                $nominalBayar = $i->nominal + $lebih;

                Pembayaran::create([
                    'id_siswa' => $s->id_siswa,
                    'id_iuran' => $i->id_iuran,
                    'id_user' => $petugas?->id_user,
                    'nominal_bayar' => $nominalBayar,
                    'kembalian' => max(0, $nominalBayar - $i->nominal),
                    'tanggal_bayar' => $i->tanggal_jatuh_tempo ?? now()->toDateString(),
                    'metode' => $indexSiswa % 2 === 0 ? 'tunai' : 'transfer',
                    'status' => 'lunas',
                ]);
            }
        }
    }
}
