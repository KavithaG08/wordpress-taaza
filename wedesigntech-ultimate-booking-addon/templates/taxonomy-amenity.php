<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<?php
	/**
	* ultimate_booking_pro_before_main_content hook.
	*/
	do_action( 'ultimate_booking_pro_before_main_content' );
?>

	<?php
		/**
		* ultimate_booking_pro_before_content hook.
		*/
		do_action( 'ultimate_booking_pro_before_content' );
    ?>

	<!-- Content -->

	<?php

	$post_layout    = cs_get_option( 'room-archives-post-layout' );
	$post_excerpt   = cs_get_option( 'room-archives-excerpt' );
	$excerpt_length = cs_get_option( 'room-archives-excerpt-length' );

	switch($post_layout):
		case 'one-fourth-column':
			$post_class = " dt-column-4";
			$columns    = 4;
			break;

		case 'one-third-column':
			$post_class = " dt-column-3";
			$columns    = 3;
			break;

		default:
		case 'one-half-column':
			$post_class = " dt-column-2";
			$columns    = 2;
			break;
	endswitch;

	$room_per_page = cs_get_option('room-per-page');



	$queried_object = get_queried_object(); 
	$args = [
		'post_type'      => 'dt_room', 
		'posts_per_page' => $room_per_page,        
		'paged'          => get_query_var('paged', 1), 
	];

	if (is_tax('dt_room_amenity') && $queried_object) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'dt_room_amenity',
				'field'    => 'slug',
				'terms'    => $queried_object->slug,
			],
		];
	}

	$query = new WP_Query($args);

	echo '<div class="dt-sc-room-amenity-wrapper">';
	$loop = 1;
		echo '<div class="dt-sc-room-amenity-content-wrapper dt-sc-room-container'.esc_attr( $post_class ).'">';
			$temp_class = ' type1';
			if ($loop == 1) $temp_class .= ' first';

			if ($loop == $columns) {
				$loop = 1;
			} else {
				$loop++;
			}
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$PID = get_the_ID();

					$amenities = wp_get_post_terms($PID, 'dt_room_amenity');
					$room_settings = get_post_meta($PID, '_custom_settings', true);
					$room_settings = is_array($room_settings) ? $room_settings : [];

					$room_permalink = get_permalink($PID);

					echo '<div id="dt_room-' . esc_attr($PID) . '" class="dt-sc-room-item ' . esc_attr(trim($temp_class)) . '">';
						echo '<div class="dt-sc-content-item dt-e-room-item">';

							echo '<div class="dt-sc-content-media-group">';
								echo '<div class="dt-sc-content-elements-group">';
								
									echo '<div class="dt-sc-room-list-image">';
										echo '<a href="' . get_permalink() . '">';
											if (has_post_thumbnail()) {
												$attr = ['title' => get_the_title(), 'alt' => get_the_title()];
												echo get_the_post_thumbnail($PID, 'full', $attr);
											} else {
												echo '<img src="https://place-hold.it/1200x800&text=' . get_the_title() . '" alt="' . get_the_title() . '" />';
											}
										echo '</a>';
									echo '</div>';

									if( array_key_exists('room-price', $room_settings) && $room_settings['room-price'] != '' ):
										echo '<div class="dt-sc-room-price-item">';
											echo '<span class="dt-sc-room-price">'.ultimate_booking_pro_get_currency_symbol().$room_settings['room-price'].esc_html__('/Night ', 'wdt-ultimate-booking').'</span>';
										echo '</div>';
									endif;
									
								echo '</div>';
							echo '</div>';

							echo '<div class="dt-sc-content-detail-group room-details">';

								echo '<div class="dt-sc-content-title">';
									echo '<h4 class="dt-sc-room-title">';
										echo '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
									echo '</h4>';
								echo '</div>';

								if( $post_excerpt == 'yes' && $excerpt_length > 0 ):
									echo '<div class="dt-sc-content-description">'.ultimate_booking_pro_post_excerpt($excerpt_length).'</div>';
								endif;

								if (!empty($amenities)) {
									echo '<div class="dt-sc-rooms-meta-wrapper">';
										if( array_key_exists('room-size', $room_settings) && $room_settings['room-size'] != '' ):
											echo '<div class="dt-sc-rooms-size-meta-wrapper">';
												echo '<span class="dt-sc-rooms-icon">';
													echo '<i class="icon-dt-sq-fit"></i>';
												echo '</span>';
												echo '<span class="dt-sc-rooms-size">'.esc_attr( $room_settings['room-size'] ).'</span>';
											echo '</div>';
										endif;
										if( array_key_exists('room-max-people', $room_settings) && $room_settings['room-max-people'] != '' ):
											echo '<div class="dt-sc-rooms-people-meta-wrapper">';
												echo '<span class="dt-sc-rooms-icon">';
													echo '<i class="icon-dt-guests"></i>';
												echo '</span>';
												echo '<span class="dt-sc-rooms-people">'.esc_attr( $room_settings['room-max-people'] ). esc_html__(' Guests', 'wdt-ultimate-booking') .'</span>';
											echo '</div>';
										endif;
										if( array_key_exists('room-duration', $room_settings) && $room_settings['room-duration'] != '' ):
											echo '<div class="dt-sc-rooms-duration-meta-wrapper">';
												echo '<span class="dt-sc-rooms-icon">';
													echo '<i class="icon-dt-clock-alt"></i>';
												echo '</span>';
												echo '<span class="dt-sc-rooms-duration">'.ultimate_booking_pro_duration_to_string( $room_settings['room-duration'] ).'</span>';
											echo '</div>';
										endif;
									echo '</div>';
								}

								echo '<div class="dt-sc-content-button">';
									echo '<a class="dt-sc-button dt-sc-button-textual" href="'.get_permalink().'" title="'.get_the_title().'">'.esc_html__("Read More", "wdt-ultimate-booking").'</a>';
								echo '</div>';

							echo '</div>';

						echo '</div>';
					echo '</div>';
				}
				wp_reset_postdata();
			} else {
				echo '<div class="dt-sc-item-not-found">';
					echo '<h2>' . esc_html__("Nothing Found.", "wdt-ultimate-booking") . '</h2>';
					echo '<p>' . esc_html__("Apologies, but no results were found for the requested archive.", "wdt-ultimate-booking") . '</p>';
				echo '</div>';
			}

		echo '</div>';
	echo '</div>';

	?>



    <?php
        /**
        * ultimate_booking_pro_after_content hook.
        */
        do_action( 'ultimate_booking_pro_after_content' );
    ?>

<?php
	/**
	* ultimate_booking_pro_after_main_content hook.
	*/
	do_action( 'ultimate_booking_pro_after_main_content' );
?>

<?php get_footer();