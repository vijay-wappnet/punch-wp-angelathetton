@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="top-bottom-media-content-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
  <div class="container">
    @if($showTopImage)
      {{-- Media Section (Top) --}}
      <div class="tbmc__media tbmc__media--top">
        @if($mediaImage)
          @php
            $image_id = is_array($mediaImage) ? $mediaImage['ID'] : $mediaImage;
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

            // Get image data (url, width, height)
            $imageData = $image_id ? wp_get_attachment_image_src($image_id, 'full') : null;
            $image_width  = $imageData[1] ?? '';
            $image_height = $imageData[2] ?? '';

          @endphp
          @if($image_url)
            <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" loading="lazy" width="{{ esc_attr($image_width) }}" height="{{ esc_attr($image_height) }}">
          @endif
        @else
          <div class="tbmc__media--placeholder">
            <p>{{ __('No image selected', 'sage') }}</p>
          </div>
        @endif
      </div>

      {{-- Content Section (Bottom) --}}
      <div class="tbmc__content">
        @if($title)
          <{{ $headingLevel }} class="tbmc__title">
            {{ $title }}
          </{{ $headingLevel }}>
        @endif

        @if($contentText)
          <div class="tbmc__description">
            {!! wp_kses_post($contentText) !!}
          </div>
        @endif

        @if(is_array($buttons) && count($buttons) > 0)
          <div class="tbmc__buttons">
            @foreach($buttons as $button)
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
                 class="btn tbmc-btn {{ esc_attr($button_class) }}"
                 @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
                 @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
                {{ esc_html($link_title) }}
              </a>
            @endforeach
          </div>
        @endif
      </div>
    @else
      {{-- Content Section (Top) --}}
      <div class="tbmc__content">
        @if($title)
          <{{ $headingLevel }} class="tbmc__title">
            {{ $title }}
          </{{ $headingLevel }}>
        @endif

        @if($contentText)
          <div class="tbmc__description">
            {!! wp_kses_post($contentText) !!}
          </div>
        @endif

        @if(is_array($buttons) && count($buttons) > 0)
          <div class="tbmc__buttons">
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
                 class="btn tbmc-btn {{ esc_attr($button_class) }}"
                 @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
                 @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
                {{ esc_html($link_title) }}
              </a>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Media Section (Bottom) --}}
      <div class="tbmc__media tbmc__media--bottom">
        @if($mediaImage)
          @php
            $image_id = is_array($mediaImage) ? $mediaImage['ID'] : $mediaImage;
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
          @endphp
          @if($image_url)
            <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" class="img-fluid" loading="lazy">
          @endif
        @else
          <div class="tbmc__media--placeholder">
            <p>{{ __('No image selected', 'sage') }}</p>
          </div>
        @endif
      </div>
    @endif
  </div>
</div>
