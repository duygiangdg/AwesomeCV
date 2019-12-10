<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Init Theme Settings and Options with Redux plugin
 * ------------------------------------------------------------------------------------------------
 */

	if ( ! class_exists( 'Redux' ) ) {
		return;
	}

	$opt_name = 'basel_options';

	$basel_selectors = basel_get_config( 'selectors' );

	$args = array(
		'opt_name'             => $opt_name,
		'display_name'         => basel_get_theme_info( 'Name' ),
		'display_version'      => basel_get_theme_info( 'Version' ),
		'menu_type'            => 'menu',
		'allow_sub_menu'       => true,
		'menu_title'           => esc_html__( 'Theme Settings', 'basel' ),
		'page_title'           => esc_html__( 'Theme Settings', 'basel' ),
		'google_api_key'       => '',
		'google_update_weekly' => false,
		'async_typography'     => false,
		'admin_bar'            => true,
		'admin_bar_icon'       => 'dashicons-portfolio',
		'admin_bar_priority'   => 50,
		'global_variable'      => '',
		'dev_mode'             => false,
		'update_notice'        => true,
		'customizer'           => true,
		'page_priority'        => 61,
		'page_parent'          => 'themes.php',
		'page_permissions'     => 'manage_options',
		'menu_icon'            => BASEL_ASSETS . '/images/theme-admin-icon.svg', 
		'last_tab'             => '',
		'page_icon'            => 'icon-themes',
		'page_slug'            => '_options',
		'save_defaults'        => true,
		'show_options_object'  => false,
		'default_show'         => false,
		'default_mark'         => '',
		'show_import_export'   => true,
		'transient_time'       => 60 * MINUTE_IN_SECONDS,
		'output'               => true,
		'output_tag'           => true,
		'footer_credit'        =>  '1.0',                  
		'database'             => '',
		'system_info'          => false,
		'hints'                => array(
			'icon'          => 'el el-question-sign',
			'icon_position' => 'right',
			'icon_color'    => 'lightgray',
			'icon_size'     => 'normal',
			'tip_style'     => array(
				'color'   => 'light',
				'shadow'  => true,
				'rounded' => false,
				'style'   => '',
			),
			'tip_position'  => array(
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array(
				'show' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'mouseover',
				),
				'hide' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'click mouseleave',
				),
			),
		)
	);

	Redux::setArgs( $opt_name, $args );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('General', 'basel'), 
		'id' => 'general',
		'icon' => 'el-icon-home',
		'fields' => array (
			array (
				'id' => 'favicon',
				'type' => 'media',
				'desc' => esc_html__('Upload image: png, ico', 'basel'), 
				'operator' => 'and',
				'title' => esc_html__('Favicon image', 'basel'), 
			),
			array (
				'id' => 'favicon_retina',
				'type' => 'media',
				'desc' => esc_html__('Upload image: png, ico', 'basel'), 
				'operator' => 'and',
				'title' => esc_html__('Favicon retina image', 'basel'), 
			),
			array (
				'id'       => 'page_comments',
				'type'     => 'switch',
				'title'    => esc_html__('Show comments on page', 'basel'), 
				'default' => true
			),
			array (
				'id'       => 'google_map_api_key',
				'type'     => 'text',
				'title'    => esc_html__('Google map API key', 'basel'), 
				'subtitle' => wp_kses( __('Obrain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.', 'basel'), array( 'a' => array( 'href' => true, 'target' => true, ), 'br' => array(), 'strong' => array() ) ),
				'tags'     => 'google api key'
			),
			array (
				'id'       => 'search_post_type',
				'type'     => 'select',
				'title'    => esc_html__('Search post type', 'basel'), 
				'subtitle' => esc_html__('You can set up site search for posts or for products (woocommerce)', 'basel'),
				'options'  => array(
					'product' => esc_html__('Product', 'basel'), 
					'post' => esc_html__('Post', 'basel'), 
				),
				'default' => 'product'
			),
			array (
				'id'       => 'search_by_sku',
				'type'     => 'switch',
				'title'    => esc_html__('Search by product SKU', 'basel'), 
				'default' => false
			),
            array (
                'id' => 'custom_404_page',
                'type' => 'select',
                'title' => esc_html__( 'Custom 404 page', 'basel' ),
                'subtitle' => esc_html__( 'You can make your custom 404 page', 'basel' ),
                'options' => basel_get_pages(),
                'default' => 'default',
            ),
		),
	) );

	Redux::setSection(
		$opt_name,
		array(
			'title'      => esc_html__( 'Mobile bottom navbar', 'basel' ),
			'id'         => 'sticky_toolbar_section',
			'subsection' => true,
			'fields'     => array(
				array(
					'id'       => 'sticky_toolbar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Enable Sticky navbar', 'basel' ),
					'subtitle' => esc_html__( 'Sticky navigation toolbar will be shown at the bottom on mobile devices.', 'basel' ),
					'default'  => false,
				),
				array(
					'id'       => 'sticky_toolbar_fields',
					'type'     => 'sorter',
					'title'    => esc_html__( 'Select buttons', 'basel' ),
					'subtitle' => esc_html__( 'Choose which buttons will be used for sticky navbar.', 'basel' ),
					'options'  => basel_get_sticky_toolbar_fields(),
				),
				array(
					'id'       => 'sticky_toolbar_label',
					'type'     => 'switch',
					'title'    => esc_html__( 'Navbar labels', 'basel' ),
					'subtitle' => esc_html__( 'Show/hide labels under icons in the mobile navbar.', 'basel' ),
					'default'  => true,
				),

				array (
					'id'         => 'link_1_title',
					'type'       => 'basel_title',
					'wood-title' => esc_html__( 'Custom button [1]', 'basel' ),
				),
				array (
					'id'       => 'link_1_url',
					'type'     => 'text',
					'title'    => esc_html__( 'Custom button URL', 'basel' ),
				),
				array (
					'id'       => 'link_1_text',
					'type'     => 'text',
					'title'    => esc_html__( 'Custom button text', 'basel' ),
				),
				array (
					'id'       => 'link_1_icon',
					'type'     => 'media',
					'title'    => esc_html__( 'Custom button icon', 'basel' ),
				),

				array (
					'id'         => 'link_2_title',
					'type'       => 'basel_title',
					'wood-title' => esc_html__( 'Custom button [2]', 'basel' ),
				),
				array (
					'id'       => 'link_2_url',
					'type'     => 'text',
					'title'    => esc_html__( 'Custom button URL', 'basel' ),
				),
				array (
					'id'       => 'link_2_text',
					'type'     => 'text',
					'title'    => esc_html__( 'Custom button text', 'basel' ),
				),
				array (
					'id'       => 'link_2_icon',
					'type'     => 'media',
					'title'    => esc_html__( 'Custom button icon', 'basel' ),
				),

				array (
					'id'         => 'link_3_title',
					'type'       => 'basel_title',
					'wood-title' => esc_html__( 'Custom button [3]', 'basel' ),
				),
				array (
					'id'       => 'link_3_url',
					'type'     => 'text',
					'title'    => esc_html__( 'Custom button URL', 'basel' ),
				),
				array (
					'id'       => 'link_3_text',
					'type'     => 'text',
					'title'    => esc_html__( 'Custom button text', 'basel' ),
				),
				array (
					'id'       => 'link_3_icon',
					'type'     => 'media',
					'title'    => esc_html__( 'Custom button icon', 'basel' ),
				),
			),
		)
	);

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('General Layout', 'basel'), 
		'id' => 'layout',
		'icon' => 'el-icon-website',
		'fields' => array (
			array (
				'id'       => 'site_width',
				'type'     => 'select',
				'title'    => esc_html__('Site width', 'basel'), 
				'subtitle' => esc_html__('You can make your content wrapper boxed or full width', 'basel'),
				'options'  => array(
					'full-width' => esc_html__('Full width', 'basel'), 
					'boxed' => esc_html__('Boxed', 'basel'), 
					'full-width-content' => esc_html__('Content full width', 'basel'), 
					'wide' => esc_html__('Wide (1600 px)', 'basel'), 
				),
				'default' => 'full-width',
				'tags'     => 'boxed full width wide'
			),
			array (
				'id'       => 'main_layout',
				'type'     => 'image_select',
				'title'    => esc_html__('Main Layout', 'basel'), 
				'subtitle' => esc_html__('Select main content and sidebar alignment.', 'basel'),
				'options'  => array(
					'full-width'      => array(
						'alt'   => '1 Column', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/none.png'
					),
					'sidebar-left'      => array(
						'alt'   => '2 Column Left', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/left.png'
					),
					'sidebar-right'      => array(
						'alt'   => '2 Column Right', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/right.png'
					),
				),
				'default' => 'sidebar-right',
				'tags'     => 'sidebar left sidebar right'
			),
			array (
                'id'       => 'hide_main_sidebar_mobile',
                'type'     => 'switch',
                'title'    => esc_html__( 'Off canvas sidebar for mobile', 'basel' ),
                'subtitle' => esc_html__( 'You can can hide sidebar and show nicely on button click on the page.', 'basel' ),
                'default' => true,
            ),
			array (
				'id'       => 'sidebar_width',
				'type'     => 'button_set',
				'title'    => esc_html__('Sidebar size', 'basel'), 
				'subtitle' => esc_html__('You can set different sizes for your pages sidebar', 'basel'),
				'options'  => array(
					2 => esc_html__('Small', 'basel'), 
					3 => esc_html__('Medium', 'basel'),
					4 => esc_html__('Large', 'basel'),
				),
				'default' => 3,
				'tags'     => 'small sidebar large sidebar'
			),
			array (         
				'id'       => 'body-background',
				'type'     => 'background',
				'title'    => esc_html__('Site background', 'basel'),
				'subtitle' => esc_html__('Set background image or color for body. Only for boxed layout', 'basel'),
				'output'   => array('body, .basel-dark .main-page-wrapper')
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Page heading', 'basel'), 
		'id' => 'page_titles',
		'icon' => 'el-icon-check',
		'fields' => array (
			array (
				'id'       => 'page-title-design',
				'type'     => 'button_set',
				'title'    => esc_html__('Page title design', 'basel'), 
				'options'  => array(
					'default' => esc_html__('Default', 'basel'), 
					'centered' => esc_html__('Centered', 'basel'),  
					'disable' => esc_html__('Disable', 'basel'), 
				),
				'default' => 'centered',
				'tags'     => 'page heading design'
			),
			array (
				'id'       => 'breadcrumbs',
				'type'     => 'switch',
				'title'    => esc_html__('Show breadcrumbs', 'basel'), 
				'subtitle' => esc_html__('Displays a full chain of links to the current page.', 'basel'),
				'default' => true
			),
			array (
                'id'       => 'yoast_shop_breadcrumbs',
                'type'     => 'switch',
                'title'    => esc_html__( 'Yoast breadcrumbs for shop', 'basel' ),
                'subtitle' => esc_html__( 'Requires Yoast SEO plugin to be installed. Replaces standard WooCommerce breadcrumbs with custom that come with the plugin.', 'basel' ),
                'description' => esc_html__( 'You need to enable and configure it in Dashboard -> SEO -> Search Appearance -> Breadcrumbs.', 'basel' ),
                'default' => false
            ),
            array (
                'id'       => 'yoast_pages_breadcrumbs',
                'type'     => 'switch',
                'title'    => esc_html__( 'Yoast breadcrumbs for pages', 'basel' ),
                'subtitle' => esc_html__( 'Requires Yoast SEO plugin to be installed. Replaces our theme\'s breadcrumbs for pages and blog with custom that come with the plugin.', 'basel' ),
                'description' => esc_html__( 'You need to enable and configure it in Dashboard -> SEO -> Search Appearance -> Breadcrumbs.', 'basel' ),
                'default' => false
            ),
			array (         
				'id'       => 'title-background',
				'type'     => 'background',
				'title'    => esc_html__('Pages heading background', 'basel'),
				'subtitle' => esc_html__('Set background image or color, that will be used as a default for all page titles, shop page and blog.', 'basel'),
				'desc'     => esc_html__('You can also specify other image for particular page', 'basel'),
				'output'   => array('.page-title-default'),
				'default'  => array(
					'background-color' => '#212121'
				),
				'tags'     => 'page title color page title background'
			),
			array (
				'id'       => 'page-title-size',
				'type'     => 'button_set',
				'title'    => esc_html__('Page title size', 'basel'), 
				'subtitle' => esc_html__('You can set different sizes for your pages titles', 'basel'),
				'options'  => array(
					'default' => esc_html__('Default',  'basel'), 
					'small' => esc_html__('Small',  'basel'), 
					'large' => esc_html__('Large', 'basel'), 
				),
				'default' => 'small',
				'tags'     => 'page heading size breadcrumbs size'
			),
			array (
				'id'       => 'page-title-color',
				'type'     => 'button_set',
				'title'    => esc_html__('Text color for page title', 'basel'), 
				'subtitle' => esc_html__('You can set different colors depending on it\'s background. May be light or dark', 'basel'),
				'options'  => array(
					'default' => esc_html__('Default',  'basel'), 
					'light' => esc_html__('Light', 'basel'),  
					'dark' => esc_html__('Dark', 'basel'), 
				),
				'default' => 'light'
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header', 'basel'),
		'id' => 'header',
		'icon' => 'el-icon-wrench'
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Logo', 'basel'),
		'id' => 'header-logo',
		'subsection' => true,
		'fields' => array (
			array (
				'id' => 'logo',
				'type' => 'media',
				'desc' => esc_html__('Upload image: png, jpg or gif file', 'basel'),
				'operator' => 'and',
				'title' => esc_html__('Logo image', 'basel'),
			),
			array (
				'id' => 'logo-sticky',
				'type' => 'media',
				'desc' => esc_html__('Upload image: png, jpg or gif file', 'basel'),
				'operator' => 'and',
				'title' => esc_html__('Logo image for sticky header', 'basel'),
			),
			array (
				'id' => 'logo-white',
				'type' => 'media',
				'desc' => esc_html__('Upload image: png, jpg or gif file', 'basel'),
				'operator' => 'and',
				'title' => esc_html__('Logo image - white', 'basel'),
				'tags'     => 'white logo white'
			),
			array(
				'id'        => 'logo_width',
				'type'      => 'slider',
				'title'     => esc_html__('Logo container width', 'basel'),
				'desc'      => esc_html__('Set width for logo area in the header. In percentages', 'basel'),
				"default"   => 20,
				"min"       => 1,
				"step"      => 1,
				"max"       => 50,
				'display_value' => 'label',
				'tags'     => 'logo width logo size'
			),
			array(
				'id'        => 'logo_img_width',
				'type'      => 'slider',
				'title'     => esc_html__('Logo image maximum width', 'basel'),
				'desc'      => esc_html__('Set maximum width for logo image in the header. In pixels', 'basel'),
				"default"   => 200,
				"min"       => 50,
				"step"      => 1,
				"max"       => 600,
				'display_value' => 'label',
				'tags'     => 'logo width logo size'
			),
		)
	) );


	Redux::setSection( $opt_name, array(
		'title' => 'Top bar',
		'id' => 'header-topbar',
		'subsection' => true,
		'fields' => array (
			array(
				'id'       => 'top-bar',
				'type'     => 'switch',
				'title'    => esc_html__('Top bar', 'basel'), 
				'subtitle' => esc_html__('Information about the header', 'basel'),
				'default'  => true,
			),
			array (
				'id'       => 'top-bar-color',
				'type'     => 'select',
				'title'    => esc_html__('Top bar text color', 'basel'), 
				'options'  => array(
					'dark' => esc_html__('Dark', 'basel'), 
					'light' => esc_html__('Light', 'basel'),  
				),
				'default' => 'light'
			),
			array(
				'id'       => 'top-bar-bg',
				'type'     => 'background',
				'title'    => esc_html__('Top bar background', 'basel'), 
				'output'   => array('.topbar-wrapp'),
				'default'  => array(
					'background-color' => '#1aada3'
				),
				'tags'     => 'top bar color topbar color topbar background'
			),
			array (
				'id'       => 'header_text',
				'type'     => 'text',
				'title'    => esc_html__('Text in the header top bar', 'basel'), 
				'subtitle' => esc_html__('Place here text you want to see in the header top bar. You can use shortocdes. Ex.: [social_buttons]', 'basel'),
				'default' => '<i class="fa fa-phone-square" style="color:white;"> </i> OUR PHONE NUMBER: <span style="margin-left:10px; border-bottom: 1px solid rgba(125,125,125,0.3);">+77 (756) 334 876</span>',
				'tags'     => 'top bar text topbar text'
			),
			array(
			   'id'        => 'top_bar_height',
			   'type'      => 'slider',
			   'title'     => esc_html__( 'Top bar height for desktop', 'basel' ),
			   'default'   => 42,
			   'min'       => 24,
			   'step'      => 1,
			   'max'       => 100,
			   'display_value' => 'label'
		   ),
		   array(
			   'id'        => 'top_bar_mobile_height',
			   'type'      => 'slider',
			   'title'     => esc_html__( 'Top bar height for mobile', 'basel' ),
			   'default'   => 38,
			   'min'       => 24,
			   'step'      => 1,
			   'max'       => 100,
			   'display_value' => 'label'
		   ),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Layout', 'basel'), 
		'id' => 'header-header',
		'subsection' => true,
		'fields' => array (
			array(
				'id'       => 'header_full_width',
				'type'     => 'switch',
				'title'    => esc_html__('Full Width', 'basel'), 
				'subtitle' => esc_html__('Make header full width', 'basel'),
				'default'  => true,
				'required' => array(
					array('header','!=', array('vertical')),
				),
				'tags'     => 'full width header'
			),
			array(
				'id'       => 'sticky_header',
				'type'     => 'switch',
				'title'    => esc_html__('Sticky Header', 'basel'), 
				'subtitle' => esc_html__('Enable/disable sticky header option', 'basel'),
				'default'  => true
			),
			array (
				'id'       => 'header',
				'type'     => 'image_select',
				'title'    => esc_html__('Header', 'basel'), 
				'subtitle' => esc_html__('Set your header design', 'basel'),
				'options'  => array(
					'shop' => array(
						'title' => 'E-Commerce',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-shop.png',
					), 
					'base' => array(
						'title' => 'Base header',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-base.png',
					), 
					'simple' => array(
						'title' => 'Simplified',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-simple.png',
					), 
					'split' => array(
						'title' => 'Double menu',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-split.png',
					), 
					'logo-center' => array(
						'title' => 'Logo center',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-logo-center.png',
					), 
					'categories' => array(
						'title' => 'With categories menu',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-categories.png',
					), 
					'menu-top' => array(
						'title' => 'Menu in top bar',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-menu-top.png',
					), 
					'vertical' => array(
						'title' => 'Vertical',
						'img' => BASEL_ASSETS_IMAGES . '/settings/header-vertical.png',
					), 
				),
				'default' => 'shop',
				'tags'     => 'header layout header type header design header base header style'
			),
			array(
				'id'       => 'header-overlap',
				'type'     => 'switch',
				'title'    => esc_html__('Header above the content', 'basel'), 
				'subtitle' => esc_html__('Overlap page content with this header (header is transparent).', 'basel'),
				'default'  => false,
				'required' => array(
					 array('header','equals', array('simple','shop','split')),
				),
				'tags'     => 'header overlap header overlay'
			),
			array(
				'id'        => 'right_column_width',
				'type'      => 'slider',
				'title'     => esc_html__('Right column width', 'basel'),
				'desc'      => esc_html__('Set width for icons and links area in the header (shopping cart, wishlist, search). In pixels', 'basel'),
				"default"   => 250,
				"min"       => 30,
				"step"      => 1,
				"max"       => 450,
				'display_value' => 'label',
				'required' => array(
					array('header','!=', array('vertical')),
				)
			),
			array(
				'id'        => 'header_height',
				'type'      => 'slider',
				'title'     => esc_html__('Header height', 'basel'),
				"default"   => 95,
				"min"       => 40,
				"step"      => 1,
				"max"       => 220,
				'display_value' => 'label',
				'required' => array(
					array('header','!=', array('vertical')),
				),
				'tags'     => 'header size logo height logo size'
			),
			array(
				'id'        => 'sticky_header_height',
				'type'      => 'slider',
				'title'     => esc_html__('Sticky header height', 'basel'),
				"default"   => 75,
				"min"       => 40,
				"step"      => 1,
				"max"       => 180,
				'display_value' => 'label',
				'required' => array(
					array('header','!=', array('vertical')),
				)
			),
			array(
				'id'        => 'mobile_header_height',
				'type'      => 'slider',
				'title'     => esc_html__('Mobile header height', 'basel'),
				'default'   => 60,
				'min'       => 40,
				'step'      => 1,
				'max'      => 120,
				'display_value' => 'label',
				'tags'     => 'mobile header size mobile logo height mobile logo size'
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__( 'Shopping cart widget', 'basel' ),
		'id' => 'header-cart',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'cart_position',
				'type'     => 'select',
				'title'    => esc_html__( 'Shopping cart position', 'basel' ), 
				'subtitle' => esc_html__( 'Shopping cart widget may be placed in the header or as a sidebar.', 'basel' ),
				'options'  => array(
					'side' => esc_html__( 'Hidden sidebar', 'basel' ),
					'dropdown' => esc_html__( 'Dropdown widget in header', 'basel' ),
					'without' => esc_html__( 'Without', 'basel' ),
				),
				'default' => 'side',
				'tags'      => 'cart widget'
			),
			array (
				'id'       => 'shopping_cart',
				'type'     => 'select',
				'title'    => esc_html__( 'Shopping cart', 'basel'), 
				'subtitle' => esc_html__( 'Set your shopping cart widget design in the header', 'basel'),
				'options'  => array(
					1 => esc_html__( 'Design 1', 'basel' ),
					2 => esc_html__( 'Design 2', 'basel' ), 
					3 => esc_html__( 'Design 3', 'basel' ),
					'disable' => esc_html__( 'Disable', 'basel' ),
				),
				'default' => 1,
				'tags'      => 'cart widget style cart widget design'
			),
			array (
				'id'       => 'shopping_icon_alt',
				'type'     => 'switch',
				'title'    => esc_html__( 'Alternative shopping cart icon', 'basel' ), 
				'subtitle' => esc_html__( 'Use alternative cart icon in header icons links', 'basel' ),
				'default' => 0
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Appearance','basel'), 
		'id' => 'header-style',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'header_color_scheme',
				'type'     => 'select',
				'title'    => esc_html__('Header text color', 'basel'), 
				'subtitle' => esc_html__('You can change colors of links and icons for the header', 'basel'),
				'options'  => array(
					'dark' => esc_html__('Dark','basel'), 
					'light' => esc_html__('Light','basel'),  
				),
				'default' => 'dark',
				'tags'     => 'header color'
			),
			array(
				'id'       => 'header_background',
				'type'     => 'background',
				'title'    => esc_html__('Header background', 'basel'), 
				'output'    => array('.main-header, .sticky-header.header-clone, .header-spacing'),
				'tags'     => 'header color'
			),
			array( 
				'id'       => 'header-border',
				'type'     => 'border',
				'title'    => esc_html__('Header Border', 'basel'),
				'output'   => array('.main-header'),
				'subtitle'     => esc_html__('Border bottom for the header.', 'basel'),
				'top'      => false,
				'left'     => false,
				'right'    => false,
			),
			array (
				'id'       => 'icons_design',
				'type'     => 'select',
				'title'    => esc_html__('Icons font for header icons', 'basel'), 
				'subtitle' => esc_html__('Choose between two icon fonts: Font Awesome and Line Icons', 'basel'),
				'options'  => array(
					'line' => 'Line Icons', 
					'fontawesome' => 'Font Awesome', 
				),
				'default' => 'line',
				'tags' => 'font awesome icons shopping cart icon'
			),
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Main menu', 'basel'), 
		'id' => 'header-menu',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'menu_align',
				'type'     => 'button_set',
				'title'    => esc_html__('Main menu align', 'basel'), 
				'subtitle' => esc_html__('Set menu text position on some headers', 'basel'),
				'options'  => array(
					'left' => esc_html__('Left', 'basel'),  
					'center' => esc_html__('Center', 'basel'),  
					'right' => esc_html__('Right', 'basel'),  
				),
				'default' => 'left',
				'tags'     => 'menu center menu'
			),
			array (
				'id'       => 'mobile_menu_position',
				'type'     => 'button_set',
				'title'    => esc_html__('Mobile menu side', 'basel'),
				'subtitle' => esc_html__('Choose from which side mobile navigation will be shown', 'basel'),
				'options'  => array(
					'left' => 'Left',
					'right' => 'Right',
				),
				'default' => 'left'
			),
		),
	) );
	
	Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'My account links', 'basel' ),  
        'id' => 'header_my_account',
        'subsection' => true,
        'fields' => array (
			array (
				'id'       => 'login_links',
				'type'     => 'switch',
				'title'    => esc_html__( 'Show in the HEADER', 'basel' ), 
				'subtitle' => esc_html__( 'Show links to login/register or my account page in the header', 'basel' ),
				'default' => 1
			),
            array (
                'id'       => 'login_sidebar',
                'type'     => 'switch',
                'title'    => esc_html__( 'Login form in sidebar', 'basel' ),
                'default' => 1
            ),
            array (
                'id'       => 'header_my_account_style',
                'type'     => 'select',
                'title'    => esc_html__( 'Style', 'basel' ),
                'options'  => array(
                    'text' => esc_html__( 'Text', 'basel' ),
                    'icon' => esc_html__( 'Icon', 'basel' )
                ),
                'default' => 'text'
            ),
			array (
				'id'       => 'my_account_with_username',
				'type'     => 'switch',
				'title'    => esc_html__( 'With username', 'basel'),
				'default' => 0
			),
        ),
    ) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Other', 'basel'),  
		'id' => 'header-other',
		'subsection' => true,
		'fields' => array (
			array(
				'id'       => 'categories-menu',
				'type'     => 'select',
				'title'    => esc_html__('Categories menu', 'basel'), 
				'subtitle' => esc_html__('Use your custom menu as a categories navigation for particular headers.', 'basel'),
				'data'     => 'menus'
			),
			array (
				'id'       => 'header_area',
				'type'     => 'textarea',
				'title'    => esc_html__('Text in the header', 'basel'), 
				'subtitle' => esc_html__('You can place here some advertisement or phone numbers. You can use shortcode to place here HTML block [html_block id=""]', 'basel'),
				'default' => ''
			),
			array (
				'id'       => 'header_search',
				'type'     => 'button_set',
				'title'    => esc_html__('Search widget', 'basel'), 
				'subtitle'    => esc_html__('Display search icon in the header in different views', 'basel'), 
				'options'  => array(
					'dropdown' => esc_html__('Dropdown', 'basel'),  
					'full-screen' => esc_html__('Full screen', 'basel'),  
					'disable' => esc_html__('Disable', 'basel'),  
				),
				'default' => 'full-screen'
			),
			array (
				'id'       => 'mobile_search_icon',
				'type'     => 'switch',
				'title'    => esc_html__('Search icon on mobile', 'basel'),
				'default' => 0,
				'required' => array(
					array( 'header_search', '!=', array( 'disable' ) ),
				),
			),
			array (
				'id'       => 'mobile_search_form',
				'type'     => 'switch',
				'title'    => esc_html__('Search above the mobile menu', 'basel'),
				'default' => 1,
			),
			array (
				'id'       => 'search_ajax',
				'type'     => 'switch',
				'title'    => esc_html__('AJAX Search', 'basel'), 
				'default' => 1
			),
			array(
				'id'        => 'search_ajax_result_count',
				'type'      => 'slider',
				'title'     => esc_html__( 'AJAX search result count', 'basel' ),
				'default'   => 5,
				'min'       => 5,
				'step'      => 1,
				'max'       => 50,
				'required' => array(
					array( 'search_ajax', '=', true ),
				),
			),
			array (
				'id'       => 'header_wishlist',
				'type'     => 'switch',
				'title'    => esc_html__('Display wishlist icon', 'basel'), 
				'default' => 1
			),
			array (
				'id'       => 'wishlist_hide_product_count',
				'type'     => 'switch',
				'title'    => esc_html__( 'Hide wishlist product count label', 'basel' ),
				'default' => 0,
				'required' => array(
					array( 'header_wishlist', 'equals', true ),
				),
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Footer', 'basel'), 
		'id' => 'footer',
		'icon' => 'el-icon-photo',
		'fields' => array (
			array(
				'id'       => 'disable_footer',
				'type'     => 'switch',
				'title'    => esc_html__('Footer', 'basel'),
				'default' => true
			),
			array(
				'id'       => 'footer-layout',
				'type'     => 'image_select',
				'title'    => esc_html__('Footer layout', 'basel'), 
				'subtitle' => esc_html__('Choose your footer layout. Depending on columns number you will have different number of widget areas for footer in Appearance->Widgets', 'basel'),
				'options'  => array(
					1 => array(
						'title' => 'Single Column',
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-1.png'
					), 
					2 => array(
						'title' => 'Two Columns',
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-2.png'
					), 
					3 => array(
						'title' => 'Three Columns',
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-3.png'
					), 
					4 => array(
						'title' => 'Four Columns',
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-4.png'
					), 
					5 => array(
						'title' => 'Six Columns',
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-5.png'
					), 
					6 => array(
						'title' => '1/4 + 1/2 + 1/4',
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-6.png'
					), 
					7 => array(
						'title' => '1/2 + 1/4 + 1/4',
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-7.png'
					), 
					8 => array(
						'title' => '1/4 + 1/4 + 1/2', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-8.png'
					),
					9 => array(
						'title' => 'Two rows', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-9.png'
					),
					10 => array(
						'title' => 'Two rows', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-10.png'
					), 
					11 => array(
						'title' => 'Two rows', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-11.png'
					),  
					12 => array(
						'title' => 'Two rows', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/footer-12.png'
					), 
				),
				'default' => 12
			),
			array(
				'id'       => 'footer-style',
				'type'     => 'select',
				'title'    => esc_html__('Footer text color', 'basel'), 
				'subtitle' => esc_html__('Choose your footer color scheme', 'basel'),
				'options'  => array(
					'dark' => esc_html__('Dark', 'basel'),  
					'light' => esc_html__('Light', 'basel'), 
				),
				'default' => 'light'
			),
			array(
				'id'       => 'footer-bar-bg',
				'type'     => 'background',
				'title'    => esc_html__('Footer background', 'basel'), 
				'output'    => array('.footer-container'),
				'default'  => array(
					'background-color' => '#000000'
				),
				'tags'     => 'footer color'
			),
			array(
				'id'       => 'disable_copyrights',
				'type'     => 'switch',
				'title'    => esc_html__('Copyrights', 'basel'),
				'default' => true
			),
			array(
				'id'       => 'copyrights-layout',
				'type'     => 'select',
				'title'    => esc_html__('Copyrights layout', 'basel'), 
				'options'  => array(
					'two-columns' => esc_html__('Two columns', 'basel'),  
					'centered' => esc_html__('Centered', 'basel'),  
				),
				'default' => 'centered'
			),
			array (
				'id'       => 'copyrights',
				'type'     => 'text',
				'title'    => esc_html__('Copyrights', 'basel'), 
				'subtitle' => esc_html__('Place here text you want to see in the copyrights area. You can use shortocdes. Ex.: [social_buttons]', 'basel'),
				'default' => ''
			),
			array (
				'id'       => 'copyrights2',
				'type'     => 'text',
				'title'    => esc_html__('Text next to copyrights', 'basel'), 
				'subtitle' => esc_html__('You can use shortocdes. Ex.: [social_buttons]', 'basel'),
				'default' => '' //'[social_buttons align="right" style="colored" size="small"]'
			),
			array(
				'id'=>'prefooter_area',
				'type' => 'textarea',
				'title' => esc_html__('HTML before footer', 'basel'), 
				'subtitle' => esc_html__('Custom HTML Allowed (wp_kses)', 'basel'),
				'desc' => esc_html__('This is the text before footer field, again good for additional info. You can place here any shortcode, for ex.: [html_block id=""]', 'basel'),
				'validate' => 'html_custom',
				'allowed_html' => array(
					'a' => array(
						'href' => array(),
						'title' => array()
					),
					'br' => array(),
					'em' => array(),
					'p' => array(),
					'div' => array(),
					'strong' => array()
				),
				'tags'     => 'prefooter'
			),
			array (
				'id'       => 'sticky_footer',
				'type'     => 'switch',
				'title'    => esc_html__('Sticky footer', 'basel'), 
				'default' => false
			),
			array(
				'id'       => 'collapse_footer_widgets',
				'type'     => 'switch',
				'title'    => esc_html__( 'Collapse widgets on mobile', 'basel' ),
				'subtitle' => esc_html__( 'Widgets added to the footer will be collapsed by default and opened when you click on their titles.', 'basel' ),
				'default' => false,
			),
			array (
				'id'       => 'scroll_top_btn',
				'type'     => 'switch',
				'title'    => esc_html__( 'Scroll to top button', 'basel'), 
				'default'  => true
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Typography', 'basel'),
		'id' => 'typography',
		'icon' => 'el-icon-fontsize',
		'fields' => array (
			array(
				'id'          => 'text-font',
				'type'        => 'typography', 
				'title'       => esc_html__('Text font', 'basel'),
				'all_styles'  => true,
				'google'      => true, 
				'font-backup' => true,
				'text-align'  => false,
				'font-weight' => false,
				'line-height' => false,
				'font-style' => false,
				'output'      => $basel_selectors['text-font'],
				'units'       =>'px',
				'subtitle'    => esc_html__('Set you typography options for body, paragraphs.', 'basel'),
				'default'     => array(
					'font-family' => 'Karla',
					'google'      => true,
					'font-backup' => 'Arial, Helvetica, sans-serif'
				),
				'tags'     => 'typography'
			),
			array(
				'id'          => 'primary-font',
				'type'        => 'typography', 
				'title'       => esc_html__('Primary font', 'basel'),
				'all_styles'  => true,
				'google'      => true, 
				'font-backup' => true,
				'font-size'   => false,
				'line-height' => false,
				'text-align'  => false,
				'output'      => $basel_selectors['primary-font'],
				'units'       =>'px',
				'subtitle'    => esc_html__('Set you typography options for titles, post names.', 'basel'),
				'default'     => array(
					'font-family' => 'Karla',
					'google'      => true,
					'font-backup' => "'MS Sans Serif', Geneva, sans-serif"
				),
				'tags'     => 'typography'
			),
			array(
				'id'          => 'post-titles-font',
				'type'        => 'typography', 
				'title'       => esc_html__('Entities names', 'basel'),
				'all_styles'  => true,
				'google'      => true, 
				'font-backup' => true,
				'font-size'   => false,
				'line-height' => false,
				'text-align'  => false,
				'output'      => $basel_selectors['titles-font'],
				'units'       =>'px',
				'subtitle'    => esc_html__('Titles for posts, products, categories and pages', 'basel'),
				'default'     => array(
					'font-family' => 'Lora',
					'google'      => true,
					'font-backup' => "'MS Sans Serif', Geneva, sans-serif"
				),
				'tags'     => 'typography'
			),
			array(
				'id'          => 'secondary-font',
				'type'        => 'typography', 
				'title'       => esc_html__('Secondary font', 'basel'),
				'all_styles'  => true,
				'google'      => true, 
				'font-backup' => true,
				'font-size'   => false,
				'line-height' => false,
				'text-align'  => false,
				'output'      => $basel_selectors['secondary-font'],
				'units'       =>'px',
				'subtitle'    => esc_html__('Use for secondary titles (use CSS class "font-alt" or "title-alt")', 'basel'),
				'default'     => array(
					'font-family' => 'Lato',
					'font-style' => 'italic',
					'google'      => true,
					'font-backup' => "'Comic Sans MS', cursive"
				),
				'tags'     => 'typography'
			),
			array(
				'id'          => 'widget-titles-font',
				'type'        => 'typography', 
				'title'       => esc_html__('Widget titles font', 'basel'),
				'all_styles'  => true,
				'google'      => true, 
				'font-backup' => true,
				'font-size'   => true,
				'line-height' => true,
				'text-align'  => true,
				'output'      => $basel_selectors['widget-titles-font'],
				'units'       =>'px',
				'tags'     => 'typography'
			),
			array(
				'id'          => 'navigation-font',
				'type'        => 'typography', 
				'title'       => esc_html__('Navigation font', 'basel'),
				'all_styles'  => true,
				'google'      => true, 
				'font-backup' => true,
				'font-size'   => true,
				'line-height' => false,
				'text-align'  => false,
				'output'      => $basel_selectors['navigation-font'],
				'units'       =>'px',
				'tags'     => 'typography'
			),
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Typekit Fonts', 'basel'),
		'id' => 'typekit_font',
		'subsection' => true,
		'fields' => array(
			array(
                'id'    => 'info_success2',
                'type'  => 'info',
                'style' => 'success',
                'desc'  => wp_kses( __( 'To use your Typekit font, you need to create an account on the <a href="https://typekit.com/" target="_blank">service</a> and obtain your key ID here. Then, you need to enter all custom fonts you will use separated with coma. After this, save Theme Settings and reload this page to be able to select your fonts in the list under the Theme Settings -> Typography section.', 'basel' ), array( 'a' => array( 'href' => true, 'target' => true, ), 'br' => array(), 'strong' => array() ) ),
            ),
			array(
				'title' => 'Typekit Kit ID',
				'id' => 'typekit_id',
				'type' => 'text',
				'desc' => esc_html__('Enter your ', 'basel') . '<a target="_blank" href="https://typekit.com/account/kits">Typekit Kit ID</a>.',
			),
			array(
				'title' => esc_html__('Typekit Typekit Font Face', 'basel'),
				'id' => 'typekit_fonts',
				'type' => 'text',
				'desc' => esc_html__('Example: futura-pt, lato', 'basel'),
			),
		),
	) );

	Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'Custom Fonts', 'basel' ),
        'id' => 'custom_fonts',
        'subsection' => true,
        'fields' => array(
            array(
                'id'   => 'info_success',
                'type' => 'info',
                'style' => 'success',
                'desc' => wp_kses( __( 'In this section you can upload your custom fonts files. To ensure the best compatibility in all browsers you would better upload your fonts in all available formats. 
<br><strong>IMPORTANT NOTE</strong>: After uploading all files and entering the font name, you will have to save Theme Settings and <strong>RELOAD</strong> this page. Then, you will be able to go to Theme Settings -> Typography and select the custom font from the list. Find more information in our documentation <a href="https://xtemos.com/docs/basel/faq-guides-2/upload-custom-fonts/" target="_blank">here</a>.', 'basel' ), array( 'a' => array( 'href' => true, 'target' => true, ), 'br' => array(), 'strong' => array() ) ),
            ),
            array (
                'id' => 'multi_custom_fonts',
                'type' => 'basel_multi_fonts',
            ),
        ),
    ) );

	Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'Advanced typography', 'basel' ),
        'id' => 'advanced_typography',
        'subsection' => true,
        'fields' => array(
            array (
                'id' => 'advanced_typography',
                'type' => 'basel_typography',
                'output' => true
            ),
        ),
    ) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Styles and colors', 'basel'),
		'id' => 'colors',
		'icon' => 'el-icon-brush',
		'fields' => array (
			array(
				'id'       => 'primary-color',
				'type'     => 'color',
				'title'    => esc_html__('Primary Color', 'basel'), 
				'subtitle' => esc_html__('Pick a background color for the theme buttons and other colored elements.', 'basel'),
				'validate' => 'color',
				'output'   => $basel_selectors['primary-color'],
				'default'  => '#1aada3'
			),
			array(
				'id'       => 'secondary-color',
				'type'     => 'color',
				'title'    => esc_html__('Secondary Color', 'basel'), 
				'validate' => 'color',
				'output'   => $basel_selectors['secondary-color']
			),
			array(
				'id'          => 'android_browser_bar_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Android browser bar color', 'basel' ), 
				'description' => wp_kses( __( 'Define color for the browser top bar on Android devices. <a href="https://developers.google.com/web/fundamentals/design-and-ux/browser-customization/#color_browser_elements">[Read more]</a>', 'basel' ), 'default' ),
				'validate'    => 'color',
				'default'     => '',
			),
			array (
				'id'       => 'dark_version',
				'type'     => 'switch',
				'title'    => esc_html__('Dark version', 'basel'), 
				'subtitle' => esc_html__('Turn your website color to dark version', 'basel'),
				'default' => false
			),
			array (
				'id'   => 'buttons_info',
				'type' => 'info',
				'style' => 'info',
				'desc' => esc_html__('Settings for all buttons used in the template.', 'basel')
			),
			array(
				'id'       => 'regular-buttons-bg-color',
				'type'     => 'color',
				'title'    => esc_html__('Regular buttons color', 'basel'), 
				'validate' => 'color',
				'transparent' => false,
				'output'   => array(
					'background-color' => current( $basel_selectors['regular-buttons-bg-color'] ),
					'border-color' => current( $basel_selectors['regular-buttons-bg-color'] )
				),
				'default' => '#ECECEC',
			),
			array(
				'id'       => 'regular-buttons-bg-hover-color',
				'type'     => 'color',
				'title'    => esc_html__('Regular buttons hover color', 'basel'), 
				'validate' => 'color',
				'transparent' => false,
				'output'   => array(
					'background-color' => basel_append_hover_state( $basel_selectors['regular-buttons-bg-color'], true),
					'border-color' => basel_append_hover_state( $basel_selectors['regular-buttons-bg-color'], true)
				),
				'default' => '#3E3E3E',
			),
			array(
				'id'   =>'divider_1',
				'type' => 'divide'
			),
			array(
				'id'       => 'shop-buttons-bg-color',
				'type'     => 'color',
				'title'    => esc_html__('Shop buttons color', 'basel'), 
				'validate' => 'color',
				'transparent' => false,
				'output'   => array(
					'background-color' => current( $basel_selectors['shop-buttons-bg-color'] ),
					'border-color' => current( $basel_selectors['shop-buttons-bg-color'] ),
					'color' => current( $basel_selectors['shop-button-color'] )
				),
				'default' => '#000',
			),
			array(
				'id'       => 'shop-buttons-bg-hover-color',
				'type'     => 'color',
				'title'    => esc_html__('Shop buttons hover color', 'basel'), 
				'validate' => 'color',
				'transparent' => false,
				'output'   => array(
					'background-color' => basel_append_hover_state( $basel_selectors['shop-buttons-bg-color'], true ),
					'border-color' => basel_append_hover_state( $basel_selectors['shop-buttons-bg-color'], true ),
					'color' => basel_append_hover_state( $basel_selectors['shop-button-color'], true )
				),
				'default' => '#333',
			),
			array(
				'id'   =>'divider_2',
				'type' => 'divide'
			),
			array(
				'id'       => 'accent-buttons-bg-color',
				'type'     => 'color',
				'title'    => esc_html__('Accent buttons color', 'basel'), 
				'validate' => 'color',
				'transparent' => false,
				'output'   => array(
					'background-color' => current( $basel_selectors['accent-buttons-bg-color'] ),
					'border-color' => current( $basel_selectors['accent-buttons-bg-color'] )
				),
			),
			array(
				'id'       => 'accent-buttons-bg-hover-color',
				'type'     => 'color',
				'title'    => esc_html__('Accent buttons hover color', 'basel'), 
				'validate' => 'color',
				'transparent' => false,
				'output'   => array(
					'background-color' => basel_append_hover_state( $basel_selectors['accent-buttons-bg-color'], true),
					'border-color' => basel_append_hover_state( $basel_selectors['accent-buttons-bg-color'], true )
				),
			),
			// array(
			// 	'id'   =>'divider_2',
			// 	'type' => 'divide'
			// ),
			// array(
			// 	'id'       => 'gradient_color',
			// 	'type'     => 'basel_gradient',
			// 	'title'    => esc_html__('Gradient color', 'basel'), 
			// 	'output'   => array(
			// 		'background-image' => current( $basel_selectors['gradient-color'] ),
			// 	),
			// ),
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Blog', 'basel'),
		'id' => 'blog',
		'icon' => 'el-icon-pencil',
		'fields' => array (
			array (
				'id'       => 'blog_layout',
				'type'     => 'image_select',
				'title'    => esc_html__('Blog Layout', 'basel'), 
				'subtitle' => esc_html__('Select main content and sidebar alignment for blog pages.', 'basel'),
				'options'  => array(
					'full-width'      => array(
						'alt'   => '1 Column', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/none.png'
					),
					'sidebar-left'      => array(
						'alt'   => '2 Column Left', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/left.png'
					),
					'sidebar-right'      => array(
						'alt'   => '2 Column Right', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/right.png'
					),
				),
				'default' => 'sidebar-right'
			),
			array (
				'id'       => 'blog_sidebar_width',
				'type'     => 'button_set',
				'title'    => esc_html__('Blog Sidebar size', 'basel'), 
				'subtitle' => esc_html__('You can set different sizes for your blog pages sidebar', 'basel'),
				'options'  => array(
					2 => esc_html__('Small', 'basel'), 
					3 => esc_html__('Medium', 'basel'), 
					4 => esc_html__('Large', 'basel'),
				),
				'default' => 3
			),
			array (
				'id'       => 'blog_design',
				'type'     => 'select',
				'title'    => esc_html__('Blog Design', 'basel'), 
				'subtitle' => esc_html__('You can use different design for your blog styled for the theme.', 'basel'),
				'options'  => array(
					'default' => esc_html__('Default', 'basel'), 
					'default-alt' => esc_html__('Default alternative', 'basel'), 
					'small-images' => esc_html__('Small images', 'basel'), 
					'masonry' => esc_html__('Masonry grid', 'basel'), 
					'mask' => esc_html__('Mask on image', 'basel'),
				),
				'default' => 'default'
			),
			array (
				'id'       => 'blog_columns',
				'type'     => 'button_set',
				'title'    => esc_html__('Blog items columns', 'basel'), 
				'subtitle' => esc_html__('For masonry grid design', 'basel'),
				'options'  => array(
					2 => '2', 
					3 => '3', 
					4 => '4', 
					6 => '6'
				),
				'default' => 3
			),
			array (
				'id'       => 'blog_excerpt',
				'type'     => 'select',
				'title'    => esc_html__('Posts excerpt', 'basel'), 
				'subtitle' => esc_html__('If you will set this option to "Excerpt" then you are able to set custom excerpt for each post or it will be cutted from the post content. If you choose "Full content" then all content will be shown, or you can also add "Read more button" while editing the post and by doing this cut your excerpt length as you need.', 'basel'),
				'options'  => array(
					'excerpt' => esc_html__('Excerpt',  'basel'),
					'full' => esc_html__('Full content', 'basel'),
				),
				'default' => 'excerpt'
			),
			array (
				'id'       => 'blog_excerpt_length',
				'type'     => 'text',
				'title'    => esc_html__('Excerpt length', 'basel'), 
				'subtitle' => esc_html__('Number of words that will be displayed for each post if you use "Excerpt" mode and don\'t set custom excerpt for each post.', 'basel'),
				'default' => 35,
				'required' => array(
					 array('blog_excerpt','equals', 'excerpt'),
				)
			),
			array (
                'id'       => 'single_post_design',
                'type'     => 'select',
                'title'    => esc_html__( 'Single post design', 'basel' ),
                'subtitle' => esc_html__( 'You can use different design for your single post page.', 'basel' ),
                'options'  => array(
                    'default' => esc_html__( 'Default', 'basel' ),
                    'large_image' => esc_html__( 'Large image', 'basel' ),
                ),
                'default' => 'default'
            ),
			array (
				'id'       => 'blog_share',
				'type'     => 'switch',
				'title'    => esc_html__('Share buttons', 'basel'), 
				'subtitle' => esc_html__('Display share icons on single post page', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'blog_navigation',
				'type'     => 'switch',
				'title'    => esc_html__('Posts navigation', 'basel'), 
				'subtitle' => esc_html__('Next and previous posts links on single post page', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'blog_author_bio',
				'type'     => 'switch',
				'title'    => esc_html__('Author bio', 'basel'), 
				'subtitle' => esc_html__('Display information about the post author', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'blog_related_posts',
				'type'     => 'switch',
				'title'    => esc_html__('Related posts', 'basel'), 
				'subtitle' => esc_html__('Show related posts on single post page', 'basel'),
				'default' => true
			),
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Portfolio', 'basel'), 
		'id' => 'portfolio',
		'icon' => 'el-icon-th',
		'fields' => array (
            array (
                'id'       => 'disable_portfolio',
                'type'     => 'switch',
                'title'    => esc_html__( 'Disable portfolio', 'basel' ),
                'default' => false
            ),
			array (
				'id'       => 'portoflio_style',
				'type'     => 'select',
				'title'    => esc_html__('Portfolio Style', 'basel'), 
				'subtitle' => esc_html__('You can use different styles for your projects.', 'basel'),
				'options'  => array(
					'hover' => esc_html__('Show text on mouse over', 'basel'),  
					'hover-inverse' => esc_html__('Hide text on mouse over', 'basel'),  
					'bordered' => esc_html__('Bordered style', 'basel'), 
					'bordered-inverse' => esc_html__('Bordered inverse', 'basel'), 
					'text-shown' => esc_html__('Text under image', 'basel'),  
					'with-bg' => esc_html__('Text with background', 'basel'),  
					'with-bg-alt' => esc_html__('Text with background alternative', 'basel'),  
				),
				'default' => 'hover'
			),
			array (
				'id'       => 'portfolio_full_width',
				'type'     => 'switch',
				'title'    => esc_html__('Full Width portfolio', 'basel'), 
				'subtitle' => esc_html__('Makes container 100% width of the page', 'basel'),
				'default' => false
			),
			array (
				'id'       => 'projects_columns',
				'type'     => 'button_set',
				'title'    => esc_html__('Projects columns', 'basel'), 
				'subtitle' => esc_html__('How many projects you want to show per row', 'basel'),
				'options'  => array(
					2 => '2', 
					3 => '3', 
					4 => '4', 
					6 => '6'
				),
				'default' => 3
			),
			array (
				'id'       => 'portfolio_spacing',
				'type'     => 'button_set',
				'title'    => esc_html__('Space between projects', 'basel'), 
				'subtitle' => esc_html__('You can set different spacing between blocks on portfolio page', 'basel'),
				'options'  => array(
					0 => '0', 
					2 => '2', 
					6 => '5', 
					10 => '10', 
					20 => '20', 
					30 => '30'
				),
				'default' => 30
			),
			array (
				'id'       => 'portfolio_pagination',
				'type'     => 'button_set',
				'title'    => esc_html__('Portfolio pagination', 'basel'), 
				'options'  => array(
					'pagination' => esc_html__('Pagination links', 'basel'),  
					'load_more' => esc_html__('"Load more" button', 'basel'), 
					'infinit' => esc_html__('Infinit scrolling', 'basel'),  
				),
				'default' => 'pagination'
			),
			array (
				'id'       => 'portoflio_per_page',
				'type'     => 'text',
				'title'    => esc_html__('Items per page', 'basel'), 
				'default' => 12
			),
			array (
				'id'       => 'portoflio_orderby',
				'type'     => 'select',
				'title'    => esc_html__('Portfolio order by', 'basel'),
				'options'  => array(
					'date' =>esc_html__( 'Date', 'basel'),
					'ID' => esc_html__( 'ID', 'basel'),
					'title' => esc_html__( 'Title', 'basel'),
					'modified' => esc_html__( 'Modified', 'basel'),
					'menu_order' => esc_html__( 'Menu order', 'basel')
				),
				'default' => 'date'
			),
			array (
				'id'       => 'portoflio_order',
				'type'     => 'select',
				'title'    => esc_html__('Portfolio order', 'basel'),
				'options'  => array(
					'DESC' =>esc_html__( 'DESC', 'basel'),
					'ASC' => esc_html__( 'ASC', 'basel'),
				),
				'default' => 'DESC'
			),
			array (
				'id'       => 'portfolio_nav_background',
				'type'     => 'background',
				'title'    => esc_html__('Filter background', 'basel'),
				'output'   => array('.portfolio-filter')
			),
			array (
				'id'       => 'portfolio_related',
				'type'     => 'switch',
				'title'    => esc_html__('Related Projects', 'basel'),
				'subtitle' => esc_html__('Show related projects carousel.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'portoflio_filters',
				'type'     => 'switch',
				'title'    => esc_html__('Show categories filters', 'basel'),
				'default'  => true
			),
			array (
				'id'       => 'portfolio_nav_color_scheme',
				'type'     => 'select',
				'title'    => esc_html__('Color scheme for filters', 'basel'), 
				'subtitle' => esc_html__('You can change colors of links in portfolio filters', 'basel'),
				'options'  => array(
					'dark' => esc_html__('Dark', 'basel'),  
					'light' => esc_html__('Light', 'basel'),  
				),
				'default' => 'dark'
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Shop', 'basel'), 
		'id' => 'shop',
		'icon' => 'el-icon-shopping-cart',
		'fields' => array (
			array (
				'id'       => 'shop_per_page',
				'type'     => 'text',
				'title'    => esc_html__('Products per page', 'basel'), 
				'subtitle' => esc_html__('Number of products per page', 'basel'),
				'default' => 12
			),
			array (
				'id'       => 'products_columns',
				'type'     => 'button_set',
				'title'    => esc_html__('Products columns', 'basel'), 
				'subtitle' => esc_html__('How many products you want to show per row', 'basel'),
				'options'  => array(
					2 => '2', 
					3 => '3', 
					4 => '4', 
					6 => '6'
				),
				'default' => 4,
				'required' => array(
					 array('shop_view','not','list'),
				)
			),
			array (
				'id'       => 'products_columns_mobile',
				'type'     => 'button_set',
				'title'    => esc_html__('Products columns on mobile', 'basel'),
				'subtitle' => esc_html__('How many products you want to show per row on mobile devices', 'basel'),
				'options'  => array(
					1 => '1',
					2 => '2',
				),
				'default' => 2,
				'required' => array(
						array('shop_view','not','list'),
				)
			),
			array (
				'id'       => 'shop_pagination',
				'type'     => 'button_set',
				'title'    => esc_html__('Products pagination', 'basel'),
				'options'  => array(
					'pagination' => esc_html__( 'Pagination', 'basel'),
					'more-btn' =>  esc_html__( '"Load more" button', 'basel' ),
					'infinit' => esc_html__( 'Infinit scrolling', 'basel'),
				),
				'default' => 'pagination'
			),
			array (
				'id'       => 'shop_filters',
				'type'     => 'switch',
				'title'    => esc_html__('Shop filters', 'basel'), 
				'subtitle' => esc_html__('Enable shop filters widget\'s area above the products.', 'basel'),
				'default' => true
			),
			array (
                'id'       => 'shop_filters_always_open',
                'type'     => 'switch',
                'title'    => esc_html__( 'Shop filters area always opened', 'basel' ),
                'subtitle' => esc_html__( 'If you enable this option the shop filters will be always opened on the shop page.', 'basel' ),
                'default' => false,
                'required' => array(
                    array( 'shop_filters', 'equals', true ),
                )
            ),
            array (
                'id'       => 'shop_filters_type',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Shop filters content type', 'basel' ),
                'subtitle' => esc_html__( 'You can use widgets or custom HTML block with our Product filters WPBakery element.', 'basel' ),
                'options'  => array(
                    'widgets' => esc_html__( 'Widgets', 'basel' ),
                    'content' => esc_html__( 'Custom content', 'basel' ),
                ),
                'default' => 'widgets',
                'required' => array(
                    array( 'shop_filters', 'equals', true ),
                )
            ),
            array (
                'id'       => 'shop_filters_content',
                'type'     => 'select',
                'title'    => esc_html__( 'Shop filters custom content', 'basel' ),
                'subtitle' => esc_html__( 'You can create an HTML Block in Dashboard -> HTML Blocks and add Product filters WPBakery element there.', 'basel' ),
                'options'  => array_flip( basel_get_static_blocks_array() ),
                'required' => array(
                    array( 'shop_filters_type', 'equals', 'content' ),
                )
            ),
            array (
                'id'       => 'shop_filters_close',
                'type'     => 'switch',
                'title'    => esc_html__( 'Stop close filters after click', 'basel' ),
                'subtitle' => esc_html__( 'This option will prevent filters area from closing when you click on certain filter links.', 'basel' ),
                'default' => false,
                'required' => array(
                    array( 'shop_filters_always_open', 'equals', false ),
                )
            ),
			array (
				'id'       => 'products_masonry',
				'type'     => 'switch',
				'title'    => esc_html__('Masonry grid', 'basel'), 
				'subtitle' => esc_html__('Products may have different sizes', 'basel'),
				'default' => false,
				'required' => array(
					 array('shop_view','not','list'),
				)
			),
			array (
				'id'       => 'add_to_cart_action',
				'type'     => 'button_set',
				'title'    => esc_html__('Action after add to cart', 'basel'),
				'subtitle' => esc_html__('Choose between showing informative popup and opening shopping cart widget. Only for shop page.', 'basel'),
				'options'  => array(
					'popup' => esc_html__('Show popup', 'basel'), 
					'widget' => esc_html__('Display widget', 'basel'), 
					'nothing' => esc_html__('No action', 'basel'), 
				),
				'default' => 'widget',
			),
				array (
				'id'       => 'add_to_cart_action_timeout',
				'type'     => 'switch',
				'title'    => esc_html__('Hide widget automatically', 'basel'),
				'subtitle' => esc_html__('After adding to cart the shopping cart widget will be hidden automatically', 'basel'),
				'default'  => false,
				'required' => array(
					array( 'add_to_cart_action', '!=', 'nothing' ),
				)
			),

			array(
				'id'        => 'add_to_cart_action_timeout_number',
				'type'      => 'slider',
				'title'     => esc_html__( 'Hide widget after', 'basel' ),
				'desc'      => esc_html__( 'Set the number of seconds for the shopping cart widget to be displayed after adding to cart', 'basel' ),
				'default'   => 3,
				'min'       => 3,
				'step'      => 1,
				'max'       => 20,
				'required' => array(
					array( 'add_to_cart_action', '!=', 'nothing' ),
					array( 'add_to_cart_action_timeout', '=', true ),
				)
			),
			array (
				'id'       => 'products_different_sizes',
				'type'     => 'switch',
				'title'    => esc_html__('Products grid with different sizes', 'basel'), 
				'default' => false,
				'required' => array(
					 array('shop_view','not','list'),
				)
			),
			array (
				'id'       => 'products_hover',
				'type'     => 'select',
				'title'    => esc_html__('Hover on product', 'basel'), 
				'subtitle' => esc_html__('Choose one of those hover effects for products', 'basel'),
				'options'  => basel_get_config( 'product-hovers' ),
				'default' => 'alt',
				'required' => array(
					 array('shop_view','not','list'),
				)
			),
			array (
				'id'       => 'ajax_shop',
				'type'     => 'switch',
				'title'    => esc_html__('AJAX shop', 'basel'), 
				'subtitle' => esc_html__('Enable AJAX functionality for filters widgets on shop.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'ajax_scroll',
				'type'     => 'switch',
				'title'    => esc_html__('Scroll to top after AJAX', 'basel'), 
				'subtitle' => esc_html__('Disable scroll to top after AJAX.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'hover_image',
				'type'     => 'switch',
				'title'    => esc_html__('Hover image', 'basel'), 
				'subtitle' => esc_html__('Show second product image on hover.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'product_title_lines_limit',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Product title lines limit', 'basel' ),
				'options'  => array(
					'one' => esc_html__( 'One line', 'basel' ),
					'two' => esc_html__( 'Two line', 'basel' ),
					'none' => esc_html__( 'None', 'basel' ),
				),
				'default' => 'none'
			),
			array (
                'id'       => 'grid_stock_progress_bar',
                'type'     => 'switch',
                'title'    => esc_html__( 'Stock progress bar', 'basel' ),
                'subtitle' => esc_html__( 'Display a number of sold and in stock products as a progress bar.', 'basel' ),
                'default' => false
            ),
			array (
				'id'       => 'shop_countdown',
				'type'     => 'switch',
				'title'    => esc_html__('Countdown timer', 'basel'), 
				'subtitle' => esc_html__('Show timer for products that have scheduled date for the sale price', 'basel'),
				'default' => false
			),
			array (
				'id'       => 'quick_view',
				'type'     => 'switch',
				'title'    => esc_html__('Quick View', 'basel'), 
				'subtitle' => esc_html__('Enable Quick view option. Ability to see the product information with AJAX.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'quick_view_variable',
				'type'     => 'switch',
				'title'    => esc_html__('Show variations on quick view', 'basel'), 
				'subtitle' => esc_html__('Enable Quick view option for variable products. Will allow your users to purchase variable products directly from the quick view.', 'basel'),
				'default' => true,
				'required' => array(
					 array('quick_view','equals',true),
				)
			),
			array (
				'id'       => 'search_categories',
				'type'     => 'switch',
				'title'    => esc_html__('Categories dropdown in WOO search form', 'basel'), 
				'subtitle' => esc_html__('Display categories select that allows users search products by category', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'categories_design',
				'type'     => 'select',
				'title'    => esc_html__('Categories design', 'basel'), 
				'subtitle' => esc_html__('Choose one of those designs for categories', 'basel'),
				'options'  => basel_get_config( 'categories-designs' ),
				'default' => 'default'
			),
			array (
                'id'       => 'hide_categories_product_count',
                'title'    => esc_html__( 'Hide product count on category', 'basel' ),
                'type'     => 'switch',
                'default'  => false
			),
			array (
                'id'       => 'cat_desc_position',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Category description position', 'basel' ),
                'options'  => array(
                    'before' => esc_html__( 'Before product grid', 'basel' ),
                    'after' => esc_html__( 'After product grid', 'basel' ),
                ),
                'default' => 'before'
            ),
			array (
				'id'       => 'empty_cart_text',
				'type'     => 'textarea',
				'title'    => esc_html__('Empty cart text', 'basel'), 
				'subtitle' => esc_html__('Text will be displayed if user don\'t add any products to cart', 'basel'),
				'default'  => 'Before proceed to checkout you must add some products to your shopping cart.<br> You will find a lot of interesting products on our "Shop" page.',
			),


		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Shop page layout', 'basel'), 
		'id' => 'shop-layout',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'shop_layout',
				'type'     => 'image_select',
				'title'    => esc_html__('Shop Layout', 'basel'), 
				'subtitle' => esc_html__('Select main content and sidebar alignment for shop pages.', 'basel'),
				'options'  => array(
					'full-width'      => array(
						'alt'   => '1 Column', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/none.png'
					),
					'sidebar-left'      => array(
						'alt'   => '2 Column Left', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/left.png'
					),
					'sidebar-right'      => array(
						'alt'   => '2 Column Right', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/right.png'
					),
				),
				'default' => 'full-width'
			),
			array (
				'id'       => 'shop_sidebar_width',
				'type'     => 'button_set',
				'title'    => esc_html__('Sidebar size', 'basel'), 
				'subtitle' => esc_html__('You can set different sizes for your shop pages sidebar', 'basel'),
				'options'  => array(
					2 => esc_html__('Small', 'basel'),  
					3 => esc_html__('Medium', 'basel'),  
					4 => esc_html__('Large', 'basel'), 
				),
				'default' => 3
			),
			array (
                'id'       => 'shop_hide_sidebar_mobile',
                'type'     => 'switch',
                'title'    => esc_html__( 'Off canvas sidebar for mobile', 'basel' ),
                'subtitle' => esc_html__( 'You can can hide sidebar and show nicely on button click on the shop page.', 'basel' ),
                'default' => true,
                'required' => array(
                     array( 'shop_layout', '!=', 'full-width' ),
                )
            ),
            array (
                'id'       => 'shop_hide_sidebar_tablet',
                'type'     => 'switch',
                'title'    => esc_html__( 'Off canvas sidebar for tablet', 'basel' ),
                'subtitle' => esc_html__( 'You can can hide sidebar and show nicely on button click on the shop page.', 'basel' ),
                'default' => true,
                'required' => array(
                     array( 'shop_layout', '!=', 'full-width' ),
                )
            ),
            array (
                'id'       => 'shop_hide_sidebar_desktop',
                'type'     => 'switch',
                'title'    => esc_html__( 'Off canvas sidebar for desktop', 'basel' ),
                'subtitle' => esc_html__( 'You can can hide sidebar and show nicely on button click on the shop page.', 'basel' ),
                'default' => false,
                'required' => array(
                     array( 'shop_layout', '!=', 'full-width' ),
                )
			),
			array (
                'id'       => 'sticky_filter_button',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sticky off canvas sidebar button', 'basel' ),
                'subtitle' => esc_html__( 'Display the filters button fixed on the screen for mobile and tablet devices.', 'basel' ),
                'default' => false,
            ),
			array (
				'id'       => 'shop_title',
				'type'     => 'switch',
				'title'    => esc_html__('Shop title', 'basel'), 
				'subtitle' => esc_html__('Show title for shop page, product categories or tags.', 'basel'),
				'default' => false
			),
			array (
				'id'       => 'shop_categories',
				'type'     => 'switch',
				'title'    => esc_html__('Categories menu in page heading', 'basel'), 
				'subtitle' => esc_html__('Show categories menu below page title', 'basel'),
				'default' => 1
			),
			array (
				'id'       => 'shop_categories_ancestors',
				'type'     => 'switch',
				'title'    => esc_html__('Show current category ancestors', 'basel'), 
				'default' => 0,
				'required' => array(
					 array('shop_categories','equals',true),
				)
			),
			array (
				'id'       => 'show_categories_neighbors',
				'type'     => 'switch',
				'title'    => esc_html__( 'Show category neighbors if there is no children', 'basel' ),
				'default' => 0,
				'required' => array(
					array( 'shop_categories_ancestors', 'equals', true ),
				)
			),
			array (
				'id'       => 'shop_view',
				'type'     => 'button_set',
				'title'    => esc_html__('Shop products view', 'basel'), 
				'subtitle' => esc_html__('You can set different view mode for the shop page', 'basel'),
				'options'  => array(
					'grid' => esc_html__('Grid', 'basel'),  
					'list' => esc_html__('List', 'basel'),  
					'grid_list' => esc_html__('Grid / List', 'basel'), 
					'list_grid' => esc_html__('List / Grid', 'basel'), 
				),
				'default' => 'grid'
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Attribute swatches', 'basel'), 
		'id' => 'shop-swatches',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'grid_swatches_attribute',
				'type'     => 'select',
				'title'    => esc_html__('Grid swatch attribute to display', 'basel'), 
				'subtitle' => esc_html__('Choose attribute that will be shown on products grid', 'basel'),
				'data'  => 'taxonomy',
				'default' => 'pa_color'
			),
			array (
				'id'       => 'swatches_use_variation_images',
				'type'     => 'switch',
				'title'    => esc_html__('Use images from product variations', 'basel'), 
				'subtitle' => esc_html__('If enabled swatches buttons will be filled with images choosed for product variations and not with images uploaded to attribute terms.', 'basel'),
				'default' => false
			),
		),
	) );
	
	Redux::setSection( $opt_name, array(
        'title' => esc_html__('Brands', 'basel'),
        'id' => 'shop-brand',
        'subsection' => true,
        'fields' => array (
            array (
                'id'       => 'brands_attribute',
                'type'     => 'select',
                'title'    => esc_html__('Brand attribute', 'basel'),
                'subtitle' => esc_html__('If you want to show brand image on your product page select desired attribute here', 'basel'),
                'data'  => 'taxonomy',
                'default' => 'pa_brand'
            ),
            array (
                'id'       => 'product_page_brand',
                'type'     => 'switch',
                'title'    => esc_html__('Show brand on the single product page', 'basel'),
                'default' => true
            ),
            array (
                'id'       => 'product_brand_location',
                'type'     => 'button_set',
                'title'    => esc_html__('Brand position on the product page', 'basel'),
                'options'  => array(
                    'about_title' => esc_html__('Above product title', 'basel'),
                    'sidebar' => esc_html__('Sidebar', 'basel'),
                ),
                'required' => array(
                     array('product_page_brand','equals',true),
                ),
                'default' => 'about_title'
            ),
            array (
                'id'       => 'brand_tab',
                'type'     => 'switch',
                'title'    => esc_html__('Show tab with brand information', 'basel'),
                'subtitle' => esc_html__('If enabled you will see additional tab with brand description on the single product page. Text will be taken from "Description" field for each brand (attribute term).', 'basel'),
                'default' => true
            ),
            array (
                'id'       => 'brand_tab_name',
                'type'     => 'switch',
                'title'    => esc_html__( 'Use brand name for tab title', 'basel' ),
                'default' => false
            ),
			array (
                'id'       => 'brands_under_title',
                'title'    => esc_html__('Show product brands next to title', 'basel'),
                'type'     => 'switch',
                'default'  => false
			),
        ),
    ) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('My Account', 'basel'), 
		'id' => 'shop-account',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'login_tabs',
				'type'     => 'switch',
				'title'    => esc_html__('Login page tabs', 'basel'), 
				'subtitle' => esc_html__('Enable tabs for login and register forms', 'basel'),
				'default' => 1
			),
			array (
				'id'       => 'reg_title',
				'type'     => 'text',
				'title'    => esc_html__( 'Registration title', 'basel' ),
				'default'  => 'Register'
			),
			array (
				'id'       => 'reg_text',
				'type'     => 'editor',
				'title'    => esc_html__('Registration text', 'basel'), 
				'subtitle' => esc_html__('Show some information about registration on your web-site', 'basel'),
				'default' => 'Registering for this site allows you to access your order status and history. Just fill in the fields below, and we\'ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.'
			),
			array (
				'id'       => 'login_title',
				'type'     => 'text',
				'title'    => esc_html__( 'Login title', 'basel' ),
				'default'  => 'Login'
			),
			array (
				'id'       => 'login_text',
				'type'     => 'editor',
				'title'    => esc_html__('Login text', 'basel'),
				'subtitle' => esc_html__('Show some information about login on your web-site', 'basel'),
				'default' => ''
			),
			array (
				'id'       => 'my_account_links',
				'type'     => 'switch',
				'title'    => esc_html__('Dashboard icons menu', 'basel'),
				'default' => 1
			),
			array (
				'id'       => 'my_account_wishlist',
				'type'     => 'switch',
				'title'    => esc_html__('Wishlist on my account page', 'basel'),
				'default' => 1
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => 'Compare',
		'id' => 'shop-compare',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'compare',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable compare', 'basel' ),
				'subtitle' => esc_html__( 'Enable compare functionality built in with the theme. Read more information in our documentation.', 'basel' ),
				'default'  => true
			),
			array(
				'id'       => 'compare_page',
				'type'     => 'select',
				'multi'    => false,
				'data'     => 'posts',
				'args'     => array( 'post_type' =>  array( 'page' ), 'numberposts' => -1 ),
				'title'    => esc_html__( 'Compare page', 'basel' ),
				'subtitle' => esc_html__( 'Select a page for compare table. It should contain the shortcode shortcode: [basel_compare]', 'basel' ),
			),
			array (
				'id'       => 'compare_on_grid',
				'type'     => 'switch',
				'title'    => esc_html__( 'Show button on product grid', 'basel' ),
				'subtitle' => esc_html__( 'Display compare product button on all products grids and lists.', 'basel' ),
				'default'  => true
			),
			array (
				'id'       => 'fields_compare',
				'type'     => 'sorter',
				'title'    => esc_html__( 'Select fields for compare table', 'basel' ),
				'subtitle' => esc_html__( 'Choose which fields should be presented on the product compare page with table.', 'basel' ),
				'options'  => basel_compare_available_fields()
			),
			array (
				'id'       => 'empty_compare_text',
				'type'     => 'textarea',
				'title'    => esc_html__('Empty compare text', 'basel'),
				'subtitle' => esc_html__('Text will be displayed if user don\'t add any products to compare', 'basel'),      
				'default'  => 'No products added in the compare list. You must add some products to compare them.<br> You will find a lot of interesting products on our "Shop" page.',
				'class'   => 'without-border'
			),
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Catalog mode', 'basel'), 
		'id' => 'shop-catalog',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'catalog_mode',
				'type'     => 'switch',
				'title'    => esc_html__('Enable catalog mode', 'basel'), 
				'subtitle' => esc_html__('You can hide all "Add to cart" buttons, cart widget, cart and checkout pages. This will allow you to showcase your products as an online catalog without ability to make a purchase.', 'basel'),
				'default' => false
			),
		),
	) );

	Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'Login to see prices', 'basel' ),
        'id' => 'shop-login-prices',
        'subsection' => true,
        'fields' => array (
            array (
                'id'       => 'login_prices',
                'type'     => 'switch',
                'title'    => esc_html__( 'Login to see add to cart and prices', 'basel' ),
                'subtitle' => esc_html__( 'You can restrict shopping functions only for logged in customers.', 'basel' ),
                'default' => false
            ),
        ),
    ) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Cookie Law Info', 'basel'), 
		'id' => 'shop-cookie',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'cookies_info',
				'type'     => 'switch',
				'title'    => esc_html__('Show cookies info', 'basel'), 
				'subtitle' => esc_html__('Under EU privacy regulations, websites must make it clear to visitors what information about them is being stored. This specifically includes cookies. Turn on this option and user will see info box at the bottom of the page that your web-site is using cookies.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'cookies_text',
				'type'     => 'editor',
				'title'    => esc_html__('Popup text', 'basel'), 
				'subtitle' => esc_html__('Place here some information about cookies usage that will be shown in the popup.', 'basel'),
				'default' => esc_html__('We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.', 'basel'),
			),
			array (
				'id'       => 'cookies_policy_page',
				'type'     => 'select',
				'title'    => esc_html__('Page with details', 'basel'), 
				'subtitle' => esc_html__('Choose page that will contain detailed information about your Privacy Policy', 'basel'),
				'data'     => 'pages'
			),
			array (
			   'id'       => 'cookies_version',
			   'type'     => 'text',
			   'title'    => esc_html__('Cookies version', 'basel'),
			   'subtitle' => esc_html__('If you change your cookie policy information you can increase their version to show the popup to all visitors again.', 'basel'),
			   'default' => 1,
		   ),
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Promo popup', 'basel'), 
		'id' => 'shop-popup',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'promo_popup',
				'type'     => 'switch',
				'title'    => esc_html__('Enable promo popup', 'basel'), 
				'subtitle' => esc_html__('Show promo popup to users when they enter the site.', 'basel'),
				'default' => 1
			),
			array (
				'id'       => 'popup_text',
				'type'     => 'editor',
				'title'    => esc_html__('Promo popup text', 'basel'), 
				'subtitle' => esc_html__('Place here some promo text or use HTML block and place here it\'s shortcode', 'basel'),
				'default' => '
<div class="vc_row">
	<div class="vc_column_container vc_col-sm-6">
		<div class="vc_column-inner ">
			<figure style="margin: -20px;">
				<img src="http://placehold.it/760x800" alt="placeholder">
			</figure>
		</div>
	</div>
	<div class="vc_column_container vc_col-sm-6">
		<div style="padding: 70px 25px 70px 40px;">
			<h1 style="margin-bottom: 0px; text-align: center;"><strong>HELLO USER, JOIN OUR</strong></h1>
			<h1 style="text-align: center;"><strong>NEWSLETTER<span style="color: #0f8a7e;"> BASEL &amp; CO.</span></strong></h1>
			<p style="text-align: center; font-size: 16px;">Be the first to learn about our latest trends and get exclusive offers.</p>
			[mc4wp_form id="173"]
		</div>
	</div>
</div>
				'
			),
			array (
				'id'       => 'popup_event',
				'type'     => 'button_set',
				'title'    => esc_html__('Show popup after', 'basel'), 
				'options'  => array(
					'time' => esc_html__('Some time', 'basel'),  
					'scroll' => esc_html__('User scroll', 'basel'), 
				),
				'default' => 'time'
			),
			array (
				'id'       => 'promo_timeout',
				'type'     => 'text',
				'title'    => esc_html__('Popup delay', 'basel'), 
				'subtitle' => esc_html__('Show popup after some time (in milliseconds)', 'basel'),
				'default' => '2000',
				'required' => array(
					 array('popup_event','equals', 'time'),
				)
			),
			array (
				'id'       => 'promo_version',
				'type'     => 'text',
				'title'    => esc_html__( 'Popup version', 'basel' ),
				'subtitle' => esc_html__( 'If you change your promo popup you can increase its version to show the popup to all visitors again.', 'basel' ),
				'default' => 1,
			),
			array(
				'id'        => 'popup_scroll',
				'type'      => 'slider',
				'title'     => esc_html__('Show after user scroll down the page', 'basel'),
				'subtitle' => esc_html__('Set the number of pixels users have to scroll down before popup opens', 'basel'),
				"default"   => 1000,
				"min"       => 100,
				"step"      => 50,
				"max"       => 5000,
				'display_value' => 'label',
				'required' => array(
					 array('popup_event','equals', 'scroll'),
				)
			),
			array(
				'id'        => 'popup_pages',
				'type'      => 'slider',
				'title'     => esc_html__('Show after number of pages visited', 'basel'),
				'subtitle' => esc_html__('You can choose how much pages user should change before popup will be shown.', 'basel'),
				"default"   => 0,
				"min"       => 0,
				"step"      => 1,
				"max"       => 10,
				'display_value' => 'label'
			),
			array (         
				'id'       => 'popup-background',
				'type'     => 'background',
				'title'    => esc_html__('Popup background', 'basel'),
				'subtitle' => esc_html__('Set background image or color for promo popup', 'basel'),
				'output'   => array('.basel-promo-popup'),
				// 'default'  => array(
				//     'background-image' => 'http://placehold.it/760x800',
				//     'background-repeat' => 'no-repeat',
				//     'background-size' => 'contain',
				//     'background-position' => 'left center',
				// )
			),
			array (
				'id'        => 'popup_width',
				'type'      => 'slider',
				'title'     => esc_html__( 'Popup width', 'basel' ),
				'default'   => 900,
				'min'       => 400,
				'step'      => 10,
				'max'       => 1000,
				'display_value' => 'label',
			),
			array (
				'id'       => 'promo_popup_hide_mobile',
				'type'     => 'switch',
				'title'    => esc_html__('Hide for mobile devices', 'basel'), 
				'default' => 1
			),
		),
	) );
	
	Redux::setSection( $opt_name, array(
		'title' => 'Header banner',
		'id' => 'header-banner',
		'subsection' => true,
		'fields' => array(
			array(
				'id'       => 'header_banner',
				'type'     => 'switch',
				'title'    => esc_html__( 'Header banner', 'basel' ),
				'subtitle' => esc_html__( 'Header banner above the header', 'basel' ),
				'default'  => false,
			),
			array(
				'id'       => 'header_banner_link',
				'type'     => 'text',
				'title'    => esc_html__( 'Banner link', 'basel' ),
				'tags'     => 'header banner text link'
			),
			array(
				'id'       => 'header_banner_shortcode',
				'type'     => 'editor',
				'title'    => esc_html__( 'Banner content', 'basel' ),
				'subtitle' => esc_html__( 'Place here shortcodes you want to see in the banner above the header. You can use shortcodes. Ex.: [social_buttons] or place an HTML Block built with WPBakery Page Builder builder there like [html_block id="258"]', 'basel' ),
				'tags'     => 'header banner text content'
			),
			array(
			   'id'        => 'header_banner_height',
			   'type'      => 'slider',
			   'title'     => esc_html__( 'Banner height for desktop', 'basel' ),
			   'default'   => 40,
			   'min'       => 0,
			   'step'      => 1,
			   'max'       => 200,
			   'display_value' => 'label'
			),
			array(
			   'id'        => 'header_banner_mobile_height',
			   'type'      => 'slider',
			   'title'     => esc_html__( 'Banner height for mobile', 'basel' ),
			   'default'   => 40,
			   'min'       => 0,
			   'step'      => 1,
			   'max'       => 200,
			   'display_value' => 'label'
			),
			array(
				'id'       => 'header_banner_color',
				'type'     => 'select',
				'title'    => esc_html__( 'Banner text color', 'basel' ),
				'options'  => array(
				   'dark' => esc_html__( 'Dark', 'basel' ), 
				   'light' => esc_html__( 'Light', 'basel' ),  
				),
				'default' => 'light'
			),
			array(
				'id'       => 'header_banner_bg',
				'type'     => 'background',
				'title'    => esc_html__( 'Banner background', 'basel' ),
				'output'   => array( '.header-banner' ),
				'tags'     => 'header banner color background'
			),
			array(
				'id'       => 'header_close_btn',
				'type'     => 'switch',
				'title'    => esc_html__( 'Close button', 'basel' ),
				'subtitle' => esc_html__( 'Show close banner button', 'basel' ),
				'default'  => true,
			),
			array(
				'id'       => 'header_banner_version',
				'type'     => 'text',
				'title'    => esc_html__( 'Banner version', 'basel' ),
				'subtitle' => esc_html__( 'If you change your banner you can increase their version to show the banner to all visitors again.', 'basel' ),
				'default' => 1,
				'required' => array(
					array( 'header_close_btn', 'equals', true ),
				),
			)
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Widgets', 'basel'), 
		'id' => 'shop-widgets',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'categories_toggle',
				'type'     => 'switch',
				'title'    => esc_html__('Toggle function for categories widget', 'basel'), 
				'subtitle' => esc_html__('Turn it on to enable accordion JS for the WooCommerce Product Categories widget. Useful if you have a lot of categories and subcategories.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'widgets_scroll',
				'type'     => 'switch',
				'title'    => esc_html__('Scroll for filters widgets', 'basel'), 
				'subtitle' => esc_html__('You can limit your Layered Navigation widgets by height and enable nice scroll for them. Useful if you have a lot of product colors/sizes or other attributes for filters.', 'basel'),
				'default' => true
			),
			array(
				'id'        => 'widget_heights',
				'type'      => 'slider',
				'title'     => esc_html__('Height for filters widgets', 'basel'),
				"default"   => 280,
				"min"       => 100,
				"step"      => 1,
				"max"       => 800,
				'display_value' => 'label',
				'required' => array(
					 array('widgets_scroll','equals', true),
				)
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Product labels', 'basel'),
		'id' => 'shop-labels',
		'subsection' => true,
		'fields' => array (
			array (
				'id'       => 'label_shape',
				'type'     => 'button_set',
				'title'    => esc_html__('Label shape', 'basel'),
				'options'  => array(
					'rounded' => esc_html__('Rounded', 'basel'),
					'rectangular' => esc_html__('Rectangular', 'basel'),
				),
				'default' => 'rounded'
			),
			array (
				'id'       => 'percentage_label',
				'type'     => 'switch',
				'title'    => esc_html__('Shop sale label in percentage', 'basel'),
				'subtitle' => esc_html__('Works with Simple, Variable and External products only.', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'new_label',
				'type'     => 'switch',
				'title'    => esc_html__('"New" label on products', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'hot_label',
				'type'     => 'switch',
				'title'    => esc_html__('"Hot" label on products', 'basel'),
				'subtitle' => esc_html__('Your products marked as "Featured" will have a badge with "Hot" label.', 'basel'),
				'default' => true
			)
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Product Page', 'basel'), 
		'id' => 'product_page',
		'icon' => 'el-icon-tags',
		'fields' => array (
			array (
				'id'       => 'single_product_layout',
				'type'     => 'image_select',
				'title'    => esc_html__('Single Product Sidebar', 'basel'), 
				'subtitle' => esc_html__('Select main content and sidebar alignment for single product pages.', 'basel'),
				'options'  => array(
					'full-width'      => array(
						'alt'   => '1 Column', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/none.png'
					),
					'sidebar-left'      => array(
						'alt'   => '2 Column Left', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/left.png'
					),
					'sidebar-right'      => array(
						'alt'   => '2 Column Right', 
						'img' => BASEL_ASSETS_IMAGES . '/settings/sidebar-layout/right.png'
					),
				),
				'default' => 'full-width'
			),
			array (
				'id'       => 'single_sidebar_width',
				'type'     => 'button_set',
				'title'    => esc_html__('Sidebar size', 'basel'), 
				'subtitle' => esc_html__('You can set different sizes for your single product pages sidebar', 'basel'),
				'options'  => array(
					2 => esc_html__('Small', 'basel'), 
					3 => esc_html__('Medium', 'basel'), 
					4 => esc_html__('Large', 'basel'),
				),
				'default' => 3
			),
			array (
				'id'       => 'product_design',
				'type'     => 'select',
				'title'    => esc_html__('Product page design', 'basel'), 
				'subtitle' => esc_html__('Choose between different predefined designs', 'basel'),
				'options'  => array(
					'default' => esc_html__('Default', 'basel'),  
					'alt' => esc_html__('Alternative', 'basel'),  
					'sticky' => esc_html__('Images scroll', 'basel'),  
					'compact' => esc_html__('Compact', 'basel'),  
				),
				'default' => 'alt'
			),
			array (
				'id'       => 'force_header_full_width',
				'type'     => 'switch',
				'title'    => esc_html__('Force full width header for product page', 'basel'), 
				'default' => false,
				'required' => array(
					 array('product_design','equals','sticky'),
				)
			),
			array (
				'id'       => 'single_product_style',
				'type'     => 'select',
				'title'    => esc_html__('Product image size', 'basel'), 
				'subtitle' => esc_html__('You can choose different page layout depending on the product image size you need', 'basel'),
				'options'  => array(
					1 => esc_html__('Small', 'basel'),  
					2 => esc_html__('Medium', 'basel'),  
					3 => esc_html__('Large', 'basel'), 
				),
				'default' => 2,
				'required' => array(
					 array('product_design','not','sticky'),
				)
			),
			array (
				'id'       => 'thums_position',
				'type'     => 'select',
				'title'    => esc_html__('Thumbnails position', 'basel'), 
				'subtitle' => esc_html__('Use vertical or horizontal position for thumbnails', 'basel'),
				'options'  => array(
					'left' => esc_html__('Left (vertical position)', 'basel'), 
					'bottom' => esc_html__('Bottom (horizontal position)', 'basel'), 
				),
				'default' => 'bottom',
				'required' => array(
					 array('product_design','not','sticky'),
				)
			),
			array (
				'id'       => 'product_slider_auto_height',
				'type'     => 'switch',
				'title'    => esc_html__('Main carousel auto height', 'basel'), 
				'default' => false
			),
			array (
				'id'       => 'variation_gallery',
				'type'     => 'switch',
				'title'    => esc_html__( 'Additional variations images', 'basel' ), 
				'subtitle' => esc_html__( 'Add an ability to upload additional images for each variation in variable products.', 'basel' ),
				'default'  => true
			),
			array (
				'id'       => 'image_action',
				'type'     => 'button_set',
				'title'    => esc_html__('Main image click action', 'basel'), 
				'options'  => array(
					'zoom' => esc_html__('Zoom', 'basel'),  
					'popup' => esc_html__('Photoswipe popup', 'basel'),  
					'none' => esc_html__('None', 'basel'),  
				),
				'default' => 'zoom',
			),
			array (
				'id'       => 'photoswipe_icon',
				'type'     => 'switch',
				'title'    => esc_html__('Show "Zoom image" icon', 'basel'), 
				'subtitle' => esc_html__('Click to open image in popup and swipe to zoom', 'basel'),
				'default' => true,
				'required' => array(
					 array('image_action','not','popup'),
				)
			),
			array (         
				'id'       => 'product-background',
				'type'     => 'background',
				'title'    => esc_html__('Product background', 'basel'),
				'subtitle' => esc_html__('Set background for your products page. You can also specify different background for particular products while editing it.', 'basel'),
				'output'   => array('.single-product .site-content')
			),
			array (
				'id'       => 'product_share',
				'type'     => 'switch',
				'title'    => esc_html__('Show share buttons', 'basel'), 
				'default' => true
			),
			array (
				'id'       => 'product_share_type',
				'type'     => 'button_set',
				'title'    => esc_html__('Share buttons type', 'basel'), 
				'options'  => array(
					'share' => esc_html__('Share', 'basel'), 
					'follow' => esc_html__('Follow', 'basel'), 
				),
				'default' => 'share',
				'required' => array(
					 array('product_share','equals', true),
				)
			),
			array (
                'id'       => 'attr_after_short_desc',
                'title'    => esc_html__( 'Show attributes table after short description', 'basel' ), 
                'type'     => 'switch',
                'default'  => false
			),
			array (
				'id'       => 'single_stock_progress_bar',
				'type'     => 'switch',
				'title'    => esc_html__( 'Stock progress bar', 'basel' ),
				'subtitle' => esc_html__( 'Display a number of sold and in stock products as a progress bar.', 'basel' ),
				'default' => false
			),
			array (
				'id'       => 'swatches_scroll_top_desktop',
				'type'     => 'switch',
				'title'    => esc_html__( 'Scroll top on variation select [desktop]', 'basel' ),
				'subtitle' => esc_html__( 'When you turn on this option and click on some variation with image, the page will be scrolled up to show that variation image in the main product gallery.', 'basel' ),
				'default'  => false,
			),
			array (
				'id'       => 'swatches_scroll_top_mobile',
				'type'     => 'switch',
				'title'    => esc_html__( 'Scroll top on variation select [mobile]', 'basel' ),
				'subtitle' => esc_html__( 'When you turn on this option and click on some variation with image, the page will be scrolled up to show that variation image in the main product gallery.', 'basel' ),
				'default'  => false,
			),
			array (
				'id'       => 'product_countdown',
				'type'     => 'switch',
				'title'    => esc_html__('Countdown timer', 'basel'), 
				'subtitle' => esc_html__('Show timer for products that have scheduled date for the sale price', 'basel'),
				'default' => false
			),
			array (
				'id'       => 'sale_countdown_variable',
				'type'     => 'switch',
				'title'    => esc_html__( 'Countdown for variable products', 'basel' ),
				'subtitle' => esc_html__( 'Sale end date will be based on the first variation date of the product.', 'basel' ),
				'default' => false
			),
			array (
				'id'       => 'hide_tabs_titles',
				'title'    => esc_html__('Hide tabs headings', 'basel'), 
				'type'     => 'switch',
				'default'  => false
			),
			array (
				'id'       => 'hide_products_nav',
				'title'    => esc_html__('Hide products navigation', 'basel'), 
				'type'     => 'switch',
				'default'  => false
			),
			array (
				'id'       => 'product_images_captions',
				'type'     => 'switch',
				'title'    => esc_html__('Images captions on Photo Swipe lightbox', 'basel'), 
				'default' => false
			),
			array (
				'id'       => 'size_guides',
				'type'     => 'switch',
				'title'    => esc_html__('Size guides', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'single_ajax_add_to_cart',
				'type'     => 'switch',
				'title'    => esc_html__('AJAX Add to cart', 'basel'),
				'default' => true
			),
			array (
                'id'       => 'single_sticky_add_to_cart',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sticky add to cart', 'basel' ),
                'default'  => false
			),
			array (
                'id'       => 'mobile_single_sticky_add_to_cart',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sticky add to cart on mobile', 'basel' ),
                'default'  => false,
                'required' => array(
                    array( 'single_sticky_add_to_cart', 'equals', true ),
                )
            ),
            array (
                'id'       => 'content_before_add_to_cart',
                'type'     => 'editor',
                'title'    => esc_html__( 'Before "Add to cart button" text area', 'basel' ),
            ),
            array (
                'id'       => 'content_after_add_to_cart',
                'type'     => 'editor',
                'title'    => esc_html__( 'After "Add to cart button" text area', 'basel' ),
            ),
			array (
				'id'       => 'additional_tab_title',
				'type'     => 'text',
				'title'    => esc_html__('Additional tab title', 'basel'), 
				'subtitle' => esc_html__('Leave empty to disable custom tab', 'basel'),
				'default'  => 'Shipping & Delivery'
			),
			array (
				'id'       => 'additional_tab_text',
				'type'     => 'textarea',
				'title'    => esc_html__('Additional tab content', 'basel'), 
				'default'  => '
<img src="http://placehold.it/250x200" class="alignleft" /> <p>Vestibulum curae torquent diam diam commodo parturient penatibus nunc dui adipiscing convallis bulum parturient suspendisse parturient a.Parturient in parturient scelerisque nibh lectus quam a natoque adipiscing a vestibulum hendrerit et pharetra fames.Consequat net</p>

<p>Vestibulum parturient suspendisse parturient a.Parturient in parturient scelerisque  nibh lectus quam a natoque adipiscing a vestibulum hendrerit et pharetra fames.Consequat netus.</p>

<p>Scelerisque adipiscing bibendum sem vestibulum et in a a a purus lectus faucibus lobortis tincidunt purus lectus nisl class eros.Condimentum a et ullamcorper dictumst mus et tristique elementum nam inceptos hac vestibulum amet elit</p>

<div class="clearfix"></div>
				'
			),
			array (
                'id'       => 'relater_divider',
                'type'     => 'divide',
            ),
			array (
				'id'       => 'related_products',
				'type'     => 'switch',
				'title'    => esc_html__('Show related products', 'basel'), 
				'default' => true
			),
			array (
				'id'       => 'related_product_count',
				'type'     => 'text',
				'title'    => esc_html__('Related product count', 'basel'), 
				'default'  => 8
			),
			array (
                'id'       => 'related_product_columns',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Related product columns', 'basel' ),
                'subtitle' => esc_html__( 'How many products you want to show per row.', 'basel' ),
                'options'  => array(
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    6 => '6'
                ),
                'default' => 4
            ),
            array (
                'id'       => 'related_product_view',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Related product view', 'basel' ),
                'subtitle' => esc_html__( 'You can set different view mode for the related products. These settings will be applied for upsells products as well.', 'basel' ),
                'options'  => array(
                    'grid' => 'Grid',
                    'slider' => 'Slider',
                ),
                'default' => 'slider'
            )
		),
	) );
	
	Redux::setSection( $opt_name, array(
        'title' => esc_html__('Login/Register', 'basel'),
        'id' => 'social-login',
        'icon' => 'el-icon-group',
        'fields' => array (
			array (
				'id'       => 'alt_social_login_btns_style',
				'type'     => 'switch',
				'title'    => esc_html__( 'Alternative social login buttons style', 'basel' ),
				'subtitle' => esc_html__( 'Solves the problem with style guidelines notices.', 'basel' ),
				'default'  => 1
			),
            array (
                'id'   => 'facebook_info',
                'type' => 'info',
                'style' => 'info',
                'desc' => 'Enable login/register with Facebook on your web-site.
                To do that you need to create an APP on the Facebook <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a>.
                Then go to APP settings and copy App ID and App Secret there. You also need to insert Redirect URI like this example <strong>' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . 'facebook/int_callback</strong> More information you can get in our <a href="https://xtemos.com/docs/basel/faq-guides/configure-facebook-login/" target="_blank">documentation</a>.'
            ),
            array (
                'id'       => 'fb_app_id',
                'type'     => 'text',
                'title'    => esc_html__('Facebook App ID', 'basel'),
                'default' => ''
            ),
            array (
                'id'       => 'fb_app_secret',
                'type'     => 'text',
                'title'    => esc_html__('Facebook App Secret', 'basel'),
                'default' => ''
            ),
            array (
                'id'   => 'google_info',
                'type' => 'info',
                'style' => 'info',
                'desc' => 'You can enable login/register with Google on your web-site.
                To do that you need to Create a Google APIs project at <a href="https://code.google.com/apis/console/" target="_blank">https://console.developers.google.com/apis/dashboard/</a>.
                Make sure to go to API Access tab and Create an OAuth 2.0 client ID. Choose Web application for Application type. Make sure that redirect URI is set to actual OAuth 2.0 callback URL, usually <strong>' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . 'google/oauth2callback</strong> More information you can get in our <a href="https://xtemos.com/docs/basel/faq-guides/configure-google-login/" target="_blank">documentation</a>.'
            ),
            array (
                'id'       => 'goo_app_id',
                'type'     => 'text',
                'title'    => esc_html__('Google App ID', 'basel'),
                'default' => ''
            ),
            array (
                'id'       => 'goo_app_secret',
                'type'     => 'text',
                'title'    => esc_html__('Google App Secret', 'basel'),
                'default' => ''
            ),
            array (
                'id'   => 'vk_info',
                'type' => 'info',
                'style' => 'info',
                'desc' => 'To enable login/register with vk.com you need to create an APP here <a href="https://vk.com/dev" target="_blank">https://vk.com/dev</a>.
                Then go to APP settings and copy App ID and App Secret there.
                You also need to insert Redirect URI like this example <strong>' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . 'vkontakte/int_callback</strong>'
            ),
            array (
                'id'       => 'vk_app_id',
                'type'     => 'text',
                'title'    => esc_html__('VKontakte App ID', 'basel'),
                'default' => ''
            ),
            array (
                'id'       => 'vk_app_secret',
                'type'     => 'text',
                'title'    => esc_html__('VKontakte App Secret', 'basel'),
                'default' => ''
            ),
        ),
    ) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Custom CSS', 'basel'),
		'id' => 'custom_css',
		'icon' => 'el-icon-css',
		'fields' => array (
			array (
				'id' => 'custom_css',
				'type' => 'ace_editor',
				'mode' => 'css',
				'title' => esc_html__('Global Custom CSS', 'basel')
			),
			array (
				'id' => 'css_desktop',
				'type' => 'ace_editor',
				'mode' => 'css',
				'title' => esc_html__('Custom CSS for desktop', 'basel')
			),
			array (
				'id' => 'css_tablet',
				'type' => 'ace_editor',
				'mode' => 'css',
				'title' => esc_html__('Custom CSS for tablet', 'basel')
			),
			array (
				'id' => 'css_wide_mobile',
				'type' => 'ace_editor',
				'mode' => 'css',
				'title' => esc_html__('Custom CSS for mobile landscape', 'basel')
			),
			array (
				'id' => 'css_mobile',
				'type' => 'ace_editor',
				'mode' => 'css',
				'title' => esc_html__('Custom CSS for mobile', 'basel')
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Custom JS', 'basel'),
		'id' => 'custom_js',
		'icon' => 'el-icon-magic',
		'fields' => array (
			array (
				'id' => 'custom_js',
				'type' => 'ace_editor',
				'mode' => 'javascript',
				'title' => esc_html__('Global Custom JS', 'basel')
			),
			array (
				'id' => 'js_ready',
				'type' => 'ace_editor',
				'mode' => 'javascript',
				'title' => esc_html__('On document ready', 'basel'),
				'desc' => esc_html__('Will be executed on $(document).ready()', 'basel')
			),
		),
	) );

	Redux::setSection( $opt_name, array(
        'title' => esc_html__( 'Social profiles', 'basel' ),
        'id' => 'social',
        'icon' => 'el-icon-group',
        'fields' => array (
            array (
                'id' => 'sticky_social',
                'type' => 'switch',
                'default' => false,
                'title' => esc_html__( 'Sticky social links', 'basel' )
            ),
            array (
                'id'       => 'sticky_social_type',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Sticky social links type', 'basel' ),
                'options'  => array(
                   'share' => esc_html__( 'Share', 'basel' ), 
                   'follow' => esc_html__( 'Follow', 'basel' ),
                ),
                'default' => 'follow',
                'required' => array(
                    array( 'sticky_social', 'equals', array( true ) ),
                )
            ),
            array (
                'id'       => 'sticky_social_position',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Sticky social links position', 'basel' ),
                'options'  => array(
                   'left' => esc_html__( 'Left', 'basel' ), 
                   'right' => esc_html__( 'Right', 'basel' ),
                ),
                'default' => 'right',
                'required' => array(
                    array( 'sticky_social', 'equals', array( true ) ),
                )
            ),
        ),
    ) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Links to social profiles', 'basel'),
		'id' => 'social-follow',
		'subsection' => true,
		'fields' => array (
			array (
				'id'   => 'info_follow',
				'type' => 'info',
				'style' => 'success',
				'desc' => esc_html__('Configurate your [social_buttons] shortcode. You can leave field empty to remove particular link. Note that there are two types of social buttons. First one is SHARE buttons [social_buttons type="share"]. It displays icons that share your page in social media. And the second one is FOLLOW buttons [social_buttons type="follow"]. Simply displays links to your social profiles. You can configure both types here.', 'basel')
			),
			array (
				'id'       => 'fb_link',
				'type'     => 'text',
				'title'    => esc_html__('Facebook link', 'basel'), 
				'default' => '#'
			),
			array (
				'id'       => 'twitter_link',
				'type'     => 'text',
				'title'    => esc_html__('Twitter link', 'basel'), 
				'default' => '#'
			),
			array (
				'id'       => 'isntagram_link',
				'type'     => 'text',
				'title'    => esc_html__('Instagram', 'basel'), 
				'default' => '#'
			),
			array (
				'id'       => 'pinterest_link',
				'type'     => 'text',
				'title'    => esc_html__('Pinterest link', 'basel'), 
				'default' => '#'
			),
			array (
				'id'       => 'youtube_link',
				'type'     => 'text',
				'title'    => esc_html__('Youtube link', 'basel'), 
				'default' => '#'
			),
			array (
				'id'       => 'tumblr_link',
				'type'     => 'text',
				'title'    => esc_html__('Tumblr link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'linkedin_link',
				'type'     => 'text',
				'title'    => esc_html__('LinkedIn link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'vimeo_link',
				'type'     => 'text',
				'title'    => esc_html__('Vimeo link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'flickr_link',
				'type'     => 'text',
				'title'    => esc_html__('Flickr link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'github_link',
				'type'     => 'text',
				'title'    => esc_html__('Github link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'dribbble_link',
				'type'     => 'text',
				'title'    => esc_html__('Dribbble link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'behance_link',
				'type'     => 'text',
				'title'    => esc_html__('Behance link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'soundcloud_link',
				'type'     => 'text',
				'title'    => esc_html__('SoundCloud link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'spotify_link',
				'type'     => 'text',
				'title'    => esc_html__('Spotify link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'ok_link',
				'type'     => 'text',
				'title'    => esc_html__('OK link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'vk_link',
				'type'     => 'text',
				'title'    => esc_html__('VK link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'whatsapp_link',
				'type'     => 'text',
				'title'    => esc_html__('WhatsApp link', 'basel'), 
				'default' => ''
			),
			array (
				'id'       => 'snapchat_link',
				'type'     => 'text',
				'title'    => esc_html__( 'Snapchat link', 'basel' ), 
				'default' => ''
			),
			array (
				'id'       => 'tg_link',
				'type'     => 'text',
				'title'    => esc_html__( 'Telegram link', 'basel' ), 
				'default' => ''
			),
			array (
				'id'       => 'social_email',
				'type'     => 'switch',
				'default'  => true,
				'title'    => esc_html__( 'Email for social links', 'basel' )
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Share buttons', 'basel'),
		'id' => 'social-share',
		'subsection' => true,
		'fields' => array (
			array (
				'id'   => 'info_share',
				'type' => 'info',
				'style' => 'success',
				'desc' => esc_html__('Configurate your [social_buttons] shortcode. You can leave field empty to remove particular link. Note that there are two types of social buttons. First one is SHARE buttons [social_buttons type="share"]. It displays icons that share your page in social media. And the second one is FOLLOW buttons [social_buttons type="follow"]. Simply displays links to your social profiles. You can configure both types here.', 'basel')
			),
			array (
				'id'       => 'share_fb',
				'default'  => true,
				'type'     => 'switch',
				'title'    => esc_html__('Share in facebook', 'basel')
			),
			array (
				'id'       => 'share_twitter',
				'default'  => true,
				'type'     => 'switch',
				'title'    => esc_html__('Share in twitter', 'basel')
			),
			array (
				'id'       => 'share_pinterest',
				'type'     => 'switch',
				'default'  => true,
				'title'    => esc_html__('Share in pinterest', 'basel')
			),
			array (
				'id'       => 'share_linkedin',
				'type'     => 'switch',
				'default'  => true,
				'title'    => esc_html__('Share in linkedin', 'basel')
			),
			array (
				'id'       => 'share_ok',
				'type'     => 'switch',
				'default'  => false,
				'title'    => esc_html__('Share in OK', 'basel')
			),
			array (
				'id'       => 'share_whatsapp',
				'type'     => 'switch',
				'default'  => false,
				'title'    => esc_html__('Share in whatsapp', 'basel')
			),
			array (
				'id'       => 'share_vk',
				'type'     => 'switch',
				'default'  => false,
				'title'    => esc_html__('Share in VK', 'basel')
			),
			array (
                'id'       => 'share_tg',
                'type'     => 'switch',
                'default'  => false,
                'title'    => esc_html__( 'Share in Telegram', 'basel' )
            ),
			array (
				'id'       => 'share_email',
				'type'     => 'switch',
				'default'  => true,
				'title'    => esc_html__( 'Email for share links', 'basel' )
			),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Performance', 'basel'), 
		'id' => 'performance',
		'icon' => 'el-icon-cog',
		'fields' => array (
			array (
				'id'       => 'minified_css',
				'type'     => 'switch',
				'title'    => esc_html__('Include minified CSS', 'basel'), 
				'subtitle' => esc_html__('Minified version of style.css file will be loaded (style.min.css)', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'minified_js',
				'type'     => 'switch',
				'title'    => esc_html__('Include minified JS', 'basel'), 
				'subtitle' => esc_html__('Minified version of functions.js file will be loaded', 'basel'),
				'default' => true
			),
			array (
				'id'       => 'combined_js',
				'type'     => 'switch',
				'title'    => esc_html__('Combine JS files', 'basel'),
				'subtitle' => esc_html__('Combine all third party libraries and theme functions into one JS file (theme.min.js)', 'basel'),
				'default' => false,
			),
			array (
                'id'       => 'lazy_loading',
                'type'     => 'switch',
                'title'    => esc_html__('Lazy loading for images', 'basel'),
                'subtitle' => esc_html__('Enable this option to optimize your images loading on the website. They will be loaded only when user will scroll the page.', 'basel'),
                'default' => false
			),
			array(
				'id'        => 'lazy_loading_offset',
				'type'      => 'slider',
				'title'     => esc_html__( 'Offset', 'basel' ),
				'subtitle'  => esc_html__( 'Start load images X pixels before the page is scrolled to the item', 'basel' ),
				'default'   => 0,
				'min'       => 0,
				'step'      => 10,
				'max'       => 1000,
				'display_value' => 'label',
			),
            array (
                'id'       => 'lazy_effect',
                'type'     => 'button_set',
                'title'    => esc_html__('Appearance effect', 'basel'),
                'subtitle' => esc_html__('When enabled, your images will be replaced with their blurred small previews. And when the visitor will scroll the page to that image, it will be replaced with an original image.', 'basel'),
                'default' => 'fade',
                'options'  => array(
                   'fade' => esc_html__('Fade', 'basel'), 
                   'blur' => esc_html__('Blur', 'basel'),
                   'none' => esc_html__('None', 'basel'),
                ),
			),
            array (
                'id'       => 'lazy_generate_previews',
                'type'     => 'switch',
                'title'    => esc_html__('Generate previews', 'basel'),
                'subtitle' => esc_html__('Create placeholders previews as miniatures from the original images.', 'basel'),
                'default' => true,
            ),
            array (
                'id'       => 'lazy_base_64',
                'type'     => 'switch',
                'title'    => esc_html__('Base 64 encode for placeholders', 'basel'),
                'subtitle' => esc_html__('This option allows you to decrease a number of HTTP requests replacing images with base 64 encoded sources.', 'basel'),
                'default' => true,
            ),
            array (
                'id'       => 'lazy_proprtion_size',
                'type'     => 'switch',
                'title'    => esc_html__('Proportional placeholders size', 'basel'),
                'subtitle' => esc_html__('Will generate proportional image size for the placeholder based on original image size.', 'basel'),
                'default' => true,
			),
            array (
                'id'       => 'lazy_custom_placeholder',
                'type' => 'media',
                'desc' => 'Upload image: png, ico',
                'title'    => esc_html__('Upload custom placeholder image', 'basel'),
                'subtitle' => esc_html__('Add your custom image placeholder that will be used before the original image will be loaded.', 'basel'),
                'default' => true,
            ),
		),
	) );

	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Other', 'basel'), 
		'id' => 'other',
		'icon' => 'el-icon-cog',
		'fields' => array (
			array (
				'id'       => 'dummy_import',
				'type'     => 'switch',
				'title'    => esc_html__('Show Dummy Content link in admin menu', 'basel'), 
				'default' => true
			),
			array(
                'id'       => 'basel_slider',
                'type'     => 'switch',
                'title'    => esc_html__('Enable custom slider', 'basel'),
                'description' => esc_html__('If you enable this option, a new post type for sliders will be added to your Dashboard menu. You will be able to create sliders with WPBakery Page Builder and place them on any page on your website.', 'basel'),
                'default' => true
			),
			array(
				'id'       => 'allow_upload_svg',
				'type'     => 'switch',
				'title'    => esc_html__('Allow SVG upload', 'basel'),
				'description' => wp_kses( __('Allow SVG uploads as well as SVG format for custom fonts. We suggest you to use <a href="https://ru.wordpress.org/plugins/safe-svg/">this plugin</a> to be sure that all uploaded content is safe. If you will install this plugin, you can disable this option.', 'basel'), 'default' ),
				'default' => true
			),
		),
	) );


	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Maintenance', 'basel'), 
		'id' => 'maintenance',
		'icon' => 'el-icon-cog',
		'fields' => array (
			array (
				'id'       => 'maintenance_mode',
				'type'     => 'switch',
				'title'    => esc_html__('Enable maintenance mode', 'basel'), 
				'subtitle' => esc_html__('This will block non-logged users access to the site.', 'basel'),
				'description' => esc_html__('If enabled you need to create maintenance page in Dashbard - Pages - Add new. Choose "Template" to be "Maintenance" in "Page attributes". Or you can import the page from our demo in Dashbard - Dummy Content', 'basel'),
				'default' => false
			),
		),
	) );

	// Load extensions
	//Redux::setExtensions( $opt_name, BASEL_3D . '/options/ext/' );

	function basel_removeDemoModeLink() { // Be sure to rename this function to something more unique
		if ( class_exists('ReduxFrameworkPlugin') ) {
			remove_action( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
		}
		if ( class_exists('ReduxFrameworkPlugin') ) {
			remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
		}
	}
	add_action('init', 'basel_removeDemoModeLink', 1520);
