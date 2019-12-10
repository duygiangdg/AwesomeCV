<?php 
	global $product;
?>
<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
<div class="product-element-top">
	<a href="<?php echo esc_url( get_permalink() ); ?>">
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked basel_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
	</a>

	<div class="hover-mask">
		<?php
			/**
			 * woocommerce_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
		?>
		
		<?php basel_product_brands_links(); ?>
		
		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

		<?php 
			basel_swatches_list();
		?>
		<div class="product-actions">
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>
	</div> 
	<div class="basel-buttons">
		<?php if( class_exists('YITH_WCWL_Shortcode')) basel_wishlist_btn(); ?>
		<?php basel_compare_btn(); ?>
		<?php basel_quick_view_btn( get_the_ID() ); ?>
	</div>
</div>