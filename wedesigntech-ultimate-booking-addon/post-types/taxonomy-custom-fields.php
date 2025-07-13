<?php

if( !class_exists('DTTaxonomyCustomFields') ) {

	class DTTaxonomyCustomFields {

		/**
		 * Instance variable
		 */
		private static $_instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        function __construct() {

            $taxonomies = apply_filters('dt_taxonomies', array('dt_room_amenity' => esc_html__('Room Amenity', 'wdt-ultimate-booking')));
        
            foreach ($taxonomies as $taxonomy => $taxonomy_label) {
                add_action($taxonomy . '_add_form_fields', array($this, 'dt_add_taxonomy_form_fields'), 10, 2);
                add_action('created_' . $taxonomy, array($this, 'dt_save_taxonomy_form_fields'), 10, 2);
                add_action($taxonomy . '_edit_form_fields', array($this, 'dt_update_taxonomy_form_fields'), 10, 2);
                add_action('edited_' . $taxonomy, array($this, 'dt_updated_taxonomy_form_fields'), 10, 2);
                add_action('admin_enqueue_scripts', array($this, 'dt_enqueue_admin_scripts'));
            }
        }

        function dt_enqueue_admin_scripts($hook) {

            if ('edit-tags.php' === $hook || 'term.php' === $hook) {
 
                wp_enqueue_media();
        
                wp_enqueue_script(
                    'dt-admin-scripts', 
                    plugins_url ('wedesigntech-ultimate-booking-addon') .'/assets/js/dt-admin-scripts.js', 
                    array('jquery'), 
                    '1.0', 
                    true
                );
        
                wp_enqueue_style(
                    'dt-admin-styles', 
                    plugins_url ('wedesigntech-ultimate-booking-addon') . '/assets/css/dt-admin-styles.css', 
                    array(), 
                    '1.0'
                );
            }
        }
        
        function dt_add_taxonomy_form_fields($taxonomy) {
            if ($taxonomy === 'dt_room_amenity') {
                echo '<div class="form-field term-group">
                        <label for="taxonomy-map-image">' . esc_html__('Image', 'wdt-ultimate-booking') . '</label>
                        <div class="dt-upload-media-items-container">
                            <input name="dt-taxonomy-map-image-url" type="hidden" class="uploadfieldurl" readonly value=""/>
                            <input name="dt-taxonomy-map-image-id" type="hidden" class="uploadfieldid" readonly value=""/>
                            <input type="button" value="' . esc_html__('Add Image', 'wdt-ultimate-booking') . '" class="dt-upload-media-item-button show-preview with-image-holder" />
                            ' . dt_adminpanel_image_preview('') . '
                        </div>
                        <p>' . esc_html__('This image will be used in "Taxonomy" shortcodes.', 'wdt-ultimate-booking') . '</p>
                    </div>';

                echo '<div class="form-field term-group">
					<label for="taxonomy-icon-dropdown">' . esc_html__('Icon', 'wdt-ultimate-booking') . '</label>
					<select name="dt-taxonomy-icon" id="taxonomy-icon-dropdown">
						<option value="icon-dt-conditioner">' . esc_html__('Air Condition', 'wdt-ultimate-booking') . '</option>
						<option value="icon-dt-wifi">' . esc_html__('WiFi', 'wdt-ultimate-booking') . '</option>
						<option value="icon-dt-pool">' . esc_html__('Swimming Pool', 'wdt-ultimate-booking') . '</option>
                        <option value="icon-dt-spa">' . esc_html__('SPA', 'wdt-ultimate-booking') . '</option> 
						<option value="icon-dt-parking-board">' . esc_html__('Parking Board', 'wdt-ultimate-booking') . '</option>
                        <option value="icon-dt-car-parking">' . esc_html__('Car Parking', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-gym">' . esc_html__('gym', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-elevator">' . esc_html__('Elevator', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-double-bed">' . esc_html__('Room Bed', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-hotel-dish">' . esc_html__('Hotel Dish', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-do-not-disturb">' . esc_html__('Do Not Disturb', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-fire-production">' . esc_html__('Fire Production', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-hair-dryer">' . esc_html__('Hair Dryer', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-hotel">' . esc_html__('Icon Hotel', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-key">' . esc_html__('Room Key', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-location">' . esc_html__('Location', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-lock">' . esc_html__('Door Lock', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-lounge">' . esc_html__('Lounge', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-menu">' . esc_html__('Menu', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-mobile">' . esc_html__('Mobile', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-no-smoking">' . esc_html__('No Smoking', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-passport">' . esc_html__('Passport', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-receipt">' . esc_html__('Receipt', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-restaurant">' . esc_html__('Restaurant', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-room-service">' . esc_html__('Room Service', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-housekeeper">' . esc_html__('Housekeeper', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-breakfast">' . esc_html__('Breakfast', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-shampoo">' . esc_html__('Shampoo', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-taxi">' . esc_html__('Pickup', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-pickup">' . esc_html__('Taxi', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-television">' . esc_html__('Television', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-towel">' . esc_html__('Towel', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-baggage">' . esc_html__('Baggage', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-bar">' . esc_html__('Bar', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-bath">' . esc_html__('Bath', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-bowling">' . esc_html__('Bowling', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-calender">' . esc_html__('Calender', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-cash">' . esc_html__('Cash', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-cctv-camera">' . esc_html__('CCTV Camera', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-secure-camera">' . esc_html__('Secure Camera', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-comport">' . esc_html__('Comport', 'wdt-ultimate-booking') . '</option> 
                        <option value="icon-dt-store">' . esc_html__('Store', 'wdt-ultimate-booking') . '</option> 
					</select>
					<p>' . esc_html__('Select an icon for this taxonomy.', 'wdt-ultimate-booking') . '</p>
				</div>';

                    
            }
        }
        
        function dt_save_taxonomy_form_fields($term_id, $tt_id) {
            if (isset($_POST['dt-taxonomy-map-image-url'])) {
                $image_url = sanitize_text_field($_POST['dt-taxonomy-map-image-url']);
                add_term_meta($term_id, 'dt-taxonomy-map-image-url', $image_url, true);
            }
        
            if (isset($_POST['dt-taxonomy-map-image-id'])) {
                $image_id = sanitize_text_field($_POST['dt-taxonomy-map-image-id']);
                add_term_meta($term_id, 'dt-taxonomy-map-image-id', $image_id, true);
            }

            if (isset($_POST['dt-taxonomy-icon'])) {
				$icon_value = sanitize_text_field($_POST['dt-taxonomy-icon']);
				add_term_meta($term_id, 'dt-taxonomy-icon', $icon_value, true);
			}

        }
        
        function dt_update_taxonomy_form_fields($term, $taxonomy) {
            if ($taxonomy === 'dt_room_amenity') {
                $image_url = get_term_meta($term->term_id, 'dt-taxonomy-map-image-url', true);
                $image_id = get_term_meta($term->term_id, 'dt-taxonomy-map-image-id', true);
        
                echo '<tr class="form-field term-group-wrap">
                        <th scope="row">
                            <label for="taxonomy-map-image">' . esc_html__('Image', 'wdt-ultimate-booking') . '</label>
                        </th>
                        <td>
                            <div class="dt-upload-media-items-container">
                                <input name="dt-taxonomy-map-image-url" type="hidden" class="uploadfieldurl" readonly value="' . esc_attr($image_url) . '"/>
                                <input name="dt-taxonomy-map-image-id" type="hidden" class="uploadfieldid" readonly value="' . esc_attr($image_id) . '"/>
                                <input type="button" value="' . esc_html__('Add Image', 'wdt-ultimate-booking') . '" class="dt-upload-media-item-button show-preview with-image-holder" />
                                <input type="button" value="' . esc_html__('Remove Image', 'wdt-ultimate-booking') . '" class="dt-upload-media-item-reset" />
                                ' . dt_adminpanel_image_preview($image_url) . '
                            </div>
                            <p>' . esc_html__('This image will be used in in "Taxonomy" shortcodes.', 'wdt-ultimate-booking') . '</p>
                        </td>
                    </tr>';

                $selected_icon = get_term_meta($term->term_id, 'dt-taxonomy-icon', true);
				echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="taxonomy-icon-dropdown">' . esc_html__('Icon', 'wdt-ultimate-booking') . '</label>
					</th>
					<td>
						<select name="dt-taxonomy-icon" id="taxonomy-icon-dropdown">
                            <option value="icon-dt-conditioner" ' . selected($selected_icon, 'icon-dt-conditioner', false) . '>' . esc_html__('Air Condition', 'wdt-ultimate-booking') . '</option>
                            <option value="icon-dt-wifi" ' . selected($selected_icon, 'icon-dt-wifi', false) . '>' . esc_html__('WiFi', 'wdt-ultimate-booking') . '</option>
                            <option value="icon-dt-pool" ' . selected($selected_icon, 'icon-dt-pool', false) . '>' . esc_html__('Swimming Pool', 'wdt-ultimate-booking') . '</option>
                            <option value="icon-dt-spa" ' . selected($selected_icon, 'icon-dt-spa', false) . '>' . esc_html__('SPA', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-parking-board" ' . selected($selected_icon, 'icon-dt-parking-board', false) . '>' . esc_html__('Parking Board', 'wdt-ultimate-booking') . '</option>
                            <option value="icon-dt-car-parking" ' . selected($selected_icon, 'icon-dt-car-parking', false) . '>' . esc_html__('Car Parking', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-gym" ' . selected($selected_icon, 'icon-dt-gym', false) . '>' . esc_html__('gym', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-elevator" ' . selected($selected_icon, 'icon-dt-elevator', false) . '>' . esc_html__('Elevator', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-double-bed" ' . selected($selected_icon, 'icon-dt-double-bed', false) . '>' . esc_html__('Room Bed', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-hotel-dish" ' . selected($selected_icon, 'icon-dt-hotel-dish', false) . '>' . esc_html__('Hotel Dish', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-do-not-disturb" ' . selected($selected_icon, 'icon-dt-do-not-disturb', false) . '>' . esc_html__('Do Not Disturb', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-fire-production" ' . selected($selected_icon, 'icon-dt-fire-production', false) . '>' . esc_html__('Fire Production', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-hair-dryer" ' . selected($selected_icon, 'icon-dt-hair-dryer', false) . '>' . esc_html__('Hair Dryer', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-hotel" ' . selected($selected_icon, 'icon-dt-hotel', false) . '>' . esc_html__('Icon Hotel', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-key" ' . selected($selected_icon, 'icon-dt-key', false) . '>' . esc_html__('Room Key', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-location" ' . selected($selected_icon, 'icon-dt-location', false) . '>' . esc_html__('Location', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-lock" ' . selected($selected_icon, 'icon-dt-lock', false) . '>' . esc_html__('Door Lock', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-lounge" ' . selected($selected_icon, 'icon-dt-lounge', false) . '>' . esc_html__('Lounge', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-menu" ' . selected($selected_icon, 'icon-dt-menu', false) . '>' . esc_html__('Menu', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-mobile" ' . selected($selected_icon, 'icon-dt-mobile', false) . '>' . esc_html__('Mobile', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-no-smoking" ' . selected($selected_icon, 'icon-dt-no-smoking', false) . '>' . esc_html__('No Smoking', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-passport" ' . selected($selected_icon, 'icon-dt-passport', false) . '>' . esc_html__('Passport', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-receipt" ' . selected($selected_icon, 'icon-dt-receipt', false) . '>' . esc_html__('Receipt', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-restaurant" ' . selected($selected_icon, 'icon-dt-restaurant', false) . '>' . esc_html__('Restaurant', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-room-service" ' . selected($selected_icon, 'icon-dt-room-service', false) . '>' . esc_html__('Room Service', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-housekeeper" ' . selected($selected_icon, 'icon-dt-housekeeper', false) . '>' . esc_html__('Housekeeper', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-breakfast" ' . selected($selected_icon, 'icon-dt-breakfast', false) . '>' . esc_html__('Breakfast', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-shampoo" ' . selected($selected_icon, 'icon-dt-shampoo', false) . '>' . esc_html__('Shampoo', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-taxi" ' . selected($selected_icon, 'icon-dt-taxi', false) . '>' . esc_html__('Pickup', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-pickup" ' . selected($selected_icon, 'icon-dt-pickup', false) . '>' . esc_html__('Taxi', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-television" ' . selected($selected_icon, 'icon-dt-television', false) . '>' . esc_html__('Television', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-towel" ' . selected($selected_icon, 'icon-dt-towel', false) . '>' . esc_html__('Towel', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-baggage" ' . selected($selected_icon, 'icon-dt-baggage', false) . '>' . esc_html__('Baggage', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-bar" ' . selected($selected_icon, 'icon-dt-bar', false) . '>' . esc_html__('Bar', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-bath" ' . selected($selected_icon, 'icon-dt-bath', false) . '>' . esc_html__('Bath', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-bowling" ' . selected($selected_icon, 'icon-dt-bowling', false) . '>' . esc_html__('Bowling', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-calender" ' . selected($selected_icon, 'icon-dt-calender', false) . '>' . esc_html__('Calender', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-cash" ' . selected($selected_icon, 'icon-dt-cash', false) . '>' . esc_html__('Cash', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-cctv-camera" ' . selected($selected_icon, 'icon-dt-cctv-camera', false) . '>' . esc_html__('CCTV Camera', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-secure-camera" ' . selected($selected_icon, 'icon-dt-secure-camera', false) . '>' . esc_html__('Secure Camera', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-comport" ' . selected($selected_icon, 'icon-dt-comport', false) . '>' . esc_html__('Comport', 'wdt-ultimate-booking') . '</option> 
                            <option value="icon-dt-store" ' . selected($selected_icon, 'icon-dt-store', false) . '>' . esc_html__('Store', 'wdt-ultimate-booking') . '</option> 
						</select>
						<p>' . esc_html__('Select an icon for this taxonomy.', 'wdt-ultimate-booking') . '</p>
					</td>
				</tr>';
            }
        }
        
        function dt_updated_taxonomy_form_fields($term_id, $tt_id) {
            // Don't update on Quick Edit
            if (defined('DOING_AJAX')) {
                return;
            }
        
            if (isset($_POST['dt-taxonomy-map-image-url']) && '' !== $_POST['dt-taxonomy-map-image-url']) {
                $image_url = sanitize_text_field($_POST['dt-taxonomy-map-image-url']);
                update_term_meta($term_id, 'dt-taxonomy-map-image-url', $image_url);
            } else {
                update_term_meta($term_id, 'dt-taxonomy-map-image-url', '');
            }
        
            if (isset($_POST['dt-taxonomy-map-image-id']) && '' !== $_POST['dt-taxonomy-map-image-id']) {
                $image_id = sanitize_text_field($_POST['dt-taxonomy-map-image-id']);
                update_term_meta($term_id, 'dt-taxonomy-map-image-id', $image_id);
            } else {
                update_term_meta($term_id, 'dt-taxonomy-map-image-id', '');
            }

            if (isset($_POST['dt-taxonomy-icon']) && '' !== $_POST['dt-taxonomy-icon']) {
				$icon_value = sanitize_text_field($_POST['dt-taxonomy-icon']);
				update_term_meta($term_id, 'dt-taxonomy-icon', $icon_value);
			} else {
				update_term_meta($term_id, 'dt-taxonomy-icon', '');
			}
        }

	}

}

?>