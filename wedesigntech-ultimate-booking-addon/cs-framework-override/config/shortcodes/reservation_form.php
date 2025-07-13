<?php
if (! class_exists ( 'DTBooking_Cs_Sc_ReservationForm' ) ) {

    class DTBooking_Cs_Sc_ReservationForm {

        function DTBooking_sc_ReservationForm() {

			$options = array(
			  'name'      => 'dt_sc_reservation_form',
			  'title'     => esc_html__('Reservaton Form', 'wdt-ultimate-booking'),
			  'fields'    => array(

				array(
				  'id'    => 'title',
				  'type'  => 'text',
				  'title' => esc_html__( 'Title', 'wdt-ultimate-booking' )
				),
				array(
				  'id'          => 'roomids',
				  'type'        => 'select',
				  'title'       => esc_html__('Room IDs', 'wdt-ultimate-booking'),
				  'options'     => 'posts',
				  'query_args'  => array(
					'post_type'	=> 'dt_room'
				  ),
				  'attributes' => array(
					'multiple' 		   => 'only-key',
					'data-placeholder' => esc_html__('Select Some Rooms', 'wdt-ultimate-booking'),
					'style'            => 'width: 200px;'
				  ),
				  'class' 		=> 'chosen',
				  'desc'       => '<div class="cs-text-muted">'.esc_html__('Enter room name & pick.', 'wdt-ultimate-booking').'</div>',
				),
				array(
				  'id'          => 'staffids',
				  'type'        => 'select',
				  'title'       => esc_html__('Staff IDs', 'wdt-ultimate-booking'),
				  'options'     => 'posts',
				  'query_args'  => array(
					'post_type'	=> 'dt_staff'
				  ),
				  'attributes' => array(
					'multiple' 		   => 'only-key',
					'data-placeholder' => esc_html__('Select Some Staffs', 'wdt-ultimate-booking'),
					'style'            => 'width: 200px;'
				  ),
				  'class' 		=> 'chosen',
				  'desc'       => '<div class="cs-text-muted">'.esc_html__('Enter staff name & pick.', 'wdt-ultimate-booking').'</div>',
				),
			  ),
			);

			return $options;
		}
	}				
}