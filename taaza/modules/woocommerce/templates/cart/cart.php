<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="wdt-cart-container wdt-cart-split-style">

	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-thumbnail"><?php esc_html_e( 'Rooms', 'taaza' ); ?></th>
					<th class="product-name"><?php esc_html_e( 'Details', 'taaza' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Quantity', 'taaza' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'taaza' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<td class="product-thumbnail">
								<div class="product-thumbnail-wrapper">
									<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

										if ( ! $product_permalink ) {
											echo taaza_html_output($thumbnail); // PHPCS: XSS ok.
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
										}
									?>

									<?php
										echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'taaza' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											),
											$cart_item_key
										);
									?>
								</div>
							</td>

							<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'taaza' ); ?>">
								<?php

									$room_id = isset( $cart_item['room_id'] ) ? $cart_item['room_id'] : '';

									if ( $room_id ) {
										$room_permalink = get_permalink( $room_id );

										if ( $room_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $room_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
										}
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
									}
									    
								// Retrieve booking details from cart item meta
									$check_in = isset( $cart_item['check_in'] ) ? $cart_item['check_in'] : '';
									$check_out = isset( $cart_item['check_out'] ) ? $cart_item['check_out'] : '';
									$adults = isset( $cart_item['adults'] ) ? $cart_item['adults'] : 0;
									$children = isset( $cart_item['children'] ) ? $cart_item['children'] : 0;
									$extra_services = isset( $cart_item['extra_services'] ) ? $cart_item['extra_services'] : [];

									$check_in_date = date( 'D, d M Y', strtotime( $check_in ) );
									$check_out_date = date( 'D, d M Y', strtotime( $check_out ) );

									$check_in_timestamp = strtotime( $check_in );
									$check_out_timestamp = strtotime( $check_out );
									$night_count = max( ( $check_out_timestamp - $check_in_timestamp ) / ( 60 * 60 * 24 ), 1 );

									$extra_services_list = !empty($extra_services) ? implode(', ', $extra_services) : 'None';

									echo '<br><strong>Price:</strong> ' .apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) . '';
									echo '<br><strong>Reservation:</strong> ' . esc_html( $check_in_date ) . '/' . esc_html( $check_out_date ) . ' (' . esc_html( $night_count ) . ' night' . ( $night_count > 1 ? 's' : '') . ')';
									echo '<br><strong>Guests:</strong> ' . esc_html( $adults ) . ' Adult' . ( $adults > 1 ? 's' : '' );
									if ($children > 0) {
										echo ' / ' . esc_html( $children ) . ' Child' . ( $children > 1 ? 'ren' : '');
									}
									echo '<br><strong>Extra Services:</strong> ' . esc_html( $extra_services_list );

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'taaza' ) . '</p>', $product_id ) );
									}
								?>
							</td>

							<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'taaza' ); ?>">
								<label><?php esc_html_e( 'Quantity', 'taaza' ); ?></label>
								<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										), $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
								?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'taaza' ); ?>">
								<label><?php esc_html_e( 'Subtotal', 'taaza' ); ?></label>
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</td>
						</tr>
						<?php
					}
				}
				?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

			</tbody>
		</table>

		<div class="actions wdt-cart-button">

			<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'taaza' ); ?>"><?php esc_html_e( 'Update cart', 'taaza' ); ?></button>

		</div>

		<div class="actions wdt-cart-coupon-holder">

			<?php if ( wc_coupons_enabled() ) { ?>
				<div class="coupon">
					<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'taaza' ); ?></label> 
					<div class="coupon-code-apply-input">
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'taaza' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'taaza' ); ?>"><?php esc_html_e( 'Apply coupon', 'taaza' ); ?></button>
					</div>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			<?php } ?>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

		</div>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="cart-collaterals">
		<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
		?>
	</div>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>