@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-user-graduate mr-1"></i> Admin</span>
        <h1 class="text-2xl font-bold mb-1">Data Siswa</h1>
        <p class="text-blue-100 text-sm">Kelola data siswa & relasi akun</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-user-graduate"></i></div>
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


@endsection
