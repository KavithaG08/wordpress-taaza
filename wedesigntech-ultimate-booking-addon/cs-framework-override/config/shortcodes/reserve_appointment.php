<?php
if (! class_exists ( 'DTBooking_Cs_Sc_ReserveAppointment' ) ) {

    class DTBooking_Cs_Sc_ReserveAppointment {

        function DTBooking_sc_ReserveAppointment() {

			$options = array(
			  'name'      => 'dt_sc_reserve_appointment',
			  'title'     => esc_html__('Reserve Appointment', 'wdt-ultimate-booking'),
			  'fields'    => array(

				array(
				  'id'    => 'title',
				  'type'  => 'text',
				  'title' => esc_html__( 'Title', 'wdt-ultimate-booking' )
				),
				array(
				  'id'           => 'type',
				  'type'         => 'select',
				  'title'        => esc_html__('Type', 'wdt-ultimate-booking'),
				  'options'      => array(
					'type1'      => esc_html__('Type - I', 'wdt-ultimate-booking'),
					'type2'      => esc_html__('Type - II', 'wdt-ultimate-booking'),
				  ),
				  'class'        => 'chosen',
				  'default'      => 'type1',
				  'info'         => esc_html__('Choose type of reservation to display.', 'wdt-ultimate-booking')
				),
			  ),
			);

			return $options;
		}
	}				
}