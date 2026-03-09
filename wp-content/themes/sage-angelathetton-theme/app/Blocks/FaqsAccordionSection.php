<?php

namespace App\Blocks;

class FaqsAccordionSection
{
    /**
     * Render the FAQs Accordion Section block
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
        $faqs = get_field('faqs');
        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'fas-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build background style
        $backgroundStyle = '';
        if (!empty($sectionBg)) {
            $backgroundStyle = 'background-color: ' . esc_attr($sectionBg) . ';';
        }

        // Validate faqs structure
        if (!is_array($faqs)) {
            $faqs = [];
        }

        // Render the Blade template with data
        echo view('blocks.faqs-accordion-section', [
            'block'           => $block,
            'blockId'         => $blockId,
            'faqs'            => $faqs,
            'backgroundStyle' => $backgroundStyle,
            'responsiveCss'   => $responsiveCss,
            'isPreview'       => $is_preview,
        ]);
    }
}
