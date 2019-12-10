<?php

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}
/**
* Colorpicker
*/
if ( ! function_exists( 'basel_get_colorpicker_param' ) ) {
	function basel_get_colorpicker_param( $settings, $value ) {
        $output = '<div class="basel-vc-colorpicker" id="' . esc_attr( uniqid() ) . '">';
            $output .= '<input name="color" class="basel-vc-colorpicker-input" type="text">';
            $output .= '<input type="hidden" class="basel-vc-colorpicker-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" data-css_args="' . esc_attr( json_encode( $settings['css_args'] ) ) . '"  value="' . esc_attr( $value ) . '">';
        $output .= '</div>';

        return $output;
    }
}
