<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skema', function (Blueprint $table) {
            $table->string('skema_id', 10)->primary();
            $table->string('nama_skema', 225);
            $table->string('jenis_kelompok_binaan', 10)->comment('KPS or KTH');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skema');
    }
};
