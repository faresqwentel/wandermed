<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Faskes;

/**
 * AuthController – Mengelola Login, Logout, dan Registrasi
 * untuk semua aktor (Wisatawan, Mitra Faskes, Mitra Pariwisata, Admin).
 */
class AuthController extends Controller
{
    // =========================================================
    // TAMPIL FORM LOGIN
    // =========================================================
    public function showLogin()
    {
        // Jika sudah login, langsung redirect ke dashboard sesuai role
        if (session()->has('auth_user')) {
            return $this->redirectByRole(session('auth_user.role'));
        }
        return view('v_login');
    }

    // =========================================================
    // PROSES LOGIN
    // =========================================================
    public function processLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $email    = $request->input('email');
        $password = $request->input('password');

        // --- 1. Cek Admin (hardcoded untuk kebutuhan demo/laporan) ---
        if ($email === config('wandermed.admin_email', 'admin@wandermed.id') &&
            $password === config('wandermed.admin_password', 'admin123')) {
            session(['auth_user' => [
                'id'    => 0,
                'name'  => 'Super Admin',
                'email' => $email,
                'role'  => 'admin',
            ]]);
            return redirect('/dashboard/admin')->with('success', 'Selamat datang, Admin!');
        }

        // --- 2. Cek Wisatawan (tabel users) ---
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            if (!$user->is_active) {
                return back()->with('error', 'Akun Anda telah DIBLOKIR oleh Admin. Silakan hubungi CS jika ini adalah kesalahan.');
            }

            session(['auth_user' => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'role'    => 'wisatawan',
            ]]);
            return redirect('/dashboard/wisatawan')->with('success', "Selamat datang, {$user->name}!");
        }

        // --- 3. Cek Mitra (tabel mitras) ---
        $mitra = Mitra::where('email', $email)->first();
        if ($mitra && Hash::check($password, $mitra->password)) {
            if (!$mitra->is_active) {
                return back()->with('error', 'Akun Faskes/Pariwisata Anda telah DIBLOKIR oleh Admin.');
            }
            if (!$mitra->is_verified) {
                return back()->with('error', 'Akun Anda belum diverifikasi oleh admin. Silakan tunggu konfirmasi.');
            }
            $role = ($mitra->jenis_mitra === 'faskes') ? 'mitra_faskes' : 'mitra_pariwisata';
            session(['auth_user' => [
                'id'          => $mitra->id,
                'name'        => $mitra->nama_penanggung_jawab,
                'email'       => $mitra->email,
                'jenis_mitra' => $mitra->jenis_mitra,
                'role'        => $role,
            ]]);
            return redirect('/dashboard/faskes')->with('success', "Selamat datang, {$mitra->nama_penanggung_jawab}!");
        }

        // --- Semua gagal ---
        return back()->with('error', 'Email atau password salah. Silakan coba lagi.');
    }

    // =========================================================
    // LOGOUT
    // =========================================================
    public function logout(Request $request)
    {
        $request->session()->forget('auth_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }

    // =========================================================
    // REGISTRASI WISATAWAN
    // =========================================================
    public function registerWisatawan(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8|confirmed',
            'gol_darah'        => 'nullable|in:A,B,AB,O',
            'kontak_darurat'   => 'nullable|string|max:20',
            'riwayat_alergi'   => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'gol_darah'      => $request->gol_darah,
            'kontak_darurat' => $request->kontak_darurat,
            'riwayat_alergi' => $request->riwayat_alergi,
        ]);

        session(['auth_user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => 'wisatawan',
        ]]);

        return redirect('/dashboard/wisatawan')->with('success', 'Akun berhasil dibuat! Selamat datang di WanderMed.');
    }

    // =========================================================
    // REGISTRASI MITRA (Faskes / Pariwisata)
    // =========================================================
    public function registerMitra(Request $request)
    {
        $request->validate([
            'nama_penanggung_jawab' => 'required|string|max:100',
            'email'                 => 'required|email|unique:mitras,email',
            'password'              => 'required|min:8|confirmed',
            'no_telp'               => 'required|string|max:20',
            'jenis_mitra'           => 'required|in:faskes,pariwisata',
            'nama_faskes'           => 'nullable|string|max:150',
            'nama_pariwisata'       => 'nullable|string|max:150',
            'jenis_faskes'          => 'nullable|string',
            'jenis_wisata'          => 'nullable|string',
            'alamat'                => 'nullable|string',
            'deskripsi'             => 'nullable|string',
            'latitude'              => 'nullable|numeric',
            'longitude'             => 'nullable|numeric',
            'dokumen_izin'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle upload dokumen izin
        $dokumenPath = null;
        if ($request->hasFile('dokumen_izin') && $request->file('dokumen_izin')->isValid()) {
            $dokumenPath = $request->file('dokumen_izin')
                ->store('dokumen_mitra', 'public');
        }

        $mitra = Mitra::create([
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'email'                 => $request->email,
            'password'              => Hash::make($request->password),
            'no_telp'               => $request->no_telp,
            'jenis_mitra'           => $request->jenis_mitra,
            'is_verified'           => false,
            'catatan_admin'         => $dokumenPath, // simpan path dokumen di catatan admin sementara
        ]);

        // Jika jenis mitra adalah faskes, buat entry Faskes yang terhubung ke id mitra
        if ($request->jenis_mitra === 'faskes') {
            Faskes::create([
                'mitra_id'           => $mitra->id,
                'nama_faskes'        => $request->nama_faskes ?? $request->nama_penanggung_jawab . ' Faskes',
                'jenis_faskes'       => $request->jenis_faskes ?? 'Klinik',
                'alamat'             => $request->alamat ?? '-',
                'no_telp'            => $request->no_telp,
                'latitude'           => $request->latitude ?? -6.5718,
                'longitude'          => $request->longitude ?? 107.7600,
                'status_operasional' => 'closed',
                'dukungan_bpjs'      => $request->dukungan_bpjs ? true : false,
                'pengumuman'         => $request->pengumuman ?? null,
            ]);
        }

        // Jika jenis mitra adalah pariwisata, buat entry di tabel pariwisatas jika model tersedia
        if ($request->jenis_mitra === 'pariwisata' && class_exists(\App\Models\Pariwisata::class)) {
            \App\Models\Pariwisata::create([
                'mitra_id'        => $mitra->id,
                'nama_pariwisata' => $request->nama_pariwisata ?? $request->nama_penanggung_jawab . ' Wisata',
                'jenis_wisata'    => $request->jenis_wisata ?? 'Alam',
                'alamat'          => $request->alamat ?? '-',
                'no_telp'         => $request->no_telp,
                'latitude'        => $request->latitude ?? -6.5718,
                'longitude'       => $request->longitude ?? 107.7600,
                'deskripsi'       => $request->deskripsi ?? null,
                'status'          => 'tutup',
            ]);
        }

        return redirect('/login')->with('success',
            'Pendaftaran berhasil dikirim! Akun Anda sedang menunggu verifikasi Admin WanderMed. Anda akan mendapat konfirmasi setelah disetujui.'
        );
    }

    // =========================================================
    // HELPER: Redirect berdasarkan role
    // =========================================================
    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin'             => redirect('/dashboard/admin'),
            'mitra_faskes'      => redirect('/dashboard/faskes'),
            'mitra_pariwisata'  => redirect('/dashboard/faskes'),
            default             => redirect('/dashboard/wisatawan'),
        };
    }
}
