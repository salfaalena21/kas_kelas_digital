@extends('layouts.app')

@section('title', 'Data Pembayaran Kas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fa-solid fa-hand-holding-dollar text-blue-600"></i> Data Kas Masuk / Pembayaran
        </h1>
        <p class="text-sm text-gray-500">Kelola pembayaran siswa berdasarkan master iuran (sesuai ERD)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Pembayaran -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-blue-600"></i> Tambah Pembayaran
        </h2>
        <form action="{{ route('bendahara.pembayaran.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Siswa (ketik untuk mencari)</label>
                <input type="text" id="siswa_search" placeholder="Ketik nama / NIS..." autocomplete="off"
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <input type="hidden" name="id_siswa" id="id_siswa">
                <div id="siswa_dropdown" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl max-h-48 overflow-y-auto shadow-lg">
                    @foreach($siswa as $s)
                        <button type="button" data-id="{{ $s->id_siswa }}" data-label="{{ $s->nama }} ({{ $s->nis }})"
                                class="siswa-option w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50">
                            <span class="font-medium text-gray-900">{{ $s->nama }}</span>
                            <span class="text-xs text-gray-400">({{ $s->nis }})</span>
                        </button>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tagihan Iuran</label>
                <select name="id_iuran" id="id_iuran" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="" data-nominal="0">-- Pilih Iuran --</option>
                    @foreach($iuran as $i)
                        <option value="{{ $i->id_iuran }}" data-nominal="{{ $i->nominal }}">{{ $i->periode }} - {{ $i->minggu }} (Rp {{ number_format($i->nominal, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Bayar (Rp)</label>
                <input type="number" name="nominal_bayar" id="nominal_bayar" min="0" placeholder="Contoh: 10000" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kembalian (Rp)</label>
                <input type="number" name="kembalian" id="kembalian" min="0" value="0" readonly
                       class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:outline-none"
                       title="Otomatis: Nominal Bayar - Nominal Iuran">
                <p class="text-xs text-gray-400 mt-1">Otomatis terisi: Nominal Bayar − Nominal Iuran</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                    <select name="metode" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-white">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl text-sm transition">
                <i class="fa-solid fa-save me-1"></i> Simpan Pembayaran
            </button>
        </form>
    </div>

    <!-- Tabel (scroll di dalam, header tetap) -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Pembayaran</h2>
        <div class="overflow-auto max-h-[560px] border border-gray-100 rounded-xl">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="text-gray-700 uppercase text-xs font-semibold sticky top-0 z-10">
                    <tr>
                        <th class="p-3 bg-gray-50">No</th>
                        <th class="p-3 bg-gray-50">Siswa</th>
                        <th class="p-3 bg-gray-50">Iuran</th>
                        <th class="p-3 bg-gray-50">Bayar</th>
                        <th class="p-3 bg-gray-50">Kembalian</th>
                        <th class="p-3 bg-gray-50">Tanggal</th>
                        <th class="p-3 bg-gray-50">Status</th>
                        <th class="p-3 bg-gray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pembayaran as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium text-gray-900">{{ $item->siswa->nama ?? '-' }}<br><span class="text-xs text-gray-400">{{ $item->siswa->nis ?? '' }}</span></td>
                            <td class="p-3">{{ $item->iuran->periode ?? '-' }} {{ $item->iuran->minggu ?? '' }}</td>
                            <td class="p-3 text-green-600 font-semibold">Rp {{ number_format($item->nominal_bayar, 0, ',', '.') }}</td>
                            <td class="p-3">Rp {{ number_format($item->kembalian, 0, ',', '.') }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}</td>
                            <td class="p-3"><span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold">{{ ucfirst($item->status) }}</span></td>
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openEditModal(this)"
                                            data-id="{{ $item->id_pembayaran }}"
                                            data-id-siswa="{{ $item->id_siswa }}"
                                            data-id-iuran="{{ $item->id_iuran }}"
                                            data-nominal="{{ $item->nominal_bayar }}"
                                            data-kembalian="{{ $item->kembalian }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('Y-m-d') }}"
                                            data-metode="{{ $item->metode }}"
                                            data-status="{{ $item->status }}"
                                            class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-semibold rounded-lg transition"
                                            title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('bendahara.pembayaran.destroy', $item->id_pembayaran) }}" method="POST"
                                          onsubmit="return confirm('Hapus data pembayaran {{ $item->siswa->nama ?? '' }} ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-4 text-center text-gray-400">Belum ada data pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Pembayaran -->
<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Pembayaran
            </h2>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Siswa</label>
                <select name="id_siswa" id="edit_id_siswa" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id_siswa }}">{{ $s->nama }} ({{ $s->nis }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tagihan Iuran</label>
                <select name="id_iuran" id="edit_id_iuran" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @foreach($iuran as $i)
                        <option value="{{ $i->id_iuran }}" data-nominal="{{ $i->nominal }}">{{ $i->periode }} - {{ $i->minggu }} (Rp {{ number_format($i->nominal, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Bayar (Rp)</label>
                    <input type="number" name="nominal_bayar" id="edit_nominal_bayar" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kembalian (Rp)</label>
                    <input type="number" name="kembalian" id="edit_kembalian" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:outline-none" readonly>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal_bayar" id="edit_tanggal_bayar" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                    <select name="metode" id="edit_metode" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-white">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="edit_status" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-white">
                    <option value="lunas">Lunas</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl text-sm transition">Batal</button>
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
    // --- Dropdown siswa searchable (ketik + tetap dropdown) ---
    const siswaSearch = document.getElementById('siswa_search');
    const siswaDropdown = document.getElementById('siswa_dropdown');
    const idSiswaInput = document.getElementById('id_siswa');
    const siswaOptions = document.querySelectorAll('.siswa-option');

    function filterSiswa() {
        const q = siswaSearch.value.toLowerCase();
        let visible = 0;
        siswaOptions.forEach(opt => {
            const match = opt.dataset.label.toLowerCase().includes(q);
            opt.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        siswaDropdown.classList.remove('hidden');
        if (siswaSearch.value === '') idSiswaInput.value = '';
    }

    siswaSearch.addEventListener('focus', filterSiswa);
    siswaSearch.addEventListener('input', filterSiswa);

    siswaOptions.forEach(opt => {
        opt.addEventListener('click', () => {
            idSiswaInput.value = opt.dataset.id;
            siswaSearch.value = opt.dataset.label;
            siswaDropdown.classList.add('hidden');
            hitungKembalian();
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#siswa_search') && !e.target.closest('#siswa_dropdown')) {
            siswaDropdown.classList.add('hidden');
        }
    });

    // --- Kembalian otomatis: Nominal Bayar - Nominal Iuran ---
    const iuranSelect = document.getElementById('id_iuran');
    const nominalInput = document.getElementById('nominal_bayar');
    const kembalianInput = document.getElementById('kembalian');

    function hitungKembalian() {
        const nominalIuran = parseFloat(iuranSelect.selectedOptions[0]?.dataset.nominal || 0);
        const bayar = parseFloat(nominalInput.value || 0);
        kembalianInput.value = Math.max(0, bayar - nominalIuran);
    }

    iuranSelect.addEventListener('change', hitungKembalian);
    nominalInput.addEventListener('input', hitungKembalian);

    // --- Modal Edit ---
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editIuran = document.getElementById('edit_id_iuran');
    const editNominal = document.getElementById('edit_nominal_bayar');
    const editKembalian = document.getElementById('edit_kembalian');

    function hitungKembalianEdit() {
        const nominalIuran = parseFloat(editIuran.selectedOptions[0]?.dataset.nominal || 0);
        const bayar = parseFloat(editNominal.value || 0);
        editKembalian.value = Math.max(0, bayar - nominalIuran);
    }

    editIuran.addEventListener('change', hitungKembalianEdit);
    editNominal.addEventListener('input', hitungKembalianEdit);

    function openEditModal(btn) {
        editForm.action = "{{ url('bendahara/pembayaran') }}/" + btn.dataset.id;
        document.getElementById('edit_id_siswa').value = btn.dataset.idSiswa;
        editIuran.value = btn.dataset.idIuran;
        editNominal.value = btn.dataset.nominal;
        editKembalian.value = btn.dataset.kembalian;
        document.getElementById('edit_tanggal_bayar').value = btn.dataset.tanggal;
        document.getElementById('edit_metode').value = btn.dataset.metode || 'tunai';
        document.getElementById('edit_status').value = btn.dataset.status || 'lunas';
        editModal.classList.remove('hidden');
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
    }

    editModal.addEventListener('click', (e) => {
        if (e.target === editModal) closeEditModal();
    });
</script>
@endpush
