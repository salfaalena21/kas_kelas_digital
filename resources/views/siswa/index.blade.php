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
                    <tr><th class="p-3 bg-gray-50">No</th><th class="p-3 bg-gray-50">NIS</th><th class="p-3 bg-gray-50">Nama</th><th class="p-3 bg-gray-50">Akun</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswa as $i => $s)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $i+1 }}</td>
                            <td class="p-3 font-medium">{{ $s->nis }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $s->nama }}</td>
                            <td class="p-3 text-xs text-gray-500">{{ $s->user->username ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-400">Belum ada data siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- Username otomatis mengikuti Nama (form tambah) ---
    const tambahNama = document.getElementById('tambah_nama');
    const tambahUsername = document.getElementById('tambah_username');
    let usernameManual = false;

    if (tambahNama && tambahUsername) {
        tambahUsername.addEventListener('input', () => { usernameManual = true; });
        tambahNama.addEventListener('input', () => {
            if (usernameManual) return;
            tambahUsername.value = tambahNama.value.toLowerCase().replace(/[^a-z0-9]/g, '');
        });
    }
</script>
@endpush
