@php
    $wrapper_style = $section_bg ? "background-color: " . esc_attr($section_bg) . ";" : '';
    $has_post = !empty($featured_post);
    $has_image = $has_post && !empty($featured_post->featured_image);
    $image_url = $has_image ? $featured_post->featured_image : '';

    // Button data (use ?: to handle empty strings from ACF)
    $button_text = !empty($button['button_text']) ? $button['button_text'] : __('Discover More', 'sage');
    $button_aria = !empty($button['aria_label']) ? $button['aria_label'] : __('Click AL - Discover More', 'sage');
    $button_event_label = !empty($button['button_google_event_label']) ? $button['button_google_event_label'] : __('Click GA - Discover More', 'sage');
@endphp

@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="featured-article-post-section{{ $is_preview ? ' is-preview' : '' }}"@if($wrapper_style) style="{{ $wrapper_style }}"@endif>
    @if($has_post)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="featured-article-post-section__wrapper" @if($has_image) style="background-image: url('{{ esc_url($image_url) }}');" @endif>
                    {{-- Overlay for better text readability --}}
                    <div class="featured-article-post-section__overlay"></div>

                    {{-- Content overlay --}}
                    <div class="featured-article-post-section__content">
                        {{-- Publish Date --}}
                        @if($featured_post->publish_date)
                            <span class="featured-article-post-section__date">
                                {{ $featured_post->publish_date }}
                            </span>
                        @endif

                        {{-- Post Title --}}
                        @if($featured_post->title)
                            <h2 class="featured-article-post-section__title">
                                {{ $featured_post->title }}
                            </h2>
                        @endif

                        {{-- Discover More Button --}}
                        <a href="{{ esc_url($featured_post->permalink) }}"
                           class="btn featured-article-post-section__btn"
                           @if($button_aria) aria-label="{{ esc_attr($button_aria) }}" @endif
                           @if($button_event_label) data-event-label="{{ esc_attr($button_event_label) }}" @endif>
                            {{ esc_html($button_text) }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        @if($is_preview)
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="featured-article-post-section__no-post">
                        <p>{{ __('No featured article found. Please set a post with "is_featured_article" field enabled.', 'sage') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</section>
