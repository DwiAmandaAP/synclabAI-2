<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratorium extends Model
{
    protected $table = 'laboratoriums';
    protected $fillable = [
        'kode_laboratorium',
        'nama_laboratorium',
        'lokasi',
        'kapasitas',
        'kepala_lab',
        'status',
    ];

    /**
     * Laboratorium memiliki banyak Jadwal
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_laboratorium');
    }

    /**
     * Kepala lab adalah User
     */
    public function kepalaLab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_lab');
    }
}
