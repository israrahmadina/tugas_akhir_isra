<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pelaporan', function (Blueprint $table) {
            $table->string('jadwal_id', 10)->primary();

            $table->string('user_id_seksi', 10);
            $table->foreign('user_id_seksi')
                  ->references('user_id')
                  ->on('user')
                  ->onDelete('cascade');

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelaporan');
    }
};
