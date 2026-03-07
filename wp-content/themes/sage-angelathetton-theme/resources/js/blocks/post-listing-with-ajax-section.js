/**
 * Post Listing With Ajax Section
 * Handles load more functionality with vanilla JS and fetch API
 */

(function() {
  'use strict';

  // Initialize all post listing sections
  function initPostListingSections() {
    const sections = document.querySelectorAll('.post-listing-with-ajax-section');

    sections.forEach(section => {
      initSection(section);
    });
  }

  // Initialize a single section
  function initSection(section) {
    const loadMoreBtn = section.querySelector('.load-more-btn');

    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener('click', function(e) {
      e.preventDefault();
      handleLoadMore(section, loadMoreBtn);
    });
  }

  // Handle load more click
  async function handleLoadMore(section, button) {
    // Get data attributes
    const postType = section.dataset.postType || 'post';
    const postsPerPage = parseInt(section.dataset.postsPerPage) || 3;
    const orderby = section.dataset.orderby || 'date';
    const order = section.dataset.order || 'DESC';
    const currentPage = parseInt(section.dataset.paged) || 1;
    const totalPosts = parseInt(section.dataset.totalPosts) || 0;
    const nextPage = currentPage + 1;

    // Calculate if there are more posts after this load
    const loadedPosts = currentPage * postsPerPage;
    const remainingPosts = totalPosts - loadedPosts;

    // Disable button and show loading state
    setButtonLoading(button, true);

    try {
      // Prepare form data
      const formData = new FormData();
      formData.append('action', 'load_more_posts');
      formData.append('nonce', postListingAjax.nonce);
      formData.append('post_type', postType);
      formData.append('posts_per_page', postsPerPage);
      formData.append('orderby', orderby);
      formData.append('order', order);
      formData.append('paged', nextPage);

      // Make AJAX request
      const response = await fetch(postListingAjax.ajaxUrl, {
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

        // Update paged attribute
        section.dataset.paged = nextPage;

        // Check if there are more posts
        const newLoadedPosts = nextPage * postsPerPage;
        if (newLoadedPosts >= totalPosts) {
          // Hide load more button
          hideLoadMoreButton(section);
        }
      } else if (data.data && data.data.message) {
        console.log(data.data.message);
        hideLoadMoreButton(section);
      }
    } catch (error) {
      console.error('Error loading more posts:', error);
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
    const grid = section.querySelector('.post-listing-grid');

    if (!grid) return;

    // Create a temporary container to parse HTML
    const tempContainer = document.createElement('div');
    tempContainer.innerHTML = html;

    // Get all new post items
    const newItems = tempContainer.querySelectorAll('.post-listing-item');

    // Add loading class initially
    newItems.forEach(item => {
      item.classList.add('is-loading');
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
    const buttonWrapper = section.querySelector('.load-more-button-wrapper');

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
    document.addEventListener('DOMContentLoaded', initPostListingSections);
  } else {
    initPostListingSections();
  }
})();
