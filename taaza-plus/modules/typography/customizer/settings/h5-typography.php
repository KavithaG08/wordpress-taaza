<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusH5Settings' ) ) {
    class TaazaPlusH5Settings {

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
            $this->selector = apply_filters( 'taaza_h5_selector', array( 'h5' ) );
            $this->settings = taaza_customizer_settings('h5_typo');

            add_filter( 'taaza_plus_customizer_default', array( $this, 'default' ) );
            add_action( 'customize_register', array( $this, 'register' ), 20);

            add_filter( 'taaza_h5_typo_customizer_update', array( $this, 'h5_typo_customizer_update' ) );

            add_filter( 'taaza_google_fonts_list', array( $this, 'fonts_list' ) );
            add_filter( 'taaza_add_inline_style', array( $this, 'base_style' ) );
            add_filter( 'taaza_add_tablet_landscape_inline_style', array( $this, 'tablet_landscape_style' ) );
            add_filter( 'taaza_add_tablet_portrait_inline_style', array( $this, 'tablet_portrait' ) );
            add_filter( 'taaza_add_mobile_res_inline_style', array( $this, 'mobile_style' ) );
        }

        function default( $option ) {
            $theme_defaults = function_exists('taaza_theme_defaults') ? taaza_theme_defaults() : array ();
            $option['h5_typo'] = $theme_defaults['h5_typo'];
            return $option;
        }

        function register( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-h5-section',
                    array(
                        'title'    => esc_html__('H5 Typography', 'taaza-plus'),
                        'panel'    => 'site-typography-main-panel',
                        'priority' => 25,
                    )
                )
            );

            /**
             * Option :H5 Typo
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[h5_typo]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Typography(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[h5_typo]', array(
                            'type'    => 'wdt-typography',
                            'section' => 'site-h5-section',
                            'label'   => esc_html__( 'H5 Tag', 'taaza-plus'),
                        )
                    )
                );

            /**
             * Option : H5 Color
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[h5_color]', array(
                        'default' => '',
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new WP_Customize_Color_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[h5_color]', array(
                            'label'   => esc_html__( 'Color', 'taaza-plus' ),
                            'section' => 'site-h5-section',
                        )
                    )
                );

        }

        function h5_typo_customizer_update( $defaults ) {
            $h5_typo = taaza_customizer_settings( 'h5_typo' );
            if( !empty( $h5_typo ) ) {
                return  $h5_typo;
            }
            return $defaults;
        }

        function fonts_list( $fonts ) {
            return taaza_customizer_frontend_font( $this->settings, $fonts );
        }

        function base_style( $style ) {
            $css   = '';
            $color = taaza_customizer_settings('h5_color');

            $css .= taaza_customizer_typography_settings( $this->settings );
            $css .= taaza_customizer_color_settings( $color );

            $css = taaza_customizer_dynamic_style( $this->selector, $css );

            return $style.$css;
        }

        function tablet_landscape_style( $style ) {
            $css = taaza_customizer_responsive_typography_settings( $this->settings, 'tablet-ls' );
            $css = taaza_customizer_dynamic_style( $this->selector, $css );

            return $style.$css;
        }

        function tablet_portrait( $style ) {
            $css = taaza_customizer_responsive_typography_settings( $this->settings, 'tablet' );
            $css = taaza_customizer_dynamic_style( $this->selector, $css );

            return $style.$css;
        }

        function mobile_style( $style ) {
            $css = taaza_customizer_responsive_typography_settings( $this->settings, 'mobile' );
            $css = taaza_customizer_dynamic_style( $this->selector, $css );

            return $style.$css;
        }
    }
}

TaazaPlusH5Settings::instance();