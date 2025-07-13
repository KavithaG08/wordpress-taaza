<?php
/**
 * Listing Options - Image Effect
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Woo_Listing_Option_Show_Custom_Type' ) ) {

    class Taaza_Woo_Listing_Option_Show_Custom_Type extends Taaza_Woo_Listing_Option_Core {

        private static $_instance = null;

        public $option_slug;

        public $option_name;

        public $option_type;

        public $option_default_value;

        public $option_value_prefix;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {

            $this->option_slug          = 'product-show-custom-type';
            $this->option_name          = esc_html__('Show Custom Product Type', 'taaza');
            $this->option_type          = array ( 'html', 'class', 'key-css' );
            $this->option_default_value = 'false';
            $this->option_value_prefix  = '';

            $this->render_backend();
        }

        /**
         * Backend Render
         */
        function render_backend() {
            add_filter( 'taaza_woo_custom_product_template_common_options', array( $this, 'woo_custom_product_template_common_options'), 85, 1 );
        }

        /**
         * Custom Product Templates - Options
         */
        function woo_custom_product_template_common_options( $template_options ) {

            array_push( $template_options, $this->setting_args() );
            return $template_options;

        }

        /**
         * Settings Group
         */
        function setting_group() {
            return 'common';
        }

        /**
         * Setting Args
         */
        function setting_args() {
            $settings            =  array ();
            $settings['id']      =  $this->option_slug;
            $settings['type']    =  'select';
            $settings['title']   =  $this->option_name;
            $settings['options'] =  array (
                'true'  => esc_html__('True', 'taaza'),
                'false' => esc_html__('False', 'taaza'),
            );
            $settings['default'] =  $this->option_default_value;

            return $settings;
        }
    }

}

if( !function_exists('taaza_woo_listing_option_show_custom_type') ) {
	function taaza_woo_listing_option_show_custom_type() {
		return Taaza_Woo_Listing_Option_Show_Custom_Type::instance();
	}
}

taaza_woo_listing_option_show_custom_type();