@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Banner -->
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10 max-w-xl">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block">
            <i class="fa-solid fa-shield-halved mr-1"></i> Panel Administrator
        </span>
        <h2 class="text-2xl font-bold mb-1">Dashboard Admin</h2>
        <p class="text-blue-100 text-sm">Halo {{ Auth::user()->nama ?? 'Admin' }}, pantau ringkasan pengguna, data kelas, dan arus kas SmartKas.</p>
    </div>
    <div class="hidden md:block opacity-20 text-8xl pr-4"><i class="fa-solid fa-gauge-high"></i></div>
</div>

<!-- Ringkasan Sistem -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total User</p>
            <h4 class="text-2xl font-bold text-gray-800">{{ $totalUser }}</h4>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-users-gear"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Kelas</p>
            <h4 class="text-2xl font-bold text-gray-800">{{ $totalKelas }}</h4>
        </div>
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-school"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Siswa</p>
            <h4 class="text-2xl font-bold text-gray-800">{{ $totalSiswa }}</h4>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-user-graduate"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Iuran</p>
            <h4 class="text-2xl font-bold text-gray-800">{{ $totalIuran }}</h4>
        </div>
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-coins"></i></div>
    </div>
</div>

<!-- Ringkasan Keuangan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pemasukan</p>
            <h4 class="text-2xl font-bold text-green-600">Rp {{ number_format($pemasukan, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h4 class="text-2xl font-bold text-red-600">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Saldo Kas</p>
            <h4 class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
    </div>
</div>

<!-- Grafik -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fa-solid fa-chart-column text-blue-600"></i> Arus Kas 6 Bulan Terakhir</h3>
                <p class="text-xs text-gray-400">Pemasukan dan pengeluaran kas per bulan</p>
            </div>
            <span class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                <i class="fa-solid fa-calendar"></i> Bulanan
            </span>
        </div>
        <div class="h-72">
            <canvas id="adminKasChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fa-solid fa-chart-pie text-blue-600"></i> Status Pembayaran</h3>
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
