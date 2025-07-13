<?php

/**
 * WooCommerce - Single - Module - Social Share & Follow - Customizer Settings
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Customizer_Single_Social_Share_And_Follow' ) ) {

    class Taaza_Shop_Customizer_Single_Social_Share_And_Follow {

        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            add_filter( 'taaza_woo_single_page_settings', array( $this, 'single_page_settings' ), 10, 1 );
            add_action( 'customize_register', array( $this, 'register' ), 15);

        }

        function single_page_settings( $settings ) {

            $product_show_sharer_facebook                = taaza_customizer_settings('wdt-single-product-show-sharer-facebook' );
            $settings['product_show_sharer_facebook']    = $product_show_sharer_facebook;

            $product_show_sharer_delicious               = taaza_customizer_settings('wdt-single-product-show-sharer-delicious' );
            $settings['product_show_sharer_delicious']   = $product_show_sharer_delicious;

            $product_show_sharer_digg                    = taaza_customizer_settings('wdt-single-product-show-sharer-digg' );
            $settings['product_show_sharer_digg']        = $product_show_sharer_digg;

            $product_show_sharer_twitter                 = taaza_customizer_settings('wdt-single-product-show-sharer-twitter' );
            $settings['product_show_sharer_twitter']     = $product_show_sharer_twitter;

            $product_show_sharer_linkedin                = taaza_customizer_settings('wdt-single-product-show-sharer-linkedin' );
            $settings['product_show_sharer_linkedin']    = $product_show_sharer_linkedin;

            $product_show_sharer_pinterest               = taaza_customizer_settings('wdt-single-product-show-sharer-pinterest' );
            $settings['product_show_sharer_pinterest']   = $product_show_sharer_pinterest;

            return $settings;

        }

        function register( $wp_customize ) {

            /**
            * Share
            */

                /**
                * Option : Sharer Description
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-description]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-description]', array(
                                'type'        => 'wdt-description',
                                'label'       => esc_html__( 'Note: ', 'taaza-pro'),
                                'section'     => 'woocommerce-single-page-sociable-share-section',
                                'description' => esc_html__( 'This option is applicable only for WooCommerce "Custom Template".', 'taaza-pro')
                            )
                        )
                    );

                /**
                * Option : Show Facebook Sharer
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-facebook]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-facebook]', array(
                                'type'    => 'wdt-switch',
                                'label'   => esc_html__( 'Show Facebook Sharer', 'taaza-pro'),
                                'section' => 'woocommerce-single-page-sociable-share-section',
                                'choices' => array(
                                    'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                    'off' => esc_attr__( 'No', 'taaza-pro' )
                                )
                            )
                        )
                    );

                /**
                * Option : Show Delicious Sharer
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-delicious]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-delicious]', array(
                                'type'    => 'wdt-switch',
                                'label'   => esc_html__( 'Show Delicious Sharer', 'taaza-pro'),
                                'section' => 'woocommerce-single-page-sociable-share-section',
                                'choices' => array(
                                    'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                    'off' => esc_attr__( 'No', 'taaza-pro' )
                                )
                            )
                        )
                    );

                /**
                * Option : Show Digg Sharer
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-digg]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-digg]', array(
                                'type'    => 'wdt-switch',
                                'label'   => esc_html__( 'Show Digg Sharer', 'taaza-pro'),
                                'section' => 'woocommerce-single-page-sociable-share-section',
                                'choices' => array(
                                    'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                    'off' => esc_attr__( 'No', 'taaza-pro' )
                                )
                            )
                        )
                    );

                /**
                * Option : Show Twitter Sharer
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-twitter]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-twitter]', array(
                                'type'    => 'wdt-switch',
                                'label'   => esc_html__( 'Show Twitter Sharer', 'taaza-pro'),
                                'section' => 'woocommerce-single-page-sociable-share-section',
                                'choices' => array(
                                    'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                    'off' => esc_attr__( 'No', 'taaza-pro' )
                                )
                            )
                        )
                    );

                /**
                * Option : Show LinkedIn Sharer
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-linkedin]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-linkedin]', array(
                                'type'    => 'wdt-switch',
                                'label'   => esc_html__( 'Show LinkedIn Sharer', 'taaza-pro'),
                                'section' => 'woocommerce-single-page-sociable-share-section',
                                'choices' => array(
                                    'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                    'off' => esc_attr__( 'No', 'taaza-pro' )
                                )
                            )
                        )
                    );

                /**
                * Option : Show Pinterest Sharer
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-pinterest]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-sharer-pinterest]', array(
                                'type'    => 'wdt-switch',
                                'label'   => esc_html__( 'Show Pinterest Sharer', 'taaza-pro'),
                                'section' => 'woocommerce-single-page-sociable-share-section',
                                'choices' => array(
                                    'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                    'off' => esc_attr__( 'No', 'taaza-pro' )
                                )
                            )
                        )
                    );

            /**
            * Follow
            */

                /**
                * Option : Follow Description
                */
                    $wp_customize->add_setting(
                        TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-follow-description]', array(
                            'type' => 'option'
                        )
                    );

                    $wp_customize->add_control(
                        new Taaza_Customize_Control_Switch(
                            $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-follow-description]', array(
                                'type'    => 'wdt-description',
                                'label'   => esc_html__( 'Note :', 'taaza-pro'),
                                'section' => 'woocommerce-single-page-sociable-follow-section',
                                'description'   => esc_html__( 'This option is applicable only for WooCommerce "Custom Template".', 'taaza-pro'),
                            )
                        )
                    );

                    $social_follow = array (
                        'delicious'   => esc_html__('Delicious', 'taaza-pro'),
                        'deviantart'  => esc_html__('Deviantart', 'taaza-pro'),
                        'digg'        => esc_html__('Digg', 'taaza-pro'),
                        'dribbble'    => esc_html__('Dribbble', 'taaza-pro'),
                        'envelope'    => esc_html__('Envelope', 'taaza-pro'),
                        'facebook'    => esc_html__('Facebook', 'taaza-pro'),
                        'flickr'      => esc_html__('Flickr', 'taaza-pro'),
                        'google-plus' => esc_html__('Google Plus', 'taaza-pro'),
                        'instagram'   => esc_html__('Instagram', 'taaza-pro'),
                        'lastfm'      => esc_html__('Lastfm', 'taaza-pro'),
                        'linkedin'    => esc_html__('Linkedin', 'taaza-pro'),
                        'myspace'     => esc_html__('Myspace', 'taaza-pro'),
                        'pinterest'   => esc_html__('Pinterest', 'taaza-pro'),
                        'reddit'      => esc_html__('Reddit', 'taaza-pro'),
                        'rss'         => esc_html__('RSS', 'taaza-pro'),
                        'skype'       => esc_html__('Skype', 'taaza-pro'),
                        'stumbleupon' => esc_html__('Stumbleupon', 'taaza-pro'),
                        'tumblr'      => esc_html__('Tumblr', 'taaza-pro'),
                        'twitter'     => esc_html__('Twitter', 'taaza-pro'),
                        'viadeo'      => esc_html__('Viadeo', 'taaza-pro'),
                        'vimeo'       => esc_html__('Vimeo', 'taaza-pro'),
                        'yahoo'       => esc_html__('Yahoo', 'taaza-pro'),
                        'youtube'     => esc_html__('Youtube', 'taaza-pro')
                    );

                    foreach($social_follow as $socialfollow_key => $socialfollow) {

                        $wp_customize->add_setting(
                            TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-follow-'.$socialfollow_key.']', array(
                                'type' => 'option'
                            )
                        );

                        $wp_customize->add_control(
                            new Taaza_Customize_Control_Switch(
                                $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-show-follow-'.$socialfollow_key.']', array(
                                    'type'    => 'wdt-switch',
                                    'label'   => sprintf(esc_html__('Show %1$s Follow', 'taaza-pro'), $socialfollow),
                                    'section' => 'woocommerce-single-page-sociable-follow-section',
                                    'choices' => array(
                                        'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
                                        'off' => esc_attr__( 'No', 'taaza-pro' )
                                    )
                                )
                            )
                        );

                        $wp_customize->add_setting(
                            TAAZA_CUSTOMISER_VAL . '[wdt-single-product-follow-'.$socialfollow_key.'-link]', array(
                                'type' => 'option'
                            )
                        );

                        $wp_customize->add_control(
                            new Taaza_Customize_Control(
                                $wp_customize, TAAZA_CUSTOMISER_VAL . '[wdt-single-product-follow-'.$socialfollow_key.'-link]', array(
                                    'type'       => 'text',
                                    'section'    => 'woocommerce-single-page-sociable-follow-section',
                                    'input_attrs' => array(
                                        'placeholder' => sprintf(esc_html__('%1$s Link', 'taaza-pro'), $socialfollow)
                                    ),
                                    'dependency' => array ( 'wdt-single-product-show-follow-'.$socialfollow_key, '==', '1' )
                                )
                            )
                        );

                    }

        }

    }

}


if( !function_exists('taaza_shop_customizer_single_social_share_and_follow') ) {
	function taaza_shop_customizer_single_social_share_and_follow() {
		return Taaza_Shop_Customizer_Single_Social_Share_And_Follow::instance();
	}
}

taaza_shop_customizer_single_social_share_and_follow();