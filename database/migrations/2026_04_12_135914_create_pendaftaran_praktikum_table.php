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
        Schema::create('pendaftaran_praktikum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal')->references('id')->on('jadwals')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('role', ['Praktikan', 'Asisten']);
            $table->timestamps();
            
            // Unique constraint: 1 user hanya bisa daftar 1x ke 1 jadwal
            $table->unique(['id_jadwal', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_praktikum');
    }
};
