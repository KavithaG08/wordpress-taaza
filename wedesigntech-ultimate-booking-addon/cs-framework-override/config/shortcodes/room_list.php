<?php
if (! class_exists ( 'DTBooking_Cs_Sc_RoomList' ) ) {

    class DTBooking_Cs_Sc_RoomList {

        function DTBooking_sc_RoomList() {

			$plural_name = '';
			if( function_exists( 'ultimate_booking_pro_cs_get_option' ) ) :
				$plural_name	=	ultimate_booking_pro_cs_get_option( 'singular-room-text', esc_html__('Room', 'wdt-ultimate-booking') );
			endif;

			$options = array(
			  'name'      => 'dt_sc_room_list',
			  'title'     => $plural_name.esc_html__(' List', 'wdt-ultimate-booking'),
			  'fields'    => array(

				array(
				  'id'          => 'terms',
				  'type'        => 'select',
				  'title'       => esc_html__('Terms', 'wdt-ultimate-booking'),
				  'options'     => 'categories',
				  'query_args'  => array(
					'type'      => 'dt_room',
					'taxonomy'  => 'dt_room_category'
				  ),
				  'attributes' => array(
					'multiple' 		   => 'only-key',
					'data-placeholder' => esc_html__('Select room category', 'wdt-ultimate-booking'),
					'style'            => 'width: 200px;'
				  ),
				  'class' 	   => 'chosen',
				  'desc'       => '<div class="cs-text-muted">'.esc_html__('Choose room as you want.', 'wdt-ultimate-booking').'</div>',
				),
				array(
				  'id'    => 'posts_per_page',
				  'type'  => 'text',
				  'title' => esc_html__( 'Products Per Page', 'wdt-ultimate-booking' ),
				  'default' => 3
				),
				array(
				  'id'        => 'orderby',
				  'type'      => 'select',
				  'title'     => esc_html__('Order by', 'wdt-ultimate-booking'),
				  'options'   => array(
					'ID'       => esc_html__('ID', 'wdt-ultimate-booking'),
					'title'    => esc_html__('Title', 'wdt-ultimate-booking'),
					'name'     => esc_html__('Name', 'wdt-ultimate-booking'),
					'type' 	   => esc_html__('Type', 'wdt-ultimate-booking'),
					'date'     => esc_html__('Date', 'wdt-ultimate-booking'),
					'rand'     => esc_html__('Random', 'wdt-ultimate-booking')
				  ),
				  'class'     => 'chosen',
				  'default'   => 'ID',
				  'info'      => esc_html__('Choose orderby of rooms to display.', 'wdt-ultimate-booking')
				),
				array(
				  'id'        => 'order',
				  'type'      => 'select',
				  'title'     => esc_html__('Sort order', 'wdt-ultimate-booking'),
				  'options'   => array(
					'desc'    => esc_html__('Descending', 'wdt-ultimate-booking'),
					'asc'     => esc_html__('Ascending', 'wdt-ultimate-booking')
				  ),
				  'class'     => 'chosen',
				  'default'   => 'desc',
				  'info'      => esc_html__('Choose order of rooms to display.', 'wdt-ultimate-booking')
				),
				array(
				  'id'    => 'el_class',
				  'type'  => 'text',
				  'title' => esc_html__( 'Extra class name', 'wdt-ultimate-booking' ),
				  'after' => '<div class="cs-text-muted">'.esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wdt-ultimate-booking').'</div>',
				),
			  ),
			);

			return $options;
		}
	}				
}