<?php
/**
 * Custom Checkout Payment Section (overrides WooCommerce default)
 *
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					?>
					<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
						<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />
						<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>"><?php echo $gateway->get_title(); ?></label>
						<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
							<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) echo 'style="display:none;"'; ?>>
								<?php $gateway->payment_fields(); ?>
							</div>
						<?php endif; ?>
					</li>
					<?php
				}
			} else {
				echo '<li>';
				wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ), 'notice' );
				echo '</li>';
			}
			?>
		</ul>
	<?php endif; ?>
	<div class="place-order custom-place-order--from-template--override">
		<?php
		do_action('woocommerce_review_order_before_submit');
		wc_get_template('checkout/terms.php');

		$terms_page_id = wc_terms_and_conditions_page_id();
		$terms_url = $terms_page_id ? get_permalink($terms_page_id) : '';
		$terms_label = __('I have read and agree to the website terms and conditions*', 'woocommerce');

		$order_button_text = apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce'));
		?>

		<p class="form-row validate-required fallback-terms-row">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox" for="custom_terms_agree">
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="custom_terms_agree" value="1" required>
				<span>
					<?php if ( $terms_url ) : ?>
						<a href="<?php echo esc_url( $terms_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $terms_label ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $terms_label ); ?>
					<?php endif; ?>
				</span>
			</label>
		</p>

		<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="<?php echo esc_attr( $order_button_text ); ?>" data-value="<?php echo esc_attr( $order_button_text ); ?>">
			<?php echo esc_html( $order_button_text ); ?>
		</button>

		<?php
		do_action('woocommerce_review_order_after_submit');
		wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce');
		?>
	</div>
</div>
<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
