<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_pariwisatas_table
 *
 * Tabel anak dari tabel 'mitras'.
 * Menyimpan data destinasi wisata yang terdaftar di WanderMed.
 * Pengelola wisata bisa memberikan info keselamatan dan panduan darurat
 * agar wisatawan tahu faskes terdekat dari destinasi mereka.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pariwisatas', function (Blueprint $table) {
            $table->id();

            // --- Foreign Key ke tabel mitras ---
            // Setiap destinasi wisata dimiliki oleh satu mitra (relasi One-to-One)
            $table->foreignId('mitra_id')
                  ->constrained('mitras')
                  ->onDelete('cascade');

            // --- Identitas Destinasi ---
            $table->string('nama_wisata');
            $table->string('kategori'); // Contoh: Alam, Budaya, Kuliner, Adrenalin
            $table->text('deskripsi')->nullable();
            $table->text('alamat');
            $table->string('foto_path')->nullable();

            // --- Data Spasial (Koordinat Peta) ---
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            // --- Info Keselamatan & Panduan Darurat ---
            // Diisi oleh mitra pariwisata, contoh:
            // "Area rawan longsor saat hujan. Faskes terdekat: Klinik Cibogo (2km)."
            $table->text('info_keselamatan')->nullable();

            // Nomor kontak darurat wisata (mis: ranger, pemandu, atau pos jaga)
            $table->string('kontak_wisata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pariwisatas');
    }
};
