<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 */

$basel_loop     = basel_loop_prop( 'basel_loop' );
$blog_design    = basel_loop_prop( 'blog_design' );
$columns        = basel_loop_prop( 'blog_columns' );
$is_shortcode   = basel_loop_prop( 'blog_type' ) == 'shortcode';
$is_large_image = basel_get_opt( 'single_post_design' ) == 'large_image' && is_single();
$classes        = array();

if ( $is_large_image ) $classes[] = 'post-single-large-image';
 
if( is_single() && !$is_shortcode ) {
	$classes[] = 'post-single-page';
}

$classes[] = 'blog-design-' . $blog_design;
$classes[] = 'blog-post-loop';

if( ! is_single() || $is_shortcode ) {
	$classes[] = 'blog-style-' . basel_get_opt( 'blog_style' );
}

if( is_single() && !$is_shortcode ) {
	$blog_design = 'default';
}

if( ( $blog_design == 'masonry' || $blog_design == 'mask' ) && ( $is_shortcode || ! is_single() ) ){
	$classes[] = basel_get_grid_el_class( $basel_loop, $columns, false, 12 );
}

if( get_the_title() == '' ){
	$classes[] = 'post-no-title';
}

$gallery_slider = apply_filters( 'basel_gallery_slider', true );
$gallery = array();

if( get_post_format() == 'gallery' && $gallery_slider ) {
	$gallery = get_post_gallery(false, false);
}

$random = 'carousel-' . rand(100,999);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<?php if ( $blog_design == 'default-alt' ): ?>
		<?php if ( get_the_category_list( ', ' ) ): ?>
			<div class="meta-post-categories"><?php echo get_the_category_list( ', ' ); ?></div>
		<?php endif ?>

		<?php if ( is_single() && basel_loop_prop( 'parts_title' ) && !$is_shortcode ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php elseif( basel_loop_prop( 'parts_title' ) ) : ?>
			<h3 class="entry-title">
				<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h3>
		<?php endif; // is_single() ?>

		<?php if ( basel_loop_prop( 'parts_meta' ) ): ?>
			<div class="entry-meta basel-entry-meta">
				<?php basel_post_meta(array(
					'date' => 0,
					'labels' => 1,
					'short_labels' => ( in_array( $blog_design, array( 'masonry', 'mask' ) ) ) 
				)); ?>
			</div><!-- .entry-meta -->
		<?php endif ?>
	<?php endif ?>
	<?php 
		$owl_atts = basel_get_owl_attributes( array(
			'carousel_id' => $random,
			'slides_per_view' => 1,
			'hide_pagination_control' => 'yes'
		) );
	?>
	<header class="entry-header">
		<?php if ( ( has_post_thumbnail() || ! empty( $gallery['src'] ) ) && ! post_password_required() && ! is_attachment() && basel_loop_prop( 'parts_media' ) ) : ?>
			<figure id="<?php echo esc_attr( $random ); ?>" class="entry-thumbnail" <?php echo ! empty( $owl_atts ) ? $owl_atts : ''; ?>>
				<?php if($blog_design == 'default-alt' ) basel_post_date(); ?>

				<?php if( get_post_format() == 'gallery' && $gallery_slider && ! empty( $gallery['src'] ) ): ?>
					<ul class="post-gallery-slider owl-carousel">
						<?php 
							foreach ( $gallery['src'] as $src ) { if ( preg_match( "/data:image/is", $src ) ) continue;
								?>
									<li> 
										<?php echo apply_filters( 'basel_image', '<img src="'. esc_url( $src ) .'" />' ); ?>
									</li>
								<?php
							}
						?>
					</ul>
				<?php elseif ( ! is_single() || $is_shortcode ): ?>

					<div class="post-img-wrapp">
						<a href="<?php echo esc_url( get_permalink() ); ?>">
							<?php echo basel_get_post_thumbnail( 'large' ); ?>
						</a>
					</div>
					<div class="post-image-mask">
						<a href="<?php echo esc_url( get_permalink() ); ?>"><?php _e("Read More", 'basel'); ?></a>
					</div>
					
				<?php elseif ( ! $is_large_image ): ?>
					<?php the_post_thumbnail( 'full' ); ?>
				<?php endif ?>

			</figure>
		<?php endif; ?>

		<?php if ( $blog_design != 'default-alt' && ! $is_large_image ): ?>

			<?php basel_post_date(); ?>

			<div class="post-mask">
				<?php if ( get_the_category_list( ', ' ) ): ?>
					<div class="meta-post-categories"><?php echo get_the_category_list( ', ' ); ?></div>
				<?php endif ?>

				<?php if ( is_single() && basel_loop_prop( 'parts_title' ) ) : ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php elseif( basel_loop_prop( 'parts_title' ) ) : ?>
					<h3 class="entry-title">
						<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
					</h3>
				<?php endif; // is_single() ?>

				<?php if ( basel_loop_prop( 'parts_meta' ) ): ?>
					<div class="entry-meta basel-entry-meta">
						<?php basel_post_meta(array(
							'date' => 0,
							'labels' => 1,
							'short_labels' => ( in_array( $blog_design, array( 'masonry', 'mask' ) ) ) 
						)); ?>
					</div><!-- .entry-meta -->
				<?php endif ?>
			</div>
		<?php endif ?>

	</header><!-- .entry-header -->

	<?php if ( is_search() && basel_loop_prop( 'parts_text' ) && get_post_format() != 'gallery' ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	<?php elseif(basel_loop_prop( 'parts_text' ) ) : ?>
		<div class="entry-content">
			<?php basel_get_content( basel_loop_prop( 'parts_btn' ), is_single() && ! $is_shortcode ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'basel' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>

	<div class="liner-continer">
		<span class="left-line"></span>
		<?php if( function_exists( 'basel_shortcode_social' ) ) echo basel_shortcode_social( array( 'style' => 'circle', 'size' => 'small' ) ); ?>
		<span class="right-line"></span>
	</div>

	<?php if ( is_single() && get_the_author_meta( 'description' ) && ! $is_shortcode ) : ?>
		<footer class="entry-meta">
			<?php get_template_part( 'author-bio' ); ?>
		</footer><!-- .entry-meta -->
	<?php endif; ?>
</article><!-- #post -->

<?php
// Increase loop count
basel_set_loop_prop( 'basel_loop', (int)$basel_loop + 1 );
