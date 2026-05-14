<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan Splash Screen Wisatawan (Halaman Awal)
     */
    public function wisatawanHome() {
        return view('v_wisatawan_home');
    }

    /**
     * Menampilkan Halaman Login
     */
    public function login() {
        return view('v_login');
    }

    /**
     * Menampilkan Dashboard Admin (Untuk Faskes/Mitra)
     */
    public function myHome() {
        return view('myHome');
    }

    public function daftarPilihan() {
        return view('v_daftar_pilihan');
    }

    public function daftarPariwisata() {
        return view('v_daftar_pariwisata');
    }

    public function daftarFaskes() {
        return view('v_daftar_faskes');
    }

    // Tambahkan di dalam class HomeController

    public function daftarWisatawan() {
        return view('v_daftar_wisatawan');
    }

    public function petaFaskes() {
        // Ambil faskes terverifikasi
        $faskes = \App\Models\Faskes::with(['mitra', 'jadwals'])
            ->withAvg('ulasans', 'rating')
            ->withCount('ulasans')
            ->whereHas('mitra', fn($q) => $q->where('is_verified', true))
            ->get()
            ->map(fn($f) => [
                'id'       => $f->id,
                'name'     => $f->nama_faskes,
                'type'     => $f->jenis_faskes,
                'address'  => $f->alamat,
                'phone'    => $f->no_telp,
                'lat'      => (float) $f->latitude,
                'lng'      => (float) $f->longitude,
                'status'   => $f->status_operasional,
                'bpjs'     => (bool) $f->dukungan_bpjs,
                'facilities' => $f->layanan_tersedia ?? [],
                'notes'    => $f->pengumuman,
                'rating_avg' => round($f->ulasans_avg_rating ?? 0, 1),
                'rating_count' => $f->ulasans_count ?? 0,
                'jadwals'  => $f->jadwals->map(fn($j) => [
                    'dokter'       => $j->nama_dokter,
                    'spesialisasi' => $j->spesialisasi,
                    'hari'         => $j->hari,
                    'jam'          => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
                ]),
            ]);

        // Ambil pariwisata yang disetujui (Dari model form publik)
        $pariwisata = \App\Models\PendaftaranPariwisata::disetujui()
            ->get()
            ->map(fn($w) => [
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

        // Gabungkan pariwisata mitra jika ada modelnya (Sudah dihapus: sekarang hanya pakai PendaftaranPariwisata)
        $daftarFaskes = $faskes;
        $daftarPariwisata = $pariwisata;

        return view('v_peta_faskes', compact('daftarFaskes', 'daftarPariwisata'));
    }

    public function jadwalFaskes($id) {
        $faskes = \App\Models\Faskes::with(['jadwals'])
            ->where('id', $id)
            ->whereHas('mitra', fn($q) => $q->where('is_verified', true))
            ->firstOrFail();
            
        return view('v_jadwal_faskes', compact('faskes'));
    }

    // Dashboard Routes
    public function dashboardWisatawan() {
        return view('dashboard_wisatawan');
    }

    public function dashboardFaskes() {
        return view('dashboard_faskes');
    }

    public function dashboardAdmin() {
        return view('dashboard_admin');
    }

    /**
     * Memproses Laporan Masalah dari Wisatawan/Publik
     */
    public function submitLaporan(Request $request) {
        try {
            $request->validate([
                'subjek' => 'required|string|max:255',
                'deskripsi' => 'required|string|max:200'
            ]);

            \App\Models\LaporanMasalah::create([
                'user_id' => session('auth_user.id'), // null jika tidak login
                'subjek' => $request->subjek,
                'deskripsi' => $request->deskripsi,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan Anda telah berhasil dikirim. Tim kami akan segera meninjaunya.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mengirim laporan.'
            ], 500);
        }
    }
}
