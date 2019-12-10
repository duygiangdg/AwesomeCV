<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * BASEL_Import
 *
 */

class BASEL_Import {
	
	private $_basel_versions = array();

	private $_response;

	private $_version;

	private $_process = array();

	private $_sliders = array();

	public function __construct() {

		$this->_basel_versions = basel_get_config( 'versions' );

		$this->_response = BASEL_Registry::getInstance()->ajaxresponse;

		add_action( 'wp_ajax_basel_import_data', array( $this, 'import_action' ) ); 

		if( isset( $_GET['clean_data'] ) ) $this->clean_imported_version_data();

		if( isset( $_GET['clean_attr'] ) ) $this->clean_import_attributes_data();

	}

	public function admin_import_screen( $type = false ) {
		$btn_label = esc_html__('Import page', 'basel');
		$activate_label = '';
		$shop_page = get_option( 'woocommerce_shop_page_id' );
		$this->basel_import_attributes();
		?>
			<div class="wrap metabox-holder basel-import-page">

				<?php if ( ! function_exists( 'is_shop' ) ): ?>
					<p class="basel-notice">
						<?php 
							printf(
								wp_kses( __('To import data properly we recommend you to install <strong><a href="%s">WooCommerce</a></strong> plugin', 'basel'), 'default' ), 
								esc_url( add_query_arg( 'page', urlencode( 'tgmpa-install-plugins' ), self_admin_url( 'themes.php' ) ) )
							); 
						?>
					</p>
				<?php endif ?>
				
				<?php if ( !$shop_page ): ?>
					<p class="basel-warning">
						<?php 
							esc_html_e( 'It seems that you didn\'t run WooCommerce setup wizard or didn\'t create a shop and the import can\'t be run now. You need to run WooCommerce setup wizard or install pages manually via WooCommerce -> System status -> Tools.', 'basel' );
						?>
					</p>
				<?php endif ?>

				<?php if( $this->_required_plugins() ): ?>
					<p class="basel-warning">
						<?php 
							printf(
								wp_kses( __('You need to install the following plugins to use our import function: <strong><a href="%s">%s</a></strong>', 'basel'), 'default' ), 
								esc_url( add_query_arg( 'page', urlencode( 'tgmpa-install-plugins' ), self_admin_url( 'themes.php' ) ) ),
								implode(', ', $this->_required_plugins()) 
							); 
						?>
					</p>
				<?php endif; ?>

				<form action="#" method="post" class="basel-import-form">

					<div class="basel-response"></div>

					<?php if ( $type == 'base' ): ?>
						<?php 

							$btn_label = esc_html__('Import base data', 'basel');
							$activate_label = esc_html__('Activate base version', 'basel');

							if( $this->is_version_imported('base') ) $btn_label = $activate_label;

							$this->page_preview(); 

							$list = $this->_basel_versions;

							$all = 'base';

							foreach ($list as $slug => $version) {
								if( $slug == $all ) continue;
								$all .= ','.$slug;
							}

						?>

						<div class="import-form-fields">

						<input type="hidden" class="basel_version" name="basel_version" value="base">
						<!-- <input type="hidden" class="basel_versions" name="basel_versions" value="furniture,food,organic"> -->
						<input type="hidden" class="basel_versions" name="basel_versions" value="<?php echo esc_attr( $all ); ?>">
						
						<?php if( ! $this->is_version_imported('base') ): ?>
							
							<div class="full-import-box">

								<fieldset>
									<legend>Recommended</legend>
									<label for="full_import">
										<input type="checkbox" id="full_import" name="full_import" value="yes" checked="checked">
										Include all pages and versions
									</label>
									<br>
									<small>
										By checking this option you will get <strong>ALL pages and versions</strong>
										imported in one click.
									</small>
								</fieldset>
							
							</div>

						<?php endif ?>

					<?php else: ?>
						<?php 

							if( $type == 'version' ) $btn_label = esc_html__('Set up version', 'basel');

							$this->versions_select( $type ); 
						?>
					<?php endif ?>


					<?php if ( ! $this->_required_plugins() && $shop_page ): ?>
						<p class="submit">
							<input type="submit" name="basel-submit" id="basel-submit" class="button button-primary" value="<?php echo esc_attr( $btn_label ); ?>" data-activate="<?php echo esc_attr( $btn_label ); ?>">
						</p>
					<?php endif ?>

					<div class="basel-import-progress animated" data-progress="0">
						<div style="width: 0;"></div>
					</div>

					</div><!-- .import-form-fields -->

				</form>

			</div>
		<?php
	}

	public function base_import_screen() {
		$this->admin_import_screen( 'base' );
	}

	public function versions_import_screen() {
		$this->admin_import_screen( 'version' );
	}

	public function pages_import_screen() {
		$this->admin_import_screen( 'page' );
	}

	public function elements_import_screen() {
		$this->admin_import_screen( 'element' );
	}

	public function shops_import_screen() {
		$this->admin_import_screen( 'shop' );
	}

	public function products_import_screen() {
		$this->admin_import_screen( 'product' );
	}

	public function versions_select( $type = false ) {
		$first_version = 'base';

		$list = $this->_basel_versions;

		if( $type ) {
			$list = array_filter( $this->_basel_versions, function( $el ) use($type) {
				return $type == $el['type'];
			});

			// reset($array);
			$first_version = key($list);
		}

		$this->page_preview( $first_version );

		?>
			<div class="import-form-fields">
			<select class="basel_version" name="basel_version">
				<option>--select--</option>
				<?php foreach ($list as $key => $value): ?>
					<option value="<?php echo esc_attr( $key ); ?>" data-imported="<?php echo true == $this->is_version_imported( $key ) ? 'yes' : 'no'; ?>"><?php echo esc_html( $value['title'] ); ?></option>
				<?php endforeach ?>
			</select>
		<?php
	}

	public function page_preview( $version = 'base' ) {
		?>
			<div class="page-preview">
				<img src="<?php echo BASEL_DUMMY_URL . $version; ?>/preview.jpg" data-dir="<?php echo BASEL_DUMMY_URL; ?>" />
			</div>
		<?php
	}

	public function import_action() {
		check_ajax_referer( 'basel-import-nonce', 'security' );

		if( empty( $_GET['basel_version'] ) ) $this->_response->send_fail_msg( 'Wrong version name' );

		$versions = explode( ',', sanitize_text_field( $_GET['basel_version'] ) );

		$sequence = false;

		if( isset( $_GET['sequence'] ) && $_GET['sequence'] == 'true'  ) {
			$sequence = true;
		}

		foreach ($versions as $version) {
			$this->_version = $version;
			if( empty( $version ) ) continue;

			// What exactly do we want to import? XML, options...?
			
			$this->_process = explode(',', $this->_basel_versions[$this->_version]['process']);
			if ( isset( $this->_basel_versions[ $this->_version ]['sliders'] ) ) {
				$this->_sliders = explode( ',', $this->_basel_versions[ $this->_version ]['sliders'] );
			}

			$type = $this->_basel_versions[$this->_version]['type'];

			if( $sequence && $type == 'version') $this->_process = array('xml', 'sliders');
			if( $sequence && ( $type == 'shop' || $type == 'product' ) ) $this->_process = array();

			if( $version == 'base' && $this->is_version_imported( 'base' ) ) {
				$this->_response->add_msg( 'Page content was imported previously' );
				foreach (array('xml', 'sliders') as $val) {
					if( ( $key = array_search($val, $this->_process ) ) !== false ) {
						unset( $this->_process[ $key ] );
					}
				}
			}

			// Run import of all elements defined in $_process
			$import = new BASEL_Importversion( $this->_version, $this->_process, $this->_sliders );
			$import->run_import();

			if ( $version == 'base' ) $this->add_imported_version( 'base' );
		}

		$this->_response->send_response();

	}

	public function basel_import_attributes() {
		if ( get_option( 'basel_import_attributes' ) == true ) return;
		
		$import_attributes = $this->create_attributes();
		
		update_option( 'basel_import_attributes', $import_attributes );
	}

	public function clean_import_attributes_data(){
		return delete_option( 'basel_import_attributes' );
	}

	public function create_attributes() {	
		global $wpdb;

		$attribute_color = $this->get_attribute_to_add('Color');
		
 		$color = true;
		
		if ( function_exists( 'wc_get_attribute_taxonomies' ) && wc_get_attribute_taxonomies() ){
			foreach ( wc_get_attribute_taxonomies() as $key => $value ) {
				if ( $value->attribute_name == 'color' ) $color = false;
			}
		}	
		
		if ( $color ) $wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute_color );

		flush_rewrite_rules();
		delete_transient( 'wc_attribute_taxonomies' );

		if ( function_exists( 'wc' ) && $color ) {
			return true;
		}else{
			return false;
		}
	}

	public function get_attribute_to_add( $name = 'Color' ) {
		$attribute = array(
			'attribute_label'   => $name,
			'attribute_type'    => 'select',
			'attribute_orderby' =>  '',
			'attribute_public'  => 0
		);

		if ( empty( $attribute['attribute_name'] ) && function_exists( 'wc_sanitize_taxonomy_name' ) ) {
			$attribute['attribute_name'] = wc_sanitize_taxonomy_name( $attribute['attribute_label'] );
		}

		return $attribute;
	}

	public function get_imported_versions_css_classes() {
		$versions = $this->imported_versions();
		$class = implode( ' imported-', $versions);
		if( ! empty( $class ) ) $class = ' imported-' . $class;
		return $class;
	}

	public function imported_versions() {
		$data = get_option('basel_imported_versions');
		if( empty( $data ) ) $data = array();
		return $data;
	}

	public function add_imported_version( $version = false ) {
		if( ! $version ) $version = $this->_version;
		$imported = $this->imported_versions();
		if( $this->is_version_imported() ) return;
		$imported[] = $version;
		return update_option( 'basel_imported_versions', $imported );
	}

	public function is_version_imported( $version = false ) {
		if( ! $version ) $version = $this->_version;
		$imported = $this->imported_versions();
		return in_array( $version, $imported);
	}

	public function clean_imported_version_data(){
		return delete_option( 'basel_imported_versions' );
	}

	private function _required_plugins() {
		$plugins = array();

		if( ! class_exists('Redux') ) {
			$plugins[] = 'Redux Framework';
		}

		if( ! class_exists('CMB2') ) {
			$plugins[] = 'CMB2';
		}

		if( ! class_exists('RevSlider') ) {
			$plugins[] = 'Slider Revolution';
		}

		if( ! class_exists('BASEL_Post_Types') ) {
			$plugins[] = 'Theme Post Types';
		}

		if( ! empty( $plugins ) ) {
			return $plugins;
		}

		return false;
	}

	private function _get_version_folder( $version = false ) {
		if( ! $version ) $version = $this->_version;

		return $this->_file_path . $this->_version . '/';
	}

}
