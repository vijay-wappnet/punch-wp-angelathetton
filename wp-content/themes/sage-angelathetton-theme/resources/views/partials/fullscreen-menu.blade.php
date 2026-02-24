<div id="fullscreen-menu" class="fullscreen-menu" aria-hidden="true">
  <!-- close button -->
  <button class="close-btn" aria-label="Close menu">
    <span class="close-icon">&times;</span>
  </button>

  <!-- overlay logo (mobile friendly) -->
  <div class="fullscreen-logo">
    <a class="brand" href="{{ home_url('/') }}">
      {!! $siteName !!}
    </a>
  </div>

  @if (has_nav_menu('primary_navigation'))
    <nav class="fullscreen-nav" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}" role="navigation">
      {!! wp_nav_menu([
        'theme_location' => 'primary_navigation',
        'menu_class'     => 'fullscreen-menu-list',
        'echo'           => false,
        'depth'          => 3,
        'container'      => false,
        'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
        'link_before'    => '<span role="menuitem">',
        'link_after'     => '</span>',
      ]) !!}
    </nav>
  @endif

  <div class="menu-footer">
    <a href="{{ esc_url( home_url('/reserve-table') ) }}" class="btn btn-outline reserve-table">Reserve a Table</a>
    <a href="{{ esc_url( home_url('/reserve-room') ) }}" class="btn btn-outline reserve-room">Reserve a Room</a>
  </div>
</div>
