<?php
/**
 * Listing Options - Image Effect
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Woo_Listing_Option_Label_Design' ) ) {

    class Taaza_Woo_Listing_Option_Label_Design extends Taaza_Woo_Listing_Option_Core {

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

            $this->option_slug          = 'product-label-design';
            $this->option_name          = esc_html__('Product Label Design', 'taaza');
            $this->option_type          = array ( 'class', 'value-css' );
            $this->option_default_value = 'product-label-boxed';
            $this->option_value_prefix  = 'product-label-';

            $this->render_backend();
        }

        /**
         * Backend Render
         */
        function render_backend() {
            add_filter( 'taaza_woo_custom_product_template_common_options', array( $this, 'woo_custom_product_template_common_options'), 75, 1 );
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
                'product-label-boxed'      => esc_html__('Boxed', 'taaza'),
                'product-label-circle'  => esc_html__('Circle', 'taaza'),
                'product-label-rounded'   => esc_html__('Rounded', 'taaza'),
                'product-label-angular'   => esc_html__('Angular', 'taaza'),
                'product-label-ribbon'   => esc_html__('Ribbon', 'taaza'),
            );
            $settings['default'] =  $this->option_default_value;

            return $settings;
        }
    }

}

if( !function_exists('taaza_woo_listing_option_label_design') ) {
	function taaza_woo_listing_option_label_design() {
		return Taaza_Woo_Listing_Option_Label_Design::instance();
	}
}

taaza_woo_listing_option_label_design();