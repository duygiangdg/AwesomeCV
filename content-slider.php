<?php
/**
 * The default template for displaying content in slider
 *
 */

$basel_loop = basel_loop_prop( 'basel_loop' );

$img = false;

if( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) {

	if( function_exists( 'wpb_getImageBySize' ) ) {
		$img_id = get_post_thumbnail_id();
		$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => basel_loop_prop( 'img_size' ), 'class' => 'content-slider-image' ) );
		$img = $img['thumbnail'];
	} else {
		$img = get_the_post_thumbnail( get_the_ID(), basel_loop_prop( 'img_size' ) );
	}

}

$classes[] = 'post-slide';
$classes[] = 'blog-design-masonry';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	
	<div class="post-head">
		<?php if ( ! is_wp_error( $img ) && $img ) : ?>
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<figure class="entry-thumbnail"><?php echo apply_filters( 'basel_content_slider_image', $img ); ?></figure>
			</a>
		<?php endif; ?>

		<?php basel_post_date(); ?>
	</div>

	<div class="post-mask">

		<?php if ( get_post_type() == 'post' ): ?>
			<?php if(get_the_category_list( ', ' ) ): ?>
				<div class="meta-post-categories"><?php echo get_the_category_list( ', ' ); ?></div>
			<?php endif; ?>

			<h3 class="entry-title">
				<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h3>
			<div class="entry-meta basel-entry-meta font-alt">
				<?php basel_post_meta(array(
						'labels' => 1,
						'author' => 0,
						'date' => 0,
						'edit' => 0,
						'comments' => 1
					));  
				?>
			</div><!-- .entry-meta -->
			
			<div class="entry-content">
				<?php basel_get_content(); ?>
			</div><!-- .entry-meta -->

		<?php else: ?>
			<h3 class="entry-title">
				<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h3>
		<?php endif ?>

	</div>

</article><!-- #post -->

<?php
// Increase loop count
basel_set_loop_prop( 'basel_loop', $basel_loop + 1 );
