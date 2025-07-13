<?php

/*
 * Product single image - Additional Labels
 */

if( ! function_exists( 'taaza_shop_woo_loop_product_additional_360_viewer_label' ) ) {

	function taaza_shop_woo_loop_product_additional_360_viewer_label( $single_template ) {

		$settings = taaza_woo_single_core()->woo_default_settings();
		extract($settings);

		if($product_show_360_viewer) {
			echo do_shortcode('[taaza_shop_product_images_360viewer product_id="" enable_popup_viewer="true" source="single-product" class="" /]');
		}

	}

	add_action('taaza_woo_loop_product_additional_labels', 'taaza_shop_woo_loop_product_additional_360_viewer_label', 10);

}