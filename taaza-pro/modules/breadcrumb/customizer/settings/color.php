<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaProBreadCrumbColor' ) ) {
    class TaazaProBreadCrumbColor {

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
            add_filter( 'taaza_pro_customizer_default', array( $this, 'default' ) );
            add_action( 'customize_register', array( $this, 'register' ), 15);
        }

        function default( $option ) {
            $option['breadcrumb_title_color']      = '';
            $option['breadcrumb_text_color']       = '';
            $option['breadcrumb_link_color']       = '';
            $option['breadcrumb_link_hover_color'] = '';
            $option['breadcrumb_background']       = array(
                'background-color'          => "var(--wdtTertiaryColor)",
                'gradient-background-color' => "",
                'background-repeat'         => 'repeat',
                'background-position'       => 'center center',
                'background-size'           => 'cover',
                'background-attachment'     => 'inherit'
            );
            $option['breadcrumb_overlay_bg_color'] = 0;

            return $option;
        }

        function register( $wp_customize ) {

            /**
             * Option : Breadcrumb Title Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_title_color]', array(
                    'type' => 'option',
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[breadcrumb_title_color]', array(
                        'label'   => esc_html__( 'Title Color', 'taaza-pro' ),
                        'section' => 'site-breadcrumb-color-section',
                    )
                )
            );

            /**
             * Option : Breadcrumb Text Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_text_color]', array(
                    'type' => 'option',
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[breadcrumb_text_color]', array(
                        'label'   => esc_html__( 'Text Color', 'taaza-pro' ),
                        'section' => 'site-breadcrumb-color-section',
                    )
                )
            );

            /**
             * Option : Breadcrumb Link Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_link_color]', array(
                    'type' => 'option',
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[breadcrumb_link_color]', array(
                        'label'   => esc_html__( 'Link Color', 'taaza-pro' ),
                        'section' => 'site-breadcrumb-color-section',
                    )
                )
            );

            /**
             * Option : Breadcrumb Link Hover Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_link_hover_color]', array(
                    'type' => 'option',
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[breadcrumb_link_hover_color]', array(
                        'label'   => esc_html__( 'Link Hover Color', 'taaza-pro' ),
                        'section' => 'site-breadcrumb-color-section',
                    )
                )
            );

            /**
             * Option : Breadcrumb Background
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_background]', array(
                    'type'    => 'option',
                )
            );

            $wp_customize->add_control(
                new Taaza_Customize_Control_Background(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[breadcrumb_background]', array(
                        'type'    => 'wdt-background',
                        'section' => 'site-breadcrumb-color-section',
                        'label'   => esc_html__( 'Background', 'taaza-pro' ),
                    )
                )
            );

            /**
             * Option : Overlay Background Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_overlay_bg_color]', array(
                    'type'    => 'option',
                )
            );

            $wp_customize->add_control(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_overlay_bg_color]', array(
                    'type'    => 'checkbox',
                    'section' => 'site-breadcrumb-color-section',
                    'label'   => esc_html__( 'Overlay Background Color', 'taaza-pro' ),
                )
            );

            /**
             * Option : Overlay Gradient Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[breadcrumb_background_gradient_color]', array(
                    'type' => 'option',
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[breadcrumb_background_gradient_color]', array(
                        'label'   => esc_html__( 'Overlay Gradient Color', 'taaza-pro' ),
                        'section' => 'site-breadcrumb-color-section',
                    )
                )
            );

        }
    }
}

TaazaProBreadCrumbColor::instance();