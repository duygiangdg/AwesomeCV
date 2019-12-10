<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ---------------------------------------------------------------------------
 * Array of widgets that will be imported
 * ---------------------------------------------------------------------------
 */
// $twitter_sidebar_title = 'Twitter';
// $twitter_sidebar = get_page_by_title( $twitter_sidebar_title, OBJECT, 'basel_sidebar' );

// $header_sidebar_title = 'Header search';
// $header_sidebar = get_page_by_title( $header_sidebar_title, OBJECT, 'basel_sidebar' );

// $sidebars = array(
// 	'main' => 'sidebar-1',
// 	'header' => 'header-widgets',
// 	'shop' => 'sidebar-shop',
// 	'account' => 'sidebar-my-account',
// 	'product' => 'sidebar-product-single',
// 	'footer1' => 'footer-1',
// 	'footer2' => 'footer-2',
// 	'footer3' => 'footer-3',
// 	'footer4' => 'footer-4',
// 	'footer5' => 'footer-5',
// 	'footer6' => 'footer-6',
// 	'footer7' => 'footer-7',
// ); 

return array(
	'sidebar-1' => array(
		'widgets' => array(
			'rpwe_widget' => array(
		    	'title' => 'Recent Posts',
		    	'thumb_height' => 60,
		    	'thumb_width' => 60,
		    ),
		),
		'flush' => true
	),
	'sidebar-shop' => array(
		'widgets' => array(
			'woocommerce_product_categories' => array(
		    	'title' => 'Categories'
		    ),
			'woocommerce_price_filter' => array(),
			'basel-woocommerce-layered-nav' => array(
		    	'title' => 'Filter by color',
		    	'attribute' => 'color',
		    	'query_type' => 'and',
		    	'display' => 'list',
		    	'size' => 'normal',
		    	'labels' => 'on',
		    ),
			'woocommerce_products' => array(
		    	'title' => 'Products'
		    ),
			'rpwe_widget' => array(
		    	'title' => 'Recent Posts',
		    	'thumb_height' => 60,
		    	'thumb_width' => 60,
		    ),
		),
		'flush' => false
	),
	'filters-area' => array(
		'widgets' => array(
			'basel-woocommerce-layered-nav' => array(
		    	'title' => 'Filter by color',
		    	'attribute' => 'color',
		    	'query_type' => 'and',
		    	'display' => 'list',
		    	'size' => 'normal',
		    	'labels' => 'on',
		    ),
		),
		'flush' => false
	),
	'sidebar-product-single' => array(
		'widgets' => array(
			'woocommerce_products' => array(
		    	'title' => 'Products'
		    ),
			'rpwe_widget' => array(
		    	'title' => 'Recent Posts',
		    	'thumb_height' => 60,
		    	'thumb_width' => 60,
		    ),
		),
		'flush' => false
	),
	'header-widgets' => array(
		'widgets' => array(
			'text' => array(
		    	'text' => 'Welcome to our store. Call free: 055 1233 32 55'
		    ),
		),
		'flush' => false
	),
	'footer-1' => array(
		'widgets' => array(
			'text' => array(
		    	'text' => '
<p style="text-align:center;"><img src="http://demo.xtemos.com/basel/wp-content/themes/basel/images/logo-white.png" alt="Basel" title="Basel and Co." style="max-width:300px;"/></p>
[social_buttons]
<br>
		    	'
		    ),
		),
		'flush' => true
	),
	'footer-2' => array(
		'widgets' => array(
			'text' => array(
		    	'title' => 'Our Stores',
				'text' => '
<ul class="menu">
<li><a href="#">New York</a></li>
<li><a href="#">London SF</a></li>
<li><a href="#">Cockfosters BP</a></li>
<li><a href="#">Los Angeles</a></li>
<li><a href="#">Chicago</a></li>
<li><a href="#">Las Vegas</a></li>
</ul>'
		    ),
		),
		'flush' => false
	),
	'footer-3' => array(
		'widgets' => array(
			'text' => array(
			    'title' => 'Information',
			    'text' => '
<ul class="menu">
<li><a href="#">About Store</a></li>
<li><a href="#">New Collection</a></li>
<li><a href="#">Woman Dress</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Latest News</a></li>
<li><a href="#">Our Sitemap</a></li>
</ul>
		    	'
		    ),
		),
		'flush' => false
	),
	'footer-4' => array(
		'widgets' => array(
			'text' => array(
			    'title' => 'Useful links',
			    'text' => '
<ul class="menu">
<li><a href="#">Privacy Policy</a></li>
<li><a href="#">Returns</a></li>
<li><a href="#">Terms &amp; Conditions</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Latest News</a></li>
<li><a href="#">Our Sitemap</a></li>
</ul>
		    	'
		    ),
		),
		'flush' => false
	),
	'footer-5' => array(
		'widgets' => array(
			'text' => array(
			    'title' => 'Footer Menu',
			    'text' => '
<ul class="menu">
<li><a href="#">Instagram profile</a></li>
<li><a href="#">New Collection</a></li>
<li><a href="#">Woman Dress</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Latest News</a></li>
<li><a href="http://themeforest.net/user/xtemos" target="_blank" style="font-style: italic; color:white;">Purchase Theme</a></li>
</ul>
		    	'
		    ),
		),
		'flush' => false
	),
	'footer-6' => array(
		'widgets' => array(
			'text' => array(
		    	'title' => 'About The Store',
		    	'text' => '
<p>STORE - worldwide fashion store since 1978. We sell over 1000+ branded products on our web-site.</p>
<div style="line-height: 2;">
<i class="fa fa-location-arrow" style="width: 15px; text-align: center; margin-right: 4px; color: #676767;"></i> 451 Wall Street, USA, New York<br>
<i class="fa fa-mobile" style="width: 15px; text-align: center; margin-right: 4px; color: #676767;"></i> Phone: (064) 332-1233<br>
<!--p><i class="fa fa-envelope-o" style="width: 15px; text-align: center; margin-right: 4px; color: #676767;"></i> Fax: (099) 453-1357<br></p-->
</div>
<br>
<p><img src="http://dummy.xtemos.com/basel/vers/wp-content/uploads/sites/3/2018/10/dummy-payments.png"></p>
		    	'
		    ),
		),
		'flush' => false
	),
	'sidebar-my-account' => array(
		'widgets' => array(
			'basel-user-panel' => array(),
		),
		'flush' => false
	),
);