<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB; // <---Tambahkan baris ini di atas

class BendaharaController extends Controller
{
    public function index()
    {
        $totalPemasukan = Pembayaran::sum('nominal_bayar');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
    //kueri rekap agregat
        $rekapKas = Pembayaran::select(
            'id_iuran', // Sesuaikan kolom pengelompokan (bisa id_iuran / kategori)
            DB::raw('COUNT(*) as total_data'),
            DB::raw('SUM(nominal_bayar) as total_nominal')
        )
        ->groupBy('id_iuran')
        ->get();
        // ----------------------------------------

        // Masukkan $rekapKas ke dalam compact view
        return view('bendahara.dashboard', compact('totalPemasukan', 'totalPengeluaran', 'saldo', 'rekapKas'));
    }
}