<?php
/**
 * Listing Options - Image Effect
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Woo_Listing_Option_Padding' ) ) {

    class Taaza_Woo_Listing_Option_Padding extends Taaza_Woo_Listing_Option_Core {

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

            $this->option_slug          = 'product-padding';
            $this->option_name          = esc_html__('Padding', 'taaza');
            $this->option_type          = array ( 'class', 'value-css' );
            $this->option_default_value = 'product-padding-default';
            $this->option_value_prefix  = 'product-padding-';

            $this->render_backend();
        }

        /**
         * Backend Render
         */
        function render_backend() {
            add_filter( 'taaza_woo_custom_product_template_common_options', array( $this, 'woo_custom_product_template_common_options'), 20, 1 );
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
                'product-padding-default' => esc_html__('Default', 'taaza'),
                'product-padding-overall' => esc_html__('Product', 'taaza'),
                'product-padding-thumb'   => esc_html__('Thumb', 'taaza'),
                'product-padding-content' => esc_html__('Content', 'taaza'),
            );
            $settings['default'] =  $this->option_default_value;

            return $settings;
        }
    }

}

if( !function_exists('taaza_woo_listing_option_padding') ) {
	function taaza_woo_listing_option_padding() {
		return Taaza_Woo_Listing_Option_Padding::instance();
	}
}

taaza_woo_listing_option_padding();