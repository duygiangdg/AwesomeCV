<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Popup element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'basel_vc_map_popup' ) ) {
	function basel_vc_map_popup() {
		if ( ! shortcode_exists( 'basel_popup' ) ) {
			return;
		}

		$basel_popup_params = vc_map_integrate_shortcode( basel_get_basel_button_shortcode_args(), '', 'Button', array(
			'exclude' => array(
				'link2',
				'el_class'
			),
		) );

		vc_map( array(
			'name' => esc_html__( 'Popup', 'basel' ),
			'base' => 'basel_popup',
			'content_element' => true,
			'as_parent' => array( 'except' => 'testimonial' ),
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'description' => esc_html__( 'Button that shows a popup on click', 'basel' ),
        	'icon' => BASEL_ASSETS . '/images/vc-icon/popup.svg',
			'params' => array_merge( array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'ID', 'basel' ),
					'param_name' => 'id',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'basel' ),
					'param_name' => 'width',
					'description' => esc_html__( 'Popup width in pixels. For ex.: 800', 'basel' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'basel' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
				)
			), $basel_popup_params ),
		    'js_view' => 'VcColumnView',
		) );
	}
	add_action( 'vc_before_init', 'basel_vc_map_popup' );
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if( class_exists( 'WPBakeryShortCodesContainer' ) ){
    class WPBakeryShortCode_basel_popup extends WPBakeryShortCodesContainer {

    }
}
