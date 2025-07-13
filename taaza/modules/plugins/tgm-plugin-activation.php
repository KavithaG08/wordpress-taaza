<?php
/**
 * Recommends plugins for use with the theme via the TGMA Script
 *
 * @package Taaza WordPress theme
 */

function taaza_tgmpa_plugins_register() {

	// Get array of recommended plugins.

	$plugins_list = array(
        
        array(
            'name'     => esc_html__('Elementor', 'taaza'),
            'slug'     => 'elementor',
            'required' => true,
        ),
        array(
            'name'     => esc_html__('Contact Form 7', 'taaza'),
            'slug'     => 'contact-form-7',
            'required' => true,
		),
		array(
            'name'               => esc_html__('Taaza Plus', 'taaza'),
            'slug'               => 'taaza-plus',
            'source'             => TAAZA_MODULE_DIR . '/plugins/taaza-plus.rar',
            'required'           => true,
            'version'            => '1.0.0',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
        array(
            'name'               => esc_html__('Taaza Pro', 'taaza'),
            'slug'               => 'taaza-pro',
            'source'             => TAAZA_MODULE_DIR . '/plugins/taaza-pro.rar',
            'required'           => true,
            'version'            => '1.0.0',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
		array(
            'name'               => esc_html__('WeDesignTech Elementor Addon', 'taaza'),
            'slug'               => 'wedesigntech-elementor-addon',
            'source'             => TAAZA_MODULE_DIR . '/plugins/wedesigntech-elementor-addon.rar',
            'required'           => true,
            'version'            => '1.0.1',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
		array(
            'name'               => esc_html__('WeDesignTech Ultimate Booking Addon', 'taaza'),
            'slug'               => 'wedesigntech-ultimate-booking-addon',
            'source'             => TAAZA_MODULE_DIR . '/plugins/wedesigntech-ultimate-booking-addon.rar',
            'required'           => true,
            'version'            => '1.0.2',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
		array(
            'name'     => esc_html__('WooCommerce', 'taaza'),
            'slug'     => 'woocommerce',
            'required' => true,
        ),
		array(
            'name'     => esc_html__('One Click Demo Import', 'taaza'),
            'slug'     => 'one-click-demo-import',
            'required' => true,
        )
	);

    $plugins = apply_filters('taaza_required_plugins_list', $plugins_list);

	// Register notice
	tgmpa( $plugins, array(
		'id'           => 'taaza_theme',
		'domain'       => 'taaza',
		'menu'         => 'install-required-plugins',
		'has_notices'  => true,
		'is_automatic' => true,
		'dismissable'  => true,
	) );

}
add_action( 'tgmpa_register', 'taaza_tgmpa_plugins_register' );