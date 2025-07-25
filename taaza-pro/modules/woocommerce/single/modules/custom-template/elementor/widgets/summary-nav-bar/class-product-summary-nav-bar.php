<?php
namespace TaazaElementor\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Taaza_Shop_Widget_Product_Summary_Nav_bar extends Widget_Base {

	public function get_categories() {
		return [ 'wdt-shop-widgets' ];
	}

	public function get_name() {
		return 'wdt-shop-product-single-summary-nav-bar';
	}

	public function get_title() {
		return esc_html__( 'Product Single - Summary Nav Bar', 'taaza-pro' );
	}

	public function get_style_depends() {
		return array( 'wdt-shop-product-single-summary-nav-bar' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'product_summary_nav_bar_section', array(
			'label' => esc_html__( 'General', 'taaza-pro' ),
		) );

			$this->add_control( 'product_id', array(
				'label'       => esc_html__( 'Product Id', 'taaza-pro' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__('Provide product id for which you have to display product summary items. No need to provide ID if it is used in Product single page.', 'taaza-pro'),
			) );

			$this->add_control( 'items', array(
				'label'   => esc_html__( 'Items', 'taaza-pro' ),
				'type'    => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default' => '',
				'options' => array(
					'breadcrumb'           => esc_html__( 'Summary Nav - Breadcrumb', 'taaza-pro' ),
					'navigation'           => esc_html__( 'Summary Nav - Navigation', 'taaza-pro' ),
				),
			) );

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

		$output = '';

		if($settings['product_id'] == '' && is_singular('product')) {
			global $post;
			$settings['product_id'] = $post->ID;
		}

		if($settings['product_id'] != '') {

			if($settings['items'] != '') {

				$items = $settings['items'];

				if(is_array($items) && !empty($items)) {

					$output .= '<div class="wdt-product-summary-nav-bar '.$settings['class'].'">';

						// Breadcrumb
						$breadcrumb = '';
						if(in_array('breadcrumb', $items) || in_array('breadcrumbnavigation', $items)) {
							ob_start();
							woocommerce_breadcrumb();
							$breadcrumb = ob_get_clean();
						}

						// Navigation
						$navigation = '';
						if(in_array('navigation', $items) || in_array('breadcrumbnavigation', $items)) {
							if( function_exists( 'taaza_shop_single_product_nav' ) ) {
								$navigation = taaza_shop_single_product_nav();
							}
						}

						// Build selected items
						foreach ($items as $key => $value) {
							$output .= $$value;
						}

					$output .= '</div>';

				}

			}

		} else {

			$output .= esc_html__('Please provide product id to display corresponding data!', 'taaza-pro');

		}

		echo $output;

	}

}