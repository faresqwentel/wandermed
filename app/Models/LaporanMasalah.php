<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model: LaporanMasalah
 *
 * Merepresentasikan tiket komplain/laporan dari wisatawan.
 * Admin memantau dan menyelesaikan laporan ini di Dashboard Admin.
 * Contoh: Laporan koordinat peta salah, jam operasional tidak sesuai, dsb.
 */
class LaporanMasalah extends Model
{
    use HasFactory;

    protected $table = 'laporan_masalah';

    protected $fillable = [
        'user_id',
        'faskes_id',
        'subjek',
        'deskripsi',
        'status',
        'catatan_penyelesaian',
    ];

    // =========================================================
    // RELASI ELOQUENT
    // =========================================================

    /**
     * Laporan ini dibuat oleh seorang wisatawan (bisa null jika anonim).
     * Relasi: LaporanMasalah -> belongsTo -> User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Laporan ini merujuk ke sebuah faskes (bisa null jika laporan umum).
     * Relasi: LaporanMasalah -> belongsTo -> Faskes
     */
    public function faskes()
    {
        return $this->belongsTo(Faskes::class);
    }

    // =========================================================
    // HELPER: Badge Status
    // =========================================================

    /**
     * Mendapatkan class CSS badge berdasarkan status laporan.
     * Berguna untuk render badge di dashboard admin Blade view.
     *
     * Penggunaan: $laporan->badgeClass
     */
    public function getBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'wm-badge yellow',
            'on_review' => 'wm-badge blue',
            'resolved'  => 'wm-badge green',
            default     => 'wm-badge',
        };
    }
}
