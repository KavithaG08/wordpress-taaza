<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusCustomizerSiteTagline' ) ) {
    class TaazaPlusCustomizerSiteTagline {

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
                    'site-tagline-section',
                    array(
                        'title'    => esc_html__('Site Tagline', 'taaza-plus'),
                        'panel'    => 'site-identity-main-panel',
                        'priority' => 15,
                    )
                )
            );

            $wp_customize->get_control('blogdescription')->section = 'site-tagline-section';
            $wp_customize->get_control('blogdescription')->priority = 5;

            if ( ! defined( 'TAAZA_PRO_VERSION' ) ) {
                $wp_customize->add_control(
                    new Taaza_Customize_Control_Separator(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[taaza-plus-site-tagline-separator]',
                        array(
                            'type'        => 'wdt-separator',
                            'section'     => 'site-tagline-section',
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

TaazaPlusCustomizerSiteTagline::instance();