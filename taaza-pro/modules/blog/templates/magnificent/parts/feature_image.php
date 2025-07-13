<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php
	$post_meta = get_post_meta( $post_ID, '_taaza_post_settings', TRUE );
	$post_meta = is_array( $post_meta ) ? $post_meta  : array();

	$post_format = !empty( $post_meta['post-format-type'] ) ? $post_meta['post-format-type'] : get_post_format();

	$template_args['post_ID'] = $post_ID;
	$template_args['meta'] = $post_meta;
	$template_args['enable_video_audio'] = $enable_video_audio;
	$template_args['enable_gallery_slider'] = $enable_gallery_slider; ?>

	<!-- Featured Image -->
	<div class="entry-thumb">
        <div class="entry-thumb-image-group">
            <?php taaza_template_part( 'blog', 'templates/post-format/post', $post_format, $template_args ); ?>
        </div>

        <div class="entry-thumb-detail-group">
            
            <?php if(in_array('date', $archive_post_elements)) :?>
                        <div class="entry-date">
                            <i class="wdticon-calendar"> </i>
                            <?php echo get_the_date ( get_option('date_format') ); ?>
                        </div>
                    <?php endif; ?>
            <?php if(in_array('title', $archive_post_elements)) :?>
                <div class="entry-title">
                    <h4><?php
                        if( is_sticky( $post_ID ) ) echo '<span class="sticky-post"><i class="wdticon-thumb-tack"></i><span>'.esc_html__('Featured', 'taaza-pro').'</span></span>'; ?>
                        <a href="<?php echo get_permalink( $post_ID );?>" title="<?php printf(esc_attr__('Permalink to %s','taaza-pro'), the_title_attribute('echo=0'));?>"><?php the_title();?></a>
                    </h4>
                </div>
            <?php endif; ?>
            <?php if(in_array('content', $archive_post_elements)) :?>
                <?php if( $enable_excerpt_text && $archive_excerpt_length > 0 ) : ?>
                    <div class="entry-body"><?php echo taaza_excerpt( $archive_excerpt_length );?></div>
                <?php endif; ?>
            <?php endif; ?>
            
        </div>

        <?php do_action( 'taaza_blog_archive_post_format', $enable_post_format, $post_format ); ?>