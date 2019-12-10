<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Execute after import
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'BASEL_ImportVersion_base' ) ):
class BASEL_ImportVersion_base extends BASEL_ImportVersion {

	public $shop_page_id;
	public $menu_id;

	public function before() {
	}

	public function after() {
		$this->menu_locations();
		$this->blog_page();
		$this->shop_page();
		$this->shop_menu();
		$this->topbar_menu();
		$this->categories_images();
		$this->set_attribute_terms_colors();
		$this->enable_VC();
		$this->show_all_fields_menu();
		$this->enable_myaccount_registration();
	}

	public function menu_locations() {
		global $wpdb;

		$location 		 = 'main-menu';
		$mobile_location = 'mobile-menu';
		$topbar_location = 'top-bar-menu';

		$tablename 	= $wpdb->prefix . 'terms';
		$menu_ids 	= $wpdb->get_results(
		    "
		    SELECT term_id, name
		    FROM " . $tablename . " 
		    WHERE name IN ('Main navigation', 'Mobile navigation', 'Categories', 'Top bar')
		    ORDER BY name ASC
		    "
		);

	    $locations = get_theme_mod('nav_menu_locations');
	    
		foreach ($menu_ids as $menu) {
			if( $menu->name == 'Categories' ) $this->update_option( 'categories-menu', $menu->term_id );

			if( $menu->name == 'Main navigation' ) {
				$this->menu_id = $menu->term_id;
				if( ! has_nav_menu( $location ) ){
				    $locations[$location] 		= $this->menu_id;
				}
			}

			if( $menu->name == 'Mobile navigation' ) {
				if( ! has_nav_menu( $mobile_location ) ){
				    $locations[$mobile_location] = $menu->term_id;
				}
			}

			if( $menu->name == 'Top bar' ) {
				if( ! has_nav_menu( $topbar_location ) ){
				    $locations[$topbar_location] = $menu->term_id;
				}
			}
		}
		

		set_theme_mod( 'nav_menu_locations', $locations );


	}

	public function blog_page() {
		// Add blog item to the menu
		$blog_page_title = 'Blog';
		$blog_page = get_page_by_title( $blog_page_title );
		if( ! is_null( $blog_page ) ) {
			update_option( 'page_for_posts', $blog_page->ID );
			update_option( 'show_on_front', 'page' );
		}

		// Move Hello World post to trash
		wp_trash_post( 1 );
		 
		// Move Sample Page to trash
		wp_trash_post( 2 );
	}

	public function shop_page() {
	 	// Setup shop page
		$this->shop_page_id = $this->add_menu_item_by_title( 
			'Shop', 
			2, 
			false, 
			'main', 
			false, 
			array( 
				'content' => '[html_block id="244"]', 
				'design' => 'full-width'
			) 
		);

		$shop_metas = array(
			'_basel_page-title-size' => 'small',
		);

		foreach ($shop_metas as $key => $value) {
			update_post_meta( $this->shop_page_id, $key, $value);
		}

	}

	public function topbar_menu() {

		$this->add_menu_item_by_title( 'My account', -1, false, 'topbar', false, array( 'icon' => 'user' ) );
		$this->add_menu_item_by_title( 'Cart', 0, false, 'topbar' );

	}

	public function shop_menu() {
		$this->add_menu_item_by_title( 'Shop', 0, 'Pages', 'main', 'WC Pages' );
		// $this->add_menu_item_by_title( 'Cart', 0, 'WC Pages', 'main', false, array( 'icon' => 'user' ) );
		$this->add_menu_item_by_title( 'Checkout', 0, 'WC Pages', 'main', false, array( 'icon' => 'credit-card' ) );
		$this->add_menu_item_by_title( 'My account', 0, 'WC Pages', 'main', false, array( 'icon' => 'user' ) );
		$this->add_menu_item_by_title( 'Shop', 0, 'WC Pages', 'main', 'Products', array( 'icon' => 'shopping-cart' ) );
		$this->add_menu_item_by_title( 'Wishlist', 0, 'WC Pages', 'main', false, array( 'icon' => 'heart' ) );
	}

	public function categories_images() {
		$categories = array('Accessories', 'Man', 'Woman', 'Bags', 'Shoes');
		$attachment_id = 126;
		foreach ($categories as $cat) {
			$cat = get_term_by( 'name', $cat, 'product_cat' );
			add_term_meta($cat->term_id, 'thumbnail_id', $attachment_id);
		}
	}

	public function set_attribute_terms_colors() {
		global $wpdb;
		$terms = array('Black' => '#000000', 'Blue' => '#1e73be', 'Brown' => '#997521');
		$product_id = 22;
		$post_terms = array();
		foreach ($terms as $term_name => $color) {
			$term = get_term_by( 'name', $term_name, 'pa_color' );
			add_term_meta( $term->term_id, 'color', $color, true );
			$post_terms[] = $term->term_id;
		}

		wp_set_object_terms( $product_id, $post_terms, 'pa_color' );

		foreach ($post_terms as $value) {
			$wpdb->update( 
				$wpdb->term_taxonomy, 
				array( 
					'count' => 1,	// string
				), 
				array( 'term_id' => $value ), 
				array( 
					'%d',	
				), 
				array( '%d' ) 
			);
		}
		delete_transient('wc_term_counts');

		// $this->response->add_msg( 'Terms updated' );
	}

	public function enable_VC() {
		if( ! function_exists( 'vc_path_dir' ) ) return;
		$file = vc_path_dir( 'SETTINGS_DIR', 'class-vc-roles.php' );
		if( ! file_exists( $file ) ) return;
		require_once $file;
		if( ! class_exists( 'Vc_Roles' ) ) return;
		$vc_roles = new Vc_Roles();
		$data = $vc_roles->save( array(
			'administrator' => json_decode( '{"post_types":{"_state":"custom","post":"1","page":"1","basel_slide":"1","basel_size_guide":"1","cms_block":"1","basel_sidebar":"0","portfolio":"1","product":"1"},"backend_editor":{"_state":"1","disabled_ce_editor":"0"},"frontend_editor":{"_state":"1"},"post_settings":{"_state":"1"},"settings":{"_state":"1"},"templates":{"_state":"1"},"shortcodes":{"_state":"1"},"grid_builder":{"_state":"1"},"presets":{"_state":"1"}}')
 		) );
		// echo json_encode( $data );
	}

	public function show_all_fields_menu() {
		$user_id = 1;
		update_user_meta( $user_id, 'managenav-menuscolumnshidden', array() );
		update_user_meta( $user_id, 'metaboxhidden_nav-menus', array() );
	}

	public function enable_myaccount_registration() {
		update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
	}

}

endif;
