import jQuery from 'jquery';
import leftArrow from '../../images/left_arrow.svg';
import rightArrow from '../../images/right-arrow.svg';

import '@accessible360/accessible-slick';

// Polyfill for jQuery.type() removed in jQuery 4.0
if (typeof jQuery.type === 'undefined') {
  jQuery.type = function(obj) {
    if (obj == null) {
      return obj + '';
    }
    const class2type = {};
    'Boolean Number String Function Array Date RegExp Object Error Symbol'.split(' ').forEach(name => {
      class2type['[object ' + name + ']'] = name.toLowerCase();
    });
    return typeof obj === 'object' || typeof obj === 'function' ?
      class2type[Object.prototype.toString.call(obj)] || 'object' :
      typeof obj;
  };
}

const MOBILE_BREAKPOINT = 768;

/**
 * Initialize Two Columns Image CTA With Mobile Slider Section block
 * Slider ONLY activates on mobile screens (<768px)
 */
export default function TwoColumnsImageCtaWithMobileSliderSection() {
  const sections = document.querySelectorAll('.two-columns-image-cta-with-mobile-slider-section');

  if (sections.length === 0) {
    return;
  }

  sections.forEach((section) => {
    const slider = section.querySelector('.tcicwmss__wrapper');

    if (!slider) {
      return;
    }

    /**
     * Initialize the slider with slide counter
     */
    function initSlider() {
      // Prevent double initialization
      if (slider.classList.contains('slick-initialized')) {
        return;
      }

      try {
        jQuery(slider).slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: false,
          arrows: true,
          prevArrow: `<button type="button" class="slick-prev" aria-label="Previous slide"><img src="${leftArrow}" alt="Previous"></button>`,
          nextArrow: `<button type="button" class="slick-next" aria-label="Next slide"><img src="${rightArrow}" alt="Next"></button>`,
          dots: false,
          infinite: true,
          speed: 500,
          autoplay: false,
          swipe: true,
          touch: true,
          draggable: true,
          accessibility: true,
          focusOnSelect: true,
        });

        // Create slide counter element
        const slideCount = slider.querySelectorAll('.slick-slide:not(.slick-cloned)').length;
        let counter = section.querySelector('.tcicwmss__slide-counter');

        if (!counter) {
          counter = document.createElement('div');
          counter.className = 'tcicwmss__slide-counter';
          slider.parentNode.insertBefore(counter, slider.nextSibling);
        }

        // Update counter on init
        updateCounter(1, slideCount, counter, slider);

        // Update counter on slide change
        jQuery(slider).on('afterChange', function(event, slick, currentSlide) {
          updateCounter(currentSlide + 1, slideCount, counter, slider);
        });

      } catch (error) {
        console.error('Error initializing mobile slider:', error);
      }
    }

    /**
     * Update the slide counter display with arrow images
     */
    function updateCounter(current, total, counterElement, sliderElement) {
      if (counterElement) {
        counterElement.innerHTML = `
          <span class="tcicwmss__counter-arrows">
            <span class="tcicwmss__counter-arrow tcicwmss__counter-arrow--left">
              <img src="${leftArrow}" alt="Previous slide">
            </span>
            <span class="tcicwmss__counter-current">${current}</span> / <span class="tcicwmss__counter-total">${total}</span>
            <span class="tcicwmss__counter-arrow tcicwmss__counter-arrow--right">
              <img src="${rightArrow}" alt="Next slide">
            </span>
          </span>
        `;

        // Add click events to counter arrows
        const leftBtn = counterElement.querySelector('.tcicwmss__counter-arrow--left');
        const rightBtn = counterElement.querySelector('.tcicwmss__counter-arrow--right');

        if (leftBtn) {
          leftBtn.onclick = () => jQuery(sliderElement).slick('slickPrev');
        }
        if (rightBtn) {
          rightBtn.onclick = () => jQuery(sliderElement).slick('slickNext');
        }
      }
    }

    /**
     * Destroy the slider
     */
    function destroySlider() {
      if (slider.classList.contains('slick-initialized')) {
        try {
          jQuery(slider).slick('unslick');

          // Remove counter element
          const counter = section.querySelector('.tcicwmss__slide-counter');
          if (counter) {
            counter.remove();
          }
        } catch (error) {
          // Silently fail if slick isn't initialized
        }
      }
    }

    /**
     * Handle responsive behavior
     */
    function handleResponsive() {
      const isMobile = window.innerWidth < MOBILE_BREAKPOINT;

      if (isMobile) {
        initSlider();
      } else {
        destroySlider();
      }
    }

    // Initial check
    handleResponsive();

    // Handle resize with debounce
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(handleResponsive, 150);
    });
  });
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  TwoColumnsImageCtaWithMobileSliderSection();
});

// Re-initialize on Gutenberg block updates (for preview)
if (window.acf) {
  document.addEventListener('acf/unmount', () => {
    // Destroy existing slick instances before re-initialization
    const sliders = document.querySelectorAll('.two-columns-image-cta-with-mobile-slider-section .tcicwmss__wrapper.slick-initialized');
    sliders.forEach((slider) => {
      try {
        jQuery(slider).slick('unslick');
      } catch (error) {
        // Silently fail if slick isn't initialized
      }
    });
  });

  document.addEventListener('acf/mount', () => {
    TwoColumnsImageCtaWithMobileSliderSection();
  });
}
