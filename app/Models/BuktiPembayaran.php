<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuktiPembayaran extends Model
{
    protected $table = 'bukti_pembayaran';
    protected $primaryKey = 'id_bukti';

    protected $fillable = [
        'id_pembayaran',
        'nama_file',
        'file_path',
        'status_verifikasi',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }
}
