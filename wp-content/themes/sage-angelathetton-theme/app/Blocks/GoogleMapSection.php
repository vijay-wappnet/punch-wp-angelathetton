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

        // Generate unique block ID
        $blockId = 'gms-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build map URL - Priority: lat/lng coordinates, then address text field
        $map_url = '';
        
        // Option 1: Use coordinates if available (requires API key for ACF Google Map field)
        if (!empty($google_map['lat']) && !empty($google_map['lng'])) {
            $lat = floatval($google_map['lat']);
            $lng = floatval($google_map['lng']);
            $map_url = "https://maps.google.com/maps?q={$lat},{$lng}&z=15&output=embed";
        }
        // Option 2: Use address text field (NO API key needed)
        elseif (!empty($map_address)) {
            $encoded_address = urlencode($map_address);
            $map_url = "https://maps.google.com/maps?q={$encoded_address}&z=15&output=embed";
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
