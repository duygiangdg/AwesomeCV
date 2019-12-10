<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Product filters
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'basel_product_filters_shortcode' ) ) {
	function basel_product_filters_shortcode( $atts, $content ) {
		global $wp;
		$classes = '';
		extract( shortcode_atts( array(
			'basel_color_scheme' => 'dark',
			'css' => '',
			'el_class' => '',
		), $atts) );
		
		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$classes .= ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		$classes .= ' color-scheme-' . $basel_color_scheme;
		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		$form_action = wc_get_page_permalink( 'shop' );

		if ( basel_is_shop_archive() && apply_filters( 'basel_filters_form_action_without_cat_widget', false ) ) {
			if ( '' === get_option( 'permalink_structure' ) ) {
				$form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			} else {
				$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
			}
		}

		if ( basel_is_shop_archive() ) {
			$classes .= ' with-ajax';
		}
		
		$output = '<form action="' . esc_url( $form_action ) . '" class="basel-product-filters' . esc_attr( $classes ) . '" method="GET">';
			$output .= do_shortcode( $content );
			$output .= '<div class="basel-pf-btn"><button type="submit">' . esc_html__( 'Filter', 'basel' ) . '</button></div>';
		$output .= '</form>';

		return $output;
	}
	
}

/**
* ------------------------------------------------------------------------------------------------
* Categories widget
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'basel_filters_categories_shortcode' ) ) {
	function basel_filters_categories_shortcode( $atts, $content ) {
		global $wp_query, $post;
		$classes = '';
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Categories', 'basel' ),
			'hierarchical' => 1,
			'orderby' => 'name',
			'hide_empty' => '',
			'show_categories_ancestors' => '',
			'el_class' => '',
		), $atts) );

		$classes .= ( $el_class ) ? ' ' . $el_class : '';
		
		$list_args = array(
			'hierarchical' => $hierarchical,
			'taxonomy' => 'product_cat',
			'use_desc_for_title' => false,
			'hide_empty' => $hide_empty,
 			'title_li' => false,
			'walker' => new Basel_Walker_Category(),
			'orderby' => $orderby,
			'echo' => false,
		);

		$cat_ancestors = array();

		if ( is_tax( 'product_cat' ) ) {
			$current_cat = $wp_query->queried_object;
			$cat_ancestors = get_ancestors( $current_cat->term_id, 'product_cat' );
		} 

		$list_args['current_category'] = ( isset( $current_cat ) ) ? $current_cat->term_id : '';
		$list_args['current_category_ancestors'] = $cat_ancestors;

		if ( $show_categories_ancestors && isset( $current_cat ) ) {
			$is_cat_has_children = get_term_children( $current_cat->term_id, 'product_cat' );
			if ( $is_cat_has_children ) {
				$list_args['child_of'] = $current_cat->term_id;
			} elseif ( $current_cat->parent != 0 ) {
				$list_args['child_of'] = $current_cat->parent;
			}
			$list_args['depth'] = 1;
		}

		$output = '<div class="basel-pf-checkboxes basel-pf-categories">';
			$output .= '<div class="basel-pf-title"><span class="title-text">' . esc_html( $title ) . '</span><ul class="basel-pf-results"></ul></div>';
			$output .= '<div class="basel-pf-dropdown basel-scroll">';
				$output .= '<ul class="basel-scroll-content">';
					if ( $show_categories_ancestors && isset( $current_cat ) && $is_cat_has_children ) {
						$output .= '<li style="display:none;" class="pf-active cat-item cat-item-' . esc_attr( $current_cat->term_id ) . '">';
							$output .= '<a class="pf-value" href="' . esc_url( get_category_link( $current_cat->term_id ) ) . '" data-val="' . esc_attr( $current_cat->slug ) . '" data-title="' . esc_attr( $current_cat->name ) . '">' . esc_attr( $current_cat->name ) . '</a>';
						$output .= '</li>';
					}
					$output .= wp_list_categories( $list_args );
				$output .= '</ul>';
			$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	
}

/**
* ------------------------------------------------------------------------------------------------
* Attributes widget
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'basel_filters_attribute_shortcode' ) ) {
	function basel_filters_attribute_shortcode( $atts, $content ) {
		$classes = '';
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Filter by', 'basel' ),
			'attribute' => '',
			'categories' => '',
			'query_type' => 'and',
			'size' => 'normal',
			'el_class' => '',
		), $atts) );

		if ( isset( $categories ) ) {
			$categories = explode( ',', $categories );
			$categories = array_map( 'trim', $categories );
		} else {
			$categories = array();
		}

		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		ob_start();

		the_widget( 'BASEL_Widget_Layered_Nav', 
			array( 
				'template' => 'filter-element',
				'attribute' => $attribute,
				'query_type' => $query_type,
				'size' => $size,
				'filter-title' => $title,
				'categories' => $categories,
			),
			array(							
				'before_widget' => '',
				'after_widget'  => '',
			)
		);

		return ob_get_clean();
		
	}

}

/**
* ------------------------------------------------------------------------------------------------
* Price slider widget
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'basel_filters_price_slider_shortcode' ) ) {
	function basel_filters_price_slider_shortcode( $atts, $content ) {
		global $wpdb;

		$classes = '';
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Filter by price', 'basel' ),
			'el_class' => '',
		), $atts) );

		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		$price_slider_params = array(
			'currency_format_num_decimals' => 0,
			'currency_format_symbol'       => get_woocommerce_currency_symbol(),
			'currency_format_decimal_sep'  => esc_attr( wc_get_price_decimal_separator() ),
			'currency_format_thousand_sep' => esc_attr( wc_get_price_thousand_separator() ),
			'currency_format'              => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
		);

		wp_localize_script( 'basel-functions', 'woocommerce_price_slider_params', $price_slider_params );
		wp_localize_script( 'basel-theme', 'woocommerce_price_slider_params', $price_slider_params );

		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'wc-jquery-ui-touchpunch' );
		wp_enqueue_script( 'accounting' );

		ob_start();

		//WC 3.6.0
		if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
			$prices = basel_get_filtered_price();
		} else {
			$prices = basel_get_filtered_price_new();
		}

		$min = floor( $prices->min_price );
		$max = ceil( $prices->max_price );

		if ( $min === $max ) {
			return;
		}
		
		if ( ( is_shop() || is_product_taxonomy() ) && ! wc()->query->get_main_query()->post_count ) {
			return;
		}

		$min_price = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : $min;
		$max_price = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : $max;

		echo '<div class="basel-pf-checkboxes basel-pf-price-range multi_select widget_price_filter">
				<div class="basel-pf-title"><span class="title-text">' . esc_html( $title ) . '</span><ul class="basel-pf-results"></ul></div>
				<div class="basel-pf-dropdown">
					<div class="price_slider_wrapper">
						<div class="price_slider_widget" style="display:none;"></div>
						<div class="filter_price_slider_amount">
							<input type="hidden" class="min_price" name="min_price" value="' . esc_attr( $min_price ) . '" data-min="' . esc_attr( $min ) . '">
							<input type="hidden" class="max_price" name="max_price" value="' . esc_attr( $max_price ) . '" data-max="' . esc_attr( $max ) . '">
							<div class="price_label" style="display:none;"><span class="from"></span><span class="to"></span></div>
						</div>
					</div>
				</div>
			</div>'; 

		return ob_get_clean();
	}

}

if ( ! function_exists( 'basel_get_filtered_price' ) ) {
	function basel_get_filtered_price() {
		global $wpdb;

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			$sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' AND price_meta.meta_key = '_price'";

			return $wpdb->get_row( $sql ); 
		}

		$args       = wc()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
			AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return $wpdb->get_row( $sql ); 
	}
}

if ( ! function_exists( 'basel_get_filtered_price_new' ) ) {
	function basel_get_filtered_price_new() {
		global $wpdb;

		if ( ! is_shop() && ! is_product_taxonomy() ) {
			$sql = "
			SELECT min( FLOOR( min_price ) ) as min_price, MAX( CEILING( max_price ) ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}";

			return $wpdb->get_row( $sql ); 
		}

		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );
		$search     = WC_Query::get_main_search_query_sql();

		$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = $search ? ' AND ' . $search : '';

		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}
}
