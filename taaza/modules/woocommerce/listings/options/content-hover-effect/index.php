<?php
/**
 * Listing Options - Image Effect
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Woo_Listing_Option_Content_Hover_Effect' ) ) {

    class Taaza_Woo_Listing_Option_Content_Hover_Effect extends Taaza_Woo_Listing_Option_Core {

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

            $this->option_slug          = 'product-content-hover-effect';
            $this->option_name          = esc_html__('Content Hover Effect', 'taaza');
            $this->option_type          = array ( 'class', 'value-css' );
            $this->option_default_value = '';
            $this->option_value_prefix  = 'product-content-hover-';

            $this->render_backend();
        }

        /**
         * Backend Render
         */
        function render_backend() {
            add_filter( 'taaza_woo_custom_product_template_hover_options', array( $this, 'woo_custom_product_template_hover_options'), 40, 1 );
        }

        /**
         * Custom Product Templates - Options
         */
        function woo_custom_product_template_hover_options( $template_options ) {

            array_push( $template_options, $this->setting_args() );

            return $template_options;
        }

        /**
         * Settings Group
         */
        function setting_group() {
            return 'hover';
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
                ''                                   => esc_html__('None', 'taaza'),
                'product-content-hover-fade'         => esc_html__('Fade', 'taaza'),
                'product-content-hover-zoom'         => esc_html__('Zoom', 'taaza'),
                'product-content-hover-slidedefault' => esc_html__('Slide Default', 'taaza'),
                'product-content-hover-slideleft'    => esc_html__('Slide From Left', 'taaza'),
                'product-content-hover-slideright'   => esc_html__('Slide From Right', 'taaza'),
                'product-content-hover-slidetop'     => esc_html__('Slide From Top', 'taaza'),
                'product-content-hover-slidebottom'  => esc_html__('Slide From Bottom', 'taaza')
            );
            $settings['default'] =  $this->option_default_value;

            return $settings;
        }
    }

}

if( !function_exists('taaza_woo_listing_option_content_hover_effect') ) {
	function taaza_woo_listing_option_content_hover_effect() {
		return Taaza_Woo_Listing_Option_Content_Hover_Effect::instance();
	}
}

taaza_woo_listing_option_content_hover_effect();