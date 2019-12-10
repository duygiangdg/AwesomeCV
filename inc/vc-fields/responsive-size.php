<?php

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
* basel responsive size param
*/
if ( ! function_exists( 'basel_get_responsive_size_param' ) ) {
	function basel_get_responsive_size_param( $settings, $value ) {
        $output = '<div class="basel-rs-wrapper ' . esc_attr( $settings['param_name'] ) . '">'; 
            $output .= '<div class="basel-rs-item desktop">';
                $output .= '<span class="basel-rs-icon basel-css-tooltip" data-text="Desktop"><i class="dashicons dashicons-desktop"></i></span>';
                $output .= '<input type="number" min="1" class="basel-rs-input" data-id="desktop">';
            $output .= '</div>';

            $output .= '<div class="basel-rs-trigger basel-css-tooltip" data-text="Responsive Options"><i class="dashicons dashicons-arrow-right-alt2"></i></div>';

            $output .= '<div class="basel-rs-item tablet hide">';
                $output .= '<span class="basel-rs-icon basel-css-tooltip" data-text="Tablet"><i class="dashicons dashicons-tablet"></i></span>';
                $output .= '<input type="number" min="1" class="basel-rs-input" data-id="tablet">';
            $output .= '</div>';

            $output .= '<div class="basel-rs-item mobile hide">';
                $output .= '<span class="basel-rs-icon basel-css-tooltip" data-text="Mobile"><i class="dashicons dashicons-smartphone"></i></span>';
                $output .= '<input type="number" min="1" class="basel-rs-input" data-id="mobile">';
            $output .= '</div>';

            $output .= '<div class="basel-rs-unit">px</div>';

            $output .= '<input type="hidden" data-css_args="' . esc_attr( json_encode( $settings['css_args'] ) ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value basel-rs-value" value="' . esc_attr( $value ) . '">';
        $output .= '</div>';

	    return $output;
    }
    
}
