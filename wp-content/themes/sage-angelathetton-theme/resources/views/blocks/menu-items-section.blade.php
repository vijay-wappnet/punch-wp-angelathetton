@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="menu-items-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
  <div class="container">
    <div class="mis__wrapper">
      @forelse($menuItems as $index => $item)
        @php
          $heading_text = $item['heading_text'] ?? '';
          $heading_level = $item['heading_level'] ?? 'h3';
          $content = $item['content'] ?? '';
        @endphp

        <div class="mis__item">
          @if($heading_text)
            <{{ $heading_level }} class="mis__title">
              {{ $heading_text }}
            </{{ $heading_level }}>
          @endif

          @if($content)
            <div class="mis__content">
              {!! wp_kses_post($content) !!}
            </div>
          @endif
        </div>

        {{-- Divider after each item except the last --}}
        @if(!$loop->last)
          <div class="mis__divider"></div>
        @endif
      @empty
        <div class="mis__item--placeholder">
          <p>{{ __('No menu items added yet.', 'sage') }}</p>
        </div>
      @endforelse

      {{-- Button --}}
      @if(!empty($button) && !empty($button['button_link']))
        @php
          $link = $button['button_link'] ?? [];
          $url = is_array($link) ? ($link['url'] ?? '#') : $link;
          $link_title = is_array($link) ? ($link['title'] ?? 'Button') : 'Button';
          $target = is_array($link) ? ($link['target'] ?? '') : '';
          $button_aria = $button['aria_label'] ?? '';
          $event_label = $button['button_google_event_label'] ?? '';
          $button_class = $button['button_class'] ?? '';
        @endphp

        <div class="mis__button text-center">
          <a href="{{ esc_url($url) }}"
             class="btn mis-btn {{ esc_attr($button_class) }}"
             @if($target)target="{{ esc_attr($target) }}"@endif
             @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
             @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
            {{ esc_html($link_title) }}
          </a>
        </div>
      @endif
    </div>
  </div>
</section>
