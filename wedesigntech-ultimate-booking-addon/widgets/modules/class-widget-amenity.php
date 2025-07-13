<?php

use UltimateBookingPro\Widgets\UltimateBookingProWidgetBase;
use Elementor\Controls_Manager;
use UltimateBookingPro\widgets\WeDesignTech_Common_Controls_Layout_Booking;
use Elementor\Utils;


class Elementor_Amenity extends UltimateBookingProWidgetBase {

    private $layout_type = 'both';
    private $cc_layout;
    private $cc_style;

    public function get_name() {
        return 'dt-amenity-item';
    }

    public function get_title() {
        return esc_html__('Amenity List', 'wdt-ultimate-booking');
    }

    public function get_icon() {
        return 'eicon-apps';
    }

    public function get_style_depends() {
        wp_enqueue_style('booking-amenity', ULTIMATEBOOKINGPRO_URL . '/widgets/assets/css/amenity.css');
		return array(
			$this->get_name() => 'booking-amenity'
		);
    }

    protected function register_controls() {
        $this->start_controls_section('dt_section_general', [
            'label' => esc_html__('General', 'wdt-ultimate-booking'),
        ]);

        $this->add_control('amenity_id', array(
            'label' => esc_html__('Select Amenities', 'wdt-ultimate-booking'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => $this->get_terms_as_options('dt_room_amenity'),
        ));

        $this->add_control('posts_per_page', array(
            'label' => esc_html__('Amenities Per Page', 'wdt-ultimate-booking'),
            'type' => Controls_Manager::NUMBER,
            'default' => 3,
        ));

        $this->add_control( 'type', array(
            'label' => esc_html__( 'Type', 'wdt-ultimate-booking' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'type1',
            'options' => array(
                'type1' => esc_html__( 'Type - I', 'wdt-ultimate-booking' ),
                'type2' => esc_html__( 'Type - II', 'wdt-ultimate-booking' )
            )
        ));

        $this->add_responsive_control( 'content_gap', array(
            'label' => esc_html__( 'Gap', 'wdt-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array('px', '%', 'em', 'rem', 'custom'),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 40,
                    'step' => 5,
                ),
                '%' => array(
                    'min' => 0,
                    'max' => 10,
                ),
            ),
            'default' => array(
                'unit' => 'px',
                'size' => 30,
            ),
            'selectors' => array(
                    '{{WRAPPER}} .dt-sc-amenity-item' => 'gap: {{SIZE}}{{UNIT}};',
            ),
            'condition' => array (
                'type' => 'type2'
            ),
        ));

        $this->add_responsive_control( 'icon_size', array(
            'label' => esc_html__( 'Icon Size', 'wdt-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array('px', '%', 'em', 'rem', 'custom'),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 100,
                    'step' => 5,
                ),
                '%' => array(
                    'min' => 0,
                    'max' => 100,
                ),
            ),
            'default' => array(
                'unit' => 'px',
                'size' => 70,
            ),
            'selectors' => array(
                    '{{WRAPPER}} .dt-sc-amenity-item .dt-sc-amenity-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
            ),
            'condition' => array (
                'type' => 'type2'
            ),
        ));

        $this->add_responsive_control( 'title_size', array(
            'label' => esc_html__( 'Title Size', 'wdt-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array('px', '%', 'em', 'rem', 'custom'),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 100,
                    'step' => 2,
                ),
                '%' => array(
                    'min' => 0,
                    'max' => 100,
                ),
            ),
            'default' => array(
                'unit' => 'px',
                'size' => 28,
            ),
            'selectors' => array(
                    '{{WRAPPER}} .dt-sc-amenity-item .dt-sc-amenity-item-inner .dt-sc-amenity-title' => 'font-size: {{SIZE}}{{UNIT}};',
            ),
        ));

        $this->add_control( 'excerpt', array(
            'label' => esc_html__( 'Show Excerpt?', 'wdt-ultimate-booking' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'yes',
            'options' => array(
                'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
            )
        ));

        $this->add_control( 'show_button', array(
            'label' => esc_html__( 'Show Button', 'wdt-ultimate-booking' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'yes',
            'options' => array(
                'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
            )
        ));

        $this->add_control( 'button_text', array(
            'label' => esc_html__( 'Button Text', 'wdt-ultimate-booking' ),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Read More', 'wdt-ultimate-booking'),
        ));

        $this->end_controls_section();

        $common_controls = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type);
        $common_controls->get_controls($this);
    }

    protected function render() {
        $settings = $this->get_settings();
        extract( $settings );

        $amenity_id = isset($settings['amenity_id']) ? $settings['amenity_id'] : [];
        $posts_per_page = isset($settings['posts_per_page']) ? $settings['posts_per_page'] : 3;

        $out = '';

        $classes = array ();
		array_push($classes, 'dt-sc-amenity-items-container');
        $settings['classes'] = $classes;
        $settings['module_id'] = $this->get_id();
        $settings['module_class'] = 'amenity-item';

        $layout_settings = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type, $settings);

        $layout_settings->set_settings($settings);
        
        $settings['module_layout_class'] = $layout_settings->get_item_class($settings['layout']);

        $out .= $layout_settings->get_wrapper_start();

        // Query Terms
        $args = [
            'taxonomy'   => 'dt_room_amenity',
            'hide_empty' => false,
            'include'    => !empty($amenity_id) ? $amenity_id : [],
            'number'     => $posts_per_page,
        ];

        $terms = get_terms($args);

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $term_link = get_term_link($term);
                $term_description = term_description($term->term_id, $term->taxonomy);
                $map_image_url = get_term_meta($term->term_id, 'dt-taxonomy-map-image-url', true);
                $icon_url = get_term_meta($term->term_id, 'dt-taxonomy-icon', true);


                if($settings['module_layout_class'] != '') {
                    $out.= '<div class="'.esc_attr($settings['module_layout_class']).'">';
                }

                    $out .= '<div class="dt-sc-amenity-item '.$type.'">';

                        if ($type == 'type1' && !empty($map_image_url)) {
                            $out .= '<div class="dt-sc-amenity-image">';
                                $out .= '<img src="'. esc_url($map_image_url) .'" alt="'. esc_attr($term->name) .' Image">';
                            $out .= '</div>';

                            if ($show_button == 'yes' && $type == 'type1') {
                                $out .= '<div class="dt-sc-content-button">';
                                    $out .= '<a class="dt-sc-button" href="' . esc_url($term_link) . '">' .esc_html($button_text). '</a>';
                                $out .= '</div>';
                            }

                        } else if ($type == 'type1') {
                            $out .= '<div class="dt-sc-amenity-image">';
                                $out .= '<img src="https://place-hold.it/1000x1000&text='. esc_attr($term->name) .'" alt="'. esc_attr($term->name) .' Image">';
                            $out .= '</div>';

                            if ($show_button == 'yes' && $type == 'type1') {
                                $out .= '<div class="dt-sc-content-button">';
                                    $out .= '<a class="dt-sc-button" href="' . esc_url($term_link) . '">' .esc_html($button_text). '</a>';
                                $out .= '</div>';
                            }

                        } else if ($type == 'type2' && !empty($icon_url)) {
                            $out .= '<div class="dt-sc-amenity-icon">';
                                $out .= '<i class="'.$icon_url.'" ></i>';
                            $out .= ' </div>';
                        }

                        $out .= '  <div class="dt-sc-amenity-item-inner">';

                            $out .= '<div class="dt-sc-content-title">';
                                $out .= '<h4 class="dt-sc-amenity-title">';
                                    $out .= '<a href="'. esc_url($term_link) .'" title="'. esc_attr($term->name) .'">'.esc_html($term->name). '</a>';
                                $out .= '</h4>';
                            $out .= '</div>';

                            if (!empty($term_description) && $excerpt == 'yes') {
                                $out .= '<div class="dt-content-container">'. wp_kses_post($term_description) . '</div>';
                            }

                            if ($show_button == 'yes' && $type == 'type2') {
                                $out .= '<div class="dt-sc-content-button">';
                                    $out .= '<a class="dt-sc-button" href="' . esc_url($term_link) . '">' .esc_html($button_text). '</a>';
                                $out .= '</div>';
                            }

                        $out .= '</div>';

                    $out .= '</div>';

                if($settings['module_layout_class'] != '') {
                    $out .= '</div>';
                }
            }

            $column_css = $layout_settings->get_column_css();

            $this->get_style_depends($column_css);
            
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $layout_settings->get_column_edit_mode_css($column_css);
                $out .= $layout_settings->get_column_edit_mode_css($column_css);
            } else {
                wp_add_inline_style('booking-amenity', $column_css);
            }

        } else {
            $out .= '<div class="dt-sc-item-not-found">';
            $out .= '  <h2>' . esc_html__('No Amenities Found.', 'wdt-ultimate-booking') . '</h2>';
            $out .= '  <p>' . esc_html__('There are no amenities available at the moment.', 'wdt-ultimate-booking') . '</p>';
            $out .= '</div>';
        }

        $out .= $layout_settings->get_wrapper_end();

        echo $out;
    }

    protected function _content_template() {}
}
