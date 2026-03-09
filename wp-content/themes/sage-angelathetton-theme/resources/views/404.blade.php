@extends('layouts.app')

@section('content')
  @php
    // Get ACF fields from options page
    $heading = get_field('404_page_heading', 'option') ?: __('APOLOGIES, WE NO LONGER HAVE THAT!', 'sage');
    $heading_level = get_field('404_heading_level', 'option') ?: 'h1';
    $background_image = get_field('404_background_image', 'option');
    $background_image_mobile = get_field('404_background_image_mobile', 'option');
    $button_group = get_field('404_button', 'option');

    // Generate unique section ID
    $sectionId = 'error-404-' . uniqid();

    // Build responsive background CSS (desktop only)
    $responsiveBgCss = '';

    // Desktop background image
    if (!empty($background_image['url'])) {
      $responsiveBgCss .= '#' . $sectionId . ' { ';
      $responsiveBgCss .= 'background-image: url("' . esc_url($background_image['url']) . '"); ';
      $responsiveBgCss .= 'background-size: cover; ';
      $responsiveBgCss .= 'background-position: center; ';
      $responsiveBgCss .= 'background-repeat: no-repeat; ';
      $responsiveBgCss .= '} ';
    }

    // Hide desktop background on mobile
    $responsiveBgCss .= '@media (max-width: 767px) { ';
    $responsiveBgCss .= '#' . $sectionId . ' { ';
    $responsiveBgCss .= 'background-image: none;padding-top:100px; ';
    $responsiveBgCss .= '} ';
    $responsiveBgCss .= '} ';

    // Button data
    $button_link = $button_group['button_link'] ?? [];
    $button_url = is_array($button_link) ? ($button_link['url'] ?? home_url('/')) : home_url('/');
    $button_title = is_array($button_link) ? ($button_link['title'] ?? __('Return to Home', 'sage')) : __('Return to Home', 'sage');
    $button_target = is_array($button_link) ? ($button_link['target'] ?? '') : '';
    $aria_label = $button_group['aria_label'] ?? '';
    $event_label = $button_group['button_google_event_label'] ?? '';

    // Mobile image data
    $mobile_image_url = $background_image_mobile['url'] ?? '';
    $mobile_image_alt = $background_image_mobile['alt'] ?? __('404 Page', 'sage');
  @endphp

  @if(!empty($responsiveBgCss))
    <style>{!! $responsiveBgCss !!}</style>
  @endif

  <section id="{{ $sectionId }}" class="error-404-page">
    {{-- Mobile Background Image (visible only on mobile) --}}
    @if(!empty($mobile_image_url))
      <div class="error-404-page__mobile-bg d-md-none">
        <img src="{{ esc_url($mobile_image_url) }}" alt="{{ esc_attr($mobile_image_alt) }}" class="error-404-page__mobile-img">
      </div>
    @endif

    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-12">
          <div class="error-404-page__content">
            {{-- 404 Heading --}}
            @if($heading)
              <{{ $heading_level }} class="error-404-page__heading">
                {{ $heading }}
              </{{ $heading_level }}>
            @endif

            {{-- Return to Home Button --}}
            @if(!empty($button_url))
              <div class="error-404-page__button-wrapper">
                <a href="{{ esc_url($button_url) }}"
                   class="btn return-home-btn"
                   @if($aria_label) aria-label="{{ esc_attr($aria_label) }}" @endif
                   @if($event_label) data-event-label="{{ esc_attr($event_label) }}" @endif
                   @if($button_target) target="{{ esc_attr($button_target) }}" @endif>
                  {{ esc_html($button_title) }}
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
