<?php
/**
 * WooCommerce Checkout Page – AJAX cart updates
 * ================================================
 * Handles quantity changes and item removal from the
 * custom checkout order-summary panel via AJAX.
 * Registers WooCommerce update_order_review fragments so
 * the products list and totals are refreshed automatically
 * after any cart change.
 */

// ---------------------------------------------------------------------------
// AJAX: update quantity of a cart item on the checkout page
// ---------------------------------------------------------------------------
add_action('wp_ajax_checkout_update_cart_qty',        'checkout_update_cart_qty_handler');
add_action('wp_ajax_nopriv_checkout_update_cart_qty', 'checkout_update_cart_qty_handler');

function checkout_update_cart_qty_handler() {
    check_ajax_referer('checkout_cart_nonce', 'nonce');

    $cart_item_key = sanitize_key(wp_unslash($_POST['cart_item_key'] ?? ''));
    $quantity      = max(0, (int) (wp_unslash($_POST['quantity'] ?? 0)));

    if (empty($cart_item_key)) {
        wp_send_json_error(['message' => 'Invalid cart key']);
        return;
    }

    if ($quantity <= 0) {
        WC()->cart->remove_cart_item($cart_item_key);
    } else {
        WC()->cart->set_quantity($cart_item_key, $quantity, true);
    }

    WC()->cart->calculate_totals();

    wp_send_json_success(['cart_updated' => true]);
}

// ---------------------------------------------------------------------------
// AJAX: remove a cart item on the checkout page
// ---------------------------------------------------------------------------
add_action('wp_ajax_checkout_remove_cart_item',        'checkout_remove_cart_item_handler');
add_action('wp_ajax_nopriv_checkout_remove_cart_item', 'checkout_remove_cart_item_handler');

function checkout_remove_cart_item_handler() {
    check_ajax_referer('checkout_cart_nonce', 'nonce');

    $cart_item_key = sanitize_key(wp_unslash($_POST['cart_item_key'] ?? ''));

    if (empty($cart_item_key)) {
        wp_send_json_error(['message' => 'Invalid cart key']);
        return;
    }

    WC()->cart->remove_cart_item($cart_item_key);
    WC()->cart->calculate_totals();

    wp_send_json_success(['cart_updated' => true]);
}

// ---------------------------------------------------------------------------
// Fragments: inject custom product list + totals HTML into WooCommerce's
// update_order_review AJAX response so our custom template stays in sync.
// ---------------------------------------------------------------------------
add_filter('woocommerce_update_order_review_fragments', 'custom_checkout_order_review_fragments');

function custom_checkout_order_review_fragments( $fragments ) {

    // ---- products fragment --------------------------------------------------
    ob_start();
    do_action('woocommerce_review_order_before_cart_contents');

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

        if (
            ! $_product
            || ! $_product->exists()
            || $cart_item['quantity'] <= 0
            || ! apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)
        ) {
            continue;
        }

        $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
        $thumbnail    = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
        $max_qty      = $_product->is_sold_individually() ? 1 : ( $_product->get_max_purchase_quantity() > 0 ? $_product->get_max_purchase_quantity() : 9999 );
        ?>
        <div class="order-summary-product" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
            <div class="order-summary-product__image"><?php echo $thumbnail; // phpcs:ignore ?></div>

            <div class="order-summary-product__content">
                <h4 class="order-summary-product__name"><?php echo wp_kses_post($product_name); ?></h4>
                <p class="order-summary-product__price"><?php echo WC()->cart->get_product_price($_product); // phpcs:ignore ?></p>
            </div>

            <div class="order-summary-product__actions">
                <button type="button"
                    class="remove-item-btn"
                    data-cart-key="<?php echo esc_attr($cart_item_key); ?>"
                    aria-label="<?php echo esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))); ?>">
                    <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 18C2.45 18 1.97917 17.8042 1.5875 17.4125C1.19583 17.0208 1 16.55 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8042 17.0208 14.4125 17.4125C14.0208 17.8042 13.55 18 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z" fill="currentColor"/>
                    </svg>
                </button>

                <div class="quantity-input">
                    <button type="button" class="quantity-input__btn minus" aria-label="<?php esc_attr_e('Decrease quantity', 'woocommerce'); ?>">-</button>
                    <input type="number"
                        class="qty"
                        value="<?php echo esc_attr($cart_item['quantity']); ?>"
                        min="1"
                        max="<?php echo esc_attr($max_qty); ?>"
                        data-cart-key="<?php echo esc_attr($cart_item_key); ?>"
                        aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>">
                    <button type="button" class="quantity-input__btn plus" aria-label="<?php esc_attr_e('Increase quantity', 'woocommerce'); ?>">+</button>
                </div>
            </div>
        </div>
        <?php
    }

    do_action('woocommerce_review_order_after_cart_contents');
    $products_html = ob_get_clean();

    $fragments['#custom-checkout-products'] =
        '<div id="custom-checkout-products" class="order-summary-products" data-nonce="' . esc_attr(wp_create_nonce('checkout_cart_nonce')) . '">'
        . $products_html
        . '</div>';

    // ---- totals fragment ----------------------------------------------------
    ob_start();
    ?>
    <div class="summary-row">
        <span><?php esc_html_e('Subtotal', 'woocommerce'); ?></span>
        <span><?php echo WC()->cart->get_cart_subtotal(); // phpcs:ignore ?></span>
    </div>

    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <div class="summary-row">
            <span><?php esc_html_e('Discount', 'woocommerce'); ?> (<?php echo wc_cart_totals_coupon_label($coupon, false); // phpcs:ignore ?>)</span>
            <span><?php echo wc_cart_totals_coupon_html($coupon); // phpcs:ignore ?></span>
        </div>
    <?php endforeach; ?>

    <?php do_action('woocommerce_review_order_before_shipping'); ?>

    <div class="summary-row">
        <span><?php esc_html_e('Delivery Fee', 'woocommerce'); ?></span>
        <span>
            <?php if ( WC()->cart->needs_shipping() ) : ?>
                <?php echo wc_price(WC()->cart->get_shipping_total()); // phpcs:ignore ?>
            <?php else : ?>
                <?php esc_html_e('FREE', 'woocommerce'); ?>
            <?php endif; ?>
        </span>
    </div>

    <?php
    do_action('woocommerce_review_order_after_shipping');
    do_action('woocommerce_review_order_before_order_total');
    ?>

    <div class="summary-row total-row">
        <div class="total-row__content">
            <strong><?php esc_html_e('TOTAL', 'woocommerce'); ?></strong>
            <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                <span class="includes-tax">(<?php esc_html_e('Includes', 'woocommerce'); ?> <strong><?php echo wc_price(WC()->cart->get_total_tax()); // phpcs:ignore ?></strong> <?php esc_html_e('VAT', 'woocommerce'); ?>)</span>
            <?php endif; ?>
        </div>
        <strong><?php echo WC()->cart->get_total(); // phpcs:ignore ?></strong>
    </div>

    <?php do_action('woocommerce_review_order_after_order_total'); ?>
    <?php
    $totals_html = ob_get_clean();

    $fragments['#custom-checkout-totals'] =
        '<div id="custom-checkout-totals" class="order-summary-totals">'
        . $totals_html
        . '</div>';

    return $fragments;
}
