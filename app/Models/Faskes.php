<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model: Faskes (Fasilitas Kesehatan)
 *
 * Menyimpan data operasional dan spasial setiap faskes.
 * Koordinat latitude/longitude digunakan langsung oleh Leaflet.js
 * untuk rendering marker di peta interaktif.
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
    ];

    protected $casts = [
        // Cast layanan_tersedia dari JSON string ke PHP array otomatis
        'layanan_tersedia'   => 'array',
        'dukungan_bpjs'      => 'boolean',
        'latitude'           => 'decimal:8',
        'longitude'          => 'decimal:8',
    ];

    // =========================================================
    // RELASI ELOQUENT
    // =========================================================

    /**
     * Setiap faskes dimiliki oleh satu mitra (pemilik/pengelola).
     * Relasi: Faskes -> belongsTo -> Mitra
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    /**
     * Faskes bisa muncul di banyak riwayat kunjungan wisatawan.
     * Relasi: Faskes -> hasMany -> RiwayatKunjungan
     */
    public function riwayatKunjungan()
    {
        return $this->hasMany(RiwayatKunjungan::class);
    }

    /**
     * Wisatawan yang pernah mengunjungi faskes ini.
     * Relasi Many-to-Many via tabel pivot riwayat_kunjungan.
     */
    public function wisatawan()
    {
        return $this->belongsToMany(User::class, 'riwayat_kunjungan', 'faskes_id', 'user_id')
                    ->withPivot(['tanggal_kunjungan', 'label_warna', 'catatan_pribadi'])
                    ->withTimestamps();
    }

    /**
     * Laporan masalah yang berkaitan dengan faskes ini.
     */
    public function laporanMasalah()
    {
        return $this->hasMany(LaporanMasalah::class);
    }

    // =========================================================
    // QUERY SCOPES (Filter Siap Pakai)
    // =========================================================

    /**
     * Scope: hanya faskes yang sedang buka.
     * Penggunaan: Faskes::buka()->get()
     */
    public function scopeBuka($query)
    {
        return $query->where('status_operasional', 'open');
    }

    /**
     * Scope: hanya faskes yang menerima BPJS.
     * Penggunaan: Faskes::bpjs()->get()
     */
    public function scopeBpjs($query)
    {
        return $query->where('dukungan_bpjs', true);
    }

    /**
     * Scope: filter berdasarkan jenis faskes.
     * Penggunaan: Faskes::jenisRs()->get()
     */
    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis_faskes', $jenis);
    }
}
