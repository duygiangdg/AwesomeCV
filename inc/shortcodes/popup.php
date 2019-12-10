<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Content in popup
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'basel_shortcode_popup' ) ) {
	function basel_shortcode_popup( $atts, $content = '' ) {
		$output = '';
		$parsed_atts = shortcode_atts( array(
			'id' 	 	 => 'my_popup',
			'title' 	 => 'GO',
			'link2'      => '',
			'color' 	 => 'default',
			'style'   	 => 'default',
			'size' 		 => 'default',
			'align' 	 => 'center',
			'button_inline' => 'no',
			'width' 	 => 800,
			'el_class' 	 => '',
		), $atts) ;

		extract( $parsed_atts );

		$parsed_atts['link2'] = 'url:#' . esc_attr( $id ) . '|||';
		$parsed_atts['el_class'] = 'basel-popup-with-content ' . $el_class;

		$output .= basel_shortcode_button( $parsed_atts , true );

		$output .= '<div id="' . esc_attr( $id ) . '" class="mfp-with-anim basel-content-popup mfp-hide" style="max-width:' . esc_attr( $width ) . 'px;"><div class="basel-popup-inner">' . do_shortcode( $content ) . '</div></div>';

		return $output;

	}
}
