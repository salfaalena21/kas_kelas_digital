@extends('layouts.app')

@section('title', 'Laporan Kas')

@section('content')
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-file-lines mr-1"></i> Admin</span>
        <h1 class="text-2xl font-bold mb-1">Laporan Kas</h1>
        <p class="text-blue-100 text-sm">Rekap pemasukan dan pengeluaran kas kelas</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-file-invoice-dollar"></i></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pemasukan</p>
            <h4 class="text-2xl font-bold text-green-600">Rp {{ number_format($pemasukan, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h4 class="text-2xl font-bold text-red-600">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Saldo Akhir</p>
            <h4 class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
        <div class="px-4 py-3 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Pemasukan Terakhir</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Tanggal</th>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Siswa</th>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Iuran</th>
                    <th class="text-right px-4 py-2 font-medium text-gray-600">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($pembayaran as $pb)
                <tr>
                    <td class="px-4 py-2 text-gray-600">{{ $pb->tanggal_bayar }}</td>
                    <td class="px-4 py-2 text-gray-800">{{ $pb->siswa->nama ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $pb->iuran->periode ?? '-' }}</td>
                    <td class="px-4 py-2 text-right text-green-600">Rp {{ number_format($pb->nominal_bayar, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
        <div class="px-4 py-3 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Pengeluaran Terakhir</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Tanggal</th>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Keterangan</th>
                    <th class="text-right px-4 py-2 font-medium text-gray-600">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($pengeluaranList as $pl)
                <tr>
                    <td class="px-4 py-2 text-gray-600">{{ $pl->tanggal }}</td>
                    <td class="px-4 py-2 text-gray-800">{{ $pl->keterangan }}</td>
                    <td class="px-4 py-2 text-right text-red-600">Rp {{ number_format($pl->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
