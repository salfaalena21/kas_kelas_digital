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
