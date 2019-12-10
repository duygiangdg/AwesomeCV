<?php
if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
* ------------------------------------------------------------------------------------------------
*  Compare element map
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'basel_vc_map_compare' ) ) {
	function basel_vc_map_compare() {
		vc_map(
			array(
				'name'        => esc_html__( 'Compare', 'basel' ),
				'base'        => 'basel_compare',
				'category'    => esc_html__( 'Theme elements', 'basel' ),
				'description' => esc_html__( 'Required for the compare table page.', 'basel' ),
				'icon'        => BASEL_ASSETS . '/images/vc-icon/compare.svg',
			)
		);
	}
	add_action( 'vc_before_init', 'basel_vc_map_compare' );
}
