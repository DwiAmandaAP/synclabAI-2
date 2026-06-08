<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Import all related models
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Praktikum;
use App\Models\PendaftaranPraktikum;
use App\Models\PengumpulanLaporan;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nomor_induk',
        'nama',
        'email',
        'nohp',
        'role',
        'password',
    ];

    /**
     * User memiliki banyak Nilai
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class, 'id_user');
    }

    /**
     * User memiliki banyak Presensi
     */
    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class, 'id_user');
    }

    /**
     * User mengikuti banyak Praktikum (Many-to-Many via Pivot)
     */
    public function praktikums(): BelongsToMany
    { 
        return $this->belongsToMany(Praktikum::class, 'pendaftaran_praktikum', 'id_user', 'id_praktikum'); 
    }

    /**
     * User memiliki banyak Jadwal (sebagai dosen/asisten)
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_dosen');
    }

    /**
     * User memiliki banyak data pendaftaran
     */
    public function pendaftaranPraktikums(): HasMany
    {
        return $this->hasMany(PendaftaranPraktikum::class, 'id_user');
    }

    /**
     * User memiliki banyak Pengumpulan Laporan
     */
    public function pengumpulanLaporans(): HasMany
    {
        return $this->hasMany(PengumpulanLaporan::class, 'id_user');
    }

    /**
     * User memiliki satu Praktikum (jika role Dosen)
     */
    public function praktikum(): HasOne
    {
        return $this->hasOne(Praktikum::class, 'id_dosen');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}