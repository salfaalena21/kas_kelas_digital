<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sesuai ERD: BUKTI_PEMBAYARAN
     * id_bukti PK, id_pembayaran FK, nama_file, file_path, status_verifikasi
     */
    public function up(): void
    {
        Schema::create('bukti_pembayaran', function (Blueprint $table) {
            $table->id('id_bukti');
            $table->unsignedBigInteger('id_pembayaran');
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('status_verifikasi')->default('pending');
            $table->timestamps();

            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_pembayaran');
    }
};
