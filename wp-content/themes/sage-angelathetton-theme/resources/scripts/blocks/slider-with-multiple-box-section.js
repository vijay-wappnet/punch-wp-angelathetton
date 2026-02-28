import Slick from 'accessible-slick';

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

    // Initialize slider with accessible-slick
    try {
      $(slider).slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        centerMode: true,
        centerPadding: '0px',
        arrows: true,
        dots: false,
        infinite: true,
        speed: 500,
        autoplay: false,
        swipe: true,
        touch: true,
        draggable: true,
        accessibility: true,
        focusOnSelect: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
              centerMode: true,
              centerPadding: '0px',
              infinite: true,
              arrows: true,
              dots: false,
            },
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              centerMode: true,
              centerPadding: '0px',
              infinite: true,
              arrows: true,
              dots: false,
            },
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              centerMode: true,
              centerPadding: '0px',
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
        $(slider).slick('unslick');
      } catch (error) {
        // Silently fail if slick isn't initialized
      }
    });
  });

  document.addEventListener('acf/mount', () => {
    SliderWithMultipleBoxSection();
  });
}
