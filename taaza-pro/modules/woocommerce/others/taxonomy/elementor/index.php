<?php

/**
 * WooCommerce - Elementor Taxonomy Widgets Core Class
 */

namespace TaazaElementor\widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Taaza_Shop_Elementor_Taxonomy_Widgets {

	/**
	 * A Reference to an instance of this class
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	function __construct() {

		add_action( 'taaza_shop_register_widgets', array( $this, 'taaza_shop_register_widgets' ), 10, 1 );

		add_action( 'taaza_shop_register_widget_styles', array( $this, 'taaza_shop_register_widget_styles' ), 10, 1 );
		add_action( 'taaza_shop_register_widget_scripts', array( $this, 'taaza_shop_register_widget_scripts' ), 10, 1 );

		add_action( 'taaza_shop_preview_styles', array( $this, 'taaza_shop_preview_styles') );

	}

	/**
	 * Register widgets
	 */
	function taaza_shop_register_widgets( $widgets_manager ) {

		require taaza_shop_others_taxonomy()->module_dir_path() . 'elementor/widgets/product-cat/class-product-cat.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Cat() );

		require taaza_shop_others_taxonomy()->module_dir_path() . 'elementor/widgets/product-cat-single/class-product-cat-single.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Cat_Single() );

	}

	/**
	 * Register widgets styles
	 */
	function taaza_shop_register_widget_styles( $suffix ) {

		# Product Cat
			wp_register_style( 'wdt-shop-product-cat',
				taaza_shop_others_taxonomy()->module_dir_url() . 'assets/css/style'.$suffix.'.css',
				array()
			);

		# Product Cat Single
			wp_register_style( 'wdt-shop-product-cat-single',
				taaza_shop_others_taxonomy()->module_dir_url() . 'assets/css/style'.$suffix.'.css',
				array()
			);

	}

	/**
	 * Register widgets scripts
	 */
	function taaza_shop_register_widget_scripts( $suffix ) {


	}

	/**
	 * Editor Preview Style
	 */
	function taaza_shop_preview_styles() {

		# Product Cat
			wp_enqueue_style( 'wdt-shop-product-cat' );

		# Product Cat Single
			wp_enqueue_style( 'wdt-shop-product-cat-single' );

	}

}

Taaza_Shop_Elementor_Taxonomy_Widgets::instance();