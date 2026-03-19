<?php

namespace App\Blocks;

class GoogleMapSection
{
    /**
     * Render the Google Map Section block
     *
     * @param array $block The block settings and attributes
     * @param string $content The block content
     * @param bool $is_preview Whether we are in preview mode
     * @param int $post_id The post ID
     * @return void
     */
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        // Get field values using ACF
        $google_map = get_field('google_map');
        $map_address = get_field('map_address'); // Text field for address (no API key needed)
        $aria_labelledby = get_field('aria_labelledby') ?: 'Location Map';
        $aria_describedby = get_field('aria_describedby') ?: 'map-description';
        $aria_description_content = get_field('aria_description_content') ?: 'Map showing the business location and surrounding area.';
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Debug: Log values for troubleshooting
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('GoogleMapSection Debug:');
            error_log('google_map: ' . print_r($google_map, true));
            error_log('map_address: ' . print_r($map_address, true));
        }

        // Generate unique block ID
        $blockId = 'gms-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build map URL - Priority: lat/lng coordinates, then address text field
        $map_url = '';
        $has_coords = !empty($google_map) && isset($google_map['lat']) && isset($google_map['lng']) && $google_map['lat'] !== '' && $google_map['lng'] !== '';
        $has_address = !empty($map_address) && trim($map_address) !== '';

        $q = '';
        if ($has_coords) {
            $lat = floatval($google_map['lat']);
            $lng = floatval($google_map['lng']);
            $q = $lat . ',' . $lng;
        } elseif ($has_address) {
            $q = trim($map_address);
        }

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('has_coords: ' . ($has_coords ? 'true' : 'false'));
            error_log('has_address: ' . ($has_address ? 'true' : 'false'));
            error_log('q: ' . $q);
        }

        if (!empty($q)) {
            $map_url = "https://www.google.com/maps/embed/v1/place?key=" . WP_GOOGLE_MAPS_API_KEY . "&q=" . urlencode($q) . "&zoom=15";
        } else {
            $map_url = '';
        }

        // Render the Blade template with data using view helper
        echo view('blocks.google-map-section', [
            'blockId'                  => $blockId,
            'responsiveCss'            => $responsiveCss,
            'google_map'               => $google_map,
            'map_url'                  => $map_url,
            'aria_labelledby'          => $aria_labelledby,
            'aria_describedby'         => $aria_describedby,
            'aria_description_content' => $aria_description_content,
            'is_preview'               => $is_preview,
        ]);
    }
}
