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
        $faskes = \App\Models\Faskes::with('mitra')
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

        // Gabungkan pariwisata mitra jika ada modelnya
        if (class_exists(\App\Models\Pariwisata::class)) {
            $mitraWisata = \App\Models\Pariwisata::with('mitra')
                ->whereHas('mitra', fn($q) => $q->where('is_verified', true))
                ->get()
                ->map(fn($w) => [
                    'id'         => 'm_' . $w->id,
                    'name'       => $w->nama_wisata ?? $w->nama_pariwisata ?? 'Wisata',
                    'kategori'   => $w->kategori ?? $w->jenis_wisata ?? 'Alam',
                    'deskripsi'  => $w->deskripsi,
                    'alamat'     => $w->alamat,
                    'lat'        => (float) $w->latitude,
                    'lng'        => (float) $w->longitude,
                    'tiket'      => 0,
                    'pengelola'  => $w->mitra->nama_penanggung_jawab ?? 'Pengelola',
                    'telp'       => $w->kontak_wisata ?? $w->mitra->no_telp ?? '',
                    'foto'       => $w->foto_path ? asset('storage/' . $w->foto_path) : null,
                ]);
            $pariwisata = $pariwisata->merge($mitraWisata)->values();
        }

        $daftarFaskes = $faskes;
        $daftarPariwisata = $pariwisata;

        return view('v_peta_faskes', compact('daftarFaskes', 'daftarPariwisata'));
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
}
