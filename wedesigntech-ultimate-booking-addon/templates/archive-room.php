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

	// Get the current page number
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	// Set up the custom query
	$args = array(
			'post_type'      => 'dt_room',
			'posts_per_page' => $room_per_page,
			'paged'          => $paged,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
	);

	$custom_query = new WP_Query($args);

	if ($custom_query->have_posts()) :
		$loop = 1; ?>
		<div class="dt-sc-room-container<?php echo esc_attr( $post_class );?>"><?php
			while ($custom_query->have_posts()) :
				$custom_query->the_post();
				$the_id = get_the_ID();

				$temp_class = 'dt-sc-room-item type1';
				if ($loop == 1) $temp_class .= ' first';

				if ($loop == $columns) {
					$loop = 1;
				} else {
					$loop++;
				}

				// Meta...
				$room_settings = get_post_meta($the_id, '_custom_settings', true);
				$room_settings = is_array($room_settings) ? $room_settings : array();

				echo '<div id="dt_room-' . esc_attr($the_id) . '" class="' . esc_attr(trim($temp_class)) . '">';

					echo '<div class="dt-sc-content-item dt-e-room-item">';
						
							echo '<div class="dt-sc-content-media-group">';
								echo '<div class="dt-sc-content-elements-group">';
								
									echo '<div class="dt-sc-room-list-image">';
										echo '<a href="' . get_permalink() . '">';
											if (has_post_thumbnail()) :
												$attr = array('title' => get_the_title(), 'alt' => get_the_title());
												echo get_the_post_thumbnail($the_id, 'full', $attr);
											else :
												$img_pros = '615x560';
												echo '<img src="https://place-hold.it/' . $img_pros . '&text=' . get_the_title() . '" alt="' . get_the_title() . '"/>';
											endif;
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

								echo '<div class="dt-sc-content-button">';
									echo '<a class="dt-sc-button dt-sc-button-textual" href="'.get_permalink().'" title="'.get_the_title().'">'.esc_html__("Read More", "wdt-ultimate-booking").'</a>';
								echo '</div>';

							echo '</div>';

						echo '</div>';
				echo '</div>';

			endwhile; ?>
		</div><?php
	endif; ?>

	<div class="pagination booking-pagination"><?php
		$big = 999999999; 
		$paginate_args = array(
			'base'         => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format'       => '?paged=%#%',
			'current'      => max(1, get_query_var('paged')),
			'total'        => $custom_query->max_num_pages,
			'prev_text'    => '<i class="fa fa-angle-left"></i>' . esc_html__(' Newer Posts', 'wdt-ultimate-booking'),
			'next_text'    => esc_html__('Older Posts ', 'wdt-ultimate-booking') . '<i class="fa fa-angle-right"></i>',
			'mid_size'     => 2,
			'type'         => 'array',
		);

		$paginate_links = paginate_links($paginate_args);

		if ($paginate_links) {
			echo '<div class="pagination-links">';
			foreach ($paginate_links as $link) {
				echo '<span>' . wp_kses_post($link) . '</span>';
			}
			echo '</div>';
		}

		wp_reset_postdata();

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

<?php get_footer(); ?>
