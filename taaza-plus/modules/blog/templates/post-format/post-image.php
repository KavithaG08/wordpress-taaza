<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php
	$img_size = array(
		'one-column' => 'full',
		'one-half-column' => 'wdt-blog-ii-column',
		'one-third-column' => 'wdt-blog-iii-column',
		'one-fourth-column' => 'wdt-blog-iv-column'
	);

	$post_column = taaza_get_archive_post_column();

	if( has_post_thumbnail( $post_ID ) ) :
		do_action( 'taaza_blog_archive_post_thumbnail', $post_ID, $img_size, $post_column );
	endif;
?>