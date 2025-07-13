<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusCustomizerBlogPost' ) ) {
    class TaazaPlusCustomizerBlogPost {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
			add_action( 'customize_register', array( $this, 'register' ), 15 );
        }

        function register( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-blog-post-section',
                    array(
                        'title'    => esc_html__('Single Post', 'taaza-plus'),
                        'panel'    => 'site-blog-main-panel',
                        'priority' => 20,
                    )
                )
            );

			if ( ! defined( 'TAAZA_PRO_VERSION' ) ) {
				$wp_customize->add_control(
					new Taaza_Customize_Control_Separator(
						$wp_customize, TAAZA_CUSTOMISER_VAL . '[taaza-plus-site-single-blog-separator]',
						array(
							'type'        => 'wdt-separator',
							'section'     => 'site-blog-post-section',
							'settings'    => array(),
							'caption'     => TAAZA_PLUS_REQ_CAPTION,
							'description' => TAAZA_PLUS_REQ_DESC,
						)
					)
				);
			}

        }
    }
}

TaazaPlusCustomizerBlogPost::instance();