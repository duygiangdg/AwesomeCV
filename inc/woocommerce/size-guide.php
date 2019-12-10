<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * ------------------------------------------------------------------------------------------------
 * Actions
 * ------------------------------------------------------------------------------------------------
 */
//Save Edit Table Action
add_action( 'save_post_basel_size_guide', 'basel_sguide_table_save' );
add_action( 'edit_post_basel_size_guide', 'basel_sguide_table_save' );

add_action( 'save_post_basel_size_guide', 'basel_sguide_hide_table_save' );
add_action( 'edit_post_basel_size_guide', 'basel_sguide_hide_table_save' );

//Save Edit Product Action
add_action( 'save_post', 'basel_sguide_dropdown_save' );
add_action( 'edit_post', 'basel_sguide_dropdown_save' );

//Add size guide to product page
add_action( 'woocommerce_single_product_summary', 'basel_sguide_display', 38 );


//Metaboxes template
if( ! function_exists( 'basel_sguide_metaboxes' ) ) {
    function basel_sguide_metaboxes( $post ) {

        if ( get_current_screen()->action == 'add' ) {
            $tables = array(
                array( 'Size', 'UK', 'US', 'EU', 'Japan' ),
                array( 'XS', '6 - 8', '4', '34', '7' ),
                array( 'S', '8 -10', '6', '36', '9'  ),
                array( 'M', '10 - 12', '8', '38', '11'  ),
                array( 'L', '12 - 14', '10', '40', '13'  ),
                array( 'XL', '14 - 16', '12', '42', '15'  ),
                array( 'XXL', '16 - 28', '14', '44', '17'  )
            );
        } else {
            $tables = get_post_meta( $post->ID, 'basel_sguide' );
            $tables = $tables[0];
        }

        basel_sguide_table_template( $tables );
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Table
 * ------------------------------------------------------------------------------------------------
 */
//Table template
if( ! function_exists( 'basel_sguide_table_template' ) ) {
    function basel_sguide_table_template( $tables ) {
        ?>
        <textarea class="basel-sguide-table-edit" name="basel-sguide-table" style="display:none;">
            <?php echo json_encode( $tables ); ?>
        </textarea>
        <?php
    }
}

//Save table action
if( ! function_exists( 'basel_sguide_table_save' ) ) {
    function basel_sguide_table_save( $post_id ){

        if ( !isset( $_POST['basel-sguide-table'] ) ) return;

        $size_guide = json_decode( stripslashes ( $_POST['basel-sguide-table'] ) );

        update_post_meta( $post_id, 'basel_sguide', $size_guide );
        
        //Save product category
        basel_sguide_save_category( $post_id );
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Dropdown
 * ------------------------------------------------------------------------------------------------
 */
//Dropdown template
if( ! function_exists( 'basel_sguide_dropdown_template' ) ) {
    function basel_sguide_dropdown_template( $post ){
        $arg = array(
            'post_type' => 'basel_size_guide',
            'numberposts' => -1
        );

        $sguide_list = get_posts( $arg );

        $sguide_post_id = get_post_meta( $post->ID, 'basel_sguide_select' );

        $sguide_post_id = isset( $sguide_post_id[0] ) ? $sguide_post_id[0] : '';
        
        ?>
            <select name="basel_sguide_select">
                <option value="">— None —</option>
                
                <?php foreach ( $sguide_list as $sguide_post ): ?>
                    <option value="<?php echo esc_attr( $sguide_post->ID ); ?>" <?php selected( $sguide_post_id, $sguide_post->ID ); ?>><?php echo wp_kses( $sguide_post->post_title, basel_get_allowed_html() ); ?></option>
                <?php endforeach; ?>
                
            </select><br><br>
            
            <label>
                <input type="checkbox" name="basel_disable_sguide" id="basel_disable_sguide" <?php checked( 'disable', $sguide_post_id, true ); ?>> 
                <?php esc_html_e( 'Hide size guide from this product', 'basel' ) ?>
            </label>
        <?php
    }
}

//Dropdown Save
if( ! function_exists( 'basel_sguide_dropdown_save' ) ) {
    function basel_sguide_dropdown_save( $post_id ){
        if ( isset( $_POST['basel_sguide_select'] ) && $_POST['basel_sguide_select']  ) {
            
            if ( isset( $_POST['basel_disable_sguide'] ) && $_POST['basel_disable_sguide'] == 'on' ) {
                update_post_meta( $post_id, 'basel_sguide_select', 'disable' );
            } else {
                update_post_meta( $post_id, 'basel_sguide_select', sanitize_text_field( $_POST['basel_sguide_select'] ) );
            }
            
        }
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Display
 * ------------------------------------------------------------------------------------------------
 */
 
//Size guide display
if( ! function_exists( 'basel_sguide_display' ) ) {
    function basel_sguide_display( $post_id = false ){
        $post_id = ( $post_id ) ? $post_id : get_the_ID();
        
        $sguide_post_id = get_post_meta( $post_id, 'basel_sguide_select' );
        
        if ( isset( $sguide_post_id[0] ) && $sguide_post_id[0] == 'disable' ) return; 
        
        if ( isset( $sguide_post_id[0] ) && !empty( $sguide_post_id[0] ) ){
            $sguide_post_id = $sguide_post_id[0];
        }else{
            $terms = wp_get_post_terms( $post_id, 'product_cat' );
            if ( $terms ) {
                foreach( $terms as $term ){
                    if ( get_term_meta( $term->term_id, 'basel_chosen_sguide', true ) ) {
                        $sguide_post_id = get_term_meta( $term->term_id, 'basel_chosen_sguide', true );
                    }else{
                        $sguide_post_id = false;
                    }
                }
            }
        }    
        if ( $sguide_post_id ) {
            $sguide_post = get_post( $sguide_post_id );
            $size_tables = get_post_meta( $sguide_post_id, 'basel_sguide' );
                
            basel_sguide_display_table_template( $sguide_post, $size_tables );
        }
    }
}

//Size guide display template
if( ! function_exists( 'basel_sguide_display_table_template' ) ) {
    function basel_sguide_display_table_template( $sguide_post, $size_tables ){
        $is_quick_view = basel_loop_prop( 'is_quick_view' );
        
        if ( !basel_get_opt( 'size_guides' ) || $is_quick_view || !$size_tables || !$sguide_post ) return;
        
        $sguide_custom_css = get_post_meta( $sguide_post->ID, '_wpb_shortcodes_custom_css', true );
        $basel_shortcodes_custom_css = get_post_meta( $sguide_post->ID, 'basel_shortcodes_custom_css', true );
        $show_table = get_post_meta( $sguide_post->ID, 'basel_sguide_hide_table' );
        $show_table = isset( $show_table[0] ) ? $show_table[0] : 'show';
        ?>
           
			<style data-type="vc_shortcodes-custom-css">
				<?php if ( ! empty( $sguide_custom_css ) ): ?>
					<?php echo get_post_meta( $sguide_post->ID, '_wpb_shortcodes_custom_css', true ); ?>
				<?php endif ?>
				<?php if ( ! empty( $basel_shortcodes_custom_css ) ): ?>
					<?php echo get_post_meta( $sguide_post->ID, 'basel_shortcodes_custom_css', true ); ?>
				<?php endif ?>
				/* */
			</style>
            <div id="basel_sizeguide" class="mfp-with-anim basel-content-popup mfp-hide basel-sizeguide">
                <h4 class="basel-sizeguide-title"><?php echo esc_html( $sguide_post->post_title ); ?></h4>
                <div class="basel-sizeguide-content"><?php echo do_shortcode( $sguide_post->post_content ); ?></div>
                <?php if ( $show_table == 'show' ): ?>
                    <div class="responsive-table">
                        <table class="basel-sizeguide-table">
                            <?php foreach ( $size_tables as $table ): ?>
                                <?php foreach ( $table as $row ): ?>
                                    <tr>
                                        <?php foreach ( $row as $col ): ?>
                                            <td><?php echo esc_html( $col ); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="sizeguide-btn-wrapp">
                <a class="basel-popup-with-content basel-sizeguide-btn" href="#basel_sizeguide">
                    <?php echo basel_get_svg_content( 'size-quide-icon' ); ?>
                    <span><?php esc_html_e( 'Size Guide', 'basel' ); ?></span>
                </a>
            </div>
        <?php
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Category
 * ------------------------------------------------------------------------------------------------
 */
 
//Size guide save category
if( ! function_exists( 'basel_sguide_save_category' ) ) {
    function basel_sguide_save_category( $post_id ) {
        if ( isset( $_POST['basel_sguide_category'] ) ) {
            $selected_sguide_category = basel_clean( $_POST['basel_sguide_category'] );
            update_post_meta( $post_id, 'basel_chosen_cats', $selected_sguide_category );
            
            $terms = get_terms( 'product_cat' );
            foreach ( $selected_sguide_category as $selected_sguide_cat ) {
                update_woocommerce_term_meta( $selected_sguide_cat, 'basel_chosen_sguide', $post_id );
            }   
            foreach( $terms as $term ){
                if ( !in_array( $term->term_id, $selected_sguide_category ) ) {
                    if ( $post_id == get_term_meta( $term->term_id, 'basel_chosen_sguide', true ) ) {
                        update_woocommerce_term_meta( $term->term_id, 'basel_chosen_sguide', '' );
                    }
                }
            }
        }
        else{
            update_post_meta( $post_id, 'basel_chosen_cats', '' );
        }
    }
}

//Size guide category template
if( ! function_exists( 'basel_sguide_category_template' ) ) {
    function basel_sguide_category_template( $post ) {
        $arg = array(
            'taxonomy'     => 'product_cat',
            'orderdby'     => 'name',
            'hierarchical' => 1
        );

        $chosen_cats = get_post_meta( $post->ID, 'basel_chosen_cats' );
        
        if ( ! empty( $chosen_cats ) ) $chosen_cats = $chosen_cats[0];

        $sguide_cat_list = get_categories( $arg );
        
        ?>
        <ul>
            <?php foreach ( $sguide_cat_list as $sguide_cat ): ?>
                <?php $checked = false; ?>
                <?php if ( is_array( $chosen_cats ) && in_array( $sguide_cat->term_id, $chosen_cats ) ) $checked = 'checked'; ?>
                <li>
                    <input type="checkbox" name="basel_sguide_category[]" value="<?php echo esc_attr( $sguide_cat->term_id ); ?>" <?php echo esc_attr( $checked ); ?>>
                    <?php echo wp_kses( $sguide_cat->name, basel_get_allowed_html() ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Hide table
 * ------------------------------------------------------------------------------------------------
 */
//Size guide hide table template
if( ! function_exists( 'basel_sguide_hide_table_template' ) ) {
    function basel_sguide_hide_table_template( $post ) {
        $disable_table = get_post_meta( $post->ID, 'basel_sguide_hide_table' );
        $disable_table = isset( $disable_table[0] ) ? $disable_table[0] : 'show';
        ?>
        <label>
            <input type="checkbox" name="basel_sguide_hide_table" id="basel_sguide_hide_table" <?php checked( 'hide', $disable_table, true ); ?> > 
            <?php esc_html_e( 'Hide size guide table', 'basel' ) ?>
        </label>
        <?php
    }
}
//Size guide hide table save
if( ! function_exists( 'basel_sguide_hide_table_save' ) ) {
    function basel_sguide_hide_table_save( $post_id ){
        if ( isset( $_POST['basel_sguide_hide_table'] ) && $_POST['basel_sguide_hide_table'] == 'on' ) {
            update_post_meta( $post_id, 'basel_sguide_hide_table', 'hide' );
        } else {
            update_post_meta( $post_id, 'basel_sguide_hide_table', 'show' );
        }
    }
}
