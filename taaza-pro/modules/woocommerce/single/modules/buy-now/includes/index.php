<?php

/*
 * Product Buy Now
 */

if ( ! function_exists( 'taaza_shop_products_buy_now' ) ) {

	function taaza_shop_products_buy_now() {

		if(is_product()) {

			$settings = taaza_woo_single_core()->woo_default_settings();
			extract($settings);

			$product_template = taaza_shop_woo_product_single_template_option();

			if( $product_template == 'woo-default' ) {

				if(!$product_buy_now) {
					return;
				}

			}

		}

		echo '<div class="product-buy-now">';
			echo '<a href="#" class="button quick_buy_now_button" >'.esc_html__('Buy Now', 'taaza-pro').'</a>';
		echo '</div>';

	}

	add_action( 'woocommerce_single_product_summary', 'taaza_shop_products_buy_now', 38 );

}