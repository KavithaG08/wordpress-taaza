<?php

/**
 * Listing
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Pro_Listing' ) ) {

    class Taaza_Pro_Listing {

        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            /* Update Options Location Path Array */
                add_filter( 'taaza_woo_option_locations', array( $this, 'option_locations_update'), 10, 1 );

            /* Update Types Location Path Array */
                add_filter( 'taaza_woo_type_locations', array( $this, 'type_locations_update'), 10, 1 );

            /* Load Modules */
                $this->load_modules();

        }

        /*
        Options Location Path Update
        */

            function option_locations_update( $paths ) {

                array_push( $paths, TAAZA_PRO_DIR_PATH . 'modules/woocommerce/listings/options/*/index.php' );

                return $paths;

            }

        /*
        Types Location Path Update
        */

            function type_locations_update( $paths ) {

                array_push( $paths, TAAZA_PRO_DIR_PATH . 'modules/woocommerce/listings/types/*/index.php' );

                return $paths;

            }

        /*
        Load Modules
        */

            function load_modules() {

                // Elementor Widgets
                    include_once TAAZA_PRO_DIR_PATH . 'modules/woocommerce/listings/elementor/index.php';

                // Sidebar
                    include_once TAAZA_PRO_DIR_PATH . 'modules/woocommerce/listings/sidebar/index.php';

            }

    }

}

if( !function_exists('taaza_listing') ) {
	function taaza_listing() {
		return Taaza_Pro_Listing::instance();
	}
}

taaza_listing();