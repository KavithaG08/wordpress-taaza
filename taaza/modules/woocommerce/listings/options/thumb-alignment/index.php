<?php
/**
 * Listing Options - Image Effect
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Woo_Listing_Option_Thumb_Alignment' ) ) {

    class Taaza_Woo_Listing_Option_Thumb_Alignment extends Taaza_Woo_Listing_Option_Core {

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

            $this->option_slug          = 'product-thumb-alignment';
            $this->option_name          = esc_html__('Alignment', 'taaza');
            $this->option_type          = array ( 'class', 'value-css' );
            $this->option_default_value = 'product-thumb-alignment-top';
            $this->option_value_prefix  = 'product-thumb-alignment-';

            $this->render_backend();
        }

        /**
         * Backend Render
         */
        function render_backend() {
            add_filter( 'taaza_woo_custom_product_template_thumb_options', array( $this, 'woo_custom_product_template_thumb_options'), 15, 1 );
        }

        /**
         * Custom Product Templates - Options
         */
        function woo_custom_product_template_thumb_options( $template_options ) {

            array_push( $template_options, $this->setting_args() );

            return $template_options;
        }

        /**
         * Settings Group
         */
        function setting_group() {
            return 'thumb';
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
                'product-thumb-alignment-top'          => esc_html__('Top', 'taaza'),
                'product-thumb-alignment-top-left'     => esc_html__('Top Left', 'taaza'),
                'product-thumb-alignment-top-right'    => esc_html__('Top Right', 'taaza'),
                'product-thumb-alignment-middle'       => esc_html__('Middle', 'taaza'),
                'product-thumb-alignment-bottom'       => esc_html__('Bottom', 'taaza'),
                'product-thumb-alignment-bottom-left'  => esc_html__('Bottom Left', 'taaza'),
                'product-thumb-alignment-bottom-right' => esc_html__('Bottom Right', 'taaza')
            );
            $settings['default'] =  $this->option_default_value;

            return $settings;
        }
    }

}

if( !function_exists('taaza_woo_listing_option_thumb_alignment') ) {
	function taaza_woo_listing_option_thumb_alignment() {
		return Taaza_Woo_Listing_Option_Thumb_Alignment::instance();
	}
}

taaza_woo_listing_option_thumb_alignment();