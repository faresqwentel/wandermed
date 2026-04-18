<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\MitraRegistrationRequest;
use App\Http\Requests\WisatawanRegistrationRequest;

/**
 * AuthController
 *
 * Mengelola Login, Logout, dan Registrasi untuk semua aktor.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLogin()
    {
        if (session()->has('auth_user')) {
            return $this->redirectByRole(session('auth_user.role'));
        }
        return view('v_login');
    }

    /**
     * Memproses percobaan login.
     */
    public function processLogin(LoginRequest $request): RedirectResponse
    {
        $email    = $request->input('email');
        $password = $request->input('password');

        if ($this->checkAdminLogin($email, $password)) {
            return redirect('/dashboard/admin')->with('success', 'Selamat datang, Admin!');
        }

        if ($response = $this->checkWisatawanLogin($email, $password)) {
            return $response;
        }

        if ($response = $this->checkMitraLogin($email, $password)) {
            return $response;
        }

        return back()->with('error', 'Email atau password salah. Silakan coba lagi.');
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('auth_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Mendaftarkan akun Wisatawan baru.
     */
    public function registerWisatawan(WisatawanRegistrationRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'gol_darah'      => $request->gol_darah,
            'kontak_darurat' => $request->kontak_darurat,
            'riwayat_alergi' => $request->riwayat_alergi,
        ]);

        $this->setSession('wisatawan', $user->id, $user->name, $user->email);

        return redirect('/dashboard/wisatawan')->with('success', 'Akun berhasil dibuat! Selamat datang di WanderMed.');
    }

    /**
     * Mendaftarkan akun Mitra (Faskes/Pariwisata).
     */
    public function registerMitra(MitraRegistrationRequest $request): RedirectResponse
    {
        $dokumenPath = null;
        if ($request->hasFile('dokumen_izin') && $request->file('dokumen_izin')->isValid()) {
            $dokumenPath = $request->file('dokumen_izin')->store('dokumen_mitra', 'public');
        }

        $mitra = Mitra::create([
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'email'                 => $request->email,
            'password'              => Hash::make($request->password),
            'no_telp'               => $request->no_telp,
            'jenis_mitra'           => $request->jenis_mitra,
            'is_verified'           => false,
            'catatan_admin'         => $dokumenPath,
        ]);

        if ($request->jenis_mitra === 'faskes') {
            $this->createFaskesProfile($mitra, $request);
        } elseif ($request->jenis_mitra === 'pariwisata' && class_exists(\App\Models\Pariwisata::class)) {
            $this->createPariwisataProfile($mitra, $request);
        }

        return redirect('/login')->with('success',
            'Pendaftaran berhasil dikirim! Akun Anda sedang menunggu verifikasi Admin WanderMed.'
        );
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    private function checkAdminLogin(string $email, string $password): bool
    {
        if ($email === config('wandermed.admin_email', 'admin@wandermed.id') &&
            $password === config('wandermed.admin_password', 'admin123')) {
            $this->setSession('admin', 0, 'Super Admin', $email);
            return true;
        }
        return false;
    }

    private function checkWisatawanLogin(string $email, string $password): ?RedirectResponse
    {
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            if (!$user->is_active) {
                return back()->with('error', 'Akun Anda telah DIBLOKIR oleh Admin.');
            }

            $this->setSession('wisatawan', $user->id, $user->name, $user->email);
            return redirect('/dashboard/wisatawan')->with('success', "Selamat datang, {$user->name}!");
        }
        return null;
    }

    private function checkMitraLogin(string $email, string $password): ?RedirectResponse
    {
        $mitra = Mitra::where('email', $email)->first();
        if ($mitra && Hash::check($password, $mitra->password)) {
            if (!$mitra->is_active) {
                return back()->with('error', 'Akun Faskes/Pariwisata Anda telah DIBLOKIR oleh Admin.');
            }
            if (!$mitra->is_verified) {
                return back()->with('error', 'Akun Anda belum diverifikasi oleh admin. Silakan tunggu konfirmasi.');
            }
            
            $role = ($mitra->jenis_mitra === 'faskes') ? 'mitra_faskes' : 'mitra_pariwisata';
            $this->setSession($role, $mitra->id, $mitra->nama_penanggung_jawab, $mitra->email, $mitra->jenis_mitra);
            
            return redirect('/dashboard/faskes')->with('success', "Selamat datang, {$mitra->nama_penanggung_jawab}!");
        }
        return null;
    }

    private function createFaskesProfile(Mitra $mitra, Request $request): void
    {
        $pengajuanPengumuman = trim($request->pengumuman ?? '');
        if ($request->has('layanan_ugd')) {
            $pengajuanPengumuman = "Layanan UGD: {$request->layanan_ugd}\n{$pengajuanPengumuman}";
        }

        Faskes::create([
            'mitra_id'           => $mitra->id,
            'nama_faskes'        => $request->nama_faskes ?? "{$mitra->nama_penanggung_jawab} Faskes",
            'jenis_faskes'       => $request->jenis_faskes ?? 'Klinik',
            'alamat'             => $request->alamat ?? '-',
            'no_telp'            => $request->no_telp,
            'latitude'           => $request->latitude ?? -6.5718,
            'longitude'          => $request->longitude ?? 107.7600,
            'status_operasional' => 'closed',
            'dukungan_bpjs'      => (bool) $request->dukungan_bpjs,
            'pengumuman'         => $pengajuanPengumuman,
        ]);
    }

    private function createPariwisataProfile(Mitra $mitra, Request $request): void
    {
        \App\Models\Pariwisata::create([
            'mitra_id'        => $mitra->id,
            'nama_pariwisata' => $request->nama_pariwisata ?? "{$mitra->nama_penanggung_jawab} Wisata",
            'jenis_wisata'    => $request->jenis_wisata ?? 'Alam',
            'alamat'          => $request->alamat ?? '-',
            'no_telp'         => $request->no_telp,
            'latitude'        => $request->latitude ?? -6.5718,
            'longitude'       => $request->longitude ?? 107.7600,
            'deskripsi'       => $request->deskripsi,
            'status'          => 'tutup',
        ]);
    }

    private function setSession(string $role, int $id, string $name, string $email, ?string $jenisMitra = null): void
    {
        session(['auth_user' => [
            'id'          => $id,
            'name'        => $name,
            'email'       => $email,
            'role'        => $role,
            'jenis_mitra' => $jenisMitra,
        ]]);
    }

    private function redirectByRole(string $role): RedirectResponse
    {
        return match($role) {
            'admin'            => redirect('/dashboard/admin'),
            'mitra_faskes', 
            'mitra_pariwisata' => redirect('/dashboard/faskes'),
            default            => redirect('/dashboard/wisatawan'),
        };
    }
}
