<?php
use UltimateBookingPro\Widgets\UltimateBookingProWidgetBase;
use Elementor\Controls_Manager;
use Elementor\Utils;

class Elementor_Reserve_Appointment extends UltimateBookingProWidgetBase {

    public function get_name() {
        return 'dt-room-booking-form';
    }

    public function get_title() {
        return esc_html__('Room Booking', 'wdt-ultimate-booking');
    }

    public function get_icon() {
		return 'eicon-apps';
	}

    public function get_style_depends() {
        wp_enqueue_style('room-booking-form', ULTIMATEBOOKINGPRO_URL . '/widgets/assets/css/room-booking-form.css');
        return array(
			$this->get_name() => 'room-booking-form'
		);
    }

    protected function register_controls() {

        $this->start_controls_section( 'dt_section_general', array(
            'label' => esc_html__( 'General', 'wdt-ultimate-booking'),
        ) );

            $this->add_control( 'title', array(
                'label' => esc_html__( 'Title', 'wdt-ultimate-booking' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Make an Appointment', 'wdt-ultimate-booking')
            ) );

            $this->add_control( 'el_class', array(
                'type' => Controls_Manager::TEXT,
                'label'       => esc_html__('Extra class name', 'wdt-ultimate-booking'),
                'description' => esc_html__('Style particular element differently - add a class name and refer to it in custom CSS', 'wdt-ultimate-booking')
            ) );

        $this->end_controls_section();
    }

    protected function render() {


		$settings = $this->get_settings();
		extract( $settings );

		$out = '';

        $query_options = isset( $_GET ) && ! empty( $_GET ) ? array_filter( $_GET ) : array();

        // Set dates variables
        $date_format = 'Y-m-d';
        $check_in    = '';
        $check_out   = '';

        // Set room capacity variables
        $rooms    = 1;
        $adult    = 1;
        $children = 0;
        $infant   = 0;

        // Override variables with forward query
        if ( ! empty( $query_options ) ) {
            
            if ( isset( $query_options['check_in'] ) && ! empty( $query_options['check_in'] ) ) {
                $check_in = date( $date_format, strtotime( $query_options['check_in'] ) );
            }
            
            if ( isset( $query_options['check_out'] ) && ! empty( $query_options['check_out'] ) ) {
                $check_out = date( $date_format, strtotime( $query_options['check_out'] ) );
            }
            
            if ( ! empty( $check_in ) && empty( $check_out ) ) {
                $check_out = date( $date_format, strtotime( $query_options['check_in'] . ' +1 days' ) );
            }
            
            if ( isset( $query_options['rooms'] ) && ! empty( $query_options['rooms'] ) ) {
                $rooms = intval( $query_options['rooms'] );
            }
            
            if ( isset( $query_options['adult'] ) && ! empty( $query_options['adult'] ) ) {
                $adult = intval( $query_options['adult'] );
            }
            
            if ( isset( $query_options['children'] ) && ! empty( $query_options['children'] ) ) {
                $children = intval( $query_options['children'] );
            }
            
            if ( isset( $query_options['infant'] ) && ! empty( $query_options['infant'] ) ) {
                $infant = intval( $query_options['infant'] );
            }
        }

        $current_date = date('Y-m-d'); 
        $next_date = date('Y-m-d', strtotime('+1 day'));

        $room_adult_price = get_post_meta(get_the_ID(), 'room-adult-price', true);
        $room_children_price = get_post_meta(get_the_ID(), 'room-children-price', true);
        $room_status = get_post_meta(get_the_ID(), 'room-status', true);
        $room_price_per_night = get_post_meta(get_the_ID(), 'room-price', true);
        $room_min_people = get_post_meta(get_the_ID(), 'room-min-people', true);
        $room_max_people = get_post_meta(get_the_ID(), 'room-max-people', true);

        $room_adult_price = !empty($room_adult_price) ? floatval($room_adult_price) : 0;
        $room_children_price = !empty($room_children_price) ? floatval($room_children_price) : 0;
        $room_price_per_night = !empty($room_price_per_night) ? floatval($room_price_per_night) : 0;
        $room_min_people = !empty($room_min_people) ? floatval($room_min_people) : 0;
        $room_max_people = !empty($room_max_people) ? floatval($room_max_people) : 0;

        $adult_count = isset($_GET['adult']) ? intval($_GET['adult']) : 0;
        $children_count = isset($_GET['children']) ? intval($_GET['children']) : 0;
        $total_people = $adult_count + $children_count;

        if ($total_people === 0) {
            $total_people = 10;
        }

        $custom_settings = get_post_meta(get_the_ID(), '_custom_settings', true);
        $extra_services = [];
        
        if (!empty($custom_settings)) {

            $settings = maybe_unserialize($custom_settings);
        
            for ($i = 1; $i <= 4; $i++) {
                $service_name = isset($settings['room_service_name_' . $i]) ? $settings['room_service_name_' . $i] : '';
                $service_type = isset($settings['room_service_type_' . $i]) ? $settings['room_service_type_' . $i] : '';
                $service_price = isset($settings['room_service_price_' . $i]) ? $settings['room_service_price_' . $i] : '';
                $service_pack = isset($settings['room_service_pack_' . $i]) ? $settings['room_service_pack_' . $i] : '';
        
                if (!empty($service_name) && $service_price !== '') {
                    $extra_services[] = [
                        'name' => $service_name,
                        'type' => $service_type,
                        'price' => !empty($service_price) ? floatval($service_price) : 0,
                        'pack' => $service_pack,
                    ];
                }
            }
        }

        $out = '<div class="dt-sc-appointment-wrapper '.esc_attr($el_class).'">';

            if (!empty($title)) {
                $out .= '<div class="dt-sc-title">';
                    $out .= '<h2>'.$title.'</h2>';
                $out .= '</div>';
            }

            $room_id = get_the_ID();
            $out .= '<form class="dt-sc-reservation-form dt-booking-form" name="roombooking-schedule-form" method="post" data-room-id="' . esc_attr($room_id) . '">';

            // $out .= '<input type="hidden" name="s" value=""/>';
            $out .= '<input type="hidden" name="room-adult-price" value="'.esc_attr($room_adult_price).'">';
            $out .= '<input type="hidden" name="room-children-price" value="'.esc_attr($room_children_price).'">';
            $out .= '<input type="hidden" name="room_status" class="room_status" value="'.esc_attr($room_status).'">';
            $out .= '<input type="hidden" name="room_price_per_night" class="room_price_per_night" value="'.esc_attr($room_price_per_night).'">';
            $out .= '<input type="hidden" name="room_min_people" class="room_min_people" value="'.esc_attr($room_min_people).'">';
            $out .= '<div class="dt-sc-room--field">
                        <div class="frm-group">
                            <div class="dt-field-label">
                                <label for="name">'.esc_html__('Check-in','wdt-ultimate-booking').'</label>
                            </div>
                            <div class="dt-sc-calendar-group">
                                <input type="text" id="roomcheckin" name="check_in" class="frm-control" name="check_in" value="' . esc_attr($check_in ?: $current_date) . '" required>
                                <span class="dt-icon-dt-calendar"><i class="icon-dt-calendar"></i></span>
                            </div>
                        </div>
                    </div>';

            $out .= '<div class="dt-sc-room--field">
                        <div class="frm-group">
                            <div class="dt-field-label">
                                <label for="name">'.esc_html__('Check-out','wdt-ultimate-booking').'</label>
                            </div>
                            <div class="dt-sc-calendar-group">
                                <input type="text" id="roomcheckout" name="check_out" class="frm-control" name="check_out" value="' . esc_attr($check_out ?: $next_date) . '" required>
                                <span class="dt-icon-dt-calendar"><i class="icon-dt-calendar"></i></span>
                            </div>
                        </div>
                    </div>';
            
            $out .= '<div class="dt-sc-room--field">
                        <div class="frm-group">
                            <div class="dt-field-label">
                                <label for="name">'.esc_html__('Rooms','wdt-ultimate-booking').'</label>
                            </div>
                            <select name="rooms" id="rooms" class="dt-select-room frm-control">
                            <option value=""></option>';
            
                                // Loop through room options
                                for ($i = 1; $i <= $rooms*2; $i++) {
                                    $out .= '<option value="' . esc_attr($i) . '" ' . selected($rooms, $i, false) . '>' . 
                                            sprintf(_n('%s Room', '%s Rooms', $i, 'wdt-ultimate-booking'), $i) . 
                                            '</option>';
                                }

                            $out .= '</select>
                            <input name="quantity" id="quantity" type="hidden" value="'.esc_attr( $rooms ).'">
                        </div>
                    </div>';

            $out .= '<div class="dt-sc-room--field">
                        <div class="frm-group">
                            <div class="dt-field-label">
                                <label for="persons">'.esc_html__('Guests', 'wdt-ultimate-booking').'</label>
                                <div class="dt-sc-guests-group">
                                    <input type="text" class="frm-control dt--guests" name="guests" value="" readonly />
                                    <span class="dt-drop--down"></span>
                                </div>
                            </div>
                            <div class="dt-sc-field-persons">
                                <div class="dt-sc-field-person dt-sc--adult">
                                    <div class="dt-sc-e-label">
                                        <span class="dt-sc-e-label-text">'.esc_html__('Adults', 'wdt-ultimate-booking').'</span>
                                    </div>
                                    <select name="adult" id="adult" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Adult', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Adults', 'wdt-ultimate-booking').'">';
                                        for ($i = 1; $i <= $room_max_people; $i++) {
                                            $out .= '<option value="'.esc_attr($i).'" '.selected($adult, $i, false).'>'.esc_html($i).'</option>';
                                        }
                                    $out .= '</select>
                                        </div>
                                        <div class="dt-sc-field-person dt-sc--children">
                                            <div class="dt-sc-e-label">
                                                <span class="dt-sc-e-label-text">'.esc_html__('Children', 'wdt-ultimate-booking').'</span>
                                                <span class="dt-sc-e-label-description">'.esc_html__('2-12 years old', 'wdt-ultimate-booking').'</span>
                                            </div>
                                            <select name="children" id="children" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Child', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Children', 'wdt-ultimate-booking').'">';
                                                for ($i = 0; $i <= $room_max_people; $i++) {
                                                    $out .= '<option value="'.esc_attr($i).'" '.selected($children, $i, false).'>'.esc_html($i).'</option>';
                                                }
                                    $out .= '</select>
                                        </div>
                                        <div class="dt-sc-field-person dt-sc--infant">
                                            <div class="dt-sc-e-label">
                                                <span class="dt-sc-e-label-text">'.esc_html__('Infants', 'wdt-ultimate-booking').'</span>
                                                <span class="dt-sc-e-label-description">'.esc_html__('0-2 years old', 'wdt-ultimate-booking').'</span>
                                            </div>
                                            <select name="infant" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Infant', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Infants', 'wdt-ultimate-booking').'">';
                                                for ($i = 0; $i <= $room_max_people; $i++) {
                                                    $out .= '<option value="'.esc_attr($i).'" '.selected($infant, $i, false).'>'.esc_html($i).'</option>';
                                                }
                                    $out .= '</select>
                                        </div>
                                        <div class="dt-sc-field-person dt-sc--button">
                                            <button type="button" class="dt-sc-button dt-sc-full-width">
                                                '.esc_html__('Done', 'wdt-ultimate-booking').'
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            if (!empty($extra_services)) {
                $out .= '<div class="dt-sc-room--field extra-services">
                            <div class="frm-group">
                                <div class="dt-field-label">
                                    <label>' . esc_html__('Extra Services', 'wdt-ultimate-booking') . '</label>
                                </div>
                                <ul>';

                                foreach ($extra_services as $index => $service) {
                                    $service_id = 'service_' . $index;
                                
                                    // Determine the price display
                                    if ($service['price'] === '0' || $service['price'] === '') {
                                        $price_display = esc_html($service['pack']);
                                    } elseif ($service['pack'] === 'add-price') {
                                        $price_display = ultimate_booking_pro_get_formatted_price($service['price']);
                                    } else {
                                        $pack_display = $service['pack'] === 'price-per-person' ? '/ per person' : ($service['pack'] ? ' / ' . esc_html($service['pack']) : '');
                                        $price_display = ultimate_booking_pro_get_formatted_price($service['price']) . $pack_display;
                                    }
                                
                                    // Check if service is mandatory
                                    $checked = $service['type'] === 'mandatory' ? 'checked' : '';
                                    $disabled = $service['type'] === 'mandatory' ? 'disabled' : '';
                                    $class_checked = $service['type'] === 'mandatory' ? 'option--checked' : '';
                                
                                    $out .= '<li class="' . esc_attr($class_checked) . '">
                                                <label for="' . esc_attr($service_id) . '">
                                                    <input type="checkbox" id="' . esc_attr($service_id) . '" name="extra_services[]" value="' . esc_attr($service['name']) . '" data-pack="'.esc_attr($service['pack']).'" data-price="' . esc_attr($service['price']) . '" ' . $checked . ' ' . $disabled . '>
                                                    <span class="service-label">'. esc_html($service['name']) . '</span> <span class="service-value">' . $price_display . '</span>
                                                </label>
                                            </li>';
                                }
                                
                                
                                $out .= '</ul>
                            </div>
                        </div>';

            }

            $symbol = ultimate_booking_pro_get_currency_symbol();

            $out .= '<div class="dt-sc-room--field dt-sc--price">
                        <div class="frm-group">
                            <div class="dt-field-label">
                                <label>' . esc_html__('Your Price', 'wdt-ultimate-booking') . '</label>
                            </div>
                            <div class="dt-sc-m-price">
                                <span class="dt-sc-m-price-currency">' . esc_html($symbol) . '</span>
                                <span class="dt-sc-m-price-value"></span>
                                <span class="dt-sc-m-price-description">' . esc_html__('/ per room', 'wdt-ultimate-booking') . '</span>
                                <input type="hidden" id="dt_room_price" name="dt_room_price" value="" />
                            </div>
                        </div>
                    </div>';           
                    
            $out .= ' <div class="dt-sc-room--field dt-sc--booking">';
                    if ($room_status == 0) {
                        $out .= '<div class="dt-sc-room-info">';
                        $out .= '<p>' . esc_html__('Sorry, this room is currently unavailable.', 'wdt-ultimate-booking') . '</p>';
                        $out .= '</div>';
                    }

                    $out .= '<div class="dt-sc-full-width dt-sc--booking-wrapper">
                        <input name="add-to-cart" id="add-to-cart" type="hidden" value="'.esc_attr( $room_id ).'">
                            <input name="roombook" class="dt-sc-button dt-sc-full-width" value="' . esc_attr__('Book Your Stay', 'wdt-ultimate-booking') . '" type="submit">
                        </div>
                    </div>';
            $out .= '<div class="dt-sc-room-response"></div>';

            $out .= '</form>';

        $out .= '</div>';

        echo "{$out}";

    }

    protected function _content_template() {
    }
}