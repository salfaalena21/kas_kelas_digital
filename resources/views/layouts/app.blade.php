<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKas | @yield('title', 'Dashboard Kas Kelas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <!-- TOPBAR MOBILE -->
    <header class="fixed top-0 inset-x-0 h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:hidden z-30">
        <div class="flex items-center space-x-2">
            <div class="bg-blue-600 text-white p-2 rounded-xl text-lg font-bold">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="leading-none">
                <h1 class="font-bold text-base text-blue-600">SmartKas</h1>
                <span class="text-[10px] text-gray-400">MANAJEMEN KAS KELAS</span>
            </div>
        </div>
        <button id="sidebarToggle" type="button" aria-label="Buka menu"
                class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 transition">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </header>

    <!-- OVERLAY (mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden"></div>

    <!-- SIDEBAR MASTER (fixed, bagian bawah selalu menempel) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 flex flex-col p-4 z-50 transform transition-transform duration-300 -translate-x-full md:translate-x-0">
        <div class="shrink-0">
            <div class="flex items-center justify-between mb-6 px-2">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 text-white p-2 rounded-xl text-xl font-bold">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-blue-600 leading-none">SmartKas</h1>
                        <span class="text-xs text-gray-400">MANAJEMEN KAS KELAS</span>
                    </div>
                </div>
                <button id="sidebarClose" type="button" aria-label="Tutup menu"
                        class="md:hidden text-gray-400 hover:text-gray-600 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <nav class="space-y-1 pb-2">
                <p class="text-xs font-semibold text-gray-400 px-2 mb-2">UTAMA</p>

                @php $role = Auth::user()->role ?? 'siswa'; @endphp

                @if($role === 'admin')
                    <a href="{{ url('/admin/dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium {{ request()->is('admin/dashboard') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-gauge-high"></i><span>Dashboard Admin</span>
                    </a>
                    <a href="{{ url('/admin/users') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/users*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-users-gear"></i><span>Kelola Pengguna</span>
                    </a>
                    <a href="{{ url('/admin/siswa') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/siswa*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-user-graduate"></i><span>Data Siswa</span>
                    </a>
                    <a href="{{ url('/admin/iuran') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/iuran*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-coins"></i><span>Iuran</span>
                    </a>
                    <a href="{{ url('/admin/pengeluaran') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/pengeluaran*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-receipt"></i><span>Pengeluaran</span>
                    </a>
                    <a href="{{ url('/admin/laporan') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/laporan*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-file-lines"></i><span>Laporan Kas</span>
                    </a>
                @elseif($role === 'bendahara')
                    <a href="{{ route('bendahara.dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium {{ request()->is('bendahara/dashboard') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-chart-pie"></i><span>Dashboard Kas</span>
                    </a>
                    <a href="{{ route('bendahara.user.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('bendahara/user*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-users-gear"></i><span>Data User</span>
                    </a>
                    <a href="{{ route('bendahara.iuran.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('bendahara/iuran*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-coins"></i><span>Data Iuran</span>
                    </a>
                    <a href="{{ route('bendahara.pembayaran') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('bendahara/pembayaran*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-hand-holding-dollar"></i><span>Kas Masuk & Bayar</span>
                    </a>
                    <a href="{{ route('bendahara.pengeluaran') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('bendahara/pengeluaran*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-receipt"></i><span>Pengeluaran & Nota</span>
                    </a>
                    <a href="{{ route('bendahara.bukti.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('bendahara/bukti*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-file-circle-check"></i><span>Bukti Pembayaran</span>
                    </a>
                @elseif($role === 'siswa')
                    <a href="{{ route('siswa.dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium {{ request()->is('siswa/dashboard') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-chart-pie"></i><span>Dashboard Kelas</span>
                    </a>
                    <a href="{{ route('siswa.riwayat') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('siswa/riwayat*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-clock-rotate-left"></i><span>Riwayat Saya</span>
                    </a>
                    <a href="{{ route('siswa.upload') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('siswa/upload-bukti*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-cloud-arrow-up"></i><span>Upload Bukti Transfer</span>
                    </a>
                @elseif($role === 'wali_kelas')
                    <a href="{{ route('walikelas.dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium {{ request()->is('walikelas/dashboard*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-chart-pie"></i><span>Dashboard</span>
                    </a>
                    <a href="{{ route('walikelas.siswa') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('walikelas/siswa*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-users"></i><span>Data Siswa</span>
                    </a>
                    <a href="{{ route('walikelas.laporan') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl {{ request()->is('walikelas/laporan*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fa-solid fa-file-lines"></i><span>Laporan Kas</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="shrink-0 border-t border-gray-100 pt-3 mt-2 space-y-3">
            <div class="p-3 bg-blue-50 rounded-xl text-xs text-blue-700 font-medium">
                <i class="fa-solid fa-shield-halved mr-1"></i> Kas Terbuka & Auditable
            </div>
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(Auth::user()->nama ?? Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="truncate flex-1">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->nama ?? Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $role) }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium rounded-xl transition">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="md:ml-64 min-h-screen p-4 md:p-8 pt-20 md:pt-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const openBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeSidebar();
            });
            sidebar.querySelectorAll('a').forEach(function (a) {
                a.addEventListener('click', function () {
                    if (window.innerWidth < 768) closeSidebar();
                });
            });
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) overlay.classList.add('hidden');
            });
        })();
    </script>
</body>
</html>
