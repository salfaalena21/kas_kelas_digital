<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sesuai ERD: IURAN (id_iuran PK, periode, minggu, nominal, tanggal_jatuh_tempo)
     * Master Tagihan kas per minggu/periode.
     */
    public function up(): void
    {
        Schema::create('iuran', function (Blueprint $table) {
            $table->id('id_iuran');
            $table->string('periode');
            $table->string('minggu')->nullable();
            $table->decimal('nominal', 12, 2);
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iuran');
    }
};
