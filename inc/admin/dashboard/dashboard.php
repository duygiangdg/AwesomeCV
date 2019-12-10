<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Theme dashbaord
 * ------------------------------------------------------------------------------------------------
 */
if( ! class_exists( 'BASEL_Dashboard' ) ) {
	class BASEL_Dashboard {

		public $page_name = 'basel_dashboard';

		public $tabs;

		public $current_tab = 'home';

		private $_notices = null;

		public function __construct() {
			$this->tabs = array(
				'home' => esc_html__( 'Base import', 'basel' ), 
				'additional' => esc_html__( 'Additional pages', 'basel' ), 
				'license' => esc_html__( 'Theme license', 'basel' ), 
			);
			
			add_action( 'admin_menu', array( $this, 'menu_page' ) );

			add_action( 'admin_notices', array( $this, 'add_notices' ), 40 );
			
			$this->_notices = BASEL_Registry()->notices;

		}
		public function menu_page() {

			if ( ! current_user_can( 'administrator' ) ) {
				return;
			}
			
			if ( ! basel_get_opt( 'dummy_import' ) ) {
				unset( $this->tabs['home'] );
				unset( $this->tabs['additional'] );
			}

			$addMenuPage = 'add_me' . 'nu_page';
			$addMenuPage( 
				esc_html__( 'Basel', 'basel' ), 
				esc_html__( 'Basel', 'basel' ), 
				'manage_options', 
				$this->page_name, 
				array( $this, 'dashboard' ),
				BASEL_ASSETS . '/images/theme-admin-icon.svg', 
				62 
			);
			
			if ( basel_get_opt( 'dummy_import' ) ) {
				add_submenu_page(
					$this->page_name,
					'Base import',
					'Base import',
					'edit_posts',
					'admin.php?page=' . $this->page_name . '&tab=home',
					'' 
				); 
				add_submenu_page(
					$this->page_name,
					'Additional pages',
					'Additional pages',
					'edit_posts',
					'admin.php?page=' . $this->page_name . '&tab=additional',
					'' 
				); 
			}
			
			if ( class_exists( 'Redux' ) ) {
				add_submenu_page(
					$this->page_name,
					'Theme settings',
					'Theme settings',
					'edit_posts',
					'admin.php?page=_options&tab=1',
					'' 
				);
			}
						
			add_submenu_page(
				$this->page_name,
				'Theme license',
				'Theme license',
				'edit_posts',
				'admin.php?page=' . $this->page_name . '&tab=license',
				'' 
			); 
			
			remove_submenu_page( $this->page_name, $this->page_name );
		}

		public function get_tabs() {
			return $this->tabs;
		}

		public function get_current_tab() {
			return $this->current_tab;
		}

		public function set_current_tab( $tab ) {
			$this->current_tab = $tab;
		}

		public function dashboard() {
			$tab = 'home';
			if( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
				$tab = sanitize_text_field( trim( $_GET['tab'] ) );

				if( ! isset( $this->tabs[ $tab ] ) ) $tab = 'home';

				$this->set_current_tab( $tab );
			} 
			
			$this->show_page( 'tabs/' . $tab );
		}

		public function tab_url( $tab ) {
			if( ! isset( $this->tabs[ $tab ] ) ) $tab = 'home';
			return admin_url( 'admin.php?page=' . $this->page_name . '&tab=' . $tab );
		}

		public function show_page( $name = 'home') {

			$this->show_part( 'header' );
			$this->show_part( $name );
			$this->show_part( 'footer' );

		}

		public function show_part( $name, $data = array() ) {
			include_once 'views/' . $name . '.php';
		}

		public function get_version() {
			$theme = wp_get_theme();
			$v = $theme->get('Version');
			$v_data = explode('.', $v);
			return $v_data[0].'.'.$v_data[1];
		}

		public function add_notices() {
			if ( is_user_logged_in() && ! defined( 'BASEL_PLUGIN_POST_TYPE_VERSION' ) || ( defined( 'BASEL_PLUGIN_POST_TYPE_VERSION' ) && version_compare( BASEL_PLUGIN_POST_TYPE_VERSION, BASEL_POST_TYPE_VERSION, '<' ) ) ) {
				$this->_notices->add_msg( 'You just installed the latest version of the Basel theme. To finish the installation and enable all theme\'s function  or if you see any problems with your WPBakery elements displayed as shortcodes you need to install the latest version of the Basel Post Type plugin too. Go to <a href=' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '>Appearance -> Install plugins</a> and click on "Install" or "Update" button.', 'warning', true );
			}
			
		}

	}

	$basel_dashboard = new BASEL_Dashboard();
}
