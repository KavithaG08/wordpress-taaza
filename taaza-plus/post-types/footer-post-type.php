<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (! class_exists ( 'TaazaPlusFooterPostType' ) ) {

	class TaazaPlusFooterPostType {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

		function __construct() {

			add_action ( 'init', array( $this, 'taaza_register_cpt' ) );
			add_filter ( 'template_include', array ( $this, 'taaza_template_include' ) );
		}

		function taaza_register_cpt() {

			$labels = array (
				'name'				 => __( 'Footers', 'taaza-plus' ),
				'singular_name'		 => __( 'Footer', 'taaza-plus' ),
				'menu_name'			 => __( 'Footers', 'taaza-plus' ),
				'add_new'			 => __( 'Add Footer', 'taaza-plus' ),
				'add_new_item'		 => __( 'Add New Footer', 'taaza-plus' ),
				'edit'				 => __( 'Edit Footer', 'taaza-plus' ),
				'edit_item'			 => __( 'Edit Footer', 'taaza-plus' ),
				'new_item'			 => __( 'New Footer', 'taaza-plus' ),
				'view'				 => __( 'View Footer', 'taaza-plus' ),
				'view_item' 		 => __( 'View Footer', 'taaza-plus' ),
				'search_items' 		 => __( 'Search Footers', 'taaza-plus' ),
				'not_found' 		 => __( 'No Footers found', 'taaza-plus' ),
				'not_found_in_trash' => __( 'No Footers found in Trash', 'taaza-plus' ),
			);

			$args = array (
				'labels' 				=> $labels,
				'public' 				=> true,
				'exclude_from_search'	=> true,
				'show_in_nav_menus' 	=> false,
				'show_in_rest' 			=> true,
				'menu_position'			=> 26,
				'menu_icon' 			=> 'dashicons-editor-insertmore',
				'hierarchical' 			=> false,
				'supports' 				=> array ( 'title', 'editor', 'revisions' ),
			);

			register_post_type ( 'wdt_footers', $args );
		}

		function taaza_template_include($template) {
			if ( is_singular( 'wdt_footers' ) ) {
				if ( ! file_exists ( get_stylesheet_directory () . '/single-wdt_footers.php' ) ) {
					$template = TAAZA_PLUS_DIR_PATH . 'post-types/templates/single-wdt_footers.php';
				}
			}

			return $template;
		}
	}
}

TaazaPlusFooterPostType::instance();