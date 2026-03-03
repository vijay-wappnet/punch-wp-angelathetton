<?php

namespace App\Blocks;

class LeftRightMediaContentSection
{
    /**
     * Render the Left Right Media Content Section block
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
        $showLeftImage = get_field('show_left_side_image') ?? true;
        $mediaImage = get_field('media_image');
        $title = get_field('title') ?? '';
        $headingLevel = get_field('heading_level');
        $contentText = get_field('content') ?? '';
        $buttons = get_field('intro_section_button');
        $backgroundImage = get_field('background_image');
        $backgroundColor = get_field('background_color');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'lrmcs-' . ($block['id'] ?? uniqid());

        // Helper function to format dimension values
        $formatDimension = function($values) {
            if (!is_array($values)) return '';
            $unit = $values['unit'] ?? 'px';
            $top = $values['top'] !== '' ? $values['top'] : null;
            $right = $values['right'] !== '' ? $values['right'] : null;
            $bottom = $values['bottom'] !== '' ? $values['bottom'] : null;
            $left = $values['left'] !== '' ? $values['left'] : null;
            // Only return if at least one value is set
            if ($top === null && $right === null && $bottom === null && $left === null) return '';
            return ($top ?? 0) . $unit . ' ' . ($right ?? 0) . $unit . ' ' . ($bottom ?? 0) . $unit . ' ' . ($left ?? 0) . $unit;
        };

        // Build responsive styles
        $responsiveStyles = [];

        // Desktop styles (default)
        $desktopMargin = is_array($margin) && isset($margin['desktop']) ? $formatDimension($margin['desktop']) : '';
        $desktopPadding = is_array($padding) && isset($padding['desktop']) ? $formatDimension($padding['desktop']) : '';

        // Tablet styles (max-width: 991px)
        $tabletMargin = is_array($margin) && isset($margin['tablet']) ? $formatDimension($margin['tablet']) : '';
        $tabletPadding = is_array($padding) && isset($padding['tablet']) ? $formatDimension($padding['tablet']) : '';

        // Mobile styles (max-width: 767px)
        $mobileMargin = is_array($margin) && isset($margin['mobile']) ? $formatDimension($margin['mobile']) : '';
        $mobilePadding = is_array($padding) && isset($padding['mobile']) ? $formatDimension($padding['mobile']) : '';

        // Build CSS string with media queries
        $responsiveCss = '';

        // Desktop (base styles)
        $desktopRules = [];
        if (!empty($desktopMargin)) $desktopRules[] = 'margin: ' . $desktopMargin;
        if (!empty($desktopPadding)) $desktopRules[] = 'padding: ' . $desktopPadding;
        if (!empty($desktopRules)) {
            $responsiveCss .= '#' . $blockId . ' { ' . implode('; ', $desktopRules) . '; }';
        }

        // Tablet
        $tabletRules = [];
        if (!empty($tabletMargin)) $tabletRules[] = 'margin: ' . $tabletMargin;
        if (!empty($tabletPadding)) $tabletRules[] = 'padding: ' . $tabletPadding;
        if (!empty($tabletRules)) {
            $responsiveCss .= ' @media (max-width: 991px) { #' . $blockId . ' { ' . implode('; ', $tabletRules) . '; } }';
        }

        // Mobile
        $mobileRules = [];
        if (!empty($mobileMargin)) $mobileRules[] = 'margin: ' . $mobileMargin;
        if (!empty($mobilePadding)) $mobileRules[] = 'padding: ' . $mobilePadding;
        if (!empty($mobileRules)) {
            $responsiveCss .= ' @media (max-width: 767px) { #' . $blockId . ' { ' . implode('; ', $mobileRules) . '; } }';
        }

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
        echo view('blocks.left-right-media-content-section', [
            'block'             => $block,
            'blockId'           => $blockId,
            'showLeftImage'     => $showLeftImage,
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
