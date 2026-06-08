<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel moduls yang sudah ada di ERD kamu
            $table->foreignId('id_modul')->constrained('moduls')->onDelete('cascade');
            $table->text('front'); // Pertanyaan / Kata Kunci
            $table->text('back');  // Jawaban / Penjelasan ringkas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flashcards');
    }
};