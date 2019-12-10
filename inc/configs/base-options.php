<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Default options array while Redux is not installed
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'basel_get_base_options', array(
	'main_layout' => 'full-width',
	'header' => 'base',
	'header_color_scheme' => 'dark',
	'header-overlap' => false,
	'page-title-size' => 'small',
	'sidebar_width' => 3,
	'blog_sidebar_width' => 3,
	'shopping_cart' => 1,
	'cart_position' => 'side',
	'header_search' => 'full-screen',
	'logo_width' => 20,
	'blog_design' => 'default',
	'products_hover' => 'alt',
	'footer-layout' => 12,
	'disable_footer' => true,
	'products_columns_mobile' => 2,
	'page_comments' => true,
) );
