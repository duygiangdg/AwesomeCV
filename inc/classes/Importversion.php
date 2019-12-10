<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * BASEL_Import_Version
 *
 */


class BASEL_Importversion {
	
	private $_basel_versions = array();

	public $response;

	private $_importer;

	private $_file_path;

	private $_version;

	private $_active_widgets;

	private $_widgets_counter = 1;

	private $_process = array();

	private $_sliders = array();

	private $_debug = false;

	public $menu_ids = false;

	public $after_import = null;

	public function __construct( $version, $process, $sliders ) {

		$this->_version = $version;
		$this->_process = $process;
		$this->_sliders = $sliders;

		$this->_basel_versions = basel_get_config( 'versions' );

		$this->response = BASEL_Registry::getInstance()->ajaxresponse;

		$this->_file_path = BASEL_THEMEROOT . '/inc/dummy-content/';

		// Load importers API
		$this->_load_importers();

	}


	public function run_import() {


		if( ! $this->_is_valid_version_slug( $this->_version ) ) {

			$this->response->send_fail_msg( 'Wrong version name ' . $this->_version );

		}

		$this->before_import();

		// Import xml file
		if ( $this->_need_process('xml') ) {
			if( $this->_debug ) {
				$this->response->add_msg( 'XML DONE' );
			} else {
				$this->_import_xml();
			}
		}

		//  Set up home page
		if ( $this->_need_process('home') ) {
			if( $this->_debug ) {
				$this->response->add_msg( 'HOME PAGE' );
			} else {
				$this->_set_home_page();
			}
		}

		//  Set up widgets
		if ( $this->_need_process('widgets') ) {
			if( $this->_debug ) {
				$this->response->add_msg( 'WIDGETS DONE' );
			} else {
				$this->_set_up_widgets();
			}
		}

		// Import sliders 
		if ( $this->_need_process('sliders') ) {
			if( $this->_debug ) {
				$this->response->add_msg( 'SLIDERS DONE' );
			} else {
				$this->_import_sliders();
			}
		}

		// Import options
		if ( $this->_need_process('options') ) {
			if( $this->_debug ) {
				$this->response->add_msg( 'OPTIONS DONE' );
			} else {
				$this->_import_options();
			}
		}


		// Add page to menu
		if ( $this->_need_process('page_menu') ) {
			if( $this->_debug ) {
				$this->response->add_msg( 'page menu DONE' );
			} else {
				$this->add_page_menu();
			}
		}
		
		$this->after_import();


	}

	public function sizes_array( $sizes ) {
		return array();
	}

	private function _import_xml() {

		$file = $this->_get_file_to_import( 'content.xml' );
		
		// Check if XML file exists
		if( ! $file ) {
			$this->response->send_fail_msg( "File doesn't exist <strong>" . $this->_version . "/content.xml</strong>");
		} 

		try{

	    	ob_start();

	    	// Prevent generating of thumbnails for 8 sizes. Only original
	    	add_filter( 'intermediate_image_sizes', array( $this, 'sizes_array') );

			$this->_importer->fetch_attachments = true;//$this->_import_attachments;

			// Run WP Importer for XML file
			$this->_importer->import( $file );

			$output = ob_get_contents();

			ob_end_clean();
			
			$this->response->add_msg( $output );

			
		} catch (Exception $e) {
			$this->response->send_fail_msg("Error while importing");
		}
	}


	private function _set_home_page() {

		$home_page_title = 'Home ' . $this->_version;
		$home_page = get_page_by_title( $home_page_title );
		if( ! is_null( $home_page )) {

			update_option( 'page_on_front', $home_page->ID );
			update_option( 'show_on_front', 'page' );

			$this->response->add_msg( 'Front page set to <strong>"' . $home_page_title . '"</strong>' );
		} else {
			$this->response->add_msg( 'Front page is not changed' );
		}

	}

	public function add_page_menu() {
		
		$page_title 	= $this->_basel_versions[$this->_version]['title'];
		$parent_title 	= $this->_basel_versions[$this->_version]['parent_menu_title'];

		$this->add_menu_item_by_title($page_title, false, $parent_title );

	}

	public function add_menu_item_by_title( $title, $position = false, $parent_title = false, $menu = 'main', $custom_title = false, $meta = array() ) {

		$page = get_page_by_title( $title );

		if( is_null( $page ) ) return;

		$this->insert_menu_item( $title, $position, $page->ID, $parent_title, $menu, $custom_title, $meta );

		return $page->ID;

	}

	public function insert_menu_item( $page_title, $position = false, $page_id = false, $parent_title = false, $menu = 'main', $custom_title = false, $meta = array() ) {
		if( ! $this->menu_ids ) $this->set_menu_ids();

		$menu_id = $this->menu_ids[ $menu ];

		$all_items = wp_get_nav_menu_items($menu_id);

		if( $custom_title ) $page_title = $custom_title;

		$menu_item = array_filter( $all_items, function( $item ) use($page_title) {
			return $item->title == $page_title;
		});

		if( ! empty($menu_item) ) return;

		$args = array(
		    'menu-item-title' 		=> $page_title,
		    'menu-item-object' 		=> 'page',
		    'menu-item-type' 		=> 'post_type',
		    'menu-item-status' 		=> 'publish'
	    );

	    if( $position ) {
	    	$args['menu-item-position'] = $position;
	    }

	    if( $page_id ) {
	    	$args['menu-item-object-id'] = $page_id;
	    }

	    if( $parent_title ) {
			$parent_items = array_filter( $all_items, function( $item ) use($parent_title) {
				return $item->title == $parent_title;
			});

			$parent_item = array_shift( $parent_items );

	    	$args['menu-item-parent-id'] = $parent_item->ID;
	    }

	    $menu_item_id = wp_update_nav_menu_item( $menu_id, 0, $args );

		if( ! empty( $meta ) ) {
			foreach ($meta as $key => $value) {
				if( $key == 'content' ) {
					// Update the post into the database
					wp_update_post( array(
						'ID'           => $menu_item_id,
						'post_content' => $value
					) );
				} else {
					add_post_meta($menu_item_id, '_menu_item_' . $key, $value);
				}
			}
		}

	}


	public function set_menu_ids() {
		global $wpdb;

		$main_menu 		= get_term_by( 'name', 'Main navigation', 'nav_menu' );
		$topbar_menu 	= get_term_by( 'name', 'Top bar', 'nav_menu' );
		
		$this->menu_ids = array(
			'main' 		=> $main_menu->term_id,
			'topbar' 	=> $topbar_menu->term_id
		);
	}

	private function _set_up_widgets() {

		$widgets = basel_get_config( 'widgets-import' );

		$version_widgets_file = $this->_get_file_to_import( 'widgets.json' );

		if( $version_widgets_file ) {
			$version_widgets = json_decode( $this->_get_local_file_content( $version_widgets_file ), true );
			$widgets = wp_parse_args( $version_widgets, $widgets ); 
		}

	    // We don't want to undo user changes, so we look for changes first.
	    $this->_active_widgets = get_option( 'sidebars_widgets' );

		$this->_widgets_counter = 1;

	    foreach ($widgets as $area => $params) {
		    if ( ! empty ( $this->_active_widgets[$area] ) && $params['flush'] ) {
		    	$this->_flush_widget_area($area);
	    	} else if(! empty ( $this->_active_widgets[$area] ) && ! $params['flush'] ) {
	    		continue;
	    	}
	    	foreach ($params['widgets'] as $widget => $args) {
			    $this->_add_widget($area, $widget, $args);
	    	}
	    }

	    // Now save the $active_widgets array.
	    update_option( 'sidebars_widgets', $this->_active_widgets );

		$this->response->add_msg( 'Widgets updated' );

	}

	private function _add_widget( $sidebar, $widget, $options = array() ) {

		$this->_active_widgets[ $sidebar ][] = $widget . '-' . $this->_widgets_counter;

	    $widget_content = get_option( 'widget_' . $widget );

	    $widget_content[ $this->_widgets_counter ] = $options;

	    update_option(  'widget_' . $widget, $widget_content );

		$this->_widgets_counter++;
	}

	private function _flush_widget_area( $area ) {

		unset($this->_active_widgets[ $area ]);

	}


	private function _import_sliders() {
		if( ! class_exists('RevSlider') ) return;
		foreach ( $this->_sliders as $slider_name ) {
			$this->_revolution_import( $slider_name . '.zip' );
		}
	}

	private function _revolution_import( $filename ) {
		if( ! apply_filters('basel_old_sliders_import', false ) ) {
			$file = $this->_download_file_to_import( $filename );
		} else {
			$file = $this->_get_file_to_import( $filename );
		}
		if( ! $file ) return;
		$revapi = new RevSlider();
		ob_start();
		$slider_result = $revapi->importSliderFromPost(true, true, $file);
		ob_end_clean();
	}

	private function _download_file_to_import( $filename ) {

		$file = BASEL_DUMMY_URL  . $this->_version . '/' . $filename;

		try {
			$zip_file = download_url( $file );
			if( is_wp_error( $zip_file ) ) {
				$this->response->add_msg( 'Can\'t download revslider file from the server ' . $file );
				return false;
			}
		} catch( Exception $e ) {
			$this->response->add_msg( 'Can\'t download revslider file from the server ' . $file );
			return false;
		}

		return $zip_file;
	}

	private function _get_file_to_import( $filename ) {

		$file = $this->_get_version_folder() . $filename;

		// Check if ZIP file exists
		if( ! file_exists( $file ) ) {
			return false;
		} 

		return $file;
	}

	private function _get_version_folder( $version = false ) {
		if( ! $version ) $version = $this->_version;

		return $this->_file_path . $this->_version . '/';
	}

	private function _import_options() {
		global $basel_options;

		$file = $this->_get_file_to_import( 'options.json' );

		if( ! $file ) return;

		try{

			if( class_exists('ReduxFrameworkInstances') ) {
				
				$new_options = json_decode( $this->_get_local_file_content( $file ), true );


				// Merge new options with new resetting values
				$version_type = $this->_basel_versions[$this->_version]['type'];
				$new_options = wp_parse_args( $new_options, $this->_get_reset_options( $version_type ) ); 

				// Merge new options with other existed ones
				$new_options = wp_parse_args( $new_options, $basel_options ); 

				$redux = ReduxFrameworkInstances::get_instance( 'basel_options' );

	            if ( isset ( $redux->validation_ran ) ) {
	                unset ( $redux->validation_ran );
	            }

	            $redux->set_options( $redux->_validate_options( $new_options ) );
			}

			
		} catch (Exception $e) {
			$this->response->send_fail_msg("Error while importing options");
		}
		$this->response->add_msg( 'Options updated' );
	}

	private function _get_reset_options( $version_type ) {
		$reset_options = array();
		( $version_type == 'base') ? $version_type = 'version' : '';
		$reset_options_keys = basel_get_config( 'reset-options-' . $version_type );

		foreach ( $reset_options_keys as $opt ) {
			$reset_options[ $opt ] = $this->_get_default_option_value( $opt );
		}

		return $reset_options;
	}

	private function _get_default_option_value( $key ) {
		if( ! class_exists( 'Redux' ) ) return false;
		
		$field = Redux::getField( 'basel_options', $key);

		return ( isset( $field['default'] ) ? $field['default'] : '' );
	}

	private function _get_local_file_content( $file ) {
		ob_start();
		include $file;
		$file_content = ob_get_contents();
		ob_end_clean();
		return $file_content;
	}


	private function _load_importers() {

		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if( ! function_exists( 'BASEL_Theme_Plugin' ) ) {

			$this->response->send_fail_msg( 'Please install theme core plugin' );

		}

		// $this->_import_attachments = ( ! empty($_GET['import_attachments']) );

		$importerError = false;

		//check if wp_importer, the base importer class is available, otherwise include it
		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) 
				require_once($class_wp_importer);
			else 
				$importerError = true;
		}

		$plugin_dir = BASEL_Theme_Plugin()->plugin_path();

		$path = apply_filters('basel_require', $plugin_dir . '/importer/wordpress-importer.php');

		if( file_exists( $path ) ) {
			require_once $path;
		} else {
			$this->response->send_fail_msg( 'wordpress-importer.php file doesn\'t exist' );
		}

		if($importerError !== false) {
			$this->response->send_fail_msg( "The Auto importing script could not be loaded. Please use the wordpress importer and import the XML file that is located in your themes folder manually." );
		} 

		if(class_exists('WP_Importer') && class_exists('WP_Import')){
			
			$this->_importer = new WP_Import();

		} else {

			$this->response->send_fail_msg( 'Can\'t find WP_Importer or WP_Import class' );

		}

	}


	private function _is_valid_version_slug( $ver ) {
		if( in_array($ver, array_keys( $this->_basel_versions ) )) return true;
		return false;
	}


	private function _need_process( $process ) {
		return in_array($process, $this->_process);
	}

	
	public function update_option( $key, $value ) {
		global $basel_options;
		try{

			if( class_exists('ReduxFrameworkInstances') ) {

				$basel_options[ $key ] = $value;

				$redux = ReduxFrameworkInstances::get_instance( 'basel_options' );

	            if ( isset ( $redux->validation_ran ) ) {
	                unset ( $redux->validation_ran );
	            }

	            $redux->set_options( $redux->_validate_options( $basel_options ) );
			}

			
		} catch (Exception $e) {
			$this->_response->send_fail_msg("Error while importing options");
		}
	}

	public function before_import() {

		$file = $this->_get_version_folder() . 'after.php';

		if( ! file_exists( $file ) ) return;
		require $file;

		$base_import_class = 'BASEL_Importversion_';
		$version_import_class = $base_import_class . $this->_version;

		if( ! class_exists( $version_import_class ) ) return;

		$this->after_import = new $version_import_class( false, false, false );

		$this->after_import->before();
	}

	public function after_import() {

		if( $this->after_import == null ) return;

		$this->after_import->after();
	}

}