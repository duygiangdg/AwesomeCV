<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

$is_quick_view = basel_loop_prop( 'is_quick_view' );

$attachment_ids = $product->get_gallery_image_ids();

$thums_position = basel_get_opt('thums_position');
$product_design = basel_product_design();

$image_action = basel_get_opt( 'image_action' );

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = $product->get_image_id();
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = $product->get_image_id() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );
if ( $product_design == 'sticky' ) $attachment_ids = false;

//WC 3.5.0
if ( version_compare( WC()->version, '3.5.0', '<' ) ) {
	$placeholder_size = 'woocommerce_thumbnail';
} else {
	$placeholder_size = 'woocommerce_single';
}

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?> images row thumbs-position-<?php echo esc_attr( $thums_position ); ?> image-action-<?php echo esc_attr( $image_action ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<div class="<?php if ( $attachment_ids && $thums_position == 'left' && ! $is_quick_view ): ?>col-md-9 col-md-push-3<?php else: ?>col-sm-12<?php endif ?>">
		<figure class="woocommerce-product-gallery__wrapper <?php if( $product_design != 'sticky' ) echo 'owl-carousel'; ?>">
			<?php
				$attributes = array(
					'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
					'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
					'class'                   => 'wp-post-image',
				);


				if ( $product->get_image_id() ) {
					$html  = '<figure data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'woocommerce_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= get_the_post_thumbnail( $post->ID, 'woocommerce_single', $attributes );
					$html .= '</a></figure>';
				} else {
					$html  = '<figure class="woocommerce-product-gallery__image--placeholder">';
					$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( $placeholder_size ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
					$html .= '</figure>';
				}

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );


			    do_action( 'woocommerce_product_thumbnails' );

			?>
		</figure>
		<?php if ( $image_action != 'popup' && basel_get_opt('photoswipe_icon')): ?>
			<div class="basel-show-product-gallery-wrap"><a href="#" class="basel-show-product-gallery basel-tooltip"><?php _e('Click to enlarge', 'basel'); ?></a></div>
		<?php endif ?>
	</div>

	<?php if ( $attachment_ids ): ?>
		<div class="<?php if ( $thums_position == 'left' && ! $is_quick_view ): ?>col-md-3 col-md-pull-9<?php else: ?>col-sm-12<?php endif ?>"><div class="thumbnails"></div></div>
	<?php endif; ?>
</div>
