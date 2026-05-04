<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    protected $fillable = ['faskes_id', 'nama_dokter', 'spesialisasi', 'hari', 'jam_mulai', 'jam_selesai'];

    public function faskes()
    {
        return $this->belongsTo(Faskes::class);
    }
}
