<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaProCustomizerSiteTitle' ) ) {
    class TaazaProCustomizerSiteTitle {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_action( 'customize_register', array( $this, 'register' ), 15 );
            add_filter( 'taaza_google_fonts_list', array( $this, 'fonts_list' ) );
        }

        function register( $wp_customize ) {

            /**
             * Option :Site Title Typography
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[site_title_typo]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Typography(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[site_title_typo]', array(
                            'type'    => 'wdt-typography',
                            'section' => 'site-title-section',
                            'label'   => esc_html__( 'Typography', 'taaza-pro'),
                        )
                    )
                );


            /**
             * Option : Site Title Color
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[site_title_color]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new WP_Customize_Color_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[site_title_color]', array(
                            'label'   => esc_html__( 'Color', 'taaza-pro' ),
                            'section' => 'site-title-section',
                        )
                    )
                );


            /**
             * Option : Site Title Hover Color
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[site_title_hover_color]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new WP_Customize_Color_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[site_title_hover_color]', array(
                            'label'   => esc_html__( 'Hover Color', 'taaza-pro' ),
                            'section' => 'site-title-section',
                        )
                    )
                );

        }

        function fonts_list( $fonts ) {
            $settings = taaza_customizer_settings( 'site_title_typo' );
            return taaza_customizer_frontend_font( $settings, $fonts );
        }

    }
}

TaazaProCustomizerSiteTitle::instance();