<?php

/**
 * WooCommerce - Taxonomy Core Class
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'Taaza_Shop_Others_Taxonomy' ) ) {

    class Taaza_Shop_Others_Taxonomy {

        private static $_instance = null;

        public static function instance() {

            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;

        }

        function __construct() {

            // Create page - Custom Fields
                add_action('product_cat_add_form_fields', array ( $this, 'taaza_shop_taxonomy_add_new_meta_field' ), 20, 1);

            // Edit page - Custom Fields
                add_action('product_cat_edit_form_fields', array ( $this, 'taaza_shop_taxonomy_edit_meta_field' ), 20, 1);

            // Save - Custom Fields
                add_action('edited_product_cat', array ( $this, 'taaza_shop_save_taxonomy_custom_meta' ), 20, 1);
                add_action('create_product_cat', array ( $this, 'taaza_shop_save_taxonomy_custom_meta' ), 20, 1);

            // Enqueue Admin Scripts
                add_action ( 'admin_enqueue_scripts', array ( $this, 'taaza_shop_admin_enqueue_scripts' ) );

            // Load Modules
				$this->load_modules();

        }


        /*
        Module Paths
        */

            function module_dir_path() {

                if( taaza_is_file_in_theme( __FILE__ ) ) {
                    return TAAZA_MODULE_DIR . '/woocommerce/others/taxonomy/';
                } else {
                    return trailingslashit( plugin_dir_path( __FILE__ ) );
                }

            }

            function module_dir_url() {

                if( taaza_is_file_in_theme( __FILE__ ) ) {
                    return TAAZA_MODULE_URI . '/woocommerce/others/taxonomy/';
                } else {
                    return trailingslashit( plugin_dir_url( __FILE__ ) );
                }

            }


        /**
         * Create page - Custom Fields
         */
            function taaza_shop_taxonomy_add_new_meta_field( $terms ) {

                ?>
                <div class="form-field">
                    <label for="taaza_shop_cat_color"><?php esc_html_e('Color', 'taaza-pro'); ?></label>
                    <input type="text" name="taaza_shop_cat_color" id="taaza_shop_cat_color" class="wdt-shop-color-picker-alpha">
                    <p class="description"><?php esc_html_e('If you wish choose specific color for your category.', 'taaza-pro'); ?></p>
                </div>
                <?php
            }

        /**
         * Edit page - Custom Fields
         */
            function taaza_shop_taxonomy_edit_meta_field( $term ) {

                //getting term ID
                $term_id = $term->term_id;

                // retrieve the existing value(s) for this meta field.
                $taaza_shop_cat_color = get_term_meta($term_id, 'taaza_shop_cat_color', true);
                ?>
                <tr class="form-field">
                    <th scope="row" valign="top"><label for="taaza_shop_cat_color"><?php esc_html_e('Color', 'taaza-pro'); ?></label></th>
                    <td>
                        <input type="text" name="taaza_shop_cat_color" id="taaza_shop_cat_color" class="wdt-shop-color-picker-alpha" value="<?php echo esc_attr($taaza_shop_cat_color) ? esc_attr($taaza_shop_cat_color) : ''; ?>">
                        <p class="description"><?php esc_html_e('If you wish choose specific color for your category.', 'taaza-pro'); ?></p>
                    </td>
                </tr>
                <?php
            }

        /**
         * Save - Custom Fields
         */
            function taaza_shop_save_taxonomy_custom_meta( $term_id ) {

                $taaza_shop_cat_color = filter_input(INPUT_POST, 'taaza_shop_cat_color');
                update_term_meta($term_id, 'taaza_shop_cat_color', $taaza_shop_cat_color);

            }

        /**
         * Enqueue Admin Scripts
         */
            function taaza_shop_admin_enqueue_scripts() {

                $current_screen = get_current_screen();
                if($current_screen->id == 'edit-product_cat') {

                    wp_enqueue_script('wdt-shop-taxonomy', $this->module_dir_url() . 'assets/js/taxonomy.js', array ('jquery'), false, true);

                }

            }

        /**
         * Load Modules
         */
            function load_modules() {

                include_once $this->module_dir_path(). 'elementor/index.php';

            }

    }

}

if( !function_exists('taaza_shop_others_taxonomy') ) {
	function taaza_shop_others_taxonomy() {
        $reflection = new ReflectionClass('Taaza_Shop_Others_Taxonomy');
        return $reflection->newInstanceWithoutConstructor();
	}
}

Taaza_Shop_Others_Taxonomy::instance();