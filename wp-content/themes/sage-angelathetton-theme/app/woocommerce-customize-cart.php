<?php
/**
 * WooCommerce Cart Page Customizations
 * =====================================
 */

/**
 * Enqueue cart page scripts for quantity buttons
 */
add_action('wp_footer', 'custom_cart_quantity_button_script');
function custom_cart_quantity_button_script() {
    // Only load on cart page
    if (!is_cart()) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartForm = document.querySelector('.woocommerce-cart-form');
        if (!cartForm) return;

        // Quantity button functionality
        function initQuantityButtons() {
            const quantityWrappers = document.querySelectorAll('.quantity-wrapper');

            quantityWrappers.forEach(function(wrapper) {
                const minusBtn = wrapper.querySelector('.quantity-btn.minus');
                const plusBtn = wrapper.querySelector('.quantity-btn.plus');
                const input = wrapper.querySelector('input.qty');

                if (!minusBtn || !plusBtn || !input) return;

                // Remove existing listeners to prevent duplicates
                const newMinusBtn = minusBtn.cloneNode(true);
                const newPlusBtn = plusBtn.cloneNode(true);
                minusBtn.parentNode.replaceChild(newMinusBtn, minusBtn);
                plusBtn.parentNode.replaceChild(newPlusBtn, plusBtn);

                // Minus button
                newMinusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt(input.value) || 1;
                    const min = parseInt(input.getAttribute('min')) || 0;

                    if (currentVal > min) {
                        input.value = currentVal - 1;
                        triggerCartUpdate();
                    }
                });

                // Plus button
                newPlusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt(input.value) || 1;
                    const max = parseInt(input.getAttribute('max')) || 9999;

                    if (currentVal < max) {
                        input.value = currentVal + 1;
                        triggerCartUpdate();
                    }
                });

                // Input change
                input.addEventListener('change', function() {
                    triggerCartUpdate();
                });
            });
        }

        // Trigger cart update
        let updateTimeout;
        function triggerCartUpdate() {
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(function() {
                // Find and trigger the update cart button
                const updateBtn = document.querySelector('button[name="update_cart"]');
                if (updateBtn) {
                    updateBtn.disabled = false;
                    updateBtn.click();
                }
            }, 500);
        }

        // Initialize quantity buttons
        initQuantityButtons();

        // Re-initialize after cart update (WooCommerce AJAX)
        jQuery(document.body).on('updated_cart_totals', function() {
            initQuantityButtons();
        });
    });
    </script>
    <?php
}

/**
 * Auto-enable update cart button when quantity changes
 */
add_action('wp_footer', 'custom_cart_auto_update_script');
function custom_cart_auto_update_script() {
    if (!is_cart()) return;
    ?>
    <script>
    jQuery(function($) {
        // Enable update button on any quantity change
        $(document).on('change', '.woocommerce-cart-form input.qty', function() {
            $('button[name="update_cart"]').prop('disabled', false);
        });
    });
    </script>
    <?php
}
