<?php

/*
* Update Summary Options Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_options_ssf_render' ) ) {
	function taaza_shop_woo_single_summary_options_ssf_render( $options ) {

		$options['share_follow'] = esc_html__('Summary Share / Follow', 'taaza-pro');
		return $options;

	}
	add_filter( 'taaza_shop_woo_single_summary_options', 'taaza_shop_woo_single_summary_options_ssf_render', 10, 1 );

}


/*
* Update Summary - Styles Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_styles_ssf_render' ) ) {
	function taaza_shop_woo_single_summary_styles_ssf_render( $styles ) {

		array_push( $styles, 'wdt-shop-social-share-and-follow' );
		return $styles;

	}
	add_filter( 'taaza_shop_woo_single_summary_styles', 'taaza_shop_woo_single_summary_styles_ssf_render', 10, 1 );

}
