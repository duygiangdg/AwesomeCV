<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

if( ! function_exists( 'basel_vc_extra_classes' ) ) {

	if( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
		add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'basel_vc_extra_classes', 30, 3 );
	}

	function basel_vc_extra_classes( $class, $base, $atts ) {
		if( ! empty( $atts['basel_color_scheme'] ) ) {
			$class .= ' color-scheme-' . $atts['basel_color_scheme'];
		}

		if( ! empty( $atts['basel_parallax'] ) ) {
			$class .= ' basel-parallax';
		}
		if( ! empty( $atts['basel_gradient_switch'] ) && apply_filters( 'basel_gradients_enabled', true ) ) {
			$class .= ' basel-row-gradient-enable';
		}
		//Responsive opt
		if( ! empty( $atts['basel_hide_large'] ) ) {
			$class .= ' hidden-lg';
		}
		if( ! empty( $atts['basel_hide_medium'] ) ) {
			$class .= ' hidden-md';
		}
		if( ! empty( $atts['basel_hide_small'] ) ) {
			$class .= ' hidden-sm';
		}
		if( ! empty( $atts['basel_hide_extra_small'] ) ) {
			$class .= ' hidden-xs';
		}
		//Bg option
		if( ! empty( $atts['basel_bg_position'] ) ) {
			$class .= ' basel-bg-' . $atts['basel_bg_position'];
		}
		//Text align option
		if( ! empty( $atts['basel_text_align'] ) ) {
			$class .= ' text-' . $atts['basel_text_align'];
		}
		//Row reverse opt
		if( ! empty( $atts['row_reverse_mobile'] ) ) {
			$class .= ' row-reverse-mobile';
		}
		if( ! empty( $atts['row_reverse_tablet'] ) ) {
			$class .= ' row-reverse-tablet';
		}

		return $class;
	}

}

if( ! function_exists( 'basel_add_field_to_video' ) ) { 
	function basel_add_field_to_video() {

	    $vc_video_new_params = array(
	         
	        array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Add poster to video', 'basel' ),
				'param_name' => 'image_poster_switch',
				'group' => esc_html__( 'Basel Extras', 'basel' ),
				'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
			),
	        array(
	            'type' => 'attach_image',
				'heading' => esc_html__( 'Image', 'basel' ),
				'param_name' => 'poster_image',
				'value' => '',
				'description' => esc_html__( 'Select image from media library.', 'basel' ),
	            'group' => esc_html__( 'Basel Extras', 'basel' ),
				'dependency' => array(
					'element' => 'image_poster_switch',
					'value' => array( 'yes' ),
				) 
	        ),
	        array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image size', 'basel' ),
				'group' => esc_html__( 'Basel Extras', 'basel' ),
				'param_name' => 'img_size',
				'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "full" size.', 'basel' ),
				'dependency' => array(
					'element' => 'image_poster_switch',
					'value' => array( 'yes' ),
				)
			),      
	     
	    );
	     
	    vc_add_params( 'vc_video', $vc_video_new_params ); 
	}      
	add_action( 'vc_after_init', 'basel_add_field_to_video' ); 
}

if( ! function_exists( 'basel_section_title_color_variation' ) ) {

	function basel_section_title_color_variation() {
		$variation = array(
			esc_html__( 'Default', 'basel' ) => 'default',
			esc_html__( 'Primary color', 'basel' ) => 'primary',
			esc_html__( 'Alternative color', 'basel' ) => 'alt',
			esc_html__( 'Black', 'basel' ) => 'black',
			esc_html__( 'White', 'basel' ) => 'white',
		);
		$variation2 = array( esc_html__( 'Gradient', 'basel' ) => 'gradient' );
		if ( apply_filters( 'basel_gradients_enabled', true ) ) {
			$variation = array_merge( $variation, $variation2 ); 
		}
		return $variation;
	}

}

if( ! function_exists( 'basel_title_gradient_picker' ) ) {

	function basel_title_gradient_picker() {
		$title_color = array(
			'type' => 'basel_gradient',
			'param_name' => 'basel_color_gradient',
			'heading' => esc_html__( 'Gradient title color', 'basel' ),
			'dependency' => array(
				'element' => 'color',
				'value' => array( 'gradient' ),
			) 
		);
		if ( !apply_filters( 'basel_gradients_enabled', true ) ) $title_color = false;
		return $title_color;
	}

}

if( ! function_exists( 'basel_get_sliders_for_vc' ) ) {
	function basel_get_sliders_for_vc() {
		$args = array(
			'taxonomy' => 'basel_slider',
			'hide_empty' => false,
		);
		$sliders = get_terms( $args );

		if( is_wp_error( $sliders ) || empty( $sliders ) ) return array('');

		$data = array( '' );

		foreach ($sliders as $slider) {
			$data[$slider->name] = $slider->slug;
		}

		return $data;
	}
}

if( ! function_exists( 'basel_vc_map_shortcodes' ) ) {

	add_action( 'vc_before_init', 'basel_vc_map_shortcodes' );

	function basel_vc_map_shortcodes() {
		
		/**
		 * ------------------------------------------------------------------------------------------------
		 * Background position
		 * ------------------------------------------------------------------------------------------------
		 */

		$basel_bg_position = array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Background position', 'basel' ),
			'param_name' => 'basel_bg_position',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array(
				esc_html__( 'None', 'basel' ) => '',
				esc_html__( 'Left top', 'basel' ) => 'left-top',
				esc_html__( 'Left center', 'basel' ) => 'left-center',
				esc_html__( 'Left bottom', 'basel' ) => 'left-bottom',
				esc_html__( 'Right top', 'basel' ) => 'right-top',
				esc_html__( 'Right center', 'basel' ) => 'right-center',
				esc_html__( 'Right bottom', 'basel' ) => 'right-bottom',
				esc_html__( 'Center top', 'basel' ) => 'center-top',
				esc_html__( 'Center center', 'basel' ) => 'center-center',
				esc_html__( 'Center bottom', 'basel' ) => 'center-bottom',
			),
		);

		vc_add_param( 'vc_row', $basel_bg_position );
		vc_add_param( 'vc_row_inner', $basel_bg_position );
		vc_add_param( 'vc_section', $basel_bg_position );
		vc_add_param( 'vc_column', $basel_bg_position );
		vc_add_param( 'vc_column_inner', $basel_bg_position );

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Text align
		 * ------------------------------------------------------------------------------------------------
		 */

		$basel_text_align = array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Text align', 'basel' ),
			'param_name' => 'basel_text_align',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array(
				esc_html__( 'Choose', 'basel' ) => '',
				esc_html__( 'Left', 'basel' ) => 'left',
				esc_html__( 'Center', 'basel' ) => 'center',
				esc_html__( 'Right', 'basel' ) => 'right',
			),
		);

		vc_add_param( 'vc_column', $basel_text_align );
		vc_add_param( 'vc_column_inner', $basel_text_align );
		/**
		 * ------------------------------------------------------------------------------------------------
		 * Parallax option
		 * ------------------------------------------------------------------------------------------------
		 */

		$attributes = array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Basel parallax', 'basel' ),
			'param_name' => 'basel_parallax',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
		);

		vc_add_param( 'vc_row', $attributes );
		vc_add_param( 'vc_section', $attributes );
		vc_add_param( 'vc_column', $attributes );

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Gradient option
		 * ------------------------------------------------------------------------------------------------
		 */
		if( apply_filters( 'basel_gradients_enabled', true ) ) {
			$basel_gradient_switch = array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Basel gradient', 'basel' ),
				'param_name' => 'basel_gradient_switch',
				'group' => esc_html__( 'Basel Extras', 'basel' ),
				'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
			);

			$basel_color_gradient = array(
				'type' => 'basel_gradient',
				'param_name' => 'basel_color_gradient',
				'group' => esc_html__( 'Basel Extras', 'basel' ),
				'dependency' => array(
					'element' => 'basel_gradient_switch',
					'value' => array( 'yes' ),
				) 
			);


			vc_add_param( 'vc_row', $basel_gradient_switch );
			vc_add_param( 'vc_section', $basel_gradient_switch );

			vc_add_param( 'vc_row', $basel_color_gradient );
			vc_add_param( 'vc_section', $basel_color_gradient );
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Hide option
		 * ------------------------------------------------------------------------------------------------
		 */

		$basel_hide_large = array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Hide on large screens', 'basel' ),
			'param_name' => 'basel_hide_large',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
		);

		$basel_hide_medium = array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Hide on medium screens', 'basel' ),
			'param_name' => 'basel_hide_medium',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
		);

		$basel_hide_small = array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Hide on small screens', 'basel' ),
			'param_name' => 'basel_hide_small',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
		);

		$basel_hide_extra_small = array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Hide on extra small screens', 'basel' ),
			'param_name' => 'basel_hide_extra_small',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
		);

		vc_add_param( 'vc_empty_space', $basel_hide_large );
		vc_add_param( 'vc_empty_space', $basel_hide_medium );
		vc_add_param( 'vc_empty_space', $basel_hide_small );
		vc_add_param( 'vc_empty_space', $basel_hide_extra_small );
		
		/**
		 * ------------------------------------------------------------------------------------------------
		 * Row reverse mobile
		 * ------------------------------------------------------------------------------------------------
		 */

		$basel_row_reverse_mobile = array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Row reverse on mobile', 'basel' ),
			'param_name' => 'row_reverse_mobile',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
		);

		vc_add_param( 'vc_row', $basel_row_reverse_mobile );
		vc_add_param( 'vc_row_inner', $basel_row_reverse_mobile );
		
		/**
		 * ------------------------------------------------------------------------------------------------
		 * Row reverse tablet
		 * ------------------------------------------------------------------------------------------------
		 */

		$basel_row_reverse_tablet = array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Row reverse on tablet', 'basel' ),
			'param_name' => 'row_reverse_tablet',
			'group' => esc_html__( 'Basel Extras', 'basel' ),
			'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
		);

		vc_add_param( 'vc_row', $basel_row_reverse_tablet );
		vc_add_param( 'vc_row_inner', $basel_row_reverse_tablet );


		$target_arr = array(
			esc_html__( 'Same window', 'basel' ) => '_self',
			esc_html__( 'New window', 'basel' ) => "_blank"
		);

		$post_types_list = array();
		$post_types_list[] = array( 'post', esc_html__( 'Post', 'basel' ) );
		//$post_types_list[] = array( 'custom', esc_html__( 'Custom query', 'basel' ) );
		$post_types_list[] = array( 'ids', esc_html__( 'List of IDs', 'basel' ) );

		/**
		* ------------------------------------------------------------------------------------------------
		* Slider element map
		* ------------------------------------------------------------------------------------------------
		*/
		if ( shortcode_exists( 'basel_slider' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Slider', 'basel' ),
				'base' => 'basel_slider',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'BASEL theme slider', 'basel' ),
				'icon' => BASEL_ASSETS . '/images/vc-icon/slider.svg',
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slider', 'basel' ),
						'param_name' => 'slider',
						'value' => basel_get_sliders_for_vc()
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			) );
		}
		
		/**
		* ------------------------------------------------------------------------------------------------
		*  Brands element map
		* ------------------------------------------------------------------------------------------------
		*/
		
		$order_by_values = array(
			'',
			esc_html__( 'Name', 'basel' ) => 'name',
			esc_html__( 'Slug', 'basel' ) => 'slug',
			esc_html__( 'Term ID', 'basel' ) => 'term_id',
			esc_html__( 'ID', 'basel' ) => 'id',
			esc_html__( 'Random', 'basel' ) => 'random',
			esc_html__( 'As IDs or slugs provided order', 'basel' ) => 'include',
		);

		$order_way_values = array(
			'',
			esc_html__( 'Descending', 'basel' ) => 'DESC',
			esc_html__( 'Ascending', 'basel' ) => 'ASC',
		);

		if ( shortcode_exists( 'basel_brands' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Brands', 'basel' ),
				'base' => 'basel_brands',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Brands carousel/grid', 'basel' ),
				'icon' => BASEL_ASSETS . '/images/vc-icon/brands.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Number', 'basel' ),
						'param_name' => 'number',
						'description' => esc_html__( 'The `number` field is used to display the number of brands.', 'basel' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Order by', 'basel' ),
						'param_name' => 'orderby',
						'value' => $order_by_values,
						'save_always' => true,
						'description' => sprintf( wp_kses(  __( 'Select how to sort retrieved brands. More at %s.', 'basel' ), array(
								'a' => array( 
									'href' => array(), 
									'target' => array()
								)
							)), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Sort order', 'basel' ),
						'param_name' => 'order',
						'value' => $order_way_values,
						'save_always' => true,
						'description' => sprintf( wp_kses(  __( 'Designates the ascending or descending order. More at %s.', 'basel' ), array(
								'a' => array( 
									'href' => array(), 
									'target' => array()
								)
							)), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Image hover', 'basel' ),
						'param_name' => 'hover',
						'save_always' => true,
						'value' => array(
							'Default' => 'default',
							'Simple' => 'simple',
							'Alternate' => 'alt',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Brand style', 'basel' ),
						'param_name' => 'brand_style',
						'save_always' => true,
						'value' => array(
							'Default' => 'default',
							'Bordered' => 'bordered',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Layout', 'basel' ),
						'value' => 4,
						'param_name' => 'style',
						'save_always' => true,
						'value' => array(
							'Carousel' => 'carousel',
							'Grid' => 'grid',
							'Links List' => 'list',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slides per view', 'basel' ),
						'param_name' => 'per_row',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
						'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'basel' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide pagination control', 'basel' ),
						'param_name' => 'hide_pagination_control',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
						'param_name' => 'hide_prev_next_buttons',
						'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider loop', 'basel' ),
						'param_name' => 'wrap',
						'description' => esc_html__( 'Enables loop mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Columns', 'basel' ),
						'value' => 3,
						'param_name' => 'columns',
						'save_always' => true,
						'description' => esc_html__( 'How much columns grid', 'basel' ),
						'value' => array(
							'',
							'1' => 1,
							'2' => 2,
							'3' => 3,
							'4' => 4,
							'5' => 5,
							'6' => 6,
						),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'grid', 'list' ),
						),
					),
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Brands', 'basel' ),
						'param_name' => 'ids',
						'settings' => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'description' => esc_html__( 'List of product brands to show. Leave empty to show all', 'basel' ),
					)
				)
			) );
		}

		//Filters For autocomplete param:
		//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
		add_filter( 'vc_autocomplete_basel_brands_ids_callback', 'basel_productBrandsAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_basel_brands_ids_render', 'basel_productBrandsRenderByIdExact', 10, 1 );
		
		if( ! function_exists( 'basel_productBrandsAutocompleteSuggester' ) ) {
			function basel_productBrandsAutocompleteSuggester( $query, $slug = false ) {
				global $wpdb;
				$cat_id = (int) $query;
				$query = trim( $query );
		
				$attribute = basel_get_opt( 'brands_attribute' );
		
				$post_meta_infos = $wpdb->get_results(
					$wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
								FROM {$wpdb->term_taxonomy} AS a
								INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
								WHERE a.taxonomy = '%s' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )",
								$attribute,
						$cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );
		
				$result = array();
				if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
					foreach ( $post_meta_infos as $value ) {
						$data = array();
						$data['value'] = $slug ? $value['slug'] : $value['id'];
						$data['label'] = esc_html__( 'Id', 'basel' ) . ': ' .
						                 $value['id'] .
						                 ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . esc_html__( 'Name', 'basel' ) . ': ' .
						                                                      $value['name'] : '' ) .
						                 ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . esc_html__( 'Slug', 'basel' ) . ': ' .
						                                                      $value['slug'] : '' );
						$result[] = $data;
					}
				}
		
				return $result;
			}
		}
		
		if( ! function_exists( 'basel_productBrandsRenderByIdExact' ) ) {
			function basel_productBrandsRenderByIdExact( $query ) {
				global $wpdb;
				$query = $query['value'];
				$cat_id = (int) $query;
				$attribute = basel_get_opt( 'brands_attribute' );
				$term = get_term( $cat_id, $attribute );
		
				return basel_productCategoryTermOutput( $term );
			}
		}
		
		/**
		* ------------------------------------------------------------------------------------------------
		* Extra menu list element map
		* ------------------------------------------------------------------------------------------------
		*/
		if ( shortcode_exists( 'extra_menu' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Extra menu list', 'basel' ),
				'base' => 'extra_menu',
				'as_parent' => array( 'only' => 'extra_menu_list' ),
				'content_element' => true,
				'show_settings_on_create' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Create a menu list for your mega menu dropdown', 'basel' ),
				'icon' => BASEL_ASSETS . '/images/vc-icon/extra-menu-list.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
						'value' => '',
					),
					array(
						'type' => 'vc_link',
						'heading' => esc_html__( 'Link', 'basel'),
						'param_name' => 'link',
						'description' => esc_html__( 'Enter URL if you want this parent menu item to have a link.', 'basel' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Label (optional)', 'basel' ),
						'param_name' => 'label',
						'value' => array(
							esc_html__( 'None', 'basel' ) => '',
							esc_html__( 'Hot', 'basel' ) => 'hot',
							esc_html__( 'New', 'basel' ) => 'new',
							esc_html__( 'Sale', 'basel' ) => 'sale',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
				'js_view' => 'VcColumnView'
			) );

			vc_map( array(
				'name' => esc_html__( 'Extra menu list item', 'basel' ),
				'base' => 'extra_menu_list',
				'as_child' => array( 'only' => 'extra_menu' ),
				'content_element' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'A link for your extra menu list', 'basel' ),
				'icon' => BASEL_ASSETS . '/images/vc-icon/extra-menu-list-item.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
						'value' => '',
					),
					array(
						'type' => 'vc_link',
						'heading' => esc_html__( 'Link', 'basel'),
						'param_name' => 'link',
						'description' => esc_html__( 'Enter URL if you want this parent menu item to have a link.', 'basel' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Label (optional)', 'basel' ),
						'param_name' => 'label',
						'value' => array(
							esc_html__( 'None', 'basel' ) => '',
							esc_html__( 'Hot', 'basel' ) => 'hot',
							esc_html__( 'New', 'basel' ) => 'new',
							esc_html__( 'Sale', 'basel' ) => 'sale',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				)
			) );
			
			if( class_exists( 'WPBakeryShortCodesContainer' ) ){
				class WPBakeryShortCode_extra_menu extends WPBakeryShortCodesContainer {
			
				}
			}
			
			// Replace Wbc_Inner_Item with your base name from mapping for nested element
			if( class_exists( 'WPBakeryShortCode' ) ){
				class WPBakeryShortCode_extra_menu_list extends WPBakeryShortCode {
			
				}
			}
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 *  List element map
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_list' ) ) {
			vc_map( array(
				'name' => esc_html__( 'List', 'basel' ),
				'base' => 'basel_list',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Display a list with icon', 'basel' ),
				'icon' => BASEL_ASSETS . '/images/vc-icon/list.svg',
				'params' => array(
					//General
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'List size', 'basel' ),
						'param_name' => 'size',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => 'default',
							esc_html__( 'Small', 'basel' ) => 'small',
							esc_html__( 'Medium', 'basel' ) => 'medium',
							esc_html__( 'Large', 'basel' ) => 'large',
							esc_html__( 'Extra Large', 'basel' ) => 'extra-large',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Color scheme', 'basel' ),
						'param_name' => 'color_scheme',
						'value' => array(
							'' => '',
							esc_html__( 'Light', 'basel' ) => 'light',
							esc_html__( 'Dark', 'basel' ) => 'dark',
						)
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
					//List
					array(
						'type'       => 'param_group',
						'param_name' => 'list',
						'group'      => esc_html__( 'List', 'basel' ),
						'params'     => array(
							array(
								'type'             => 'textarea',
								'heading'          => esc_html__( 'Content', 'basel' ),
								'param_name'       => 'list-content'
							)
						)
					),
					//Icon
					array(
						'type' => 'dropdown',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'List type', 'basel' ),
						'value' => array(
							esc_html__( 'With icon', 'basel' ) => 'icon',
							esc_html__( 'With image', 'basel' ) => 'image',
							esc_html__( 'Ordered', 'basel' ) => 'ordered',
							esc_html__( 'Unordered', 'basel' ) => 'unordered',
							esc_html__( 'Without icon', 'basel' ) => 'without'
						),
						'param_name' => 'list_type'
					),
					array(
						'type' => 'dropdown',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'List style', 'basel' ),
						'value' => array(
							esc_html__( 'Default', 'basel' ) => 'default',
							esc_html__( 'Rounded', 'basel' ) => 'rounded',
							esc_html__( 'Square', 'basel' ) => 'square',
						),
						'param_name' => 'list_style',
						'dependency' => array(
							'element' => 'list_type',
							'value' => array( 'icon', 'ordered', 'unordered' )
						)
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'Image', 'basel' ),
						'group' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'image',
						'value' => '',
						'description' => esc_html__( 'Select image from media library.', 'basel' ),
						'dependency' => array(
							'element' => 'list_type',
							'value' => array( 'image' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Image size', 'basel' ),
						'group' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' ),
						'dependency' => array(
							'element' => 'list_type',
							'value' => array( 'image' ),
						),
					),
					array(
						'type' => 'dropdown',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon library', 'basel' ),
						'value' => array(
							esc_html__( 'Font Awesome', 'basel' ) => 'fontawesome',
							esc_html__( 'Open Iconic', 'basel' ) => 'openiconic',
							esc_html__( 'Typicons', 'basel' ) => 'typicons',
							esc_html__( 'Entypo', 'basel' ) => 'entypo',
							esc_html__( 'Linecons', 'basel' ) => 'linecons',
							esc_html__( 'Mono Social', 'basel' ) => 'monosocial',
							esc_html__( 'Material', 'basel' ) => 'material'
						),
						'param_name' => 'icon_library',
						'description' => esc_html__( 'Select icon library.', 'basel' ),
						'dependency' => array(
							'element' => 'list_type',
							'value' => 'icon'
						)
					),
					array(
						'type' => 'iconpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'icon_fontawesome',
						'value' => 'fa fa-adjust',
						'settings' => array(
							'emptyIcon' => false,
							'iconsPerPage' => 4000
						),
						'dependency' => array(
							'element' => 'icon_library',
							'value' => 'fontawesome'
						),
						'description' => esc_html__( 'Select icon from library.', 'basel' ),
					),
					array(
						'type' => 'iconpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'icon_openiconic',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'openiconic',
							'iconsPerPage' => 4000
						),
						'dependency' => array(
							'element' => 'icon_library',
							'value' => 'openiconic'
						),
						'description' => esc_html__( 'Select icon from library.', 'basel' ),
					),
					array(
						'type' => 'iconpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'icon_typicons',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'typicons',
							'iconsPerPage' => 4000
						),
						'dependency' => array(
							'element' => 'icon_library',
							'value' => 'typicons'
						),
						'description' => esc_html__( 'Select icon from library.', 'basel' )
					),
					array(
						'type' => 'iconpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'icon_entypo',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'entypo',
							'iconsPerPage' => 4000
						),
						'dependency' => array(
							'element' => 'icon_library',
							'value' => 'entypo'
						)
					),
					array(
						'type' => 'iconpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'icon_linecons',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'linecons',
							'iconsPerPage' => 4000
						),
						'dependency' => array(
							'element' => 'icon_library',
							'value' => 'linecons'
						),
						'description' => esc_html__( 'Select icon from library.', 'basel' )
					),
					array(
						'type' => 'iconpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'icon_monosocial',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'monosocial',
							'iconsPerPage' => 4000
						),
						'dependency' => array(
							'element' => 'icon_library',
							'value' => 'monosocial'
						),
						'description' => esc_html__( 'Select icon from library.', 'basel' )
					),
					array(
						'type' => 'iconpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icon', 'basel' ),
						'param_name' => 'icon_material',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'material',
							'iconsPerPage' => 4000
						),
						'dependency' => array(
							'element' => 'icon_library',
							'value' => 'material'
						),
						'description' => esc_html__( 'Select icon from library.', 'basel' )
					),
					array(
						'type' => 'colorpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icons color', 'basel' ),
						'param_name' => 'icons_color',
						'dependency' => array(
							'element' => 'list_type',
							'value' => array( 'icon', 'ordered', 'unordered' )
						)
					),
					array(
						'type' => 'colorpicker',
						'group' => esc_html__( 'Icon', 'basel' ),
						'heading' => esc_html__( 'Icons background color', 'basel' ),
						'param_name' => 'icons_bg_color',
						'dependency' => array(
							'element' => 'list_style',
							'value' => array( 'rounded', 'square' )
						)
					),
					//Style
					array(
						'type' => 'css_editor',
						'heading' => esc_html__( 'CSS box', 'basel' ),
						'param_name' => 'css',
						'group' => esc_html__( 'Design Options', 'basel' )
					)
				)
			) );
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Timeline shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_timeline' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Timeline', 'basel'),
				'base' => 'basel_timeline',
				'as_parent' => array('only' => 'basel_timeline_item,basel_timeline_breakpoint'),
				'content_element' => true,
				'show_settings_on_create' => true,
				'description' => esc_html__( 'Timeline for the history of your product', 'basel' ),
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/timeline.svg',
				'params' => array(
					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Color of line', 'basel' ),
						'param_name' => 'line_color',
					),
					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Color of dots', 'basel' ),
						'param_name' => 'dots_color',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
				'js_view' => 'VcColumnView',
			) );
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Timeline item shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_timeline_item' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Timeline item', 'basel'),
				'base' => 'basel_timeline_item',
				'as_child' => array('only' => 'basel_timeline'),
				'content_element' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/timeline-item.svg',
				'params' => array(
					array(
						'type' => 'textarea',
						'holder' => 'div',
						'heading' => esc_html__( 'Title Primary', 'basel' ),
						'param_name' => 'title_primary',
						'description' => esc_html__( 'Provide the title for primary timeline item.', 'basel' )
					),
					array(
						'type' => 'textarea_html',
						'heading' => esc_html__( 'Content Primary', 'basel' ),
						'param_name' => 'content',
						'description' => esc_html__( 'Provide the description for primary timeline item.', 'basel' )
					),
					array(
						'type' => 'vc_link',
						'heading' => esc_html__( 'Link primary', 'basel'),
						'param_name' => 'link_primary',
						'description' => esc_html__( 'Enter URL if you want this banner to have a link.', 'basel' )
					),
					array(
						'type' => 'textarea',
						'holder' => 'div',
						'heading' => esc_html__( 'Title Secondary', 'basel' ),
						'param_name' => 'title_secondary',
						'description' => esc_html__( 'Provide the title for secondary timeline item.', 'basel' )
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'Image Secondary', 'basel' ),
						'param_name' => 'image_secondary',
						'value' => '',
						'description' => esc_html__( 'Select image from media library.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Image size', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
					),
					array(
						'type' => 'textarea',
						'heading' => esc_html__( 'Content Secondary', 'basel' ),
						'param_name' => 'content_secondary',
						'description' => esc_html__( 'Provide the description for secondary timeline item.', 'basel' )
					),
					array(
						'type' => 'vc_link',
						'heading' => esc_html__( 'Link Secondary', 'basel'),
						'param_name' => 'link_secondary',
						'description' => esc_html__( 'Enter URL if you want this banner to have a link.', 'basel' )
					),
					array(
						'type' => 'colorpicker',
						'holder' => 'div',
						'heading' => esc_html__( 'Background color ', 'basel' ),
						'param_name' => 'color_bg',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Position', 'basel' ),
						'param_name' => 'position',
						'value' => array(
							esc_html__( 'Left', 'basel' ) => 'left',
							esc_html__( 'Right', 'basel' ) => 'right',
							esc_html__( 'Full Width', 'basel' ) => 'full-width',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
			) );
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Timeline breakpoint shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_timeline_breakpoint' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Timeline breakpoint', 'basel'),
				'base' => 'basel_timeline_breakpoint',
				'as_child' => array('only' => 'basel_timeline'),
				'content_element' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/timeline-breakpoint.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
						'description' => esc_html__( 'Provide the title for this timeline item.', 'basel' )
					),
					array(
						'type' => 'colorpicker',
						'holder' => 'div',
						'heading' => esc_html__( 'Background color ', 'basel' ),
						'param_name' => 'color_bg',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
			) );

			// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
			if(class_exists('WPBakeryShortCodesContainer')){
				class WPBakeryShortCode_basel_timeline extends WPBakeryShortCodesContainer {}
			}

			// Replace Wbc_Inner_Item with your base name from mapping for nested element
			if(class_exists('WPBakeryShortCode')){
				class WPBakeryShortCode_basel_timeline_item extends WPBakeryShortCode {}
			}
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Section divider shortcode
		 * ------------------------------------------------------------------------------------------------
		 */

		if ( shortcode_exists( 'basel_row_divider' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Section divider', 'basel'),
				'base' => 'basel_row_divider',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Divider for sections', 'basel' ),
	        	'icon'            => BASEL_ASSETS . '/images/vc-icon/section-divider.svg',
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Position', 'basel' ),
						'param_name' => 'position',
						'value' => array(
							esc_html__( 'Top', 'basel' ) => 'top',
							esc_html__( 'Bottom', 'basel' ) => 'bottom',
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Overlap', 'basel' ),
						'param_name' => 'content_overlap',
						'value' => array( esc_html__( 'Enable', 'basel' ) => 'enable' )
					),
					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Color', 'basel' ),
						'param_name' => 'color',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							esc_html__( 'Waves Small', 'basel' ) => 'waves-small',
							esc_html__( 'Waves Wide', 'basel' ) => 'waves-wide',
							esc_html__( 'Curved Line', 'basel' ) => 'curved-line',
							esc_html__( 'Triangle', 'basel' ) => 'triangle',
							esc_html__( 'Clouds', 'basel' ) => 'clouds',
							esc_html__( 'Diagonal Right', 'basel' ) => 'diagonal-right',
							esc_html__( 'Diagonal Left', 'basel' ) => 'diagonal-left',
							esc_html__( 'Half Circle', 'basel' ) => 'half-circle',
							esc_html__( 'Paint Stroke', 'basel' ) => 'paint-stroke',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Custom height', 'basel' ),
						'param_name' => 'custom_height',
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'curved-line', 'diagonal-right', 'half-circle', 'diagonal-left' )
						),
						'description' => esc_html__( 'Enter divider height (Note: CSS measurement units allowed).', 'basel' )
					),
					
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
			) );
		}

		/**
 		 * ------------------------------------------------------------------------------------------------
 		 * Map title shortcode
 		 * ------------------------------------------------------------------------------------------------
 		 */
		if ( shortcode_exists( 'basel_title' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Section title', 'basel' ),
				'base' => 'basel_title',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Styled title for sections', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/section-title.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'holder' => 'div',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Sub title', 'basel' ),
						'param_name' => 'subtitle'
					),
					array(
						'type' => 'textarea',
						'heading' => esc_html__( 'Text after title', 'basel' ),
						'param_name' => 'after_title',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Title style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => 'default',
							esc_html__( 'Simple', 'basel' ) => 'simple',
							esc_html__( 'X sign', 'basel' ) => 'cross',
							esc_html__( 'Bordered', 'basel' ) => 'bordered',
							esc_html__( 'Shadow', 'basel' ) => 'shadow',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Title color', 'basel' ),
						'param_name' => 'color',
						'value' => basel_section_title_color_variation()
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Title tag', 'basel' ),
						'param_name' => 'tag',
						'value' => array(
							'h1','h2','h3','h4','h5','h6','p','div','span'
						),
						'std' => 'h4'
					),
					basel_title_gradient_picker(),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Title size', 'basel' ),
						'param_name' => 'size',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => 'default',
							esc_html__( 'Small', 'basel' ) => 'small',
							esc_html__( 'Large', 'basel' ) => 'large',
							esc_html__( 'Extra Large', 'basel' ) => 'extra-large',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Subtitle font', 'basel' ),
						'param_name' => 'subtitle_font',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => 'default',
							esc_html__( 'Alternative', 'basel' ) => 'alt',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Title align', 'basel' ),
						'param_name' => 'align',
						'value' => array(
							esc_html__( 'Center', 'basel' ) => 'center',
							esc_html__( 'Left', 'basel' ) => 'left',
							esc_html__( 'Right', 'basel' ) => 'right',
						),
					),
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
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map blog shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_blog' ) ) {
			vc_map( array(
				'name' => esc_html__('Blog', 'basel' ),
				'base' => 'basel_blog',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Show your blog posts on the page', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/blog.svg',
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Data source', 'basel' ),
						'param_name' => 'post_type',
						'value' => $post_types_list,
						'description' => esc_html__( 'Select content type for your grid.', 'basel' )
					),
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Include only', 'basel' ),
						'param_name' => 'include',
						'description' => esc_html__( 'Add posts, pages, etc. by title.', 'basel' ),
						'settings' => array(
							'multiple' => true,
							'sortable' => true,
							'groups' => true,
						),
						'dependency' => array(
							'element' => 'post_type',
							'value' => array( 'ids' ),
							//'callback' => 'vc_grid_include_dependency_callback',
						),
					),
					// Custom query tab
					array(
						'type' => 'textarea_safe',
						'heading' => esc_html__( 'Custom query', 'basel' ),
						'param_name' => 'custom_query',
						'description' => wp_kses( __( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts">WordPress Codex</a>.', 'basel' ), 'default'),
						'dependency' => array(
							'element' => 'post_type',
							'value' => array( 'custom' ),
						),
					),
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Narrow data source', 'basel' ),
						'param_name' => 'taxonomies',
						'settings' => array(
							'multiple' => true,
							// is multiple values allowed? default false
							// 'sortable' => true, // is values are sortable? default false
							'min_length' => 1,
							// min length to start search -> default 2
							// 'no_hide' => true, // In UI after select doesn't hide an select list, default false
							'groups' => true,
							// In UI show results grouped by groups, default false
							'unique_values' => true,
							// In UI show results except selected. NB! You should manually check values in backend, default false
							'display_inline' => true,
							// In UI show results inline view, default false (each value in own line)
							'delay' => 500,
							// delay for search. default 500
							'auto_focus' => true,
							// auto focus input, default true
							// 'values' => $taxonomies_for_filter,
						),
						'param_holder_class' => 'vc_not-for-custom',
						'description' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'basel' ),
						'dependency' => array(
							'element' => 'post_type',
							'value_not_equal_to' => array( 'ids', 'custom' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Items per page', 'basel' ),
						'param_name' => 'items_per_page',
						'description' => esc_html__( 'Number of items to show per page.', 'basel' ),
						'value' => '10',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Pagination', 'basel' ),
						'param_name' => 'pagination',
						'value' => array(
							'' => '',
							'Pagination' => 'pagination',
							'Load more button' => 'more-btn',
						),
					),
					// Design settings
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Style', 'basel' ),
						'param_name' => 'blog_design',
						'value' => array(
							'Default' => 'default',
							'Default alternative' => 'default-alt',
							'Small images' => 'small-images',
							'Masonry grid' => 'masonry',
							'Mask on image' => 'mask'
						),
						'description' => esc_html__( 'You can use different design for your blog styled for the theme', 'basel' ),
						'group' => esc_html__( 'Design', 'basel' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Images size', 'basel' ),
						'group' => esc_html__( 'Design', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Columns', 'basel' ),
						'param_name' => 'blog_columns',
						'value' => array(
							2, 3, 4, 6
						),
						'description' => esc_html__( 'Blog items columns', 'basel' ),
						'group' => esc_html__( 'Design', 'basel' ),
						'dependency' => array(
							'element' => 'blog_design',
							'value' => array( 'masonry', 'mask' ),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Title for posts', 'basel' ),
						'param_name' => 'parts_title',
						'group' => esc_html__( 'Design', 'basel' ),
						'value' => array(
							'Show' => 1,
							'Hide' => 0,
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Meta information', 'basel' ),
						'param_name' => 'parts_meta',
						'group' => esc_html__( 'Design', 'basel' ),
						'value' => array(
							'Show' => 1,
							'Hide' => 0,
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Post text', 'basel' ),
						'param_name' => 'parts_text',
						'group' => esc_html__( 'Design', 'basel' ),
						'value' => array(
							'Show' => 1,
							'Hide' => 0,
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Read more button', 'basel' ),
						'param_name' => 'parts_btn',
						'group' => esc_html__( 'Design', 'basel' ),
						'value' => array(
							'Show' => 1,
							'Hide' => 0,
						),
					),
					// Data settings
					array(
						'type' => '`dropdown`',
						'heading' => esc_html__( 'Order by', 'basel' ),
						'param_name' => 'orderby',
						'value' => array(
							esc_html__( 'Date', 'basel' ) => 'date',
							esc_html__( 'Order by post ID', 'basel' ) => 'ID',
							esc_html__( 'Author', 'basel' ) => 'author',
							esc_html__( 'Title', 'basel' ) => 'title',
							esc_html__( 'Last modified date', 'basel' ) => 'modified',
							esc_html__( 'Post/page parent ID', 'basel' ) => 'parent',
							esc_html__( 'Number of comments', 'basel' ) => 'comment_count',
							esc_html__( 'Menu order/Page Order', 'basel' ) => 'menu_order',
							esc_html__( 'Meta value', 'basel' ) => 'meta_value',
							esc_html__( 'Meta value number', 'basel' ) => 'meta_value_num',
							// esc_html__('Matches same order you passed in via the 'include' parameter.', 'basel') => 'post__in'
							esc_html__( 'Random order', 'basel' ) => 'rand',
						),
						'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'basel' ),
						'group' => esc_html__( 'Data Settings', 'basel' ),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'dependency' => array(
							'element' => 'post_type',
							'value_not_equal_to' => array( 'ids', 'custom' ),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Sorting', 'basel' ),
						'param_name' => 'order',
						'group' => esc_html__( 'Data Settings', 'basel' ),
						'value' => array(
							esc_html__( 'Descending', 'basel' ) => 'DESC',
							esc_html__( 'Ascending', 'basel' ) => 'ASC',
						),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'description' => esc_html__( 'Select sorting order.', 'basel' ),
						'dependency' => array(
							'element' => 'post_type',
							'value_not_equal_to' => array( 'ids', 'custom' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Meta key', 'basel' ),
						'param_name' => 'meta_key',
						'description' => esc_html__( 'Input meta key for grid ordering.', 'basel' ),
						'group' => esc_html__( 'Data Settings', 'basel' ),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'dependency' => array(
							'element' => 'orderby',
							'value' => array( 'meta_value', 'meta_value_num' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Offset', 'basel' ),
						'param_name' => 'offset',
						'description' => esc_html__( 'Number of grid elements to displace or pass over.', 'basel' ),
						'group' => esc_html__( 'Data Settings', 'basel' ),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'dependency' => array(
							'element' => 'post_type',
							'value_not_equal_to' => array( 'ids', 'custom' ),
						),
					),
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Exclude', 'basel' ),
						'param_name' => 'exclude',
						'description' => esc_html__( 'Exclude posts, pages, etc. by title.', 'basel' ),
						'group' => esc_html__( 'Data Settings', 'basel' ),
						'settings' => array(
							'multiple' => true,
						),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'dependency' => array(
							'element' => 'post_type',
							'value_not_equal_to' => array( 'ids', 'custom' ),
							'callback' => 'vc_grid_exclude_dependency_callback',
						),
					)
				)
			) );

			// Necessary hooks for blog autocomplete fields
			add_filter( 'vc_autocomplete_basel_blog_include_callback',	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_basel_blog_include_render',
				'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

			// Narrow data taxonomies
			add_filter( 'vc_autocomplete_basel_blog_taxonomies_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
			add_filter( 'vc_autocomplete_basel_blog_taxonomies_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

			// Narrow data taxonomies for exclude_filter
			add_filter( 'vc_autocomplete_basel_blog_exclude_filter_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
			add_filter( 'vc_autocomplete_basel_blog_exclude_filter_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

			add_filter( 'vc_autocomplete_basel_blog_exclude_callback',	'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_basel_blog_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map social buttons shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'social_buttons' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Social buttons', 'basel' ),
				'base' => 'social_buttons',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Follow or share buttons', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/social-buttons.svg',
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Buttons type', 'basel' ),
						'param_name' => 'type',
						'value' => array(
							esc_html__( 'Share', 'basel' ) => 'share',
							esc_html__( 'Follow', 'basel' ) => 'follow',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Buttons size', 'basel' ),
						'param_name' => 'size',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => '',
							esc_html__( 'Small', 'basel' ) => 'small',
							esc_html__( 'Large', 'basel' ) => 'large',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Buttons style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => '',
							esc_html__( 'Circle buttons', 'basel' ) => 'circle',
							esc_html__( 'Colored', 'basel' ) => 'colored',
							esc_html__( 'Colored alternative', 'basel' ) => 'colored-alt',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Align', 'basel' ),
						'param_name' => 'align',
						'value' => array(
							esc_html__( 'center', 'basel' ) => 'center',
							esc_html__( 'left', 'basel' ) => 'left',
							esc_html__( 'right', 'basel' ) => 'right',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map button shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_button' ) ) {
			vc_map( basel_get_basel_button_shortcode_args() );
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map Portfolio shortcode
		 * ------------------------------------------------------------------------------------------------
		 */

		$order_by_values = array(
			'',
			esc_html__( 'Date', 'basel' ) => 'date',
			esc_html__( 'ID', 'basel' ) => 'ID',
			// esc_html__( 'Author', 'basel' ) => 'author',
			esc_html__( 'Title', 'basel' ) => 'title',
			esc_html__( 'Modified', 'basel' ) => 'modified',
			esc_html__( 'Menu order', 'basel' ) => 'menu_order',
			//esc_html__( 'Random', 'basel' ) => 'rand',
			// esc_html__( 'Comment count', 'basel' ) => 'comment_count',
			// esc_html__( 'Menu order', 'basel' ) => 'menu_order',
		);

		$order_way_values = array(
			'',
			esc_html__( 'Descending', 'basel' ) => 'DESC',
			esc_html__( 'Ascending', 'basel' ) => 'ASC',
		);

		if ( shortcode_exists( 'basel_portfolio' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Portfolio', 'basel' ),
				'base' => 'basel_portfolio',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Showcase your projects or gallery', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/portfolio.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Number of posts per page', 'basel' ),
						'param_name' => 'posts_per_page'
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							esc_html__('Inherit from theme settings', 'basel' ) => '',
							esc_html__('Show text on mouse over', 'basel' ) => 'hover',
							esc_html__('Hide text on mouse over', 'basel' ) => 'hover-inverse',
							esc_html__('Bordered style', 'basel' ) => 'bordered',
							esc_html__('Bordered inverse', 'basel' ) => 'bordered-inverse',
							esc_html__('Text under image', 'basel' ) => 'text-shown',
							esc_html__('Text with background', 'basel' ) => 'with-bg',
							esc_html__('Text with background alternative', 'basel' ) => 'with-bg-alt',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Columns', 'basel' ),
						'param_name' => 'columns',
						'value' => array(
							2,
							3,
							4,
							6,
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Space between projects', 'basel' ),
						'param_name' => 'spacing',
						'value' => array(
							0,
							2,
							6,
							10,
							20,
							30
						)
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Show categories filters', 'basel' ),
						'param_name' => 'filters',
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
					),

					basel_get_color_scheme_param(),

					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Filters background', 'basel' ),
						'param_name' => 'filters_bg',
					),

					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Categories', 'basel' ),
						'param_name' => 'categories',
						'value' => basel_get_projects_cats_array()
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Order by', 'basel' ),
						'param_name' => 'orderby',
						'value' => $order_by_values,
						'save_always' => true,
						'description' => sprintf( esc_html__( 'Select how to sort retrieved projects. More at %s.', 'basel' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Sort order', 'basel' ),
						'param_name' => 'order',
						'value' => $order_way_values,
						'save_always' => true,
						'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'basel' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Pagination', 'basel' ),
						'param_name' => 'pagination',
						'value' => array(
							'' => '',
							'Pagination' => 'pagination',
							'Load more button' => 'load_more',
							'Infinit' => 'infinit',
							'Disable' => 'disable',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map Google Map shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_google_map' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Google Map', 'basel' ),
				'base' => 'basel_google_map',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Shows Google map block', 'basel' ),
				"as_parent" => array('except' => 'testimonial'),
				"content_element" => true,
				"js_view" => 'VcColumnView',
				'icon'            => BASEL_ASSETS . '/images/vc-icon/google-maps.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Latitude (required)', 'basel' ),
						'param_name' => 'lat',
						'description' => 'You can use <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">this service</a> to get coordinates of your location'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Longitude (required)', 'basel' ),
						'param_name' => 'lon'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Google API key (required)', 'basel' ),
						'param_name' => 'google_key',
						'description' => wp_kses( __('Obrain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.', 'basel'), 'default' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title'
					),
					array(
						'type' => 'textarea',
						'heading' => esc_html__( 'Text on marker', 'basel' ),
						'param_name' => 'popup_text'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Zoom', 'basel' ),
						'param_name' => 'zoom',
						'description' => 'Zoom level when focus the marker<br> 0 - 19'
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'Marker icon', 'basel' ),
						'param_name' => 'marker_icon'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Height', 'basel' ),
						'param_name' => 'height',
						'description' => 'Default: 400'
					),
					array(
						'type' => 'textarea_raw_html',
						'heading' => esc_html__( 'Styles (JSON)', 'basel' ),
						'param_name' => 'style_json',
						'description' => 'Styled maps allow you to customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas.<br>
	You can find more Google Maps styles on the website: <a target="_blank" href="http://snazzymaps.com/">Snazzy Maps</a><br>
	Just copy JSON code and paste it here<br>
	For example:<br>
	[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]
						'
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Zoom with mouse wheel', 'basel' ),
						'param_name' => 'scroll',
						'value' => array(
							'' => '',
							esc_html__( 'Yes', 'basel' ) => 'yes',
							esc_html__( 'No', 'basel' ) => 'no',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Map mask', 'basel' ),
						'param_name' => 'mask',
						'value' => array(
							'' => '',
							esc_html__( 'Dark', 'basel' ) => 'dark',
							esc_html__( 'Light', 'basel' ) => 'light',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map Mega Menu shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_mega_menu' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Mega Menu widget', 'basel' ),
				'base' => 'basel_mega_menu',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Categories mega menu widget', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/mega-menu-widget.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title'
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Choose Menu', 'basel' ),
						'param_name' => 'nav_menu',
						'value' => basel_get_menus_array()
					),
					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Title Color', 'basel' ),
						'param_name' => 'color'
					),
					basel_get_color_scheme_param(),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map Counter shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_counter' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Animated Counter', 'basel' ),
				'description' => esc_html__( 'Shows animated counter with label', 'basel' ),
				'base' => 'basel_counter',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/animated-counter.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Label', 'basel' ),
						'param_name' => 'label'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Actual value', 'basel' ),
						'param_name' => 'value',
						'description' => esc_html__('Our final point. For ex.: 95', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Size', 'basel' ),
						'param_name' => 'size',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => '',
							esc_html__( 'Small', 'basel' ) => 'small',
							esc_html__( 'Large', 'basel' ) => 'large',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map Team Member Shortcode
		 * ------------------------------------------------------------------------------------------------
		 */

		if ( shortcode_exists( 'team_member' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Team Member', 'basel' ),
				'base' => 'team_member',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Display information about some person', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/team-member.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Name', 'basel' ),
						'param_name' => 'name',
						'value' => '',
						'description' => esc_html__( 'User name', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'position',
						'value' => '',
						'description' => esc_html__( 'User title', 'basel' )
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'User Avatar', 'basel' ),
						'param_name' => 'img',
						'value' => '',
						'description' => esc_html__( 'Select image from media library.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Image size', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Align', 'basel' ),
						'param_name' => 'align',
						'value' => array(
							esc_html__( 'Left', 'basel' ) => 'left',
							esc_html__( 'Center', 'basel' ) => 'center',
							esc_html__( 'Right', 'basel' ) => 'right',
						),
					),
					basel_get_color_scheme_param(),
					array(
						'type' => 'textarea_html',
						'heading' => esc_html__( 'Text', 'basel' ),
						'param_name' => 'content',
						'description' => esc_html__( 'You can add some member bio here.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Email', 'basel' ),
						'param_name' => 'email',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Facebook link', 'basel' ),
						'param_name' => 'facebook',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Twitter link', 'basel' ),
						'param_name' => 'twitter',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Linkedin link', 'basel' ),
						'param_name' => 'linkedin',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Skype link', 'basel' ),
						'param_name' => 'skype',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Instagram link', 'basel' ),
						'param_name' => 'instagram',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Social buttons size', 'basel' ),
						'param_name' => 'size',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => '',
							esc_html__( 'Small', 'basel' ) => 'small',
							esc_html__( 'Large', 'basel' ) => 'large',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Layout', 'basel' ),
						'param_name' => 'layout',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => 'default',
							esc_html__( 'With hover', 'basel' ) => 'hover',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Social buttons style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							esc_html__( 'Default', 'basel' ) => '',
							esc_html__( 'Circle buttons', 'basel' ) => 'circle',
							esc_html__( 'Colored', 'basel' ) => 'colored',
							esc_html__( 'Colored alternative', 'basel' ) => 'colored-alt',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map WC Products widget
		 * ------------------------------------------------------------------------------------------------
		 */

		if ( shortcode_exists( 'basel_shortcode_products_widget' ) ) {
			vc_map( array(
				'name' => esc_html__( 'WC products widget', 'basel' ),
				'base' => 'basel_shortcode_products_widget',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Categories mega menu widget', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/wc-product-widget.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title'
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Number of products to show', 'basel' ),
						'param_name' => 'number',
						'value' => array(
							1,
							2,
							3,
							4,
							5,
							6,
							7
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Show', 'basel' ),
						'param_name' => 'show',
						'value' => array(
							esc_html__( 'All Products', 'basel' ) => '',
							esc_html__( 'Featured Products', 'basel' ) => 'featured',
							esc_html__( 'On-sale Products', 'basel' ) => 'onsale',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Order by', 'basel' ),
						'param_name' => 'orderby',
						'value' => array(
							esc_html__( 'Date', 'basel' ) => 'date',
							esc_html__( 'Price', 'basel' ) => 'price',
							esc_html__( 'Random', 'basel' ) => 'rand',
							esc_html__( 'Sales', 'basel' ) => 'sales',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Order', 'basel' ),
						'param_name' => 'order',
						'value' => array(
							esc_html__( 'ASC', 'basel' ) => 'asc',
							esc_html__( 'DESC', 'basel' ) => 'desc',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Images size', 'basel' ),
						'param_name' => 'images_size',
						'description' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\' or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use \'thumbnail\' size.', 'basel' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide free products', 'basel' ),
						'param_name' => 'hide_free',
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Show hidden products', 'basel' ),
						'param_name' => 'show_hidden',
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map testimonial shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'testimonials' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Testimonials', 'basel' ),
				'base' => 'testimonials',
				'as_parent' => array('only' => 'testimonial'),
				'content_element' => true,
				'show_settings_on_create'=> false,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'User testimonials slider or grid', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/testimonials.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
						'value' => '',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Layout', 'basel' ),
						'param_name' => 'layout',
						'value' => array(
							esc_html__( 'Slider', 'basel' ) => 'slider',
							esc_html__( 'Grid', 'basel' ) => 'grid',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							esc_html__( 'Standard', 'basel' ) => 'standard',
							esc_html__( 'Boxed', 'basel' ) => 'boxed',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Align', 'basel' ),
						'param_name' => 'align',
						'value' => array(
							esc_html__( 'Center', 'basel' ) => 'center',
							esc_html__( 'Left', 'basel' ) => 'left',
							esc_html__( 'Right', 'basel' ) => 'right',
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Display stars rating', 'basel' ),
						'param_name' => 'stars_rating',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'std' => 'no',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Columns', 'basel' ),
						'param_name' => 'columns',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'dependency' => array(
							'element' => 'layout',
							'value' => array( 'grid' ),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slides per view', 'basel' ),
						'param_name' => 'slides_per_view',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'group' => 'Slider',
						'dependency' => array(
							'element' => 'layout',
							'value' => array( 'slider' ),
						),
						'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'basel' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider autoplay', 'basel' ),
						'param_name' => 'autoplay',
						'description' => esc_html__( 'Enables autoplay mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'group' => 'Slider',
						'dependency' => array(
							'element' => 'layout',
							'value' => array( 'slider' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Slider speed', 'basel' ),
						'param_name' => 'speed',
						'value' => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms)', 'basel' ),
						'group' => 'Slider',
						'dependency' => array(
							'element' => 'layout',
							'value' => array( 'slider' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide pagination control', 'basel' ),
						'param_name' => 'hide_pagination_control',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'group' => 'Slider',
						'dependency' => array(
							'element' => 'layout',
							'value' => array( 'slider' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
						'param_name' => 'hide_prev_next_buttons',
						'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'group' => 'Slider',
						'dependency' => array(
							'element' => 'layout',
							'value' => array( 'slider' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider loop', 'basel' ),
						'param_name' => 'wrap',
						'description' => esc_html__( 'Enables loop mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'group' => 'Slider',
						'dependency' => array(
							'element' => 'layout',
							'value' => array( 'slider' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
				"js_view" => 'VcColumnView'
			));

			vc_map( array(
				'name' => esc_html__( 'Testimonial', 'basel' ),
				'base' => 'testimonial',
				'class' => '',
				'as_child' => array('only' => 'testimonials'),
				'content_element' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'User testimonial', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/testimonials.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Name', 'basel' ),
						'param_name' => 'name',
						'value' => '',
						'description' => esc_html__( 'User name', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
						'value' => '',
						'description' => esc_html__( 'User title', 'basel' )
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'User Avatar', 'basel' ),
						'param_name' => 'image',
						'value' => '',
						'description' => esc_html__( 'Select image from media library.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Image size', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
					),
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'heading' => esc_html__( 'Text', 'basel' ),
						'param_name' => 'content'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map pricing tables shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'pricing_tables' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Pricing tables', 'basel' ),
				'base' => 'pricing_tables',
				"as_parent" => array('only' => 'pricing_plan'),
				"content_element" => true,
				"show_settings_on_create" => false,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Show your pricing plans', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/pricing-tables.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
				"js_view" => 'VcColumnView'
			));

			vc_map( array(
				'name' => esc_html__( 'Price plan', 'basel' ),
				'base' => 'pricing_plan',
				'class' => '',
				"as_child" => array('only' => 'pricing_tables'),
				"content_element" => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Price option', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/price-plan.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Pricing plan name', 'basel' ),
						'param_name' => 'name',
						'value' => '',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Subtitle', 'basel' ),
						'param_name' => 'subtitle',
						'value' => '',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Price value', 'basel' ),
						'param_name' => 'price_value',
						'value' => '',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Price suffix', 'basel' ),
						'param_name' => 'price_suffix',
						'value' => 'per month',
						'description' => esc_html__( 'For example: per month', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Price currency', 'basel' ),
						'param_name' => 'currency',
						'value' => '',
						'description' => esc_html__( 'For example: $', 'basel' )
					),
					array(
						'type' => 'textarea',
						'heading' => esc_html__( 'Featured list', 'basel' ),
						'param_name' => 'features_list',
						'description' => esc_html__( 'Start each feature text from a new line', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Button type', 'basel' ),
						'param_name' => 'button_type',
						'value' => array(
							esc_html__( 'Custom', 'basel' ) => 'custom',
							esc_html__( 'Product add to cart', 'basel' ) => 'product',
						),
						'description' => esc_html__( 'Set your custom link for button or allow users to add some product to cart', 'basel' )
					),
					array(
						'type' => 'href',
						'heading' => esc_html__( 'Button link', 'basel'),
						'param_name' => 'link',
						'description' => esc_html__( 'Enter URL if you want this box to have a link.', 'basel' ),
						'dependency' => array(
							'element' => 'button_type',
							'value' => array( 'custom' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Button label', 'basel' ),
						'param_name' => 'button_label',
						'value' => '',
						'dependency' => array(
							'element' => 'button_type',
							'value' => array( 'custom' ),
						),
					),
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Select identificator', 'basel' ),
						'param_name' => 'id',
						'description' => esc_html__( 'Input product ID or product SKU or product title to see suggestions', 'basel' ),
						'dependency' => array(
							'element' => 'button_type',
							'value' => array( 'product' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Label text', 'basel' ),
						'param_name' => 'label',
						'value' => '',
						'description' => esc_html__( 'For example: Best option!', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Label color', 'basel' ),
						'param_name' => 'label_color',
						'value' => array(
							'' => '',
							esc_html__( 'Red', 'basel' ) => 'red',
							esc_html__( 'Green', 'basel' ) => 'green',
							esc_html__( 'Blue', 'basel' ) => 'blue',
							esc_html__( 'Yellow', 'basel' ) => 'yellow',
						)
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			));
			// Necessary hooks for blog autocomplete fields
			add_filter( 'vc_autocomplete_pricing_plan_id_callback',	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_pricing_plan_id_render', 'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map instagram shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_instagram' ) ) {
			vc_map(array(
				'name' => esc_html__( 'Instagram', 'basel' ),
				'base' => 'basel_instagram',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Instagram photos', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/instagram.svg',
				'params' =>  basel_get_instagram_params()
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map Author Widget shortcode
		 * ------------------------------------------------------------------------------------------------
		 */

		if ( shortcode_exists( 'author_area' ) ) {
			vc_map(array(
				'name' => esc_html__( 'Author area', 'basel' ),
				'base' => 'author_area',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Widget for author information', 'basel' ),
				'icon' => BASEL_ASSETS . '/images/vc-icon/author-area.svg',
				'params' =>  basel_get_author_area_params()
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map promo banner shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'promo_banner' ) ) {
			vc_map(array(
				'name' => esc_html__( 'Promo Banner', 'basel' ),
				'base' => 'promo_banner',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Promo image with text and hover effect', 'basel' ),
				'icon' => BASEL_ASSETS . '/images/vc-icon/promo-banner.svg',
				'params' =>  basel_get_banner_params()
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map banners carousel shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'banners_carousel' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Banners carousel', 'basel' ),
				'base' => 'banners_carousel',
				'as_parent' => array('only' => 'promo_banner'),
				'content_element' => true,
				'show_settings_on_create' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Show your banners as a carousel', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/banners-carousel.svg',
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slides per view', 'basel' ),
						'param_name' => 'slides_per_view',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'basel' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider autoplay', 'basel' ),
						'param_name' => 'autoplay',
						'description' => esc_html__( 'Enables autoplay mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Slider speed', 'basel' ),
						'param_name' => 'speed',
						'value' => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms)', 'basel' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide pagination control', 'basel' ),
						'param_name' => 'hide_pagination_control',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
						'param_name' => 'hide_prev_next_buttons',
						'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider loop', 'basel' ),
						'param_name' => 'wrap',
						'description' => esc_html__( 'Enables loop mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
				"js_view" => 'VcColumnView'
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map 3D view slider
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_3d_view' ) ) {
			vc_map(array(
				'name' => esc_html__( '360 degree view', 'basel' ),
				'base' => 'basel_3d_view',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Showcase your product as 3D model', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/360-degree.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
					),
					array(
						'type' => 'attach_images',
						'heading' => esc_html__( 'Images', 'basel' ),
						'param_name' => 'images',
						'value' => '',
						'description' => esc_html__( 'Select images from media library.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map images gallery shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_gallery' ) ) {
			vc_map(array(
				'name' => esc_html__( 'Images gallery', 'basel' ),
				'base' => 'basel_gallery',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Images grid/carousel', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/images-gallery.svg',
				'params' => array(
					array(
						'type' => 'attach_images',
						'heading' => esc_html__( 'Images', 'basel' ),
						'param_name' => 'images',
						'value' => '',
						'description' => esc_html__( 'Select images from media library.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Image size', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'View', 'basel' ),
						'value' => 4,
						'param_name' => 'view',
						'save_always' => true,
						'value' => array(
							'Default grid' => 'grid',
							'Masonry grid' => 'masonry',
							'Carousel' => 'carousel',
							'Justified gallery' => 'justified',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Space between images', 'basel' ),
						'param_name' => 'spacing',
						'value' => array(
							0, 2, 6, 10, 20, 30
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slides per view', 'basel' ),
						'param_name' => 'slides_per_view',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'carousel' ),
						),
						'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'basel' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide pagination control', 'basel' ),
						'param_name' => 'hide_pagination_control',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
						'param_name' => 'hide_prev_next_buttons',
						'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider loop', 'basel' ),
						'param_name' => 'wrap',
						'description' => esc_html__( 'Enables loop mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Center mode', 'basel' ),
						'param_name' => 'center_mode',
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider autoplay', 'basel' ),
						'param_name' => 'autoplay',
						'description' => esc_html__( 'Enables autoplay mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Autoplay speed', 'basel' ),
						'param_name' => 'speed',
						'description' => esc_html__( 'Default speed 5000.', 'basel' ),
						'dependency' => array(
							'element' => 'autoplay',
							'value' => array( 'yes' ),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Columns', 'basel' ),
						'value' => 3,
						'param_name' => 'columns',
						'save_always' => true,
						'description' => esc_html__( 'How much columns grid', 'basel' ),
						'value' => array(
							'1' => 1,
							'2' => 2,
							'3' => 3,
							'4' => 4,
							'6' => 6,
						),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'grid', 'masonry' ),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'On click action', 'basel' ),
						'param_name' => 'on_click',
						'value' => array(
							'' => '',
							'Lightbox' => 'lightbox',
							'Custom link' => 'links',
							'None' => 'none'
						)
					),
					array(
						'type' => 'exploded_textarea_safe',
						'heading' => esc_html__( 'Custom links', 'basel' ),
						'param_name' => 'custom_links',
						'description' => esc_html__( 'Enter links for each slide (Note: divide links with linebreaks (Enter)).', 'basel' ),
						'dependency' => array(
							'element' => 'on_click',
							'value' => array( 'links' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Open in new tab', 'basel' ),
						'save_always' => true,
						'param_name' => 'target_blank',
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'default' => 'yes',
						'dependency' => array(
							'element' => 'on_click',
							'value' => array( 'links' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Show captions on in lightbox', 'basel' ),
						'save_always' => true,
						'param_name' => 'caption',
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'default' => 'yes'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map menu price element
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_menu_price' ) ) {
			vc_map(array(
				'name' => esc_html__( 'Menu price', 'basel' ),
				'base' => 'basel_menu_price',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Showcase your menu', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/menu-price.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Description', 'basel' ),
						'param_name' => 'description',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Price', 'basel' ),
						'param_name' => 'price',
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'Image', 'basel' ),
						'param_name' => 'img_id',
						'value' => '',
						'description' => esc_html__( 'Select images from media library.', 'basel' )
					),
					array(
						'type' => 'href',
						'heading' => esc_html__( 'Link', 'basel'),
						'param_name' => 'link',
						'description' => esc_html__( 'Enter URL if you want this box to have a link.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map countdown timer
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_countdown_timer' ) ) {
			vc_map(array(
				'name' => esc_html__( 'Countdown timer', 'basel' ),
				'base' => 'basel_countdown_timer',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Shows countdown timer', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/countdown-timer.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Date', 'basel' ),
						'param_name' => 'date',
						'description' => esc_html__( 'Final date in the format Y/m/d. For example 2020/12/12 13:00', 'basel' )
					),
					basel_get_color_scheme_param(),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Size', 'basel' ),
						'param_name' => 'size',
						'value' => array(
							'' => '',
							esc_html__( 'Small', 'basel' ) => 'small',
							esc_html__( 'Medium', 'basel' ) => 'medium',
							esc_html__( 'Large', 'basel' ) => 'large',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Align', 'basel' ),
						'param_name' => 'align',
						'value' => array(
							'' => '',
							esc_html__( 'left', 'basel' ) => 'left',
							esc_html__( 'center', 'basel' ) => 'center',
							esc_html__( 'right', 'basel' ) => 'right',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							'' => '',
							esc_html__( 'Standard', 'basel' ) => 'standard',
							esc_html__( 'Transparent', 'basel' ) => 'transparent',
						)
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Information box with image (icon)
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_info_box' ) ) {
			vc_map(array(
				'name' => esc_html__( 'Information box', 'basel' ),
				'base' => 'basel_info_box',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Show your brief information as a carousel', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/information-box.svg',
				'params' => array(
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'Image', 'basel' ),
						'param_name' => 'image',
						'value' => '',
						'description' => esc_html__( 'Select image from media library.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Image size', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
					),
					array(
						'type' => 'href',
						'heading' => esc_html__( 'Link', 'basel'),
						'param_name' => 'link',
						'description' => esc_html__( 'Enter URL if you want this box to have a link.', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Link target', 'basel' ),
						'param_name' => 'link_target',
						'value' => $target_arr
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Button text', 'basel' ),
						'param_name' => 'btn_text',
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Button style', 'basel' ),
						'param_name' => 'btn_position',
						'value' => array(
							esc_html__( 'Show on hover', 'basel' ) => 'hover',
							esc_html__( 'Static', 'basel' ) => 'static',
						)
					),
					// array(
					// 	'type' => 'dropdown',
					// 	'heading' => esc_html__( 'Button color', 'basel' ),
					// 	'param_name' => 'btn_color',
					// 	'value' => array(
					// 		esc_html__( 'Default', 'basel' ) => 'default',
					// 		esc_html__( 'Primary color', 'basel' ) => 'primary',
					// 		esc_html__( 'Alternative color', 'basel' ) => 'alt',
					// 		esc_html__( 'Black', 'basel' ) => 'black',
					// 		esc_html__( 'White', 'basel' ) => 'white',
					// 	),
					// ),
					// array(
					// 	'type' => 'dropdown',
					// 	'heading' => esc_html__( 'Button style', 'basel' ),
					// 	'param_name' => 'btn_style',
					// 	'value' => array(
					// 		esc_html__( 'Link button', 'basel' ) => 'link',
					// 		esc_html__( 'Default', 'basel' ) => 'default',
					// 		esc_html__( 'Bordered', 'basel' ) => 'bordered',
					// 	),
					// ),
					// array(
					// 	'type' => 'dropdown',
					// 	'heading' => esc_html__( 'Button size', 'basel' ),
					// 	'param_name' => 'btn_size',
					// 	'value' => array(
					// 		esc_html__( 'Default', 'basel' ) => 'default',
					// 		esc_html__( 'Extra Small', 'basel' ) => 'extra-small',
					// 		esc_html__( 'Small', 'basel' ) => 'small',
					// 		esc_html__( 'Large', 'basel' ) => 'large',
					// 		esc_html__( 'Extra Large', 'basel' ) => 'extra-large',
					// 	),
					// ),
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'heading' => esc_html__( 'Brief content', 'basel' ),
						'param_name' => 'content',
						'description' => esc_html__( 'Add here few words to your banner image.', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Text alignment', 'basel' ),
						'param_name' => 'alignment',
						'value' => array(
							esc_html__( 'Align left', 'basel' ) => '',
							esc_html__( 'Align right', 'basel' ) => 'right',
							esc_html__( 'Align center', 'basel' ) => 'center'
						),
						'description' => esc_html__( 'Select image alignment.', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Image alignment', 'basel' ),
						'param_name' => 'image_alignment',
						'value' => array(
							esc_html__( 'Top', 'basel' ) => 'top',
							esc_html__( 'Left', 'basel' ) => 'left',
							esc_html__( 'Right', 'basel' ) => 'right'
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Box style', 'basel' ),
						'param_name' => 'style',
						'value' => array(
							esc_html__( 'Base', 'basel' ) => 'base',
							esc_html__( 'Bordered', 'basel' ) => 'border',
							esc_html__( 'Shadow', 'basel' ) => 'shadow',
						)
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'New CSS structure', 'basel' ),
						'param_name' => 'new_styles',
						'description' => esc_html__( 'Use improved version with CSS flexbox that was added in 2.9 version.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Disable SVG animation', 'basel' ),
						'param_name' => 'no_svg_animation',
						'description' => esc_html__( 'By default, your SVG files will be animated. If you don\'t want you can disable the animation.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					basel_get_color_scheme_param(),
					array(
						'type' => 'css_editor',
						'heading' => esc_html__( 'CSS box', 'basel' ),
						'param_name' => 'css',
						'group' => esc_html__( 'Design Options', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			));
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map info box carousel shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_info_box_carousel' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Information box carousel', 'basel' ),
				'base' => 'basel_info_box_carousel',
				'as_parent' => array('only' => 'basel_info_box'),
				'content_element' => true,
				'show_settings_on_create' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Show your banners as a carousel', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/infobox-slider.svg',
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slides per view', 'basel' ),
						'param_name' => 'slides_per_view',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slider spacing', 'basel' ),
						'param_name' => 'slider_spacing',
						'value' => array(
							30,20,10,6,2,0
						),

						'description' => esc_html__( 'Set the interval numbers that you want to display between slider items.', 'basel' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider autoplay', 'basel' ),
						'param_name' => 'autoplay',
						'description' => esc_html__( 'Enables autoplay mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Slider speed', 'basel' ),
						'param_name' => 'speed',
						'value' => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms)', 'basel' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide pagination control', 'basel' ),
						'param_name' => 'hide_pagination_control',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
						'param_name' => 'hide_prev_next_buttons',
						'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider loop', 'basel' ),
						'param_name' => 'wrap',
						'description' => esc_html__( 'Enables loop mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					),
				),
				'js_view' => 'VcColumnView'
			));

			
			// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
			if( class_exists( 'WPBakeryShortCodesContainer' ) ){
				class WPBakeryShortCode_basel_info_box_carousel extends WPBakeryShortCodesContainer {}
			}

			// Replace Wbc_Inner_Item with your base name from mapping for nested element
			if( class_exists( 'WPBakeryShortCode' ) ){
				class WPBakeryShortCode_basel_info_box extends WPBakeryShortCode {}
			}		
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Add options to columns and text block
		 * ------------------------------------------------------------------------------------------------
		 */

		add_action( 'vc_after_init', 'basel_update_vc_column');

		if( ! function_exists( 'basel_update_vc_column' ) ) {
			function basel_update_vc_column() {
				if(!function_exists('vc_map')) return;
				vc_remove_param( 'vc_column', 'el_class' );

		        vc_add_param( 'vc_column', basel_get_color_scheme_param() );

		        vc_add_param( 'vc_column', array(
		            'type' => 'textfield',
		            'heading' => esc_html__( 'Extra class name', 'basel' ),
		            'param_name' => 'el_class',
		            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
		        ) );

				vc_remove_param( 'vc_column_text', 'el_class' );

		        vc_add_param( 'vc_column_text', basel_get_color_scheme_param() );

		        vc_add_param( 'vc_column_text', array(
		            'type' => 'textfield',
		            'heading' => esc_html__( 'Extra class name', 'basel' ),
		            'param_name' => 'el_class',
		            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
		        ) );
			}
		}


		/**
		 * ------------------------------------------------------------------------------------------------
		 * Add new element to VC: Categories [basel_categories]
		 * ------------------------------------------------------------------------------------------------
		 */


		$order_by_values = array(
			'',
			esc_html__( 'Date', 'basel' ) => 'date',
			esc_html__( 'ID', 'basel' ) => 'ID',
			esc_html__( 'Author', 'basel' ) => 'author',
			esc_html__( 'Title', 'basel' ) => 'title',
			esc_html__( 'Modified', 'basel' ) => 'modified',
			//esc_html__( 'Random', 'basel' ) => 'rand',
			esc_html__( 'Comment count', 'basel' ) => 'comment_count',
			esc_html__( 'Menu order', 'basel' ) => 'menu_order',
			esc_html__( 'As IDs or slugs provided order', 'basel' ) => 'include',
		);

		$order_way_values = array(
			'',
			esc_html__( 'Descending', 'basel' ) => 'DESC',
			esc_html__( 'Ascending', 'basel' ) => 'ASC',
		);

		if ( shortcode_exists( 'basel_categories' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Product categories', 'basel' ),
				'base' => 'basel_categories',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Product categories grid', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/product-categories.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Number', 'basel' ),
						'param_name' => 'number',
						'description' => esc_html__( 'The `number` field is used to display the number of categories.', 'basel' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Order by', 'basel' ),
						'param_name' => 'orderby',
						'value' => $order_by_values,
						'save_always' => true,
						'description' => sprintf( esc_html__( 'Select how to sort retrieved categories. More at %s.', 'basel' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Sort order', 'basel' ),
						'param_name' => 'order',
						'value' => $order_way_values,
						'save_always' => true,
						'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'basel' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Layout', 'basel' ),
						'value' => 4,
						'param_name' => 'style',
						'save_always' => true,
						'description' => esc_html__( 'Try out our creative styles for categories block', 'basel' ),
						'value' => array(
							'Default' => 'default',
							'Masonry' => 'masonry',
							'Masonry (with first wide)' => 'masonry-first',
							'Carousel' => 'carousel',
						)
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Categories design', 'basel' ),
						'description' => esc_html__( 'Overrides option from Theme Settings -> Shop', 'basel' ),
						'param_name' => 'categories_design',
						'value' => array_merge( array( 'Inherit' => '' ), array_flip( basel_get_config( 'categories-designs' ) ) ),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Space between categories', 'basel' ),
						'param_name' => 'spacing',
						'value' => array(
							30,20,10,6,2,0
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slides per view', 'basel' ),
						'param_name' => 'slides_per_view',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
						'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'basel' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide pagination control', 'basel' ),
						'param_name' => 'hide_pagination_control',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
						'param_name' => 'hide_prev_next_buttons',
						'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider loop', 'basel' ),
						'param_name' => 'wrap',
						'description' => esc_html__( 'Enables loop mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider autoplay', 'basel' ),
						'param_name' => 'autoplay',
						'description' => esc_html__( 'Enables autoplay mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'dependency' => array(
							'element' => 'view',
							'value' => array( 'carousel' ),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Columns', 'basel' ),
						'value' => 4,
						'param_name' => 'columns',
						'save_always' => true,
						'description' => esc_html__( 'How much columns grid', 'basel' ),
						'value' => array(
							'1' => 1,
							'2' => 2,
							'3' => 3,
							'4' => 4,
							'6' => 6,
						),
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'masonry', 'default', 'masonry-first' ),
						),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide empty', 'basel' ),
						'param_name' => 'hide_empty',
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'std'         => 'yes',
					),
					array(
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Categories', 'basel' ),
						'param_name' => 'ids',
						'settings' => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'description' => esc_html__( 'List of product categories', 'basel' ),
					)
				)
			) );
		}

		//Filters For autocomplete param:
		//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
		add_filter( 'vc_autocomplete_basel_categories_ids_callback', 'basel_productCategoryCategoryAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_basel_categories_ids_render', 'basel_productCategoryCategoryRenderByIdExact', 10, 1 );

		if( ! function_exists( 'basel_productCategoryCategoryAutocompleteSuggester' ) ) {
			function basel_productCategoryCategoryAutocompleteSuggester( $query, $slug = false ) {
				global $wpdb;
				$cat_id = (int) $query;
				$query = trim( $query );
				$post_meta_infos = $wpdb->get_results(
					$wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
								FROM {$wpdb->term_taxonomy} AS a
								INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
								WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )",
						$cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

				$result = array();
				if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
					foreach ( $post_meta_infos as $value ) {
						$data = array();
						$data['value'] = $slug ? $value['slug'] : $value['id'];
						$data['label'] = esc_html__( 'Id', 'basel' ) . ': ' .
						                 $value['id'] .
						                 ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . esc_html__( 'Name', 'basel' ) . ': ' .
						                                                      $value['name'] : '' ) .
						                 ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . esc_html__( 'Slug', 'basel' ) . ': ' .
						                                                      $value['slug'] : '' );
						$result[] = $data;
					}
				}

				return $result;
			}
		}
		if( ! function_exists( 'basel_productCategoryCategoryRenderByIdExact' ) ) {
			function basel_productCategoryCategoryRenderByIdExact( $query ) {
				global $wpdb;
				$query = $query['value'];
				$cat_id = (int) $query;
				$term = get_term( $cat_id, 'product_cat' );

				return basel_productCategoryTermOutput( $term );
			}
		}

		if( ! function_exists( 'basel_productCategoryTermOutput' ) ) {
			function basel_productCategoryTermOutput( $term ) {
				$term_slug = $term->slug;
				$term_title = $term->name;
				$term_id = $term->term_id;

				$term_slug_display = '';
				if ( ! empty( $term_sku ) ) {
					$term_slug_display = ' - ' . esc_html__( 'Sku', 'basel' ) . ': ' . $term_slug;
				}

				$term_title_display = '';
				if ( ! empty( $product_title ) ) {
					$term_title_display = ' - ' . esc_html__( 'Title', 'basel' ) . ': ' . $term_title;
				}

				$term_id_display = esc_html__( 'Id', 'basel' ) . ': ' . $term_id;

				$data = array();
				$data['value'] = $term_id;
				$data['label'] = $term_id_display . $term_title_display . $term_slug_display;

				return ! empty( $data ) ? $data : false;
			}
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Add new element to VC: Posts [basel_posts]
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_posts' ) ) {
			vc_map( array(
				'name' => esc_html__( 'Posts carousel', 'basel' ),
				'base' => 'basel_posts',
				'class' => '',
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Animated carousel with posts', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/posts-carousel.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Slider title', 'basel' ),
						'param_name' => 'title',
					),
					array(
						'type' => 'loop',
						'heading' => esc_html__( 'Carousel content', 'basel' ),
						'param_name' => 'posts_query',
						'settings' => array(
							'size' => array( 'hidden' => false, 'value' => 10 ),
							'post_type' => array( 'value' => 'post' ),
							'order_by' => array( 'value' => 'date' )
						),
						'description' => esc_html__( 'Create WordPress loop, to populate content from your site.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Images size', 'basel' ),
						'param_name' => 'img_size',
						'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Slider speed', 'basel' ),
						'param_name' => 'speed',
						'value' => '5000',
						'description' => esc_html__( 'Duration of animation between slides (in ms)', 'basel' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Slides per view', 'basel' ),
						'param_name' => 'slides_per_view',
						'value' => array(
							1,2,3,4,5,6,7,8
						),
						'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Also supports for "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode doesn\'t compatible with loop mode.', 'basel' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Scroll per page', 'basel' ),
						'param_name' => 'scroll_per_page',
						'description' => esc_html__( 'Scroll per page not per item. This affect next/prev buttons and mouse/touch dragging.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
						'std' => 'yes',
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider autoplay', 'basel' ),
						'param_name' => 'autoplay',
						'description' => esc_html__( 'Enables autoplay mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide pagination control', 'basel' ),
						'param_name' => 'hide_pagination_control',
						'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
						'param_name' => 'hide_prev_next_buttons',
						'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Slider loop', 'basel' ),
						'param_name' => 'wrap',
						'description' => esc_html__( 'Enables loop mode.', 'basel' ),
						'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Products hover (deprecated)', 'basel' ),
						'description' => esc_html__( 'If you use products carousel', 'basel' ),
						'param_name' => 'product_hover',
						'value' => array_merge( array( 'Inherit' => '' ), array_flip( basel_get_config( 'product-hovers' ) ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				)
			) );
		}
		/**
		 * ------------------------------------------------------------------------------------------------
		 * Add new element to VC: Products [basel_products]
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'basel_products' ) ) {
			vc_map( basel_get_products_shortcode_map_params() );
		}

		// Necessary hooks for blog autocomplete fields
		add_filter( 'vc_autocomplete_basel_products_include_callback',	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_basel_products_include_render',
			'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

		// Narrow data taxonomies
		add_filter( 'vc_autocomplete_basel_products_taxonomies_callback', 'basel_vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_basel_products_taxonomies_render', 'basel_vc_autocomplete_taxonomies_field_render', 10, 1 );

		// Narrow data taxonomies for exclude_filter
		add_filter( 'vc_autocomplete_basel_products_exclude_filter_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_basel_products_exclude_filter_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_basel_products_exclude_callback',	'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_basel_products_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
		
		
		if( ! function_exists( 'basel_vc_autocomplete_taxonomies_field_render' ) ) {
			function basel_vc_autocomplete_taxonomies_field_render( $term ) {
				$vc_taxonomies_types = vc_taxonomies_types();
		
				$brands_attribute = basel_get_opt( 'brands_attribute' );
		
				if( !empty( $brands_attribute ) && taxonomy_exists( $brands_attribute ) ) {
					$vc_taxonomies_types[ $brands_attribute ] = $brands_attribute;
				}
		
				$terms = get_terms( array_keys( $vc_taxonomies_types ), array(
					'include' => array( $term['value'] ),
					'hide_empty' => false,
				) );
				
				
				$data = false;
				if ( is_array( $terms ) && 1 === count( $terms ) ) {
					$term = $terms[0];
					$data = vc_get_term_object( $term );
				}
		
				return $data;
			}
		}
		
		// Add other product attributes
		if( ! function_exists( 'basel_vc_autocomplete_taxonomies_field_search' ) ) {
			function basel_vc_autocomplete_taxonomies_field_search( $search_string ) {
				$data = array();
				$vc_filter_by = vc_post_param( 'vc_filter_by', '' );
				$vc_taxonomies_types = strlen( $vc_filter_by ) > 0 ? array( $vc_filter_by ) : array_keys( vc_taxonomies_types() );
		
				$brands_attribute = basel_get_opt( 'brands_attribute' );
				
				if( !empty( $brands_attribute ) && taxonomy_exists( $brands_attribute ) ) {
					array_push($vc_taxonomies_types, $brands_attribute);
				}
		
				$vc_taxonomies = get_terms( $vc_taxonomies_types, array(
					'hide_empty' => false,
					'search' => $search_string,
				) );
				if ( is_array( $vc_taxonomies ) && ! empty( $vc_taxonomies ) ) {
					foreach ( $vc_taxonomies as $t ) {
						if ( is_object( $t ) ) {
							$data[] = vc_get_term_object( $t );
						}
					}
				}
		
				return $data;
			}
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Map products tabs shortcode
		 * ------------------------------------------------------------------------------------------------
		 */
		if ( shortcode_exists( 'products_tabs' ) ) {
			vc_map( array(
				'name' => esc_html__( 'AJAX Products tabs', 'basel' ),
				'base' => 'products_tabs',
				"as_parent" => array('only' => 'products_tab'),
				"content_element" => true,
				"show_settings_on_create" => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Product tabs for your marketplace', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/ajax-products-tabs.svg',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'basel' ),
						'param_name' => 'title',
					),
					array(
						'type' => 'attach_image',
						'heading' => esc_html__( 'Icon image', 'basel' ),
						'param_name' => 'image',
						'value' => '',
						'description' => esc_html__( 'Select image from media library.', 'basel' )
					),
					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Tabs color', 'basel' ),
						'param_name' => 'color'
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'basel' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
					)
				),
				"js_view" => 'VcColumnView'
			));

			$basel_prdoucts_params = vc_map_integrate_shortcode( basel_get_products_shortcode_map_params(), '', '', array(
				'exclude' => array(
				),
			));

			vc_map( array(
				'name' => esc_html__( 'Products tab', 'basel' ),
				'base' => 'products_tab',
				'class' => '',
				'as_child' => array('only' => 'products_tabі'),
				'content_element' => true,
				'category' => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Products block', 'basel' ),
				'icon'            => BASEL_ASSETS . '/images/vc-icon/product-categories.svg',
				'params' => array_merge( array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title for the tab', 'basel' ),
						'param_name' => 'title',
						'value' => '',
					)
				), $basel_prdoucts_params )
			));

			// Necessary hooks for blog autocomplete fields
			add_filter( 'vc_autocomplete_products_tab_include_callback',	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_products_tab_include_render',
				'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

			// Narrow data taxonomies
			add_filter( 'vc_autocomplete_products_tab_taxonomies_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
			add_filter( 'vc_autocomplete_products_tab_taxonomies_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

			// Narrow data taxonomies for exclude_filter
			add_filter( 'vc_autocomplete_products_tab_exclude_filter_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
			add_filter( 'vc_autocomplete_products_tab_exclude_filter_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

			add_filter( 'vc_autocomplete_products_tab_exclude_callback',	'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_products_tab_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
		}

		/**
		 * ------------------------------------------------------------------------------------------------
		 * Update images carousel parameters
		 * ------------------------------------------------------------------------------------------------
		 */
		add_action( 'init', 'basel_update_vc_images_carousel');

		if( ! function_exists( 'basel_update_vc_images_carousel' ) ) {
			function basel_update_vc_images_carousel() {
				if(!function_exists('vc_map')) return;
				vc_remove_param( 'vc_images_carousel', 'mode' );
				vc_remove_param( 'vc_images_carousel', 'partial_view' );
				vc_remove_param( 'vc_images_carousel', 'el_class' );

		        vc_add_param( 'vc_images_carousel', array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Add spaces between images', 'basel' ),
					'param_name' => 'spaces',
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
				) );

		        vc_add_param( 'vc_images_carousel', array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Specific design', 'basel' ),
					'param_name' => 'design',
		            'description' => esc_html__( 'With this option your gallery will be styled in a different way, and sizes will be changed.', 'basel' ),
					'value' => array(
						'' => 'none',
						esc_html__( 'Iphone', 'basel' ) => 'iphone',
						esc_html__( 'MacBook', 'basel' ) => 'macbook',
					)
				) );

		        vc_add_param( 'vc_images_carousel', array(
		            'type' => 'textfield',
		            'heading' => esc_html__( 'Extra class name', 'basel' ),
		            'param_name' => 'el_class',
		            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
		        ) );
			}
		}

	}
}


if( ! function_exists( 'basel_get_products_shortcode_params' ) ) {
	function basel_get_products_shortcode_map_params() {
		return array(
			'name' => esc_html__( 'Products (grid or carousel)', 'basel' ),
			'base' => 'basel_products',
			'class' => '',
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'description' => esc_html__( 'Animated carousel with posts', 'basel' ),
        	'icon'            => BASEL_ASSETS . '/images/vc-icon/products-grid-or-carousel.svg',
			'params' => basel_get_products_shortcode_params()
		);
	}
}

if( ! function_exists( 'basel_get_products_shortcode_params' ) ) {
	function basel_get_products_shortcode_params() {
		return apply_filters( 'basel_get_products_shortcode_params', array(
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Products view', 'basel' ),
					'param_name' => 'layout',
					'value' =>  array(
						array( 'grid', esc_html__( 'Grid', 'basel' ) ),
						array( 'list', esc_html__( 'List', 'basel' ) ),
						array( 'carousel', esc_html__( 'Carousel', 'basel' ) ),

					),
					'description' => esc_html__( 'Show products in standard grid or via slider carousel', 'basel' )
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Data source', 'basel' ),
					'param_name' => 'post_type',
					'value' =>  array(
						array( 'product', esc_html__( 'All Products', 'basel' ) ),
						array( 'featured', esc_html__( 'Featured Products', 'basel' ) ),
						array( 'new', esc_html__( 'Products with NEW label', 'basel' ) ),
						array( 'sale', esc_html__( 'Sale Products', 'basel' ) ),
						array( 'bestselling', esc_html__( 'Bestsellers', 'basel' ) ),
						array( 'ids', esc_html__( 'List of IDs', 'basel' ) ),
						array( 'top_rated_products', esc_html__( 'Top Rated Products', 'basel' ) ),
					),
					'description' => esc_html__( 'Select content type for your grid.', 'basel' )
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Include only', 'basel' ),
					'param_name' => 'include',
					'description' => esc_html__( 'Add products by title.', 'basel' ),
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
						'groups' => true,
					),
					'dependency' => array(
						'element' => 'post_type',
						'value' => array( 'ids' ),
						//'callback' => 'vc_grid_include_dependency_callback',
					),
				),
				// Custom query tab
				array(
					'type' => 'textarea_safe',
					'heading' => esc_html__( 'Custom query', 'basel' ),
					'param_name' => 'custom_query',
					'description' => wp_kses( __( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts">WordPress Codex</a>.', 'basel' ), 'default' ),
					'dependency' => array(
						'element' => 'post_type',
						'value' => array( 'custom' ),
					),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Categories or tags', 'basel' ),
					'param_name' => 'taxonomies',
					'settings' => array(
						'multiple' => true,
						// is multiple values allowed? default false
						// 'sortable' => true, // is values are sortable? default false
						'min_length' => 1,
						// min length to start search -> default 2
						// 'no_hide' => true, // In UI after select doesn't hide an select list, default false
						'groups' => true,
						// In UI show results grouped by groups, default false
						'unique_values' => true,
						// In UI show results except selected. NB! You should manually check values in backend, default false
						'display_inline' => true,
						// In UI show results inline view, default false (each value in own line)
						'delay' => 500,
						// delay for search. default 500
						'auto_focus' => true,
						// auto focus input, default true
					),
					'param_holder_class' => 'vc_not-for-custom',
					'description' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'basel' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Items per page', 'basel' ),
					'param_name' => 'items_per_page',
					'description' => esc_html__( 'Number of items to show per page.', 'basel' ),
					'value' => '10',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination', 'basel' ),
					'param_name' => 'pagination',
					'value' => array(
	                    '' => '',
	                    esc_html__('"Load more" button', 'basel') => 'more-btn',
	                    esc_html__('Arrows', 'basel') => 'arrows',
	                    esc_html__('Infinit scrolling', 'basel') => 'infinit',  
					),
					'dependency' => array(
						'element' => 'layout',
						'value_not_equal_to' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Masonry grid', 'basel'), 
					'param_name' => 'products_masonry',
					'description' => esc_html__('Products may have different sizes', 'basel'),
					'value' => array(
	                    '' => '',
	                    'Enable' => 'enable',
	                    'Disable' => 'disable',
					),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid' ),
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Products grid with different sizes', 'basel'), 
					'param_name' => 'products_different_sizes',
					'value' => array(
	                    '' => '',
	                    'Enable' => 'enable',
	                    'Disable' => 'disable',
					),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid' ),
					),
				),
				// Design settings
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Products hover', 'basel' ),
					'param_name' => 'product_hover',
					'value' => array_merge( array( 'Inherit' => '' ), array_flip( basel_get_config( 'product-hovers' ) ) ),
					'group' => esc_html__( 'Design', 'basel' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Columns', 'basel' ),
					'param_name' => 'columns',
					'value' => array(
						2, 3, 4, 6
					),
					'description' => esc_html__( 'Columns', 'basel' ),
					'group' => esc_html__( 'Design', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'grid' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Images size', 'basel' ),
					'group' => esc_html__( 'Design', 'basel' ),
					'param_name' => 'img_size',
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Sale countdown', 'basel' ),
					'description' => esc_html__( 'Countdown to the end sale date will be shown. Be sure you have set final date of the product sale price.', 'basel' ),
					'param_name' => 'sale_countdown',
					'value' => 1,
					'group' => esc_html__( 'Design', 'basel' ),
				),
				// Carousel settings
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Slider speed', 'basel' ),
					'param_name' => 'speed',
					'value' => '5000',
					'description' => esc_html__( 'Duration of animation between slides (in ms)', 'basel' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Slides per view', 'basel' ),
					'param_name' => 'slides_per_view',
					'value' => array(
						1,2,3,4,5,6,7,8
					),
					'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode. Also supports for "auto" value, in this case it will fit slides depending on container\'s width. "auto" mode doesn\'t compatible with loop mode.', 'basel' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Scroll per page', 'basel' ),
					'param_name' => 'scroll_per_page',
					'description' => esc_html__( 'Scroll per page not per item. This affect next/prev buttons and mouse/touch dragging.', 'basel' ),
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'std' => 'yes',
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Slider autoplay', 'basel' ),
					'param_name' => 'autoplay',
					'description' => esc_html__( 'Enables autoplay mode.', 'basel' ),
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Hide pagination control', 'basel' ),
					'param_name' => 'hide_pagination_control',
					'description' => esc_html__( 'If "YES" pagination control will be removed', 'basel' ),
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Hide prev/next buttons', 'basel' ),
					'param_name' => 'hide_prev_next_buttons',
					'description' => esc_html__( 'If "YES" prev/next control will be removed', 'basel' ),
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Slider loop', 'basel' ),
					'param_name' => 'wrap',
					'description' => esc_html__( 'Enables loop mode.', 'basel' ),
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Center mode', 'basel' ),
					'param_name' => 'center_mode',
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
					'group' => esc_html__( 'Carousel Settings', 'basel' ),
					'dependency' => array(
						'element' => 'layout',
						'value' => array( 'carousel' ),
					),
				),
				// Data settings
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'basel' ),
					'param_name' => 'orderby',
					'value' => array(
						esc_html__( 'Date', 'basel' ) => 'date',
						esc_html__( 'Order by post ID', 'basel' ) => 'ID',
						esc_html__( 'Author', 'basel' ) => 'author',
						esc_html__( 'Title', 'basel' ) => 'title',
						esc_html__( 'Last modified date', 'basel' ) => 'modified',
						esc_html__( 'Number of comments', 'basel' ) => 'comment_count',
						esc_html__( 'Menu order/Page Order', 'basel' ) => 'menu_order',
						esc_html__( 'Meta value', 'basel' ) => 'meta_value',
						esc_html__( 'Meta value number', 'basel' ) => 'meta_value_num',
						esc_html__( 'Matches same order you passed in via the include parameter.', 'basel') => 'post__in',
						esc_html__( 'Random order', 'basel' ) => 'rand',
						esc_html__( 'Price', 'basel' ) => 'price',
					),
					'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'basel' ),
					'group' => esc_html__( 'Data Settings', 'basel' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'custom' ),
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Query type', 'basel' ),
					'param_name' => 'query_type',
					'group' => esc_html__( 'Data Settings', 'basel' ),
					'value' => array(
						esc_html__( 'OR', 'basel' ) => 'OR',
						esc_html__( 'AND', 'basel' ) => 'AND'
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Sorting', 'basel' ),
					'param_name' => 'order',
					'group' => esc_html__( 'Data Settings', 'basel' ),
					'value' => array(
						esc_html__( 'Descending', 'basel' ) => 'DESC',
						esc_html__( 'Ascending', 'basel' ) => 'ASC',
					),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'description' => esc_html__( 'Select sorting order.', 'basel' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Meta key', 'basel' ),
					'param_name' => 'meta_key',
					'description' => esc_html__( 'Input meta key for grid ordering.', 'basel' ),
					'group' => esc_html__( 'Data Settings', 'basel' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'orderby',
						'value' => array( 'meta_value', 'meta_value_num' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Offset', 'basel' ),
					'param_name' => 'offset',
					'description' => esc_html__( 'Number of grid elements to displace or pass over.', 'basel' ),
					'group' => esc_html__( 'Data Settings', 'basel' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
					),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Exclude', 'basel' ),
					'param_name' => 'exclude',
					'description' => esc_html__( 'Exclude posts, pages, etc. by title.', 'basel' ),
					'group' => esc_html__( 'Data Settings', 'basel' ),
					'settings' => array(
						'multiple' => true,
					),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
						'callback' => 'vc_grid_exclude_dependency_callback',
					),
				)
			)
		);
	}
}
if( ! function_exists( 'basel_get_basel_button_shortcode_args' ) ) {
	function basel_get_basel_button_shortcode_args() {
		return array(
			'name' => esc_html__( 'Button', 'basel' ),
			'base' => 'basel_button',
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'description' => esc_html__( 'Simple button in different theme styles', 'basel' ),
        	'icon' => BASEL_ASSETS . '/images/vc-icon/button.svg',
			'params' => basel_get_button_shortcode_params()
		);
	}
}

if( ! function_exists( 'basel_get_button_shortcode_params' ) ) {
	function basel_get_button_shortcode_params() {
		return apply_filters( 'basel_get_button_shortcode_params', array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'basel' ),
					'param_name' => 'title'
				),
				array(
					'type' => 'vc_link',
					'heading' => esc_html__( 'Link', 'basel'),
					'param_name' => 'link2',
					'description' => esc_html__( 'Enter URL if you want this box to have a link.', 'basel' )
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Button color', 'basel' ),
					'param_name' => 'color',
					'value' => array(
						esc_html__( 'Default', 'basel' ) => 'default',
						esc_html__( 'Primary color', 'basel' ) => 'primary',
						esc_html__( 'Alternative color', 'basel' ) => 'alt',
						esc_html__( 'Black', 'basel' ) => 'black',
						esc_html__( 'White', 'basel' ) => 'white',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Button style', 'basel' ),
					'param_name' => 'style',
					'value' => array(
						esc_html__( 'Default', 'basel' ) => 'default',
						esc_html__( 'Bordered', 'basel' ) => 'bordered',
						esc_html__( 'Link button', 'basel' ) => 'link',
						esc_html__( 'Rounded', 'basel' ) => 'round',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Button size', 'basel' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Default', 'basel' ) => 'default',
						esc_html__( 'Extra Small', 'basel' ) => 'extra-small',
						esc_html__( 'Small', 'basel' ) => 'small',
						esc_html__( 'Large', 'basel' ) => 'large',
						esc_html__( 'Extra Large', 'basel' ) => 'extra-large',
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Button inline', 'basel' ),
					'param_name' => 'button_inline',
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Align', 'basel' ),
					'param_name' => 'align',
					'value' => array(
						'' => '',
						esc_html__( 'left', 'basel' ) => 'left',
						esc_html__( 'center', 'basel' ) => 'center',
						esc_html__( 'right', 'basel' ) => 'right',
					),
					'dependency' => array(
						'element' => 'button_inline',
						'value_not_equal_to' => array( 'yes' ),
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'basel' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
				)
			)
		);
	}
}

if( ! function_exists( 'basel_get_color_scheme_param' ) ) {
	function basel_get_color_scheme_param() {
		return apply_filters( 'basel_get_color_scheme_param', array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Color Scheme', 'basel' ),
			'param_name' => 'basel_color_scheme',
			'value' => array(
				esc_html__( 'choose', 'basel' ) => '',
				esc_html__( 'Light', 'basel' ) => 'light',
				esc_html__( 'Dark', 'basel' ) => 'dark',
			),
		) );
	}
}


if( ! function_exists( 'basel_get_user_panel_params' ) ) {
	function basel_get_user_panel_params() {
		return apply_filters( 'basel_get_user_panel_params', array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'basel' ),
				'param_name' => 'title',
			)
		));
	}
}

if( ! function_exists( 'basel_get_author_area_params' ) ) {
	function basel_get_author_area_params() {
		return apply_filters( 'basel_get_author_area_params', array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'basel' ),
				'param_name' => 'title',
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__( 'Image', 'basel' ),
				'param_name' => 'image',
				'value' => '',
				'description' => esc_html__( 'Select image from media library.', 'basel' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image size', 'basel' ),
				'param_name' => 'img_size',
				'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
			),
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => esc_html__( 'Author bio', 'basel' ),
				'param_name' => 'content',
				'description' => esc_html__( 'Add here few words to your author info.', 'basel' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Text alignment', 'basel' ),
				'param_name' => 'alignment',
				'value' => array(
					esc_html__( 'Align left', 'basel' ) => '',
					esc_html__( 'Align right', 'basel' ) => 'right',
					esc_html__( 'Align center', 'basel' ) => 'center'
				),
				'description' => esc_html__( 'Select image alignment.', 'basel' )
			),
			array(
				'type' => 'href',
				'heading' => esc_html__( 'Author link', 'basel'),
				'param_name' => 'link',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Link text', 'basel'),
				'param_name' => 'link_text',
			),
			basel_get_color_scheme_param(),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'basel' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
			)
		));
	}
}


if( ! function_exists( 'basel_get_banner_params' ) ) {
	function basel_get_banner_params() {
		return apply_filters( 'basel_get_banner_params', array(
			array(
				'type' => 'attach_image',
				'heading' => esc_html__( 'Image', 'basel' ),
				'param_name' => 'image',
				'value' => '',
				'description' => esc_html__( 'Select image from media library.', 'basel' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image size', 'basel' ),
				'param_name' => 'img_size',
				'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'basel' )
			),
			array(
				'type' => 'href',
				'heading' => esc_html__( 'Banner link', 'basel'),
				'param_name' => 'link',
				'description' => esc_html__( 'Enter URL if you want this banner to have a link.', 'basel' )
			),
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => esc_html__( 'Banner content', 'basel' ),
				'param_name' => 'content',
				'description' => esc_html__( 'Add here few words to your banner image.', 'basel' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Text alignment', 'basel' ),
				'param_name' => 'alignment',
				'value' => array(
					esc_html__( 'Align left', 'basel' ) => '',
					esc_html__( 'Align right', 'basel' ) => 'right',
					esc_html__( 'Align center', 'basel' ) => 'center'
				),
				'description' => esc_html__( 'Select image alignment.', 'basel' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Content vertical alignment', 'basel' ),
				'param_name' => 'vertical_alignment',
				'value' => array(
					esc_html__( 'Top', 'basel' ) => '',
					esc_html__( 'Middle', 'basel' ) => 'middle',
					esc_html__( 'Bottom', 'basel' ) => 'bottom'
				)
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Hover effect', 'basel' ),
				'param_name' => 'hover',
				'value' => array(
					esc_html__( 'Default', 'basel' ) => '',
					esc_html__( 'Zoom image', 'basel' ) => '1',
					esc_html__( 'Bordered', 'basel' ) => '2',
					esc_html__( 'Content animation', 'basel' ) => '3',
					esc_html__( 'Translate and scale', 'basel' ) => '4',
				),
				'description' => esc_html__( 'Set beautiful hover effects for your banner.', 'basel' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Content style', 'basel' ),
				'param_name' => 'style',
				'value' => array(
					esc_html__( 'Default', 'basel' ) => '',
					esc_html__( 'Color mask', 'basel' ) => '2',
					esc_html__( 'Mask with border', 'basel' ) => '3',
					esc_html__( 'Content with line background', 'basel' ) => '1',
					esc_html__( 'Content with rectangular background', 'basel' ) => '5',
					//esc_html__( 'Style 4', 'basel' ) => '4',
					//esc_html__( 'Style 5', 'basel' ) => '5',
				),
				'description' => esc_html__( 'You can use some of our predefined styles for your banner content.', 'basel' )
			),
			basel_get_color_scheme_param(),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Increase spaces', 'basel' ),
				'param_name' => 'increase_spaces',
				'description' => esc_html__( 'Suggest to use this option if you have large banners. Padding will be set in percentage to your screen width.', 'basel' ),
				'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'basel' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
			)
		));
	}
}

if( ! function_exists( 'basel_get_instagram_params' ) ) {
	function basel_get_instagram_params() {
		return apply_filters( 'basel_get_instagram_params', array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'basel' ),
				'param_name' => 'title',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Username', 'basel' ),
				'param_name' => 'username',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Number of photos', 'basel' ),
				'param_name' => 'number',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Photo size', 'basel' ),
				'param_name' => 'size',
				'value' => array(
					esc_html__( 'Thumbnail', 'basel' ) => 'thumbnail',
					esc_html__( 'Large', 'basel' ) => 'large',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Open link in', 'basel' ),
				'param_name' => 'target',
				'value' => array(
					esc_html__( 'Current window (_self)', 'basel' ) => '_self',
					esc_html__( 'New window (_blank)', 'basel' ) => '_blank',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Link text', 'basel' ),
				'param_name' => 'link',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Design', 'basel' ),
				'param_name' => 'design',
				'skip_in' => 'widget',
				'value' => array(
					esc_html__( 'Default', 'basel' ) => '',
					esc_html__( 'Grid', 'basel' ) => 'grid',
					esc_html__( 'Slider', 'basel' ) => 'slider',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Photos per row', 'basel' ),
				'param_name' => 'per_row',
				'skip_in' => 'widget',
				'description' => esc_html__('Number of photos per row for grid design or items in slider per view.', 'basel' ),
				'value' => array(
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10,
					11,
					12
				)
			),
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => esc_html__( 'Instagram text', 'basel' ),
				'param_name' => 'content',
				'skip_in' => 'widget',
				'description' => esc_html__( 'Add here few words about your instagram profile.', 'basel' )
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Add spaces between photos', 'basel' ),
				'skip_in' => 'widget',
				'param_name' => 'spacing',
				'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Rounded corners for images', 'basel' ),
				'skip_in' => 'widget',
				'param_name' => 'rounded',
				'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 )
			),
		));
	}
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_testimonials extends WPBakeryShortCodesContainer {

    }
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_testimonial extends WPBakeryShortCode {

    }
}

if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_banners_carousel extends WPBakeryShortCodesContainer {

    }
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_pricing_tables extends WPBakeryShortCodesContainer {

    }
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_pricing_plan extends WPBakeryShortCode {

    }
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_products_tabs extends WPBakeryShortCodesContainer {

    }
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_products_tab extends WPBakeryShortCode {

    }
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_basel_carousel extends WPBakeryShortCodesContainer {}
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_basel_carousel_item extends WPBakeryShortCode {}
}


// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_basel_google_map extends WPBakeryShortCodesContainer {

    }
}

/**
* Add gradient to VC 
*/
if( ! function_exists( 'basel_add_gradient_type' ) && apply_filters( 'basel_gradients_enabled', true ) ) {
	function basel_add_gradient_type( $settings, $value ) {
		return basel_get_gradient_field( $settings['param_name'], $value, true );
	}
}

/**
* Parallax scroll
*/

if( ! function_exists( 'basel_parallax_scroll_map' ) ) { 
    function basel_parallax_scroll_map(){
        return array(
        	array(
        		'type' => 'checkbox',
        		'heading' => esc_html__( 'Enable parallax on mouse scroll', 'basel' ),
        		'param_name' => 'parallax_scroll',
        		'group' => esc_html__( 'Basel Extras', 'basel' ),
        		'value' => array( esc_html__( 'Yes, please', 'basel' ) => 'yes' )
        	),
        	array(
        		'group'            => esc_html__( 'Basel Extras', 'basel' ),
        		'heading'          => esc_html__( 'X axis translation', 'basel' ),
                'description'      => esc_html__( 'Recommended -200 to 200', 'basel' ),
        		'type'             => 'textfield',
        		'param_name'       => 'scroll_x',
                'edit_field_class' => 'vc_col-sm-4 vc_column',
        		'value'            => 0,
        		'dependency'       => array(
        			'element' => 'parallax_scroll',
                    'value' => array( 'yes' ),
        		),
        	),
        	array(
        		'group'            => esc_html__( 'Basel Extras', 'basel' ),
        		'heading'          => esc_html__( 'Y axis translation', 'basel' ),
                'description'      => esc_html__( 'Recommended -200 to 200', 'basel' ),
        		'type'             => 'textfield',
        		'param_name'       => 'scroll_y',
                'edit_field_class' => 'vc_col-sm-4 vc_column',
        		'value'            => -80,
        		'dependency'       => array(
        			'element' => 'parallax_scroll',
        			'value' => array( 'yes' ),
        		),
        	),
        	array(
        		'group'            => esc_html__( 'Basel Extras', 'basel' ),
        		'heading'          => esc_html__( 'Z axis translation', 'basel' ),
                'description'      => esc_html__( 'Recommended -200 to 200', 'basel' ),
        		'type'             => 'textfield',
        		'param_name'       => 'scroll_z',
                'edit_field_class' => 'vc_col-sm-4 vc_column',
        		'value'            => 0,
        		'dependency'       => array(
        			'element' => 'parallax_scroll',
        			'value' => array( 'yes' ),
        		),
        	),
        	array(
        		'group'       => esc_html__( 'Basel Extras', 'basel' ),
        		'heading'     => esc_html__( 'Parallax speed', 'basel' ),
        		'description' => esc_html__( 'Define the parallax speed on mouse scroll. By default - 30', 'basel' ),
        		'type'        => 'dropdown',
        		'param_name'  => 'scroll_smooth',
        		'value'       => array( '', 10, 20, 30, 40, 50, 60, 70, 80, 90, 100 ),
        		'dependency'  => array(
        			'element' => 'parallax_scroll',
        			'value' => array( 'yes' ),
        		),
        	),
        );
    }
}

if( ! function_exists( 'basel_add_parallax_scroll_field' ) ) { 
	function basel_add_parallax_scroll_field() {

	    $vc_image_new_params = basel_parallax_scroll_map();
	     
	    vc_add_params( 'vc_single_image', $vc_image_new_params ); 
        
        vc_add_params( 'vc_column', $vc_image_new_params ); 
        
	}      
	add_action( 'vc_after_init', 'basel_add_parallax_scroll_field', 200 ); 
}

if( ! function_exists( 'basel_parallax_scroll_data' ) ) { 
    function basel_parallax_scroll_data( $x, $y, $z, $smooth ) {
    	$data = array();
        
    	if ( $x ) $data[] = '"x":' . $x;
        if ( $y ) $data[] = '"y":' . $y;
        if ( $z ) $data[] = '"z":' . $z;
        if ( $smooth ) $data[] = '"smoothness":' . $smooth;

    	return 'data-parallax={' . implode( ',', $data ) . '} ';
    }
}

if( ! function_exists( 'basel_parallax_scroll' ) ) {
	function basel_parallax_scroll( $output, $obj, $attr ) {
		if ( ! empty( $attr['parallax_scroll'] ) ) {
            $x = $attr['scroll_x'] ? $attr['scroll_x'] : 0;
            $y = $attr['scroll_y'] ? $attr['scroll_y'] : -80;
            $z = $attr['scroll_z'] ? $attr['scroll_z'] : 0;
            $smooth = isset( $attr['scroll_smooth'] ) && $attr['scroll_smooth'] ? $attr['scroll_smooth'] : 30;
             
            $parallax = basel_parallax_scroll_data( $x, $y, $z, $smooth );
            
            if ( strpos( $output, 'wpb_single_image' ) !== false ) {
                $element = 'wpb_single_image';
            } else if ( strpos( $output, 'wpb_column' ) !== false ) {
                $element = 'wpb_column';
            }
            
            $output = preg_replace( '/<div(.*?)class="' . $element . '/is', '<div$1' . $parallax . 'class="' . $element, $output, 1 );
		}
		return $output;
	}
}

add_filter( 'vc_shortcode_output', 'basel_parallax_scroll', 10, 3 );

//Fixed problem with custom class and parallax scroll for wpb column
if ( ! function_exists( 'basel_wpb_class_sorting' ) ) {
	function basel_wpb_class_sorting( $css_classes ) {
		$css_classes = array_filter( explode( ' ', $css_classes ) );
		if ( in_array( 'wpb_column', $css_classes ) ) array_unshift( $css_classes, 'wpb_column' );
		$css_classes = implode( ' ', array_unique( $css_classes ) );
		return $css_classes;
	}
}

add_filter( 'vc_shortcodes_css_class', 'basel_wpb_class_sorting' );
