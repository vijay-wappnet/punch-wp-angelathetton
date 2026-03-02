<?php

namespace App\Blocks;

class BannerImageSection
{
    /**
     * Render the Banner Image Section block
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
        $banner_image = get_field('banner_image') ?: '';
        $section_bg = get_field('section_bg') ?: '';

        // Render the Blade template with data using view helper
        echo view('blocks.banner-image-section', [
            'banner_image' => $banner_image,
            'section_bg'   => $section_bg,
            'is_preview'   => $is_preview,
        ]);
    }
}
