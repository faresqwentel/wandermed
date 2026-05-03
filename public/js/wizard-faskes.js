/* File: public/js/wizard-faskes.js */

document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 5; // Profil, Lokasi, Kontak, Dokumen, Akun

    const judulSteps = [
        "Profil Fasilitas Kesehatan",
        "Lokasi & Koordinat di Peta",
        "Layanan & Kontak Darurat",
        "Dokumentasi & Penanggung Jawab",
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
            // Sembunyikan semua step
            document.querySelectorAll(".step-content").forEach((el) => {
                el.classList.add("d-none");
                el.style.opacity = "0";
            });

            // Tampilkan step aktif dengan animasi fade
            const activeStep = document.getElementById("step" + currentStep);
            if (activeStep) {
                activeStep.classList.remove("d-none");
                setTimeout(() => { activeStep.style.opacity = "1"; }, 50);
            }

            formTitle.innerText = judulSteps[currentStep - 1];

            if (currentStep <= totalSteps) {
                formSubtitle.innerText = "Langkah " + currentStep + " dari " + totalSteps;
                formProgress.style.width = (currentStep / totalSteps) * 100 + "%";

                // Label tombol berubah di step terakhir
                if (currentStep === totalSteps) {
                    btnNext.innerHTML = 'Kirim & Buat Akun <i class="fas fa-user-plus ml-2"></i>';
                } else {
                    btnNext.innerHTML = 'Lanjut <i class="fas fa-chevron-right ml-2"></i>';
                }

                navButtons.classList.add("d-flex");
                navButtons.classList.remove("d-none");
                navFinish.classList.add("d-none");
                navFinish.classList.remove("d-flex");
            } else {
                // Layar sukses setelah submit berhasil (redirect dari server)
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
                const form = document.getElementById("wizardForm");
                
                // --- VALIDASI PER STEP ---
                if (currentStep < totalSteps) {
                    const activeStep = document.getElementById("step" + currentStep);
                    if (activeStep) {
                        const stepInputs = activeStep.querySelectorAll('input, select, textarea');
                        let stepValid = true;
                        for (let i = 0; i < stepInputs.length; i++) {
                            if (!stepInputs[i].checkValidity()) {
                                stepInputs[i].reportValidity();
                                stepValid = false;
                                break;
                            }
                        }
                        if (!stepValid) return; // Hentikan jika ada form yang tidak valid di step ini
                    }
                    
                    // Khusus Step 2 (Lokasi): Validasi koordinat peta
                    if (currentStep === 2) {
                        const lat = form.querySelector('[name="latitude"]').value;
                        const lng = form.querySelector('[name="longitude"]').value;
                        if (!lat.trim() || !lng.trim()) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Lokasi Belum Dipilih',
                                text: "Koordinat Peta (Latitude & Longitude) belum diisi! Silakan klik 'Pilih Peta' dan tentukan lokasi Anda.",
                                confirmButtonColor: '#ff6b35'
                            });
                            return;
                        }
                    }
                }
                
                // --- STEP TERAKHIR (SUBMIT) ---
                if (currentStep === totalSteps) {
                    // Cek keseluruhan form (sebagai pengaman tambahan)
                    if (form && !form.checkValidity()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Tidak Lengkap',
                            text: 'Ada data wajib yang belum diisi. Silakan periksa kembali setiap langkah pendaftaran Anda.',
                            confirmButtonColor: '#ff6b35'
                        });
                        return;
                    }
                    
                    // Validasi ketat pengaman
                    const alamat = form.querySelector('[name="alamat"]').value;
                    const lat = form.querySelector('[name="latitude"]').value;
                    const lng = form.querySelector('[name="longitude"]').value;
                    
                    if (!alamat.trim() || !lat.trim() || !lng.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lokasi Wajib Diisi',
                            text: 'Alamat Lengkap dan Koordinat (Latitude/Longitude) WAJIB diisi! Silakan kembali ke Langkah 2.',
                            confirmButtonColor: '#ff6b35'
                        });
                        return;
                    }
                    
                    // Peringatan jika ada field opsional yang kosong
                    let hasEmpty = false;
                    const inputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');
                    inputs.forEach(input => {
                        if (!input.value.trim() && !input.hasAttribute('required')) {
                            hasEmpty = true;
                        }
                    });

                    if (hasEmpty) {
                        Swal.fire({
                            title: 'Data Belum Lengkap',
                            text: "Beberapa data opsional masih kosong. Apakah Anda yakin ingin mengirimkan form pendaftaran?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#ff6b35',
                            cancelButtonColor: '#a0aec0',
                            confirmButtonText: 'Ya, Kirim',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                btnNext.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
                                btnNext.disabled = true;
                                if (form) form.submit();
                            }
                        });
                        return; // Hentikan alur synchronous
                    }

                    btnNext.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
                    btnNext.disabled = true;
                    if (form) form.submit();
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

        // Inisialisasi tampilan awal
        updateForm();
    }

    // Toggle Password Visibility
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
