<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 */

get_header(); ?>

<?php 
	
	// Get content width and sidebar position
	$content_class = basel_get_content_class();

?>


<div class="site-content <?php echo esc_attr( $content_class ); ?>" role="main">

		<?php /* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

				<?php if ( basel_get_opt( 'blog_share' ) ): ?>
					<div class="single-post-social">
						<?php if( function_exists( 'basel_shortcode_social' ) ) echo basel_shortcode_social(array('type' => 'share', 'tooltip' => 'yes', 'style' => 'colored')) ?>
					</div>
				<?php endif ?>
			
				<?php if ( basel_get_opt( 'blog_navigation' ) ): ?>
					<div class="single-post-navigation">
						 <?php previous_post_link('<div class="prev-link">%link</div>', esc_html__('Previous Post', 'basel')); ?> 
						 <?php next_post_link('<div class="next-link">%link</div>', esc_html__('Next Post', 'basel')); ?> 
					</div>
				<?php endif ?>

				<?php 

					if ( basel_get_opt( 'blog_related_posts' ) ) {
					    $args = basel_get_related_posts_args( $post->ID );

					    $query = new WP_Query( $args );

						 if( function_exists( 'basel_generate_posts_slider' ) ) echo basel_generate_posts_slider(array(
							'title' => esc_html__('Related Posts', 'basel'),
							'slides_per_view' => 2,
							'el_class' => 'basel-related-posts',
						), $query); 
					}

				?>

				<?php comments_template(); ?>

		<?php endwhile; ?>

</div><!-- .site-content -->


<?php get_sidebar(); ?>

<?php get_footer(); ?>
