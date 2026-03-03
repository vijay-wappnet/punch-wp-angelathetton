<?php

namespace App\Blocks;

class TopBottomMediaContentSection
{
    /**
     * Render the Top Bottom Media Content Section block
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
        $showTopImage = get_field('show_top_side_image') ?? true;
        $mediaImage = get_field('media_image');
        $title = get_field('title') ?? '';
        $headingLevel = get_field('heading_level') ?: 'h2';
        $contentText = get_field('content') ?? '';
        $buttons = get_field('intro_section_button');
        $backgroundImage = get_field('background_image');
        $backgroundColor = get_field('background_color');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'tbmcs-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

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

        // Validate button structure
        if (!is_array($buttons)) {
            $buttons = [];
        }

        // Render the Blade template with data
        echo view('blocks.top-bottom-media-content-section', [
            'block'             => $block,
            'blockId'           => $blockId,
            'showTopImage'      => $showTopImage,
            'mediaImage'        => $mediaImage,
            'title'             => $title,
            'headingLevel'      => $headingLevel,
            'contentText'       => $contentText,
            'buttons'           => $buttons,
            'backgroundStyle'   => $backgroundStyle,
            'responsiveCss'     => $responsiveCss,
            'isPreview'         => $is_preview,
        ]);
    }
}
