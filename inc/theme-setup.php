<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Set up base wordpress configuration (widgets, plugins, menus)
 * ------------------------------------------------------------------------------------------------
 */


/**
 * ------------------------------------------------------------------------------------------------
 * Set up the content width value based on theme's design.
 * ------------------------------------------------------------------------------------------------
 */
if( ! isset( $content_width ) ) {
	$content_width = 1200;
}


/**
 * Make the theme available for translations.
 */
$lang_dir = BASEL_THEMEROOT . '/languages';
load_theme_textdomain( 'basel', $lang_dir );


/**
 * ------------------------------------------------------------------------------------------------
 * Set up theme default and register various supported features
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_theme_setup' ) ) {
	function basel_theme_setup() {

		/**
		 * Add support for post formats
		 */
		add_theme_support( 'post-formats', 
			array(
				'video', 
				'audio', 
				'quote', 
				'image', 
				'gallery', 
				'link'
			) 
		);
	
		/**
		 * Add support for automatic feed links
		 */
		add_theme_support( 'automatic-feed-links' );	

		/**
		 * Add support for post thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add support for post title tag
		 */
		add_theme_support( "title-tag" );

		/**
		 * Register nav menus
		 */
		register_nav_menus( array(
			'main-menu' => esc_html__( 'Main Menu', 'basel' ),
			'mobile-menu' => esc_html__( 'Mobile Side Menu', 'basel' ),
			'top-bar-menu' => esc_html__( 'Top Bar Menu', 'basel' )
		) );

		 add_editor_style( get_template_directory_uri() . '/css/editor-style.css' );

	}

	add_action( 'after_setup_theme', 'basel_theme_setup' );
}


/**
 * ------------------------------------------------------------------------------------------------
 * Allow SVG logo
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'basel_allow_svg_upload' ) ) {
	add_filter( 'upload_mimes', 'basel_allow_svg_upload', 100, 1 );
	function basel_allow_svg_upload( $mimes ) {
		if ( basel_get_opt( 'allow_upload_svg' ) ) {
			$mimes['svg'] = 'image/svg+xml';
			$mimes['svgz'] = 'image/svg+xml';
		}
		$mimes['woff'] = 'font/woff';
		$mimes['woff2'] = 'font/woff2';
		$mimes['ttf'] = 'font/ttf';
		$mimes['eot'] = 'font/eot';
		return $mimes;
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Register the widget areas
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_widget_init' ) ) {
	function basel_widget_init() {
		if( function_exists( 'register_sidebar' ) ) {
			register_sidebar( 
				array(
					'name'          => esc_html__( 'Main Widget Area', 'basel' ),
					'id'            => 'sidebar-1',
					'description'   => esc_html__( 'Default Widget Area for posts and pages', 'basel' ),
					'class'         => '',
					'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget-title">',
					'after_title'   => '</h5>'
				)
			);
			if( basel_woocommerce_installed() ) {

				$filter_widget_class = basel_get_widget_column_class( 'filters-area' );

				register_sidebar( 
					array(
						'name'          => esc_html__( 'Shop page Widget Area', 'basel' ),
						'id'            => 'sidebar-shop',
						'description'   => esc_html__( 'Widget Area for shop pages', 'basel' ),
						'class'         => '',
						'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget-title">',
						'after_title'   => '</h5>'
					)
				);
				register_sidebar( 
					array(
						'name'          => esc_html__( 'Shop filters', 'basel' ),
						'id'            => 'filters-area',
						'description'   => esc_html__( 'Widget Area for shop filters above the products', 'basel' ),
						'class'         => '',
						'before_widget' => '<div id="%1$s" class="filter-widget ' . esc_attr( $filter_widget_class ) . ' %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget-title">',
						'after_title'   => '</h5>'
					)
				);
				register_sidebar( 
					array(
						'name'          => esc_html__( 'Single product page Widget Area', 'basel' ),
						'id'            => 'sidebar-product-single',
						'description'   => esc_html__( 'Widget Area for single product page', 'basel' ),
						'class'         => '',
						'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget-title">',
						'after_title'   => '</h5>'
					)
				);
				register_sidebar( 
					array(
						'name'          => esc_html__( 'My Account pages sidebar', 'basel' ),
						'id'            => 'sidebar-my-account',
						'description'   => esc_html__( 'Widget Area for My Account, orders and other user pages.', 'basel' ),
						'class'         => '',
						'before_widget' => '<div id="%1$s" class="sidebar-widget widget-my-account %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget-title">',
						'after_title'   => '</h5>'
					)
				);
			}

			register_sidebar( 
				array(
					'name'          => esc_html__( 'Widget Area in the header', 'basel' ),
					'id'            => 'header-widgets',
					'description'   => esc_html__( 'Widget Area for some header types. Will be displayed if "Text in the header" field is empty in Theme Options', 'basel' ),
					'class'         => '',
					'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h5 class="widget-title">',
					'after_title'   => '</h5>'
				)
			);

			$footer_layout = basel_get_opt( 'footer-layout' );

			$footer_classes = basel_get_opt( 'collapse_footer_widgets' ) ? ' footer-widget-collapse' : '';

			$footer_config = basel_get_footer_config( $footer_layout );

			if( count( $footer_config['cols'] ) > 0 ) {
				foreach ( $footer_config['cols'] as $key => $columns ) {
					$index = $key + 1;
					register_sidebar( 
						array(
							'name'          => 'Footer Column ' . $index,
							'id'            => 'footer-'.$index,
							'description'   => 'Footer column',
							'class'         => '',
							'before_widget' => '<div id="%1$s" class="footer-widget ' . esc_attr( $footer_classes ) . ' %2$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<h5 class="widget-title">',
							'after_title'   => '</h5>'
						)
					);
				}
			}
			
			$custom_sidebars = get_posts( array( 'post_type' => 'basel_sidebar', 'post_status'=>'publish', 'numberposts' => -1 ) );
			
			foreach ( $custom_sidebars as $sidebar ) {
				register_sidebar( 
					array(
						'name'          => $sidebar->post_title,
						'id'            => 'sidebar-' . $sidebar->ID,
						'description'   => '',
						'class'         => '',
						'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h5 class="widget-title">',
						'after_title'   => '</h5>'
					)
				);
			}
		}
	}

	add_action( 'widgets_init', 'basel_widget_init' );
}


/**
 * ------------------------------------------------------------------------------------------------
 * Register plugins necessary for theme
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'basel_register_required_plugins' ) ) {
	function basel_register_required_plugins() {

	    $plugins = array(

	        // This is an example of how to include a plugin pre-packaged with a theme.

	        array(
	            'name'      => 'Theme Options Framework',
	            'slug'      => 'redux-framework',
	            'required'  => true,
	        ),
	        array(
	            'name'      => 'Custom metaboxes Framework',
	            'slug'      => 'cmb2',
	            'required'  => true,
	        ),
	        array(
	            'name'               => 'WPBakery Page Builder', // The plugin name.
	            'slug'               => 'js_composer', // The plugin slug (typically the folder name).
	            'source'             => BASEL_PLUGINS_URL . 'js_composer.zip', // The plugin source.
	            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
	            'version'            => '6.0.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
	            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
	            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
	            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
	        ),
	        array(
	            'name'               => 'Slider Revolution', // The plugin name.
	            'slug'               => 'revslider', // The plugin slug (typically the folder name).
	            'source'             => BASEL_PLUGINS_URL . 'revslider.zip', // The plugin source.
	            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
	            'version'            => '5.4.8.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
	            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
	            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
	            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
	        ),

	        array(
	            'name'               => 'Theme Post Types', // The plugin name.
	            'slug'               => 'basel-post-types', // The plugin slug (typically the folder name).
	            'source'             => BASEL_FRAMEWORK . '/plugins/basel-post-types.zip', // The plugin source.
	            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
	            'version'            => BASEL_POST_TYPE_VERSION, // E.g. 1.0.0. If set, the active plugin must be this version or higher.
	            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
	            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
	            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
	        ),

	        array(
	            'name'               => 'Twitter Widget', // The plugin name.
	            'slug'               => 'simple-twitter-tweets', // The plugin slug (typically the folder name).
	            'source'             => BASEL_PLUGINS_URL . 'simple-twitter-tweets.zip', // The plugin source.
	            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
	            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
	            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
	        ),

	        array(
	            'name'               => 'Recent posts widget', // The plugin name.
	            'slug'               => 'recent-posts-widget-extended', // The plugin slug (typically the folder name).
	            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
	        ),

	        array(
	            'name'      => 'WooCommerce',
	            'slug'      => 'woocommerce',
	            'required'  => false,
	        ),

	        array(
	            'name'      => 'YITH WooCommerce Wishlist',
	            'slug'      => 'yith-woocommerce-wishlist',
	            'required'  => false,
	        ),

	        array(
	            'name'      => 'Contact Form 7',
	            'slug'      => 'contact-form-7',
	            'required'  => false,
	        ),

	        array(
	            'name'      => 'MailChimp for WordPress',
	            'slug'      => 'mailchimp-for-wp',
	            'required'  => false,
			),
			
			array(
	            'name'      => 'Safe SVG',
	            'slug'      => 'safe-svg',
	            'required'  => false,
	        ),

	    );

	    $config = array(
	        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
	        'menu'         => 'tgmpa-install-plugins', // Menu slug.
	        'has_notices'  => true,                    // Show admin notices or not.
	        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
	        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
	        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
	        'message'      => '',                      // Message to output right before the plugins table.
	        'strings'      => array(
	            'page_title'                      => esc_html__( 'Install Required Plugins', 'basel' ),
	            'menu_title'                      => esc_html__( 'Install Plugins', 'basel' ),
	            'installing'                      => esc_html__( 'Installing Plugin: %s', 'basel' ), // %s = plugin name.
	            'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'basel' ),
	            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'basel' ), // %1$s = plugin name(s).
	            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'basel' ), // %1$s = plugin name(s).
	            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'basel' ), // %1$s = plugin name(s).
	            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'basel' ), // %1$s = plugin name(s).
	            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'basel' ), // %1$s = plugin name(s).
	            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'basel' ), // %1$s = plugin name(s).
	            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'basel' ), // %1$s = plugin name(s).
	            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'basel' ), // %1$s = plugin name(s).
	            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'basel' ),
	            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'basel' ),
	            'return'                          => esc_html__( 'Return to Required Plugins Installer', 'basel' ),
	            'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'basel' ),
	            'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'basel' ), // %s = dashboard link.
	            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
	        )
	    );

	    tgmpa( $plugins, $config );

	}

	add_action( 'tgmpa_register', 'basel_register_required_plugins' );
}
