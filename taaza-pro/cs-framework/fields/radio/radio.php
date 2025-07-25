<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Radio
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_radio extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo apply_filters( 'cs_element_before', $this->element_before() );

    if( isset( $this->field['options'] ) ) {

      $options = $this->field['options'];
      $options = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );

      if( ! empty( $options ) ) {

        echo '<ul'. $this->element_class() .'>';
        foreach ( $options as $key => $value ) {
          echo '<li><label><input type="radio" name="'. esc_attr($this->element_name()) .'" value="'. esc_attr($key) .'"'. taaza_html_output($this->element_attributes( $key )) . $this->checked( $this->element_value(), $key ) .'/> '. $value .'</label></li>';
        }
        echo '</ul>';
      }

    } else {
      $label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
      echo '<label><input type="radio" name="'. esc_attr($this->element_name()) .'" value="1"'. taaza_html_output($this->element_class()) . $this->element_attributes() . checked( $this->element_value(), 1, false ) .'/> '. esc_html($label) .'</label>';
    }

    echo apply_filters( 'cs_element_after', $this->element_after() );
  }

}
