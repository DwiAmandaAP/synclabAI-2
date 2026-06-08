<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    protected $table = 'student_answers';
    
    protected $fillable = [
        'id_user',
        'id_pretest',
        'id_question',
        'user_answer',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Jawaban siswa dimiliki oleh satu User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Jawaban siswa dimiliki oleh satu Pretest
     */
    public function pretest(): BelongsTo
    {
        return $this->belongsTo(Pretest::class, 'id_pretest');
    }

    /**
     * Jawaban siswa dimiliki oleh satu Question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'id_question');
    }
}
