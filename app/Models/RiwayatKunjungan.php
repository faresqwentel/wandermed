<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model: RiwayatKunjungan
 *
 * Model pivot (junction) yang menghubungkan User (wisatawan) dan Faskes.
 * Ini adalah backbone dari fitur "Riwayat Kunjungan" di Dashboard Wisatawan.
 * Setiap baris = satu kunjungan seorang wisatawan ke satu faskes.
 */
class RiwayatKunjungan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kunjungan';

    protected $fillable = [
        'user_id',
        'faskes_id',
        'tanggal_kunjungan',
        'label_warna',
        'catatan_pribadi',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    // =========================================================
    // RELASI ELOQUENT
    // =========================================================

    /**
     * Riwayat ini milik seorang wisatawan.
     * Relasi: RiwayatKunjungan -> belongsTo -> User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Riwayat ini mereferensikan sebuah faskes.
     * Relasi: RiwayatKunjungan -> belongsTo -> Faskes
     */
    public function faskes()
    {
        return $this->belongsTo(Faskes::class);
    }

    // =========================================================
    // HELPER: Mapping label warna ke teks deskriptif
    // =========================================================

    /**
     * Mendapatkan teks label berdasarkan nilai warna.
     * Berguna untuk ditampilkan di view blade (badge tabel).
     *
     * Penggunaan: $riwayat->labelTeks
     */
    public function getLabelTeksAttribute(): string
    {
        return match($this->label_warna) {
            'green'  => 'Sangat Direkomendasikan',
            'yellow' => 'Standar',
            'red'    => 'Tidak Direkomendasikan',
            default  => 'Belum Dinilai',
        };
    }
}
