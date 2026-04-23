@php
    use Illuminate\Support\Facades\Vite;
@endphp

<header id="site-header" class="site-header" role="banner">
  <div class="header-inner container-fluid">
    <!-- hamburger / menu toggle -->
    <button class="menu-toggle" aria-expanded="false" aria-controls="fullscreen-menu" aria-label="Open menu">
      <div class="hamburger">
        <div class="d-flex align-items-center gap-4">
            <span class="bar">
                <img src="{{ Vite::asset('resources/images/menu-icon-white.svg') }}" alt="menu" class="icon menu-icon-white" />
                <img src="{{ Vite::asset('resources/images/menu-Icon-black.svg') }}" alt="menu" class="icon menu-icon-black" />
            </span>
            <p class="mb-0 pb-0">MENU</p>
        </div>
      </div>
    </button>

    <!-- logo centered -->
    <div class="logo-wrapper">
      @php
          // logo image from options
          $header_logo_black = get_field('header_logo', 'option');
          $header_logo_white = get_field('header_logo_white', 'option');
          $header_logo_for_transition = get_field('header_logo_for_transition', 'option');
          if ($header_logo_white) {
              $header_logo_white_url = esc_url($header_logo_white['url']);
              $header_logo_white_alt = esc_attr($header_logo_white['alt']);
          }
          if($header_logo_black) {
              $header_logo_black_url = esc_url($header_logo_black['url']);
              $header_logo_black_alt = esc_attr($header_logo_black['alt']);
          }
          // group for link settings
          $logo_link_group = get_field('header_logo_link', 'option');
          $siteName = get_bloginfo('name');

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

        @if ($header_logo_white || $header_logo_black)
            @if ($link_url)
              @if(!is_front_page())
                <a href="{{ esc_url($link_url) }}" class="brand-logo brand-logo-black"
                   aria-label="{{ esc_attr($aria_label) }}"
                   {!! $link_target ? 'target="'.esc_attr($link_target).'"' : '' !!}
                   {!! $ga_label ? 'data-event="'.esc_attr($ga_label).'"' : '' !!}
                   aria-hidden=""
                   >
                    <img src="{{ esc_url($header_logo_black['url']) }}"
                         alt="{{ esc_attr($header_logo_black['alt']) }}"
                         class="img-fluid">
                </a>
              @endif

              @if(is_front_page())
                <a href="{{ esc_url($link_url) }}" class="brand-logo brand-logo-white logo-link-first only-home-logo"
                  aria-label="{{ esc_attr($aria_label) }}"
                  {!! $link_target ? 'target="'.esc_attr($link_target).'"' : '' !!}
                  {!! $ga_label ? 'data-event="'.esc_attr($ga_label).'"' : '' !!}
                  aria-hidden=""
                  >
                    <img src="{{ esc_url($header_logo_white['url']) }}"
                        alt="{{ esc_attr($header_logo_white['alt']) }}"
                        class="img-fluid brand-logo-first">
                </a>
              @endif

              <a href="{{ esc_url($link_url) }}" class="brand-logo brand-logo-white logo-link-second"
              aria-label="{{ esc_attr($aria_label) }}"
              {!! $link_target ? 'target="'.esc_attr($link_target).'"' : '' !!}
              {!! $ga_label ? 'data-event="'.esc_attr($ga_label).'"' : '' !!}
              aria-hidden=""
              >
              <img src="{{ esc_url($header_logo_for_transition['url']) }}"
                    alt="{{ esc_attr($header_logo_for_transition['alt']) }}"
                    class="img-fluid brand-logo-second">
              </a>

            @else
                <span class="brand-logo" aria-label="{{ esc_attr($aria_label) }}">
                    <img src="{{ esc_url($header_logo_black['url']) }}"
                         alt="{{ esc_attr($header_logo_black['alt']) }}"
                         class="img-fluid">
                </span>
            @endif
        @else
            @if ($link_url)
                <a href="{{ esc_url($link_url) }}" class="brand-logo img-fluid" aria-label="{{ esc_attr($aria_label) }}">
                    {!! $siteName !!}
                </a>
            @else
                <span class="img-fluid" aria-label="{{ esc_attr($aria_label) }}">
                    {!! $siteName !!}
                </span>
            @endif
        @endif
    </div>

    @if (has_nav_menu('header_top_navigation'))
      <nav class="header-top-nav" aria-label="{{ wp_get_nav_menu_name('header_top_navigation') }}">
        {!! wp_nav_menu([
          'theme_location' => 'header_top_navigation',
          'menu_class'     => 'header-top-menu nav',
          'echo'           => false,
          'walker'         => app(\App\HeaderTopMenuWalker::class),
        ]) !!}
      </nav>
    @endif
  </div>

  {{-- fullscreen overlay menu partial --}}
  @include('partials.fullscreen-menu')
</header>
