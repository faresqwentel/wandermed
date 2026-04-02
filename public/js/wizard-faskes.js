/* File: public/js/wizard-faskes.js */

document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 5;

    const judulSteps = [
        "Profil Fasilitas Kesehatan",
        "Lokasi Strategis Faskes",
        "Layanan & Kontak Darurat",
        "Dokumentasi & Info Admin",
        "Keamanan Akses Dashboard",
        "Faskes Terdaftar!",
    ];

    const btnNext = document.getElementById("btnNext");
    const btnPrev = document.getElementById("btnPrev");
    const formTitle = document.getElementById("formTitle");
    const formSubtitle = document.getElementById("formSubtitle");
    const formProgress = document.getElementById("formProgress");
    const navButtons = document.getElementById("navButtons");
    const navFinish = document.getElementById("navFinish");

    if (btnNext && btnPrev) {
        function updateForm() {
            // Sembunyikan step dengan efek transisi
            document.querySelectorAll(".step-content").forEach((el) => {
                el.classList.add("d-none");
                el.style.opacity = "0";
            });

            // Tampilkan step aktif
            const activeStep = document.getElementById("step" + currentStep);
            if (activeStep) {
                activeStep.classList.remove("d-none");
                setTimeout(() => {
                    activeStep.style.opacity = "1";
                }, 50);
            }

            formTitle.innerText = judulSteps[currentStep - 1];

            if (currentStep <= totalSteps) {
                formSubtitle.innerText =
                    "Langkah " + currentStep + " dari " + totalSteps;
                formProgress.style.width =
                    (currentStep / totalSteps) * 100 + "%";

                // PERBAIKAN: Gunakan .innerHTML agar ikon tidak hilang
                if (currentStep === totalSteps) {
                    btnNext.innerHTML =
                        'Kirim & Buat Akun <i class="fas fa-user-plus ml-2"></i>';
                } else {
                    btnNext.innerHTML =
                        'Lanjut <i class="fas fa-chevron-right ml-2"></i>';
                }
            } else {
                // Layar Sukses
                formSubtitle.innerText = "Selesai";
                formProgress.style.width = "100%";
                formProgress.classList.replace("bg-hnb-orange", "bg-success");
                navButtons.classList.replace("d-flex", "d-none");
                navFinish.classList.replace("d-none", "d-flex");
            }
        }

        btnNext.addEventListener("click", function () {
            if (currentStep <= totalSteps) {
                currentStep++;
                updateForm();
                window.scrollTo({ top: 0, behavior: "smooth" });
            }
        });

        btnPrev.addEventListener("click", function () {
            if (currentStep > 1) {
                currentStep--;
                updateForm();
                window.scrollTo({ top: 0, behavior: "smooth" });
            } else {
                window.location.href = "/daftar";
            }
        });

        // Inisialisasi tampilan
        updateForm();
    }

    // --- LOGIKA DINAMIS UGD ---
    const selectUGD = document.getElementById("selectUGD");
    const ugdTimeForm = document.getElementById("ugdTimeForm");

    if (selectUGD && ugdTimeForm) {
        selectUGD.addEventListener("change", function () {
            if (this.value === "Terbatas") {
                ugdTimeForm.classList.remove("d-none");
            } else {
                ugdTimeForm.classList.add("d-none");
            }
        });
    }

    // --- LOGIKA TOGGLE PASSWORD ---
    const btnTogglePass = document.getElementById("btnTogglePass");
    const inputPass = document.getElementById("inputPass");

    if (btnTogglePass && inputPass) {
        btnTogglePass.addEventListener("click", function () {
            if (inputPass.type === "password") {
                inputPass.type = "text";
                this.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                inputPass.type = "password";
                this.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    }
});
