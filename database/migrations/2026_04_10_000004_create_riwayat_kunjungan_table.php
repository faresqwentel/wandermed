<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_riwayat_kunjungan_table
 *
 * Tabel penghubung (pivot/junction table) antara 'users' dan 'faskes'.
 * Ini adalah KUNCI dari fitur Dashboard Wisatawan:
 * - Wisatawan mencatat faskes yang pernah dikunjungi
 * - Bisa memberikan label warna (rekomendasi personal)
 * - Bisa menambahkan catatan pribadi (pengalaman/notes)
 *
 * Relasi: Many-to-Many antara users dan faskes (dengan extra columns)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kunjungan', function (Blueprint $table) {
            $table->id();

            // --- Foreign Key ke tabel users (Wisatawan) ---
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Jika akun wisatawan dihapus, riwayatnya terhapus

            // --- Foreign Key ke tabel faskes ---
            $table->foreignId('faskes_id')
                  ->constrained('faskes')
                  ->onDelete('cascade'); // Jika faskes dihapus dari sistem, riwayat juga ikut

            // --- Data Kunjungan ---
            // Tanggal wisatawan mengunjungi faskes tersebut
            $table->date('tanggal_kunjungan');

            // Label warna penilaian personal wisatawan
            // 'green' = Sangat Direkomendasikan
            // 'yellow' = Standar / Cukup
            // 'red' = Tidak Direkomendasikan
            $table->string('label_warna', 10)->default('yellow')
                  ->comment('green=Sangat Direk, yellow=Standar, red=Tidak Direk');

            // Catatan pribadi wisatawan (misal: "Dokternya ramah, parkiran luas")
            $table->text('catatan_pribadi')->nullable();

            $table->timestamps();

            // Index gabungan untuk mencegah duplikasi dan mempercepat query
            // Satu wisatawan bisa punya beberapa catatan untuk faskes yang sama (berbeda tanggal)
            $table->index(['user_id', 'faskes_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kunjungan');
    }
};
