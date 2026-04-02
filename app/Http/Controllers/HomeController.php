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
}
