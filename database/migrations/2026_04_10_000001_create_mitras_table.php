<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_mitras_table
 *
 * Tabel induk (parent) untuk semua aktor mitra di WanderMed.
 * Menjadi satu pintu autentikasi bagi Mitra Faskes dan Mitra Pariwisata.
 * Tabel anak (faskes & pariwisatas) memiliki FK ke tabel ini.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();

            // --- Informasi Autentikasi ---
            $table->string('nama_penanggung_jawab');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_telp', 20)->nullable();

            // --- Jenis & Status Mitra ---
            // Menentukan apakah mitra ini mengelola Faskes atau Pariwisata
            $table->enum('jenis_mitra', ['faskes', 'pariwisata']);

            // Status verifikasi oleh admin.
            // Default FALSE, berubah TRUE setelah admin menekan tombol 'Approve'
            $table->boolean('is_verified')->default(false);

            // Alasan penolakan jika admin menekan 'Reject' (nullable)
            $table->text('catatan_admin')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
