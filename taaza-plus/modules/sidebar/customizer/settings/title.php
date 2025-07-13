<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusWidgetTitleSettings' ) ) {
    class TaazaPlusWidgetTitleSettings {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_action( 'customize_register', array( $this, 'register' ), 15);
        }

        function register( $wp_customize ){

            /**
             * Title Section
             */
            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-widgets-title-style-section',
                    array(
                        'title'    => esc_html__('Widget Title', 'taaza-plus'),
                        'panel'    => 'site-widget-settings-panel',
                        'priority' => 5,
                    )
                )
            );

            if ( ! defined( 'TAAZA_PRO_VERSION' ) ) {
                $wp_customize->add_control(
                    new Taaza_Customize_Control_Separator(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[taaza-plus-site-sidebar-title-separator]',
                        array(
                            'type'        => 'wdt-separator',
                            'section'     => 'site-widgets-title-style-section',
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

TaazaPlusWidgetTitleSettings::instance();