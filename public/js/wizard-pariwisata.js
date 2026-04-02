/* File: public/js/wizard-pariwisata.js */
document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 4;

    const judulSteps = [
        "Profil Singkat Pariwisata",
        "Lokasi Strategis",
        "Operasional Destinasi",
        "Kontak & Dokumentasi",
        "Pendaftaran Berhasil!",
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
            document.querySelectorAll(".step-content").forEach((el) => {
                el.classList.add("d-none");
                el.style.opacity = "0";
            });

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

                if (currentStep === totalSteps) {
                    btnNext.innerHTML =
                        'Kirim Data <i class="fas fa-paper-plane ml-2"></i>';
                } else {
                    btnNext.innerHTML =
                        'Lanjut <i class="fas fa-chevron-right ml-2"></i>';
                }

                navButtons.classList.add("d-flex");
                navButtons.classList.remove("d-none");
                navFinish.classList.add("d-none");
            } else {
                formSubtitle.innerText = "Selesai";
                formProgress.style.width = "100%";
                formProgress.classList.replace("bg-hnb-orange", "bg-success");
                navButtons.classList.add("d-none");
                navFinish.classList.remove("d-none");
                navFinish.classList.add("d-flex");
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

        // Inisialisasi tampilan pertama kali
        updateForm();
    }
});
