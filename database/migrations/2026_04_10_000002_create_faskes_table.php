<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_faskes_table
 *
 * Tabel anak dari tabel 'mitras'.
 * Menyimpan semua data operasional dan spasial (koordinat peta)
 * setiap Fasilitas Kesehatan yang terdaftar di WanderMed.
 * Digunakan untuk rendering marker di Leaflet.js.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faskes', function (Blueprint $table) {
            $table->id();

            // --- Foreign Key ke tabel mitras ---
            // Setiap faskes PASTI dimiliki oleh satu mitra (relasi One-to-One)
            $table->foreignId('mitra_id')
                  ->constrained('mitras')
                  ->onDelete('cascade'); // Jika mitra dihapus, data faskes ikut terhapus

            // --- Identitas Fasilitas ---
            $table->string('nama_faskes');
            $table->enum('jenis_faskes', ['Rumah Sakit', 'Klinik', 'Apotek', 'Puskesmas', 'Lainnya']);
            $table->text('alamat');
            $table->string('no_telp', 20)->nullable();
            $table->string('foto_path')->nullable(); // Path ke file foto faskes

            // --- Data Spasial (Koordinat Peta) ---
            // Decimal(10,8) untuk latitude: akurasi hingga ~1.1mm di ekuator
            $table->decimal('latitude', 10, 8);
            // Decimal(11,8) untuk longitude: range -180 s.d. 180 derajat
            $table->decimal('longitude', 11, 8);

            // --- Status Operasional (Dikontrol oleh Mitra via Dashboard) ---
            // Status buka/tutup sementara — diupdate real-time oleh mitra
            $table->enum('status_operasional', ['open', 'closed'])->default('open');

            // --- Informasi BPJS ---
            // TRUE jika faskes menerima pasien dengan BPJS Kesehatan
            $table->boolean('dukungan_bpjs')->default(false);

            // --- Layanan & Fasilitas ---
            // Disimpan sebagai JSON untuk fleksibilitas daftar fasilitas
            // Contoh: ["UGD 24 Jam", "Ambulans", "Rawat Inap", "Apotek"]
            $table->json('layanan_tersedia')->nullable();

            // Pengumuman atau catatan sementara dari mitra untuk wisatawan
            // Contoh: "Stok oksigen terbatas. Poli gigi libur hari ini."
            $table->text('pengumuman')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faskes');
    }
};
