<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardProgress extends Model
{
    protected $table = 'flashcard_progress';
    
    protected $fillable = [
        'id_user', 'id_flashcard', 'interval', 'ease_factor', 'repetitions', 'next_review_date'
    ];

    protected $casts = [
        'next_review_date' => 'date',
    ];

    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class, 'id_flashcard');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}