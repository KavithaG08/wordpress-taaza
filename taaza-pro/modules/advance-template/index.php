<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if( !class_exists( 'TaazaProAdvanceTemplate' ) ) {
    class TaazaProAdvanceTemplate {
        private static $_instance = null;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        function __construct() {
            $this->load_widgets();
            add_action( 'taaza_after_main_css', array( $this, 'enqueue_css_assets' ), 20 );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js_assets' ) );
        }
        function load_widgets() {
            add_action( 'widgets_init', array( $this, 'register_widgets_init' ) );
        }
        function register_widgets_init() {
            include_once TAAZA_PRO_DIR_PATH.'modules/advance-template/widget/widget-advance-template.php';
            register_widget('Taaza_Widget_Advance_Template');
        }
        function enqueue_css_assets() {
        }
        function enqueue_js_assets() {  
        }
    }
}
TaazaProAdvanceTemplate::instance();