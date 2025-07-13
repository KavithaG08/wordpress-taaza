<?php

/**
 * WooCommerce - Others - Cart Notification - Customizer Settings
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Customizer_Others_Custom_Product_Type' ) ) {

    class Taaza_Shop_Customizer_Others_Custom_Product_Type {

        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            add_filter( 'taaza_woo_others_settings', array( $this, 'others_settings' ), 10, 1 );
            add_action( 'customize_register', array( $this, 'register' ), 15);

        }

        function others_settings( $settings ) {

            $custom_product_types                   = taaza_customizer_settings('wdt-woo-custom-product-types' );
            $settings['custom_product_types']       = $custom_product_types;

            return $settings;

        }

        function register( $wp_customize ) {

            /**
             * Option : Custom Product Types
             */

                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[wdt-woo-custom-product-types]', array(
                        'type' => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-woo-custom-product-types]', array(
                            'type'        => 'textarea',
                            'label'       => esc_html__( 'Custom Product Types', 'taaza-shop' ),
                            'description'       => esc_html__( 'Add custom product types separated by commas.', 'taaza-shop' ),
                            'section'     => 'woocommerce-others-section'
                        )
                    )
                );

        }

    }

}


if( !function_exists('taaza_shop_customizer_others_custom_product_type') ) {
	function taaza_shop_customizer_others_custom_product_type() {
		return Taaza_Shop_Customizer_Others_Custom_Product_Type::instance();
	}
}

taaza_shop_customizer_others_custom_product_type();