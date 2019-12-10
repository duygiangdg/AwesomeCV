<?php
/**
 * The template for displaying product widget entries.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $basel_widget_product_img_size;

//WC 3.5.0
if ( version_compare( WC()->version, '3.5.0', '>=' ) && ! is_a( $product, 'WC_Product' ) ) {
	return;
}

if ( $basel_widget_product_img_size && function_exists( 'wpb_getImageBySize' ) ) {
	$image_data = wpb_getImageBySize( array( 'attach_id' => get_post_thumbnail_id( $product->get_id() ), 'thumb_size' => $basel_widget_product_img_size ) ); 
	$img = $image_data['thumbnail'];
} else {
	$img = $product->get_image();
}

?>
<li>
	<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>

	<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
		<?php echo apply_filters( 'basel_product_widget_image', $img ); ?>
		<span class="product-title"><?php echo wp_kses_post( $product->get_name() ); ?></span>
	</a>

	<?php if ( ! empty( $show_rating ) ) : ?>
		<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
	<?php endif; ?>

	<?php echo apply_filters( 'basel_product_widget_price_html', $product->get_price_html() ); ?>

	<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
</li>
