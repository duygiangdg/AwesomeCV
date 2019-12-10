<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );
/**
 * Maintenance mode
 */

class BASEL_Maintenance {

	public function __construct() {

        add_action( 'template_redirect', array( $this, 'init' ) );
        
	}

    public function init() {

        if( ! basel_get_opt( 'maintenance_mode' ) || is_user_logged_in() ) return;

        $page_id = basel_pages_ids_from_template( 'maintenance' );

        $page_id = current($page_id);

        if( ! $page_id ) return;

        if( ! is_page( $page_id ) && ! is_user_logged_in() ) {
            wp_redirect( get_permalink( $page_id ) );
            exit();
        }

    }


}
