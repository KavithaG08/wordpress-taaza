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
					$PID = get_the_ID();

					$staff_settings = get_post_meta($PID, '_custom_settings', true);
					$staff_settings = is_array ( $staff_settings ) ? $staff_settings : array ();

					echo '<div class="staff-header dt-sc-staff-header-section">';

						echo '<div class="dt-sc-staff-image-wrapper">';
							the_post_thumbnail( 'post-thumbnail', array( 'loading' => false ) );
						echo '</div>';

						echo '<div class="dt-sc-staff-info-wrapper">';

							// Role

							if( array_key_exists('staff-role', $staff_settings) ):
								echo  '<div class="dt-sc-staff-role-wrapper">';
									echo  '<span class="dt-sc-staff-role">';
										echo $staff_settings['staff-role'];
									echo '</span>';
								echo  '</div>';
							endif;

							// Title

							echo  '<div class="dt-sc-staff-title-wrapper">';
								echo  '<h2 class="dt-sc-staff-title">';
									the_title();
								echo  '</h2>';
							echo  '</div>';

							// Excerpt Content

							echo  '<div class="dt-sc-staff-excerpt-wrapper">';
								echo '<h6 class="dt-sc-staff-label">'.esc_html__('Description', 'wdt-ultimate-booking').':</h6>';
								echo '<span class="dt-sc-staff-item">';
									the_excerpt();
								echo '</span>';
							echo  '</div>';

							// Specialization

							if( array_key_exists('staff-specialization', $staff_settings) ):
								echo  '<div class="dt-sc-staff-specialization-wrapper">';
									echo '<span class="dt-sc-staff-label">'.esc_html__('Specialized In', 'wdt-ultimate-booking').':</span>';
									echo  '<span class="dt-sc-staff-item">';
										echo $staff_settings['staff-specialization'];
									echo '</span>';
								echo  '</div>';
							endif;

							// Staff Email

							if( array_key_exists('staff-email', $staff_settings) ):
								echo  '<div class="dt-sc-staff-email-wrapper">';
									echo '<span class="dt-sc-staff-label">'.esc_html__('Email Id', 'wdt-ultimate-booking').':</span>';
									echo  '<span class="dt-sc-staff-item">';
										echo '<a href="mailto:'.$staff_settings['staff-email'].'" class="dt-sc-staff-link">'.$staff_settings['staff-email'].'</a>';
									echo '</span>';
								echo  '</div>';
							endif;

							// Mob Number

							if( array_key_exists('staff-mobile', $staff_settings) ):
								echo  '<div class="dt-sc-staff-mobile-wrapper">';
									echo '<span class="dt-sc-staff-label">'.esc_html__('Mobile Number', 'wdt-ultimate-booking').':</span>';
									echo  '<span class="dt-sc-staff-item">';
										echo '<a href="tel:'.$staff_settings['staff-mobile'].'" class="dt-sc-staff-link">'.$staff_settings['staff-mobile'].'</a>';
									echo '</span>';
								echo  '</div>';
							endif;

							// Socials

							if( array_key_exists('staff-social', $staff_settings) ):
								echo  '<div class="dt-sc-staff-social-wrapper">';
									echo '<span class="dt-sc-staff-label">'.esc_html__('Follow us on', 'wdt-ultimate-booking').':</span>';
									echo do_shortcode($staff_settings['staff-social']);
								echo  '</div>';
							endif;


							// Description

							echo '<hr class="dt-sc-hr" />';
							
							the_content();

						echo '</div>';
					echo '</div>';

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