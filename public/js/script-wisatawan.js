/* File: public/js/script-wisatawan.js */

document.addEventListener("DOMContentLoaded", function () {
    // 1. FITUR SHOW/HIDE PASSWORD
    const btnToggle = document.getElementById("btnToggle");
    const inputPassword = document.getElementById("inputPassword");
    const ikonMata = document.getElementById("ikonMata");

    if (btnToggle && inputPassword && ikonMata) {
        btnToggle.addEventListener("click", function () {
            if (inputPassword.type === "password") {
                inputPassword.type = "text";
                ikonMata.classList.remove("fa-eye");
                ikonMata.classList.add("fa-eye-slash");
            } else {
                inputPassword.type = "password";
                ikonMata.classList.remove("fa-eye-slash");
                ikonMata.classList.add("fa-eye");
            }
        });
    }

    // 2. FITUR DARK/LIGHT MODE TOGGLE
    const themeToggleBtn = document.getElementById("themeToggle");
    const themeIcon = document.getElementById("themeIcon");
    const body = document.body;

    // Cek apakah sebelumnya user sudah memilih light mode (tersimpan di browser)
    if (localStorage.getItem("wanderMedTheme") === "light") {
        body.classList.add("light-mode");
        if (themeIcon) {
            themeIcon.classList.remove("fa-sun");
            themeIcon.classList.add("fa-moon", "text-hnb-navy"); // Ganti ikon ke bulan
        }
    }

    // Jika tombol toggle ditekan
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            body.classList.toggle("light-mode"); // Nyala/Matikan class light-mode di body

            if (body.classList.contains("light-mode")) {
                // Simpan pilihan mode terang
                localStorage.setItem("wanderMedTheme", "light");
                themeIcon.classList.remove("fa-sun");
                themeIcon.classList.add("fa-moon", "text-hnb-navy");
            } else {
                // Simpan pilihan mode gelap
                localStorage.setItem("wanderMedTheme", "dark");
                themeIcon.classList.remove("fa-moon", "text-hnb-navy");
                themeIcon.classList.add("fa-sun");
            }
        });
    }
});
