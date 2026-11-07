<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_siswa',
        'id_iuran',
        'id_user',
        'nominal_bayar',
        'kembalian',
        'tanggal_bayar',
        'metode',
        'status',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal_bayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
    ];

    // Relasi sesuai ERD
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'id_iuran', 'id_iuran');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function bukti()
    {
        return $this->hasOne(BuktiPembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
