<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_pendaftaran_pariwisata_table
 *
 * Tabel INDEPENDEN untuk pendaftaran destinasi pariwisata.
 * Berbeda dengan faskes yang memiliki akun Mitra,
 * pengelola Pariwisata hanya menyerahkan data destinasi.
 * Verifikasi dilakukan Admin dan pemberitahuan via email.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_pariwisata', function (Blueprint $table) {
            $table->id();

            // --- Identitas Destinasi ---
            $table->string('nama_wisata');
            $table->string('kategori');           // Alam, Budaya, Buatan, Kuliner, Petualangan
            $table->text('deskripsi')->nullable();
            $table->text('alamat');
            $table->string('foto_path')->nullable(); // path dokumen/foto yang diupload

            // --- Koordinat Peta ---
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // --- Kontak Pengelola (untuk Admin hubungi via email) ---
            $table->string('nama_pengelola');
            $table->string('email_kontak');       // Admin akan mengirim keputusan ke sini
            $table->string('no_telp')->nullable();
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->integer('harga_tiket')->default(0); // 0 = gratis

            // --- Status Review Admin ---
            $table->enum('status_review', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable(); // Alasan tolak atau pesan dari admin

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pariwisata');
    }
};
