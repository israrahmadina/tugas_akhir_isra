<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lembaga', function (Blueprint $table) {
            $table->string('lembaga_id', 10)->primary();

            $table->string('user_id', 10);
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('user')
                  ->onDelete('cascade');

            $table->string('nip_penyuluh', 10);
            $table->foreign('nip_penyuluh')
                  ->references('nip_penyuluh')
                  ->on('penyuluh')
                  ->onDelete('cascade');

            $table->string('nama_lembaga', 200);
            $table->string('ketua', 200);
            $table->string('kode_lembaga', 20);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lembaga');
    }
};
