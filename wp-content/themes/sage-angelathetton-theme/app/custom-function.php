<?php
if( function_exists ( 'acf_add_options_page' ) ) {
// Punch Theme General Settings
    $general_settings = array (
        'page_title' => __ ( 'Global Website Options', 'punch_theme' ),
        'menu_title' => __ ( 'Website Options', 'punch_theme' ),
        'menu_slug'  => 'punch_theme-general-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-customizer'
    );
    acf_add_options_page ( $general_settings );
}

// added dynamic
function add_dynamic_id_to_menu_links($atts, $item, $args, $depth) {
    // Apply only to the 'primary_navigation' menu
    if (in_array('menu-item-has-children', $item->classes)) {
        $atts['id'] = 'menu-item-' . esc_attr($item->ID);
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_dynamic_id_to_menu_links', 10, 4);


/* ====== Remove WordPress Version from Source Code ====== */
function remove_wp_version() {
    return '';
}
add_filter('the_generator', 'remove_wp_version');

function remove_wp_version_from_rss() {
    return '';
}
add_filter('the_generator', 'remove_wp_version_from_rss');

/**================ // Disable Plugin Update notification in admin panel ================
/**
 * Prevent update notification for plugin
 */
function disable_plugin_updates( $value ) {
    $pluginsToDisable = [
        'advanced-custom-fields-pro/acf.php'
    ];
    if ( isset($value) && is_object($value) ) {
        foreach ($pluginsToDisable as $plugin) {
            if ( isset( $value->response[$plugin] ) ) {
                unset( $value->response[$plugin] );
            }
        }
    }
    return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

// Secure SVG /Webp Uploads (Sanitize SVG Content)
function allow_svg_upload($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['webp'] = 'image/webp'; //
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

/**
 * Generate responsive CSS for ACF dimension fields (margin/padding)
 *
 * @param array $margin The margin field values with desktop, tablet, mobile breakpoints
 * @param array $padding The padding field values with desktop, tablet, mobile breakpoints
 * @param string $blockId The unique block ID for CSS selector
 * @return string The generated responsive CSS
 */
function custom_acf_dimensions($margin, $padding, $blockId) {
    // Helper function to format dimension values
    $formatDimension = function($values) {
        if (!is_array($values)) return '';
        $unit = $values['unit'] ?? 'px';
        $top = $values['top'] !== '' ? $values['top'] : null;
        $right = $values['right'] !== '' ? $values['right'] : null;
        $bottom = $values['bottom'] !== '' ? $values['bottom'] : null;
        $left = $values['left'] !== '' ? $values['left'] : null;
        // Only return if at least one value is set
        if ($top === null && $right === null && $bottom === null && $left === null) return '';
        return ($top ?? 0) . $unit . ' ' . ($right ?? 0) . $unit . ' ' . ($bottom ?? 0) . $unit . ' ' . ($left ?? 0) . $unit;
    };

    // Desktop styles (default)
    $desktopMargin = is_array($margin) && isset($margin['desktop']) ? $formatDimension($margin['desktop']) : '';
    $desktopPadding = is_array($padding) && isset($padding['desktop']) ? $formatDimension($padding['desktop']) : '';

    // Tablet styles (max-width: 991px)
    $tabletMargin = is_array($margin) && isset($margin['tablet']) ? $formatDimension($margin['tablet']) : '';
    $tabletPadding = is_array($padding) && isset($padding['tablet']) ? $formatDimension($padding['tablet']) : '';

    // Mobile styles (max-width: 767px)
    $mobileMargin = is_array($margin) && isset($margin['mobile']) ? $formatDimension($margin['mobile']) : '';
    $mobilePadding = is_array($padding) && isset($padding['mobile']) ? $formatDimension($padding['mobile']) : '';

    // Build CSS string with media queries
    $responsiveCss = '';

    // Desktop (base styles)
    $desktopRules = [];
    if (!empty($desktopMargin)) $desktopRules[] = 'margin: ' . $desktopMargin;
    if (!empty($desktopPadding)) $desktopRules[] = 'padding: ' . $desktopPadding;
    if (!empty($desktopRules)) {
        $responsiveCss .= '#' . $blockId . ' { ' . implode('; ', $desktopRules) . '; }';
    }

    // Tablet
    $tabletRules = [];
    if (!empty($tabletMargin)) $tabletRules[] = 'margin: ' . $tabletMargin;
    if (!empty($tabletPadding)) $tabletRules[] = 'padding: ' . $tabletPadding;
    if (!empty($tabletRules)) {
        $responsiveCss .= ' @media (max-width: 991px) { #' . $blockId . ' { ' . implode('; ', $tabletRules) . '; } }';
    }

    // Mobile
    $mobileRules = [];
    if (!empty($mobileMargin)) $mobileRules[] = 'margin: ' . $mobileMargin;
    if (!empty($mobilePadding)) $mobileRules[] = 'padding: ' . $mobilePadding;
    if (!empty($mobileRules)) {
        $responsiveCss .= ' @media (max-width: 767px) { #' . $blockId . ' { ' . implode('; ', $mobileRules) . '; } }';
    }

    return $responsiveCss;
}

/**
 * Enqueue Post Listing AJAX Scripts
 */
add_action('wp_enqueue_scripts', function() {
    // Only enqueue if Vite is available
    if (class_exists('Illuminate\Support\Facades\Vite')) {
        wp_localize_script('sage/app', 'postListingAjax', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('post_listing_ajax_nonce'),
        ]);
    }
});

/**
 * Add inline script for AJAX URL (fallback)
 */
add_action('wp_head', function() {
    ?>
    <script>
        var postListingAjax = {
            ajaxUrl: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            nonce: '<?php echo wp_create_nonce('post_listing_ajax_nonce'); ?>'
        };
        var careerListingAjax = {
            ajaxUrl: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            nonce: '<?php echo wp_create_nonce('career_listing_ajax_nonce'); ?>'
        };
    </script>
    <?php
});

/**
 * AJAX Handler for Load More Posts
 */
add_action('wp_ajax_load_more_posts', 'handle_load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'handle_load_more_posts');

function handle_load_more_posts() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'post_listing_ajax_nonce')) {
        wp_send_json_error(['message' => 'Invalid security token']);
        return;
    }

    // Allowed post types
    $allowed_post_types = ['post', 'package', 'product'];

    // Get parameters
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';

    // Validate post type against allowed values
    $post_type = in_array($post_type, $allowed_post_types) ? $post_type : 'post';

    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 3;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // Validate order direction
    $order = in_array(strtoupper($order), ['ASC', 'DESC']) ? strtoupper($order) : 'DESC';

    // Validate orderby
    $allowed_orderby = ['date', 'title', 'ID', 'modified', 'rand', 'menu_order'];
    $orderby = in_array($orderby, $allowed_orderby) ? $orderby : 'date';

    // Query posts
    $args = [
        'post_type'      => $post_type,
        'posts_per_page' => $posts_per_page,
        'orderby'        => $orderby,
        'order'          => $order,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ];

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        wp_send_json_error(['message' => 'No more posts found']);
        return;
    }

    // Build HTML output
    ob_start();

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
        $post_title = html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8');
        $post_link = get_permalink();
        $post_description = get_post_listing_description($post_id);
        ?>
        <div class="col-lg-4 col-md-6 col-12 post-listing-item">
            <div class="post-card">
                <div class="post-card__image">
                    <?php if ($featured_image) : ?>
                        <a href="<?php echo esc_url($post_link); ?>" aria-label="<?php echo esc_attr($post_title); ?>">
                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($post_title); ?>" loading="lazy">
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url($post_link); ?>" aria-label="<?php echo esc_attr($post_title); ?>">
                            <div class="post-card__image--placeholder">
                                <span><?php esc_html_e('No Image', 'sage'); ?></span>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="post-card__body">
                    <h3 class="post-card__title">
                        <a href="<?php echo esc_url($post_link); ?>"><?php echo esc_html($post_title); ?></a>
                    </h3>
                    <?php if ($post_description) : ?>
                        <p class="post-card__description"><?php echo esc_html($post_description); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo esc_url($post_link); ?>" class="btn trans-black-btn post-card__button plwas-btn">
                        <?php esc_html_e('Discover More', 'sage'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success([
        'html'      => $html,
        'max_pages' => $query->max_num_pages,
    ]);
}

/**
 * Get post description for listing
 *
 * @param int $post_id The post ID
 * @return string The post description
 */
function get_post_listing_description($post_id) {
    // First try to get excerpt
    $excerpt = get_the_excerpt($post_id);

    if (!empty($excerpt) && $excerpt !== '') {
        return wp_trim_words($excerpt, 20, '...');
    }

    // If no excerpt, try to get IntrotextSection content from post content
    $post = get_post($post_id);
    if ($post && has_blocks($post->post_content)) {
        $blocks = parse_blocks($post->post_content);
        foreach ($blocks as $block) {
            if ($block['blockName'] === 'acf/introtext-section') {
                // Get the content field from this block
                if (!empty($block['attrs']['data']['content'])) {
                    $content = $block['attrs']['data']['content'];
                    return wp_trim_words(wp_strip_all_tags($content), 20, '...');
                }
            }
        }
    }

    // Fallback to post content
    $content = get_the_content(null, false, $post_id);
    return wp_trim_words(wp_strip_all_tags($content), 20, '...');
}

/**
 * AJAX Handler for Load More Career Posts
 */
add_action('wp_ajax_load_more_career_posts', 'handle_load_more_career_posts');
add_action('wp_ajax_nopriv_load_more_career_posts', 'handle_load_more_career_posts');

function handle_load_more_career_posts() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'career_listing_ajax_nonce')) {
        wp_send_json_error(['message' => 'Invalid security token']);
        return;
    }

    // Allowed post types
    $allowed_post_types = ['career'];

    // Get parameters
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'career';

    // Validate post type against allowed values
    $post_type = in_array($post_type, $allowed_post_types) ? $post_type : 'career';

    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 4;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // Validate order direction
    $order = in_array(strtoupper($order), ['ASC', 'DESC']) ? strtoupper($order) : 'DESC';

    // Validate orderby
    $allowed_orderby = ['date', 'title', 'ID'];
    $orderby = in_array($orderby, $allowed_orderby) ? $orderby : 'date';

    // Query posts
    $args = [
        'post_type'      => $post_type,
        'posts_per_page' => $posts_per_page,
        'orderby'        => $orderby,
        'order'          => $order,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ];

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        wp_send_json_error(['message' => 'No more career posts found']);
        return;
    }

    // Get admin email
    $admin_email = get_option('admin_email');
    $email = is_email($admin_email) ? sanitize_email($admin_email) : '';

    // Build HTML output
    ob_start();

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $post_title = html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8');
        $post_link = get_permalink();
        $department_type = get_field('department_type', $post_id);
        $employment_type = get_field('employment_type', $post_id);
        $post_description = get_field('short_description', $post_id);
        ?>
        <div class="career-item row align-items-center">
            <div class="col-12 col-md-10">
                <h2 class="career-title"><?php echo esc_html($post_title); ?></h2>

                <h4 class="career-meta">
                    <div>DEPARTMENT - <?php echo esc_html($department_type ?: 'N/A'); ?> <span>|</span> </div>
                    <div>EMPLOYMENT - <?php echo esc_html($employment_type ?: 'N/A'); ?> </div>
                </h4>

                <?php if ($post_description) : ?>
                    <p class="career-description"><?php echo esc_html($post_description); ?></p>
                <?php endif; ?>
            </div>

            <div class="col-12 col-md-2 text-md-end">
                <a
                   href="<?php echo !empty($email) ? 'mailto:' . esc_attr($email) : '#'; ?>"
                   class="btn trans-black-btn career-btn"
                   aria-label="<?php echo esc_attr('Get in touch about ' . $post_title); ?>"
                   data-event-label="Get in touch">
                    <?php esc_html_e('Get in touch', 'sage'); ?>
                </a>
            </div>
        </div>
        <?php
    }

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success([
        'html'      => $html,
        'max_pages' => $query->max_num_pages,
    ]);
}
