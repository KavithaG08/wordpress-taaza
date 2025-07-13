<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (! class_exists ( 'TaazaPlusHeaderPostType' ) ) {

	class TaazaPlusHeaderPostType {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

		function __construct() {

			add_action ( 'init', array( $this, 'taaza_register_cpt' ), 5 );
			add_filter ( 'template_include', array ( $this, 'taaza_template_include' ) );
		}

		function taaza_register_cpt() {

			$labels = array (
				'name'				 => __( 'Headers', 'taaza-plus' ),
				'singular_name'		 => __( 'Header', 'taaza-plus' ),
				'menu_name'			 => __( 'Headers', 'taaza-plus' ),
				'add_new'			 => __( 'Add Header', 'taaza-plus' ),
				'add_new_item'		 => __( 'Add New Header', 'taaza-plus' ),
				'edit'				 => __( 'Edit Header', 'taaza-plus' ),
				'edit_item'			 => __( 'Edit Header', 'taaza-plus' ),
				'new_item'			 => __( 'New Header', 'taaza-plus' ),
				'view'				 => __( 'View Header', 'taaza-plus' ),
				'view_item' 		 => __( 'View Header', 'taaza-plus' ),
				'search_items' 		 => __( 'Search Headers', 'taaza-plus' ),
				'not_found' 		 => __( 'No Headers found', 'taaza-plus' ),
				'not_found_in_trash' => __( 'No Headers found in Trash', 'taaza-plus' ),
			);

			$args = array (
				'labels' 				=> $labels,
				'public' 				=> true,
				'exclude_from_search'	=> true,
				'show_in_nav_menus' 	=> false,
				'show_in_rest' 			=> true,
				'menu_position'			=> 25,
				'menu_icon' 			=> 'dashicons-heading',
				'hierarchical' 			=> false,
				'supports' 				=> array ( 'title', 'editor', 'revisions' ),
			);

			register_post_type ( 'wdt_headers', $args );
		}

		function taaza_template_include($template) {
			if ( is_singular( 'wdt_headers' ) ) {
				if ( ! file_exists ( get_stylesheet_directory () . '/single-wdt_headers.php' ) ) {
					$template = TAAZA_PLUS_DIR_PATH . 'post-types/templates/single-wdt_headers.php';
				}
			}

			return $template;
		}
	}
}

TaazaPlusHeaderPostType::instance();