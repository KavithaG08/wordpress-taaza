<?php

if ( ! class_exists( 'WDT_Ultimate_Booking_Integration' ) ) {

    class WDT_Ultimate_Booking_Integration {

        private static $instance;

        public function __construct() {
            $this->init_hooks();
        }

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function init_hooks() {
            add_action('save_post', array($this, 'dt_room_create_woocommerce_product'),20);

            add_action('wp_ajax_dt_validate_booking', array($this, 'handle_room_booking'));
            add_action('wp_ajax_nopriv_dt_validate_booking', array($this, 'handle_room_booking'));

            add_action('woocommerce_checkout_create_order_line_item', array($this, 'save_room_booking_to_order'), 10, 4);

            add_filter('woocommerce_return_to_shop_redirect', array($this, 'custom_return_to_checkout_link'));
            add_filter( 'woocommerce_order_item_name', array($this, 'change_order_item_permalink_in_order'), 10, 2 );
        }

        function dt_room_create_woocommerce_product($post_id) {
            
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        
            if (get_post_type($post_id) != 'dt_room') return;
        
            $existing_product_id = get_post_meta($post_id, '_dt_room_product_id', true);
        
            
            if ($existing_product_id) {
                $product = wc_get_product($existing_product_id);
        
                $this->update_product_details($product, $post_id);
        
                wp_update_post(array(
                    'ID' => $product->get_id(),
                    'post_status' => 'publish', 
                ));

                return;
            }
        
            $room = get_post($post_id);
            $price = get_post_meta($post_id, 'room-price', true);
    
            $product = new WC_Product_Simple();
            
            $product->set_name($room->post_title); 
            $product->set_price($price);
            $product->set_regular_price($price);
            $product->set_description($room->post_content);
            $product->set_status('publish');
            $product->set_manage_stock(false);
            $this->set_product_image($product, $post_id);
        
            $product->save();
        
            update_post_meta($post_id, '_dt_room_product_id', $product->get_id());
        
            wp_update_post(array(
                'ID' => $product->get_id(),
                'post_status' => 'publish',
            ));
        }
        
        private function update_product_details($product, $post_id) {

            $room = get_post($post_id);
            $price = get_post_meta($post_id, 'room-price', true);
        
            $product->set_name($room->post_title); 
            $product->set_price($price);
            $product->set_regular_price($price);
            $product->set_description($room->post_content);
        
            $this->set_product_image($product, $post_id);
        
            $product->save();
        
            wp_update_post(array(
                'ID' => $product->get_id(),
                'post_status' => 'publish', 
            ));
        }
        
        private function set_product_image($product, $post_id) {

            $thumbnail_id = get_post_thumbnail_id($post_id);
        
            if ($thumbnail_id) {
                $product->set_image_id($thumbnail_id);
            }
        }
             
        public function handle_room_booking() {
            
            if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'dt_validate_booking')) {
                wp_send_json_error(array('message' => 'Invalid security token.'));
            }

            $room_id        = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
            $check_in       = isset($_POST['check_in']) ? sanitize_text_field($_POST['check_in']) : '';
            $check_out      = isset($_POST['check_out']) ? sanitize_text_field($_POST['check_out']) : '';
            $adults         = isset($_POST['adults']) ? intval($_POST['adults']) : 1;
            $children       = isset($_POST['children']) ? intval($_POST['children']) : 0;
            $extra_services = isset($_POST['extra_services']) ? $_POST['extra_services'] : [];
            $quantity       = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            $room_price     = isset($_POST['dt_room_price']) ? floatval($_POST['dt_room_price']) : 0;

            
            $product_id = get_post_meta($room_id, '_dt_room_product_id', true);

            if (empty($product_id)) {
                wp_send_json_error(array('message' => 'Invalid room or product.'));
            }

            $product = wc_get_product($product_id);

            if ($product) {
                
                $product->set_price($room_price);  
                $product->set_regular_price($room_price);  
                $product->set_description('Updated description for ' . $room_id); 

                $product->save(); 
            }

            $cart_item_data = array(
                'room_id'        => $room_id,
                'check_in'       => $check_in,
                'check_out'      => $check_out,
                'adults'         => $adults,
                'children'       => $children,
                'extra_services' => $extra_services,
                'room_price'     => $room_price,
            );

            $added = WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $cart_item_data);

            if ($added) {
                wp_send_json_success(array(
                    'message'   => 'Room added to the cart.',
                    'cart_url'  => wc_get_cart_url(), 
                ));
            } else {
                wp_send_json_error(array('message' => 'Failed to add room to the cart.'));
            }
        }

        function custom_return_to_checkout_link() {
            
            return home_url('/dt_room');
    
        }

        function change_order_item_permalink_in_order( $product_name, $item ) {
            $product = $item->get_product();
            
            if ( $product ) {
                $product_name = $product->get_name();
        
                $args = array(
                    'post_type'      => 'dt_room',      
                    'posts_per_page' => 1,                
                    'post_status'    => 'publish',       
                    'title'          => $product_name,   
                );
        
                $room_query = new WP_Query( $args );
        
                if ( $room_query->have_posts() ) {
                    $room_query->the_post();
                    $room_permalink = get_permalink();
                    $product_name = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $room_permalink ), $product_name );
                }
                wp_reset_postdata();
            }
        
            return $product_name;
        } 

        public function save_room_booking_to_order($item, $cart_item_key, $values, $order) {
            if (isset($values['room_id'])) {
                $item->add_meta_data('Room ID', $values['room_id']);
            }
            if (isset($values['check_in'])) {
                $item->add_meta_data('Check-In Date', $values['check_in']);
            }
            if (isset($values['check_out'])) {
                $item->add_meta_data('Check-Out Date', $values['check_out']);
            }
            if (isset($values['adults'])) {
                $item->add_meta_data('Adults', $values['adults']);
            }
            if (isset($values['children'])) {
                $item->add_meta_data('Children', $values['children']);
            }
            if (isset($values['extra_services'])) {
                $item->add_meta_data('Extra Services', implode(', ', $values['extra_services']));
            }
            // if (isset($values['room_price'])) {
            //     $item->add_meta_data('Room Price', wc_price($values['room_price']));
            // }
        }
    }

    WDT_Ultimate_Booking_Integration::get_instance();
}
