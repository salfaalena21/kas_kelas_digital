@extends('layouts.app')

@section('title', 'Laporan Kas')

@section('content')
<div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-file-lines mr-1"></i> Wali Kelas</span>
        <h1 class="text-2xl font-bold mb-1">Laporan Kas Kelas</h1>
        <p class="text-indigo-100 text-sm">Rekap pemasukan (pembayaran) & pengeluaran</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-file-invoice-dollar"></i></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pemasukan</p>
            <h4 class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h4 class="text-2xl font-bold text-red-500">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Saldo Akhir</p>
            <h4 class="text-2xl font-bold text-indigo-600">Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
        <div>
            <h3 class="font-bold text-gray-800">Laporan Kas per Siswa</h3>
            <p class="text-xs text-gray-500 mt-1">Rekap otomatis iuran <span class="font-medium text-gray-700">{{ $periodeLabel ?? '6 bulan' }}</span> (total {{ $totalIuran6Bulan ?? 0 }} iuran/tagihan aktif) — jumlah sudah dibayar (lunas) dan belum dibayar (nunggak) per siswa.</p>
        </div>
        <span class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-medium w-fit">{{ isset($rekapPerSiswa) ? $rekapPerSiswa->count() : 0 }} siswa</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold">
                <tr>
                    <th class="p-3 rounded-l-xl">No</th>
                    <th class="p-3">Nama Siswa</th>
                    <th class="p-3 text-center">Lunas</th>
                    <th class="p-3 text-center">Belum Bayar (Nunggak)</th>
                    <th class="p-3 rounded-r-xl">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($rekapPerSiswa ?? []) as $i => $r)
                @php $tuntas = ($r['belum'] ?? 0) == 0 && ($totalIuran6Bulan ?? 0) > 0; @endphp
                <tr>
                    <td class="p-3">{{ $i + 1 }}</td>
                    <td class="p-3 font-medium text-gray-900">{{ $r['nama'] ?? '-' }}</td>
                    <td class="p-3 text-center"><span class="text-green-700 font-semibold">{{ $r['lunas'] ?? 0 }}</span> <span class="text-gray-400">/ {{ $totalIuran6Bulan ?? 0 }}</span></td>
                    <td class="p-3 text-center"><span class="{{ ($r['belum'] ?? 0) > 0 ? 'text-red-600' : 'text-green-700' }} font-semibold">{{ $r['belum'] ?? 0 }}</span></td>
                    <td class="p-3">
                        @if(($totalIuran6Bulan ?? 0) == 0)
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">Belum ada data iuran</span>
                        @elseif($tuntas)
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 font-medium">Lunas semua</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">Masih nunggak</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-4 text-center text-gray-400">Data siswa belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-800 mb-1">Rekap Pembayaran per Siswa</h3>
        <p class="text-xs text-gray-500 mb-4">Ringkasan per nama siswa (rapi, tanpa daftar transaksi panjang).</p>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-3 rounded-l-xl">No</th>
                        <th class="p-3">Nama Siswa</th>
                        <th class="p-3 text-center">Jml Bayar</th>
                        <th class="p-3 rounded-r-xl text-right">Total Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(($rekapPemasukanSiswa ?? []) as $i => $r)
                        <tr>
                            <td class="p-3">{{ $i + 1 }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $r['nama'] ?? '-' }}</td>
                            <td class="p-3 text-center text-gray-700">{{ $r['jml_bayar'] ?? 0 }}x</td>
                            <td class="p-3 text-right text-green-600 font-semibold">Rp {{ number_format($r['total_bayar'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-400">Belum ada data pembayaran.</td></tr>
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
