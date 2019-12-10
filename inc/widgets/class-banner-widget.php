<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register widget based on VC_MAP parameters that display banner shortcode
 *
 */

if ( ! class_exists( 'BASEL_Banner_Widget' ) ) {
	class BASEL_Banner_Widget extends WPH_Widget {
	
		function __construct() {
			if( ! function_exists( 'basel_get_banner_params' ) ) return;
		
			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'BASEL Banner', 'basel' ), 
				// Widget Backend Description								
				'description' => esc_html__( 'Promo banner with text', 'basel' ), 
				'slug' => 'basel-banner',		
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = basel_get_banner_params();

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function

		function widget( $args, $instance )	{
			extract($args);
			echo wp_kses_post( $before_widget );
			echo basel_shortcode_promo_banner( $instance, $instance['content'] );
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
