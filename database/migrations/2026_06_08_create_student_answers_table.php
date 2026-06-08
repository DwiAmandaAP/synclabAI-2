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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_pretest')->constrained('pretests')->onDelete('cascade');
            $table->foreignId('id_question')->constrained('questions')->onDelete('cascade');
            $table->char('user_answer', 1)->nullable(); // A, B, C, D atau null jika tidak dijawab
            $table->boolean('is_correct')->nullable(); // true/false atau null jika belum dinilai
            $table->timestamps();
            
            // Unique constraint agar user hanya bisa menjawab 1x per pertanyaan
            $table->unique(['id_user', 'id_pretest', 'id_question']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
