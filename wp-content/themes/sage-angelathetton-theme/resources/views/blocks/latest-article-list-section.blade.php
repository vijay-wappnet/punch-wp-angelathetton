@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="latest-article-list-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
    <div class="container">
        {{-- Section Heading --}}
        @if($title)
            <div class="lals__header">
                <{{ $headingLevel }} class="lals__title">
                    {{ html_entity_decode($title, ENT_QUOTES, 'UTF-8') }}
                </{{ $headingLevel }}>
                @if(!empty($see_all_articles_button['button_link']['url']) && !empty($see_all_articles_button['button_text']))
                    <a
                        href="{{ esc_url($see_all_articles_button['button_link']['url']) }}"
                        class="btn trans-black-btn lals__see-all-button{{ !empty($see_all_articles_button['button_class']) ? ' ' . esc_attr($see_all_articles_button['button_class']) : '' }}"
                        target="{{ esc_attr($see_all_articles_button['button_link']['target'] ?? '_self') }}"
                        @if(!empty($see_all_articles_button['aria_label'])) aria-label="{{ esc_attr($see_all_articles_button['aria_label']) }}" @endif
                        @if(!empty($see_all_articles_button['button_google_event_label'])) data-google-event-label="{{ esc_attr($see_all_articles_button['button_google_event_label']) }}" @endif
                    >
                        {{ esc_html($see_all_articles_button['button_text']) }}
                    </a>
                @endif
            </div>
        @endif

        {{-- Posts Grid --}}
        <div class="row lals__grid">
            @if(!empty($posts))
                @foreach($posts as $post)
                    @php
                        $post_id = $post->ID;
                        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
                        $post_title = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
                        $post_link = get_permalink($post_id);
                        $post_description = \App\Blocks\LatestArticleListSection::getPostDescription($post_id);
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 lals__item">
                        <div class="lals__card">
                            <div class="lals__card-image">
                                @if($featured_image)
                                    <a href="{{ esc_url($post_link) }}" aria-label="{{ esc_attr($post_title) }}">
                                        <img src="{{ esc_url($featured_image) }}" alt="{{ esc_attr($post_title) }}" loading="lazy">
                                    </a>
                                @else
                                    <a href="{{ esc_url($post_link) }}" aria-label="{{ esc_attr($post_title) }}">
                                        <div class="lals__card-image--placeholder">
                                            <span>{{ __('No Image', 'sage') }}</span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="lals__card-body">
                              <p class="lals__card-date">
                                {{ \App\Blocks\LatestArticleListSection::getPostPublishDate($post_id) }}
                              </p>
                              <h3 class="lals__card-title">
                                <a href="{{ esc_url($post_link) }}">{{ $post_title }}</a>
                              </h3>
                              <a href="{{ esc_url($post_link) }}" class="btn trans-black-btn lals__card-button">
                                {{ __('Discover More', 'sage') }}
                              </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="lals__no-posts">{{ __('No posts found.', 'sage') }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
