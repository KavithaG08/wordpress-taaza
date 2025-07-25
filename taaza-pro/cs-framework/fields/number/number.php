<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Number
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_number extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo apply_filters( 'cs_element_before', $this->element_before() );
    $unit = ( isset( $this->field['unit'] ) ) ? '<em>'. $this->field['unit'] .'</em>' : '';
    echo '<input type="number" name="'. esc_attr($this->element_name()) .'" value="'. esc_attr($this->element_value()).'"'. taaza_html_output($this->element_class()) . $this->element_attributes() .'/>'. $unit;
    echo apply_filters( 'cs_element_after', $this->element_after() );
  }

}
