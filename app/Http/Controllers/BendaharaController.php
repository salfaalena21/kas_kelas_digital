<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;

class BendaharaController extends Controller
{
    public function index()
    {
        $totalPemasukan = Pembayaran::sum('nominal_bayar');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('bendahara.dashboard', compact('totalPemasukan', 'totalPengeluaran', 'saldo'));
    }
}
