<?php
namespace TaazaElementor\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Taaza_Shop_Widget_Product_360_Image_Viewer extends Widget_Base {

	public function get_categories() {
		return [ 'wdt-shop-widgets' ];
	}

	public function get_name() {
		return 'wdt-shop-product-single-images-360-viewer';
	}

	public function get_title() {
		return esc_html__( 'Product Single - Images 360 Viewer', 'taaza-pro' );
	}

	public function get_style_depends() {
		return array( 'wdt-shop-product-single-images-360-viewer' );
	}

	public function get_script_depends() {
		return array( 'jquery-360viewer', 'wdt-shop-product-single-images-360-viewer' );
	}

	protected function register_controls() {

		$this->start_controls_section( 'product_images_360viewer_section', array(
			'label' => esc_html__( 'Product', 'taaza-pro' ),
		) );

			$this->add_control( 'product_id', array(
				'label'       => esc_html__( 'Product Id', 'taaza-pro' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__('Provide product id for which you have to display product images in list format. No need to provide ID if it is used in Product single page.', 'taaza-pro'),
			) );

			$this->add_control( 'enable_popup_viewer', array(
				'label'        => esc_html__( 'Enable PopUp Viewer', 'taaza-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__('If you wish, you can show 360 viewer in popup.', 'taaza-pro'),
				'label_on'     => esc_html__( 'yes', 'taaza-pro' ),
				'label_off'    => esc_html__( 'no', 'taaza-pro' ),
				'default'      => '',
				'return_value' => 'true',
			) );

			$this->add_control(
				'source',
				array (
					'label' => esc_html__( 'Source', 'taaza-pro' ),
					'type'  => Controls_Manager::TEXT
				)
			);

			$this->add_control(
				'class',
				array (
					'label' => esc_html__( 'Class', 'taaza-pro' ),
					'type'  => Controls_Manager::TEXT
				)
			);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();

		$output = taaza_shop_product_images_360viewer_render_html($settings);

		echo $output;

	}


}