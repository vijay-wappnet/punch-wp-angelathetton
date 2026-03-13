{{--
  Template Name: Cart Page
--}}

@extends('layouts.app')

@section('content')

  @php
    do_action('get_header', 'shop');
  @endphp

  <div class="cart-page">
    <div class="container">

      @php
        do_action('woocommerce_before_main_content');
      @endphp

      @if (WC()->cart->is_empty())
        {{-- Empty Cart Message --}}
        <div class="cart-empty-wrapper">
          <p class="cart-empty">{{ __('Your basket is currently empty.', 'woocommerce') }}</p>
          <a href="{{ wc_get_page_permalink('shop') }}" class="btn black-trans-btn return-to-shop">
            {{ __('Return to Shop', 'woocommerce') }}
          </a>
        </div>
      @else
        <form class="woocommerce-cart-form" action="{{ wc_get_cart_url() }}" method="post">
          @php
            do_action('woocommerce_before_cart');
          @endphp

          <div class="row cart-layout">
            {{-- Left Column - Basket --}}
            <div class="col-lg-7 basket-column">
              <h2 class="basket-title">{{ __('YOUR BASKET', 'woocommerce') }}</h2>

              @php
                do_action('woocommerce_before_cart_table');
              @endphp

              <div class="cart-items-list">
                @php
                  do_action('woocommerce_before_cart_contents');
                @endphp

                @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
                  @php
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                  @endphp

                  @if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key))
                    <div class="cart-item woocommerce-cart-form__cart-item {{ apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key) }}">
                      {{-- Product Image --}}
                      <div class="cart-item__image">
                        @php
                          $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                        @endphp
                        @if (!$product_permalink)
                          {!! $thumbnail !!}
                        @else
                          <a href="{{ $product_permalink }}">{!! $thumbnail !!}</a>
                        @endif
                      </div>

                      {{-- Product Details --}}
                      <div class="cart-item__details">
                        <div class="cart-item__info">
                          {{-- Product Name --}}
                          <h3 class="cart-item__name">
                            @if (!$product_permalink)
                              {!! wp_kses_post($product_name) !!}
                            @else
                              <a href="{{ $product_permalink }}">{!! wp_kses_post($product_name) !!}</a>
                            @endif
                          </h3>

                          {{-- Product Price --}}
                          <div class="cart-item__price">
                            @if ($_product->is_on_sale())
                              <span class="regular-price">{!! wc_price($_product->get_regular_price()) !!}</span>
                              <span class="sale-price">{!! wc_price($_product->get_sale_price()) !!}</span>
                            @else
                              {!! apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key) !!}
                            @endif
                          </div>

                          {{-- Product Meta --}}
                          @php
                            echo wc_get_formatted_cart_item_data($cart_item);
                          @endphp
                        </div>

                        {{-- Remove Item --}}
                        <div class="cart-item__remove">
                          {!! apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                              '<a href="%s" class="remove-item" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M3 18C2.45 18 1.97917 17.8042 1.5875 17.4125C1.19583 17.0208 1 16.55 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8042 17.0208 14.4125 17.4125C14.0208 17.8042 13.55 18 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z" fill="currentColor"/>
                                </svg>
                              </a>',
                              esc_url(wc_get_cart_remove_url($cart_item_key)),
                              esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                              esc_attr($product_id),
                              esc_attr($_product->get_sku())
                            ),
                            $cart_item_key
                          ) !!}
                        </div>
                      </div>

                      {{-- Quantity Controls --}}
                      <div class="cart-item__quantity">
                        @if ($_product->is_sold_individually())
                          @php
                            $min_quantity = 1;
                            $max_quantity = 1;
                          @endphp
                        @else
                          @php
                            $min_quantity = 0;
                            $max_quantity = $_product->get_max_purchase_quantity();
                          @endphp
                        @endif

                        <div class="quantity-wrapper">
                          <button type="button" class="quantity-btn minus" aria-label="{{ __('Decrease quantity', 'woocommerce') }}">
                            <svg width="10" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M14 2H0V0H14V2Z" fill="currentColor"/>
                            </svg>
                          </button>
                          <input
                            type="number"
                            class="input-text qty text"
                            name="cart[{{ $cart_item_key }}][qty]"
                            value="{{ $cart_item['quantity'] }}"
                            aria-label="{{ __('Product quantity', 'woocommerce') }}"
                            min="{{ $min_quantity }}"
                            @if ($max_quantity > 0) max="{{ $max_quantity }}" @endif
                            step="1"
                            inputmode="numeric"
                            autocomplete="off"
                          >
                          <button type="button" class="quantity-btn plus" aria-label="{{ __('Increase quantity', 'woocommerce') }}">
                            <svg width="10" height="10" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M6 14V8H0V6H6V0H8V6H14V8H8V14H6Z" fill="currentColor"/>
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach

                @php
                  do_action('woocommerce_cart_contents');
                  do_action('woocommerce_after_cart_contents');
                @endphp
              </div>

              @php
                do_action('woocommerce_after_cart_table');
              @endphp

              {{-- Coupon Section --}}
              <div class="coupon-section">
                @if (wc_coupons_enabled())
                  <div class="coupon-form">
                    <label for="coupon_code" class="screen-reader-text">{{ __('Coupon:', 'woocommerce') }}</label>
                    <input type="text" name="coupon_code" class="input-text coupon-input" id="coupon_code" value="" placeholder="{{ __('Coupon Code', 'woocommerce') }}" />
                    <button type="submit" class="btn trans-black-btn apply-coupon-btn" name="apply_coupon" value="{{ __('Apply Coupon', 'woocommerce') }}">{{ __('Apply Coupon', 'woocommerce') }}</button>
                    @php
                      do_action('woocommerce_cart_coupon');
                    @endphp
                  </div>
                @endif
              </div>

              {{-- Hidden update cart button --}}
              <button type="submit" class="update-cart-btn" name="update_cart" value="{{ __('Update cart', 'woocommerce') }}" aria-hidden="true" style="display:none;">{{ __('Update cart', 'woocommerce') }}</button>

              @php
                wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');
              @endphp
            </div>

            {{-- Right Column - Order Summary --}}
            <div class="col-lg-5 order-summary-column">
              <div class="order-summary">
                <h2 class="order-summary__title">{{ __('ORDER SUMMARY', 'woocommerce') }}</h2>

                @php
                  do_action('woocommerce_before_cart_totals');
                @endphp

                <div class="order-summary__details">
                  {{-- Subtotal --}}
                  <div class="summary-row subtotal-row">
                    <span class="summary-label">{{ __('Subtotal', 'woocommerce') }}</span>
                    <span class="summary-value">{!! WC()->cart->get_cart_subtotal() !!}</span>
                  </div>

                  {{-- Discount (Coupons) --}}
                  @foreach (WC()->cart->get_coupons() as $code => $coupon)
                    <div class="summary-row discount-row">
                      <span class="summary-label">{{ __('Discount', 'woocommerce') }} ({{ wc_cart_totals_coupon_label($coupon, false) }})</span>
                      <span class="summary-value discount-value">{!! wc_cart_totals_coupon_html($coupon) !!}</span>
                    </div>
                  @endforeach

                  {{-- Shipping / Delivery Fee --}}
                  @php
                    do_action('woocommerce_cart_totals_before_shipping');
                  @endphp

                  @if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
                    @php
                      do_action('woocommerce_cart_totals_before_shipping');
                      wc_cart_totals_shipping_html();
                      do_action('woocommerce_cart_totals_after_shipping');
                    @endphp
                  @elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc'))
                    <div class="summary-row delivery-row">
                      <span class="summary-label">{{ __('Delivery Fee', 'woocommerce') }}</span>
                      <span class="summary-value">{!! wc_price(WC()->cart->get_shipping_total()) !!}</span>
                    </div>
                  @else
                    <div class="summary-row delivery-row">
                      <span class="summary-label">{{ __('Delivery Fee', 'woocommerce') }}</span>
                      <span class="summary-value">{{ __('FREE', 'woocommerce') }}</span>
                    </div>
                  @endif

                  @php
                    do_action('woocommerce_cart_totals_before_order_total');
                  @endphp

                  {{-- Total --}}
                  <div class="summary-row total-row">
                    <div class="total-label-wrapper">
                      <span class="summary-label total-label">{{ __('TOTAL', 'woocommerce') }}</span>
                      @if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax())
                        <span class="includes-tax">({{ __('Includes', 'woocommerce') }} <strong>{!! wc_price(WC()->cart->get_total_tax()) !!}</strong> {{ __('VAT', 'woocommerce') }})</span>
                      @endif
                    </div>
                    <span class="summary-value total-value">{!! WC()->cart->get_total() !!}</span>
                  </div>

                  @php
                    do_action('woocommerce_cart_totals_after_order_total');
                  @endphp
                </div>

                {{-- Checkout Buttons --}}
                <div class="checkout-buttons">
                  @php
                    //do_action('woocommerce_proceed_to_checkout');
                  @endphp

                  <a href="{{ wc_get_checkout_url() }}" class="btn checkout-button proceed-to-checkout">
                    {{ __('Proceed to Checkout', 'woocommerce') }}
                  </a>

                  {{-- Payment Buttons (Apple Pay, Google Pay) --}}
                  <div class="payment-buttons">
                    @php
                      // Call the custom payment buttons function
                      if (function_exists('custom_payment_buttons')) {
                        custom_payment_buttons();
                      }
                    @endphp
                  </div>
                </div>

                @php
                  do_action('woocommerce_after_cart_totals');
                @endphp
              </div>
            </div>
          </div>

          @php
            do_action('woocommerce_after_cart');
          @endphp
        </form>
      @endif

      @php
        do_action('woocommerce_after_main_content');
      @endphp

    </div>
  </div>

  @php
    do_action('get_footer', 'shop');
  @endphp

@endsection
