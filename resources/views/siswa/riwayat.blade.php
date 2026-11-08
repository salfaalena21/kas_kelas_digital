@extends('layouts.app')

@section('title', 'Riwayat Pembayaran Saya')

@section('content')
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Siswa</span>
        <h1 class="text-2xl font-bold mb-1">Riwayat Pembayaran Saya</h1>
        <p class="text-blue-100 text-sm">{{ $siswaSaya->nama ?? '' }} ({{ $siswaSaya->nis ?? '' }}) &middot; {{ $siswaSaya->kelas->nama_kelas ?? '' }}</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-clock-rotate-left"></i></div>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr><th class="p-3 rounded-l-xl">No</th><th class="p-3">Tagihan</th><th class="p-3">Nominal Tagihan</th><th class="p-3">Dibayar</th><th class="p-3">Kembalian</th><th class="p-3">Tanggal</th><th class="p-3 rounded-r-xl">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($riwayat ?? [] as $i => $r)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $i+1 }}</td>
                        <td class="p-3 font-medium text-gray-900">{{ $r->iuran->periode ?? '-' }} {{ $r->iuran->minggu ?? '' }}</td>
                        <td class="p-3">Rp {{ number_format($r->iuran->nominal ?? 0, 0, ',', '.') }}</td>
                        <td class="p-3 text-green-600 font-semibold">Rp {{ number_format($r->nominal_bayar, 0, ',', '.') }}</td>
                        <td class="p-3">Rp {{ number_format($r->kembalian, 0, ',', '.') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($r->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td class="p-3"><span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">{{ ucfirst($r->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-4 text-center text-gray-400">Belum ada riwayat pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
