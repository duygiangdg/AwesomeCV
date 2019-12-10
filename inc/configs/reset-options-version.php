<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Array of options key that would be reset to default values if there is no specific value for 
 * some version that is currently importing
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'basel_get_reset_options_version', array(
	'site_width',
	'primary-color',
	'dark_version',
	'body-background',
	'main_layout',
	'header',
	'header_full_width',
	'top-bar',
	'top-bar-color',
	'top-bar-bg',
	'header_area',
	'header_search',
	'header_color_scheme',
	'header-overlap',
	'header_wishlist',
	'header-border',
	'right_column_width',
	'menu_align',
	'page-title-size',
	'shopping_cart',
	'shopping_icon_alt',
	'cart_position',
	'logo_width',
	'logo_img_width',
	'blog_design',
	'products_hover',
	'footer-layout',
	'sticky_footer',
	'social_email',
	'pinterest_link',
	'portfolio_pagination',
	'header_background',
	'footer-bar-bg',
	'text-font',
	'primary-font',
	'post-titles-font',
	'secondary-font',
	'widget-titles-font',
	'navigation-font',
	'header_banner',
	'header_banner_link',
	'header_banner_shortcode',
	'header_banner_height',
	'header_banner_mobile_height',
	'header_banner_color',
	'header_banner_bg',
	'header_close_btn',
) );