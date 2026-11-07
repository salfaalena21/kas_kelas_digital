<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['username' => 'admin'],
            ['nama' => 'Administrator', 'password' => Hash::make('admin123'), 'password_hint' => Crypt::encryptString('admin123'), 'role' => 'admin']
        );

        // Bendahara (petugas pencatat sesuai ERD)
        User::firstOrCreate(
            ['username' => 'bendahara1'],
            ['nama' => 'Siti Andini', 'password' => Hash::make('bendahara1'), 'password_hint' => Crypt::encryptString('bendahara1'), 'role' => 'bendahara']
        );

        // Wali Kelas
        User::firstOrCreate(
            ['username' => 'walikelas1'],
            ['nama' => 'Pak Baban', 'password' => Hash::make('walikelas1'), 'password_hint' => Crypt::encryptString('walikelas1'), 'role' => 'wali_kelas']
        );

        // 36 akun siswa (siswa1 - siswa36)
        foreach (self::daftarSiswa() as $s) {
            User::firstOrCreate(
                ['username' => $s['username']],
                ['nama' => $s['nama'], 'password' => Hash::make($s['username']), 'password_hint' => Crypt::encryptString($s['username']), 'role' => 'siswa']
            );
        }
    }

    public static function daftarSiswa(): array
    {
        // Daftar 36 siswa; diurutkan alfabetis lalu diberi username siswa1..siswa36
        $nama = [
            'Salfa Alena', 'Dimas Prasetyo', 'Anggi Pratama', 'Budi Santoso',
            'Citra Ayu', 'Dewi Lestari', 'Eko Saputra', 'Fitri Handayani',
            'Gita Permata', 'Hadi Gunawan', 'Intan Sari', 'Joko Susilo',
            'Kirana Dewi', 'Lutfi Hakim', 'Maya Putri', 'Nanda Pratama',
            'Oktavia Rahma', 'Putri Ayunda', 'Qoriatul Hasanah', 'Rizky Ramadhan',
            'Salsa Bila', 'Taufik Hidayat', 'Utami Wulandari', 'Vina Amelia',
            'Wahyu Firmansyah', 'Yulia Anggraini', 'Yoga Saputra', 'Zahra Nabila',
            'Fajar Nugroho', 'Galih Permana', 'Hana Marsya', 'Ilham Maulana',
            'Aldi Renaldi', 'Bella Safitri', 'Candra Wijaya', 'Dian Puspita',
        ];

        usort($nama, fn($a, $b) => strcasecmp($a, $b));

        $daftar = [];
        foreach ($nama as $i => $n) {
            $daftar[] = ['nama' => $n, 'username' => 'siswa' . ($i + 1)];
        }

        return $daftar;
    }
}
