<?php 
/**
 * Slider field for CMB2 plugin.
 */

if( ! function_exists( 'basel_cmb_slider_field' ) ) {
	function basel_cmb_slider_field( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

		echo '<div class="basel-slider-field"></div>';

		echo apply_filters( 'basel_cmb_slider_input',$field_type_object->input( array(
			'type'       => 'hidden',
			'class'      => 'basel-slider-field-value',
			'readonly'   => 'readonly',
			'data-start' => absint( $field_escaped_value ),
			'data-min'   => $field->min(),
			'data-max'   => $field->max(),
			'data-step'  => $field->step(),
			'desc'       => '',
		) ) );

		echo '<span class="basel-slider-field-value-display">'. $field->value_label() .' <span class="basel-slider-field-value-text"></span></span>';

		$field_type_object->_desc( true, true );
	}

	add_filter( 'cmb2_render_basel_slider',  'basel_cmb_slider_field', 10, 5 );
}
