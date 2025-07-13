<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaProCustomizerSite404' ) ) {
    class TaazaProCustomizerSite404 {

        private static $_instance = null;

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

            $option['enable_404message']   = '1';
            $option['notfound_style']      = 'type2';
            $option['notfound_darkbg']     = '1';
            $option['notfound_pageid']     = '';
            $option['notfound_background'] = array(
                'background-color'      => 'rgb(0,0,0)',
                'background-repeat'     => 'repeat',
                'background-position'   => 'center center',
                'background-size'       => 'cover',
                'background-attachment' => 'inherit'
            );
            $option['notfound_bg_style'] = '';

            return $option;
        }

        function register( $wp_customize ) {

            /**
             * Option : 404 Meaage
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[enable_404message]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Switch(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[enable_404message]', array(
                            'type'        => 'wdt-switch',
                            'label'       => esc_html__( 'Enable Message', 'taaza-pro'),
                            'description' => esc_html__('YES! to enable not-found page message.', 'taaza-pro'),
                            'section'     => 'site-404-page-section',
                            'choices'     => array(
                                'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                'off' => esc_attr__( 'No', 'taaza-pro' )
                            )
                        )
                    )
                );

            /**
             * Option : Template Style
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[notfound_style]', array(
                        'default' => 'type2',
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[notfound_style]', array(
                            'type'    => 'select',
                            'section' => 'site-404-page-section',
                            'label'   => esc_html__( 'Template Style', 'taaza-pro' ),
                            'choices' => array(
                                'type1'  => esc_html__('Modern', 'taaza-pro'),
                                'type2'  => esc_html__('Classic', 'taaza-pro'),
                                'type4'  => esc_html__('Diamond', 'taaza-pro'),
                                'type5'  => esc_html__('Shadow', 'taaza-pro'),
                                'type6'  => esc_html__('Diamond Alt', 'taaza-pro'),
                                'type7'  => esc_html__('Stack', 'taaza-pro'),
                                'type8'  => esc_html__('Minimal', 'taaza-pro'),
                            ),
                            'description' => esc_html__('Choose the style of not-found template page.', 'taaza-pro'),
                        )
                    )
                );

            /**
             * Option : Notfound Dark BG
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[notfound_darkbg]', array(
                        'default' => '',
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Switch(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[notfound_darkbg]', array(
                            'type'        => 'wdt-switch',
                            'label'       => esc_html__( '404 Dark BG', 'taaza-pro'),
                            'description' => esc_html__('YES! to use dark bg notfound page for this site.', 'taaza-pro'),
                            'section'     => 'site-404-page-section',
                            'choices'     => array(
                                'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                'off' => esc_attr__( 'No', 'taaza-pro' )
                            )
                        )
                    )
                );

            /**
             * Option : Custom Page
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[notfound_pageid]', array(
                        'default' => '',
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[notfound_pageid]', array(
                            'type'        => 'select',
                            'section'     => 'site-404-page-section',
                            'label'       => esc_html__( 'Custom Page', 'taaza-pro' ),
                            'choices'     => $this->pages_list(),
                            'description' => esc_html__('Choose the page for not-found content.', 'taaza-pro'),
                        )
                    )
                );

            /**
             * Option : 404 Background
             */
                $wp_customize->add_setting(
                    TAAZA_CUSTOMISER_VAL . '[notfound_background]', array(
                        'type'    => 'option',
                    )
                );

                $wp_customize->add_control(
                    new Taaza_Customize_Control_Background(
                        $wp_customize, TAAZA_CUSTOMISER_VAL . '[notfound_background]', array(
                            'type'    => 'wdt-background',
                            'section' => 'site-404-page-section',
                            'label'   => esc_html__( 'Background', 'taaza-pro' ),
                        )
                    )
                );

            /**
             * Option : Custom Styles
             */
            $wp_customize->add_setting(
                TAAZA_CUSTOMISER_VAL . '[notfound_bg_style]', array(
                    'type'    => 'option',
                )
            );

            $wp_customize->add_control(
                new Taaza_Customize_Control(
                    $wp_customize, TAAZA_CUSTOMISER_VAL . '[notfound_bg_style]', array(
                        'type'    	  => 'textarea',
                        'section'     => 'site-404-page-section',
                        'label'       => esc_html__( 'Custom Inline Styles', 'taaza-pro' ),
                        'description' => esc_html__('Paste custom CSS styles for not found page.', 'taaza-pro'),
                        'input_attrs' => array(
                            'placeholder' => esc_html__( 'color:#ff00bb; text-align:left;', 'taaza-pro' ),
                        ),
                    )
                )
            );

        }

        function pages_list() {
            $choices     = array();
            $choices[''] = esc_html__('Choose the page', 'taaza-pro');

            $args  = array(
                'post_type'   => 'page',
                'post_status' => 'publish'
            );
            $pages = get_pages($args);

            foreach( $pages as $page ):
                $choices[$page->ID]	= $page->post_title;
            endforeach;

            return $choices;
        }

    }
}

TaazaProCustomizerSite404::instance();