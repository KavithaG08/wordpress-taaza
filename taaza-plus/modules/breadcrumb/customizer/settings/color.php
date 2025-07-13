<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusBreadCrumbColor' ) ) {
    class TaazaPlusBreadCrumbColor {

        private static $_instance = null;
        private $settings         = null;
        private $selector         = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_action( 'customize_register', array( $this, 'register' ), 15);
        }

        function register( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-breadcrumb-color-section',
                    array(
                        'title'    => esc_html__('Colors & Background', 'taaza-plus'),
                        'panel'    => 'site-breadcrumb-main-panel',
                        'priority' => 10,
                    )
                )
            );

            if ( ! defined( 'TAAZA_PRO_VERSION' ) ) {
                $wp_customize->add_control(
                    new Taaza_Customize_Control_Separator(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[taaza-plus-site-breadcrumb-color-separator]',
                        array(
                            'type'        => 'wdt-separator',
                            'section'     => 'site-breadcrumb-color-section',
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

TaazaPlusBreadCrumbColor::instance();