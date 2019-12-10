<?php

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
* basel slider param
*/
if ( ! function_exists( 'basel_get_slider_param' ) ) {
	function basel_get_slider_param( $settings, $value ) {
        $value = ! $value ? $settings['default'] : $value;
        $output = '<div class="basel-vc-slider">';
		    $output .= '<div class="basel-slider-field"></div>';
            $output .= '<input type="hidden" class="basel-slider-field-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" id="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '" data-start="' . esc_attr( $value ) . '" data-min="' . esc_attr( $settings['min'] ) . '" data-max="' . esc_attr( $settings['max'] ) . '" data-step="' . esc_attr( $settings['step'] ) . '">';
            $output .= '<span class="basel-slider-field-value-display"><span class="basel-slider-field-value-text"></span>' . esc_attr( $settings['units'] ) . '</span>';
        $output .= '</div>';

        return $output;
    }
    
}
