<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WisatawanController;
use App\Http\Controllers\FaskesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PariwisataController;

/*
|--------------------------------------------------------------------------
| WanderMed Web Routes
|--------------------------------------------------------------------------
|
| Struktur:
|  - Rute publik (tanpa auth)
|  - Rute autentikasi (login/logout/register)
|  - Rute Dashboard yang dilindungi middleware auth.session + role
|  - Rute AJAX API (dilindungi auth.session)
|
*/

// =========================================================
// RUTE PUBLIK (Tanpa Login)
// =========================================================

Route::get('/', [HomeController::class, 'wisatawanHome']);
Route::get('/mitra', fn() => view('v_mitra_home'))->name('mitra.home');
Route::get('/faq', fn() => view('v_faq'))->name('faq');
Route::get('/peta-faskes', [HomeController::class, 'petaFaskes'])->name('peta.faskes');
Route::get('/faskes/{id}/jadwal', [HomeController::class, 'jadwalFaskes'])->name('faskes.jadwal');
Route::post('/lapor-masalah', [HomeController::class, 'submitLaporan'])->name('lapor.masalah');
Route::get('/daftar', [HomeController::class, 'daftarPilihan']);
Route::get('/daftar/wisatawan', [HomeController::class, 'daftarWisatawan']);
Route::get('/daftar/faskes', [HomeController::class, 'daftarFaskes']);
Route::get('/daftar/pariwisata', [HomeController::class, 'daftarPariwisata']);

// API publik: data faskes untuk Leaflet.js
Route::get('/api/faskes', [AdminController::class, 'getFaskesJson']);
// API publik: data pariwisata yang disetujui untuk Peta
Route::get('/api/pariwisata', [AdminController::class, 'getPariwisataJson']);
// API publik: faskes terdekat berdasarkan koordinat (Haversine)
Route::get('/api/faskes/nearby', [AdminController::class, 'getNearbyFaskes']);


// =========================================================
// RUTE AUTENTIKASI
// =========================================================

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Registrasi Wisatawan
Route::post('/daftar/wisatawan', [AuthController::class, 'registerWisatawan'])->name('register.wisatawan');
// Registrasi Faskes (butuh akun Mitra)
Route::post('/daftar/faskes', [AuthController::class, 'registerMitra'])->name('register.mitra');
// Pendaftaran Pariwisata (tanpa akun — hanya form submission)
Route::post('/daftar/pariwisata', [PariwisataController::class, 'submitPendaftaran'])->name('register.pariwisata');


// =========================================================
// RUTE DASHBOARD — Dilindungi Middleware
// =========================================================

// --- Dashboard Wisatawan ---
Route::middleware(['auth.session', 'role:wisatawan'])->group(function () {
    Route::get('/dashboard/wisatawan', [WisatawanController::class, 'dashboard'])
         ->name('dashboard.wisatawan');

    // AJAX: Update catatan & label riwayat kunjungan
    Route::put('/wisatawan/catatan/{id}', [WisatawanController::class, 'updateCatatan'])
         ->name('wisatawan.catatan.update');
    Route::put('/wisatawan/label/{id}', [WisatawanController::class, 'updateLabel'])
         ->name('wisatawan.label.update');

    // Update profil & data medis
    Route::post('/wisatawan/profil', [WisatawanController::class, 'updateProfil'])
         ->name('wisatawan.profil.update');
});


// --- Dashboard Mitra Faskes ---
Route::middleware(['auth.session', 'role:mitra_faskes,mitra_pariwisata'])->group(function () {
    Route::get('/dashboard/faskes', [FaskesController::class, 'dashboard'])
         ->name('dashboard.faskes');

    // AJAX: Toggle status operasional, BPJS, pengumuman
    Route::post('/faskes/status', [FaskesController::class, 'updateStatus'])
         ->name('faskes.status.update');

    // AJAX: Update daftar fasilitas
    Route::post('/faskes/fasilitas', [FaskesController::class, 'updateFasilitas'])
         ->name('faskes.fasilitas.update');

    // Update profil faskes
    Route::post('/faskes/profil', [FaskesController::class, 'updateProfil'])
         ->name('faskes.profil.update');

    // Ulasan dan Jadwal
    Route::post('/faskes/ulasan/{id}/reply', [FaskesController::class, 'replyUlasan'])->name('faskes.ulasan.reply');
    Route::post('/faskes/jadwal', [FaskesController::class, 'storeJadwal'])->name('faskes.jadwal.store');
    Route::delete('/faskes/jadwal/{id}', [FaskesController::class, 'destroyJadwal'])->name('faskes.jadwal.destroy');
});

// Route Submit Ulasan Wisatawan (harus login)
Route::post('/faskes/{faskes_id}/ulasan', [FaskesController::class, 'submitUlasan'])
     ->middleware(['auth.session'])->name('faskes.ulasan.submit');


// --- Dashboard Admin ---
Route::middleware(['auth.session', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])
         ->name('dashboard.admin');

    // AJAX: Approve / Reject mitra (Faskes)
    Route::post('/admin/mitra/{id}/approve', [AdminController::class, 'approveMitra'])
         ->name('admin.mitra.approve');
    Route::post('/admin/mitra/{id}/reject', [AdminController::class, 'rejectMitra'])
         ->name('admin.mitra.reject');

    // AJAX: Approve / Reject pendaftaran pariwisata
    Route::post('/admin/pariwisata/{id}/approve', [PariwisataController::class, 'approve'])
         ->name('admin.pariwisata.approve');
    Route::post('/admin/pariwisata/{id}/reject', [PariwisataController::class, 'reject'])
         ->name('admin.pariwisata.reject');

    // AJAX: Selesaikan laporan masalah
    Route::post('/admin/laporan/{id}/resolve', [AdminController::class, 'resolveLaporan'])
         ->name('admin.laporan.resolve');
         
    // Data Master Management
    Route::post('/admin/user/{id}/toggle-status', [AdminController::class, 'toggleUserActive'])
         ->name('admin.user.toggle');
    Route::post('/admin/faskes/{id}/update-lokasi', [AdminController::class, 'updateFaskesData'])
         ->name('admin.faskes.update');
    Route::post('/admin/pariwisata/{id}/update-lokasi', [AdminController::class, 'updatePariwisataData'])
         ->name('admin.pariwisata.update');
    // Hapus data pariwisata
    Route::delete('/admin/pariwisata/{id}', [AdminController::class, 'destroyPariwisata'])
         ->name('admin.pariwisata.destroy');
    // Toggle status operasional faskes
    Route::post('/admin/faskes/{id}/toggle-status', [AdminController::class, 'toggleStatusFaskes'])
         ->name('admin.faskes.toggle-status');
    // Hapus data faskes (admin)
    Route::delete('/admin/faskes/{id}', [AdminController::class, 'destroyFaskes'])
         ->name('admin.faskes.destroy');
    // Export Faskes
    Route::get('/admin/faskes/export', [AdminController::class, 'exportFaskesCsv'])
         ->name('admin.faskes.export');
});

// Rute lama untuk backward compatibility (redirect ke rute baru)
Route::get('/admin', fn() => redirect('/dashboard/admin'));
