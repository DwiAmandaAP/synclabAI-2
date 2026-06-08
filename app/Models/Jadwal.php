<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Laboratorium;
use App\Models\Pertemuan;
use App\Models\Praktikum;
use App\Models\PendaftaranPraktikum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    protected $fillable = [
        'id_praktikum',
        'id_dosen',
        'id_laboratorium',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'jumlah_max_peserta',
        'status'
    ];

    public function praktikum()
    {
        return $this->belongsTo(Praktikum::class, 'id_praktikum');
    }

    /**
     * Jadwal memiliki satu Laboratorium
     */
    public function laboratorium(): BelongsTo
    {
        return $this->belongsTo(Laboratorium::class, 'id_laboratorium');
    }
    
    public function pertemuan(): HasMany
    {
        return $this->hasMany(Pertemuan::class, 'id_jadwal');
    }
    
    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    /**
     * Jadwal memiliki banyak Pendaftaran
     */
    public function pendaftarans(): HasMany
    {
        return $this->hasMany(PendaftaranPraktikum::class, 'id_jadwal');
    }
}
