<?php
if (! class_exists ( 'UltimateBookingModules' )) {

	class UltimateBookingModules {

		function __construct() {

			add_action( 'wp_enqueue_scripts', array ( $this, 'ultimate_booking_pro_wp_enqueue_scripts' ) );

		}


		function ultimate_booking_pro_wp_enqueue_scripts() {

			$themeData = wp_get_theme();
			$version = $themeData->get('Version');

			wp_enqueue_style( 'dt-dropdown', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/dropdown.css', false, $version, 'all' );
			wp_enqueue_style( 'dt-column', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/column.css', false, $version, 'all' );
			wp_enqueue_style( 'wdt-ultimate-booking', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/booking.css', false, $version, 'all' );
			wp_enqueue_style( 'wdt-ultimate-icons', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/dt-icons.css', false, $version, 'all' );

			wp_enqueue_style( 'wdt-ultimate-booking-swiper', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/swiper.min.css', false, $version, 'all' );
			wp_enqueue_style( 'wdt-ultimate-booking-swiper-carousel', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/carousel.css', false, $version, 'all' );

			wp_enqueue_script( 'dt-dropdown', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/dropdown.js', array('jquery'), false, true );

			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui-datepicker', 'https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css' );

			wp_enqueue_script( 'dt-reservation', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/reservation.js', array(), false, true );
			wp_enqueue_script( 'wdt-ultimate-booking-swiper-js', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/swiper.min.js', array(), false, true );
			wp_enqueue_script( 'wdt-ultimate-booking-carousel-js', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/carousel.js', array(), false, true );
			wp_enqueue_script( 'jquery-validate', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/jquery.validate.min.js', array(), false, true );
			wp_localize_script( 'dt-reservation', 'ultimateBookingPro', array(
				'ajaxurl'         => admin_url('admin-ajax.php'),
				'name'         => esc_html__('Name:', 'wdt-ultimate-booking'),
				'phone'         => esc_html__('Phone:', 'wdt-ultimate-booking'),
				'email'         => esc_html__('Email', 'wdt-ultimate-booking'),
				'address'         => esc_html__('Address', 'wdt-ultimate-booking'),
				'message'         => esc_html__('Message', 'wdt-ultimate-booking'),
				'plugin_url'      => plugin_dir_url ( __FILE__ ),
				'eraptdatepicker' => esc_html__('Please Select Room and Date!', 'wdt-ultimate-booking'),
				'stripe_pub_api'  => cs_get_option('stripe-publishable-api-key')
			));

			wp_enqueue_script('dt-booking-script', plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/dt-booking.js', ['jquery'], null, true);
			wp_localize_script('dt-booking-script', 'dtBooking', [
				'ajax_url' => admin_url('admin-ajax.php'),
				'validation_nonce' => wp_create_nonce('dt_validate_booking'),
			]);

			$stripe = cs_get_option('enable-stripe');
			if( !empty($stripe) ):
				wp_enqueue_script ( 'stripe-js', 'https://js.stripe.com/v3/' );
			endif;
		}


	}
}