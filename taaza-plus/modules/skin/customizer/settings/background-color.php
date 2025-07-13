<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusMainBGColor' ) ) {
    class TaazaPlusMainBGColor {
        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_filter( 'taaza_plus_customizer_default', array( $this, 'default' ) );
            add_action( 'customize_register', array( $this, 'register' ), 15);

            add_filter( 'taaza_body_bg_color_css_var', array( $this, 'body_bg_color_var' ) );
            add_filter( 'taaza_body_bg_rgb_color_css_var', array( $this, 'body_bg_rgb_color_var' ) );
            add_filter( 'taaza_add_inline_style', array( $this, 'base_style' ) );
        }

        function default( $option ) {
            $theme_defaults = function_exists('taaza_theme_defaults') ? taaza_theme_defaults() : array ();
            $option['body_bg_color'] = $theme_defaults['body_bg_color'];
            return $option;
        }

        function register( $wp_customize ) {

            /**
             * Option : Primary Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[body_bg_color]', array(
                    'type'    => 'option',
                )
            );

            $wp_customize->add_control(
                new Taaza_Customize_Control_Color(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[body_bg_color]', array(
                        'section' => 'site-skin-main-section',
                        'label'   => esc_html__( 'Main Background Color', 'taaza-plus' ),
                    )
                )
            );
        }

        function body_bg_color_var( $var ) {
            $body_bg_color = taaza_customizer_settings( 'body_bg_color' );
            if( !empty( $body_bg_color ) ) {
                $var = '--wdtBodyBGColor:'.esc_attr($body_bg_color).';';
            }

            return $var;
        }

        function body_bg_rgb_color_var( $var ) {
            $body_bg_color = taaza_customizer_settings( 'body_bg_color' );
            if( !empty( $body_bg_color ) ) {
                $var = '--wdtBodyBGColorRgb:'.taaza_hex2rgba($body_bg_color, false).';';
            }

            return $var;
        }

        function base_style( $style ) {
            return $style;
        }
    }
}

TaazaPlusMainBGColor::instance();