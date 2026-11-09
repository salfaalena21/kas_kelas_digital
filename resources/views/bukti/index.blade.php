@extends('layouts.app')

@section('title', 'Bukti Pembayaran')

@section('content')
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-file-circle-check mr-1"></i> Bendahara</span>
        <h1 class="text-2xl font-bold mb-1">Bukti Pembayaran</h1>
        <p class="text-blue-100 text-sm">Verifikasi bukti transfer / nota pembayaran siswa</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-file-circle-check"></i></div>
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
                        @php
                            $fileUrl = url('/bukti-file/' . ltrim(preg_replace('#^/?storage/#', '', $b->file_path), '/'));
                            $fileSrc = !empty($b->file_data) ? $b->file_data : $fileUrl;
                            $isImgThumb = \Illuminate\Support\Str::of($b->nama_file)->lower()->endsWith(['.jpg','.jpeg','.png','.gif','.webp','.bmp']) || \Illuminate\Support\Str::startsWith((string)$b->file_data, 'data:image');
                            $isPdfThumb = \Illuminate\Support\Str::of($b->nama_file)->lower()->endsWith(['.pdf']) || \Illuminate\Support\Str::startsWith((string)$b->file_data, 'data:application/pdf');
                        @endphp
                        <td class="p-3 text-xs">
                            @if($isImgThumb)
                                <span class="block mb-1">
                                    <img src="{{ $fileSrc }}" alt="bukti" class="h-14 w-20 object-cover rounded-lg border border-gray-200 bg-white" loading="lazy" onerror="this.style.display='none'">
                                </span>
                            @elseif($isPdfThumb)
                                <span class="inline-flex items-center gap-1 mb-1 text-[11px] bg-red-50 text-red-700 px-2 py-1 rounded-lg border border-red-100"><i class="fa-solid fa-file-pdf"></i> PDF</span>
                            @endif
                            <a href="{{ $fileSrc }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline font-semibold">{{ $b->nama_file }}</a>
                        </td>
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
                                        data-file="{{ $b->nama_file }}"
                                        data-path="{{ !empty($b->file_data) ? $b->file_data : url('/bukti-file/' . ltrim(preg_replace('#^/?storage/#', '', $b->file_path), '/')) }}"
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
                <p class="text-xs text-gray-400 font-medium">Tanggal Bayar</p>
                <p id="lihat_tanggal" class="font-semibold text-gray-800"></p>
            </div>
        </div>

        <div class="mb-2 flex items-center justify-between gap-2">
            <p class="text-sm font-medium text-gray-700 truncate"><span id="lihat_file"></span></p>
            <div class="flex items-center gap-2 shrink-0">
                <a id="lihat_open_link" href="#" target="_blank" rel="noopener" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded-lg">Buka tab baru</a>
                <span id="lihat_status"></span>
            </div>
        </div>
        <div class="bg-gray-100 rounded-xl overflow-hidden mb-4 flex items-center justify-center min-h-[220px]">
            <img id="lihat_img" src="" alt="Bukti transfer" class="max-h-[65vh] w-full object-contain">
            <iframe id="lihat_pdf" src="" class="w-full hidden" style="height:65vh;" title="Bukti PDF"></iframe>
            <div id="lihat_img_fallback" style="display:none;" class="flex flex-col items-center text-gray-600 text-sm p-8 text-center gap-2">
                <i class="fa-solid fa-image text-3xl mb-1 text-gray-400"></i>
                <p id="lihat_fallback_msg" class="font-medium">Gambar belum bisa ditampilkan.</p>
                <p class="text-xs text-gray-500">Kemungkinan file bukti ini diupload sebelum perbaikan penyimpanan, jadi filenya tidak ada di server. Minta siswa <b>upload ulang</b> bukti (setelah pembaruan ini), lalu cek lagi.</p>
                <a id="lihat_open_link2" href="#" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg mt-1">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Coba buka file
                </a>
                <span class="text-[11px] text-gray-400 break-all">URL: <span id="lihat_path"></span></span>
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
        document.getElementById('lihat_file').textContent = btn.dataset.file;
        document.getElementById('lihat_path').textContent = btn.dataset.path;
        document.getElementById('lihat_open_link').href = btn.dataset.path;
        var ol2 = document.getElementById('lihat_open_link2'); if (ol2) ol2.href = btn.dataset.path;

        const statusEl = document.getElementById('lihat_status');
        const st = btn.dataset.status;
        let cls = 'bg-amber-100 text-amber-700', label = 'Pending';
        if (st === 'verified') { cls = 'bg-emerald-100 text-emerald-700'; label = 'Terverifikasi'; }
        if (st === 'rejected') { cls = 'bg-red-100 text-red-700'; label = 'Ditolak'; }
        statusEl.className = cls + ' text-xs px-2.5 py-1 rounded-full font-semibold';
        statusEl.textContent = label;

        const img = document.getElementById('lihat_img');
        const pdf = document.getElementById('lihat_pdf');
        const fb = document.getElementById('lihat_img_fallback');
        const file = (btn.dataset.file || '').toLowerCase();
        const path = btn.dataset.path || '';
        const isPdf = file.endsWith('.pdf');
        const isImg = /\.(jpe?g|png|gif|webp|bmp)$/i.test(file);

        img.onerror = null; img.onload = null;
        img.classList.add('hidden'); img.removeAttribute('src');
        pdf.classList.add('hidden'); pdf.removeAttribute('src');
        fb.style.display = 'none';

        if (isPdf) {
            pdf.src = path;
            pdf.classList.remove('hidden');
        } else {
            // tampilkan langsung sebagai gambar (default), termasuk jika ekstensi tidak terdeteksi
            img.onload = function () { fb.style.display = 'none'; img.classList.remove('hidden'); };
            img.onerror = function () {
                img.classList.add('hidden');
                fb.style.display = 'flex';
            };
            img.src = path;
            img.classList.remove('hidden');
        }

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
