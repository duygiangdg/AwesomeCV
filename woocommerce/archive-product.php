<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if( basel_is_woo_ajax() === 'fragments' ) {
	basel_woocommerce_main_loop( true );
	die();
}

if ( ! basel_is_woo_ajax() ) {
	get_header( 'shop' ); 
} else {
	basel_page_top_part();
}

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

if ( basel_get_opt( 'cat_desc_position' ) == 'before' ) {
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' ); 
}
?>

<div class="shop-loop-head">
	<?php
		basel_current_breadcrumbs( 'shop' );

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked wc_print_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );
	?>
</div>

<?php do_action( 'basel_shop_filters_area' ); ?>

<div class="basel-active-filters">
	<?php 

		do_action( 'basel_before_active_filters_widgets' );

		the_widget( 'WC_Widget_Layered_Nav_Filters', array(
			'title' => ''
		), array() );

		do_action( 'basel_after_active_filters_widgets' );

	?>
</div>

<div class="basel-shop-loader"></div>

<?php

//WC 3.4
$have_posts = 'have_posts';
if ( function_exists( 'WC' ) && WC()->version >= '3.4' ) {
	$have_posts = 'woocommerce_product_loop';
}

if ( $have_posts() ) {

	woocommerce_product_loop_start();
	
	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 *
			 * @hooked WC_Structured_Data::generate_product_data() - 10
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

if ( basel_get_opt( 'cat_desc_position' ) == 'after' ) {
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

if ( ! basel_is_woo_ajax() ) {
	get_footer( 'shop' ); 
} else {
	basel_page_bottom_part();
}
