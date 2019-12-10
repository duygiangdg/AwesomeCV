<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 	2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$isotope 		   		  = basel_loop_prop( 'products_masonry' );
$different_sizes  		  = basel_loop_prop( 'products_different_sizes' );
$categories_design 		  = basel_loop_prop( 'product_categories_design' );
$product_categories_style = basel_loop_prop( 'product_categories_style' );
$loop_column 			  = basel_loop_prop( 'products_columns' );
$classes 				  = array();
$hide_product_count       = basel_get_opt( 'hide_categories_product_count' );

if( $different_sizes ) $isotope = true;

// Increase loop count
basel_set_loop_prop( 'woocommerce_loop', basel_loop_prop( 'woocommerce_loop' ) + 1 );

$woocommerce_loop = basel_loop_prop( 'woocommerce_loop' );

$items_wide = basel_get_wide_items_array( $different_sizes );

$is_double_size = $different_sizes && in_array( $woocommerce_loop, $items_wide );

basel_set_loop_prop( 'double_size', $is_double_size );

$xs_columns = (int) basel_get_opt( 'products_columns_mobile' ) ? (int) basel_get_opt( 'products_columns_mobile' ) : 2;
$xs_size = 12 / $xs_columns;

if( $product_categories_style != 'carousel' )
	$classes[] = basel_get_grid_el_class( $woocommerce_loop, $loop_column, $different_sizes, $xs_size );

$classes[] = 'category-grid-item';
$classes[] = 'cat-design-' . $categories_design;

if ( $hide_product_count ) {
	$classes[] = 'without-product-count';
}

?>
<div <?php wc_product_cat_class( $classes, $category ); ?> data-loop="<?php echo esc_attr( $woocommerce_loop ); ?>">
	
	<div class="category-content">
		<a href="<?php echo esc_url( get_term_link( $category->slug, 'product_cat' ) ); ?>" class="category-link">
			<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
			<div class="product-category-thumbnail">
				<?php
					/**
					 * woocommerce_before_subcategory_title hook
					 *
					 * @hooked basel_category_thumb_double_size - 10
					 */
					do_action( 'woocommerce_before_subcategory_title', $category );
				?>
			</div>
			
			<?php if ( ! $hide_product_count ): ?>
				<span class="products-cat-number">
					<?php echo sprintf( _n( '%s product', '%s products', $category->count, 'basel' ), $category->count ); ?>
				</span>
			<?php endif; ?>
		</a>
		<div class="hover-mask">
			<a href="<?php echo esc_url( get_term_link( $category->slug, 'product_cat' ) ); ?>" class="category-link-overlay"></a>
			<h3>
				<?php
					echo esc_html( $category->name );
				?>
			</h3>

			<a href="<?php echo esc_url( get_term_link( $category->slug, 'product_cat' ) ); ?>"><?php _e('View products', 'basel'); ?></a>

			<?php
				/**
				 * woocommerce_after_subcategory_title hook
				 */
				do_action( 'woocommerce_after_subcategory_title', $category );
			?>
		</div>

		<?php do_action( 'woocommerce_after_subcategory', $category ); ?>
	</div>
</div>

<?php 	

if( ! $isotope ) echo basel_get_grid_clear( $woocommerce_loop, $loop_column );