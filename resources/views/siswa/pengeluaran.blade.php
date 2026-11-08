@extends('layouts.app')

@section('title', 'Pengeluaran Kas Kelas')

@section('content')
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-receipt mr-1"></i> Siswa</span>
        <h1 class="text-2xl font-bold mb-1">Pengeluaran Kas Kelas</h1>
        <p class="text-blue-100 text-sm">Catatan penggunaan kas kelas yang dapat dilihat seluruh siswa</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-receipt"></i></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h4 class="text-2xl font-bold text-red-600">Rp {{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jumlah Catatan</p>
            <h4 class="text-2xl font-bold text-gray-800">{{ $pengeluaran->count() }}</h4>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-list"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Terakhir</p>
            <h4 class="text-2xl font-bold text-gray-800">{{ $pengeluaran->first() ? \Carbon\Carbon::parse($pengeluaran->first()->tanggal)->format('d/m/Y') : '-' }}</h4>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-regular fa-calendar"></i></div>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Pengeluaran</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr>
                    <th class="p-3 rounded-l-xl">No</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Keterangan</th>
                    <th class="p-3 rounded-r-xl">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengeluaran as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="p-3 font-medium text-gray-900">{{ $item->keterangan }}</td>
                        <td class="p-3 text-red-600 font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-gray-400">Belum ada catatan pengeluaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
