<?php

    add_action( 'taaza_after_main_css', 'search_style' );
    function search_style() {
        wp_enqueue_style( 'taaza-quick-search', get_theme_file_uri('/modules/search/assets/css/search.css'), false, TAAZA_THEME_VERSION, 'all');
    }

    add_action('wp_ajax_taaza_search_data_fetch' , 'taaza_search_data_fetch');
	add_action('wp_ajax_nopriv_taaza_search_data_fetch','taaza_search_data_fetch');
	function taaza_search_data_fetch(){
        $nonce = $_POST['security'];
        if ( ! wp_verify_nonce( $nonce, 'search_data_fetch_nonce' ) ) {
            die( 'Security check failed' );
        }
        $search_val = taaza_sanitization($_POST['search_val']);

        $the_query = new WP_Query( array( 'posts_per_page' => 5, 's' => $search_val, 'post_type' => array('post', 'product') ) );
        if( $the_query->have_posts() ) :
            while( $the_query->have_posts() ): $the_query->the_post(); ?>
                <li class="quick_search_data_item">
                    <a href="<?php echo esc_url( get_permalink() ); ?>">
                        <?php the_post_thumbnail( 'thumbnail', array( 'class' => ' ' ) ); ?>
                        <?php the_title();?>
                    </a>
                </li>
            <?php endwhile;
            wp_reset_postdata();
        else:
            echo'<p>'. esc_html__( 'No Results Found', 'taaza') .'</p>';
        endif;

        die();
}
add_action( 'wp_enqueue_scripts', 'taaza_enqueue_scripts' );
    function taaza_enqueue_scripts() {
        // Enqueue your script here
        wp_enqueue_script( 'taaza-jqcustom', get_theme_file_uri('/assets/js/custom.js'), array('jquery'), false, true );
        // Create nonce and pass it to the script
        $ajax_nonce = wp_create_nonce( 'search_data_fetch_nonce' );
        wp_localize_script( 'taaza-jqcustom', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => $ajax_nonce ) );
    }

?>