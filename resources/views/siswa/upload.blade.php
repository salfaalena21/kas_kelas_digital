@extends('layouts.app')

@section('title', 'Upload Bukti Transfer')

@section('content')
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-slate-900 p-6 sm:p-8 rounded-2xl text-white shadow-sm flex items-center justify-between relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="bg-white/10 text-white text-xs px-3 py-1 rounded-full font-medium mb-3 inline-block"><i class="fa-solid fa-cloud-arrow-up mr-1"></i> Siswa</span>
        <h1 class="text-2xl font-bold mb-1">Upload Bukti Transfer</h1>
        <p class="text-blue-100 text-sm">{{ $siswaSaya->nama ?? '' }} ({{ $siswaSaya->nis ?? '' }}) &middot; Lampirkan bukti untuk pembayaran transfermu</p>
    </div>
    <div class="hidden md:block opacity-20 text-7xl pr-4"><i class="fa-solid fa-cloud-arrow-up"></i></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Upload -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-blue-600"></i> Upload Baru
        </h2>
        <form action="{{ route('siswa.upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <select name="metode" required class="w-full px-3 py-2 border {{ $errors->has('metode') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih metode --</option>
                    <option value="tunai" {{ old('metode') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="transfer" {{ old('metode') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
                @error('metode')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Bukti (JPG/PNG/PDF, maks 2MB)</label>
                <input type="file" name="proof_file" accept="image/jpeg,image/png,image/jpg,application/pdf" required
                       class="w-full px-3 py-2 border {{ $errors->has('proof_file') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-sm bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold focus:outline-none">
                @error('proof_file')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl text-sm transition">
                <i class="fa-solid fa-upload me-1"></i> Upload Bukti
            </button>
        </form>
    </div>

    <!-- Riwayat Upload -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Bukti yang Sudah Diupload</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-3 rounded-l-xl">No</th>
                        <th class="p-3">File</th>
                        <th class="p-3">Metode</th>
                        <th class="p-3 rounded-r-xl">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($buktiSaya as $i => $b)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">{{ $i + 1 }}</td>
                            <td class="p-3">
                                <a href="{{ asset($b->file_path) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-semibold">
                                    <i class="fa-solid fa-image me-1"></i>{{ $b->nama_file }}
                                </a>
                            </td>
                            <td class="p-3 capitalize">{{ $b->pembayaran->metode ?? '-' }}</td>
                            <td class="p-3">
                                @if($b->status_verifikasi === 'verified')
                                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">Terverifikasi</span>
                                @elseif($b->status_verifikasi === 'rejected')
                                    <span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-semibold">Ditolak</span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-semibold">Menunggu Verifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-400">Belum ada bukti yang diupload.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
