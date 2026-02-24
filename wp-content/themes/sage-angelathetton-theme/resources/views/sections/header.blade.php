<header id="site-header" class="site-header" role="banner">
  <div class="header-inner container-fluid">
    <!-- hamburger / menu toggle -->
    <button class="menu-toggle" aria-expanded="false" aria-controls="fullscreen-menu" aria-label="Open menu">
      <span class="hamburger">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </span>
    </button>

    <!-- logo centered -->
    <div class="logo-wrapper">
      <a class="brand" href="{{ home_url('/') }}">
        {!! $siteName !!} BBBBBBBBBBBB
      </a>
    </div>

    <!-- top navigation on right -->
    @if (has_nav_menu('header_top_navigation'))
      <nav class="header-top-nav" aria-label="{{ wp_get_nav_menu_name('header_top_navigation') }}">
        {!! wp_nav_menu([
          'theme_location' => 'header_top_navigation',
          'menu_class'     => 'nav',
          'echo'           => false,
        ]) !!}
      </nav>
    @endif
  </div>

  {{-- fullscreen overlay menu partial --}}
  @include('partials.fullscreen-menu')
</header>
