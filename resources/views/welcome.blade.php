<!DOCTYPE html><html lang="id"><head></head><body class="text-slate-800 antialiased min-h-screen"><svg class="inline-defs-container" aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden"></svg>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard Siswa Interaktif &amp; Grafik - SmartKas (Blue Edition)</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@600;700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            mono: ['"JetBrains Mono"', 'monospace']
          },
          colors: {
            brand: {
              50: '#eff6ff',
              100: '#dbeafe',
              200: '#bfdbfe',
              500: '#3b82f6',
              600: '#2563eb',
              700: '#1d4ed8',
              800: '#1e40af',
              900: '#1e3a8a'
            }
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 9999px; }
    .donut-seg { transition: stroke-width 0.2s ease, opacity 0.2s ease; cursor: pointer; }
    .donut-seg:hover { stroke-width: 19; opacity: 0.95; }
    .bar-col:hover .bar-in { fill: #1D4ED8; }
    .bar-col:hover .bar-out { fill: #0284C7; }
  </style>



  <!-- Left Sidebar (Fixed 64 / 256px) -->
  <aside class="fixed left-0 top-0 h-full w-64 bg-white border-r border-slate-200 z-50 flex flex-col shadow-sm">
    <!-- Brand Logo -->
    <div class="h-16 px-5 flex items-center cursor-pointer border-b border-slate-100" onclick="navigateSidebar('dashboard-kas')">
      <div class="flex items-center gap-2.5">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-sky-400 flex items-center justify-center text-white shadow-md shadow-blue-500/25">
          <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
        </div>
        <div>
          <div class="text-lg font-extrabold tracking-tight text-slate-900 leading-none">Smart<span class="text-blue-600">Kas</span></div>
          <div class="text-[9.5px] font-bold text-slate-400 tracking-wider uppercase mt-0.5">Manajemen Kas Kelas</div>
        </div>
      </div>
    </div>

    <!-- Active Class Badge -->
    <div class="px-4 py-3">
      <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-2.5 flex items-center gap-2.5">
        <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center">
          <span class="material-symbols-outlined text-base">school</span>
        </div>
        <div>
          <div class="text-xs font-bold text-slate-900 leading-tight">XII PPLG</div>
          <div class="text-[10.5px] text-blue-700 font-medium">TP 2024/2025 • Semester 2</div>
        </div>
      </div>
    </div>

    <!-- Nav Items -->
    <nav class="flex-1 px-3 py-1 space-y-1 overflow-y-auto" id="sidebarMenu">
      <div class="px-3 pt-2 pb-1 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Utama</div>
      <a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-xs shadow-md shadow-blue-600/25 cursor-pointer" data-nav="dashboard-kas" onclick="navigateSidebar('dashboard-kas')">
        <span class="material-symbols-outlined text-lg">dashboard</span>
        <span class="">Dashboard Kas</span>
      </a>
      <a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium text-xs transition-colors cursor-pointer" data-nav="grafik-analisis" onclick="navigateSidebar('grafik-analisis')">
        <span class="material-symbols-outlined text-lg text-blue-600">monitoring</span>
        <span class="">Grafik &amp; Analisis</span>
      </a>
      <a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium text-xs transition-colors cursor-pointer" data-nav="kas-masuk-bayar" onclick="navigateSidebar('kas-masuk-bayar')">
        <span class="material-symbols-outlined text-lg">payments</span>
        <span class="">Kas Masuk &amp; Bayar</span>
      </a>
      <a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium text-xs transition-colors cursor-pointer" data-nav="pengeluaran-nota" onclick="navigateSidebar('pengeluaran-nota')">
        <span class="material-symbols-outlined text-lg">receipt_long</span>
        <span class="">Pengeluaran &amp; Nota</span>
      </a>

      <div class="px-3 pt-4 pb-1 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Audit &amp; Verifikasi</div>
      <a class="nav-item flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium text-xs transition-colors cursor-pointer" data-nav="verifikasi-bukti" onclick="navigateSidebar('verifikasi-bukti')">
        <div class="flex items-center gap-2.5">
          <span class="material-symbols-outlined text-lg">fact_check</span>
          <span class="">Verifikasi Bukti</span>
        </div>
        <span class="px-1.5 py-0.5 rounded-full bg-red-100 text-red-700 text-[10px] font-bold">4</span>
      </a>
      <a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium text-xs transition-colors cursor-pointer" data-nav="rekap-tunggakan" onclick="navigateSidebar('rekap-tunggakan')">
        <span class="material-symbols-outlined text-lg">account_balance_wallet</span>
        <span class="">Data &amp; Tunggakan</span>
      </a>

      <div class="px-3 pt-4 pb-1 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Administrasi</div>
      <a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium text-xs transition-colors cursor-pointer" data-nav="laporan-export" onclick="navigateSidebar('laporan-export')">
        <span class="material-symbols-outlined text-lg">print</span>
        <span class="">Laporan &amp; Export</span>
      </a>
      <a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-medium text-xs transition-colors cursor-pointer" data-nav="pengaturan-wali-kelas" onclick="navigateSidebar('pengaturan-wali-kelas')">
        <span class="material-symbols-outlined text-lg">settings_suggest</span>
        <span class="">Kelola Wali &amp; Iuran</span>
      </a><a class="nav-item flex items-center gap-2.5 px-3 py-2 rounded-xl text-red-600 hover:bg-red-50 font-bold text-xs transition-colors cursor-pointer mt-1 border border-transparent hover:border-red-200" onclick="document.getElementById('logoutModal').classList.remove('hidden')"><span class="material-symbols-outlined text-lg text-red-600">logout</span><span class="">Logout / Keluar Akun</span></a>
    </nav>

    <!-- Bottom Info -->
    <div class="p-3 border-t border-slate-100">
      <div class="rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/60 p-3">
        <div class="flex items-center gap-1.5 text-blue-700 font-bold text-xs">
          <span class="material-symbols-outlined text-base">verified_user</span> Kas Terbuka &amp; Auditable
        </div>
        <p class="text-[11px] text-slate-600 mt-1">Target mingguan: <strong class="text-blue-900">Rp 10.000/siswa</strong></p>
      </div>
    </div>
  </aside>

  <!-- Main Content Wrap -->
  <div class="pl-64">
    <!-- Top Header -->
    <header class="fixed top-0 left-64 right-0 h-16 bg-white/95 backdrop-blur-md z-40 border-b border-slate-200 flex items-center justify-between px-6">
      <div class="flex items-center gap-3">
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-xs font-semibold text-blue-900">
          <span class="material-symbols-outlined text-blue-600 text-base">calendar_month</span>
          <span class="">Minggu Ke-12</span>
          <span class="text-blue-300">•</span>
          <span class="text-blue-600">Semester Genap</span>
        </div>
        <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-100/70 text-blue-800 text-[11px] font-bold border border-blue-200">
          Iuran Aktif Rp 10.000
        </span>
      </div>

      <!-- Right Header Actions -->
      <div class="flex items-center gap-3">
        <!-- Switch Role Simulator -->
        <div class="bg-slate-100 p-0.5 rounded-full flex items-center text-xs font-semibold">
          <button class="px-3 py-1 rounded-full bg-white text-blue-700 shadow-xs border border-slate-200/80" onclick="showToast('Aktif sebagai mode Siswa', 'info')">Siswa</button>
          <button class="px-2.5 py-1 rounded-full text-slate-600 hover:text-slate-900" onclick="showToast('Role Bendahara: Siti Andini &amp; Salfa Azzahra', 'info')">Bendahara</button>
          <button class="px-2.5 py-1 rounded-full text-slate-600 hover:text-slate-900" onclick="showToast('Role Pembina: Pak Baban, M.Pd.', 'info')">Wali Kelas</button>
        </div>
        <button class="relative p-2 rounded-full hover:bg-slate-100 text-slate-600" onclick="showToast('4 verifikasi bukti baru menunggu bendahara', 'info')">
          <span class="material-symbols-outlined text-xl">notifications</span>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        </button>

        <div class="h-6 w-px bg-slate-200"></div>

        <!-- Student Profile Pill (Vector Symbol Only, No Face Photo) -->
        <div class="flex items-center gap-2 cursor-pointer" onclick="showToast('Profil Siswa: Dimas Prasetyo (XII PPLG)', 'info')">
          <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-700 to-sky-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
            <span class="material-symbols-outlined text-base">person</span>
          </div>
          <div class="hidden md:flex flex-col text-left">
            <span class="text-xs font-bold text-slate-900 leading-tight">Dimas Prasetyo</span>
            <span class="text-[10px] text-blue-600 font-semibold leading-tight">NIS: 20241014</span>
          </div>
        </div><button type="button" onclick="document.getElementById('logoutModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-800 text-xs font-bold transition-all border border-red-200 shadow-xs" title="Keluar dari Akun"><span class="material-symbols-outlined text-base">logout</span><span class="hidden sm:inline">Keluar</span></button>
      </div>
    </header>

    <!-- Page Body -->
    <main class="pt-20 px-6 pb-12 w-full max-w-[1366px] mx-auto space-y-6">

      <!-- Welcome Banner -->
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 text-white p-6 shadow-md border border-blue-800/40">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md text-cyan-200 text-[11px] font-semibold border border-white/10 mb-2">
              <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span> Portal Siswa Aktif • TA 2024/2025
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white flex items-center gap-2">
              Halo, Dimas Prasetyo <span class="text-blue-200 text-base font-normal">(NIS: 20241014)</span>
            </h1>
            <p class="text-sm text-blue-100/90 mt-1">
              Kelas XII PPLG • Wali Kelas: <span class="font-semibold text-white cursor-pointer underline hover:text-cyan-200" onclick="navigateSidebar('pengaturan-wali-kelas')">Pak Baban</span> • Bendahara: <span class="font-semibold text-white cursor-pointer underline hover:text-cyan-200" onclick="navigateSidebar('pengaturan-wali-kelas')">Siti Andini &amp; Salfa</span>
            </p>
          </div>
          <div class="flex items-center gap-3">
            <div class="flex flex-col items-start md:items-end">
              <span class="text-[11px] text-blue-200 font-medium">Status Tagihan Kamu</span>
              <button class="mt-1 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 text-xs font-bold shadow-sm hover:scale-105 transition-transform" onclick="focusPaymentSection()">
                <span class="material-symbols-outlined text-base">error_outline</span>
                2 Minggu Menunggak (Rp 20.000)
              </button>
            </div>
          </div>
        </div>
        <!-- Decorative Glows -->
        <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-cyan-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-56 h-56 rounded-full bg-indigo-500/30 blur-2xl pointer-events-none"></div>
      </div>

      <!-- Key Metrics Row (3 Cards) -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Card 1 -->
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 hover:shadow-md transition-shadow cursor-pointer" onclick="navigateSidebar('pengeluaran-nota')">
          <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500">Kas Terkumpul (Umum)</span>
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-lg">account_balance</span>
            </div>
          </div>
          <div class="mt-2 text-2xl font-bold font-mono text-blue-700">Rp 4.850.000</div>
          <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-sm text-blue-600">verified</span> Auditable oleh 36 siswa kelas
          </p>
          <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-sky-500 h-full rounded-full" style="width: 82%"></div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 hover:shadow-md transition-shadow cursor-pointer" onclick="navigateSidebar('rekap-tunggakan')">
          <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500">Iuran Anda (Oktober)</span>
            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-lg">person_outline</span>
            </div>
          </div>
          <div class="mt-2 text-2xl font-bold font-mono text-slate-900">
            Rp 80.000 <span class="text-xs font-sans text-slate-400 font-normal">/ Rp 100.000</span>
          </div>
          <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-sm text-indigo-600">pie_chart</span> Tercapai 80% (4 dari 5 minggu)
          </p>
          <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-500 h-full rounded-full" style="width: 80%"></div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 hover:shadow-md transition-shadow cursor-pointer" onclick="focusPaymentSection()">
          <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500">Harus Dibayar</span>
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
              <span class="material-symbols-outlined text-lg">pending_actions</span>
            </div>
          </div>
          <div class="mt-2 text-2xl font-bold font-mono text-amber-600">Rp 20.000</div>
          <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-sm text-amber-600">schedule</span> Minggu Ke-3 &amp; Ke-4 Oktober
          </p>
          <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-400 to-amber-500 h-full rounded-full" style="width: 20%"></div>
          </div>
        </div>
      </div>

      <!-- ================= GRAFIK & ANALISIS ARUS KAS KELAS ================= -->
      <div class="space-y-4" id="sectionGrafik">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <div class="flex items-center gap-2">
              <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-2xl">monitoring</span>
                Grafik &amp; Analisis Arus Kas Kelas
              </h2>
              <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10.5px] font-bold border border-blue-200">Interaktif</span>
            </div>
            <p class="text-xs text-slate-500">Visualisasi tren kas masuk vs pengeluaran per pekan semester genap serta alokasi pos belanja kelas</p>
          </div>

          <!-- Time Filter Switcher -->
          <div class="flex items-center p-1 bg-slate-100 rounded-xl text-xs font-semibold border border-slate-200/80">
            <button id="btnFilterAll" type="button" class="px-3 py-1 rounded-lg bg-white text-blue-700 shadow-xs border border-slate-200/80 font-bold transition-all" onclick="filterCashflowChart('all')">Semua Pekan (1-12)</button>
            <button id="btnFilterRecent" type="button" class="px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium" onclick="filterCashflowChart('recent')">4 Pekan Terakhir</button>
            <button id="btnFilterMonth" type="button" class="px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium" onclick="filterCashflowChart('month')">Per Bulan</button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
          <!-- Chart 1 (Left 7 Cols): Bar & Area Chart Arus Kas -->
          <div class="lg:col-span-7 bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex flex-col justify-between relative">
            <div>
              <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
                <div>
                  <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                    Tren Kas Masuk vs Kas Keluar
                  </h3>
                  <span class="text-[11px] text-slate-500" id="chartRangeDesc">Pekan 1 hingga Pekan 12 Semester Genap</span>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold">
                  <span class="flex items-center gap-1.5 text-slate-700">
                    <span class="w-3 h-3 rounded-sm bg-blue-600"></span> Kas Masuk
                  </span>
                  <span class="flex items-center gap-1.5 text-slate-700">
                    <span class="w-3 h-3 rounded-sm bg-sky-400"></span> Kas Keluar
                  </span>
                </div>
              </div>

              <!-- SVG Interactive Container -->
              <div class="relative w-full h-56 pt-3 pb-1">
                <svg id="chartSvg" class="w-full h-full overflow-visible" viewBox="0 0 540 180">
                  <!-- Grid horizontal lines & scale markers -->
                  <line x1="32" y1="20" x2="520" y2="20" stroke="#F1F5F9" stroke-dasharray="3 3" stroke-width="1.2"></line>
                  <text x="26" y="24" text-anchor="end" font-size="9" fill="#94A3B8" font-family="'JetBrains Mono', monospace">400k</text>

                  <line x1="32" y1="60" x2="520" y2="60" stroke="#F1F5F9" stroke-dasharray="3 3" stroke-width="1.2"></line>
                  <text x="26" y="64" text-anchor="end" font-size="9" fill="#94A3B8" font-family="'JetBrains Mono', monospace">300k</text>

                  <line x1="32" y1="100" x2="520" y2="100" stroke="#F1F5F9" stroke-dasharray="3 3" stroke-width="1.2"></line>
                  <text x="26" y="104" text-anchor="end" font-size="9" fill="#94A3B8" font-family="'JetBrains Mono', monospace">200k</text>

                  <line x1="32" y1="140" x2="520" y2="140" stroke="#F1F5F9" stroke-dasharray="3 3" stroke-width="1.2"></line>
                  <text x="26" y="144" text-anchor="end" font-size="9" fill="#94A3B8" font-family="'JetBrains Mono', monospace">100k</text>

                  <line x1="32" y1="155" x2="520" y2="155" stroke="#CBD5E1" stroke-width="1.2"></line>

                  <!-- Outflow Smooth Fill Area -->
                  <defs>
                    <linearGradient id="areaGradient" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.45"></stop>
                      <stop offset="100%" stop-color="#38BDF8" stop-opacity="0.02"></stop>
                    </linearGradient>
                  </defs>
                  <path id="outflowAreaPath" d="M 54 138 L 94 142 L 134 110 L 174 147 L 214 90 L 254 144 L 294 85 L 334 146 L 374 138 L 414 115 L 454 149 L 494 120 L 494 155 L 54 155 Z" fill="url(#areaGradient)"></path>

                  <!-- Interactive Column Groups (P1 - P12) -->
                  <!-- P1 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="1" onmouseenter="showChartTip(event, 'Pekan 1', 'Rp 360.000', 'Rp 45.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="44" y="32" width="10" height="123" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="56" y="138" width="10" height="17" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="55" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P1</text>
                  </g>
                  <!-- P2 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="2" onmouseenter="showChartTip(event, 'Pekan 2', 'Rp 350.000', 'Rp 30.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="84" y="36" width="10" height="119" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="96" y="142" width="10" height="13" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="95" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P2</text>
                  </g>
                  <!-- P3 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="3" onmouseenter="showChartTip(event, 'Pekan 3', 'Rp 360.000', 'Rp 90.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="124" y="32" width="10" height="123" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="136" y="110" width="10" height="45" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="135" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P3</text>
                  </g>
                  <!-- P4 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="4" onmouseenter="showChartTip(event, 'Pekan 4', 'Rp 340.000', 'Rp 20.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="164" y="40" width="10" height="115" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="176" y="147" width="10" height="8" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="175" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P4</text>
                  </g>
                  <!-- P5 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="5" onmouseenter="showChartTip(event, 'Pekan 5', 'Rp 360.000', 'Rp 130.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="204" y="32" width="10" height="123" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="216" y="90" width="10" height="65" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="215" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P5</text>
                  </g>
                  <!-- P6 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="6" onmouseenter="showChartTip(event, 'Pekan 6', 'Rp 330.000', 'Rp 25.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="244" y="44" width="10" height="111" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="256" y="144" width="10" height="11" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="255" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P6</text>
                  </g>
                  <!-- P7 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="7" onmouseenter="showChartTip(event, 'Pekan 7', 'Rp 370.000', 'Rp 140.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="284" y="28" width="10" height="127" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="296" y="85" width="10" height="70" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="295" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P7</text>
                  </g>
                  <!-- P8 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="8" onmouseenter="showChartTip(event, 'Pekan 8', 'Rp 350.000', 'Rp 20.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="324" y="36" width="10" height="119" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="336" y="146" width="10" height="9" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="335" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P8</text>
                  </g>
                  <!-- P9 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="9" onmouseenter="showChartTip(event, 'Pekan 9', 'Rp 360.000', 'Rp 35.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="364" y="32" width="10" height="123" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="376" y="138" width="10" height="17" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="375" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P9</text>
                  </g>
                  <!-- P10 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="10" onmouseenter="showChartTip(event, 'Pekan 10', 'Rp 380.000', 'Rp 80.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="404" y="24" width="10" height="131" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="416" y="115" width="10" height="40" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="415" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P10</text>
                  </g>
                  <!-- P11 -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="11" onmouseenter="showChartTip(event, 'Pekan 11', 'Rp 340.000', 'Rp 15.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all" x="444" y="40" width="10" height="115" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all" x="456" y="149" width="10" height="6" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="455" y="170" text-anchor="middle" font-size="9" font-weight="600" fill="#64748B">P11</text>
                  </g>
                  <!-- P12 (Active) -->
                  <g class="bar-col cursor-pointer transition-all" data-pekan="12" onmouseenter="showChartTip(event, 'Pekan 12 (Aktif)', 'Rp 360.000', 'Rp 70.000')" onmouseleave="hideChartTip()">
                    <rect class="bar-in transition-all ring-2 ring-blue-300" x="484" y="32" width="10" height="123" rx="3" fill="#2563EB"></rect>
                    <rect class="bar-out transition-all ring-1 ring-sky-300" x="496" y="120" width="10" height="35" rx="3" fill="#38BDF8"></rect>
                    <text class="bar-label" x="495" y="170" text-anchor="middle" font-size="9" font-weight="700" fill="#2563EB">P12*</text>
                  </g>
                </svg>

                <!-- Floating Chart Tooltip -->
                <div id="chartTooltip" class="absolute pointer-events-none hidden bg-slate-900/90 text-white rounded-xl px-3 py-2 shadow-xl text-xs backdrop-blur-sm z-30 transition-all border border-slate-700">
                  <div class="font-bold text-cyan-300" id="ttTitle">Pekan 12</div>
                  <div class="flex items-center gap-2 mt-0.5"><span class="text-blue-300 font-medium">Kas Masuk:</span> <span class="font-mono font-bold" id="ttIn">Rp 360.000</span></div>
                  <div class="flex items-center gap-2"><span class="text-sky-300 font-medium">Kas Keluar:</span> <span class="font-mono font-bold" id="ttOut">Rp 70.000</span></div>
                </div>
              </div>
            </div>

            <!-- Bottom Sub-indicators -->
            <div class="grid grid-cols-3 gap-2.5 pt-3 border-t border-slate-100 mt-2">
              <div class="p-2.5 rounded-xl bg-blue-50/70 border border-blue-100">
                <div class="text-[10.5px] text-slate-500 font-medium">Rata-rata Masuk</div>
                <div class="font-mono text-xs font-bold text-blue-700">Rp 355.000 <span class="text-[10px] text-emerald-600 font-semibold font-sans">↑14%</span></div>
              </div>
              <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                <div class="text-[10.5px] text-slate-500 font-medium">Status Pengeluaran</div>
                <div class="text-xs font-bold text-slate-800 flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hemat &amp; Terkendali
                </div>
              </div>
              <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                <div class="text-[10.5px] text-slate-500 font-medium">Rasio Likuiditas Kas</div>
                <div class="text-xs font-bold text-indigo-700">2.06x (Sangat Baik)</div>
              </div>
            </div>
          </div>

          <!-- Chart 2 (Right 5 Cols): Donut Komposisi Pengeluaran & Pos Kas -->
          <div class="lg:col-span-5 bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                    Komposisi Alokasi Pengeluaran
                  </h3>
                  <span class="text-[11px] text-slate-500">Realisasi per kategori Semester Genap</span>
                </div>
                <button type="button" class="text-xs text-blue-600 font-semibold hover:underline" onclick="navigateSidebar('pengeluaran-nota')">Lihat Nota</button>
              </div>

              <!-- Donut Graphic & Legend -->
              <div class="flex flex-col sm:flex-row items-center gap-4 py-3">
                <!-- SVG Donut -->
                <div class="relative w-36 h-36 flex items-center justify-center flex-shrink-0">
                  <svg class="w-36 h-36 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="38" fill="transparent" stroke="#F1F5F9" stroke-width="16"></circle>
                    <!-- Seg 1: KBM & Ujian (42%) -->
                    <circle class="donut-seg" cx="50" cy="50" r="38" fill="transparent" stroke="#2563EB" stroke-width="16" stroke-dasharray="100.3 138.5" stroke-dashoffset="0" onmouseenter="donutHover('KBM &amp; Ujian', '42%', 'Rp 987.000')" onmouseleave="donutReset()"></circle>
                    <!-- Seg 2: Kebersihan (25%) -->
                    <circle class="donut-seg" cx="50" cy="50" r="38" fill="transparent" stroke="#0284C7" stroke-width="16" stroke-dasharray="59.7 179.1" stroke-dashoffset="-100.3" onmouseenter="donutHover('Kebersihan &amp; Peralatan', '25%', 'Rp 587.500')" onmouseleave="donutReset()"></circle>
                    <!-- Seg 3: Sosial & Santunan (18%) -->
                    <circle class="donut-seg" cx="50" cy="50" r="38" fill="transparent" stroke="#38BDF8" stroke-width="16" stroke-dasharray="43.0 195.8" stroke-dashoffset="-160.0" onmouseenter="donutHover('Sosial &amp; Santunan', '18%', 'Rp 423.000')" onmouseleave="donutReset()"></circle>
                    <!-- Seg 4: Cadangan/Pensi (15%) -->
                    <circle class="donut-seg" cx="50" cy="50" r="38" fill="transparent" stroke="#6366F1" stroke-width="16" stroke-dasharray="35.8 203.0" stroke-dashoffset="-203.0" onmouseenter="donutHover('Cadangan &amp; Pensi', '15%', 'Rp 352.500')" onmouseleave="donutReset()"></circle>
                  </svg>
                  <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                    <span class="text-[9.5px] text-slate-400 font-semibold uppercase tracking-wider" id="dnLabel">Terealisasi</span>
                    <span class="text-xs font-mono font-extrabold text-blue-900" id="dnVal">Rp 2.35M</span>
                    <span class="text-[9px] text-blue-600 font-bold" id="dnPct">100% Sesuai Pos</span>
                  </div>
                </div>

                <!-- Legend Rows -->
                <div class="space-y-1.5 flex-1 w-full text-xs">
                  <div class="p-1.5 rounded-lg hover:bg-blue-50 border border-transparent hover:border-blue-200 transition-all flex items-center justify-between cursor-pointer" onmouseenter="donutHover('KBM &amp; Ujian', '42%', 'Rp 987.000')" onmouseleave="donutReset()">
                    <div class="flex items-center gap-2">
                      <span class="w-2.5 h-2.5 rounded-full bg-blue-600 flex-shrink-0"></span>
                      <span class="font-medium text-slate-800">KBM &amp; Ujian</span>
                    </div>
                    <div class="text-right">
                      <span class="font-bold text-slate-900">42%</span>
                      <span class="text-[10px] text-slate-400 font-mono block">Rp 987k</span>
                    </div>
                  </div>

                  <div class="p-1.5 rounded-lg hover:bg-blue-50 border border-transparent hover:border-blue-200 transition-all flex items-center justify-between cursor-pointer" onmouseenter="donutHover('Kebersihan &amp; Peralatan', '25%', 'Rp 587.500')" onmouseleave="donutReset()">
                    <div class="flex items-center gap-2">
                      <span class="w-2.5 h-2.5 rounded-full bg-[#0284C7] flex-shrink-0"></span>
                      <span class="font-medium text-slate-800">Kebersihan Kelas</span>
                    </div>
                    <div class="text-right">
                      <span class="font-bold text-slate-900">25%</span>
                      <span class="text-[10px] text-slate-400 font-mono block">Rp 587k</span>
                    </div>
                  </div>

                  <div class="p-1.5 rounded-lg hover:bg-blue-50 border border-transparent hover:border-blue-200 transition-all flex items-center justify-between cursor-pointer" onmouseenter="donutHover('Sosial &amp; Santunan', '18%', 'Rp 423.000')" onmouseleave="donutReset()">
                    <div class="flex items-center gap-2">
                      <span class="w-2.5 h-2.5 rounded-full bg-[#38BDF8] flex-shrink-0"></span>
                      <span class="font-medium text-slate-800">Sosial &amp; Santunan</span>
                    </div>
                    <div class="text-right">
                      <span class="font-bold text-slate-900">18%</span>
                      <span class="text-[10px] text-slate-400 font-mono block">Rp 423k</span>
                    </div>
                  </div>

                  <div class="p-1.5 rounded-lg hover:bg-blue-50 border border-transparent hover:border-blue-200 transition-all flex items-center justify-between cursor-pointer" onmouseenter="donutHover('Cadangan &amp; Pensi', '15%', 'Rp 352.500')" onmouseleave="donutReset()">
                    <div class="flex items-center gap-2">
                      <span class="w-2.5 h-2.5 rounded-full bg-[#6366F1] flex-shrink-0"></span>
                      <span class="font-medium text-slate-800">Cadangan / Pensi</span>
                    </div>
                    <div class="text-right">
                      <span class="font-bold text-slate-900">15%</span>
                      <span class="text-[10px] text-slate-400 font-mono block">Rp 352k</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
              <span class="flex items-center gap-1"><span class="material-symbols-outlined text-xs text-blue-600">verified</span> Diawasi Pak Baban</span>
              <button type="button" class="text-blue-600 font-semibold hover:underline" onclick="navigateSidebar('laporan-export')">Unduh Rincian XLS</button>
            </div>
          </div>
        </div>
      </div>
      <!-- ================= END GRAFIK & ANALISIS ================= -->

      <!-- Main Workspace: Payment Workflow + Transparency Widget -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" id="paymentSection">
        <!-- Left: Payment Form & Gateway (7 Cols) -->
        <div class="lg:col-span-7 space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-bold text-slate-900">Bayar Kas &amp; Upload Bukti</h2>
              <p class="text-xs text-slate-500">Pindai QRIS resmi kas kelas atau transfer bank, lalu lampirkan bukti pembayaran</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-blue-600 text-white text-xs font-semibold shadow-sm">Resmi XII PPLG</span>
          </div>

          <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 space-y-4">
            <!-- Tabs -->
            <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-xl">
              <button id="tabQris" type="button" class="flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 bg-white text-blue-700 shadow-sm border border-slate-200/80" onclick="switchMethod('qris')">
                <span class="material-symbols-outlined text-base">qr_code_scanner</span> QRIS Kas Kelas
              </button>
              <button id="tabRek" type="button" class="flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900" onclick="switchMethod('rekening')">
                <span class="material-symbols-outlined text-base">account_balance_wallet</span> Rekening Bendahara
              </button>
            </div>

            <!-- QRIS View -->
            <div id="viewQris" class="rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50/50 border border-blue-200/70 p-4 flex flex-col items-center justify-center group cursor-pointer" onclick="showToast('Pindai QRIS via GoPay, BCA Mobile, ShopeePay, OVO, atau Livin', 'info')">
              <div class="w-full flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                  <span class="px-2 py-0.5 rounded bg-blue-600 text-white text-[10px] font-bold">QRIS RESMI</span>
                  <span class="text-xs font-bold text-slate-800">KAS XII PPLG (NMID: ID102030)</span>
                </div>
                <span class="material-symbols-outlined text-blue-600 text-lg group-hover:scale-110 transition-transform">fullscreen</span>
              </div>
              <!-- QR Vector Mockup -->
              <div class="bg-white p-4 rounded-xl shadow-sm border border-blue-100 flex flex-col items-center">
                <svg class="w-32 h-32 text-blue-900" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                  <rect x="5" y="5" width="3" height="3" fill="#2563EB"></rect>
                  <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                  <rect x="16" y="5" width="3" height="3" fill="#2563EB"></rect>
                  <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                  <rect x="5" y="16" width="3" height="3" fill="#2563EB"></rect>
                  <circle cx="16" cy="16" r="1.5" fill="#0284C7"></circle>
                  <path d="M14 14h2v2h-2zM19 14h2v5h-5v-2h3zM14 19h2v2h-2z" fill="#2563EB"></path>
                </svg>
                <div class="text-[11px] font-bold text-blue-900 mt-2">BCA / GOPAY / OVO / SHOPEEPAY</div>
                <div class="text-[10px] text-slate-400">Klik untuk perbesar QR</div>
              </div>
              <p class="text-[11px] text-slate-500 mt-2">Biaya admin Rp 0 • Masuk tercatat di buku kas</p>
            </div>

            <!-- Rekening View (Hidden by default) -->
            <div id="viewRek" class="hidden rounded-xl bg-gradient-to-br from-indigo-50/50 to-blue-50 border border-blue-200/70 p-4 space-y-2.5">
              <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold text-slate-900">Rekening Resmi Bendahara</span>
                <span class="material-symbols-outlined text-indigo-600 text-lg">account_balance_wallet</span>
              </div>
              <!-- BCA -->
              <div class="p-3 rounded-xl bg-white border border-blue-100 flex items-center justify-between">
                <div>
                  <div class="text-[10.5px] text-slate-400">BCA - Bendahara 1</div>
                  <div class="font-mono text-sm font-bold text-slate-900">8831 2940 11</div>
                  <div class="text-[10.5px] text-blue-600 font-semibold">a.n Siti Andini</div>
                </div>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 text-xs font-bold transition-all border border-blue-200" onclick="copyText('8831294011', 'BCA Siti Andini')">
                  Salin
                </button>
              </div>
              <!-- Mandiri -->
              <div class="p-3 rounded-xl bg-white border border-blue-100 flex items-center justify-between">
                <div>
                  <div class="text-[10.5px] text-slate-400">Mandiri - Bendahara 2</div>
                  <div class="font-mono text-sm font-bold text-slate-900">132 00 234188 9</div>
                  <div class="text-[10.5px] text-blue-600 font-semibold">a.n Salfa Nurul Aini</div>
                </div>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 text-xs font-bold transition-all border border-blue-200" onclick="copyText('132002341889', 'Mandiri Salfa')">
                  Salin
                </button>
              </div>
            </div>

            <!-- Form -->
            <form onsubmit="handlePaySubmit(event)" class="space-y-4 pt-1">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Periode Iuran</label>
                  <select id="periodeSelect" class="w-full rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600" onchange="changeNominal(this.value)">
                    <option value="20000" selected="">Minggu 3 &amp; 4 Oktober (Rp 20.000)</option>
                    <option value="10000">Minggu 3 Oktober Saja (Rp 10.000)</option>
                    <option value="40000">Paket 1 Bulan Penuh - Nov (Rp 40.000)</option>
                    <option value="80000">Paket 2 Bulan Penuh (Rp 80.000)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 mb-1.5">Nominal Terhitung</label>
                  <input id="nominalBox" type="text" readonly="" value="Rp 20.000" class="w-full rounded-xl bg-blue-50 border border-blue-200 px-3 py-2 text-xs font-mono font-bold text-blue-700 focus:outline-none">
                </div>
              </div>

              <!-- Upload Drag Zone -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Unggah Bukti Struk / Screenshot Transfer</label>
                <div class="rounded-xl border-2 border-dashed border-blue-300 p-4 text-center bg-blue-50/30 hover:bg-blue-50 hover:border-blue-500 transition-all cursor-pointer relative group">
                  <input type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" onchange="fileChosen(this)">
                  <div id="dropPlaceholder" class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-sky-400 text-white flex items-center justify-center shadow-md shadow-blue-500/25 mb-1.5 group-hover:scale-110 transition-transform">
                      <span class="material-symbols-outlined text-xl">add_photo_alternate</span>
                    </div>
                    <div class="text-xs font-bold text-slate-800" id="dropTitle">Tarik foto ke sini atau klik untuk telusuri berkas</div>
                    <div class="text-[10.5px] text-slate-400">Format JPG, PNG, WEBP (Maks. 5 MB)</div>
                  </div>
                </div>
              </div>

              <!-- Catatan -->
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Transaksi (Opsional)</label>
                <input type="text" placeholder="Contoh: Transfer lewat BCA Kakak / Titip tunai via Salfa" class="w-full rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
              </div>

              <button type="submit" id="btnSubmitPay" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 px-4 text-xs font-bold shadow-md shadow-blue-600/30 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">send</span>
                <span class="">Kirim Bukti Pembayaran Ke Bendahara</span>
              </button>
            </form>
          </div>
        </div>

        <!-- Right: Transparency Kas (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-bold text-slate-900">Transparansi Kas</h2>
              <p class="text-xs text-slate-500">Laporan pengeluaran kelas terverifikasi</p>
            </div>
            <button class="text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-full border border-blue-200 transition-colors" onclick="navigateSidebar('pengeluaran-nota')">
              Lihat Semua
            </button>
          </div>

          <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 space-y-3">
            <!-- Expense Item 1 -->
            <div class="p-3 rounded-xl bg-slate-50 hover:bg-blue-50/60 border border-transparent hover:border-blue-200 transition-all flex items-center justify-between gap-3">
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                  <span class="material-symbols-outlined text-lg">brush</span>
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-900 truncate">Dekorasi Peringatan 17-an</div>
                  <div class="text-[10.5px] text-slate-400">14 Agu 2024 • Disetujui Pak Baban</div>
                </div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-xs font-bold font-mono text-red-600">-Rp 250.000</div>
                <button type="button" class="text-[11px] text-blue-600 font-semibold hover:underline" onclick="openReceipt('Dekorasi Peringatan 17-an', 'Rp 250.000', '14 Agu 2024', 'Toko Karya Agung • Pak Baban')">Nota</button>
              </div>
            </div>

            <!-- Expense Item 2 -->
            <div class="p-3 rounded-xl bg-slate-50 hover:bg-blue-50/60 border border-transparent hover:border-blue-200 transition-all flex items-center justify-between gap-3">
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                  <span class="material-symbols-outlined text-lg">description</span>
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-900 truncate">Foto Copy Soal Try Out Fisika</div>
                  <div class="text-[10.5px] text-slate-400">02 Okt 2024 • Disetujui Pak Baban</div>
                </div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-xs font-bold font-mono text-red-600">-Rp 110.000</div>
                <button type="button" class="text-[11px] text-blue-600 font-semibold hover:underline" onclick="openReceipt('Foto Copy Soal Try Out Fisika', 'Rp 110.000', '02 Okt 2024', 'Fotocopy Berkah Sekolah')">Nota</button>
              </div>
            </div>

            <!-- Expense Item 3 -->
            <div class="p-3 rounded-xl bg-slate-50 hover:bg-blue-50/60 border border-transparent hover:border-blue-200 transition-all flex items-center justify-between gap-3">
              <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                  <span class="material-symbols-outlined text-lg">inventory_2</span>
                </div>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-slate-900 truncate">Spidol Boardmarker &amp; Penghapus</div>
                  <div class="text-[10.5px] text-slate-400">18 Okt 2024 • Pembelian Rutin</div>
                </div>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-xs font-bold font-mono text-red-600">-Rp 45.000</div>
                <button type="button" class="text-[11px] text-blue-600 font-semibold hover:underline" onclick="openReceipt('Spidol Boardmarker &amp; Penghapus', 'Rp 45.000', '18 Okt 2024', 'Gramedia Stationeries')">Nota</button>
              </div>
            </div>

            <!-- Update Status Bar -->
            <div class="p-2.5 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/50 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-base">check_circle</span>
                <span class="text-xs font-semibold text-blue-950">Buku Kas diperbarui oleh Siti Andini</span>
              </div>
              <span class="text-[10px] text-blue-600 font-medium">Kemarin 16:30</span>
            </div>
          </div>

          <!-- Rules Notice -->
          <div class="rounded-2xl bg-white p-4 shadow-sm border-l-4 border-blue-600 border border-slate-200/80">
            <div class="flex items-start gap-3">
              <span class="material-symbols-outlined text-blue-600 text-lg">info</span>
              <div>
                <h4 class="text-xs font-bold text-slate-900">Aturan Pembayaran Kas</h4>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                  Iuran kas dikumpulkan tiap hari Jum'at sebesar <strong class="text-blue-700">Rp 10.000/minggu</strong>. Jika membayar tunai langsung ke Salfa di kelas, bendahara akan langsung memberikan konfirmasi lewat sistem.
                </p>
                <button class="text-xs text-blue-600 font-semibold hover:underline mt-2 inline-flex items-center gap-1" onclick="navigateSidebar('pengaturan-wali-kelas')">
                  Baca SOP &amp; Kebijakan Kas Selengkapnya <span class="material-symbols-outlined text-xs">arrow_forward</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Riwayat Pembayaran Siswa Table -->
      <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <h3 class="text-base font-bold text-slate-900">Riwayat Pembayaran Kas Saya</h3>
            <p class="text-xs text-slate-500">Daftar transaksi dan status verifikasi bendahara kelas</p>
          </div>
          <div class="flex items-center gap-2">
            <button class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold" onclick="showToast('Periode aktif Semester Genap', 'info')">Semester Genap 2024</button>
            <button class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold flex items-center gap-1" onclick="navigateSidebar('laporan-export')">
              <span class="material-symbols-outlined text-sm">download</span> Ekspor
            </button>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-blue-50/70 text-blue-900 font-bold border-b border-blue-100">
                <th class="py-3 px-4 rounded-l-xl">ID Transaksi</th>
                <th class="py-3 px-4">Periode / Minggu</th>
                <th class="py-3 px-4">Nominal</th>
                <th class="py-3 px-4">Tanggal Bayar</th>
                <th class="py-3 px-4">Metode</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4 rounded-r-xl text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr class="hover:bg-blue-50/30">
                <td class="py-3 px-4 font-mono font-bold text-blue-600">#KAS-2024-1004</td>
                <td class="py-3 px-4 font-medium text-slate-800">Minggu ke-2 Oktober</td>
                <td class="py-3 px-4 font-mono font-bold">Rp 10.000</td>
                <td class="py-3 px-4 text-slate-500">11 Okt 2024, 09:14</td>
                <td class="py-3 px-4"><span class="inline-flex items-center gap-1 text-slate-700"><span class="material-symbols-outlined text-sm text-blue-600">qr_code_2</span> QRIS Kas</span></td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold text-[10.5px]">Terverifikasi</span>
                </td>
                <td class="py-3 px-4 text-right">
                  <button class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold" onclick="openReceipt('Iuran Minggu ke-2 Oktober', 'Rp 10.000', '11 Okt 2024, 09:14', 'QRIS Kas XII PPLG')">Lihat Bukti</button>
                </td>
              </tr>
              <tr class="hover:bg-blue-50/30">
                <td class="py-3 px-4 font-mono font-bold text-blue-600">#KAS-2024-0988</td>
                <td class="py-3 px-4 font-medium text-slate-800">Minggu ke-1 Oktober</td>
                <td class="py-3 px-4 font-mono font-bold">Rp 10.000</td>
                <td class="py-3 px-4 text-slate-500">04 Okt 2024, 07:30</td>
                <td class="py-3 px-4"><span class="inline-flex items-center gap-1 text-slate-700"><span class="material-symbols-outlined text-sm text-indigo-600">payments</span> Tunai (Salfa)</span></td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold text-[10.5px]">Terverifikasi</span>
                </td>
                <td class="py-3 px-4 text-right">
                  <button class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold" onclick="openReceipt('Iuran Minggu ke-1 Oktober (Tunai)', 'Rp 10.000', '04 Okt 2024, 07:30', 'Tunai diterima Salfa')">Kuitansi</button>
                </td>
              </tr>
              <tr class="hover:bg-blue-50/30">
                <td class="py-3 px-4 font-mono font-bold text-blue-600">#KAS-2024-0912</td>
                <td class="py-3 px-4 font-medium text-slate-800">Minggu ke-4 September</td>
                <td class="py-3 px-4 font-mono font-bold">Rp 20.000</td>
                <td class="py-3 px-4 text-slate-500">27 Sep 2024, 13:20</td>
                <td class="py-3 px-4"><span class="inline-flex items-center gap-1 text-slate-700"><span class="material-symbols-outlined text-sm text-blue-600">account_balance</span> Transfer BCA</span></td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[10.5px]">Lunas</span>
                </td>
                <td class="py-3 px-4 text-right">
                  <button class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold" onclick="openReceipt('Iuran Minggu ke-4 September', 'Rp 20.000', '27 Sep 2024, 13:20', 'Bank BCA Rek Siti Andini')">Lihat Bukti</button>
                </td>
              </tr>
              <tr class="hover:bg-blue-50/30 bg-amber-50/40">
                <td class="py-3 px-4 font-mono font-bold text-slate-400">#KAS-2024-PEND</td>
                <td class="py-3 px-4 font-medium text-slate-800">Minggu 3 &amp; 4 Oktober</td>
                <td class="py-3 px-4 font-mono font-bold text-amber-600">Rp 20.000</td>
                <td class="py-3 px-4 text-slate-500">Belum diajukan</td>
                <td class="py-3 px-4 text-slate-400">-</td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10.5px]">Belum Lunas</span>
                </td>
                <td class="py-3 px-4 text-right">
                  <button class="px-3 py-1 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-sm" onclick="focusPaymentSection()">Bayar Sekarang</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- MODAL: Kuitansi Bukti Bayar -->
  <div id="receiptModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden" onclick="closeModalOnBackdrop(event, 'receiptModal')">
    <div class="bg-white rounded-2xl max-w-sm w-full mx-4 overflow-hidden shadow-2xl p-6 border border-slate-200">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-1.5 text-blue-700 font-bold text-sm">
          <span class="material-symbols-outlined text-xl">verified</span> Bukti Kuitansi SmartKas
        </div>
        <button onclick="closeModal('receiptModal')" class="text-slate-400 hover:text-slate-700"><span class="material-symbols-outlined">close</span></button>
      </div>
      <div class="py-4 text-center space-y-1">
        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2">
          <span class="material-symbols-outlined text-2xl">receipt</span>
        </div>
        <div class="text-sm font-bold text-slate-900" id="recTitle">Pembayaran Kas Siswa</div>
        <div class="text-2xl font-bold font-mono text-blue-600" id="recAmount">Rp 10.000</div>
        <div class="text-xs text-slate-400" id="recDate">11 Okt 2024</div>
        <div class="mt-2 inline-block px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[11px] font-semibold" id="recDetail">Tervalidasi Sistem</div>
      </div>
      <div class="space-y-1.5 bg-slate-50 p-3 rounded-xl text-xs border border-slate-100 mb-4">
        <div class="flex justify-between text-slate-500"><span class="">Siswa:</span> <span class="font-bold text-slate-800">Dimas Prasetyo (XII PPLG)</span></div>
        <div class="flex justify-between text-slate-500"><span class="">Penerima:</span> <span class="font-bold text-slate-800">Siti Andini &amp; Salfa</span></div>
        <div class="flex justify-between text-slate-500"><span class="">Status:</span> <span class="font-bold text-blue-600">Sah &amp; Disetujui</span></div>
        <div class="flex justify-between text-slate-500"><span class="">Ref ID:</span> <span class="font-mono text-slate-500">#98E4-XII-PPLG</span></div>
      </div>
      <div class="flex gap-2">
        <button class="flex-1 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold" onclick="closeModal('receiptModal')">Tutup</button>
        <button class="flex-1 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold" onclick="window.print()">Cetak</button>
      </div>
    </div>
  </div><div id="logoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden" onclick="closeModalOnBackdrop(event, 'logoutModal')"><div class="bg-white rounded-2xl max-w-sm w-full mx-4 overflow-hidden shadow-2xl p-6 border border-slate-200"><div class="flex items-center justify-between pb-3 border-b border-slate-100"><div class="flex items-center gap-2 text-red-600 font-bold text-sm"><span class="material-symbols-outlined text-xl">logout</span><span class="">Konfirmasi Keluar</span></div><button onclick="closeModal('logoutModal')" class="text-slate-400 hover:text-slate-700"><span class="material-symbols-outlined">close</span></button></div><div class="py-5 text-center space-y-2"><div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-1"><span class="material-symbols-outlined text-3xl">power_settings_new</span></div><h4 class="text-base font-bold text-slate-900">Keluar dari SmartKas?</h4><p class="text-xs text-slate-500 leading-relaxed px-2">Apakah Anda yakin ingin mengakhiri sesi kas ini? Anda akan dialihkan kembali ke Halaman Login Portal Siswa.</p></div><div class="bg-blue-50/70 p-3 rounded-xl border border-blue-100 text-xs mb-5 flex items-center gap-2.5"><div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined text-base">person</span></div><div class="text-left min-w-0"><div class="font-bold text-slate-900 truncate">Dimas Prasetyo (XII PPLG)</div><div class="text-[10px] text-blue-700">NIS: 20241014 • Sesi Aktif</div></div></div><div class="flex items-center gap-2.5"><button type="button" class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors" onclick="closeModal('logoutModal')">Batal</button><button type="button" class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md shadow-blue-500/25 transition-all flex items-center justify-center gap-1.5" onclick="closeModal('logoutModal'); showToast('Berhasil keluar. Mengalihkan ke Halaman Login...', 'info');"><span class="material-symbols-outlined text-sm">logout</span><span class="">Ya, Keluar</span></button></div></div></div>

  <!-- Toast Container -->
  <div id="toastContainer" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

  <!-- Interactive JavaScript Logic -->
  <script>
    function showToast(msg, type = 'info') {
      const container = document.getElementById('toastContainer');
      const toast = document.createElement('div');
      toast.className = 'pointer-events-auto transform transition-all duration-300 ease-out flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg border text-xs font-semibold text-white bg-slate-900 border-slate-700';
      toast.innerHTML = `<span class="material-symbols-outlined text-base text-cyan-400">info</span><span>${msg}</span>`;
      container.appendChild(toast);
      setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    function switchMethod(method) {
      const qrisView = document.getElementById('viewQris');
      const rekView = document.getElementById('viewRek');
      const tabQris = document.getElementById('tabQris');
      const tabRek = document.getElementById('tabRek');

      if (method === 'qris') {
        qrisView.classList.remove('hidden');
        rekView.classList.add('hidden');
        tabQris.className = 'flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 bg-white text-blue-700 shadow-sm border border-slate-200/80';
        tabRek.className = 'flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900';
      } else {
        qrisView.classList.add('hidden');
        rekView.classList.remove('hidden');
        tabRek.className = 'flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 bg-white text-blue-700 shadow-sm border border-slate-200/80';
        tabQris.className = 'flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900';
      }
    }

    function changeNominal(val) {
      const nominal = parseInt(val).toLocaleString('id-ID');
      document.getElementById('nominalBox').value = 'Rp ' + nominal;
    }

    function copyText(text, label) {
      navigator.clipboard.writeText(text);
      showToast('Nomor rekening ' + label + ' berhasil disalin!', 'success');
    }

    function fileChosen(input) {
      if (input.files && input.files[0]) {
        document.getElementById('dropTitle').innerText = 'Berkas dipilih: ' + input.files[0].name;
        showToast('Foto bukti pembayaran berhasil diunggah', 'success');
      }
    }

    function handlePaySubmit(e) {
      e.preventDefault();
      showToast('Bukti pembayaran berhasil dikirim ke Siti Andini (Bendahara)!', 'success');
    }

    function focusPaymentSection() {
      const el = document.getElementById('paymentSection');
      el.scrollIntoView({ behavior: 'smooth' });
      el.classList.add('ring-2', 'ring-blue-500', 'rounded-2xl', 'p-1', 'transition-all');
      setTimeout(() => el.classList.remove('ring-2', 'ring-blue-500', 'p-1'), 1500);
    }

    function navigateSidebar(target) {
      if (target === 'dashboard-kas') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        showToast('Menampilkan Dashboard Kas Utama', 'info');
      } else if (target === 'grafik-analisis') {
        document.getElementById('sectionGrafik').scrollIntoView({ behavior: 'smooth' });
        showToast('Menampilkan Grafik & Analisis Arus Kas', 'info');
      } else if (target === 'kas-masuk-bayar') {
        focusPaymentSection();
      } else if (target === 'pengeluaran-nota') {
        showToast('Membuka rincian pengeluaran & nota kelas (Rp 405.000 terealisasi)', 'info');
      } else if (target === 'verifikasi-bukti') {
        showToast('4 antrean verifikasi bukti transfer sedang diproses bendahara', 'info');
      } else if (target === 'rekap-tunggakan') {
        showToast('Data tunggakan: 2 pekan (Rp 20.000)', 'info');
      } else if (target === 'laporan-export') {
        showToast('Mengekspor laporan kas kelas bulanan format PDF & Excel', 'info');
      } else if (target === 'pengaturan-wali-kelas') {
        showToast('SOP Kas: Pengeluaran > Rp 50.000 wajib otorisasi Pak Baban', 'info');
      }
    }

    // Chart Filter Logic
    function filterCashflowChart(mode) {
      const btnAll = document.getElementById('btnFilterAll');
      const btnRec = document.getElementById('btnFilterRecent');
      const btnMon = document.getElementById('btnFilterMonth');
      const desc = document.getElementById('chartRangeDesc');
      const cols = document.querySelectorAll('.bar-col');

      [btnAll, btnRec, btnMon].forEach(b => {
        b.className = 'px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium';
      });

      if (mode === 'all') {
        btnAll.className = 'px-3 py-1 rounded-lg bg-white text-blue-700 shadow-xs border border-slate-200/80 font-bold transition-all';
        desc.innerText = 'Pekan 1 hingga Pekan 12 Semester Genap';
        cols.forEach(c => c.style.opacity = '1');
        showToast('Menampilkan seluruh 12 pekan kas', 'info');
      } else if (mode === 'recent') {
        btnRec.className = 'px-3 py-1 rounded-lg bg-white text-blue-700 shadow-xs border border-slate-200/80 font-bold transition-all';
        desc.innerText = 'Fokus 4 Pekan Terakhir (Pekan 9 - Pekan 12)';
        cols.forEach(c => {
          const p = parseInt(c.getAttribute('data-pekan'));
          c.style.opacity = p >= 9 ? '1' : '0.25';
        });
        showToast('Menyorot 4 pekan terkini', 'info');
      } else if (mode === 'month') {
        btnMon.className = 'px-3 py-1 rounded-lg bg-white text-blue-700 shadow-xs border border-slate-200/80 font-bold transition-all';
        desc.innerText = 'Agregasi Bulanan (Bln 1: P1-P4, Bln 2: P5-P8, Bln 3: P9-P12)';
        cols.forEach(c => c.style.opacity = '1');
        showToast('Menampilkan tren per bulan', 'info');
      }
    }

    // Chart Tooltip
    function showChartTip(e, title, cin, cout) {
      const tip = document.getElementById('chartTooltip');
      document.getElementById('ttTitle').innerText = title;
      document.getElementById('ttIn').innerText = cin;
      document.getElementById('ttOut').innerText = cout;

      const rect = e.currentTarget.getBoundingClientRect();
      const parentRect = document.getElementById('chartSvg').getBoundingClientRect();

      tip.style.left = (rect.left - parentRect.left + 5) + 'px';
      tip.style.top = '10px';
      tip.classList.remove('hidden');
    }

    function hideChartTip() {
      document.getElementById('chartTooltip').classList.add('hidden');
    }

    // Donut Interactions
    function donutHover(label, pct, val) {
      document.getElementById('dnLabel').innerText = label;
      document.getElementById('dnVal').innerText = val;
      document.getElementById('dnPct').innerText = pct + ' Anggaran';
    }

    function donutReset() {
      document.getElementById('dnLabel').innerText = 'Terealisasi';
      document.getElementById('dnVal').innerText = 'Rp 2.35M';
      document.getElementById('dnPct').innerText = '100% Sesuai Pos';
    }

    // Modal Helpers
    function openReceipt(title, amount, date, detail) {
      document.getElementById('recTitle').innerText = title;
      document.getElementById('recAmount').innerText = amount;
      document.getElementById('recDate').innerText = date;
      document.getElementById('recDetail').innerText = detail;
      document.getElementById('receiptModal').classList.remove('hidden');
    }

    function closeModal(id) {
      document.getElementById(id).classList.add('hidden');
    }

    function closeModalOnBackdrop(e, id) {
      if (e.target.id === id) closeModal(id);
    }
  </script>



</body></html>
