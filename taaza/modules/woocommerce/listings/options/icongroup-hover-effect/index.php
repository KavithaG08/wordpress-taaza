<?php
/**
 * Listing Options - Image Effect
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Woo_Listing_Option_Icon_Group_Hover_Effect' ) ) {

    class Taaza_Woo_Listing_Option_Icon_Group_Hover_Effect extends Taaza_Woo_Listing_Option_Core {

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

            $this->option_slug          = 'product-icongroup-hover-effect';
            $this->option_name          = esc_html__('Icon Group Hover Effect', 'taaza');
            $this->option_type          = array ( 'class', 'value-css' );
            $this->option_default_value = '';
            $this->option_value_prefix  = 'product-icongroup-hover-';

            $this->render_backend();
        }

        /**
         * Backend Render
         */
        function render_backend() {
            add_filter( 'taaza_woo_custom_product_template_hover_options', array( $this, 'woo_custom_product_template_hover_options'), 35, 1 );
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
                ''                               => esc_html__('None', 'taaza'),
                'product-icongroup-hover-flipx'  => esc_html__('Flip X', 'taaza'),
                'product-icongroup-hover-flipy'  => esc_html__('Flip Y', 'taaza'),
                'product-icongroup-hover-bounce' => esc_html__('Bounce', 'taaza')
            );
            $settings['default'] =  $this->option_default_value;

            return $settings;
        }
    }

}

if( !function_exists('taaza_woo_listing_option_icongroup_hover_effect') ) {
	function taaza_woo_listing_option_icongroup_hover_effect() {
		return Taaza_Woo_Listing_Option_Icon_Group_Hover_Effect::instance();
	}
}

taaza_woo_listing_option_icongroup_hover_effect();