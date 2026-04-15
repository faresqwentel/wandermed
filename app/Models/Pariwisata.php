<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model: Pariwisata (Destinasi Wisata)
 *
 * Menyimpan data destinasi wisata yang terdaftar di WanderMed.
 * Pengelola wisata bisa mengisi info keselamatan agar wisatawan
 * mengetahui potensi bahaya dan faskes terdekat.
 */
class Pariwisata extends Model
{
    use HasFactory;

    protected $table = 'pariwisatas';

    protected $fillable = [
        'mitra_id',
        'nama_wisata',
        'kategori',
        'deskripsi',
        'alamat',
        'foto_path',
        'latitude',
        'longitude',
        'info_keselamatan',
        'kontak_wisata',
    ];

    protected $casts = [
        'latitude'  => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // =========================================================
    // RELASI ELOQUENT
    // =========================================================

    /**
     * Setiap destinasi wisata dimiliki oleh satu mitra pariwisata.
     * Relasi: Pariwisata -> belongsTo -> Mitra
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
}
