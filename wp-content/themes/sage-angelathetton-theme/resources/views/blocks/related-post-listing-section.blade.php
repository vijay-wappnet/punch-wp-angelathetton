@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="related-post-listing-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
    <div class="container">
        {{-- Section Heading --}}
        @if($title)
            <div class="rpls__header">
                <{{ $headingLevel }} class="rpls__title">
                    {{ html_entity_decode($title, ENT_QUOTES, 'UTF-8') }}
                </{{ $headingLevel }}>
            </div>
        @endif

        {{-- Posts Grid --}}
        <div class="row rpls__grid">
            @if(!empty($posts))
                @foreach($posts as $post)
                    @php
                        $post_id = $post->ID;
                        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
                        $post_title = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
                        $post_link = get_permalink($post_id);
                        $post_description = \App\Blocks\RelatedPostListingSection::getPostDescription($post_id);
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 rpls__item">
                        <div class="rpls__card">
                            <div class="rpls__card-image">
                                @if($featured_image)
                                    <a href="{{ esc_url($post_link) }}" aria-label="{{ esc_attr($post_title) }}">
                                        <img src="{{ esc_url($featured_image) }}" alt="{{ esc_attr($post_title) }}" loading="lazy">
                                    </a>
                                @else
                                    <a href="{{ esc_url($post_link) }}" aria-label="{{ esc_attr($post_title) }}">
                                        <div class="rpls__card-image--placeholder">
                                            <span>{{ __('No Image', 'sage') }}</span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="rpls__card-body">
                                <h3 class="rpls__card-title">
                                    <a href="{{ esc_url($post_link) }}">{{ $post_title }}</a>
                                </h3>
                                @if($post_description)
                                    <p class="rpls__card-description">{{ $post_description }}</p>
                                @endif
                                <a href="{{ esc_url($post_link) }}" class="btn trans-black-btn rpls__card-button">
                                    {{ __('Discover More', 'sage') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="rpls__no-posts">{{ __('No posts found.', 'sage') }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
