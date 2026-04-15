<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PendaftaranPariwisata;

/**
 * PariwisataController
 *
 * Mengelola pendaftaran destinasi wisata secara mandiri (tanpa akun).
 * Pengelola wisata hanya mengisi form, Admin verifikasi dan
 * mengirim konfirmasi via email yang dicantumkan.
 */
class PariwisataController extends Controller
{
    // =========================================================
    // SUBMIT PENDAFTARAN PARIWISATA (Tanpa Akun)
    // =========================================================
    public function submitPendaftaran(Request $request)
    {
        $request->validate([
            'nama_wisata'    => 'required|string|max:150',
            'kategori'       => 'required|string',
            'deskripsi'      => 'nullable|string',
            'alamat'         => 'required|string',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'nama_pengelola' => 'required|string|max:100',
            'email_kontak'   => 'required|email|max:100',
            'no_telp'        => 'required|string|max:20',
            'harga_tiket'    => 'nullable|integer|min:0',
            'foto_path'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'nama_wisata.required'    => 'Nama destinasi wisata wajib diisi.',
            'alamat.required'         => 'Alamat lengkap wajib diisi.',
            'nama_pengelola.required' => 'Nama pengelola wajib diisi.',
            'email_kontak.required'   => 'Email kontak wajib diisi agar Admin dapat menghubungi Anda.',
            'email_kontak.email'      => 'Format email tidak valid.',
            'no_telp.required'        => 'Nomor telepon wajib diisi.',
        ]);

        // Handle upload foto/dokumen
        $fotoPath = null;
        if ($request->hasFile('foto_path') && $request->file('foto_path')->isValid()) {
            $fotoPath = $request->file('foto_path')->store('dokumen_mitra', 'public');
        }

        // Simpan pengajuan ke tabel pendaftaran_pariwisata
        PendaftaranPariwisata::create([
            'nama_wisata'    => $request->nama_wisata,
            'kategori'       => $request->kategori,
            'deskripsi'      => $request->deskripsi,
            'alamat'         => $request->alamat,
            'foto_path'      => $fotoPath,
            'latitude'       => $request->latitude ?: null,
            'longitude'      => $request->longitude ?: null,
            'nama_pengelola' => $request->nama_pengelola,
            'email_kontak'   => $request->email_kontak,
            'no_telp'        => $request->no_telp,
            'harga_tiket'    => $request->harga_tiket ?? 0,
            'status_review'  => 'menunggu',
        ]);

        return redirect('/daftar/pariwisata')
            ->with('success',
                'Terima kasih! Pendaftaran destinasi wisata Anda telah kami terima. ' .
                'Admin WanderMed akan menghubungi Anda melalui email <strong>' .
                $request->email_kontak . '</strong> untuk konfirmasi selanjutnya.'
            );
    }

    // =========================================================
    // APPROVE PARIWISATA (Dipanggil Admin via AJAX)
    // =========================================================
    public function approve(Request $request, $id)
    {
        $pendaftaran = PendaftaranPariwisata::findOrFail($id);
        $pendaftaran->update(['status_review' => 'disetujui']);

        return response()->json([
            'success' => true,
            'message' => "Destinasi \"{$pendaftaran->nama_wisata}\" berhasil disetujui.",
        ]);
    }

    // =========================================================
    // REJECT PARIWISATA (Dipanggil Admin via AJAX)
    // =========================================================
    public function reject(Request $request, $id)
    {
        $pendaftaran = PendaftaranPariwisata::findOrFail($id);
        $catatan = $request->input('catatan', 'Tidak memenuhi kriteria.');
        $pendaftaran->update([
            'status_review' => 'ditolak',
            'catatan_admin' => $catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Pendaftaran \"{$pendaftaran->nama_wisata}\" telah ditolak.",
        ]);
    }
}
