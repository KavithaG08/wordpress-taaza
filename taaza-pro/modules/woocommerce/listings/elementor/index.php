<?php

/**
 * WooCommerce - Elementor Listings Widgets Core Class
 */

namespace TaazaElementor\widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Taaza_Pro_Elementor_Listings_Widgets {

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

		$this->taaza_shop_load_modules();

		add_action( 'taaza_shop_register_widgets', array( $this, 'taaza_shop_register_widgets' ), 10, 1 );

		add_action( 'taaza_shop_register_widget_styles', array( $this, 'taaza_shop_register_widget_styles' ), 10, 1 );
		add_action( 'taaza_shop_register_widget_scripts', array( $this, 'taaza_shop_register_widget_scripts' ), 10, 1 );

		add_action( 'taaza_shop_preview_styles', array( $this, 'taaza_shop_preview_styles') );
		add_action( 'taaza_shop_preview_scripts', array( $this, 'taaza_shop_preview_scripts') );

	}

	/**
	 * Init
	 */
	function taaza_shop_load_modules() {

		require TAAZA_PRO_DIR_PATH . 'modules/woocommerce/listings/elementor/widgets/products/shortcodes.php';

	}

	/**
	 * Register widgets
	 */
	function taaza_shop_register_widgets( $widgets_manager ) {

		require TAAZA_PRO_DIR_PATH . 'modules/woocommerce/listings/elementor/widgets/products/class-widget-products.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Products() );

	}

	/**
	 * Register widgets styles
	 */
	function taaza_shop_register_widget_styles( $suffix ) {

		# Swiper
			wp_register_style( 'css-swiper',
				TAAZA_PRO_DIR_URL . 'modules/woocommerce/listings/elementor/widgets/products/assets/css/swiper.min'.$suffix.'.css',
				array()
			);

		# Carousel
			wp_register_style( 'wdt-shop-products-carousel',
				TAAZA_MODULE_URI . '/woocommerce/assets/css/carousel.css',
				array()
			);

		# Products
			wp_register_style( 'wdt-shop-products',
				TAAZA_PRO_DIR_URL . 'modules/woocommerce/listings/elementor/widgets/products/assets/css/style'.$suffix.'.css',
				array()
			);

	}

	/**
	 * Register widgets scripts
	 */
	function taaza_shop_register_widget_scripts( $suffix ) {

		# Swiper
			wp_register_script( 'product-jquery-swiper',
				TAAZA_PRO_DIR_URL . 'modules/woocommerce/listings/elementor/widgets/products/assets/js/swiper.min'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

		# Products
			wp_register_script( 'wdt-shop-products',
				TAAZA_PRO_DIR_URL . 'modules/woocommerce/listings/elementor/widgets/products/assets/js/script'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

			wp_localize_script('wdt-shop-products', 'wdtShopScObjects',  array (
				'ajaxurl' => esc_url( admin_url('admin-ajax.php') )
			));


		# Products Admin
			wp_register_script( 'wdt-shop-admin',
				TAAZA_PRO_DIR_URL . 'modules/woocommerce/listings/elementor/widgets/products/assets/js/admin'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

	}

	/**
	 * Editor Preview Style
	 */
	function taaza_shop_preview_styles() {

		# Products
			wp_enqueue_style( 'swiper' );
			wp_enqueue_style( 'wdt-shop-products' );

	}

	/**
	 * Editor Preview Script
	 */
	function taaza_shop_preview_scripts() {

		# Products Admin
			wp_enqueue_script( 'wdt-shop-admin' );

	}

}

Taaza_Pro_Elementor_Listings_Widgets::instance();