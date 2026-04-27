@if(!empty($responsiveCss))
  <style>
    {{ $responsiveCss }}
  </style>
@endif
@php
  // Get contact details repeater field
  $contact_details = get_field('contact_details', 'option');
  // Get social media details repeater field
  $social_media_title = get_field('social_media_title', 'option');
  $social_media_details = get_field('social_media_details', 'option');
@endphp

<section id="{{ $blockId }}" class="contact-details-with-image-section" @if(!empty($backgroundStyle))
style="{{ $backgroundStyle }}" @endif>
  <div class="container">
    <div class="row cdwis-row">
      {{-- Address Column (col-lg-4) --}}
      @if ($contact_details)
        <div class="col-12 col-lg-4 footer-contact">
          <div class="contact-details-with-image-section__address">
            {{-- Address Heading --}}
            @if($addressHeadingText)
              <{{ $addressHeadingLevel }} class="contact-details-with-image-section__heading">
                {{ $addressHeadingText }}
              </{{ $addressHeadingLevel }}>
            @endif

            {{-- Contact Details List --}}
            <ul>
              @foreach ($contact_details as $contact)
                @php
                  $icon = $contact['icon'] ?? '';
                  $title = $contact['title'] ?? '';
                  $link = $contact['link'] ?? '';
                  $link_target_new_tab = $contact['link_target_new_tab'] ?? false;
                  $data_event_label = $contact['data_event_label'] ?? '';
                  $aria_label = $contact['aria_label'] ?? '';

                  $link_url = $link['url'] ?? '';
                  $link_target = $link_target_new_tab ? '_blank' : '';
                @endphp

                <li class="fc-items {{ $loop->first ? 'fc-location-item' : '' }}">
                  @if ($link_url)
                    <a href="{{ esc_url($link_url) }}" target="{{ esc_attr($link_target) }}"
                      aria-label="{{ esc_attr($aria_label) }}" data-event="{{ esc_attr($data_event_label) }}">
                      @if ($icon)
                        <img src="{{ esc_url($icon['url']) }}" alt="{{ esc_attr($icon['alt']) }}" class="icon" width="24"
                          height="24">
                      @endif
                      <div>{!! $title !!}</div>
                    </a>
                  @else
                    <span>
                      @if ($icon)
                        <img src="{{ esc_url($icon['url']) }}" alt="{{ esc_attr($icon['alt']) }}" class="icon" width="24"
                          height="24">
                      @endif
                      <div>{!! $title !!}</div>
                    </span>
                  @endif
                </li>
              @endforeach
            </ul>

            @if ($social_media_details)
              <div class="footer-social-icon-box">
                @foreach ($social_media_details as $social)
                  @php
                    $icon = $social['icon'] ?? '';
                    $title = $social['title'] ?? '';
                    $link = $social['link'] ?? '';
                    $data_event_label = $social['data_event_label'] ?? '';
                    $aria_label = $social['aria_label'] ?? '';

                    $link_url = $link['url'] ?? '';
                    $link_target = $link['target'] ?? '_self';
                  @endphp

                  @if ($link_url && $icon)
                    <a href="{{ esc_url($link_url) }}" target="{{ esc_attr($link_target) }}"
                      aria-label="{{ esc_attr($aria_label) }}" data-event="{{ esc_attr($data_event_label) }}">
                      <img src="{{ esc_url($icon['url']) }}" alt="{{ esc_attr($icon['alt']) }}" class="footer-social-icon"
                        width="24" height="24">
                    </a>
                  @endif
                @endforeach
              </div>
            @endif
          </div>
        </div>
      @endif

      {{-- Opening Times Column (col-lg-5) --}}
      <div class="col-12 col-lg-5">
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

      {{-- Image Column (col-lg-3) --}}
      <div class="col-12 col-lg-3 {{ !$showContactImageInMobile ? 'd-none d-md-block' : '' }}">
        <div class="contact-details-with-image-section__image">
          @if($contactImage)
            @php
              $imageId = is_array($contactImage) ? $contactImage['ID'] : $contactImage;
              $imageUrl = wp_get_attachment_image_url($imageId, 'large');
              $imageAlt = is_array($contactImage) ? ($contactImage['alt'] ?? '') : get_post_meta($imageId, '_wp_attachment_image_alt', true);

              $metadata = wp_get_attachment_metadata($imageId);
              $width = $metadata['width'] ?? '';
              $height = $metadata['height'] ?? '';
            @endphp
            @if($imageUrl)
              <img src="{{ esc_url($imageUrl) }}" alt="{{ esc_attr($imageAlt) }}" loading="lazy" width="{{ $width }}"
                height=" {{ $height }}">
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