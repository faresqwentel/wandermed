<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Message;
use App\Models\Mitra;
use App\Models\Faskes;

/**
 * ChatController
 *
 * Mengelola ruang obrolan dua arah antara Admin dan Mitra Faskes.
 * Menggunakan AJAX polling untuk real-time updates.
 */
class ChatController extends Controller
{
    // =========================================================
    // ADMIN: Halaman chat (SPA dalam sectionChat di dashboard_admin)
    // =========================================================

    /**
     * API: Daftar kontak Mitra Faskes beserta pesan terakhir & unread count.
     */
    public function adminContacts(): JsonResponse
    {
        $mitras = Mitra::where('jenis_mitra', 'faskes')
            ->where('is_verified', true)
            ->with('faskes:id,mitra_id,nama_faskes,jenis_faskes')
            ->get()
            ->map(function (Mitra $m) {
                $lastMsg = Message::where('mitra_id', $m->id)
                    ->latest()
                    ->first();

                $unread = Message::where('mitra_id', $m->id)
                    ->where('sender_role', 'mitra')
                    ->where('read_by_admin', false)
                    ->count();

                return [
                    'id'           => $m->id,
                    'nama'         => $m->faskes?->nama_faskes ?? $m->nama_penanggung_jawab,
                    'jenis'        => $m->faskes?->jenis_faskes ?? 'Faskes',
                    'initial'      => strtoupper(substr($m->faskes?->nama_faskes ?? $m->nama_penanggung_jawab, 0, 1)),
                    'last_message' => $lastMsg?->body ?? null,
                    'last_time'    => $lastMsg?->created_at?->format('H:i') ?? null,
                    'last_date'    => $lastMsg?->created_at?->diffForHumans() ?? null,
                    'unread'       => $unread,
                ];
            });

        return response()->json($mitras);
    }

    /**
     * API: Ambil riwayat pesan untuk satu kontak (mitra_id).
     * Sekaligus tandai semua pesan mitra sebagai sudah dibaca admin.
     */
    public function adminMessages(int $mitraId): JsonResponse
    {
        // Validasi mitra exists
        $mitra = Mitra::with('faskes:id,mitra_id,nama_faskes,jenis_faskes')->findOrFail($mitraId);

        // Mark as read oleh admin
        Message::where('mitra_id', $mitraId)
            ->where('sender_role', 'mitra')
            ->where('read_by_admin', false)
            ->update(['read_by_admin' => true]);

        $messages = Message::where('mitra_id', $mitraId)
            ->orderBy('created_at')
            ->get()
            ->map(fn (Message $msg) => $this->formatMessage($msg));

        return response()->json([
            'mitra'    => [
                'id'    => $mitra->id,
                'nama'  => $mitra->faskes?->nama_faskes ?? $mitra->nama_penanggung_jawab,
                'jenis' => $mitra->faskes?->jenis_faskes ?? 'Faskes',
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * API: Admin kirim pesan ke Mitra.
     */
    public function adminSend(Request $request): JsonResponse
    {
        $request->validate([
            'mitra_id' => 'required|exists:mitras,id',
            'body'     => 'required|string|max:2000',
        ]);

        $msg = Message::create([
            'mitra_id'       => $request->mitra_id,
            'sender_role'    => 'admin',
            'body'           => trim($request->body),
            'read_by_mitra'  => false,
            'read_by_admin'  => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => $this->formatMessage($msg),
        ]);
    }

    /**
     * API: Polling — ambil pesan baru setelah last_id tertentu (Admin).
     */
    public function adminPoll(Request $request, int $mitraId): JsonResponse
    {
        $lastId = (int) $request->query('last_id', 0);

        // Mark as read
        Message::where('mitra_id', $mitraId)
            ->where('sender_role', 'mitra')
            ->where('read_by_admin', false)
            ->update(['read_by_admin' => true]);

        $newMessages = Message::where('mitra_id', $mitraId)
            ->where('id', '>', $lastId)
            ->orderBy('created_at')
            ->get()
            ->map(fn (Message $msg) => $this->formatMessage($msg));

        return response()->json(['messages' => $newMessages]);
    }

    // =========================================================
    // MITRA FASKES: Ruang chat langsung dengan Admin
    // =========================================================

    /**
     * AJAX: Ambil semua pesan chat mitra ini dengan Admin.
     * Sekaligus tandai pesan admin sebagai sudah dibaca mitra.
     */
    public function mitraMessages(): JsonResponse
    {
        $mitraId = session('auth_user.id');

        Message::where('mitra_id', $mitraId)
            ->where('sender_role', 'admin')
            ->where('read_by_mitra', false)
            ->update(['read_by_mitra' => true]);

        $messages = Message::where('mitra_id', $mitraId)
            ->orderBy('created_at')
            ->get()
            ->map(fn (Message $msg) => $this->formatMessage($msg));

        return response()->json(['messages' => $messages]);
    }

    /**
     * API: Mitra kirim pesan ke Admin.
     */
    public function mitraSend(Request $request): JsonResponse
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $mitraId = session('auth_user.id');

        $msg = Message::create([
            'mitra_id'       => $mitraId,
            'sender_role'    => 'mitra',
            'body'           => trim($request->body),
            'read_by_mitra'  => true,
            'read_by_admin'  => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $this->formatMessage($msg),
        ]);
    }

    /**
     * API: Polling — ambil pesan baru setelah last_id tertentu (Mitra).
     */
    public function mitraPoll(Request $request): JsonResponse
    {
        $mitraId = session('auth_user.id');
        $lastId  = (int) $request->query('last_id', 0);

        Message::where('mitra_id', $mitraId)
            ->where('sender_role', 'admin')
            ->where('read_by_mitra', false)
            ->update(['read_by_mitra' => true]);

        $newMessages = Message::where('mitra_id', $mitraId)
            ->where('id', '>', $lastId)
            ->orderBy('created_at')
            ->get()
            ->map(fn (Message $msg) => $this->formatMessage($msg));

        return response()->json(['messages' => $newMessages]);
    }

    /**
     * API: Jumlah pesan belum dibaca (untuk badge notifikasi Mitra).
     */
    public function mitraUnreadCount(): JsonResponse
    {
        $mitraId = session('auth_user.id');
        $count   = Message::where('mitra_id', $mitraId)
            ->where('sender_role', 'admin')
            ->where('read_by_mitra', false)
            ->count();

        return response()->json(['unread' => $count]);
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    private function formatMessage(Message $msg): array
    {
        return [
            'id'          => $msg->id,
            'body'        => $msg->body,
            'sender_role' => $msg->sender_role,
            'time'        => $msg->created_at->format('H:i'),
            'date'        => $msg->created_at->format('d M Y'),
            'datetime'    => $msg->created_at->toIso8601String(),
        ];
    }
}
