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
        $atts['id'] = 'AAAA----menu-item-' . esc_attr($item->ID);
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
