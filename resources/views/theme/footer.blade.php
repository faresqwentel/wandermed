<footer class="footer-premier pt-5 mt-5">
    <div class="container px-4">
        <div class="row mb-5">
            <!-- Brand & Info -->
            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <a href="/" class="text-decoration-none footer-logo-text">
                        <span class="font-weight-bold footer-text-main" style="font-size: 2.2rem; letter-spacing: 1px;">Wander<span class="text-hnb-orange">Med</span></span>
                    </a>
                </div>
                <p class="footer-text-muted pr-lg-5" style="font-size: 0.95rem; line-height: 1.8;">
                    Sistem Manajemen Ekosistem Sehat pertama yang mengintegrasikan keamanan pariwisata dengan tanggap darurat medis di wilayah Subang secara real-time.
                </p>
                <div class="social-links mt-4">
                    <a href="https://github.com/faresbrilyan/wandermed" target="_blank" class="social-icon" title="View on GitHub"><i class="fab fa-github"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-text-main font-weight-bold mb-4" style="font-size: 1.1rem;">Eksplorasi</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="/">Beranda</a></li>
                    <li><a href="/peta-faskes">Peta Faskes Live</a></li>
                    <li><a href="/daftar">Gabung Mitra</a></li>
                    <li><a href="/login">Masuk Akun</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-text-main font-weight-bold mb-4" style="font-size: 1.1rem;">Layanan</h5>
                <ul class="list-unstyled footer-list-text" style="font-size: 0.95rem;">
                    <li class="mb-3 footer-text-muted">UGD 24 Jam</li>
                    <li class="mb-3 footer-text-muted">Klinik Umum</li>
                    <li class="mb-3 footer-text-muted">Apotek Reguler</li>
                    <li class="mb-3 footer-text-muted">Faskes BPJS</li>
                </ul>
            </div>

            <!-- Contact/Support -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-text-main font-weight-bold mb-4" style="font-size: 1.1rem;">Hubungi Kami</h5>
                <ul class="list-unstyled footer-contact footer-text-muted" style="font-size: 0.95rem; line-height: 1.8;">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-map-marker-alt text-hnb-orange mt-1 mr-3"></i>
                        <span>Subang, Jawa Barat<br>Indonesia</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="fas fa-envelope text-hnb-orange mr-3"></i>
                        <span>support@wandermed.id</span>
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="fas fa-phone-alt text-hnb-orange mr-3"></i>
                        <span>087775733922</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Footer: Copyright & Credits -->
        <div class="footer-bottom py-4 footer-border-top">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center text-lg-left mb-3 mb-lg-0">
                    <p class="footer-text-muted mb-0" style="font-size: 0.9rem;">
                        &copy; {{ date('Y') }} WanderMed. All rights reserved.
                    </p>
                </div>
                <div class="col-lg-8 text-center text-lg-right">
                    <div class="d-inline-flex flex-wrap align-items-center justify-content-center justify-content-lg-end" style="font-size: 0.9rem; gap: 8px;">
                        <span class="footer-text-muted">Made with <i class="fas fa-heart text-danger mx-1 heart-beat"></i> by</span>
                        <strong class="footer-text-main" style="letter-spacing: 0.5px;">Hear & Build Studio</strong> 
                        <span class="footer-text-muted mx-1 d-none d-sm-inline">|</span> 
                        <span class="footer-text-muted">
                            Coded by <strong class="text-hnb-orange">Faresya</strong> & <strong class="text-hnb-orange">Sahwa</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Default / Dark Mode Footer Variables (WanderMed is dark by default) */
:root {
    --footer-bg: #111827;
    --footer-text-main: #ffffff;
    --footer-text-muted: rgba(255, 255, 255, 0.5);
    --footer-border: rgba(255, 255, 255, 0.08);
    --footer-icon-bg: rgba(255, 255, 255, 0.05);
    --footer-icon-border: rgba(255, 255, 255, 0.1);
}

/* Light Mode Footer Variables */
html.light-mode {
    --footer-bg: #f8f9fc;
    --footer-text-main: #2e384d;
    --footer-text-muted: #6e7687;
    --footer-border: rgba(0, 0, 0, 0.08);
    --footer-icon-bg: rgba(0, 0, 0, 0.04);
    --footer-icon-border: rgba(0, 0, 0, 0.08);
}

/* Base styling */
.footer-text-main { color: var(--footer-text-main); transition: color 0.3s ease; }
.footer-text-muted { color: var(--footer-text-muted); transition: color 0.3s ease; }
.footer-border-top { border-top: 1px solid var(--footer-border); transition: border-color 0.3s ease; }

/* Footer Wrapper */
.footer-premier {
    background: var(--footer-bg);
    border-top: 3px solid var(--hnb-orange);
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s ease;
}

/* Logo Text Animation */
.footer-logo-text {
    display: inline-block;
    transition: transform 0.3s ease, text-shadow 0.3s ease;
}
.footer-logo-text:hover {
    transform: translateY(-3px) scale(1.02);
    text-shadow: 0 8px 20px rgba(255, 122, 0, 0.3);
}

/* Background Glow Effect (Only visible in dark mode) */
:root:not(.light-mode) .footer-premier::before {
    content: '';
    position: absolute;
    top: -150px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,122,0,0.05) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

/* Links */
.footer-links li { margin-bottom: 12px; }
.footer-links a {
    color: var(--footer-text-muted);
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}
.footer-links a:hover {
    color: var(--hnb-orange);
    transform: translateX(5px);
}

/* Social Icons */
.social-links {
    display: flex;
    gap: 12px;
}
.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    background: var(--footer-icon-bg);
    border: 1px solid var(--footer-icon-border);
    border-radius: 12px;
    color: var(--footer-text-main);
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s ease;
}
.social-icon:hover {
    background: var(--hnb-orange);
    border-color: var(--hnb-orange);
    color: #fff;
    transform: translateY(-4px);
    box-shadow: 0 8px 15px rgba(255, 122, 0, 0.3);
}

/* Heartbeat animation */
@keyframes heartBeat {
    0% { transform: scale(1); }
    15% { transform: scale(1.3); }
    30% { transform: scale(1); }
    45% { transform: scale(1.3); }
    60% { transform: scale(1); }
}
.heart-beat {
    animation: heartBeat 2s infinite;
}
</style>
