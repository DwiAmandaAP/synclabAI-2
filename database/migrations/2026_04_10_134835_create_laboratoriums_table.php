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
        Schema::create('laboratoriums', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laboratorium');
            $table->string('nama_laboratorium');
            $table->string('lokasi');
            $table->integer('kapasitas');
            $table->foreignId('kepala_lab')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['Terpakai', 'Tersedia']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoriums');
    }
};
