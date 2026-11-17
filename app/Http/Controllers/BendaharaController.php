public function store(Request $request)
    {
        // 1. Validasi SKKNI 3: Cek apakah siswa sudah pernah membayar iuran yang sama
        $sudahBayar = Pembayaran::where('id_siswa', $request->id_siswa)
                               ->where('id_iuran', $request->id_iuran)
                               ->exists();

        if ($sudahBayar) {
            // Jika sudah ada, kembalikan ke form dengan pesan error (ditangkap SKKNI 5)
            return redirect()->back()
                ->withInput()
                ->with('error', 'Peringatan: Siswa ini sudah tercatat membayar untuk iuran/periode tersebut!');
        }

        // 2. Jika belum ada, simpan data pembayaran baru ke database
        Pembayaran::create([
            'id_siswa' => $request->id_siswa,
            'id_iuran' => $request->id_iuran,
            'nominal_bayar' => $request->nominal_bayar,
            'kembalian' => $request->kembalian,
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode' => $request->metode,
            'status' => $request->status,
        ]);

        return redirect()->route('bendahara.pembayaran')->with('success', 'Pembayaran berhasil dicatat.');
    }