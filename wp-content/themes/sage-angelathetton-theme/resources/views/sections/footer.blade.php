@php
use Illuminate\Support\Facades\Vite;
@endphp

<footer class="content-info site-footer" role="contentinfo">
  @php
    // Get contact details repeater field
    $contact_details = get_field('contact_details', 'option');
    // Get social media details repeater field
    $social_media_title = get_field('social_media_title', 'option');
    $social_media_details = get_field('social_media_details', 'option');
  @endphp

    <div class="footer-top">
      <div class="container">
        <div class="row justify-content-between">
          @php
              // Get social media title and details
              $social_media_title = get_field('social_media_title', 'option');
              $social_media_details = get_field('social_media_details', 'option');
          @endphp

          <div class="col-12 col-md-4 follow-and-social">
              @if ($social_media_title)
                  <h3 class="follow-title mb-0">{{ esc_html($social_media_title) }}</h3>
              @endif

              @if ($social_media_details)
                <div class="social-icons">
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

                        @if ($link_url && $title)
                            <a href="{{ esc_url($link_url) }}"
                              target="{{ esc_attr($link_target) }}"
                              aria-label="{{ esc_attr($aria_label) }}"
                              data-event="{{ esc_attr($data_event_label) }}"
                              class="social-link">
                                @if ($icon)
                                    <img src="{{ esc_url($icon['url']) }}" alt="{{ esc_attr($icon['alt']) }}" class="social-icon img-fluid">
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
              @endif
          </div>

          @php
              // Get social media images repeater field
              $social_media_images = get_field('social_media_images_temporary', 'option');
          @endphp

          @if ($social_media_images)
              <div class="col-12 col-md-8 footer-gallery">
                  <ul>
                      @foreach ($social_media_images as $item)
                          @php
                              $image = $item['image'] ?? '';
                              $image_url = $image['url'] ?? '';
                              $image_alt = $image['alt'] ?? '';
                          @endphp

                          @if ($image_url)
                              <li><img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($image_alt) }}"></li>
                          @endif
                      @endforeach
                  </ul>
              </div>
          @endif
        </div>
      </div>
    </div>

    <div class="footer-main">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-3 footer-logo">
            @php
              // logo image from options
              $footer_logo = get_field('footer_logo', 'option');
              // group for link settings
              $logo_link_group = get_field('footer_logo_link', 'option');

              // defaults
              $link_url    = home_url('/');
              $link_target = '';
              $aria_label  = '';
              $ga_label    = '';

              if ($logo_link_group) {
                  $link_type  = $logo_link_group['link_type'] ?? '';
                  $logo_link  = $logo_link_group['logo_link'] ?? '';
                  $aria_label = $logo_link_group['aria_label'] ?? '';
                  $ga_label   = $logo_link_group['button_google_event_label'] ?? '';

                  // determine url/target based on link_type
                  if ($logo_link) {
                      if (is_array($logo_link)) {
                          $link_url     = $logo_link['url'] ?? $link_url;
                          $link_target  = $logo_link['target'] ?? '';
                      } else {
                          $link_url = $logo_link;
                      }

                      if ($link_type === 'external') {
                          // force open in new tab for external links
                          $link_target = $link_target ?: '_blank';
                      }

                      if ($link_type === 'internal' && ! preg_match('#^https?://#i', $link_url)) {
                          // make sure internal paths are absolute
                          $link_url = home_url($link_url);
                      }
                  }

                  if ($link_type === 'none') {
                      // don't output an anchor
                      $link_url = '';
                  }
              }

              // fallback aria label
              if (! $aria_label) {
                  $aria_label = __('Home', 'sage');
              }
            @endphp

            @if ($footer_logo)
              @if ($link_url)
                  <a href="{{ esc_url($link_url) }}" class="footer-logo-link"
                    aria-label="{{ esc_attr($aria_label) }}"
                    {!! $link_target ? 'target="'.esc_attr($link_target).'"' : '' !!}
                    {!! $ga_label ? 'data-event="'.esc_attr($ga_label).'"' : '' !!}>
                      <img src="{{ esc_url($footer_logo['url']) }}"
                          alt="{{ esc_attr($footer_logo['alt']) }}"
                          class="img-fluid">
                  </a>
              @else
                  <span class="footer-logo-link" aria-label="{{ esc_attr($aria_label) }}">
                      <img src="{{ esc_url($footer_logo['url']) }}"
                          alt="{{ esc_attr($footer_logo['alt']) }}"
                          class="img-fluid">
                  </span>
              @endif
            @endif
          </div>

          @if ($contact_details)
              <div class="col-12 col-md-4 footer-contact">
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

                          <li class="fc-items">
                              @if ($link_url)
                                  <a href="{{ esc_url($link_url) }}"
                                    target="{{ esc_attr($link_target) }}"
                                    aria-label="{{ esc_attr($aria_label) }}"
                                    data-event="{{ esc_attr($data_event_label) }}">
                                      @if ($icon)
                                          <img src="{{ esc_url($icon['url']) }}" alt="{{ esc_attr($icon['alt']) }}" class="icon">
                                      @endif
                                      <div>{!! $title !!}</div>
                                  </a>
                              @else
                                  <span>
                                      @if ($icon)
                                          <img src="{{ esc_url($icon['url']) }}" alt="{{ esc_attr($icon['alt']) }}" class="icon">
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
                              <a href="{{ esc_url($link_url) }}"
                                target="{{ esc_attr($link_target) }}"
                                aria-label="{{ esc_attr($aria_label) }}"
                                data-event="{{ esc_attr($data_event_label) }}">
                                  <img src="{{ esc_url($icon['url']) }}" alt="{{ esc_attr($icon['alt']) }}" class="footer-social-icon">
                              </a>
                          @endif
                      @endforeach
                  </div>
              @endif
            </div>
          @endif

          <div class="col-12 col-md-5 footer-links">
            @if (has_nav_menu('footer_main_navigation'))
                <nav class="footer-main-nav" aria-label="{{ wp_get_nav_menu_name('footer_main_navigation') }}">
                  {!! wp_nav_menu([
                    'theme_location' => 'footer_main_navigation',
                    'menu_class'     => 'footer-main-menu nav',
                    'echo'           => false,
                  ]) !!}
                </nav>
              @endif
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <ul class="roses">
          <li>
            <img src="{{ Vite::asset('resources/images/flower-black.svg') }}" alt="flower" class="icon">
            <img src="{{ Vite::asset('resources/images/flower-red.svg') }}" alt="flower" class="icon">
            <img src="{{ Vite::asset('resources/images/flower-red.svg') }}" alt="flower" class="icon">
            <img src="{{ Vite::asset('resources/images/flower-red.svg') }}" alt="flower" class="icon">
            <img src="{{ Vite::asset('resources/images/flower-red.svg') }}" alt="flower" class="icon">
            <img src="{{ Vite::asset('resources/images/flower-red.svg') }}" alt="flower" class="icon">
          </li>
        </ul>
        <div class="footer-bottom-menu-col">
          <!-- footer bottom navigation -->
          @if (has_nav_menu('footer_bottom_navigation'))
            <nav class="footer-bottom-nav" aria-label="{{ wp_get_nav_menu_name('footer_bottom_navigation') }}">
              {!! wp_nav_menu([
                'theme_location' => 'footer_bottom_navigation',
                'menu_class'     => 'footer-bottom-menu nav',
                'echo'           => false,
              ]) !!}
            </nav>
          @endif
        </div>
        <div class="newsletter">Sign up to our newsletter</div>
      </div>
    </div>

</footer>
