<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusContentBeforeHookSettings' ) ) {
    class TaazaPlusContentBeforeHookSettings {
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

            /**
             * Load Hook Before Content in theme.
             */
            add_action( 'taaza_hook_content_before', array( $this, 'hook_content_before' ) );
        }

        function default( $option ) {
            $option['enable_content_before_hook'] = 0;
            $option['content_before_hook']        = '';
            return $option;
        }

        function register( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-content-before-hook-section',
                    array(
                        'title'    => esc_html__('Content Before Hook', 'taaza-plus'),
                        'panel'    => 'site-hook-main-panel',
                        'priority' => 10,
                    )
                )
            );

                /**
                 * Option : Enable Before Hook
                 */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[enable_content_before_hook]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Switch(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[enable_content_before_hook]', array(
                            'type'        => 'wdt-switch',
                            'section'     => 'site-content-before-hook-section',
                            'label'       => esc_html__( 'Enable Content Before Hook', 'taaza-plus' ),
                            'description' => esc_html__('YES! to enable content before hook.', 'taaza-plus'),
                            'choices'     => array(
                                'on'  => esc_attr__( 'Yes', 'taaza-plus' ),
                                'off' => esc_attr__( 'No', 'taaza-plus' )
                            )
                        )
                    )
                );

                /**
                 * Option : Before Hook
                 */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[content_before_hook]', array(
                        'type'    => 'option',
                        'default' => '',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[content_before_hook]', array(
                            'type'        => 'textarea',
                            'section'     => 'site-content-before-hook-section',
                            'label'       => esc_html__( 'Content Before Hook', 'taaza-plus' ),
                            'dependency'  => array( 'enable_content_before_hook', '!=', '' ),
                            'description' => sprintf( esc_html__('Paste your content after hook, Executes before the opening %s tag.', 'taaza-plus'), '&lt;#primary&gt;' )
                        )
                    )
                );

        }

        function hook_content_before() {
            $enable_hook = taaza_customizer_settings( 'enable_content_before_hook' );
            $hook        = taaza_customizer_settings( 'content_before_hook' );

            if( $enable_hook && !empty( $hook ) ) {
                echo do_shortcode( stripslashes( $hook ) );
            }
        }

    }
}

TaazaPlusContentBeforeHookSettings::instance();