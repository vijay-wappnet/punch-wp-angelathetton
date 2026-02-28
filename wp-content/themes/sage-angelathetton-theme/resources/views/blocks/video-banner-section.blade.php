@php
use Illuminate\Support\Facades\Vite;
@endphp

<div class="video-banner-section" @if($section_bg) style="--overlay-color: {{ esc_attr($section_bg) }};" @endif>

  <div class="video-banner-section__media">
    @if($video_file)
      <video
        class="video-banner-section__video"
        autoplay
        muted
        loop
        playsinline
        preload="auto">
        <source src="{{ esc_url($video_file) }}" type="video/mp4">
        @if($video_image)
          <img src="{{ esc_url($video_image) }}" alt="banner" class="video-banner-section__fallback-image">
        @endif
      </video>
    @elseif($video_image)
      <img src="{{ esc_url($video_image) }}" alt="banner" class="video-banner-section__image">
    @else
      <img src="{{ Vite::asset('resources/images/home-video.webp') }}" alt="home video banner" class="video-banner-section__fallback-image home-video-banner">
    @endif
  </div>

  @if($section_bg)
    <div class="video-banner-section__overlay"></div>
  @endif

  @if($heading_text)
    <div class="video-banner-section__heading-wrapper">
      <{{ $heading_level }} class="video-banner-section__heading">
        {{ esc_html($heading_text) }}
      </{{ $heading_level }}>
    </div>
  @endif

  <div class="video-banner-section__arrow" role="button" tabindex="0" aria-label="Scroll to next section">


    <img src="{{ Vite::asset('resources/images/arrow-down.svg') }}" alt="flower" class="icon">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M12 2V20M12 20L5 13M12 20L19 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>

</div>
