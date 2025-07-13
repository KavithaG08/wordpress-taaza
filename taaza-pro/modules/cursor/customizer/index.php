<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaProCustomizerCursor' ) ) {
    class TaazaProCustomizerCursor {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_filter( 'taaza_pro_customizer_default', array( $this, 'default' ) );
            add_action( 'taaza_general_cutomizer_options', array( $this, 'register_general' ), 30 );
        }

        function default( $option ) {

            $option['enable_cursor_effect'] = '1';
            $option['cursor_type'] = 'type-1';
            $option['cursor_link_hover_effect'] = 'link-hover-effect-1';
            $option['cursor_lightbox_hover_effect'] = 'image-hover-effect-1';

            return $option;
        }

        function register_general( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'cursor-section',
                    array(
                        'title'    => esc_html__('Cursor', 'taaza-pro'),
                        'panel'    => 'site-general-main-panel',
                        'priority' => 30,
                    )
                )
            );

                /**
                 * Option : Enable Cursor
                 */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[enable_cursor_effect]', array(
                        'type' => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Switch(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[enable_cursor_effect]', array(
                            'type'    => 'wdt-switch',
                            'section' => 'cursor-section',
                            'label'   => esc_html__( 'Enable Cursor Effect', 'taaza-pro' ),
                            'choices' => array(
                                'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                'off' => esc_attr__( 'No', 'taaza-pro' )
                            )
                        )
                    )
                );

                /**
                 * Option : Type
                 */
                /* $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[cursor_type]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[cursor_type]', array(
                            'type'       => 'select',
                            'section'    => 'cursor-section',
                            'label'      => esc_html__( 'Type', 'taaza-pro' ),
                            'desc'      => esc_html__( 'Choose one of the available cursor types.', 'taaza-pro' ),
                            'choices'    => array (
                                'type-1' => esc_html__('Type 1', 'taaza-pro'),
                                'type-2' => esc_html__('Type 2', 'taaza-pro'),
                            ),
                            'dependency' => array( 'enable_cursor_effect', '!=', '' ),
                        )
                    )
                ); */

                /**
                 * Option : Link Hover Effect
                 */
                /* $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[cursor_link_hover_effect]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[cursor_link_hover_effect]', array(
                            'type'       => 'select',
                            'section'    => 'cursor-section',
                            'label'      => esc_html__( 'Link Hover Effect', 'taaza-pro' ),
                            'desc'      => esc_html__( 'Effects to use if cursor hovers on links.', 'taaza-pro' ),
                            'choices'    => array (
                                '' => esc_html__('None', 'taaza-pro'),
                                'link-hover-effect-1' => esc_html__('Effect 1', 'taaza-pro'),
                                'link-hover-effect-2' => esc_html__('Effect 2', 'taaza-pro'),
                            ),
                            'dependency' => array( 'enable_cursor_effect', '!=', '' ),
                        )
                    )
                ); */

                /**
                 * Option : LightBox Hover Effect
                 */
                /* $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[cursor_lightbox_hover_effect]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[cursor_lightbox_hover_effect]', array(
                            'type'       => 'select',
                            'section'    => 'cursor-section',
                            'label'      => esc_html__( 'LightBox Hover Effect', 'taaza-pro' ),
                            'desc'      => esc_html__( 'Effects to use if cursor hovers on images.', 'taaza-pro' ),
                            'choices'    => array (
                                '' => esc_html__('None', 'taaza-pro'),
                                'image-hover-effect-1' => esc_html__('Effect 1', 'taaza-pro'),
                                'image-hover-effect-2' => esc_html__('Effect 2', 'taaza-pro'),
                            ),
                            'dependency' => array( 'enable_cursor_effect', '!=', '' ),
                        )
                    )
                ); */

        }

    }
}

TaazaProCustomizerCursor::instance();