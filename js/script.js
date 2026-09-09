document.addEventListener('DOMContentLoaded', function () {
    // 1. تشغيل السلايدر
    if (document.querySelector('.header-slider')) {
        new Swiper('.header-slider', {
            slidesPerView: 1,
            spaceBetween: 12,
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            pagination: { el: '.swiper-pagination', clickable: true },
            breakpoints: {
                640: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 4 }
            }
        });
    }

    // 2. جعل أزرار القوائم المنسدلة تفتح بنقرة سلسة
    const triggers = document.querySelectorAll('.btn-action-trigger');
    triggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const parent = this.closest('.dropdown-wrapper');
            document.querySelectorAll('.dropdown-wrapper').forEach(d => {
                if (d !== parent) d.classList.remove('active');
            });
            parent.classList.toggle('active');
        });
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-wrapper').forEach(d => d.classList.remove('active'));
    });
});
