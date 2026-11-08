@extends('layouts.app')

@section('title', 'Dashboard Kas Kelas')

@section('content')
<!-- Banner -->
<div class="bg-gradient-to-r from-blue-800 via-blue-700 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10 max-w-xl">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block">
            <i class="fa-regular fa-calendar mr-1"></i> {{ $periodeAktif ?? 'Minggu berjalan' }} &middot; {{ $kelas->nama_kelas ?? 'Kelas' }}
        </span>
        <h2 class="text-2xl font-bold mb-1">Halo, {{ Auth::user()->nama ?? 'Bendahara' }}</h2>
        <p class="text-blue-100 text-sm">{{ $kelas->nama_kelas ?? 'Kelas XII PPLG' }} &middot; Wali Kelas: {{ $kelas->wali_kelas ?? '-' }} &middot; Total Siswa: {{ $totalSiswa ?? 0 }}</p>
    </div>
    <div class="hidden md:block opacity-20 text-8xl pr-4"><i class="fa-solid fa-chart-pie"></i></div>
</div>

<!-- Ringkasan Saldo -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kas Terkumpul (Saldo)</p>
            <h4 class="text-2xl font-bold text-gray-800">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</h4>
            <span class="text-xs text-gray-400 mt-1 block">Auditable oleh {{ $totalSiswa ?? 0 }} siswa kelas</span>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pemasukan</p>
            <h4 class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</h4>
            <span class="text-xs text-gray-400 mt-1 block">{{ $totalPembayaran ?? 0 }} transaksi pembayaran</span>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h4 class="text-2xl font-bold text-red-500">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h4>
            <span class="text-xs text-gray-400 mt-1 block">Sisa kas aman</span>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </div>
</div>

<!-- Pembayaran Terbaru -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fa-solid fa-hand-holding-dollar text-blue-600"></i> Pembayaran Terbaru</h3>
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

<!-- Grafik -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2 mb-4"><i class="fa-solid fa-chart-column text-blue-600"></i> Grafik & Analisis Arus Kas Kelas</h3>
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
                backgroundColor: '#2563eb',
                borderRadius: 6
            }, {
                label: 'Kas Keluar',
                data: {!! json_encode($chartKeluar ?? [0,0,0,0,0,0]) !!},
                backgroundColor: '#f87171',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });
</script>
@endpush
