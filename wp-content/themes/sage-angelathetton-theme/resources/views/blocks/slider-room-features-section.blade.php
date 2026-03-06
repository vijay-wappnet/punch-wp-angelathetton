@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="slider-room-features-section" 
     data-left-arrow="{{ Vite::asset('resources/images/left_arrow.svg') }}"
     data-right-arrow="{{ Vite::asset('resources/images/right-arrow.svg') }}"
     style="background-color: {{ $background_color }};">
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
                @if ($button)
                    <a href="{{ $button['button_link']['url'] }}" class="btn srfs-btn {{ $button['button_class'] }}" aria-label="{{ $button['aria_label'] }}" data-ga-label="{{ $button['button_google_event_label'] }}">
                        {{ $button['button_link']['title'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
