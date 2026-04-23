<?php

namespace App;

use Walker_Nav_Menu;

/**
 * Custom nav menu walker for the footer bottom menu that displays ACF images instead of text links.
 * It looks for an ACF image field named 'footer_bottom_menu_images' attached to each
 */
class FooterBottomMenuWalker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        // Get ACF image for this menu item

        $url = $item->url;
        $title = $item->title;
        $menu_classes = [];
        $menu_li_classes = $item->classes;
        $atts = "";

        if (in_array('site-accessibility', $menu_li_classes)) {
            $menu_classes[] = 'footer-btn';
            $atts .= ' data-event="' . esc_attr($item->title) . '"';
            $atts .= ' aria-label="' . esc_attr($item->title) . '"';
            $atts .= ' data-toggle="modal" data-target="#accessibilityModal"';
        } else {
            $menu_classes[] = 'footer-btn';
            $atts .= ' data-event="' . esc_attr($item->title) . '"';
            $atts .= ' aria-label="' . esc_attr($item->title) . '"';
        }

         $output .= '<li class="menu-item ' . implode(' ', $menu_li_classes) . '">';

         // fallback to text
         $output .= '<a ' . $atts . ' class="' . implode(' ', $menu_classes) . '" href="' . esc_url($url) . '">' . esc_html($title) . '</a>';
         $output .= '</li>';
    }
}
