<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>WanderMed - Bantuan Medis</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style-wisatawan.css') }}" rel="stylesheet">

    <style>
        /* Ganjal body global agar konten tidak tertutup navbar fixed yang tebal */
        body {
            padding-top: 100px !important;
            overflow-x: hidden;
            transition: background-color 0.3s ease;
        }

        @media (max-width: 991px) {
            body { padding-top: 85px !important; }
        }
    </style>
</head>
<body class="bg-hnb-navy">
    <div class="w-100 d-flex flex-column min-vh-100">
        @yield('content')
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script-wisatawan.js') }}"></script>
</body>
</html>
