<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'MetaboxSlider' ) ) {
    class MetaboxSlider {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            add_filter( 'cs_metabox_options', array( $this, 'slider' ) );
        }

        function slider( $options ) {

            $post_types = apply_filters( 'taaza_slider_posts', array( 'page' ) );

            $options[] = array(
                'id'        => '_taaza_slider_settings',
                'title'     => esc_html('Slider', 'taaza-pro'),
                'post_type' => $post_types,
                'context'   => 'advanced',
                'priority'  => 'high',
                'sections'  => array(
                    array(
                        'name'   => 'layout_section',
                        'fields' => array(
                            array(
                                'id'      => 'slider-notice',
                                'type'    => 'notice',
                                'class'   => 'danger',
                                'content' => esc_html__('Slider tab works only if breadcrumb disabled.','taaza-pro'),
                                'class'   => 'margin-30 cs-danger'
                            ),
                            array(
                                'id'    => 'show',
                                'type'  => 'switcher',
                                'title' => esc_html__('Show Slider', 'taaza-pro' ),
                            ),
                            array(
                                'id'         => 'position',
                                'type'       => 'select',
                                'title'      => esc_html__('Position?', 'taaza-pro' ),
                                'options'    => array(
                                    'header-top-relative' => esc_html__('Top Header Relative','taaza-pro'),
                                    'header-top-absolute' => esc_html__('Top Header Absolute','taaza-pro'),
                                    'bottom-header'       => esc_html__('Bottom Header','taaza-pro'),
                                ),
                                'default'    => 'bottom-header',
                                'dependency' => array( 'show', '==', 'true' ),
                            ),
                            array(
                                'id'         => 'type',
                                'type'       => 'select',
                                'title'      => esc_html__('Slider', 'taaza-pro' ),
                                'options'    => array(
                                  ''                 => esc_html__('Select a slider','taaza-pro'),
                                  'layerslider'      => esc_html__('Layer slider','taaza-pro'),
                                  'revolutionslider' => esc_html__('Revolution slider','taaza-pro'),
                                  'customslider'     => esc_html__('Custom Slider Shortcode','taaza-pro'),
                                  'elementorsection' => esc_html__('Elementor Section','taaza-pro')
                                ),
                                'dependency' => array( 'show', '==', 'true' ),
                            ),
                            array(
                                'id'         => 'layerslider',
                                'type'       => 'select',
                                'title'      => esc_html__('Layer Slider', 'taaza-pro' ),
                                'options'    => $this->layersliders(),
                                'dependency' => array( 'show|type', '==|==', 'true|layerslider' ),
                            ),
                            array(
                                'id'         => 'revolutionslider',
                                'type'       => 'select',
                                'title'      => esc_html__('Revolution Slider', 'taaza-pro' ),
                                'options'    => $this->revolutionsliders(),
                                'dependency' => array( 'show|type', '==|==', 'true|revolutionslider' ),
                            ),
                            array(
                                'id'         => 'customslider',
                                'type'       => 'textarea',
                                'title'      => esc_html__('Custom Slider Code', 'taaza-pro' ),
                                'dependency' => array( 'show|type', '==|==', 'true|customslider' ),
                            ),
                            array(
                                'id'         => 'elementorsection',
                                'type'       => 'select',
                                'title'      => esc_html__('Elementor Section', 'taaza-pro' ),
                                'options'    => $this->elementor_library_list(),
                                'dependency' => array( 'show|type', '==|==', 'true|elementorsection' ),
                            )
                        )
                    )
                )
            );

            return $options;
        }

        function elementor_library_list() {
            $pagelist = get_posts( array(
                'post_type' => 'elementor_library',
                'showposts' => -1,
            ));

            if ( ! empty( $pagelist ) && ! is_wp_error( $pagelist ) ) {

                foreach ( $pagelist as $post ) {
                    $options[ $post->ID ] = $post->post_title;
                }

                $options[0] = esc_html__('Select Elementor Library', 'taaza-pro');
                asort($options);

                return $options;
            }
        }

        function layersliders() {
            $layerslider = array( esc_html__('Select a slider','taaza-pro') );

            if( class_exists('LS_Sliders') ) {
                $sliders = LS_Sliders::find(array('limit' => 50));

                if(!empty($sliders)) {
                    foreach($sliders as $key => $item){
                        $layerslider[ $item['id'] ] = $item['name'];
                    }
                }
            }

            return $layerslider;
        }

        function revolutionsliders() {
            $revolutionslider = array( '' => esc_html__('Select a slider','taaza-pro') );

            if( class_exists('RevSlider') ) {
                $sld     = new RevSlider();
                $sliders = $sld->getArrSliders();

                if(!empty($sliders)){
                    foreach($sliders as $key => $item) {
                        $revolutionslider[$item->getAlias()] = $item->getTitle();
                    }
                }
            }

            return $revolutionslider;
        }
    }
}

MetaboxSlider::instance();