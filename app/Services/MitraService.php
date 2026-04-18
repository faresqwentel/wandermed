<?php

namespace App\Services;

use App\Models\Mitra;
use App\Models\Faskes;
use Exception;

class MitraService
{
    /**
     * Setujui mitra pendaftar.
     */
    public function approveMitra(int $id): Mitra
    {
        $mitra = Mitra::with('faskes')->findOrFail($id);
        
        $mitra->update([
            'is_verified'   => true,
            'catatan_admin' => null,
        ]);

        if ($mitra->faskes) {
            $mitra->faskes->update(['status_operasional' => 'open']);
        }

        return $mitra;
    }

    /**
     * Tolak dan hapus mitra pendaftar.
     */
    public function rejectMitra(int $id, string $alasan = 'Dokumen tidak lengkap atau tidak valid.'): Mitra
    {
        $mitra = Mitra::findOrFail($id);
        
        $mitra->update([
            'is_verified'   => false,
            'catatan_admin' => $alasan,
        ]);

        $mitra->delete();

        return $mitra;
    }
}
