<?php

namespace App;

use Walker_Nav_Menu;

/**
 * Custom nav menu walker for the header top menu that displays ACF images instead of text links.
 * It looks for an ACF image field named 'top_menu_items_images' attached to each
 */
class HeaderTopMenuWalker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        // Get ACF image for this menu item
        $image = get_field('top_menu_items_images', $item);
        $image_url = $image['url'] ?? '';

        $url = $item->url;
        $title = $item->title;
        $menu_classes = $item->classes;
        $menu_li_classes = "";

        // Get cart count if WooCommerce is active
        $cart_count = 0;
        if (function_exists('WC') && WC()->cart) {
            $cart_count = WC()->cart->get_cart_contents_count();
        }

        if ($item->title == 'Cart' || $item->title == 'cart') {
            if ($cart_count >= 1) {
                $menu_classes[] = 'main-cart-items';
                $menu_li_classes = 'main-cart-li';
            } else {
                $menu_classes[] = 'main-cart-items-zero';
                $menu_li_classes = 'main-cart-li-zero';
            }
        } else {
            $menu_classes[] = 'regular-menu-item';
            $menu_li_classes = 'regular-menu-li';
        }

        $output .= '<li class="menu-item ' . $menu_li_classes . '">';

        if ($image_url) {
            $output .= '<a class="' . implode(' ', $menu_classes) . '" href="' . esc_url($url) . '">';
            $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($title) . '" class="cart-menu-image" />';
            $output .= '<span class="cart-menu-count">' . $cart_count . '</span>';
            $output .= '</a>';
        } else {
            // fallback to text
            $output .= '<a class="' . implode(' ', $menu_classes) . '" href="' . esc_url($url) . '">' . esc_html($title) . '</a>';
        }

        $output .= '</li>';
    }
}
