@if(!empty($responsiveCss))
<style>{{ $responsiveCss }}</style>
@endif
<section id="{{ $blockId }}" class="contact-details-with-image-section" @if(!empty($backgroundStyle)) style="{{ $backgroundStyle }}" @endif>
  <div class="container">
    <div class="row cdwis-row">
      {{-- Address Column (col-md-4) --}}
      <div class="col-12 col-md-4">
        <div class="contact-details-with-image-section__address">
          {{-- Address Heading --}}
          @if($addressHeadingText)
            <{{ $addressHeadingLevel }} class="contact-details-with-image-section__heading">
              {{ $addressHeadingText }}
            </{{ $addressHeadingLevel }}>
          @endif

          {{-- Address Contact Details Repeater --}}
          @if(is_array($addressContactDetails) && count($addressContactDetails) > 0)
            <ul class="contact-details-with-image-section__list">
              @foreach($addressContactDetails as $index => $contact)
                @php
                  $icon = $contact['icon'] ?? '';
                  $title = $contact['title'] ?? '';
                  $link = $contact['link'] ?? '';
                  $linkTargetNewTab = $contact['link_target_new_tab'] ?? false;
                  $ariaLabel = $contact['aria_label'] ?? '';
                  $dataEventLabel = $contact['data_event_label'] ?? '';

                  $linkUrl = '';
                  $linkTarget = '';

                  if (is_array($link)) {
                    $linkUrl = $link['url'] ?? '';
                  } elseif (is_string($link)) {
                    $linkUrl = $link;
                  }

                  if ($linkTargetNewTab) {
                    $linkTarget = '_blank';
                  }

                  // Get icon URL
                  $iconUrl = '';
                  $iconAlt = '';
                  if ($icon) {
                    $iconId = is_array($icon) ? $icon['ID'] : $icon;
                    $iconUrl = wp_get_attachment_image_url($iconId, 'thumbnail');
                    $iconAlt = is_array($icon) ? ($icon['alt'] ?? '') : get_post_meta($iconId, '_wp_attachment_image_alt', true);
                  }
                @endphp

                <li class="contact-details-with-image-section__list-item {{ $loop->first ? 'cdwis-location-item' : '' }}">
                  @if($linkUrl)
                    <a href="{{ esc_url($linkUrl) }}"
                       class="contact-details-with-image-section__link"
                       @if($linkTarget) target="{{ esc_attr($linkTarget) }}" rel="noopener noreferrer" @endif
                       @if($ariaLabel) aria-label="{{ esc_attr($ariaLabel) }}" @endif
                       @if($dataEventLabel) data-event="{{ esc_attr($dataEventLabel) }}" @endif>
                      @if($iconUrl)
                        <span class="contact-details-with-image-section__icon">
                          <img src="{{ esc_url($iconUrl) }}" alt="{{ esc_attr($iconAlt) }}" loading="lazy">
                        </span>
                      @endif
                      @if($title)
                        <span class="contact-details-with-image-section__title">{!! $title !!}</span>
                      @endif
                    </a>
                  @else
                    <div class="contact-details-with-image-section__item-content">
                      @if($iconUrl)
                        <span class="contact-details-with-image-section__icon">
                          <img src="{{ esc_url($iconUrl) }}" alt="{{ esc_attr($iconAlt) }}" loading="lazy">
                        </span>
                      @endif
                      @if($title)
                        <span class="contact-details-with-image-section__title">{!! $title !!}</span>
                      @endif
                    </div>
                  @endif
                </li>
              @endforeach
            </ul>
          @else
            <div class="contact-details-with-image-section__placeholder">
              <p>{{ __('No contact details added', 'sage') }}</p>
            </div>
          @endif
        </div>
      </div>

      {{-- Opening Times Column (col-md-5) --}}
      <div class="col-12 col-md-5">
        <div class="contact-details-with-image-section__opening">
          {{-- Opening Heading --}}
          @if($openingHeadingText)
            <{{ $openingHeadingLevel }} class="contact-details-with-image-section__heading">
              {{ $openingHeadingText }}
            </{{ $openingHeadingLevel }}>
          @endif

          {{-- Opening Contents --}}
          @if($openingContents)
            <div class="contact-details-with-image-section__opening-content">
              {!! wp_kses_post($openingContents) !!}
            </div>
          @else
            <div class="contact-details-with-image-section__placeholder">
              <p>{{ __('No opening times content added', 'sage') }}</p>
            </div>
          @endif
        </div>
      </div>

      {{-- Image Column (col-md-3) --}}
      <div class="col-12 col-md-3 {{ !$showContactImageInMobile ? 'd-none d-md-block' : '' }}">
        <div class="contact-details-with-image-section__image">
          @if($contactImage)
            @php
              $imageId = is_array($contactImage) ? $contactImage['ID'] : $contactImage;
              $imageUrl = wp_get_attachment_image_url($imageId, 'large');
              $imageAlt = is_array($contactImage) ? ($contactImage['alt'] ?? '') : get_post_meta($imageId, '_wp_attachment_image_alt', true);
            @endphp
            @if($imageUrl)
              <img src="{{ esc_url($imageUrl) }}" alt="{{ esc_attr($imageAlt) }}" loading="lazy">
            @endif
          @else
            <div class="contact-details-with-image-section__image-placeholder">
              <p>{{ __('No image selected', 'sage') }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
