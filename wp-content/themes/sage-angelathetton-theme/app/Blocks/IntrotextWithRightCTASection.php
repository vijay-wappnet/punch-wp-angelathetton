<?php

namespace App\Blocks;

class IntrotextWithRightCTASection
{
    /**
     * Render the Introtext With Right CTA Section block
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
        $heading_text = get_field('heading_text');
        $heading_level = get_field('heading_level') ?: 'h2';
        $content = get_field('content');
        $bg_color = get_field('intro_section_bg');
        $button = get_field('button');
        $margin = get_field('margin');
        $padding = get_field('padding');
        $content_alignment = get_field('content_alignment') ?: 'left';

        // Generate unique block ID
        $blockId = 'itwrctas-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Render the Blade template with data using view helper
        echo view('blocks.introtext-with-right-cta-section', [
            'blockId'           => $blockId,
            'responsiveCss'     => $responsiveCss,
            'heading_text'      => $heading_text,
            'heading_level'     => $heading_level,
            'content'           => $content,
            'bg_color'          => $bg_color,
            'button'            => $button,
            'content_alignment' => $content_alignment,
            'is_preview'        => $is_preview,
        ]);
    }
}
