<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaProPostMinimal' ) ) {
    class TaazaProPostMinimal {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_filter( 'taaza_post_styles', array( $this, 'add_post_styles_option' ) );
        }

        function add_post_styles_option( $options ) {
            $options['minimal'] = esc_html__('Minimal', 'taaza-pro');
            return $options;
        }

    }
}

TaazaProPostMinimal::instance();