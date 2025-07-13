<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaProCustomizerBlogPost' ) ) {
    class TaazaProCustomizerBlogPost {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_filter( 'taaza_pro_customizer_default', array( $this, 'default' ) );
			add_action( 'customize_register', array( $this, 'register' ), 20 );
        }

        function default( $option ) {

            $post_defaults = array();
            if( function_exists('taaza_single_post_params_default') ) {
                $post_defaults = taaza_single_post_params_default();
            }

            $option['enable_title'] 		  = $post_defaults['enable_title'];
            $option['enable_image_lightbox']  = $post_defaults['enable_image_lightbox'];
			$option['enable_disqus_comments'] = $post_defaults['enable_disqus_comments'];
			$option['post_disqus_shortname']  = $post_defaults['post_disqus_shortname'];
			$option['post_dynamic_elements']  = $post_defaults['post_dynamic_elements'];
            $option['post_commentlist_style'] = $post_defaults['post_commentlist_style'];
			$option['select_post_navigation'] = $post_defaults['select_post_navigation'];

            $post_misc_defaults = array();
            if( function_exists('taaza_single_post_misc_default') ) {
                $post_misc_defaults = taaza_single_post_misc_default();
            }

            $option['enable_related_article'] = $post_misc_defaults['enable_related_article'];
			$option['rposts_title']    		  = $post_misc_defaults['rposts_title'];
			$option['rposts_column']   		  = $post_misc_defaults['rposts_column'];
			$option['rposts_count']    		  = $post_misc_defaults['rposts_count'];
			$option['rposts_excerpt']  		  = $post_misc_defaults['rposts_excerpt'];
			$option['rposts_excerpt_length']  = $post_misc_defaults['rposts_excerpt_length'];
			$option['rposts_carousel']  	  = $post_misc_defaults['rposts_carousel'];
			$option['rposts_carousel_nav']    = $post_misc_defaults['rposts_carousel_nav'];

            return $option;
        }

        function register( $wp_customize ) {

			/**
			 * Option : Post Title
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[enable_title]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Switch(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[enable_title]', array(
						'type'    => 'wdt-switch',
						'label'   => esc_html__( 'Enable Title', 'taaza-pro'),
						'description' => esc_html__('YES! to enable the title of single post.', 'taaza-pro'),
						'section' => 'site-blog-post-section',
						'choices' => array(
							'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
							'off' => esc_attr__( 'No', 'taaza-pro' )
						)
					)
				)
			);

			/**
			 * Option : Post Elements
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[post_dynamic_elements]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Sortable(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[post_dynamic_elements]', array(
						'type' => 'wdt-sortable',
						'label' => esc_html__( 'Post Elements Positioning', 'taaza-pro'),
						'section' => 'site-blog-post-section',
						'choices' => apply_filters( 'taaza_blog_post_dynamic_elements', array(
							'author'		=> esc_html__('Author', 'taaza-pro'),
							'author_bio' 	=> esc_html__('Author Bio', 'taaza-pro'),
							'category'    	=> esc_html__('Categories', 'taaza-pro'),
							'comment' 		=> esc_html__('Comments', 'taaza-pro'),
							'comment_box' 	=> esc_html__('Comment Box', 'taaza-pro'),
							'content'    	=> esc_html__('Content', 'taaza-pro'),
							'date'     		=> esc_html__('Date', 'taaza-pro'),
							'image'			=> esc_html__('Feature Image', 'taaza-pro'),
							'navigation'    => esc_html__('Navigation', 'taaza-pro'),
							'tag'  			=> esc_html__('Tags', 'taaza-pro'),
							'title'      	=> esc_html__('Title', 'taaza-pro'),
							'likes_views'   => esc_html__('Likes & Views', 'taaza-pro'),
							'related_posts' => esc_html__('Related Posts', 'taaza-pro'),
							'social'  		=> esc_html__('Social Share', 'taaza-pro'),
						)
					),
				)
			));

			/**
			 * Option : Post Navigation
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[select_post_navigation]', array(
					'type' => 'option',
				)
			);
			$wp_customize->add_control( new Taaza_Customize_Control(
				$wp_customize, TAAZA_CUSTOMISER_VAL . '[select_post_navigation]', array(
					'type'    => 'select',
					'section' => 'site-blog-post-section',
					'label'   => esc_html__( 'Navigation Type', 'taaza-pro' ),
					'choices' => array(
						'type1' 	=> esc_html__('Type 1', 'taaza-pro'),
						'type2'   	=> esc_html__('Type 2', 'taaza-pro'),
						'type3'   	=> esc_html__('Type 3', 'taaza-pro'),
					),
				)
			));


			/**
			 * Option : Image Lightbox
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[enable_image_lightbox]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Switch(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[enable_image_lightbox]', array(
						'type'    => 'wdt-switch',
						'label'   => esc_html__( 'Feature Image Lightbox', 'taaza-pro'),
						'description' => esc_html__('YES! to enable lightbox for feature image. Will not work in "Overlay" style.', 'taaza-pro'),
						'section' => 'site-blog-post-section',
						'choices' => array(
							'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
							'off' => esc_attr__( 'No', 'taaza-pro' )
						)
					)
				)
			);

			/**
			 * Option : Related Article
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[enable_related_article]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Switch(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[enable_related_article]', array(
						'type'    => 'wdt-switch',
						'label'   => esc_html__( 'Enable Related Article', 'taaza-pro'),
						'description' => esc_html__('YES! to enable related article at right hand side of post.', 'taaza-pro'),
						'section' => 'site-blog-post-section',
						'choices' => array(
							'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
							'off' => esc_attr__( 'No', 'taaza-pro' )
						)
					)
				)
			);

			/**
			 * Option : Disqus Comments
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[enable_disqus_comments]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Switch(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[enable_disqus_comments]', array(
						'type'    => 'wdt-switch',
						'label'   => esc_html__( 'Enable Disqus Comments', 'taaza-pro'),
						'description' => esc_html__('YES! to enable disqus platform comments module.', 'taaza-pro'),
						'section' => 'site-blog-post-section',
						'choices' => array(
							'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
							'off' => esc_attr__( 'No', 'taaza-pro' )
						)
					)
				)
			);

			/**
			 * Option : Disqus Short Name
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[post_disqus_shortname]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[post_disqus_shortname]', array(
						'type'    	  => 'textarea',
						'section'     => 'site-blog-post-section',
						'label'       => esc_html__( 'Shortname', 'taaza-pro' ),
						'input_attrs' => array(
							'placeholder' => 'disqus',
						),
						'dependency' => array( 'enable_disqus_comments', '==', 'true' ),
					)
				)
			);

			/**
			 * Option : Disqus Description
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[post_disqus_description]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Description(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[post_disqus_description]', array(
						'type'    	  => 'wdt-description',
						'section'     => 'site-blog-post-section',
						'description' => esc_html__('Your site\'s unique identifier', 'taaza-pro').' '.'<a href="'.esc_url('https://help.disqus.com/customer/portal/articles/466208').'" target="_blank">'.esc_html__('What is this?', 'taaza-pro').'</a>',
						'dependency' => array( 'enable_disqus_comments', '==', 'true' ),
					)
				)
			);

			/**
			 * Option : Comment List Style
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[post_commentlist_style]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control( new Taaza_Customize_Control(
				$wp_customize, TAAZA_CUSTOMISER_VAL . '[post_commentlist_style]', array(
					'type'    => 'select',
					'section' => 'site-blog-post-section',
					'label'   => esc_html__( 'Comments List Style', 'taaza-pro' ),
					'choices' => array(
						'rounded' 	=> esc_html__('Rounded', 'taaza-pro'),
						'square'   	=> esc_html__('Square', 'taaza-pro'),
					),
					'description' => esc_html__('Choose comments list style to display single post.', 'taaza-pro'),
					'dependency' => array( 'enable_disqus_comments', '!=', 'true' ),
				)
			));

			/**
			 * Option : Post Related Title
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[rposts_title]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[rposts_title]', array(
						'type'    	  => 'text',
						'section'     => 'site-blog-post-section',
						'label'       => esc_html__( 'Related Posts Section Title', 'taaza-pro' ),
						'description' => esc_html__('Put the related posts section title here', 'taaza-pro'),
						'input_attrs' => array(
							'value'	=> esc_html__('Related Posts', 'taaza-pro'),
						)
					)
				)
			);

			/**
			 * Option : Related Columns
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[rposts_column]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control( new Taaza_Customize_Control_Radio_Image(
				$wp_customize, TAAZA_CUSTOMISER_VAL . '[rposts_column]', array(
					'type' => 'wdt-radio-image',
					'label' => esc_html__( 'Columns', 'taaza-pro'),
					'section' => 'site-blog-post-section',
					'choices' => apply_filters( 'taaza_blog_post_related_columns', array(
						'one-column' => array(
							'label' => esc_html__( 'One Column', 'taaza-pro' ),
							'path' => TAAZA_PRO_DIR_URL . 'modules/post/customizer/images/one-column.png'
						),
						'one-half-column' => array(
							'label' => esc_html__( 'One Half Column', 'taaza-pro' ),
							'path' => TAAZA_PRO_DIR_URL . 'modules/post/customizer/images/one-half-column.png'
						),
						'one-third-column' => array(
							'label' => esc_html__( 'One Third Column', 'taaza-pro' ),
							'path' => TAAZA_PRO_DIR_URL . 'modules/post/customizer/images/one-third-column.png'
						),
					)),
				)
			));

			/**
			 * Option : Related Count
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[rposts_count]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[rposts_count]', array(
						'type'    	  => 'text',
						'section'     => 'site-blog-post-section',
						'label'       => esc_html__( 'No.of Posts to Show', 'taaza-pro' ),
						'description' => esc_html__('Put the no.of related posts to show', 'taaza-pro'),
						'input_attrs' => array(
							'value'	=> 3,
						),
					)
				)
			);

			/**
			 * Option : Enable Excerpt
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[rposts_excerpt]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Switch(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[rposts_excerpt]', array(
						'type'    => 'wdt-switch',
						'label'   => esc_html__( 'Enable Excerpt Text', 'taaza-pro'),
						'section' => 'site-blog-post-section',
						'choices' => array(
							'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
							'off' => esc_attr__( 'No', 'taaza-pro' )
						)
					)
				)
			);

			/**
			 * Option : Excerpt Text
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[rposts_excerpt_length]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[rposts_excerpt_length]', array(
						'type'    	  => 'text',
						'section'     => 'site-blog-post-section',
						'label'       => esc_html__( 'Excerpt Length', 'taaza-pro' ),
						'description' => esc_html__('Put Excerpt Length', 'taaza-pro'),
						'input_attrs' => array(
							'value'	=> 25,
						),
						'dependency' => array( 'rposts_excerpt', '==', 'true' ),
					)
				)
			);

			/**
			 * Option : Related Carousel
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[rposts_carousel]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control(
				new Taaza_Customize_Control_Switch(
					$wp_customize, TAAZA_CUSTOMISER_VAL . '[rposts_carousel]', array(
						'type'    => 'wdt-switch',
						'label'   => esc_html__( 'Enable Carousel', 'taaza-pro'),
						'description' => esc_html__('YES! to enable carousel related posts', 'taaza-pro'),
						'section' => 'site-blog-post-section',
						'choices' => array(
							'on'  => esc_attr__( 'Yes', 'taaza-pro' ),
							'off' => esc_attr__( 'No', 'taaza-pro' )
						)
					)
				)
			);

			/**
			 * Option : Related Carousel Nav
			 */
			$wp_customize->add_setting(
				TAAZA_CUSTOMISER_VAL . '[rposts_carousel_nav]', array(
					'type' => 'option',
				)
			);

			$wp_customize->add_control( new Taaza_Customize_Control(
				$wp_customize, TAAZA_CUSTOMISER_VAL . '[rposts_carousel_nav]', array(
					'type'    => 'select',
					'section' => 'site-blog-post-section',
					'label'   => esc_html__( 'Navigation Style', 'taaza-pro' ),
					'choices' => array(
						'' 			 => esc_html__('None', 'taaza-pro'),
						'navigation' => esc_html__('Navigations', 'taaza-pro'),
						'pager'   	 => esc_html__('Pager', 'taaza-pro'),
					),
					'description' => esc_html__('Choose navigation style to display related post carousel.', 'taaza-pro'),
					'dependency' => array( 'rposts_carousel', '==', 'true' ),
				)
			));

        }
    }
}

TaazaProCustomizerBlogPost::instance();