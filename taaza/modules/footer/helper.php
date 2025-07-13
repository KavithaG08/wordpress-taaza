<?php
add_action( 'taaza_after_main_css', 'footer_style' );
function footer_style() {
    wp_enqueue_style( 'taaza-footer', get_theme_file_uri('/modules/footer/assets/css/footer.css'), false, TAAZA_THEME_VERSION, 'all');
}

add_action( 'taaza_footer', 'footer_content' );
function footer_content() {
    taaza_template_part( 'content', 'content', 'footer' );
}