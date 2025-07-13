<?php

use UltimateBookingPro\Widgets\UltimateBookingProWidgetBase;
use Elementor\Controls_Manager;
use UltimateBookingPro\widgets\WeDesignTech_Common_Controls_Layout_Booking;
use Elementor\Utils;

class Elementor_Room_Item extends UltimateBookingProWidgetBase {

    private $layout_type = 'both';
    private $cc_layout;
    private $cc_style;
	private $settings = array ();

    public function get_name() {
        return 'dt-room-item';
    }

    public function get_title() {
        return $this->get_singular_name();
    }

    public function get_icon() {
        return 'eicon-apps';
    }

    public function get_script_depends() {
        $script_depends = array('dt-reservation', 'wdt-ultimate-booking-carousel-js');

        return $script_depends;
    }

    public function get_style_depends() {
        if (defined('ULTIMATEBOOKINGPRO_URL')) {
            wp_enqueue_style('booking-rooms', ULTIMATEBOOKINGPRO_URL . '/widgets/assets/css/rooms.css');  
        }
        return array(
			$this->get_name() => 'booking-rooms'
		);
    }

    public function get_singular_name() {
        $singular_name = esc_html__('Room', 'wdt-ultimate-booking');
        if (function_exists('ultimate_booking_pro_cs_get_option')) {
            $singular_name = ultimate_booking_pro_cs_get_option('singular-room-text', esc_html__('Room', 'wdt-ultimate-booking'));
        }
        return $singular_name;
    }

    protected function register_controls() {

        $this->start_controls_section( 'dt_section_general', array(
            'label' => esc_html__( 'General', 'wdt-ultimate-booking'),
        ) );

            $this->add_control( 'room_id', array(
                'label' => esc_html__( 'Room IDs', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $this->dt_get_post_ids('dt_room')
            ));

            $this->add_control( 'type', array(
                'label' => esc_html__( 'Type', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'type1',
                'options' => array(
                    'type1' => esc_html__( 'Type - I', 'wdt-ultimate-booking' ),
                    'type2' => esc_html__( 'Type - II', 'wdt-ultimate-booking' ),
                )
            ));

            $this->add_control( 'posts_per_page', array(
                'label' => esc_html__( 'Room Per Page', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
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
            ));

            $this->add_control( 'meta', array(
                'label' => esc_html__( 'Show Meta?', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'wdt-ultimate-booking' ),
                    'no' => esc_html__( 'No', 'wdt-ultimate-booking' ),
                )
            ));

            $this->add_control( 'amenity_item', array(
                'label' => esc_html__( 'Show amenity?', 'wdt-ultimate-booking' ),
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
                'default' => esc_html__('Read More', 'wdt-ultimate-booking'),
            ));

        $this->end_controls_section();
        $common_controls = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type);
        $common_controls->get_controls($this);
    }

    protected function render() {

        $settings = $this->get_settings();
        extract( $settings );
        $out = '';

        $settings['module_id'] = $this->get_id();
        $settings['module_class'] = 'rooms-item';
        
        $layout_settings = new WeDesignTech_Common_Controls_Layout_Booking($this->layout_type, $settings);

        $layout_settings->set_settings($settings);
        
        $settings['module_layout_class'] = $layout_settings->get_item_class($settings['layout']);

        #Performing query...
        $args = array('post_type' => 'dt_room', 'post__in' => $room_id, 'posts_per_page' => $settings['posts_per_page'] );

        $out .= '<div class="dt-sc-room-items-container" data-moduleid="'.$settings['module_id'].'" >';
            $out .= $layout_settings->get_wrapper_start();

                $the_query = new \WP_Query($args);
                if($the_query->have_posts()):
                        
                        if($type == 'type1'){
                            while($the_query->have_posts()): $the_query->the_post();
                            
                                $PID = get_the_ID();

                                // Get Amenities
                                $amenities = wp_get_post_terms($PID, 'dt_room_amenity');

                                #Meta...
                                $room_settings = get_post_meta($PID, '_custom_settings', true);
                                $room_settings = is_array ( $room_settings ) ? $room_settings : array ();
                                if($settings['module_layout_class'] != '') {
                                    $out.= '<div class="'.esc_attr($settings['module_layout_class']).'">';
                                }

                                    $out .= '<div class="dt-sc-room-item '.$settings['type'].'">';

                                        $out .= '<div class="image">';
                                            $out .= '<div class="image-item">';
                                                if(has_post_thumbnail()):
                                                    $attr = array('title' => get_the_title(), 'alt' => get_the_title());
                                                    $img_size = 'full';
                                                    $out .= get_the_post_thumbnail($PID, $img_size, $attr);
                                                else:
                                                    $img_pros = '615x560';
                                                    $out .= '<img src="https://place-hold.it/'.$img_pros.'&text='.get_the_title().'" alt="'.get_the_title().'" />';
                                                endif;
                                            $out .= '</div>';
                                        $out .= '</div>';
                                            
                                        $out .= '<div class="room-details">';

                                            $out .= '<div class="dt-sc-content-title-group">';
                                                $out .= '<div class="dt-sc-content-title">';
                                                    $out .= '<h4 class="dt-sc-room-title">';
                                                        $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
                                                    $out .= '</h4>';
                                                $out .= '</div>';

                                                if( array_key_exists('room-price', $room_settings) && $room_settings['room-price'] != '' ):
                                                    $out .= '<div class="dt-sc-room-price-item">';
                                                        $out .= '<span class="dt-sc-room-price">'.$room_settings['room-price'].ultimate_booking_pro_get_currency_symbol().esc_html__(' / Per Night ', 'wdt-ultimate-booking').'</span>';
                                                    $out .= '</div>';
                                                endif;
                                            $out .= '</div>';

                                            if($meta == 'yes' ):
                                                $out .= '<div class="dt-sc-rooms-meta-wrapper">';
                                                    if( array_key_exists('room-size', $room_settings) && $room_settings['room-size'] != '' ):
                                                        $out .= '<div class="dt-sc-rooms-size-meta-wrapper">';
                                                            $out .= '<span class="dt-sc-rooms-icon">';
                                                                $out .= '<i class="icon-dt-sq-fit"></i>';
                                                            $out .= '</span>';
                                                            $out .= '<span class="dt-sc-rooms-size">'.esc_attr( $room_settings['room-size'] ).'</span>';
                                                        $out .= '</div>';
                                                    endif;
                                                    if( array_key_exists('room-max-people', $room_settings) && $room_settings['room-max-people'] != '' ):
                                                        $out .= '<div class="dt-sc-rooms-people-meta-wrapper">';
                                                            $out .= '<span class="dt-sc-rooms-icon">';
                                                                $out .= '<i class="icon-dt-guests"></i>';
                                                            $out .= '</span>';
                                                            $out .= '<span class="dt-sc-rooms-people">'.esc_attr( $room_settings['room-max-people'] ). esc_html__(' Guests', 'wdt-ultimate-booking') .'</span>';
                                                        $out .= '</div>';
                                                    endif;
                                                    if( array_key_exists('room-duration', $room_settings) && $room_settings['room-duration'] != '' ):
                                                        $out .= '<div class="dt-sc-rooms-duration-meta-wrapper">';
                                                            $out .= '<span class="dt-sc-rooms-icon">';
                                                                $out .= '<i class="icon-dt-clock-alt"></i>';
                                                            $out .= '</span>';
                                                            $out .= '<span class="dt-sc-rooms-duration">'.ultimate_booking_pro_duration_to_string( $room_settings['room-duration'] ).'</span>';
                                                        $out .= '</div>';
                                                    endif;
                                                $out .= '</div>';
                                            endif;

                                            if( $excerpt == 'yes' && $excerpt_length > 0 ):
                                                $out .= '<div class="dt-sc-content-description">'.ultimate_booking_pro_post_excerpt($excerpt_length).'</div>';
                                            endif;

                                            if($button_text != ''):
                                                $out .= '<div class="dt-sc-content-button">';
                                                    $out .= '<a class="dt-sc-button" href="'.get_permalink().'" title="'.get_the_title().'">'.esc_html($button_text).'</a>';
                                                $out .= '</div>';
                                            endif;

                                            if($amenity_item == 'yes' ):
                                                $out .= '<hr class="dt-sc-hr" />';
                                            endif;

                                            if(!empty($amenities) && $amenity_item == 'yes' ):
                                                $out .= '<div class="dt-sc-room-amenities">';
                                                    $out .= '<div class="dt-sc-room-amenities-item">';
                                                    foreach ($amenities as $amenity):
                                                        $amenity_icon = get_term_meta($amenity->term_id, 'dt-taxonomy-icon', true);
                                                        $out .= '<div class="dt-sc-room-amenity">';
                                                        if (!empty($amenity_icon)) {
                                                            $out .= '<i class="'.esc_attr($amenity_icon).'"></i>';
                                                        }
                                                        $out .= '</div>';
                                                    endforeach;
                                                    $out .= '</div>';
                                                $out .= '</div>';
                                            endif;

                                        $out .= '</div>';
                                    $out .= '</div>';

                                if($settings['module_layout_class'] != '') {
                                    $out .= '</div>';
                                }
                            endwhile;
                        } else {

                            while($the_query->have_posts()): $the_query->the_post();
                            
                            $PID = get_the_ID();

                            // Get Amenities
                            $amenities = wp_get_post_terms($PID, 'dt_room_amenity');

                            #Meta...
                            $room_settings = get_post_meta($PID, '_custom_settings', true);
                            $room_settings = is_array ( $room_settings ) ? $room_settings : array ();
                            if($settings['module_layout_class'] != '') {
                                $out.= '<div class="'.esc_attr($settings['module_layout_class']).'">';
                            }

                                $out .= '<div class="dt-sc-room-item '.$settings['type'].'" data-id="'.$PID.'">';

                                    $out .= '<div class="image">';
                                        $out .= '<div class="image-item">';
                                            $out .='<a href="'.get_permalink().'" >';
                                                if(has_post_thumbnail()):
                                                    $attr = array('title' => get_the_title(), 'alt' => get_the_title());
                                                    $img_size = 'full';
                                                    $out .= get_the_post_thumbnail($PID, $img_size, $attr);
                                                else:
                                                    $img_pros = '615x560';
                                                    $out .= '<img src="https://place-hold.it/'.$img_pros.'&text='.get_the_title().'" alt="'.get_the_title().'" />';
                                                endif;
                                            $out .='</a>';
                                        $out .= '</div>';
                                    $out .= '</div>';
                                    
                                    $out .= '<div class="room-details">';
                                        $out .= '<div class="dt-sc-content-title">';
                                            $out .= '<h4 class="dt-sc-room-title">';
                                                $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
                                            $out .= '</h4>';
                                        $out .= '</div>';

                                        if($meta == 'yes' ):
                                            $out .= '<div class="dt-sc-rooms-meta-wrapper">';
                                                if( array_key_exists('room-max-people', $room_settings) && $room_settings['room-max-people'] != '' ):
                                                    $out .= '<div class="dt-sc-rooms-people-meta-wrapper">';
                                                        $out .= '<span class="dt-sc-rooms-people">'.esc_attr( $room_settings['room-max-people'] ). esc_html__(' guest room', 'wdt-ultimate-booking') .'</span>';
                                                    $out .= '</div>';
                                                endif;
                                            $out .= '</div>';
                                        endif;
                                        
                                        if( $excerpt == 'yes' && $excerpt_length > 0 ):
                                            $out .= '<div class="dt-sc-content-description">'.ultimate_booking_pro_post_excerpt($excerpt_length).'</div>';
                                        endif;

                                        if(!empty($amenities) && $amenity_item == 'yes' ):
                                            $out .= '<div class="dt-sc-room-amenities">';
                                                foreach ($amenities as $amenity):
                                                    $amenity_icon = get_term_meta($amenity->term_id, 'dt-taxonomy-icon', true);
                                                    $out .= '<div class="dt-sc-room-amenity">';
                                                    if (!empty($amenity_icon)) {
                                                        $out .= '<i class="'.esc_attr($amenity_icon).'"></i>';
                                                    }
                                                    $out .= '</div>';
                                                endforeach;
                                            $out .= '</div>';
                                        endif;

                                        if($button_text != ''):
                                            $out .= '<div class="dt-sc-content-button">';
                                                $out .= '<a class="dt-sc-button dt-sc-button-textual" href="'.get_permalink().'" title="'.get_the_title().'">'.esc_html($button_text).'</a>';
                                            $out .= '</div>';
                                        endif;


                                    $out .= '</div>';

                                $out .= '</div>';  
                            
                            if($settings['module_layout_class'] != '') {
                                $out .= '</div>';
                            }
                            endwhile;
                        } 

                    $column_css = $layout_settings->get_column_css();

                    $this->get_style_depends($column_css);
                    
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        $layout_settings->get_column_edit_mode_css($column_css);
                        $out .= $layout_settings->get_column_edit_mode_css($column_css);
                    } else {
                        wp_add_inline_style('booking-rooms', $column_css);
                    }

                    wp_reset_postdata();

                else:
                    $out .= '<div class="dt-sc-item-not-found">';
                        $out .= '<h2>'.esc_html__("Nothing Found.", "wdt-ultimate-booking").'</h2>';
                        $out .= '<p>'.esc_html__("Apologies, but no results were found for the requested archive.", "wdt-ultimate-booking").'</p>';
                    $out .= '</div>';
                endif;

            $out .= $layout_settings->get_wrapper_end();

        $out .= '</div>';

        echo "{$out}";
    }

    protected function _content_template() {
    }
}