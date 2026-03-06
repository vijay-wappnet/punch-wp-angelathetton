/**
 * Video Banner Section - Smooth Scroll Arrow
 *
 * Add this to your theme's main JavaScript file or include it separately.
 * This enables smooth scrolling to bottom of page when arrow is clicked.
 */

document.addEventListener('DOMContentLoaded', function () {
  const arrows = document.querySelectorAll('.video-banner-section__arrow');

  arrows.forEach((arrow) => {
    arrow.addEventListener('click', handleArrowClick);
    arrow.addEventListener('keydown', handleArrowKeypress);
  });

  function handleArrowClick(e) {
    e.preventDefault();
    scrollToNextSection(e.currentTarget);
  }

  function handleArrowKeypress(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      scrollToNextSection(e.currentTarget);
    }
  }

  function scrollToNextSection(arrowElement) {
    const section = arrowElement.closest('.video-banner-section');
    if (section) {
      // Find next sibling that is an actual section (skip style tags, scripts, etc.)
      let nextElement = section.nextElementSibling;
      while (nextElement && (nextElement.tagName === 'STYLE' || nextElement.tagName === 'SCRIPT')) {
        nextElement = nextElement.nextElementSibling;
      }
      
      if (nextElement) {
        nextElement.scrollIntoView({ behavior: 'smooth' });
      } else {
        // Scroll to bottom of page if no next element
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
      }
    }
  }
});
