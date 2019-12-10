<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register widget based on VC_MAP parameters that display author area shortcode
 *
 */

if ( ! class_exists( 'BASEL_Author_Area_Widget' ) ) {
	class BASEL_Author_Area_Widget extends WPH_Widget {
	
		function __construct() {
			if( ! function_exists( 'basel_get_banner_params' ) ) return;
		
			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'BASEL Author Information', 'basel' ), 
				// Widget Backend Description								
				'description' => esc_html__( 'Small information block about blog author', 'basel' ),
				'slug' => 'basel-author-information', 		
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = basel_get_author_area_params();

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function

		function widget( $args, $instance )	{
			extract($args);

			echo wp_kses_post( $before_widget );

			if(!empty($instance['title'])) { echo wp_kses_post( $before_title ) . $instance['title'] . wp_kses_post( $after_title ); };

			do_action( 'wpiw_before_widget', $instance );

			$instance['title'] = '';

			echo basel_shortcode_author_area( $instance, $instance['content'] );

			do_action( 'wpiw_after_widget', $instance );

			echo wp_kses_post( $after_widget );
		}

		function form( $instance ) {
			$id = uniqid();
			echo '<div class="widget-'. $id .'">';
			parent::form( $instance );
			echo "<script type=\"text/javascript\">
				jQuery(document).ready(function() {
					if ( typeof basel_media_init !== 'undefined' ) {
						basel_media_init('.widget-". $id ." .basel-image-upload', '.widget-". $id ." .basel-image-upload-btn', '.widget-". $id ." .basel-image-src');
					}
				});
			</script>";
			echo '</div>';
		}
	
	} // class
}
