<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modul extends Model
{
    protected $fillable = [
        'id_pertemuan',
        'judul_modul',
        'filepath',
        'deskripsi',
    ];

    /**
     * Modul dimiliki oleh satu Pertemuan
     */
    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }

    /**
     * Modul memiliki banyak Flashcard
     */
    public function flashcards(): HasMany
    {
        return $this->hasMany(Flashcard::class, 'id_modul');
    }

    /**
     * Hubungan ke Praktikum melalui Pertemuan
     */
    public function praktikum()
    {
        return $this->hasOneThrough(
            Praktikum::class,
            Pertemuan::class,
            'id',           // FK di Pertemuan
            'id',           // FK di Praktikum
            'id_pertemuan', // local key di Modul
            'id_praktikum'  // local key di Pertemuan
        );
    }
}
