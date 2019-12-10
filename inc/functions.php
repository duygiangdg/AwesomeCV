<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

// **********************************************************************// 
// ! Body classes
// **********************************************************************// 

if( ! function_exists( 'basel_body_class' ) ) {
	function basel_body_class( $classes ) {

		$page_id = basel_page_ID();

		$site_width = basel_get_opt( 'site_width' );
		$cart_design = basel_get_opt( 'shopping_cart' );
		$wishlist = basel_get_opt( 'header_wishlist' );
		$header = basel_get_opt( 'header' );
		$header_overlap_opt = basel_get_opt( 'header-overlap' );
		$product_design = basel_product_design();
		$top_bar = basel_get_opt( 'top-bar' );
		$ajax_shop = basel_get_opt( 'ajax_shop' );
		$header_search = basel_get_opt( 'header_search' );
		$ajax_search = basel_get_opt( 'search_ajax' );
		$catalog_mode = basel_get_opt( 'catalog_mode' );
		$categories_toggle = basel_get_opt( 'categories_toggle' );
		$header = basel_get_opt( 'header' );
		$sticky_footer = basel_get_opt( 'sticky_footer' );
		$dark = basel_get_opt( 'dark_version' );
		$mobile_menu_position = basel_get_opt( 'mobile_menu_position' );
		$hide_sidebar_mobile = basel_get_opt( 'shop_hide_sidebar_mobile' );
		$hide_sidebar_tablet = basel_get_opt( 'shop_hide_sidebar_tablet' );
		$hide_sidebar_desktop = basel_get_opt( 'shop_hide_sidebar_desktop' );
		$main_sidebar_mobile = basel_get_opt( 'hide_main_sidebar_mobile' );
		$single_post_design = basel_get_opt( 'single_post_design' );
		$header_overlap = $header_sticky = $disable_sticky = false;
		$disable = get_post_meta( $page_id, '_basel_title_off', true );

		$classes[] = 'wrapper-' . $site_width;
		$classes[] = 'global-cart-design-' . $cart_design;
		$classes[] = 'global-search-' . $header_search;
		$classes[] = 'global-header-' . $header;
		$classes[] = 'mobile-nav-from-' . $mobile_menu_position;

		if ( $single_post_design == 'large_image' && is_single() ) {
			$classes[] = 'single-post-large-image';
		}

		if( is_singular( 'product') ) 
			$classes[] = 'basel-product-design-' . $product_design;
		
		if ( basel_woocommerce_installed() && ( is_shop() || is_product_category() ) && ( $hide_sidebar_desktop && $sticky_footer ) ) {
			$classes[] = 'no-sticky-footer';
		}elseif( $sticky_footer ){
			$classes[] = 'sticky-footer-on';
		}
		
		$classes[] = ( $dark ) ? 'basel-dark' : 'basel-light';

		if( $catalog_mode ) {
			$classes[] = 'catalog-mode-on';
		} else {
			$classes[] = 'catalog-mode-off';
		}

		if( $categories_toggle ) {
			$classes[] = 'categories-accordion-on';
		} else {
			$classes[] = 'categories-accordion-off';
		}

		if( $wishlist ) {
			$classes[] = 'global-wishlist-enable';
		} else {
			$classes[] = 'global-wishlist-disable';
		}

		if( $top_bar ) {
			$classes[] = 'basel-top-bar-on';
		} else {
			$classes[] = 'basel-top-bar-off';
		}

		if( $ajax_shop ) {
			$classes[] = 'basel-ajax-shop-on';
		} else {
			$classes[] = 'basel-ajax-shop-off';
		}

		if( $ajax_search ) {
			$classes[] = 'basel-ajax-search-on';
		} else {
			$classes[] = 'basel-ajax-search-off';
		}
		
		//Header banner
		if ( !basel_get_opt( 'header_close_btn' ) && basel_get_opt( 'header_banner' ) && ! basel_maintenance_page() ) {
			$classes[] = 'header-banner-display';
		}
		if ( basel_get_opt( 'header_banner' ) && ! basel_maintenance_page() ) {
			$classes[] = 'header-banner-enabled';
		}

		// Sticky header settings
		if( basel_get_opt('sticky_header') ) {
			$classes[] = 'enable-sticky-header';
			$header_sticky = true;
		} else {
			$disable_sticky = true;
			$classes[] = 'disable-sticky-header';
		}

		// Force header full width class
		if(  is_singular( 'product') && basel_get_opt('force_header_full_width') && basel_product_design() == 'sticky' ) {
			$classes[] = 'header-full-width';
		}

		if( basel_get_opt('header_full_width') ) {
			$classes[] = 'header-full-width';
		}

		if( in_array( $header, array('menu-top') ) ) {
			$header_sticky = 'real';
			$classes[] = 'sticky-navigation-only';
		} else if( in_array( $header, array('base', 'simple', 'logo-center', 'categories') ) ) {
			$header_sticky = 'clone';
		}

		// Header overlaps content in the following cases:
		// 1. Header type is overlap
		// 2. Not on the single product page
		// 3. Not shop page and not disabled page title
		/*if( $header == 'overlap' 
			&& ! is_singular( 'product' )
			&& ! ( basel_woocommerce_installed() 
					&& ( is_shop() || is_product_category() || is_product_tag() || is_singular( "product" ) ) 
					&& $disable
				)
		) {
			$header_overlap = true;
			$header_sticky = 'real';
		} */

		// If header type is SHOP and overlap option is enabled
		if( $header == 'shop' || $header == 'split' ) {
			$header_sticky = 'real';
			if( $header_overlap_opt ) {
				$header_overlap = true;
			}
		}

		if( $header == 'simple' && $header_overlap_opt ) {
			$header_overlap = true;
			$header_sticky = 'real';
		}

		/*if( $header == 'simple' && $header_sticky == 'real' && ! $header_overlap ) {
			$classes[] = 'basel-header-smooth';
		}*/

		if( $header_overlap ) {
			$classes[] = 'basel-header-overlap';
		}

		if( $header_sticky == 'clone' && ! $disable_sticky ) {
			$classes[] = 'sticky-header-clone';
		} elseif( $header_sticky && ! $disable_sticky ) {
			$classes[] = 'sticky-header-real';
		}
		
		//Off canvas sidebar
		if( ( $hide_sidebar_mobile && ( basel_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || basel_is_product_attribute_archieve() ) ) ) || ( $main_sidebar_mobile && ( ! basel_woocommerce_installed() || ( ! is_shop() && ! is_product_category() && ! is_product_tag() && ! basel_is_product_attribute_archieve() ) ) ) ) {
			$classes[] = 'offcanvas-sidebar-mobile';
		}

		if( $hide_sidebar_tablet ) {
			$classes[] = 'offcanvas-sidebar-tablet';
		}

		if( $hide_sidebar_desktop ) {
			$classes[] = 'offcanvas-sidebar-desktop';
		}

		if ( ! is_user_logged_in() && basel_get_opt( 'login_prices' ) ) {
			$classes[] = 'login-see-prices';
		}

		if ( basel_get_opt( 'sticky_toolbar' ) ) {
			$classes[] = 'sticky-toolbar-on';
		}

		return $classes;
	}

	add_filter('body_class', 'basel_body_class');
}


/**
 * ------------------------------------------------------------------------------------------------
 * Filter wp_title
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'basel_wp_title' ) ) {
	function basel_wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( esc_html__( 'Page %s', 'basel' ), max( $paged, $page ) );

		return $title;
	}
	add_filter( 'wp_title', 'basel_wp_title', 10, 2 );

}

/**
 * ------------------------------------------------------------------------------------------------
 * Get predefined footer configuration by index
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'basel_get_footer_config' ) ) {
	function basel_get_footer_config( $index ) {

		if( $index > 20 || $index < 1) {
			$index = 1;
		}

		$configs = apply_filters( 'basel_footer_configs_array', array(
			1 => array(
				'cols' => array(
					'col-sm-12'
				),
				
			),
			2 => array(
				'cols' => array(
					'col-sm-6',
					'col-sm-6',
				),
			),
			3 => array(
				'cols' => array(
					'col-sm-4',
					'col-sm-4',
					'col-sm-4',
				),
			),
			4 => array(
				'cols' => array(
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
				),
				'clears' => array(
					2 => 'sm'
				)
			),
			5 => array(
				'cols' => array(
					'col-md-2 col-sm-4',
					'col-md-2 col-sm-4',
					'col-md-2 col-sm-4',
					'col-md-2 col-sm-4',
					'col-md-2 col-sm-4',
					'col-md-2 col-sm-4',
				),
				'clears' => array(
					3 => 'sm'
				)
			),
			6 => array(
				'cols' => array(
					'col-md-3 col-sm-4',
					'col-md-6 col-sm-4',
					'col-md-3 col-sm-4',
				),
			),
			7 => array(
				'cols' => array(
					'col-md-6 col-sm-4',
					'col-md-3 col-sm-4',
					'col-md-3 col-sm-4',
				),
			),
			8 => array(
				'cols' => array(
					'col-md-3 col-sm-4',
					'col-md-3 col-sm-4',
					'col-md-6 col-sm-4',
				),
			),
			9 => array(
				'cols' => array(
					'col-md-12 col-sm-12',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
				),
				'clears' => array(
					1 => 'md',
					1 => 'lg',
					3 => 'sm',
				),
			),
			10 => array(
				'cols' => array(
					'col-md-6 col-sm-12',
					'col-md-6 col-sm-12',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
					'col-md-3 col-sm-6',
				),
				'clears' => array(
					2 => 'md',
					2 => 'lg',
					4 => 'sm',
				),
			),
			11 => array(
				'cols' => array(
					'col-md-6 col-sm-12',
					'col-md-6 col-sm-12',
					'col-md-2 col-sm-6',
					'col-md-2 col-sm-6',
					'col-md-2 col-sm-6',
					'col-md-2 col-sm-6',
					'col-md-4 col-sm-12',
				),
				'clears' => array(
					2 => 'md',
					2 => 'lg',
					4 => 'sm',
				),
			),
			12 => array(
				'cols' => array(
					'col-md-12 col-sm-12',
					'col-md-2 col-sm-6',
					'col-md-2 col-sm-6',
					'col-md-2 col-sm-6',
					'col-md-2 col-sm-6',
					'col-md-4 col-sm-12',
				),
				'clears' => array(
					1 => 'md',
					1 => 'lg',
					3 => 'sm',
				),
			),
		) );

		return (isset( $configs[$index] )) ? $configs[$index] : array();
	}
}


// **********************************************************************// 
// ! Theme 3d plugins
// **********************************************************************// 


if(!defined('YITH_REFER_ID')) {
    define('YITH_REFER_ID', '1040314');
}


if( ! function_exists( 'basel_3d_plugins' )) {
    function basel_3d_plugins() {
        if( function_exists( 'set_revslider_as_theme' ) ){
            set_revslider_as_theme();
        }
    } 

    add_action( 'init', 'basel_3d_plugins' );
}

if( ! function_exists( 'basel_vcSetAsTheme' ) ) {

    function basel_vcSetAsTheme() {
        if( function_exists( 'vc_set_as_theme' ) ){
            vc_set_as_theme();
        }
    } 

    add_action( 'vc_before_init', 'basel_vcSetAsTheme' );
}


// **********************************************************************// 
// ! Function to get taxonomy meta data
// **********************************************************************// 

if( ! function_exists( 'basel_tax_data' ) ) {
	function basel_tax_data($taxonomy, $term_id, $meta_key) {
		return get_term_meta( $term_id, $meta_key, true);
	}
}

// **********************************************************************// 
// ! Obtain real page ID (shop page, blog, portfolio or simple page)
// **********************************************************************// 

/**
 * This function is called once when initializing BASEL_Layout object
 * then you can use function basel_page_ID to get current page id
 */
if( ! function_exists( 'basel_get_the_ID' ) ) {
	function basel_get_the_ID( $settings = array() ) {
		global $post;

		$page_id = 0;

		$page_for_posts    = get_option( 'page_for_posts' );
		$page_for_shop     = get_option( 'woocommerce_shop_page_id' );
		$page_for_projects = basel_tpl2id( 'portfolio.php' );
		$custom_404_id 	   = basel_get_opt( 'custom_404_page' );
		
		if ( isset( $post->ID ) ) $page_id = $post->ID;

		if ( isset( $post->ID ) && ( is_singular( 'page' ) || is_singular( 'post' ) ) ) { 
			$page_id = $post->ID;
		} else if ( is_home() || is_singular( 'post' ) || is_search() || is_tag() || is_category() || is_date() || is_author() ) {
			$page_id = $page_for_posts;
		} else if ( is_archive('portfolio') && get_post_type() == 'portfolio' ) {
			$page_id = $page_for_projects;
		}

		if ( basel_woocommerce_installed() && function_exists( 'is_shop' )  ) {
			if ( isset( $settings['singulars'] ) && in_array( 'product', $settings['singulars']) && is_singular( 'product' ) ) {
				// keep post id
			} else if ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) || basel_is_product_attribute_archieve() ) {
				$page_id = $page_for_shop;
			}
				
		}

		if ( is_404() && ( $custom_404_id != 'default' || ! empty( $custom_404_id ) ) ) $page_id = $custom_404_id;

		return $page_id;
	}
}


// **********************************************************************// 
// ! Function to get HTML block content
// **********************************************************************// 

if( ! function_exists( 'basel_get_html_block' ) ) {
	function basel_get_html_block($id) {
		$post = get_post( $id );
		if ( ! $post || $post->post_type != 'cms_block' ) return;
		$content = do_shortcode( $post->post_content );

		$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
		$basel_shortcodes_custom_css = get_post_meta( $id, 'basel_shortcodes_custom_css', true );

		$content .= '<style data-type="vc_shortcodes-custom-css">';
			if ( ! empty( $basel_shortcodes_custom_css ) ) {
				$content .= $basel_shortcodes_custom_css;
			}
			if ( ! empty( $shortcodes_custom_css ) ) {
				$content .= $shortcodes_custom_css;
			}
		$content .= '</style>';
		return $content;
	}

}

if( ! function_exists( 'basel_get_static_blocks_array' ) ) {
	function basel_get_static_blocks_array() {
		$args = array( 'posts_per_page' => 200, 'post_type' => 'cms_block' );
		$blocks_posts = get_posts( $args );
		$array = array();
		foreach ( $blocks_posts as $post ) : 
			setup_postdata( $post ); 
			$array[$post->post_title] = $post->ID; 
		endforeach;
		wp_reset_postdata();
		return $array;
	}
}


// **********************************************************************// 
// ! Support shortcodes in text widget
// **********************************************************************// 

add_filter('widget_text', 'do_shortcode');



// **********************************************************************// 
// ! Set excerpt length and more btn
// **********************************************************************// 

add_filter( 'excerpt_length', 'basel_excerpt_length', 999 );

if( ! function_exists( 'basel_excerpt_length' ) ) {
	function basel_excerpt_length( $length ) {
		return 20;
	}
}

add_filter('excerpt_more', 'basel_new_excerpt_more');

if( ! function_exists( 'basel_new_excerpt_more' ) ) {
	function basel_new_excerpt_more( $more ) {
		return '';
	}
}

// **********************************************************************// 
// ! Add scroll to top buttom 
// **********************************************************************// 

add_action( 'wp_footer', 'basel_scroll_top_btn' );

if( ! function_exists( 'basel_scroll_top_btn' ) ) {
	function basel_scroll_top_btn( $more ) {
		if( !basel_get_opt( 'scroll_top_btn' ) ) return;
		?>
			<a href="#" class="scrollToTop basel-tooltip"><?php esc_attr_e( 'Scroll To Top', 'basel' ); ?></a>
		<?php
	}
}


// **********************************************************************// 
// ! Return related posts args array
// **********************************************************************// 

if( ! function_exists( 'basel_get_related_posts_args' ) ) {
	function basel_get_related_posts_args( $post_id ) {
	    $taxs = wp_get_post_tags( $post_id );
	    $args = array();
	    if ( $taxs ) {
	        $tax_ids = array();
	        foreach( $taxs as $individual_tax ) $tax_ids[] = $individual_tax->term_id;
	         
	        $args = array(
	            'tag__in'               => $tax_ids,
	            'post__not_in'          => array( $post_id ),
	            'posts_per_page'        => 12,
	            'ignore_sticky_posts'   => 1
	        );  
	        
	    }

	    return $args;
	}
}





// **********************************************************************// 
// ! Navigation walker
// **********************************************************************// 

if( ! class_exists( 'BASEL_Mega_Menu_Walker' )) {
	class BASEL_Mega_Menu_Walker extends Walker_Nav_Menu {

		private $color_scheme = 'dark';

		/**
		 * Starts the list before the elements are added.
		 *
		 * @see Walker::start_lvl()
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of arguments. @see wp_nav_menu()
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat("\t", $depth);

			if( $depth == 0) {
				$output .= "\n$indent<div class=\"sub-menu-dropdown color-scheme-" . $this->color_scheme . "\">\n";
				$output .= "\n$indent<div class=\"container\">\n";

			}
			if( $depth < 1 ) {
				$sub_menu_class = "sub-menu";
			} else {
				$sub_menu_class = "sub-sub-menu";
			}
			
			$output .= "\n$indent<ul class=\"$sub_menu_class color-scheme-" . $this->color_scheme . "\">\n";

			if( $this->color_scheme == 'light') $this->color_scheme = 'dark';
		}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @see Walker::end_lvl()
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of arguments. @see wp_nav_menu()
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";
			if( $depth == 0) {
				$output .= "$indent</div>\n";
				$output .= "$indent</div>\n";
			}
		}

		/**
		 * Start the element output.
		 *
		 * @see Walker::start_el()
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item   Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   An array of arguments. @see wp_nav_menu()
		 * @param int    $id     Current item ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$design   = $width = $height = $icon = $label = $label_out = '';
			$design   = get_post_meta( $item->ID, '_menu_item_design',   true );
			$width    = get_post_meta( $item->ID, '_menu_item_width',    true );
			$height   = get_post_meta( $item->ID, '_menu_item_height',   true );
			$icon     = get_post_meta( $item->ID, '_menu_item_icon',     true );
			$event    = get_post_meta( $item->ID, '_menu_item_event',    true );
			$label    = get_post_meta( $item->ID, '_menu_item_label',    true );
			$opanchor = get_post_meta( $item->ID, '_menu_item_opanchor', true );
			$callbtn  = get_post_meta( $item->ID, '_menu_item_callbtn', true );
			$color_scheme = get_post_meta( $item->ID, '_menu_item_colorscheme', true );

			if( $color_scheme == 'light' ) $this->color_scheme = 'light';

			if( empty($design) ) $design = 'default';
			$classes[] = 'menu-item-design-' . $design;

			$event = (empty($event)) ? 'hover' : $event;
			$classes[] = 'item-event-' . $event;

			if( $opanchor == 'enable' ) {
				 $classes[] = 'onepage-link';
				if(($key = array_search('current-menu-item', $classes)) !== false) {
					unset($classes[$key]);
				}
			}

			if( $callbtn == 'enable' ) {
				$classes[] = 'callto-btn';
			}

			if( !empty( $label ) ) {
				$classes[] = 'item-with-label';
				$classes[] = 'item-label-' . $label;
				$label_text = '';
				switch ( $label ) {
					case 'hot':
						$label_text = esc_html__('Hot', 'basel');
					break;
					case 'sale':
						$label_text = esc_html__('Sale', 'basel');
					break;
					case 'new':
						$label_text = esc_html__('New', 'basel');
					break;
				}
				$label_out = '<span class="menu-label menu-label-' . $label . '">' . esc_attr( $label_text ) . '</span>';
			}

			if( ! empty( $item->description ) ) {
				$classes[] = 'menu-item-has-children';
			}

			/**
			 * Filter the CSS class(es) applied to a menu item's list item element.
			 *
			 * @since 3.0.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
			 * @param object $item    The current menu item.
			 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
			 * @param int    $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filter the ID applied to a menu item's list item element.
			 *
			 * @since 3.0.1
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
			 * @param object $item    The current menu item.
			 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
			 * @param int    $depth   Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $class_names .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

			/**
			 * Filter the HTML attributes applied to a menu item's anchor element.
			 *
			 * @since 3.6.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array $atts {
			 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 *     @type string $title  Title attribute.
			 *     @type string $target Target attribute.
			 *     @type string $rel    The rel attribute.
			 *     @type string $href   The href attribute.
			 * }
			 * @param object $item  The current menu item.
			 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
			 * @param int    $depth Depth of menu item. Used for padding.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			if($icon != '') {
				$item_output .= '<i class="fa fa-' . $icon . '"></i>';
			}
			/** This filter is documented in wp-includes/post-template.php */
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= $label_out;
			$item_output .= '</a>';
			$item_output .= $args->after;

			$styles = '';

			if( $depth == 0) {
				/**
				 * Add background image to dropdown
				 **/


				if( has_post_thumbnail( $item->ID ) ) {
					$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'full' );

					//ar($post_thumbnail);

					$styles .= '.menu-item-' . $item->ID . ' > .sub-menu-dropdown {';
						$styles .= 'background-image: url(' . $post_thumbnail[0] .'); ';
					$styles .= '}';
				}

				if( ! empty( $item->description ) && !in_array("menu-item-has-children", $item->classes) ) {
					$item_output .= "\n$indent<div class=\"sub-menu-dropdown color-scheme-" . $this->color_scheme . "\">\n";
					$item_output .= "\n$indent<div class=\"container\">\n";
						$item_output .= do_shortcode( $item->description );
					$item_output .= "\n$indent</div>\n";
					$item_output .= "\n$indent</div>\n";

					if( $this->color_scheme == 'light') $this->color_scheme = 'dark';
				}
			}

			if($design == 'sized' && !empty($height) && !empty($width)) {
				$styles .= '.menu-item-' . $item->ID . ' > .sub-menu-dropdown {';
					$styles .= 'min-height: ' . $height .'px; ';
					$styles .= 'width: ' . $width .'px; ';
				$styles .= '}';
			}


			if( $styles != '' ) {
				$item_output .= '<style>';
				$item_output .= $styles;
				$item_output .= '</style>';
			}

			/**
			 * Filter a menu item's starting output.
			 *
			 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
			 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			 * no filter for modifying the opening and closing `<li>` for a menu item.
			 *
			 * @since 3.0.0
			 *
			 * @param string $item_output The menu item's starting HTML output.
			 * @param object $item        Menu item data object.
			 * @param int    $depth       Depth of menu item. Used for padding.
			 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
}



// **********************************************************************// 
// ! // Deletes first gallery shortcode and returns content (http://stackoverflow.com/questions/17224100/wordpress-remove-shortcode-and-save-for-use-elsewhere)
// **********************************************************************// 

if( ! function_exists( 'basel_strip_shortcode_gallery' ) ) {
	function  basel_strip_shortcode_gallery( $content ) {
	    preg_match_all( '/'. get_shortcode_regex() .'/s', $content, $matches, PREG_SET_ORDER );
	    if ( ! empty( $matches ) ) {
	        foreach ( $matches as $shortcode ) {
	            if ( 'gallery' === $shortcode[2] ) {
	                $pos = strpos( $content, $shortcode[0] );
	                if ($pos !== false)
	                    return substr_replace( $content, '', $pos, strlen($shortcode[0]) );
	            }
	        }
	    }
	    return $content;
	}
}


// **********************************************************************// 
// ! Get exceprt from post content
// **********************************************************************// 

if( ! function_exists( 'basel_excerpt_from_content' ) ) {
	function basel_excerpt_from_content($post_content, $limit, $shortcodes = '') {
        // Strip shortcodes and HTML tags
        if ( empty( $shortcodes )) {
	        $post_content = preg_replace("/\[caption(.*)\[\/caption\]/i", '', $post_content);
            $post_content = preg_replace('`\[[^\]]*\]`','',$post_content);
        }

        $post_content = stripslashes(wp_filter_nohtml_kses($post_content));

        $excerpt = explode(' ', $post_content, $limit);

        if ( count( $excerpt) >= $limit ) {
            array_pop( $excerpt );
            $excerpt = implode( " ", $excerpt ) . '...';
        } else {
            $excerpt = implode( " ", $excerpt );
        }

        $excerpt = strip_tags( $excerpt );

        if (trim($excerpt) == '...') {
            return '';
        }

        return $excerpt;
    }
}

// **********************************************************************// 
// ! Get portfolio taxonomies dropdown
// **********************************************************************// 

if( ! function_exists( 'basel_get_projects_cats_array') ) {
	function basel_get_projects_cats_array() {
		$return = array('All' => '');

		if( ! post_type_exists( 'portfolio' ) ) return array();

		$cats = get_terms( 'project-cat' );

		foreach ($cats as $key => $cat) {
			$return[$cat->name] = $cat->term_id;
		}

		return $return;
	}
}

// **********************************************************************// 
// ! Get menus dropdown
// **********************************************************************// 

if( ! function_exists( 'basel_get_menus_array') ) {
	function basel_get_menus_array() {
		$basel_menus = wp_get_nav_menus();
		$basel_menu_dropdown = array();
		
		foreach ( $basel_menus as $menu ) {

			$basel_menu_dropdown[$menu->term_id] = $menu->name;
			
		}

		return $basel_menu_dropdown;
	}
}


// **********************************************************************// 
// ! Get registered sidebars dropdown
// **********************************************************************// 

if(!function_exists('basel_get_sidebars_array')) {
    function basel_get_sidebars_array() {
        global $wp_registered_sidebars;
        $sidebars['none'] = 'none';
        foreach( $wp_registered_sidebars as $id=>$sidebar ) {
            $sidebars[ $id ] = $sidebar[ 'name' ];
        }
        return $sidebars;
    }
}


// **********************************************************************// 
// ! If page needs header
// **********************************************************************// 

if( ! function_exists( 'basel_needs_header' ) ) {
	function basel_needs_header() {
		return ( ! basel_maintenance_page() );
	}
}

// **********************************************************************// 
// ! If page needs footer
// **********************************************************************// 

if( ! function_exists( 'basel_needs_footer' ) ) {
	function basel_needs_footer() {
		return ( ! basel_maintenance_page() );
	}
}

// **********************************************************************// 
// ! Is maintenance page
// **********************************************************************// 

if( ! function_exists( 'basel_maintenance_page' ) ) {
	function basel_maintenance_page() {
		
        $pages_ids = basel_pages_ids_from_template( 'maintenance' );

        if( ! empty( $pages_ids ) && is_page( $pages_ids ) ) {
        	return true;
        }

		return false;
	}
}


// **********************************************************************// 
// ! Get page id by template name
// **********************************************************************// 

if( ! function_exists( 'basel_pages_ids_from_template' ) ) {
	function basel_pages_ids_from_template( $name ) {
		$pages = get_pages(array(
		    'meta_key' => '_wp_page_template',
		    'meta_value' => $name . '.php'
		));

		$return = array();

		foreach($pages as $page){
		    $return[] = $page->ID;
		}

		return $return;
	}
}

// **********************************************************************// 
// ! Get content of the SVG icon located in images/svg folder
// **********************************************************************// 
if( ! function_exists( 'basel_get_svg_content' ) ) {
	function basel_get_svg_content($name) {
		$folder = BASEL_THEMEROOT . '/images/svg';
		$file = $folder . '/' . $name . '.svg';

		return (file_exists( $file )) ? basel_get_any_svg( $file ) : false;
	}
}

if( ! function_exists( 'basel_get_any_svg' ) ) {
	function basel_get_any_svg( $file, $id = false ) {
		$content = basel_get_svg( $file );
		$start_tag = '<svg';
		if( $id ) {
			$pattern = "/id=\"(\w)+\"/";
			if( preg_match($pattern, $content) ) {
				$content = preg_replace($pattern, "id=\"" . $id . "\"", $content, 1);
			} else {
				$content = preg_replace( "/<svg/", "<svg id=\"" . $id . "\"", $content);
			}
		}
		// Strip doctype
		$position = strpos($content, $start_tag);
		$content = substr($content, $position);
		return $content;
	}
}

// **********************************************************************// 
// ! Get config file
// **********************************************************************// 

if( ! function_exists( 'basel_get_config' ) ) {
	function basel_get_config( $name ) {
		// $allowed = array('selectors', 'versions', 'base-options', 'widgets-import', 'specific-options', 'product-hovers');
		$path = BASEL_CONFIGS . '/' . $name . '.php';
		if( file_exists( $path ) ) { // && in_array($name, $allowed) 
			return include $path;
		} else {
			return array();
		}
	}
}


// **********************************************************************// 
// ! Text to one-line string
// **********************************************************************// 

if( ! function_exists( 'basel_text2line')) {
	function basel_text2line( $str ) {
        return trim(preg_replace("/('|\"|\r?\n)/", '', $str)); 
	}
}


// **********************************************************************// 
// ! Get page ID by it's template name
// **********************************************************************// 
if( ! function_exists( 'basel_tpl2id' ) ) {
	function basel_tpl2id( $tpl = '' ) {
		$pages = get_pages(array(
		    'meta_key' => '_wp_page_template',
		    'meta_value' => $tpl
		));
		foreach($pages as $page){
		    return $page->ID;
		}
	}
}

// **********************************************************************// 
// ! Function print array within a pre tags
// **********************************************************************// 
if( ! function_exists( 'ar' ) ) {
	function ar($array) {

		echo '<pre>';
			print_r($array);
		echo '</pre>';

	}
}


// **********************************************************************// 
// ! Get protocol (http or https)
// **********************************************************************// 
if( ! function_exists( 'basel_http' )) {
	function basel_http() {
		if( ! is_ssl() ) {
			return 'http';
		} else {
			return 'https';
		}
	}
}

// **********************************************************************// 
//  Function return vc_row with gradient.
// **********************************************************************// 
if( ! function_exists( 'basel_get_gradient_attr' ) ) {
	function basel_get_gradient_attr( $output, $obj, $attr ) {
		if ( ! empty( $attr['basel_gradient_switch'] ) ) {
			$gradient_css = basel_get_gradient_css( $attr['basel_color_gradient'] );
			$output = preg_replace_callback('/basel-row-gradient-enable.*?>/',
				function ( $matches ) use( $gradient_css ) {
				   return strtolower( $matches[0] . '<div class="basel-row-gradient" style="' . $gradient_css . '";></div>' );
				}, $output );
		}
		return $output;
	}
}
add_filter( 'vc_shortcode_output', 'basel_get_gradient_attr', 10, 3 );

// **********************************************************************// 
//  Function return gradient css.
// **********************************************************************// 
if( ! function_exists( 'basel_get_gradient_css' ) ) {
	function basel_get_gradient_css( $gradient_attr ) {
		$gradient_css = explode( '|', $gradient_attr );
		$result =  'background-image:-webkit-' . $gradient_css[1] . ';';
		$result .= 'background-image:-moz-' . $gradient_css[1] . ';';
		$result .= 'background-image:-o-' . $gradient_css[1] . ';';
		$result .= 'background-image:'.$gradient_css[1] . ';';
		$result .= 'background-image:-ms-' . $gradient_css[1] . ';';
		return $result;
	}
}
// **********************************************************************// 
// ! Append :hover to CSS selectors array
// **********************************************************************// 
if( ! function_exists( 'basel_append_hover_state' ) ) {
	function basel_append_hover_state( $selectors , $focus = false ) {
		// if( ! is_array( $selectors ) ) {
			$selectors = explode(',', $selectors[0]);
		// }

		$return = array();

		foreach ($selectors as $selector) {
			$return[] = $selector . ':hover';
			( $focus ) ? $return[] .= $selector . ':focus' : false ;
		}

		return implode(',', $return);
	}
}
// **********************************************************************// 
// Include gradient file
// **********************************************************************// 
if( ! function_exists( 'basel_register_redux_gradient' ) ) {
	function basel_register_redux_gradient( $field ) {
		return get_template_directory() . 'inc/classes/Gradient.php';
	}
}
add_filter( 'redux/basel_options/field/class/basel_gradient', 'basel_register_redux_gradient' ); 


// **********************************************************************// 
// Get gradient field
// **********************************************************************// 
if( ! function_exists( 'basel_get_gradient_field' ) ) {
	function basel_get_gradient_field( $param_name, $value, $is_VC = false ) {
		$classes = $param_name;
		$classes .= ( $is_VC ) ? ' wpb_vc_param_value' : '';
		$uniqid = uniqid();
		$output = '<div class="basel-grad-wrap">';
			$output .= '<div class="basel-grad-line" id="basel-grad-line' . $uniqid . '"></div>';
			$output .= '<div class="basel-grad-preview" id="basel-grad-preview' . $uniqid . '"></div>';
			$output .= '<input id="basel-grad-val' . $uniqid . '" class="' . $classes . '" name="' . $param_name . '"  style="display:none"  value="'.$value.'"/>';
		$output .= '</div>';

		$gradient_data = explode( '|', $value );
		$gradient_points_data = $gradient_data[0];
		$gradient_type_data = ( isset( $gradient_data[2] ) ) ? $gradient_data[2] : '';
		$gradient_direction_data = ( isset( $gradient_data[3] ) ) ? $gradient_data[3] : '';

		//Point result
		$result_point_value = '';
		if ( ! empty( $gradient_points_data ) ) {
			$points_value = explode( '/', $gradient_points_data );
			array_pop( $points_value );
			foreach ( $points_value as $key => $points_values ) {
				$points_values = explode( '-', $points_values );
				$result_point_value .= '{color:"' . $points_values[0] . '",position:' . $points_values[1] . '},';
			}
		}else{
			$result_point_value = '{color:"rgb(60, 27, 59)",position:0},{color:"rgb(90, 55, 105)",position: 33},{color:"rgb(46, 76, 130)",position:66},{color:"rgb(29, 28, 44)",position:100}';
		}

		//Type result
		$result_type_value = ( ! empty( $gradient_type_data ) ) ? $gradient_type_data : 'linear' ;

		//Direction result
		$result_direction_value = ( ! empty( $gradient_direction_data ) ) ? $gradient_direction_data : 'left' ;

		
		$output .= "<script>
		jQuery( document ).ready( function() {
			var gradient_line = '#basel-grad-line" . $uniqid . "',
				gradient_preview = '#basel-grad-preview" . $uniqid . "',
				grad_val = '#basel-grad-val" . $uniqid . "';

			gradX(gradient_line, {
				targets: [gradient_preview],
				change: function( points, styles, type, direction ) {
				   for( i = 0; i < styles.length; ++i )  {  
				       jQuery( gradient_preview ).css( 'background-image', styles[i] );
						var points_value = '';
						jQuery( points ).each( function( index , value ){
							points_value +=  value[0] + '-' + value[1] + '/';
						})
						jQuery( grad_val ).attr( 'value', points_value + '|' + styles[i] + '|' + type + '|' + direction );
				   }
				 }, 
				type: \"" . $result_type_value . "\",
				direction: \"" .  $result_direction_value . "\",
				sliders: [" . $result_point_value . "]
			});
		})
		</script>";
		return $output;
	}
}

// **********************************************************************// 
// Basel get theme info
// **********************************************************************// 
if( ! function_exists( 'basel_get_theme_info' ) ) {
	function basel_get_theme_info( $parameter ) {
		$theme_info = wp_get_theme();
		if ( is_child_theme() ){
			$theme_info = wp_get_theme( $theme_info->parent()->template );
		} 
		return $theme_info->get( $parameter ); 
	}
}

// **********************************************************************// 
// Basel page CSS
// **********************************************************************// 
if ( !function_exists('basel_settings_css') ) { 
	function basel_settings_css() { 
        $logo_container_width = basel_get_opt( 'logo_width' );
        $logo_img_width = basel_get_opt( 'logo_img_width' );
		$right_column_width   = basel_get_opt( 'right_column_width' );

        $header = basel_get_opt( 'header' );
        $header_height = basel_get_opt( 'header_height' );
        $sticky_header_height = basel_get_opt( 'sticky_header_height' );
        $mobile_header_height = basel_get_opt( 'mobile_header_height' );

        $right_column_width_percents = $menu_width = (int) (100 - $logo_container_width) / 2;

        $widgets_scroll = basel_get_opt( 'widgets_scroll' );
        $widgets_height = basel_get_opt( 'widget_heights' );

        $custom_css 		= basel_get_opt( 'custom_css' );
        $css_desktop 		= basel_get_opt( 'css_desktop' );
        $css_tablet 		= basel_get_opt( 'css_tablet' );
        $css_wide_mobile 	= basel_get_opt( 'css_wide_mobile' );
        $css_mobile         = basel_get_opt( 'css_mobile' );
        $custom_js          = basel_get_opt( 'custom_js' );
        $js_ready 		    = basel_get_opt( 'js_ready' );
		
		//Topbar
		$topbar_height = basel_get_opt( 'top_bar_height' );
		$topbar_height_mobile = basel_get_opt( 'top_bar_mobile_height' );
		
		//Header banner
		$header_banner_height = basel_get_opt( 'header_banner_height' );
		$header_banner_height_mobile = basel_get_opt( 'header_banner_mobile_height' );
		
		//Shop popup
		$shop_popup_width = basel_get_opt( 'popup_width' );

        $custom_product_background = get_post_meta( get_the_ID(),  '_basel_product-background', true );
		
		ob_start();

		?>	
			/* Shop popup */
			
			.basel-promo-popup {
			   max-width: <?php echo esc_html( $shop_popup_width ); ?>px;
			}
	
            .site-logo {
                width: <?php echo esc_html( $logo_container_width ); ?>%;
            }    

            .site-logo img {
                max-width: <?php echo esc_html( $logo_img_width ); ?>px;
                max-height: <?php echo esc_html( $header_height ); ?>px;
            }    

            <?php if( $header == 'shop'  ): ?>
                .widgetarea-head,
                .main-nav {
                    width: <?php echo esc_html( $menu_width ); ?>%;
                }  

                .right-column {
                    width: <?php echo esc_html( $right_column_width_percents ); ?>%;
                }  

            <?php elseif( $header == 'logo-center' ): ?>
                .widgetarea-head {
                    width: <?php echo esc_html( $menu_width ); ?>%;
                }  

                .right-column {
                    width: <?php echo esc_html( $right_column_width_percents ); ?>%;
                }  

                .sticky-header .right-column {
                    width: <?php echo esc_html( $right_column_width ); ?>px;
                }  

            <?php elseif( $header == 'split' ): ?>
                .left-column,
                .right-column {
                    width: <?php echo esc_html( $right_column_width ); ?>px;
                }  
            <?php else: ?>
                .right-column {
                    width: <?php echo esc_html( $right_column_width ); ?>px;
                }  
            <?php endif; ?>

            <?php if( $widgets_scroll ): ?>
                .basel-woocommerce-layered-nav .basel-scroll {
                    max-height: <?php echo esc_html($widgets_height); ?>px;
                }
            <?php endif; ?>

			/* header Banner */
			.header-banner {
				height: <?php echo esc_html( $header_banner_height ); ?>px;
			}
	
			.header-banner-display .website-wrapper {
				margin-top:<?php echo esc_html( $header_banner_height ); ?>px;
			}	

            /* Topbar height configs */

			.topbar-menu ul > li {
				line-height: <?php echo esc_html( $topbar_height ); ?>px;
			}
			
			.topbar-wrapp,
			.topbar-content:before {
				height: <?php echo esc_html( $topbar_height ); ?>px;
			}
			
			.sticky-header-prepared.basel-top-bar-on .header-shop, 
			.sticky-header-prepared.basel-top-bar-on .header-split,
			.enable-sticky-header.basel-header-overlap.basel-top-bar-on .main-header {
				top: <?php echo esc_html( $topbar_height ); ?>px;
			}

            /* Header height configs */

            /* Limit logo image height for according to header height */
            .site-logo img {
                max-height: <?php echo esc_html( $header_height ); ?>px;
            } 

            /* And for sticky header logo also */
            .act-scroll .site-logo img,
            .header-clone .site-logo img {
                max-height: <?php echo esc_html( $sticky_header_height ); ?>px;
            }   

            /* Set sticky headers height for cloned headers based on menu links line height */
            .header-clone .main-nav .menu > li > a {
                height: <?php echo esc_html( $sticky_header_height ); ?>px;
                line-height: <?php echo esc_html( $sticky_header_height ); ?>px;
            } 

            /* Height for switch logos */

            .sticky-header-real:not(.global-header-menu-top) .switch-logo-enable .basel-logo {
                height: <?php echo esc_html( $header_height ); ?>px;
            }

            .sticky-header-real:not(.global-header-menu-top) .act-scroll .switch-logo-enable .basel-logo {
                height: <?php echo esc_html( $sticky_header_height ); ?>px;
            }

            .sticky-header-real:not(.global-header-menu-top) .act-scroll .switch-logo-enable {
                transform: translateY(-<?php echo esc_html( $sticky_header_height ); ?>px);
                -webkit-transform: translateY(-<?php echo esc_html( $sticky_header_height ); ?>px);
            }

            <?php if( $header == 'base' || $header == 'logo-center' || $header == 'split' ): ?>
                /* Header height for layouts that don't have line height for menu links */
                .wrapp-header {
                    min-height: <?php echo esc_html( $header_height ); ?>px;
                } 
            <?php elseif( $header != 'vertical' ): ?>
                /* Header height for these layouts based on it's menu links line height */
                .main-nav .menu > li > a {
                    height: <?php echo esc_html( $header_height ); ?>px;
                    line-height: <?php echo esc_html( $header_height ); ?>px;
                }  
                /* The same for sticky header */
                .act-scroll .main-nav .menu > li > a {
                    height: <?php echo esc_html( $sticky_header_height ); ?>px;
                    line-height: <?php echo esc_html( $sticky_header_height ); ?>px;
                }  
            <?php endif; ?>

            <?php if( $header == 'split'  ): ?>
                /* Sticky header height for split header layout */
                .act-scroll .wrapp-header {
                    min-height: <?php echo esc_html( $sticky_header_height ); ?>px;
                }   
            <?php endif; ?>

            <?php if( $header == 'shop'  ): ?>
                /* Set line height for header links for shop header layout. Based in the header height option */
                .header-shop .right-column .header-links {
                    height: <?php echo esc_html( $header_height ); ?>px;
                    line-height: <?php echo esc_html( $header_height ); ?>px;
                }  

                /* The same for sticky header */
                .header-shop.act-scroll .right-column .header-links {
                    height: <?php echo esc_html( $sticky_header_height ); ?>px;
                    line-height: <?php echo esc_html( $sticky_header_height ); ?>px;
                }  
            <?php endif; ?>

            <?php if ( !empty( $custom_product_background ) ): ?>
				.single-product .site-content{
					background-color: <?php echo esc_html( $custom_product_background ); ?> !important;
				}
			<?php endif ?>

            /* Page headings settings for heading overlap. Calculate on the header height base */

            .basel-header-overlap .title-size-default,
            .basel-header-overlap .title-size-small,
            .basel-header-overlap .title-shop.without-title.title-size-default,
            .basel-header-overlap .title-shop.without-title.title-size-small {
                padding-top: <?php echo esc_html($header_height + 40);  ?>px;
            }


            .basel-header-overlap .title-shop.without-title.title-size-large,
            .basel-header-overlap .title-size-large {
                padding-top: <?php echo esc_html($header_height + 120);  ?>px;
            }

            @media (max-width: 991px) {

				/* header Banner */
				.header-banner {
					height: <?php echo esc_html( $header_banner_height_mobile ); ?>px;
				}
	
				.header-banner-display .website-wrapper {
					margin-top:<?php echo esc_html( $header_banner_height_mobile ); ?>px;
				}

	            /* Topbar height configs */
				.topbar-menu ul > li {
					line-height: <?php echo esc_html( $topbar_height_mobile ); ?>px;
				}
				
				.topbar-wrapp,
				.topbar-content:before {
					height: <?php echo esc_html( $topbar_height_mobile ); ?>px;
				}
				
				.sticky-header-prepared.basel-top-bar-on .header-shop, 
				.sticky-header-prepared.basel-top-bar-on .header-split,
				.enable-sticky-header.basel-header-overlap.basel-top-bar-on .main-header {
					top: <?php echo esc_html( $topbar_height_mobile ); ?>px;
				}

                /* Set header height for mobile devices */
                .main-header .wrapp-header {
                    min-height: <?php echo esc_html( $mobile_header_height ); ?>px;
                } 

                /* Limit logo image height for mobile according to mobile header height */
                .site-logo img {
                    max-height: <?php echo esc_html( $mobile_header_height ); ?>px;
                }   

                /* Limit logo on sticky header. Both header real and header cloned */
                .act-scroll .site-logo img,
                .header-clone .site-logo img {
                    max-height: <?php echo esc_html( $mobile_header_height ); ?>px;
                }

                /* Height for switch logos */

                .main-header .switch-logo-enable .basel-logo {
                    height: <?php echo esc_html( $mobile_header_height ); ?>px;
                }

                .sticky-header-real:not(.global-header-menu-top) .act-scroll .switch-logo-enable .basel-logo {
                    height: <?php echo esc_html( $mobile_header_height ); ?>px;
                }

                .sticky-header-real:not(.global-header-menu-top) .act-scroll .switch-logo-enable {
                    transform: translateY(-<?php echo esc_html( $mobile_header_height ); ?>px);
                    -webkit-transform: translateY(-<?php echo esc_html( $mobile_header_height ); ?>px);
                }

                /* Page headings settings for heading overlap. Calculate on the MOBILE header height base */
                .basel-header-overlap .title-size-default,
                .basel-header-overlap .title-size-small,
                .basel-header-overlap .title-shop.without-title.title-size-default,
                .basel-header-overlap .title-shop.without-title.title-size-small {
                    padding-top: <?php echo esc_html($mobile_header_height + 20);  ?>px;
                }

                .basel-header-overlap .title-shop.without-title.title-size-large,
                .basel-header-overlap .title-size-large {
                    padding-top: <?php echo esc_html($mobile_header_height + 60);  ?>px;
                }
 
            }
     
            <?php 
            if( $custom_css != '' ) {
                echo basel_get_opt( 'custom_css' );
            }
            if( $css_desktop != '' ) {
                echo '@media (min-width: 992px) { ' . ($css_desktop) . ' }'; 
            }
            if( $css_tablet != '' ) {
                echo '@media (min-width: 768px) and (max-width: 991px) {' . ($css_tablet) . ' }'; 
            }
            if( $css_wide_mobile != '' ) {
                echo '@media (min-width: 481px) and (max-width: 767px) { ' . ($css_wide_mobile) . ' }'; 
            }
            if( $css_mobile != '' ) {
                echo '@media (max-width: 480px) { ' . ($css_mobile) . ' }'; 
            }

			return ob_get_clean();
	} 
}

// **********************************************************************// 
//  Function return vc_video with image mask.
// **********************************************************************// 
if( ! function_exists( 'basel_add_video_poster' ) ) {
	function basel_add_video_poster( $output, $obj, $attr ) {
		if ( ! empty( $attr['image_poster_switch'] ) ) {
			$image_id = $attr['poster_image'];
			$image_size = 'full';
			if ( isset( $attr['img_size'] ) ) $image_size = $attr['img_size'];
			$image = basel_get_image_src( $image_id, $image_size );
			$output = preg_replace_callback('/wpb_video_wrapper.*?>/',
				function ( $matches ) use( $image ) {
				   return $matches[0] . '<div class="basel-video-poster-wrapper"><div class="basel-video-poster" style="background-image:url(' . $image . ')";></div><div class="button-play"></div></div>';
				}, $output );
		}
		return $output;
	}
	add_filter( 'vc_shortcode_output', 'basel_add_video_poster', 10, 3 );
}

// **********************************************************************// 
//  Function return all images sizes
// **********************************************************************// 
if( ! function_exists( 'basel_get_all_image_sizes' ) ) {
	function basel_get_all_image_sizes() {
	    global $_wp_additional_image_sizes;

	    $default_image_sizes = array( 'thumbnail', 'medium', 'large', 'full' );

	    foreach ( $default_image_sizes as $size ) {
	        $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
	        $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
	        $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
	    }

	    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
	        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
	    }

	    return $image_sizes;
	}
}
if( ! function_exists( 'basel_get_image_size' ) ) {
	function basel_get_image_size( $thumb_size ) {
		if ( is_string( $thumb_size ) && in_array( $thumb_size, array( 'thumbnail', 'thumb', 'medium', 'large', 'full' ) ) ) {
			$images_sizes = basel_get_all_image_sizes();
			$image_size = $images_sizes[$thumb_size];
			if ( $thumb_size == 'full') {
				$image_size['width'] = 999999; 
				$image_size['height'] = 999999;
			}
			return array( $image_size['width'], $image_size['height'] );
		}elseif ( is_string( $thumb_size ) ) {
			preg_match_all( '/\d+/', $thumb_size, $thumb_matches );
			if ( isset( $thumb_matches[0] ) ) {
				$thumb_size = array();
				if ( count( $thumb_matches[0] ) > 1 ) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][1]; // height
				} elseif ( count( $thumb_matches[0] ) > 0 && count( $thumb_matches[0] ) < 2 ) {
					$thumb_size[] = $thumb_matches[0][0]; // width
					$thumb_size[] = $thumb_matches[0][0]; // height
				} else {
					$thumb_size = false;
				}
			}
			return $thumb_size;	
		}	
	}
}

if( ! function_exists( 'basel_get_image_src' ) ) {
	function basel_get_image_src( $thumb_id, $thumb_size ) {
		$thumb_size = basel_get_image_size( $thumb_size );
		$thumbnail = wpb_resize( $thumb_id, null, $thumb_size[0], $thumb_size[1], true );
		return $thumbnail['url'];
	}
}

// **********************************************************************// 
// Check is theme is activated with a purchase code
// **********************************************************************// 

if ( ! function_exists( 'basel_is_license_activated' ) ) {
	function basel_is_license_activated() {
	    return get_option( 'basel_is_activated', false );
	}
}

// **********************************************************************//
// ! Function to get all pages
// **********************************************************************//

if( ! function_exists( 'basel_get_pages' ) ) {
	function basel_get_pages() {
		$pages = array( 'default' => esc_html__( 'Default', 'basel' ) );
		foreach( get_pages() as $page ){
			$pages[$page->ID] = $page->post_title;
		}  
		return $pages;
	}
}

// **********************************************************************//
// ! Function to set custom 404 page
// **********************************************************************//
if( ! function_exists( 'basel_custom_404_page' ) ) {
	function basel_custom_404_page( $template ) {
		global $wp_query;
		$custom_404 = basel_get_opt( 'custom_404_page' );
		if ( $custom_404 == 'default' || empty( $custom_404 )  ) return $template;

		$wp_query->query( 'page_id=' . $custom_404 );
		$wp_query->the_post();
		$template = get_page_template();
		rewind_posts();

		return $template;
	}
	add_filter( '404_template', 'basel_custom_404_page', 999 );
}

// **********************************************************************//
// Styles | Custom Font
// **********************************************************************//

if ( ! function_exists( 'basel_custom_font_css' ) ) {
	function basel_custom_font_css() {
		$custom_fonts = basel_get_opt( 'multi_custom_fonts' );
		if ( ! $custom_fonts ) return;

		echo '<style>'."\n";
			foreach ( $custom_fonts as $key => $value ) {

				$eot = basel_get_custom_font_url( $value['font-eot'] );
				$woff = basel_get_custom_font_url( $value['font-woff'] );
				$woff2 = basel_get_custom_font_url( $value['font-woff2'] );
				$ttf = basel_get_custom_font_url( $value['font-ttf'] );
				$svg = basel_get_custom_font_url( $value['font-svg'] );

				if ( ! $value['font-name'] ) continue;

				echo '@font-face {'."\n";
					echo 'font-family: "'. sanitize_text_field( $value['font-name'] ) .'";'."\n";
					if ( $eot ) {
						echo 'src: url("'. esc_url( $eot ) .'");'."\n";
						echo 'src: url("'. esc_url( $eot ).'#iefix") format("embedded-opentype"),'."\n";
					}

					if ( $woff ) {
						echo 'url("'. esc_url( $woff ) .'") format("woff"),'."\n";
					}

					if ( $woff2 ) {
						echo 'url("'. esc_url( $woff2 ) .'") format("woff2"),'."\n";
					}

					if ( $ttf ) {
						echo 'url("'. esc_url( $ttf ) .'") format("truetype"),'."\n";
					}

					if ( $svg ) {
						echo 'url("'. esc_url( $svg ) .'#'. sanitize_text_field( $value['font-name'] ) .'") format("svg");'."\n";
					}

					if ( $value['font-weight'] ) {
						echo 'font-weight: ' . sanitize_text_field( $value['font-weight'] ) . ';'."\n";
					} else {
						echo 'font-weight: normal;'."\n";
					}

					echo 'font-style: normal;'."\n";
				echo '}'."\n";
			}
		echo '</style>'."\n";
	}
	
	add_action( 'wp_head', 'basel_custom_font_css' );
}

if ( ! function_exists( 'basel_get_custom_font_url' ) ) {
	function basel_get_custom_font_url( $font ) {
		$url = $font;
		if ( isset( $font['id'] ) && $font['id'] ) {
			$url = wp_get_attachment_url( $font['id'] );
		} elseif ( is_array( $font ) ) {
			$url = $font['url'];
		}

		return $url;
	}
}

// **********************************************************************//
// Get typekit fonts
// **********************************************************************//
if ( ! function_exists( 'basel_add_custom_fonts' ) ) {
	function basel_add_custom_fonts() {
		global $basel_options;
		$fonts = array();
		$typekit_fonts = isset( $basel_options['typekit_fonts'] ) ? $basel_options['typekit_fonts'] : '';
		$custom_fonts = isset( $basel_options['multi_custom_fonts'] ) ? $basel_options['multi_custom_fonts'] : '';

		if ( $typekit_fonts ) {
			$typekit = explode( ',', $typekit_fonts );
			$fonts['Custom-Fonts'] = array_combine( $typekit, $typekit );
		}

		if ( $custom_fonts ) {
			foreach ( $custom_fonts as $key => $value ) {
				if ( $value['font-name'] ) {
					$fonts['Custom-Fonts'][$value['font-name']] = $value['font-name'];
				}
			}
		}

		return $fonts;
		
	}
	add_filter( 'redux/basel_options/field/typography/custom_fonts', 'basel_add_custom_fonts' );
}

// **********************************************************************//
// TODO: Need remove after 1-2 updates
// **********************************************************************//

if ( ! function_exists( 'basel_new_custom_fonts' ) ) {
	function basel_new_custom_fonts() {
		global $basel_options; 
		$old_fonts = array();
		if ( isset( $basel_options['font_custom_name'] ) && $basel_options['font_custom_name'] ) {
			$old_fonts[0] = array(
				'font-name' => $basel_options['font_custom_name'],
				'font-weight' => isset( $basel_options['font_custom_weight'] ) ? $basel_options['font_custom_weight'] : '',
				'font-woff' => isset( $basel_options['font_custom_woff']['url'] ) ? $basel_options['font_custom_woff']['url'] : '',
				'font-woff2' => isset( $basel_options['font_custom_woff2']['url'] ) ? $basel_options['font_custom_woff2']['url'] : '',
				'font-ttf' => isset( $basel_options['font_custom_ttf']['url'] ) ? $basel_options['font_custom_ttf']['url'] : '',
				'font-svg' => isset( $basel_options['font_custom_svg']['url'] ) ? $basel_options['font_custom_svg']['url'] : '',
				'font-eot' => isset( $basel_options['font_custom_eot']['url'] ) ? $basel_options['font_custom_eot']['url'] : '',
			);
		}

		if ( isset( $basel_options['font_custom2_name'] ) && $basel_options['font_custom2_name'] ) {
			$old_fonts[1] = array(
				'font-name' => $basel_options['font_custom2_name'],
				'font-weight' => isset( $basel_options['font_custom2_weight'] ) ? $basel_options['font_custom2_weight'] : '',
				'font-woff' => isset( $basel_options['font_custom2_woff']['url'] ) ? $basel_options['font_custom2_woff']['url'] : '',
				'font-woff2' => isset( $basel_options['font_custom2_woff2']['url'] ) ? $basel_options['font_custom2_woff2']['url'] : '',
				'font-ttf' => isset( $basel_options['font_custom2_ttf']['url'] ) ? $basel_options['font_custom2_ttf']['url'] : '',
				'font-svg' => isset( $basel_options['font_custom2_svg']['url'] ) ? $basel_options['font_custom2_svg']['url'] : '',
				'font-eot' => isset( $basel_options['font_custom2_eot']['url'] ) ? $basel_options['font_custom2_eot']['url'] : '',
			);
		}

		if ( isset( $basel_options['font_custom3_name'] ) && $basel_options['font_custom3_name'] ) {
			$old_fonts[2] = array(
				'font-name' => $basel_options['font_custom3_name'],
				'font-weight' => isset( $basel_options['font_custom3_weight'] ) ? $basel_options['font_custom3_weight'] : '',
				'font-woff' => isset( $basel_options['font_custom3_woff']['url'] ) ? $basel_options['font_custom3_woff']['url'] : '',
				'font-woff2' => isset( $basel_options['font_custom3_woff2']['url'] ) ? $basel_options['font_custom3_woff2']['url'] : '',
				'font-ttf' => isset( $basel_options['font_custom3_ttf']['url'] ) ? $basel_options['font_custom3_ttf']['url'] : '',
				'font-svg' => isset( $basel_options['font_custom3_svg']['url'] ) ? $basel_options['font_custom3_svg']['url'] : '',
				'font-eot' => isset( $basel_options['font_custom3_eot']['url'] ) ? $basel_options['font_custom3_eot']['url'] : '',
			);
		}

		if ( ! $old_fonts ) return;

		Redux::setOption('basel_options','multi_custom_fonts', $old_fonts);
		Redux::setOption('basel_options','font_custom_name', '');
		Redux::setOption('basel_options','font_custom2_name', '');
		Redux::setOption('basel_options','font_custom3_name', '');
	}
	add_filter( 'init', 'basel_new_custom_fonts' );
}

// **********************************************************************//
// Get current breadcrumbs
// **********************************************************************//

if ( ! function_exists( 'basel_current_breadcrumbs' ) ) {
	function basel_current_breadcrumbs( $type ) {
		$function = ( $type == 'shop' ) ? 'woocommerce_breadcrumb' : 'basel_breadcrumbs';

		if ( basel_get_opt( 'yoast_' . $type . '_breadcrumbs' ) && function_exists( 'yoast_breadcrumb' ) ) {
			if ( is_singular( 'product' ) && $type == 'shop' ) echo basel_back_btn();
			echo '<div class="yoast-breadcrumb">';
				echo yoast_breadcrumb();
			echo '</div>';
		} else {
			$function();
		}
	}
}

/*==============================================
=            Lazy loading functions            =
==============================================*/


// **********************************************************************// 
// Init lazy loading
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_loading_init' ) ) {
	function basel_lazy_loading_init() {
		if( ! basel_get_opt( 'lazy_loading' ) || is_admin() ) return;

		// Used for product categories images for example.
		add_filter('basel_attachment', 'basel_lazy_attachment_replace', 10, 3);

		// Used for instagram images.
		add_filter('basel_image', 'basel_lazy_image_standard', 10, 1);

		// Used for avatar images.
		add_filter( 'get_avatar', 'basel_lazy_avatar_image', 10 );

		// Images generated by WPBakery functions
		add_filter('vc_wpb_getimagesize', 'basel_lazy_image', 10, 3);

		// Products, blog, a lot of other standard wordpress images
		add_filter('wp_get_attachment_image_attributes', 'basel_lazy_attributes', 10, 3);

	}

	add_action( 'init', 'basel_lazy_loading_init', 120 );
}

// **********************************************************************// 
// Filters HTML <img> tag and adds lazy loading attributes. Used for avatar images.
// **********************************************************************// 
if ( ! function_exists( 'basel_lazy_avatar_image' ) ) {
	function basel_lazy_avatar_image( $html ) {

		if ( preg_match( "/src=['\"]data:image/is", $html ) ) return $html;

		$uploaded = basel_get_opt( 'lazy_custom_placeholder' );

		if ( $uploaded['url'] ) {
			$lazy_image = $uploaded['url'];
		} else {
			$lazy_image = basel_lazy_get_default_preview();
		}

		return basel_lazy_replace_image( $html, $lazy_image );
	}
}


// **********************************************************************// 
// Filters HTML <img> tag and adds lazy loading attributes. Used for product categories images for example.
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_attachment_replace' ) ) {
	function basel_lazy_attachment_replace( $imgHTML, $attach_id, $size ) {

		if ( preg_match( "/src=['\"]data:image/is", $imgHTML ) ) return $imgHTML;


		if( $attach_id ) {
			$lazy_image = basel_get_attachment_placeholder( $attach_id, $size );
		} else {
			$lazy_image = basel_lazy_get_default_preview();
		}


		return  basel_lazy_replace_image( $imgHTML, $lazy_image );
	}
}


// **********************************************************************// 
// Filters HTML <img> tag and adds lazy loading attributes. Used for instagram images.
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_image_standard' ) ) {
	function basel_lazy_image_standard( $html ) {

		if ( preg_match( "/src=['\"]data:image/is", $html ) ) return $html;

		$lazy_image = basel_lazy_get_default_preview();

		return basel_lazy_replace_image( $html, $lazy_image );
	}

}


// **********************************************************************// 
// Get default preview image.
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_get_default_preview' ) ) {
	function basel_lazy_get_default_preview() {
		return BASEL_IMAGES . '/lazy.png';
	}
}


// **********************************************************************// 
// Filters WPBakery generated image. Needs an HTML, its ID, and params with image size.
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_image' ) ) {
	function basel_lazy_image( $img, $attach_id, $params ) {

		$thumb_size = basel_get_image_size( $params['thumb_size'] );

		$imgHTML = $img['thumbnail'];

		if ( preg_match( "/src=['\"]data:image|basel-lazy-load/is", $imgHTML ) ) return $img;

		$lazy_image = basel_get_attachment_placeholder( $attach_id, $thumb_size );

		$img['thumbnail'] = basel_lazy_replace_image( $imgHTML, $lazy_image );

		return $img;
	}
}


// **********************************************************************// 
// Filters <img> tag passed as an argument.
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_replace_image' ) ) {
	function basel_lazy_replace_image( $html, $src ) {

		$class = basel_lazy_css_class();

		$new = '';
		$new = preg_replace( '/<img(.*?)src=/is', '<img$1src="'.$src.'" data-basel-src=', $html );
		$new = preg_replace( '/<img(.*?)srcset=/is', '<img$1srcset="" data-srcset=', $new );


		if ( preg_match( '/class=["\']/i', $new ) ) {
			$new = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1' . $class . ' $2$1', $new );
		} else {
			$new = preg_replace( '/<img/is', '<img class="' . $class . '"', $new );
		}

		return $new;
	}
}


// **********************************************************************// 
// Filters default WordPress images ATTRIBUTES array called by core API functions.
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_attributes' ) ) {
	function basel_lazy_attributes($attr, $attachment, $size) {

		$attr['data-basel-src'] = $attr['src'];
		if( isset( $attr['srcset'] ) ) $attr['data-srcset'] = $attr['srcset'];
		
		$attr['src'] = basel_get_attachment_placeholder( $attachment->ID, $size );
		$attr['srcset'] = '';

		$attr['class'] = $attr['class'] . ' ' . basel_lazy_css_class();


		return $attr;
	}
}


// **********************************************************************// 
// Get lazy loading image CSS class
// **********************************************************************// 
if( ! function_exists( 'basel_lazy_css_class' ) ) {
	function basel_lazy_css_class() {
		$class = 'basel-lazy-load';

		$class .= ' basel-lazy-' . basel_get_opt( 'lazy_effect' );

		return $class;
	}
}


// **********************************************************************// 
// Get placeholder image. Needs ID to genereate a blurred preview and size.
// **********************************************************************// 
if( ! function_exists( 'basel_get_attachment_placeholder' ) ) {
	function basel_get_attachment_placeholder( $id, $size ) {

		// Get size from array
		if( is_array( $size) ) {
			$width = $size[0];
			$height = $size[1];
		} else {
			// Take it from the original image
			$image = wp_get_attachment_image_src($id, $size);
			$width = $image[1];
			$height = $image[2];
		}

		$placeholder_size = basel_get_placeholder_size( $width, $height );

		$uploaded = basel_get_opt('lazy_custom_placeholder');

		$img = basel_lazy_get_default_preview();

		if( basel_get_opt( 'lazy_generate_previews' ) && function_exists( 'vc_get_image_by_size' ) ) {
			$img = vc_get_image_by_size( $id, $placeholder_size );
		} else if( ! empty( $uploaded ) && is_array( $uploaded ) && ! empty( $uploaded['url'] ) && ! empty( $uploaded['id'] ) ) {
			$img = $uploaded['url'];
			if( basel_get_opt( 'lazy_proprtion_size' ) && function_exists( 'vc_get_image_by_size' ) ) {
				$img = vc_get_image_by_size( $uploaded['id'], $width . 'x' . $height );
			}
		} else {
			return basel_lazy_get_default_preview();
		}

		if( basel_get_opt( 'lazy_base_64' ) ) $img = basel_encode_image($id, $img);

		return $img;
	}  
}


// **********************************************************************// 
// Encode small preview image to BASE 64
// **********************************************************************// 
if( ! function_exists( 'basel_encode_image' ) ) {
	function basel_encode_image( $id, $url ) {

		if( ! wp_attachment_is_image( $id ) || preg_match('/^data\:image/', $url ) ) return $url;

		$meta_key = '_base64_image.' . md5($url);
		
		$img_url = get_post_meta( $id, $meta_key, true );

		if( $img_url ) return $img_url;

		$image_path = preg_replace('/^.*?wp-content\/uploads\//i', '', $url);

		if( ( $uploads = wp_get_upload_dir() ) && ( false === $uploads['error'] ) && ( 0 !== strpos( $image_path, $uploads['basedir'] ) ) ) {
			if( false !== strpos( $image_path, 'wp-content/uploads' ) ) 
				$image_path = trailingslashit( $uploads['basedir'] . '/' . _wp_get_attachment_relative_path( $image_path ) ) . basename( $image_path );
			else 
				$image_path = $uploads['basedir'] . '/' . $image_path;
		}

		$max_size = 150 * 1024; // MB

		//echo '[['.$max_size.' vs '.filesize($image_path).']]';

		if( file_exists( $image_path ) && ( ! $max_size || ( filesize( $image_path ) <= $max_size ) ) ) {
			$filetype = wp_check_filetype( $image_path );

			// Read image path, convert to base64 encoding
			if ( function_exists( 'basel_compress' ) && function_exists( 'basel_get_file' ) ) {
				$imageData = basel_compress( basel_get_file( $image_path ) );
			} else {
				$imageData = '';
			}

			// Format the image SRC:  data:{mime};base64,{data};
			$img_url = 'data:image/' . $filetype['ext'] . ';base64,' . $imageData;

			update_post_meta( $id, $meta_key, $img_url );

			return $img_url;
		}

		return $url;
	}
} 


// **********************************************************************// 
// Generate placeholder preview small size.
// **********************************************************************// 
if( ! function_exists( 'basel_get_placeholder_size' ) ) {
	function basel_get_placeholder_size( $x0, $y0 ) {

		$x = $y = 10;

		if( $x0 < $y0) {
			$y = ($x * $y0) / $x0;
		}

		if( $x0 > $y0) {
			$x = ($y * $x0) / $y0;
		}

		$x = ceil( $x );
		$y = ceil( $y );

		return (int) $x . 'x' . (int) $y;
	}
}

/*=====  End of Lazy loading functions  ======*/


// **********************************************************************// 
// Is blog archive page
// **********************************************************************// 

if ( ! function_exists( 'basel_is_blog_archive' ) ) {
	function basel_is_blog_archive() {
		return ( is_home() || is_search() || is_tag() || is_category() || is_date() || is_author() );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Is shop on front page
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'basel_is_shop_on_front' ) ) {
	function basel_is_shop_on_front() {
		return function_exists( 'wc_get_page_id' ) && 'page' === get_option( 'show_on_front' ) && wc_get_page_id( 'shop' ) == get_option( 'page_on_front' );
	}
}

if ( ! function_exists( 'basel_get_allowed_html' ) ) {
	/**
	 * Return allowed html tags
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function basel_get_allowed_html() {
		return array(
			'br'     => array(),
			'i'      => array(),
			'b'      => array(),
			'u'      => array(),
			'em'     => array(),
			'a'      => array(
				'href'  => true,
				'class' => true,
				'title' => true,
				'rel'   => true,
			),
			'strong' => array(),
			'span'   => array(
				'style' => true,
				'class' => true,
			),
		);
	}
}

if ( ! function_exists( 'basel_core_outdated_message' ) ) {
	function basel_core_outdated_message() {
		if ( is_user_logged_in() && ! defined( 'BASEL_PLUGIN_POST_TYPE_VERSION' ) || ( defined( 'BASEL_PLUGIN_POST_TYPE_VERSION' ) && version_compare( BASEL_PLUGIN_POST_TYPE_VERSION, BASEL_POST_TYPE_VERSION, '<' ) ) ) {
			echo '<div class="basel-core-message">You just installed the latest version of the Basel theme. To finish the installation and enable all theme\'s function  or if you see any problems with your WPBakery elements displayed as shortcodes you need to install the latest version of the Basel Post Type plugin too. Go to <a href=' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '>Appearance -> Install plugins</a> and click on "Install" or "Update" button.</div>';	
		}
	}
	
	add_filter( 'wp_footer', 'basel_core_outdated_message' );
}

if ( ! function_exists( 'basel_dokan_lazy_load_fix' ) ) {
	function basel_dokan_lazy_load_fix(){
		return array(
			'img' => array(
				'alt'            => array(),
				'class'          => array(),
				'height'         => array(),
				'src'            => array(),
				'width'          => array(),
				'data-basel-src' => array(),
				'data-srcset'    => array(),
			),
		);
	}

	add_filter( 'dokan_product_image_attributes', 'basel_dokan_lazy_load_fix', 10 );
}



if ( ! function_exists( 'basel_clean' ) ) {
	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 * @return string|array
	 */
	function basel_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'basel_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}
