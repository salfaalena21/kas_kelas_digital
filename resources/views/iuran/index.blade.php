@extends('layouts.app')

@section('title', 'Data Iuran')

@section('content')
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-coins mr-1"></i> Bendahara</span>
        <h1 class="text-2xl font-bold mb-1">Data Iuran (Master Tagihan)</h1>
        <p class="text-blue-100 text-sm">Kelola tagihan kas per periode / minggu</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-coins"></i></div>
</div>

@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl shadow-sm text-sm" role="alert">
        <p class="font-semibold"><i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}</p>
    </div>
@endif
@if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-sm text-sm" role="alert">
        <p class="font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Terjadi kesalahan:</p>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Form Tambah Iuran Satuan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-plus-circle text-blue-600"></i> Tambah Iuran (Satuan)</h2>
            <form action="{{ route('bendahara.iuran.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <input type="text" name="periode" required placeholder="Contoh: Oktober 2026" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minggu</label>
                    <input type="text" name="minggu" placeholder="Contoh: Minggu ke-1" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" required placeholder="Contoh: 5000" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo</label>
                    <div class="relative">
                        <input type="text" id="tanggal_jatuh_tempo_display" readonly placeholder=""
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 pointer-events-none">
                            <i class="fa-regular fa-calendar"></i>
                        </span>
                        <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" aria-label="Pilih tanggal jatuh tempo">
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl text-sm transition">Simpan Iuran</button>
            </form>
        </div>

        <!-- Form Generate Iuran Massal (Baru) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-lg font-bold text-gray-800 mb-1"><i class="fa-solid fa-wand-magic-sparkles text-emerald-600"></i> Generate Iuran Massal</h2>
            <p class="text-xs text-gray-500 mb-4">Buat tagihan otomatis per rentang tahun (setiap bulan & minggu). Data duplikat (periode & minggu sama) otomatis dilewati.</p>
            {{-- Route: bendahara.iuran.generate (POST) — sesuaikan nama route jika berbeda --}}
            <form action="{{ route('bendahara.iuran.generate') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Awal</label>
                        <input type="number" name="tahun_awal" value="{{ old('tahun_awal', 2020) }}" min="2000" max="2100" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Akhir</label>
                        <input type="number" name="tahun_akhir" value="{{ old('tahun_akhir', 2026) }}" min="2000" max="2100" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Default (Rp)</label>
                    <input type="number" name="nominal_default" value="{{ old('nominal_default', 5000) }}" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-gray-400 mt-1">Contoh: 2020–2026, 12 bulan, 4 minggu = 336 tagihan (yang sudah ada tidak dibuat ulang).</p>
                </div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 rounded-xl text-sm transition"
                        onclick="return confirm('Generate iuran untuk rentang tahun terpilih? Data yang sudah ada tidak akan diduplikasi.')">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generate Iuran Massal
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Daftar Tagihan Iuran</h2>
                <p class="text-xs text-gray-500 mt-1">Total: <span id="iuranCountTotal">{{ $iuran->count() }}</span> data</p>
            </div>
            <div class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" id="iuranSearch" placeholder="Cari periode, minggu, nominal, jatuh tempo..."
                       class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr><th class="p-3 rounded-l-xl">No</th><th class="p-3">Periode</th><th class="p-3">Minggu</th><th class="p-3">Nominal</th><th class="p-3">Jatuh Tempo</th><th class="p-3">Terbayar</th><th class="p-3 rounded-r-xl">Aksi</th></tr>
                </thead>
                <tbody id="iuranTableBody" class="divide-y divide-gray-100">
                    @forelse($iuran as $i => $r)
                        @php
                            $jatuhTempoText = $r->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($r->tanggal_jatuh_tempo)->format('d/m/Y') : '-';
                        @endphp
                        <tr class="hover:bg-gray-50 iuran-row" data-search="{{ strtolower($r->periode . ' ' . ($r->minggu ?? '') . ' ' . $r->nominal . ' ' . $jatuhTempoText) }}">
                            <td class="p-3 iuran-no">{{ $i+1 }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $r->periode }}</td>
                            <td class="p-3">{{ $r->minggu ?? '-' }}</td>
                            <td class="p-3 text-green-600 font-semibold">Rp {{ number_format($r->nominal, 0, ',', '.') }}</td>
                            <td class="p-3">{{ $jatuhTempoText }}</td>
                            <td class="p-3"><span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-full font-semibold">{{ $r->pembayaran_count ?? $r->pembayaran->count() }} siswa</span></td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openEditIuran(this)"
                                            data-id="{{ $r->id_iuran }}"
                                            data-periode="{{ $r->periode }}"
                                            data-minggu="{{ $r->minggu }}"
                                            data-nominal="{{ $r->nominal }}"
                                            data-jatuh="{{ $r->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($r->tanggal_jatuh_tempo)->format('Y-m-d') : '' }}"
                                            class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-semibold rounded-lg transition" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('bendahara.iuran.destroy', $r->id_iuran) }}" method="POST"
                                          onsubmit="return confirm('Hapus iuran {{ $r->periode }} {{ $r->minggu }}? Pembayaran terkait ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-4 text-center text-gray-400">Belum ada data iuran.</td></tr>
                    @endforelse
                    <tr id="iuranNoResult" class="hidden"><td colspan="7" class="p-4 text-center text-gray-400">Tidak ada data yang cocok dengan pencarian.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Iuran -->
<div id="editIuranModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Iuran
            </h2>
            <button type="button" onclick="closeEditIuran()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editIuranForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <input type="text" name="periode" id="edit_iuran_periode" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minggu</label>
                <input type="text" name="minggu" id="edit_iuran_minggu" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                <input type="number" name="nominal" id="edit_iuran_nominal" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo</label>
                <div class="relative">
                    <input type="text" id="edit_iuran_jatuh_display" readonly placeholder=""
                           class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 pointer-events-none">
                        <i class="fa-regular fa-calendar"></i>
                    </span>
                    <input type="date" name="tanggal_jatuh_tempo" id="edit_iuran_jatuh"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" aria-label="Pilih tanggal jatuh tempo">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEditIuran()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl text-sm transition">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl text-sm transition">
                    <i class="fa-solid fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const editIuranModal = document.getElementById('editIuranModal');
    const editIuranForm = document.getElementById('editIuranForm');

    // Format yyyy-mm-dd -> dd/mm/yyyy untuk tampilan (tanpa teks hint dd/mm/tttt)
    function formatDateDisplay(isoDate) {
        if (!isoDate) return '';
        const parts = String(isoDate).split('-');
        if (parts.length !== 3) return isoDate;
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function bindDatePicker(realId, displayId) {
        const real = document.getElementById(realId);
        const display = document.getElementById(displayId);
        if (!real || !display) return;
        const sync = () => { display.value = formatDateDisplay(real.value); };
        real.addEventListener('change', sync);
        real.addEventListener('input', sync);
        sync();
    }

    bindDatePicker('tanggal_jatuh_tempo', 'tanggal_jatuh_tempo_display');
    bindDatePicker('edit_iuran_jatuh', 'edit_iuran_jatuh_display');

    function openEditIuran(btn) {
        editIuranForm.action = "{{ url('bendahara/iuran') }}/" + btn.dataset.id;
        document.getElementById('edit_iuran_periode').value = btn.dataset.periode;
        document.getElementById('edit_iuran_minggu').value = btn.dataset.minggu || '';
        document.getElementById('edit_iuran_nominal').value = btn.dataset.nominal;
        const jatuh = document.getElementById('edit_iuran_jatuh');
        jatuh.value = btn.dataset.jatuh || '';
        document.getElementById('edit_iuran_jatuh_display').value = formatDateDisplay(jatuh.value);
        editIuranModal.classList.remove('hidden');
    }

    function closeEditIuran() {
        editIuranModal.classList.add('hidden');
    }

    editIuranModal.addEventListener('click', (e) => {
        if (e.target === editIuranModal) closeEditIuran();
    });

    // --- Pencarian/filter tabel daftar iuran ---
    const iuranSearch = document.getElementById('iuranSearch');
    const iuranRows = document.querySelectorAll('.iuran-row');
    const iuranNoResult = document.getElementById('iuranNoResult');
    const iuranCountTotal = document.getElementById('iuranCountTotal');
    if (iuranSearch) {
        iuranSearch.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let visible = 0;
            let no = 1;
            iuranRows.forEach(row => {
                const text = row.dataset.search || row.textContent.toLowerCase();
                const match = q === '' || text.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) {
                    visible++;
                    const noCell = row.querySelector('.iuran-no');
                    if (noCell) noCell.textContent = no++;
                }
            });
            if (iuranNoResult) iuranNoResult.classList.toggle('hidden', visible !== 0);
            if (iuranCountTotal) iuranCountTotal.textContent = q ? (visible + ' / {{ $iuran->count() }}') : '{{ $iuran->count() }}';
        });
    }
</script>
@endpush
