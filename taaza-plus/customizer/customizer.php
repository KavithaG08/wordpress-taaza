<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'TaazaPlusCustomizer' ) ) {
    class TaazaPlusCustomizer {

        private static $_instance = null;

        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        function __construct() {
            /**
             * Before Hook
             */
            do_action( 'taaza_plus_before_fw_customizer_load' );

                add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts') );
                add_filter( 'customize_previewable_devices', array( $this, 'previewable_devices' ) );

                add_action( 'customize_register', array( $this, 'extend_panels' ), 5 );
                add_action( 'customize_register', array( $this, 'extend_sections' ), 5 );
                add_action( 'customize_register', array( $this, 'extend_controls' ), 10 );

            /**
             * Adter Hook
             */
            do_action( 'taaza_plus_after_fw_customizer_load' );
        }

        function enqueue_scripts() {
            wp_enqueue_style( 'taaza-plus-customizer', TAAZA_PLUS_DIR_URL.'customizer/assets/css/customizer.css', array(), TAAZA_PLUS_VERSION, 'all' );

            wp_enqueue_script( 'taaza-plus-customizer', TAAZA_PLUS_DIR_URL.'customizer/assets/js/customizer.js', array(), TAAZA_PLUS_VERSION, true );
            wp_enqueue_script( 'taaza-plus-customizer-color-picker', TAAZA_PLUS_DIR_URL.'customizer/assets/js/wp-color-picker-alpha.js', array( 'jquery', 'wp-color-picker' ), TAAZA_PLUS_VERSION, true );
            wp_enqueue_script( 'taaza-plus-customizer-interdependencies', TAAZA_PLUS_DIR_URL.'customizer/assets/js/jquery.interdependencies.js', array( 'jquery' ), TAAZA_PLUS_VERSION, true );
            wp_enqueue_script( 'taaza-plus-customizer-dependencies', TAAZA_PLUS_DIR_URL.'customizer/assets/js/jquery.dependencies.js', array( 'taaza-plus-customizer-interdependencies' ), TAAZA_PLUS_VERSION, true );
        }

        function previewable_devices( $devices ) {

			$devices = array(
				'desktop' => array(
					'label' => esc_html__( 'Enter desktop preview mode', 'taaza-plus'),
					'default' => true,
				),
				'tablet-landscape' => array(
					'label' => esc_html__( 'Enter tablet landscape preview mode', 'taaza-plus'),
				),
				'tablet' => array(
					'label' => esc_html__( 'Enter tablet preview mode', 'taaza-plus'),
				),
				'mobile' => array(
					'label' => esc_html__( 'Enter mobile preview mode', 'taaza-plus'),
				),
			);

            return $devices;
        }

        function extend_panels( $wp_customize ) {
            require_once TAAZA_PLUS_DIR_PATH . 'customizer/lib/class-wp-customize-panel.php';
            $wp_customize->register_panel_type( 'Taaza_Customize_Panel' );
        }

        function extend_sections( $wp_customize ) {
            require_once TAAZA_PLUS_DIR_PATH . 'customizer/lib/class-wp-customize-section.php';
            $wp_customize->register_panel_type( 'Taaza_Customize_Section' );
        }

        function extend_controls( $wp_customize ) {

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/class-base-control.php';
            $wp_customize->register_control_type('Taaza_Customize_Control');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/separator/class-control-separator.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Separator');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/description/class-control-description.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Description');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/radio-image/class-control-radio-image.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Radio_Image');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/sortable/class-control-sortable.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Sortable');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/slider/class-control-slider.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Slider');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/responsive-slider/class-control-responsive-slider.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Responsive_Slider');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/responsive-number/class-control-responsive-number.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Responsive_Number');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/responsive-spacing/class-control-responsive-spacing.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Responsive_Spacing');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/spacing/class-control-spacing.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Spacing');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/color/class-control-color.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Color');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/background/class-control-background.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Background');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/typography/class-control-typography.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Typography');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/fontawesome/class-control-fontawesome.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Fontawesome');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/switch/class-control-switch.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Switch');

			require TAAZA_PLUS_DIR_PATH . 'customizer/controls/upload/class-control-upload.php';
			$wp_customize->register_control_type('Taaza_Customize_Control_Upload');
        }
    }
}

TaazaPlusCustomizer::instance();