<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; // WAJIB ada agar controller terbaca

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Bawaan Laravel
Route::get('/welcome', function () {
    return view('welcome');
});

// 1. Rute Halaman Utama (Wisatawan) - Berada di halaman paling depan
Route::get('/', [HomeController::class, 'wisatawanHome']);

// 2. Rute Halaman Login
Route::get('/login', [HomeController::class, 'login']);

// 3. Rute Dashboard Admin
Route::get('/admin', [HomeController::class, 'myHome']);

// Rute untuk Halaman Pilihan Daftar
Route::get('/daftar', [HomeController::class, 'daftarPilihan']);

// Rute untuk Formulir Pariwisata
Route::get('/daftar/pariwisata', [HomeController::class, 'daftarPariwisata']);

// Rute untuk Formulir Faskes
Route::get('/daftar/faskes', [HomeController::class, 'daftarFaskes']);

// routes/web.php

Route::get('/', function () {
    return view('v_wisatawan_home');
});
