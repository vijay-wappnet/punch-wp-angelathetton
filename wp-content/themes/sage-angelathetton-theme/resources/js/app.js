import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

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
  if (window.scrollY > 80) {
    header.classList.add('header-scrolled');
  } else {
    header.classList.remove('header-scrolled');
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
    'a, button, input, [tabindex]:not([tabindex="-1"])'
  );

  firstFocusable = focusableElements[0];
  lastFocusable = focusableElements[focusableElements.length - 1];

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

function initPanelNavigation() {
  if (!fullscreenMenu) return;

  const rootMenu = fullscreenMenu.querySelector('.fullscreen-menu-list');

  fullscreenMenu
    .querySelectorAll('.menu-item-has-children > a')
    .forEach(link => {

      link.addEventListener('click', function (e) {

        const parentLi = this.parentElement;
        const childMenu = parentLi.querySelector(':scope > .sub-menu');

        if (!childMenu) return;

        e.preventDefault();

        const currentPanel = this.closest('ul');

        currentPanel.classList.add('menu-panel-hidden');
        childMenu.classList.add('menu-panel-active');

        insertBackButton(childMenu, currentPanel);

        // Force parent image when panel opens
        const previewImage = document.getElementById('menu-preview-image');
        const parentImage = parentLi.getAttribute('data-menu-image');
        if (previewImage && parentImage) {
          previewImage.src = parentImage;
        }
      });

    });
}

function insertBackButton(panel, previousPanel) {

  if (panel.querySelector('.menu-back')) return;

  const backBtn = document.createElement('div');
  backBtn.className = 'menu-back';
  backBtn.innerHTML = '← BACK';

  panel.prepend(backBtn);

  backBtn.addEventListener('click', function () {

    panel.classList.remove('menu-panel-active');
    previousPanel.classList.remove('menu-panel-hidden');

    setTimeout(() => {
      backBtn.remove();
    }, 300);
  });
}

function resetMenuPanels() {
  if (!fullscreenMenu) return;

  const panels = fullscreenMenu.querySelectorAll('.sub-menu');
  const rootMenu = fullscreenMenu.querySelector('.fullscreen-menu-list');

  panels.forEach(panel => {
    panel.classList.remove('menu-panel-active');
  });

  if (rootMenu) {
    rootMenu.classList.remove('menu-panel-hidden');
  }

  fullscreenMenu.querySelectorAll('.menu-back').forEach(btn => btn.remove());
}

/* ==========================================
   IMAGE SWITCHING LOGIC
========================================== */

function initImageHover() {
  if (!fullscreenMenu) return;

  const previewImage = document.getElementById('menu-preview-image');
  if (!previewImage) return;

  const defaultImageSrc = previewImage.src;
  const items = fullscreenMenu.querySelectorAll('.menu-left .menu-item');

  const fadeToImage = (newSrc) => {
    if (!newSrc || previewImage.src === newSrc) return;

    previewImage.style.opacity = 0;

    setTimeout(() => {
      previewImage.src = newSrc;
      previewImage.style.opacity = 1;
    }, 300); // half of transition time
  };

  items.forEach(li => {

    const changeImage = () => {
      const imgUrl = li.getAttribute('data-menu-image');
      fadeToImage(imgUrl ? imgUrl : defaultImageSrc);
    };

    const resetImage = () => {
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

  initPanelNavigation();
  initImageHover();
});
