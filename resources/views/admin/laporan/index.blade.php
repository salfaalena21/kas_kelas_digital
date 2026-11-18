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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto mb-6">
    <div class="px-4 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h2 class="font-semibold text-gray-800">Laporan Kas per Siswa</h2>
            <p class="text-xs text-gray-500 mt-1">Rekap otomatis iuran <span class="font-medium text-gray-700">{{ $periodeLabel ?? '6 bulan' }}</span> (total {{ $totalIuran6Bulan ?? 0 }} iuran/tagihan aktif). Menampilkan jumlah iuran sudah dibayar (lunas) dan belum dibayar (nunggak) per siswa.</p>
        </div>
        <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium w-fit">{{ isset($rekapPerSiswa) ? $rekapPerSiswa->count() : 0 }} siswa</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-4 py-2 font-medium text-gray-600">No</th>
                <th class="text-left px-4 py-2 font-medium text-gray-600">Nama Siswa</th>
                <th class="text-left px-4 py-2 font-medium text-gray-600">Kelas</th>
                <th class="text-center px-4 py-2 font-medium text-gray-600">Lunas</th>
                <th class="text-center px-4 py-2 font-medium text-gray-600">Belum Bayar (Nunggak)</th>
                <th class="text-left px-4 py-2 font-medium text-gray-600">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse(($rekapPerSiswa ?? []) as $i => $r)
            @php $tuntas = ($r['belum'] ?? 0) == 0 && ($totalIuran6Bulan ?? 0) > 0; @endphp
            <tr>
                <td class="px-4 py-2 text-gray-600">{{ $i + 1 }}</td>
                <td class="px-4 py-2 text-gray-800 font-medium">{{ $r['nama'] ?? '-' }}</td>
                <td class="px-4 py-2 text-gray-600">{{ $r['kelas'] ?? '-' }}</td>
                <td class="px-4 py-2 text-center"><span class="text-green-700 font-semibold">{{ $r['lunas'] ?? 0 }}</span> <span class="text-gray-400">/ {{ $totalIuran6Bulan ?? 0 }}</span></td>
                <td class="px-4 py-2 text-center"><span class="{{ ($r['belum'] ?? 0) > 0 ? 'text-red-600' : 'text-green-700' }} font-semibold">{{ $r['belum'] ?? 0 }}</span></td>
                <td class="px-4 py-2">
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
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Data siswa belum tersedia.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
        <div class="px-4 py-3 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Rekap Pemasukan per Siswa</h2>
            <p class="text-xs text-gray-500 mt-1">Ringkasan per nama siswa (rapi, tanpa daftar transaksi panjang).</p>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">No</th>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Nama Siswa</th>
                    <th class="text-left px-4 py-2 font-medium text-gray-600">Kelas</th>
                    <th class="text-center px-4 py-2 font-medium text-gray-600">Jml Bayar</th>
                    <th class="text-right px-4 py-2 font-medium text-gray-600">Total Bayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($rekapPemasukanSiswa ?? []) as $i => $r)
                <tr>
                    <td class="px-4 py-2 text-gray-600">{{ $i + 1 }}</td>
                    <td class="px-4 py-2 text-gray-800 font-medium">{{ $r['nama'] ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $r['kelas'] ?? '-' }}</td>
                    <td class="px-4 py-2 text-center text-gray-700">{{ $r['jml_bayar'] ?? 0 }}x</td>
                    <td class="px-4 py-2 text-right text-green-600 font-semibold">Rp {{ number_format($r['total_bayar'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada data pembayaran.</td></tr>
                @endforelse
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
