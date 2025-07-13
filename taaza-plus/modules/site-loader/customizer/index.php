<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusCustomizerSiteLoader' ) ) {
    class TaazaPlusCustomizerSiteLoader {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_filter( 'taaza_plus_customizer_default', array( $this, 'default' ) );
            add_action( 'taaza_general_cutomizer_options', array( $this, 'register_general' ), 30 );
        }

        function default( $option ) {

            $option['show_site_loader'] = '1';
            $option['site_loader']      = '';
            $option['site_loader_image'] = '';

            return $option;
        }

        function register_general( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-loader-section',
                    array(
                        'title'    => esc_html__('Loader', 'taaza-plus'),
                        'panel'    => 'site-general-main-panel',
                        'priority' => 30,
                    )
                )
            );

                /**
                 * Option : Enable Site Loader
                 */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[show_site_loader]', array(
                        'type' => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Switch(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[show_site_loader]', array(
                            'type'    => 'wdt-switch',
                            'section' => 'site-loader-section',
                            'label'   => esc_html__( 'Enable Loader', 'taaza-plus' ),
                            'choices' => array(
                                'on'  => esc_attr__( 'Yes', 'taaza-plus' ),
                                'off' => esc_attr__( 'No', 'taaza-plus' )
                            )
                        )
                    )
                );

                /**
                 * Option :Site Loader
                 */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[site_loader]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[site_loader]', array(
                            'type'       => 'select',
                            'section'    => 'site-loader-section',
                            'label'      => esc_html__( 'Select Loader', 'taaza-plus' ),
                            'choices'    => apply_filters( 'taaza_loader_layouts', array() ),
                            'dependency' => array( 'show_site_loader', '!=', '' ),
                        )
                    )
                );

                /**
                 * Option :Site Image
                 */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[site_loader_image]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Upload(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[site_loader_image]', array(
                            'type'       => 'wdt-upload',
                            'section'    => 'site-loader-section',
                            'label'      => esc_html__( 'Upload Loader Image', 'taaza-plus' ),
                            'dependency' => array( 'site_loader|show_site_loader', '==|!=', 'custom-loader|' ),
                        )
                    )
                );
        }

    }
}

TaazaPlusCustomizerSiteLoader::instance();