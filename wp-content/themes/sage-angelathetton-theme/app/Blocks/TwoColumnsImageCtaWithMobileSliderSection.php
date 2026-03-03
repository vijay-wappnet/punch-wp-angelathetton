<?php

namespace App\Blocks;

class TwoColumnsImageCtaWithMobileSliderSection
{
    /**
     * Render the Two Columns Image CTA With Mobile Slider Section block
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
        $main_title_heading = get_field('main_title_heading') ?? '';
        $main_heading_level = get_field('main_heading_level') ?: 'h2';
        $image_contents_cta = get_field('image_contents_cta') ?? [];
        $section_bg = get_field('section_bg') ?? '';

        // Validate repeater structure
        if (!is_array($image_contents_cta)) {
            $image_contents_cta = [];
        }

        // Process each row in the repeater
        $columns = [];
        foreach ($image_contents_cta as $item) {
            $buttons = $item['buttons'] ?? [];

            // Validate button structure
            if (!is_array($buttons)) {
                $buttons = [];
            }

            $columns[] = [
                'heading_text'          => $item['heading_text'] ?? '',
                'heading_level'         => $item['heading_level'] ?? 'h2',
                'description'           => $item['description'] ?? '',
                'image'                 => $item['image'] ?? [],
                'buttons'               => $buttons,
            ];
        }

        // Render the Blade template with data using view helper
        echo view('blocks.two-columns-image-cta-with-mobile-slider-section', [
            'main_title_heading' => $main_title_heading,
            'main_heading_level' => $main_heading_level,
            'columns'     => $columns,
            'section_bg'  => $section_bg,
            'is_preview'  => $is_preview,
        ]);
    }
}
