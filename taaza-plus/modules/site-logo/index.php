<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusSiteLogo' ) ) {
    class TaazaPlusSiteLogo {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            $this->load_modules();

        }

        function load_modules() {
            include_once TAAZA_PLUS_DIR_PATH.'modules/site-logo/customizer/index.php';
        }
    }
}

TaazaPlusSiteLogo::instance();