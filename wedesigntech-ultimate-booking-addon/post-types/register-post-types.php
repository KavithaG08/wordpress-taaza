<?php
if (! class_exists ( 'UltimateBookingProCustomPostTypes' )) {

	class UltimateBookingProCustomPostTypes {

		function __construct() {

			// Required From Plugin
			if (!defined('CS_ACTIVE_FRAMEWORK')) define('CS_ACTIVE_FRAMEWORK', true);
			if (!defined('CS_ACTIVE_METABOX')) define('CS_ACTIVE_METABOX', true);
			if (!defined('CS_ACTIVE_TAXONOMY')) define('CS_ACTIVE_TAXONOMY', true);
			if (!defined('CS_ACTIVE_SHORTCODE')) define('CS_ACTIVE_SHORTCODE', true);

			// Can be changed in theme or other plugins using Codestar
			if (!defined('CS_ACTIVE_CUSTOMIZE')) define('CS_ACTIVE_CUSTOMIZE', false);
			if (!defined('CS_ACTIVE_LIGHT_THEME')) define('CS_ACTIVE_LIGHT_THEME', false);

			add_filter ( 'cs_shortcode_options', array (
				$this,
				'ultimate_booking_pro_cs_shortcode_options'
			) );

			add_filter ( 'cs_framework_options', array (
				$this,
				'ultimate_booking_pro_cs_framework_options'
			) );

            // add_filter ( 'cs_framework_options', array (
			// 	$this,
			// 	'ultimate_booking_pro_cs_framework_backup_options'
            // ), 100 );

			add_filter ( 'cs_framework_settings', array (
				$this,
				'ultimate_booking_pro_cs_framework_settings'
			) );

			// Room custom post type
			require_once plugin_dir_path ( __FILE__ ) . '/dt-room-post-type.php';
			if (class_exists ( 'DTRoomPostType' )) {
				new DTRoomPostType();
			}

			require_once plugin_dir_path ( __FILE__ ) . '/dt-room-wc.php';

			// Staff custom post type
			require_once plugin_dir_path ( __FILE__ ) . '/dt-staff-post-type.php';
			if (class_exists ( 'DTStaffPostType' )) {
				new DTStaffPostType();
			}

			add_action( 'plugins_loaded', array( $this, 'bookig_shop_plugins_loaded' ) );

		}
		function bookig_shop_plugins_loaded(){
			
			if( !function_exists( 'is_woocommerce' ) ) {
				add_action( 'admin_notices', function() {
					echo '<div class="error"><p>' . esc_html__( 'WooCommerce is not active taaza. Please install and activate WooCommerce.', 'wdt-ultimate-booking' ) . '</p></div>';
				});
			}

		}

		function ultimate_booking_pro_cs_shortcode_options( $options ) {

			$codestar = ultimate_booking_pro_has_codestar();
			$options  =  ( $codestar ) ? $options : array();

			require_once plugin_dir_path( __DIR__ ) . 'cs-framework-override/config/shortcodes/base.php';
			$obj = new DTBooking_Cs_Sc_Base;
			$options = $obj->DTBooking_cs_sc_Combined();

			return $options;
		}

		/**
		 * Room framework options
		 */
		function ultimate_booking_pro_cs_framework_options( $options ) {

			global $timearray;
			$timearray = array( '' => 'OFF', '00:00' => '12:00 am', '00:15' => '12:15 am', '00:30' => '12:30 am', '00:45' => '12:45 am', '01:00' => '1:00 am', '01:15' => '1:15 am',
						   '01:30' => '1:30 am', '01:45' => '1:45 am', '02:00' => '2:00 am', '02:15' => '2:15 am', '02:30' => '2:30 am', '02:45' => '2:45 am', '03:00' => '3:00 am',
						   '03:15' => '3:15 am', '03:30' => '3:30 am', '03:45' => '3:45 am', '04:00' => '4:00 am', '04:15' => '4:15 am', '04:30' => '4:30 am', '04:45' => '4:45 am',
						   '05:00' => '5:00 am', '05:15' => '5:15 am', '05:30' => '5:30 am', '05:45' => '5:45 am', '06:00' => '6:00 am', '06:15' => '6:15 am', '06:30' => '6:30 am',
						   '06:45' => '6:45 am', '07:00' => '7:00 am', '07:15' => '7:15 am', '07:30' => '7:30 am', '07:45' => '7:45 am', '08:00' => '8:00 am', '08:15' => '8:15 am',
						   '08:30' => '8:30 am', '08:45' => '8:45 am', '09:00' => '9:00 am', '09:15' => '9:15 am', '09:30' => '9:30 am', '09:45' => '9:45 am', '10:00' => '10:00 am',
						   '10:15' => '10:15 am', '10:30' => '10:30 am', '10:45' => '10:45 am', '11:00' => '11:00 am', '11:15' => '11:15 am', '11:30' => '11:30 am', '11:45' => '11:45 am',
						   '12:00' => '12:00 pm', '12:15' => '12:15 pm', '12:30' => '12:30 pm', '12:45' => '12:45 pm', '13:00' => '1:00 pm', '13:15' => '1:15 pm', '13:30' => '1:30 pm',
						   '13:45' => '1:45 pm', '14:00' => '2:00 pm', '14:15' => '2:15 pm', '14:30' => '2:30 pm', '14:45' => '2:45 pm', '15:00' => '3:00 pm', '15:15' => '3:15 pm',
						   '15:30' => '3:30 pm', '15:45' => '3:45 pm', '16:00' => '4:00 pm', '16:15' => '4:15 pm', '16:30' => '4:30 pm', '16:45' => '4:45 pm', '17:00' => '5:00 pm',
						   '17:15' => '5:15 pm', '17:30' => '5:30 pm', '17:45' => '5:45 pm', '18:00' => '6:00 pm', '18:15' => '6:15 pm', '18:30' => '6:30 pm', '18:45' => '6:45 pm',
						   '19:00' => '7:00 pm', '19:15' => '7:15 pm', '19:30' => '7:30 pm', '19:45' => '7:45 pm', '20:00' => '8:00 pm', '20:15' => '8:15 pm', '20:30' => '8:30 pm',
						   '20:45' => '8:45 pm', '21:00' => '9:00 pm', '21:15' => '9:15 pm', '21:30' => '9:30 pm', '21:45' => '9:45 pm', '22:00' => '10:00 pm', '22:15' => '10:15 pm',
						   '22:30' => '10:30 pm', '22:45' => '10:45 pm', '23:00' => '11:00 pm', '23:15' => '11:15 pm', '23:30' => '11:30 pm', '23:45' => '11:45 pm' );

			$currencies = array();
			$currency_codes = ultimate_booking_pro_get_currencies();
			foreach( $currency_codes as $code => $value ){
				$currencies[$code] = $value . ' ('. ultimate_booking_pro_get_currency_symbol( $code ) .')';
			}

			$codestar = ultimate_booking_pro_has_codestar();
			$options  =  ( $codestar ) ? $options : array();

			$options['booking-manager'] = array(
			  'name'        => 'wdt-ultimate-booking',
			  'title'       => esc_html__('Booking Options', 'wdt-ultimate-booking'),
			  'icon'        => 'fa fa-calendar',
			  'sections'	=> array(

				  // -----------------------------------------
				  // General Options
				  // -----------------------------------------
				  array(
					'name'	=> 'general_options',
					'title' => esc_html__('General Options', 'wdt-ultimate-booking'),
					'icon'  => 'fa fa-gear',

					'fields'	=> array(

						array(
						  'type'    => 'subheading',
						  'content' => esc_html__( 'General Options', 'wdt-ultimate-booking' ),
						),

						array(
						  'id'  	=> 'enable-room-taxonomy',
						  'type'  	=> 'switcher',
						  'title' 	=> esc_html__("Enable Room's  Categories", "wdt-ultimate-booking"),
						  'label'	=> esc_html__("YES! to enable Room's  taxonomy", "wdt-ultimate-booking")
						),

						array(
						  'id'  	=> 'enable-room-amenity',
						  'type'  	=> 'switcher',
						  'title' 	=> esc_html__("Enable Room's  Amenities", "wdt-ultimate-booking"),
						  'label'	=> esc_html__("YES! to enable Room's  amenities", "wdt-ultimate-booking"),
						  'default' => true,
						),

						array(
						  'id'  	=> 'enable-staff-taxonomy',
						  'type'  	=> 'switcher',
						  'title' 	=> esc_html__("Enable Staff's Departments", "wdt-ultimate-booking"),
						  'label'	=> esc_html__("YES! to enable staff's taxonomy", "wdt-ultimate-booking")
						),

						array(
							'id'  	=> 'enable-price-in-dropdown',
							'type'  	=> 'switcher',
							'title' 	=> esc_html__("Enable Price in Staff & Room dropdown", "wdt-ultimate-booking"),
							'label'	=> esc_html__("YES! to enable price in Staff & Room dropdown", "wdt-ultimate-booking")
						  ),

						array(
						  'id'         => 'book-currency',
						  'type'       => 'select',
						  'title'      => esc_html__('Currency', 'wdt-ultimate-booking'),
						  'options'    => $currencies,
						  'class'      => 'chosen',
						  'default'    => 'USD',
						),

						array(
						  'id'           => 'currency-pos',
						  'type'         => 'select',
						  'title'        => esc_html__('Currency Position', 'wdt-ultimate-booking'),
						  'options'      => array(
							'left' 			   => esc_html__('Left ( $36.55 )', 'wdt-ultimate-booking'),
							'right'      	   => esc_html__('Right ( 36.55$ )', 'wdt-ultimate-booking'),
							'left-with-space'  => esc_html__('Left with space ( $ 36.55 )', 'wdt-ultimate-booking'),
							'right-with-space' => esc_html__('Right with space ( 36.55 $ )', 'wdt-ultimate-booking'),
						  	),
						  'class'        => 'chosen',
						),
					),
			  	 ),
			    ),
			);

			return $options;
		}

		function ultimate_booking_pro_cs_framework_settings($settings){

			$codestar = ultimate_booking_pro_has_codestar();
			if( !$codestar ) {

				$settings           = array(
                    'menu_title'      => esc_html__('WeDesignTech Settings', 'wdt-ultimate-booking'),
                    'menu_type'       => 'menu',
                    'menu_slug'       => 'wedesigntech-settings',
                    'ajax_save'       => true,
                    'show_reset_all'  => false,
                    'framework_title' => esc_html__('WeDesignTech Settings', 'wdt-ultimate-booking')
                  );

			}

			return $settings;
		}
	}
}