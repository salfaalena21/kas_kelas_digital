<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        return response()->json(
            Pembayaran::with(['siswa', 'iuran', 'petugas'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_iuran' => 'required|exists:iuran,id_iuran',
            'nominal_bayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode' => 'nullable|string',
            'status' => 'nullable|string',
            'id_user' => 'nullable|exists:users,id_user',
        ]);
        //(Validasi Anti-Duplikat) 
        $existing = Pembayaran::where('id_siswa', $request->id_siswa)
                              ->where('id_iuran', $request->id_iuran)
                              ->where('tanggal_bayar', $request->tanggal_bayar)
                              ->first();
        if ($existing) {
            // Jika data sudah ada, tolak dan kembalikan pesan error ke UI 
            return redirect()->back()->with('error', 'Data gagal disimpan! siswa tersebut sudah melakukan pembayaran untuk jenis iuran ini pada tanggal yang sama,');
        }
            // Jika tidak duplikat, simpan data baru ke database
            pembayaran::create([
                'id_siswa' => $request->id_siswa,
                'id_iuran' => $request->id_iuran,
                'nominal_bayar' => $request->nominal_bayar,
                'tanggal_bayar' => $request->tanggal_bayar,
                'metode' => $request->metode,
                'status' => $request->status,
                'id_user' => $request->id_user,
            ]);
    return redirect()->route('bendahara.dashboard')->with('success','Data pembayaran kas berhasil ditambahkan.');
    
            
        $iuran = \App\Models\Iuran::findOrFail($validated['id_iuran']);
        $validated['kembalian'] = max(0, $validated['nominal_bayar'] - $iuran->nominal);
        $validated['status'] = $validated['status'] ?? 'lunas';

        $pembayaran = Pembayaran::create($validated);

        return response()->json($pembayaran, 201);
    }

    public function show(Pembayaran $pembayaran)
    {
        return response()->json(
            $pembayaran->load(['siswa', 'iuran', 'petugas', 'bukti'])
        );
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_iuran' => 'required|exists:iuran,id_iuran',
            'nominal_bayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $pembayaran->update($validated);

        return response()->json($pembayaran);
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return response()->json([
            'message' => 'Data pembayaran berhasil dihapus'
        ]);
    }
}
