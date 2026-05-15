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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
     * Mengganti password pengguna (Wisatawan & Faskes).
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $role = session('auth_user.role');
        $id = session('auth_user.id');

        if ($role === 'wisatawan') {
            $user = User::find($id);
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Password saat ini salah.');
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
        } elseif (in_array($role, ['mitra_faskes', 'mitra_pariwisata'])) {
            $mitra = Mitra::find($id);
            if (!Hash::check($request->current_password, $mitra->password)) {
                return back()->with('error', 'Password saat ini salah.');
            }
            $mitra->password = Hash::make($request->new_password);
            $mitra->save();
        } else {
            return back()->with('error', 'Gagal mengganti password.');
        }

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Mendaftarkan akun Wisatawan baru.
     */
    public function registerWisatawan(WisatawanRegistrationRequest $request): RedirectResponse
    {
        $pin = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'recovery_pin'   => $pin,
            'gol_darah'      => $request->gol_darah,
            'kontak_darurat' => $request->kontak_darurat,
            'riwayat_alergi' => $request->riwayat_alergi,
        ]);

        $this->setSession('wisatawan', $user->id, $user->name, $user->email);

        return redirect('/dashboard/wisatawan')->with('success', 'Akun berhasil dibuat! Selamat datang di WanderMed.');
    }

    /**
     * Mendaftarkan akun Mitra (Faskes).
     */
    public function registerMitra(MitraRegistrationRequest $request): RedirectResponse
    {
        $dokumenPath = null;
        if ($request->hasFile('dokumen_izin') && $request->file('dokumen_izin')->isValid()) {
            $dokumenPath = $request->file('dokumen_izin')->store('dokumen_mitra', 'public');
        }

        $pin = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $mitra = Mitra::create([
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'email'                 => $request->email,
            'password'              => Hash::make($request->password),
            'recovery_pin'          => $pin,
            'no_telp'               => $request->no_telp,
            'jenis_mitra'           => 'faskes',
            'is_verified'           => false,
            'catatan_admin'         => $dokumenPath,
        ]);

        $this->createFaskesProfile($mitra, $request);

        return redirect('/login')->with('success',
            'Pendaftaran berhasil dikirim! Akun Anda sedang menunggu verifikasi Admin WanderMed.'
        );
    }

    /**
     * Memproses reset password menggunakan PIN.
     */
    public function resetPasswordViaPin(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'recovery_pin' => 'required|string|size:6',
            'password' => 'required|min:8|confirmed'
        ]);

        $email = $request->email;
        $pin = $request->recovery_pin;

        // Cek Wisatawan
        $user = User::where('email', $email)->where('recovery_pin', $pin)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['success' => true, 'message' => 'Password akun Wisatawan berhasil direset!']);
        }

        // Cek Mitra
        $mitra = Mitra::where('email', $email)->where('recovery_pin', $pin)->first();
        if ($mitra) {
            $mitra->password = Hash::make($request->password);
            $mitra->save();
            return response()->json(['success' => true, 'message' => 'Password akun Mitra berhasil direset!']);
        }

        return response()->json(['success' => false, 'message' => 'Email atau PIN rahasia tidak cocok!']);
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    private function checkAdminLogin(string $email, string $password): bool
    {
        if ($email === config('wandermed.admin_email', 'adminwandermed@gmail.com') &&
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
                $reason = $user->blocking_reason ?? 'Pelanggaran ketentuan layanan.';
                return back()->with('error', "Akun Anda telah DIBLOKIR oleh Admin. Alasan: {$reason}");
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
                $reason = $mitra->blocking_reason ?? 'Pelanggaran ketentuan layanan.';
                return back()->with('error', "Akun Faskes Anda telah DIBLOKIR oleh Admin. Alasan: {$reason}");
            }
            if (!$mitra->is_verified) {
                return back()->with('error', 'Akun Anda belum diverifikasi oleh admin. Silakan tunggu konfirmasi.');
            }
            
            $this->setSession('mitra_faskes', $mitra->id, $mitra->nama_penanggung_jawab, $mitra->email, 'faskes');
            
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
