<?php

namespace App\Blocks;

class PackageDetailsWithImagesSection
{
    /**
     * Render the Package Details With Images Section block
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
        $mainHeadingText = get_field('main_heading_text') ?? '';
        $mainHeadingLevel = get_field('main_heading_level') ?? 'h2';

        // Image display settings
        $showLeftImage = get_field('show_left_side_image') ?? true;
        $hideFirstImageDesktop = get_field('hide_first_image_in_desktop') ?? false;
        $hideSecondImageDesktop = get_field('hide_second_image_in_desktop') ?? false;
        $hideFirstImageMobile = get_field('hide_first_image_in_mobile') ?? false;
        $hideSecondImageMobile = get_field('hide_second_image_in_mobile') ?? false;

        // Images
        $firstImage = get_field('first_image');
        $secondImage = get_field('second_image');

        // Contents repeater
        $contents = get_field('contents');

        // Section styling
        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'pdwis-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build background style
        $backgroundStyle = [];
        if (!empty($sectionBg)) {
            $backgroundStyle[] = 'background-color: ' . esc_attr($sectionBg);
        }
        $backgroundStyle = !empty($backgroundStyle) ? implode('; ', $backgroundStyle) . ';' : '';

        // Validate contents structure
        if (!is_array($contents)) {
            $contents = [];
        }

        // Render the Blade template with data
        echo view('blocks.package-details-with-images-section', [
            'block'                  => $block,
            'blockId'                => $blockId,
            'mainHeadingText'        => $mainHeadingText,
            'mainHeadingLevel'       => $mainHeadingLevel,
            'showLeftImage'          => $showLeftImage,
            'hideFirstImageDesktop'  => $hideFirstImageDesktop,
            'hideSecondImageDesktop' => $hideSecondImageDesktop,
            'hideFirstImageMobile'   => $hideFirstImageMobile,
            'hideSecondImageMobile'  => $hideSecondImageMobile,
            'firstImage'             => $firstImage,
            'secondImage'            => $secondImage,
            'contents'               => $contents,
            'backgroundStyle'        => $backgroundStyle,
            'responsiveCss'          => $responsiveCss,
            'isPreview'              => $is_preview,
        ]);
    }
}
