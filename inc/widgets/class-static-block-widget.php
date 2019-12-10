<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register widget that displays HTML static block
 *
 */

if ( ! class_exists( 'BASEL_Static_Block_Widget' ) ) {
	class BASEL_Static_Block_Widget extends WPH_Widget {
	
		function __construct() {
			
		
			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'BASEL HTML Block', 'basel' ), 
				// Widget Backend Description								
				'description' => esc_html__( 'Display HTML block', 'basel' ), 	
				'slug' => 'basel-html-block',	
			 );
		
		
			// fields array

			$args['fields'] = array( 	
				array(
					'id' => 'id',
					'type' => 'dropdown', 
					'heading' => 'Select block',
					'value' => basel_get_static_blocks_array()
				)
			
			 ); // fields array

			// create widget
			$this->create_widget( $args );
		}
		
		// Output function

		function widget( $args, $instance )	{
	
			echo basel_get_html_block( $instance['id'] );
		}
	
	} // class
}
