<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flashcard extends Model
{
    protected $fillable = ['id_modul', 'front', 'back'];

    /**
     * Flashcard dimiliki oleh satu Modul
     */
    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class, 'id_modul');
    }

    /**
     * Flashcard memiliki banyak progress belajar siswa (Spaced Repetition)
     */
    public function progress(): HasMany
    {
        return $this->hasMany(FlashcardProgress::class, 'id_flashcard');
    }

    /**
     * Hubungan ke Pertemuan melalui Modul
     */
    public function pertemuan()
    {
        return $this->hasOneThrough(
            Pertemuan::class,
            Modul::class,
            'id',           // FK di Modul
            'id',           // FK di Pertemuan
            'id_modul',     // local key di Flashcard
            'id_pertemuan'  // local key di Modul
        );
    }
}
