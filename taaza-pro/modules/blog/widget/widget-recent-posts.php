<?php
class Taaza_Widget_Recent_Posts extends WP_Widget {
	#1.constructor
	function __construct() {
		$widget_options = array(
			'classname'   => 'widget_recent_posts',
			'description' => esc_html__('To list out posts', 'taaza-pro')
		);

        $theme_name =  defined('TAAZA_THEME_NAME') ? TAAZA_THEME_NAME : 'Taaza';
		parent::__construct( false, $theme_name . esc_html__(' Posts','taaza-pro'), $widget_options );
	}

	#2.widget input form in back-end
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'            => '',
			'_post_count'      => '',
			'_post_categories' => '',
			'_enabled_image'   => '',
			'_excerpt'         => ''
		) );

		$title            = strip_tags($instance['title']);
		$_post_count      = !empty($instance['_post_count']) ? strip_tags($instance['_post_count']) : "-1";
		$_post_categories = !empty($instance['_post_categories']) ? $instance['_post_categories']: array();
		$_enabled_image   = isset($instance['_enabled_image']) ? (bool) $instance['_enabled_image'] : false;
		$_excerpt         = !empty($instance['_excerpt']) ? $instance['_excerpt'] : 'show title only';?>

        <!-- Form -->
        <p>
        	<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
        		<?php esc_html_e('Title:','taaza-pro');?>
        		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
			</label>
		</p>

	    <p>
	    	<label for="<?php echo esc_attr($this->get_field_id('_post_categories')); ?>">
	    		<?php esc_html_e('Choose the categories you want to display (multiple selection possible)','taaza-pro');?>
	    	</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('_post_categories').'[]');?>" name="<?php echo esc_attr($this->get_field_name('_post_categories').'[]');?>" multiple="multiple">
            	<option value=""><?php esc_html_e("Select",'taaza-pro');?></option><?php
            	$cats = get_categories( 'orderby=name&hide_empty=0' );
            	foreach ($cats as $cat):
					$id       = esc_attr($cat->term_id);
					$selected = ( in_array($id,$_post_categories)) ? 'selected="selected"' : '';
					$title    = esc_html($cat->name);?>
        			<option <?php echo esc_attr($selected);?> value="<?php echo esc_attr($id);?>"><?php echo esc_attr($title);?></option><?php
				endforeach;?>
            </select>
        </p>

        <p>
        	<label for="<?php echo esc_attr($this->get_field_id('_excerpt')); ?>">
        		<?php esc_html_e('Display title only or title &amp; excerpt','taaza-pro');?>
        	</label>
        	<select class="widefat" id="<?php echo esc_attr($this->get_field_id('_excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('_excerpt')); ?>"><?php
        		$answers = array('show title only','show title and excerpt');
		   		foreach ($answers  as $answer ):
		   			$selected = ($_excerpt == $answer ) ? "selected='selected'" : "";?>
		   			<option <?php echo esc_attr($selected);?> value="<?php echo esc_attr($answer);?>"><?php echo esc_attr($answer);?></option><?php
		   		endforeach;?>
           </select>
        </p>

        <p>
        	<input type="checkbox" id="<?php echo esc_attr($this->get_field_id('_enabled_image'));?>" name="<?php echo esc_attr($this->get_field_name('_enabled_image'));?>" <?php checked($_enabled_image);?>/>
        	<?php esc_html_e("Show Image",'taaza-pro');?>
        </p>

	    <p>
	    	<label for="<?php echo esc_attr($this->get_field_id('_post_count'));?>">
	    		<?php esc_html_e('No.of posts to show:','taaza-pro');?>
	    	</label>
		    <input id="<?php echo esc_attr($this->get_field_id('_post_count')); ?>" name="<?php echo esc_attr($this->get_field_name('_post_count')); ?>" value="<?php echo esc_attr($_post_count);?>"/>
		</p><!-- Form end--><?php
	}

	#3.processes & saves the twitter widget option
	function update( $new_instance,$old_instance ) {
		$instance = $old_instance;

		$instance['title']            = strip_tags($new_instance['title']);
		$instance['_post_count']      = strip_tags($new_instance['_post_count']);
		$instance['_post_categories'] = $new_instance['_post_categories'];
		$instance['_excerpt']         = $new_instance['_excerpt'];
		$instance['_enabled_image']   = !empty($new_instance['_enabled_image']) ? 1 : 0;

		return $instance;
	}

	#4.output in front-end
	function widget($args, $instance) {
		extract($args);

		global $post;

		$title            = empty($instance['title']) ?	'' : strip_tags($instance['title']);
		$_post_count      = (int) $instance['_post_count'];

		$_post_categories = "";
		if(!empty($instance['_post_categories']) ) {
			$_post_categories = is_array($instance['_post_categories']) ? implode(",",$instance['_post_categories']) : $instance['_post_categories'];
		}

		$_enabled_image = isset($instance['_enabled_image']) ? $instance['_enabled_image']:0;
		$show_title     = ($instance['_excerpt'] == 'show title only') ? (bool) true : (bool) false;

		$arg = empty($_post_categories) ? "posts_per_page={$_post_count}":"cat={$_post_categories}&posts_per_page={$_post_count}";

		echo taaza_pro_before_after_widget( $before_widget );

		if( !empty( $title ) ) {

			echo taaza_pro_widget_title( $before_title . $title . $after_title );
		}

		 echo "<div class='recent-posts-widget'><ul>";
		 $the_query = new WP_Query($arg);
		 if( $the_query->have_posts() ) :
			while( $the_query->have_posts() ):
				$the_query->the_post();
				$title = get_the_title();
				echo "<li>";
					if(1 == $_enabled_image):
						$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
						$image = ( $image != false)? $image[0] : TAAZA_PRO_DIR_URL . 'modules/blog/assets/images/post-thumb.jpg';
						echo "<div class='entry-image'><a href='".get_permalink()."' class='thumb'>";
							echo "<img src='{$image}' alt='".the_title_attribute('echo=0')."'>";
						echo "</a></div>";
					endif;

					echo '<div class="post-details">';
						echo '<div class="post-date">';
							echo '<p> '.get_the_date('M').' '.get_the_date('d').'</p>';
						echo '</div>';

						if($show_title):
							echo "<div class='entry-title'><h4><a href='".get_permalink()."'>{$title}</a></h4></div>";
						else:
							echo "<div class='entry-title'><h4><a href='".get_permalink()."'>{$title}</a></h4></div>";
							echo "<div class='entry-content'>".taaza_excerpt(10)."</div>";
						endif;
					echo '</div>';
				echo "</li>";
			endwhile;
		 else:
			 echo "<li><h4>".esc_html__('No Posts found','taaza-pro')."</h4></li>";
		 endif;
		 wp_reset_postdata();
	 echo "</ul></div>";

		echo taaza_pro_before_after_widget( $after_widget );
	}
}?>