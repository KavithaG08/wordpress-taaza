<?php

namespace TaazaElementor\Widgets;
use TaazaElementor\Widgets\Taaza_Shop_Widget_Product_Summary;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


class Taaza_Shop_Widget_Product_Summary_Extend extends Taaza_Shop_Widget_Product_Summary {

	function dynamic_register_controls() {

		$this->start_controls_section( 'product_summary_extend_section', array(
			'label' => esc_html__( 'Social Options', 'taaza-pro' ),
		) );

			$this->add_control( 'share_follow_type', array(
				'label'   => esc_html__( 'Share / Follow Type', 'taaza-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'share',
				'options' => array(
					''       => esc_html__('None', 'taaza-pro'),
					'share'  => esc_html__('Share', 'taaza-pro'),
					'follow' => esc_html__('Follow', 'taaza-pro'),
				),
				'description' => esc_html__( 'Choose between Share / Follow you would like to use.', 'taaza-pro' ),
			) );

			$this->add_control( 'social_icon_style', array(
				'label'   => esc_html__( 'Social Icon Style', 'taaza-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'simple'        => esc_html__( 'Simple', 'taaza-pro' ),
					'bgfill'        => esc_html__( 'BG Fill', 'taaza-pro' ),
					'brdrfill'      => esc_html__( 'Border Fill', 'taaza-pro' ),
					'skin-bgfill'   => esc_html__( 'Skin BG Fill', 'taaza-pro' ),
					'skin-brdrfill' => esc_html__( 'Skin Border Fill', 'taaza-pro' ),
				),
				'description' => esc_html__( 'This option is applicable for all buttons used in product summary.', 'taaza-pro' ),
				'condition'   => array( 'share_follow_type' => array ('share', 'follow') )
			) );

			$this->add_control( 'social_icon_radius', array(
				'label'   => esc_html__( 'Social Icon Radius', 'taaza-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'square'  => esc_html__( 'Square', 'taaza-pro' ),
					'rounded' => esc_html__( 'Rounded', 'taaza-pro' ),
					'circle'  => esc_html__( 'Circle', 'taaza-pro' ),
				),
				'condition'   => array(
					'social_icon_style' => array ('bgfill', 'brdrfill', 'skin-bgfill', 'skin-brdrfill'),
					'share_follow_type' => array ('share', 'follow')
				),
			) );

			$this->add_control( 'social_icon_inline_alignment', array(
				'label'        => esc_html__( 'Social Icon Inline Alignment', 'taaza-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'yes', 'taaza-pro' ),
				'label_off'    => esc_html__( 'no', 'taaza-pro' ),
				'default'      => '',
				'return_value' => 'true',
				'description'  => esc_html__( 'This option is applicable for all buttons used in product summary.', 'taaza-pro' ),
				'condition'   => array( 'share_follow_type' => array ('share', 'follow') )
			) );

		$this->end_controls_section();

	}

}