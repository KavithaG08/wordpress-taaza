<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Parallax_Carousel {

	private static $_instance = null;
	private $cc_style;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct() {
		// Initialize depandant class
		$this->cc_style = new WeDesignTech_Common_Controls_Style();
	}

	public function name() {
		return 'wdt-parallax-carousel';
	}

	public function title() {
		return esc_html__( 'Parallax Carousel', 'wdt-elementor-addon' );
	}

	public function icon() {
		return 'eicon-apps';
	}

	public function init_styles() {
		return array (
			'jquery-swiper' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/parallax-carousel/assets/css/swiper.min.css',
            $this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/parallax-carousel/assets/css/style.css',	
		);
	}

	public function init_inline_styles() {
		return array ();
	}

	public function init_scripts() {
		return array (
			'jquery-swiper' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/parallax-carousel/assets/js/swiper.min.js',
			$this->name() => WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/parallax-carousel/assets/js/script.js',
		);
	}

	public function create_elementor_controls($elementor_object) {

        $elementor_object->start_controls_section( 'wdt_section_features', array(
        	'label' => esc_html__( 'Content', 'wdt-elementor-addon'),
        ) );

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'list_title',
                array(
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'label' => esc_html__( 'Title', 'wdt-elementor-addon' ),
                    'default' => 'Sample Title'
                )
            );

            $repeater->add_control(
                'list_sub_title',
                array(
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'label' => esc_html__( 'Sub Title', 'wdt-elementor-addon' ),
                    'default' => 'Sample Sub Title',
					'condition' => array ()
                )
            );

            $repeater->add_control(
                'list_content',
                array(
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'label' => esc_html__( 'Description', 'wdt-elementor-addon' ),
                    'default' => 'Sample Description',
					'condition' => array ()
                )
                
            );

            $repeater->add_control(
                'image',
                array(
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'label' => esc_html__( 'Image', 'wdt-elementor-addon' ),
                    'default' => array(
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ),
                )
            );

            $repeater->add_control(
                'button',
                array(
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'label' => esc_html__( 'Button text', 'wdt-elementor-addon' ),
                    'default' => 'Click Here',
					'condition' => array ()
                )
            );

            $repeater->add_control(
                'button_link', 
					array(
							'label' => esc_html__( 'Link', 'wdt-elementor-addon' ),
							'type' => \Elementor\Controls_Manager::URL,
							'placeholder' => esc_html__( 'https://your-link.com', 'wdt-elementor-addon' ),
							'options' => array( 'url', 'is_external', 'nofollow' ),
							'default' => array(
								'url' => '#',
								'is_external' => false,
								'nofollow' => false,
							
							),
							'label_block' => true,
							'condition' => array ()
					)
            );

            $elementor_object->add_control( 'features_content', array(
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'label'       => esc_html__('Banner Items', 'wdt-elementor-addon'),
                'description' => esc_html__('Banner Items', 'wdt-elementor-addon' ),
                'fields'      => $repeater->get_controls(),
                'default' => array (
                    array (
                        'list_title'     => esc_html__('Sed ut perspiciatis', 'wdt-elementor-addon' ),
                        'list_sub_title'     => esc_html__('Unde omnis iste', 'wdt-elementor-addon' ),
                        'list_content'     => esc_html__('when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin.', 'wdt-elementor-addon' ),
                        'button' => esc_html__('Click Here', 'wdt-elementor-addon' ),
                        'button_link' => array( 'value' => '#' )
                    ),
                    array (
                        'list_title'     => esc_html__('Lorem ipsum dolor', 'wdt-elementor-addon' ),
                        'list_sub_title'     => esc_html__('Nemo enim ipsam', 'wdt-elementor-addon' ),
                        'list_content'     => esc_html__('He lay on his armour-like back, and if he lifted his head a little he could see his brown belly', 'wdt-elementor-addon' ),
                        'button' => esc_html__('Click Here', 'wdt-elementor-addon' ),
                        'button_link' => array( 'value' => '#' )
                    ),
                    array (
                        'list_title'     => esc_html__('Li Europan lingues', 'wdt-elementor-addon' ),
                        'list_sub_title'     => esc_html__('The European languages', 'wdt-elementor-addon' ),
                        'list_content'     => esc_html__('The bedding was hardly able to cover it and seemed ready to slide off any moment.', 'wdt-elementor-addon' ),
                        'button' => esc_html__('Click Here', 'wdt-elementor-addon' ),
                        'button_link' => array( 'value' => '#' )
                    ),
                    array (
                        'list_title'     => esc_html__('Far far away', 'wdt-elementor-addon' ),
                        'list_sub_title'     => esc_html__('One morning, when', 'wdt-elementor-addon' ),
                        'list_content'     => esc_html__('His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked.', 'wdt-elementor-addon' ),
                        'button' => esc_html__('Click Here', 'wdt-elementor-addon' ),
                        'button_link' => array( 'value' => '#' )
                    ),
                    array (
                        'list_title'     => esc_html__('A wonderful serenity', 'wdt-elementor-addon' ),
                        'list_sub_title'     => esc_html__('The quick, brown', 'wdt-elementor-addon' ),
                        'list_content'     => esc_html__('Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.', 'wdt-elementor-addon' ),
                        'button' => esc_html__('Click Here', 'wdt-elementor-addon' ),
                        'button_link' => array( 'value' => '#' )
                    )         
                ),
                'title_field' => '{{{list_title}}}'
            ) );

        $elementor_object->end_controls_section();

		$elementor_object->start_controls_section( 'wdt_section_settings', array(
			'label' => esc_html__( 'Settings', 'wdt-elementor-addon'),
			));

			$elementor_object->add_responsive_control( 'slider-height', array(
				'label' => esc_html__( 'slider Height', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array('px', '%', 'em', 'rem', 'custom'),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 950,
				),
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-holder .wdt-parallax-carousel-item' => 'height: {{SIZE}}{{UNIT}};',
				),
			));

			$elementor_object->add_control( 'arrows', array(
				'label' => esc_html__( 'Arrows', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true
			) );

			$elementor_object->add_control(
				'arrows_prev_icon',
				array (
					'label' => esc_html__( 'Arrow Prev Icon', 'wdt-elementor-addon' ),
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
					'label' => esc_html__( 'Arrow Next Icon', 'wdt-elementor-addon' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'label_block' => false,
					'skin' => 'inline',
					'default' => array( 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid', ),
					'condition' => array( 'arrows' => 'yes' )
				)
			);

		$elementor_object->end_controls_section();

		$elementor_object->start_controls_section(
			'wdt_item_style_section',
			array (
				'label' => esc_html__( 'Item', 'wdt-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$elementor_object->add_control(
			'text_align',
			array(
				'label' => esc_html__( 'Alignment', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-left',
					),
	
					'center' => array(
						'title' => esc_html__( 'Center', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-center',
					),
											
					'right' => array(
						'title' => esc_html__( 'Right', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-right',
					),
						
				),
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-info, {{WRAPPER}} .wdt-parallax-carousel-icon-wrapper' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wdt-parallax-carousel-image, {{WRAPPER}} .wdt-parallax-carousel-icon-wrapper' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .wdt-parallax-carousel-info p' => 'text-align: {{VALUE}};',
				),
			)
				
		);

		$elementor_object->add_control(
			'vertical_align',
			array(
				'label' => esc_html__( 'Vertical Alignment', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'wdt-elementor-addon' ),
						'icon' => 'eicon-v-align-top',
					),
	
					'center' => array(
						'title' => esc_html__( 'Center', 'wdt-elementor-addon' ),
						'icon' => 'eicon-v-align-middle',
					),
											
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'wdt-elementor-addon' ),
						'icon' => 'eicon-v-align-bottom',
					),
						
				),
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-info' => 'align-self: {{VALUE}};',
				),
			)
				
		);

		$elementor_object->add_control(
			'horizontal_align',
			array(
				'label' => esc_html__( 'Horizontal Alignment', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'wdt-elementor-addon' ),
						'icon' => 'eicon-h-align-left',
					),
	
					'center' => array(
						'title' => esc_html__( 'Center', 'wdt-elementor-addon' ),
						'icon' => 'eicon-h-align-center',
					),
											
					'right' => array(
						'title' => esc_html__( 'Right', 'wdt-elementor-addon' ),
						'icon' => 'eicon-h-align-right',
					),
						
				),
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-info' => 'justify-self: {{VALUE}};',
				),
			)
				
		);

		$elementor_object->add_responsive_control(
			'item_margin',
			array (
				'label' => esc_html__( 'Margin', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_responsive_control(
			'item_padding',
			array (
				'label' => esc_html__( 'Padding', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);	

		$elementor_object->start_controls_tabs(
			'style_tabs'
		);

		$elementor_object->start_controls_tab(
			'style_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'text_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-item, {{WRAPPER}} .wdt-parallax-carousel-item h4, {{WRAPPER}} .wdt-parallax-carousel-item a, {{WRAPPER}} .wdt-parallax-carousel-sub-title span' => 'color: {{VALUE}}',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name' => 'background',
				'types' =>  array('classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-info',
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'border',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-item',
			)
				
		);

		$elementor_object->add_responsive_control(
			'item-border-radius',
			array (
				'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item-box_shadow',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-item',
			)
				
		);

		$elementor_object->end_controls_tab();

		$elementor_object->start_controls_tab(
			'style_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'text_color_hover',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-item:hover, {{WRAPPER}} .wdt-parallax-carousel-item:hover h4, {{WRAPPER}} .wdt-parallax-carousel-item:hover a, {{WRAPPER}} .wdt-parallax-carousel-item:hover h6' => 'color: {{VALUE}}',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name' => 'background_hover',
				'types' =>  array('classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-info:hover',
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'border_hover',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-item:hover',
			)
				
		);

		$elementor_object->add_responsive_control(
			'item-border-radius_hover',
			array (
				'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item-box_shadow_hover',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-item:hover',
			)	
		);

		$elementor_object->end_controls_tab();

		$elementor_object->end_controls_tabs();

		$elementor_object->end_controls_section();

		//Title

		$elementor_object->start_controls_section(
			'wdt_title_style_section',
			array (
				'label' => esc_html__( 'Title', 'wdt-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-title h2',
			)
		);

		$elementor_object->add_responsive_control(
			'item_title_margin',
			array (
				'label' => esc_html__( 'Margin', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-title h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		
		$elementor_object->start_controls_tabs(
			'title_style_tabs'
		);

		$elementor_object->start_controls_tab(
			'title_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'title_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-item .wdt-parallax-carousel-title h2' => 'color: {{VALUE}}',
				),
			)
		);

		$elementor_object->end_controls_tab();

		$elementor_object->start_controls_tab(
			'title_hover_tab',
			array(
				'label' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'title_hover_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-item:hover .wdt-parallax-carousel-title h2' => 'color: {{VALUE}}',
				),
			)
		);

		$elementor_object->end_controls_tab();

		$elementor_object->end_controls_tabs();

		$elementor_object->end_controls_section();

		//Subtitle

		$elementor_object->start_controls_section(
			'wdt_sub_title_style_section',
			array (
				'label' => esc_html__( 'Sub Title', 'wdt-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'sub_title_typography',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-sub-title span',
			)
		);

		$elementor_object->add_responsive_control(
			'item_sub_title_margin',
			array (
				'label' => esc_html__( 'Margin', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-sub-title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$elementor_object->start_controls_tabs(
			'sub_title_style_tabs'
		);

		$elementor_object->start_controls_tab(
			'sub_title_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'sub_title_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-sub-title span' => 'color: {{VALUE}}',
				),
			)
		);

		$elementor_object->end_controls_tab();

		$elementor_object->start_controls_tab(
			'sub_title_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'sub_title_hover_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-item:hover .wdt-parallax-carousel-sub-title span' => 'color: {{VALUE}}',
				),
			)
		);
		$elementor_object->end_controls_tab();

		$elementor_object->end_controls_tabs();

		$elementor_object->end_controls_section();


		//content 

		$elementor_object->start_controls_section(
			'wdt_content_style_section',
			array (
				'label' => esc_html__( 'Description', 'wdt-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-content',
			)	
		);

		$elementor_object->add_responsive_control(
			'item_content_margin',
			array (
				'label' => esc_html__( 'Margin', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_responsive_control(
			'item_content_padding',
			array (
				'label' => esc_html__( 'Padding', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);	

		$elementor_object->add_control(
			'content_text_align',
			array(
				'label' => esc_html__( 'Alignment', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-left',
					),
	
					'center' => array(
						'title' => esc_html__( 'Center', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-center',
					),
											
					'right' => array(
						'title' => esc_html__( 'Right', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-right',
					),
						
			),
				'default' => '',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-content' => 'text-align: {{VALUE}};',
				),
			)
				
		);
		
		$elementor_object->start_controls_tabs(
			'content_style_tabs'
		);

		$elementor_object->start_controls_tab(
			'content_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'content_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-content' => 'color: {{VALUE}}',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name' => 'content_background',
				'types' =>  array('classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-content',
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'content_border',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-content',
			)
				
		);

		$elementor_object->add_responsive_control(
			'item-content_border-radius',
			array (
				'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item-content_box_shadow',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-content',
			)
				
		);

		$elementor_object->end_controls_tab();

		$elementor_object->start_controls_tab(
			'content_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
			)	
		);

		$elementor_object->add_control(
			'content_hover_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-content:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name' => 'content_hover_background',
				'types' =>  array('classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-content:hover',
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'content_hover_border',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-content:hover',
			)
				
		);

		$elementor_object->add_responsive_control(
			'item-content-hover-border-radius',
			array (
				'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-content:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item-content_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-content:hover',
			)
				
		);

		$elementor_object->end_controls_tab();

		$elementor_object->end_controls_tabs();

		$elementor_object->end_controls_section();


		// $elementor_object->end_controls_tabs();
		

		// $elementor_object->end_controls_section();

		//Button

		$elementor_object->start_controls_section(
			'wdt_button_style_section',
			array (
				'label' => esc_html__( 'Button', 'wdt-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-button-text span',
			)			
		);

		$elementor_object->add_control(
			'button_text_align',
			array(
				'label' => esc_html__( 'Alignment', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-left',
					),
	
					'center' => array(
						'title' => esc_html__( 'Center', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-center',
					),
											
					'right' => array(
						'title' => esc_html__( 'Right', 'wdt-elementor-addon' ),
						'icon' => 'eicon-text-align-right',
					),
						
			),
				'default' => '',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-button' => 'text-align: {{VALUE}};',
				),
			)
				
		);

		$elementor_object->add_responsive_control(
			'button_margin',
			array (
				'label' => esc_html__( 'Margin', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_responsive_control(
			'button_padding',
			array (
				'label' => esc_html__( 'Padding', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);	

		$elementor_object->start_controls_tabs(
			'button_style_tabs'
		);

		$elementor_object->start_controls_tab(
			'button_style_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'wdt-elementor-addon' ),
			)
		);

		$elementor_object->add_control(
			'button_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-button a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name' => 'button_normal_background',
				'types' =>  array('classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-button a',
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'button_normal_border',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-button a',
			)
		);

		$elementor_object->add_responsive_control(
			'item-button-border-radius',
			array (
				'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-button a',
			)
				
		);

		$elementor_object->end_controls_tab();

		$elementor_object->start_controls_tab(
			'button_style_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'wdt-elementor-addon' ),
			)
		);

		$elementor_object->add_control(
			'button_hover_color',
			array(
				'label' => esc_html__( 'Color', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wdt-parallax-carousel-button a:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name' => 'button_hover_background',
				'types' =>  array('classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-button a:hover',
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'button_hover_border',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-button a:hover',
			)
		);

		$elementor_object->add_responsive_control(
			'item-button-hover-border-radius',
			array (
				'label' => esc_html__( 'Border Radius', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array ( 'px', 'em', '%' ),
				'selectors' => array (
					'{{WRAPPER}} .wdt-parallax-carousel-button a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$elementor_object->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wdt-parallax-carousel-button a:hover',
			)
				
		);

		$elementor_object->end_controls_tab();

		$elementor_object->end_controls_tabs();

		$elementor_object->end_controls_section();

    }

	public function get_thumb_carousel_attributes($settings) {

		extract($settings);

		$slides_to_show = $slides_to_show_opts;
		if( $slides_to_scroll_opts == 'all' ) {
			$slides_to_scroll = $slides_to_show;
		} else {
			$slides_to_scroll = 1;
		}

		$carousel_settings = array (
			'slides_to_scroll'      	=> $slides_to_scroll,
			'slides_to_show' 			=> $slides_to_show,
			'loop'						=> $loop,
			'arrows'					=> $arrows,
			'freemode'					=> $freemode,
			'centered_slides'			=> $centered_slides,
			'gap' 						=> isset($gap['size']) ? $gap['size'] : 20
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

	public function render_html($widget_object, $settings) {

		if($widget_object->widget_type != 'elementor') {
			return;
		}

		$output  = '';
		$settings['module_id'] = $widget_object->get_id();
		$settings['module_class'] = 'wdt-parallax-carousel';
	
		// $settings_attr = $this->get_thumb_carousel_attributes($settings);

		$output .= '<div class="wdt-parallax-carousel-holder">';
			$output .= '<div class="swiper '.esc_attr($settings['module_class']).'-'.($settings['module_id']).'" data-id="'.($settings['module_id']).'" data-wrapper-class="'.esc_attr($settings['module_class']).'-'.($settings['module_id']).'">';

				// $output .= '<div class="parallax-bg" style="background-image: url(https://swiperjs.com/demos/images/nature-1.jpg);" data-swiper-parallax="-23%"></div>';

				$output .= '<div class="swiper-wrapper">';

				foreach ( $settings['features_content'] as $index => $item ) :

					$output .= '<div class="swiper-slide">';
						$output .= '<div class="wdt-parallax-carousel-item">';

							$output .= '<div class="wdt-parallax-carousel-image">';
								$output .= '<img src="' . esc_url( $item['image']['url'] ) . '" alt="">';
							$output .= '</div>';

							$output .= '<div class="wdt-parallax-carousel-info">';
								if(isset($item['list_sub_title']) && !empty($item['list_sub_title'])) {
									$output .= '<div class="wdt-parallax-carousel-sub-title"><span>' . $item['list_sub_title'] . '</span></div>';
								}
								if(isset($item['list_title']) && !empty($item['list_title'])) {
									$output .= '<div class="wdt-parallax-carousel-title"><h2>' . $item['list_title'] . '</h2></div>';
								}
								if(isset($item['list_content']) && !empty($item['list_content'])) {
									$output .= '<div class="wdt-parallax-carousel-content">' . $item['list_content'] . '</div>';
								}

								$link_start = $link_end = '';
								if( !empty( $item['button_link']['url'] ) && $item['button'] !== '' ){
									$target = ( $item['button_link']['is_external'] == 'on' ) ? ' target="_blank" ' : '';
									$nofollow = ( $item['button_link']['nofollow'] == 'on' ) ? 'rel="nofollow" ' : '';
									$link_start = '<a href="'.esc_url( $item['button_link']['url'] ).'"'. $target . $nofollow.'>';
									$link_end = '</a>';
									$output .= '<div class="wdt-parallax-carousel-button">' . $link_start . '<div class="wdt-parallax-carousel-button-text"><span>' . $item['button'] . '</div></span>' . $link_end . '</div>' ;
								}
							$output .= '</div>';

						$output .= '</div>';	
					$output .= '</div>';
				endforeach;
	
				$output .= '</div>';

				$output .= '<div class="wdt-parallax-carousel-pagination-wrapper">';

					$output .= '<div class="slide-progress"> ';
						$output .= '<div class="swiper-pagination swiper-pagination-progressbar">';
							$output .= '<span class="swiper-pagination-progressbar-fill"></span>';
						$output .= '</div>';
					$output .= '</div>';


					if ( $settings['arrows'] == 'yes' ) :
						$output .= '<div class="wdt-parallax-carousel-arrow-pagination">';
							if(!empty( $settings['arrows_prev_icon']['value'])) {
								$output .= '<div class="wdt-arrow-pagination-prev wdt-arrow-pagination-prev-'.esc_attr($settings['module_id']).'">';
									$output .= ($settings['arrows_prev_icon']['library'] === 'svg') ? '<i>' : '';
										ob_start();
										\Elementor\Icons_Manager::render_icon( $settings['arrows_prev_icon'], [ 'aria-hidden' => 'true' ] );
										$output .= ob_get_clean();
									$output .= ($settings['arrows_prev_icon']['library'] === 'svg') ? '</i>' : '';
									$output .= '<span class="wdt-arrow-pagination-prev-text">'.esc_html__('Prev', 'wdt-elementor-addon').'</span>';
								$output .= '</div>';
							}
							if(!empty($settings['arrows_next_icon']['value'])) {
								$output .= '<div class="wdt-arrow-pagination-next wdt-arrow-pagination-next-'.esc_attr($settings['module_id']).'">';
									$output .= '<span class="wdt-arrow-pagination-prev-text">'.esc_html__('Next', 'wdt-elementor-addon').'</span>';
									$output .= ($settings['arrows_next_icon']['library'] === 'svg') ? '<i>' : '';
										ob_start();
										\Elementor\Icons_Manager::render_icon( $settings['arrows_next_icon'], [ 'aria-hidden' => 'true' ] );
										$output .= ob_get_clean();
									$output .= ($settings['arrows_next_icon']['library'] === 'svg') ? '</i>' : '';
								$output .= '</div>';
							}
						$output .= '</div>';
					endif;
				$output .= '</div>';

			$output .= '</div>';
		$output .= '</div>';

        return $output;

	}

}

if( !function_exists( 'wedesigntech_widget_base_parallax_carousel' ) ) {
    function wedesigntech_widget_base_parallax_carousel() {
        return WeDesignTech_Widget_Base_Parallax_Carousel::instance();
    }
}