<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->string('laporan_id', 10)->primary();

            $table->string('jadwal_id', 10);
            $table->foreign('jadwal_id')
                  ->references('jadwal_id')
                  ->on('jadwal_pelaporan')
                  ->onDelete('cascade');

            $table->string('kelompok_id', 10);
            $table->foreign('kelompok_id')
                  ->references('kelompok_id')
                  ->on('kelompok_usaha')
                  ->onDelete('cascade');

            $table->decimal('jumlah_produksi', 15, 2)->nullable();
            $table->integer('jumlah_stup')->nullable();

            $table->enum('status_verifikasi_lembaga', ['pending', 'ditolak', 'diverifikasi'])->default('pending');
            $table->enum('status_verifikasi_penyuluh', ['pending', 'ditolak', 'diverifikasi'])->default('pending');
            $table->enum('status_validasi_seksi', ['pending', 'ditolak', 'divalidasi'])->default('pending');

            $table->timestamp('created_at')->nullable();
            $table->timestamp('update_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
