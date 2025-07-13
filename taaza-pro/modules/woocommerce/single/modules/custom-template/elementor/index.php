<?php

/**
 * WooCommerce - Elementor Single Widgets Core Class
 */

namespace TaazaElementor\widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Taaza_Shop_Elementor_Single_Widgets {

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

	}

	/**
	 * Init
	 */
	function taaza_shop_load_modules() {

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/utils.php';

	}

	/**
	 * Register widgets
	 */
	function taaza_shop_register_widgets( $widgets_manager ) {

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/images-carousel/class-product-images-carousel.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Images_Carousel() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/images-default/class-product-images-default.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Images_Default() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/images-list/class-product-images-list.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Images_List() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/product-tabs/class-product-tabs.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Tabs() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/product-tabs-exploded/class-product-tabs-exploded.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Tabs_Exploded() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/related-products/class-related-products.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Related_Products() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/summary/class-product-summary.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Summary() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/summary-nav-bar/class-product-summary-nav-bar.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Product_Summary_Nav_bar() );

		require taaza_shop_single_module_custom_template()->module_dir_path() . 'elementor/widgets/upsell-products/class-upsell-products.php';
		$widgets_manager->register( new Taaza_Shop_Widget_Upsell_Products() );




	}

	/**
	 * Register widgets styles
	 */
	function taaza_shop_register_widget_styles( $suffix ) {

		# Images Carousel

			wp_register_style( 'swiper',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/images-carousel/assets/css/swiper.min'.$suffix.'.css',
				array()
			);

			wp_register_style( 'wdt-shop-product-single-images-carousel',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/images-carousel/assets/css/style'.$suffix.'.css',
				array()
			);

		# Images List

			wp_register_style( 'wdt-shop-product-single-images-list',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/images-list/assets/css/style'.$suffix.'.css',
				array()
			);

		# Product Tabs

			wp_register_style( 'wdt-shop-product-single-tabs',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/product-tabs/assets/css/style'.$suffix.'.css',
				array()
			);

		# Product Tabs Exploded

			wp_register_style( 'wdt-shop-product-single-tabs-exploded',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/product-tabs-exploded/assets/css/style'.$suffix.'.css',
				array()
			);

		# Related Products
			wp_register_style( 'swiper',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/related-products/assets/css/swiper.min'.$suffix.'.css',
				array()
			);
			wp_register_style( 'wdt-shop-product-single-related-products',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/related-products/assets/css/style'.$suffix.'.css',
				array()
			);

		# Summary

			wp_register_style( 'wdt-shop-product-single-summary',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/summary/assets/css/style'.$suffix.'.css',
				array()
			);

		# Summary Nav Bar

			wp_register_style( 'wdt-shop-product-single-summary-nav-bar',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/summary-nav-bar/assets/css/style'.$suffix.'.css',
				array()
			);

		# Upsell Products

			wp_register_style( 'wdt-shop-product-single-upsell-products',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/upsell-products/assets/css/style'.$suffix.'.css',
				array()
			);

	}

	/**
	 * Register widgets scripts
	 */
	function taaza_shop_register_widget_scripts( $suffix ) {

		# Libraries

			wp_register_script( 'jquery-nicescroll',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/assets/js/jquery.nicescroll'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

		# Images Carousel

			wp_register_script( 'single-product-jquery-swiper',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/images-carousel/assets/js/swiper.min'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

			wp_register_script( 'wdt-shop-product-single-images-carousel',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/images-carousel/assets/js/script'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

		#Related prodcut
			wp_register_script( 'related-products-jquery-swiper',
			taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/related-products/assets/js/swiper.min'.$suffix.'.js',
			array( 'jquery' ),
			false,
			true
		);

		wp_register_script( 'wdt-shop-product-single-related-products',
			taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/related-products/assets/js/script'.$suffix.'.js',
			array( 'jquery' ),
			false,
			true
		);

		# Images List

			wp_register_script( 'wdt-shop-product-single-images-list',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/images-list/assets/js/script'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

		# Product Tabs - Exploded

			wp_register_script( 'wdt-shop-product-single-tabs-exploded',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/product-tabs-exploded/assets/js/script'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

		# Summary

			wp_register_script( 'wdt-shop-product-single-summary',
				taaza_shop_single_module_custom_template()->module_dir_url() . 'elementor/widgets/summary/assets/js/script'.$suffix.'.js',
				array( 'jquery' ),
				false,
				true
			);

	}

	/**
	 * Editor Preview Style
	 */
	function taaza_shop_preview_styles() {

		# Images Carousel
			wp_enqueue_style( 'swiper' );
			wp_enqueue_style( 'wdt-shop-product-single-images-carousel' );

		# Images List
			wp_enqueue_style( 'wdt-shop-product-single-images-list' );

		# Product Tabs
			wp_enqueue_style( 'wdt-shop-product-single-tabs' );

		# Product Tabs Exploded
			wp_enqueue_style( 'wdt-shop-product-single-tabs-exploded' );

		# Related Products
			wp_enqueue_style( 'swiper' );
			wp_enqueue_style( 'wdt-shop-product-single-related-products' );

		# Summary
			wp_enqueue_style( 'wdt-shop-product-single-summary' );

		# Summary Nav Bar
			wp_enqueue_style( 'wdt-shop-product-single-summary-nav-bar' );

		# Upsell Products
			wp_enqueue_style( 'wdt-shop-product-single-upsell-products' );

	}

}

Taaza_Shop_Elementor_Single_Widgets::instance();