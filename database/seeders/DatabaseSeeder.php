<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\RiwayatKunjungan;
use App\Models\LaporanMasalah;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =======================================================
        // 1. DATA WISATAWAN (End-User)
        // =======================================================
        $wisatawan1 = User::create([
            'name'           => 'Fares Qwentel',
            'email'          => 'fares@wisata.id',
            'password'       => Hash::make('password123'),
            'gol_darah'      => 'A',
            'riwayat_alergi' => 'Alergi dingin ekstreme',
            'kontak_darurat' => '081234567890',
        ]);

        $wisatawan2 = User::create([
            'name'           => 'Budi Subang',
            'email'          => 'budi@wisata.id',
            'password'       => Hash::make('password123'),
            'gol_darah'      => 'O',
        ]);


        // =======================================================
        // 2. DATA MITRA FASKES
        // =======================================================
        $mitraFaskes1 = Mitra::create([
            'nama_penanggung_jawab' => 'dr. Hendra Setiawan',
            'email'                 => 'rsud@subang.id',
            'password'              => Hash::make('mitra123'),
            'no_telp'               => '081122334455',
            'jenis_mitra'           => 'faskes',
            'is_verified'           => true, // Sudah di-approve admin
        ]);

        $mitraFaskes2 = Mitra::create([
            'nama_penanggung_jawab' => 'Apt. Rina Marlina',
            'email'                 => 'apotek@subang.id',
            'password'              => Hash::make('mitra123'),
            'no_telp'               => '082233445566',
            'jenis_mitra'           => 'faskes',
            'is_verified'           => true,
        ]);

        $mitraFaskesPending = Mitra::create([
            'nama_penanggung_jawab' => 'Klinik Pratama Baru',
            'email'                 => 'klinikbaru@subang.id',
            'password'              => Hash::make('mitra123'),
            'no_telp'               => '0833',
            'jenis_mitra'           => 'faskes',
            'is_verified'           => false, // Belum di-approve
        ]);


        // =======================================================
        // 3. FASKES MAP DATA
        // =======================================================
        $rsud = Faskes::create([
            'mitra_id'           => $mitraFaskes1->id,
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
            'mitra_id'           => $mitraFaskes2->id,
            'nama_faskes'        => 'Apotek K-24',
            'jenis_faskes'       => 'Apotek',
            'latitude'           => -6.5650,
            'longitude'          => 107.7500,
            'status_operasional' => 'open',
            'dukungan_bpjs'      => false,
            'alamat'             => 'Jl. Raya Cibogo No.12, Subang',
            'no_telp'            => '(0260) 422111',
            'layanan_tersedia'   => ['Obat Keras', 'Apotek'],
        ]);


        // =======================================================
        // 4. RIWAYAT KUNJUNGAN (Untuk Dashboard Wisatawan)
        // =======================================================
        RiwayatKunjungan::create([
            'user_id'           => $wisatawan1->id,
            'faskes_id'         => $rsud->id,
            'tanggal_kunjungan' => Carbon::now()->subDays(5),
            'label_warna'       => 'green',
            'catatan_pribadi'   => 'Dokternya teliti dan perawat ramah.',
        ]);

        RiwayatKunjungan::create([
            'user_id'           => $wisatawan1->id,
            'faskes_id'         => $apotek->id,
            'tanggal_kunjungan' => Carbon::now()->subDays(1),
            'label_warna'       => 'yellow',
            'catatan_pribadi'   => 'Antrean agak lama, tapi obat lengkap.',
        ]);


        // =======================================================
        // 5. LAPORAN MASALAH (Untuk Dashboard Admin)
        // =======================================================
        LaporanMasalah::create([
            'user_id'              => $wisatawan2->id,
            'faskes_id'            => $rsud->id,
            'subjek'               => 'Titik lokasi geser',
            'deskripsi'            => 'Lokasi RSUD di peta agak kurang geser ke selatan dikit dari pintu masuk.',
            'status'               => 'pending',
        ]);
        
        $this->command->info('Database berhasil di-seed dengan data Faskes dan Wisatawan!');
    }
}
