import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

// header scroll behaviour, menu open/close and accessibility
const header = document.getElementById('site-header');
const menuToggle = document.querySelector('.menu-toggle');
const fullscreenMenu = document.getElementById('fullscreen-menu');
const closeBtn = fullscreenMenu && fullscreenMenu.querySelector('.close-btn');

let focusableElements = [];
let firstFocusable = null;
let lastFocusable = null;

function updateScrollState() {
  if (!header) return;
  if (window.scrollY > 80) {
    header.classList.add('header-scrolled');
  } else {
    header.classList.remove('header-scrolled');
  }
}

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
}

function toggleMenu() {
  if (fullscreenMenu.classList.contains('is-open')) {
    closeMenu();
  } else {
    openMenu();
  }
}

function handleEsc(e) {
  if (e.key === 'Escape' && fullscreenMenu && fullscreenMenu.classList.contains('is-open')) {
    closeMenu();
  }
}

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

  // detect submenus and attach toggles
  if (fullscreenMenu) {
    const items = fullscreenMenu.querySelectorAll('.menu-item-has-children');
    items.forEach((item, idx) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'submenu-toggle';
      btn.setAttribute('aria-expanded', 'false');
      const submenu = item.querySelector('.sub-menu');
      if (submenu) {
        // generate id for aria-controls
        let subId = submenu.id;
        if (!subId) {
          subId = `submenu-${idx}`;
          submenu.id = subId;
        }
        btn.setAttribute('aria-controls', subId);
      }
      btn.innerHTML = '<span class="icon">+</span>';

      const link = item.querySelector('a');
      if (link) {
        link.after(btn);
      }

      btn.addEventListener('click', () => {
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
        item.classList.toggle('open');
        if (!expanded && submenu) {
          submenu.style.maxHeight = submenu.scrollHeight + 'px';
        } else if (submenu) {
          submenu.style.maxHeight = null;
        }
      });
    });
  }
});
