<?php
add_action( 'wp_ajax_ultimate_booking_pro_fill_staffs', 'ultimate_booking_pro_fill_staffs' ); # For logged-in users
add_action( 'wp_ajax_nopriv_ultimate_booking_pro_fill_staffs','ultimate_booking_pro_fill_staffs'); # For logged-out users
function ultimate_booking_pro_fill_staffs() {
	if( isset($_REQUEST['room_id']) ){

		$room_id = ultimate_booking_pro_sanitization($_REQUEST['room_id']);

		if( ultimate_booking_pro_check_plugin_active('sitepress-multilingual-cms/sitepress.php') ) {
			global $sitepress;

			$default_lang = $sitepress->get_default_language();
			$current_lang = ICL_LANGUAGE_CODE;

			if( $default_lang != $current_lang ) {
				$room_id =  icl_object_id(  $room_id ,'dt_room', true ,$sitepress->get_default_language());
			}
		}

		$mata_query = array( array(
				'key'     => '_ultimate_booking_pro_staff_rooms',
				'value'   => $room_id,
				'compare' => 'LIKE' ) );

		$wp_query = new WP_Query();
		$staffs = array(
			'post_type' => 'dt_staff',
			'posts_per_page' => '-1',
			'meta_query' => $mata_query );

		$wp_query->query( $staffs );
		// echo "<option class='test' value=''> Select Staff </option>";
		if( !$wp_query->have_posts() ) {
			$placeholder_image = 'https://via.placeholder.com/60X60.jpg';
			echo "<option value='' style='background-image:url(".$placeholder_image.");'>Select Staff</option>";
		} else {
			echo "<option value=''>Select Staff</option>";
		}
		if( $wp_query->have_posts() ):
			while( $wp_query->have_posts() ):
				$wp_query->the_post();
				$id = get_the_ID();

				$pmeta = get_post_meta($id, '_custom_settings', true);
				$pmeta = is_array ( $pmeta ) ? $pmeta : array ();

				$pcost = '';

				$title = get_the_title($id);

				if( has_post_thumbnail( $id ) ) {
					$post_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'dt-bm-dropdown-staff', false );
					$image = $post_thumb[0];
				} else {
					$image = $popup = 'https://via.placeholder.com/60X60.jpg&text='.get_the_title( $id );
				}

				echo '<option value="'.$id.'" style="background-image:url(\''.$image.'\');">'.$title.$pcost.'</option>';
			endwhile;
		endif;
	}
	die( '' );
}

//Room meta 
add_action( 'save_post_dt_room', 'save_room_max_people_meta', 10, 3 );
function save_room_max_people_meta( $post_id, $post, $update ) {
    if ( $post->post_type !== 'dt_room' ) {
        return;
    }

    $room_settings = get_post_meta( $post_id, '_custom_settings', true );
    if ( is_array( $room_settings ) && isset( $room_settings['room-max-people'] ) ) {
        update_post_meta( $post_id, 'room-max-people', (int) $room_settings['room-max-people'] );
    }
	if ( is_array( $room_settings ) && isset( $room_settings['room-min-people'] ) ) {
        update_post_meta( $post_id, 'room-min-people', (int) $room_settings['room-min-people'] );
    }

	if ( is_array( $room_settings ) && isset( $room_settings['room-price'] ) ) {
        update_post_meta( $post_id, 'room-price', (int) $room_settings['room-price'] );
    }
	if ( is_array( $room_settings ) && isset( $room_settings['room-status'] ) ) {
        update_post_meta( $post_id, 'room-status', (int) $room_settings['room-status'] );
    }

    if ( is_array( $room_settings ) && isset( $room_settings['room-adult-price'] ) ) {
        update_post_meta( $post_id, 'room-adult-price', (int) $room_settings['room-adult-price'] );
    }

    if ( is_array( $room_settings ) && isset( $room_settings['room-children-price'] ) ) {
        update_post_meta( $post_id, 'room-children-price', (int) $room_settings['room-children-price'] );
    }

	for ( $i = 1; $i <= 4; $i++ ) {
        $service_name = isset( $_POST['room_service_name_' . $i] ) ? sanitize_text_field( $_POST['room_service_name_' . $i] ) : '';
        $service_type = isset( $_POST['room_service_type_' . $i] ) ? sanitize_text_field( $_POST['room_service_type_' . $i] ) : '';
        $service_price = isset( $_POST['room_service_price_' . $i] ) ? (float) $_POST['room_service_price_' . $i] : 0;
        $service_pack = isset( $_POST['room_service_pack_' . $i] ) ? sanitize_text_field( $_POST['room_service_pack_' . $i] ) : '';

        // Save the service details if all fields are filled
        update_post_meta( $post_id, 'room_service_name_' . $i, $service_name );
        update_post_meta( $post_id, 'room_service_type_' . $i, $service_type );
        update_post_meta( $post_id, 'room_service_price_' . $i, $service_price );
        update_post_meta( $post_id, 'room_service_pack_' . $i, $service_pack );
		update_post_meta( $post_id, '_associated_product_id', $post_id );
		update_post_meta( $post_id, '_product_type', 'dt_room' );

    }
}
