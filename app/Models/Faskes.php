<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model: Faskes (Fasilitas Kesehatan)
 *
 * Menyimpan data operasional dan spasial setiap faskes.
 */
class Faskes extends Model
{
    use HasFactory;

    protected $table = 'faskes';

    protected $fillable = [
        'mitra_id',
        'nama_faskes',
        'jenis_faskes',
        'alamat',
        'no_telp',
        'foto_path',
        'latitude',
        'longitude',
        'status_operasional',
        'dukungan_bpjs',
        'layanan_tersedia',
        'pengumuman',
        'pesan_admin',
    ];

    protected $casts = [
        'layanan_tersedia'   => 'array',
        'dukungan_bpjs'      => 'boolean',
        'latitude'           => 'decimal:8',
        'longitude'          => 'decimal:8',
    ];

    // =========================================================
    // RELASI ELOQUENT
    // =========================================================

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function riwayatKunjungan(): HasMany
    {
        return $this->hasMany(RiwayatKunjungan::class);
    }

    public function wisatawan(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'riwayat_kunjungan', 'faskes_id', 'user_id')
                    ->withPivot(['tanggal_kunjungan', 'label_warna', 'catatan_pribadi'])
                    ->withTimestamps();
    }

    public function laporanMasalah(): HasMany
    {
        return $this->hasMany(LaporanMasalah::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(JadwalDokter::class);
    }

    public function ulasans(): HasMany
    {
        return $this->hasMany(UlasanFaskes::class);
    }

    // =========================================================
    // QUERY SCOPES
    // =========================================================

    public function scopeBuka(Builder $query): Builder
    {
        return $query->where('status_operasional', 'open');
    }

    public function scopeBpjs(Builder $query): Builder
    {
        return $query->where('dukungan_bpjs', true);
    }

    public function scopeJenis(Builder $query, string $jenis): Builder
    {
        return $query->where('jenis_faskes', $jenis);
    }

    /**
     * Menghitung dan menggabungkan jarak (Haversine) dalam radius kilometer.
     */
    public function scopeNearby(Builder $query, float $lat, float $lng, float $radius = 5): Builder
    {
        return $query->selectRaw("
                *, ( 6371 * ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                    COS(RADIANS(longitude) - RADIANS(?)) +
                    SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }
}
