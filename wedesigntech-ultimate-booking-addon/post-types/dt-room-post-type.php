<?php
if (! class_exists ( 'DTRoomPostType' )) {
	class DTRoomPostType {

		function __construct() {
			// Add Hook into the 'init()' action
			add_action ( 'init', array (
					$this,
					'dt_init'
			) );

			// Add Hook into the 'admin_init()' action
			add_action ( 'admin_init', array (
					$this,
					'dt_admin_init'
			) );

			// Add Hook into the 'admin_enqueue_scripts' filter
			add_action( 'admin_enqueue_scripts', array (
					$this,
					'dt_room_admin_scripts'
			) );

			// Add Hook into the 'cs_framework_options' filter
			add_filter ( 'cs_framework_options', array (
					$this,
					'dt_room_cs_framework_options'
			) );

			// Add Hook into the 'cs_metabox_options' filter
			add_filter ( 'cs_metabox_options', array (
				$this,
				'dt_room_cs_metabox_options'
			) );
		}

		/**
		 * A function hook that the WordPress core launches at 'init' points
		 */
		function dt_init() {
			$this->createPostType ();

			if ( ! session_id() ) {
				session_start(); 
			}
			
		}

		/**
		 * Creating a post type
		 */
		function createPostType() {

			$roomslug 			= ultimate_booking_pro_cs_get_option( 'single-room-slug', 'dt_room' );
			$room_singular		= ultimate_booking_pro_cs_get_option( 'singular-room-text', esc_html__('Room', 'wdt-ultimate-booking') );
			$room_plural			= ultimate_booking_pro_cs_get_option( 'plural-room-text', esc_html__('Rooms', 'wdt-ultimate-booking') );

			$roomcatslug  		= ultimate_booking_pro_cs_get_option( 'room-cat-slug', 'dt_room_category' );
			$room_cat_singular 	= ultimate_booking_pro_cs_get_option( 'singular-room-cat-text', esc_html__('Category', 'wdt-ultimate-booking') );
			$room_cat_plural		= ultimate_booking_pro_cs_get_option( 'plural-room-cat-text', esc_html__('Categories', 'wdt-ultimate-booking') );

			//Room Amenities
			$roomamenityslug  		= ultimate_booking_pro_cs_get_option( 'room-amenity-slug', 'dt_room_amenity' );
			$room_amenity_singular 	= ultimate_booking_pro_cs_get_option( 'singular-room-amenity-text', esc_html__('Amenity', 'wdt-ultimate-booking') );
			$room_amenity_plural		= ultimate_booking_pro_cs_get_option( 'plural-room-amenity-text', esc_html__('Amenities', 'wdt-ultimate-booking') );

			$labels = array (
				'name' 				 => $room_plural,
				'all_items' 		 => esc_html__( 'All', 'wdt-ultimate-booking' ).' '.$room_plural,
				'singular_name' 	 => $room_singular,
				'add_new' 			 => esc_html__( 'Add New', 'wdt-ultimate-booking' ),
				'add_new_item' 		 => esc_html__( 'Add New', 'wdt-ultimate-booking' ).' '.$room_singular,
				'edit_item' 		 => esc_html__( 'Edit', 'wdt-ultimate-booking' ).' '.$room_singular,
				'new_item' 			 => esc_html__( 'New', 'wdt-ultimate-booking' ).' '.$room_singular,
				'view_item' 		 => esc_html__( 'View', 'wdt-ultimate-booking' ).' '.$room_singular,
				'search_items' 		 => esc_html__( 'Search', 'wdt-ultimate-booking' ).' '.$room_plural,
				'not_found' 		 => esc_html__( 'No', 'wdt-ultimate-booking').' '.$room_plural.' '.esc_html__('found', 'wdt-ultimate-booking' ),
				'not_found_in_trash' => esc_html__( 'No', 'wdt-ultimate-booking').' '.$room_plural.' '.esc_html__('found in Trash', 'wdt-ultimate-booking' ),
				'parent_item_colon'  => esc_html__( 'Parent', 'wdt-ultimate-booking' ).' '.$room_singular.':',
				'menu_name' 		 => $room_plural,
			);

			$args = array (
				'labels' 				=> $labels,
				'hierarchical' 			=> false,
				'description' 			=> esc_html__( 'Post type archives of ', 'wdt-ultimate-booking' ).' '.$room_plural,
				'supports' 				=> array (
											'title',
											'editor',
											'comments',
											'thumbnail',
											'excerpt'
										),
				'public' 				=> true,
				'show_ui' 				=> true,
				'show_in_menu' 			=> true,
				'menu_position' 		=> 8,
				'menu_icon' 			=> 'dashicons-carrot',

				'show_in_nav_menus' 	=> true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'has_archive' 			=> true,
				'query_var' 			=> true,
				'can_export' 			=> true,
				'rewrite' 				=> array( 'slug' => $roomslug ),
				'capability_type' 		=> 'post'
			);

			register_post_type ( 'dt_room', $args );

			if( cs_get_option('enable-room-taxonomy') ):
				// Room Categories
				$labels = array(
					'name'              => $room_cat_plural,
					'singular_name'     => $room_cat_singular,
					'search_items'      => esc_html__( 'Search', 'wdt-ultimate-booking' ).' '.$room_cat_plural,
					'all_items'         => esc_html__( 'All', 'wdt-ultimate-booking' ).' '.$room_cat_plural,
					'parent_item'       => esc_html__( 'Parent', 'wdt-ultimate-booking' ).' '.$room_cat_singular,
					'parent_item_colon' => esc_html__( 'Parent', 'wdt-ultimate-booking' ).' '.$room_cat_singular.':',
					'edit_item'         => esc_html__( 'Edit', 'wdt-ultimate-booking' ).' '.$room_cat_singular,
					'update_item'       => esc_html__( 'Update', 'wdt-ultimate-booking' ).' '.$room_cat_singular,
					'add_new_item'      => esc_html__( 'Add New', 'wdt-ultimate-booking' ).' '.$room_cat_singular,
					'new_item_name'     => esc_html__( 'New', 'wdt-ultimate-booking' ).' '.$room_cat_singular.' '.esc_html__('Name', 'wdt-ultimate-booking'),
					'menu_name'         => $room_cat_plural,
				);

				register_taxonomy ( 'dt_room_category', array (
					'dt_room'
				), array (
					'hierarchical' 		=> true,
					'labels' 			=> $labels,
					'show_admin_column' => true,
					'rewrite' 			=> array( 'slug' => $roomcatslug ),
					'query_var' 		=> true
				) );
			endif;

			if( cs_get_option('enable-room-amenity') ):
				// Room Amenities
				$labels = array(
					'name'              => $room_amenity_plural,
					'singular_name'     => $room_amenity_singular,
					'search_items'      => esc_html__( 'Search', 'wdt-ultimate-booking' ).' '.$room_amenity_plural,
					'all_items'         => esc_html__( 'All', 'wdt-ultimate-booking' ).' '.$room_amenity_plural,
					'parent_item'       => esc_html__( 'Parent', 'wdt-ultimate-booking' ).' '.$room_amenity_singular,
					'parent_item_colon' => esc_html__( 'Parent', 'wdt-ultimate-booking' ).' '.$room_amenity_singular.':',
					'edit_item'         => esc_html__( 'Edit', 'wdt-ultimate-booking' ).' '.$room_amenity_singular,
					'update_item'       => esc_html__( 'Update', 'wdt-ultimate-booking' ).' '.$room_amenity_singular,
					'add_new_item'      => esc_html__( 'Add New', 'wdt-ultimate-booking' ).' '.$room_amenity_singular,
					'new_item_name'     => esc_html__( 'New', 'wdt-ultimate-booking' ).' '.$room_amenity_singular.' '.esc_html__('Name', 'wdt-ultimate-booking'),
					'menu_name'         => $room_amenity_plural,
				);

				register_taxonomy ( 'dt_room_amenity', array (
					'dt_room'
				), array (
					'hierarchical' 		=> true,
					'labels' 			=> $labels,
					'show_admin_column' => true,
					'rewrite' 			=> array( 'slug' => $roomamenityslug ),
					'query_var' 		=> true
				) );
			endif;
		}

		/**
		 * A function hook that the WordPress core launches at 'admin_init' points
		 */
		function dt_admin_init() {

			/* Taxomony custom fields */
			require_once plugin_dir_path ( __FILE__ ) . '/taxonomy-custom-fields.php';
			if (class_exists ( 'DTTaxonomyCustomFields' )) {
				new DTTaxonomyCustomFields();
			}

			add_filter ( "manage_edit-dt_room_columns", array (
					$this,
					"dt_room_edit_columns"
			) );

			add_action ( "manage_posts_custom_column", array (
					$this,
					"dt_room_columns_display"
			), 10, 2 );
		}

		/**
		 * custom admin scripts & styles
		 */
		function dt_room_admin_scripts( $hook ) {

			if( $hook == "edit.php" ) {
				wp_enqueue_style ( 'dt-room-admin', plugins_url ('wedesigntech-ultimate-booking-addon') . '/post-types/css/admin-styles.css', array (), false, 'all' );
			}
		}

		/**
		 * Room framework options
		 */
		function dt_room_cs_framework_options( $options ) {

			$roomslug 			= ultimate_booking_pro_cs_get_option( 'single-room-slug', 'dt_room' );
			$room_singular		= ultimate_booking_pro_cs_get_option( 'singular-room-text', esc_html__('Room', 'wdt-ultimate-booking') );
			$room_plural			= ultimate_booking_pro_cs_get_option( 'plural-room-text', esc_html__('Rooms', 'wdt-ultimate-booking') );

			$roomcatslug  		= ultimate_booking_pro_cs_get_option( 'room-cat-slug', 'dt_room_category' );
			$room_cat_singular 	= ultimate_booking_pro_cs_get_option( 'singular-room-cat-text', esc_html__('Category', 'wdt-ultimate-booking') );
			$room_cat_plural		= ultimate_booking_pro_cs_get_option( 'plural-room-cat-text', esc_html__('Categories', 'wdt-ultimate-booking') );

			// Room Amenities
			$roomamenityslug  		= ultimate_booking_pro_cs_get_option( 'room-amenity-slug', 'dt_room_amenity' );
			$room_amenity_singular 	= ultimate_booking_pro_cs_get_option( 'singular-room-amenity-text', esc_html__('Amenity', 'wdt-ultimate-booking') );
			$room_amenity_plural		= ultimate_booking_pro_cs_get_option( 'plural-room-amenity-text', esc_html__('Amenities', 'wdt-ultimate-booking') );

			$options['booking-manager']['sections'][] = array(

				// -----------------------------------------
				// Room Options
				// -----------------------------------------
				'name'      => 'room_options',
				'title'     => $room_singular.' '.esc_html__('Options', 'wdt-ultimate-booking'),
				'icon'      => 'fa fa-info-circle',

				  'fields'      => array(
					  array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Room Archives Post Layout', 'wdt-ultimate-booking' ),
					  ),

					  array(
						'id'      	   => 'room-archives-post-layout',
						'type'         => 'image_select',
						'title'        => esc_html__('Post Layout', 'wdt-ultimate-booking'),
						'options'      => array(
						  'one-half-column'   => ULTIMATEBOOKINGPRO_URL . '/cs-framework-override/images/one-half-column.png',
						  'one-third-column'  => ULTIMATEBOOKINGPRO_URL . '/cs-framework-override/images/one-third-column.png',
						  'one-fourth-column' => ULTIMATEBOOKINGPRO_URL . '/cs-framework-override/images/one-fourth-column.png',
						),
						'default'      => 'one-third-column',
					  ),

					  array(
						'id'       => 'room-per-page',
						'type'     => 'text',
						'title'    => esc_html__('Number of room Per Page', 'wdt-ultimate-booking'),
						'desc'     => esc_html__('Enter the number of room to display per page.', 'wdt-ultimate-booking'),
						'default'  => '-1', 
						'validate' => 'numeric', 
					),

					  array(
						'id'  	=> 'room-archives-excerpt',
						'type'  => 'switcher',
						'title' => esc_html__('Show Excerpt', 'wdt-ultimate-booking'),
						'label'	=> esc_html__("YES! to enable Room's  excerpt", "wdt-ultimate-booking"),
						'default' => true,
					  ),

					  array(
					  	'id'  	  	 => 'room-archives-excerpt-length',
					  	'type'    	 => 'number',
					  	'title'   	 => esc_html__('Excerpt Length', 'wdt-ultimate-booking'),
					  	'after'	   	 => '<span class="cs-text-desc">&nbsp;'.esc_html__('No.of words', 'wdt-ultimate-booking').'</span>',
					  	'default' 	 => 18,
					  	'dependency' => array( 'room-archives-excerpt', '==', 'true' ),
					  ),

					  array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Permalinks', 'wdt-ultimate-booking' ),
					  ),

					  array(
						'id'      => 'singular-room-text',
						'type'    => 'text',
						'title'   => esc_html__('Singular', 'wdt-ultimate-booking').' '.$room_singular.' '.esc_html__('Name', 'wdt-ultimate-booking'),
						'default' => $room_singular,
						'after'   => '<p class="cs-text-info">'.esc_html__('Change as you like, save options & reload.', 'wdt-ultimate-booking').'</p>',
					  ),

					  array(
						'id'      => 'plural-room-text',
						'type'    => 'text',
						'title'   => esc_html__('Plural', 'wdt-ultimate-booking').' '.$room_singular.' '.esc_html__('Name', 'wdt-ultimate-booking'),
						'default' => $room_plural,
						'after'   => '<p class="cs-text-info">'.esc_html__('Change as you like, save options & reload.', 'wdt-ultimate-booking').'</p>',
					  ),

					  array(
						'id'      => 'singular-room-cat-text',
						'type'    => 'text',
						'title'   => esc_html__('Singular', 'wdt-ultimate-booking').' '.$room_cat_singular.' '.esc_html__('Name', 'wdt-ultimate-booking'),
						'default' => $room_cat_singular,
						'after'   => '<p class="cs-text-info">'.esc_html__('Change as you like, save options & reload.', 'wdt-ultimate-booking').'</p>',
					  ),

					  array(
						'id'      => 'plural-room-cat-text',
						'type'    => 'text',
						'title'   => esc_html__('Plural', 'wdt-ultimate-booking').' '.$room_cat_plural.' '.esc_html__('Name', 'wdt-ultimate-booking'),
						'default' => $room_cat_plural,
						'after'   => '<p class="cs-text-info">'.esc_html__('Change as you like, save options & reload.', 'wdt-ultimate-booking').'</p>',
					  ),

					  array(
						'id'      => 'singular-room-amenity-text',
						'type'    => 'text',
						'title'   => esc_html__('Singular', 'wdt-ultimate-booking').' '.$room_amenity_singular.' '.esc_html__('Name', 'wdt-ultimate-booking'),
						'default' => $room_amenity_singular,
						'after'   => '<p class="cs-text-info">'.esc_html__('Change as you like, save options & reload.', 'wdt-ultimate-booking').'</p>',
					  ),

					  array(
						'id'      => 'plural-room-amenity-text',
						'type'    => 'text',
						'title'   => esc_html__('Plural', 'wdt-ultimate-booking').' '.$room_amenity_plural.' '.esc_html__('Name', 'wdt-ultimate-booking'),
						'default' => $room_amenity_plural,
						'after'   => '<p class="cs-text-info">'.esc_html__('Change as you like, save options & reload.', 'wdt-ultimate-booking').'</p>',
					  ),

					  array(
						'id'      => 'single-room-slug',
						'type'    => 'text',
						'title'   => esc_html__('Single', 'wdt-ultimate-booking').' '.$room_singular.' '.esc_html__('Slug', 'wdt-ultimate-booking'),
						'after'   => '<p class="cs-text-info">'.esc_html__('Do not use characters not allowed in links. Use, eg. room-item ', 'wdt-ultimate-booking').'<br> <b>'.esc_html__('After made changes save permalinks.', 'wdt-ultimate-booking').'</b></p>',
					  ),

					  array(
						'id'      => 'room-cat-slug',
						'type'    => 'text',
						'title'   => $room_singular.' '.$room_cat_singular.' '.esc_html__('Slug', 'wdt-ultimate-booking'),
						'after'   => '<p class="cs-text-info">'.esc_html__('Do not use characters not allowed in links. Use, eg. room-type ', 'wdt-ultimate-booking').'<br> <b>'.esc_html__('After made changes save permalinks.', 'wdt-ultimate-booking').'</b></p>',
					  ),
				  ),
			);

			// Filter to add additional options for themes
			$options = apply_filters( 'ultimate_booking_pro_template_framework_options', $options );

			return $options;
		}

		/**
		 *
		 * @param unknown $columns
		 * @return multitype:
		 */
		function dt_room_edit_columns($columns) {

			$newcolumns = array (
				"cb"               => "<input type=\"checkbox\" />",
				"dt_room_thumb" => esc_html__("Image", 'wdt-ultimate-booking'),
				"title"            => esc_html__("Title", 'wdt-ultimate-booking'),
				"cost"        	   => esc_html__("Cost", 'wdt-ultimate-booking'),
				"duration"         => esc_html__("Duration", 'wdt-ultimate-booking')
			);

			$columns = array_merge ( $newcolumns, $columns );
			return $columns;
		}

		/**
		 * Room metabox options
		 */
		function dt_room_cs_metabox_options( $options ) {
			$fields = cs_get_option( 'room-custom-fields');
			// $bothfields = $fielddef = $x = array();
			$before = '';
			$times = array( '' => esc_html__('Select', 'wdt-ultimate-booking') );
			for ( $i = 0; $i < 12; $i++ ) :
				for ( $j = 15; $j <= 60; $j += 15 ) :
					$duration = ( $i * 3600 ) + ( $j * 60 );
					$duration_output = ultimate_booking_pro_duration_to_string( $duration );
					$times[$duration] = $duration_output;
				endfor;
			endfor;
			$staff_plural     = ultimate_booking_pro_cs_get_option( 'plural-staff-text', esc_html__('Staffs', 'wdt-ultimate-booking') );
			$room_singular = ultimate_booking_pro_cs_get_option( 'singular-room-text', esc_html__('Room', 'wdt-ultimate-booking') );
			$symbol = ultimate_booking_pro_get_currency_symbol();
			$options[]    = array(
			  'id'        => '_custom_settings',
			  'title'     => esc_html__('Custom Room Options', 'wdt-ultimate-booking'),
			  'post_type' => 'dt_room',
			  'context'   => 'normal',
			  'priority'  => 'default',
			  'sections'  => array(

				array(
				  'name'  => 'gallery_section',
				  'title' => esc_html__('Gallery Options', 'wdt-ultimate-booking'),
				  'icon'  => 'fa fa-picture-o',
				  'fields' => array(
					array(
					  'id'          => 'room-gallery',
					  'type'        => 'gallery',
					  'title'       => esc_html__('Gallery Images', 'wdt-ultimate-booking'),
					  'desc'        => esc_html__('Simply add images to gallery items.', 'wdt-ultimate-booking'),
					  'add_title'   => esc_html__('Add Images', 'wdt-ultimate-booking'),
					  'edit_title'  => esc_html__('Edit Images', 'wdt-ultimate-booking'),
					  'clear_title' => esc_html__('Remove Images', 'wdt-ultimate-booking')
					),
				  ), // end: fields
				), // end: a section
				array(
				  'name'  => 'mand_section',
				  'title' => esc_html__('Mandatory Fields', 'wdt-ultimate-booking'),
				  'icon'  => 'fa fa-clock-o',
				  'fields' => array(
					array(
						'id'      => 'room-status',
						'type'    => 'switcher',
						'title'   => esc_html__('Room status', 'wdt-ultimate-booking'),
						'desc'    => '<p class="cs-text-muted">'.esc_html__('switch to YES if room is available.', 'wdt-ultimate-booking').'</p>',
						'attributes' => array(
						  'style'    => 'width: 90px;'
						),
						'default' => true,
					  ),
					array(
					  'id'      => 'room-price',
					  'type'    => 'number',
					  'title'   => esc_html__('Room Price', 'wdt-ultimate-booking'),
					  'after'	=> '&nbsp;'.$symbol,
					  'desc'    => '<p class="cs-text-muted">'.esc_html__('Enter the room price per night for the minimum number of people. Additional guests will be charged by below price oprion.', 'wdt-ultimate-booking').'</p>',
					  'attributes' => array(
						'style'    => 'width: 90px;'
					  )
					),
					array(
						'id'      => 'room-size',
						'type'    => 'text',
						'title'   => esc_html__('Room size', 'wdt-ultimate-booking'),
						'desc'    => '<p class="cs-text-muted">'.esc_html__('Enter room size here. For Example: 220 sq.ft', 'wdt-ultimate-booking').'</p>',
						'attributes' => array(
						  'style'    => 'width: 90px;'
						)
					),
					array(
						'id'      => 'room-min-people',
						'type'    => 'number',
						'title'   => esc_html__('Room Minimum People', 'wdt-ultimate-booking'),
						'desc'    => '<p class="cs-text-muted">'.esc_html__('Enter Minimum number of people can stay in this room.', 'wdt-ultimate-booking').'</p>',
						'attributes' => array(
						  'style'    => 'width: 90px;'
						)
					),
					array(
						'id'      => 'room-max-people',
						'type'    => 'number',
						'title'   => esc_html__('Room Maximum People', 'wdt-ultimate-booking'),
						'desc'    => '<p class="cs-text-muted">'.esc_html__('Enter Maximum number of people can stay in this room.', 'wdt-ultimate-booking').'</p>',
						'attributes' => array(
						  'style'    => 'width: 90px;'
						)
					),
					array(
						'id'      => 'room-adult-price',
						'type'    => 'number',
						'title'   => esc_html__('Additional Adult Room Price', 'wdt-ultimate-booking'),
						'after'	=> '&nbsp;'.$symbol,
						'desc'    => '<p class="cs-text-muted">'.esc_html__('Enter room price per night for adult', 'wdt-ultimate-booking').'</p>',
						'attributes' => array(
						  'style'    => 'width: 90px;'
						)
					),
					array(
					'id'      => 'room-children-price',
					'type'    => 'number',
					'title'   => esc_html__('Additional Children Room Price', 'wdt-ultimate-booking'),
					'after'	=> '&nbsp;'.$symbol,
					'desc'    => '<p class="cs-text-muted">'.esc_html__('Enter room price per night for children.', 'wdt-ultimate-booking').'</p>',
					'attributes' => array(
						'style'    => 'width: 90px;'
					)
					),
					array(
						'id'      => 'room-duration-from',
						'type'    => 'select',
						'title'   => esc_html__('Duration From', 'wdt-ultimate-booking'),
						'after'   => '<p class="cs-text-muted">'.esc_html__('Select From time duration here', 'wdt-ultimate-booking').'</p>',
						'options' => $times,
						'class'   => 'chosen'
					  ),
					array(
					  'id'      => 'room-duration',
					  'type'    => 'select',
					  'title'   => esc_html__('Duration To', 'wdt-ultimate-booking'),
					  'after'   => '<p class="cs-text-muted">'.esc_html__('Select To time duration here', 'wdt-ultimate-booking').'</p>',
					  'options' => $times,
					  'class'   => 'chosen'
					),
				  ), // end: fields
				), // end: a section
				array(
					'name'  => 'optional_services_section',
					'title' => esc_html__('Service Settings', 'wdt-ultimate-booking'),
					'icon'  => 'fa fa-plug',
					'fields' => array(
						
						// Service 1 Fields
						array(
							'id'       => 'room_service_name_1',
							'type'     => 'text',
							'title'    => esc_html__('Service 1 Name', 'wdt-ultimate-booking'),
							'desc'     => esc_html__('Enter the name of the service offered.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_type_1',
							'type'     => 'select',
							'title'    => esc_html__('Service 1 Type', 'wdt-ultimate-booking'),
							'options'  => array(
								'optional'  => esc_html__('Optional', 'wdt-ultimate-booking'),
								'mandatory' => esc_html__('Mandatory', 'wdt-ultimate-booking'),
							),
						),
						array(
							'id'       => 'room_service_pack_1',
							'type'     => 'select',
							'title'    => esc_html__('Service 1 Pack', 'wdt-ultimate-booking'),
							'options'  => array(
								'free'         => esc_html__('Free', 'wdt-ultimate-booking'),
								'price-per-person' => esc_html__('Price per Person', 'wdt-ultimate-booking'),
								'add-price'    => esc_html__('Add Price', 'wdt-ultimate-booking'),
							),
							'desc'     => esc_html__('Select how the extra service will be calculated.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_price_1',
							'type'     => 'number',
							'title'    => esc_html__('Service 1 Price', 'wdt-ultimate-booking'),
							'after'    => '&nbsp;' . $symbol,
							'desc'     => esc_html__('Enter the price for the service. If Service Pack is Free enter price as "0".', 'wdt-ultimate-booking'),
							'attributes' => array(
								'style' => 'width: 90px;'
							)
						),
						
				
						// Service 2 Fields
						array(
							'id'       => 'room_service_name_2',
							'type'     => 'text',
							'title'    => esc_html__('Service 2 Name', 'wdt-ultimate-booking'),
							'desc'     => esc_html__('Enter the name of the service offered.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_type_2',
							'type'     => 'select',
							'title'    => esc_html__('Service 2 Type', 'wdt-ultimate-booking'),
							'options'  => array(
								'optional'  => esc_html__('Optional', 'wdt-ultimate-booking'),
								'mandatory' => esc_html__('Mandatory', 'wdt-ultimate-booking'),
							),
						),
						array(
							'id'       => 'room_service_pack_2',
							'type'     => 'select',
							'title'    => esc_html__('Service 2 Pack', 'wdt-ultimate-booking'),
							'options'  => array(
								'free'         => esc_html__('Free', 'wdt-ultimate-booking'),
								'price-per-person' => esc_html__('Price per Person', 'wdt-ultimate-booking'),
								'add-price'    => esc_html__('Add Price', 'wdt-ultimate-booking'),
							),
							'desc'     => esc_html__('Select how the extra service will be calculated.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_price_2',
							'type'     => 'number',
							'title'    => esc_html__('Service 2 Price', 'wdt-ultimate-booking'),
							'after'    => '&nbsp;' . $symbol,
							'desc'     => esc_html__('Enter the price for the service. If Service Pack is Free enter price as "0".', 'wdt-ultimate-booking'),
							'attributes' => array(
								'style' => 'width: 90px;'
							)
						),
						
				
						// Service 3 Fields
						array(
							'id'       => 'room_service_name_3',
							'type'     => 'text',
							'title'    => esc_html__('Service 3 Name', 'wdt-ultimate-booking'),
							'desc'     => esc_html__('Enter the name of the service offered.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_type_3',
							'type'     => 'select',
							'title'    => esc_html__('Service 3 Type', 'wdt-ultimate-booking'),
							'options'  => array(
								'optional'  => esc_html__('Optional', 'wdt-ultimate-booking'),
								'mandatory' => esc_html__('Mandatory', 'wdt-ultimate-booking'),
							),
						),
						array(
							'id'       => 'room_service_pack_3',
							'type'     => 'select',
							'title'    => esc_html__('Service 3 Pack', 'wdt-ultimate-booking'),
							'options'  => array(
								'free'         => esc_html__('Free', 'wdt-ultimate-booking'),
								'price-per-person' => esc_html__('Price per Person', 'wdt-ultimate-booking'),
								'add-price'    => esc_html__('Add Price', 'wdt-ultimate-booking'),
							),
							'desc'     => esc_html__('Select how the extra service will be calculated.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_price_3',
							'type'     => 'number',
							'title'    => esc_html__('Service 3 Price', 'wdt-ultimate-booking'),
							'after'    => '&nbsp;' . $symbol,
							'desc'     => esc_html__('Enter the price for the service. If Service Pack is Free enter price as "0".', 'wdt-ultimate-booking'),
							'attributes' => array(
								'style' => 'width: 90px;'
							)
						),
						
				
						// Service 4 Fields
						array(
							'id'       => 'room_service_name_4',
							'type'     => 'text',
							'title'    => esc_html__('Service 4 Name', 'wdt-ultimate-booking'),
							'desc'     => esc_html__('Enter the name of the service offered.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_type_4',
							'type'     => 'select',
							'title'    => esc_html__('Service 4 Type', 'wdt-ultimate-booking'),
							'options'  => array(
								'optional'  => esc_html__('Optional', 'wdt-ultimate-booking'),
								'mandatory' => esc_html__('Mandatory', 'wdt-ultimate-booking'),
							),
						),
						array(
							'id'       => 'room_service_pack_4',
							'type'     => 'select',
							'title'    => esc_html__('Service 4 Pack', 'wdt-ultimate-booking'),
							'options'  => array(
								'free'         => esc_html__('Free', 'wdt-ultimate-booking'),
								'price-per-person' => esc_html__('Price per Person', 'wdt-ultimate-booking'),
								'add-price'    => esc_html__('Add Price', 'wdt-ultimate-booking'),
							),
							'desc'     => esc_html__('Select how the extra service will be calculated.', 'wdt-ultimate-booking'),
						),
						array(
							'id'       => 'room_service_price_4',
							'type'     => 'number',
							'title'    => esc_html__('Service 4 Price', 'wdt-ultimate-booking'),
							'after'    => '&nbsp;' . $symbol,
							'desc'     => esc_html__('Enter the price for the service. If Service Pack is Free enter price as "0".', 'wdt-ultimate-booking'),
							'attributes' => array(
								'style' => 'width: 90px;'
							)
						),
						
					),
				),
				
			  ),
			);
			
			// Filter to add additional options for themes
			$options = apply_filters( 'ultimate_booking_pro_template_metabox_options', $options );
			return $options;
		}

		/**
		 *
		 * @param unknown $columns
		 * @param unknown $id
		 */
		function dt_room_columns_display($columns, $id) {
			global $post;

			$room_settings = get_post_meta ( $post->ID, '_custom_settings', TRUE );
			$room_settings = is_array ( $room_settings ) ? $room_settings : array ();

			switch ($columns) {

				case "dt_room_thumb" :
				    $image = wp_get_attachment_image(get_post_thumbnail_id($id), array(75,75));
					if(!empty($image)):
					  	echo "{$image}";
				    else:
						if( array_key_exists("room-gallery", $room_settings)) {
							$items = explode(',', $room_settings["room-gallery"]);
							echo wp_get_attachment_image( $items[0], array(75, 75) );
						}
					endif;
				break;

				case "cost" :
					if( array_key_exists("room-price", $room_settings) && $room_settings['room-price'] != '' ) {
						echo ultimate_booking_pro_get_currency_symbol().floatval( $room_settings['room-price'] );
					}
				break;

				case "duration" :
					if( array_key_exists("room-duration", $room_settings) && $room_settings['room-duration'] != '' ) {
						echo ultimate_booking_pro_duration_to_string( $room_settings['room-duration'] );
					}
				break;
			}
		}
	}
}