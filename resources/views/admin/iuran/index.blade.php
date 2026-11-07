@extends('layouts.app')

@section('title', 'Iuran')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-2">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Iuran</h1>
        <p class="text-gray-500 text-sm">Kelola tagihan iuran mingguan kas kelas</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm">+ Tambah Iuran</button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Periode</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Minggu</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Nominal</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($iuran as $i)
            <tr>
                <td class="px-4 py-3 text-gray-800">{{ $i->periode }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $i->minggu ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-600">Rp {{ number_format($i->nominal, 0, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="editIuran({{ $i->id_iuran }}, '{{ addslashes($i->periode) }}', '{{ addslashes($i->minggu ?? '') }}', {{ $i->nominal }})" class="w-8 h-8 inline-flex items-center justify-center bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-lg transition" title="Edit" aria-label="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('admin.iuran.destroy', $i->id_iuran) }}" method="POST" onsubmit="return confirm('Hapus iuran ini?')" class="inline-flex">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition" title="Hapus" aria-label="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-3 text-center text-gray-400">Belum ada data iuran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Tambah Iuran</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.iuran.store') }}" method="POST" class="p-5 space-y-3">
            @csrf
            <input type="text" name="periode" required placeholder="Periode (contoh: Oktober 2026)" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="text" name="minggu" placeholder="Minggu (opsional)" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="number" name="nominal" required placeholder="Nominal" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="date" name="tanggal_jatuh_tempo" placeholder="Tanggal jatuh tempo" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm">Simpan</button>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Edit Iuran</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-5 space-y-3">
            @csrf @method('PUT')
            <input type="text" id="edit_periode" name="periode" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="text" id="edit_minggu" name="minggu" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="number" id="edit_nominal" name="nominal" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="date" id="edit_tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm">Update</button>
        </form>
    </div>
</div>

<script>
function editIuran(id, periode, minggu, nominal) {
    document.getElementById('editForm').action = '/admin/iuran/' + id;
    document.getElementById('edit_periode').value = periode;
    document.getElementById('edit_minggu').value = minggu;
    document.getElementById('edit_nominal').value = nominal;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection
