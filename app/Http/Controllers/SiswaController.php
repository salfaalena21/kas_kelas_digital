<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        return response()->json(Siswa::with(['kelas', 'user'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        $siswa = Siswa::create($validated);

        return response()->json($siswa, 201);
    }

    public function show(Siswa $siswa)
    {
        return response()->json($siswa->load(['kelas', 'user', 'pembayaran']));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'nama' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        $siswa->update($validated);

        return response()->json($siswa);
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return response()->json([
            'message' => 'Data siswa berhasil dihapus'
        ]);
    }
}
