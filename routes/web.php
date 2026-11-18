<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Sajikan file upload dari disk publik (hindari konflik path public/storage di hosting).
// One-shot migrate helper (aman: token sederhana). Hapus/abaikan setelah setup.
Route::get('/__migrate/{token}', function (string $token) {
    if ($token !== 'smartkas-setup-2026') abort(404);
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return '<pre>OK migrate\n' . e(\Illuminate\Support\Facades\Artisan::output()) . '</pre>';
    } catch (\Throwable $e) {
        return '<pre>ERR ' . e($e->getMessage()) . '</pre>';
    }
});

// One-shot helper: set password = username untuk semua user kecuali admin (admin tetap admin123)
Route::get('/__fixpass/{token}', function (string $token) {
    if ($token !== 'smartkas-setup-2026') abort(404);
    try {
        $users = \App\Models\User::all();
        $updated = 0;
        foreach ($users as $u) {
            $username = (string) $u->username;
            if (strtolower($username) === 'admin') {
                $plain = 'admin123';
            } else {
                $plain = $username;
            }
            $u->password = \Illuminate\Support\Facades\Hash::make($plain);
            $u->password_hint = \Illuminate\Support\Facades\Crypt::encryptString($plain);
            $u->save();
            $updated++;
        }
        return '<pre>OK fixpass: ' . $updated . ' user diperbarui</pre>';
    } catch (\Throwable $e) {
        return '<pre>ERR ' . e($e->getMessage()) . '</pre>';
    }
});

// One-shot helper: seed iuran 6 bulan (Juni-November 2026) & lunasi semua siswa
Route::get('/__seed-lunas/{token}', function (string $token) {
    if ($token !== 'smartkas-setup-2026') abort(404);
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\LunasinIuranSeeder',
            '--force' => true,
        ]);
        return '<pre>OK seed lunas\n' . e(\Illuminate\Support\Facades\Artisan::output()) . '</pre>';
    } catch (\Throwable $e) {
        return '<pre>ERR ' . e($e->getMessage()) . '</pre>';
    }
});

// One-shot helper: ubah metode transfer -> tunai
Route::get('/__metode-tunai/{token}', function (string $token) {
    if ($token !== 'smartkas-setup-2026') abort(404);
    try {
        $updated = \App\Models\Pembayaran::where('metode', 'transfer')->update(['metode' => 'tunai']);
        return '<pre>OK metode tunai: ' . $updated . ' pembayaran diperbarui</pre>';
    } catch (\Throwable $e) {
        return '<pre>ERR ' . e($e->getMessage()) . '</pre>';
    }
});

// Diagnostik data (sementara)
Route::get('/__diag/{token}', function (string $token) {
    if ($token !== 'smartkas-setup-2026') abort(404);
    try {
        $periode6 = ['Juni 2026','Juli 2026','Agustus 2026','September 2026','Oktober 2026','November 2026'];
        $siswaCount = \App\Models\Siswa::count();
        $iuranTotal = \App\Models\Iuran::count();
        $iuran6 = \App\Models\Iuran::whereIn('periode', $periode6)->get(['id_iuran','periode','minggu','nominal']);
        $iuranByPeriode = \App\Models\Iuran::selectRaw('periode, COUNT(*) as c')->groupBy('periode')->orderBy('periode')->get();
        $pembTotal = \App\Models\Pembayaran::count();
        $pembLunas6 = \App\Models\Pembayaran::whereIn('id_iuran', $iuran6->pluck('id_iuran'))->where('status','lunas')->count();
        $pembDistinctSiswa6 = \App\Models\Pembayaran::whereIn('id_iuran', $iuran6->pluck('id_iuran'))->distinct('id_siswa')->count('id_siswa');
        $sampleIuran = \App\Models\Iuran::orderBy('id_iuran')->get(['id_iuran','periode','minggu','nominal']);
        $samplePemb = \App\Models\Pembayaran::with(['siswa','iuran'])->orderBy('id_pembayaran','desc')->limit(10)->get()->map(fn($p)=>[
            'id'=>$p->id_pembayaran,'siswa'=>$p->siswa->nama ?? null,'periode'=>$p->iuran->periode ?? null,'minggu'=>$p->iuran->minggu ?? null,'status'=>$p->status
        ]);
        return response()->json([
            'siswa_count'=>$siswaCount,
            'iuran_total'=>$iuranTotal,
            'iuran_6bulan_count'=>$iuran6->count(),
            'iuran_by_periode'=>$iuranByPeriode,
            'pembayaran_total'=>$pembTotal,
            'pembayaran_lunas_6bulan'=>$pembLunas6,
            'distinct_siswa_paid_6bulan'=>$pembDistinctSiswa6,
            'sample_iuran'=>$sampleIuran,
            'sample_pembayaran_latest'=>$samplePemb,
        ]);
    } catch (\Throwable $e) {
        return '<pre>ERR ' . e($e->getMessage()) . '</pre>';
    }
});

// Sajikan bukti langsung dari DB berdasarkan ID (paling andal)
Route::get('/bukti-img/{id}', function ($id) {
    try {
        $bukti = \App\Models\BuktiPembayaran::findOrFail($id);
        if (!empty($bukti->file_data) && preg_match('#^data:([^;]+);base64,(.+)$#', $bukti->file_data, $m)) {
            $bin = base64_decode($m[2], true);
            if ($bin !== false) {
                return response($bin, 200, [
                    'Content-Type' => $m[1],
                    'Content-Disposition' => 'inline; filename="' . addslashes($bukti->nama_file ?: ('bukti-' . $id)) . '"',
                ]);
            }
        }
        $path = ltrim(preg_replace('#^/?storage/#', '', (string)$bukti->file_path), '/');
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if ($path && $disk->exists($path)) {
            return $disk->response($path);
        }
        $full = storage_path('app/public/' . $path);
        if ($path && is_file($full)) return response()->file($full);
    } catch (\Throwable $e) {}
    abort(404);
})->name('bukti.img');

Route::get('/bukti-file/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);
    $path = ltrim($path, '/');
    $candidates = array_values(array_unique(array_filter([
        $path,
        preg_replace('#^storage/#', '', $path),
        preg_replace('#^public/#', '', $path),
        preg_replace('#^bukti-file/#', '', $path),
    ])));

    // 1) Coba sajikan dari data DB (base64) jika ada
    try {
        $base = basename($path);
        $bukti = \App\Models\BuktiPembayaran::query()
            ->whereNotNull('file_data')
            ->where(function ($q) use ($path, $base, $candidates) {
                foreach ($candidates as $c) {
                    $q->orWhere('file_path', 'like', '%' . $c)
                      ->orWhere('file_path', 'like', '%/storage/' . $c);
                }
                $q->orWhere('file_path', 'like', '%' . $base);
            })
            ->latest('id_bukti')
            ->first();
        if ($bukti && !empty($bukti->file_data) && preg_match('#^data:([^;]+);base64,(.+)$#', $bukti->file_data, $m)) {
            $bin = base64_decode($m[2], true);
            if ($bin !== false) {
                return response($bin, 200, [
                    'Content-Type' => $m[1],
                    'Content-Disposition' => 'inline; filename="' . addslashes($bukti->nama_file ?: $base) . '"',
                ]);
            }
        }
    } catch (\Throwable $e) {}

    // 2) Disk public
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    foreach ($candidates as $p) {
        try {
            if ($disk->exists($p)) {
                $full = $disk->path($p);
                $mime = @mime_content_type($full) ?: 'application/octet-stream';
                return response()->file($full, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="' . basename($full) . '"']);
            }
        } catch (\Throwable $e) {}
    }

    // 3) Fallback path absolut
    $roots = array_filter(array_unique([
        storage_path('app/public'),
        base_path('storage/app/public'),
        rtrim((string) (env('LARAVEL_STORAGE_PATH') ?: ''), '/\\') . '/app/public',
        '/data/storage/app/public',
    ]));
    foreach ($roots as $root) {
        foreach ($candidates as $p) {
            $full = rtrim($root, '/\\') . '/' . ltrim($p, '/\\');
            if (is_file($full)) {
                $mime = @mime_content_type($full) ?: 'application/octet-stream';
                return response()->file($full, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="' . basename($full) . '"']);
            }
        }
    }
    abort(404);
})->where('path', '.*')->name('bukti.file');

Route::get('/storage/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);
    $path = ltrim($path, '/');
    $disk = \Illuminate\Support\Facades\Storage::disk('public');

    $candidates = array_values(array_unique(array_filter([
        $path,
        preg_replace('#^storage/#', '', $path),
        preg_replace('#^public/#', '', $path),
    ])));

    foreach ($candidates as $p) {
        if ($disk->exists($p)) {
            $full = $disk->path($p);
            $mime = @mime_content_type($full) ?: 'application/octet-stream';
            return response()->file($full, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . basename($full) . '"',
            ]);
        }
    }

    // Fallback: coba path absolut (termasuk lokasi non-volume & LARAVEL_STORAGE_PATH)
    $roots = array_filter(array_unique([
        storage_path('app/public'),
        base_path('storage/app/public'),
        rtrim((string) (env('LARAVEL_STORAGE_PATH') ?: ''), '/\\') . '/app/public',
        '/data/storage/app/public',
    ]));
    foreach ($roots as $root) {
        foreach ($candidates as $p) {
            $full = rtrim($root, '/\\') . '/' . ltrim($p, '/\\');
            if (is_file($full)) {
                $mime = @mime_content_type($full) ?: 'application/octet-stream';
                return response()->file($full, [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="' . basename($full) . '"',
                ]);
            }
        }
    }

    abort(404);
})->where('path', '.*');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $totalSiswa = \App\Models\Siswa::count();
        $totalKelas = \App\Models\Kelas::count();
        $totalUser = \App\Models\User::count();
        $totalIuran = \App\Models\Iuran::count();
        $pemasukan = \App\Models\Pembayaran::sum('nominal_bayar');
        $pengeluaran = \App\Models\Pengeluaran::sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        // Grafik arus kas (Juni - Desember 2026)
        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];
        $start = \Carbon\Carbon::create(2026, 6, 1)->startOfMonth();
        $end = \Carbon\Carbon::create(2026, 12, 1)->startOfMonth();
        for ($bulan = $start->copy(); $bulan->lte($end); $bulan->addMonth()) {
            $chartLabels[] = $bulan->locale('id')->translatedFormat('M Y');
            $chartMasuk[] = (float) \App\Models\Pembayaran::whereYear('tanggal_bayar', $bulan->year)
                ->whereMonth('tanggal_bayar', $bulan->month)
                ->sum('nominal_bayar');
            $chartKeluar[] = (float) \App\Models\Pengeluaran::whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->sum('jumlah');
        }

        // Grafik status pembayaran (lunas vs belum lunas)
        $totalTagihan = $totalSiswa * $totalIuran;
        $totalLunas = min(\App\Models\Pembayaran::where('status', 'lunas')->count(), $totalTagihan);
        $belumLunas = max($totalTagihan - $totalLunas, 0);

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalKelas', 'totalUser', 'totalIuran',
            'pemasukan', 'pengeluaran', 'saldo',
            'chartLabels', 'chartMasuk', 'chartKeluar',
            'totalTagihan', 'totalLunas', 'belumLunas'
        ));
    })->name('admin.dashboard');

    Route::get('/users', function () {
        $users = \App\Models\User::with('siswa')
            ->orderByRaw("FIELD(role,'admin','bendahara','wali_kelas','siswa')")
            ->orderByRaw("CASE WHEN role='siswa' AND username REGEXP '^siswa[0-9]+$' THEN CAST(SUBSTRING(username,6) AS UNSIGNED) ELSE 0 END")
            ->orderBy('id_user')
            ->paginate(50);
        return view('admin.users.index', compact('users'));
    })->name('admin.users.index');
    Route::post('/users', function (Illuminate\Http\Request $request) {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,bendahara,siswa,wali_kelas',
            'nis' => 'nullable',
        ]);
        $user = \App\Models\User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'password_hint' => \Illuminate\Support\Facades\Crypt::encryptString($request->password),
            'role' => $request->role,
        ]);
        if ($request->role === 'siswa' && $request->filled('nis')) {
            $siswa = \App\Models\Siswa::where('nis', $request->nis)->first();
            if ($siswa && !$siswa->id_user) {
                $siswa->id_user = $user->id_user;
                $siswa->save();
            }
        }
        return back()->with('success', 'User berhasil ditambahkan.');
    })->name('admin.users.store');
    Route::put('/users/{user}', function (Illuminate\Http\Request $request, \App\Models\User $user) {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id_user . ',id_user',
            'role' => 'required|in:admin,bendahara,siswa,wali_kelas',
            'password' => 'nullable|string|min:6',
            'nis' => 'nullable|string|max:30',
        ]);
        $user->nama = $data['nama'];
        $user->username = $data['username'];
        $user->role = $data['role'];
        if (!empty($data['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
            $user->password_hint = \Illuminate\Support\Facades\Crypt::encryptString($data['password']);
        }
        $user->save();

        if (($data['role'] ?? null) === 'siswa' && $request->filled('nis')) {
            $nis = trim((string) $request->nis);
            $siswa = \App\Models\Siswa::where('nis', $nis)->first();
            if ($siswa && (!$siswa->id_user || (int) $siswa->id_user === (int) $user->id_user)) {
                \App\Models\Siswa::where('id_user', $user->id_user)
                    ->where('id_siswa', '!=', $siswa->id_siswa)
                    ->update(['id_user' => null]);
                $siswa->id_user = $user->id_user;
                $siswa->save();
            }
        }

        return back()->with('success', 'User berhasil diperbarui.');
    })->name('admin.users.update');
    Route::delete('/users/{user}', function (\App\Models\User $user) {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    })->name('admin.users.destroy');

    Route::get('/siswa', function () {
        $siswa = \App\Models\Siswa::with(['user', 'kelas'])->orderBy('nama')->get();
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $users = \App\Models\User::where('role', 'siswa')->whereDoesntHave('siswa')->orderBy('nama')->get();
        return view('admin.siswa.index', compact('siswa', 'kelas', 'users'));
    })->name('admin.siswa.index');
    Route::post('/siswa', function (Illuminate\Http\Request $request) {
        $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nama' => 'required|string|max:100',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user|unique:siswa,id_user',
        ]);
        \App\Models\Siswa::create($request->only('nis', 'nama', 'id_kelas', 'id_user'));
        return back()->with('success', 'Siswa berhasil ditambahkan.');
    })->name('admin.siswa.store');
    Route::put('/siswa/{siswa}', function (Illuminate\Http\Request $request, \App\Models\Siswa $siswa) {
        $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'nama' => 'required|string|max:100',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user|unique:siswa,id_user,' . $siswa->id_siswa . ',id_siswa',
        ]);
        $siswa->update($request->only('nis', 'nama', 'id_kelas', 'id_user'));
        return back()->with('success', 'Siswa berhasil diperbarui.');
    })->name('admin.siswa.update');
    Route::delete('/siswa/{siswa}', function (\App\Models\Siswa $siswa) {
        $siswa->delete();
        return back()->with('success', 'Siswa berhasil dihapus.');
    })->name('admin.siswa.destroy');

    Route::get('/laporan', function () {
        $pemasukan = \App\Models\Pembayaran::sum('nominal_bayar');
        $pengeluaran = \App\Models\Pengeluaran::sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;
        $pengeluaranList = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->limit(50)->get();

        // Rekap pemasukan per siswa (rapi, tanpa scroll panjang per transaksi)
        $bayarMap = \App\Models\Pembayaran::selectRaw('id_siswa, COUNT(*) as jml_bayar, SUM(nominal_bayar) as total_bayar')
            ->groupBy('id_siswa')
            ->get()
            ->keyBy('id_siswa');
        $rekapPemasukanSiswa = \App\Models\Siswa::with('kelas')->orderBy('nama')->get()->map(function ($s) use ($bayarMap) {
            $b = $bayarMap[$s->id_siswa] ?? null;
            return [
                'nama' => $s->nama,
                'kelas' => $s->kelas->nama_kelas ?? '-',
                'jml_bayar' => (int) ($b->jml_bayar ?? 0),
                'total_bayar' => (float) ($b->total_bayar ?? 0),
            ];
        });

        // Laporan kas per siswa (berdasarkan total semua iuran/tagihan)
        $iuranAllIds = \App\Models\Iuran::pluck('id_iuran');
        $totalIuranAll = $iuranAllIds->count();
        $lunasMap = \App\Models\Pembayaran::whereIn('id_iuran', $iuranAllIds)
            ->where('status', 'lunas')
            ->selectRaw('id_siswa, COUNT(DISTINCT id_iuran) as lunas_count')
            ->groupBy('id_siswa')
            ->pluck('lunas_count', 'id_siswa');
        $rekapPerSiswa = \App\Models\Siswa::with('kelas')->orderBy('nama')->get()->map(function ($s) use ($lunasMap, $totalIuranAll) {
            $lunas = (int) ($lunasMap[$s->id_siswa] ?? 0);
            return [
                'nama' => $s->nama,
                'kelas' => $s->kelas->nama_kelas ?? '-',
                'lunas' => $lunas,
                'belum' => max($totalIuranAll - $lunas, 0),
            ];
        });
        $totalIuran6Bulan = $totalIuranAll; // kompatibilitas view
        $periodeLabel = 'Total semua iuran (Juni - Desember 2026)';

        return view('admin.laporan.index', compact('pemasukan', 'pengeluaran', 'saldo', 'pengeluaranList', 'rekapPemasukanSiswa', 'rekapPerSiswa', 'totalIuran6Bulan', 'periodeLabel'));
    })->name('admin.laporan.index');
});

Route::middleware(['auth', 'role:bendahara'])->prefix('bendahara')->group(function () {

    Route::get('/dashboard', function () {
        $kelas = \App\Models\Kelas::first();
        $totalSiswa = \App\Models\Siswa::count();
        $totalPemasukan = \App\Models\Pembayaran::sum('nominal_bayar');
        $totalPengeluaran = \App\Models\Pengeluaran::sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
        $totalPembayaran = \App\Models\Pembayaran::count();
        $pembayaranTerbaru = \App\Models\Pembayaran::with(['siswa', 'iuran'])
            ->orderBy('tanggal_bayar', 'desc')->limit(10)->get();

        $periodeAktif = optional(\App\Models\Iuran::orderBy('id_iuran', 'desc')->first())->periode ?? 'Minggu berjalan';

        // Grafik arus kas (Juni - Desember 2026)
        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];
        $start = \Carbon\Carbon::create(2026, 6, 1)->startOfMonth();
        $end = \Carbon\Carbon::create(2026, 12, 1)->startOfMonth();
        for ($bulan = $start->copy(); $bulan->lte($end); $bulan->addMonth()) {
            $chartLabels[] = $bulan->locale('id')->translatedFormat('M Y');
            $chartMasuk[] = (float) \App\Models\Pembayaran::whereYear('tanggal_bayar', $bulan->year)
                ->whereMonth('tanggal_bayar', $bulan->month)
                ->sum('nominal_bayar');
            $chartKeluar[] = (float) \App\Models\Pengeluaran::whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->sum('jumlah');
        }

        // Grafik status pembayaran (lunas vs belum lunas)
        $totalIuran = \App\Models\Iuran::count();
        $totalTagihan = $totalSiswa * $totalIuran;
        $totalLunas = min(\App\Models\Pembayaran::where('status', 'lunas')->count(), $totalTagihan);
        $belumLunas = max($totalTagihan - $totalLunas, 0);

        return view('bendahara.dashboard', compact(
            'kelas', 'totalSiswa', 'totalPemasukan', 'totalPengeluaran', 'saldo',
            'totalPembayaran', 'pembayaranTerbaru', 'periodeAktif',
            'chartLabels', 'chartMasuk', 'chartKeluar',
            'totalTagihan', 'totalLunas', 'belumLunas'
        ));
    })->name('bendahara.dashboard');

    // Data Siswa (lama)
    Route::get('/siswa', function () {
        $kelas = \App\Models\Kelas::first();
        $semuaKelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $siswa = \App\Models\Siswa::with(['kelas', 'user'])->orderBy('nama')->get();
        $usersSiswa = \App\Models\User::where('role', 'siswa')->orderBy('nama')->get();
        return view('siswa.index', compact('kelas', 'semuaKelas', 'siswa', 'usersSiswa'));
    })->name('bendahara.siswa.index');

    Route::post('/siswa', function (Illuminate\Http\Request $request) {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);
        \App\Models\Siswa::create($request->only('nis', 'nama', 'id_kelas'));

        if ($request->filled('username') && $request->filled('password')) {
            $user = \App\Models\User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'password_hint' => \Illuminate\Support\Facades\Crypt::encryptString($request->password),
                'role' => 'siswa',
            ]);
            $siswa = \App\Models\Siswa::where('nis', $request->nis)->first();
            if ($siswa) {
                $siswa->id_user = $user->id_user;
                $siswa->save();
            }
        }
        return back()->with('success', 'Data siswa berhasil ditambahkan.');
    })->name('bendahara.siswa.store');

    Route::put('/siswa/{siswa}', function (Illuminate\Http\Request $request, \App\Models\Siswa $siswa) {
        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'nama' => 'required',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user',
        ]);
        $data = $request->only('nis', 'nama', 'id_user');
        if ($request->filled('id_kelas')) {
            $data['id_kelas'] = $request->id_kelas;
        }
        $siswa->update($data);
        return back()->with('success', 'Data siswa berhasil diperbarui.');
    })->name('bendahara.siswa.update');

    Route::delete('/siswa/{siswa}', function (\App\Models\Siswa $siswa) {
        $siswa->delete();
        return back()->with('success', 'Data siswa berhasil dihapus.');
    })->name('bendahara.siswa.destroy');

    // Data User
    Route::get('/user', function () {
        $users = \App\Models\User::with(['siswa', 'siswa.kelas'])
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'bendahara' THEN 2 WHEN 'wali_kelas' THEN 3 WHEN 'siswa' THEN 4 ELSE 5 END")
            ->orderByRaw("CASE WHEN role='siswa' THEN CAST(SUBSTRING_INDEX(username, 'siswa', -1) AS UNSIGNED) ELSE 0 END")
            ->orderBy('id_user')
            ->get();
        $kelas = \App\Models\Kelas::first();
        $siswaTanpaAkun = \App\Models\Siswa::whereNull('id_user')->get();
        return view('user.index', compact('users', 'kelas', 'siswaTanpaAkun'));
    })->name('bendahara.user.index');

    Route::put('/user/{user}', function (Illuminate\Http\Request $request, \App\Models\User $user) {
        $data = $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username,' . $user->id_user . ',id_user',
            'role' => 'required|in:admin,bendahara,siswa,wali_kelas',
        ]);

        $user->nama = $data['nama'];
        $user->username = $data['username'];
        $user->role = $data['role'];
        $user->save();

        if ($request->role === 'siswa' && $request->filled('nis')) {
            $siswa = \App\Models\Siswa::where('nis', $request->nis)->first();
            if ($siswa && !$siswa->id_user) {
                $siswa->id_user = $user->id_user;
                $siswa->save();
            }
        }
        return back()->with('success', 'User berhasil diperbarui.');
    })->name('bendahara.user.update');

    Route::delete('/user/{user}', function (\App\Models\User $user) {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    })->name('bendahara.user.destroy');

    // Iuran
    Route::get('/iuran', function () {
        $iuran = \App\Models\Iuran::withCount('pembayaran')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->orderBy('id_iuran', 'asc')
            ->get();
        return view('iuran.index', compact('iuran'));
    })->name('bendahara.iuran.index');

    Route::post('/iuran', function (Illuminate\Http\Request $request) {
        $request->validate([
            'periode' => 'required',
            'nominal' => 'required|numeric',
        ]);
        \App\Models\Iuran::create($request->only('periode', 'minggu', 'nominal', 'tanggal_jatuh_tempo'));
        return back()->with('success', 'Data iuran berhasil ditambahkan.');
    })->name('bendahara.iuran.store');

    Route::put('/iuran/{iuran}', function (Illuminate\Http\Request $request, \App\Models\Iuran $iuran) {
        $request->validate([
            'periode' => 'required',
            'nominal' => 'required|numeric',
        ]);
        $iuran->update($request->only('periode', 'minggu', 'nominal', 'tanggal_jatuh_tempo'));
        return back()->with('success', 'Data iuran berhasil diperbarui.');
    })->name('bendahara.iuran.update');

    Route::delete('/iuran/{iuran}', function (\App\Models\Iuran $iuran) {
        $iuran->delete();
        return back()->with('success', 'Data iuran berhasil dihapus.');
    })->name('bendahara.iuran.destroy');

    // Pembayaran
    Route::get('/pembayaran', function () {
        $pembayaran = \App\Models\Pembayaran::with(['siswa', 'iuran'])->orderBy('tanggal_bayar', 'desc')->get();
        $siswa = \App\Models\Siswa::with('kelas')->orderBy('nama')->get();
        $iuran = \App\Models\Iuran::orderBy('id_iuran', 'desc')->get();
        return view('pembayaran.index', compact('pembayaran', 'siswa', 'iuran'));
    })->name('bendahara.pembayaran');

    // --- VALIDASI ANTI-DUPLIKAT (SKKNI 3) DITAMBAHKAN DI SINI ---
    Route::post('/pembayaran', function (Illuminate\Http\Request $request) {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_iuran' => 'required|exists:iuran,id_iuran',
            'tanggal_bayar' => 'required|date',
            'nominal_bayar' => 'required|numeric',
            'status' => 'required',
        ]);

        // Cek apakah siswa sudah pernah membayar iuran yang sama sebelumnya
        $sudahBayar = \App\Models\Pembayaran::where('id_siswa', $request->id_siswa)
                                           ->where('id_iuran', $request->id_iuran)
                                           ->exists();

        if ($sudahBayar) {
            return back()
                ->withInput()
                ->with('error', 'Peringatan: Siswa ini sudah tercatat membayar untuk iuran/periode tersebut!');
        }

        \App\Models\Pembayaran::create([
            'id_siswa' => $request->id_siswa,
            'id_iuran' => $request->id_iuran,
            'id_user' => \Illuminate\Support\Facades\Auth::id(),
            'tanggal_bayar' => $request->tanggal_bayar,
            'nominal_bayar' => $request->nominal_bayar,
            'kembalian' => $request->kembalian ?? 0,
            'metode' => $request->metode ?? 'transfer',
            'status' => $request->status,
        ]);
        return back()->with('success', 'Pembayaran berhasil dicatat.');
    })->name('bendahara.pembayaran.store');
    // -------------------------------------------------------------

    Route::put('/pembayaran/{pembayaran}', function (Illuminate\Http\Request $request, \App\Models\Pembayaran $pembayaran) {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_iuran' => 'required|exists:iuran,id_iuran',
            'tanggal_bayar' => 'required|date',
            'nominal_bayar' => 'required|numeric',
            'status' => 'required',
        ]);
        $pembayaran->update([
            'id_siswa' => $request->id_siswa,
            'id_iuran' => $request->id_iuran,
            'tanggal_bayar' => $request->tanggal_bayar,
            'nominal_bayar' => $request->nominal_bayar,
            'kembalian' => $request->kembalian ?? $pembayaran->kembalian,
            'metode' => $request->metode ?? $pembayaran->metode,
            'status' => $request->status,
        ]);
        return back()->with('success', 'Pembayaran berhasil diperbarui.');
    })->name('bendahara.pembayaran.update');

    Route::delete('/pembayaran/{pembayaran}', function (\App\Models\Pembayaran $pembayaran) {
        $pembayaran->delete();
        return back()->with('success', 'Data pembayaran berhasil dihapus.');
    })->name('bendahara.pembayaran.destroy');

    // Pengeluaran
    Route::get('/pengeluaran', function () {
        $pengeluaran = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->get();
        return view('pengeluaran.index', compact('pengeluaran'));
    })->name('bendahara.pengeluaran');

    Route::post('/pengeluaran', function (Illuminate\Http\Request $request) {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $saldoKas = (float) \App\Models\Pembayaran::sum('nominal_bayar') - (float) \App\Models\Pengeluaran::sum('jumlah');
        $jumlah = (float) $request->jumlah;
        if ($jumlah > $saldoKas) {
            return back()->withInput()->withErrors([
                'jumlah' => 'Saldo kas tidak mencukupi. Saldo tersedia Rp ' . number_format(max($saldoKas, 0), 0, ',', '.') . ', pengeluaran diminta Rp ' . number_format($jumlah, 0, ',', '.') . '.',
            ])->with('error', 'Pengeluaran gagal: saldo kas tidak mencukupi.');
        }
        \App\Models\Pengeluaran::create($request->only('tanggal', 'keterangan', 'jumlah'));
        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    })->name('bendahara.pengeluaran.store');

    Route::put('/pengeluaran/{pengeluaran}', function (Illuminate\Http\Request $request, \App\Models\Pengeluaran $pengeluaran) {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'jumlah' => 'required|numeric|min:1',
        ]);
        $saldoKas = (float) \App\Models\Pembayaran::sum('nominal_bayar') - (float) \App\Models\Pengeluaran::sum('jumlah');
        $saldoTersedia = $saldoKas + (float) $pengeluaran->jumlah; // kembalikan nominal lama sebelum ganti
        $jumlah = (float) $request->jumlah;
        if ($jumlah > $saldoTersedia) {
            return back()->withInput()->withErrors([
                'jumlah' => 'Saldo kas tidak mencukupi. Saldo tersedia Rp ' . number_format(max($saldoTersedia, 0), 0, ',', '.') . ', pengeluaran diminta Rp ' . number_format($jumlah, 0, ',', '.') . '.',
            ])->with('error', 'Pengeluaran gagal: saldo kas tidak mencukupi.');
        }
        $pengeluaran->update($request->only('tanggal', 'keterangan', 'jumlah'));
        return back()->with('success', 'Pengeluaran berhasil diperbarui.');
    })->name('bendahara.pengeluaran.update');

    Route::delete('/pengeluaran/{pengeluaran}', function (\App\Models\Pengeluaran $pengeluaran) {
        $pengeluaran->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    })->name('bendahara.pengeluaran.destroy');

    // Bukti Pembayaran
    Route::get('/bukti', function () {
        $bukti = \App\Models\BuktiPembayaran::with(['pembayaran.siswa', 'pembayaran.iuran'])
            ->orderBy('id_bukti', 'desc')->get();
        return view('bukti.index', compact('bukti'));
    })->name('bendahara.bukti.index');

    Route::patch('/bukti/{id}', function (Illuminate\Http\Request $request, $id) {
        $bukti = \App\Models\BuktiPembayaran::findOrFail($id);
        $bukti->status_verifikasi = $request->status_verifikasi;
        $bukti->save();
        return back()->with('success', 'Status verifikasi diperbarui.');
    })->name('bendahara.bukti.verify');

    Route::delete('/bukti/{id}', function ($id) {
        $bukti = \App\Models\BuktiPembayaran::findOrFail($id);
        $bukti->delete();
        return back()->with('success', 'Bukti berhasil dihapus.');
    })->name('bendahara.bukti.destroy');

    Route::get('/transaksi', function () {
        return view('bendahara.transaksi');
    })->name('bendahara.transaksi');
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {

    Route::get('/dashboard', function () {
        $siswaSaya = \Illuminate\Support\Facades\Auth::user()->siswa;
        $totalPemasukan = \App\Models\Pembayaran::sum('nominal_bayar');
        $totalPengeluaran = \App\Models\Pengeluaran::sum('jumlah');
        $iuranAktif = \App\Models\Iuran::orderBy('id_iuran', 'desc')->first();

        $riwayatSaya = collect();
        if ($siswaSaya) {
            $riwayatSaya = \App\Models\Pembayaran::with('iuran')
                ->where('id_siswa', $siswaSaya->id_siswa)
                ->orderBy('tanggal_bayar', 'desc')->get();
        }

        $rekapSiswa = [];
        $siswaList = \App\Models\Siswa::with('pembayaran')->orderBy('nama')->get();
        foreach ($siswaList as $s) {
            $lunasCount = $s->pembayaran->where('status', 'lunas')->count();
            $totalTagihan = \App\Models\Iuran::count();
            $rekapSiswa[] = [
                'nama' => $s->nama,
                'lunas' => $lunasCount >= $totalTagihan && $totalTagihan > 0,
                'keterangan' => $lunasCount >= $totalTagihan && $totalTagihan > 0
                    ? 'Lunas ' . $lunasCount . '/' . $totalTagihan
                    : 'Belum bayar ' . $lunasCount . '/' . $totalTagihan,
            ];
        }

        $pengeluaran = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->limit(5)->get();

        return view('siswa.dashboard', compact(
            'siswaSaya', 'totalPemasukan', 'totalPengeluaran', 'iuranAktif',
            'riwayatSaya', 'rekapSiswa', 'pengeluaran'
        ));
    })->name('siswa.dashboard');

    Route::get('/riwayat', function () {
        $siswaSaya = \Illuminate\Support\Facades\Auth::user()->siswa;
        $riwayat = collect();
        if ($siswaSaya) {
            $riwayat = \App\Models\Pembayaran::with('iuran')
                ->where('id_siswa', $siswaSaya->id_siswa)
                ->orderBy('tanggal_bayar', 'desc')->get();
        }
        return view('siswa.riwayat', compact('siswaSaya', 'riwayat'));
    })->name('siswa.riwayat');

    Route::get('/pengeluaran', function () {
        $pengeluaran = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->get();
        return view('siswa.pengeluaran', compact('pengeluaran'));
    })->name('siswa.pengeluaran');

    Route::get('/upload-bukti', function () {
        $siswaSaya = \Illuminate\Support\Facades\Auth::user()->siswa;
        $buktiSaya = collect();
        $iuranList = \App\Models\Iuran::orderBy('id_iuran', 'desc')->get();
        $iuranDipilih = $iuranList->first();
        if ($siswaSaya) {
            $buktiSaya = \App\Models\BuktiPembayaran::with(['pembayaran.iuran'])
                ->whereHas('pembayaran', fn ($q) => $q->where('id_siswa', $siswaSaya->id_siswa))
                ->orderBy('id_bukti', 'desc')->get();
            $lunasIuranIds = \App\Models\Pembayaran::where('id_siswa', $siswaSaya->id_siswa)
                ->where('status', 'lunas')->pluck('id_iuran')->all();
            $belum = $iuranList->first(fn ($i) => ! in_array($i->id_iuran, $lunasIuranIds));
            if ($belum) {
                $iuranDipilih = $belum;
            }
        }
        return view('siswa.upload', compact('siswaSaya', 'buktiSaya', 'iuranList', 'iuranDipilih'));
    })->name('siswa.upload');

    Route::post('/upload-bukti', function (Illuminate\Http\Request $request) {
        $request->validate([
            'id_iuran' => 'required|exists:iuran,id_iuran',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,bmp,pdf|max:5120',
        ]);
        $siswaSaya = \Illuminate\Support\Facades\Auth::user()->siswa;
        if (! $siswaSaya) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $iuran = \App\Models\Iuran::findOrFail($request->id_iuran);

        $pembayaran = \App\Models\Pembayaran::where('id_siswa', $siswaSaya->id_siswa)
            ->where('id_iuran', $iuran->id_iuran)
            ->orderBy('id_pembayaran', 'desc')
            ->first();

        if (! $pembayaran) {
            $pembayaran = \App\Models\Pembayaran::create([
                'id_siswa' => $siswaSaya->id_siswa,
                'id_iuran' => $iuran->id_iuran,
                'id_user' => null,
                'tanggal_bayar' => now()->toDateString(),
                'nominal_bayar' => $iuran->nominal ?? 0,
                'kembalian' => 0,
                'metode' => 'transfer',
                'status' => 'pending',
            ]);
        } else {
            $pembayaran->metode = 'transfer';
            $pembayaran->save();
        }

        $file = $request->file('proof_file');
        $mime = $file->getMimeType() ?: 'application/octet-stream';
        $data = @file_get_contents($file->getRealPath());
        $fileData = $data !== false ? ('data:' . $mime . ';base64,' . base64_encode($data)) : null;

        $relPath = 'bukti/' . $file->hashName();
        $storedPath = $relPath;
        try {
            $dir = storage_path('app/public/bukti');
            if (! is_dir($dir)) { @mkdir($dir, 0775, true); }
            $stored = $file->storeAs('bukti', $file->hashName(), 'public');
            if (is_string($stored) && $stored !== '') {
                $storedPath = $stored;
            } else {
                $target = $dir . '/' . $file->hashName();
                if (@move_uploaded_file($file->getRealPath(), $target) || @copy($file->getRealPath(), $target)) {
                    $storedPath = $relPath;
                }
            }
        } catch (\Throwable $e) {}

        $payload = [
            'id_pembayaran' => $pembayaran->id_pembayaran,
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => 'storage/' . ltrim($storedPath, '/'),
            'status_verifikasi' => 'pending',
        ];
        try {
            \App\Models\BuktiPembayaran::create($payload + ['file_data' => $fileData]);
        } catch (\Throwable $e) {
            \App\Models\BuktiPembayaran::create($payload);
        }
        return back()->with('success', 'Bukti berhasil diupload. Foto/bukti dapat langsung dilihat.');
    })->name('siswa.upload.store');
});

Route::middleware(['auth', 'role:wali_kelas'])->prefix('walikelas')->group(function () {

    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $kelas = \App\Models\Kelas::where('wali_kelas', $user->nama)->first();

        $siswaList = $kelas
            ? \App\Models\Siswa::with('pembayaran')->where('id_kelas', $kelas->id_kelas)->orderBy('nama')->get()
            : collect();

        $totalSiswa = $siswaList->count();
        $totalIuran = \App\Models\Iuran::count();
        $totalPemasukan = 0;
        $rekapSiswa = [];
        $lunasCount = 0;

        foreach ($siswaList as $s) {
            $bayar = $s->pembayaran->where('status', 'lunas')->count();
            $totalPemasukan += (float) $s->pembayaran->sum('nominal_bayar');
            $lunasSemua = ($totalIuran > 0 && $bayar >= $totalIuran);
            if ($lunasSemua) $lunasCount++;
            $rekapSiswa[] = [
                'nama' => $s->nama,
                'bayar_count' => $bayar,
                'lunas_semua' => $lunasSemua,
            ];
        }

        $totalPengeluaran = \App\Models\Pengeluaran::sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
        $persenLunas = $totalSiswa > 0 ? (int) round($lunasCount / $totalSiswa * 100) : 0;
        $pengeluaran = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->limit(10)->get();

        return view('walikelas.dashboard', compact(
            'kelas', 'totalSiswa', 'totalIuran', 'totalPemasukan', 'totalPengeluaran',
            'saldo', 'persenLunas', 'rekapSiswa', 'pengeluaran'
        ));
    })->name('walikelas.dashboard');

    Route::get('/siswa', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $kelas = \App\Models\Kelas::where('wali_kelas', $user->nama)->first();
        $siswa = $kelas
            ? \App\Models\Siswa::withCount('pembayaran')->where('id_kelas', $kelas->id_kelas)->orderBy('nama')->get()
            : collect();
        return view('walikelas.siswa', compact('siswa', 'kelas'));
    })->name('walikelas.siswa');

    Route::get('/laporan', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $kelas = \App\Models\Kelas::where('wali_kelas', $user->nama)->first();

        $totalPemasukan = 0;
        $rekapPerSiswa = collect();
        $rekapPemasukanSiswa = collect();
        $totalIuran6Bulan = 0;
        $periodeLabel = 'Total semua iuran (Juni - Desember 2026)';

        $iuranAllIds = \App\Models\Iuran::pluck('id_iuran');
        $totalIuran6Bulan = $iuranAllIds->count();

        if ($kelas) {
            $siswaIds = \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
            $totalPemasukan = (float) \App\Models\Pembayaran::whereIn('id_siswa', $siswaIds)->sum('nominal_bayar');

            $bayarMap = \App\Models\Pembayaran::whereIn('id_siswa', $siswaIds)
                ->selectRaw('id_siswa, COUNT(*) as jml_bayar, SUM(nominal_bayar) as total_bayar')
                ->groupBy('id_siswa')
                ->get()
                ->keyBy('id_siswa');

            $lunasMap = \App\Models\Pembayaran::whereIn('id_siswa', $siswaIds)
                ->whereIn('id_iuran', $iuranAllIds)
                ->where('status', 'lunas')
                ->selectRaw('id_siswa, COUNT(DISTINCT id_iuran) as lunas_count')
                ->groupBy('id_siswa')
                ->pluck('lunas_count', 'id_siswa');

            $rekapPemasukanSiswa = \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->orderBy('nama')->get()->map(function ($s) use ($bayarMap) {
                $b = $bayarMap[$s->id_siswa] ?? null;
                return [
                    'nama' => $s->nama,
                    'jml_bayar' => (int) ($b->jml_bayar ?? 0),
                    'total_bayar' => (float) ($b->total_bayar ?? 0),
                ];
            });

            $rekapPerSiswa = \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->orderBy('nama')->get()->map(function ($s) use ($lunasMap, $totalIuran6Bulan) {
                $lunas = (int) ($lunasMap[$s->id_siswa] ?? 0);
                return [
                    'nama' => $s->nama,
                    'lunas' => $lunas,
                    'belum' => max($totalIuran6Bulan - $lunas, 0),
                ];
            });
        }

        $pengeluaran = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->get();
        $totalPengeluaran = (float) $pengeluaran->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('walikelas.laporan', compact(
            'kelas', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran', 'saldo',
            'rekapPemasukanSiswa', 'rekapPerSiswa', 'totalIuran6Bulan', 'periodeLabel'
        ));
    })->name('walikelas.laporan');
});