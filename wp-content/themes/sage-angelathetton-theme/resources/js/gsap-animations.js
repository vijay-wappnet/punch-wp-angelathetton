import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

if (!document.body.classList.contains('animations-enabled')) {
  // Animations disabled via ACF Options — exit silently.
} else {
  document.addEventListener('DOMContentLoaded', () => {
    // Add CSS selectors here to animate matching elements on scroll.
    const animatedElements = [
      '.intro-section__content-wrapper',
      '.two-columns-image-with-cta-section .tciwcs__column',
      '.video-banner-section__heading',
      '.smb-item__wrapper',
      '.tcicwmss__wrapper .tcicwmss__column',
      '.top-bottom-media-content-section .tbmc__media',
      '.top-bottom-media-content-section .tbmc__content',
      '.slider-room-fs__slider',
      '.slider-room-fs__content',
      '.career-list .career-item',
      '.contact-details-with-image-section__address',
      '.contact-details-with-image-section__opening',
      '.contact-details-with-image-section__image',
      '.faqs-accordion .faq-item',
      '.gallery-grid-filter-section__grid',
      '.featured-article-post-section__wrapper',
      '.article-post-card',
      '.post-listing-item .post-card__image',
      '.post-listing-item .post-card__body',
      '.mis__wrapper .mis__title',
      '.mis__wrapper .mis__content',
      '.mis__wrapper .mis__button',
      '.left-right-media-content-section__image',
      '.left-right-media-content-section__content',
      '.instagram-feed-container',
    ];

    // Optional per-selector overrides (fromVars, toVars, trigger).
    const customAnimations = [];

    const DEFAULT_FROM = { y: 80, opacity: 0 };
    const DEFAULT_TO = { y: 0, opacity: 1, duration: 1.2, ease: 'power3.out' };
    const DEFAULT_TRIGGER = { start: 'top 80%', once: true, markers: false };

    function buildTrigger(el, overrides = {}) {
      return { ...DEFAULT_TRIGGER, trigger: el, ...overrides };
    }

    function getCustomConfig(selector) {
      return customAnimations.find((cfg) => cfg.selector === selector) ?? null;
    }

    function applyAnimation(selector) {
      const elements = document.querySelectorAll(selector);
      if (!elements.length) return;

      const custom = getCustomConfig(selector);

      elements.forEach((el) => {
        if (el.dataset.gsapInitialised) return;
        el.dataset.gsapInitialised = 'true';

        const fromVars = custom?.fromVars ?? DEFAULT_FROM;
        const toVars = {
          ...(custom?.toVars ?? DEFAULT_TO),
          scrollTrigger: buildTrigger(el, custom?.trigger ?? {}),
        };

        gsap.fromTo(el, fromVars, toVars);
      });
    }

    function initScrollAnimations() {
      animatedElements.forEach(applyAnimation);
    }

    initScrollAnimations();
  });
}
