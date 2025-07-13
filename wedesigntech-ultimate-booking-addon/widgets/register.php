<?php
namespace UltimateBookingPro;
namespace UltimateBookingPro\widgets;

class WeDesignTech_Common_Controls_Layout_Booking {

	private $layout_type;
	private $cc_style;
	private $settings = array ();

    function __construct($layout_type) {
		$this->layout_type = $layout_type;
    }

	public function init_styles() {
		$layout_styles = array ();
		if(\Elementor\Plugin::$instance->preview->is_preview_mode() || (array_key_exists('layout', $this->settings) && $this->settings['layout'] == 'column')) {
			$layout_styles['dt-column'] =  plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/column.css';
		}

		if(\Elementor\Plugin::$instance->preview->is_preview_mode() || (array_key_exists('layout', $this->settings) && $this->settings['layout'] == 'carousel')) {
			$layout_styles['dt-swiper'] =  plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/swiper.min.css';
			$layout_styles['dt-carousel'] =  plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/carousel.css';
		}

		return $layout_styles;
	}

	public function init_scripts() {
		$layout_scripts = array ();

		if(\Elementor\Plugin::$instance->preview->is_preview_mode() || (array_key_exists('layout', $this->settings) && $this->settings['layout'] == 'carousel')) {
			$layout_scripts['dt-jquery-swiper'] = plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/swiper.min.js';
			$layout_scripts['dt-jquery-carousel'] = plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/js/carousel.js';
		}

		return $layout_scripts;
	}

	public function set_settings($settings) {
		$this->settings = $settings;

		if($this->layout_type == 'carousel') {
			$this->settings['layout'] = 'carousel';
		} else if($this->layout_type == 'column') {
			$this->settings['layout'] = 'column';
		}
	}

	public function get_controls($elementor_object) {

		if($this->layout_type == 'both' || $this->layout_type == 'column') {
			$elementor_object->start_controls_section( 'dt_section_layout', array(
				'label' => esc_html__( 'Layout', 'wdt-ultimate-booking')
			) );
		}

			$column_condition = array();
			$carousel_condition = array();

			if($this->layout_type == 'both') {

				$column_condition = array( 'layout' => 'column' );
				$carousel_condition = array( 'layout' => 'carousel' );

				$elementor_object->add_control(
					'layout',
					array (
						'label' => esc_html__( 'Layout', 'wdt-ultimate-booking' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => array (
							'default' => esc_html__( 'Default', 'wdt-ultimate-booking' ),
							'column' => esc_html__( 'Column', 'wdt-ultimate-booking' ),
							'carousel' => esc_html__( 'Carousel', 'wdt-ultimate-booking' )
						),
						'default' => 'column'
					)
				);

			}

				if($this->layout_type == 'both' || $this->layout_type == 'column') {
					$this->get_column_controls($elementor_object, $column_condition);
				}

		if($this->layout_type == 'both' || $this->layout_type == 'column') {
			$elementor_object->end_controls_section();
		}

		if($this->layout_type == 'both' || $this->layout_type == 'carousel') {
			$this->get_carousel_controls($elementor_object, $carousel_condition);
		}

	}

	public function get_column_controls($elementor_object, $column_condition) {

		$columns = range( 1, 6 );
		$columns = array_combine( $columns, $columns );

		$elementor_object->add_responsive_control( 'columns', array(
			'type' => \Elementor\Controls_Manager::SELECT,
			'label' => esc_html__( 'Columns', 'wdt-ultimate-booking' ),
			'options' => $columns,
			'desktop_default'      => 2,
			'laptop_default'       => 2,
			'tablet_default'       => 2,
			'tablet_extra_default' => 2,
			'mobile_default'       => 1,
			'mobile_extra_default' => 1,
			'frontend_available'   => true,
			'condition'   		   => $column_condition
		) );

		$elementor_object->add_control(
			'column_gap',
			array(
				'label' => esc_html__( 'Columns Gap', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'wdt-ultimate-booking' ),
					'no' => esc_html__( 'No Gap', 'wdt-ultimate-booking' ),
					'narrow' => esc_html__( 'Narrow', 'wdt-ultimate-booking' ),
					'extended' => esc_html__( 'Extended', 'wdt-ultimate-booking' ),
					'wide' => esc_html__( 'Wide', 'wdt-ultimate-booking' ),
					'wider' => esc_html__( 'Wider', 'wdt-ultimate-booking' ),
					'custom' => esc_html__( 'Custom', 'wdt-ultimate-booking' ),
				),
				'condition' => $column_condition
			)
		);

		$elementor_object->add_responsive_control(
			'column_gap_custom',
			array(
				'label' => esc_html__( 'Custom Columns Gap', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', '%', 'vh', 'vw' ),
				'selectors' => array(
                    '{{WRAPPER}} .dt-column-gap-custom' => 'margin: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .dt-column-gap-custom .dt-column' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'condition' => array_merge (
					array(
					'column_gap' => 'custom',
					),
					$column_condition
				),
			)
		);

		$elementor_object->add_control(
			'dt_snap_scroll',
			array(
				'label'        => esc_html__( 'Enable Snap Scroll', 'wdt-ultimate-booking' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'wdt-ultimate-booking' ),
				'label_off'    => esc_html__( 'Off', 'wdt-ultimate-booking' ),
				'return_value' => 'yes',
				'condition'    => array( 'layout' => 'column' )
			)
		);

		if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints') ) {
			$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
			$breakpoints_list   = array();

			foreach ($active_breakpoints as $key => $value) {
				$breakpoints_list[$key] = $value->get_label();
			}

			$breakpoints_list['desktop'] = esc_html__( 'Desktop', 'wdt-ultimate-booking' );
			$breakpoints_list            = array_reverse($breakpoints_list);
		} else {
			$breakpoints_list = array(
				'desktop' => esc_html__( 'Desktop', 'wdt-ultimate-booking' ),
				'tablet'  => esc_html__( 'Tablet', 'wdt-ultimate-booking' ),
				'mobile'  => esc_html__( 'Mobile', 'wdt-ultimate-booking' )
			);
		}

		$elementor_object->add_control(
			'dt_snap_scroll_on_devices',
			array(
				'label'       => esc_html__( 'Snap Scroll On Devices', 'wdt-ultimate-booking' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => 'true',
				'default'     => array(
					'tablet',
					'mobile',
				),
				'condition' => array( 'dt_snap_scroll' => 'yes', 'layout' => 'column' ),
				'options' => $breakpoints_list,
			)
		);

	}

	public function get_carousel_controls($elementor_object, $condition) {

		$elementor_object->start_controls_section( 'dt_section_carousel', array(
			'label' => esc_html__( 'Carousel Options', 'wdt-ultimate-booking'),
			'condition' => $condition
		) );

			$elementor_object->add_control( 'direction', array(
				'label' => esc_html__( 'Direction', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'wdt-ultimate-booking' ),
					'vertical' => esc_html__( 'Vertical', 'wdt-ultimate-booking' ),
				),
				'frontend_available' => true
			));

            $elementor_object->add_control( 'effect', array(
				'label' => esc_html__( 'Effect', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'   => esc_html__( 'Default', 'wdt-ultimate-booking' ),
					'fade' 	    => esc_html__( 'Fade', 'wdt-ultimate-booking' ),
					'cube' 	    => esc_html__( 'Cube', 'wdt-ultimate-booking' ),
					'coverflow' => esc_html__( 'Coverflow', 'wdt-ultimate-booking' ),
					'free_mode'	=> esc_html__( 'Free Mode', 'wdt-ultimate-booking' )
				),
				'frontend_available' => true
			));

			/* $elementor_object->add_control( 'effect_type', array(
				'label' => esc_html__( 'Creative Effect Type', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'type_1',
				'options' => array(
					'type_1'   => esc_html__( 'Type 1', 'wdt-ultimate-booking' ),
					'type_2'   => esc_html__( 'Type 2', 'wdt-ultimate-booking' ),
					'type_3'   => esc_html__( 'Type 3', 'wdt-ultimate-booking' ),
					'type_4'   => esc_html__( 'Type 4', 'wdt-ultimate-booking' ),
					'type_5'   => esc_html__( 'Type 5', 'wdt-ultimate-booking' ),
					'type_6'   => esc_html__( 'Type 6', 'wdt-ultimate-booking' )
				),
				'condition' => array( 'effect' => 'creative' ),
				'frontend_available' => true
			)); */

			$slides_per_view = range( 1, 8 );
			$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

			$elementor_object->add_responsive_control( 'slides_to_show_opts', array(
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Slides to Show', 'wdt-ultimate-booking' ),
				'options' => $slides_per_view,
				'desktop_default'      => 4,
				'laptop_default'       => 4,
				'tablet_default'       => 2,
				'tablet_extra_default' => 2,
				'mobile_default'       => 1,
				'mobile_extra_default' => 1,
				'frontend_available'   => true,
				'condition'   		   => array( 'direction' => 'horizontal' )
			) );

			$elementor_object->add_control( 'slides_to_scroll_opts', array(
				'label'              => esc_html__( 'Slides to Scroll', 'wdt-ultimate-booking' ),
				'type'               => \Elementor\Controls_Manager::SELECT,
				'default'            => 'single',
				'frontend_available' => true,
				'options'            => array(
					'all'    => esc_html__( 'All visible', 'wdt-ultimate-booking' ),
					'single' => esc_html__( 'One at a Time', 'wdt-ultimate-booking' ),
				),
				'condition' => array( 'direction' => 'horizontal' )
			) );

			$elementor_object->add_control( 'marquee_class', array(
				'label' => esc_html__( 'Marquee', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'frontend_available' => true
			) );

			$elementor_object->add_control( 'mouse_wheel_scroll', array(
				'label' => esc_html__( 'Mouse Wheel Scroll', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'false',
				'frontend_available' => true
			) );

			$elementor_object->add_control( 'pagination', array(
				'label' => esc_html__( 'Pagination', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => array(
					'' => esc_html__( 'None', 'wdt-ultimate-booking' ),
					'bullets' => esc_html__( 'Dots', 'wdt-ultimate-booking' ),
					'fraction' => esc_html__( 'Fraction', 'wdt-ultimate-booking' ),
					'progressbar' => esc_html__( 'Progress', 'wdt-ultimate-booking' ),
					'scrollbar' => esc_html__( 'Scrollbar', 'wdt-ultimate-booking' ),
				),
				'frontend_available' => true
			));

			$elementor_object->add_control( 'arrows', array(
				'label' => esc_html__( 'Arrows', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			) );

			$elementor_object->add_control(
				'arrows_prev_icon',
				array (
					'label' => esc_html__( 'Arrow Prev Icon', 'wdt-ultimate-booking' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'label_block' => false,
					'skin' => 'inline',
					'default' => array( 'value' => 'fas fa-arrow-left', 'library' => 'fa-solid', ),
					'condition' => array( 'arrows' => 'yes' )
				)
			);

			$elementor_object->add_control(
				'arrows_next_icon',
				array (
					'label' => esc_html__( 'Arrow Next Icon', 'wdt-ultimate-booking' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'label_block' => false,
					'skin' => 'inline',
					'default' => array( 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid', ),
					'condition' => array( 'arrows' => 'yes' )
				)
			);

			$elementor_object->add_control( 'speed', array(
				'label' => esc_html__( 'Transition Duration', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 300,
				'frontend_available' => true
			));

			$elementor_object->add_control( 'unequal_height_compatability', array(
				'label' => esc_html__( 'Unequal Height Compatability', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return' => 'yes',
				'default' => 'no',
				'condition' => array(
					'direction' => 'vertical'
				),
				'frontend_available' => true
			));

            $elementor_object->add_responsive_control(
                'gap',
                array (
                    'label' => esc_html__( 'Gap', 'wdt-ultimate-booking' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'default' => array (
                        'size' => 20,
                        'unit' => 'dpt',
                    ),
                    'size_units' => array( 'dpt' ),
                    'range' => array (
                        'dpt' => array(
                            'min' => 0,
                            'step' => 1,
                            'max' => 100
                        )
                    ),
                    'frontend_available' => true,
                    'condition' => array( 'direction' => 'horizontal' )
                )
            );

			$elementor_object->add_control( 'autoplay', array(
				'label' => esc_html__( 'Autoplay', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before',
				'frontend_available' => true
			));

			$elementor_object->add_control( 'autoplay_speed', array(
				'label' => esc_html__( 'Autoplay Speed', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => array(
					'autoplay' => 'yes'
				),
				'frontend_available' => true
			));

			$elementor_object->add_control( 'autoplay_direction', array(
				'label' => esc_html__( 'Autoplay Direction', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'' => esc_html__( 'None', 'wdt-ultimate-booking' ),
					'left' => esc_html__( 'Left', 'wdt-ultimate-booking' ),
					'right' => esc_html__( 'Right', 'wdt-ultimate-booking' ),
				),
				'condition' => array(
				'autoplay' => 'yes'
				),
				'frontend_available' => true
			));

			$elementor_object->add_control( 'allow_touch', array(
				'label' => esc_html__( 'Allow Touch', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			));
			$elementor_object->add_control( 'loop', array(
				'label' => esc_html__( 'Infinite Loop', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' =>'Not works for Dot Pagination',
				'frontend_available' => true,
				// 'condition' => array(
				// 	'pagination!' => 'bullets'
				// ),
			));
			$elementor_object->add_control( 'centered_slides', array(
				'label' => esc_html__( 'Centered Slides', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				// 'condition' => array(
				// 	'pagination!' => 'bullets'
				// ),
				'frontend_available' => true
			));

			$elementor_object->add_control( 'pause_on_interaction', array(
				'label' => esc_html__( 'Pause on Interaction', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'autoplay' => 'yes'
				),
				'frontend_available' => true
			));
			$elementor_object->add_control( 'overflow_opacity', array(
				'label' => esc_html__( 'Overflow Opacity', 'wdt-ultimate-booking' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			) );

        $elementor_object->end_controls_section();

	}

	public function get_wrapper_start() {

		extract($this->settings);

		$wrapper_start = '';

		$classes_str = '';
		if(isset($classes)) {
			$classes_str = implode(' ', $classes);
		}

		$custom_attributes_str = '';
        if(isset($this->settings['custom_attributes']) && !empty($this->settings['custom_attributes'])) {
            $custom_attributes_str = wp_json_encode($this->settings['custom_attributes']);
        }
		
		$marquee_class_holder = '';
		if($marquee_class == 'yes'){
			$marquee_class_holder = 'dt-marquee-wrapper';
		}

		if($layout == 'carousel') {

			$settings_attr = $this->get_carousel_attributes();

			$wrapper_start .= '<div class="dt-'.esc_attr($module_class).'-holder '.esc_attr($marquee_class_holder).' dt-content-item-holder dt-carousel-holder '.esc_attr($classes_str).'" id="dt-'.esc_attr($module_class).'-'.esc_attr($module_id).'" data-id="'.esc_attr($module_id).'" data-settings="'.esc_js($custom_attributes_str).'">';
				$wrapper_start .= '<div class="dt-'.esc_attr($module_class).'-container swiper" data-settings="'.esc_js($settings_attr).'" id="dt-'.esc_attr($module_class).'-swiper-'.esc_attr($module_id).'">';
					$wrapper_start .= '<div class="dt-'.esc_attr($module_class).'-wrapper swiper-wrapper">';

		} else if($layout == 'column') {

			$dt_snap_scroll = $json_column_settings = '';
			if( $this->settings['dt_snap_scroll'] ) {
				$dt_snap_scroll = 'dt-snap-scroll-enabled';

				$json_column_settings = wp_json_encode(array (
					'columnDevices'    => $this->settings['dt_snap_scroll_on_devices']
				));
			}

			$wrapper_start .= '<div class="dt-'.esc_attr($module_class).'-holder dt-content-item-holder dt-column-holder '.esc_attr($classes_str).'" id="dt-'.esc_attr($module_class).'-'.esc_attr($module_id).'" data-settings="'.esc_js($custom_attributes_str).'">';
				$wrapper_start .= '<div class="dt-column-wrapper dt-column-gap-'.esc_attr($column_gap).' '.$dt_snap_scroll.'" data-column-settings="'.esc_js($json_column_settings).'" data-module-id="dt-module-id-'.esc_attr($module_id).'" id="dt-module-id-'.esc_attr($module_id).'">';

		} else {

			$wrapper_start .= '<div class="dt-'.esc_attr($module_class).'-holder dt-content-item-holder dt-column-holder '.esc_attr($classes_str).'" id="dt-'.esc_attr($module_class).'-'.esc_attr($module_id).'">';

		}

		return $wrapper_start;

	}

	public function get_wrapper_end() {

		extract($this->settings);

		$wrapper_end = '';

		if($layout == 'carousel') {

					$wrapper_end .= '</div>';
				$wrapper_end .= '</div>';
				$wrapper_end .= $this->get_carousel_pagination_html();
			$wrapper_end .= '</div>';

		} else if($layout == 'column') {
				$wrapper_end .= '</div>';
				if( $this->settings['dt_snap_scroll'] ) {
					$wrapper_end .= '<div class="dt-column-pagination dt-snap-scroll-pagination">
						<button class="dt-pagination-prev dt-module-id-'.esc_attr($module_id).'">'.esc_html__('Prev', 'wdt-ultimate-booking').'</button>
						<button class="dt-pagination-next dt-module-id-'.esc_attr($module_id).'">'.esc_html__('Next', 'wdt-ultimate-booking').'</button>';
					$wrapper_end .= '</div>';
				}
			$wrapper_end .= '</div>';
		} else {
			$wrapper_end .= '</div>';
		}

		return $wrapper_end;

	}

	public function get_item_class() {

		extract($this->settings);

		$item_class = '';

		if($layout == 'column') {
			$item_class = 'dt-column';
		} else if($layout == 'carousel') {
			$item_class = 'swiper-slide';
		}

		return $item_class;

	}

	public function get_column_css() {

		extract($this->settings);


		// if (!isset($this->settings['layout']) || $this->settings['layout'] !== 'column') {
        //     return ''; // Return empty if not in column layout
        // }

		$column_css = '';

		$responsive_breakpoints = array ();

		$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
		$breakpoint_keys = array_keys($active_breakpoints);
		$breakpoint_keys= array_reverse($breakpoint_keys);
		$mobile_breakpoint_value = $active_breakpoints['mobile']->get_value()+1;

		foreach($breakpoint_keys as $breakpoint) {
			if($breakpoint == 'widescreen') {
				$widescreen_breakpoint_value = $active_breakpoints['widescreen']->get_value();
			} else {
				$breakpoint_value = $active_breakpoints[$breakpoint]->get_value();
				$column_str = 'columns_'.$breakpoint;
				$responsive_breakpoints[$breakpoint_value] = $$column_str;
			}
		}

		//for desktop
		$column_css .= "\n".'@media only screen and (min-width: '.$mobile_breakpoint_value.'px) {'."\n";
			$column_css .= '#dt-'.esc_attr($module_class).'-'.esc_attr($module_id).' .dt-column-wrapper:not(.dt-snap-scroll-enabled) .dt-column {'."\n";
				if($columns == 6) {
					$column_css .= 'width: 16.66%;'."\n";
				} else if($columns == 5) {
					$column_css .= 'width: 20%;'."\n";
				} else if($columns == 4) {
					$column_css .= 'width: 25%;'."\n";
				} else if($columns == 3) {
					$column_css .= 'width: 33.33%;'."\n";
				} else if($columns == 2) {
					$column_css .= 'width: 50%;'."\n";
				} else if($columns == 1) {
					$column_css .= 'width: 100%;'."\n";
				}
			$column_css .= '}'."\n";
		$column_css .= '}'."\n";

		// Snap Scroll Responsive
		$column_css .= "\n".'@media only screen and (min-width: '.$mobile_breakpoint_value.'px) {'."\n";
			$column_css .= '#dt-'.esc_attr($module_class).'-'.esc_attr($module_id).' .dt-column-wrapper.dt-snap-scroll-enabled .dt-column {'."\n";
				if($columns == 6) {
					$column_css .= 'flex: 0 0 16.66%;'."\n";
				} else if($columns == 5) {
					$column_css .= 'flex: 0 0 20%;'."\n";
				} else if($columns == 4) {
					$column_css .= 'flex: 0 0 25%;'."\n";
				} else if($columns == 3) {
					$column_css .= 'flex: 0 0 33.33%;'."\n";
				} else if($columns == 2) {
					$column_css .= 'flex: 0 0 50%;'."\n";
				} else if($columns == 1) {
					$column_css .= 'flex: 0 0 100%;'."\n";
				}
			$column_css .= '}'."\n";
		$column_css .= '}'."\n";

		// for widescreen
		if(isset($columns_widescreen) && $columns_widescreen != '') {
            if(isset($widescreen_breakpoint_value)) {
                $column_css .= "\n".'@media only screen and (min-width: '.$widescreen_breakpoint_value.'px) {'."\n";
                    $column_css .= '#dt-'.esc_attr($module_class).'-'.esc_attr($module_id).' .dt-column-wrapper:not(.dt-snap-scroll-enabled) .dt-column {'."\n";
                        if($columns_widescreen == 6) {
                            $column_css .= 'width: 16.66%;'."\n";
                        } else if($columns_widescreen == 5) {
                            $column_css .= 'width: 20%;'."\n";
                        } else if($columns_widescreen == 4) {
                            $column_css .= 'width: 25%;'."\n";
                        } else if($columns_widescreen == 3) {
                            $column_css .= 'width: 33.33%;'."\n";
                        } else if($columns_widescreen == 2) {
                            $column_css .= 'width: 50%;'."\n";
                        } else if($columns_widescreen == 1) {
                            $column_css .= 'width: 100%;'."\n";
                        }
                    $column_css .= '}'."\n";
                $column_css .= '}'."\n";
            }
		}
		// Snap Scroll Responsive
		if(isset($columns_widescreen) && $columns_widescreen != '') {
            if(isset($widescreen_breakpoint_value)) {
                $column_css .= "\n".'@media only screen and (min-width: '.$widescreen_breakpoint_value.'px) {'."\n";
                    $column_css .= '#dt-'.esc_attr($module_class).'-'.esc_attr($module_id).' .dt-column-wrapper.dt-snap-scroll-enabled .dt-column {'."\n";
                        if($columns_widescreen == 6) {
                            $column_css .= 'flex: 0 0 16.66%;'."\n";
                        } else if($columns_widescreen == 5) {
                            $column_css .= 'flex: 0 0 20%;'."\n";
                        } else if($columns_widescreen == 4) {
                            $column_css .= 'flex: 0 0 25%;'."\n";
                        } else if($columns_widescreen == 3) {
                            $column_css .= 'flex: 0 0 33.33%;'."\n";
                        } else if($columns_widescreen == 2) {
                            $column_css .= 'flex: 0 0 50%;'."\n";
                        } else if($columns_widescreen == 1) {
                            $column_css .= 'flex: 0 0 100%;'."\n";
                        }
                    $column_css .= '}'."\n";
                $column_css .= '}'."\n";
            }
		}

		// for other responsive size
		if(is_array($responsive_breakpoints) && !empty($responsive_breakpoints)) {
			foreach($responsive_breakpoints as $key => $responsive_breakpoint) {
				$column_css .= "\n".'@media only screen and (max-width: '.$key.'px) {'."\n";
					$column_css .= '#dt-'.esc_attr($module_class).'-'.esc_attr($module_id).' .dt-column-wrapper:not(.dt-snap-scroll-enabled) .dt-column {'."\n";

						if($responsive_breakpoint == 6) {
							$column_css .= 'width: 16.66%;'."\n";
						} else if($responsive_breakpoint == 5) {
							$column_css .= 'width: 20%;'."\n";
						} else if($responsive_breakpoint == 4) {
							$column_css .= 'width: 25%;'."\n";
						} else if($responsive_breakpoint == 3) {
							$column_css .= 'width: 33.33%;'."\n";
						} else if($responsive_breakpoint == 2) {
							$column_css .= 'width: 50%;'."\n";
						} else {
							$column_css .= 'width: 100%;'."\n";
						}

					$column_css .= '}'."\n";
				$column_css .= '}'."\n";
			}
		}
		// Snap Scroll Responsive
		if(is_array($responsive_breakpoints) && !empty($responsive_breakpoints)) {
			foreach($responsive_breakpoints as $key => $responsive_breakpoint) {
				$column_css .= "\n".'@media only screen and (max-width: '.$key.'px) {'."\n";
					$column_css .= '#dt-'.esc_attr($module_class).'-'.esc_attr($module_id).' .dt-column-wrapper.dt-snap-scroll-enabled .dt-column {'."\n";

						if($responsive_breakpoint == 6) {
							$column_css .= 'flex: 0 0 16.66%;'."\n";
						} else if($responsive_breakpoint == 5) {
							$column_css .= 'flex: 0 0 20%;'."\n";
						} else if($responsive_breakpoint == 4) {
							$column_css .= 'flex: 0 0 25%;'."\n";
						} else if($responsive_breakpoint == 3) {
							$column_css .= 'flex: 0 0 33.33%;'."\n";
						} else if($responsive_breakpoint == 2) {
							$column_css .= 'flex: 0 0 50%;'."\n";
						} else {
							$column_css .= 'flex: 0 0 100%;'."\n";
						}

					$column_css .= '}'."\n";
				$column_css .= '}'."\n";
			}
		}

		return $column_css;

	}

	public function get_column_edit_mode_css() {

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
			return '<style type="text/css">'."\n".$this->get_column_css()."\n".'</style>';
		}
		return '';
	}

	public function get_carousel_attributes() {

		extract($this->settings);

		$slides_to_show_opts_vertical = 1;

		if($direction == 'vertical') {
			$slides_to_show = $slides_to_show_opts_vertical;
		} else {
			$slides_to_show = $slides_to_show_opts;
		}

		if( $slides_to_scroll_opts == 'all' ) {
			$slides_to_scroll = $slides_to_show;
		} else {
			$slides_to_scroll = 1;
		}

		$carousel_settings = array (
			'direction' 				=> $direction,
            'effect' 				    => $effect,
			// 'effect_type'				=> $effect_type,
			'slides_to_show' 			=> $slides_to_show,
			'slides_to_scroll'      	=> $slides_to_scroll,
			'arrows'					=> $arrows,
			'pagination'				=> $pagination,
			'mouse_wheel_scroll'        => $mouse_wheel_scroll,
			'speed'						=> $speed,
			'autoplay'   				=> $autoplay,
			'autoplay_speed'   			=> $autoplay_speed,
			'autoplay_direction'        => $autoplay_direction,
			'allow_touch'               => $allow_touch,
			'loop'						=> $loop,
			'centered_slides'			=> $centered_slides,
			'pause_on_interaction'		=> $pause_on_interaction,
			'overflow_opacity'			=> $overflow_opacity,
			'unequal_height_compatability' => $unequal_height_compatability,
			'gap' => isset($gap['size']) ? $gap['size'] : 20
		);

		$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
		$breakpoint_keys = array_keys($active_breakpoints);


        $space_between_gaps = array ( 'desktop' => isset($gap['size']) ? $gap['size'] : 20 );

		$swiper_breakpoints = array ();
		$swiper_breakpoints[] = array (
				'breakpoint' => 319
			);
		$swiper_breakpoints_slides = array ();

		foreach($breakpoint_keys as $breakpoint) {

			if($direction == 'vertical') {

				$breakpoint_toshow = 1;
				$breakpoint_toscroll = 1;

			} else {
				$breakpoint_show_str = 'slides_to_show_opts_'.$breakpoint;
				$breakpoint_toshow = $$breakpoint_show_str;
				if($breakpoint_toshow == '') {
					if($breakpoint == 'mobile') {
						$breakpoint_toshow = 1;
					} else if($breakpoint == 'mobile_extra') {
						$breakpoint_toshow = 1;
					} else if($breakpoint == 'tablet') {
						$breakpoint_toshow = 2;
					} else if($breakpoint == 'tablet_extra') {
						$breakpoint_toshow = 2;
					} else if($breakpoint == 'laptop') {
						$breakpoint_toshow = 4;
                    } else if($breakpoint == 'widescreen') {
						$breakpoint_toshow = 4;
					} else {
						$breakpoint_toshow = 4;
					}
				}
				if( $slides_to_scroll_opts == 'all' ) {
					$breakpoint_toscroll = $breakpoint_toshow;
				} else {
					$breakpoint_toscroll = 1;
				}

				$breakpoint_gap_str = 'gap_'.$breakpoint;
				$breakpoint_gap = $$breakpoint_gap_str;
                $breakpoint_gap = ($breakpoint_gap['size'] != '') ? $breakpoint_gap['size'] : $gap['size'];

                $space_between_gaps[$breakpoint] = $breakpoint_gap;

			}

			array_push($swiper_breakpoints, array (
					'breakpoint' => $active_breakpoints[$breakpoint]->get_value() + 1
				)
			);
			array_push($swiper_breakpoints_slides, array (
					'toshow' => (int)$breakpoint_toshow,
					'toscroll' => (int)$breakpoint_toscroll
				)
			);

		}

		array_push($swiper_breakpoints_slides, array (
				'toshow' => (int)$slides_to_show,
				'toscroll' => (int)$slides_to_scroll
			)
		);

		$responsive_breakpoints = array ();
		if(is_array($swiper_breakpoints) && !empty($swiper_breakpoints)) {
			foreach($swiper_breakpoints as $key => $swiper_breakpoint) {
				$responsive_breakpoints[] = array_merge($swiper_breakpoint, $swiper_breakpoints_slides[$key]);
			}
		}

		$carousel_settings['responsive'] = $responsive_breakpoints;
		$carousel_settings['space_between_gaps'] = $space_between_gaps;

		return wp_json_encode($carousel_settings);

	}

    public function get_carousel_pagination_html() {

		extract($this->settings);

        $output = '';

		$output .= '<div class="dt-carousel-pagination-wrapper">';
			if ( isset( $pagination ) && $pagination == 'scrollbar' ) :
				$output .= '<div class="dt-swiper-scrollbar dt-swiper-scrollbar-'.esc_attr($this->settings['module_id']).'"></div>';
			elseif ( isset( $pagination ) && $pagination != 'scrollbar' ) :
				$output .= '<div class="dt-swiper-pagination dt-swiper-pagination-'.esc_attr($this->settings['module_id']).'"></div>';
			endif;
			if ( isset( $arrows ) && $arrows == 'yes' ) :
				$output .= '<div class="dt-carousel-arrow-pagination">';
					if(!empty($arrows_prev_icon['value'])) {
						$output .= '<div class="dt-arrow-pagination-prev dt-arrow-pagination-prev-'.esc_attr($this->settings['module_id']).'">';
							$output .= ($arrows_prev_icon['library'] === 'svg') ? '<i>' : '';
								ob_start();
								\Elementor\Icons_Manager::render_icon( $arrows_prev_icon, [ 'aria-hidden' => 'true' ] );
								$output .= ob_get_clean();
							$output .= ($arrows_prev_icon['library'] === 'svg') ? '</i>' : '';
						$output .= '</div>';
					}
					if(!empty($arrows_next_icon['value'])) {
						$output .= '<div class="dt-arrow-pagination-next dt-arrow-pagination-next-'.esc_attr($this->settings['module_id']).'">';
							$output .= ($arrows_next_icon['library'] === 'svg') ? '<i>' : '';
								ob_start();
								\Elementor\Icons_Manager::render_icon( $arrows_next_icon, [ 'aria-hidden' => 'true' ] );
								$output .= ob_get_clean();
							$output .= ($arrows_next_icon['library'] === 'svg') ? '</i>' : '';
						$output .= '</div>';
					}
				$output .= '</div>';
			endif;
		$output .= '</div>';

        return $output;

    }

}