<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
 * WooCommerce catalog mode functions
 */


class BASEL_Catalog {

	public function __construct() {

        add_action( 'wp', array( $this, 'catalog_mode_init' ), 10 );
        add_action( 'wp', array( $this, 'pages_redirect' ), 10 );
        add_action( 'woocommerce_before_shop_loop_item', array( $this, 'catalog_mode_init' ), 10 );
        
	}

    public function catalog_mode_init() {

        if( ! basel_get_opt( 'catalog_mode' ) ) return false;

        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    }

    public function pages_redirect() {
        if( ! basel_get_opt( 'catalog_mode' ) || ! basel_woocommerce_installed() ) return false;

        $cart     = is_page( wc_get_page_id( 'cart' ) );
        $checkout = is_page( wc_get_page_id( 'checkout' ) );

        wp_reset_query();

        if ( $cart || $checkout ) {
            wp_redirect( home_url() );
            exit;
        }
    }

    public function is_quick_view() {
        return defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'basel_quick_view' );
    }

}
