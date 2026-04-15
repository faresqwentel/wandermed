<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\LaporanMasalah;
use App\Models\User;
use App\Models\PendaftaranPariwisata;

/**
 * AdminController
 *
 * Mengelola semua operasi untuk Administrator WanderMed:
 * - Dashboard statistik global
 * - Verifikasi / penolakan mitra baru
 * - Manajemen laporan masalah dari wisatawan
 */
class AdminController extends Controller
{
    // =========================================================
    // DASHBOARD ADMIN
    // Statistik global sistem + antrean validasi + laporan masalah
    // =========================================================
    public function dashboard()
    {
        // --- Statistik Global (Stat Cards) ---
        $totalWisatawan = User::count();
        $totalFaskes    = Faskes::count();
        $totalPariwisata = PendaftaranPariwisata::disetujui()->count();
        
        $pendingFaskes = Mitra::pending()->where('jenis_mitra', 'faskes')->count();
        $pendingWisata = PendaftaranPariwisata::menunggu()->count();
        $pendingMitra  = $pendingFaskes + $pendingWisata;

        // --- Antrean Validasi Mitra Faskes ---
        $mitraPending = Mitra::pending()
                             ->where('jenis_mitra', 'faskes')
                             ->with('faskes')
                             ->orderBy('created_at', 'asc')
                             ->get();
                             
        // --- Antrean Validasi Pariwisata ---
        $wisataPending = PendaftaranPariwisata::menunggu()
                             ->orderBy('created_at', 'asc')
                             ->get();

        // --- Laporan Masalah Terbaru ---
        $laporans = LaporanMasalah::with(['user', 'faskes'])
                       ->orderByRaw("FIELD(status, 'pending', 'on_review', 'resolved')")
                       ->orderBy('created_at', 'desc')
                       ->get();

        // --- Data Master ---
        $users = User::all();
        $faskesList = Faskes::all();
        // Fallback for Pariwisata model since it's probably not fully built or same structure but I'll query if exists.
        $pariwisataList = PendaftaranPariwisata::disetujui()->get();

        return view('dashboard_admin', compact(
            'totalWisatawan',
            'totalFaskes',
            'totalPariwisata',
            'pendingMitra',
            'mitraPending',
            'wisataPending',
            'laporans',
            'users',
            'faskesList',
            'pariwisataList'
        ));
    }

    // =========================================================
    // APPROVE MITRA (AJAX)
    // Endpoint: POST /admin/mitra/{id}/approve
    // =========================================================
    public function approveMitra(int $id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->update([
            'is_verified'   => true,
            'catatan_admin' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Mitra '{$mitra->nama_penanggung_jawab}' berhasil disetujui!",
        ]);
    }

    // =========================================================
    // REJECT MITRA (AJAX)
    // Endpoint: POST /admin/mitra/{id}/reject
    // =========================================================
    public function rejectMitra(Request $request, int $id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->update([
            'is_verified'   => false,
            'catatan_admin' => $request->input('alasan', 'Dokumen tidak lengkap atau tidak valid.'),
        ]);

        // Hapus data mitra (opsional: bisa juga hanya soft-reject tanpa hapus)
        $mitra->delete();

        return response()->json([
            'success' => true,
            'message' => "Mitra '{$mitra->nama_penanggung_jawab}' telah ditolak dan dihapus.",
        ]);
    }

    // =========================================================
    // SELESAIKAN LAPORAN MASALAH (AJAX)
    // Endpoint: POST /admin/laporan/{id}/resolve
    // =========================================================
    public function resolveLaporan(Request $request, int $id)
    {
        $laporan = LaporanMasalah::findOrFail($id);
        $laporan->update([
            'status'               => 'resolved',
            'catatan_penyelesaian' => $request->input('catatan', 'Masalah telah ditinjau dan diselesaikan oleh admin.'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Laporan #{$laporan->id} berhasil diselesaikan!",
        ]);
    }

    // =========================================================
    // DATA FASKES untuk Peta (API Endpoint)
    // Endpoint: GET /api/faskes
    // Mengembalikan JSON koordinat semua faskes yang aktif
    // untuk dirender oleh Leaflet.js di halaman peta publik
    // =========================================================
    public function getFaskesJson()
    {
        $faskesData = Faskes::with('mitra')
                        ->select([
                            'id', 'nama_faskes', 'jenis_faskes', 'alamat',
                            'no_telp', 'latitude', 'longitude',
                            'status_operasional', 'dukungan_bpjs',
                            'layanan_tersedia', 'pengumuman',
                        ])
                        ->get()
                        ->map(function ($f) {
                            return [
                                'id'                 => $f->id,
                                'name'               => $f->nama_faskes,
                                'type'               => $f->jenis_faskes,
                                'address'            => $f->alamat,
                                'phone'              => $f->no_telp,
                                'lat'                => (float) $f->latitude,
                                'lng'                => (float) $f->longitude,
                                'status'             => $f->status_operasional,
                                'bpjs'               => (bool) $f->dukungan_bpjs,
                                'facilities'         => $f->layanan_tersedia ?? [],
                                'notes'              => $f->pengumuman,
                            ];
                        });

        return response()->json($faskesData);
    }

    // =========================================================
    // MANGEMENT DATA MASTER
    // =========================================================
    
    // Toggle Status Wisatawan
    public function toggleUserActive($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Status Wisatawan {$user->name} berhasil diubah.",
            'is_active' => $user->is_active
        ]);
    }

    // Update Data Faskes (Koordinat, dll)
    public function updateFaskesData(Request $request, $id)
    {
        $faskes = Faskes::findOrFail($id);
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $faskes->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'nama_faskes' => $request->nama_faskes ?? $faskes->nama_faskes
        ]);

        return response()->json([
            'success' => true,
            'message' => "Data Faskes {$faskes->nama_faskes} berhasil diperbarui."
        ]);
    }

    // Update Data Pariwisata (Koordinat, dll)
    public function updatePariwisataData(Request $request, $id)
    {
        if (!class_exists(\App\Models\Pariwisata::class)) {
            return response()->json(['success' => false, 'message' => 'Model Pariwisata tidak ditemukan.']);
        }
        
        $pariwisata = \App\Models\Pariwisata::findOrFail($id);
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $pariwisata->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'nama_pariwisata' => $request->nama_pariwisata ?? $pariwisata->nama_pariwisata
        ]);

        return response()->json([
            'success' => true,
            'message' => "Data Pariwisata berhasil diperbarui."
        ]);
    }
}
