<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_laporan_masalah_table
 *
 * Tabel untuk menampung laporan/komplain dari wisatawan kepada admin.
 * Contoh laporan: "Titik peta RS tidak sesuai lokasi aslinya."
 * Admin bisa melihat dan menyelesaikan laporan ini di Dashboard Admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_masalah', function (Blueprint $table) {
            $table->id();

            // --- Pelapor (Wisatawan) ---
            // Nullable agar wisatawan yang belum login pun bisa melaporkan
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null'); // Jika akun dihapus, laporan tetap ada (user_id jadi NULL)

            // --- Referensi ke Mitra yang Dilaporkan (Optional) ---
            // Nullable karena laporan bisa umum (tidak spesifik ke mitra)
            $table->foreignId('faskes_id')
                  ->nullable()
                  ->constrained('faskes')
                  ->onDelete('set null');

            // --- Konten Laporan ---
            $table->string('subjek'); // Judul singkat masalah
            $table->text('deskripsi'); // Penjelasan detail masalah yang dilaporkan

            // --- Status Penanganan oleh Admin ---
            // 'pending'  = Baru masuk, belum ditangani
            // 'on_review' = Sedang dicek oleh admin
            // 'resolved'  = Sudah diselesaikan
            $table->enum('status', ['pending', 'on_review', 'resolved'])->default('pending');

            // Catatan tindak lanjut dari admin setelah menyelesaikan laporan
            $table->text('catatan_penyelesaian')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_masalah');
    }
};
