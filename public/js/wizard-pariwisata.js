/* File: public/js/wizard-pariwisata.js */

document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 4; // Tahap 5 adalah layar sukses

    const judulSteps = [
        "Profil Singkat Pariwisata",
        "Lokasi Pariwisata",
        "Kontak & Operasional",
        "Dokumentasi Pariwisata",
        "Pendaftaran Berhasil!",
    ];

    const btnNext = document.getElementById("btnNext");
    const btnPrev = document.getElementById("btnPrev");
    const formTitle = document.getElementById("formTitle");
    const formSubtitle = document.getElementById("formSubtitle");
    const formProgress = document.getElementById("formProgress");
    const navButtons = document.getElementById("navButtons");
    const navFinish = document.getElementById("navFinish");

    // Pastikan script ini hanya berjalan jika elemen-elemen di atas ada di halaman
    if (btnNext && btnPrev) {
        // Fungsi untuk Memperbarui Tampilan Form
        function updateForm() {
            // Sembunyikan semua step
            document
                .querySelectorAll(".step-content")
                .forEach((el) => el.classList.add("d-none"));
            // Tampilkan step yang aktif
            document
                .getElementById("step" + currentStep)
                .classList.remove("d-none");

            // Update Teks Judul
            formTitle.innerText = judulSteps[currentStep - 1];

            if (currentStep <= totalSteps) {
                // Masih di tahap isi form
                formSubtitle.innerText =
                    "Langkah " + currentStep + " dari " + totalSteps;
                formProgress.style.width =
                    (currentStep / totalSteps) * 100 + "%";

                // Ganti teks tombol jika di langkah terakhir
                if (currentStep === totalSteps) {
                    btnNext.innerText = "Kirim Data";
                } else {
                    btnNext.innerText = "Lanjut";
                }
            } else {
                // Tahap 5 (Sukses)
                formSubtitle.innerText = "Selesai";
                formProgress.style.width = "100%";
                formProgress.classList.replace("bg-hnb-orange", "bg-success");

                // Sembunyikan tombol prev/next, tampilkan tombol beranda
                navButtons.classList.add("d-none");
                navButtons.classList.remove("d-flex");
                navFinish.classList.remove("d-none");
                navFinish.classList.add("d-flex");
            }
        }

        // Aksi saat klik tombol Lanjut
        btnNext.addEventListener("click", function () {
            if (currentStep <= totalSteps) {
                currentStep++;
                updateForm();
            }
        });

        // Aksi saat klik tombol Kembali
        btnPrev.addEventListener("click", function () {
            if (currentStep > 1) {
                currentStep--;
                updateForm();
            } else {
                // Jika di tahap 1, kembali ke halaman pilihan peran
                window.location.href = "/daftar";
            }
        });
    }
});
