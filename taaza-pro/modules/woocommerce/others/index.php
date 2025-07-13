<?php

/**
 * WooCommerce - Others Class
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Pro_Others' ) ) {

    class Taaza_Pro_Others {

        private static $_instance = null;

        private $settings;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            // Load Modules
                $this->load_modules();

        }

        /*
        Load Modules
        */
        function load_modules() {

            // Customizer
                include_once TAAZA_PRO_DIR_PATH . 'modules/woocommerce/others/customizer/index.php';

        }

    }

}

if( !function_exists('taaza_others') ) {
	function taaza_others() {
		return Taaza_Pro_Others::instance();
	}
}

taaza_others();