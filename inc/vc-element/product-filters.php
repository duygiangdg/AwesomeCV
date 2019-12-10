<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
* ------------------------------------------------------------------------------------------------
* Product filters
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'basel_vc_map_product_filters' ) ) {
	function basel_vc_map_product_filters() {
		if ( ! shortcode_exists( 'basel_product_filters' ) ) {
			return;
		}

		$attribute_array = array( '' => '' );

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
		}

        //Product filter parent element
		vc_map( array(
			'name' => esc_html__( 'Product filters', 'basel' ),
			'base' => 'basel_product_filters',
			'class' => '',
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'description' => esc_html__( 'Add filters by category, attribute or price', 'basel' ),
            'icon' => BASEL_ASSETS . '/images/vc-icon/product-filter.svg',
            'as_parent' => array( 'only' => 'basel_filter_categories, basel_filters_attribute, basel_filters_price_slider' ),
			'content_element' => true,
			'show_settings_on_create' => true,
			'params' => array(
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
            ),
			'js_view' => 'VcColumnView'
        ) );

        //Product filter categories
        vc_map( array(
			'name' => esc_html__( 'Filter categories', 'basel'),
			'base' => 'basel_filter_categories',
			'as_child' => array( 'only' => 'basel_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'icon' => BASEL_ASSETS . '/images/vc-icon/product-filter-categories.svg',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'basel' ),
					'param_name' => 'title',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'basel' ),
					'param_name' => 'order_by',
					'value' => array(
						esc_html__( 'Name', 'basel' ) => 'name',
						esc_html__( 'ID', 'basel' ) => 'ID',
						esc_html__( 'Slug', 'basel' ) => 'slug',
						esc_html__( 'Count', 'basel' ) => 'count',
					)
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show hierarchy', 'basel' ),
					'param_name' => 'hierarchical',
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 ),
					'std' => 1
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Hide empty categories', 'basel' ),
					'param_name' => 'hide_empty',
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 ),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show current category ancestors', 'basel' ),
					'param_name' => 'show_categories_ancestors',
					'value' => array( esc_html__( 'Yes, please', 'basel' ) => 1 ),
					'std' => 0,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'basel' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
				)
			),
        ) );
        
        //Product filter attribute
        vc_map( array(
			'name' => esc_html__( 'Filter attribute', 'basel'),
			'base' => 'basel_filters_attribute',
			'as_child' => array( 'only' => 'basel_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'icon' => BASEL_ASSETS . '/images/vc-icon/product-filter-atribute.svg',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'basel' ),
					'param_name' => 'title',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Attribute', 'basel' ),
					'param_name' => 'attribute',
					'value' => $attribute_array
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Query type', 'basel' ),
					'param_name' => 'query_type',
					'value' => array(
						esc_html__( 'AND', 'basel' ) => 'and',
						esc_html__( 'OR', 'basel' ) => 'or',
					)
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Categories', 'basel' ),
					'param_name' => 'categories',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'description' => esc_html__( 'List of product categories', 'basel' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Swatches size', 'basel' ),
					'param_name' => 'size',
					'value' => array(
						esc_html__( 'Normal', 'basel' ) => 'normal',
						esc_html__( 'Small', 'basel' ) => 'small',
						esc_html__( 'Large', 'basel' ) => 'large',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'basel' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
				)
			),
        ) );
        
        //Product filter price
        vc_map( array(
			'name' => esc_html__( 'Filter price', 'basel'),
			'base' => 'basel_filters_price_slider',
			'as_child' => array( 'only' => 'basel_product_filters' ),
			'content_element' => true,
			'category' => esc_html__( 'Theme elements', 'basel' ),
			'icon' => BASEL_ASSETS . '/images/vc-icon/product-filter-price.svg',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'basel' ),
					'param_name' => 'title',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'basel' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'basel' )
				)
			),
		) );
        
        // A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
		if ( class_exists( 'WPBakeryShortCodesContainer' ) ){
			class WPBakeryShortCode_basel_product_filters extends WPBakeryShortCodesContainer {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_basel_filter_categories extends WPBakeryShortCode {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_basel_filters_attribute extends WPBakeryShortCode {}
		}

		// Replace Wbc_Inner_Item with your base name from mapping for nested element
		if ( class_exists( 'WPBakeryShortCode' ) ){
			class WPBakeryShortCode_basel_filters_price_slider extends WPBakeryShortCode {}
		}

		add_filter( 'vc_autocomplete_basel_filters_attribute_categories_callback', 'basel_productCategoryCategoryAutocompleteSuggester', 10, 1 ); 
		
		add_filter( 'vc_autocomplete_basel_filters_attribute_categories_render', 'basel_productCategoryCategoryRenderByIdExact', 10, 1 ); 

    }

	add_action( 'vc_before_init', 'basel_vc_map_product_filters' );
}
