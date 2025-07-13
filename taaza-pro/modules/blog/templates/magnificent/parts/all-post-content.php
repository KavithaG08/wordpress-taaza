<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php
	$post_meta = get_post_meta( $post_ID, '_taaza_post_settings', TRUE );
	$post_meta = is_array( $post_meta ) ? $post_meta  : array();

	$post_format = !empty( $post_meta['post-format-type'] ) ? $post_meta['post-format-type'] : get_post_format();

	$template_args['post_ID'] = $post_ID;
	$template_args['meta'] = $post_meta;
	$template_args['enable_video_audio'] = $enable_video_audio;
	$template_args['enable_gallery_slider'] = $enable_gallery_slider;
    $template_args['archive_excerpt_length'] = $archive_excerpt_length;
    $template_args['enable_excerpt_text'] = $enable_excerpt_text;
    $template_args['archive_readmore_text'] = $archive_readmore_text;
    $template_args['enable_disqus_comments'] = $enable_disqus_comments;
    $template_args['post_disqus_shortname'] = $post_disqus_shortname;
     ?>

	<!-- Featured Image -->
	<div class="entry-thumb">
        <div class="entry-thumb-image-group">
            <?php taaza_template_part( 'blog', 'templates/post-format/post', $post_format, $template_args ); ?>
        </div>
        <div class="entry-thumb-detail-group">
            <?php
            foreach ( $archive_post_elements as $key => $value ) 
            {
                if($value!=='feature_image' && $value!=='meta_group' )
                {
                    $path=taaza_template_part( 'blog', 'templates/post-extra/'.$value, '', $template_args );
                    echo taaza_html_output($path);
                }
                else if($value=='meta_group')
                {
                    echo '<div class="entry-meta-group">';
                        foreach ( $archive_meta_elements as $key => $value ) {
                            switch( $value ) {
                                case 'likes_views':
                                case 'social':
                                    taaza_template_part( 'blog', 'templates/post-extra/'.$value, '', $template_args );
                                    break;
                                default:
                                    $path = taaza_get_template_part( 'blog', 'templates/'.$Post_Style.'/parts/'.$value, '', $template_args );
                                    $path = !empty( $path ) ? $path : taaza_get_template_part( 'blog', 'templates/post-extra/'.$value, '', $template_args );
                                    echo taaza_html_output($path);
                                    break;
                            }
                        }
			        echo '</div>';
                }
            }
            ?>
        </div>
    <?php do_action( 'taaza_blog_archive_post_format', $enable_post_format, $post_format ); ?>