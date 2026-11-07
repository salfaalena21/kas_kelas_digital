@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fa-solid fa-users text-blue-600"></i> Data Siswa
        </h1>
        <p class="text-sm text-gray-500">Kelas {{ $kelas->nama_kelas ?? '' }} • {{ $siswa->count() }} siswa terdaftar</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-user-plus text-blue-600"></i> Tambah Siswa</h2>
        <form action="{{ route('bendahara.siswa.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas ?? $semuaKelas->first()?->id_kelas }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                <input type="text" name="nis" required placeholder="Contoh: 20141001" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" id="tambah_nama" required placeholder="Contoh: Budi Santoso" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Akun Login (Username)</label>
                <input type="text" name="username" id="tambah_username" placeholder="Otomatis dari nama..." autocomplete="off"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Muncul otomatis mengikuti Nama. Kosongkan bila siswa tidak diberi akun.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Akun (min. 6)</label>
                <input type="text" name="password" id="tambah_password" placeholder="Contoh: password123" autocomplete="off"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Wajib diisi bila Username diisi.</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl text-sm transition">Simpan Siswa</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Siswa</h2>
        <div class="overflow-auto max-h-[560px] border border-gray-100 rounded-xl">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="text-gray-700 uppercase text-xs font-semibold sticky top-0 z-10">
                    <tr><th class="p-3 bg-gray-50">No</th><th class="p-3 bg-gray-50">NIS</th><th class="p-3 bg-gray-50">Nama</th><th class="p-3 bg-gray-50">Akun</th><th class="p-3 bg-gray-50">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswa as $i => $s)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $i+1 }}</td>
                            <td class="p-3 font-medium">{{ $s->nis }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $s->nama }}</td>
                            <td class="p-3 text-xs text-gray-500">{{ $s->user->username ?? '-' }}</td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openEditSiswa(this)"
                                            data-id="{{ $s->id_siswa }}"
                                            data-nis="{{ $s->nis }}"
                                            data-nama="{{ $s->nama }}"
                                            data-id-user="{{ $s->id_user ?? '' }}"
                                            data-akun-label="{{ $s->user ? $s->user->nama . ' (' . $s->user->username . ')' : '' }}"
                                            class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-semibold rounded-lg transition"
                                            title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('bendahara.siswa.destroy', $s->id_siswa) }}" method="POST"
                                          onsubmit="return confirm('Hapus siswa {{ $s->nama }}? Riwayat pembayarannya ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-4 text-center text-gray-400">Belum ada data siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div id="editSiswaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Siswa
            </h2>
            <button type="button" onclick="closeEditSiswa()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editSiswaForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                <input type="text" name="nis" id="edit_siswa_nis" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" id="edit_siswa_nama" required class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Akun User (Opsional, ketik untuk mencari)</label>
                <input type="text" id="edit_akun_search" placeholder="Ketik username / nama..." autocomplete="off"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="hidden" name="id_user" id="edit_id_user">
                <div id="edit_akun_dropdown" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl max-h-48 overflow-y-auto shadow-lg">
                    @foreach($usersSiswa as $u)
                        <button type="button" data-id="{{ $u->id_user }}" data-label="{{ $u->nama }} ({{ $u->username }})"
                                class="edit-akun-option w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50">
                            <span class="font-medium text-gray-900">{{ $u->nama }}</span>
                            <span class="text-xs text-gray-400">({{ $u->username }})</span>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEditSiswa()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl text-sm transition">Batal</button>
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
    const editSiswaModal = document.getElementById('editSiswaModal');
    const editSiswaForm = document.getElementById('editSiswaForm');

    function openEditSiswa(btn) {
        editSiswaForm.action = "{{ url('bendahara/siswa') }}/" + btn.dataset.id;
        document.getElementById('edit_siswa_nis').value = btn.dataset.nis;
        document.getElementById('edit_siswa_nama').value = btn.dataset.nama;
        document.getElementById('edit_id_user').value = btn.dataset.idUser || '';
        document.getElementById('edit_akun_search').value = btn.dataset.akunLabel || '';
        editSiswaModal.classList.remove('hidden');
    }

    function closeEditSiswa() {
        editSiswaModal.classList.add('hidden');
    }

    editSiswaModal.addEventListener('click', (e) => {
        if (e.target === editSiswaModal) closeEditSiswa();
    });

    // --- Username otomatis mengikuti Nama (form tambah) ---
    const tambahNama = document.getElementById('tambah_nama');
    const tambahUsername = document.getElementById('tambah_username');
    let usernameManual = false;

    tambahUsername.addEventListener('input', () => { usernameManual = true; });
    tambahNama.addEventListener('input', () => {
        if (usernameManual) return;
        tambahUsername.value = tambahNama.value.toLowerCase().replace(/[^a-z0-9]/g, '');
    });

    // --- Combobox Akun (modal edit) ---
    const editAkunSearch = document.getElementById('edit_akun_search');
    const editAkunDropdown = document.getElementById('edit_akun_dropdown');
    const editIdUser = document.getElementById('edit_id_user');

    function filterEditAkun() {
        const q = editAkunSearch.value.toLowerCase();
        document.querySelectorAll('.edit-akun-option').forEach(opt => {
            opt.style.display = opt.dataset.label.toLowerCase().includes(q) ? '' : 'none';
        });
        editAkunDropdown.classList.remove('hidden');
        if (editAkunSearch.value === '') editIdUser.value = '';
    }

    editAkunSearch.addEventListener('focus', filterEditAkun);
    editAkunSearch.addEventListener('input', filterEditAkun);

    document.querySelectorAll('.edit-akun-option').forEach(opt => {
        opt.addEventListener('click', () => {
            editIdUser.value = opt.dataset.id;
            editAkunSearch.value = opt.dataset.label;
            editAkunDropdown.classList.add('hidden');
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#edit_akun_search') && !e.target.closest('#edit_akun_dropdown')) {
            editAkunDropdown.classList.add('hidden');
        }
    });
</script>
@endpush
