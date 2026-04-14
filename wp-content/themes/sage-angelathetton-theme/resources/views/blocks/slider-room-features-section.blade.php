@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="slider-room-features-section"
     data-left-arrow="{{ Vite::asset('resources/images/left_arrow.svg') }}"
     data-right-arrow="{{ Vite::asset('resources/images/right-arrow.svg') }}"
     @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
    <div class="container">
        <div class="slider-room-fs__wrapper">
            <div class="slider-room-fs__slider">
                @if ($slider_images)
                    <div class="slider-room-fs__slider-wrapper">
                        @foreach ($slider_images as $slide)
                            <div class="slider-room-fs__slide">
                                <img class="slider-room-fs__image" src="{{ $slide['image']['url'] }}" alt="{{ $slide['image']['alt'] }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="slider-room-fs__content">
                @if ($heading_text)
                    <<?= $heading_level ?> class="slider-room-fs__heading">{{ $heading_text }}</<?= $heading_level ?>>
                @endif
                @if ($short_description)
                    <p class="slider-room-fs__short_description">{{ $short_description }}</p>
                @endif
                @if ($feature_title)
                  <div class="slider-room-fs__features-wrapper">
                    <div class="slider-room-fs__feature-title">
                        {{ $feature_title }}
                        <span class="slider-room-fs__toggle">
                          <img src="{{ Vite::asset('resources/images/bottom_arrow.svg') }}" alt="arrow" class="icon">
                        </span>
                    </div>
                    <ul class="slider-room-fs__features">
                        @foreach ($feature_contents as $feature)
                            <li class="slider-room-fs__feature-item">{{ $feature['content'] }}</li>
                        @endforeach
                    </ul>
                  </div>
                @endif

                {{-- Content/Body --}}
                @if($other_content)
                    <div class="slider-room-fs__othercontent">
                        {!! wp_kses_post($other_content) !!}
                    </div>
                @endif

                @if ($button)
                @php
                  $link = $button['button_link'] ?? [];
                  $url = is_array($link) ? ($link['url'] ?? '#') : $link;
                  $target = is_array($link) ? ($link['target'] ?? '') : '';
                  $link_title = is_array($link) ? ($link['title'] ?? 'Button') : 'Button';
                  $button_aria = $button['aria_label'] ?? '';
                  $event_label = $button['button_google_event_label'] ?? '';
                  $button_class = $button['button_class'] ?? '';
                  $target_attr = $target ? 'target="' . esc_attr($target) . '"' : '';
                @endphp
                    <a href="{{ esc_url($url) }}"
                        @if($target_attr)
                          {{ $target_attr }}
                        @endif
                      class="btn srfs-btn {{ esc_attr($button_class) }}"
                      @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
                      @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
                      {{ esc_html($link_title) }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
