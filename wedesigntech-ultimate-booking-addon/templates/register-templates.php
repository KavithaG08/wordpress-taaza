<?php
// Global Functions Outside the Class
if ( ! function_exists( 'custom_is_room_search_page_triggered' ) ) {
    function custom_is_room_search_page_triggered() {
        return is_search() && isset( $_GET['check_in'], $_GET['check_out'] ) && ! empty( $_GET['check_in'] ) && ! empty( $_GET['check_out'] );
    }
}

if ( ! function_exists( 'custom_add_room_search_body_class' ) ) {
    function custom_add_room_search_body_class( $classes ) {
        if ( custom_is_room_search_page_triggered() ) {
            $classes[] = 'custom-room-search-page';
        }
        return $classes;
    }
}

if ( ! function_exists( 'custom_override_room_search_template' ) ) {
    function custom_override_room_search_template( $template ) {
		if ( custom_is_room_search_page_triggered() ) {
			// Path to the template file in the plugin
			$template_path = plugin_dir_path( __FILE__ ) . '/room-search-results.php';
	
			// Check if the template exists and include it
			if ( file_exists( $template_path ) ) {
				return $template_path;
			}
		}
		return $template;
	}
	
}

if ( ! function_exists( 'custom_get_room_search_parameters' ) ) {
    function custom_get_room_search_parameters() {
        $params = array();
        
        $query_args = array( 'check_in', 'check_out', 'rooms', 'adult', 'children', 'infant' );
        foreach ( $query_args as $arg ) {
            if ( isset( $_GET[ $arg ] ) && ! empty( $_GET[ $arg ] ) ) {
                $params['query_options'][ $arg ] = sanitize_text_field( $_GET[ $arg ] );
            }
        }

        return $params;
    }
}


if ( ! class_exists( 'UltimateBookingProTemplates' ) ) {

    class UltimateBookingProTemplates {

        function __construct() {
            add_action( 'init', array( $this, 'ultimate_booking_pro_add_image_sizes' ) );

            add_filter( 'template_include', array( $this, 'ultimate_booking_pro_template_include' ) );
            
            // Hooking the custom functions
            add_filter( 'template_include', 'custom_override_room_search_template' );
            add_filter( 'body_class', 'custom_add_room_search_body_class' );
        }

        function ultimate_booking_pro_add_image_sizes() {
            $pwidth = ultimate_booking_pro_cs_get_option('staff-img-width', 205);
            $phight = ultimate_booking_pro_cs_get_option('staff-img-height', 205);

            $swidth = ultimate_booking_pro_cs_get_option('room-img-width', 205);
            $shight = ultimate_booking_pro_cs_get_option('room-img-height', 205);

            $apwidth = ultimate_booking_pro_cs_get_option('archive-staff-img-width', 650);
            $aphight = ultimate_booking_pro_cs_get_option('archive-staff-img-height', 650);

            add_image_size( 'dt-bm-staff-type2', $pwidth, $phight, array( 'center', 'top' ) );
            add_image_size( 'dt-bm-room-type2', $swidth, $shight, array( 'center', 'top' ) );
            add_image_size( 'dt-bm-archive-staff', $apwidth, $aphight, array( 'center', 'top' ) );
            add_image_size( 'dt-bm-dropdown-staff', 60, 60, array( 'center', 'top' ) );
        }

        function ultimate_booking_pro_template_include( $template ) {
            $post_type = get_post_type();

            $file = '';
            $find = array();

            if ( is_post_type_archive( 'dt_room' ) ) {
                $file = 'archive-room.php';
                $find[] = $file;
                $find[] = ULTIMATEBOOKINGPRO_PATH . '/' . $file;
            } else if ( is_post_type_archive( 'dt_staff' ) ) {
                $file = 'archive-staff.php';
                $find[] = $file;
                $find[] = ULTIMATEBOOKINGPRO_PATH . '/' . $file;
            } else if ( is_singular('dt_room') ) {
                $file = 'single-room.php';
                $find[] = $file;
                $find[] = ULTIMATEBOOKINGPRO_PATH . '/' . $file;
            } else if ( is_singular('dt_staff') ) {
                $file = 'single-staff.php';
                $find[] = $file;
                $find[] = ULTIMATEBOOKINGPRO_PATH . '/' . $file;
            } else if ( taxonomy_exists('dt_room_category') || taxonomy_exists('dt_staff_department') || taxonomy_exists('dt_room_amenity') ) {
                if ( is_tax( 'dt_room_category' ) ) {
                    $file = 'taxonomy-category.php';
                } else if ( is_tax( 'dt_staff_department' ) ) {
                    $file = 'taxonomy-department.php';
                } else if ( is_tax( 'dt_room_amenity' ) ) {
                    $file = 'taxonomy-amenity.php';
                }
                $find[] = ULTIMATEBOOKINGPRO_PATH . '/' . $file;
            }

            if ( $file ) {
                $find[] = ULTIMATEBOOKINGPRO_PATH . '/' . $file;
                $dt_template = untrailingslashit( ULTIMATEBOOKINGPRO_PATH ) . '/templates/' . $file;
                $template = locate_template( array_unique( $find ) );
                
                if ( !$template && file_exists( $dt_template ) ) {
                    $template = $dt_template;
                }
            }

            return $template;
        }
    }
}
