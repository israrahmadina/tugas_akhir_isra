<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_pelaporan', function (Blueprint $table) {
            $table->string('bukti_id', 10)->primary();

            $table->string('laporan_id', 10);
            $table->foreign('laporan_id')
                  ->references('laporan_id')
                  ->on('laporan')
                  ->onDelete('cascade');

            $table->string('file_path', 255);
            $table->string('keterangan', 255)->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_pelaporan');
    }
};
