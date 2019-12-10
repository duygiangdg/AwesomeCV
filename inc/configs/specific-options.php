<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Array of specific optinos
 * ------------------------------------------------------------------------------------------------
 */

$rules = array(
	'top-bar' => array(
		'will-be' => false,
		'if' => 'header',
		'in_array' => array( 'menu-top' )
	),
	'header-overlap' => array(
		'will-be' => false,
		'if' => 'header',
		'in_array' => array( 'base', 'logo-center', 'categories', 'menu-top', )
	),
	'shopping_cart' => array(
		'will-be' => 'disable',
		'if' => 'catalog_mode',
		'in_array' => array( true )
	),
	'header_full_width' => array(
		'will-be' => false,
		'if' => 'header',
		'in_array' => array( 'vertical' )
	),
);

if( is_singular( 'product' ) ) {
	$shop_header_color = get_post_meta( basel_page_ID(), '_basel_header_color_scheme', true );

	$rules['header_color_scheme'] = array(
		'will-be' => 'dark',
		'if' => 'header-overlap',
		'in_array' => array(true)
	);
	
	$rules['header-overlap'] = array(
		'will-be' => false,
		'if' => 'header-overlap',
		'in_array' => array(true)
	);

	if( $shop_header_color == 'light' ) {
		$rules['header_color_scheme'] = array(
			'will-be' => 'dark'
		);
	}

}

if( ! is_user_logged_in() ) {
	$rules['promo_popup'] = array(
		'will-be' => false,
		'if' => 'maintenance_mode',
		'in_array' => array(true)
	);
}

return apply_filters( 'basel_get_specific_options', $rules );