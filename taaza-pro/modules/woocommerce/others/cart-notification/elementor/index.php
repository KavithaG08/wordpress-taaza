<?php

/**
 * WooCommerce - Elementor Cart Notification Widgets Core Class
 */

namespace TaazaElementor\widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Taaza_Shop_Elementor_Cart_Notification_Widgets {

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

		require taaza_shop_others_cart_notification()->module_dir_path() . 'elementor/widgets/menu-icon/class-widget-menu-icon.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Menu_Icon() );

	}

	/**
	 * Register widgets styles
	 */
	function taaza_shop_register_widget_styles( $suffix ) {

			wp_register_style( 'wdt-shop-cart-widgets',
				taaza_shop_others_cart_notification()->module_dir_url() . 'assets/css/style'.$suffix.'.css',
				array()
			);

			wp_register_style( 'wdt-shop-menu-icon',
				taaza_shop_others_cart_notification()->module_dir_url() . 'elementor/widgets/menu-icon/assets/css/style'.$suffix.'.css',
				array()
			);

	}

	/**
	 * Register widgets scripts
	 */
	function taaza_shop_register_widget_scripts( $suffix ) {

			wp_register_script( 'jquery-nicescroll',
				taaza_shop_others_cart_notification()->module_dir_url() . 'assets/js/jquery.nicescroll'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

			wp_register_script( 'wdt-shop-menu-icon',
				taaza_shop_others_cart_notification()->module_dir_url() . 'elementor/widgets/menu-icon/assets/js/script'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

	}

	/**
	 * Editor Preview Style
	 */
	function taaza_shop_preview_styles() {

		wp_enqueue_style( 'wdt-shop-cart-widgets' );
		wp_enqueue_style( 'wdt-shop-menu-icon' );

	}

}

Taaza_Shop_Elementor_Cart_Notification_Widgets::instance();