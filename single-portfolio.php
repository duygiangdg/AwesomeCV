<?php
/**
 * The template for displaying single project
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

				<div class="portfolio-single-content">
					<?php the_content( esc_html__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'basel' ) ); ?>
				</div>
				<?php
				    $args = array(
				    	'post_type' => 'portfolio',
						'order' => 'DESC',
						'orderby' => 'date',
						'posts_per_page' => 12,
						'post__not_in' => array( get_the_ID() )
				    );

				    $query = new WP_Query( $args );
				    if ( basel_get_opt( 'portfolio_related' ) ) {
						echo basel_generate_posts_slider(array(
							'title' => esc_html__('Related Projects', 'basel'),
							'slides_per_view' => 3,
							'hide_pagination_control' => 'yes'
						), $query); 
					}
				 ?>

		<?php endwhile; ?>

</div><!-- .site-content -->


<?php get_sidebar(); ?>

<?php get_footer(); ?>