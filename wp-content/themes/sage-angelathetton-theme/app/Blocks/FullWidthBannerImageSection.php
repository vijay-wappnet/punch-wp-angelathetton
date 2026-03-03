<?php

namespace App\Blocks;

class FullWidthBannerImageSection
{
    public static function render($block, $is_preview = false, $post_id = 0)
    {
        $banner_image = get_field('banner_image') ?: '';
        $section_bg = get_field('section_bg') ?: '#ffffff';
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'fwbis-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Render the Blade template with data using view helper
        echo view('blocks.full-width-banner-image-section', [
            'blockId'       => $blockId,
            'responsiveCss' => $responsiveCss,
            'banner_image'  => $banner_image,
            'section_bg'    => $section_bg,
            'is_preview'    => $is_preview,
        ]);
    }
}
