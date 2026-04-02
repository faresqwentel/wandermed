/* ========================================================= */
/* FILE: public/js/script-wisatawan.js                       */
/* PROJECT: WanderMed - Hear & Build Studio                  */
/* DESCRIPTION: Main Logic for Navigation, Theme, & UI       */
/* ========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    // --- 1. INISIALISASI VARIABEL GLOBAL ---
    const body = document.body;
    const html = document.documentElement;
    const navbar = document.getElementById("mainNavbar");
    const navLinks = document.querySelectorAll(".scroll-link");
    const sections = document.querySelectorAll(".section-scroll, #page-top");

    // Fungsi dinamis untuk mendapatkan tinggi navbar (Offset Scroll)
    const getNavbarHeight = () => (navbar ? navbar.offsetHeight : 90);

    // --- 2. MANAJEMEN TEMA (DARK/LIGHT MODE) ---
    const themeToggleBtn = document.getElementById("themeToggle");
    const themeIcon = document.getElementById("themeIcon");

    /**
     * Fungsi untuk memperbarui UI Ikon Tema
     * Latar belakang sudah ditangani oleh Blocking Script di Layout
     */
    const updateThemeUI = () => {
        if (body.classList.contains("light-mode")) {
            themeIcon?.classList.replace("fa-sun", "fa-moon");
            themeIcon?.classList.add("text-hnb-navy");
        } else {
            themeIcon?.classList.replace("fa-moon", "fa-sun");
            themeIcon?.classList.remove("text-hnb-navy");
        }
    };

    // Jalankan sinkronisasi ikon saat halaman dimuat
    updateThemeUI();

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function (e) {
            e.preventDefault();

            // Toggle class pada body dan html
            body.classList.toggle("light-mode");
            html.classList.toggle("light-mode");

            // Simpan preferensi ke LocalStorage
            if (body.classList.contains("light-mode")) {
                localStorage.setItem("wanderMedTheme", "light");
            } else {
                localStorage.setItem("wanderMedTheme", "dark");
            }

            // Perbarui ikon
            updateThemeUI();
        });
    }

    // --- 3. NAVIGASI SMOOTH SCROLL ---
    navLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");

            // Pastikan hanya memproses link yang mengandung anchor (#)
            if (href.includes("#")) {
                const targetId = href.substring(href.indexOf("#"));
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    e.preventDefault();

                    // Hitung posisi target dikurangi offset navbar
                    const targetPosition =
                        targetElement.offsetTop - getNavbarHeight();

                    window.scrollTo({
                        top: targetPosition,
                        behavior: "smooth",
                    });

                    // Tutup otomatis menu mobile (Bootstrap Collapse) jika terbuka
                    const navbarCollapse = document.getElementById("navbarNav");
                    if (
                        navbarCollapse &&
                        navbarCollapse.classList.contains("show")
                    ) {
                        $(navbarCollapse).collapse("hide");
                    }
                }
            }
        });
    });

    // --- 4. SCROLL SPY (HIGHLIGHT MENU AKTIF) ---
    const handleScrollSpy = () => {
        let currentSectionId = "";
        const scrollPosition =
            window.pageYOffset || document.documentElement.scrollTop;

        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            if (scrollPosition >= sectionTop - getOffset() - 100) {
                currentSectionId = section.getAttribute("id");
            }
        });

        navLinks.forEach((link) => {
            link.classList.remove("active");
            const href = link.getAttribute("href");

            // Perbaikan: Hanya aktifkan jika currentSectionId TIDAK kosong
            if (
                currentSectionId !== "" &&
                href.includes(`#${currentSectionId}`)
            ) {
                link.classList.add("active");
            }
        });

        // Efek Navbar Mengecil saat Scroll
        if (navbar) {
            if (scrollPosition > 50) {
                navbar.style.padding = "10px 0";
                navbar.classList.add("shadow-lg");
            } else {
                navbar.style.padding = "15px 0";
                navbar.classList.remove("shadow-lg");
            }
        }
    };

    window.addEventListener("scroll", handleScrollSpy);
    // Jalankan sekali saat load untuk deteksi posisi awal
    handleScrollSpy();

    // --- 5. FITUR SHOW/HIDE PASSWORD (LOGIN PAGE) ---
    const btnToggle = document.getElementById("btnToggle");
    const inputPassword = document.getElementById("inputPassword");
    const ikonMata = document.getElementById("ikonMata");

    if (btnToggle && inputPassword && ikonMata) {
        btnToggle.addEventListener("click", function () {
            if (inputPassword.type === "password") {
                inputPassword.type = "text";
                ikonMata.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                inputPassword.type = "password";
                ikonMata.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    }
});
