<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengumpulanLaporan extends Model
{
    protected $table = 'pengumpulan_laporan';

    protected $fillable = [
        'id_pertemuan',
        'id_user',
        'file_path',
        'keterangan',
        'status',
        'nilai',
        'komentar',
    ];

    /**
     * Pengumpulan Laporan dimiliki oleh satu Pertemuan
     */
    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }

    /**
     * Pengumpulan Laporan dimiliki oleh satu User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}