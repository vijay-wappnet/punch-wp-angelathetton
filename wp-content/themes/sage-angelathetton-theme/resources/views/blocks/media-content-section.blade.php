<div class="media-content-section" @if($backgroundStyle) style="{{ $backgroundStyle }}" @endif>
  <div class="container">
    <div class="row align-items-center mcs-row">
      {{-- Image Column --}}
      <div class="col-12 col-lg-6 @if(!$showLeftImage) order-lg-2 @endif">
        <div class="media-content-section__image">
          @if($mediaImage)
            @php
              $image_id = is_array($mediaImage) ? $mediaImage['ID'] : $mediaImage;
              $image_url = wp_get_attachment_image_url($image_id, 'full');
              $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            @endphp
            @if($image_url)
              <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" loading="lazy">
            @endif
          @else
            <div class="media-content-section__image--placeholder">
              <p>{{ __('No image selected', 'sage') }}</p>
            </div>
          @endif
        </div>
      </div>

      {{-- Content Column --}}
      <div class="col-12 col-lg-6 @if(!$showLeftImage) order-lg-1 @endif">
        <div class="media-content-section__content">
          {{-- Heading --}}
          @if($title)
            <{{ $headingLevel }} class="media-content-section__title">
                {{ $title }}
            </{{ $headingLevel }}>
        @endif

          {{-- Body Content --}}
          @if($contentText)
            <div class="media-content-section__body">
              {!! wp_kses_post($contentText) !!}
            </div>
          @endif

          {{-- Buttons from Repeater --}}
          @if(is_array($buttons) && count($buttons) > 0)
            <div class="media-content-section__buttons">
              @foreach($buttons as $button)
                @php
                  $link = $button['button_link'] ?? [];
                  $url = is_array($link) ? ($link['url'] ?? '#') : $link;
                  $link_title = is_array($link) ? ($link['title'] ?? 'Button') : 'Button';
                  $button_aria = $button['aria_label'] ?? '';
                  $event_label = $button['button_google_event_label'] ?? '';
                  $button_class = $button['button_class'] ?? '';
                @endphp

                <a href="{{ esc_url($url) }}"
                   class="btn mcs-btn {{ esc_attr($button_class) }}"
                   @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
                   @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
                  {{ esc_html($link_title) }}
                </a>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
