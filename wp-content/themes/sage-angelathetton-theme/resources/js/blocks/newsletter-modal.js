/* ==========================================
   NEWSLETTER MODAL
========================================== */

function initNewsletterModal() {
  const modal = document.getElementById('newsletter-modal');
  const openTriggers = document.querySelectorAll('.newsletter-sign-up');
  const closeTriggers = document.querySelectorAll('[data-newsletter-close]');

  if (!modal) return;

  let previousActiveElement = null;
  let modalFocusableElements = [];
  let firstModalFocusable = null;
  let lastModalFocusable = null;

  function openNewsletterModal() {
    previousActiveElement = document.activeElement;

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');

    // Set up focus trap
    modalFocusableElements = modal.querySelectorAll(
      'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
    );

    if (modalFocusableElements.length > 0) {
      firstModalFocusable = modalFocusableElements[0];
      lastModalFocusable = modalFocusableElements[modalFocusableElements.length - 1];
      firstModalFocusable.focus();
    }

    document.addEventListener('keydown', handleModalKeydown);
  }

  function closeNewsletterModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('no-scroll');

    document.removeEventListener('keydown', handleModalKeydown);

    // Return focus to the trigger element
    if (previousActiveElement) {
      previousActiveElement.focus();
    }
  }

  function handleModalKeydown(e) {
    if (e.key === 'Escape') {
      closeNewsletterModal();
      return;
    }

    if (e.key === 'Tab') {
      if (!firstModalFocusable || !lastModalFocusable) return;

      if (e.shiftKey) {
        if (document.activeElement === firstModalFocusable) {
          e.preventDefault();
          lastModalFocusable.focus();
        }
      } else {
        if (document.activeElement === lastModalFocusable) {
          e.preventDefault();
          firstModalFocusable.focus();
        }
      }
    }
  }

  // Attach open event to triggers
  openTriggers.forEach((trigger) => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      openNewsletterModal();
    });
  });

  // Attach close event to close triggers (X button and overlay)
  closeTriggers.forEach((trigger) => {
    trigger.addEventListener('click', closeNewsletterModal);
  });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', initNewsletterModal);
