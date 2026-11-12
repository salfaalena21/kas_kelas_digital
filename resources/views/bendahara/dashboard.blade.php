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

<!-- Grafik Arus Kas (khusus bendahara) -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fa-solid fa-chart-column text-blue-600"></i> Grafik &amp; Analisis Arus Kas Kelas</h3>
            <p class="text-xs text-gray-400">Pemasukan dan pengeluaran kas per bulan</p>
        </div>
        <span class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
            <i class="fa-solid fa-calendar"></i> Bulanan
        </span>
    </div>
    <div class="h-72">
        <canvas id="bendaharaKasChart"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        const compactRupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact', maximumFractionDigits: 1 }).format(value);

        const kasCanvas = document.getElementById('bendaharaKasChart');
        if (kasCanvas) {
            new Chart(kasCanvas, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: @json($chartMasuk ?? []),
                            backgroundColor: '#2563eb',
                            borderRadius: 6,
                            maxBarThickness: 28
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($chartKeluar ?? []),
                            backgroundColor: '#f87171',
                            borderRadius: 6,
                            maxBarThickness: 28
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.dataset.label + ': ' + rupiah(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (value) => compactRupiah(value) },
                            grid: { color: '#f1f5f9' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }


    })();
</script>
@endpush
