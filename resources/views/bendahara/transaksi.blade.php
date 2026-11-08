@extends('layouts.app')

@section('title', 'Catat Transaksi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex flex-wrap items-center justify-between gap-3 relative overflow-hidden mb-6">
        <div class="relative z-10">
            <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-pen-to-square mr-1"></i> Bendahara</span>
            <h1 class="text-2xl font-bold mb-1">Pencatatan Kas Kelas</h1>
            <p class="text-blue-100 text-sm">Ringkasan transaksi kas kelas</p>
        </div>
        <a href="{{ route('bendahara.dashboard') }}" class="relative z-10 bg-white/15 hover:bg-white/25 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition border border-white/20">&larr; Kembali ke Dashboard</a>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl mb-4 text-sm">
        Halaman ini sudah digantikan oleh <a href="{{ route('bendahara.pembayaran') }}" class="font-semibold underline">Kas Masuk & Bayar</a>
        (sesuai ERD: Pembayaran + Iuran) dan <a href="{{ route('bendahara.pengeluaran') }}" class="font-semibold underline">Pengeluaran</a>.
    </div>

    <h2 class="text-lg font-bold text-gray-800 mb-3">Riwayat Pembayaran Terakhir</h2>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-200 text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Siswa</th>
                    <th class="border p-2">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran ?? [] as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2">{{ \Carbon\Carbon::parse($t->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td class="border p-2">{{ $t->siswa->nama ?? '-' }}</td>
                        <td class="border p-2 font-semibold text-green-600">Rp {{ number_format($t->nominal_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="border p-4 text-center text-gray-500">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
