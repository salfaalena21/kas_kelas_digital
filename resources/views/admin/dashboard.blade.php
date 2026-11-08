@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-gray-500 text-sm">Ringkasan sistem kas kelas</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total User</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUser }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Kelas</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalKelas }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Siswa</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSiswa }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Iuran</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalIuran }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Pemasukan</p>
        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Pengeluaran</p>
        <p class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Saldo Kas</p>
        <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-gray-800">Arus Kas 6 Bulan Terakhir</h2>
                <p class="text-xs text-gray-400">Pemasukan dan pengeluaran kas per bulan</p>
            </div>
            <span class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                <i class="fa-solid fa-chart-column"></i> Bulanan
            </span>
        </div>
        <div class="h-72">
            <canvas id="adminKasChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="mb-4">
            <h2 class="font-bold text-gray-800">Status Pembayaran</h2>
            <p class="text-xs text-gray-400">Lunas vs belum lunas</p>
        </div>
        <div class="h-56">
            <canvas id="adminStatusChart"></canvas>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-center">
            <div class="bg-green-50 rounded-xl p-3">
                <p class="text-xs text-green-600 font-medium">Lunas</p>
                <p class="text-lg font-bold text-green-700">{{ number_format($totalLunas, 0, ',', '.') }}</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-3">
                <p class="text-xs text-amber-600 font-medium">Belum Lunas</p>
                <p class="text-lg font-bold text-amber-700">{{ number_format($belumLunas, 0, ',', '.') }}</p>
            </div>
        </div>
        <p class="text-[11px] text-gray-400 text-center mt-2">Dari {{ number_format($totalTagihan, 0, ',', '.') }} tagihan</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        const compactRupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact', maximumFractionDigits: 1 }).format(value);

        const kasCanvas = document.getElementById('adminKasChart');
        if (kasCanvas) {
            new Chart(kasCanvas, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: @json($chartMasuk),
                            backgroundColor: '#2563eb',
                            borderRadius: 6,
                            maxBarThickness: 28
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($chartKeluar),
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

        const statusCanvas = document.getElementById('adminStatusChart');
        if (statusCanvas) {
            const lunas = @json($totalLunas);
            const belum = @json($belumLunas);
            const kosong = (lunas + belum) === 0;

            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: kosong ? ['Belum ada tagihan'] : ['Lunas', 'Belum Lunas'],
                    datasets: [{
                        data: kosong ? [1] : [lunas, belum],
                        backgroundColor: kosong ? ['#e5e7eb'] : ['#22c55e', '#f59e0b'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                        tooltip: {
                            enabled: !kosong,
                            callbacks: {
                                label: (ctx) => ctx.label + ': ' + rupiah(ctx.parsed)
                            }
                        }
                    }
                }
            });
        }
    })();
</script>
@endpush
