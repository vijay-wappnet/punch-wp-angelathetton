@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="introtext-with-right-cta-section" @if($bg_color) style="background-color: {{ esc_attr($bg_color) }};" @endif>

  <div class="container">
    <div class="row align-items-center">
      
      <div class="col-12 border-top-initialize"></div>

      <div class="col-12 {{ $button ? 'col-md-10' : 'col-md-12' }} introtext-with-right-cta-section__content-wrapper" style="text-align: {{ esc_attr($content_alignment) }};">

        {{-- Heading --}}
        @if($heading_text)
            <{{ $heading_level }} class="introtext-with-right-cta-section__heading">
                {{ $heading_text }}
            </{{ $heading_level }}>
        @endif

        {{-- Content/Body --}}
        @if($content)
            <div class="introtext-with-right-cta-section__content">
                {!! wp_kses_post($content) !!}
            </div>
        @endif

      </div>

      {{-- Button --}}
      @if($button)
        <div class="col-12 col-md-2 introtext-with-right-cta-section__button-wrapper">
          @php
            $link = $button['button_link'] ?? [];
            $url = is_array($link) ? ($link['url'] ?? '#') : $link;
            $link_title = is_array($link) ? ($link['title'] ?? 'Button') : 'Button';
            $button_aria = $button['aria_label'] ?? '';
            $event_label = $button['button_google_event_label'] ?? '';
            $button_class = $button['button_class'] ?? '';
          @endphp

          <a href="{{ esc_url($url) }}"
            class="btn itwrctas-btn {{ esc_attr($button_class) }}"
            @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
            @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
              {{ esc_html($link_title) }}
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
