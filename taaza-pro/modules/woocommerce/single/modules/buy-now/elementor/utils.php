<?php

/*
* Update Summary - Options Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_options_bn_render' ) ) {
	function taaza_shop_woo_single_summary_options_bn_render( $options ) {

		$options['buy_now'] = esc_html__('Summary Buy Now', 'taaza-pro');
		return $options;

	}
	add_filter( 'taaza_shop_woo_single_summary_options', 'taaza_shop_woo_single_summary_options_bn_render', 10, 1 );

}

/*
* Update Summary - Styles Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_styles_bn_render' ) ) {
	function taaza_shop_woo_single_summary_styles_bn_render( $styles ) {

		array_push( $styles, 'wdt-shop-buy-now' );
		return $styles;

	}
	add_filter( 'taaza_shop_woo_single_summary_styles', 'taaza_shop_woo_single_summary_styles_bn_render', 10, 1 );

}

/*
* Update Summary - Scripts Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_scripts_bn_render' ) ) {
	function taaza_shop_woo_single_summary_scripts_bn_render( $scripts ) {

		array_push( $scripts, 'wdt-shop-buy-now' );
		return $scripts;

	}
	add_filter( 'taaza_shop_woo_single_summary_scripts', 'taaza_shop_woo_single_summary_scripts_bn_render', 10, 1 );

}