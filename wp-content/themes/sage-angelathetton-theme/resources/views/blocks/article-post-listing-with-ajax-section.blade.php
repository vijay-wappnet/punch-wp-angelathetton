@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="article-post-listing-with-ajax-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif
    data-post-type="{{ esc_attr($selectPostType) }}"
    data-posts-per-page="{{ esc_attr($postsPerPage) }}"
    data-posts-per-page-mobile="{{ esc_attr($postsPerPageMobile) }}"
    data-orderby="{{ esc_attr($orderby) }}"
    data-order="{{ esc_attr($order) }}"
    data-paged="1"
    data-total-posts="{{ esc_attr($totalPosts) }}">
    <div class="container">
        {{-- Posts Grid --}}
        <div class="row article-post-listing-grid">
            @if(!empty($posts))
                @foreach($posts as $post)
                    @php
                        $post_id = $post->ID;
                        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
                        $post_title = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
                        $post_link = get_permalink($post_id);
                        $post_date = \App\Blocks\ArticlePostListingWithAjaxSection::getFormattedDate($post_id);
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 article-post-listing-item">
                        <div class="article-post-card">
                            <div class="article-post-card__image">
                                @if($featured_image)
                                    <a href="{{ esc_url($post_link) }}" aria-label="{{ esc_attr($post_title) }}">
                                        <img src="{{ esc_url($featured_image) }}" alt="{{ esc_attr($post_title) }}" loading="lazy">
                                    </a>
                                @else
                                    <a href="{{ esc_url($post_link) }}" aria-label="{{ esc_attr($post_title) }}">
                                        <div class="article-post-card__image--placeholder">
                                            <span>{{ __('No Image', 'sage') }}</span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="article-post-card__body">
                                <span class="article-post-card__date">{{ $post_date }}</span>
                                <h3 class="article-post-card__title">
                                    <a href="{{ esc_url($post_link) }}">{{ $post_title }}</a>
                                </h3>
                                <a href="{{ esc_url($post_link) }}" class="btn trans-black-btn article-post-card__button aplwas-btn">
                                    {{ __('Discover More', 'sage') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="no-posts-message">{{ __('No posts found.', 'sage') }}</p>
                </div>
            @endif
        </div>

        {{-- Load More Button --}}
        @if($hasMorePosts)
            @php
                $buttonTitle = $loadMoreButton['button_title'] ?? 'Load More';
                $ariaLabel = $loadMoreButton['aria_label'] ?? '';
                $eventLabel = $loadMoreButton['button_google_event_label'] ?? '';
                $buttonClass = $loadMoreButton['button_class'] ?? 'trans-black-btn';
            @endphp
            <div class="article-load-more-button-wrapper">
                <button type="button"
                    class="btn {{ esc_attr($buttonClass) }} article-load-more-btn"
                    data-block-id="{{ $blockId }}"
                    @if($ariaLabel)aria-label="{{ esc_attr($ariaLabel) }}"@endif
                    @if($eventLabel)data-event-label="{{ esc_attr($eventLabel) }}"@endif>
                    <span class="btn-text">{{ esc_html($buttonTitle) }}</span>
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
