<?php
get_header();

$params = custom_get_room_search_parameters();

$check_in  = $params['query_options']['check_in'] ?? '';
$check_out = $params['query_options']['check_out'] ?? '';
$rooms     = $params['query_options']['rooms'] ?? '';
$adult     = $params['query_options']['adult'] ?? 1;
$children  = $params['query_options']['children'] ?? 0;
$infant    = $params['query_options']['infant'] ?? 0;
$total_people = $adult + $children + $infant;

$post_excerpt   = cs_get_option( 'room-archives-excerpt' );
$excerpt_length = cs_get_option( 'room-archives-excerpt-length' );

$args = array(
    'post_type'      => 'dt_room',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'room-max-people', 
            'value'   => $total_people,    
            'type'    => 'NUMERIC',
            'compare' => '>=',           
        ),
		array(
            'key'     => 'room-status', 
            'value'   => '1',    
            'compare' => '=',    
        ),
    ),
);


$query = new WP_Query( $args );

echo '<div class="dt-sc-room-search-wrapper">';
    echo '<div class="dt-sc-room-search-content-wrapper">';
		if ( $query->have_posts() ) {
			while ($query->have_posts()) {
				$query->the_post();
				$PID = get_the_ID();
		
				// Get Amenities
				$amenities = wp_get_post_terms($PID, 'dt_room_amenity');

				$room_settings = get_post_meta($PID, '_custom_settings', true);
				$room_settings = is_array($room_settings) ? $room_settings : [];

				$current_query_params = $_GET;
                $query_string = http_build_query($current_query_params);
                $room_permalink = add_query_arg($current_query_params, get_permalink($PID));
		
				$out = '';

				echo '<div class="dt-sc-content-item dt-e-room-item">';
		
					echo '<div class="dt-sc-content-media-group">';
						echo '<div class="dt-sc-content-elements-group">';
				
							echo '<div class="dt-sc-room-list-image">';
							if (has_post_thumbnail()) {
								$attr = ['title' => get_the_title(), 'alt' => get_the_title()];
								echo get_the_post_thumbnail($PID, 'full', $attr);
							} else {
								echo '<img src="https://place-hold.it/1200x800&text=' . get_the_title() . '" alt="' . get_the_title() . '" />';
							}
							echo '</div>';
				
							if (array_key_exists('room-price', $room_settings) && $room_settings['room-price'] != '') {
								echo '<div class="dt-sc-room-price-item">';
									echo '<span class="dt-sc-room-price">' . ultimate_booking_pro_get_currency_symbol() . $room_settings['room-price'] . esc_html__(' / Night ', 'wdt-ultimate-booking') . '</span>';
								echo '</div>';
							}
				
						echo '</div>';
					echo '</div>';
			
					echo '<div class="dt-sc-content-detail-group room-details">';

						echo '<div class="dt-sc-room-content-group">';
				
							echo '<div class="dt-sc-content-title">';
								echo '<h4 class="dt-sc-room-title">';
									echo '<a href="' . esc_url($room_permalink) . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
								echo '</h4>';
							echo '</div>';
					
							if( $post_excerpt == 'yes' && $excerpt_length > 0 ):
								echo '<div class="dt-sc-content-description">'.ultimate_booking_pro_post_excerpt($excerpt_length).'</div>';
							endif;
					
							echo '<div class="dt-sc-rooms-meta-wrapper">';
								if (array_key_exists('room-size', $room_settings) && $room_settings['room-size'] != '') {
									echo '<div class="dt-sc-rooms-size-meta-wrapper">';
											echo '<span class="dt-sc-rooms-icon">';
												echo '<i class="icon-dt-sq-fit"></i>';
											echo '</span>';
										echo '<span class="dt-sc-rooms-size">' . esc_attr($room_settings['room-size']) . '</span>';
									echo '</div>';
								}
								if (array_key_exists('room-max-people', $room_settings) && $room_settings['room-max-people'] != '') {
									echo '<div class="dt-sc-rooms-people-meta-wrapper">';
											echo '<span class="dt-sc-rooms-icon">';
												echo '<i class="icon-dt-guests"></i>';
											echo '</span>';
										echo '<span class="dt-sc-rooms-people">' . esc_attr($room_settings['room-max-people']) . esc_html__(' Guests', 'wdt-ultimate-booking') .'</span>';
									echo '</div>';
								}
								if (array_key_exists('room-duration', $room_settings) && $room_settings['room-duration'] != '') {
									echo '<div class="dt-sc-rooms-duration-meta-wrapper">';
											echo '<span class="dt-sc-rooms-icon">';
												echo '<i class="icon-dt-clock-alt"></i>';
											echo '</span>';
										echo '<span class="dt-sc-rooms-duration">' . ultimate_booking_pro_duration_to_string($room_settings['room-duration']) . '</span>';
									echo '</div>';
								}
							echo '</div>';
					
							echo '<div class="dt-sc-content-button">';
								echo '<a class="dt-sc-button dt-sc-button-textual" href="' . get_permalink() . '" title="' . get_the_title() . '">' . esc_html__('Read More', 'wdt-ultimate-booking') . '</a>';
							echo '</div>';

						echo '</div>';

						echo '<div class="dt-sc-amenities-group">';

							if (!empty($amenities) ) {
								echo '<div class="dt-sc-room-amenities">';
									echo '<ul class="dt-sc-room-amenity-list">';
										foreach ($amenities as $amenity) {
											$amenity_icon = get_term_meta($amenity->term_id, 'dt-taxonomy-icon', true);
											echo '<li class="dt-sc-room-amenity-item">';
											if (!empty($amenity_icon)) {
												echo '<i class="' . esc_attr($amenity_icon) . '"></i>';
											}
											echo '<span class="dt-sc-room-amenity-text">' . esc_html($amenity->name) . '</span>';
											echo '</li>';
										}
									echo '</ul>';
								echo '</div>';
							}

						echo '</div>';
			
					echo '</div>';
		
				echo '</div>';

			}
			wp_reset_postdata();
        } else {
            echo '<div class="dt-sc-item-not-found">';
				echo '<h2>'.esc_html__("Nothing Found.", "wdt-ultimate-booking").'</h2>';
				echo '<p>'.esc_html__("Apologies, but no results were found for the requested archive.", "wdt-ultimate-booking").'</p>';
			echo '</div>';
        }
    echo '</div>';
    echo '<div class="dt-sc-room-search-form-wrapper">';
            echo '<form class="dt-sc-reservation-form dt-appointment-form" name="reservation-schedule-form" method="get" action="'.esc_url( home_url( '/' )).'">';
				echo '<input type="hidden" name="s" value=""/>';
				echo '<div class="dt-sc-room--field">
							<div class="frm-group">
								<div class="dt-field-label">
            						<label for="name">'.esc_html__('Check-in','wdt-ultimate-booking').'</label>
          						</div>
								<div class="dt-sc-calendar-group">
									<input type="text" id="roomcheckin" name="check_in" class="frm-control" name="check_in" value="' . esc_attr($check_in) . '" required>
									<span class="dt-icon-dt-calendar"><i class="icon-dt-calendar"></i></span>
								</div>
							</div>
						</div>';

				echo '<div class="dt-sc-room--field">
							<div class="frm-group">
								<div class="dt-field-label">
									<label for="name">'.esc_html__('Check-out','wdt-ultimate-booking').'</label>
								</div>
								<div class="dt-sc-calendar-group">
									<input type="text" id="roomcheckout" name="check_out" class="frm-control" name="check_out" value="' . esc_attr($check_out) . '" required>
									<span class="dt-icon-dt-calendar"><i class="icon-dt-calendar"></i></span>
								</div>
							</div>
						</div>';
				
				echo '<div class="dt-sc-room--field">
							<div class="frm-group">
								<div class="dt-field-label">
									<label for="name">'.esc_html__('Rooms','wdt-ultimate-booking').'</label>
								</div>
								<select name="rooms" id="rooms" class="dt-select-room frm-control">
								<option value=""></option>';
                
									// Loop through room options
									for ($i = 1; $i <= 20; $i++) {
										echo '<option value="' . esc_attr($i) . '" ' . selected($rooms, $i, false) . '>' . 
												sprintf(_n('%s Room', '%s Rooms', $i, 'wdt-ultimate-booking'), $i) . 
												'</option>';
									}

								echo '</select>
							</div>
						</div>';

				echo '<div class="dt-sc-room--field">
							<div class="frm-group">
								<div class="dt-field-label">
									<label for="persons">'.esc_html__('Guests', 'wdt-ultimate-booking').'</label>
									<div class="dt-sc-guests-group">
										<input type="text" class="frm-control dt--guests" name="guests" value="" readonly />
										<span class="dt-drop--down"></span>
									</div>
								</div>
								<div class="dt-sc-field-persons">
									<div class="dt-sc-field-person dt-sc--adult">
										<div class="dt-sc-e-label-text">'.esc_html__('Adults', 'wdt-ultimate-booking').'</div>
										<select name="adult" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Adult', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Adults', 'wdt-ultimate-booking').'">';
											for ($i = 1; $i <= 20; $i++) {
												echo '<option value="'.esc_attr($i).'" '.selected($adult, $i, false).'>'.esc_html($i).'</option>';
											}
										echo '</select>
											</div>
											<div class="dt-sc-field-person dt-sc--children">
												<div class="dt-sc-e-label">
													<span class="dt-sc-e-label-text">'.esc_html__('Children', 'wdt-ultimate-booking').'</span>
													<span class="dt-sc-e-label-description">'.esc_html__('2-12 years old', 'wdt-ultimate-booking').'</span>
												</div>
												<select name="children" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Child', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Children', 'wdt-ultimate-booking').'">';
													for ($i = 0; $i <= 20; $i++) {
														echo '<option value="'.esc_attr($i).'" '.selected($children, $i, false).'>'.esc_html($i).'</option>';
													}
										echo '</select>
											</div>
											<div class="dt-sc-field-person dt-sc--infant">
												<div class="dt-sc-e-label">
													<span class="dt-sc-e-label-text">'.esc_html__('Infants', 'wdt-ultimate-booking').'</span>
													<span class="dt-sc-e-label-description">'.esc_html__('0-2 years old', 'wdt-ultimate-booking').'</span>
												</div>
												<select name="infant" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Infant', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Infants', 'wdt-ultimate-booking').'">';
													for ($i = 0; $i <= 20; $i++) {
														echo '<option value="'.esc_attr($i).'" '.selected($infant, $i, false).'>'.esc_html($i).'</option>';
													}
										echo '</select>
											</div>
											<div class="dt-sc-field-person dt-sc--button">
												<button type="button" class="dt-sc-button dt-sc-full-width">
													'.esc_html__('Done', 'wdt-ultimate-booking').'
												</button>
											</div>
										</div>
									</div>
								</div>';
			
				echo '<div class="dt-sc-room--field">
							<div class="dt-sc-full-width">
								<input name="subschedule" class="dt-sc-button dt-sc-full-width" value="'.esc_attr__('Check Availability', 'wdt-ultimate-booking').'" type="submit">
							</div>
						</div>';

			echo '</form>';
    echo '</div>';

echo '</div>';

wp_reset_postdata();

get_footer();
