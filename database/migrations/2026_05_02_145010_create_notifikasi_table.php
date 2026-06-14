<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->string('notifikasi_id', 10)->primary();

            $table->string('user_id', 10);
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('user')
                  ->onDelete('cascade');

            $table->string('riwayat_id', 10)->nullable();
            $table->foreign('riwayat_id')
                  ->references('riwayat_id')
                  ->on('riwayat_verifikasi')
                  ->onDelete('cascade');

            $table->text('pesan');
            $table->boolean('is_read')->default(false);

            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
