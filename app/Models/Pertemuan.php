<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Jadwal;
use App\Models\Modul;
use App\Models\Laporan;
use App\Models\Presensi;
use App\Models\Nilai;
use App\Models\Praktikum;
use App\Models\PengumpulanLaporan;
use App\Models\Pretest;

class Pertemuan extends Model
{
    protected $fillable = [
        'id_praktikum',
        'id_jadwal',
        'nama_pertemuan',
        'pertemuan_ke',
        'deskripsi_pertemuan',
        'id_modul',
    ];

    /**
     * Pertemuan dimiliki oleh satu Praktikum
     */
    public function praktikum(): BelongsTo
    {
        return $this->belongsTo(Praktikum::class, 'id_praktikum');
    }

    /**
     * Pertemuan dimiliki oleh satu Jadwal
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    /**
     * Pertemuan memiliki satu Modul
     */
    public function modul(): HasOne
    {
        return $this->hasOne(Modul::class, 'id_pertemuan');
    }

    /**
     * Pertemuan memiliki satu Pretest
     */
    public function pretest(): HasOne
    {
        return $this->hasOne(Pretest::class, 'id_pertemuan');
    }

    /**
     * Pertemuan memiliki satu Laporan
     */
    public function laporan(): HasOne
    {
        return $this->hasOne(Laporan::class, 'id_pertemuan');
    }

    /**
     * Pertemuan memiliki banyak Presensi
     */
    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class, 'id_pertemuan');
    }

    // Alias singular untuk backward compat di controller/blade yang pakai ->presensi
    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'id_pertemuan');
    }

    /**
     * Pertemuan memiliki banyak Nilai
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class, 'id_pertemuan');
    }

    // Alias singular
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'id_pertemuan');
    }

    /**
     * Pertemuan memiliki banyak Pengumpulan Laporan
     */
    public function pengumpulanLaporans(): HasMany
    {
        return $this->hasMany(PengumpulanLaporan::class, 'id_pertemuan');
    }
}
