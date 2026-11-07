<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;

class KasKelasController extends Controller
{
    /**
     * Mengambil seluruh data kas kelas sesuai ERD
     * (Siswa, Iuran, Pembayaran, Pengeluaran)
     */
    public function index()
    {
        $siswa = Siswa::with('kelas:id_kelas,nama_kelas')
            ->select('id_siswa', 'nis', 'nama', 'id_kelas')
            ->get();

        $iuran = Iuran::orderBy('id_iuran')->get();

        $pembayaran = Pembayaran::with(['siswa:id_siswa,nama', 'iuran:id_iuran,periode,minggu'])
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($b) {
                return [
                    'id_pembayaran' => $b->id_pembayaran,
                    'id_siswa' => $b->id_siswa,
                    'siswa_nama' => $b->siswa->nama ?? '-',
                    'id_iuran' => $b->id_iuran,
                    'periode' => $b->iuran->periode ?? '-',
                    'nominal_bayar' => (float) $b->nominal_bayar,
                    'kembalian' => (float) $b->kembalian,
                    'tanggal_bayar' => $b->tanggal_bayar,
                    'metode' => $b->metode,
                    'status' => $b->status,
                ];
            });

        $totalMasuk = Pembayaran::sum('nominal_bayar');
        $totalKeluar = Pengeluaran::sum('jumlah');

        return response()->json([
            'status' => 'success',
            'summary' => [
                'total_masuk' => (float) $totalMasuk,
                'total_keluar' => (float) $totalKeluar,
                'saldo_aktif' => (float) ($totalMasuk - $totalKeluar),
            ],
            'siswa' => $siswa,
            'iuran' => $iuran,
            'pembayaran' => $pembayaran,
        ]);
    }

    /**
     * Simpan kolektif pembayaran kas (satu iuran dibayar banyak siswa)
     */
    public function storeKasMasuk(Request $request)
    {
        $validated = $request->validate([
            'id_iuran' => 'required|exists:iuran,id_iuran',
            'payments' => 'required|array',
            'payments.*.id_siswa' => 'required|exists:siswa,id_siswa',
            'payments.*.nominal_bayar' => 'required|numeric|min:0',
        ]);

        $iuran = Iuran::findOrFail($validated['id_iuran']);

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($validated['payments'] as $payment) {
                Pembayaran::create([
                    'id_siswa' => $payment['id_siswa'],
                    'id_iuran' => $validated['id_iuran'],
                    'id_user' => $request->user()?->id_user,
                    'nominal_bayar' => $payment['nominal_bayar'],
                    'kembalian' => max(0, $payment['nominal_bayar'] - $iuran->nominal),
                    'tanggal_bayar' => now()->toDateString(),
                    'metode' => 'tunai',
                    'status' => 'lunas',
                ]);
                $count++;
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran kas berhasil disimpan!',
                'created_count' => $count,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload bukti pembayaran (relasi bukti_pembayaran -> pembayaran)
     */
    public function storeUploadProof(Request $request)
    {
        $request->validate([
            'id_pembayaran' => 'required|exists:pembayaran,id_pembayaran',
            'nama_file' => 'nullable|string',
            'proof_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $filePath = $request->file('proof_file')->store('bukti', 'public');

        $bukti = \App\Models\BuktiPembayaran::create([
            'id_pembayaran' => $request->id_pembayaran,
            'nama_file' => $request->nama_file ?? $request->file('proof_file')->getClientOriginalName(),
            'file_path' => '/storage/' . $filePath,
            'status_verifikasi' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bukti berhasil diunggah!',
            'data' => $bukti,
        ], 201);
    }
}
