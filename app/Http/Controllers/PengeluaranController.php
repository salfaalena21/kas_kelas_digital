<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        return response()->json(Pengeluaran::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ]);

        $pengeluaran = Pengeluaran::create($validated);

        return response()->json($pengeluaran, 201);
    }

    public function show(Pengeluaran $pengeluaran)
    {
        return response()->json($pengeluaran);
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ]);

        $pengeluaran->update($validated);

        return response()->json($pengeluaran);
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return response()->json([
            'message' => 'Data pengeluaran berhasil dihapus'
        ]);
    }
}
