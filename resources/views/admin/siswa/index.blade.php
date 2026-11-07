@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-2">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Siswa</h1>
        <p class="text-gray-500 text-sm">Kelola data siswa & relasi akun</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm">+ Tambah Siswa</button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">NIS</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Nama</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Kelas</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Akun Login</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($siswa as $s)
            <tr>
                <td class="px-4 py-3 text-gray-800">{{ $s->nis }}</td>
                <td class="px-4 py-3 text-gray-800">{{ $s->nama }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $s->user->username ?? '-' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="editSiswa({{ $s->id_siswa }}, '{{ addslashes($s->nis) }}', '{{ addslashes($s->nama) }}', {{ $s->id_kelas }}, {{ $s->id_user ?? 'null' }})" class="w-8 h-8 inline-flex items-center justify-center bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-lg transition" title="Edit" aria-label="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('admin.siswa.destroy', $s->id_siswa) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')" class="inline-flex">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition" title="Hapus" aria-label="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Tambah Siswa</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.siswa.store') }}" method="POST" class="p-5 space-y-3">
            @csrf
            <input type="text" name="nis" required placeholder="NIS" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="text" name="nama" required placeholder="Nama Lengkap" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <select name="id_kelas" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <select name="id_user" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <option value="">-- Tanpa Akun Login --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id_user }}">{{ $u->username }} ({{ $u->nama }})</option>
                @endforeach
            </select>
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm">Simpan</button>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Edit Siswa</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-5 space-y-3">
            @csrf @method('PUT')
            <input type="text" id="edit_nis" name="nis" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="text" id="edit_nama" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <select id="edit_id_kelas" name="id_kelas" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                @foreach($kelas as $k)
                    <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <select id="edit_id_user" name="id_user" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <option value="">-- Tanpa Akun Login --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id_user }}">{{ $u->username }} ({{ $u->nama }})</option>
                @endforeach
            </select>
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm">Update</button>
        </form>
    </div>
</div>

<script>
function editSiswa(id, nis, nama, id_kelas, id_user) {
    document.getElementById('editForm').action = '/admin/siswa/' + id;
    document.getElementById('edit_nis').value = nis;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_id_kelas').value = id_kelas;
    document.getElementById('edit_id_user').value = id_user === null ? '' : id_user;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection
