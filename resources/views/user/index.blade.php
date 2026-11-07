@extends('layouts.app')

@section('title', 'Data User')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fa-solid fa-users-gear text-blue-600"></i> Data User
        </h1>
        <p class="text-sm text-gray-500">Kelola akun login: Siswa, Bendahara, &amp; Wali Kelas • {{ $users->count() }} akun terdaftar</p>
    </div>
</div>

<div class="grid grid-cols-1 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar User</h2>
        <div class="overflow-auto max-h-[560px] border border-gray-100 rounded-xl">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="text-gray-700 uppercase text-xs font-semibold sticky top-0 z-10">
                    <tr><th class="p-3 bg-gray-50">No</th><th class="p-3 bg-gray-50">Nama</th><th class="p-3 bg-gray-50">Username</th><th class="p-3 bg-gray-50">Peran</th><th class="p-3 bg-gray-50">NIS</th><th class="p-3 bg-gray-50">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $i => $u)
                        @php
                            $warna = match($u->role) {
                                'admin' => 'bg-purple-100 text-purple-700',
                                'bendahara' => 'bg-indigo-100 text-indigo-700',
                                'wali_kelas' => 'bg-blue-100 text-blue-700',
                                default => 'bg-green-100 text-green-700',
                            };
                            $label = match($u->role) {
                                'admin' => 'Admin',
                                'bendahara' => 'Bendahara',
                                'wali_kelas' => 'Wali Kelas',
                                default => 'Siswa',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $i+1 }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $u->nama }}</td>
                            <td class="p-3 text-xs text-gray-500">{{ $u->username }}</td>
                            <td class="p-3"><span class="{{ $warna }} text-xs px-2.5 py-1 rounded-full font-semibold">{{ $label }}</span></td>
                            <td class="p-3 text-gray-500">{{ $u->siswa->nis ?? '-' }}</td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openEditUser(this)"
                                            data-id="{{ $u->id_user }}"
                                            data-nama="{{ $u->nama }}"
                                            data-username="{{ $u->username }}"
                                            data-role="{{ $u->role }}"
                                            data-nis="{{ $u->siswa->nis ?? '' }}"
                                            class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-semibold rounded-lg transition"
                                            title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('bendahara.user.destroy', $u->id_user) }}" method="POST"
                                          onsubmit="return confirm('Hapus user {{ $u->nama }}? Data siswa & riwayat pembayarannya ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-4 text-center text-gray-400">Belum ada data user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit User
            </h2>
            <button type="button" onclick="closeEditUser()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editUserForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas ?? '' }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Peran (Role)</label>
                <select name="role" id="edit_role" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="admin">Admin</option>
                    <option value="siswa">Siswa</option>
                    <option value="bendahara">Bendahara</option>
                    <option value="wali_kelas">Wali Kelas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" id="edit_nama" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="edit_username" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="edit_nis_wrap">
                <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                <input type="text" name="nis" id="edit_nis" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEditUser()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl text-sm transition">Batal</button>
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
    function toggleNis(roleSel, wrapId) {
        document.getElementById(wrapId).style.display = roleSel.value === 'siswa' ? '' : 'none';
    }

    // Form edit
    const editUserModal = document.getElementById('editUserModal');
    const editUserForm = document.getElementById('editUserForm');
    const editRole = document.getElementById('edit_role');
    editRole.addEventListener('change', () => toggleNis(editRole, 'edit_nis_wrap'));

    function openEditUser(btn) {
        editUserForm.action = "{{ url('bendahara/user') }}/" + btn.dataset.id;
        document.getElementById('edit_nama').value = btn.dataset.nama;
        document.getElementById('edit_username').value = btn.dataset.username;
        document.getElementById('edit_role').value = btn.dataset.role;
        document.getElementById('edit_nis').value = btn.dataset.nis || '';
        toggleNis(editRole, 'edit_nis_wrap');
        editUserModal.classList.remove('hidden');
    }

    function closeEditUser() {
        editUserModal.classList.add('hidden');
    }

    editUserModal.addEventListener('click', (e) => {
        if (e.target === editUserModal) closeEditUser();
    });
</script>
@endpush
