<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\LaporanMasalah;
use App\Models\User;
use App\Models\PendaftaranPariwisata;
use App\Services\MitraService;
use App\Http\Requests\ResolveLaporanRequest;
use Exception;

/**
 * AdminController
 *
 * Mengelola semua operasi untuk Administrator WanderMed.
 */
class AdminController extends Controller
{
    private MitraService $mitraService;

    public function __construct(MitraService $mitraService)
    {
        $this->mitraService = $mitraService;
    }

    /**
     * Menampilkan antarmuka Dashboard Admin.
     */
    public function dashboard(): View
    {
        return view('dashboard_admin', [
            'totalWisatawan'  => User::count(),
            'totalFaskes'     => Faskes::count(),
            'totalPariwisata' => PendaftaranPariwisata::disetujui()->count(),
            
            'pendingMitra'    => Mitra::pending()->where('jenis_mitra', 'faskes')->count() + PendaftaranPariwisata::menunggu()->count(),
            'mitraPending'    => Mitra::pending()->where('jenis_mitra', 'faskes')->with('faskes')->oldest()->get(),
            'wisataPending'   => PendaftaranPariwisata::menunggu()->oldest()->get(),
            
            'laporans'        => LaporanMasalah::with(['user', 'faskes'])
                                    ->orderByRaw("FIELD(status, 'pending', 'on_review', 'resolved')")
                                    ->latest()->get(),
                                    
            'users'           => User::all(),
            'faskesList'      => Faskes::all(),
            'wisataApproved'  => PendaftaranPariwisata::disetujui()->get(),
        ]);
    }

    /**
     * Menyetujui pendaftaran Mitra Faskes.
     */
    public function approveMitra(int $id): JsonResponse
    {
        try {
            $mitra = $this->mitraService->approveMitra($id);

            return response()->json([
                'success' => true,
                'message' => "Mitra '{$mitra->nama_penanggung_jawab}' berhasil disetujui!",
            ]);
        } catch (Exception $e) {
            Log::error("Approve Mitra failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    /**
     * Menolak pendaftaran Mitra Faskes.
     */
    public function rejectMitra(Request $request, int $id): JsonResponse
    {
        try {
            $alasan = $request->input('alasan', 'Dokumen tidak lengkap atau tidak valid.');
            $mitra = $this->mitraService->rejectMitra($id, $alasan);

            return response()->json([
                'success' => true,
                'message' => "Mitra '{$mitra->nama_penanggung_jawab}' telah ditolak dan dihapus.",
            ]);
        } catch (Exception $e) {
            Log::error("Reject Mitra failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    /**
     * Menyelesaikan status laporan masalah.
     */
    public function resolveLaporan(ResolveLaporanRequest $request, int $id): JsonResponse
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

    /**
     * API: Mengambil data faskes untuk Leaflet JS.
     */
    public function getFaskesJson(): JsonResponse
    {
        $faskesData = Faskes::with('mitra')
            ->whereHas('mitra', fn($query) => $query->where('is_verified', true))
            ->select([
                'id', 'nama_faskes', 'jenis_faskes', 'alamat',
                'no_telp', 'latitude', 'longitude',
                'status_operasional', 'dukungan_bpjs',
                'layanan_tersedia', 'pengumuman',
            ])
            ->get()
            ->map(fn($f) => [
                'id'         => $f->id,
                'name'       => $f->nama_faskes,
                'type'       => $f->jenis_faskes,
                'address'    => $f->alamat,
                'phone'      => $f->no_telp,
                'lat'        => (float) $f->latitude,
                'lng'        => (float) $f->longitude,
                'status'     => $f->status_operasional,
                'bpjs'       => (bool) $f->dukungan_bpjs,
                'facilities' => $f->layanan_tersedia ?? [],
                'notes'      => $f->pengumuman,
            ]);

        return response()->json($faskesData);
    }

    /**
     * Toggle status aktif pengguna (wisatawan).
     */
    public function toggleUserActive(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success'   => true,
            'message'   => "Status Wisatawan {$user->name} berhasil diubah.",
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Memperbarui detail operasional faskes via Admin.
     */
    public function updateFaskesData(Request $request, int $id): JsonResponse
    {
        $faskes = Faskes::findOrFail($id);

        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $updateData = [
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'pesan_admin' => $request->pesan_admin ?? $faskes->pesan_admin,
        ];

        if ($request->has('dukungan_bpjs')) {
            $updateData['dukungan_bpjs'] = (bool) $request->dukungan_bpjs;
        }
        if ($request->has('pengumuman')) {
            $updateData['pengumuman'] = $request->pengumuman;
        }

        $faskes->update($updateData);

        return response()->json([
            'success' => true,
            'message' => "Data Faskes \"{$faskes->nama_faskes}\" berhasil disimpan ✅"
        ]);
    }

    /**
     * Toggle Buka / Tutup fasilitas kesehatan.
     */
    public function toggleStatusFaskes(int $id): JsonResponse
    {
        $faskes = Faskes::findOrFail($id);

        $newStatus = ($faskes->status_operasional === 'open') ? 'closed' : 'open';

        try {
            $faskes->update(['status_operasional' => $newStatus]);
        } catch (Exception $e) {
            Log::error("Toggle status faskes failed: " . $e->getMessage());
        }

        return response()->json([
            'success'            => true,
            'status_operasional' => $newStatus,
            'is_open'            => ($newStatus === 'open'),
            'message'            => "Status {$faskes->nama_faskes} diubah ke: " . (($newStatus === 'open') ? 'Buka' : 'Tutup')
        ]);
    }

    /**
     * Menghapus data Faskes beserta akun Mitra-nya.
     */
    public function destroyFaskes(int $id): JsonResponse
    {
        try {
            $faskes = Faskes::findOrFail($id);
            $namaFaskes = $faskes->nama_faskes;

            // Hapus akun Mitra terkait (cascade akan menghapus faskes lewat FK)
            if ($faskes->mitra) {
                $faskes->mitra->delete();
            } else {
                $faskes->delete();
            }

            return response()->json([
                'success' => true,
                'message' => "Faskes \"{$namaFaskes}\" beserta akun mitra berhasil dihapus."
            ]);
        } catch (Exception $e) {
            Log::error("Destroy Faskes failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data faskes.'], 500);
        }
    }

    /**
     * Memperbarui data lokasi pariwisata.
     */
    public function updatePariwisataData(Request $request, int $id): JsonResponse
    {
        if (!class_exists(\App\Models\Pariwisata::class)) {
            return response()->json(['success' => false, 'message' => 'Model Pariwisata tidak ditemukan.'], 400);
        }

        $wisata = \App\Models\Pariwisata::findOrFail($id);
        
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $wisata->update([
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'alamat'      => $request->input('alamat', $wisata->alamat),
            'harga_tiket' => $request->input('harga_tiket', $wisata->harga_tiket),
            'deskripsi'   => $request->input('deskripsi', $wisata->deskripsi),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Data Pariwisata \"{$wisata->nama_wisata}\" berhasil diperbarui."
        ]);
    }

    /**
     * API: Menggabungkan data pariwisata untuk peta Leaflet (gabungan form tanpa akun & via Mitra).
     */
    public function getPariwisataJson(): JsonResponse
    {
        return response()->json($this->getMergedPariwisataData());
    }

    private function getMergedPariwisataData()
    {
        $pendaftaranData = PendaftaranPariwisata::disetujui()->get()->map(fn($w) => [
            'id'         => 'p_' . $w->id,
            'name'       => $w->nama_wisata,
            'kategori'   => $w->kategori,
            'deskripsi'  => $w->deskripsi,
            'alamat'     => $w->alamat,
            'lat'        => (float) $w->latitude,
            'lng'        => (float) $w->longitude,
            'tiket'      => $w->harga_tiket ?? 0,
            'pengelola'  => $w->nama_pengelola,
            'telp'       => $w->no_telp,
            'foto'       => $w->foto_path ? asset('storage/' . $w->foto_path) : null,
        ]);

        $mitraData = collect();
        if (class_exists(\App\Models\Pariwisata::class)) {
            $mitraData = \App\Models\Pariwisata::with('mitra')
                ->whereHas('mitra', fn($query) => $query->where('is_verified', true))
                ->get()
                ->map(fn($w) => [
                    'id'         => 'm_' . $w->id,
                    'name'       => $w->nama_wisata,
                    'kategori'   => $w->kategori ?? 'Lainnya',
                    'deskripsi'  => $w->deskripsi,
                    'alamat'     => $w->alamat,
                    'lat'        => (float) $w->latitude,
                    'lng'        => (float) $w->longitude,
                    'tiket'      => 0,
                    'pengelola'  => $w->mitra->nama_penanggung_jawab ?? 'Pengelola',
                    'telp'       => $w->kontak_wisata ?? $w->mitra->no_telp ?? '',
                    'foto'       => $w->foto_path ? asset('storage/' . $w->foto_path) : null,
                ]);
        }

        return $pendaftaranData->merge($mitraData)->values();
    }

    /**
     * API: Mengambil Nearby Faskes menggunakan scope Haversine.
     */
    public function getNearbyFaskes(Request $request): JsonResponse
    {
        $lat    = (float) $request->input('lat', 0);
        $lng    = (float) $request->input('lng', 0);
        $radius = (float) $request->input('radius', 5);

        $faskes = Faskes::with('mitra')
            ->whereHas('mitra', fn($q) => $q->where('is_verified', true))
            ->nearby($lat, $lng, $radius) // Menggunakan Clean Code Scope 
            ->get()
            ->map(fn($f) => [
                'id'       => $f->id,
                'name'     => $f->nama_faskes,
                'type'     => $f->jenis_faskes,
                'lat'      => (float) $f->latitude,
                'lng'      => (float) $f->longitude,
                'distance' => round($f->distance, 2)
            ]);

        return response()->json($faskes);
    }
}
