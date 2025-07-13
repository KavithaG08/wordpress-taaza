<?php

use UltimateBookingPro\Widgets\UltimateBookingProWidgetBase;
use Elementor\Controls_Manager;
use UltimateBookingPro\widgets\WeDesignTech_Common_Controls_Layout_Booking;
use Elementor\Utils;

class Elementor_Staff_Item extends UltimateBookingProWidgetBase {

    private $layout_type = 'both';
    private $cc_layout;
    private $cc_style;
	private $settings = array ();

    public function get_name() {
        return 'dt-staff-item';
    }

    public function get_title() {
        return $this->get_singular_name();
    }

    public function get_icon() {
		return 'eicon-apps';
	}

    public function get_style_depends() {
        wp_enqueue_style('booking-staff', ULTIMATEBOOKINGPRO_URL . '/widgets/assets/css/staff.css');
        return array(
			$this->get_name() => 'booking-staff'
		);
    }

    public function get_singular_name() {

        $singular_name = esc_html__('Staff', 'wdt-ultimate-booking');

        if( function_exists( 'ultimate_booking_pro_cs_get_option' ) ) :
            $singular_name = ultimate_booking_pro_cs_get_option( 'singular-staff-text', esc_html__('Staff', 'wdt-ultimate-booking') );
        endif;

        return $singular_name;
    }

    protected function register_controls() {

        $this->start_controls_section( 'dt_section_general', array(
            'label' => esc_html__( 'General', 'wdt-ultimate-booking'),
        ) );

            $this->add_control( 'staff_id', array(
                'label' => esc_html__( 'Enter Staff ID', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $this->dt_get_post_ids('dt_staff')
            ));

            $this->add_control( 'posts_per_page', array(
                'label' => esc_html__( 'Staff Per Page', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
            ));

            $this->add_control( 'type', array(
                'label' => esc_html__( 'Type', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'type1',
                'options' => array(
                    'type1' => esc_html__( 'Type - I', 'wdt-ultimate-booking' ),
                    'type2' => esc_html__( 'Type - II', 'wdt-ultimate-booking' ),
                    'type3' => esc_html__( 'Type - III', 'wdt-ultimate-booking' )
                )
            ));

            $this->add_control( 'excerpt', array(
                'label' => esc_html__( 'Show Excerpt?', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                    'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
                )
            ));

            $this->add_control( 'excerpt_length', array(
                'label' => esc_html__( 'Excerpt Length', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 12,
                'condition' => array (
						'excerpt' => 'yes'
					)
            ));

            $this->add_control( 'show_button', array(
                'label' => esc_html__( 'Show button?', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                    'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
                )
            ));

            $this->add_control( 'button_text', array(
                'label' => esc_html__( 'Button Text', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Book an appointment', 'wdt-ultimate-booking'),
            ));   

        $this->end_controls_section();

        $common_controls = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type);
        $common_controls->get_controls($this);
    }

    protected function render() {

        $settings = $this->get_settings();
        extract( $settings );

        $out = '';

        $classes = array ();
		array_push($classes, 'dt-sc-staff-items-container');
        $settings['classes'] = $classes;
        $settings['module_id'] = $this->get_id();
        $settings['module_class'] = 'staff-item';

        $layout_settings = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type, $settings);

        $layout_settings->set_settings($settings);
        
        $settings['module_layout_class'] = $layout_settings->get_item_class($settings['layout']);


       #Performing query...
       $args = array('post_type' => 'dt_staff', 'post__in' => $staff_id, 'posts_per_page' => $posts_per_page );

        $out .= $layout_settings->get_wrapper_start();  
        $the_query = new WP_Query($args);

            if($the_query->have_posts()):
                while($the_query->have_posts()): $the_query->the_post();
                    $PID = get_the_ID();

                    #Meta...
                    $staff_settings = get_post_meta($PID, '_custom_settings', true);
                    $staff_settings = is_array ( $staff_settings ) ? $staff_settings : array ();
        
                    if($settings['module_layout_class'] != '') {
                        $out.= '<div class="dt-sc-staff-item '.esc_attr($settings['module_layout_class']).'">';
                    }
                        if( $type == 'type1' || $type == 'type2') {
                            $out .= '<div class="dt-sc-staff-item '.$type.'">';
                         
                                $out .= '<div class="image">';
                                    $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
                                        $out .= '<div class="dt-image-item">';
                                            if(has_post_thumbnail()):
                                                $attr = array('title' => get_the_title(), 'alt' => get_the_title());
                                                $img_size = 'full';
                                                $out .= get_the_post_thumbnail($PID, $img_size, $attr);
                                            else:
                                                $img_pros = '700x800';

                                                if( $type == 'type2' ) {
                                                    $img_pros = '700x700';
                                                }
                                                $out .= '<img src="https://place-hold.it/'.$img_pros.'&text='.get_the_title().'" alt="'.get_the_title().'" />';
                                            endif;
                                        $out .= '</div>';
                                    $out .= '</a>';

                                    if( $type == 'type1' || $type == 'type2' ) {
                                        $out .= '<div class="dt-sc-staff-overlay">';
                                            $out .= '<div class="dt-sc-staff-social-container">';
                                                if( array_key_exists('staff-social', $staff_settings) ):
                                                    $socialicondr = do_shortcode($staff_settings['staff-social']);
                                                    $out .= '<div class="social-media">'.$socialicondr.'</div>';
                                                endif;
                                            $out .= '</div>';
                                        $out .= '</div>';
                                    }
                                    
                                $out .= '</div>';
                                
                                $out .= '<div class="dt-sc-staff-details">';

                                    $out .= '<div class="dt-sc-content-title">';
                                        $out .= '<h4 class="dt-sc-staff-title">';
                                            $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
                                        $out .= '</h4>';
                                    $out .= '</div>';

                                    if( array_key_exists('staff-role', $staff_settings) ):
                                        $out .= '<div class="dt-sc-content-sub-title">';
                                            $out .= '<span class="dt-sc-staff-role">'.$staff_settings['staff-role'].'</span>';
                                        $out .= '</div>';
                                    endif;

                                    // if( $type == 'type2' ) {
                                    //     if( array_key_exists('appointment_fs1', $staff_settings) && array_key_exists('appointment_fs5', $staff_settings) ):
                                    //         $out .= '<div class="dt-sc-content-working-time">';
                                    //             $out .= '<p>'.esc_html__('Monday to Friday : ', 'wdt-ultimate-booking').$staff_settings['appointment_fs1']['ultimate_booking_pro_monday_start'].' - '.$staff_settings['appointment_fs5']['ultimate_booking_pro_friday_end'].esc_html__(' hrs', 'wdt-ultimate-booking');
                                    //         $out .= '</div>';
                                    //     endif;
                                    // }

                                    if( $excerpt == 'yes' && $excerpt_length > 0 ):
                                        $out .= '<div class="dt-content-container">';
                                            $out .= '<p>'.wp_trim_words(get_the_excerpt(), $excerpt_length).'</p>';
                                        $out .= '</div>';
                                    endif;

                                    if( $show_button == 'yes' ):
                                        $out .= '<div class="dt-sc-content-button">';
                                            $out .= '<a class="dt-sc-button" href="'.get_permalink().'" title="'.get_the_title().'">'.esc_html($button_text).'</a>';
                                        $out .= '</div>';
                                    endif;
                                $out .= '</div>';

                            $out .= '</div>';
                        }

                        if( $type == 'type3') {
                            $out .= '<div class="dt-sc-staff-item '.$type.'">';

                                $out .= '<div class="dt-sc-staff-image">';
                                    $out .= '<a class="dt-sc-image-wrapper" href="'.get_permalink().'" title="'.get_the_title().'">';
                                        if(has_post_thumbnail()):
                                            $attr = array('title' => get_the_title(), 'alt' => get_the_title());
                                            $img_size = 'full';
                                            $out .= get_the_post_thumbnail($PID, $img_size, $attr);
                                        else:
                                            $img_pros = '1000x1100';
                                            $out .= '<img src="https://place-hold.it/'.$img_pros.'&text='.get_the_title().'" alt="'.get_the_title().'" />';
                                        endif;
                                    $out .= '</a>';
                                $out .= '</div>';

                                $out .= '<div class="dt-sc-staff-details">';

                                    if( array_key_exists('staff-role', $staff_settings) ):
                                        $out .= '<div class="dt-sc-content-sub-title">';
                                            $out .= '<span class="dt-sc-staff-role">'.$staff_settings['staff-role'].'</span>';
                                        $out .= '</div>';
                                    endif;

                                    $out .= '<div class="dt-sc-content-title">';
                                        $out .= '<h4 class="dt-sc-staff-title">';
                                            $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
                                        $out .= '</h4>';
                                    $out .= '</div>';

                                    if( $excerpt == 'yes' && $excerpt_length > 0 ):
                                        $out .= '<div class="dt-content-container">';
                                            $out .= '<h6 class="staff-opt-title">'.esc_html__('Description:','wdt-ultimate-booking').'</h6>';
                                            $out .= '<p>'.wp_trim_words(get_the_excerpt(), $excerpt_length).'</p>';
                                        $out .= '</div>';
                                    endif;

                                    $out .= '<div class="dt-sc-staff-special-container">';
                                        $out .= '<h6 class="staff-opt-title">'.esc_html__('Specialized In:','wdt-ultimate-booking').'</h6>';
                                        $out .= '<p class="staff-opt-value">'.$staff_settings['staff-specialization'].'</p>';
                                    $out .= '</div>';

                                    $out .= '<div class="dt-sc-staff-options-container">';
                                        if( array_key_exists('staff_opt_flds', $staff_settings) ):
                                            $out .= '<h6 class="staff-opt-title">'.$staff_settings['staff_opt_flds']['staff_opt_flds_title_1'].'</h6>';
                                            $out .= '<p class="staff-opt-value">'.$staff_settings['staff_opt_flds']['staff_opt_flds_value_1'].'</p>';
                                        endif;
                                    $out .= '</div>';

                                    $out .= '<div class="dt-sc-staff-social-container">';
                                        if( array_key_exists('staff-social', $staff_settings) ):
                                            $out .= '<h6 class="staff-opt-title">'.esc_html__('Social Media:','wdt-ultimate-booking').'</h6>';
                                            $socialicondr = do_shortcode($staff_settings['staff-social']);
                                            $out .= '<div class="social-media">'.$socialicondr.'</div>';
                                        endif;
                                    $out .= '</div>';

                                $out .= '</div>';

                            $out .= '</div>';
                        }
  
                    if($settings['module_layout_class'] != '') {
                        $out .= '</div>';
                    }
                endwhile;

                    $column_css = $layout_settings->get_column_css();

                    $this->get_style_depends($column_css);
                    
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        $layout_settings->get_column_edit_mode_css($column_css);
                        $out .= $layout_settings->get_column_edit_mode_css($column_css);
                    } else {
                        wp_add_inline_style('booking-staff', $column_css);
                    }

                wp_reset_postdata();
            else:
                $out .= '<div class="dt-sc-item-not-found">';
                    $out .= '<h2>'.esc_html__("Nothing Found.", "wdt-ultimate-booking").'</h2>';
                    $out .= '<p>'.esc_html__("Apologies, but no results were found for the requested archive.", "wdt-ultimate-booking").'</p>';
                $out .= '</div>';
            endif;
        $out .= $layout_settings->get_wrapper_end();

        echo "{$out}";
    }

    protected function _content_template() {
    }
}