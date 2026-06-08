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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pertemuan')->references('id')->on('pertemuans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('nilai_pretest');
            $table->integer('nilai_laporan');
            $table->integer('nilai_total');
            $table->integer('nilai_akhir');
            $table->longText('komentar')->nullable();
            $table->enum('status', ['Pending', 'Terkonfirmasi']);
            $table->timestamps();
            
            // Unique constraint: 1 user hanya bisa punya 1 nilai per pertemuan
            $table->unique(['id_pertemuan', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
