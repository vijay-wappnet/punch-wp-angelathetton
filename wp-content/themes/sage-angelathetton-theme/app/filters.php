<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Map WooCommerce cart and checkout pages to subfolder Blade templates.
 */
add_filter('sage/view', function ($view) {
    // Cart page
    if (is_page('cart') || is_cart()) {
        return 'woocommerce.cart.page-cart';
    }

    // Checkout page
    if (is_page('checkout') || is_checkout()) {
        return 'woocommerce.checkout.page-form-checkout';
    }

    return $view;
});
