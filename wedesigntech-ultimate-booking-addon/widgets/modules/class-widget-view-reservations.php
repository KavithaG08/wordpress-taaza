<?php
use UltimateBookingPro\Widgets\UltimateBookingProWidgetBase;
use Elementor\Controls_Manager;
use Elementor\Utils;

class Elementor_View_Reservations extends UltimateBookingProWidgetBase {

    public function get_name() {
        return 'dt-view-reservations';
    }

    public function get_title() {
        return esc_html__('View Reservations', 'wdt-ultimate-booking');
    }

    public function get_icon() {
		return 'eicon-apps';
	}

    public function get_style_depends() {
        wp_enqueue_style('booking-view-reservation', ULTIMATEBOOKINGPRO_URL . '/widgets/assets/css/view-reservation.css');
        return array(
			$this->get_name() => 'booking-view-reservation'
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
        $out = '';

        if ( ! is_user_logged_in() ) {
            $out .= '<div class="dt-sc-info-box">'.esc_html__('Please logged in to view your complete reservations!', 'wdt-ultimate-booking').'</div>';
            $out .= wp_login_form( array( 'echo' => false, 'redirect' => get_permalink(), 'form_id' => 'viewreservelogin' ) );
        } else {
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $user_email = $current_user->user_email;

            $out .= '<div class="dt-sc-view-reservations">';
            $out .= '<p>'.esc_html__('Welcome ', 'wdt-ultimate-booking') . esc_html($current_user->display_name) . '!</p>';
            $out .= '<div class="dt-sc-title"><h3>'.esc_html__('Order Details:', 'wdt-ultimate-booking').'</h3></div>';

            // Fetch reservations
            $args = array(
                'post_type' => 'dt_customers',
                'order' => 'ASC',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => '_info',
                            'value' => serialize( strval( $user_id ) ),
                            'compare' => 'LIKE',
                        ),
                        array(
                            'key' => '_info',
                            'value' => serialize( intval( $user_id ) ),
                            'compare' => 'LIKE',
                        ),
                    ),
                    array(
                        'key' => '_info',
                        'value' => serialize( strval( $user_email ) ),
                        'compare' => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query( $args );
            if ( $query->have_posts() ) {
                $out .= '<div class="tbl-view-reservation-container">';
                $out .= '<table class="tbl-view-reservations">';
                $out .= '<tr>';
                $out .= '<th>'.esc_html__('Order #', 'wdt-ultimate-booking').'</th>';
                $out .= '<th>'.esc_html__('Date', 'wdt-ultimate-booking').'</th>';
                $out .= '<th>'.esc_html__('Room', 'wdt-ultimate-booking').'</th>';
                $out .= '<th>'.esc_html__('Amount', 'wdt-ultimate-booking').'</th>';
                $out .= '<th>'.esc_html__('Type', 'wdt-ultimate-booking').'</th>';
                $out .= '<th>'.esc_html__('Transaction ID', 'wdt-ultimate-booking').'</th>';
                $out .= '<th>'.esc_html__('Status', 'wdt-ultimate-booking').'</th>';
                $out .= '</tr>';

                while ( $query->have_posts() ) {
                    $query->the_post();
                    $ID = get_the_ID();
                    $payments = get_post_meta( $ID, '_info', true );

                    $out .= '<tr>';
                    $out .= '<td>'.get_the_title( $ID ).'</td>';
                    $out .= '<td>'.$payments['order_date'].'</td>';
                    $out .= '<td>'.$payments['order_room'].'</td>';
                    $out .= '<td>'.$payments['order_amount'].'</td>';
                    $out .= '<td>'.$payments['order_type'].'</td>';
                    $out .= '<td>'.$payments['order_transid'].'</td>';
                    $out .= '<td>'.$payments['order_status'].'</td>';
                    $out .= '</tr>';
                }

                $out .= '</table>';
                $out .= '</div>';
            } else {
                $out .= "<p>".esc_html__("Sorry you haven't made any purchases yet. Please follow the button to make your first reservation.", "wdt-ultimate-booking")."</p>";
                // $view_id = cs_get_option('appointment-pageid');
                $view_link = get_page_link($view_id);
                $out .= '<a href="'.esc_url($view_link).'" class="dt-sc-button" title="'.esc_attr__('Book Reservation', 'wdt-ultimate-booking').'">'.esc_html__('Book Reservation', 'wdt-ultimate-booking').'</a>';
            }

            $out .= '</div>'; 
        }

        echo $out; 
	}

	protected function _content_template() {
    }
}