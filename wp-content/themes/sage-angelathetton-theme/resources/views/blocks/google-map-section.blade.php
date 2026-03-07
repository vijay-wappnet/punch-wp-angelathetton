@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="google-map-section">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="gms__wrapper">
                    {{-- Accessibility: Hidden label for screen readers --}}
                    <h2 id="{{ esc_attr($blockId) }}-label" class="visually-hidden">
                        {{ esc_html($aria_labelledby) }}
                    </h2>

                    {{-- Accessibility: Hidden description for screen readers --}}
                    <p id="{{ esc_attr($blockId) }}-description" class="visually-hidden">
                        {{ esc_html($aria_description_content) }}
                    </p>

                    {{-- Google Map Embed --}}
                    <div class="gms__map ratio ratio-21x9">
                        @if($map_url)
                            <iframe
                                class="gms__iframe"
                                src="{{ esc_url($map_url) }}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen=""
                                aria-labelledby="{{ esc_attr($blockId) }}-label"
                                aria-describedby="{{ esc_attr($blockId) }}-description"
                                title="{{ esc_attr($aria_labelledby) }}">
                            </iframe>
                        @else
                            <div class="gms__placeholder">
                                <p>{{ __('Please configure Google Map coordinates in the block settings.', 'sage') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
