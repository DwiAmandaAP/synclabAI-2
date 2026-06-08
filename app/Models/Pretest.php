<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pretest extends Model
{
    protected $fillable = ['id_pertemuan', 'judul_kuis'];

    /**
     * Pretest dimiliki oleh satu Pertemuan
     */
    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }

    /**
     * Pretest memiliki banyak Question
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'id_pretest');
    }

    /**
     * Pretest memiliki banyak Student Answer records
     */
    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'id_pretest');
    }
}