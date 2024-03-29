<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * Color filter widget
 *
 */

if ( ! class_exists( 'BASEL_Widget_Layered_Nav' ) ) {
	class BASEL_Widget_Layered_Nav extends WPH_Widget {
	
		function __construct() {
			if( ! basel_woocommerce_installed() || ! function_exists( 'wc_get_attribute_taxonomies' ) ) return;

			$attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}

			$categories_array = array(
				esc_html( 'All', 'basel' ) => 'all',
			);

			$categories = $this->get_categories();

			if ( ! empty( $categories ) ) {
				foreach ( $categories as $cat ) {
					$categories_array[ $cat->post_title ] = $cat->id;
				}
			}

			// Configure widget array
			$args = array( 
				// Widget Backend label
				'label' => esc_html__( 'BASEL WooCommerce Layered Nav', 'basel' ),
				// Widget Backend Description								
				'description' =>esc_html__( 'Shows a custom attribute in a widget which lets you narrow down the list of products when viewing product categories.', 'basel' ),
				'slug' => 'basel-woocommerce-layered-nav',
			 );
		
			// Configure the widget fields
		
			// fields array
			$args['fields'] = array(
				array(
					'id'	=> 'title',
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by', 'woocommerce' ),
					'name' 	=> esc_html__( 'Title', 'woocommerce' )
				),
				array(
					'id'	=> 'attribute',
					'type'    => 'dropdown',
					'std'     => '',
					'name'   => esc_html__( 'Attribute', 'woocommerce' ),
					'fields' => $attribute_array
				),
				array(
					'id'     => 'category',
					'type'   => 'dropdown',
					'std'    => '',
					'name'   => esc_html__( 'Show on category', 'basel' ),
					'fields' => $categories_array
				),
				array(
					'id'	=> 'query_type',
					'type'    => 'dropdown',
					'std'     => 'and',
					'name'   => esc_html__( 'Query type', 'woocommerce' ),
					'fields' => array(
						esc_html__( 'AND', 'woocommerce' ) => 'and',
						esc_html__( 'OR', 'woocommerce' ) => 'or'
					)
				),
				array(
					'id'	=> 'display',
					'type'    => 'dropdown',
					'std'     => 'list',
					'name'   => esc_html__( 'Display type', 'basel' ),
					'fields' => array(
						esc_html__( 'list', 'basel' ) => 'list',
						esc_html__( 'inline', 'basel' ) => 'inline',
						esc_html__( 'Dropdown', 'woocommerce' ) => 'dropdown'
					)
				),
				array(
					'id'	=> 'size',
					'type'    => 'dropdown',
					'std'     => 'normal',
					'name'   => esc_html__( 'Swatches size', 'basel' ),
					'fields' => array(
						esc_html__( 'normal', 'basel' ) => 'normal',
						esc_html__( 'large', 'basel' ) => 'large',
						esc_html__( 'small', 'basel' ) => 'small',
					)
				),
				array(
					'id'	=> 'labels',
					'type'    => 'dropdown',
					'std'     => 'on',
					'name'   => esc_html__( 'Show labels', 'basel' ),
					'fields' => array(
						esc_html__( 'ON', 'basel' ) => 'on',
						esc_html__( 'OFF', 'basel' ) => 'off'
					)
				)
			);

			// create widget
			$this->create_widget( $args );
		}

		public function get_categories() {
			global $wpdb;

			$categories = $wpdb->get_results( "
			SELECT 
				t.term_id AS id,
				t.name    AS post_title,
				t.slug    AS post_url
			FROM {$wpdb->prefix}terms t
				LEFT JOIN {$wpdb->prefix}term_taxonomy tt
						ON t.term_id = tt.term_id
			WHERE tt.taxonomy = 'product_cat'
			ORDER BY name" );

			return $categories;
		}
		
		// Output function
		// Based on woo widget @version  2.3.0
		function widget( $args, $instance )	{
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
			$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];
			$display	  		= isset( $instance['display'] ) ? $instance['display'] : 'list';
			$category           = isset( $instance['category'] ) ? $instance['category'] : 'all';


			$current_cat = get_queried_object();


			if ( ! is_tax() && 'all' != $category ) {
				return;
			}

			if ( 'all' != $category && property_exists( $current_cat, 'term_id' ) && $current_cat->term_id != $category && $current_cat->parent != $category ) {
				return;
			}

			$template = isset( $instance['template'] ) ? $instance['template'] : 'default';

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = array( 'hide_empty' => '1' );

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name' :
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
				break;
				case 'id' :
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
				break;
				case 'menu_order' :
					$get_terms_args['menu_order'] = 'ASC';
				break;
			}

			$terms = get_terms( $taxonomy, $get_terms_args );

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			ob_start();

			echo wp_kses_post( $args['before_widget'] );

			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance ) ) {
				echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
			}

			if ( $template == 'default' ) {
				if ( 'dropdown' === $display ) {
					wp_enqueue_script( 'selectWoo' );
					wp_enqueue_style( 'select2' );
					$found = $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
				} else {
					$found = $this->layered_nav_list( $terms, $taxonomy, $query_type, $instance );
				}
			} else {
				$found = $this->layered_nav_checkbox_list( $terms, $taxonomy, $query_type, $instance );
			}

			echo wp_kses_post( $args['after_widget'] );

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
			
		}

		/**
		 * Return the currently viewed taxonomy name.
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}

		/**
		 * Show dropdown layered nav.
		 * @param  array $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @return bool Will nav display?
		 */
		protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {
			global $wp;
			$found = false;

			if ( $taxonomy !== $this->get_current_taxonomy() ) {
				//WC 3.6.0
				if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
					$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
				} else {
					$term_counts          = $this->get_filtered_term_product_counts_new( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
				}
				
				$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
				$taxonomy_filter_name = str_replace( 'pa_', '', $taxonomy );
				$taxonomy_label       = wc_attribute_label( $taxonomy );

				/* translators: %s: taxonomy name */
				$any_label      = apply_filters( 'woocommerce_layered_nav_any_label', sprintf( __( 'Any %s', 'basel' ), $taxonomy_label ), $taxonomy_label, $taxonomy );
				$multiple       = 'or' === $query_type;
				$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();

				if ( '' === get_option( 'permalink_structure' ) ) {
					$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
				} else {
					$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
				}

				echo '<form method="get" action="' . esc_url( $form_action ) . '" class="basel-widget-layered-nav-dropdown-form">';
				echo '<select class="basel-widget-layered-nav-dropdown basel_dropdown_layered_nav_' . esc_attr( $taxonomy_filter_name ) . '"' . ( $multiple ? 'multiple="multiple"' : '' ) . ' data-placeholder="' . esc_attr( $any_label ) . '" data-noResults="' . esc_html__( 'No matches found', 'basel' ) . '" data-slug="' . esc_attr( $taxonomy_filter_name ) . '">';
				echo '<option value="">' . esc_html( $any_label ) . '</option>';

				foreach ( $terms as $term ) {

					// If on a term page, skip that term in widget list.
					if ( $term->term_id === $this->get_current_term_id() ) {
						continue;
					}

					// Get count based on current view.
					$option_is_set = in_array( $term->slug, $current_values, true );
					$count         = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

					// Only show options with count > 0.
					if ( 0 < $count ) {
						$found = true;
					} elseif ( 0 === $count && ! $option_is_set ) {
						continue;
					}

					echo '<option value="' . esc_attr( urldecode( $term->slug ) ) . '" ' . selected( $option_is_set, true, false ) . '>' . esc_html( $term->name ) . '</option>';
				}

				echo '</select>';

				if ( $multiple ) {
					echo '<button class="basel-widget-layered-nav-dropdown__submit" type="submit" value="' . esc_attr__( 'Apply', 'basel' ) . '">' . esc_html__( 'Apply', 'basel' ) . '</button>';
				}

				if ( 'or' === $query_type ) {
					echo '<input type="hidden" name="query_type_' . esc_attr( $taxonomy_filter_name ) . '" value="or" />';
				}

				echo '<input type="hidden" name="filter_' . esc_attr( $taxonomy_filter_name ) . '" value="' . esc_attr( implode( ',', $current_values ) ) . '" />';
				echo wc_query_string_form_fields( null, array( 'filter_' . $taxonomy_filter_name, 'query_type_' . $taxonomy_filter_name ), '', true ); // @codingStandardsIgnoreLine
				echo '</form>';
			}

			return $found;
		}

		/**
		 * Get current page URL for layered nav items.
		 * @return string
		 */
		protected function get_page_base_url( $taxonomy ) {
			if ( basel_is_shop_on_front() ) {
				$link = home_url();
			} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
				$link = get_post_type_archive_link( 'product' );
			} elseif( is_product_category() ) {
				$link = get_term_link( get_query_var('product_cat'), 'product_cat' );
			} elseif( is_product_tag() ) {
				$link = get_term_link( get_query_var('product_tag'), 'product_tag' );
			} else {
				$queried_object = get_queried_object();
				$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}

			// Min/Max
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
			}

			// Orderby
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
			}

			// Min Rating Arg
			if ( isset( $_GET['min_rating'] ) ) {
				$link = add_query_arg( 'min_rating', wc_clean( $_GET['min_rating'] ), $link );
			}

			// All current filters
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
				foreach ( $_chosen_attributes as $name => $data ) {
					if ( $name === $taxonomy ) {
						continue;
					}
					$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' == $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}

			return $link;
		}

		/**
		 * Count products within certain terms, taking the main WP query into consideration.
		 * @param  array $term_ids
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @return array
		 */
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
			global $wpdb;
			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();
			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}
			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );
			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );
			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				" . $tax_query_sql['join'] . $meta_query_sql['join'];
			$query['where']   = "
				WHERE {$wpdb->posts}.post_type IN ( 'product' )
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
				AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
			";
			if ( method_exists('WC_Query', 'get_main_search_query_sql') && $search = WC_Query::get_main_search_query_sql() ) {
				$query['where'] .= ' AND ' . $search;
				
				if ( basel_get_opt( 'search_by_sku' ) ) {
					// search for variations with a matching sku and return the parent.
					$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $_GET['s'] ) ) );
					
					//Search for a regular product that matches the sku.
					$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean( $_GET['s'] ) ) );
					
					$search_ids = array_merge( $sku_to_id, $sku_to_parent_id );
					
					$search_ids = array_filter( array_map( 'absint', $search_ids ) );
									
					if ( sizeof( $search_ids ) > 0 ) {
						$query['where'] = str_replace( '))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . ")))", $query['where'] );
					}
				}
				
			}
			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );

			// We have a query - let's see if cached results of this query already exist.
			$query_hash    = md5( $query );

			// Maybe store a transient of the count values.
			$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
			if ( true === $cache ) {
				$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . $taxonomy );
			} else {
				$cached_counts = array();
			}

			if ( ! isset( $cached_counts[ $query_hash ] ) ) {
				$results                      = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
				$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
				$cached_counts[ $query_hash ] = $counts;
				if ( true === $cache ) {
					set_transient( 'wc_layered_nav_counts_' . $taxonomy, $cached_counts, DAY_IN_SECONDS );
				}
			}

			return array_map( 'absint', (array) $cached_counts[ $query_hash ] );

		}

		protected function get_filtered_term_product_counts_new( $term_ids, $taxonomy, $query_type ) {
			global $wpdb;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();
			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );
			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );
			// Generate query
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				" . $tax_query_sql['join'] . $meta_query_sql['join'];
			$query['where']   = "
				WHERE {$wpdb->posts}.post_type IN ( 'product' )
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
				AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
			";
			
			if ( $search = WC_Query::get_main_search_query_sql() ) {
				
				$query['where'] .= ' AND ' . $search;

				if ( basel_get_opt( 'search_by_sku' ) ) {
					// search for variations with a matching sku and return the parent.
					$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->wc_product_meta_lookup} ml on p.ID = ml.product_id and ml.sku LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $_GET['s'] ) ) );
					
					//Search for a regular product that matches the sku.
					$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT product_id FROM {$wpdb->wc_product_meta_lookup} WHERE sku LIKE '%%%s%%';", wc_clean( $_GET['s'] ) ) );
					
					$search_ids = array_merge( $sku_to_id, $sku_to_parent_id );
					
					$search_ids = array_filter( array_map( 'absint', $search_ids ) );
									
					if ( sizeof( $search_ids ) > 0 ) {
						$query['where'] = str_replace( '))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . ")))", $query['where'] );
					}
				}
				
			}
			
			$query['group_by'] = 'GROUP BY terms.term_id';
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );

			// We have a query - let's see if cached results of this query already exist.
			$query_hash    = md5( $query );

			// Maybe store a transient of the count values.
			$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
			if ( true === $cache ) {
				$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . $taxonomy );
			} else {
				$cached_counts = array();
			}

			if ( ! isset( $cached_counts[ $query_hash ] ) ) {
				$results                      = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
				$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
				$cached_counts[ $query_hash ] = $counts;
				if ( true === $cache ) {
					set_transient( 'wc_layered_nav_counts_' . $taxonomy, $cached_counts, DAY_IN_SECONDS );
				}
			}

			return array_map( 'absint', (array) $cached_counts[ $query_hash ] );

		}

		/**
		 * Show list based layered nav.
		 * @param  array $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @return bool Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $query_type, $instance ) {
			$labels		  		= isset( $instance['labels'] ) ? $instance['labels'] : 'on';
			$size		  		= isset( $instance['size'] ) ? $instance['size'] : 'normal';
			$display	  		= isset( $instance['display'] ) ? $instance['display'] : 'list';
			$scroll_for_widget  = basel_get_opt('widgets_scroll');
			
			$is_brand = ( basel_get_opt( 'brands_attribute' ) == $taxonomy );

			$class = 'show-labels-' . $labels;
			$class .= ' swatches-' . $size;
			$class .= ' swatches-display-' . $display;
			$class .= ( $is_brand ) ? ' swatches-brands' : '';
			
			// List display
			if( $scroll_for_widget ) {
				echo '<div class="basel-scroll">';
				$class .= ' basel-scroll-content';
			}
			echo '<ul class="' . esc_attr( $class ) . '">';

			//WC 3.6.0
			if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
				$term_counts = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			} else {
				$term_counts = $this->get_filtered_term_product_counts_new( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			}

			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			foreach ( $terms as $term ) {
				$current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set     = in_array( $term->slug, $current_values );
				$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// skip the term for the current archive
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );
				if ( is_wp_error( $link ) ) $link = '';

				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL
					if ( $query_type === 'or' && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}

				// Add swatches block
				$swatch_div = $swatch_style = '';
				$swatch_color = basel_tax_data( $taxonomy, $term->term_id, 'color' );
				$swatch_image = basel_tax_data( $taxonomy, $term->term_id, 'image' );
				$swatch_text = basel_tax_data( $taxonomy, $term->term_id, 'not_dropdown' );

				$class = $option_is_set ? 'chosen' : '';

				if( ! empty( $swatch_color ) ) {
					$class .= ' with-swatch-color';
					$swatch_style = 'background-color: ' . $swatch_color .';';
				} else if( ! empty( $swatch_image ) ) {
					$class .= ' with-swatch-image';
					$swatch_style = 'background-image: url(' . $swatch_image .');';
				} else if( ! empty( $swatch_text ) ) {
					$class .= ' with-swatch-text';
				}

				if( ! empty( $swatch_style ) ) {
					$swatch_div = '<span style="' . $swatch_style. '"></span>';
				}
				// END swatches customization

				echo '<li class="wc-layered-nav-term ' . esc_attr( $class ) . '">';

				echo ( true == $option_is_set || $count > 0 ) ? '<a href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '">' : '<span>';

				if ( $swatch_div ) {
					echo '<span class="filter-swatch">'.$swatch_div.'</span>';
				}

				echo esc_html( $term->name );

				echo ( true == $option_is_set || $count > 0 ) ? '</a>' : '</span>';

				echo ' <span class="count">(' . absint( $count ) . ')</span></li>';
			}

			echo '</ul>';
			if( $scroll_for_widget ) echo '</div>';

			return $found;
		}

		protected function layered_nav_checkbox_list( $terms, $taxonomy, $query_type, $instance ) {
			$query_type = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
			$title = isset( $instance['filter-title'] ) ? $instance['filter-title'] : esc_html__( 'Filter by', 'basel' );
			$size = isset( $instance['size'] ) ? $instance['size'] : 'normal';
			$categories = isset( $instance['categories'] ) ? $instance['categories'] : array();
			$is_on_shop = is_shop() || is_product_taxonomy();
			$current_cat = get_queried_object();
			if ( $categories[0] && ! in_array( $current_cat->term_id, $categories ) && $is_on_shop ) return;
			
			$is_brand = ( basel_get_opt( 'brands_attribute' ) == $taxonomy );

			$classes = ' show-labels-on';
			$classes .= ' swatches-' . $size;
			$classes .= ( $is_brand ) ? ' swatches-brands' : '';
			$multi_select = ( $query_type == 'or' ) ? ' multi_select' : '';

			$taxonomy_filter_name = str_replace( 'pa_', '', $taxonomy );
			$current_value = isset( $_GET['filter_' . $taxonomy_filter_name] ) ? sanitize_text_field( $_GET['filter_' . $taxonomy_filter_name] ) : '';
			$current_value_arr = explode( ',', $current_value );

			if ( $is_on_shop ) {
				//WC 3.6.0
				if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.6.0', '<' ) ) {
					$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
				} else {
					$term_counts          = $this->get_filtered_term_product_counts_new( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
				}
			}
			
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found = false;

			echo '<div class="basel-pf-checkboxes basel-pf-attributes' . esc_attr( $multi_select ) . '">';
				echo '<input class="result-input" name="filter_' . esc_attr( $taxonomy_filter_name ) . '" type="hidden" value="' . esc_attr( $current_value ) . '">';
				if ( $query_type == 'or' ) {
					echo '<input name="query_type_' . esc_attr( $taxonomy_filter_name ) . '" type="hidden" value="' . esc_attr( $query_type ) . '">';
				}
				echo '<div class="basel-pf-title"><span class="title-text">' . esc_html( $title ) . '</span><ul class="basel-pf-results"></ul></div>';
				echo '<div class="basel-pf-dropdown basel-scroll">';
					echo '<ul class="basel-scroll-content' . esc_attr( $classes ) . '">';
						foreach ( $terms as $term ) {
							$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
							$option_is_set = in_array( $term->slug, $current_values );

							// Only show options with count > 0
							if ( $is_on_shop ) {
								$count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
								if ( 0 < $count ) {
									$found = true;
								}
							}

							// Add swatches block
							$swatch_div = $swatch_style = '';
							$swatch_color = basel_tax_data( $taxonomy, $term->term_id, 'color' );
							$swatch_image = basel_tax_data( $taxonomy, $term->term_id, 'image' );
							$swatch_text = basel_tax_data( $taxonomy, $term->term_id, 'not_dropdown' );

							$class = $option_is_set ? ' pf-active' : '';

							if( ! empty( $swatch_color ) ) {
								$class .= ' with-swatch-color';
								$swatch_style = 'background-color: ' . $swatch_color .';';
							}

							if( ! empty( $swatch_image ) ) {
								$class .= ' with-swatch-image';
								$swatch_style = 'background-image: url(' . $swatch_image .');';
							}

							if( ! empty( $swatch_text ) ) {
								$class .= ' with-swatch-text';
							}

							if( ! empty( $swatch_style ) ) {
								$swatch_div = '<span style="' . $swatch_style. '"></span>';
							}
							// END swatches customization

							echo '<li class="basel-pf-' . esc_attr( $term->slug ) . esc_attr( $class ) . '">';
								echo '<span class="swatch-inner pf-value" data-val="' . esc_attr( $term->slug ) . '" data-title="' . esc_attr( $term->name ) . '">';
									if ( $swatch_div ) {
										echo '<span class="filter-swatch">' . $swatch_div . '</span>';
									}
									echo '<span class="layer-term-name">' . esc_html( $term->name ) . '</span>';
								echo '</span>';
							echo '</li>';
						}
					echo '</ul>';
				echo '</div>';
			echo '</div>';

			if ( ! $is_on_shop ) {
				$found = true;
			}

			return $found;
		}

		function form( $instance ) {
			parent::form( $instance );
		}
	
	} // class
}
