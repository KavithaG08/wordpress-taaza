<?php
use UltimateBookingPro\Widgets\UltimateBookingProWidgetBase;
use Elementor\Controls_Manager;
use Elementor\Utils;

class Elementor_Reservation_Form extends UltimateBookingProWidgetBase {

    public function get_name() {
        return 'dt-room-search-form';
    }

    public function get_title() {
        return esc_html__('Room Search Form', 'wdt-ultimate-booking');
    }

    public function get_icon() {
		return 'eicon-apps';
	}

	public function get_style_depends() {
        wp_enqueue_style('booking-room-search-form', ULTIMATEBOOKINGPRO_URL . '/widgets/assets/css/room-search-form.css');
		return array(
			$this->get_name() => 'booking-room-search-form'
		);
    }

    protected function register_controls() {

        $this->start_controls_section( 'dt_section_general', array(
            'label' => esc_html__( 'General', 'wdt-ultimate-booking'),
        ) );

			$this->add_control( 'title', array(
				'label' => esc_html__( 'Title', 'wdt-ultimate-booking' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Appointment', 'wdt-ultimate-booking'),
			) );

			$this->add_control(	'el_class', array(
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
			$date_format = 'yy-mm-dd';
			$check_in    = '';
			$check_out   = '';

			// Set room capacity variables
			$type     = '';
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
				
				if ( isset( $query_options['type'] ) && ! empty( $query_options['type'] ) ) {
					$type = esc_attr( $query_options['type'] );
				}
				
				if ( isset( $query_options['amount'] ) && ! empty( $query_options['amount'] ) ) {
					$rooms = intval( $query_options['amount'] );
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

			$out = '<div class="dt-sc-appointment-wrapper '.esc_attr($el_class).'">';

				if (!empty($title)) {
					$out .= '<div class="dt-sc-title">';
						$out .= '<h2>'.$title.'</h2>';
					$out .= '</div>';
				}

				$out .= '<form class="dt-sc-reservation-form dt-appointment-form" name="reservation-schedule-form" method="get" action="'.esc_url( home_url( '/' )).'">';
				$out .= '<input type="hidden" name="s" value=""/>';
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
									for ($i = 1; $i <= 20; $i++) {
										$out .= '<option value="' . esc_attr($i) . '" ' . selected($rooms, $i, false) . '>' . 
												sprintf(_n('%s Room', '%s Rooms', $i, 'wdt-ultimate-booking'), $i) . 
												'</option>';
									}

								$out .= '</select>
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
										<select name="adult" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Adult', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Adults', 'wdt-ultimate-booking').'">';
											for ($i = 1; $i <= 20; $i++) {
												$out .= '<option value="'.esc_attr($i).'" '.selected($adult, $i, false).'>'.esc_html($i).'</option>';
											}
										$out .= '</select>
											</div>
											<div class="dt-sc-field-person dt-sc--children">
												<div class="dt-sc-e-label">
													<span class="dt-sc-e-label-text">'.esc_html__('Children', 'wdt-ultimate-booking').'</span>
													<span class="dt-sc-e-label-description">'.esc_html__('2-12 years old', 'wdt-ultimate-booking').'</span>
												</div>
												<select name="children" class="dt-select-person frm-control" data-singular-label="'.esc_attr__('Child', 'wdt-ultimate-booking').'" data-plural-label="'.esc_attr__('Children', 'wdt-ultimate-booking').'">';
													for ($i = 0; $i <= 20; $i++) {
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
													for ($i = 0; $i <= 20; $i++) {
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
			
				$out .= '<div class="dt-sc-room--field">
							<div class="dt-sc-full-width">
								<input name="subschedule" class="dt-sc-button dt-sc-full-width" value="'.esc_attr__('Check Availability', 'wdt-ultimate-booking').'" type="submit">
							</div>
						</div>';


				$out .= '</form>';

			$out .= '</div>';

		echo "{$out}";
	}

	protected function _content_template() {
    }
}