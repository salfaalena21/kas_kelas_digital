@extends('layouts.app')

@section('title', 'Portal Keuangan Siswa')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10 max-w-xl">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block">Informasi Kas Kelas 📢</span>
        <h3 class="text-2xl font-bold mb-1">Rekapitulasi Keuangan Kelas</h3>
        <p class="text-blue-100 text-sm">Halo {{ $siswaSaya->nama ?? Auth::user()->nama ?? 'Siswa' }}, pantau transparansi kas, status bayarmu, dan pengeluaran kelas.</p>
    </div>
    <div class="hidden md:block opacity-20 text-8xl pr-4"><i class="fa-solid fa-users-line"></i></div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Kas Terkumpul</p>
            <h4 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Iuran Per Minggu</p>
            <h4 class="text-2xl font-bold text-blue-600">Rp {{ number_format($iuranAktif->nominal ?? 5000, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-coins"></i></div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h4 class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-arrow-up-right-from-square"></i></div>
    </div>
</div>

<!-- Grafik (sama seperti dashboard admin) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
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
            <canvas id="siswaKasChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="mb-4">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fa-solid fa-chart-pie text-blue-600"></i> Status Pembayaran</h3>
            <p class="text-xs text-gray-400">Lunas vs belum lunas</p>
        </div>
        <div class="h-56">
            <canvas id="siswaStatusChart"></canvas>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-center">
            <div class="bg-green-50 rounded-xl p-3">
                <p class="text-xs text-green-600 font-medium">Lunas</p>
                <p class="text-lg font-bold text-green-700">{{ number_format($totalLunas ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-3">
                <p class="text-xs text-amber-600 font-medium">Belum Lunas</p>
                <p class="text-lg font-bold text-amber-700">{{ number_format($belumLunas ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <p class="text-[11px] text-gray-400 text-center mt-2">Dari {{ number_format($totalTagihan ?? 0, 0, ',', '.') }} tagihan</p>
    </div>
</div>

<!-- Status Saya -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2 mb-4">
        <i class="fa-solid fa-id-card text-blue-600"></i> Status Pembayaran Saya
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr><th class="p-3 rounded-l-xl">Tagihan</th><th class="p-3">Nominal</th><th class="p-3">Bayar</th><th class="p-3 rounded-r-xl">Status</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($riwayatSaya ?? [] as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-medium text-gray-900">{{ $r->iuran->periode ?? '-' }} {{ $r->iuran->minggu ?? '' }}</td>
                        <td class="p-3">Rp {{ number_format($r->iuran->nominal ?? 0, 0, ',', '.') }}</td>
                        <td class="p-3 text-green-600 font-semibold">Rp {{ number_format($r->nominal_bayar, 0, ',', '.') }}</td>
                        <td class="p-3"><span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">{{ ucfirst($r->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-gray-400">Belum ada pembayaran atas namamu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Rekap Semua Siswa -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fa-solid fa-list-check text-blue-600"></i> Status Pembayaran Seluruh Siswa</h3>
        <span class="text-xs bg-gray-100 text-gray-600 font-medium px-3 py-1 rounded-lg">{{ $iuranAktif->minggu ?? 'Minggu Ini' }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr><th class="p-3 rounded-l-xl">No</th><th class="p-3">Nama Siswa</th><th class="p-3">Status</th><th class="p-3 rounded-r-xl">Keterangan</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rekapSiswa ?? [] as $i => $s)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $i+1 }}</td>
                        <td class="p-3 font-medium text-gray-900">{{ $s['nama'] }}</td>
                        <td class="p-3">
                            @if($s['lunas'])
                                <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold"><i class="fa-solid fa-circle-check me-1"></i> Lunas</span>
                            @else
                                <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-semibold"><i class="fa-solid fa-clock me-1"></i> Belum Bayar</span>
                            @endif
                        </td>
                        <td class="p-3 text-gray-500">{{ $s['keterangan'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-gray-400">Belum ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pengeluaran -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fa-solid fa-receipt text-red-500"></i> Catatan Pengeluaran Kelas</h3>
        <a href="{{ route('siswa.pengeluaran') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr><th class="p-3 rounded-l-xl">No</th><th class="p-3">Keterangan</th><th class="p-3">Nominal</th><th class="p-3 rounded-r-xl">Tanggal</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengeluaran ?? [] as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3 font-medium text-gray-900">{{ $item->keterangan }}</td>
                        <td class="p-3 text-red-600 font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-gray-400">Belum ada catatan pengeluaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        const compactRupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact', maximumFractionDigits: 1 }).format(value);

        const kasCanvas = document.getElementById('siswaKasChart');
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

        const statusCanvas = document.getElementById('siswaStatusChart');
        if (statusCanvas) {
            const lunas = @json($totalLunas ?? 0);
            const belum = @json($belumLunas ?? 0);
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
