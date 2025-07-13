<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WeDesignTech_Widget_Base_Redeem_Coupon {

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
	}

	public function name() {
		return 'wdt-redeem-coupon';
	}

	public function title() {
		return esc_html__( 'Redeem Coupon', 'wdt-elementor-addon' );
	}

	public function icon() {
		return 'eicon-apps';
	}

	public function init_styles() {
		return array (
            $this->name() =>  WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/redeem-coupon/assets/css/style.css',	
		);
	}

	public function init_inline_styles() {
		return array ();
	}

	public function init_scripts() {
		return array (
			$this->name() => WEDESIGNTECH_ELEMENTOR_ADDON_DIR_URL.'inc/widgets/redeem-coupon/assets/js/script.js',
		);
	}

	public function create_elementor_controls($elementor_object) {

		$elementor_object->start_controls_section( 'wdt_section_features', array(
			'label' => esc_html__( 'Content', 'wdt-elementor-addon'),
			));

			$elementor_object->add_control( 'image', array(
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label' => esc_html__( 'Image', 'wdt-elementor-addon' ),
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			));
	
			$elementor_object->add_control( 'coupon_offer', array(
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'label' => esc_html__( 'Offer Value', 'wdt-elementor-addon' ),
				'min'     => 5,
				'default' => 30
			));

			$elementor_object->add_control( 'coupon_title', array(
				'type'    => \Elementor\Controls_Manager::TEXT,
				'label' => esc_html__( 'Title', 'wdt-elementor-addon' ),
				'default' => 'Redeem Coupon Title'
			));

			$elementor_object->add_control( 'coupon_description', array(
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'label' => esc_html__( 'Description', 'wdt-elementor-addon' ),
				'default' => 'Fusce pretium imperdiet tempus. Vestibulum id risus arcu. Ut vitae fringilla libero. Cras malesuada sapien vel lobortis rhoncus. Mauris ante arcu, malesuada id purus pulvinar.'
			));

			$elementor_object->add_control( 'coupon_code', array(
				'type'    => \Elementor\Controls_Manager::TEXT,
				'label' => esc_html__( 'Coupon Code', 'wdt-elementor-addon' ),
				'default' => 'TAAZ30'
			));

			$elementor_object->add_control( 'currency_symbol', array(
				'label' => esc_html__( 'Currency Symbol', 'wdt-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'' => esc_html__( 'None', 'wdt-elementor-addon' ),
					'&#36; ' . _x( 'Dollar', 'Currency', 'wdt-elementor-addon' ),
					'&#128; ' . _x( 'Euro', 'Currency', 'wdt-elementor-addon' ),
					'&#3647; ' . _x( 'Baht', 'Currency', 'wdt-elementor-addon' ),
					'&#8355; ' . _x( 'Franc', 'Currency', 'wdt-elementor-addon' ),
					'&fnof; ' . _x( 'Guilder', 'Currency', 'wdt-elementor-addon' ),
					'kr ' . _x( 'Krona', 'Currency', 'wdt-elementor-addon' ),
					'&#8356; ' . _x( 'Lira', 'Currency', 'wdt-elementor-addon' ),
					'&#8359 ' . _x( 'Peseta', 'Currency', 'wdt-elementor-addon' ),
					'&#8369; ' . _x( 'Peso', 'Currency', 'wdt-elementor-addon' ),
					'&#163; ' . _x( 'Pound Sterling', 'Currency', 'wdt-elementor-addon' ),
					'R$ ' . _x( 'Real', 'Currency', 'wdt-elementor-addon' ),
					'&#8381; ' . _x( 'Ruble', 'Currency', 'wdt-elementor-addon' ),
					'&#8360; ' . _x( 'Rupee', 'Currency', 'wdt-elementor-addon' ),
					'&#8377; ' . _x( 'Rupee (Indian)', 'Currency', 'wdt-elementor-addon' ),
					'&#8362; ' . _x( 'Shekel', 'Currency', 'wdt-elementor-addon' ),
					'&#165; ' . _x( 'Yen/Yuan', 'Currency', 'wdt-elementor-addon' ),
					'&#8361; ' . _x( 'Won', 'Currency', 'wdt-elementor-addon' ),
				),
				'default' => '&#36;',
			) );

			$elementor_object->add_control( 'price', array(
				'label'   => esc_html__( 'Price', 'wdt-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 0,
				'default' => 39.99
			) );
           

        $elementor_object->end_controls_section();

    }

	public function render_html($widget_object, $settings) {

		if($widget_object->widget_type != 'elementor') {
			return;
		}

		// echo '<pre>';
		// print_r($settings);
		// echo '</pre>';

		$output  = '';

		$output.= '<div class="wdt-redeem-coupon-container">';
			$output.= '<div class="wdt-redeem-coupon-wrapper">';

				$output.= '<div class="wdt-redeem-coupon-image">';

					if( !empty($settings['image']['url']) ) {
						$output .= '<div class="wdt-redeem-coupon-image">';
							$output.= '<img src="'.esc_url($settings['image']['url']).'" alt="'.esc_attr($settings['coupon_title']).'">';
						$output .= '</div>';
					}
				
					if( !empty($settings['coupon_offer']) ) {
						$output .= '<div class="wdt-redeem-coupon-offer">';
							$output.= '<span class="wdt-coupon-offer">'.esc_html($settings['coupon_offer']).'%</span>';
							$output.= '<span class="wdt-coupon-offer-label">'.esc_html__( 'off', 'wdt-elementor-addon' ).'</span>';
						$output .= '</div>';
					}

					if( !empty($settings['price']) ) {
						$output.= '<div class="wdt-redeem-coupon-price">';
							$output.= '<span class="wdt-price-from">'.esc_html__( 'from / ', 'wdt-elementor-addon' ).'</span>';
							$output.= '<span class="wdt-price">'.esc_html($settings['price']).esc_html($settings['currency_symbol']).'</span>';
						$output.= '</div>';
					}

				$output.= '</div>';

				$output.= '<div class="wdt-redeem-coupon-content">';

					if( !empty($settings['coupon_title']) ) {
						$output .= '<div class="wdt-redeem-coupon-title">';
							$output .= '<h4 class="wdt-redeem-title">'.esc_html($settings['coupon_title']).'</h4>';
						$output.= '</div>';
					}
					
					if( !empty($settings['coupon_description']) ) {
						$output .= '<div class="wdt-redeem-coupon-description">'.esc_html($settings['coupon_description']).'</div>';
					}
				
					if( !empty($settings['coupon_code']) ) {
						$output .= '<div class="wdt-redeem-coupon-code">';
							$output .= '<span id="wdt-copy-coupon-code">'.esc_attr($settings['coupon_code']).'</span>';
							$output .= '<span class="wdt-copy-code">'.esc_html__( 'Copy Code', 'wdt-elementor-addon' ).'</span>';
							$output .= '<span class="code-copy-icon"></span>';
						$output .= '</div>';
					}

				$output.= '</div>';
			$output.= '</div>';
		$output.= '</div>';

		
		// $output.= '<div class="wdt-redeem-coupon-container">';
		// 	$output.= '<div class="wdt-redeem-coupon-wrapper">';
		// 		$output.= '<span class="code-copy-icon"></span>';
		// 		$output.= '<span id="wdt-copy-coupon-code">'.esc_attr($settings['coupon_code']).'</span>';
		// 		$output.= '<span class="wdt-copy-code">'.esc_html__( 'Copy Code', 'wdt-elementor-addon' ).'</span>';
		// 		$output.= '<span class="wdt-copy-code">'.esc_html__( 'Copy Code', 'wdt-elementor-addon' ).'</span>';
		// 	$output.= '</span>';
		// $output.= '</div>';

        return $output;

	}

}

if( !function_exists( 'wedesigntech_widget_base_redeem_coupon' ) ) {
    function wedesigntech_widget_base_redeem_coupon() {
        return WeDesignTech_Widget_Base_Redeem_Coupon::instance();
    }
}