<?php
/**
 * Single Product Images
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;

$is_quick_view = basel_loop_prop( 'is_quick_view' );

$attachment_ids = $product->get_gallery_image_ids();

$attachment_count = count( $attachment_ids );

?>
<div class="images">
	

	<div class="woocommerce-product-gallery__wrapper quick-view-gallery">
		<?php
			$attributes = array(
				'title' => esc_attr( get_the_title( get_post_thumbnail_id() ) )
			);

			if ( has_post_thumbnail() ) {

				echo '<figure class="woocommerce-product-gallery__image">' . get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ), $attributes ) . '</figure>';


				if ( $attachment_count > 0 ) {
					foreach ( $attachment_ids as $attachment_id ) {
						echo '<figure class="woocommerce-product-gallery__image">' . wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) ) . '</figure>';
					}
				}

			} else {

				echo '<figure class="woocommerce-product-gallery__image--placeholder">' . apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'basel' ) ), $post->ID ) . '</figure>';

			}

		?>
	</div>
<?php 

	if ( $attachment_count > 0 ) {
		?>

			<script type="text/javascript">

				jQuery('.product-quick-view .woocommerce-product-gallery__wrapper').addClass('owl-carousel').owlCarousel({
		            rtl: jQuery('body').hasClass('rtl'),
		            items: 1, 
					dots:false,
					nav: true,
					navText: false
				});

			</script>
		<?php
	}

 ?>
</div>
