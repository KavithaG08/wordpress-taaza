<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<!-- Entry Navigation -->
<div class="entry-post-navigation"><?php
	$prev_post = get_previous_post();
	if( !empty( $prev_post ) ):	?>

		<div class="post-prev-link"><?php
			if( has_post_thumbnail( $prev_post->ID ) ):
				$entry_bg = '';
				$url = get_the_post_thumbnail_url( $prev_post->ID, 'full' );
				$entry_bg = "style=background-image:url(".$url.")"; ?>

				<a href="<?php echo get_permalink( $prev_post->ID ); ?>" <?php echo esc_attr($entry_bg);?> class="prev-post-bgimg"></a><?php
			endif; ?>

			<div class="nav-title-wrap">
				<p><a href="<?php echo get_permalink( $prev_post->ID ); ?>" title="<?php echo esc_attr($prev_post->post_title); ?>"><?php esc_html_e('Previous Blogs','taaza-pro'); ?></a></p>
				<span class="zmdi zmdi-long-arrow-left zmdi-hc-fw"></span>
				<h3><a href="<?php echo get_permalink( $prev_post->ID ); ?>" title="<?php echo esc_attr($prev_post->post_title); ?>"><?php
					if( get_the_title( $prev_post->ID ) == '') {
						echo esc_html__('Previous Post', 'taaza-pro');
					} else {
						echo "$prev_post->post_title";
					} ?></a>
				</h3>
			</div>

		</div>
		<?php
	else: ?>
		<div class="post-prev-link no-post">
            <a href="#" style="background-image:url(<?php echo esc_url(TAAZA_ROOT_URI.'/assets/images/no-post.jpg') ?>);" class="prev-post-bgimg"></a>
			<div class="nav-title-wrap">
				<span class="zmdi zmdi-long-arrow-left zmdi-hc-fw"></span>
				<h3><?php echo esc_html__('No previous story to show!', 'taaza-pro'); ?></h3>
			</div>
		</div>
		<?php
	endif;

	$next_post = get_next_post();
	if( !empty( $next_post ) ):	?>
		<div class="post-next-link"><?php

			if( has_post_thumbnail( $next_post->ID ) ):
				$entry_bg = '';
				$url = get_the_post_thumbnail_url( $next_post->ID, 'full' );
				$entry_bg = "style=background-image:url(".$url.")"; ?>

				<a href="<?php echo get_permalink( $next_post->ID ); ?>" <?php echo esc_attr($entry_bg);?> class="next-post-bgimg"></a><?php
			endif; ?>

			<div class="nav-title-wrap">
				<p><a href="<?php echo get_permalink( $next_post->ID ); ?>" title="<?php echo esc_attr($next_post->post_title); ?>"><?php esc_html_e('Next Blogs','taaza-pro'); ?></a></p>
				<span class="zmdi zmdi-long-arrow-right zmdi-hc-fw"></span>
				<h3><a href="<?php echo get_permalink( $next_post->ID ); ?>" title="<?php echo esc_attr($next_post->post_title); ?>"><?php
					if(get_the_title( $next_post->ID ) == '') {
						echo esc_html__('Next Post', 'taaza-pro');
					} else {
						echo "$next_post->post_title";
					} ?></a>
				</h3>
			</div>

		</div>
		<?php
	else: ?>
		<div class="post-next-link no-post">
            <a href="#" style="background-image:url(<?php echo esc_url(TAAZA_ROOT_URI.'/assets/images/no-post.jpg') ?>);" class="next-post-bgimg"></a>
			<div class="nav-title-wrap">
				<span class="zmdi zmdi-long-arrow-right zmdi-hc-fw"></span>
				<h3><?php echo esc_html__('No next story to show!', 'taaza-pro'); ?></h3>
			</div>
		</div>
		<?php
	endif; ?>
</div><!-- Entry Navigation -->