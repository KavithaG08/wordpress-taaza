<?php

/*
* Update Summary - Options Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_options_ctaaza_render' ) ) {
	function taaza_shop_woo_single_summary_options_ctaaza_render( $options ) {

		$options['countdown'] = esc_html__('Summary Count Down', 'taaza-pro');
		return $options;

	}
	add_filter( 'taaza_shop_woo_single_summary_options', 'taaza_shop_woo_single_summary_options_ctaaza_render', 10, 1 );

}

/*
* Update Summary - Styles Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_styles_ctaaza_render' ) ) {
	function taaza_shop_woo_single_summary_styles_ctaaza_render( $styles ) {

		array_push( $styles, 'wdt-shop-coundown-timer' );
		return $styles;

	}
	add_filter( 'taaza_shop_woo_single_summary_styles', 'taaza_shop_woo_single_summary_styles_ctaaza_render', 10, 1 );

}

/*
* Update Summary - Scripts Filter
*/

if( ! function_exists( 'taaza_shop_woo_single_summary_scripts_ctaaza_render' ) ) {
	function taaza_shop_woo_single_summary_scripts_ctaaza_render( $scripts ) {

		array_push( $scripts, 'jquery-downcount' );
		array_push( $scripts, 'wdt-shop-coundown-timer' );
		return $scripts;

	}
	add_filter( 'taaza_shop_woo_single_summary_scripts', 'taaza_shop_woo_single_summary_scripts_ctaaza_render', 10, 1 );

}