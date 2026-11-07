@extends('layouts.app')

@section('title', 'Data Siswa Binaan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fa-solid fa-users text-indigo-600"></i> Data Siswa Binaan
    </h1>
    <p class="text-sm text-gray-500">{{ $kelas->nama_kelas ?? '' }} • Wali: {{ $kelas->wali_kelas ?? '' }}</p>
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
