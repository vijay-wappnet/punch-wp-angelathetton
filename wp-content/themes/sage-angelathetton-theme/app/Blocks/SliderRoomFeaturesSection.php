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
        $other_content = get_field('other_content');
        $backgroundColor = get_field('background_color');
        $backgroundImage = get_field('background_image');
        $margin = get_field('margin');
        $padding = get_field('padding');

        $blockId = 'slider-room-fs-' . ($block['id'] ?? uniqid());
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build background style like TopBottomMediaContentSection
        $backgroundStyle = [];

        // Always add background color if selected
        if (!empty($backgroundColor)) {
            $backgroundStyle[] = 'background-color: ' . esc_attr($backgroundColor);
        }

        // Always add background image if selected
        if (!empty($backgroundImage['url'])) {
            $backgroundStyle[] = 'background-image: url("' . esc_url($backgroundImage['url']) . '")';
            $backgroundStyle[] = 'background-size: cover';
            $backgroundStyle[] = 'background-position: center';
            $backgroundStyle[] = 'background-repeat: no-repeat';
        }

        // Convert array to inline style string
        $backgroundStyle = !empty($backgroundStyle) ? implode('; ', $backgroundStyle) . ';' : '';

        echo view('blocks.slider-room-features-section', [
            'blockId' => $blockId,
            'slider_images' => $slider_images,
            'heading_text' => $heading_text,
            'heading_level' => $heading_level,
            'feature_title' => $feature_title,
            'feature_contents' => $feature_contents,
            'button' => $button,
            'other_content' => $other_content,
            'backgroundStyle' => $backgroundStyle,
            'responsiveCss' => $responsiveCss,
            'is_preview' => $is_preview,
        ]);
    }
}
