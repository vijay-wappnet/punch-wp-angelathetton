@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $block_id }}" class="slider-room-features-section" style="background-color: {{ $background_color }};">
    <div class="container">
        <div class="slider-room-fs__wrapper row">
            <div class="slider-room-fs__slider col-12 col-md-8">
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
            <div class="slider-room-fs__content col-12 col-md-4">
                @if ($heading_text)
                    <<?= $heading_level ?> class="slider-room-fs__heading">{{ $heading_text }}</<?= $heading_level ?>>
                @endif
                @if ($feature_title)
                    <div class="slider-room-fs__feature-title">
                        {{ $feature_title }}
                        <span class="slider-room-fs__toggle">▼</span>
                    </div>
                    <ul class="slider-room-fs__features">
                        @foreach ($feature_contents as $feature)
                            <li class="slider-room-fs__feature-item">{{ $feature['content'] }}</li>
                        @endforeach
                    </ul>
                @endif
                @if ($button)
                    <a href="{{ $button['button_link']['url'] }}" class="slider-room-fs__button {{ $button['button_class'] }}" aria-label="{{ $button['aria_label'] }}" data-ga-label="{{ $button['button_google_event_label'] }}">
                        {{ $button['button_link']['title'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>