<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'id_pretest', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'
    ];

    /**
     * Question dimiliki oleh satu Pretest
     */
    public function pretest(): BelongsTo
    {
        return $this->belongsTo(Pretest::class, 'id_pretest');
    }

    /**
     * Question memiliki banyak Student Answer records
     */
    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'id_question');
    }
}