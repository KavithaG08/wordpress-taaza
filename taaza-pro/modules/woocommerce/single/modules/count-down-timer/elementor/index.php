<?php

/**
 * WooCommerce - Elementor Single Widgets Core Class
 */

namespace TaazaElementor\widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Taaza_Shop_Elementor_Single_Count_Down_Timer_Widgets {

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

		$this->taaza_shop_load_ctaaza_modules();

		add_action( 'taaza_shop_register_widget_styles', array( $this, 'taaza_shop_register_widget_styles' ), 10, 1 );
		add_action( 'taaza_shop_register_widget_scripts', array( $this, 'taaza_shop_register_widget_scripts' ), 10, 1 );

		add_action( 'taaza_shop_preview_styles', array( $this, 'taaza_shop_preview_styles') );

	}

	/**
	 * Init
	 */
	function taaza_shop_load_ctaaza_modules() {

		require taaza_shop_single_module_count_down_timer()->module_dir_path() . 'elementor/utils.php';

	}

	/**
	 * Register widgets styles
	 */
	function taaza_shop_register_widget_styles( $suffix ) {

		wp_register_style( 'wdt-shop-coundown-timer',
			taaza_shop_single_module_count_down_timer()->module_dir_url() . 'assets/css/style'.$suffix.'.css',
			array()
		);

	}

	/**
	 * Register widgets scripts
	 */
	function taaza_shop_register_widget_scripts( $suffix ) {

		wp_register_script( 'jquery-downcount',
			taaza_shop_single_module_count_down_timer()->module_dir_url() . 'assets/js/jquery.downcount'.$suffix.'.js',
			array( 'jquery' ),
			false,
			true
		);

		wp_register_script( 'wdt-shop-coundown-timer',
			taaza_shop_single_module_count_down_timer()->module_dir_url() . 'assets/js/scripts'.$suffix.'.js',
			array( 'jquery' ),
			false,
			true
		);

	}

	/**
	 * Editor Preview Style
	 */
	function taaza_shop_preview_styles() {

		wp_enqueue_style( 'wdt-shop-coundown-timer' );

	}

}

Taaza_Shop_Elementor_Single_Count_Down_Timer_Widgets::instance();