@extends('layouts.app')

@section('title', 'Data Siswa Binaan')

@section('content')
<div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-users mr-1"></i> Wali Kelas</span>
        <h1 class="text-2xl font-bold mb-1">Data Siswa Binaan</h1>
        <p class="text-indigo-100 text-sm">{{ $kelas->nama_kelas ?? '' }} &middot; Wali: {{ $kelas->wali_kelas ?? '' }}</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-users"></i></div>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr><th class="p-3 rounded-l-xl">No</th><th class="p-3">NIS</th><th class="p-3">Nama</th><th class="p-3">Total Bayar</th><th class="p-3 rounded-r-xl">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($siswa as $i => $s)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $i+1 }}</td>
                        <td class="p-3">{{ $s->nis }}</td>
                        <td class="p-3 font-medium text-gray-900">{{ $s->nama }}</td>
                        <td class="p-3">{{ $s->pembayaran_count ?? $s->pembayaran->count() }} transaksi</td>
                        <td class="p-3"><span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-full font-semibold">Aktif</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-4 text-center text-gray-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
