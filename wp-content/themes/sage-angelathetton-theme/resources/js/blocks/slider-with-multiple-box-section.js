import jQuery from 'jquery';

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

/**
 * Initialize Slider With Multiple Box Section block
 */
export default function SliderWithMultipleBoxSection() {
  // Find all slider instances in the page
  const sliders = document.querySelectorAll('.slider-with-multiple-box-section .smb-item__slider');

  if (sliders.length === 0) {
    return;
  }

  sliders.forEach((slider) => {
    // Check if slider is already initialized
    if (slider.classList.contains('slick-initialized')) {
      return;
    }

    // Get arrow images from data attributes
    const section = slider.closest('.slider-with-multiple-box-section');
    const leftArrow = section?.dataset.leftArrow || '';
    const rightArrow = section?.dataset.rightArrow || '';

    // Initialize slider with accessible-slick
    try {
      jQuery(slider).slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        centerMode: true,
        centerPadding: '12.5%',
        arrows: true,
        prevArrow: `<button type="button" class="slick-prev" aria-label="Previous slide"><img src="${leftArrow}" alt=""></button>`,
        nextArrow: `<button type="button" class="slick-next" aria-label="Next slide"><img src="${rightArrow}" alt=""></button>`,
        dots: false,
        infinite: true,
        speed: 500,
        autoplay: false,
        swipe: true,
        touch: true,
        draggable: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
              centerMode: true,
              centerPadding: '12.5%',
              infinite: true,
              arrows: true,
              dots: false,
            },
          },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              centerMode: true,
              centerPadding: '12.5%',
              infinite: true,
              arrows: true,
              dots: false,
            },
          },
        ],
      });
    } catch (error) {
      console.error('Error initializing slider:', error);
    }
  });
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  SliderWithMultipleBoxSection();
});

// Re-initialize on Gutenberg block updates (for preview)
if (window.acf) {
  document.addEventListener('acf/unmount', () => {
    // Destroy existing slick instances before re-initialization
    const sliders = document.querySelectorAll('.slider-with-multiple-box-section .smb-item__slider.slick-initialized');
    sliders.forEach((slider) => {
      try {
        jQuery(slider).slick('unslick');
      } catch (error) {
        // Silently fail if slick isn't initialized
      }
    });
  });

  document.addEventListener('acf/mount', () => {
    SliderWithMultipleBoxSection();
  });
}
