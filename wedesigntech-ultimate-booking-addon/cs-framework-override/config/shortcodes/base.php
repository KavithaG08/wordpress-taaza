<?php
if (! class_exists ( 'DTBooking_Cs_Sc_Base' ) ) {

    class DTBooking_Cs_Sc_Base {

        function DTBooking_cs_sc_Combined() {

			require_once 'reservation_form.php';
			$obj = new DTBooking_Cs_Sc_ReservationForm;
			$reservation_form = $obj->DTBooking_sc_ReservationForm();
			
			require_once 'reserve_appointment.php';
			$obj = new DTBooking_Cs_Sc_ReserveAppointment;
			$reserve_appointment = $obj->DTBooking_sc_ReserveAppointment();

			require_once 'room_list.php';
			$obj = new DTBooking_Cs_Sc_RoomList;
			$room_list = $obj->DTBooking_sc_RoomList();
			
			require_once 'room_item.php';
			$obj = new DTBooking_Cs_Sc_RoomItem;
			$room_item = $obj->DTBooking_sc_RoomItem();

			require_once 'staff_item.php';
			$obj = new DTBooking_Cs_Sc_StaffItem;
			$staff_item = $obj->DTBooking_sc_StaffItem();

			$options[] 	   = array(
			  'title'      => esc_html__('Reservation', 'wdt-ultimate-booking'),
			  'shortcodes' => array(

				// begin: shortcode
				$reservation_form,
				$reserve_appointment,
				$room_list,
				$room_item,
				$staff_item

			  ),
			);

			return $options;
		}
	}
}