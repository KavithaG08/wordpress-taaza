<?php
/**
 * Plugin Name: WeDesignTech Ultimate Booking Addon
 * Description: A simple wordpress plugin designed to implements <strong>reservation addon features of WeDesignTech</strong>
 * Version: 1.0.2
 * Author: the WeDesignTech team
 * Author URI: https://wedesignthemes.com/
 * Text Domain: wdt-ultimate-booking
 */

if (! class_exists ( 'UltimateBookingPro' )) {

	class UltimateBookingPro {

		/**
		 * Instance variable
		 */
		private static $_instance = null;

		/**
		 * Base Plugin URL
		 */
		private $plugin_url = null;

		/**
		 * Base Plugin Path
		 */
		private $plugin_path = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {

			add_action ( 'init', array($this, 'ultimateBookingProTextDomain') );
			add_action ( 'plugins_loaded', array($this, 'ultimateBookingProPluginsLoaded') );

			define( 'ULTIMATEBOOKINGPRO_PATH', dirname( __FILE__ ) );
			define( 'ULTIMATEBOOKINGPRO_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );

			register_activation_hook( __FILE__ , array( $this , 'ultimateBookingProActivate' ) );
			register_deactivation_hook( __FILE__ , array( $this , 'ultimateBookingProDeactivate' ) );

			// Include Codestar Framework
			if( ! function_exists( 'cs_framework_init' ) && ! class_exists( 'CSFramework' ) ) {
                if( !defined( 'CS_OPTION' ) ) {
                    define( 'CS_OPTION', '_taaza_cs_options' );
                }
				require_once plugin_dir_path ( __FILE__ ) . 'cs-framework/cs-framework.php';
			}

			// Include Functions
			require_once plugin_dir_path ( __FILE__ ) . '/functions/core-functions.php';
			require_once plugin_dir_path ( __FILE__ ) . '/functions/reservation-functions.php';
			require_once plugin_dir_path ( __FILE__ ) . '/functions/cal-reservation-functions.php';
			require_once plugin_dir_path ( __FILE__ ) . '/functions/template-functions.php';

			// Register Custom Post Types
			require_once plugin_dir_path ( __FILE__ ) . '/post-types/register-post-types.php';
			if(class_exists( 'UltimateBookingProCustomPostTypes' )){
				new UltimateBookingProCustomPostTypes();
			}

			// Register Templates
			require_once plugin_dir_path ( __FILE__ ) . '/templates/register-templates.php';
			if(class_exists('UltimateBookingProTemplates')){
				new UltimateBookingProTemplates();
			}

			// Register Visual Composer
			require_once plugin_dir_path ( __FILE__ ) . '/assets/register-assets.php';
			if(class_exists('UltimateBookingModules')){
				new UltimateBookingModules();
			}

			// Theme Support
			$this->ultimate_booking_pro_support_includes();
		}


		/**
		 * Load Text Domain
		 */
		public function ultimateBookingProTextDomain() {
			load_plugin_textdomain ( 'wdt-ultimate-booking', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Check Plugin Is Active
		 */
		public function ultimateBookingProIsPluginActive( $plugin ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if( is_plugin_active( $plugin ) || is_plugin_active_for_network( $plugin ) ) return true;
			else return false;
		}

		/**
		 * Include Theme Suports
		 */
		public function ultimate_booking_pro_support_includes() {

			include_once plugin_dir_path ( __FILE__ ) . '/theme-support/class-default.php';

		}

		public function ultimateBookingProPluginsLoaded() {

			// Check if Elementor installed and activated
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', array( $this, 'missing_elementor_plugin' ) );
				return;
			}

			// Register Elementor Category
			add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );

			// Register Elementor Widgets
			require $this->plugin_path( 'widgets/class-register-widgets.php' );
		}

		/**
		 * Admin notice
		 * Warning when the site doesn't have Elementor installed or activated.
		 */
		public function missing_elementor_plugin() {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" recommended "%2$s" to be installed and activated.', 'wdt-ultimate-booking' ),
				'<strong>' . esc_html__( 'Ultimate Booking Pro', 'wdt-ultimate-booking' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'wdt-ultimate-booking' ) . '</strong>'
			);

			printf( '<div class="notice notice-info is-dismissible"><p>%1$s</p></div>', $message );
		}

		/**
		 * Register category
		 * Add Booking Manager category in elementor
		 */
		public function register_category( $elements_manager ) {

			$elements_manager->add_category(
				'dt-widgets',array(
					'title' => esc_html__( 'Booking Manager', 'wdt-ultimate-booking' ),
					'icon'  => 'font'
				)
			);
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}

		/**
		 * Returns url to file or dir inside plugin folder
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Custom Manager Activate
		 */
		public static function ultimateBookingProActivate() {
		}

		/**
		 * Custom Manager Deactivate
		 */
		public static function ultimateBookingProDeactivate() {
		}
	}
}

if( !function_exists('ultimate_booking_pro') ) {

	function ultimate_booking_pro() {
		return UltimateBookingPro::instance();
	}
}

ultimate_booking_pro();