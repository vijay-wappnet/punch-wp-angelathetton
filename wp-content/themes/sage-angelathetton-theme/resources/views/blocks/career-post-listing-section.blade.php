@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif

<section id="{{ $blockId }}" class="career-post-listing-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif
    data-post-type="{{ esc_attr($selectPostType) }}"
    data-posts-per-page="{{ esc_attr($postsPerPage) }}"
    data-orderby="{{ esc_attr($orderby) }}"
    data-order="{{ esc_attr($order) }}"
    data-paged="{{ esc_attr($currentPage) }}"
    data-total-posts="{{ esc_attr($totalPosts) }}">
  <div class="container">
    <div class="career-list">
      @if(!empty($posts))
        @foreach($posts as $post)
          @php
            $post_id = $post->ID;
            $post_title = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
            $post_link = get_permalink($post_id);
            $department_type = get_field('department_type', $post_id);
            $employment_type = get_field('employment_type', $post_id);
            $post_description = get_field('short_description', $post_id);
          @endphp

          <div class="career-item row align-items-center">
            <div class="col-12 col-md-10">
              <h2 class="career-title">{{ $post_title }}</h2>

              <h4 class="career-meta">
                <div>DEPARTMENT - {{ esc_html($department_type ?: 'N/A') }} <span>|</span> </div>
                <div>EMPLOYMENT - {{ esc_html($employment_type ?: 'N/A') }} </div>
              </h4>

              @if($post_description)
                <p class="career-description">{{ $post_description }}</p>
              @endif
            </div>

            <div class="col-12 col-md-2 text-md-end">
              <a
                 {{-- href="{{ esc_url($post_link) }}" --}}
                 href="{{ !empty($email) ? 'mailto:' . esc_attr($email) : '#' }}"
                 class="btn trans-black-btn career-btn"
                 aria-label="{{ esc_attr('Get in touch about ' . $post_title) }}"
                 data-event-label="Get in touch">
                {{ __('Get in touch', 'sage') }}
              </a>
            </div>
          </div>
        @endforeach
      @else
        <p class="no-posts-message">{{ __('No career posts found.', 'sage') }}</p>
      @endif
    </div>

    {{-- Desktop Pagination --}}
    @if(($maxNumPages ?? 1) > 1)
      <div class="career-pagination d-none d-md-flex">
        @if(!empty($prevPageUrl))
          <a href="{{ esc_url($prevPageUrl) }}" class="prev" aria-label="{{ esc_attr__('Previous page', 'sage') }}">
            <img src="{{ Vite::asset('resources/images/left_arrow.svg') }}" alt="{{ esc_attr__('Previous', 'sage') }}">
          </a>
        @else
          <span class="prev is-disabled" aria-hidden="true">
            <img src="{{ Vite::asset('resources/images/left_arrow.svg') }}" alt="">
          </span>
        @endif

        <span class="page-count">{{ (int) $currentPage }} / {{ (int) $maxNumPages }}</span>

        @if(!empty($nextPageUrl))
          <a href="{{ esc_url($nextPageUrl) }}" class="next" aria-label="{{ esc_attr__('Next page', 'sage') }}">
            <img src="{{ Vite::asset('resources/images/right-arrow.svg') }}" alt="{{ esc_attr__('Next', 'sage') }}">
          </a>
        @else
          <span class="next is-disabled" aria-hidden="true">
            <img src="{{ Vite::asset('resources/images/right-arrow.svg') }}" alt="">
          </span>
        @endif
      </div>
    @endif

    {{-- Mobile Load More Button --}}
    @if($totalPosts > $postsPerPage)
      <div class="load-more-button-wrapper d-md-none">
        <button type="button"
            class="btn trans-black-btn load-more-btn"
            data-block-id="{{ $blockId }}"
            aria-label="{{ esc_attr__('Load more careers', 'sage') }}"
            data-event-label="Load more">
            <span class="btn-text">{{ __('Load more', 'sage') }}</span>
            <span class="btn-loading" style="display: none;">
                <svg class="spinner" width="20" height="20" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                </svg>
                {{ __('Loading...', 'sage') }}
            </span>
        </button>
      </div>
    @endif
  </div>
</section>
