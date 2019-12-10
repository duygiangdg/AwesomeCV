<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

// **********************************************************************//
// Title
// **********************************************************************//

if( ! class_exists( 'ReduxFramework_basel_title' ) && class_exists( 'ReduxFramework' ) ) {

    class ReduxFramework_basel_title extends ReduxFramework {
    
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field  = $field;
            $this->value  = $value;
        }

        public function render() {
            echo '</td></tr></table>';

            echo '<div class="basel-settings-title">';
                if ( isset( $this->field['basel-title'] ) && $this->field['basel-title'] ) {
                    echo '<h4 class="basel-title">' . esc_html( $this->field['basel-title'] ) . '</h4>';
                }
                if ( isset( $this->field['basel-desc'] ) && $this->field['basel-desc'] ) {
                    echo '<p class="basel-title-desc">' . esc_html( $this->field['basel-desc'] ) . '</p>';
                }
            echo '</dev>';

            echo '</div><table class="form-table no-border" style="margin-top: 0;"><tbody><tr style="border-bottom:0; display:none;"><th style="padding-top:0;"></th><td style="padding-top:0;">';
        }
    }
}