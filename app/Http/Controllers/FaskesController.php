<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\Faskes;

/**
 * FaskesController
 *
 * Mengelola semua operasi untuk Mitra Faskes:
 * - Dashboard (data operasional)
 * - Toggle status real-time (AJAX)
 * - Manajemen fasilitas
 */
class FaskesController extends Controller
{
    // =========================================================
    // DASHBOARD MITRA FASKES
    // Menampilkan data operasional + statistik faskes yang dimiliki mitra ini
    // =========================================================
    public function dashboard()
    {
        $mitraId = session('auth_user.id');

        // Ambil profil mitra dan faskes miliknya sekaligus
        $mitra  = Mitra::with('faskes')->find($mitraId);
        $faskes = $mitra?->faskes;

        // Jika mitra belum punya data faskes, arahkan untuk mengisi profil dulu
        if (!$faskes) {
            return view('dashboard_faskes', ['mitra' => $mitra, 'faskes' => null]);
        }

        // Statistik dashboard (kelak dari data nyata)
        $totalPengunjung = $faskes->riwayatKunjungan()->count();
        $totalUlasan     = \App\Models\UlasanFaskes::where('faskes_id', $faskes->id)->count();

        $ulasans = \App\Models\UlasanFaskes::with('user')->where('faskes_id', $faskes->id)->latest()->get();
        $jadwals = \App\Models\JadwalDokter::where('faskes_id', $faskes->id)->orderBy('hari')->get();

        return view('dashboard_faskes', compact(
            'mitra',
            'faskes',
            'totalPengunjung',
            'totalUlasan',
            'ulasans',
            'jadwals'
        ));
    }

    // =========================================================
    // UPDATE STATUS OPERASIONAL (AJAX Real-Time)
    // Endpoint: POST /faskes/status
    // Dipanggil saat Mitra klik Toggle Buka/Tutup di dashboard
    // =========================================================
    public function updateStatus(Request $request)
    {
        $request->validate([
            'field' => 'required|in:status_operasional,dukungan_bpjs,pengumuman',
            'value' => 'required',
        ]);

        $mitraId = session('auth_user.id');
        $faskes  = Faskes::where('mitra_id', $mitraId)->firstOrFail();

        // Tangani update berdasarkan field yang dikirim
        if ($request->field === 'status_operasional') {
            $faskes->status_operasional = $request->value === 'true' ? 'open' : 'closed';
        } elseif ($request->field === 'dukungan_bpjs') {
            $faskes->dukungan_bpjs = filter_var($request->value, FILTER_VALIDATE_BOOLEAN);
        } elseif ($request->field === 'pengumuman') {
            $faskes->pengumuman = $request->value;
        }

        $faskes->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui di peta!',
            'updated' => $faskes->only(['status_operasional', 'dukungan_bpjs', 'pengumuman']),
        ]);
    }

    // =========================================================
    // UPDATE DAFTAR FASILITAS (AJAX)
    // Endpoint: POST /faskes/fasilitas
    // =========================================================
    public function updateFasilitas(Request $request)
    {
        $request->validate([
            'layanan_tersedia' => 'nullable|array',
        ]);

        $faskes = Faskes::where('mitra_id', session('auth_user.id'))->firstOrFail();
        
        // Support both key names for compatibility
        $layanan = $request->layanan_tersedia ?? $request->fasilitas ?? [];
        $faskes->update(['layanan_tersedia' => $layanan]);

        return response()->json([
            'success' => true,
            'message' => 'Daftar fasilitas berhasil disimpan dan akan tampil di peta!',
        ]);
    }

    // =========================================================
    // UPDATE PROFIL FASKES
    // Endpoint: POST /faskes/profil
    // =========================================================
    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama_faskes'  => 'required|string|max:150',
            'jenis_faskes' => 'required|in:Rumah Sakit,Klinik,Apotek,Puskesmas,Lainnya',
            'alamat'       => 'required|string',
            'no_telp'      => 'nullable|string|max:20',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'dukungan_bpjs'=> 'nullable|boolean',
        ]);

        $faskes = Faskes::where('mitra_id', session('auth_user.id'))->firstOrFail();

        // Field profil dasar selalu diupdate
        $data = $request->only(['nama_faskes', 'jenis_faskes', 'alamat', 'no_telp', 'dukungan_bpjs']);

        // Koordinat hanya diupdate jika dikirim (dari menu Update Koordinat)
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $data['latitude']  = $request->latitude;
            $data['longitude'] = $request->longitude;
        }

        $faskes->update($data);

        return back()->with('success', 'Profil faskes berhasil diperbarui!');
    }

    // =========================================================
    // ULASAN DAN JADWAL
    // =========================================================

    public function submitUlasan(Request $request, $faskes_id)
    {
        // Hanya wisatawan yang boleh memberikan ulasan
        if (session('auth_user.role') !== 'wisatawan') {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan hanya dapat diberikan oleh wisatawan yang terdaftar.'
            ], 403);
        }

        // Pastikan wisatawan sudah login
        if (!session()->has('auth_user')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        \App\Models\UlasanFaskes::create([
            'user_id'   => session('auth_user.id'),
            'faskes_id' => $faskes_id,
            'rating'    => $request->rating,
            'komentar'  => $request->komentar,
        ]);

        return response()->json(['success' => true, 'message' => 'Ulasan berhasil dikirim!']);
    }


    public function replyUlasan(Request $request, $id)
    {
        $request->validate(['balasan' => 'required|string']);
        $ulasan = \App\Models\UlasanFaskes::findOrFail($id);
        $ulasan->update(['balasan_faskes' => $request->balasan]);
        return back()->with('success', 'Balasan berhasil disimpan!');
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string',
            'spesialisasi' => 'required|string',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $faskes = Faskes::where('mitra_id', session('auth_user.id'))->firstOrFail();

        \App\Models\JadwalDokter::create(array_merge($request->all(), ['faskes_id' => $faskes->id]));
        return back()->with('success', 'Jadwal dokter berhasil ditambahkan!');
    }

    public function destroyJadwal($id)
    {
        $jadwal = \App\Models\JadwalDokter::findOrFail($id);
        $jadwal->delete();
        return back()->with('success', 'Jadwal dokter berhasil dihapus!');
    }
}
