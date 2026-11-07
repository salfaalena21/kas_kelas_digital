@extends('layouts.app')

@section('title', 'Laporan Kas')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fa-solid fa-file-lines text-indigo-600"></i> Laporan Kas Kelas
    </h1>
    <p class="text-sm text-gray-500">Rekap pemasukan (pembayaran) & pengeluaran</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Total Pemasukan</p>
        <h3 class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Total Pengeluaran</p>
        <h3 class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Saldo Akhir</p>
        <h3 class="text-2xl font-bold text-indigo-600 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-4">Semua Pembayaran</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-xs font-semibold"><tr><th class="p-3 rounded-l-xl">Siswa</th><th class="p-3">Iuran</th><th class="p-3 rounded-r-xl">Bayar</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pembayaran as $p)
                        <tr><td class="p-3 font-medium text-gray-900">{{ $p->siswa->nama ?? '-' }}</td><td class="p-3">{{ $p->iuran->periode ?? '-' }} {{ $p->iuran->minggu ?? '' }}</td><td class="p-3 text-green-600 font-semibold">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-400">Kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-4">Semua Pengeluaran</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-xs font-semibold"><tr><th class="p-3 rounded-l-xl">Keterangan</th><th class="p-3">Jumlah</th><th class="p-3 rounded-r-xl">Tanggal</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengeluaran as $p)
                        <tr><td class="p-3 font-medium text-gray-900">{{ $p->keterangan }}</td><td class="p-3 text-red-600 font-semibold">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td><td class="p-3">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-400">Kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
