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
        Schema::create('pengumpulan_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pertemuan')->references('id')->on('pertemuans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_laporan')->references('id')->on('laporans')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file_path');
            $table->text('keterangan');
            $table->enum('status', ['Disubmit', 'Dalam Review', 'Diterima', 'Ditolak', 'Terlambat']);
            $table->integer('nilai')->nullable();
            $table->longText('komentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_laporan');
    }
};
