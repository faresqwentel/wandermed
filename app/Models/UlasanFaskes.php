<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlasanFaskes extends Model
{
    protected $fillable = ['user_id', 'faskes_id', 'rating', 'komentar', 'balasan_faskes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faskes()
    {
        return $this->belongsTo(Faskes::class);
    }
}
