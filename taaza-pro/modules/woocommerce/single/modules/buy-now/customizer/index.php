<?php

/**
 * WooCommerce - Single - Module - Buy Now - Customizer Settings
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Customizer_Single_Buy_Now' ) ) {

    class Taaza_Shop_Customizer_Single_Buy_Now {

        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            add_filter( 'taaza_woo_single_page_settings', array( $this, 'single_page_settings' ), 10, 1 );
            add_action( 'customize_register', array( $this, 'register' ), 15);

        }

        function single_page_settings( $settings ) {

            $product_buy_now                   = taaza_customizer_settings('wdt-single-product-buy-now' );
            $settings['product_buy_now']       = $product_buy_now;

            return $settings;

        }

        function register( $wp_customize ) {

             /**
            * Option : Enable Buy Now
            */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[wdt-single-product-buy-now]', array(
                        'type' => 'option'
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Switch(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-buy-now]', array(
                            'type'    => 'wdt-switch',
                            'label'   => esc_html__( 'Enable Buy Now', 'taaza-pro'),
                            'section' => 'woocommerce-single-page-default-section',
                            'choices' => array(
                                'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                'off' => esc_attr__( 'No', 'taaza-pro' )
                            ),
                            'description'   => esc_html__('This option is applicable only for "WooCommerce Default" single page.', 'taaza-pro')
                        )
                    )
                );

        }

    }

}


if( !function_exists('taaza_shop_customizer_single_buy_now') ) {
	function taaza_shop_customizer_single_buy_now() {
		return Taaza_Shop_Customizer_Single_Buy_Now::instance();
	}
}

taaza_shop_customizer_single_buy_now();