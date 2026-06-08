<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    protected $fillable = [
        'id_pertemuan',
        'id_user',
        'nilai_pretest',
        'nilai_laporan',
        'nilai_total',
        'nilai_akhir',
        'komentar',
        'status',
    ];

    /**
     * Nilai dimiliki oleh satu Pertemuan
     */
    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }

    /**
     * Nilai dimiliki oleh satu User (Praktikan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
