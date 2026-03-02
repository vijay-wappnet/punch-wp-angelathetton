<?php

namespace App\Blocks;

class FullWidthBannerImageSection
{
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $banner_image = get_field('banner_image') ?: '';
        $section_bg = get_field('section_bg') ?: '#ffffff';

        $context = [
            'banner_image' => $banner_image,
            'section_bg' => $section_bg,
            'is_preview' => $is_preview,
        ];

        echo \App\template('blocks.full-width-banner-image-section', $context);
    }
}