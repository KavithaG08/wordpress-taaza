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

		<?php
			$teamstyle    = cs_get_option( 'staff-archives-post-style' );
			$post_layout  = cs_get_option( 'staff-archives-post-layout' );
			$post_excerpt = cs_get_option( 'staff-archives-excerpt' );

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

			if( have_posts() ) :
				$i = 1;?>
				<div class="dt-sc-staff-container<?php echo esc_attr( $post_class );?>"><?php
					while( have_posts() ):
						the_post();
						$the_id = get_the_ID();

						$temp_class = $teamstyle;
						if($i == 1) $temp_class .= ' first';
						if($i == $columns) $i = 1; else $i = $i + 1;

						# Meta
						$staff_meta = get_post_meta($the_id,'_custom_settings',TRUE);
						$staff_meta = is_array( $staff_meta ) ? $staff_meta  : array(); ?>

						<div id="dt_staff-<?php echo esc_attr($the_id);?>" class="dt-sc-staff-item <?php echo esc_attr( trim($temp_class));?>"><?php
							# Feature image
							if( has_post_thumbnail() ){
								$post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id( $the_id ), 'dt-bm-archive-staff', false);
								$image = $post_thumb[0];
							} else {

								$img_pros = '700x800';

								if( $teamstyle == 'type2' ) {
									$img_pros = '700x700';
								}
								$image = $popup = 'https://via.placeholder.com/'.$img_pros.'.jpg&text='.get_the_title($the_id);
							}

							echo '<div class="dt-sc-staff-item '.$teamstyle.'">';
							
								echo '<div class="image">';
									echo '<a href="'.get_permalink().'" title="'.get_the_title().'">';
										echo '<div class="dt-image-item">';
											if( has_post_thumbnail() ){
												$post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id( $the_id ), 'dt-bm-archive-staff', false);
												$image = $post_thumb[0];
											} else {
												$img_pros = '700x800';

												if( $teamstyle == 'type2' ) {
													$img_pros = '700x700';
												}
												$image = $popup = 'https://via.placeholder.com/'.$img_pros.'.jpg&text='.get_the_title($the_id);
											}
											echo '<img src="'.$image.'" alt="'.get_the_title().'" />';
										echo '</div>';
									echo '</a>';

									if( $teamstyle == 'type1' || $teamstyle == 'type2' ) {
										echo '<div class="dt-sc-staff-overlay">';
											echo '<div class="dt-sc-staff-social-container">';
												if( array_key_exists('staff-social', $staff_meta) ):
													$socialicondr = do_shortcode($staff_meta['staff-social']);
													echo '<div class="social-media">'.$socialicondr.'</div>';
												endif;
											echo '</div>';
										echo '</div>';
									}
									
								echo '</div>';
								
								echo '<div class="dt-sc-staff-details">';

									echo '<div class="dt-sc-content-title">';
										echo '<h4 class="dt-sc-staff-title">';
											echo '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
										echo '</h4>';
									echo '</div>';

									if( array_key_exists('staff-role', $staff_meta) ):
										echo '<div class="dt-sc-content-sub-title">';
											echo '<span class="dt-sc-staff-role">'.$staff_meta['staff-role'].'</span>';
										echo '</div>';
									endif;

									// if( $teamstyle == 'type2' ) {
									// 	if( array_key_exists('appointment_fs1', $staff_meta) && array_key_exists('appointment_fs5', $staff_meta) ):
									// 		echo '<div class="dt-sc-content-working-time">';
									// 			echo '<p>'.esc_html__('Monday to Friday : ', 'wdt-ultimate-booking').$staff_meta['appointment_fs1']['ultimate_booking_pro_monday_start'].' - '.$staff_meta['appointment_fs5']['ultimate_booking_pro_friday_end'].esc_html__(' hrs', 'wdt-ultimate-booking');
									// 		echo '</div>';
									// 	endif;
									// }

									if( $post_excerpt ) {
										echo ultimate_booking_pro_post_excerpt(12);
									}
								echo '</div>';

						echo '</div>';
						?>

						</div><?php
					endwhile;?>
				</div><?php
			endif;?>

			<!-- **Pagination** -->
			<div class="pagination booking-pagination"><?php
				echo '<div class="older-posts">'.get_next_posts_link( esc_html__('Older Posts ', 'wdt-ultimate-booking').'<i class="fa fa-angle-right"></i>' ).'</div>';
                echo '<div class="newer-posts">'.get_previous_posts_link( '<i class="fa fa-angle-left"></i>'.esc_html__(' Newer Posts', 'wdt-ultimate-booking') ).'</div>';
            ?></div><!-- **Pagination** -->

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