@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<div id="{{ $blockId }}" class="two-columns-image-cta-with-mobile-slider-section" @if($section_bg) style="background-color: {{ esc_attr($section_bg) }};" @endif>
    <div class="tcicwmss__wrapper">
      @forelse($columns as $column)
        <div class="tcicwmss__column">

          {{-- Main Image in full-width container --}}
          @if(!empty($column['image']))

              <div class="tcicwmss__image">
                @php
                  $image_id = is_array($column['image']) ? $column['image']['ID'] : $column['image'];
                  $image_url = wp_get_attachment_image_url($image_id, 'full');
                  $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                @endphp
                @if($image_url)
                  <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}" loading="lazy">
                @endif
              </div>

          @endif

          {{-- Content Area in standard container --}}

          <div class="tcicwmss__content">
            {{-- Heading --}}
            @if($column['heading_text'])
              @php
                $heading_tag = $column['heading_level'] ?? 'h2';
                $is_paragraph = $heading_tag === 'p';
              @endphp

              @if($is_paragraph)
                <p class="tcicwmss__heading">
                  {{ $column['heading_text'] }}
                </p>
              @else
                <{{ $heading_tag }} class="tcicwmss__heading">
                  {{ $column['heading_text'] }}
                </{{ $heading_tag }}>
              @endif
            @endif

            {{-- Description --}}
            @if($column['description'])
              <div class="tcicwmss__description">
                {!! wp_kses_post($column['description']) !!}
              </div>
            @endif

            {{-- Buttons --}}
            @if(count($column['buttons']) > 0)
              <div class="tcicwmss__buttons">
                @foreach($column['buttons'] as $button)
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
                    class="btn tcicwmss-btn {{ esc_attr($button_class) }}"
                    @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
                    @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif
                    {!! $target_attr !!}>
                    {{ esc_html($link_title) }}
                  </a>
                @endforeach
              </div>
            @endif

          </div>
        </div>
      @empty
        <div class="tcicwmss__content-placeholder">
          <p>{{ __('No content added yet.', 'sage') }}</p>
        </div>
      @endforelse
    </div>

</div>
