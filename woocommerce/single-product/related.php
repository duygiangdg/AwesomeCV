<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$related_product_view = basel_get_opt( 'related_product_view' );

if ( $related_products ) : ?>

	<section class="related-products">
		
		<h3 class="title slider-title"><?php echo esc_html__( 'Related products', 'woocommerce' ); ?></h3>
		
		<?php 
		
			if ( $related_product_view == 'slider' ) {
				$slider_args = array(
					'slides_per_view' => ( basel_get_opt( 'related_product_columns' ) ) ? basel_get_opt( 'related_product_columns' ) : apply_filters( 'basel_related_products_per_view', 4 ),
					'img_size' => 'woocommerce_thumbnail',
					'custom_sizes' => apply_filters( 'basel_product_related_custom_sizes', false )
				);
	
				echo basel_generate_posts_slider( $slider_args, false, $related_products );
			}elseif ( $related_product_view == 'grid' ) {
				
				basel_set_loop_prop( 'products_columns', basel_get_opt( 'related_product_columns' ) );
				basel_set_loop_prop( 'products_different_sizes', false );
				basel_set_loop_prop( 'products_masonry', false );
				basel_set_loop_prop( 'products_view', 'grid' );

				woocommerce_product_loop_start();

				foreach ( $related_products as $related_product ) {
					$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] = $post_object );

					wc_get_template_part( 'content', 'product' ); 
				}

				woocommerce_product_loop_end();

				if ( function_exists( 'woocommerce_reset_loop' ) ) woocommerce_reset_loop();
				
				basel_reset_loop();
				
			}
			
		?>
		
	</section>

<?php endif;

wp_reset_postdata();
