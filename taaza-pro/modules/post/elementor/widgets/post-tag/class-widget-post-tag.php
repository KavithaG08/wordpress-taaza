<?php
use TaazaElementor\Widgets\TaazaElementorWidgetBase;
use Elementor\Controls_Manager;
use Elementor\Utils;

class Elementor_Post_Tags extends TaazaElementorWidgetBase {

    public function get_name() {
        return 'wdt-post-tags';
    }

    public function get_title() {
        return esc_html__('Post - Tags', 'taaza-pro');
    }

    protected function register_controls() {

        $this->start_controls_section( 'wdt_section_general', array(
            'label' => esc_html__( 'General', 'taaza-pro'),
        ) );

            $this->add_control( 'style', array(
                'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__('Style', 'taaza-pro'),
                'default' => '',
                'options' => array(
                    ''  => esc_html__('Default', 'taaza-pro'),
                    'meta-elements-space'		 => esc_html__('Space', 'taaza-pro'),
                    'meta-elements-boxed'  		 => esc_html__('Boxed', 'taaza-pro'),
                    'meta-elements-boxed-curvy'  => esc_html__('Curvy', 'taaza-pro'),
                    'meta-elements-boxed-round'  => esc_html__('Round', 'taaza-pro'),
					'meta-elements-filled'  	 => esc_html__('Filled', 'taaza-pro'),
					'meta-elements-filled-curvy' => esc_html__('Filled Curvy', 'taaza-pro'),
					'meta-elements-filled-round' => esc_html__('Filled Round', 'taaza-pro'),
                ),
            ) );

            $this->add_control( 'el_class', array(
                'type'        => Controls_Manager::TEXT,
                'label'       => esc_html__('Extra class name', 'taaza-pro'),
                'description' => esc_html__('Style particular element differently - add a class name and refer to it in custom CSS', 'taaza-pro')
            ) );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        extract($settings);

		$out = '';

        global $post;
        $post_id =  $post->ID;

        $Post_Style = taaza_get_single_post_style( $post_id );

        $template_args['post_ID'] = $post_id;
        $template_args['post_Style'] = $Post_Style;

		$out .= '<div class="entry-tags-wrapper '.$style.' '.$el_class.'">';
            $out .= taaza_get_template_part( 'post', 'templates/'.$Post_Style.'/parts/tag', '', $template_args );
		$out .= '</div>';

		echo $out;
	}

}