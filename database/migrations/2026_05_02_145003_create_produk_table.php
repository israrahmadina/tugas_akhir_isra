<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('produk_id', 10)->primary();

            $table->string('kategori_id', 50);
            $table->foreign('kategori_id')
                  ->references('kategori_id')
                  ->on('kategori_produk')
                  ->onDelete('cascade');

            $table->string('nama_produk', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};