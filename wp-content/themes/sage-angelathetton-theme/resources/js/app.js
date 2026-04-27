import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import leftArrow from '../images/left_arrow.svg';

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

/* ==========================================
   BLOCK SCRIPTS
========================================== */
import './blocks/video-banner-scroll.js';
import './blocks/slider-with-multiple-box-section.js';
import './blocks/team-slider-with-multiple-box-section.js';
import './blocks/slider-room-features-section.js';
import './blocks/two-columns-image-cta-with-mobile-slider-section.js';
import './blocks/newsletter-modal.js';
import './blocks/post-listing-with-ajax-section.js';
import './blocks/career-post-listing-section.js';
import './blocks/faqs-accordion-section.js';
import './blocks/article-post-listing-with-ajax-section.js';
import './blocks/gallery-grid-filter-section.js';
import './accessibility-modal.js';
import './gsap-animations.js';
import './dataLayer-script.js';

/* ==========================================
   HEADER + FULLSCREEN MENU BEHAVIOUR
========================================== */

const header = document.getElementById('site-header');
const menuToggle = document.querySelector('.menu-toggle');
const fullscreenMenu = document.getElementById('fullscreen-menu');
const closeBtn = fullscreenMenu?.querySelector('.close-btn');

let focusableElements = [];
let firstFocusable = null;
let lastFocusable = null;

/* ==========================================
   HEADER SCROLL EFFECT
========================================== */

function updateScrollState() {
  if (!header) return;

  // Select logo elements
  const blackLogo = header.querySelector('.brand-logo-black');
  const whiteLogoFirstOnlyHome = header.querySelector('.brand-logo-white.logo-link-first.only-home-logo');
  const whiteLogoSecond = header.querySelector('.brand-logo-white.logo-link-second');

  if (window.scrollY > 80) {
    header.classList.add('header-scrolled');
    header.classList.add('logo-switched');


    // On scroll: show white logo, hide black logo
    if (blackLogo) blackLogo.setAttribute('aria-hidden', 'true');
    if (whiteLogoSecond) whiteLogoSecond.setAttribute('aria-hidden', 'true');

    if (whiteLogoSecond) whiteLogoSecond.setAttribute('aria-hidden', 'false');
    if (whiteLogoFirstOnlyHome) whiteLogoFirstOnlyHome.setAttribute('aria-hidden', 'true');


  } else {
    header.classList.remove('header-scrolled');
    header.classList.remove('logo-switched');

    // At top: show black logo, hide white logo
    if (blackLogo) blackLogo.setAttribute('aria-hidden', 'false');
    if (whiteLogoSecond) whiteLogoSecond.setAttribute('aria-hidden', 'false');

    if (whiteLogoSecond) whiteLogoSecond.setAttribute('aria-hidden', 'true');
    if (whiteLogoFirstOnlyHome) whiteLogoFirstOnlyHome.setAttribute('aria-hidden', 'false');
  }
}

/* ==========================================
   FOCUS TRAP (ACCESSIBILITY)
========================================== */

function trapFocus(e) {
  if (e.key !== 'Tab') return;
  if (!firstFocusable || !lastFocusable) return;

  if (e.shiftKey) {
    if (document.activeElement === firstFocusable) {
      e.preventDefault();
      lastFocusable.focus();
    }
  } else {
    if (document.activeElement === lastFocusable) {
      e.preventDefault();
      firstFocusable.focus();
    }
  }
}

function lockBodyScroll() {
  document.body.classList.add('no-scroll');
}

function unlockBodyScroll() {
  document.body.classList.remove('no-scroll');
}

/* ==========================================
   OPEN / CLOSE MENU
========================================== */

function openMenu() {
  if (!fullscreenMenu) return;

  fullscreenMenu.classList.add('is-open');
  fullscreenMenu.setAttribute('aria-hidden', 'false');
  menuToggle.setAttribute('aria-expanded', 'true');
  lockBodyScroll();

  focusableElements = fullscreenMenu.querySelectorAll(
    //'a, button, input, [tabindex]:not([tabindex="-1"])',
    'a:not([tabindex="-1"]), button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])'
  );

  //firstFocusable = focusableElements[0];
  firstFocusable = closeBtn || focusableElements[0];
  lastFocusable = focusableElements[focusableElements.length - 1];

  firstFocusable?.focus();

  document.querySelectorAll('body > *:not(#fullscreen-menu)').forEach(el => {
    el.setAttribute('aria-hidden', 'true');
  });

  document.addEventListener('keydown', trapFocus);
}

function closeMenu() {
  if (!fullscreenMenu) return;

  fullscreenMenu.classList.remove('is-open');
  fullscreenMenu.setAttribute('aria-hidden', 'true');
  menuToggle.setAttribute('aria-expanded', 'false');
  unlockBodyScroll();

  document.removeEventListener('keydown', trapFocus);
  menuToggle.focus();

  document.querySelectorAll('body > *:not(#fullscreen-menu)').forEach(el => {
    el.removeAttribute('aria-hidden');
  });

  // Reset panels to root when closing
  resetMenuPanels();
}

function toggleMenu() {
  if (!fullscreenMenu) return;

  if (fullscreenMenu.classList.contains('is-open')) {
    closeMenu();
  } else {
    openMenu();
  }
}

function handleEsc(e) {
  if (e.key === 'Escape' && fullscreenMenu?.classList.contains('is-open')) {
    closeMenu();
  }
}

/* ==========================================
   PANEL NAVIGATION SYSTEM
========================================== */

const MOBILE_BREAKPOINT = 992;

let currentSubMenuParentImage = null;
let isInSubMenu = false;

function isMobileView() {
  return window.innerWidth < MOBILE_BREAKPOINT;
}

function initPanelNavigation() {
  if (!fullscreenMenu) return;

  const rootMenu = fullscreenMenu.querySelector('.fullscreen-menu-list');

  fullscreenMenu
    .querySelectorAll('.menu-item-has-children > a')
    .forEach((link) => {
      link.addEventListener('click', function (e) {
        const parentLi = this.parentElement;
        const childMenu = parentLi.querySelector(':scope > .sub-menu');

        // OPEN submenu
        this.setAttribute('aria-expanded', 'true');
        childMenu.setAttribute('aria-hidden', 'false');

        if (!childMenu) return;

        e.preventDefault();

        /* ── mobile: toggle submenu inline ── */
        if (isMobileView()) {
          parentLi.classList.toggle('mobile-submenu-open');
          return;
        }

        /* ── desktop: panel-slide navigation ── */
        const currentPanel = this.closest('ul');

        // Ensure no other submenus are active within the current panel context
        currentPanel.querySelectorAll(':scope > .menu-item > .sub-menu').forEach(submenu => {
          submenu.classList.remove('menu-panel-active');
        });

        currentPanel.classList.add('menu-panel-hidden');
        childMenu.classList.add('menu-panel-active');

        insertBackButton(childMenu, currentPanel);

        // Force parent image when panel opens
        const previewImage = document.getElementById('menu-preview-image');
        const parentImage = parentLi.getAttribute('data-menu-image');
        if (previewImage && parentImage) {
          previewImage.src = parentImage;
          currentSubMenuParentImage = parentImage;
          isInSubMenu = true;
        }
      });
    });
}

function updateFocusableElements() {
  focusableElements = fullscreenMenu.querySelectorAll(
    'a:not([tabindex="-1"]), button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])'
  );

  firstFocusable = closeBtn || focusableElements[0];
  lastFocusable = focusableElements[focusableElements.length - 1];
}

function insertBackButton(panel, previousPanel) {
  if (panel.querySelector('.menu-back')) return;

  const backBtn = document.createElement('button');
  backBtn.className = 'menu-back';
  backBtn.setAttribute('type', 'button');
  backBtn.setAttribute('aria-label', 'Go back to previous menu');
  backBtn.innerHTML = `<img src="${leftArrow}" alt="Back" class="back-arrow"> BACK`;

  panel.prepend(backBtn);
  updateFocusableElements();
  setTimeout(() => {
    backBtn.focus();
  }, 0);

  backBtn.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      backBtn.click();
    }
  });

  backBtn.addEventListener('click', function () {
    panel.classList.remove('menu-panel-active');
    previousPanel.classList.remove('menu-panel-hidden');

    // Reset submenu state and restore previous image
    isInSubMenu = false;
    const previewImage = document.getElementById('menu-preview-image');
    if (previewImage && currentSubMenuParentImage) {
      previewImage.src = currentSubMenuParentImage;
    }

    // move focus back
    const parentLink = previousPanel.querySelector('.menu-item > a');
    parentLink?.focus();

    const parentTrigger = previousPanel
      .closest('.menu-item-has-children')
      ?.querySelector(':scope > a');

    parentTrigger?.setAttribute('aria-expanded', 'false');
    panel.setAttribute('aria-hidden', 'true');

    setTimeout(() => {
      backBtn.remove();
      updateFocusableElements();
    }, 10); // Minimal delay or immediate removal as class reset handles hiding
  });

}

function resetMenuPanels() {
  if (!fullscreenMenu) return;

  const panels = fullscreenMenu.querySelectorAll('.sub-menu');
  const rootMenu = fullscreenMenu.querySelector('.fullscreen-menu-list');

  panels.forEach((panel) => {
    panel.classList.remove('menu-panel-active');
  });

  if (rootMenu) {
    rootMenu.classList.remove('menu-panel-hidden');
  }

  fullscreenMenu.querySelectorAll('.menu-back').forEach((btn) => btn.remove());

  // Reset mobile submenu toggles
  fullscreenMenu
    .querySelectorAll('.mobile-submenu-open')
    .forEach((li) => li.classList.remove('mobile-submenu-open'));

  // Reset submenu state
  isInSubMenu = false;
  currentSubMenuParentImage = null;
}

/* ==========================================
   IMAGE SWITCHING LOGIC
========================================== */

function initImageHover() {
  if (!fullscreenMenu) return;

  const previewImage = document.getElementById('menu-preview-image');
  if (!previewImage) return;

  const defaultImageSrc = previewImage.src;
  // Only select root-level menu items, not submenu items
  const items = fullscreenMenu.querySelectorAll(
    '.fullscreen-menu-list > .menu-item',
  );

  const fadeToImage = (newSrc) => {
    if (!newSrc || previewImage.src === newSrc) return;
    // Don't change image if we're inside a submenu
    if (isInSubMenu) return;

    previewImage.style.opacity = 1;

    setTimeout(() => {
      previewImage.src = newSrc;
      previewImage.style.opacity = 1;
    }); // half of transition time
  };

  items.forEach((li) => {
    const changeImage = () => {
      const imgUrl = li.getAttribute('data-menu-image');
      fadeToImage(imgUrl ? imgUrl : defaultImageSrc);
    };

    const resetImage = () => {
      // Don't reset if we're inside a submenu
      if (isInSubMenu) return;
      fadeToImage(defaultImageSrc);
    };

    li.addEventListener('pointerenter', changeImage);
    li.addEventListener('pointerleave', resetImage);
    li.addEventListener('focusin', changeImage);
    li.addEventListener('focusout', resetImage);
  });
}

/* ==========================================
   INIT
========================================== */

document.addEventListener('DOMContentLoaded', () => {
  updateScrollState();
  window.addEventListener('scroll', updateScrollState);

  if (menuToggle) {
    menuToggle.addEventListener('click', toggleMenu);
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeMenu);
  }

  document.addEventListener('keydown', handleEsc);

  // Footer sitemap toggle (mobile)
  const sitemapToggle = document.querySelector('.footer-sitemap-toggle');
  const sitemapContent = document.querySelector('.footer-sitemap-content');

  if (sitemapToggle && sitemapContent) {
    // Make it keyboard focusable
    sitemapToggle.setAttribute('tabindex', '0');
    sitemapToggle.setAttribute('role', 'button');
    sitemapToggle.setAttribute('aria-expanded', 'false');

    const toggleSitemap = () => {
      const isOpen = sitemapToggle.classList.toggle('is-open');
      sitemapContent.classList.toggle('is-open');
      sitemapToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    // Click handler
    sitemapToggle.addEventListener('click', toggleSitemap);

    // Keyboard handler (Enter and Space)
    sitemapToggle.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        toggleSitemap();
      }
    });
  }

  initPanelNavigation();
  initImageHover();
});


/* ==========================================
   Cart Count Cleanup (removes non-numeric characters added by WooCommerce)
========================================== */
document.addEventListener("DOMContentLoaded", function () {
  function updateCartCount() {
    document.querySelectorAll(".cart-menu-count").forEach(function (el) {
      el.textContent = el.textContent.replace(/\D/g, "");
    });
  }

  updateCartCount();

  // Re-run when WooCommerce updates cart fragments
  document.body.addEventListener("wc_fragments_refreshed", updateCartCount);
  document.body.addEventListener("added_to_cart", updateCartCount);
});
