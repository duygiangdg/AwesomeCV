<?php

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'basel_shortcode_responsive_text_block' ) ) {
	/**
	* ------------------------------------------------------------------------------------------------
	* Responsive text block shortcode
	* ------------------------------------------------------------------------------------------------
	*/
	function basel_shortcode_responsive_text_block( $atts, $content ) {
		$text_classes = $output = $wrap_classes = '';
		
		extract( shortcode_atts( array(
			'text'          => 'Title',
			'font'          => 'primary',
			'font_weight'   => '',
			'content_width' => '100',
			'color_scheme'  => '',
			'size'          => 'default',
			'align'         => 'center',
			'basel_css_id'  => '',
			'el_class'      => '',
			'css'           => '',
		), $atts) );

		$id = 'basel-' . $basel_css_id;

		$wrap_classes .= ' basel-text-block-size-' . $size;
		$wrap_classes .= ' basel-text-block-width-' . $content_width;
		$wrap_classes .= ' color-scheme-' . $color_scheme;
		$wrap_classes .= ' text-' . $align;
		$wrap_classes .= $el_class ? ' ' . $el_class : '';

		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$wrap_classes .= ' ' . vc_shortcode_custom_css_class( $css );
		}

		$text_classes .= ' font-' . $font;
		$text_classes .= ' basel-font-weight-' . $font_weight;

		$output .= '<div id="' . esc_attr( $id ) . '" class="basel-text-block-wrapper ' . esc_attr( $wrap_classes ) . '">';
			$output .= '<div class="basel-text-block ' . esc_attr( $text_classes ) . '">';
				$output .= do_shortcode( $content );
			$output .= '</div>';
		$output .= '</div>';

		return $output;

	}

}
