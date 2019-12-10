<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ----------------------------------------------------------------------------------------
 * Include the generated CSS and JS in the page header.
 * ----------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'basel_load_wp_head' ) ) {
	function basel_load_wp_head() {
        $custom_js  = basel_get_opt( 'custom_js' );
        $js_ready 	= basel_get_opt( 'js_ready' );
        ?>
        <style> 
            <?php echo basel_settings_css(); ?>
        </style>
        
        <?php if( ! empty( $custom_js ) || ! empty( $js_ready ) ): ?>
            <script type="text/javascript">
                <?php if( ! empty( $custom_js ) ): ?>
                    <?php echo basel_get_opt( 'custom_js' ); ?>
                <?php endif; ?>
                <?php if( ! empty( $js_ready ) ): ?>
                    jQuery(document).ready(function() {
                        <?php echo basel_get_opt( 'js_ready' ); ?>
                    });
                <?php endif; ?>
            </script>
        <?php endif; ?>

		<?php
	}

	add_action( 'wp_head', 'basel_load_wp_head' );
}
