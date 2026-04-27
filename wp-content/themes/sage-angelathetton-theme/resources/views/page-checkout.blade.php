{{--
Template Name: Checkout Page
--}}

@extends('layouts.app')

@section('content')

  @php
    $checkout = WC()->checkout();
    do_action('get_header', 'shop');
  @endphp

  <div class="container checkout-page">
    <h1 class="checkout-title">{{ __('CHECK-OUT', 'sage') }}</h1>

    @php
      do_action('woocommerce_before_checkout_form', $checkout);
    @endphp

    @if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in())
      <p class="woocommerce-info">
        {!! apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')) !!}
      </p>
    @else
        <form name="checkout" method="post" class="checkout woocommerce-checkout"
          action="{{ esc_url(wc_get_checkout_url()) }}" enctype="multipart/form-data">
          @php
            do_action('woocommerce_checkout_before_customer_details');
          @endphp

          <div class="row checkout-layout">
            <div class="col-lg-6 col-12 checkout-customer-column">
              <div class="checkout-tabs" role="tablist" aria-label="{{ __('Checkout details tabs', 'sage') }}">
                <button type="button" class="checkout-tabs__button active" data-tab-target="billing-details"
                  aria-expanded="true">
                  {{ __('Billing details', 'woocommerce') }}
                </button>
                <button type="button" class="checkout-tabs__button" data-tab-target="delivery-details" aria-expanded="false">
                  {{ __('Delivery details', 'woocommerce') }}
                </button>
              </div>

              <div class="checkout-tabs__content">
                <section id="billing-details" class="checkout-tab-pane active" role="tabpanel">
                  @php
                    do_action('woocommerce_before_checkout_billing_form', $checkout);
                  @endphp

                  @foreach ($checkout->get_checkout_fields('billing') as $key => $field)
                    @php
                      woocommerce_form_field($key, $field, $checkout->get_value($key));
                    @endphp
                  @endforeach

                  @php
                    do_action('woocommerce_after_checkout_billing_form', $checkout);
                  @endphp
                </section>

                <section id="delivery-details" class="checkout-tab-pane" role="tabpanel" hidden>
                  @php
                    do_action('woocommerce_before_checkout_shipping_form', $checkout);
                  @endphp

                  @foreach ($checkout->get_checkout_fields('shipping') as $key => $field)
                    @php
                      woocommerce_form_field($key, $field, $checkout->get_value($key));
                    @endphp
                  @endforeach

                  @php
                    do_action('woocommerce_after_checkout_shipping_form', $checkout);
                  @endphp
                </section>
              </div>

              <div class="checkout-extra-options d-flex align-items-center flex-wrap">
                <label class="checkout-extra-options__item">
                  <input type="checkbox" name="ship_to_different_address" value="1" @checked((bool) $checkout->get_value('ship_to_different_address')) class="custom-checkbox">
                  <span>{{ __('Deliver to a different address', 'woocommerce') }}</span>
                </label>

                <label class="checkout-extra-options__item">
                  <input type="checkbox" name="newsletter_subscribe" value="1" class="custom-checkbox">
                  <span>{{ __('Subscribe to our newsletter', 'sage') }}</span>
                </label>
              </div>

              {{-- Order notes removed from left column --}}
            </div>

            <div class="col-lg-6 col-12 order-summary-column">
              @php
                do_action('woocommerce_checkout_before_order_review');
              @endphp

              <div id="order_review" class="woocommerce-checkout-review-order order-summary">
                <button type="button" class="order-summary-header" aria-expanded="true" aria-controls="order-summary-content">
                  <span>{{ __('ORDER SUMMARY', 'woocommerce') }}</span>
                  <img src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/bottom_arrow.svg') }}"
                    alt="{{ __('Toggle order summary', 'sage') }}" class="icon" width="20" height="20">
                </button>

                <div id="order-summary-content" class="order-summary-body">
                  <div id="custom-checkout-products" class="order-summary-products"
                    data-nonce="{{ wp_create_nonce('checkout_cart_nonce') }}">
                    <div class="checkout-loader" id="checkout-loader" aria-live="polite" aria-busy="false">
                      <div class="checkout-loader__spinner" role="status" aria-label="Loading"></div>
                    </div>
                    @php
                      do_action('woocommerce_review_order_before_cart_contents');
                    @endphp

                    @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
                      @php
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                        if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                          continue;
                        }

                        $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                      @endphp

                      <div class="order-summary-product" data-cart-item-key="{{ $cart_item_key }}">
                        <div class="order-summary-product__image">{!! $thumbnail !!}</div>

                        <div class="order-summary-product__content">
                          <h4 class="order-summary-product__name">{!! wp_kses_post($product_name) !!}</h4>
                          <p class="order-summary-product__price">{!! WC()->cart->get_product_price($_product) !!}</p>
                        </div>

                        <div class="order-summary-product__actions">
                          <button type="button" class="remove-item-btn" data-cart-key="{{ $cart_item_key }}"
                            aria-label="{{ esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))) }}">
                            <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                d="M3 18C2.45 18 1.97917 17.8042 1.5875 17.4125C1.19583 17.0208 1 16.55 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8042 17.0208 14.4125 17.4125C14.0208 17.8042 13.55 18 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                fill="currentColor" />
                            </svg>
                          </button>

                          @php
                            $max_qty = $_product->is_sold_individually() ? 1 : ($_product->get_max_purchase_quantity() > 0 ? $_product->get_max_purchase_quantity() : 9999);
                          @endphp

                          <div class="quantity-input">
                            <button type="button" class="quantity-input__btn minus"
                              aria-label="{{ __('Decrease quantity', 'woocommerce') }}">-</button>
                            <input type="number" class="qty" value="{{ $cart_item['quantity'] }}" min="1" max="{{ $max_qty }}"
                              data-cart-key="{{ $cart_item_key }}" aria-label="{{ __('Product quantity', 'woocommerce') }}">
                            <button type="button" class="quantity-input__btn plus"
                              aria-label="{{ __('Increase quantity', 'woocommerce') }}">+</button>
                          </div>
                        </div>
                      </div>
                    @endforeach

                    @php
                      do_action('woocommerce_review_order_after_cart_contents');
                    @endphp
                  </div>

                  <div id="custom-checkout-totals" class="order-summary-totals">
                    <div class="summary-row">
                      <span>{{ __('Subtotal', 'woocommerce') }}</span>
                      <span>{!! WC()->cart->get_cart_subtotal() !!}</span>
                    </div>

                    @foreach (WC()->cart->get_coupons() as $coupon)
                      <div class="summary-row">
                        <span>{{ __('Discount', 'woocommerce') }} ({{ wc_cart_totals_coupon_label($coupon, false) }})</span>
                        <span>{!! wc_cart_totals_coupon_html($coupon) !!}</span>
                      </div>
                    @endforeach

                    @php
                      do_action('woocommerce_review_order_before_shipping');
                    @endphp

                    <div class="summary-row">
                      <span>{{ __('Delivery Fee', 'woocommerce') }}</span>
                      <span>
                        @if (WC()->cart->needs_shipping())
                          {!! wc_price(WC()->cart->get_shipping_total()) !!}
                        @else
                          {{ __('FREE', 'woocommerce') }}
                        @endif
                      </span>
                    </div>

                    @php
                      do_action('woocommerce_review_order_after_shipping');
                      do_action('woocommerce_review_order_before_order_total');
                    @endphp

                    <div class="summary-row total-row">
                      <div class="total-row__content">
                        <strong>{{ __('TOTAL', 'woocommerce') }}</strong>
                        @if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax())
                          <span class="includes-tax">({{ __('Includes', 'woocommerce') }}
                            <strong>{!! wc_price(WC()->cart->get_total_tax()) !!}</strong>
                            {{ __('VAT', 'woocommerce') }})</span>
                        @endif
                      </div>
                      <strong>{!! WC()->cart->get_total() !!}</strong>
                    </div>

                    @php
                      do_action('woocommerce_review_order_after_order_total');
                    @endphp
                  </div>

                  {{-- Move payment methods outside order-summary-body --}}
                </div> <!-- end order-summary-body -->

                <div id="payment" class="payment-methods woocommerce-checkout-payment">
                  @php
                    do_action('woocommerce_review_order_before_payment');
                    $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
                  @endphp

                  @if (WC()->cart->needs_payment())
                    @if (!empty($available_gateways))
                      <ul class="wc_payment_methods payment_methods methods">
                        @foreach ($available_gateways as $gateway)
                          <li class="wc_payment_method payment_method_{{ $gateway->id }}">
                            <input id="payment_method_{{ $gateway->id }}" type="radio" class="input-radio" name="payment_method"
                              value="{{ $gateway->id }}" @checked($gateway->chosen)>
                            <label for="payment_method_{{ $gateway->id }}">{!! $gateway->get_title() !!}</label>

                            @if ($gateway->has_fields() || $gateway->get_description())
                              <div class="payment_box payment_method_{{ $gateway->id }}" @if (!$gateway->chosen)
                              style="display:none;" @endif>
                                @php
                                  $gateway->payment_fields();
                                @endphp
                              </div>
                            @endif
                          </li>
                        @endforeach
                      </ul>
                    @else
                      <p class="woocommerce-notice woocommerce-notice--info woocommerce-info">
                        {!! apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) !!}
                      </p>
                    @endif
                  @endif

                  <div class="place-order custom-place-order--from-template--override">
                    @php
                      do_action('woocommerce_review_order_before_submit');
                      wc_get_template('checkout/terms.php');

                      $terms_page_id = wc_terms_and_conditions_page_id();
                      $terms_url = $terms_page_id ? get_permalink($terms_page_id) : '';
                      $terms_label = __('I have read and agree to the website terms and conditions*', 'woocommerce');

                      $order_button_text = apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce'));
                    @endphp

                    <p class="form-row validate-required fallback-terms-row">
                      <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox"
                        for="custom_terms_agree">
                        <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                          name="terms" id="custom_terms_agree" value="1" required>
                        <span>
                          @if ($terms_url)
                            <a href="{{ esc_url($terms_url) }}" target="_blank" rel="noopener">{{ $terms_label }}</a>
                          @else
                            {{ $terms_label }}
                          @endif
                        </span>
                      </label>
                    </p>

                    <button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order"
                      value="{{ esc_attr($order_button_text) }}" data-value="{{ esc_attr($order_button_text) }}">
                      {{ esc_html($order_button_text) }}
                    </button>

                    @php
                      do_action('woocommerce_review_order_after_submit');
                      wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce');
                    @endphp
                  </div>

                  @php
                    do_action('woocommerce_review_order_after_payment');
                  @endphp
                </div>

                <div class="order-notes">
                  @php
                    do_action('woocommerce_before_order_notes', $checkout);

                    $order_notes = $checkout->get_checkout_fields('order')['order_comments'] ?? [
                      'type' => 'textarea',
                      'class' => ['notes'],
                      'label' => __('Order notes', 'woocommerce'),
                      'placeholder' => __('Notes about your order, eg. special notes for delivery', 'woocommerce'),
                    ];

                    $order_notes['placeholder'] = __('Notes about your order, eg. special notes for delivery', 'woocommerce');
                    woocommerce_form_field('order_comments', $order_notes, $checkout->get_value('order_comments'));

                    do_action('woocommerce_after_order_notes', $checkout);
                  @endphp
                </div>
              </div>
            </div>

            @php
              do_action('woocommerce_checkout_after_order_review');
            @endphp
          </div>
      </div>

      @php
        do_action('woocommerce_checkout_after_customer_details');
      @endphp
      </form>
    @endif

  @php
    do_action('woocommerce_after_checkout_form', $checkout);
  @endphp
  </div>

  @php
    do_action('get_footer', 'shop');
  @endphp

  <style>
    .order-summary-products {
      position: relative;
    }

    .checkout-loader {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: none;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.7);
      z-index: 10;
      pointer-events: none;
    }

    .checkout-loader.is-active {
      display: flex;
      pointer-events: auto;
    }

    .checkout-loader__spinner {
      width: 48px;
      height: 48px;
      border: 6px solid #cfc3bc;
      border-top: 6px solid #007bff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tabButtons = document.querySelectorAll('.checkout-tabs__button');
      const tabPanes = document.querySelectorAll('.checkout-tab-pane');

      tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
          const target = button.getAttribute('data-tab-target');

          tabButtons.forEach(function (item) {
            item.classList.remove('active');
            item.setAttribute('aria-expanded', 'false');
          });

          tabPanes.forEach(function (pane) {
            pane.classList.remove('active');
            pane.setAttribute('hidden', 'hidden');
          });

          button.classList.add('active');
          button.setAttribute('aria-expanded', 'true');

          const activePane = document.getElementById(target);
          if (activePane) {
            activePane.classList.add('active');
            activePane.removeAttribute('hidden');
          }
        });
      });

      const summaryToggle = document.querySelector('.order-summary-header');
      const summaryBody = document.getElementById('order-summary-content');
      if (summaryToggle && summaryBody) {
        summaryToggle.addEventListener('click', function () {
          const expanded = summaryToggle.getAttribute('aria-expanded') === 'true';
          summaryToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
          summaryBody.classList.toggle('is-collapsed');
        });
      }

      // Loader helpers
      const loader = document.getElementById('checkout-loader');
      function showLoader() { if (loader) { loader.style.display = 'flex'; loader.classList.add('is-active'); } }
      function hideLoader() { if (loader) { loader.style.display = 'none'; loader.classList.remove('is-active'); } }

      // ── AJAX cart helpers ─────────────────────────────────────────────────
      const ajaxUrl = (window.wc_checkout_params && wc_checkout_params.ajax_url)
        ? wc_checkout_params.ajax_url
        : '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';

      function getCartNonce() {
        return document.getElementById('custom-checkout-products')?.dataset.nonce || '';
      }

      function triggerOrderReviewUpdate() {
        if (window.jQuery) {
          jQuery(document.body).trigger('update_checkout');
        }
      }

      function ajaxPost(action, data) {
        showLoader();
        return new Promise(function (resolve, reject) {
          if (!window.jQuery) { hideLoader(); reject(new Error('jQuery not available')); return; }
          jQuery.post(ajaxUrl, Object.assign({ action: action, nonce: getCartNonce() }, data))
            .done(function (res) { hideLoader(); resolve(res); })
            .fail(function () { hideLoader(); reject(); });
        });
      }

      function bindCheckoutCartEvents() {
        hideLoader(); // Always hide loader before rebinding events
        const productsContainer = document.getElementById('custom-checkout-products');
        if (!productsContainer) return;

        // Remove buttons
        productsContainer.querySelectorAll('.remove-item-btn').forEach(function (btn) {
          btn.addEventListener('click', function () {
            const cartKey = btn.dataset.cartKey;
            if (!cartKey) return;
            btn.disabled = true;
            ajaxPost('checkout_remove_cart_item', { cart_item_key: cartKey })
              .then(function (res) {
                if (res && res.success) triggerOrderReviewUpdate();
                else btn.disabled = false;
              })
              .catch(function () { btn.disabled = false; });
          });
        });

        // Quantity +/- buttons
        productsContainer.querySelectorAll('.quantity-input').forEach(function (wrapper) {
          const input = wrapper.querySelector('input.qty');
          const minus = wrapper.querySelector('.minus');
          const plus = wrapper.querySelector('.plus');
          if (!input || !minus || !plus) return;

          let qtyTimer;
          function queueQtyUpdate() {
            clearTimeout(qtyTimer);
            qtyTimer = setTimeout(function () {
              const cartKey = input.dataset.cartKey;
              const qty = parseInt(input.value, 10);
              if (!cartKey || isNaN(qty)) return;
              ajaxPost('checkout_update_cart_qty', { cart_item_key: cartKey, quantity: qty })
                .then(function (res) { if (res && res.success) triggerOrderReviewUpdate(); });
            }, 500);
          }

          minus.addEventListener('click', function () {
            const val = parseInt(input.value, 10) || 1;
            const min = parseInt(input.min, 10) || 1;
            if (val > min) { input.value = val - 1; queueQtyUpdate(); }
          });

          plus.addEventListener('click', function () {
            const val = parseInt(input.value, 10) || 1;
            const max = parseInt(input.max, 10) || 9999;
            if (val < max) { input.value = val + 1; queueQtyUpdate(); }
          });

          input.addEventListener('change', queueQtyUpdate);
        });
      }

      bindCheckoutCartEvents();

      if (window.jQuery) {
        jQuery(document.body).on('updated_checkout', function () {
          hideLoader();
          bindCheckoutCartEvents();
          initTermsButtonToggle();
        });
      }

      // Keep Place order disabled until terms are accepted.
      function initTermsButtonToggle() {
        const placeOrderButton = document.getElementById('place_order');
        const termsCheckbox = document.getElementById('custom_terms_agree');

        if (!placeOrderButton) {
          return;
        }

        if (!termsCheckbox) {
          placeOrderButton.disabled = false;
          return;
        }

        function updatePlaceOrderState() {
          const isChecked = termsCheckbox.checked;
          placeOrderButton.disabled = !isChecked;
          placeOrderButton.classList.toggle('is-disabled', !isChecked);
        }

        termsCheckbox.addEventListener('change', updatePlaceOrderState);
        updatePlaceOrderState();
      }

      initTermsButtonToggle();
    });
  </script>

@endsection