@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-2">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Pengeluaran</h1>
        <p class="text-gray-500 text-sm">Kelola pengeluaran kas kelas</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm">+ Tambah Pengeluaran</button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Tanggal</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Keterangan</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Jumlah</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pengeluaran as $p)
            <tr>
                <td class="px-4 py-3 text-gray-800">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->keterangan }}</td>
                <td class="px-4 py-3 text-red-600">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="editPeng({{ $p->id }}, '{{ \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') }}', '{{ addslashes($p->keterangan) }}', {{ $p->jumlah }})" class="w-8 h-8 inline-flex items-center justify-center bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-lg transition" title="Edit" aria-label="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('admin.pengeluaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?')" class="inline-flex">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition" title="Hapus" aria-label="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-3 text-center text-gray-400">Belum ada data pengeluaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Tambah Pengeluaran</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.pengeluaran.store') }}" method="POST" class="p-5 space-y-3">
            @csrf
            <input type="date" name="tanggal" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="text" name="keterangan" required placeholder="Keterangan" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="number" name="jumlah" required placeholder="Jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm">Simpan</button>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Edit Pengeluaran</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-5 space-y-3">
            @csrf @method('PUT')
            <input type="date" id="edit_tanggal" name="tanggal" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="text" id="edit_keterangan" name="keterangan" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="number" id="edit_jumlah" name="jumlah" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm">Update</button>
        </form>
    </div>
</div>

<script>
function editPeng(id, tanggal, keterangan, jumlah) {
    document.getElementById('editForm').action = '/admin/pengeluaran/' + id;
    document.getElementById('edit_tanggal').value = tanggal;
    document.getElementById('edit_keterangan').value = keterangan;
    document.getElementById('edit_jumlah').value = jumlah;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection
