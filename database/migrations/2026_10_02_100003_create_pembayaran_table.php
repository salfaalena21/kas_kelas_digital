<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sesuai ERD: PEMBAYARAN
     * id_pembayaran PK, id_siswa FK (Siswa yang membayar),
     * id_iuran FK (Jenis Tagihan), id_user FK (Petugas pencatat),
     * nominal_bayar, kembalian (nominal_bayar - nominal iuran),
     * tanggal_bayar, metode, status
     */
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_iuran');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->decimal('nominal_bayar', 12, 2);
            $table->decimal('kembalian', 12, 2)->default(0);
            $table->date('tanggal_bayar');
            $table->string('metode')->nullable();
            $table->string('status')->default('lunas');
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->cascadeOnDelete();
            $table->foreign('id_iuran')->references('id_iuran')->on('iuran')->cascadeOnDelete();
            $table->foreign('id_user')->references('id_user')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
