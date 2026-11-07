@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-gray-500 text-sm">Ringkasan sistem kas kelas</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total User</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUser }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Kelas</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalKelas }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Siswa</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSiswa }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Iuran</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalIuran }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Pemasukan</p>
        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Total Pengeluaran</p>
        <p class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <p class="text-sm text-gray-500">Saldo Kas</p>
        <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
    </div>
</div>
@endsection
