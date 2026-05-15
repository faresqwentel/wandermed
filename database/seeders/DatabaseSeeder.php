<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\PendaftaranPariwisata;
use App\Models\RiwayatKunjungan;
use App\Models\LaporanMasalah;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =======================================================
        // 1. DATA WISATAWAN (Tabel: users)
        // =======================================================
        $wisatawan1 = User::create([
            'name'           => 'Fares Qwentel',
            'email'          => 'fares@gmail.com',
            'password'       => Hash::make('password123'),
            'recovery_pin'   => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'gol_darah'      => 'A',
            'riwayat_alergi' => 'Alergi dingin ekstreme',
            'kontak_darurat' => '081234567890',
        ]);

        $wisatawan2 = User::create([
            'name'           => 'Aditya Saputra',
            'email'          => 'adityazsaputra11@gmail.com',
            'password'       => Hash::make('password123'),
            'recovery_pin'   => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'gol_darah'      => 'O',
        ]);

        $wisatawan3 = User::create([
            'name'           => 'Siti Aminah',
            'email'          => 'siti@gmail.com',
            'password'       => Hash::make('password123'),
            'recovery_pin'   => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'gol_darah'      => 'B',
            'riwayat_alergi' => 'Antibiotik Amoxicillin',
        ]);


        // =======================================================
        // 2. DATA MITRA (Tabel: mitras)
        // =======================================================
        // Mitra Faskes
        $mitraF1 = Mitra::create(['nama_penanggung_jawab' => 'dr. Hendra Setiawan', 'email' => 'rsudsubang@gmail.com', 'password' => Hash::make('mitra123'), 'recovery_pin' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT), 'no_telp' => '081122334455', 'jenis_mitra' => 'faskes', 'is_verified' => true]);
        $mitraF2 = Mitra::create(['nama_penanggung_jawab' => 'Brilyan Faresya', 'email' => 'brilyannfaresya02@gmail.com', 'password' => Hash::make('mitra123'), 'recovery_pin' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT), 'no_telp' => '082233445566', 'jenis_mitra' => 'faskes', 'is_verified' => true]);
        $mitraF3 = Mitra::create(['nama_penanggung_jawab' => 'drg. Maya Indah', 'email' => 'klinikmaya@gmail.com', 'password' => Hash::make('mitra123'), 'recovery_pin' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT), 'no_telp' => '081299008811', 'jenis_mitra' => 'faskes', 'is_verified' => true]);
        $mitraF4 = Mitra::create(['nama_penanggung_jawab' => 'Kepala Puskesmas Ciater', 'email' => 'puskesmasciater@gmail.com', 'password' => Hash::make('mitra123'), 'recovery_pin' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT), 'no_telp' => '082211002233', 'jenis_mitra' => 'faskes', 'is_verified' => true]);


        // =======================================================
        // 3. FASKES DETAILS (Tabel: faskes)
        // =======================================================
        $rsud = Faskes::create([
            'mitra_id'           => $mitraF1->id,
            'nama_faskes'        => 'RSUD Subang',
            'jenis_faskes'       => 'Rumah Sakit',
            'latitude'           => -6.5710,
            'longitude'          => 107.7600,
            'status_operasional' => 'open',
            'dukungan_bpjs'      => true,
            'alamat'             => 'Jl. Brigjen Katamso No.37, Subang',
            'no_telp'            => '(0260) 411421',
            'pengumuman'         => 'Antrean poli umum buka pukul 07.00 WIB.',
            'layanan_tersedia'   => ['UGD 24 Jam', 'Ambulans', 'Rawat Inap', 'Dok. Spesialis'],
        ]);

        $apotek = Faskes::create([
            'mitra_id'           => $mitraF2->id,
            'nama_faskes'        => 'Apotek K-24 Subang',
            'jenis_faskes'       => 'Apotek',
            'latitude'           => -6.5650,
            'longitude'          => 107.7500,
            'status_operasional' => 'open',
            'dukungan_bpjs'      => false,
            'alamat'             => 'Jl. Raya Cibogo No.12, Subang',
            'no_telp'            => '(0260) 422111',
            'layanan_tersedia'   => ['Obat Keras', 'Apotek', 'Poli Umum'],
        ]);

        $klinikGigi = Faskes::create([
            'mitra_id'           => $mitraF3->id,
            'nama_faskes'        => 'Klinik Gigi Sehat',
            'jenis_faskes'       => 'Klinik',
            'latitude'           => -6.5750,
            'longitude'          => 107.7650,
            'status_operasional' => 'open',
            'dukungan_bpjs'      => true,
            'alamat'             => 'Jl. Otista No. 45, Subang',
            'no_telp'            => '081299008811',
            'layanan_tersedia'   => ['Poli Gigi', 'Apotek'],
        ]);

        Faskes::create([
            'mitra_id'           => $mitraF4->id,
            'nama_faskes'        => 'Puskesmas Ciater',
            'jenis_faskes'       => 'Puskesmas',
            'latitude'           => -6.7400,
            'longitude'          => 107.6600,
            'status_operasional' => 'open',
            'dukungan_bpjs'      => true,
            'alamat'             => 'Jl. Raya Ciater, Subang',
            'no_telp'            => '(0260) 470001',
            'layanan_tersedia'   => ['Poli Umum', 'Ambulans', 'Imunisasi'],
        ]);


        // =======================================================
        // 4. PARIWISATA DETAILS (Tabel: pendaftaran_pariwisata)
        // =======================================================
        // Pariwisata di sini murni dari pendaftaran (Tanpa Akun)
        PendaftaranPariwisata::create([
            'nama_wisata'    => 'Sari Ater Hot Spring',
            'kategori'       => 'Alam',
            'deskripsi'      => 'Pemandian air panas alami dari kawah Gunung Tangkuban Perahu.',
            'alamat'         => 'Jl. Raya Ciater, Ciater, Subang',
            'latitude'       => -6.7360,
            'longitude'      => 107.6530,
            'nama_pengelola' => 'Bapak Asep',
            'email_kontak'   => 'asepsariater@gmail.com',
            'no_telp'        => '081199887766',
            'status_review'  => 'disetujui',
            'harga_tiket'    => 35000,
        ]);

        PendaftaranPariwisata::create([
            'nama_wisata'    => 'Kawah Tangkuban Perahu',
            'kategori'       => 'Alam',
            'deskripsi'      => 'Gunung api aktif dengan pemandangan kawah memukau.',
            'alamat'         => 'Cikahuripan, Lembang, Bandung Barat',
            'latitude'       => -6.7596,
            'longitude'      => 107.6096,
            'nama_pengelola' => 'Ibu Lilis',
            'email_kontak'   => 'lilistangkuban@gmail.com',
            'no_telp'        => '082299887766',
            'status_review'  => 'disetujui',
            'harga_tiket'    => 20000,
        ]);

        PendaftaranPariwisata::create([
            'nama_wisata'    => 'Florawisata DCastello',
            'kategori'       => 'Buatan',
            'deskripsi'      => 'Taman bunga dengan kastil megah bergaya Rusia dan Turki.',
            'alamat'         => 'Jl. Raya Ciater, Subang',
            'latitude'       => -6.7123,
            'longitude'      => 107.6712,
            'nama_pengelola' => 'Pak Dedi',
            'email_kontak'   => 'dedidcastello@gmail.com',
            'no_telp'        => '081377889900',
            'status_review'  => 'disetujui',
            'harga_tiket'    => 30000,
        ]);


        // =======================================================
        // 5. RIWAYAT KUNJUNGAN & LAPORAN
        // =======================================================
        RiwayatKunjungan::create(['user_id' => $wisatawan1->id, 'faskes_id' => $rsud->id, 'tanggal_kunjungan' => Carbon::now()->subDays(5), 'label_warna' => 'green', 'catatan_pribadi' => 'Cek kesehatan rutin.']);
        LaporanMasalah::create(['user_id' => $wisatawan2->id, 'faskes_id' => $rsud->id, 'subjek' => 'Lokasi Peta', 'deskripsi' => 'Pintu masuk UGD sedang diperbaiki.', 'status' => 'pending']);
        

        // =======================================================
        // 6. ULASAN FASKES
        // =======================================================
        \App\Models\UlasanFaskes::create(['user_id' => $wisatawan1->id, 'faskes_id' => $rsud->id, 'rating' => 5, 'komentar' => 'Sangat cepat saat keadaan darurat malam hari.']);
        \App\Models\UlasanFaskes::create(['user_id' => $wisatawan2->id, 'faskes_id' => $rsud->id, 'rating' => 4, 'komentar' => 'Dokternya ramah, antrean pagi lumayan panjang.']);
        \App\Models\UlasanFaskes::create(['user_id' => $wisatawan3->id, 'faskes_id' => $apotek->id, 'rating' => 5, 'komentar' => 'Obat lengkap dan harga transparan.']);
        \App\Models\UlasanFaskes::create(['user_id' => $wisatawan1->id, 'faskes_id' => $klinikGigi->id, 'rating' => 5, 'komentar' => 'Modern dan nyaman. Recomended!']);

        $this->command->info('Database WanderMed berhasil di-seed dengan data lengkap!');
    }
}
