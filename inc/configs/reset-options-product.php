<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Array of options key that would be reset to default values if there is no specific value for 
 * some product option that is currently importing
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'basel_get_reset_options_product', array(
	'product_design',
	'force_header_full_width',
	'thums_position',
	'single_product_style',
	'single_product_layout',
	'product-background',
) );