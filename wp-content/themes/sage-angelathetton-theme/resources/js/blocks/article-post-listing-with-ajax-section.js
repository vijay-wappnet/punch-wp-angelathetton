/**
 * Article Post Listing With Ajax Section
 * Handles load more functionality with vanilla JS and fetch API
 */

(function() {
  'use strict';

  // Check if current viewport is mobile (below 768px)
  function isMobileViewport() {
    return window.innerWidth < 768;
  }

  // Initialize all article post listing sections
  function initArticlePostListingSections() {
    const sections = document.querySelectorAll('.article-post-listing-with-ajax-section');

    sections.forEach(section => {
      initSection(section);
    });

    // Handle resize for mobile/desktop switching
    let resizeTimeout;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(function() {
        sections.forEach(section => {
          handleVisibility(section);
          updateLoadMoreButton(section);
        });
      }, 250);
    });
  }

  // Initialize a single section
  function initSection(section) {
    // Handle initial visibility based on viewport
    handleVisibility(section);
    updateLoadMoreButton(section);

    const loadMoreBtn = section.querySelector('.article-load-more-btn');

    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener('click', function(e) {
      e.preventDefault();
      handleLoadMore(section, loadMoreBtn);
    });
  }

  // Handle visibility of posts based on viewport
  function handleVisibility(section) {
    const postsPerPageMobile = parseInt(section.dataset.postsPerPageMobile) || 3;
    const items = section.querySelectorAll('.article-post-listing-item');
    const isMobile = isMobileViewport();

    items.forEach((item, index) => {
      // Only hide/show items that were loaded initially (not via AJAX)
      if (!item.classList.contains('ajax-loaded')) {
        if (isMobile && index >= postsPerPageMobile) {
          item.classList.add('mobile-hidden');
        } else {
          item.classList.remove('mobile-hidden');
        }
      }
    });
  }

  // Update load more button visibility
  function updateLoadMoreButton(section) {
    const totalPosts = parseInt(section.dataset.totalPosts) || 0;
    const visibleCount = getVisiblePostsCount(section);
    const buttonWrapper = section.querySelector('.article-load-more-button-wrapper');

    if (buttonWrapper) {
      if (visibleCount >= totalPosts) {
        buttonWrapper.style.display = 'none';
      } else {
        buttonWrapper.style.display = 'flex';
        buttonWrapper.style.opacity = '1';
      }
    }
  }

  // Get count of visible posts
  function getVisiblePostsCount(section) {
    const items = section.querySelectorAll('.article-post-listing-item');
    let visibleCount = 0;
    items.forEach(item => {
      if (!item.classList.contains('mobile-hidden')) {
        visibleCount++;
      }
    });
    return visibleCount;
  }

  // Handle load more click
  async function handleLoadMore(section, button) {
    // Get data attributes
    const postType = section.dataset.postType || 'post';
    const postsPerPageDesktop = parseInt(section.dataset.postsPerPage) || 9;
    const postsPerPageMobile = parseInt(section.dataset.postsPerPageMobile) || 3;
    const isMobile = isMobileViewport();
    const postsPerPage = isMobile ? postsPerPageMobile : postsPerPageDesktop;
    const orderby = section.dataset.orderby || 'date';
    const order = section.dataset.order || 'DESC';
    const totalPosts = parseInt(section.dataset.totalPosts) || 0;

    // Check if there are hidden posts to show first (on mobile)
    const hiddenItems = section.querySelectorAll('.article-post-listing-item.mobile-hidden');

    if (isMobile && hiddenItems.length > 0) {
      // Show next batch of hidden posts
      let shown = 0;
      hiddenItems.forEach(item => {
        if (shown < postsPerPage) {
          item.classList.remove('mobile-hidden');
          item.classList.add('is-loaded');
          shown++;
        }
      });

      // Update button visibility
      updateLoadMoreButton(section);
      return;
    }

    // Calculate offset based on total items in DOM
    const allItems = section.querySelectorAll('.article-post-listing-item');
    const offset = allItems.length;

    // Check if we need to load more
    if (offset >= totalPosts) {
      hideLoadMoreButton(section);
      return;
    }

    // Disable button and show loading state
    setButtonLoading(button, true);

    try {
      // Prepare form data
      const formData = new FormData();
      formData.append('action', 'load_more_article_posts');
      formData.append('nonce', articleListingAjax.nonce);
      formData.append('post_type', postType);
      formData.append('posts_per_page', postsPerPage);
      formData.append('orderby', orderby);
      formData.append('order', order);
      formData.append('offset', offset);

      // Make AJAX request
      const response = await fetch(articleListingAjax.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      const data = await response.json();

      if (data.success && data.data.html) {
        // Append new posts
        appendPosts(section, data.data.html);

        // Check if there are more posts
        const newTotalItems = section.querySelectorAll('.article-post-listing-item').length;
        if (newTotalItems >= totalPosts) {
          hideLoadMoreButton(section);
        }
      } else if (data.data && data.data.message) {
        console.log(data.data.message);
        hideLoadMoreButton(section);
      }
    } catch (error) {
      console.error('Error loading more article posts:', error);
    } finally {
      // Reset button state
      setButtonLoading(button, false);
    }
  }

  // Set button loading state
  function setButtonLoading(button, isLoading) {
    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');

    if (isLoading) {
      button.disabled = true;
      if (btnText) btnText.style.display = 'none';
      if (btnLoading) btnLoading.style.display = 'inline-flex';
    } else {
      button.disabled = false;
      if (btnText) btnText.style.display = 'inline';
      if (btnLoading) btnLoading.style.display = 'none';
    }
  }

  // Append new posts to the grid
  function appendPosts(section, html) {
    const grid = section.querySelector('.article-post-listing-grid');

    if (!grid) return;

    // Create a temporary container to parse HTML
    const tempContainer = document.createElement('div');
    tempContainer.innerHTML = html;

    // Get all new post items
    const newItems = tempContainer.querySelectorAll('.article-post-listing-item');

    // Add loading class initially and mark as ajax-loaded
    newItems.forEach(item => {
      item.classList.add('is-loading');
      item.classList.add('ajax-loaded');
    });

    // Append each item to the grid
    newItems.forEach((item, index) => {
      grid.appendChild(item);

      // Trigger reflow and add loaded class for animation
      setTimeout(() => {
        item.classList.remove('is-loading');
        item.classList.add('is-loaded');
      }, index * 100);
    });
  }

  // Hide load more button
  function hideLoadMoreButton(section) {
    const buttonWrapper = section.querySelector('.article-load-more-button-wrapper');

    if (buttonWrapper) {
      buttonWrapper.style.opacity = '0';
      buttonWrapper.style.transition = 'opacity 0.3s ease';

      setTimeout(() => {
        buttonWrapper.style.display = 'none';
      }, 300);
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initArticlePostListingSections);
  } else {
    initArticlePostListingSections();
  }
})();
