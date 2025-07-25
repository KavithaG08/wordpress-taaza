<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php
	$template_args['post_ID'] = $post_ID;
	$template_args = array_merge( $template_args, taaza_single_post_params() );

	foreach ( $post_dynamic_elements as $key => $value ) {

		switch( $value ) {

			case 'title':
			case 'comment_box':
			case 'navigation':
			case 'likes_views':
			case 'related_posts':
			case 'social':
				taaza_template_part( 'post', 'templates/post-extra/'.$value, '', $template_args );
				break;

			default:
				taaza_template_part( 'post', 'templates/'.$post_Style.'/parts/'.$value, '', $template_args );
				break;
		}
	}