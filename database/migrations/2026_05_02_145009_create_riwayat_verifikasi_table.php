<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_verifikasi', function (Blueprint $table) {
            $table->string('riwayat_id', 10)->primary();

            $table->string('user_id', 10);
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('user')
                  ->onDelete('cascade');

            $table->string('laporan_id', 10);
            $table->foreign('laporan_id')
                  ->references('laporan_id')
                  ->on('laporan')
                  ->onDelete('cascade');

            // Verifikasi Lembaga
            $table->enum('status_verifikasi_lembaga', ['pending', 'ditolak', 'diterima'])->nullable();
            $table->text('catatan_verifikasi_lembaga')->nullable();

            // Verifikasi Penyuluh
            $table->enum('status_verifikasi_penyuluh', ['pending', 'ditolak', 'diterima'])->nullable();
            $table->text('catatan_verifikasi_penyuluh')->nullable();

            // Validasi Seksi
            $table->enum('status_validasi_seksi', ['pending', 'ditolak', 'diterima'])->nullable();
            $table->text('catatan_validasi_seksi')->nullable();

            $table->timestamp('tanggal')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_verifikasi');
    }
};
