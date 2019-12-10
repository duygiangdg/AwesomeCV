<?php
$output = $title = $onclick = $custom_links = $img_size = $custom_links_target = $images = $el_class = $partial_view = '';
$mode = $slides_per_view = $wrap = $autoplay = $hide_pagination_control = $hide_prev_next_buttons = $speed = $spaces = '';
extract( shortcode_atts( array(
	'title' => '',
	'onclick' => 'link_image',
	'custom_links' => '',
	'custom_links_target' => '',
	'img_size' => 'thumbnail',
	'images' => '',
	'el_class' => '',
	'mode' => 'horizontal',
	'slides_per_view' => '1',
	'wrap' => '',
	'autoplay' => '',
	'hide_pagination_control' => '',
	'hide_prev_next_buttons' => '',
	'speed' => '5000',
	'spaces' => 'no',
	'design' => '',
	'scroll_per_page' => 'yes',
	'partial_view' => ''
), $atts ) );
$gal_images = '';
$link_start = '';
$link_end = '';
$el_start = '';
$el_end = '';
$slides_wrap_start = '';
$slides_wrap_end = '';
$pretty_rand = $onclick == 'link_image' ? rand() : '';

if( $design == 'iphone' ) {
	$slides_per_view = 1;
	$img_size = '335x600';
	$spaces = 'none';
	$el_class .= ' iphone-carousel carousel-device';
}

if( $design == 'macbook' ) {
	$slides_per_view = 1;
	$img_size = '548x343';
	$spaces = 'none';
	$el_class .= ' macbook-carousel carousel-device';
}

if ( $onclick == 'link_image' ) {
	wp_enqueue_script( 'prettyphoto' );
	wp_enqueue_style( 'prettyphoto' );
}

$el_class = $this->getExtraClass( $el_class );

if ( $images == '' ) $images = '-1,-2,-3';

if ( $onclick == 'custom_link' ) {
	$custom_links = explode( ',', $custom_links );
}

$images = explode( ',', $images );
$i = - 1;
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_images_carousel wpb_content_element' . $el_class . ' vc_clearfix', $this->settings['base'], $atts );
$carousel_id = 'vc_images-carousel-' . WPBakeryShortCode_VC_images_carousel::getCarouselIndex();


?>
<div class="<?php echo apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, $this->settings['base'], $atts ) ?>">
	<div class="wpb_wrapper">
		<?php echo  wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_gallery_heading' ) ) ?>
		<div id="<?php echo esc_attr( $carousel_id ); ?>" class="vc_slide vc_images_carousel <?php if($spaces == 'yes') echo 'items-with-space' ?>">

			<!-- Wrapper for slides -->
			<div class="owl-carousel">
						<?php foreach ( $images as $attach_id ): ?>
							<?php
								$i ++;
								if ( $attach_id > 0 ) {
									$post_thumbnail = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => $img_size ) );
								} else {
									$post_thumbnail = array();
									$post_thumbnail['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
									$post_thumbnail['p_img_large'][0] = vc_asset_url( 'vc/no_image.png' );
								}
								$thumbnail = $post_thumbnail['thumbnail'];
							?>
							<div class="owl-carousel-item ">
								<div class="owl-carousel-item-inner">
									<?php if ( $onclick == 'link_image' ): ?>
									<?php $p_img_large = $post_thumbnail['p_img_large']; ?>
									<a class="prettyphoto"
									   href="<?php echo esc_url($p_img_large[0]); ?>" <?php echo ' rel="prettyPhoto[rel-' . $pretty_rand . ']"' ?>>
										<?php echo apply_filters( 'vc_images_carousel_thumbnail', $thumbnail ); ?>
									</a>
									<?php elseif ( $onclick == 'custom_link' && isset( $custom_links[$i] ) && $custom_links[$i] != '' ): ?>
									<a
									  href="<?php echo esc_url( $custom_links[$i] ); ?>"<?php echo ( ! empty( $custom_links_target ) ? ' target="' . $custom_links_target . '"' : '' ) ?>>
										<?php echo apply_filters( 'vc_images_carousel_thumbnail', $thumbnail ); ?>
									</a>
									<?php else: ?>
										<?php echo apply_filters( 'vc_images_carousel_thumbnail', $thumbnail ); ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<?php 
	$func_name = 'carousel_' . $carousel_id;
	$func_name = function() use( $carousel_id, $speed, $slides_per_view, $autoplay, $hide_pagination_control, $hide_prev_next_buttons, $scroll_per_page, $wrap ) {
		
		$items = array();
		$items['desktop'] = ($slides_per_view > 0) ? $slides_per_view : 1;
		$items['desktop_small'] = ($items['desktop'] > 1) ? $items['desktop'] - 1 : 1;
		$items['tablet'] = ($items['desktop_small'] > 1) ? $items['desktop_small'] - 1 : 1;
		$items['mobile'] = ($items['tablet'] > 1) ? $items['tablet'] - 1 : 1;

		if($items['mobile'] > 2) {
			$items['mobile'] = 2;
		}

		?>

		<script type="text/javascript">
			jQuery( document ).ready(function( $ ) {
				$("#<?php echo esc_js( $carousel_id ); ?> .owl-carousel").owlCarousel({
		            rtl: $('body').hasClass('rtl'),
		            items: <?php echo esc_js( $items['desktop'] ); ?>, 
		            responsive: {
		            	979: {
		            		items: <?php echo esc_js( $items['desktop'] ); ?>
		            	},
		            	768: {
		            		items: <?php echo esc_js( $items['desktop_small'] ); ?>
		            	},
		            	479: {
		            		items: <?php echo esc_js( $items['tablet'] ); ?>
		            	},
		            	0: {
		            		items: <?php echo esc_js( $items['mobile'] ); ?>
		            	}
		            },
		            autoplay: <?php echo 'yes' == $autoplay ? 'true' : 'false'; ?>,
		            autoplayTimeout: <?php echo esc_js( $speed ); ?>,
		            dots: <?php echo 'yes' == $hide_pagination_control ? 'false' : 'true'; ?>,
		            nav: <?php echo 'yes' == $hide_prev_next_buttons ? 'false' : 'true'; ?>,
		            slideBy:  <?php echo 'yes' == $scroll_per_page ? '\'page\'' : 1; ?>,
		            navText:false,
		            loop: <?php echo 'yes' == $wrap ? 'true' : 'false'; ?>,
		            onRefreshed: function() {
		            	$(window).resize();
		            }
				});
            	$(window).resize();
			});
		</script>

		<?php
	};

	add_action( 'basel_after_footer', $func_name );
 ?>
