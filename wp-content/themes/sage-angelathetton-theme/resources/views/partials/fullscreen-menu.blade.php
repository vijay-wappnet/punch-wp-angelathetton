<div id="fullscreen-menu" class="fullscreen-menu" aria-hidden="true">
  <!-- close button -->
  <button class="close-btn" aria-label="Close menu">
    <span class="close-icon">&times;</span>
  </button>

  @php
    // default image menu from nav_menu term (ACF)

    $locations = get_nav_menu_locations();
    if (isset($locations['primary_navigation'])) {
        $menu_id = $locations['primary_navigation'];
        $menu = wp_get_nav_menu_object($menu_id);

        $defaultImage = get_field('menu_default_image', $menu);
    }

    $defaultImageUrl = isset($defaultImage['url']) ? $defaultImage['url'] : '';
  @endphp

  @if (has_nav_menu('primary_navigation'))
    <nav class="fullscreen-nav" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}" role="navigation">
      <div class="menu-left">
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'menu_class'     => 'fullscreen-menu-list',
          'echo'           => false,
          'depth'          => 3,
          'container'      => false,
          'walker'         => new \App\MenuWalker(),
          'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
          'link_before'    => '<span role="menuitem">',
          'link_after'     => '</span>',
        ]) !!}
      </div>

      <div class="menu-right">
        <img id="menu-preview-image" src="{{ esc_url($defaultImageUrl) }}" alt="">
      </div>
    </nav>
  @endif

  @php
    // header bottom group for buttons link settings (ACF repeater)
    $button_link_group = get_field('header_bottom_buttons', 'option');

    // build HTML for one or more buttons
    $buttons_html = '';

    if ($button_link_group && is_array($button_link_group)) {
      $counter = 0;
      foreach ($button_link_group as $row) {
        $link_url     = home_url('/');
        $button_title = $row['button_title'] ?? '';
        $btn_link_type = $row['btn_link_type'] ?? '';
        $btn_link     = $row['btn_link'] ?? '';
        $link_target  = '';
        $aria_label   = $row['aria_label'] ?? '';
        $ga_label     = $row['button_google_event_label'] ?? '';

        $btn_title_trim = preg_split('/\s+/', trim($button_title));
        $btn_class = 'reserve-' . strtolower($btn_title_trim[count($btn_title_trim) - 1]) . '-btn';

        // determine url/target based on btn_link
        if ($btn_link) {
          if (is_array($btn_link)) {
            $link_url    = $btn_link['url'] ?? $link_url;
            $link_target = $btn_link['target'] ?? '';
          } else {
            $link_url = $btn_link;
          }

          if ($btn_link_type === 'external') {
            $link_target = $link_target ?: '_blank';
          }

          if ($btn_link_type === 'internal' && ! preg_match('#^https?://#i', $link_url)) {
            $link_url = home_url($link_url);
          }
        }

        if ($btn_link_type === 'none') {
          $link_url = '';
        }

        if (! $button_title) {
          $button_title = __('Home', 'sage');
        }
        if (! $aria_label) {
          $aria_label = $button_title ?: __('Home', 'sage');
        }

        if ($link_url) {
          $buttons_html .= '<a href="'.esc_url($link_url).'" class="btn hfsm-btn '.$btn_class.'" aria-label="'.esc_attr($aria_label).'"'.($link_target ? ' target="'.esc_attr($link_target).'"' : '').($ga_label ? ' data-event="'.esc_attr($ga_label).'"' : '').'>' . esc_html($button_title) . '</a>';
        } else {
          $buttons_html .= '<span class="btn btn-reserve-table-room" aria-label="'.esc_attr($aria_label).'">' . esc_html($button_title) . '</span>';
        }
        $counter ++;
      }
    }
  @endphp

  @if (! empty($buttons_html))
    <div class="menu-footer">
      {!! $buttons_html !!}
    </div>
  @endif

</div>
