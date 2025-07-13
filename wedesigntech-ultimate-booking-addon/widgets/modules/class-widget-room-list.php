<?php
use UltimateBookingPro\Widgets\UltimateBookingProWidgetBase;
use Elementor\Controls_Manager;
use Elementor\Utils;
use UltimateBookingPro\widgets\WeDesignTech_Common_Controls_Layout_Booking;

class Elementor_Room_List extends UltimateBookingProWidgetBase {

	private $layout_type = 'both';
    private $cc_layout;
    private $cc_style;
	private $settings = array ();

    public function get_name() {
        return 'dt-room-list';
    }

    public function get_title() {
        return $this->get_singular_name().esc_html__(' List', 'wdt-ultimate-booking');
    }

    public function get_icon() {
		return 'eicon-apps';
	}

    public function get_style_depends() {
        wp_enqueue_style('booking-rooms', ULTIMATEBOOKINGPRO_URL . '/widgets/assets/css/rooms.css');
		return array(
			$this->get_name() => 'booking-rooms'
		);
    }
	
    public function get_singular_name() {

        $singular_name = esc_html__('Room', 'wdt-ultimate-booking');

        if( function_exists( 'ultimate_booking_pro_cs_get_option' ) ) :
            $singular_name = ultimate_booking_pro_cs_get_option( 'singular-room-text', esc_html__('Room', 'wdt-ultimate-booking') );
        endif;

        return $singular_name;
    }

    protected function register_controls() {

        $this->start_controls_section( 'dt_section_general', array(
            'label' => esc_html__( 'General', 'wdt-ultimate-booking'),
        ) );

            $this->add_control( 'terms', array(
                'label' => esc_html__( 'Terms', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $this->dt_room_categories()
            ) );

            $this->add_control( 'posts_per_page', array(
                'label' => esc_html__( 'Rooms Per Page', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
            ));

            $this->add_control( 'orderby', array(
                'label' => esc_html__( 'Order by', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'ID',
                'options' => array(
                    'ID' => esc_html__( 'ID', 'wdt-ultimate-booking' ),
                    'title' => esc_html__( 'Title', 'wdt-ultimate-booking' ),
                    'name' => esc_html__( 'Name', 'wdt-ultimate-booking' ),
                    'type' => esc_html__( 'Type', 'wdt-ultimate-booking' ),
                    'date' => esc_html__( 'Date', 'wdt-ultimate-booking' ),
                    'rand' => esc_html__( 'Random', 'wdt-ultimate-booking' ),
                )
            ));

            $this->add_control( 'order', array(
                'label' => esc_html__( 'Sort order', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => array(
                    'desc' => esc_html__( 'Descending', 'wdt-ultimate-booking' ),
                    'asc' => esc_html__( 'Ascending', 'wdt-ultimate-booking' ),
                )
            ));

			$this->add_control( 'product_price', array(
                'label' => esc_html__( 'Show Price?', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                    'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
                )
            ));

			$this->add_control( 'excerpt', array(
                'label' => esc_html__( 'Show Excerpt?', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                    'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
                )
            ));

			$this->add_control( 'amenity', array(
                'label' => esc_html__( 'Show amenity?', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                    'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
                )
            ));

			$this->add_control( 'room_meta', array(
                'label' => esc_html__( 'Show Additional fields?', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                    'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
                )
            ));

			$this->add_control( 'excerpt_length', array(
                'label' => esc_html__( 'Excerpt Length', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 12,
            ));

			$this->add_control(	'el_class', array(
				'type' => Controls_Manager::TEXT,
				'label'       => esc_html__('Extra class name', 'wdt-ultimate-booking'),
				'description' => esc_html__('Style particular element differently - add a class name and refer to it in custom CSS', 'wdt-ultimate-booking')
			) );

        $this->end_controls_section();

		$common_controls = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type);
        $common_controls->get_controls($this);
    }

    protected function render() {

        $settings = $this->get_settings();
        extract( $settings );

		$out = '';

		$classes = array ();
		array_push($classes, 'dt-rooms-list');
        $settings['classes'] = $classes;
        $settings['module_id'] = $this->get_id();
        $settings['module_class'] = 'rooms-item';

		$layout_settings = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type, $settings);

        $layout_settings->set_settings($settings);
        
        $settings['module_layout_class'] = $layout_settings->get_item_class($settings['layout']);

		$categories = !empty($terms) ? $terms : array();

		$query_args = array();
		if( empty($categories) ):
			$query_args = array( 'posts_per_page' => $posts_per_page, 'orderby' => $orderby, 'order' => $order, 'post_status' => 'publish', 'post_type' => 'dt_room');
		else:
			$query_args = array(
				'post_type'           => 'dt_room',
				'post_status'         => 'publish',
				'posts_per_page'      => $posts_per_page,
				'orderby'             => $orderby,
				'order'               => $order,
				'tax_query' => array(
					array(
						'taxonomy' => 'dt_room_category',
						'field' => 'term_id',
						'operator' => 'IN',
						'terms' => $categories
					)
				)
			);
		endif;
		
		$current_permalink = get_permalink();
		$current_title = get_the_title();

		$out .= $layout_settings->get_wrapper_start();
			$the_query = new WP_Query($query_args);
			if ( $the_query->have_posts() ) :

				while ( $the_query->have_posts() ) : $the_query->the_post();
					$PID = get_the_ID();

					// Get Amenities
					$amenities = wp_get_post_terms($PID, 'dt_room_amenity');

					$active_class = '';
					
					if ( get_permalink($PID) == $current_permalink && get_the_title($PID) == $current_title) {
						$active_class = ' dt-room-active';
					}
					#Meta...
					$room_settings = get_post_meta($PID, '_custom_settings', true);
					$room_settings = is_array ( $room_settings ) ? $room_settings : array ();

					if($settings['module_layout_class'] != '') {
						$out.= '<div class="dt-sc-room-item '.esc_attr($settings['module_layout_class']).'">';
					}
						$out .= '<div class="dt-sc-content-item dt-e-room-item">';
						
							$out .= '<div class="dt-sc-content-media-group">';
								$out .= '<div class="dt-sc-content-elements-group">';
								
									$out .= '<div class="dt-sc-room-list-image">';
										if(has_post_thumbnail()):
											$attr = array('title' => get_the_title(), 'alt' => get_the_title());
											$out .= get_the_post_thumbnail($PID, 'full', $attr);
										else:
											$out .= '<img src="https://place-hold.it/1200x800&text='.get_the_title().'" alt="'.get_the_title().'" />';
										endif;
									$out .= '</div>';

									if( $product_price == 'yes' && array_key_exists('room-price', $room_settings) && $room_settings['room-price'] != '' ):
										$out .= '<div class="dt-sc-room-price-item">';
											$out .= '<span class="dt-sc-room-price">'.ultimate_booking_pro_get_currency_symbol().$room_settings['room-price'].esc_html__(' / Night ', 'wdt-ultimate-booking').'</span>';
										$out .= '</div>';
									endif;
									
								$out .= '</div>';
							$out .= '</div>';

							$out .= '<div class="dt-sc-content-detail-group room-details">';

								$out .= '<div class="dt-sc-content-title">';
									$out .= '<h4 class="dt-sc-room-title">';
										$out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
									$out .= '</h4>';
								$out .= '</div>';

								if( $excerpt == 'yes' && $excerpt_length > 0 ):
									$out .= '<div class="dt-sc-content-description">'.ultimate_booking_pro_post_excerpt($excerpt_length).'</div>';
								endif;

								if(!empty($amenities) && $amenity == 'yes' ):
									$out .= '<div class="dt-sc-room-amenities">';
										$out .= '<ul>';
										foreach ($amenities as $amenity):
											$amenity_icon = get_term_meta($amenity->term_id, 'dt-taxonomy-icon', true);
											$out .= '<li>';
											if (!empty($amenity_icon)) {
												$out .= '<i class="'.esc_attr($amenity_icon).'"></i>';
											}
											$out .= '<span class="dt-sc-room-amenity-text">'.esc_html($amenity->name).'</span>';
											$out .= '</li>';
										endforeach;
										$out .= '</ul>';
									$out .= '</div>';
								endif;

								if($room_meta == 'yes' ):
									$out .= '<div class="dt-sc-rooms-meta-wrapper">';
										if( array_key_exists('room-size', $room_settings) && $room_settings['room-size'] != '' ):
											$out .= '<div class="dt-sc-rooms-size-meta-wrapper">';
												$out .= '<span class="dt-sc-rooms-icon">';
													$out .= '<i class="icon-dt-sq-fit"></i>';
												$out .= '</span>';
												$out .= '<span class="dt-sc-rooms-size">'.esc_attr( $room_settings['room-size'] ).'</span>';
											$out .= '</div>';
										endif;
										if( array_key_exists('room-max-people', $room_settings) && $room_settings['room-max-people'] != '' ):
											$out .= '<div class="dt-sc-rooms-people-meta-wrapper">';
												$out .= '<span class="dt-sc-rooms-icon">';
													$out .= '<i class="icon-dt-guests"></i>';
												$out .= '</span>';
												$out .= '<span class="dt-sc-rooms-people">'.esc_attr( $room_settings['room-max-people'] ). esc_html__(' Guests', 'wdt-ultimate-booking') .'</span>';
											$out .= '</div>';
										endif;
										if( array_key_exists('room-duration', $room_settings) && $room_settings['room-duration'] != '' ):
											$out .= '<div class="dt-sc-rooms-duration-meta-wrapper">';
												$out .= '<span class="dt-sc-rooms-icon">';
													$out .= '<i class="icon-dt-clock-alt"></i>';
												$out .= '</span>';
												$out .= '<span class="dt-sc-rooms-duration">'.ultimate_booking_pro_duration_to_string( $room_settings['room-duration'] ).'</span>';
											$out .= '</div>';
										endif;
									$out .= '</div>';
								endif;

								$out .= '<div class="dt-sc-content-button">';
									$out .= '<a class="dt-sc-button dt-sc-button-textual" href="'.get_permalink().'" title="'.get_the_title().'">'.esc_html__("Read More", "wdt-ultimate-booking").'</a>';
								$out .= '</div>';

							$out .= '</div>';

						$out .= '</div>';
					if($settings['module_layout_class'] != '') {
						$out .= '</div>';
					}

				endwhile;
				
				$column_css = $layout_settings->get_column_css();

				$this->get_style_depends($column_css);
				
				if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
					$layout_settings->get_column_edit_mode_css($column_css);
					$out .= $layout_settings->get_column_edit_mode_css($column_css);
				} else {
					wp_add_inline_style('booking-rooms', $column_css);
				}

				wp_reset_postdata();

			else:
				$out .= '<div class="dt-sc-item-not-found">';
                    $out .= '<h2>'.esc_html__("Nothing Found.", "wdt-ultimate-booking").'</h2>';
                    $out .= '<p>'.esc_html__("Apologies, but no results were found for the requested archive.", "wdt-ultimate-booking").'</p>';
                $out .= '</div>';
			endif;
		$out .= $layout_settings->get_wrapper_end();

		echo "{$out}";
    }

    protected function _content_template() {
    }
}