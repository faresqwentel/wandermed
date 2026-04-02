/* File: public/js/wizard-faskes.js */

document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 5; // Sekarang ada 5 tahap form, tahap 6 adalah layar sukses

    // Judul 5 Tahap + 1 Sukses
    const judulSteps = [
        "Profil Fasilitas Kesehatan",
        "Lokasi Faskes",
        "Layanan & Kontak Darurat",
        "Dokumentasi & Info PIC",
        "Pembuatan Akun Faskes",
        "Faskes Terdaftar!",
    ];

    const btnNext = document.getElementById("btnNext");
    const btnPrev = document.getElementById("btnPrev");
    const formTitle = document.getElementById("formTitle");
    const formSubtitle = document.getElementById("formSubtitle");
    const formProgress = document.getElementById("formProgress");
    const navButtons = document.getElementById("navButtons");
    const navFinish = document.getElementById("navFinish");

    // === LOGIKA WIZARD MULTI-STEP ===
    if (btnNext && btnPrev) {
        function updateForm() {
            // Sembunyikan semua step
            document
                .querySelectorAll(".step-content")
                .forEach((el) => el.classList.add("d-none"));
            // Tampilkan step aktif
            document
                .getElementById("step" + currentStep)
                .classList.remove("d-none");
            // Update Teks Judul
            formTitle.innerText = judulSteps[currentStep - 1];

            if (currentStep <= totalSteps) {
                formSubtitle.innerText =
                    "Langkah " + currentStep + " dari " + totalSteps;
                formProgress.style.width =
                    (currentStep / totalSteps) * 100 + "%";
                btnNext.innerText =
                    currentStep === totalSteps
                        ? "Kirim Data & Buat Akun"
                        : "Lanjut";
            } else {
                // Layar Sukses (Tahap 6)
                formSubtitle.innerText = "Selesai";
                formProgress.style.width = "100%";
                formProgress.classList.replace("bg-hnb-orange", "bg-success");
                navButtons.classList.add("d-none");
                navButtons.classList.remove("d-flex");
                navFinish.classList.remove("d-none");
                navFinish.classList.add("d-flex");
            }
        }

        btnNext.addEventListener("click", function () {
            if (currentStep <= totalSteps) {
                currentStep++;
                updateForm();
            }
        });

        btnPrev.addEventListener("click", function () {
            if (currentStep > 1) {
                currentStep--;
                updateForm();
            } else {
                window.location.href = "/daftar";
            }
        });
    }

    // === LOGIKA DINAMIS UGD (Tampil/Sembunyi Jam UGD) ===
    const selectUGD = document.getElementById("selectUGD");
    const ugdTimeForm = document.getElementById("ugdTimeForm");

    if (selectUGD && ugdTimeForm) {
        selectUGD.addEventListener("change", function () {
            if (this.value === "Terbatas") {
                ugdTimeForm.classList.remove("d-none"); // Munculkan form jam
            } else {
                ugdTimeForm.classList.add("d-none"); // Sembunyikan form jam
            }
        });
    }

    // === LOGIKA SHOW/HIDE PASSWORD DI TAHAP 5 ===
    const btnTogglePass = document.getElementById("btnTogglePass");
    const inputPass = document.getElementById("inputPass");

    if (btnTogglePass && inputPass) {
        btnTogglePass.addEventListener("click", function () {
            if (inputPass.type === "password") {
                inputPass.type = "text";
                btnTogglePass.classList.remove("fa-eye");
                btnTogglePass.classList.add("fa-eye-slash");
            } else {
                inputPass.type = "password";
                btnTogglePass.classList.remove("fa-eye-slash");
                btnTogglePass.classList.add("fa-eye");
            }
        });
    }
});
