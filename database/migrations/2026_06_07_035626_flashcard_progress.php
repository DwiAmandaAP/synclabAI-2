<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flashcard_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_flashcard')->constrained('flashcards')->onDelete('cascade');
            $table->integer('interval')->default(1); // Jarak hari review berikutnya
            $table->float('ease_factor')->default(2.5); // Tingkat kemudahan kartu
            $table->integer('repetitions')->default(0); // Jumlah jawaban benar beruntun
            $table->date('next_review_date')->default(now()); // Kapan kartu harus muncul lagi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_progress');
    }
};
