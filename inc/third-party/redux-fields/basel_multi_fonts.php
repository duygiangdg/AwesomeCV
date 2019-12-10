<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

// **********************************************************************//
// Multi custom fonts
// **********************************************************************//

if ( ! class_exists( 'ReduxFramework_basel_multi_fonts' ) && class_exists( 'ReduxFramework' ) ) {

    class ReduxFramework_basel_multi_fonts extends ReduxFramework {

        public $order = 0;

        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field  = $field;
            $this->value  = $value;
        }

        public function render() {
            echo '<ul id="' . esc_attr( $this->field['id'] ) . '-ul" class="basel-multi-fonts">';
                if ( isset( $this->value ) && is_array( $this->value ) ) {
                    foreach ( $this->value as $key => $value ) {
                        if ( ! $value ) continue;
                        $this->fields( 'multi', $value ); 
                        $this->order++;
                    }
                } else {
                    $this->fields( 'default' );
                }

                //Clone standart
                $this->fields( 'clone' );

            echo '</ul>';

            //Add new btn
            echo '<a href="javascript:void(0);" class="basel-multi-fonts-add" data-id="' . esc_attr( $this->field['id'] ) . '-ul"  data-name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '" data-order="' . esc_attr( $this->order ) . '">'. esc_html__( 'Add More', 'basel' ) .'</a>';
        }

        public function fields( $type, $multi_value = false ) {
            $style = $classes = '';
            $name = $this->field['name'] . $this->field['name_suffix'] . '[' . $this->order. ']';

            $font_name_values = array(
                'title' => esc_html__( 'Font name', 'basel' ),
                'name' => ( $type != 'clone' ) ? $name . '[font-name]' : '',
                'value' => ( $multi_value['font-name'] ) ? $multi_value['font-name'] : '',
            );

            $font_weight_values = array(
                'title' => esc_html__( 'Font weight', 'basel' ),
                'name' => ( $type != 'clone' ) ? $name . '[font-weight]' : '',
                'value' => ( $multi_value['font-weight'] ) ? $multi_value['font-weight'] : '400',
                'opt' => array( 
                    esc_html__( 'Ultra-Light 100', 'basel' ) => 100,
                    esc_html__( 'Light 200', 'basel' ) => 200,
                    esc_html__( 'Book 300', 'basel' ) => 300,
                    esc_html__( 'Normal 400', 'basel' ) => 400,
                    esc_html__( 'Medium 500', 'basel' ) => 500,
                    esc_html__( 'Semi-Bold 600', 'basel' ) => 600,
                    esc_html__( 'Bold 700', 'basel' ) => 700,
                    esc_html__( 'Extra-Bold 800', 'basel' ) => 800,
                    esc_html__( 'Ultra-Bold 900', 'basel' ) => 900,
                ),
            );

            $media_fields_values = array(
                'font-woff' => array(
                    'title' => esc_html__( 'Font (.woff)', 'basel' ),
                    'name' => '',
                    'value' => '',
                    'id' => '',
                    'mime' => array( 'woff' => 'font/woff' )
                ),
                'font-woff2' => array(
                    'title' => esc_html__( 'Font (.woff2)', 'basel' ),
                    'name' => '',
                    'value' => '',
                    'id' => '',
                    'mime' => array( 'woff2' => 'font/woff2' )
                ),
                'font-ttf' => array(
                    'title' => esc_html__( 'Font (.ttf)', 'basel' ),
                    'name' => '',
                    'value' => '',
                    'id' => '',
                    'mime' => array( 'ttf' => 'font/ttf' )
                ),
                'font-svg' => array(
                    'title' => esc_html__( 'Font (.svg)', 'basel' ),
                    'name' => '',
                    'value' => '',
                    'id' => '',
                    'mime' => array( 'svg' => 'image/svg+xml' )
                ),
                'font-eot' => array(
                    'title' => esc_html__( 'Font (.eot)', 'basel' ),
                    'name' => '',
                    'value' => '',
                    'id' => '',
                    'mime' => array( 'eot' => 'font/eot' )
                ),
            ); 

            if ( $type == 'clone' ) $style = 'display:none;';
            if ( $this->order == 0 ) $classes = 'active';
            
            $font_name = esc_html__( 'Custom font', 'basel' );
            if ( $font_name_values['value'] && $font_weight_values['value'] ) $font_name .= ' - ' . $font_name_values['value'] . ' (' . $font_weight_values['value'] . ')';

            echo '<li class="basel-miltifont-repeater '. esc_attr( $classes ) .'" style="' . esc_attr( $style ) . '">';

                echo '<h3 class="basel-miltifonts-accordion">';
                    echo '<span class="icon"><i class="el el-chevron-down"></i></span>';
                    echo '<span class="font-name">' . esc_html( $font_name ) . '</span>';
                    echo '<a href="javascript:void(0);" class="deletion basel-multi-fonts-remove">' . esc_html__( 'Remove', 'basel' ) . '</a>';
                echo '</h3>';

                echo '<div class="miltifont-fields">';

                    //Font name
                    echo '<div class="miltifont-field">';
                        echo '<h4>' . $font_name_values['title'] . '</h4>';
                        echo '<input type="text" name="' . esc_attr( $font_name_values['name'] ) . '" value="' . esc_attr( $font_name_values['value'] ) . '">';
                        echo '<p>' . esc_html__( 'Enter your name with letters and spacing only. It will be used in a list of fonts under the Typography section. For example: Indie Flower', 'basel' ) . '</p>';
                    echo '</div>';

                    //Font weight
                    echo '<div class="miltifont-field">';
                        echo '<h4>' . $font_weight_values['title'] . '</h4>';
                        echo '<select name="' . esc_attr( $font_weight_values['name'] ) . '">';
                            foreach ( $font_weight_values['opt'] as $key => $opt_val ) {
                                $selected = ( $font_weight_values['value'] == $opt_val ) ? 'selected' : ''; 
                                echo '<option value="' . esc_attr( $opt_val ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $key ) . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';

                    //Media fields
                    foreach ( $media_fields_values as $key => $value ) {
                        if ( isset( $multi_value[$key]['url'] ) && isset( $multi_value[$key]['id'] ) ) {
                            $value['value'] = $multi_value[$key]['url'];
                            $value['id'] = $multi_value[$key]['id'];
                        } else {
                            $value['value'] = $multi_value[$key];
                        }
                        if ( $type != 'clone' ) $value['name'] = $name . '[' . $key. ']';
                        $this->mediaFields( $value['title'], $value['value'], $value['name'], $value['mime'], $value['id'] );
                    }
                    
                echo '</div>';

            echo '</li>';
        }

        public function mediaFields( $title, $value, $name, $mime, $id ) {
            $lib_filter = urlencode( json_encode( $mime ) );

            echo '<div class="miltifont-field">';
                echo '<h4>' . esc_html( $title ) . '</h4>';
                echo '<fieldset class="redux-field-container redux-field redux-container-media redux-field-init multifonts" id="' . esc_attr( uniqid() ) . '-media" data-type="media">';
                
                    echo '<input type="text" placeholder="' . esc_attr__( 'No file selected', 'basel' ) . '" class="upload large-text" name="' . esc_attr( $name . '[url]' ) . '" value="' .  esc_attr( $value ) . '" readonly="readonly">';
                    echo '<input type="hidden" class="data" data-mode="0">';
                    // echo '<input type="hidden" class="library-filter" data-lib-filter="' . esc_attr( $lib_filter ) . '">';
                    echo '<input type="hidden" class="upload-id" name="' . esc_attr( $name . '[id]' ) . '" value="' . esc_attr( $id ) . '">';

                    echo '<div class="upload_button_div">';
                        echo '<span class="button media_upload_button">' . esc_html__( 'Upload', 'basel' ) . '</span>';
                        $hide = ( empty( $value ) && empty( $id ) ) ? ' hide' : '';
                        echo '<span class="button remove-image ' . esc_attr( $hide ) . '">' . esc_html__( 'Remove', 'basel' ) . '</span>';
                    echo '</div>';

                echo '</fieldset>';
            echo '</div>';
        }
    }
}