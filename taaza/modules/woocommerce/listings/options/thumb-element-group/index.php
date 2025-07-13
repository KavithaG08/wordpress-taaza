<?php

/**
 * Listing Options - Product Thumb Content
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Woo_Listing_Option_Thumb_Element_Group' ) ) {

    class Taaza_Woo_Listing_Option_Thumb_Element_Group extends Taaza_Woo_Listing_Option_Core {

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

            $this->option_slug          = 'product-thumb-element-group';
            $this->option_name          = esc_html__('Element Group Content', 'taaza');
            $this->option_type          = array ( 'html', 'value-css' );
            $this->option_default_value = '';
            $this->option_value_prefix  = '';

            $this->render_backend();
        }

        /**
         * Backend Render
         */
        function render_backend() {

            /* Custom Product Templates - Options */
            add_filter( 'taaza_woo_custom_product_template_thumb_options', array( $this, 'woo_custom_product_template_thumb_options'), 55, 1 );
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
         * Setting Arguments
         */
        function setting_args() {

            $settings            =  array ();
            $settings['id']      =  $this->option_slug;
            $settings['type']    =  'sorter';
            $settings['title']   =  $this->option_name;
            $settings['default'] =  array (
                'enabled' => array(
                    'title' => esc_html__('Title', 'taaza'),
                    'price' => esc_html__('Price', 'taaza'),
                ),
                'disabled'         => array(
                    'cart'           => esc_html__('Cart', 'taaza'),
                    'wishlist'       => esc_html__('Wishlist', 'taaza'),
                    'compare'        => esc_html__('Compare', 'taaza'),
                    'quickview'      => esc_html__('Quick View', 'taaza'),
                    'category'       => esc_html__('Category', 'taaza'),
                    'button_element' => esc_html__('Button Element', 'taaza'),
                    'icons_group'    => esc_html__('Icons Group', 'taaza'),
                    'excerpt'        => esc_html__('Excerpt', 'taaza'),
                    'rating'         => esc_html__('Rating', 'taaza'),
                    'separator'      => esc_html__('Separator', 'taaza'),
                    'swatches'       => esc_html__('Swatches', 'taaza')
                ),
            );
            $settings['enabled_title']  =  esc_html__('Active Elements', 'taaza');
            $settings['disabled_title'] =  esc_html__('Deatcive Elements', 'taaza');

            return $settings;
        }
    }

}

if( !function_exists('taaza_woo_listing_option_thumb_element_group') ) {
	function taaza_woo_listing_option_thumb_element_group() {
		return Taaza_Woo_Listing_Option_Thumb_Element_Group::instance();
	}
}

taaza_woo_listing_option_thumb_element_group();