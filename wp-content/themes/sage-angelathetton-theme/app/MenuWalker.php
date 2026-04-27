<?php

namespace App;

use Walker_Nav_Menu;

/**
 * Custom nav menu walker that adds data attributes for menu images
 * and a helper class for items with children.
 */
class MenuWalker extends Walker_Nav_Menu
{
    /**
     * Starts the element output.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Additional arguments.
     * @param int    $id     Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        $this->current_item = $item;
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        // ACF image
        $image_attr = '';
        $image = get_field('menu_items_images', $item);
        if ($image && is_array($image) && ! empty($image['url'])) {
            $image_attr = ' data-menu-image="' . esc_url($image['url']) . '"';
        }

        $output .= '<li' . $class_names . $image_attr . '>';

        // Link attributes
        $atts = [];
        $atts['href'] = ! empty($item->url) ? $item->url : '#';
        $atts['class'] = 'header-btn';
        $atts['data-event'] = $item->title;
        $atts['aria-label'] = $item->title;

        // ✅ Accessibility improvements
        if ($has_children) {
            $atts['href'] = '#'; // prevent navigation
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
            $atts['aria-controls'] = 'submenu-' . $item->ID;
        }

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ($attr === 'href') ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);

        $item_output  = $args->before;

        // ✅ role applied correctly
        $item_output .= '<a role="menuitem"' . $attributes . '>';
        $item_output .= $title;
        $item_output .= '</a>';

        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Start the level output.
     *
     * @param string $output Passed by reference.
     * @param int    $depth  Depth of submenu.
     * @param array  $args   Additional arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        $indent  = str_repeat("\t", $depth);
        // Get parent item ID safely
        $parent_id = isset($this->current_item) ? $this->current_item->ID : uniqid();
        $output .= "\n{$indent}<ul class=\"sub-menu\" id=\"submenu-{$parent_id}\" role=\"menu\" aria-hidden=\"true\">\n";
    }
}
