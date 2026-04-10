{{--
WooCommerce Single Product - Related Products Section
Reusable partial for displaying related products
--}}

@php
  // Get ACF fields
  $title = get_field('related_product_title');
  $heading_level = get_field('related_product_title_heading_level') ?: 'h2';
  $description = get_field('related_product_short_description');
  $related_products = get_field('related_products');
  $bg_color = get_field('related_products_section_bg');
  $margin = get_field('related_products_margin');
  $padding = get_field('related_products_padding');

  // Generate unique section ID
  $sectionId = 'woo-srps-' . uniqid();

  // Generate responsive CSS for margin and padding
  $responsiveCss = custom_acf_dimensions($margin, $padding, $sectionId);

  // Mobile limit for initial display
  $mobileLimit = 3;
  $totalProducts = is_array($related_products) ? count($related_products) : 0;
  $hasMoreProducts = $totalProducts > $mobileLimit;
@endphp

@if($title || $description || !empty($related_products))
  @if(!empty($responsiveCss))
    <style>{{ $responsiveCss }}</style>
  @endif

  <section
    id="{{ $sectionId }}"
    class="woo-single-related-products-section"
    @if($bg_color) style="background-color: {{ esc_attr($bg_color) }};" @endif
    data-mobile-limit="{{ $mobileLimit }}"
  >
    <div class="container">
      {{-- Header Row --}}
      @if($title || $description)
        <div class="row">
          <div class="col-12 woo-single-related-products-section__header">
            {{-- Title --}}
            @if($title)
              <{{ $heading_level }} class="woo-single-related-products-section__title">
                {{ $title }}
              </{{ $heading_level }}>
            @endif

            {{-- Description --}}
            @if($description)
              <p class="woo-single-related-products-section__description">
                {{ $description }}
              </p>
            @endif
          </div>
        </div>
      @endif

      {{-- Products Grid --}}
      @if(!empty($related_products))
        <div class="row woo-single-related-products-section__grid">
          @foreach($related_products as $index => $product)
            @php
              $product_id = $product->ID;
              $product_obj = wc_get_product($product_id);
              $featured_image = get_the_post_thumbnail_url($product_id, 'large');
              $product_title = html_entity_decode(get_the_title($product_id), ENT_QUOTES, 'UTF-8');
              $product_link = get_permalink($product_id);

              // Get product short description or excerpt
              $product_description = '';
              if ($product_obj) {
                $product_description = $product_obj->get_short_description();
              }
              if (empty($product_description)) {
                $product_description = get_the_excerpt($product_id);
              }
              if (empty($product_description)) {
                $content = get_the_content(null, false, $product_id);
                $product_description = wp_trim_words(wp_strip_all_tags($content), 20, '...');
              } else {
                $product_description = wp_trim_words(wp_strip_all_tags($product_description), 20, '...');
              }

              // Mobile hidden class for products beyond mobile limit
              $mobileHiddenClass = ($index >= $mobileLimit) ? 'mobile-hidden' : '';
            @endphp
            <div class="col-lg-3 col-md-6 col-12 woo-single-related-products-section__item {{ $mobileHiddenClass }}">
              <div class="woo-single-related-products-section__card">
                <div class="woo-single-related-products-section__image">
                  @if($featured_image)
                    <a href="{{ esc_url($product_link) }}" aria-label="{{ esc_attr($product_title) }}">
                      <img src="{{ esc_url($featured_image) }}" alt="{{ esc_attr($product_title) }}" loading="lazy">
                    </a>
                  @else
                    <a href="{{ esc_url($product_link) }}" aria-label="{{ esc_attr($product_title) }}">
                      <div class="woo-single-related-products-section__image--placeholder">
                        <span>{{ __('No Image', 'sage') }}</span>
                      </div>
                    </a>
                  @endif
                </div>
                <div class="woo-single-related-products-section__content">
                  <h3 class="woo-single-related-products-section__card-title">
                    <a href="{{ esc_url($product_link) }}">{{ $product_title }}</a>
                  </h3>
                  @if($product_description)
                    <p class="woo-single-related-products-section__card-description">{{ $product_description }}</p>
                  @endif
                  <a href="{{ esc_url($product_link) }}"
                     class="btn trans-black-btn wsrps-btn"
                     aria-label="{{ esc_attr($product_title) }}"
                     data-event-label="related-product">
                    {{ __('Discover More', 'sage') }}
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- Load More Button (Mobile Only) --}}
        @if($hasMoreProducts)
          <div class="row">
            <div class="col-12 woo-single-related-products-section__load-more-wrapper">
              <button type="button"
                class="btn trans-black-btn woo-single-related-products-section__load-more"
                data-section-id="{{ $sectionId }}"
                aria-label="{{ __('Load more related products', 'sage') }}"
                data-event-label="load-more-related-products">
                <span class="btn-text">{{ __('Load More', 'sage') }}</span>
                <span class="btn-loading" style="display: none;">
                  <svg class="spinner" width="20" height="20" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                  </svg>
                  {{ __('Loading...', 'sage') }}
                </span>
              </button>
            </div>
          </div>
        @endif
      @endif
    </div>
  </section>

  {{-- Load More Script --}}
  @if($hasMoreProducts)
    <script>
      (function() {
        document.addEventListener('DOMContentLoaded', function() {
          var section = document.getElementById('{{ $sectionId }}');
          if (!section) return;

          var loadMoreBtn = section.querySelector('.woo-single-related-products-section__load-more');
          if (!loadMoreBtn) return;

          loadMoreBtn.addEventListener('click', function() {
            var btn = this;
            var btnText = btn.querySelector('.btn-text');
            var btnLoading = btn.querySelector('.btn-loading');

            // Show loading state
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
            btn.disabled = true;

            // Simulate loading delay for UX
            setTimeout(function() {
              // Show all hidden products
              var hiddenItems = section.querySelectorAll('.woo-single-related-products-section__item.mobile-hidden');
              hiddenItems.forEach(function(item, index) {
                item.classList.remove('mobile-hidden');
                item.classList.add('is-loading');

                // Stagger animation
                setTimeout(function() {
                  item.classList.remove('is-loading');
                  item.classList.add('is-loaded');
                }, index * 100);
              });

              // Hide the load more button wrapper
              var loadMoreWrapper = section.querySelector('.woo-single-related-products-section__load-more-wrapper');
              if (loadMoreWrapper) {
                loadMoreWrapper.style.display = 'none';
              }
            }, 500);
          });
        });
      })();
    </script>
  @endif
@endif
