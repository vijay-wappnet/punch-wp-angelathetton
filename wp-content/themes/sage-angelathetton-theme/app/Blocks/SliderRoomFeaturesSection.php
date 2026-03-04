<?php

namespace App\Blocks;

class SliderRoomFeaturesSection
{
    public static function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $slider_images = get_field('slider_images') ?: [];
        $heading_text = get_field('heading_text') ?: '';
        $heading_level = get_field('heading_level') ?: 'h2';
        $feature_title = get_field('feature_title') ?: '';
        $feature_contents = get_field('feature_contents') ?: [];
        $button = get_field('button') ?: [];
        $background_color = get_field('background_color') ?: '#ffffff';
        $margin = get_field('margin');
        $padding = get_field('padding');

        $block_id = 'slider-room-fs-' . ($block['id'] ?? uniqid());
        $responsive_css = custom_acf_dimensions($margin, $padding, $block_id);

        echo view('blocks.slider-room-features-section', [
            'block_id' => $block_id,
            'slider_images' => $slider_images,
            'heading_text' => $heading_text,
            'heading_level' => $heading_level,
            'feature_title' => $feature_title,
            'feature_contents' => $feature_contents,
            'button' => $button,
            'background_color' => $background_color,
            'responsive_css' => $responsive_css,
            'is_preview' => $is_preview,
        ]);
    }
}