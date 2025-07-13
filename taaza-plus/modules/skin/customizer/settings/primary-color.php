<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusSkinPrimayColor' ) ) {
    class TaazaPlusSkinPrimayColor {
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

            add_filter( 'taaza_primary_color_css_var', array( $this, 'primary_color_var' ) );
            add_filter( 'taaza_primary_rgb_color_css_var', array( $this, 'primary_rgb_color_var' ) );
            add_filter( 'taaza_add_inline_style', array( $this, 'base_style' ) );
        }

        function default( $option ) {
            $theme_defaults = function_exists('taaza_theme_defaults') ? taaza_theme_defaults() : array ();
            $option['primary_color'] = $theme_defaults['primary_color'];
            return $option;
        }

        function register( $wp_customize ) {

            /**
             * Option : Primary Color
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[primary_color]', array(
                    'type'    => 'option',
                )
            );

            $wp_customize->add_control(
                new Taaza_Customize_Control_Color(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[primary_color]', array(
                        'section' => 'site-skin-main-section',
                        'label'   => esc_html__( 'Primary Color', 'taaza-plus' ),
                    )
                )
            );
        }

        function primary_color_var( $var ) {
            $primary_color = taaza_customizer_settings( 'primary_color' );
            if( !empty( $primary_color ) ) {
                $var = '--wdtPrimaryColor:'.esc_attr($primary_color).';';
            }

            return $var;
        }

        function primary_rgb_color_var( $var ) {
            $primary_color = taaza_customizer_settings( 'primary_color' );
            if( !empty( $primary_color ) ) {
                $var = '--wdtPrimaryColorRgb:'.taaza_hex2rgba($primary_color, false).';';
            }

            return $var;
        }

        function base_style( $style ) {
            $style = apply_filters( 'taaza_primary_color_style', $style );
            return $style;
        }
    }
}

TaazaPlusSkinPrimayColor::instance();