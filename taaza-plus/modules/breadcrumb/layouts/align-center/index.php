<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusBreadcrumbAlignCenter' ) ) {
    class TaazaPlusBreadcrumbAlignCenter {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_filter( 'taaza_plus_breadcrumb_layouts', array( $this, 'add_option' ) );
        }

        function add_option( $options ) {
            $options['aligncenter'] = esc_html__('Align Center', 'taaza-plus');
            return $options;
        }
    }
}

TaazaPlusBreadcrumbAlignCenter::instance();