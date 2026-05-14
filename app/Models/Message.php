<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'mitra_id',
        'sender_role',
        'body',
        'read_by_mitra',
        'read_by_admin',
    ];

    protected $casts = [
        'read_by_mitra' => 'boolean',
        'read_by_admin' => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    // ─── Helper ───────────────────────────────────────────────

    /** Waktu terformat untuk bubble chat */
    public function getTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    /** Apakah pesan ini dari Admin */
    public function isFromAdmin(): bool
    {
        return $this->sender_role === 'admin';
    }
}
