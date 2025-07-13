<?php
	$template_args['post_ID'] = $ID;
	$template_args['post_Style'] = $Post_Style;
	$template_args = array_merge( $template_args, taaza_single_post_params() ); ?>

    <?php taaza_template_part( 'post', 'templates/'.$Post_Style.'/parts/image', '', $template_args ); ?>

    <?php taaza_template_part( 'post', 'templates/post-extra/content', '', $template_args ); ?>

    <!-- Post Meta -->
    <div class="post-meta">
    	<!-- Meta Left -->
    	<div class="meta-left">
			<?php taaza_template_part( 'post', 'templates/'.$Post_Style.'/parts/author', '', $template_args ); ?>
    	</div><!-- Meta Left -->
    	<!-- Meta Right -->
    	<div class="meta-right">
			<?php taaza_template_part( 'post', 'templates/'.$Post_Style.'/parts/social', '', $template_args ); ?>
        </div>
    </div><!-- Post Meta -->

    <!-- Post Dynamic -->
    <?php echo apply_filters( 'taaza_single_post_dynamic_template_part', taaza_get_template_part( 'post', 'templates/'.$Post_Style.'/parts/dynamic', '', $template_args ) ); ?><!-- Post Dynamic -->