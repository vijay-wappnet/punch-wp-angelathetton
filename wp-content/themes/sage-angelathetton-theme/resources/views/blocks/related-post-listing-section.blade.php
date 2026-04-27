@if(!empty($responsiveCss))
    <style>
        {{ $responsiveCss }}
    </style>
@endif
@php
    use Illuminate\Support\Facades\Vite;
@endphp
<section id="{{ $blockId }}" class="related-post-listing-section" @if(!empty($backgroundStyle))
style="{{ $backgroundStyle }}" @endif>
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

                        $image_id = get_post_thumbnail_id($post_id);
                        $image_url = wp_get_attachment_url($image_id);
                        $image_size = 'large';
                        $image_attributes = wp_get_attachment_image_src($image_id, $image_size);
                        $post_image_width = !empty($image_attributes[1]) ? $image_attributes[1] : '';
                        $post_image_height = !empty($image_attributes[2]) ? $image_attributes[2] : '';

                        $post_title = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
                        $post_link = get_permalink($post_id);
                        $post_description = \App\Blocks\RelatedPostListingSection::getPostDescription($post_id);
                    @endphp
                    <div class="col-lg-4 col-md-6 col-12 rpls__item">
                        <a href="{{ esc_url($post_link) }}" aria-label="{{ esc_attr($post_title) }}"
                            data-event-label="Related package">
                            <div class="rpls__card">
                                <div class="rpls__card-image">
                                    @if($featured_image)
                                        <img src="{{ esc_url($featured_image) }}" alt="{{ esc_attr($post_title) }}" loading="lazy"
                                            width="{{ $post_image_width }}" height="{{ $post_image_height }}">
                                    @else
                                        <div class="rpls__card-image--placeholder">
                                            <img src="{{ Vite::asset('resources/images/package-placeholder-image.webp') }}"
                                                alt="{{ esc_attr($post_title) }}" loading="lazy" width="369" height="402" />
                                        </div>
                                    @endif
                                </div>
                                <div class="rpls__card-body">
                                    <h3 class="rpls__card-title">
                                        {{ $post_title }}
                                    </h3>
                                    @if($post_description)
                                        <p class="rpls__card-description">{{ $post_description }}</p>
                                    @endif
                                    <span class="btn trans-black-btn rpls__card-button">
                                        {{ __('Discover More', 'sage') }}
                                    </span>
                                </div>
                            </div>
                        </a>
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