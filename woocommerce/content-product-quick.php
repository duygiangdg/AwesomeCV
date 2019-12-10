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
	<?php basel_hover_image(); ?>
	<div class="basel-buttons">
		<?php if( class_exists('YITH_WCWL_Shortcode')) basel_wishlist_btn(); ?>
		<?php basel_compare_btn(); ?>
		<?php basel_quick_view_btn( get_the_ID() ); ?>
	</div>

	<div class="quick-shop-wrapper">
		<div class="quick-shop-close"><span><?php _e('Close', 'basel'); ?></span></div>
		<div class="quick-shop-btn">
			<?php basel_quick_shop_btn(); ?>
		</div>
		<div class="quick-shop-form">
		</div>
	</div>
</div>
<div class="swatches-wrapper">
	<?php 
		basel_swatches_list();
	?>
</div>
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

<?php if ( basel_loop_prop( 'progress_bar' ) ): ?>
	<?php basel_stock_progress_bar(); ?>
<?php endif ?>

<?php if ( basel_loop_prop( 'timer' ) ): ?>
	<?php basel_product_sale_countdown(); ?>
<?php endif ?>