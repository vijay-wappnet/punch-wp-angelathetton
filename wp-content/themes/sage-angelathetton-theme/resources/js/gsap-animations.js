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
      '.introtext-with-right-cta-section .row',
      '.container-width-banner-image-section img',
      '.container-width-two-column-image-section .row',
      '.related-post-listing-section .rpls__header',
      '.related-post-listing-section .rpls__grid .rpls__card .rpls__card-image',
      '.related-post-listing-section .rpls__grid .rpls__card .rpls__card-body',
      '.two-columns-image-with-cta-section .tciwcs__column .tciwcs__image',
      '.two-columns-image-with-cta-section .tciwcs__column .tciwcs__content',
      '.video-banner-section__heading',
      '.smb-item__wrapper',
      '.two-columns-image-cta-with-mobile-slider-section .tcicwmss__column .tcicwmss__image',
      '.two-columns-image-cta-with-mobile-slider-section .tcicwmss__column .tcicwmss__content',
      '.top-bottom-media-content-section .tbmc__media',
      '.top-bottom-media-content-section .tbmc__content',
      '.slider-room-fs__slider',
      '.slider-room-fs__content',
      '.career-list .career-item',
      '.contact-details-with-image-section .cdwis-row',
      '.google-map-section',
      '.banner-image-section',
      '.faqs-accordion .faq-item',
      '.gallery-grid-filter-section__grid',
      '.featured-article-post-section__wrapper',
      '.article-post-card .article-post-card__image',
      '.article-post-card .article-post-card__body',
      '.article-single-banner-image-section',
      '.article-read-time-author-details-section',
      '.latest-article-list-section .lals__header',
      '.latest-article-list-section .lals__grid .lals__card .lals__card-image',
      '.latest-article-list-section .lals__grid .lals__card .lals__card-body',
      '.post-listing-item .post-card__image',
      '.post-listing-item .post-card__body',
      '.mis__wrapper .mis__title',
      '.mis__wrapper .mis__content',
      '.mis__wrapper .mis__button',
      '.left-right-media-content-section__image',
      '.left-right-media-content-section__content',
      '.instagram-feed-container',
      '.package-details-with-images-section .package-details-with-images-section__main-heading',
      '.package-details-with-images-section .pdwis-row img',
      '.package-details-with-images-section .pdwis-row .package-details-with-images-section__contents',
      '.error-404-page',
      '.error-404-page .error-404-page__heading',
      '.error-404-page .error-404-page__button-wrapper',
      '.single-product .single-product-container__product_title',
      '.single-product .woocommerce-product-gallery__wrapper',
      '.single-product .entry-summary',
      '.woo-single-product-delivery-section .woo-single-product-delivery-section__title',
      '.woo-single-product-delivery-section .woo-single-product-delivery-section__description',
      '.woo-single-related-products-section .woo-single-related-products-section__header',
      '.woo-single-related-products-section .woo-single-related-products-section__image',
      '.woo-single-related-products-section .woo-single-related-products-section__content',
      '.woocommerce-cart .cart-layout .basket-column',
      '.woocommerce-cart .cart-layout .order-summary-column',
      '.woocommerce-checkout .checkout-page .checkout-title',
      '.woocommerce-checkout .checkout-page .checkout-layout .checkout-customer-column',
      '.woocommerce-checkout .checkout-page .checkout-layout .order-summary-column',
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
