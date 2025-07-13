<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusCustomizerSiteLogo' ) ) {
    class TaazaPlusCustomizerSiteLogo {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_action( 'customize_register', array( $this, 'register' ), 15 );
        }

        function register( $wp_customize ) {

            $wp_customize->add_section(
                new Taaza_Customize_Section(
                    $wp_customize,
                    'site-logo-section',
                    array(
                        'title'    => esc_html__('Site Logo', 'taaza-plus'),
                        'panel'    => 'site-identity-main-panel',
                        'priority' => 5,
                    )
                )
            );

            /**
             * Option: Site Logo
             */
			$wp_customize->get_control('custom_logo')->section  = 'site-logo-section';
			$wp_customize->get_control('custom_logo')->priority = 5;


            /**
             * Option: Site Alternate Logo
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[custom_alt_logo]', array(
                    'type'    => 'option',
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Cropped_Image_Control(
                    $wp_customize,  TAAZA_CUSTOMISER_VAL . '[custom_alt_logo]', array(
                        'label'         => esc_html__( 'Alternate Logo', 'taaza-plus' ),
                        'section'       => 'site-logo-section',
                        'height'        => 100,
                        'width'         => 400,
                        'flex_height'   => true,
                        'flex_width'    => true,
                        'button_labels' => array(
                            'select'       => esc_html__( 'Select logo', 'taaza-plus' ),
                            'change'       => esc_html__( 'Change logo', 'taaza-plus' ),
                            'remove'       => esc_html__( 'Remove', 'taaza-plus' ),
                            'placeholder'  => esc_html__( 'No logo selected', 'taaza-plus' ),
                            'frame_title'  => esc_html__( 'Select logo', 'taaza-plus' ),
                            'frame_button' => esc_html__( 'Choose logo', 'taaza-plus' ),
                        )
                    )
                )
            );

        }

    }
}

TaazaPlusCustomizerSiteLogo::instance();