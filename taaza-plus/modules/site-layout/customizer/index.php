<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusCustomizerSiteLayout' ) ) {
    class TaazaPlusCustomizerSiteLayout {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {

            add_action( 'customize_register', array( $this, 'register' ), 15 );
            add_filter( 'body_class', array( $this, 'body_class' ) );
        }

        function register( $wp_customize ) {

            /**
             * Site Layout Section
             */
            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-layout-main-section',
                    array(
                        'title'    => esc_html__('Site Layout', 'taaza-plus'),
                        'priority' => taaza_customizer_panel_priority( 'layout' )
                    )
                )
            );

                /**
                 * Option :Site Layout
                 */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[site_layout]', array(
                        'type'    => 'option',
                        'default' => 'wide'
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[site_layout]', array(
                            'type'    => 'select',
                            'section' => 'site-layout-main-section',
                            'label'   => esc_html__( 'Site Layout', 'taaza-plus' ),
                            'choices' => apply_filters( 'taaza_site_layouts', array() ),
                        )
                    )
                );


            do_action('taaza_site_layout_cutomizer_options', $wp_customize );

        }

        function body_class( $classes ) {
            $layout = taaza_customizer_settings('site_layout');

            global $post;
            $id = get_the_ID();
            $settings = get_post_meta( $id, '_taaza_custom_settings', TRUE );
            $settings = is_array( $settings ) ? $settings  : array();

            if( isset($settings['show-fixed-footer']) && !empty($settings['show-fixed-footer']) ) {
                $classes[] = 'wdt-fixed-footer-enabled';
            }

            if( !empty( $layout ) ) {
                $classes[] = $layout;
            }

            return $classes;
        }
    }
}

TaazaPlusCustomizerSiteLayout::instance();