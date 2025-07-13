<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

		<?php
            if( have_posts() ) {
                while( have_posts() ) {
                    the_post();

					// Get the custom settings
					$post_id = get_the_ID();
					$room_settings = get_post_meta ( $post_id, '_custom_settings', TRUE );


					echo '<div class="dt-sc-room-single-thumb">';
						the_post_thumbnail( 'post-thumbnail', array( 'loading' => false ) );
						
						echo '<div class="dt-sc-room-single-meta">';
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

							if( array_key_exists('room-price', $room_settings) && $room_settings['room-price'] != '' ):
								echo '<div class="dt-sc-room-price-item">';
									echo '<span class="dt-sc-room-price">'.ultimate_booking_pro_get_currency_symbol().$room_settings['room-price'].esc_html__(' / Night ', 'wdt-ultimate-booking').'</span>';
								echo '</div>';
							endif;
						echo '</div>';

					echo '</div>';

                    the_content();
                }
            }?>

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