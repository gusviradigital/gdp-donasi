/**
 * Front page scripts
 */

(function($) {
    'use strict';

    /**
     * Initialize testimonials slider
     */
    function initTestimonialsSlider() {
        const testimonialsSlider = new Swiper('.gdp-testimonials__items', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
            autoplay: {
                delay: 5000,
            },
        });
    }

    /**
     * Initialize partners slider
     */
    function initPartnersSlider() {
        const partnersSlider = new Swiper('.gdp-partners__items', {
            slidesPerView: 2,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3000,
            },
            breakpoints: {
                640: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 4,
                },
                1024: {
                    slidesPerView: 5,
                },
            },
        });
    }

    /**
     * Initialize featured programs slider
     */
    function initFeaturedSlider() {
        if ($('.gdp-featured--slider').length) {
            const featuredSlider = new Swiper('.gdp-featured__items', {
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        }
    }

    /**
     * Initialize masonry layout
     */
    function initMasonry() {
        if ($('.gdp-featured--masonry').length) {
            $('.gdp-featured__items').masonry({
                itemSelector: '.gdp-featured__item',
                columnWidth: '.gdp-featured__item',
                percentPosition: true
            });
        }
    }

    /**
     * Document ready
     */
    $(document).ready(function() {
        initTestimonialsSlider();
        initPartnersSlider();
        initFeaturedSlider();
        initMasonry();
    });

})(jQuery); 