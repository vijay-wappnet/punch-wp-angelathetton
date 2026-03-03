<?php

namespace App\Blocks;

class MenuItemsSection
{
    /**
     * Render the Menu Items Section block
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
        $menuItems = get_field('menu_items') ?? [];
        $button = get_field('button') ?? [];
        $backgroundColor = get_field('background_color');
        $margin = get_field('margin');
        $padding = get_field('padding');

        // Generate unique block ID
        $blockId = 'mis-' . ($block['id'] ?? uniqid());

        // Generate responsive CSS for margin and padding
        $responsiveCss = custom_acf_dimensions($margin, $padding, $blockId);

        // Build background style
        $backgroundStyle = '';
        if (!empty($backgroundColor)) {
            $backgroundStyle = 'background-color: ' . esc_attr($backgroundColor) . ';';
        }

        // Validate menu items structure
        if (!is_array($menuItems)) {
            $menuItems = [];
        }

        // Validate button structure
        if (!is_array($button)) {
            $button = [];
        }

        // Render the Blade template with data
        echo view('blocks.menu-items-section', [
            'block'           => $block,
            'blockId'         => $blockId,
            'menuItems'       => $menuItems,
            'button'          => $button,
            'backgroundStyle' => $backgroundStyle,
            'responsiveCss'   => $responsiveCss,
            'isPreview'       => $is_preview,
        ]);
    }
}
