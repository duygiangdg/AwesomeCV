<?php
/**
 *
 * The framework's functions and definitions
 *
 */

/**
 * ------------------------------------------------------------------------------------------------
 * Define constants.
 * ------------------------------------------------------------------------------------------------
 */
define( 'BASEL_THEME_DIR', 		get_template_directory_uri() );
define( 'BASEL_THEMEROOT', 		get_template_directory() );
define( 'BASEL_IMAGES', 		BASEL_THEME_DIR . '/images' );
define( 'BASEL_SCRIPTS', 		BASEL_THEME_DIR . '/js' );
define( 'BASEL_STYLES', 		BASEL_THEME_DIR . '/css' );
define( 'BASEL_FRAMEWORK', 		BASEL_THEMEROOT . '/inc' );
define( 'BASEL_DUMMY', 			BASEL_THEME_DIR . '/inc/dummy-content' );
define( 'BASEL_CLASSES', 		BASEL_THEMEROOT . '/inc/classes' );
define( 'BASEL_CONFIGS', 		BASEL_THEMEROOT . '/inc/configs' );
define( 'BASEL_3D', 			BASEL_FRAMEWORK . '/third-party' );
define( 'BASEL_ASSETS', 		BASEL_THEME_DIR . '/inc/assets' );
define( 'BASEL_ASSETS_IMAGES', 	BASEL_ASSETS    . '/images' );
define( 'BASEL_API_URL', 		'https://xtemos.com/licenses/api/' );
define( 'BASEL_DEMO_URL', 		'https://demo.xtemos.com/basel/' );
define( 'BASEL_PLUGINS_URL', 	'https://woodmart.xtemos.com/plugins/');
define( 'BASEL_DUMMY_URL', 		BASEL_DEMO_URL . 'dummy-content/');
define( 'BASEL_SLUG', 			'basel' );
define( 'BASEL_POST_TYPE_VERSION', '1.9' );

/**
 * ------------------------------------------------------------------------------------------------
 * Load all CORE Classes and files
 * ------------------------------------------------------------------------------------------------
 */
require_once( apply_filters('basel_require', BASEL_FRAMEWORK . '/autoload.php') );

$basel_theme = new BASEL_Theme();

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue styles
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_enqueue_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'basel_enqueue_styles', 10000 );

	function basel_enqueue_styles() {
		$version = basel_get_theme_info( 'Version' );

		if( basel_get_opt( 'minified_css' ) ) {
			$main_css_url = get_template_directory_uri() . '/style.min.css';
		} else {
			$main_css_url = get_stylesheet_uri();
		}

		wp_dequeue_style( 'yith-wcwl-font-awesome' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css-theme' );
		wp_enqueue_style( 'font-awesome-css', BASEL_STYLES . '/font-awesome.min.css', array(), $version );
		wp_enqueue_style( 'bootstrap', BASEL_STYLES . '/bootstrap.min.css', array(), $version );
		wp_enqueue_style( 'basel-style', $main_css_url, array( 'bootstrap' ), $version );
		wp_enqueue_style( 'js_composer_front', false, array(), $version );
		
		// load typekit fonts
		$typekit_id = basel_get_opt( 'typekit_id' );

		if ( $typekit_id ) {
			wp_enqueue_style( 'basel-typekit', 'https://use.typekit.net/' . esc_attr ( $typekit_id ) . '.css', array(), $version );
		}

		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue scripts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_enqueue_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'basel_enqueue_scripts', 10000 );

	function basel_enqueue_scripts() {
		
		$version = basel_get_theme_info( 'Version' );
		
		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply', false, array(), $version );
		
		wp_register_script( 'maplace', basel_get_script_url( 'maplace-0.1.3' ), array('jquery', 'google.map.api'), $version, true );
		
		if( ! basel_woocommerce_installed() )
			wp_register_script( 'js-cookie', basel_get_script_url( 'js.cookie' ), array('jquery'), $version, true );

		wp_enqueue_script( 'basel_html5shiv', basel_get_script_url( 'html5' ), array(), $version );
		wp_script_add_data( 'basel_html5shiv', 'conditional', 'lt IE 9' );

		wp_dequeue_script( 'flexslider' );
		wp_dequeue_script( 'photoswipe-ui-default' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_style( 'photoswipe-default-skin' );

		if( basel_get_opt( 'image_action' ) != 'zoom' ) {
			wp_dequeue_script( 'zoom' );
		}

		wp_enqueue_script( 'isotope', basel_get_script_url( 'isotope.pkgd' ), array( 'jquery' ), $version, true );
		wp_enqueue_script( 'wpb_composer_front_js' );

		if( basel_get_opt( 'combined_js' ) ) {
			wp_enqueue_script( 'basel-theme', basel_get_script_url( 'theme' ), array( 'jquery', 'js-cookie' ), $version, true );
		} else {
			wp_enqueue_script( 'basel-magnific-popup', basel_get_script_url( 'jquery.magnific-popup' ), array(), $version, true );
			wp_enqueue_script( 'basel-owl-carousel', basel_get_script_url( 'owl.carousel' ), array(), $version, true );
			wp_enqueue_script( 'basel-photoswipe', basel_get_script_url( 'photoswipe' ), array(), $version, true );
			wp_enqueue_script( 'basel-photoswipe-ui-default', basel_get_script_url( 'photoswipe-ui-default' ), array(), $version, true );
			wp_enqueue_script( 'basel-slick', basel_get_script_url( 'slick' ), array(), $version, true );
			wp_enqueue_script( 'basel-justified-gallery', basel_get_script_url( 'jquery.justifiedGallery' ), array(), $version, true );
			wp_enqueue_script( 'basel-imagesloaded', basel_get_script_url( 'imagesloaded.pkgd' ), array(), $version, true );
			wp_enqueue_script( 'basel-pjax', basel_get_script_url( 'jquery.pjax' ), array(), $version, true );
			wp_enqueue_script( 'basel-countdown', basel_get_script_url( 'jquery.countdown' ), array(), $version, true );
			wp_enqueue_script( 'basel-packery', basel_get_script_url( 'packery-mode.pkgd' ), array(), $version, true );
			wp_enqueue_script( 'basel-autocomplete', basel_get_script_url( 'jquery.autocomplete' ), array(), $version, true );
			wp_enqueue_script( 'basel-threesixty', basel_get_script_url( 'threesixty' ), array(), $version, true );
			wp_enqueue_script( 'basel-tween-max', basel_get_script_url( 'TweenMax' ), array(), $version, true );
			wp_enqueue_script( 'basel-nanoscroller', basel_get_script_url( 'jquery.nanoscroller' ), array(), $version, true );
			wp_enqueue_script( 'basel-panr', basel_get_script_url( 'jquery.panr' ), array(), $version, true );
			wp_enqueue_script( 'basel-parallax', basel_get_script_url( 'jquery.parallax' ), array(), $version, true );
			wp_enqueue_script( 'basel-vivus', basel_get_script_url( 'vivus' ), array(), $version, true );
			wp_enqueue_script( 'basel-moment', basel_get_script_url( 'moment' ), array(), $version, true );
			wp_enqueue_script( 'basel-moment-timezone', basel_get_script_url( 'moment-timezone-with-data' ), array(), $version, true );
			wp_enqueue_script( 'basel-fastclick', basel_get_script_url( 'fastclick' ), array(), $version, true );
			wp_enqueue_script( 'basel-parallax-scroll', basel_get_script_url( 'jquery.parallax-scroll' ), array(), $version, true );
			wp_enqueue_script( 'basel-device', basel_get_script_url( 'device' ), array( 'jquery' ), $version, true );
			wp_enqueue_script( 'basel-waypoints', basel_get_script_url( 'waypoints' ), array( 'jquery' ), $version, true );

			$minified = basel_get_opt( 'minified_js' ) ? '.min' : '';
			wp_enqueue_script( 'basel-functions', BASEL_SCRIPTS . '/functions' . $minified . '.js', array( 'jquery', 'js-cookie' ), $version, true );
		}

		// Add virations form scripts through the site to make it work on quick view
		if( basel_get_opt( 'quick_view_variable' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation', false, array(), $version );
		}

		$translations = array(
			'adding_to_cart' => esc_html__('Processing', 'basel'),
			'added_to_cart' => esc_html__('Product was successfully added to your cart.', 'basel'),
			'continue_shopping' => esc_html__('Continue shopping', 'basel'),
			'view_cart' => esc_html__('View Cart', 'basel'),
			'go_to_checkout' => esc_html__('Checkout', 'basel'),
			'countdown_days' => esc_html__('days', 'basel'),
			'countdown_hours' => esc_html__('hr', 'basel'),
			'countdown_mins' => esc_html__('min', 'basel'),
			'countdown_sec' => esc_html__('sc', 'basel'),
			'loading' => esc_html__('Loading...', 'basel'),
			'close' => esc_html__('Close (Esc)', 'basel'),
			'share_fb' => esc_html__('Share on Facebook', 'basel'),
			'pin_it' => esc_html__('Pin it', 'basel'),
			'tweet' => esc_html__('Tweet', 'basel'),
			'download_image' => esc_html__('Download image', 'basel'),
			'wishlist' => ( class_exists( 'YITH_WCWL' ) ) ? 'yes' : 'no',
			'cart_url' => ( basel_woocommerce_installed() ) ?  esc_url( wc_get_cart_url() ) : '',
			'ajaxurl' => admin_url('admin-ajax.php'),
			'add_to_cart_action' => ( basel_get_opt( 'add_to_cart_action' ) ) ? esc_js( basel_get_opt( 'add_to_cart_action' ) ) : 'widget',
			'categories_toggle' => ( basel_get_opt( 'categories_toggle' ) ) ? 'yes' : 'no',
			'enable_popup' => ( basel_get_opt( 'promo_popup' ) ) ? 'yes' : 'no',
			'popup_delay' => ( basel_get_opt( 'promo_timeout' ) ) ? (int) basel_get_opt( 'promo_timeout' ) : 1000,
			'popup_event' => basel_get_opt( 'popup_event' ),
			'popup_scroll' => ( basel_get_opt( 'popup_scroll' ) ) ? (int) basel_get_opt( 'popup_scroll' ) : 1000,
			'popup_pages' => ( basel_get_opt( 'popup_pages' ) ) ? (int) basel_get_opt( 'popup_pages' ) : 0,
			'promo_popup_hide_mobile' => ( basel_get_opt( 'promo_popup_hide_mobile' ) ) ? 'yes' : 'no',
			'product_images_captions' => ( basel_get_opt( 'product_images_captions' ) ) ? 'yes' : 'no',
			'all_results' => esc_html__('View all results', 'basel'),
			'product_gallery' => basel_get_product_gallery_settings(),
			'zoom_enable' => ( basel_get_opt( 'image_action' ) == 'zoom') ? 'yes' : 'no',
			'ajax_scroll' => ( basel_get_opt( 'ajax_scroll' ) ) ? 'yes' : 'no',
			'ajax_scroll_class' => apply_filters( 'basel_ajax_scroll_class' , '.main-page-wrapper' ),
			'ajax_scroll_offset' => apply_filters( 'basel_ajax_scroll_offset' , 100 ),
			'product_slider_auto_height' => ( basel_get_opt( 'product_slider_auto_height' ) ) ? 'yes' : 'no',
			'product_slider_autoplay' => apply_filters( 'basel_product_slider_autoplay' , false ),
			'ajax_add_to_cart' => ( apply_filters( 'basel_ajax_add_to_cart', true ) ) ? basel_get_opt( 'single_ajax_add_to_cart' ) : false,
			'cookies_version' => ( basel_get_opt( 'cookies_version' ) ) ? (int)basel_get_opt( 'cookies_version' ) : 1,
			'header_banner_version' => ( basel_get_opt( 'header_banner_version' ) ) ? (int)basel_get_opt( 'header_banner_version' ) : 1,
			'header_banner_close_btn' => basel_get_opt( 'header_close_btn' ),
			'header_banner_enabled' => basel_get_opt( 'header_banner' ),
			'promo_version' => ( basel_get_opt( 'promo_version' ) ) ? (int)basel_get_opt( 'promo_version' ) : 1,
			'pjax_timeout' => apply_filters( 'basel_pjax_timeout' , 5000 ),
			'split_nav_fix' => apply_filters( 'basel_split_nav_fix' , false ),
			'shop_filters_close' => basel_get_opt( 'shop_filters_close' ) ? 'yes' : 'no',
			'sticky_desc_scroll' => apply_filters( 'basel_sticky_desc_scroll', true ),
			'quickview_in_popup_fix' => apply_filters( 'quickview_in_popup_fix', false ),
			'one_page_menu_offset' => apply_filters( 'basel_one_page_menu_offset', 150 ),
			'is_multisite' => is_multisite(),
			'current_blog_id' => get_current_blog_id(),
			'swatches_scroll_top_desktop' => basel_get_opt( 'swatches_scroll_top_desktop' ),
			'swatches_scroll_top_mobile' => basel_get_opt( 'swatches_scroll_top_mobile' ),
			'lazy_loading_offset' => basel_get_opt( 'lazy_loading_offset' ),
			'add_to_cart_action_timeout' => basel_get_opt( 'add_to_cart_action_timeout' ) ? 'yes' : 'no',
			'add_to_cart_action_timeout_number' => basel_get_opt( 'add_to_cart_action_timeout_number' ),
		);

		$basel_core = array(
			esc_html__( 'You are now logged in as <strong>%s</strong>', 'basel' ),
			esc_html__( 'Basel Slider', 'basel' ),
			esc_html__( 'Slide', 'basel' ),
			esc_html__( 'Slides', 'basel' ),
			esc_html__( 'Parent Item:', 'basel' ),
			esc_html__( 'All Items', 'basel' ),
			esc_html__( 'View Item', 'basel' ),
			esc_html__( 'Add New Item', 'basel' ),
			esc_html__( 'Add New', 'basel' ),
			esc_html__( 'Edit Item', 'basel' ),
			esc_html__( 'Update Item', 'basel' ),
			esc_html__( 'Search Item', 'basel' ),
			esc_html__( 'Not found', 'basel' ),
			esc_html__( 'Not found in Trash', 'basel' ),
			esc_html__( 'Sliders', 'basel' ),
			esc_html__( 'Slider', 'basel' ),
			esc_html__( 'Search Sliders', 'basel' ),
			esc_html__( 'Popular Sliders', 'basel' ),
			esc_html__( 'All Sliders', 'basel' ),
			esc_html__( 'Parent Slider', 'basel' ),
			esc_html__( 'Parent Slider', 'basel' ),
			esc_html__( 'Edit Slider', 'basel' ),
			esc_html__( 'Update Slider', 'basel' ),
			esc_html__( 'Add New Slider', 'basel' ),
			esc_html__( 'New Slide', 'basel' ),
			esc_html__( 'Add or remove Sliders', 'basel' ),
			esc_html__( 'Choose from most used sliders', 'basel' ),
			esc_html__( 'Title', 'basel' ),
			esc_html__( 'Date', 'basel' ),
			esc_html__( 'Size Guide', 'basel' ),
			esc_html__( 'Size Guides', 'basel' ),
			esc_html__( 'Add new', 'basel' ),
			esc_html__( 'Add new size guide', 'basel' ),
			esc_html__( 'New size guide', 'basel' ),
			esc_html__( 'Edit size guide', 'basel' ),
			esc_html__( 'View size guide', 'basel' ),
			esc_html__( 'All size guides', 'basel' ),
			esc_html__( 'Search size guides', 'basel' ),
			esc_html__( 'No size guides found.', 'basel' ),
			esc_html__( 'No size guides found in trash.', 'basel' ),
			esc_html__( 'Size guide to place in your products', 'basel' ),
			esc_html__( 'HTML Block', 'basel' ),
			esc_html__( 'HTML Blocks', 'basel' ),
			esc_html__( 'CMS Blocks for custom HTML to place in your pages', 'basel' ),
			esc_html__( 'Shortcode', 'basel' ),	   
			esc_html__( 'Sidebar', 'basel' ),
			esc_html__( 'Sidebars', 'basel' ),
			esc_html__( 'You can create additional custom sidebar and use them in Visual Composer', 'basel' ),
			esc_html__( 'Portfolio', 'basel' ),
			esc_html__( 'Project', 'basel' ),
			esc_html__( 'Projects', 'basel' ),
			esc_html__( 'portfolio', 'basel' ),
			esc_html__( 'Project Categories', 'basel' ),
			esc_html__( 'Project Category', 'basel' ),
			esc_html__( 'Search Categories', 'basel' ),
			esc_html__( 'Popular Project Categories', 'basel' ),
			esc_html__( 'All Project Categories', 'basel' ),
			esc_html__( 'Parent Category', 'basel' ),
			esc_html__( 'Parent Category', 'basel' ),
			esc_html__( 'Edit Category', 'basel' ),
			esc_html__( 'Update Category', 'basel' ),
			esc_html__( 'Add New Category', 'basel' ),
			esc_html__( 'New Category', 'basel' ),
			esc_html__( 'Add or remove Categories', 'basel' ),
			esc_html__( 'Choose from most used text-domain', 'basel' ),
			esc_html__( 'Category', 'basel' ),
			esc_html__( 'Categories', 'basel' ), 
		);

		wp_localize_script( 'basel-functions', 'basel_settings', $translations );
		wp_localize_script( 'basel-theme', 'basel_settings', $translations );
		
		if( ( is_home() || is_singular( 'post' ) || is_archive() ) && basel_get_opt('blog_design') == 'masonry' ) {
			// Load masonry script JS for blog
			wp_enqueue_script( 'masonry', false, array(), $version );
		}

	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue google fonts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_enqueue_google_fonts' ) ) {
	add_action( 'wp_enqueue_scripts', 'basel_enqueue_google_fonts', 10000 );

	function basel_enqueue_google_fonts() {
		$default_google_fonts = 'Karla:400,400italic,700,700italic|Lora:400,400italic,700,700italic';

		if( ! class_exists('Redux') )
   			wp_enqueue_style( 'basel-google-fonts', basel_get_fonts_url( $default_google_fonts ), array(), basel_get_theme_info( 'Version' ) );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get google fonts URL
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_get_fonts_url') ) {
	function basel_get_fonts_url( $fonts ) {
	    $font_url = '';

        $font_url = add_query_arg( 'family', urlencode( $fonts ), "//fonts.googleapis.com/css" );

	    return $font_url;
	}
}

function basel_lazy_loading_init( $force_init = false ) {
	if ( ( ( ! basel_get_opt( 'lazy_loading' ) || is_admin() ) && ! $force_init ) ) {
		return;
	}

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

function basel_lazy_loading_deinit( $force_deinit = false ) {
	if ( basel_get_opt( 'lazy_loading' ) && ! $force_deinit ) {
		return;
	}

	remove_action( 'basel_attachment', 'basel_lazy_attachment_replace', 10, 3) ;
	remove_action( 'get_avatar', 'basel_lazy_avatar_image', 10 );
	remove_action( 'basel_image', 'basel_lazy_image_standard', 10, 1 );
	remove_action( 'vc_wpb_getimagesize', 'basel_lazy_image', 10, 3 );
	remove_action( 'wp_get_attachment_image_attributes', 'basel_lazy_attributes', 10, 3 );
}

/**
 * Fix Woocommerce email with lazy load
 */
if ( ! function_exists( 'basel_stop_lazy_loading_before_order_table' ) ) {
	function basel_stop_lazy_loading_before_order_table() {
		basel_lazy_loading_deinit( true );
	}

	add_action( 'woocommerce_email_before_order_table', 'basel_stop_lazy_loading_before_order_table', 20 );
}


if ( ! function_exists( 'basel_start_lazy_loading_before_order_table' ) ) {
	function basel_start_lazy_loading_before_order_table() {
		basel_lazy_loading_init( true );
	}

	add_action( 'woocommerce_email_after_order_table', 'basel_start_lazy_loading_before_order_table', 20 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get script URL
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_get_script_url') ) {
	function basel_get_script_url( $script_name ) {
	    return BASEL_SCRIPTS . '/' . $script_name . '.min.js';
	}
}
