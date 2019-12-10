<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Elements selectors for advanced typography options
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'basel_typography_selectors', array(
    'main_nav' => array(
        'title' => 'Main navigation',
    ),
    'main_navigation' => array(
        'title' => 'Main navigation links',
        'selector' => '.website-wrapper .basel-navigation.main-nav .menu > .menu-item > a',
        'selector-hover' => '.website-wrapper .basel-navigation.main-nav .menu > .menu-item:hover > a, .website-wrapper .basel-navigation.main-nav .menu > .menu-item.current-menu-item > a'
    ),
    'mega_menu_drop_first_level' => array(
        'title' => 'Menu dropdowns first level',
        'selector' => '.website-wrapper .basel-navigation .menu-item-design-full-width .sub-menu-dropdown .sub-menu > li > a, .website-wrapper .basel-navigation .menu-item-design-sized .sub-menu-dropdown .sub-menu > li > a',
        'selector-hover' => '.website-wrapper .basel-navigation .menu-item-design-full-width .sub-menu-dropdown .sub-menu > li > a:hover, .website-wrapper .basel-navigation .menu-item-design-sized .sub-menu-dropdown .sub-menu > li > a:hover'
    ),
    'mega_menu_drop_second_level' => array(
        'title' => 'Menu dropdowns second level',
        'selector' => '.website-wrapper .basel-navigation .menu-item-design-full-width .sub-menu-dropdown .sub-sub-menu li a, .website-wrapper .basel-navigation .menu-item-design-sized .sub-menu-dropdown .sub-sub-menu li a',
        'selector-hover' => '.website-wrapper .basel-navigation .menu-item-design-full-width .sub-menu-dropdown .sub-sub-menu li a:hover, .website-wrapper .basel-navigation .menu-item-design-sized .sub-menu-dropdown .sub-sub-menu li a:hover'
    ),
    'simple_dropdown' => array(
        'title' => 'Menu links on simple dropdowns',
        'selector' => '.website-wrapper .basel-navigation .menu-item-design-default .sub-menu-dropdown li a',
        'selector-hover' => '.website-wrapper .basel-navigation .menu-item-design-default .sub-menu-dropdown li a:hover'
    ),
    'secondary_nav' => array(
        'title' => 'Other navigations',
    ),
    'browse_categories' => array(
        'title' => '"Browse categories" title',
        'selector' => '.header-categories .mega-navigation .menu-opener',
        'selector-hover' => '.header-categories .mega-navigation .menu-opener:hover'
    ),
    'category_navigation' => array(
        'title' => 'Categories navigation links',
        'selector' => '.basel-navigation.categories-menu-dropdown .menu > .menu-item > a',
        'selector-hover' => '.basel-navigation.categories-menu-dropdown .menu > .menu-item:hover > a'
    ),
    'mobile_nav' => array(
        'title' => 'Mobile menu',
    ),
    'mobile_menu_first_level' => array(
        'title' => 'Mobile menu first level',
        'selector' => '.mobile-nav .site-mobile-menu > li > a, .mobile-nav .header-links > ul > li > a',
        'selector-hover' => '.mobile-nav .site-mobile-menu > li > a:hover, .mobile-nav .site-mobile-menu > li.current-menu-item > a, .mobile-nav .header-links > ul > li > a:hover'
    ),
    'mobile_menu_second_level' => array(
        'title' => 'Mobile menu second level',
        'selector' => '.mobile-nav .site-mobile-menu .sub-menu li a',
        'selector-hover' => '.mobile-nav .site-mobile-menu .sub-menu li a:hover, .mobile-nav .site-mobile-menu .sub-menu li.current-menu-item > a'
    ),
    'page_header' => array(
        'title' => 'Page heading',
    ),
    'page_title' => array(
        'title' => 'Page title',
        'selector' => '.page-title > .container .entry-title'
    ),
    'page_title_bredcrumps' => array(
        'title' => 'Breadcrumbs links',
        'selector' => '.website-wrapper .page-title .breadcrumbs a, .website-wrapper .page-title .breadcrumbs span, .website-wrapper .page-title .yoast-breadcrumb a, .website-wrapper .page-title .yoast-breadcrumb span',
        'selector-hover' => '.website-wrapper .page-title .breadcrumbs a:hover, .website-wrapper .page-title .yoast-breadcrumb a:hover'
    ),
    'products_categories' => array(
        'title' => 'Products and categories',
    ),
    'product_title' => array(
        'title' => 'Product grid title',
        'selector' => '.product.product-grid-item .product-title a',
        'selector-hover' => '.product.product-grid-item .product-title a:hover'
    ),
    'product_price' => array(
        'title' => 'Product grid price',
        'selector' => '.product-grid-item .price > .amount, .product-grid-item .price ins > .amount'
    ),
    'product_old_price' => array(
        'title' => 'Product old price',
        'selector' => '.product.product-grid-item del, .product.product-grid-item del .amount, .product-image-summary .summary-inner > .price del, .product-image-summary .summary-inner > .price del .amount'
    ),
    'product_category_title' => array(
        'title' => 'Category title',
        'selector' => '.product.category-grid-item .hover-mask h3'
    ),
    'product_category_count' => array(
        'title' => 'Category products count',
        'selector' => '.product.category-grid-item .products-cat-number'
    ),
    'single_product' => array(
        'title' => 'Single product',
    ),
    'product_title_single_page' => array(
        'title' => 'Single product title',
        'selector' => '.single-product-page .entry-summary .entry-title'
    ),
    'product_price_single_page' => array(
        'title' => 'Single product price',
        'selector' => '.single-product-page .summary-inner > .price > .amount, .single-product-page .basel-scroll-content > .price > .amount, .single-product-page .summary-inner > .price > ins .amount, .single-product-page .basel-scroll-content > .price > ins .amount'
    ),
    'product_variable_price_single_page' => array(
        'title' => 'Variable product price',
        'selector' => '.single-product-page .variations_form .woocommerce-variation-price .price > .amount, .single-product-page .variations_form .woocommerce-variation-price .price > ins .amount'
    ),
    'blog' => array(
        'title' => 'Blog',
    ),
    'blog_title' => array(
        'title' => 'Blog post title',
        'selector' => '.website-wrapper .post.blog-post-loop .entry-title a',
        'selector-hover' => '.website-wrapper .post.blog-post-loop .entry-title a:hover'
    ),
    'blog_title_shortcode' => array(
        'title' => 'Blog title on WPBakery element',
        'selector' => '.website-wrapper .basel-blog-holder .post.blog-post-loop .entry-title a',
        'selector-hover' => '.website-wrapper .basel-blog-holder .post.blog-post-loop .entry-title a:hover'
    ),
    'blog_title_carousel' => array(
        'title' => 'Blog title on carousel',
        'selector' => '.post-slide.post .entry-title a',
        'selector-hover' => '.post-slide.post .entry-title a:hover'
    ),
    'blog_title_sinle_post' => array(
        'title' => 'Blog title on single post',
        'selector' => '.post-single-page .entry-title'
    ),
    'custom_selector' => array(
        'title' => 'Write your own selector',
    ),
    'custom' => array(
        'title' => 'Custom selector',
        'selector' => 'custom'
    ),
) );