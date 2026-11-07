@extends('layouts.app')

@section('title', 'Bukti Pembayaran')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fa-solid fa-file-circle-check text-blue-600"></i> Bukti Pembayaran
    </h1>
    <p class="text-sm text-gray-500">Verifikasi bukti transfer / nota pembayaran siswa</p>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr><th class="p-3 rounded-l-xl">No</th><th class="p-3">Siswa</th><th class="p-3">Tagihan</th><th class="p-3">File</th><th class="p-3">Status Verifikasi</th><th class="p-3 rounded-r-xl">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bukti as $i => $b)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $i+1 }}</td>
                        <td class="p-3 font-medium text-gray-900">{{ $b->pembayaran->siswa->nama ?? '-' }}</td>
                        <td class="p-3">{{ $b->pembayaran->iuran->periode ?? '-' }} {{ $b->pembayaran->iuran->minggu ?? '' }}</td>
                        <td class="p-3 text-xs">{{ $b->nama_file }}<br><span class="text-gray-400">{{ $b->file_path }}</span></td>
                        <td class="p-3">
                            @if($b->status_verifikasi === 'verified')
                                <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">Terverifikasi</span>
                            @elseif($b->status_verifikasi === 'rejected')
                                <span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-semibold">Ditolak</span>
                            @else
                                <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-semibold">Pending</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" onclick="openLihatModal(this)"
                                        data-siswa="{{ $b->pembayaran->siswa->nama ?? '-' }} ({{ $b->pembayaran->siswa->nis ?? '' }})"
                                        data-tagihan="{{ $b->pembayaran->iuran->periode ?? '-' }} {{ $b->pembayaran->iuran->minggu ?? '' }}"
                                        data-bayar="Rp {{ number_format($b->pembayaran->nominal_bayar ?? 0, 0, ',', '.') }}"
                                        data-tanggal="{{ $b->pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($b->pembayaran->tanggal_bayar)->format('d/m/Y') : '-' }}"
                                        data-metode="{{ ucfirst($b->pembayaran->metode ?? '-') }}"
                                        data-file="{{ $b->nama_file }}"
                                        data-path="{{ $b->file_path }}"
                                        data-status="{{ $b->status_verifikasi }}"
                                        data-id="{{ $b->id_bukti }}"
                                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-lg transition"
                                        title="Lihat Bukti">
                                    <i class="fa-solid fa-eye me-1"></i>Lihat
                                </button>
                                <form action="{{ route('bendahara.bukti.verify', $b->id_bukti) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status_verifikasi" value="verified">
                                    <button class="text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded-lg transition">Verifikasi</button>
                                </form>
                                <form action="{{ route('bendahara.bukti.verify', $b->id_bukti) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status_verifikasi" value="rejected">
                                    <button class="text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-lg transition">Tolak</button>
                                </form>
                                <form action="{{ route('bendahara.bukti.destroy', $b->id_bukti) }}" method="POST"
                                      onsubmit="return confirm('Hapus bukti pembayaran ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-center text-gray-400">Belum ada bukti pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Lihat Bukti Transfer -->
<div id="lihatModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-image text-blue-600"></i> Bukti Transfer Pembayaran
            </h2>
            <button type="button" onclick="closeLihatModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-sm">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-medium">Siswa</p>
                <p id="lihat_siswa" class="font-semibold text-gray-800"></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-medium">Tagihan</p>
                <p id="lihat_tagihan" class="font-semibold text-gray-800"></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-medium">Nominal Bayar</p>
                <p id="lihat_bayar" class="font-semibold text-green-600"></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 font-medium">Tanggal / Metode</p>
                <p class="font-semibold text-gray-800"><span id="lihat_tanggal"></span> • <span id="lihat_metode"></span></p>
            </div>
        </div>

        <div class="mb-2 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700"><span id="lihat_file"></span></p>
            <span id="lihat_status"></span>
        </div>
        <div class="bg-gray-100 rounded-xl overflow-hidden mb-4 flex items-center justify-center min-h-[200px]">
            <img id="lihat_img" src="" alt="Bukti transfer" class="max-h-[60vh] w-full object-contain"
                 onerror="this.style.display='none';document.getElementById('lihat_img_fallback').style.display='flex';">
            <div id="lihat_img_fallback" style="display:none;" class="flex-col items-center text-gray-400 text-sm p-8 text-center">
                <i class="fa-solid fa-triangle-exclamation text-3xl mb-2"></i>
                <p>File gambar tidak ditemukan di server.<br><span class="text-xs">Path: <span id="lihat_path"></span></span></p>
            </div>
        </div>

        <div class="flex gap-3">
            <form id="lihatFormVerify" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="status_verifikasi" value="verified">
                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 rounded-xl text-sm transition">Verifikasi</button>
            </form>
            <form id="lihatFormReject" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="status_verifikasi" value="rejected">
                <button class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2.5 rounded-xl text-sm transition">Tolak</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const lihatModal = document.getElementById('lihatModal');

    function openLihatModal(btn) {
        document.getElementById('lihat_siswa').textContent = btn.dataset.siswa;
        document.getElementById('lihat_tagihan').textContent = btn.dataset.tagihan;
        document.getElementById('lihat_bayar').textContent = btn.dataset.bayar;
        document.getElementById('lihat_tanggal').textContent = btn.dataset.tanggal;
        document.getElementById('lihat_metode').textContent = btn.dataset.metode;
        document.getElementById('lihat_file').textContent = btn.dataset.file;
        document.getElementById('lihat_path').textContent = btn.dataset.path;

        const statusEl = document.getElementById('lihat_status');
        const st = btn.dataset.status;
        let cls = 'bg-amber-100 text-amber-700', label = 'Pending';
        if (st === 'verified') { cls = 'bg-emerald-100 text-emerald-700'; label = 'Terverifikasi'; }
        if (st === 'rejected') { cls = 'bg-red-100 text-red-700'; label = 'Ditolak'; }
        statusEl.className = cls + ' text-xs px-2.5 py-1 rounded-full font-semibold';
        statusEl.textContent = label;

        const img = document.getElementById('lihat_img');
        img.style.display = '';
        document.getElementById('lihat_img_fallback').style.display = 'none';
        img.src = btn.dataset.path;

        const verifyUrl = "{{ url('bendahara/bukti') }}/" + btn.dataset.id;
        document.getElementById('lihatFormVerify').action = verifyUrl;
        document.getElementById('lihatFormReject').action = verifyUrl;

        lihatModal.classList.remove('hidden');
    }

    function closeLihatModal() {
        lihatModal.classList.add('hidden');
    }

    lihatModal.addEventListener('click', (e) => {
        if (e.target === lihatModal) closeLihatModal();
    });
</script>
@endpush
