<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// Import all related models
use App\Models\Jadwal;
use App\Models\Pertemuan;
use App\Models\Nilai;
use App\Models\User;
use App\Models\Modul;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Praktikum extends Model
{
    protected $fillable = [
        'kode_praktikum',
        'nama_praktikum',
        'id_dosen', 
        'angkatan',
        'semester',
    ];

    /**
     * Relasi ke Jadwal
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_praktikum');
    }

    /**
     * Relasi ke Nilai
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class, 'id_praktikum');
    }

    /**
     * Relasi ke Dosen (User dengan role Dosen)
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    /**
     * Relasi ke Pertemuan
     */
    public function pertemuans(): HasMany
    {
        return $this->hasMany(Pertemuan::class, 'id_praktikum');
    }

    /**
     * Mendapatkan Modul melalui Pertemuan
     */
    public function moduls(): HasManyThrough
    {
        return $this->hasManyThrough(
            Modul::class, 
            Pertemuan::class, 
            'id_praktikum', // Foreign key on Pertemuan table
            'id_pertemuan', // Foreign key on Modul table
            'id',            // Local key on Praktikum table
            'id'             // Local key on Pertemuan table
        );
    }

    /**
     * Relasi Many-to-Many ke Asisten melalui tabel pivot pendaftaran_praktikum
     */
    public function asisten(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pendaftaran_praktikum', 'id_praktikum', 'id_user')
                    ->wherePivot('role', 'Asisten')
                    ->wherePivot('status', 'Dikonfirmasi')
                    ->withTimestamps();
    }

    /**
     * Relasi Many-to-Many ke Mahasiswa (Praktikan) melalui tabel pivot pendaftaran_praktikum
     */
    

    /*
    |--------------------------------------------------------------------------
    | Helper Methods (Custom Queries)
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan semua Asisten yang terdaftar di Jadwal praktikum ini
     */
    public function getAsistens()
    {
        return User::select('users.*')
            ->join('pendaftaran_praktikum', 'users.id', '=', 'pendaftaran_praktikum.id_user')
            ->join('jadwals', 'pendaftaran_praktikum.id_jadwal', '=', 'jadwals.id')
            ->where('jadwals.id_praktikum', $this->id)
            ->where('pendaftaran_praktikum.role', 'Asisten')
            ->distinct()
            ->get();
    }

    /**
     * Mendapatkan semua Praktikan yang terdaftar di Jadwal praktikum ini
     */
    public function getPraktikans()
    {
        return User::select('users.*')
            ->join('pendaftaran_praktikum', 'users.id', '=', 'pendaftaran_praktikum.id_user')
            ->join('jadwals', 'pendaftaran_praktikum.id_jadwal', '=', 'jadwals.id')
            ->where('jadwals.id_praktikum', $this->id)
            ->where('pendaftaran_praktikum.role', 'Praktikan')
            ->distinct()
            ->get();
    }
}