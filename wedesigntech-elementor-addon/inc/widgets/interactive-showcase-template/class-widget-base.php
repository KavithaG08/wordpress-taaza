<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Interactive_Showcase_Template {

	private static $_instance = null;

	private $cc_layout;
	private $cc_style;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct() { }

	public function name() {
		return 'wdt-interactive-showcase-template';
	}

	public function title() {
		return esc_html__( 'Gallery', 'wdt-elementor-addon' );
	}

	public function icon() {
		return 'eicon-apps';
	}

	public function init_styles() {
		return array_merge(
			array (
				$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/interactive-showcase-template/assets/css/style.css'
			)
		);
	}

	public function init_inline_styles() {
		return array ();
	}

	public function init_scripts() {
		return array (
			'isotope' =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/interactive-showcase-template/assets/js/isotope.pkgd.js',
			$this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/interactive-showcase-template/assets/js/script.js'
		);
	}

	public function create_elementor_controls($elementor_object) {

		$elementor_object->start_controls_section( 'wdt_section_images', array(
			'label' => esc_html__( 'Content', 'wdt-elementor-addon'),
			));
	
			$repeater = new \Elementor\Repeater();

			$repeater->add_control( 'list_title', array(
				'type'    => \Elementor\Controls_Manager::TEXT,
				'label' => esc_html__( 'Title', 'wdt-elementor-addon' ),
				'default' => 'Gallery Item'
			));

			$repeater->add_control( 'image', array(
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label' => esc_html__( 'Image', 'wdt-elementor-addon' ),
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			));
	
			$elementor_object->add_control( 'images_content', array(
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'label'       => esc_html__('Banner Items', 'wdt-elementor-addon'),
				'description' => esc_html__('Banner Items', 'wdt-elementor-addon' ),
				'fields'      => $repeater->get_controls(),
				'default' => array (
					array (
						'list_title' => esc_html__('Gallery Item 1', 'wdt-elementor-addon' ),
					),
					array (
						'list_title' => esc_html__('Gallery Item 2', 'wdt-elementor-addon' ),
					),
					array (
						'list_title' => esc_html__('Gallery Item 3', 'wdt-elementor-addon' ),
					)       
				),
				'title_field' => '{{{list_title}}}'
			));
	
			$elementor_object->end_controls_section();

		$elementor_object->start_controls_section( 'wdt_section_settings', array(
			'label' => esc_html__( 'Settings', 'wdt-elementor-addon'),
		));

			$elementor_object->add_control( 'show_title', array(
				'label'              => esc_html__( 'Show Title', 'wdt-elementor-addon' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'default'            => 'false',
				'return_value'       => 'true',
				'condition' => array ()
			));

			$elementor_object->add_control( 'column_count', array(
				'label'   => esc_html__( 'Number Of Columns', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'wdt-column-3',
				'options' => array(
					'default'  => esc_html__( 'Default', 'wdt-elementor-addon' ),
					'wdt-column-1'   => esc_html__( 'Column 1', 'wdt-elementor-addon' ),
					'wdt-column-2'   => esc_html__( 'Column 2', 'wdt-elementor-addon' ),
					'wdt-column-3'   => esc_html__( 'Column 3', 'wdt-elementor-addon' ),
					'wdt-column-4'   => esc_html__( 'Column 4', 'wdt-elementor-addon' ),
					'wdt-column-5'   => esc_html__( 'Column 5', 'wdt-elementor-addon' ),
					'wdt-column-6'   => esc_html__( 'Column 6', 'wdt-elementor-addon' )
				)
			));

		$elementor_object->end_controls_section();


	}

	public function render_html($widget_object, $settings) {

		if($widget_object->widget_type != 'elementor') {
			return;
		}

		$output = '';
		$settings['module_id'] = $widget_object->get_id();
		$settings['module_class'] = 'wdt-gallery-';

		$output .= '<div class="gallery wdt-'.esc_attr($settings['module_class']).($settings['module_id']).' wdt-grid">';

			foreach ( $settings['images_content'] as $index => $item ) :
			
				$output .= '<div class="wdt-gallery-item '.$settings['column_count'].' wdt-grid-item">';

					$output .= '<div class="wdt-gallery-item-inner">';
						$output .= '<div class="wdt-gallery-item-image">';
							$output .= '<a class="wdt-gallery-pop-img" href="'.esc_url($item['image']['url']).'" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="'.($settings['module_id']).'" data-elementor-lightbox-title="'.esc_attr($item['list_title']).'">';	
								$output .= '<img src="'.esc_url($item['image']['url']).'" alt="'.esc_attr($item['list_title']).'">';
							$output .= '</a>';
						$output .= '</div>';
					$output .= '</div>';

					if( $settings['show_title'] == 'true' ) :
						$output .= '<div class="wdt-gallery-item-content">';
							$output .= '<h6 class="wdt-gallery-item-content-title">';
								$output .= $item['list_title'];
							$output .= '</h6>';
						$output .= '</div>';
					endif;

				$output .= '</div>';
				
			endforeach;
		$output .= '</div>';

		return $output;

	}

}

if( !function_exists( 'wedesigntech_widget_base_interactive_showcase_template' ) ) {
    function wedesigntech_widget_base_interactive_showcase_template() {
        return WeDesignTech_Widget_Base_Interactive_Showcase_Template::instance();
    }
}