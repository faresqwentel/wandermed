/* File: public/js/wizard-pariwisata.js */

document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 4; // Profil, Lokasi, Operasional, Data Pengelola

    const judulSteps = [
        "Profil Destinasi Wisata",
        "Lokasi & Koordinat",
        "Operasional & Dokumentasi",
        "Data Pengelola & Kontak",
        "Pendaftaran Terkirim!",
    ];

    const btnNext = document.getElementById("btnNext");
    const btnPrev = document.getElementById("btnPrev");
    const formTitle = document.getElementById("formTitle");
    const formSubtitle = document.getElementById("formSubtitle");
    const formProgress = document.getElementById("formProgress");
    const navButtons = document.getElementById("navButtons");
    const navFinish = document.getElementById("navFinish");

    if (!btnNext || !btnPrev) return;

    function updateForm() {
        document.querySelectorAll(".step-content").forEach((el) => {
            el.classList.add("d-none");
            el.style.opacity = "0";
        });

        const activeStep = document.getElementById("step" + currentStep);
        if (activeStep) {
            activeStep.classList.remove("d-none");
            setTimeout(() => { activeStep.style.opacity = "1"; }, 50);
        }

        formTitle.innerText = judulSteps[currentStep - 1];

        if (currentStep <= totalSteps) {
            formSubtitle.innerText = "Langkah " + currentStep + " dari " + totalSteps;
            formProgress.style.width = (currentStep / totalSteps) * 100 + "%";

            btnNext.innerHTML = currentStep === totalSteps
                ? 'Kirim Pendaftaran <i class="fas fa-paper-plane ml-2"></i>'
                : 'Lanjut <i class="fas fa-chevron-right ml-2"></i>';

            navButtons.classList.add("d-flex");
            navButtons.classList.remove("d-none");
            navFinish.classList.add("d-none");
            navFinish.classList.remove("d-flex");
        } else {
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
            // Pada step terakhir: validasi field wajib lalu submit form
            if (currentStep === totalSteps) {
                const form = document.getElementById("wizardForm");
                const emailInput = form.querySelector('[name="email_kontak"]');
                const penggelolaInput = form.querySelector('[name="nama_pengelola"]');

                if (penggelolaInput && !penggelolaInput.value.trim()) {
                    penggelolaInput.focus();
                    penggelolaInput.reportValidity();
                    return;
                }
                if (emailInput && !emailInput.value.trim()) {
                    emailInput.focus();
                    emailInput.reportValidity();
                    return;
                }

                btnNext.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
                btnNext.disabled = true;
                form.submit();
                return;
            }

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

    updateForm();
});
