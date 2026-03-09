/**
 * FAQs Accordion Section
 * Handles accordion toggle functionality
 */

(function() {
  'use strict';

  // Initialize all FAQ accordion sections
  function initFaqsAccordion() {
    const sections = document.querySelectorAll('.faqs-accordion-section');

    sections.forEach(section => {
      initSection(section);
    });
  }

  // Initialize a single section
  function initSection(section) {
    const faqItems = section.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
      const questionBtn = item.querySelector('.faq-question');

      if (questionBtn) {
        questionBtn.addEventListener('click', function(e) {
          e.preventDefault();
          toggleFaqItem(item, section);
        });

        // Keyboard accessibility
        questionBtn.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleFaqItem(item, section);
          }
        });
      }
    });
  }

  // Toggle FAQ item
  function toggleFaqItem(item, section) {
    const isActive = item.classList.contains('is-active');
    const questionBtn = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');

    // Optional: Close other items (accordion behavior)
    // Uncomment the following lines if you want only one item open at a time
    // const allItems = section.querySelectorAll('.faq-item');
    // allItems.forEach(otherItem => {
    //   if (otherItem !== item) {
    //     otherItem.classList.remove('is-active');
    //     const otherBtn = otherItem.querySelector('.faq-question');
    //     const otherAnswer = otherItem.querySelector('.faq-answer');
    //     if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
    //     if (otherAnswer) otherAnswer.setAttribute('aria-hidden', 'true');
    //   }
    // });

    // Toggle current item
    if (isActive) {
      item.classList.remove('is-active');
      if (questionBtn) questionBtn.setAttribute('aria-expanded', 'false');
      if (answer) answer.setAttribute('aria-hidden', 'true');
    } else {
      item.classList.add('is-active');
      if (questionBtn) questionBtn.setAttribute('aria-expanded', 'true');
      if (answer) answer.setAttribute('aria-hidden', 'false');
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFaqsAccordion);
  } else {
    initFaqsAccordion();
  }

  // Re-initialize for Gutenberg editor preview
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=faqs-accordion-section', function() {
      setTimeout(initFaqsAccordion, 100);
    });
  }
})();
