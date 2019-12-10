<?php 
/**
 * Images select field for CMB2 plugin.
 */

if( ! function_exists( 'basel_cmb_images_select_field' ) ) {
	function basel_cmb_images_select_field( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

		echo '<div class="basel-images-select-field">';

			echo '<div class="basel-images-opts">';

			foreach ($field->images_opts() as $key => $value) {
				$active = (  $field_escaped_value == $key ) ? ' active' : '';
				echo '<div class="basel-image-opt' . $active . '" data-val="' . $key . '">
						<img src="' . $value['image'] . '" /><span>' . $value['label'] . '</span>
					</div>';
			}

			echo '</div>';

			echo apply_filters( 'basel_cmb_images_select_input', $field_type_object->input( array(
				'type'       => 'hidden',
				'class'      => 'basel-image-select-input',
				'readonly'   => 'readonly',
				'data-value' => $field_escaped_value,
				'desc'       => '',
			) ) );

		echo '</div>';

		$field_type_object->_desc( true, true );
	}

	add_filter( 'cmb2_render_basel_images_select',  'basel_cmb_images_select_field', 10, 5 );
}
