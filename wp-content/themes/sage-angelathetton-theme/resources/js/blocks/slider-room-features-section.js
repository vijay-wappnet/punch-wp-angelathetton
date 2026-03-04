import jQuery from 'jquery';
import leftArrow from '../../images/left_arrow.svg';
import rightArrow from '../../images/right-arrow.svg';

import '@accessible360/accessible-slick';

export default function SliderRoomFeaturesSection() {
    const sliders = document.querySelectorAll('.slider-room-fs__slider-wrapper');

    if (sliders.length === 0) {
        return;
    }

    sliders.forEach((slider) => {
        if (slider.classList.contains('slick-initialized')) {
            return;
        }

        try {
            jQuery(slider).slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                adaptiveHeight: true,
                infinite: true,
                arrows: true,
                prevArrow: `<div class="slick-prev"><img src="${leftArrow}" alt="Previous slide"></div>`,
                nextArrow: `<div class="slick-next"><img src="${rightArrow}" alt="Next slide"></div>`,
                dots: true,
                autoplay: false,
                swipe: true,
                touch: true,
                draggable: true,
                accessibility: true,
            });
        } catch (error) {
            console.error('Error initializing slider:', error);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    SliderRoomFeaturesSection();
});

if (window.acf) {
    document.addEventListener('acf/unmount', () => {
        const sliders = document.querySelectorAll('.slider-room-fs__slider-wrapper.slick-initialized');
        sliders.forEach((slider) => {
            try {
                jQuery(slider).slick('unslick');
            } catch (error) {
                // Silently fail if slick isn't initialized
            }
        });
    });

    document.addEventListener('acf/mount', () => {
        SliderRoomFeaturesSection();
    });
}