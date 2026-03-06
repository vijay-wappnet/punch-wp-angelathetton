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
            const sliderContainer = jQuery(slider).closest('.slider-room-fs__slider');

            // Create arrows wrapper
            const arrowsWrapper = jQuery('<div class="slider-room-fs__arrows-wrapper"></div>');
            sliderContainer.append(arrowsWrapper);

            jQuery(slider).slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                adaptiveHeight: false,
                infinite: true,
                arrows: true,
                prevArrow: `<button class="slick-prev" type="button"><img src="${leftArrow}" alt="Previous slide"></button>`,
                nextArrow: `<button class="slick-next" type="button"><img src="${rightArrow}" alt="Next slide"></button>`,
                dots: true,
                autoplay: false,
                swipe: true,
                touch: true,
                draggable: true,
                appendDots: sliderContainer,
                appendArrows: arrowsWrapper,
            });
        } catch (error) {
            console.error('Error initializing slider:', error);
        }
    });

    // Initialize feature dropdown toggles
    initFeatureDropdowns();
}

/**
 * Initialize feature dropdown toggle functionality
 */
function initFeatureDropdowns() {
    const featureTitles = document.querySelectorAll('.slider-room-fs__feature-title');

    featureTitles.forEach((title) => {
        // Skip if already initialized
        if (title.dataset.initialized === 'true') {
            return;
        }

        title.dataset.initialized = 'true';

        // Make it keyboard focusable
        title.setAttribute('tabindex', '0');
        title.setAttribute('role', 'button');
        title.setAttribute('aria-expanded', 'false');

        const toggleFeature = () => {
            const features = title.nextElementSibling;

            if (features && features.classList.contains('slider-room-fs__features')) {
                const isOpen = title.classList.toggle('is-open');
                features.classList.toggle('is-open');
                title.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }
        };

        // Click handler
        title.addEventListener('click', toggleFeature);

        // Keyboard handler (Enter and Space)
        title.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleFeature();
            }
        });
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
