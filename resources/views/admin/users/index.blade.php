@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Pengguna</h1>
    <p class="text-gray-500 text-sm">CRUD akun pengguna (admin, bendahara, wali kelas, siswa)</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-user-plus text-blue-600"></i> Tambah User</h2>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Peran (Role)</label>
                <select name="role" id="add_role" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="admin">Admin</option>
                    <option value="siswa" selected>Siswa</option>
                    <option value="bendahara">Bendahara</option>
                    <option value="wali_kelas">Wali Kelas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" id="add_nama" required placeholder="Contoh: Budi Santoso" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="add_username" required placeholder="Otomatis dari nama..." autocomplete="off"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Muncul otomatis mengikuti Nama. Bisa diubah manual.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password (min. 6)</label>
                <input type="text" name="password" id="add_password" required placeholder="Contoh: password123" autocomplete="off"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="add_nis_wrap">
                <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                <input type="text" name="nis" id="add_nis" placeholder="Contoh: 20141001" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Wajib diisi untuk peran Siswa.</p>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl text-sm transition">Simpan User</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar User</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Nama</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Username</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Role</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $u)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $u->nama }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $u->username }}</td>
                        <td class="px-4 py-3 text-gray-600 capitalize">{{ str_replace('_', ' ', $u->role) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editUser({{ $u->id_user }}, '{{ addslashes($u->nama) }}', '{{ addslashes($u->username) }}', '{{ $u->role }}', '{{ addslashes($u->password_plain) }}')" class="w-8 h-8 inline-flex items-center justify-center bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-lg transition" title="Edit" aria-label="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" class="inline-flex">
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
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Edit User</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-5 space-y-3">
            @csrf @method('PUT')
            <input type="text" id="edit_nama" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <input type="text" id="edit_username" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
            <div class="relative">
                <input type="password" id="edit_password" name="password" class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-xl text-sm">
                <button type="button" onclick="toggleEditPassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600" title="Tampilkan password" aria-label="Tampilkan password">
                    <i id="edit_password_icon" class="fa-solid fa-eye"></i>
                </button>
            </div>
            <select id="edit_role" name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <option value="admin">Admin</option>
                <option value="bendahara">Bendahara</option>
                <option value="wali_kelas">Wali Kelas</option>
                <option value="siswa">Siswa</option>
            </select>
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm">Update</button>
        </form>
    </div>
</div>

<script>
function toggleNis(roleSel, wrapId) {
    document.getElementById(wrapId).style.display = roleSel.value === 'siswa' ? '' : 'none';
}

const addRole = document.getElementById('add_role');
const addNis = document.getElementById('add_nis');
addRole.addEventListener('change', () => {
    toggleNis(addRole, 'add_nis_wrap');
    addNis.required = addRole.value === 'siswa';
});
toggleNis(addRole, 'add_nis_wrap');
addNis.required = addRole.value === 'siswa';

const addNama = document.getElementById('add_nama');
const addUsername = document.getElementById('add_username');
let usernameTouched = false;
addUsername.addEventListener('input', () => { usernameTouched = true; });
addNama.addEventListener('input', () => {
    if (!usernameTouched) addUsername.value = addNama.value.toLowerCase().replace(/[^a-z0-9]/g, '');
});

function editUser(id, nama, username, role, password) {
    document.getElementById('editForm').action = '/admin/users/' + id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_password').value = password || '';
    document.getElementById('edit_password').type = 'password';
    document.getElementById('edit_password_icon').className = 'fa-solid fa-eye';
    document.getElementById('editModal').classList.remove('hidden');
}

function toggleEditPassword() {
    const input = document.getElementById('edit_password');
    const icon = document.getElementById('edit_password_icon');
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    icon.className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
}
</script>
@endsection
