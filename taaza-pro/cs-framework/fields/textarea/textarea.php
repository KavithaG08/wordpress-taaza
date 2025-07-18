<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_textarea extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo apply_filters( 'cs_element_before', $this->element_before() );

    $this->shortcode_generator();

    echo '<textarea name="'. esc_attr($this->element_name()) .'"'. taaza_html_output($this->element_class()) . $this->element_attributes() .'>'. $this->element_value() .'</textarea>';

    echo apply_filters( 'cs_element_after', $this->element_after() );
  }

  public function shortcode_generator() {
    if( isset( $this->field['shortcode'] ) && CS_ACTIVE_SHORTCODE ) {
      echo '<a href="#" class="button button-primary cs-shortcode cs-shortcode-textarea">'. esc_html__( 'Add Shortcode', 'taaza-pro' ) .'</a>';
    }
  }
}
