@extends('layouts.app')

@section('title', 'Pengeluaran Kas Kelas')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fa-solid fa-receipt text-red-500"></i> Pengeluaran Kas Kelas
    </h1>
    <p class="text-sm text-gray-500">Catatan penggunaan kas kelas yang dapat dilihat seluruh siswa</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
        <h4 class="text-2xl font-bold text-red-600">Rp {{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</h4>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jumlah Catatan</p>
        <h4 class="text-2xl font-bold text-gray-800">{{ $pengeluaran->count() }}</h4>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Terakhir</p>
        <h4 class="text-2xl font-bold text-gray-800">
            {{ $pengeluaran->first() ? \Carbon\Carbon::parse($pengeluaran->first()->tanggal)->format('d/m/Y') : '-' }}
        </h4>
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
