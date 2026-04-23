/* ==========================================
   NEWSLETTER MODAL
========================================== */

import jQuery from 'jquery';

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

    // Update aria-expanded on trigger elements
    openTriggers.forEach((trigger) => {
      trigger.setAttribute('aria-expanded', 'true');
    });

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

    // Update aria-expanded on trigger elements
    openTriggers.forEach((trigger) => {
      trigger.setAttribute('aria-expanded', 'false');
    });

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

if(jQuery('.wpcf7-form')[0]) {
    jQuery('.wpcf7-form').each(function() {
        var answer = jQuery(this).find('.cf7ic_instructions span').text();
        var id = jQuery(this).attr('id');
        var i = 1;

        jQuery(this).find('.captcha-image label').each(function() {
            jQuery(this).find('input[value="bot"]').each(function() {
                jQuery(this).parent().append('<div class="visually-hidden">This is not the '+answer+' icon.</div>');
                jQuery(this).parent().attr('for', 'bot-'+id+'-'+i);
                jQuery(this).parent().prop('for', 'bot-'+id+'-'+i);
                jQuery(this).attr('id', 'bot-'+id+'-'+i);
                jQuery(this).prop('id', 'bot-'+id+'-'+i);
                i++;
            });

            if(jQuery(this).find('input[value="kc_human"]')[0]) {
                jQuery(this).find('input[value="kc_human"]').parent().append('<div class="visually-hidden">'+answer+' icon.</div>');
                jQuery(this).find('input[value="kc_human"]').parent().attr('for', 'bot-'+id+'-3');
                jQuery(this).find('input[value="kc_human"]').parent().prop('for', 'bot-'+id+'-3');
                jQuery(this).find('input[value="kc_human"]').attr('id', 'bot-'+id+'-3');
                jQuery(this).find('input[value="kc_human"]').prop('id', 'bot-'+id+'-3');
            }
        });

        jQuery(this).find('input[name="kc_honeypot"]').attr('tabindex', -1);
        jQuery(this).find('input[name="kc_honeypot"]').prop('tabindex', -1);
        jQuery(this).find('input[name="kc_honeypot"]').attr('id', 'kc_honeypot-'+id);
        jQuery(this).find('input[name="kc_honeypot"]').prop('id', 'kc_honeypot-'+id);
        jQuery(this).find('input[name="kc_honeypot"]').wrap('<label for="kc_honeypot-'+id+'"></label>');
    });
}
