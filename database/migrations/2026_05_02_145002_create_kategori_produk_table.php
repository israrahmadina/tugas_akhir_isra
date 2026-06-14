<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->string('kategori_id', 10)->primary();
            $table->string('nama_kategori', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_produk');
    }
};
