/**
 * Gallery Grid Filter Section
 * Handles filtering, lightbox, and AJAX functionality
 */

(function() {
  'use strict';

  // Initialize all gallery sections
  function initGalleryGridFilterSections() {
    const sections = document.querySelectorAll('.gallery-grid-filter-section');

    sections.forEach(section => {
      initSection(section);
    });
  }

  // Initialize a single section
  function initSection(section) {
    initDropdown(section);
    initRefreshButton(section);
    initLightbox(section);
    initImageClicks(section);
  }

  /**
   * Dropdown functionality
   */
  function initDropdown(section) {
    const toggle = section.querySelector('.gallery-grid-filter-section__dropdown-toggle');
    const menu = section.querySelector('.gallery-grid-filter-section__dropdown-menu');
    const textElement = section.querySelector('.gallery-grid-filter-section__dropdown-text');
    const options = section.querySelectorAll('.gallery-grid-filter-section__dropdown-menu li');

    if (!toggle || !menu) return;

    // Toggle dropdown
    toggle.addEventListener('click', function(e) {
      e.stopPropagation();
      const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', !isExpanded);
      menu.classList.toggle('is-open');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!section.contains(e.target)) {
        toggle.setAttribute('aria-expanded', 'false');
        menu.classList.remove('is-open');
      }
    });

    // Handle option selection
    options.forEach(option => {
      option.addEventListener('click', function() {
        const value = this.dataset.value;
        const label = this.textContent.trim();

        // Update selected state
        options.forEach(opt => {
          opt.classList.remove('is-selected');
          opt.setAttribute('aria-selected', 'false');
        });
        this.classList.add('is-selected');
        this.setAttribute('aria-selected', 'true');

        // Update button text
        if (textElement) {
          textElement.textContent = label;
        }

        // Close dropdown
        toggle.setAttribute('aria-expanded', 'false');
        menu.classList.remove('is-open');

        // Filter images via AJAX
        filterGallery(section, value);
      });

      // Keyboard navigation
      option.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.click();
        }
      });
    });

    // Keyboard navigation for toggle
    toggle.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        toggle.setAttribute('aria-expanded', 'false');
        menu.classList.remove('is-open');
      }
    });
  }

  /**
   * Refresh button functionality
   */
  function initRefreshButton(section) {
    const refreshBtn = section.querySelector('.gallery-grid-filter-section__refresh');
    const dropdownText = section.querySelector('.gallery-grid-filter-section__dropdown-text');
    const options = section.querySelectorAll('.gallery-grid-filter-section__dropdown-menu li');

    if (!refreshBtn) return;

    refreshBtn.addEventListener('click', function() {
      // Reset to "All"
      options.forEach(opt => {
        opt.classList.remove('is-selected');
        opt.setAttribute('aria-selected', 'false');
        if (opt.dataset.value === 'all') {
          opt.classList.add('is-selected');
          opt.setAttribute('aria-selected', 'true');
        }
      });

      if (dropdownText) {
        dropdownText.textContent = 'All';
      }

      // Filter to show all images
      filterGallery(section, 'all');
    });
  }

  /**
   * Filter gallery via AJAX
   */
  async function filterGallery(section, category) {
    const grid = section.querySelector('.gallery-grid-filter-section__grid');
    const blockId = section.dataset.blockId;

    if (!grid) return;

    // Add loading state
    grid.classList.add('is-loading');

    try {
      // Prepare form data
      const formData = new FormData();
      formData.append('action', 'gallery_filter');
      formData.append('nonce', window.galleryFilterAjax?.nonce || '');
      formData.append('category', category);
      formData.append('block_id', blockId);

      // Make AJAX request
      const response = await fetch(window.galleryFilterAjax?.ajaxUrl || '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });

      const data = await response.json();

      if (data.success && data.data.html) {
        // Update grid with new HTML
        grid.innerHTML = data.data.html;

        // Re-initialize image clicks for new content
        initImageClicks(section);
      } else {
        console.error('Gallery filter failed:', data);
      }
    } catch (error) {
      console.error('Gallery filter error:', error);
    } finally {
      // Remove loading state
      grid.classList.remove('is-loading');
    }
  }

  /**
   * Lightbox functionality
   */
  function initLightbox(section) {
    const lightbox = section.querySelector('.gallery-grid-filter-section__lightbox');
    const closeBtn = lightbox?.querySelector('.gallery-grid-filter-section__lightbox-close');
    const overlay = lightbox?.querySelector('.gallery-grid-filter-section__lightbox-overlay');
    const prevBtn = lightbox?.querySelector('.gallery-grid-filter-section__lightbox-prev');
    const nextBtn = lightbox?.querySelector('.gallery-grid-filter-section__lightbox-next');
    const imageEl = lightbox?.querySelector('.gallery-grid-filter-section__lightbox-image');

    if (!lightbox) return;

    let currentIndex = 0;
    let galleryImages = [];

    // Store lightbox data on section
    section._lightbox = {
      element: lightbox,
      image: imageEl,
      prevBtn,
      nextBtn,
      currentIndex: 0,
      images: []
    };

    // Close button
    closeBtn?.addEventListener('click', () => closeLightbox(section));
    overlay?.addEventListener('click', () => closeLightbox(section));

    // Navigation
    prevBtn?.addEventListener('click', () => navigateLightbox(section, -1));
    nextBtn?.addEventListener('click', () => navigateLightbox(section, 1));

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (!lightbox.classList.contains('is-open')) return;

      switch (e.key) {
        case 'Escape':
          closeLightbox(section);
          break;
        case 'ArrowLeft':
          navigateLightbox(section, -1);
          break;
        case 'ArrowRight':
          navigateLightbox(section, 1);
          break;
      }
    });
  }

  /**
   * Initialize image click handlers
   */
  function initImageClicks(section) {
    const items = section.querySelectorAll('.gallery-grid-filter-section__item');

    items.forEach((item, index) => {
      item.addEventListener('click', () => {
        openLightbox(section, index);
      });

      // Keyboard accessibility
      item.setAttribute('tabindex', '0');
      item.setAttribute('role', 'button');
      item.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          openLightbox(section, index);
        }
      });
    });
  }

  /**
   * Open lightbox
   */
  function openLightbox(section, index) {
    const lightbox = section._lightbox;
    if (!lightbox) return;

    // Get all visible images
    const items = section.querySelectorAll('.gallery-grid-filter-section__item:not(.is-hidden)');
    lightbox.images = Array.from(items).map(item => item.dataset.fullImage).filter(Boolean);
    lightbox.currentIndex = index;

    // Set image
    if (lightbox.images[index]) {
      lightbox.image.src = lightbox.images[index];
      lightbox.image.alt = '';
    }

    // Update nav buttons
    updateNavButtons(lightbox);

    // Open lightbox
    lightbox.element.classList.add('is-open');
    lightbox.element.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  /**
   * Close lightbox
   */
  function closeLightbox(section) {
    const lightbox = section._lightbox;
    if (!lightbox) return;

    lightbox.element.classList.remove('is-open');
    lightbox.element.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  /**
   * Navigate lightbox
   */
  function navigateLightbox(section, direction) {
    const lightbox = section._lightbox;
    if (!lightbox || !lightbox.images.length) return;

    const newIndex = lightbox.currentIndex + direction;

    if (newIndex >= 0 && newIndex < lightbox.images.length) {
      lightbox.currentIndex = newIndex;
      lightbox.image.src = lightbox.images[newIndex];
      updateNavButtons(lightbox);
    }
  }

  /**
   * Update navigation button states
   */
  function updateNavButtons(lightbox) {
    if (!lightbox.prevBtn || !lightbox.nextBtn) return;

    lightbox.prevBtn.disabled = lightbox.currentIndex <= 0;
    lightbox.nextBtn.disabled = lightbox.currentIndex >= lightbox.images.length - 1;
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGalleryGridFilterSections);
  } else {
    initGalleryGridFilterSections();
  }

  // Re-initialize for Gutenberg editor preview
  if (window.acf) {
    window.acf.addAction('render_block_preview/type=acf/gallery-grid-filter-section', function() {
      setTimeout(initGalleryGridFilterSections, 100);
    });
  }
})();
