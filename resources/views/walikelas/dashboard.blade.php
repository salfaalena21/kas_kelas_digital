@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas')

@section('content')
<div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10 max-w-xl">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block">Selamat Datang Wali Kelas 🎓</span>
        <h3 class="text-2xl font-bold mb-1">{{ Auth::user()->nama ?? 'Wali Kelas' }}</h3>
        <p class="text-indigo-100 text-sm">Monitoring arus kas {{ $kelas->nama_kelas ?? 'kelas' }} secara transparan. Kepatuhan bayar: {{ $persenLunas ?? 0 }}%.</p>
    </div>
    <div class="hidden md:block opacity-20 text-8xl pr-4"><i class="fa-solid fa-chalkboard-user"></i></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pemasukan</p>
            <h4 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h4 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Saldo Saat Ini</p>
            <h4 class="text-2xl font-bold text-indigo-600">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2 mb-4"><i class="fa-solid fa-users text-indigo-600"></i> Kepatuhan Siswa ({{ $totalSiswa ?? 0 }} siswa)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr><th class="p-3 rounded-l-xl">Nama</th><th class="p-3">Dibayar</th><th class="p-3 rounded-r-xl">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rekapSiswa ?? [] as $s)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-900">{{ $s['nama'] }}</td>
                            <td class="p-3">{{ $s['bayar_count'] }} / {{ $totalIuran ?? 0 }} iuran</td>
                            <td class="p-3">
                                @if($s['lunas_semua'])
                                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">Lunas Semua</span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-semibold">Ada Tunggakan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2 mb-4"><i class="fa-solid fa-receipt text-indigo-600"></i> Monitoring Pengeluaran</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr><th class="p-3 rounded-l-xl">Keterangan</th><th class="p-3">Jumlah</th><th class="p-3 rounded-r-xl">Tanggal</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengeluaran ?? [] as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-900">{{ $item->keterangan }}</td>
                            <td class="p-3 text-red-600 font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-400">Belum ada pengeluaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
