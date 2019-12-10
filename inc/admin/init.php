<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue admin scripts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_admin_scripts' ) ) {
	function basel_admin_scripts() {
		$version = basel_get_theme_info( 'Version' );

		wp_enqueue_script( 'basel-admin-scripts', BASEL_ASSETS . '/js/admin.js', array(), $version, true );

		if( apply_filters( 'basel_gradients_enabled', true ) ) {
			wp_enqueue_script( 'basel-colorpicker-scripts', BASEL_ASSETS . '/js/colorpicker.min.js', array(), $version, true );
			wp_enqueue_script( 'basel-gradient-scripts', BASEL_ASSETS . '/js/gradX.min.js', array(), $version, true );
		}

		if ( basel_get_opt( 'size_guides' ) ) {
			wp_enqueue_script( 'basel-edittable-scripts', BASEL_ASSETS . '/js/jquery.edittable.min.js', array(), $version, true );
		}

		basel_admin_scripts_localize();

		//Slider
		wp_enqueue_script( 'jquery-ui-slider' );

		//VC Fields
		wp_enqueue_script( 'basel-slider', BASEL_ASSETS . '/js/vc-fields/slider.js', array(), $version, true );
		wp_enqueue_script( 'basel-responsive-size', BASEL_ASSETS . '/js/vc-fields/responsive-size.js', array(), $version, true );
		wp_enqueue_script( 'basel-vc-image-select', BASEL_ASSETS . '/js/vc-fields/image-select.js', array(), $version, true );
		wp_enqueue_script( 'basel-vc-colorpicker', BASEL_ASSETS . '/js/vc-fields/colorpicker.js', array(), $version, true );
		wp_enqueue_script( 'basel-vc-functions', BASEL_ASSETS . '/js/vc-fields/vc-functions.js', array(), $version, true );

	}
	add_action('admin_init','basel_admin_scripts', 100);
}

if ( ! function_exists( 'basel_frontend_editor_enqueue_scripts' ) ) {
	function basel_frontend_editor_enqueue_scripts() {
		$version = basel_get_theme_info( 'Version' );
		wp_enqueue_script( 'js-cookie', BASEL_SCRIPTS . '/js.cookie.js', array( 'jquery' ), $version, true );
		basel_enqueue_scripts();
		wp_enqueue_script( 'basel-frontend-editor-functions', BASEL_ASSETS . '/js/vc-fields/frontend-editor-functions.js', array(), $version, true );
	}
	
	add_action( 'vc_frontend_editor_enqueue_js_css', 'basel_frontend_editor_enqueue_scripts', 100 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Localize admin script function
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_admin_scripts_localize' ) ) {
	function basel_admin_scripts_localize() {
		wp_localize_script( 'basel-admin-scripts', 'baselConfig', basel_admin_script_local() );
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Get localization array for admin scripts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_admin_script_local' ) ) {
	function basel_admin_script_local() {
		$localize_data = array(
			'ajax' => admin_url( 'admin-ajax.php' ),
			'import_nonce' => wp_create_nonce( 'basel-import-nonce' ),
			'mega_menu_added_thumbnail_nonce' => wp_create_nonce( 'basel-mega-menu-added-thumbnail-nonce' ),
		);

		// If we are on edit product attribute page
		if( ! empty( $_GET['page'] ) && $_GET['page'] == 'product_attributes' && ! empty( $_GET['edit'] ) && function_exists('wc_attribute_taxonomy_name_by_id')) {
			$attribute_id   = sanitize_text_field( wp_unslash( $_GET['edit'] ) );
			$taxonomy_ids   = wc_get_attribute_taxonomy_ids();
			$attribute_name = array_search( $attribute_id, $taxonomy_ids, false );
			$localize_data['attributeSwatchSize'] = basel_wc_get_attribute_term( 'pa_' . $attribute_name, 'swatch_size' );
			$localize_data['attributeShowOnProduct'] = basel_wc_get_attribute_term( 'pa_' . $attribute_name, 'show_on_product' );
		}
		
		if( class_exists('Redux') ) {
			$redux_options = array();
			$options_key = 'basel_options';

			$redux_sections = Redux::getSections($options_key);


			foreach ($redux_sections as $id => $section) {
				if( ! isset( $section['subsection'] ) ) {
					$parent_name = $section['title'];
					$parent_icon = $section['icon'];
				} else {
					$redux_sections[$id]['parent_name'] = $parent_name;
					$redux_sections[$id]['icon'] = $parent_icon;
				}
			}

			$options = Redux::$fields[$options_key];

			foreach ($options as $id => $option) {
				if( ! isset( $option['title'] ) ) continue;
				$text = $option['title'];
				if( isset($option['desc']) ) $text .= ' ' . $option['desc'];
				if( isset($option['subtitle']) ) $text .= ' ' . $option['subtitle'];
				if( isset($option['tags']) ) $text .= ' ' . $option['tags'];

				if( isset( $redux_sections[$option['section_id']]['subsection'] ) ) {
					 $path = $redux_sections[$option['section_id']]['parent_name'] . ' -> ' . $redux_sections[$option['section_id']]['title'];
				} else {
					 $path = $redux_sections[$option['section_id']]['title'];
				}

				$redux_options[] = array(
					'id' => $id,
					'title' => $option['title'],
					'text' => $text,
					'section_id' => $redux_sections[$option['section_id']]['priority'],
					'icon' => $redux_sections[$option['section_id']]['icon'],
					'path' => $path,
				);
			}

			$localize_data['reduxOptions'] = $redux_options;
		}

		$localize_data['searchOptionsPlaceholder'] = esc_js(esc_html__('Search for options', 'basel'));

		return apply_filters( 'basel_admin_script_local', $localize_data );
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue admin styles
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_enqueue_admin_styles' ) ) {
	function basel_enqueue_admin_styles() {
		$version = basel_get_theme_info( 'Version' );

		if ( is_admin() ) {
			wp_enqueue_style( 'basel-admin-style', BASEL_ASSETS . '/css/theme-admin.css', array(), $version );
			if( apply_filters( 'basel_gradients_enabled', true ) ) {
				wp_enqueue_style( 'basel-colorpicker-style', BASEL_ASSETS . '/css/colorpicker.css', array(), $version );
				wp_enqueue_style( 'basel-gradient-style', BASEL_ASSETS . '/css/gradX.css', array(), $version );
			}
			if ( basel_get_opt( 'size_guides' ) ) {
				wp_enqueue_style( 'basel-edittable-style', BASEL_ASSETS . '/css/jquery.edittable.min.css', array(), $version );
			}

			wp_enqueue_style( 'basel-jquery-ui', BASEL_ASSETS . '/css/jquery-ui.css', array(), $version );
		}

	}

	add_action( 'admin_enqueue_scripts', 'basel_enqueue_admin_styles' );
}

