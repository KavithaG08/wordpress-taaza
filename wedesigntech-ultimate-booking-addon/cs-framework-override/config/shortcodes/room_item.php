<?php
if (! class_exists ( 'DTBooking_Cs_Sc_RoomItem' ) ) {

    class DTBooking_Cs_Sc_RoomItem {

        function DTBooking_sc_RoomItem() {

			$plural_name = '';
			if( function_exists( 'ultimate_booking_pro_cs_get_option' ) ) :
				$plural_name	=	ultimate_booking_pro_cs_get_option( 'singular-room-text', esc_html__('Room', 'wdt-ultimate-booking') );
			endif;

			$options = array(
			  'name'      => 'dt_sc_room_item',
			  'title'     => $plural_name,
			  'fields'    => array(

				array(
				  'id'    => 'room_id',
				  'type'  => 'text',
				  'title' => esc_html__( 'Enter Room ID', 'wdt-ultimate-booking' ),
				  'after' => '<div class="cs-text-muted">'.esc_html__('Enter IDs of rooms to display. More than one ids with comma(,) seperated.', 'wdt-ultimate-booking').'</div>',
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
				  'info'      => esc_html__('Choose type of rooms to display.', 'wdt-ultimate-booking')
				),
				array(
				  'id'        => 'excerpt',
				  'type'      => 'select',
				  'title'     => esc_html__('Show Excerpt?', 'wdt-ultimate-booking'),
				  'options'   => array(
					'yes'   => esc_html__('Yes', 'wdt-ultimate-booking'),
					'no'    => esc_html__('No', 'wdt-ultimate-booking')
				  ),
				  'class'     => 'chosen',
				  'default'   => 'no',
				  'info'      => esc_html__('Choose "Yes" to show excerpt.', 'wdt-ultimate-booking')
				),
				array(
				  'id'    => 'excerpt_length',
				  'type'  => 'text',
				  'title' => esc_html__( 'Excerpt Length', 'wdt-ultimate-booking' ),
				  'default' => 12
				),
				array(
				  'id'        => 'meta',
				  'type'      => 'select',
				  'title'     => esc_html__('Show Meta?', 'wdt-ultimate-booking'),
				  'options'   => array(
					'yes'   => esc_html__('Yes', 'wdt-ultimate-booking'),
					'no'    => esc_html__('No', 'wdt-ultimate-booking')
				  ),
				  'class'     => 'chosen',
				  'default'   => 'no',
				  'info'      => esc_html__('Choose "Yes" to show meta.', 'wdt-ultimate-booking')
				),
				array(
				  'id'    => 'button_text',
				  'type'  => 'text',
				  'title' => esc_html__( 'Button Text', 'wdt-ultimate-booking' ),
				  'default' => esc_html__('Read More', 'wdt-ultimate-booking'),
				  'info'  => esc_html__( 'Enter button text.', 'wdt-ultimate-booking' )
				)
			  ),
			);

			return $options;
		}
	}				
}