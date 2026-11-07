@extends('layouts.app')

@section('title', 'Pengeluaran & Nota')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fa-solid fa-receipt text-red-500"></i> Data Pengeluaran Kas Kelas
        </h1>
        <p class="text-sm text-gray-500">Catat dan pantau penggunaan uang kas kelas</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-minus-circle text-red-500"></i> Tambah Pengeluaran
        </h2>
        <form action="{{ route('bendahara.pengeluaran.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <input type="text" name="keterangan" placeholder="Contoh: Beli Spidol & Penghapus" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                <input type="number" name="jumlah" placeholder="Contoh: 15000" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:outline-none" required>
            </div>
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 rounded-xl text-sm transition">
                <i class="fa-solid fa-save me-1"></i> Simpan Pengeluaran
            </button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Pengeluaran</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-3 rounded-l-xl">No</th>
                        <th class="p-3">Keterangan</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3 {{ (Auth::user()->role ?? '') === 'bendahara' ? '' : 'rounded-r-xl' }}">Tanggal</th>
                        @if((Auth::user()->role ?? '') === 'bendahara')
                            <th class="p-3 rounded-r-xl">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengeluaran as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-800">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $item->keterangan }}</td>
                            <td class="p-3 text-red-600 font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            @if((Auth::user()->role ?? '') === 'bendahara')
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="openEditPengeluaran(this)"
                                                data-id="{{ $item->id }}"
                                                data-keterangan="{{ $item->keterangan }}"
                                                data-jumlah="{{ $item->jumlah }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                                class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-semibold rounded-lg transition" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('bendahara.pengeluaran.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus pengeluaran \"{{ $item->keterangan }}\"?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold rounded-lg transition" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ (Auth::user()->role ?? '') === 'bendahara' ? 5 : 4 }}" class="p-3 text-center text-gray-400">Belum ada data pengeluaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if((Auth::user()->role ?? '') === 'bendahara')
<!-- Modal Edit Pengeluaran -->
<div id="editPengeluaranModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Pengeluaran
            </h2>
            <button type="button" onclick="closeEditPengeluaran()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editPengeluaranForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <input type="text" name="keterangan" id="edit_peng_keterangan" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="edit_peng_jumlah" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="edit_peng_tanggal" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEditPengeluaran()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl text-sm transition">Batal</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 rounded-xl text-sm transition">
                    <i class="fa-solid fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    const editPengeluaranModal = document.getElementById('editPengeluaranModal');
    if (editPengeluaranModal) {
        const editPengeluaranForm = document.getElementById('editPengeluaranForm');

        window.openEditPengeluaran = function (btn) {
            editPengeluaranForm.action = "{{ url('bendahara/pengeluaran') }}/" + btn.dataset.id;
            document.getElementById('edit_peng_keterangan').value = btn.dataset.keterangan;
            document.getElementById('edit_peng_jumlah').value = btn.dataset.jumlah;
            document.getElementById('edit_peng_tanggal').value = btn.dataset.tanggal;
            editPengeluaranModal.classList.remove('hidden');
        };
        window.closeEditPengeluaran = function () {
            editPengeluaranModal.classList.add('hidden');
        };
        editPengeluaranModal.addEventListener('click', (e) => {
            if (e.target === editPengeluaranModal) closeEditPengeluaran();
        });
    }
</script>
@endpush
