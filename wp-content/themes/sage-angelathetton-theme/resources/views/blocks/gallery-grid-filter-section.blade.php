@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="gallery-grid-filter-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif data-block-id="{{ $blockId }}">
  <div class="container">
    {{-- Header with Filter and Refresh --}}
    <div class="gallery-grid-filter-section__header">
      <div class="gallery-grid-filter-section__filter">
        <span class="gallery-grid-filter-section__filter-label">{{ esc_html($filterByTitle) }} : </span>
        <div class="gallery-grid-filter-section__dropdown">
          <button type="button" class="gallery-grid-filter-section__dropdown-toggle" aria-expanded="false" aria-haspopup="listbox">
            <span class="gallery-grid-filter-section__dropdown-text">All</span>
            <img src="{{ Vite::asset('resources/images/bottom_black_arrow.svg') }}" alt="arrow-down" class="gallery-grid-filter-section__dropdown-icon">
          </button>
          <ul class="gallery-grid-filter-section__dropdown-menu" role="listbox">
            @foreach($categories as $value => $label)
              <li role="option" data-value="{{ $value }}" @if($value === 'all') aria-selected="true" class="is-selected" @endif>
                {{ $label }}
              </li>
            @endforeach
          </ul>
        </div>
      </div>
      <button type="button" class="gallery-grid-filter-section__refresh" aria-label="{{ esc_attr($refreshTitle) }}">
        {{ esc_html($refreshTitle) }}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="gallery-grid-filter-section__refresh-icon">
          <path d="M21 2v6h-6"></path>
          <path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path>
          <path d="M3 22v-6h6"></path>
          <path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path>
        </svg>
      </button>
    </div>

    {{-- Gallery Grid --}}
    <div class="gallery-grid-filter-section__grid">
      @if(!empty($galleryImages) && is_array($galleryImages))
        @php
          $totalImages = count($galleryImages);
          $imageIndex = 0;
          $rowNumber = 0;
        @endphp

        @while($imageIndex < $totalImages)
          @php
            $rowType = ($rowNumber % 2 === 0) ? 'A' : 'B';
            $rowNumber++;
          @endphp

          <div class="gallery-grid-filter-section__row gallery-grid-filter-section__row--type-{{ strtolower($rowType) }}">
            @if($rowType === 'A')
              {{-- Row Type A: Large image on left, 4 small images on right --}}
              {{-- Large Image --}}
              @if(isset($galleryImages[$imageIndex]))
                @php
                  $item = $galleryImages[$imageIndex];
                  $image = $item['image'] ?? null;
                  $category = $item['select_category'] ?? 'all';
                  $image_id = is_array($image) ? ($image['ID'] ?? null) : $image;
                  $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
                  $image_full = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
                  $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                  $imageIndex++;
                @endphp
                <div class="gallery-grid-filter-section__large" data-category="{{ esc_attr($category) }}">
                  <div class="gallery-grid-filter-section__item" data-full-image="{{ esc_url($image_full) }}">
                    @if($image_url)
                      <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" class="gallery-grid-filter-section__image" loading="lazy">
                    @else
                      <div class="gallery-grid-filter-section__placeholder">
                        <p>{{ __('No image', 'sage') }}</p>
                      </div>
                    @endif
                    <div class="gallery-grid-filter-section__overlay">
                      <img src="{{ Vite::asset('resources/images/plus_icon.svg') }}" alt="plus" class="gallery-grid-filter-section__icon">
                    </div>
                  </div>
                </div>
              @endif

              {{-- Small Grid (4 images) --}}
              <div class="gallery-grid-filter-section__small-grid">
                @for($i = 0; $i < 4; $i++)
                  @if(isset($galleryImages[$imageIndex]))
                    @php
                      $item = $galleryImages[$imageIndex];
                      $image = $item['image'] ?? null;
                      $category = $item['select_category'] ?? 'all';
                      $image_id = is_array($image) ? ($image['ID'] ?? null) : $image;
                      $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium_large') : '';
                      $image_full = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
                      $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                      $imageIndex++;
                    @endphp
                    <div class="gallery-grid-filter-section__small-item" data-category="{{ esc_attr($category) }}">
                      <div class="gallery-grid-filter-section__item" data-full-image="{{ esc_url($image_full) }}">
                        @if($image_url)
                          <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" class="gallery-grid-filter-section__image" loading="lazy">
                        @else
                          <div class="gallery-grid-filter-section__placeholder">
                            <p>{{ __('No image', 'sage') }}</p>
                          </div>
                        @endif
                        <div class="gallery-grid-filter-section__overlay">
                          <img src="{{ Vite::asset('resources/images/plus_icon.svg') }}" alt="plus" class="gallery-grid-filter-section__icon">
                        </div>
                      </div>
                    </div>
                  @endif
                @endfor
              </div>

            @else
              {{-- Row Type B: 4 small images on left, Large image on right --}}
              {{-- Small Grid (4 images) --}}
              <div class="gallery-grid-filter-section__small-grid">
                @for($i = 0; $i < 4; $i++)
                  @if(isset($galleryImages[$imageIndex]))
                    @php
                      $item = $galleryImages[$imageIndex];
                      $image = $item['image'] ?? null;
                      $category = $item['select_category'] ?? 'all';
                      $image_id = is_array($image) ? ($image['ID'] ?? null) : $image;
                      $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium_large') : '';
                      $image_full = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
                      $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                      $imageIndex++;
                    @endphp
                    <div class="gallery-grid-filter-section__small-item" data-category="{{ esc_attr($category) }}">
                      <div class="gallery-grid-filter-section__item" data-full-image="{{ esc_url($image_full) }}">
                        @if($image_url)
                          <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" class="gallery-grid-filter-section__image" loading="lazy">
                        @else
                          <div class="gallery-grid-filter-section__placeholder">
                            <p>{{ __('No image', 'sage') }}</p>
                          </div>
                        @endif
                        <div class="gallery-grid-filter-section__overlay">
                          <img src="{{ Vite::asset('resources/images/plus_icon.svg') }}" alt="plus" class="gallery-grid-filter-section__icon">
                        </div>
                      </div>
                    </div>
                  @endif
                @endfor
              </div>

              {{-- Large Image --}}
              @if(isset($galleryImages[$imageIndex]))
                @php
                  $item = $galleryImages[$imageIndex];
                  $image = $item['image'] ?? null;
                  $category = $item['select_category'] ?? 'all';
                  $image_id = is_array($image) ? ($image['ID'] ?? null) : $image;
                  $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
                  $image_full = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
                  $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                  $imageIndex++;
                @endphp
                <div class="gallery-grid-filter-section__large" data-category="{{ esc_attr($category) }}">
                  <div class="gallery-grid-filter-section__item" data-full-image="{{ esc_url($image_full) }}">
                    @if($image_url)
                      <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" class="gallery-grid-filter-section__image" loading="lazy">
                    @else
                      <div class="gallery-grid-filter-section__placeholder">
                        <p>{{ __('No image', 'sage') }}</p>
                      </div>
                    @endif
                    <div class="gallery-grid-filter-section__overlay">
                      <img src="{{ Vite::asset('resources/images/plus_icon.svg') }}" alt="plus" class="gallery-grid-filter-section__icon">
                    </div>
                  </div>
                </div>
              @endif
            @endif
          </div>
        @endwhile
      @else
        <div class="gallery-grid-filter-section__no-images">
          <p>{{ __('No gallery images found. Please add images in the block settings.', 'sage') }}</p>
        </div>
      @endif
    </div>

    {{-- Lightbox Modal --}}
    <div class="gallery-grid-filter-section__lightbox" aria-hidden="true" role="dialog" aria-modal="true">
      <div class="gallery-grid-filter-section__lightbox-overlay"></div>
      <div class="gallery-grid-filter-section__lightbox-content">
        <button type="button" class="gallery-grid-filter-section__lightbox-close" aria-label="{{ __('Close lightbox', 'sage') }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
        <button type="button" class="gallery-grid-filter-section__lightbox-prev" aria-label="{{ __('Previous image', 'sage') }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>
        <div class="gallery-grid-filter-section__lightbox-image-wrapper">
          <img src="" alt="" class="gallery-grid-filter-section__lightbox-image">
        </div>
        <button type="button" class="gallery-grid-filter-section__lightbox-next" aria-label="{{ __('Next image', 'sage') }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>
