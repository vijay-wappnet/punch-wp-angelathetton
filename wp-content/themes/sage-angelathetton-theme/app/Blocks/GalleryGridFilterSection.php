<?php

namespace App\Blocks;

class GalleryGridFilterSection
{
    /**
     * Render the Gallery Grid Filter Section block
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
        $filterByTitle = get_field('filter_by_title') ?? 'Filter by:';
        $refreshTitle = get_field('refresh_title') ?? 'Refresh';
        $galleryImages = get_field('gallery_images') ?? [];
        $sectionBg = get_field('section_bg');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'ggfs-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        $backgroundStyle = [];

        // Add background color if selected
        if (!empty($sectionBg)) {
            $backgroundStyle[] = 'background-color: ' . esc_attr($sectionBg);
        }

        // Convert array to inline style string
        $backgroundStyle = !empty($backgroundStyle) ? implode('; ', $backgroundStyle) . ';' : '';

        // Validate gallery images
        if (!is_array($galleryImages)) {
            $galleryImages = [];
        }

        // Get all unique categories for dropdown
        $categories = [
            'all' => 'ALL',
            'dine' => 'Dine',
            'gift' => 'Gift',
            'latest' => 'Latest',
            'package' => 'Package',
            'room' => 'Room',
            'story' => 'Story',
            'wedding' => 'Wedding',
        ];

        // Render the Blade template with data
        echo view('blocks.gallery-grid-filter-section', [
            'block'             => $block,
            'blockId'           => $blockId,
            'filterByTitle'     => $filterByTitle,
            'refreshTitle'      => $refreshTitle,
            'galleryImages'     => $galleryImages,
            'categories'        => $categories,
            'backgroundStyle'   => $backgroundStyle,
            'responsiveCss'     => $responsiveCss,
            'isPreview'         => $is_preview,
        ]);
    }
}
