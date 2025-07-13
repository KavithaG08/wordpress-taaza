<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusSiteLayout' ) ) {
    class TaazaPlusSiteLayout {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            $this->load_site_layouts();
            $this->load_modules();
        }

        function load_site_layouts() {
            foreach( glob( TAAZA_PLUS_DIR_PATH. 'modules/site-layout/layouts/*/index.php'  ) as $module ) {
                include_once $module;
            }
        }

        function load_modules() {
            include_once TAAZA_PLUS_DIR_PATH.'modules/site-layout/customizer/index.php';
        }

    }
}

TaazaPlusSiteLayout::instance();