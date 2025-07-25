<?php

/**
 * WooCommerce - Single - Module - Additional Tabs
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Single_Module_Additional_Tabs' ) ) {

    class Taaza_Shop_Single_Module_Additional_Tabs {

        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            // Load Modules
                $this->load_modules();

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
                    return TAAZA_MODULE_DIR . '/woocommerce/single/modules/additional-tabs/';
                } else {
                    return trailingslashit( plugin_dir_path( __FILE__ ) );
                }

            }

            function module_dir_url() {

                if( taaza_is_file_in_theme( __FILE__ ) ) {
                    return TAAZA_MODULE_URI . '/woocommerce/single/modules/additional-tabs/';
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

                        // Metabox
                            include_once $this->module_dir_path() . 'metabox/index.php';

                        // Elementor
                            include_once $this->module_dir_path() . 'elementor/index.php';

                    }

                // Includes
                    include_once $this->module_dir_path() . 'includes/index.php';

            }


        /*
        CSS
        */
            function woo_css( $css ) {

                $product_template = taaza_shop_woo_product_single_template_option();

                if( $product_template == 'custom-template' ) {

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

                if( $product_template == 'custom-template' ) {

                    wp_enqueue_script('jquery-nicescroll', $this->module_dir_url() . 'assets/js/jquery.nicescroll.js', array('jquery'), false, true);

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

if( !function_exists('taaza_shop_single_module_additional_tabs') ) {
	function taaza_shop_single_module_additional_tabs() {
        $reflection = new ReflectionClass('Taaza_Shop_Single_Module_Additional_Tabs');
        return $reflection->newInstanceWithoutConstructor();
	}
}

Taaza_Shop_Single_Module_Additional_Tabs::instance();