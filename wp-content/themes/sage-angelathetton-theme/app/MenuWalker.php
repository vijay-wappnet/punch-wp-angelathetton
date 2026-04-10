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
        // classes that WordPress generated for the <li>
        $classes = empty($item->classes) ? [] : (array) $item->classes;

        // add our own "has-children" class if the item has sub-items
        // 11111111111 if (!empty($args->has_children)) {
        //     $classes[] = 'has-children';
        // }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        // attempt to fetch image attached to this menu item via ACF
        $image_attr = '';
        $image     = get_field('menu_items_images', $item);
        if ($image && is_array($image) && ! empty($image['url'])) {
            $image_attr = ' data-menu-image="' . esc_url($image['url']) . '"';
        }

        $output .= '<li' . $class_names . $image_attr . '>';

        // build the <a> tag attributes
        $atts = [];
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        $atts['rel']    = ! empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = ! empty($item->url) ? $item->url : '';
        $atts           = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
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
        $output .= "\n{$indent}<ul class=\"sub-menu\">\n";
    }
}
