@extends('layouts.app')

@section('title', 'Laporan Kas')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Kas</h1>
    <p class="text-gray-500 text-sm">Rekap pemasukan dan pengeluaran</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Pemasukan</p>
        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Pengeluaran</p>
        <p class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Saldo Akhir</p>
        <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
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
