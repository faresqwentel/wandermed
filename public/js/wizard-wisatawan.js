document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 3;

    const judulSteps = [
        "Identitas Wisatawan",
        "Informasi Kesehatan",
        "Akses Akun Personal",
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
            document
                .querySelectorAll(".step-content")
                .forEach((el) => el.classList.add("d-none"));
            const activeStep = document.getElementById("step" + currentStep);
            if (activeStep) activeStep.classList.remove("d-none");

            formTitle.innerText = judulSteps[currentStep - 1];

            if (currentStep <= totalSteps) {
                formSubtitle.innerText = `Langkah ${currentStep} dari ${totalSteps}`;
                formProgress.style.width =
                    (currentStep / totalSteps) * 100 + "%";
                btnNext.innerHTML =
                    currentStep === totalSteps
                        ? 'Selesaikan <i class="fas fa-check ml-2"></i>'
                        : 'Lanjut <i class="fas fa-chevron-right ml-2"></i>';
            } else {
                formSubtitle.innerText = "Selesai";
                formProgress.style.width = "100%";
                formProgress.classList.replace("bg-hnb-orange", "bg-success");
                navButtons.classList.replace("d-flex", "d-none");
                navFinish.classList.replace("d-none", "d-flex");
            }
        }

        btnNext.addEventListener("click", () => {
            currentStep++;
            updateForm();
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
        btnPrev.addEventListener("click", () => {
            if (currentStep > 1) {
                currentStep--;
                updateForm();
            } else {
                window.location.href = "/daftar";
            }
        });

        updateForm();
    }

    // Toggle Password
    const btnToggle = document.getElementById("btnTogglePass");
    const inputPass = document.getElementById("inputPass");
    if (btnToggle && inputPass) {
        btnToggle.addEventListener("click", function () {
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
