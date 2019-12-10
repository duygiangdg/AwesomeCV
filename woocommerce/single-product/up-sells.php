<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$related_product_view = basel_get_opt( 'related_product_view' );

if ( $upsells ) : ?>
	
	<section class="up-sells upsells-products">
		
		<h3 class="title slider-title"><?php echo esc_html__( 'You may also like&hellip;', 'woocommerce' ); ?></h3>
		
		<?php 
		
			if ( $related_product_view == 'slider' ) {
				$slider_args = array(
					'slides_per_view' => ( basel_get_opt( 'related_product_columns' ) ) ? basel_get_opt( 'related_product_columns' ) : apply_filters( 'basel_related_products_per_view', 4 ),
					'img_size' => 'woocommerce_thumbnail',
					'custom_sizes' => apply_filters( 'basel_cross_sells_custom_sizes', false )
				);
	
				echo basel_generate_posts_slider( $slider_args, false, $upsells );
			}elseif ( $related_product_view == 'grid' ) {
				
				basel_set_loop_prop( 'products_columns', basel_get_opt( 'related_product_columns' ) );
				basel_set_loop_prop( 'products_different_sizes', false );
				basel_set_loop_prop( 'products_masonry', false );
				basel_set_loop_prop( 'products_view', 'grid' );

				woocommerce_product_loop_start();

				foreach ( $upsells as $upsell ) {
					$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] = $post_object );

					wc_get_template_part( 'content', 'product' ); 
				}

				woocommerce_product_loop_end();

				basel_reset_loop();
	
				if ( function_exists( 'woocommerce_reset_loop' ) ) woocommerce_reset_loop();
			}
			
		?>
		
	</section>

<?php endif;

wp_reset_postdata();
