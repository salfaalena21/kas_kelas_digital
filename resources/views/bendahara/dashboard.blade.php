@extends('layouts.app')

@section('title', 'Dashboard Kas Kelas')

@section('content')
<!-- Header -->
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div class="flex items-center space-x-3">
        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold">
            <i class="fa-regular fa-calendar mr-1"></i> {{ $periodeAktif ?? 'Minggu berjalan' }}
        </span>
    </div>
    <div class="flex items-center space-x-3">
        <div class="text-right">
            <p class="font-bold text-sm text-gray-800">{{ Auth::user()->nama ?? 'Bendahara' }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', Auth::user()->role ?? '') }}</p>
        </div>
    </div>
</div>

<!-- Banner Welcome -->
<div class="bg-blue-800 text-white p-6 rounded-2xl mb-6 relative overflow-hidden">
    <span class="bg-blue-700 text-xs px-3 py-1 rounded-full text-blue-200">{{ $kelas->nama_kelas ?? 'Kelas XII PPLG' }} • TA 2024/2025</span>
    <h2 class="text-2xl font-bold mt-2">Halo, {{ Auth::user()->nama ?? 'Bendahara' }}</h2>
    <p class="text-sm text-blue-200 mt-1">{{ $kelas->nama_kelas ?? 'Kelas XII PPLG' }} • Wali Kelas: {{ $kelas->wali_kelas ?? '-' }} • Total Siswa: {{ $totalSiswa ?? 0 }}</p>
</div>

<!-- Kartu Ringkasan Saldo -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Kas Terkumpul (Saldo)</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</h3>
        <span class="text-xs text-gray-400 mt-2 block">Auditable oleh {{ $totalSiswa ?? 0 }} siswa kelas</span>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Total Pemasukan</p>
        <h3 class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</h3>
        <span class="text-xs text-gray-400 mt-2 block">{{ $totalPembayaran ?? 0 }} transaksi pembayaran</span>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Total Pengeluaran</p>
        <h3 class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h3>
        <span class="text-xs text-gray-400 mt-2 block">Sisa Kas Aman</span>
    </div>
</div>

<!-- Pembayaran Terbaru -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-4">
        <h4 class="font-bold text-gray-800">Pembayaran Terbaru</h4>
        <a href="{{ route('bendahara.pembayaran') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr>
                    <th class="p-3 rounded-l-xl">Siswa</th>
                    <th class="p-3">Tagihan</th>
                    <th class="p-3">Bayar</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3 rounded-r-xl">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pembayaranTerbaru ?? [] as $b)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-medium text-gray-900">{{ $b->siswa->nama ?? '-' }}</td>
                        <td class="p-3">{{ $b->iuran->periode ?? '-' }} {{ $b->iuran->minggu ?? '' }}</td>
                        <td class="p-3 text-green-600 font-semibold">Rp {{ number_format($b->nominal_bayar, 0, ',', '.') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($b->tanggal_bayar)->format('d/m/Y') }}</td>
                        <td class="p-3"><span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold">{{ ucfirst($b->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-4 text-center text-gray-400">Belum ada pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Area Grafik -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <h4 class="font-bold text-gray-800 mb-4">Grafik & Analisis Arus Kas Kelas</h4>
    <canvas id="kasChart" height="100"></canvas>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('kasChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Pekan 1','Pekan 2','Pekan 3','Pekan 4','Pekan 5','Pekan 6']) !!},
            datasets: [{
                label: 'Kas Masuk',
                data: {!! json_encode($chartMasuk ?? [0,0,0,0,0,0]) !!},
                backgroundColor: '#2563eb'
            }, {
                label: 'Kas Keluar',
                data: {!! json_encode($chartKeluar ?? [0,0,0,0,0,0]) !!},
                backgroundColor: '#38bdf8'
            }]
        }
    });
</script>
@endpush
