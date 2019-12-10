<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * Activate theme and enable auto updates
 *
 */

class BASEL_License {

    private $_api = null;
    private $_notices = null;
    private $_current_version = '';
    private $_new_version = '';
    private $_theme_name = '';
    private $_info;
    private $_token;


    function __construct() {
        $this->_current_version = basel_get_theme_info( 'Version' );
        $this->_theme_name = BASEL_SLUG;
        $this->_token = get_option( 'basel_token' );

        $this->_api = BASEL_Registry()->api;
        $this->_notices = BASEL_Registry()->notices;

        $this->process_form();

        if( ! basel_is_license_activated() ) return;

        add_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );
        add_filter( 'pre_set_site_transient_update_themes', array( $this, 'set_update_transient' ) );
        add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);

    }

    public function form() {
        $this->_notices->show_msgs();
        ?>

        <h3><?php esc_html_e( 'Theme license activation form', 'basel' ); ?></h3>

        <?php if ( basel_is_license_activated() ): ?>
            <div class="basel-activated-message">
                <p>Thank you for activation. Now you are able to get automatic updates for our theme via <a href="<?php echo admin_url( 'themes.php' ); ?>">Appearance -> Themes</a> or via <a href="<?php echo admin_url( 'update-core.php?force-check=1' ); ?>">Dashboard -> Updates</a>. <br>
                You can click this button to deactivate your license code from this domain if you are going to transfer your website to some other domain or server.<br>

                </p>
                <form action="" class="basel-form" method="post">
                    <p>
                        <input type="hidden" name="purchase-code-deactivate" value="1"/>
                        <input class="button-primary" type="submit" value="<?php esc_attr_e( 'Deactivate theme', 'basel' ); ?>" />
                    </p>
                </form>
            </div>

        <?php else: ?>
            <p>Activate your purchase code for this domain to turn on auto updates function. Note that you can do this for two domains only: for your development website and for the production one.</p>
            <form action="" class="basel-form" method="post">
                <p>
                    <label for="purchase-code"><?php _e('Purchase code', 'basel'); ?> (<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">Where can I get my purchase code?</a>)</label>
                    <input type="text" name="purchase-code" placeholder="Example: 1e71cs5f-13d9-41e8-a140-2cff01d96afb" id="purchase-code" required>
                </p>
                <label for="agree_stored" class="agree-label">
                    <input type="checkbox" name="agree_stored" id="agree_stored" required>
                    I agree that my purchase code and user data will be stored by xtemos.com
                </label>
                <p class="agree-text">
                    To activate the theme and receive product support, you have to register your Envato purchase code on our site. This purchase code will be stored together with support expiration dates and your user data. This is required for us to provide you with product support and other customer services.
                </p>
                <p>
                    <input class="button-primary" name="basel-purchase-code" type="submit" value="<?php esc_attr_e( 'Activate theme', 'basel' ); ?>" />
                </p>
            </form>

        <?php endif;
    }

    public function process_form() {
        if( isset( $_POST['purchase-code-deactivate'] ) ) {
            $this->deactivate();
            $this->_notices->add_success( 'Theme license is successfully deactivated.' );
            return;
        }

        if( isset( $_POST['basel-purchase-code'] ) && ( ! isset( $_POST['agree_stored'] ) || empty( $_POST['agree_stored'] ) ) ) {
            $this->_notices->add_error( 'You must agree to store your purchase code and user data by xtemos.com' );
            return;
        }

        if( ! isset( $_POST['purchase-code'] ) || empty( $_POST['purchase-code'] ) ) return;

        $code = sanitize_text_field( trim( $_POST['purchase-code'] ) );

        $response = $this->_api->call( 'activate/' . $code . '?domain=' . $this->domain() . '&theme=' . $this->_theme_name );

        if( isset( $_GET['xtemos_debug'] ) ) ar($response);

        $response_code = wp_remote_retrieve_response_code( $response );

        if( $response_code != '200' ) {
            $this->_notices->add_error('API request call error. Contact your server providers and ask to update OpenSSL system library to the 1.0+ version.');
            return;
        }

        $data = json_decode( wp_remote_retrieve_body($response), true );

        if( isset( $data['error'] ) ) {
            $this->_notices->add_error( $data['error'] );
            return;
        } 

        if( ! $data['verified'] ) {
            $this->_notices->add_error( 'The purchase code is invalid. <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">Where can I get my purchase code?</a>' );
            return;
        } 

        $this->activate( $code, $data['token'] );

        $this->_notices->add_success( 'The license is verified and theme is activated successfully. Auto updates function is enabled.' );

    }
    
    public function domain() {
        $domain = get_option('siteurl'); 
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('https://', '', $domain);
        $domain = str_replace('www', '', $domain); 
        return urlencode($domain);
    }

    public function activate( $purchase, $token ) {
        update_option( 'basel_token', $token );
        update_option( 'basel_is_activated', true );
        update_option( 'basel_purchase_code', $purchase );
    }

    public function deactivate() {
        $this->_api->call( 'deactivate/' . $this->_token );
        delete_option( 'basel_token' );
        delete_option( 'basel_is_activated' );
        delete_option( 'basel_purchase_code' );
    }


    public function update_transient($value, $transient) {
        if(isset($_GET['force-check']) && $_GET['force-check'] == '1') return false;
        return $value;
    }


    public function set_update_transient($transient) {
    
        $this->check_for_update();

        if( isset( $transient ) && ! isset( $transient->response ) ) {
            $transient->response = array();
        }

        if( ! empty( $this->_info ) && is_object( $this->_info ) ) {
            if( $this->is_update_available() ) {
                $transient->response[ $this->_theme_name ] = json_decode( json_encode( $this->_info ), true );
            }
        }

        remove_action( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );

        return $transient;
    }


    public function api_results($result, $action, $args) {
    
        $this->check_for_update();

        if( isset( $args->slug ) && $args->slug == $this->_theme_name && $action == 'theme_information') {
            if( is_object( $this->_info ) && ! empty( $this->_info ) ) {
                $result = $this->_info;
            }
        }

        return $result;
    }


    protected function check_for_update() {
        $force = false;

        if( isset( $_GET['force-check'] ) && $_GET['force-check'] == '1') $force = true;
        
        // Get data
        if( empty( $this->_info ) ) {
            $version_information = get_option( 'basel-update-info', false );
            $version_information = $version_information ? $version_information : new stdClass;
            
            $this->_info = is_object( $version_information ) ? $version_information : maybe_unserialize( $version_information );
            
        }
        
        $last_check = get_option( 'basel-update-time' );
        if( $last_check == false ){ 
            update_option( 'basel-update-time', time() );
        }
        
        if( time() - $last_check > 172800 || $force || $last_check == false ){
            
            $response = $this->api_info();

            update_option( 'basel-update-time', time() );
            
            $this->_info          = new stdClass;
            $this->_info->new_version = $response->version;
            $this->_info->theme = $response->theme;
            $this->_info->checked = time();
            $this->_info->url     = 'https://xtemos.com/basel-changelog.php';
            $this->_info->package = $this->download_url();

        }
        
        // Save results
        update_option( 'basel-update-info', $this->_info );
    }

    public function api_info() {
        $version_information = new stdClass;

        $response = $this->_api->call( 'info/' . $this->_theme_name );

        if( isset( $_GET['xtemos_debug'] ) ) ar($response);

        $response_code = wp_remote_retrieve_response_code( $response );

        if( $response_code != '200' ) {
            return array();
        }

        $response = json_decode( wp_remote_retrieve_body( $response ) );
        if( ! $response->version ) {
            return $version_information;
        } 

        return $response;
    }

    public function is_update_available() {
        return version_compare( $this->_current_version, $this->release_version(), '<' );
    }

    public function download_url() {
        return BASEL_API_URL . 'files/get/' . $this->_theme_name . '.zip?token=' . $this->_token;
    }
    public function release_version() {
        $this->check_for_update();
        return $this->_info->new_version;
    }

}
