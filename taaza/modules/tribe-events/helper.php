<?php

if( ! function_exists('taaza_event_breadcrumb_title') ) {
    function taaza_event_breadcrumb_title($title) {
        if( get_post_type() == 'tribe_events' && is_single()) {
            $etitle = esc_html__( 'Event Detail', 'taaza' );
            return '<h1>'.$etitle.'</h1>';
        } else {
            return $title;
        }
    }

    add_filter( 'taaza_breadcrumb_title', 'taaza_event_breadcrumb_title', 20, 1 );
}

?>