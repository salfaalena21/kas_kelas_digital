<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

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
        return view('admin.dashboard', compact('totalSiswa', 'totalKelas', 'totalUser', 'totalIuran', 'pemasukan', 'pengeluaran', 'saldo'));
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
        ]);
        $user->nama = $data['nama'];
        $user->username = $data['username'];
        $user->role = $data['role'];
        if (!empty($data['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($data['password']);
            $user->password_hint = \Illuminate\Support\Facades\Crypt::encryptString($data['password']);
        }
        $user->save();
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

    Route::get('/iuran', function () {
        $iuran = \App\Models\Iuran::withCount('pembayaran')->orderBy('id_iuran', 'desc')->get();
        return view('admin.iuran.index', compact('iuran'));
    })->name('admin.iuran.index');
    Route::post('/iuran', function (Illuminate\Http\Request $request) {
        $request->validate([
            'periode' => 'required|string|max:50',
            'minggu' => 'nullable|string|max:50',
            'nominal' => 'required|numeric|min:0',
        ]);
        \App\Models\Iuran::create($request->only('periode', 'minggu', 'nominal', 'tanggal_jatuh_tempo'));
        return back()->with('success', 'Iuran berhasil ditambahkan.');
    })->name('admin.iuran.store');
    Route::put('/iuran/{iuran}', function (Illuminate\Http\Request $request, \App\Models\Iuran $iuran) {
        $request->validate([
            'periode' => 'required|string|max:50',
            'minggu' => 'nullable|string|max:50',
            'nominal' => 'required|numeric|min:0',
        ]);
        $iuran->update($request->only('periode', 'minggu', 'nominal', 'tanggal_jatuh_tempo'));
        return back()->with('success', 'Iuran berhasil diperbarui.');
    })->name('admin.iuran.update');
    Route::delete('/iuran/{iuran}', function (\App\Models\Iuran $iuran) {
        $iuran->delete();
        return back()->with('success', 'Iuran berhasil dihapus.');
    })->name('admin.iuran.destroy');

    Route::get('/pengeluaran', function () {
        $pengeluaran = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->get();
        return view('admin.pengeluaran.index', compact('pengeluaran'));
    })->name('admin.pengeluaran.index');
    Route::post('/pengeluaran', function (Illuminate\Http\Request $request) {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
        ]);
        \App\Models\Pengeluaran::create($request->only('tanggal', 'keterangan', 'jumlah'));
        return back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    })->name('admin.pengeluaran.store');
    Route::put('/pengeluaran/{pengeluaran}', function (Illuminate\Http\Request $request, \App\Models\Pengeluaran $pengeluaran) {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
        ]);
        $pengeluaran->update($request->only('tanggal', 'keterangan', 'jumlah'));
        return back()->with('success', 'Pengeluaran berhasil diperbarui.');
    })->name('admin.pengeluaran.update');
    Route::delete('/pengeluaran/{pengeluaran}', function (\App\Models\Pengeluaran $pengeluaran) {
        $pengeluaran->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    })->name('admin.pengeluaran.destroy');

    Route::get('/laporan', function () {
        $pemasukan = \App\Models\Pembayaran::sum('nominal_bayar');
        $pengeluaran = \App\Models\Pengeluaran::sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;
        $pembayaran = \App\Models\Pembayaran::with(['siswa', 'iuran'])->orderBy('tanggal_bayar', 'desc')->limit(50)->get();
        $pengeluaranList = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->limit(50)->get();
        return view('admin.laporan.index', compact('pemasukan', 'pengeluaran', 'saldo', 'pembayaran', 'pengeluaranList'));
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

        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];
        $iuranList = \App\Models\Iuran::orderBy('id_iuran')->get();
        foreach ($iuranList as $i) {
            $chartLabels[] = trim(($i->periode ?? '') . ' ' . ($i->minggu ?? ''));
            $chartMasuk[] = (float) \App\Models\Pembayaran::where('id_iuran', $i->id_iuran)->sum('nominal_bayar');
            $chartKeluar[] = 0;
        }
        if (empty($chartLabels)) {
            $chartLabels = ['Pekan 1', 'Pekan 2', 'Pekan 3', 'Pekan 4', 'Pekan 5', 'Pekan 6'];
            $chartMasuk = [0, 0, 0, 0, 0, 0];
            $chartKeluar = [0, 0, 0, 0, 0, 0];
        }

        return view('bendahara.dashboard', compact(
            'kelas', 'totalSiswa', 'totalPemasukan', 'totalPengeluaran', 'saldo',
            'totalPembayaran', 'pembayaranTerbaru', 'periodeAktif',
            'chartLabels', 'chartMasuk', 'chartKeluar'
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
        $iuran = \App\Models\Iuran::withCount('pembayaran')->orderBy('id_iuran', 'desc')->get();
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

    Route::post('/pembayaran', function (Illuminate\Http\Request $request) {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_iuran' => 'required|exists:iuran,id_iuran',
            'tanggal_bayar' => 'required|date',
            'nominal_bayar' => 'required|numeric',
            'status' => 'required',
        ]);
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
        return back()->with('success', 'Pembayaran berhasil dihapus.');
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
            'jumlah' => 'required|numeric',
        ]);
        \App\Models\Pengeluaran::create($request->only('tanggal', 'keterangan', 'jumlah'));
        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    })->name('bendahara.pengeluaran.store');

    Route::put('/pengeluaran/{pengeluaran}', function (Illuminate\Http\Request $request, \App\Models\Pengeluaran $pengeluaran) {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'jumlah' => 'required|numeric',
        ]);
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
        $pembayaranSaya = collect();
        $buktiSaya = collect();
        if ($siswaSaya) {
            $pembayaranSaya = \App\Models\Pembayaran::with('iuran')
                ->where('id_siswa', $siswaSaya->id_siswa)
                ->where('status', '!=', 'lunas')
                ->orderBy('tanggal_bayar', 'desc')->get();

            $buktiSaya = \App\Models\BuktiPembayaran::with(['pembayaran.iuran'])
                ->whereHas('pembayaran', fn ($q) => $q->where('id_siswa', $siswaSaya->id_siswa))
                ->orderBy('id_bukti', 'desc')->get();
        }
        return view('siswa.upload', compact('siswaSaya', 'pembayaranSaya', 'buktiSaya'));
    })->name('siswa.upload');

    Route::post('/upload-bukti', function (Illuminate\Http\Request $request) {
        $request->validate([
            'id_pembayaran' => 'required|exists:pembayaran,id_pembayaran',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $siswaSaya = \Illuminate\Support\Facades\Auth::user()->siswa;

        $file = $request->file('proof_file');
        $path = $file->store('bukti', 'public');

        \App\Models\BuktiPembayaran::create([
            'id_pembayaran' => $request->id_pembayaran,
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => 'storage/' . $path,
            'status_verifikasi' => 'pending',
        ]);
        return back()->with('success', 'Bukti berhasil diupload.');
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

        $pembayaran = collect();
        $totalPemasukan = 0;
        if ($kelas) {
            $siswaIds = \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
            $pembayaran = \App\Models\Pembayaran::with(['siswa', 'iuran'])
                ->whereIn('id_siswa', $siswaIds)
                ->orderBy('tanggal_bayar', 'desc')->get();
            $totalPemasukan = (float) $pembayaran->sum('nominal_bayar');
        }

        $pengeluaran = \App\Models\Pengeluaran::orderBy('tanggal', 'desc')->get();
        $totalPengeluaran = (float) $pengeluaran->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('walikelas.laporan', compact(
            'kelas', 'pembayaran', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran', 'saldo'
        ));
    })->name('walikelas.laporan');
});
