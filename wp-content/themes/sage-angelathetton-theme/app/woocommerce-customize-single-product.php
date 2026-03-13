<?php
/**
 * WooCommerce Single Product Customizations
 * ==========================================
 */

use Illuminate\Support\Facades\Vite;

/**
 * Remove duplicate product title from summary section
 * (Title is displayed via custom hook after notices)
 */
add_action('init', function() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
});

/**
 * Display product title after breadcrumb and notices, before product content
 * Hook: woocommerce_before_single_product runs after breadcrumb/notices
 */
add_action('woocommerce_before_single_product', 'custom_display_product_title_after_notices', 15);
function custom_display_product_title_after_notices() {
    echo '<div class="single-product-container__product_title">';
    echo '<h1 class="single-product-container__product_title__text">' . get_the_title() . '</h1>';
    echo '</div>';
}

/**
 * Remove short description from summary section
 */
add_action('init', function() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
});

/**
 * Remove default price display (we'll add custom price with stock)
 */
add_action('init', function() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
});

/**
 * Remove related products output
 */
add_action('init', function() {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
});

/**
 * Add full product description before price in summary section
 */
add_action('woocommerce_single_product_summary', 'custom_display_full_product_description', 6);
function custom_display_full_product_description() {
    global $product;

    $description = $product->get_description();

    if ($description) {
        echo '<div class="woocommerce-product-details__full-description">';
        echo '<h3 class="woocommerce-product-details__description-title">' . esc_html__('Description', 'woocommerce') . '</h3>';
        echo wp_kses_post(wpautop($description));
        echo '</div>';
    }
}

/**
 * Add custom price with stock status on same line
 */
add_action('woocommerce_single_product_summary', 'custom_price_with_stock_status', 10);
function custom_price_with_stock_status() {
    global $product;

    echo '<div class="custom-price-stock-wrapper">';

    // Price
    echo '<span class="custom-price">' . $product->get_price_html() . '</span>';

    // Stock status
    $availability = $product->get_availability();
    $stock_status = $availability['availability'] ? $availability['availability'] : __('In Stock', 'woocommerce');

    if ($product->is_in_stock()) {
        echo '<span class="custom-stock-status in-stock">' . esc_html($stock_status) . '</span>';
    } else {
        echo '<span class="custom-stock-status out-of-stock">' . esc_html($stock_status) . '</span>';
    }

    echo '</div>';
}

/**
 * Remove the Description tab from product tabs
 * (since we're displaying description in the summary)
 */
add_filter('woocommerce_product_tabs', function($tabs) {
    unset($tabs['description']);
    unset($tabs['reviews']);
    unset($tabs['additional_information']);
    return $tabs;
}, 98);

/**
 * Custom quantity input with +/- buttons
 */
add_filter('woocommerce_quantity_input_args', 'custom_quantity_input_args', 10, 2);
function custom_quantity_input_args($args, $product) {
    $args['input_type'] = 'number';
    return $args;
}

/**
 * Add Apple Pay and Google Pay buttons after add to cart
 */
add_action('woocommerce_after_add_to_cart_form', 'custom_payment_buttons', 10);
function custom_payment_buttons() {
    // Use Vite facade to get processed asset URLs (works in dev and production)
    try {
        $apple_pay_icon = Vite::asset('resources/images/apple-pay-icon.svg');
        $gpay_icon = Vite::asset('resources/images/gpay-icon.svg');
    } catch (\Exception $e) {
        // Fallback if assets not in manifest yet - use source files directly
        $theme_url = get_stylesheet_directory_uri();
        $apple_pay_icon = $theme_url . '/resources/images/apple-pay-icon.svg';
        $gpay_icon = $theme_url . '/resources/images/gpay-icon.svg';
    }
    ?>
    <div class="custom-payment-buttons">
        <button type="button" class="custom-payment-btn apple-pay-btn trans-black-btn" aria-label="<?php esc_attr_e('Pay with Apple Pay', 'woocommerce'); ?>">
            <img src="<?php echo esc_url($apple_pay_icon); ?>" alt="Apple Pay" class="icon">
        </button>
        <button type="button" class="custom-payment-btn gpay-btn trans-black-btn" aria-label="<?php esc_attr_e('Pay with Google Pay', 'woocommerce'); ?>">
            <img src="<?php echo esc_url($gpay_icon); ?>" alt="Google Pay" class="icon">
        </button>
    </div>
    <?php
}

/**
 * Change "Add to cart" button text to "Add to Basket"
 */
add_filter('woocommerce_product_single_add_to_cart_text', 'custom_add_to_cart_button_text');
function custom_add_to_cart_button_text($text) {
    return __('Add to Basket', 'woocommerce');
}

/**
 * Add custom classes to single product Add to Cart button
 */
add_filter('woocommerce_loop_add_to_cart_args', 'custom_add_to_cart_button_classes', 10, 2);
function custom_add_to_cart_button_classes($args, $product) {
    $args['class'] .= ' btn trans-black-btn';
    return $args;
}

/**
 * Add custom classes to single product Add to Cart button via JavaScript
 */
add_action('wp_footer', 'add_custom_class_to_add_to_cart_button');
function add_custom_class_to_add_to_cart_button() {
    if (!is_product()) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartBtn = document.querySelector('.single_add_to_cart_button');
        if (addToCartBtn) {
            addToCartBtn.classList.add('btn', 'trans-black-btn');
        }
    });
    </script>
    <?php
}

/**
 * Custom quantity input with +/- buttons
 * Add wrapper and buttons around quantity input
 */
add_filter('woocommerce_quantity_input_min', function() {
    return 1;
});

/**
 * Add minus button before quantity input
 */
add_action('woocommerce_before_quantity_input_field', 'custom_quantity_minus_button');
function custom_quantity_minus_button() {
    echo '<button type="button" class="quantity-btn minus" aria-label="' . esc_attr__('Decrease quantity', 'woocommerce') . '">
        <svg width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line x1="0" y1="1" x2="14" y2="1" stroke="currentColor" stroke-width="1.5"/>
        </svg>
    </button>';
}

/**
 * Add plus button after quantity input
 */
add_action('woocommerce_after_quantity_input_field', 'custom_quantity_plus_button');
function custom_quantity_plus_button() {
    echo '<button type="button" class="quantity-btn plus" aria-label="' . esc_attr__('Increase quantity', 'woocommerce') . '">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line x1="7" y1="0" x2="7" y2="14" stroke="currentColor" stroke-width="1.5"/>
            <line x1="0" y1="7" x2="14" y2="7" stroke="currentColor" stroke-width="1.5"/>
        </svg>
    </button>';
}

/**
 * Add custom class to quantity wrapper
 */
add_filter('woocommerce_quantity_input_classes', function($classes) {
    $classes[] = 'custom-quantity';
    return $classes;
});

/**
 * Enqueue WooCommerce quantity button scripts
 */
add_action('wp_footer', 'custom_quantity_button_script');
function custom_quantity_button_script() {
    if (!is_product()) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity button functionality
        document.querySelectorAll('.quantity').forEach(function(wrapper) {
            const minusBtn = wrapper.querySelector('.quantity-btn.minus');
            const plusBtn = wrapper.querySelector('.quantity-btn.plus');
            const input = wrapper.querySelector('.qty');

            if (minusBtn && input) {
                minusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const min = parseInt(input.min) || 1;
                    let value = parseInt(input.value) || 1;
                    if (value > min) {
                        input.value = value - 1;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
            }

            if (plusBtn && input) {
                plusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const max = parseInt(input.max) || 9999;
                    let value = parseInt(input.value) || 1;
                    if (max === -1 || value < max) {
                        input.value = value + 1;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
            }
        });
    });
    </script>
    <?php
}
