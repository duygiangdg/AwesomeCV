<?php
/**
 * The main template file
 */

get_header(); ?>

<?php 

	// Get content width and sidebar position
	$content_class = basel_get_content_class();
	$blog_design = basel_get_opt('blog_design');
	
	if( in_array( $blog_design, array( 'masonry', 'mask' ) ) ) {
		$content_class .= ' row';
	}

?>

<div class="site-content <?php echo esc_attr( $content_class ); ?>" role="main">

	<?php do_action( 'basel_main_loop' ); ?>

</div><!-- .site-content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>