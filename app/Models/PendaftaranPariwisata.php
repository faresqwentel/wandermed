<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model: PendaftaranPariwisata
 *
 * Merepresentasikan pengajuan destinasi wisata yang masuk dari form publik.
 * TIDAK memiliki akun login — pengelola wisata hanya mengisi form dan
 * menunggu konfirmasi Admin via email yang dicantumkan.
 */
class PendaftaranPariwisata extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_pariwisata';

    protected $fillable = [
        'nama_wisata',
        'kategori',
        'deskripsi',
        'alamat',
        'foto_path',
        'latitude',
        'longitude',
        'nama_pengelola',
        'email_kontak',
        'no_telp',
        'jam_buka',
        'jam_tutup',
        'harga_tiket',
        'status_review',
        'catatan_admin',
    ];

    protected $casts = [
        'latitude'     => 'float',
        'longitude'    => 'float',
        'harga_tiket'  => 'integer',
    ];

    // Scope: filter yang masih menunggu review
    public function scopeMenunggu($query)
    {
        return $query->where('status_review', 'menunggu');
    }

    // Scope: sudah disetujui (tampil di peta)
    public function scopeDisetujui($query)
    {
        return $query->where('status_review', 'disetujui');
    }
}
