<?php

namespace App\Blocks;

class FullWidthBannerImageSection
{
    public static function render($block, $is_preview = false, $post_id = 0)
    {
        $banner_image = get_field('banner_image') ?: '';
        $section_bg = get_field('section_bg') ?: '#ffffff';

        // Render the Blade template with data using view helper
        echo view('blocks.full-width-banner-image-section', [
            'banner_image' => $banner_image,
            'section_bg' => $section_bg,
            'is_preview' => $is_preview,
        ]);
    }
}
