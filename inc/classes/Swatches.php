<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
 * Color and Images swatches for WooCommerce products attributes
 */


class BASEL_Swatches {

	public function __construct() {

		// Init swatches after CMB plugin loaded
		// on init hook after 199

		add_action( 'cmb2_init', array( $this, 'add_new_fields' ), 299 );

	}

	public function add_new_fields() {
		if( ! function_exists('wc_get_attribute_taxonomies') ) return;
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		foreach ($attribute_taxonomies as $key => $value) {

			$fields = array(
				array(
                    'name' => 'Enable swatch',
                    'desc' => 'Attribute dropdown will be replaces with squared buttons',
                    'id' => 'not_dropdown',
                    'type' => 'checkbox'
            	),
				array(
                    'name' => 'Image preview for this value',
                    'desc' => 'Upload an image',
                    'id' => 'image',
		            'type' => 'file',
		            'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
            	),
				array(
                    'name' => 'Color preview for this value',
                    'desc' => 'Select color',
                    'id' => 'color',
                    'type' => 'colorpicker'
            	),
			);

			$cmb_term = cmb2_get_metabox( array(
				'id'               => 'pa_fields_' . $value->attribute_name,
				'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
				'taxonomies'       => array( 'pa_' . $value->attribute_name ), // Tells CMB2 which taxonomies should have these fields
				// 'new_term_section' => true, // Will display in the "Add New Category" section
			), basel_get_current_term_id(), 'term' );

			foreach ($fields as $field) {
				$cmb_term->add_field($field);
			}
		}
	}
}

if( ! function_exists( 'basel_has_swatches' ) ) {
	function basel_has_swatches( $id, $attr_name, $options, $available_variations, $swatches_use_variation_images = false ) {
		$swatches = array();

		foreach ($options as $key => $value) {
			$swatch = basel_has_swatch($id, $attr_name, $value);

			if( ! empty( $swatch ) ) {

				if( $swatches_use_variation_images && basel_get_opt( 'grid_swatches_attribute' ) == $attr_name ) {

					$variation = basel_get_option_variations( $attr_name, $available_variations, $value );

					$swatch = array_merge( $swatch, $variation);
				}

				$swatches[$key] = $swatch;
			}
		}

		return $swatches;
	}
}

if( ! function_exists( 'basel_has_swatch' ) ) {
	function basel_has_swatch($id, $attr_name, $value) {
		$swatches = array();

		$color = $image = $not_dropdown = '';
		$term = get_term_by( 'slug', $value, $attr_name );
	
		if ( is_object( $term ) ) {
			$color = basel_tax_data( $attr_name, $term->term_id, 'color' );
			$image = basel_tax_data( $attr_name, $term->term_id, 'image' );
			$not_dropdown = basel_tax_data( $attr_name, $term->term_id, 'not_dropdown' );
		}

		if( $color != '' ) {
			$swatches['color'] = $color;
		}

		if( $image != '' ) {
			$swatches['image'] = $image;
		}

		if( $not_dropdown != '' ) {
			$swatches['not_dropdown'] = $not_dropdown;
		}

		return $swatches;
	}
}

if( ! function_exists( 'basel_get_option_variations' ) ) {
	function basel_get_option_variations( $attribute_name, $available_variations, $option = false, $product_id = false ) {
		$swatches_to_show = array();
		foreach ($available_variations as $key => $variation) {
			$option_variation = array();
			$attr_key = 'attribute_' . $attribute_name;
			if( ! isset( $variation['attributes'][$attr_key] )) return;

			$val = $variation['attributes'][$attr_key]; // red green black ..

			if( ! empty( $variation['image']['src'] ) ) {
				$option_variation = array(
					'variation_id' => $variation['variation_id'],
					'image_src' => $variation['image']['src'],
					'image_srcset' => $variation['image']['srcset'],
					'image_sizes' => $variation['image']['sizes'],
					'is_in_stock' => $variation['is_in_stock'],
				);
			}

			// Get only one variation by attribute option value 
			if( $option ) {
				if( $val != $option ) {
					continue;
				} else {
					return $option_variation;
				}
			} else {
				// Or get all variations with swatches to show by attribute name
				
				$swatch = basel_has_swatch($product_id, $attribute_name, $val);
				$swatches_to_show[$val] = array_merge( $swatch, $option_variation);

			}

		}

		return $swatches_to_show;

	}
}


if ( ! function_exists( 'basel_get_active_variations' ) ) {
	function basel_get_active_variations( $attribute_name, $available_variations ) {
		$results = array();

		if ( ! $available_variations ) {
			return $results;
		}

		foreach ( $available_variations as $variation ) {
			$attr_key = 'attribute_' . $attribute_name;
			if ( isset( $variation['attributes'][ $attr_key ] ) ) {
				$results[] = $variation['attributes'][ $attr_key ];
			}
		}

		return $results;
	}
}

if ( ! function_exists( 'basel_product_attributes_thumbnail' ) ) {
	function basel_product_attributes_thumbnail( $columns ){
		if( ! function_exists('wc_get_attribute_taxonomies') ) {
			return;
		}
		
		foreach ( wc_get_attribute_taxonomies() as $value ) {
			add_filter( 'manage_edit-pa_' . $value->attribute_name . '_columns', 'basel_product_attributes_add_thumbnail_column' );
			add_filter( 'manage_pa_' . $value->attribute_name . '_custom_column' , 'basel_product_attributes_thumbnail_column_content', 10, 3 );
		}
	}

	add_filter( 'admin_init' , 'basel_product_attributes_thumbnail' );
}

if ( ! function_exists( 'basel_product_attributes_add_thumbnail_column' ) ) {
	function basel_product_attributes_add_thumbnail_column( $columns ){
		return array(
			'cb' => '<input type="checkbox" />',
			'name' => esc_html__( 'Name', 'basel' ),
			'thumbnail' => esc_html__( 'Preview', 'basel' ),
			'description' => esc_html__( 'Description', 'basel' ),
			'slug' => esc_html__( 'Slug', 'basel' ),
			'posts' => esc_html__( 'Count', 'basel' ),
		);
	}
}

if ( ! function_exists( 'basel_product_attributes_thumbnail_column_content' ) ) {
	function basel_product_attributes_thumbnail_column_content( $content, $column_name, $term_id ) {
		if ( 'thumbnail' === $column_name ) {
			$color = get_term_meta( $term_id, 'color', true );
			$image = get_term_meta( $term_id, 'image', true );

			if ( $image ) {
				?>
					<div class="basel-attr-peview">
						<img src="<?php echo esc_attr( $image ); ?>">
					</div>
				<?php
			} elseif ( $color ) {
				?>
					<div class="basel-attr-peview" style="background-color:<?php echo esc_attr( $color ) ?>;"></div>
				<?php
			}
		}
	}
}
