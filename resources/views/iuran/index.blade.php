@extends('layouts.app')

@section('title', 'Data Iuran')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fa-solid fa-coins text-blue-600"></i> Data Iuran (Master Tagihan)
    </h1>
    <p class="text-sm text-gray-500">Kelola tagihan kas per periode / minggu sesuai ERD</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-plus-circle text-blue-600"></i> Tambah Iuran</h2>
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
                <input type="date" name="tanggal_jatuh_tempo" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl text-sm transition">Simpan Iuran</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Tagihan Iuran</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr><th class="p-3 rounded-l-xl">No</th><th class="p-3">Periode</th><th class="p-3">Minggu</th><th class="p-3">Nominal</th><th class="p-3">Jatuh Tempo</th><th class="p-3">Terbayar</th><th class="p-3 rounded-r-xl">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($iuran as $i => $r)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $i+1 }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $r->periode }}</td>
                            <td class="p-3">{{ $r->minggu ?? '-' }}</td>
                            <td class="p-3 text-green-600 font-semibold">Rp {{ number_format($r->nominal, 0, ',', '.') }}</td>
                            <td class="p-3">{{ $r->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($r->tanggal_jatuh_tempo)->format('d/m/Y') : '-' }}</td>
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
                <input type="date" name="tanggal_jatuh_tempo" id="edit_iuran_jatuh" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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

    function openEditIuran(btn) {
        editIuranForm.action = "{{ url('bendahara/iuran') }}/" + btn.dataset.id;
        document.getElementById('edit_iuran_periode').value = btn.dataset.periode;
        document.getElementById('edit_iuran_minggu').value = btn.dataset.minggu || '';
        document.getElementById('edit_iuran_nominal').value = btn.dataset.nominal;
        document.getElementById('edit_iuran_jatuh').value = btn.dataset.jatuh || '';
        editIuranModal.classList.remove('hidden');
    }

    function closeEditIuran() {
        editIuranModal.classList.add('hidden');
    }

    editIuranModal.addEventListener('click', (e) => {
        if (e.target === editIuranModal) closeEditIuran();
    });
</script>
@endpush
