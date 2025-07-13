<?php

/**
 * WooCommerce - Single - Module - Sticky Cart - Customizer Settings
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Customizer_Single_Sticky_Cart' ) ) {

    class Taaza_Shop_Customizer_Single_Sticky_Cart {

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

            $product_addtocart_sticky             = taaza_customizer_settings('wdt-single-product-addtocart-sticky' );
            $settings['product_addtocart_sticky'] = $product_addtocart_sticky;

            return $settings;

        }

        function register( $wp_customize ) {

            /**
            * Option : Sticky Add to Cart
            */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[wdt-single-product-addtocart-sticky]', array(
                        'type' => 'option'
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Switch(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-addtocart-sticky]', array(
                            'type'    => 'wdt-switch',
                            'label'   => esc_html__( 'Sticky Add to Cart', 'taaza-pro'),
                            'section' => 'woocommerce-single-page-default-section',
                            'choices' => array(
                                'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                'off' => esc_attr__( 'No', 'taaza-pro' )
                            )
                        )
                    )
                );

        }

    }

}


if( !function_exists('taaza_shop_customizer_single_sticky_cart') ) {
	function taaza_shop_customizer_single_sticky_cart() {
		return Taaza_Shop_Customizer_Single_Sticky_Cart::instance();
	}
}

taaza_shop_customizer_single_sticky_cart();