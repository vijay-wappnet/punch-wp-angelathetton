@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="package-details-with-images-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
  <div class="container">
    {{-- Main Heading Row --}}
    @if($mainHeadingText)
    <div class="row">
      <div class="col-12">
        <{{ $mainHeadingLevel }} class="package-details-with-images-section__main-heading">
          {{ $mainHeadingText }}
        </{{ $mainHeadingLevel }}>
      </div>
    </div>
    @endif

    @php
      // Determine if both images are hidden on desktop/mobile
      $bothHiddenDesktop = $hideFirstImageDesktop && $hideSecondImageDesktop;
      $bothHiddenMobile = $hideFirstImageMobile && $hideSecondImageMobile;

      // Build images column classes
      $imagesColClasses = ['col-12'];
      if ($bothHiddenDesktop && $bothHiddenMobile) {
        $imagesColClasses[] = 'd-none';
      } elseif ($bothHiddenDesktop) {
        $imagesColClasses[] = 'd-md-none';
      } elseif ($bothHiddenMobile) {
        $imagesColClasses[] = 'd-none d-md-block col-md-8';
      } else {
        $imagesColClasses[] = 'col-md-8';
      }

      // Build content column classes
      $contentColClasses = ['col-12'];
      if ($bothHiddenDesktop) {
        $contentColClasses[] = 'col-md-12';
      } else {
        $contentColClasses[] = 'col-md-4';
      }
    @endphp
    <div class="row align-items-center pdwis-row">
      {{-- Images Column --}}
      <div class="{{ implode(' ', $imagesColClasses) }} @if(!$showLeftImage) order-md-2 @endif">
        <div class="package-details-with-images-section__images">
          {{-- First Image --}}
          @if($firstImage)
            @php
              $first_image_id = is_array($firstImage) ? $firstImage['ID'] : $firstImage;
              $first_image_url = wp_get_attachment_image_url($first_image_id, 'full');
              $first_image_alt = get_post_meta($first_image_id, '_wp_attachment_image_alt', true);

              // Build classes for first image visibility
              $firstImageClasses = ['package-details-with-images-section__image', 'package-details-with-images-section__image--first'];

              // Visibility logic: mobile < 768px, desktop >= 768px
              if ($hideFirstImageDesktop && $hideFirstImageMobile) {
                // Hidden on both
                $firstImageClasses[] = 'd-none';
              } elseif ($hideFirstImageDesktop) {
                // Hidden on desktop only (visible on mobile)
                $firstImageClasses[] = 'd-md-none';
              } elseif ($hideFirstImageMobile) {
                // Hidden on mobile only (visible on desktop)
                $firstImageClasses[] = 'd-none d-md-block';
              }
            @endphp
            @if($first_image_url)
              <div class="{{ implode(' ', $firstImageClasses) }}">
                <img src="{{ esc_url($first_image_url) }}" alt="{{ esc_attr($first_image_alt) }}" loading="lazy">
              </div>
            @endif
          @else
            <div class="package-details-with-images-section__image package-details-with-images-section__image--placeholder">
              <p>{{ __('No first image selected', 'sage') }}</p>
            </div>
          @endif

          {{-- Second Image --}}
          @if($secondImage)
            @php
              $second_image_id = is_array($secondImage) ? $secondImage['ID'] : $secondImage;
              $second_image_url = wp_get_attachment_image_url($second_image_id, 'full');
              $second_image_alt = get_post_meta($second_image_id, '_wp_attachment_image_alt', true);

              // Build classes for second image visibility
              $secondImageClasses = ['package-details-with-images-section__image', 'package-details-with-images-section__image--second'];

              // Visibility logic: mobile < 768px, desktop >= 768px
              if ($hideSecondImageDesktop && $hideSecondImageMobile) {
                // Hidden on both
                $secondImageClasses[] = 'd-none';
              } elseif ($hideSecondImageDesktop) {
                // Hidden on desktop only (visible on mobile)
                $secondImageClasses[] = 'd-md-none';
              } elseif ($hideSecondImageMobile) {
                // Hidden on mobile only (visible on desktop)
                $secondImageClasses[] = 'd-none d-md-block';
              }
            @endphp
            @if($second_image_url)
              <div class="{{ implode(' ', $secondImageClasses) }}">
                <img src="{{ esc_url($second_image_url) }}" alt="{{ esc_attr($second_image_alt) }}" loading="lazy">
              </div>
            @endif
          @else
            <div class="package-details-with-images-section__image package-details-with-images-section__image--placeholder">
              <p>{{ __('No second image selected', 'sage') }}</p>
            </div>
          @endif
        </div>
      </div>

      {{-- Content Column --}}
      <div class="col-12 col-md-4 @if(!$showLeftImage) order-md-1 @endif">
        <div class="package-details-with-images-section__contents">
          @if(is_array($contents) && count($contents) > 0)
            @foreach($contents as $index => $contentItem)
              <div class="package-details-with-images-section__content-item">
                {{-- Title --}}
                @if(!empty($contentItem['title']))
                  @php
                    $titleHeadingLevel = $contentItem['title_heading_level'] ?? 'h3';
                  @endphp
                  <{{ $titleHeadingLevel }} class="package-details-with-images-section__content-title">
                    {{ $contentItem['title'] }}
                  </{{ $titleHeadingLevel }}>
                @endif

                {{-- Description --}}
                @if(!empty($contentItem['description']))
                  <div class="package-details-with-images-section__content-description">
                    {!! wp_kses_post($contentItem['description']) !!}
                  </div>
                @endif

                {{-- Button --}}
                @if(!empty($contentItem['button']))
                  @php
                    $button = $contentItem['button'];
                    $link = $button['button_link'] ?? [];
                    $url = is_array($link) ? ($link['url'] ?? '#') : $link;
                    $target = is_array($link) ? ($link['target'] ?? '') : '';
                    $link_title = is_array($link) ? ($link['title'] ?? 'Button') : 'Button';
                    $button_aria = $button['aria_label'] ?? '';
                    $event_label = $button['button_google_event_label'] ?? '';
                    $button_class = $button['button_class'] ?? ''
                  @endphp
                  @if(!empty($url) && $url !== '#')
                    <div class="package-details-with-images-section__content-button">
                      <a href="{{ esc_url($url) }}"
                         target="{{ esc_attr($target) }}"
                         class="btn pdwis-btn {{ esc_attr($button_class) }}"
                         @if($button_aria)aria-label="{{ esc_attr($button_aria) }}"@endif
                         @if($event_label)data-event-label="{{ esc_attr($event_label) }}"@endif>
                        {{ esc_html($link_title) }}
                      </a>
                    </div>
                  @endif
                @endif
              </div>
            @endforeach
          @else
            <div class="package-details-with-images-section__content-placeholder">
              <p>{{ __('No content items added', 'sage') }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
