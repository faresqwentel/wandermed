<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="theme-color" content="#112240">
    <meta name="msapplication-navbutton-color" content="#112240">
    <meta name="apple-mobile-web-app-status-bar-style" content="#112240">

    <title>WanderMed - Bantuan Medis Wisatawan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style-wisatawan.css') }}" rel="stylesheet">

    <style>
        /* CSS Dasar untuk mencegah flash putih saat loading CSS eksternal */
        html, body {
            background-color: #112240 !important; /* Warna Navy WanderMed */
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease;
        }

        /* Ganjal body agar konten tidak tertutup navbar fixed */
        body {
            padding-top: 100px !important;
            overflow-x: hidden;
        }

        @media (max-width: 991px) {
            body { padding-top: 85px !important; }
        }
    </style>
</head>

<body class="bg-hnb-navy">
    <script>
        (function() {
            const savedTheme = localStorage.getItem("wanderMedTheme");
            if (savedTheme === "light") {
                // Menambahkan class pada html dan body sekaligus agar selektor CSS kuat
                document.documentElement.classList.add("light-mode");
                document.body.classList.add("light-mode");
            }
        })();
    </script>

    <div class="w-100 d-flex flex-column min-vh-100">
        @yield('content')
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/script-wisatawan.js') }}"></script>
</body>
</html>
