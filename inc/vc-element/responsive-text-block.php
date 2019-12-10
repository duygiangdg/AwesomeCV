<?php

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'basel_vc_map_responsive_text_block' ) ) {

	/**
	* ------------------------------------------------------------------------------------------------
	* Responsive text block element map
	* ------------------------------------------------------------------------------------------------
	*/
	function basel_vc_map_responsive_text_block() {
		if ( ! shortcode_exists( 'basel_responsive_text_block' ) ) {
			return;
		}

		$secondary_font = basel_get_opt( 'secondary-font' );
		$primary_font = basel_get_opt( 'primary-font' );
		$text_font = basel_get_opt( 'text-font' );
		
		$secondary_font_title = $secondary_font ? esc_html__( 'Secondary', 'basel' ) . ' (' . $secondary_font['font-family'] . ')' : esc_html__( 'Secondary', 'basel' );
		$primary_font_title = $primary_font ? esc_html__( 'Primary', 'basel' ) . ' (' . $primary_font['font-family'] . ')' : esc_html__( 'Primary', 'basel' );
		$text_font_title = $text_font ? esc_html__( 'Text', 'basel' ) . ' (' . $text_font['font-family'] . ')' : esc_html__( 'Text', 'basel' );

		vc_map( array(
			'name' => esc_html__( 'Responsive text block', 'basel' ),
			'base' => 'basel_responsive_text_block',
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'description' => esc_html__( 'A block of text with responsive text sizes', 'basel' ),
        	'icon' => BASEL_ASSETS . '/images/vc-icon/text-blox-res.svg',
			'params' => array(
				array(
					'type' => 'basel_css_id',
					'param_name' => 'basel_css_id'
				),
				/**
				* Text
				*/
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => esc_html__( 'Text', 'basel' ),
					'param_name' => 'content'
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Text font', 'basel' ),
					'param_name' => 'font',
					'value' => array(
						$primary_font_title => 'primary',
						$text_font_title => 'text',
						$secondary_font_title => 'alt'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Font size', 'basel' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Default (22px)', 'basel' ) => 'default',
						esc_html__( 'Small (18px)', 'basel' ) => 'small',
						esc_html__( 'Medium (26px)', 'basel' ) => 'medium',
						esc_html__( 'Large (36px)', 'basel' ) => 'large',
						esc_html__( 'Extra Large (48px)', 'basel' ) => 'extra-large',
						esc_html__( 'Custom', 'basel' ) => 'custom'
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'basel_responsive_size',
					'heading' => esc_html__( 'Size', 'basel' ),
					'param_name' => 'text_font_size',
					'css_args' => array(
						'font-size' => array(
							' .basel-text-block',
						),
					),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'basel_responsive_size',
					'heading' => esc_html__( 'Line height', 'basel' ),
					'param_name' => 'text_line_height',
					'css_args' => array(
						'line-height' => array(
							' .basel-text-block',
						),
					),
					'dependency' => array(
						'element' => 'size',
						'value' => array( 'custom' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column', 
				),
				array(
					'type' => 'basel_empty_space',
					'param_name' => 'basel_empty_space',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Font weight', 'basel' ),
					'param_name' => 'font_weight',
					'value' => array(
						'' => '',
						esc_html__( 'Ultra-Light 100', 'basel' ) => 100,
						esc_html__( 'Light 200', 'basel' ) => 200,
						esc_html__( 'Book 300', 'basel' ) => 300,
						esc_html__( 'Normal 400', 'basel' ) => 400,
						esc_html__( 'Medium 500', 'basel' ) => 500,
						esc_html__( 'Semi-Bold 600', 'basel' ) => 600,
						esc_html__( 'Bold 700', 'basel' ) => 700,
						esc_html__( 'Extra-Bold 800', 'basel' ) => 800,
						esc_html__( 'Ultra-Bold 900', 'basel' ) => 900,
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'basel_dropdown',
					'heading' => esc_html__( 'Color scheme', 'basel' ),
					'param_name' => 'color_scheme',
					'value' => array(
						'' => '',
						esc_html__( 'Light', 'basel' ) => 'light',
						esc_html__( 'Dark', 'basel' ) => 'dark',
						esc_html__( 'Custom', 'basel' ) => 'custom'
					),
					'style' => array(
						'dark' => '#2d2a2a',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'basel_colorpicker',
					'heading' => esc_html__( 'Custom Color', 'basel' ),
					'param_name' => 'color',
					'css_args' => array(
						'color' => array(
							' .basel-text-block',
						),
					),
					'dependency' => array(
						'element' => 'color_scheme',
						'value' => array( 'custom' )
					),
				),
				/**
				* Layout
				*/
				array(
					'type' => 'basel_image_select',
					'heading' => esc_html__( 'Text align', 'basel' ),
					'param_name' => 'align',
				    'value' => array( 
						esc_html__( 'Left', 'basel' ) => 'left',
						esc_html__( 'Center', 'basel' ) => 'center',
						esc_html__( 'Right', 'basel' ) => 'right',
					),
					'images_value' => array(
						'center' => BASEL_ASSETS_IMAGES . '/settings/align/center.jpg',
						'left' => BASEL_ASSETS_IMAGES . '/settings/align/left.jpg',
						'right' => BASEL_ASSETS_IMAGES . '/settings/align/right.jpg',
					),
					'std' => 'center',
					'basel_tooltip' => true,
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				array(
					'type' => 'basel_slider',
					'heading' => esc_html__( 'Content width', 'basel' ),
					'param_name' => 'content_width',
					'min' => '10',
					'max' => '100',
					'step' => '10',
					'default' => '100',
					'units' => '%',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				/**
				* Extra
				*/
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'basel' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
				),
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'basel' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Design Options', 'basel' )
				),
			),
		) );
	}
	
	add_action( 'vc_before_init', 'basel_vc_map_responsive_text_block' );
}
