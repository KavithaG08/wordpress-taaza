<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_Image extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo apply_filters( 'cs_element_before', $this->element_before() );

    $preview = '';
    $value   = $this->element_value();
    $add     = ( ! empty( $this->field['add_title'] ) ) ? $this->field['add_title'] : esc_html__( 'Add Image', 'taaza-pro' );
    $hidden  = ( empty( $value ) ) ? ' hidden' : '';

    if( ! empty( $value ) ) {
      $attachment = wp_get_attachment_image_src( $value, 'thumbnail' );
      $preview    = $attachment[0];
    }

    echo '<div class="cs-image-preview'. $hidden .'"><div class="cs-preview"><i class="fa fa-times cs-remove"></i><img src="'. esc_url($preview) .'" alt="'.esc_attr__('preview', 'taaza-pro').'" /></div></div>';
    echo '<a href="#" class="button button-primary cs-add">'. $add .'</a>';
    echo '<input type="text" name="'. esc_attr($this->element_name()) .'" value="'. esc_attr($this->element_value()) .'"'. taaza_html_output($this->element_class()) . $this->element_attributes() .'/>';

    echo apply_filters( 'cs_element_after', $this->element_after() );
  }

}
