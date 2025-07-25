<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Metabox_Single_Upsell_Related' ) ) {
    class Taaza_Shop_Metabox_Single_Upsell_Related {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {

			add_filter( 'taaza_shop_product_custom_settings', array( $this, 'taaza_shop_product_custom_settings' ), 10 );

		}

        function taaza_shop_product_custom_settings( $options ) {

			$ct_dependency      = array ();
			$upsell_dependency  = array ( 'show-upsell', '==', 'true');
			$related_dependency = array ( 'show-related', '==', 'true');
			if( function_exists('taaza_shop_single_module_custom_template') ) {
				$ct_dependency['dependency'] 	= array ( 'product-template', '!=', 'custom-template');
				$upsell_dependency 				= array ( 'product-template|show-upsell', '!=|==', 'custom-template|true');
				$related_dependency 			= array ( 'product-template|show-related', '!=|==', 'custom-template|true');
			}

			$product_options = array (

				array_merge (
					array(
						'id'         => 'show-upsell',
						'type'       => 'select',
						'title'      => esc_html__('Show Upsell Products', 'taaza'),
						'class'      => 'chosen',
						'default'    => 'admin-option',
						'attributes' => array( 'data-depend-id' => 'show-upsell' ),
						'options'    => array(
							'admin-option' => esc_html__( 'Admin Option', 'taaza' ),
							'true'         => esc_html__( 'Show', 'taaza'),
							null           => esc_html__( 'Hide', 'taaza'),
						)
					),
					$ct_dependency
				),

				array(
					'id'         => 'upsell-column',
					'type'       => 'select',
					'title'      => esc_html__('Choose Upsell Column', 'taaza'),
					'class'      => 'chosen',
					'default'    => 4,
					'options'    => array(
						'admin-option' => esc_html__( 'Admin Option', 'taaza' ),
						1              => esc_html__( 'One Column', 'taaza' ),
						2              => esc_html__( 'Two Columns', 'taaza' ),
						3              => esc_html__( 'Three Columns', 'taaza' ),
						4              => esc_html__( 'Four Columns', 'taaza' ),
					),
					'dependency' => $upsell_dependency
				),

				array(
					'id'         => 'upsell-limit',
					'type'       => 'select',
					'title'      => esc_html__('Choose Upsell Limit', 'taaza'),
					'class'      => 'chosen',
					'default'    => 4,
					'options'    => array(
						'admin-option' => esc_html__( 'Admin Option', 'taaza' ),
						1              => esc_html__( 'One', 'taaza' ),
						2              => esc_html__( 'Two', 'taaza' ),
						3              => esc_html__( 'Three', 'taaza' ),
						4              => esc_html__( 'Four', 'taaza' ),
						5              => esc_html__( 'Five', 'taaza' ),
						6              => esc_html__( 'Six', 'taaza' ),
						7              => esc_html__( 'Seven', 'taaza' ),
						8              => esc_html__( 'Eight', 'taaza' ),
						9              => esc_html__( 'Nine', 'taaza' ),
						10              => esc_html__( 'Ten', 'taaza' ),
					),
					'dependency' => $upsell_dependency
				),

				array_merge (
					array(
						'id'         => 'show-related',
						'type'       => 'select',
						'title'      => esc_html__('Show Related Products', 'taaza'),
						'class'      => 'chosen',
						'default'    => 'admin-option',
						'attributes' => array( 'data-depend-id' => 'show-related' ),
						'options'    => array(
							'admin-option' => esc_html__( 'Admin Option', 'taaza' ),
							'true'         => esc_html__( 'Show', 'taaza'),
							null           => esc_html__( 'Hide', 'taaza'),
						)
					),
					$ct_dependency
				),

				array(
					'id'         => 'related-column',
					'type'       => 'select',
					'title'      => esc_html__('Choose Related Column', 'taaza'),
					'class'      => 'chosen',
					'default'    => 4,
					'options'    => array(
						'admin-option' => esc_html__( 'Admin Option', 'taaza' ),
						2              => esc_html__( 'Two Columns', 'taaza' ),
						3              => esc_html__( 'Three Columns', 'taaza' ),
						4              => esc_html__( 'Four Columns', 'taaza' ),
					),
					'dependency' => $related_dependency
				),

				array(
					'id'         => 'related-limit',
					'type'       => 'select',
					'title'      => esc_html__('Choose Related Limit', 'taaza'),
					'class'      => 'chosen',
					'default'    => 4,
					'options'    => array(
						'admin-option' => esc_html__( 'Admin Option', 'taaza' ),
						1              => esc_html__( 'One', 'taaza' ),
						2              => esc_html__( 'Two', 'taaza' ),
						3              => esc_html__( 'Three', 'taaza' ),
						4              => esc_html__( 'Four', 'taaza' ),
						5              => esc_html__( 'Five', 'taaza' ),
						6              => esc_html__( 'Six', 'taaza' ),
						7              => esc_html__( 'Seven', 'taaza' ),
						8              => esc_html__( 'Eight', 'taaza' ),
						9              => esc_html__( 'Nine', 'taaza' ),
						10              => esc_html__( 'Ten', 'taaza' ),
					),
					'dependency' => $related_dependency
				)

			);

			$options = array_merge( $options, $product_options );

			return $options;

		}

    }
}

Taaza_Shop_Metabox_Single_Upsell_Related::instance();