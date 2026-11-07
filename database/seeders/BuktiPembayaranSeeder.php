<?php

namespace Database\Seeders;

use App\Models\BuktiPembayaran;
use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class BuktiPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $pembayaran = Pembayaran::where('metode', 'transfer')->take(5)->get();

        foreach ($pembayaran as $index => $p) {
            BuktiPembayaran::create([
                'id_pembayaran' => $p->id_pembayaran,
                'nama_file' => 'bukti-' . $p->id_pembayaran . '.jpg',
                'file_path' => 'bukti/bukti-' . $p->id_pembayaran . '.jpg',
                'status_verifikasi' => $index < 3 ? 'verified' : 'pending',
            ]);
        }
    }
}
