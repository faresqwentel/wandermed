<!-- Menghubungkan ke file layout utama di folder theme -->
@extends('theme.default')

<!-- Memasukkan konten ke dalam penanda @yield('content') di layout -->
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard WanderMed</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Cetak Laporan
        </a>
    </div>

    <!-- Content Row - Kartu Statistik -->
    <div class="row">
        <!-- Kartu Data Pariwisata -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pariwisata</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">25 Lokasi</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Data Fasilitas Kesehatan -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Fasilitas Kesehatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12 Instansi</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Data Wisatawan -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Wisatawan Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">150 User</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Area Teks Informasi -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Selamat Datang di Sistem Informasi WanderMed!</h6>
                </div>
                <div class="card-body">
                    <p>Sistem ini dirancang untuk mempermudah pengelolaan data fasilitas kesehatan dan destinasi pariwisata. Sesuai dengan panduan <strong>POLSUB</strong>, template ini telah berhasil dipotong (slicing) dan diintegrasikan menggunakan framework Laravel.</p>
                    <p class="mb-0">Silakan gunakan menu navigasi di sebelah kiri untuk menambah, mengedit, atau menghapus data yang ada di dalam sistem WanderMed.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
