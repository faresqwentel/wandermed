<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gol_darah',
        'riwayat_alergi',
        'kontak_darurat',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi ke JSON/Array.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom tertentu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================================================
    // RELASI ELOQUENT
    // =========================================================

    /**
     * Seorang wisatawan memiliki banyak riwayat kunjungan ke faskes.
     */
    public function riwayatKunjungan()
    {
        return $this->hasMany(RiwayatKunjungan::class);
    }

    /**
     * Faskes yang pernah dikunjungi wisatawan via tabel pivot riwayat_kunjungan.
     * withPivot() memungkinkan akses ke kolom extra di tabel pivot (label, catatan).
     */
    public function faskesKunjungan()
    {
        return $this->belongsToMany(Faskes::class, 'riwayat_kunjungan', 'user_id', 'faskes_id')
                    ->withPivot(['tanggal_kunjungan', 'label_warna', 'catatan_pribadi'])
                    ->withTimestamps();
    }

    /**
     * Wisatawan bisa membuat banyak laporan masalah ke admin.
     */
    public function laporanMasalah()
    {
        return $this->hasMany(LaporanMasalah::class);
    }
}
