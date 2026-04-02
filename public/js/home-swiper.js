/* File: public/js/home-swiper.js */

document.addEventListener("DOMContentLoaded", function () {
    const swiper = document.getElementById("aboutSwiper");
    const dots = document.querySelectorAll(".pagination-dot");

    if (swiper && dots.length > 0) {
        // 1. Sinkronisasi Geser ke Titik (Dots)
        swiper.addEventListener("scroll", () => {
            const slideWidth = swiper.offsetWidth;
            const currentSlide = Math.round(swiper.scrollLeft / slideWidth);

            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add("active");
                } else {
                    dot.classList.remove("active");
                }
            });
        });
    }
});

// 2. Fungsi saat titik di-klik akan menggeser kotak
// Dideklarasikan menggunakan 'window' agar dapat diakses oleh atribut onclick di HTML
window.scrollToSlide = function (index) {
    const swiper = document.getElementById("aboutSwiper");
    if (swiper) {
        const slideWidth = swiper.offsetWidth;
        swiper.scrollTo({
            left: slideWidth * index,
            behavior: "smooth",
        });
    }
};
