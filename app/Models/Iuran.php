<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'iuran';
    protected $primaryKey = 'id_iuran';

    protected $fillable = [
        'periode',
        'minggu',
        'nominal',
        'tanggal_jatuh_tempo',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'nominal' => 'decimal:2',
    ];

    // Satu iuran (tagihan) dibayarkan banyak pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_iuran', 'id_iuran');
    }
}
