<?php
use TaazaElementor\Widgets\TaazaElementorWidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Elementor_Post_Meta_Group extends TaazaElementorWidgetBase {

    public function get_name() {
        return 'wdt-post-meta-group';
    }

    public function get_title() {
        return esc_html__('Post - Meta Group', 'taaza-pro');
    }

    protected function register_controls() {

        $this->start_controls_section( 'wdt_section_general', array(
            'label' => esc_html__( 'General', 'taaza-pro'),
        ) );

            $content = new Repeater();
            $content->add_control( 'element_value', array(
                'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__('Element', 'taaza-pro'),
                'default' => 'author',
                'options' => array(
                    'author'      => esc_html__('Author', 'taaza-pro'),
                    'date'        => esc_html__('Date', 'taaza-pro'),
                    'comment'     => esc_html__('Comments', 'taaza-pro'),
                    'category'    => esc_html__('Categories', 'taaza-pro'),
                    'tag'         => esc_html__('Tags', 'taaza-pro'),
                    'social'      => esc_html__('Social Share', 'taaza-pro'),
                    'likes_views' => esc_html__('Likes & Views', 'taaza-pro'),
                ),
            ) );

            $this->add_control( 'blog_meta_position', array(
                'type'        => Controls_Manager::REPEATER,
                'label'       => esc_html__('Meta Group Positioning', 'taaza-pro'),
                'fields'      => array_values( $content->get_controls() ),
                'default'     => array(
                    array( 'element_value' => 'author' ),
                ),
                'title_field' => '{{{ element_value.replace( \'_\', \' \' ).replace( /\b\w/g, function( letter ){ return letter.toUpperCase() } ) }}}'
            ) );

            $this->add_control( 'style', array(
                'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__('Style', 'taaza-pro'),
                'default' => 'metagroup-space-separator',
                'options' => array(
                    'metagroup-space-separator'  => esc_html__('Space', 'taaza-pro'),
                    'metagroup-slash-separator'  => esc_html__('Slash', 'taaza-pro'),
                    'metagroup-vertical-separator'  => esc_html__('Vertical', 'taaza-pro'),
                    'metagroup-horizontal-separator'  => esc_html__('Horizontal', 'taaza-pro'),
                    'metagroup-dot-separator'  => esc_html__('Dot', 'taaza-pro'),
                    'metagroup-comma-separator'  => esc_html__('Comma', 'taaza-pro'),
                    'metagroup-elements-boxed'  => esc_html__('Boxed', 'taaza-pro'),
                    'metagroup-elements-boxed-curvy'  => esc_html__('Boxed Curvy', 'taaza-pro'),
                    'metagroup-elements-boxed-round'  => esc_html__('Boxed Round', 'taaza-pro'),
                    'metagroup-elements-filled'  => esc_html__('Filled', 'taaza-pro'),
                    'metagroup-elements-filled-curvy'  => esc_html__('Filled Curvy', 'taaza-pro'),
                    'metagroup-elements-filled-round'  => esc_html__('Filled Round', 'taaza-pro'),
                ),
                'description' => esc_html__('Select any one of meta group styling.', 'taaza-pro'),
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

		$newMEles = array();
		$meta_group_position = !empty( $blog_meta_position ) ? $blog_meta_position : explode( ',', $blog_meta_position );

		if( is_array( $meta_group_position[0] ) ) {
			foreach($meta_group_position as $key => $items) {
				$newMEles[$items['element_value']] = $items['element_value'];
			}
		} else {
			foreach($meta_group_position as $item) {
				$newMEles[$item] = $item;
			}
		}

		if( count( $newMEles ) >= 1 ) {

			$out .= '<div class="wdt-posts-meta-group '.$style.' '.$el_class.'">';

                $Post_Style = taaza_get_single_post_style( $post_id );

                $template_args['post_ID'] = $post_id;
                $template_args['post_Style'] = $Post_Style;
                $template_args = array_merge( $template_args, taaza_single_post_params() );

				foreach( $newMEles as $value ):

                    switch( $value ):

                        case 'likes_views':
                        case 'social':
                            $out .= taaza_get_template_part( 'post', 'templates/post-extra/'.$value, '', $template_args );
                            break;

                        default:
                            $out .= taaza_get_template_part( 'post', 'templates/'.$Post_Style.'/parts/'.$value, '', $template_args );
                            break;

                    endswitch;

				endforeach;

			$out .= '</div>';
		}

		echo $out;
    }

}