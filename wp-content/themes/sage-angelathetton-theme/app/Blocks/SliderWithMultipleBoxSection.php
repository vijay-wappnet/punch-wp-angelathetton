<?php

namespace App\Blocks;

class SliderWithMultipleBoxSection
{
    /**
     * Render the Slider With Multiple Box Section block
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
        $slider_contents = get_field('slider_contents_cta');
        $section_bg = get_field('section_bg');

        // Validate slider contents structure
        if (!is_array($slider_contents)) {
            $slider_contents = [];
        }

        // Process slider contents
        $processed_slides = [];
        if (count($slider_contents) > 0) {
            foreach ($slider_contents as $slide) {
                $heading_level = $slide['heading_level'] ?? 'h2';
                $sub_heading_level = $slide['sub_heading_level'] ?? 'p';
                $heading_text = $slide['heading_text'] ?? '';
                $sub_heading_text = $slide['sub_heading_text'] ?? '';
                $description = $slide['description'] ?? '';
                $image = $slide['image'] ?? [];
                $buttons = $slide['buttons'] ?? [];

                // Validate buttons structure
                if (!is_array($buttons)) {
                    $buttons = [];
                }

                $processed_slides[] = [
                    'heading_level'     => $heading_level,
                    'heading_text'      => $heading_text,
                    'sub_heading_level' => $sub_heading_level,
                    'sub_heading_text'  => $sub_heading_text,
                    'description'       => $description,
                    'image'             => $image,
                    'buttons'           => $buttons,
                ];
            }
        }

        // Render the Blade template with data using view helper
        echo view('blocks.slider-with-multiple-box-section', [
            'slides'      => $processed_slides,
            'section_bg'  => $section_bg,
            'is_preview'  => $is_preview,
        ]);
    }
}
