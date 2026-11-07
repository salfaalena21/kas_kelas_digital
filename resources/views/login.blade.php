<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartKas Manajemen Kas Kelas</title>
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-6 sm:p-8 space-y-6">
        <!-- Header -->
        <div class="text-center">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                Sistem Kas Auditable & Terbuka
            </span>
            <h1 class="text-2xl font-bold text-gray-800 mt-3">Selamat Datang di SmartKas</h1>
            <p class="text-sm text-gray-500 mt-1">Portal Terintegrasi Siswa, Bendahara & Wali Kelas</p>
        </div>

        <!-- Pesan Notifikasi Error -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login (sesuai ERD: username + password) -->
        <form action="{{ url('/login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" id="username" name="username" required placeholder="Contoh: bendahara1, siswa1"
                       value="{{ old('username') }}"
                       class="w-full h-12 px-4 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                <input type="password" id="password" name="password" required
                       class="w-full h-12 px-4 text-gray-900 placeholder-gray-400 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                       placeholder="Masukkan kata sandi">
                <div class="mt-2 flex justify-end">
                    <button type="button" id="togglePassword"
                            aria-label="Tampilkan sandi"
                            class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 hover:text-blue-700 focus:outline-none">
                        <i class="fa-solid fa-eye"></i><span>Tampilkan sandi</span>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold h-12 rounded-xl transition duration-200 shadow-md">
                Masuk ke System
            </button>
        </form>

        <div class="text-center text-xs text-gray-400 pt-4 border-t border-gray-100">
            SmartKas &copy; XII PPLG - SMKN 1 TA
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const show = passwordInput.type === 'password';
                passwordInput.type = show ? 'text' : 'password';
                const icon = this.querySelector('i');
                const label = this.querySelector('span');
                icon.classList.toggle('fa-eye', !show);
                icon.classList.toggle('fa-eye-slash', show);
                label.textContent = show ? 'Sembunyikan sandi' : 'Tampilkan sandi';
                this.setAttribute('aria-label', show ? 'Sembunyikan sandi' : 'Tampilkan sandi');
            });
        }
    </script>
</body>
</html>
