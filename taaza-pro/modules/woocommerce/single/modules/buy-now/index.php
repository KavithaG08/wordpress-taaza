<?php

/**
 * WooCommerce - Single - Module - CountDown Timer
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Single_Module_Buy_Now' ) ) {

    class Taaza_Shop_Single_Module_Buy_Now {

        private static $_instance = null;

        private $product_buy_now;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            // Load Modules
                $this->load_modules();

            // Enable Addiitonal Info - Default Single Page
                $settings = taaza_woo_single_core()->woo_default_settings();
                extract($settings);
                $this->product_buy_now = $product_buy_now;

            // Js Variables
                add_filter( 'taaza_woo_objects', array ( $this, 'woo_objects' ), 10, 1 );

            // CSS
                add_filter( 'taaza_woo_css', array( $this, 'woo_css'), 10, 1 );

            // JS
                add_filter( 'taaza_woo_js', array( $this, 'woo_js'), 10, 1 );

        }

        /*
        Module Paths
        */

            function module_dir_path() {

                if( taaza_is_file_in_theme( __FILE__ ) ) {
                    return TAAZA_MODULE_DIR . '/woocommerce/single/modules/buy-now/';
                } else {
                    return trailingslashit( plugin_dir_path( __FILE__ ) );
                }

            }

            function module_dir_url() {

                if( taaza_is_file_in_theme( __FILE__ ) ) {
                    return TAAZA_MODULE_URI . '/woocommerce/single/modules/buy-now/';
                } else {
                    return trailingslashit( plugin_dir_url( __FILE__ ) );
                }

            }


        /*
        Load Modules
        */

            function load_modules() {

                // If Theme-Plugin is activated

                    if( function_exists( 'taaza_pro' ) ) {

                        // Customizer
                            include_once $this->module_dir_path() . 'customizer/index.php';

                        // Elementor
                            include_once $this->module_dir_path() . 'elementor/index.php';

                    }

                // Includes
                    include_once $this->module_dir_path() . 'includes/index.php';

            }


        /*
        Js Variables
        */

            function woo_objects( $woo_objects ) {

                $product_template = taaza_shop_woo_product_single_template_option();

                $woo_objects['enable_buy_now_scripts'] = esc_js(false);
                if( $this->product_buy_now && $product_template == 'woo-default' ) {
                    $woo_objects['enable_buy_now_scripts'] = esc_js(true);
                }

                return $woo_objects;

            }

        /*
        CSS
        */
            function woo_css( $css ) {

                $product_template = taaza_shop_woo_product_single_template_option();

                if( $this->product_buy_now && $product_template == 'woo-default' ) {

                    $css_file_path = $this->module_dir_path() . 'assets/css/style.css';

                    if( file_exists ( $css_file_path ) ) {

                        $css .= file_get_contents( $css_file_path );

                    }

                }

                return $css;

            }

        /*
        JS
        */
            function woo_js( $js ) {

                $product_template = taaza_shop_woo_product_single_template_option();

                if( $this->product_buy_now && $product_template == 'woo-default' ) {

                    $js_file_path = $this->module_dir_path() . 'assets/js/scripts.js';

                    if( file_exists ( $js_file_path ) ) {

                        ob_start();
                        include( $js_file_path );
                        $js .= "\n\n".ob_get_clean();

                    }

                }

                return $js;

            }

    }

}


if( !function_exists('taaza_shop_single_module_buy_now') ) {
	function taaza_shop_single_module_buy_now() {
        $reflection = new ReflectionClass('Taaza_Shop_Single_Module_Buy_Now');
        return $reflection->newInstanceWithoutConstructor();
	}
}

if( class_exists( 'Taaza_Shop_Single_Module_Custom_template' ) ) {
    Taaza_Shop_Single_Module_Buy_Now::instance();
}