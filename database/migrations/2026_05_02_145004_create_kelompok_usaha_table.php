<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok_usaha', function (Blueprint $table) {
            $table->string('kelompok_id', 10)->primary();

            $table->string('user_id', 10);
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('user')
                  ->onDelete('cascade');

            $table->string('nip_penyuluh', 10)->nullable();
            $table->foreign('nip_penyuluh')
                  ->references('nip_penyuluh')
                  ->on('penyuluh')
                  ->onDelete('set null');

            $table->string('lembaga_id', 10)->nullable();
            $table->foreign('lembaga_id')
                  ->references('lembaga_id')
                  ->on('lembaga')
                  ->onDelete('set null');

            $table->string('skema_id', 10)->nullable();
            $table->foreign('skema_id')
                  ->references('skema_id')
                  ->on('skema')
                  ->onDelete('set null');

            $table->string('produk_id', 10)->nullable();
            $table->foreign('produk_id')
                  ->references('produk_id')
                  ->on('produk')
                  ->onDelete('set null');

            $table->string('nama_usaha', 225);
            $table->string('legalitas_perizinan', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('alamat_lengkap');
            $table->string('foto_produk', 225)->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('update_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok_usaha');
    }
};
