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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_praktikum')->references('id')->on('praktikums')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_dosen')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_laboratorium')->references('id')->on('laboratoriums')->onDelete('cascade')->onUpdate('cascade');
            $table->string('hari');      
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('jumlah_max_peserta');
            $table->enum('status', ['Dibuka', 'Penuh', 'Selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
