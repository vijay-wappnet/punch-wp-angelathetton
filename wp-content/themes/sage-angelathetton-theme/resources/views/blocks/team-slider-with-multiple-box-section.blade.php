@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="team-slider-with-multiple-box-section"
     data-left-arrow="{{ Vite::asset('resources/images/left_arrow.svg') }}"
     data-right-arrow="{{ Vite::asset('resources/images/right-arrow.svg') }}"
     @if($section_bg) style="background-color: {{ esc_attr($section_bg) }};" @endif>
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
              <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($slide['heading_text'] ?? 'Slide') }}" class="smb-item__image" width="{{ $slide['image']['width'] }}" height="{{ $slide['image']['height'] }}"/>
            @endif
          @endif


          {{-- Dynamic Buttons (used for both desktop and mobile reveal) --}}
          <div class="btn smb-item__buttons smb-item__reveal-btns">
            @if($slide['heading_text'])
                <div class="smb-item__heading-wrap">
                  <{{ $slide['heading_level'] }} class="smb-item__heading">
                    {{ $slide['heading_text'] }}
                  </{{ $slide['heading_level'] }}>
                </div>
              @endif
            @foreach($slide['button'] as $button)
              @php
                $button_title = $button['button_title'] ?? 'Discover More';
                $url = '#';
                $button_aria = $button['aria_label'] ?? '';
                $event_label = $button['button_google_event_label'] ?? '';
                $button_class = $button['button_class'] ?? '';
              @endphp
              <a href="{{ esc_url($url) }}"
                class="btn tswmbs-btn {{ esc_attr($button_class) }}"
                @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
                @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
                {{ esc_html($button_title) }}
              </a>
            @endforeach
          </div>

          {{-- Overlay Content --}}
          <div class="smb-item__overlay">
            <div class="smb-item__content">

              {{-- Main Heading --}} {{-- Always visible heading --}}
              @if($slide['heading_text'])
                <div class="smb-item__heading-wrap">
                  <{{ $slide['heading_level'] }} class="smb-item__heading">
                    {{ $slide['heading_text'] }}
                  </{{ $slide['heading_level'] }}>
                </div>
              @endif


              {{-- Hover / Active content --}}
              <div class="smb-item__hover-content">
                  {{-- Sub Heading --}}
                  @if($slide['sub_heading_text'])
                    <{{ $slide['sub_heading_level'] }} class="smb-item__subheading">
                      {{ $slide['sub_heading_text'] }}
                    </{{ $slide['sub_heading_level'] }}>
                  @endif

                  {{-- Description --}}
                  @if($slide['description'])
                    <p class="smb-item__description">
                      {{ $slide['description'] }}
                    </p>
                  @endif


                  {{-- Buttons removed from overlay --}}

                </div>

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
