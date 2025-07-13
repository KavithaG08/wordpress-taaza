<?php
if (! class_exists ( 'DTBooking_Cs_Sc_StaffItem' ) ) {

    class DTBooking_Cs_Sc_StaffItem {

        function DTBooking_sc_StaffItem() {

			$plural_name = '';
			if( function_exists( 'ultimate_booking_pro_cs_get_option' ) ) :
				$plural_name	=	ultimate_booking_pro_cs_get_option( 'singular-staff-text', esc_html__('Staff', 'wdt-ultimate-booking') );
			endif;

			$options = array(
			  'name'      => 'dt_sc_staff_item',
			  'title'     => $plural_name,
			  'fields'    => array(

				array(
				  'id'    => 'staff_id',
				  'type'  => 'text',
				  'title' => esc_html__( 'Enter Staff ID', 'wdt-ultimate-booking' ),
				  'after' => '<div class="cs-text-muted">'.esc_html__('Enter ID of staff to display. More than one ids with comma(,) seperated.', 'wdt-ultimate-booking').'</div>',
				),
				array(
				  'id'        => 'type',
				  'type'      => 'select',
				  'title'     => esc_html__('Type', 'wdt-ultimate-booking'),
				  'options'   => array(
					'type1'    => esc_html__('Type - 1', 'wdt-ultimate-booking'),
					'type2'    => esc_html__('Type - 2', 'wdt-ultimate-booking')
				  ),
				  'class'     => 'chosen',
				  'default'   => 'desc',
				  'info'      => esc_html__('Choose type of staffs to display.', 'wdt-ultimate-booking')
				),
				array(
				  'id'        => 'show_button',
				  'type'      => 'select',
				  'title'     => esc_html__('Show button?', 'wdt-ultimate-booking'),
				  'options'   => array(
					'yes'   => esc_html__('Yes', 'wdt-ultimate-booking'),
					'no'    => esc_html__('No', 'wdt-ultimate-booking')
				  ),
				  'class'     => 'chosen',
				  'default'   => 'no',
				  'info'      => esc_html__('Choose "Yes" to show button.', 'wdt-ultimate-booking')
				),
				array(
				  'id'    => 'button_text',
				  'type'  => 'text',
				  'title' => esc_html__( 'Button Text', 'wdt-ultimate-booking' ),
				  'default' => esc_html__('Book an appointment', 'wdt-ultimate-booking'),
				  'info'  => esc_html__( 'Enter button text.', 'wdt-ultimate-booking' )
				)
			  ),
			);

			return $options;
		}
	}				
}