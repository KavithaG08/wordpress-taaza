<?php

/**
 * Listing Customizer - Shop Settings
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Pro_Listing_Customizer_Shop' ) ) {

    class Taaza_Pro_Listing_Customizer_Shop {

        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            add_filter( 'taaza_shop_pro_customizer_default', array( $this, 'default' ) );
            add_filter( 'taaza_woo_shop_page_default_settings', array( $this, 'shop_page_default_settings' ), 10, 1 );
            add_action( 'customize_register', array( $this, 'register' ), 15);

        }

        function default( $option ) {

            $settings = taaza_woo_listing_shop()->woo_default_settings();
            extract($settings);

            if( $product_style_template == 'predefined' ) {
                $option['wdt-woo-shop-page-product-style-template'] = 'predefined-template-'.$product_style_custom_template;
            } else {
                $option['wdt-woo-shop-page-product-style-template'] = $product_style_custom_template;
            }

            $option['wdt-woo-shop-page-product-per-page']  = $product_per_page;
            $option['wdt-woo-shop-page-product-layout']    = $product_layout;

            // Default Values from Shop Plugin
            $option['wdt-woo-shop-page-show-sorter-on-header']  = $show_sorter_on_header;
            $option['wdt-woo-shop-page-sorter-header-elements'] = $sorter_header_elements;
            $option['wdt-woo-shop-page-show-sorter-on-footer']  = $show_sorter_on_footer;
            $option['wdt-woo-shop-page-sorter-footer-elements'] = $sorter_footer_elements;

            return $option;

        }

        function shop_page_default_settings( $settings ) {

            $product_style_custom_template = taaza_customizer_settings('wdt-woo-shop-page-product-style-template' );
            if( isset($product_style_custom_template) && !empty($product_style_custom_template) ) {
                $settings['product_style_template']        = 'custom';
                $settings['product_style_custom_template'] = $product_style_custom_template;
            }

            $product_per_page              = taaza_customizer_settings('wdt-woo-shop-page-product-per-page' );
            $settings['product_per_page']  = $product_per_page;

            $product_layout                = taaza_customizer_settings('wdt-woo-shop-page-product-layout' );
            $settings['product_layout']    = $product_layout;

            return $settings;

        }

        function register( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'woocommerce-shop-page-section',
                    array(
                        'title'    => esc_html__('Shop Page', 'taaza-pro'),
                        'panel'    => 'woocommerce-main-section',
                        'priority' => 10,
                    )
                )
            );

                /**
                 * Option : Product Style Template
                 */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-woo-shop-page-product-style-template]', array(
                            'type'              => 'option',
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-woo-shop-page-product-style-template]', array(
                                'type'     => 'select',
                                'label'    => esc_html__( 'Product Style Template', 'taaza-pro'),
                                'section'  => 'woocommerce-shop-page-section',
                                'choices'  => taaza_woo_listing_customizer_settings()->product_templates_list()
                            )
                        )
                    );

                /**
                 * Option : Products Per Page
                 */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-woo-shop-page-product-per-page]', array(
                            'type' => 'option',
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-woo-shop-page-product-per-page]', array(
                                'type'        => 'number',
                                'label'       => esc_html__( 'Products Per Page', 'taaza-pro' ),
                                'section'     => 'woocommerce-shop-page-section'
                            )
                        )
                    );

                /**
                 * Option : Product Layout
                 */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-woo-shop-page-product-layout]', array(
                            'type' => 'option',
                        )
                    );

                    $wp_customize->add_control( new Taaza_Customize_Control_Radio_Image(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-woo-shop-page-product-layout]', array(
                            'type' => 'wdt-radio-image',
                            'label' => esc_html__( 'Columns', 'taaza-pro'),
                            'section' => 'woocommerce-shop-page-section',
                            'choices' => apply_filters( 'taaza_woo_shop_columns_options', array(
                                1 => array(
                                    'label' => esc_html__( 'One Column', 'taaza-pro' ),
                                    'path' => TAAZA_PRO_DIR_URL . 'modules/woocommerce/shop/customizer/images/one-column.png'
                                ),
                                2 => array(
                                    'label' => esc_html__( 'One Half Column', 'taaza-pro' ),
                                    'path' => TAAZA_PRO_DIR_URL . 'modules/woocommerce/shop/customizer/images/one-half-column.png'
                                ),
                                3 => array(
                                    'label' => esc_html__( 'One Third Column', 'taaza-pro' ),
                                    'path' => TAAZA_PRO_DIR_URL . 'modules/woocommerce/shop/customizer/images/one-third-column.png'
                                ),
                                4 => array(
                                    'label' => esc_html__( 'One Fourth Column', 'taaza-pro' ),
                                    'path' => TAAZA_PRO_DIR_URL . 'modules/woocommerce/shop/customizer/images/one-fourth-column.png'
                                )
                            ))
                        )
                    ));

        }

    }

}


if( !function_exists('taaza_listing_customizer_shop') ) {
	function taaza_listing_customizer_shop() {
		return Taaza_Pro_Listing_Customizer_Shop::instance();
	}
}

taaza_listing_customizer_shop();