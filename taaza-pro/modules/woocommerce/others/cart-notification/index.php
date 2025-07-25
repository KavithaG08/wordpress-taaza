<?php

/**
 * WooCommerce - Cart Notification Core Class
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Others_Cart_Notification' ) ) {

    class Taaza_Shop_Others_Cart_Notification {

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
                add_filter( 'taaza_after_woo_css', array( $this, 'woo_css'), 10 );

            // JS
                add_filter( 'taaza_after_woo_js', array( $this, 'woo_js'), 10 );

        }


        /*
        Module Paths
        */

            function module_dir_path() {

                if( taaza_is_file_in_theme( __FILE__ ) ) {
                    return TAAZA_MODULE_DIR . '/woocommerce/others/cart-notification/';
                } else {
                    return trailingslashit( plugin_dir_path( __FILE__ ) );
                }

            }

            function module_dir_url() {

                if( taaza_is_file_in_theme( __FILE__ ) ) {
                    return TAAZA_MODULE_URI . '/woocommerce/others/cart-notification/';
                } else {
                    return trailingslashit( plugin_dir_url( __FILE__ ) );
                }

            }

        /**
         * Load Modules
         */
            function load_modules() {

                if( function_exists( 'taaza_pro' ) ) {

                    // Customizer
                        include_once $this->module_dir_path(). 'customizer/index.php';

                    // Elementor
                        include_once $this->module_dir_path(). 'elementor/index.php';

                    // Includes
                        include_once $this->module_dir_path(). 'includes/index.php';

                }

            }

        /*
        CSS
        */
            function woo_css() {

                wp_register_style( 'taaza-woo-cart-notification', '', array (), TAAZA_PRO_VERSION, 'all' );
                wp_enqueue_style( 'taaza-woo-cart-notification' );

                $css_file_path = $this->module_dir_path() . 'assets/css/style.css';

                $css = '';
                if( file_exists ( $css_file_path ) ) {

                    ob_start();
                    include( $css_file_path );
                    $css .= "\n\n".ob_get_clean();

                }

                if( !empty($css) ) {
                    wp_add_inline_style( 'taaza-woo-cart-notification', $css );
                }


                return $css;

            }

        /*
        JS
        */
            function woo_js() {

                wp_enqueue_script('jquery-nicescroll', $this->module_dir_url() . 'assets/js/jquery.nicescroll.js', array('jquery'), false, true);

                wp_register_script( 'taaza-woo-cart-notification', '', array ('jquery'), false, true );
                wp_enqueue_script( 'taaza-woo-cart-notification' );

                $js_file_path = $this->module_dir_path() . 'assets/js/scripts.js';

                $js = '';
                if( file_exists ( $js_file_path ) ) {

                    ob_start();
                    include( $js_file_path );
                    $js .= "\n\n".ob_get_clean();

                }

                if( !empty($js) ) {
                    wp_add_inline_script( 'taaza-woo-cart-notification', $js );
                }

                return $js;

            }

    }

}

if( !function_exists('taaza_shop_others_cart_notification') ) {
	function taaza_shop_others_cart_notification() {
        $reflection = new ReflectionClass('Taaza_Shop_Others_Cart_Notification');
        return $reflection->newInstanceWithoutConstructor();
	}
}

Taaza_Shop_Others_Cart_Notification::instance();