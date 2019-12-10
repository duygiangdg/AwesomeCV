<?php

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
* Add image select
*/
if( ! function_exists( 'basel_add_image_select_type' ) ) {
	function basel_add_image_select_type( $settings, $value ) {
		$settings_value = array_flip( $settings['value'] );
		$value = ( ! $value && isset( $settings['std'] ) ) ? $settings['std'] : $value;
		$tooltip = ( isset( $settings['basel_tooltip'] ) ) ? $settings['basel_tooltip'] : false;
		$title = ( isset( $settings['title'] ) ) ? $settings['title'] : true;
		$classes = $tooltip ? 'basel-css-tooltip' : '';
		$classes .= ! $tooltip && $title ? ' with-title' : '';

		$output = '<ul class="basel-vc-image-select">';
			$output .= '<input type="hidden" class="basel-vc-image-select-input wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
			foreach ( $settings['value'] as $key => $value ) {
				$output .= '<li data-value="' . esc_attr( $value ) . '" class="' . esc_attr( $classes ) . '" data-text="' . esc_html( $settings_value[$value] ) . '">';
				$output .= '<img src="' . esc_url( $settings['images_value'][$value] ) . '">';
				if ( ! $tooltip && $title ) {
					$output .= '<h4>' . esc_html( $settings_value[$value] ) . '</h4>';
				}
				$output .= '</li>';
			}
		$output .= '</ul>';

		return $output;
	}
}
