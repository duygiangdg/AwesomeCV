<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
 * Class to work with Theme Options
 * Will modify global $basel_options variable
 * 
 */
class BASEL_Options {

	public function __construct() {

		$options = get_option('basel_options');

	    if ( ! class_exists( 'Redux' ) || ! $options || empty( $options ) ) {
	        $this->_load_base_options();
	    }

		if( ! is_admin() ) {

			add_action( 'wp', array( $this, 'set_custom_meta_for_post' ), 10 );
			add_action( 'wp', array( $this, 'set_options_for_post' ), 20 );
			add_action( 'wp', array( $this, 'specific_options' ), 30 );
			add_action( 'wp', array( $this, 'specific_taxonomy_options' ), 40 );

		}
	}

	/**
	 * Load basic options if redux is not installed
	 */
	private function _load_base_options() {
		global $basel_options;

		$basel_options = basel_get_config( 'base-options' );

	}

	/**
	 * Specific options
	 */
	public function set_options_for_post($slug = '') {
		global $basel_options;

		$custom_options = json_decode( get_post_meta( basel_page_ID(), '_basel_options', true), true );

		if( ! empty($custom_options) ) {
			$basel_options = wp_parse_args( $custom_options, $basel_options ); 
		}

		$basel_options = apply_filters( 'basel_global_options', $basel_options );

	}

		
	/**
	 * [set_custom_meta_for_post description]
	 */
	public function set_custom_meta_for_post($slug = '') {
		global $basel_options, $basel_transfer_options, $basel_prefix;

		if( ! empty( $basel_transfer_options ) ) {
			foreach ($basel_transfer_options as $field) {
				$meta = get_post_meta( basel_page_ID(), $basel_prefix . $field, true );
				$basel_options[$field] = ( isset($meta) && $meta != '' && $meta != 'inherit' && $meta != 'default' ) ? $meta : $basel_options[$field];
			}
		}

	}


	/**
	 * Specific options dependencies
	 */
	public function specific_options($slug = '') {
		global $basel_options;

		$rules = basel_get_config( 'specific-options' );

		foreach ($rules as $option => $rule) {
			if( ! empty( $rule['will-be'] ) && ! isset( $rule['if'] )) {
				$basel_options[ $option ] = $rule['will-be'];
			} elseif( isset($basel_options[ $rule['if'] ]) && in_array( $basel_options[ $rule['if'] ], $rule['in_array'] ) ) {
				$basel_options[ $option ] = $rule['will-be'];
			}
		}

	}


	/**
	 * Specific options for taxonomies
	 */
	public function specific_taxonomy_options($slug = '') {
		global $basel_options;

		if ( is_category() ) {
			$option_key = 'blog_design';
			$category = get_query_var( 'cat' );
			$current_category = get_category( $category );
			//$current_category->term_id;
				
			$category_blog_design = basel_tax_data( 'category', $current_category->term_id, '_basel_' . $option_key );

			if( ! empty($category_blog_design) && $category_blog_design != 'default' && $category_blog_design != 'inherit' ) {
				$basel_options[ $option_key ] = $category_blog_design;
			}
		 }

	}


	/**
	 * Get option from Redux array $basel_options
	 * @param  String option slug
	 * @return String option value
	 */
	public function get_opt( $slug ) {
		global $basel_options;

		return isset($basel_options[$slug]) ? $basel_options[$slug] : '';
	}

}

// **********************************************************************// 
// ! Function to get option from Redux Framework
// **********************************************************************// 
if( ! function_exists( 'basel_get_opt' ) ) {
	function basel_get_opt($slug = '') {
		return BASEL_Registry::getInstance()->options->get_opt( $slug );
	}
}