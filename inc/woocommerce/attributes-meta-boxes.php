<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Add meta boxes to attributes interface for woocommerce
 * ------------------------------------------------------------------------------------------------
 */

/**
 * ------------------------------------------------------------------------------------------------
 * Handle edit attribute action
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_wc_attribute_update' ) ) {
	add_action( 'woocommerce_attribute_updated', 'basel_wc_attribute_update', 20, 3);
	function basel_wc_attribute_update( $attribute_id, $attribute, $old_attribute_name ) {
		update_option( 'basel_pa_' . $attribute['attribute_name'] . '_swatch_size', sanitize_text_field( $_POST['attribute_swatch_size'] ) );
		
		$attribute_show_on_product = isset( $_POST['attribute_show_on_product'] ) ? $_POST['attribute_show_on_product'] : '';
		update_option( 'basel_pa_' . $attribute['attribute_name'] . '_show_on_product', sanitize_text_field( $attribute_show_on_product ) );

		// Change value of selected option
		basel_admin_scripts_localize();
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Handle add attribute action
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_wc_attribute_add' ) ) {
	add_action( 'woocommerce_attribute_added', 'basel_wc_attribute_add', 20, 2);
	function basel_wc_attribute_add( $attribute_id, $attribute ) {
		add_option( 'basel_pa_' . $attribute['attribute_name'] . '_swatch_size', sanitize_text_field( $_POST['attribute_swatch_size'] ) );

		$attribute_show_on_product = isset( $_POST['attribute_show_on_product'] ) ? $_POST['attribute_show_on_product'] : '';
		add_option( 'basel_pa_' . $attribute['attribute_name'] . '_show_on_product', sanitize_text_field( $attribute_show_on_product ) );
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Get attribute term function
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_wc_get_attribute_term' ) ) {
	function basel_wc_get_attribute_term( $attribute_name, $term ) {
		return get_option( 'basel_' . $attribute_name . '_' .$term );
	}
}
