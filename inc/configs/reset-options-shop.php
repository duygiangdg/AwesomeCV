<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Array of options key that would be reset to default values if there is no specific value for 
 * some shop option that is currently importing
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'basel_get_reset_options_shop', array(
	'products_masonry',
	'products_different_sizes',
	'products_columns',
	'site_width',
	'products_hover',
	'shop_layout',
	'shop_filters',
	'ajax_shop',
	'shop_title',
	'shop_categories',
	'header',
	'header-overlap',
	'header_color_scheme',
	'menu_align',
	'header-border',
	'title-background',
	'page-title-size' ,
	'page-title-color',
) );