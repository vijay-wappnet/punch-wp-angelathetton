<div class="slider-with-multiple-box-section" @if($section_bg) style="background-color: {{ esc_attr($section_bg) }};" @endif>
  <div class="smb-item__wrapper">
    <div class="smb-item__slider">
      @forelse($slides as $slide)
        <div class="smb-item__slide">
          {{-- Background Image --}}
          @if(!empty($slide['image']))
            @php
              $image_url = is_array($slide['image']) ? ($slide['image']['url'] ?? '') : wp_get_attachment_image_url($slide['image'], 'full');
            @endphp
            @if($image_url)
              <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($slide['heading_text'] ?? 'Slide') }}" class="smb-item__image" />
            @endif
          @endif

          {{-- Overlay Content --}}
          <div class="smb-item__overlay">
            <div class="smb-item__content">
              {{-- Sub Heading --}}
              @if($slide['sub_heading_text'])
                <{{ $slide['sub_heading_level'] }} class="smb-item__subheading">
                  {{ $slide['sub_heading_text'] }}
                </{{ $slide['sub_heading_level'] }}>
              @endif

              {{-- Main Heading --}}
              @if($slide['heading_text'])
                <{{ $slide['heading_level'] }} class="smb-item__heading">
                  {{ $slide['heading_text'] }}
                </{{ $slide['heading_level'] }}>
              @endif

              {{-- Description --}}
              @if($slide['description'])
                <p class="smb-item__description">
                  {{ $slide['description'] }}
                </p>
              @endif

              {{-- Buttons --}}
              @if(count($slide['buttons']) > 0)
                <div class="smb-item__buttons">
                  @foreach($slide['buttons'] as $button)
                    @php
                      $link = $button['button_link'] ?? [];
                      $url = is_array($link) ? ($link['url'] ?? '#') : $link;
                      $link_title = is_array($link) ? ($link['title'] ?? 'Button') : 'Button';
                      $target = is_array($link) ? ($link['target'] ?? '') : '';
                      $button_aria = $button['aria_label'] ?? '';
                      $event_label = $button['button_google_event_label'] ?? '';
                      $button_class = $button['button_class'] ?? '';
                    @endphp

                    <a href="{{ esc_url($url) }}"
                      class="btn smb-item__button {{ esc_attr($button_class) }}"
                      @if($target)target="{{ esc_attr($target) }}"@endif
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
      @empty
        <div class="smb-item__slide">
          <div class="smb-item__overlay">
            <div class="smb-item__content">
              <p class="smb-item__description">{{ __('No slides added yet', 'sage') }}</p>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</div>
