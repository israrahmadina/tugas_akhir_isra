<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
                Schema::create('user', function (Blueprint $table) {
                        $table->string('user_id', 10)->primary();
                        $table->string('role_id', 10);
                        $table->foreign('role_id')->references('role_id')->on('roles');

                        $table->string('nama', 100);
                        $table->string('email', 100);
                        $table->string('password', 255);
                        $table->string('jabatan', 255)->nullable();
                        $table->string('contact_person', 15)->nullable();
                        $table->string('foto_profile', 50)->nullable();
                        $table->string('token_registrasi', 255)->nullable();

                        $table->timestamp('created_at')->nullable();
                        $table->timestamp('updated_at')->nullable();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
