<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

//WC 3.4
$post_class = 'post_class';
if ( function_exists( 'WC' ) && WC()->version >= '3.4' ) {
	$post_class = 'wc_product_class';
}

?>

<div class="single-breadcrumbs-wrapper">
	<div class="container">
		<?php basel_current_breadcrumbs( 'shop' ); ?>
		<?php if ( ! basel_get_opt( 'hide_products_nav' ) ): ?>
			<?php basel_products_nav(); ?>
		<?php endif ?>
	</div>
</div>

<div class="container">
	<?php
		/**
		 * Hook: woocommerce_before_single_product.
		 *
		 * @hooked wc_print_notices - 10
		 */
		 do_action( 'woocommerce_before_single_product' );

		 if ( post_password_required() ) {
		 	echo get_the_password_form();
		 	return;
		 }

		$product_images_class  	= basel_product_images_class();
		$product_summary_class 	= basel_product_summary_class();
		$single_product_class  	= basel_single_product_class();
		$content_class 			= basel_get_content_class();
		$product_design 		= basel_product_design();

		$container_summary = 'container';

		if( $product_design == 'sticky' ) {
			$container_summary = 'container-fluid';
		}

	?>
</div>
<div id="product-<?php the_ID(); ?>" <?php $post_class( $single_product_class, $product ); ?>>

	<div class="<?php echo esc_attr( $container_summary ); ?>">

		<div class="row">
			<div class="product-image-summary <?php echo esc_attr( $content_class ); ?>">
				<div class="row">
					<div class="<?php echo esc_attr( $product_images_class ); ?> product-images">
						<?php
							/**
							 * woocommerce_before_single_product_summary hook
							 *
							 * @hooked woocommerce_show_product_sale_flash - 10
							 * @hooked woocommerce_show_product_images - 20
							 */
							do_action( 'woocommerce_before_single_product_summary' );
						?>
					</div>
					<div class="<?php echo esc_attr( $product_summary_class ); ?> summary entry-summary">
						<div class="summary-inner <?php if( $product_design == 'compact' ) echo 'basel-scroll'; ?>">
							<div class="basel-scroll-content">
								<?php
									/**
									 * Hook: woocommerce_single_product_summary.
									 *
									 * @hooked woocommerce_template_single_title - 5
									 * @hooked woocommerce_template_single_rating - 10
									 * @hooked woocommerce_template_single_price - 10
									 * @hooked woocommerce_template_single_excerpt - 20
									 * @hooked woocommerce_template_single_add_to_cart - 30
									 * @hooked woocommerce_template_single_meta - 40
									 * @hooked woocommerce_template_single_sharing - 50
									 */
									do_action( 'woocommerce_single_product_summary' );
								?>

								<?php if ( $product_design != 'alt' && $product_design != 'sticky' && basel_get_opt( 'product_share' ) ): ?>
									<div class="product-share">
										<span class="share-title"><?php _e('Share', 'basel'); ?></span>
										<?php echo basel_shortcode_social( array( 'type' => basel_get_opt( 'product_share_type' ), 'size' => 'small', 'align' => 'left' ) ); ?>
									</div>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div><!-- .summary -->
			</div>

			<?php 
				/**
				 * woocommerce_sidebar hook
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action( 'woocommerce_sidebar' );
			?>

		</div>
	</div>

	<?php if ( ( $product_design == 'alt' || $product_design == 'sticky' ) && basel_get_opt( 'product_share' ) ): ?>
		<div class="product-share">
			<?php echo basel_shortcode_social( array( 'type' => basel_get_opt( 'product_share_type' ), 'style' => 'colored' ) ); ?>
		</div>
	<?php endif ?>

	<div class="container">
		<?php
			/**
			 * basel_after_product_content hook
			 *
			 * @hooked basel_product_extra_content - 20
			 */
			do_action( 'basel_after_product_content' );
		?>
	</div>

	<?php if( $product_design != 'compact' ): ?>
		
		<div class="product-tabs-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<?php
							/**
							 * woocommerce_after_single_product_summary hook
							 *
							 * @hooked woocommerce_output_product_data_tabs - 10
							 * @hooked woocommerce_upsell_display - 15
							 * @hooked woocommerce_output_related_products - 20
							 */
							do_action( 'woocommerce_after_single_product_summary' );
						?>
					</div>
				</div>	
			</div>
		</div>

	<?php endif ?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
