<?php
/**
 * WooCommerce compare functions
 *
 * @package basel
 */

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}


if ( ! function_exists( 'basel_wishlist_shortcode' ) ) {
	/**
	 * WooCommerce compare page shortcode.
	 *
	 * @since 4.5
	 */
	function basel_compare_shortcode() {
		if ( ! basel_woocommerce_installed() ) return;
		ob_start();
		?>
			<?php basel_get_compared_products_table(); ?>
		<?php
		return ob_get_clean();
	}

}


if ( ! function_exists( 'basel_add_to_compare' ) ) {
	/**
	 * Add product to comapre
	 *
	 * @since 4.5
	 */
	function basel_add_to_compare() {

		$id = sanitize_text_field( $_GET['id'] );

		$cookie_name = basel_compare_cookie_name();

		if ( basel_is_product_in_compare( $id ) ) {
			basel_compare_json_response();
		}

		$products = basel_get_compared_products();

		$products[] = $id;

		setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

		$_COOKIE[$cookie_name] = json_encode( $products );

		basel_compare_json_response();
	}

	add_action( 'wp_ajax_basel_add_to_compare', 'basel_add_to_compare' );
	add_action( 'wp_ajax_nopriv_basel_add_to_compare', 'basel_add_to_compare' );
}


if ( ! function_exists( 'basel_add_to_compare_btn' ) ) {
	/**
	 * Add product to comapre button
	 *
	 * @since 4.5
	 */
	function basel_add_to_compare_btn() {
		global $product;
		echo '<div class="basel-compare-btn product-compare-button"><a class="button" href="' . esc_url( basel_get_compare_page_url() ) . '" data-added-text="' . esc_html__('Compare products', 'basel') . '" data-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__('Compare', 'basel') . '</a></div>';
	}
}

if ( ! function_exists( 'basel_add_to_compare_single_btn' ) ) {
	/**
	 * Add product to comapre button on single product
	 *
	 * @since 4.4
	 */
	function basel_add_to_compare_single_btn() {
		global $product;
		echo '<a class="basel-compare-btn button" href="' . esc_url( basel_get_compare_page_url() ) . '" data-added-text="' . esc_html__('Compare products', 'basel') . '" data-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__('Compare', 'basel') . '</a>';
	}
}


if ( ! function_exists( 'basel_compare_json_response' ) ) {
	/**
	 * Compare JSON reponse.
	 *
	 * @since 4.5
	 */
	function basel_compare_json_response() {
		$count = 0;
		$products = basel_get_compared_products();

		ob_start();

		basel_get_compared_products_table();

		$table_html = ob_get_clean();

		if ( is_array( $products ) ) {
			$count = count( $products );
		}

		wp_send_json( array(
			'count' => $count,
			'table' => $table_html,
		) );
	}
}

if ( ! function_exists( 'basel_get_compare_page_url' ) ) {
	/**
	 * Get compare page ID.
	 *
	 * @since 4.5
	 */
	function basel_get_compare_page_url() {
		$page_id = basel_get_opt( 'compare_page' );

		return get_permalink( $page_id );
	}
}


if ( ! function_exists( 'basel_remove_from_compare' ) ) {
	/**
	 * Add product to comapre
	 *
	 * @since 4.5
	 */
	function basel_remove_from_compare() {

		$id = sanitize_text_field( $_GET['id'] );

		$cookie_name = basel_compare_cookie_name();

		if ( ! basel_is_product_in_compare( $id ) ) {
			basel_compare_json_response();
		}

		$products = basel_get_compared_products();

		foreach ( $products as $k => $product_id ) {
			if ( intval( $id ) == $product_id ) {
				unset( $products[ $k ] );
			}
		}

		if ( empty( $products ) ) {
			setcookie( $cookie_name, false, 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			$_COOKIE[$cookie_name] = false;
		} else {
			setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			$_COOKIE[$cookie_name] = json_encode( $products );
		}

		basel_compare_json_response();
	}

	add_action( 'wp_ajax_basel_remove_from_compare', 'basel_remove_from_compare' );
	add_action( 'wp_ajax_nopriv_basel_remove_from_compare', 'basel_remove_from_compare' );
}


if ( ! function_exists( 'basel_is_product_in_compare' ) ) {
	/**
	 * Is product in compare
	 *
	 * @since 4.5
	 */
	function basel_is_product_in_compare( $id ) {
		$list = basel_get_compared_products();

		return in_array( $id, $list, true );
	}
}


if ( ! function_exists( 'basel_get_compare_count' ) ) {
	/**
	 * Get compare number.
	 *
	 * @since 4.5
	 */
	function basel_get_compare_count() {
		$count = 0;
		$products = basel_get_compared_products();

		if ( is_array( $products ) ) {
			$count = count( $products );
		}

		return $count;
	}
}


if ( ! function_exists( 'basel_compare_cookie_name' ) ) {
	/**
	 * Get compare cookie namel.
	 *
	 * @since 4.5
	 */
	function basel_compare_cookie_name() {
		$name = 'basel_compare_list';

        if ( is_multisite() ) $name .= '_' . get_current_blog_id();

        return $name;

	}
}


if ( ! function_exists( 'basel_get_compared_products' ) ) {
	/**
	 * Get compared products IDs array
	 *
	 * @since 4.5
	 */
	function basel_get_compared_products() {
		$cookie_name = basel_compare_cookie_name();
        return isset( $_COOKIE[ $cookie_name ] ) ? json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) : array();
	}
}

if ( ! function_exists( 'basel_is_products_have_field' ) ) {
	/**
	 * Checks if the products have such a field.
	 *
	 * @since 3.4
	 */
	function basel_is_products_have_field( $field_id, $products ) {
		foreach ( $products as $product_id => $product ) {
			if ( isset( $product[ $field_id ] ) && ( ! empty( $product[ $field_id ] ) && '-' !== $product[ $field_id ] && 'N/A' !== $product[ $field_id ] ) ) {
				return true;
			}
		}

		return false;
	}
}


if ( ! function_exists( 'basel_get_compared_products_table' ) ) {
	/**
	 * Get compared products data table HTML
	 *
	 * @since 4.5
	 */
	function basel_get_compared_products_table() {
		$products = basel_get_compared_products_data();
		$fields = basel_get_compare_fields();
		$empty_compare_text = basel_get_opt( 'empty_compare_text' );

		?>
		<div class="basel-compare-table">
			<?php
			if ( ! empty( $products ) ) {
				array_unshift( $products, array() );
				foreach ( $fields as $field_id => $field ) {
					if ( ! basel_is_products_have_field( $field_id, $products ) ) {
						continue;
					}
					?>
						<div class="basel-compare-row compare-<?php echo esc_attr( $field_id ); ?>">
							<?php foreach ( $products as $product_id => $product) : ?>
								<?php if ( ! empty( $product ) ) : ?>
									<div class="basel-compare-col compare-value" data-title="<?php echo esc_attr( $field ); ?>">
										<?php basel_compare_display_field( $field_id, $product ); ?>
									</div>
								<?php else: ?>
									<div class="basel-compare-col compare-field">
										<?php 
											$field_name = basel_compare_get_field_name_by_id( $field_id );
											echo true == $field_name ? esc_html( $field_name ) : esc_html( $field );
										?>
									</div>
								<?php endif; ?>

							<?php endforeach ?>
						</div>
					<?php
				}	
			} else {
				?>
					<p class="basel-empty-compare">
						<?php esc_html_e('Compare list is empty.', 'basel'); ?>
					</p>
					<?php if ( $empty_compare_text ) : ?>
						<div class="basel-empty-page-text"><?php echo wp_kses( $empty_compare_text, array('p' => array(), 'h1' => array(), 'h2' => array(), 'h3' => array(), 'strong' => array(), 'em' => array(), 'span' => array(), 'div' => array() , 'br' => array()) ); ?></div>
					<?php endif; ?>
					<p class="return-to-shop">
						<a class="button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
							<?php esc_html_e( 'Return to shop', 'basel' ); ?>
						</a>
					</p>
				<?php
			}

			?>
			<div class="compare-loader"></div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'basel_get_compare_fields' ) ) {
	/**
	 * Get compare fields data.
	 *
	 * @since 4.5
	 */
	function basel_get_compare_fields() {
		$fields = array(
			'basic' => ''
		);

		$fields_settings = basel_get_opt('fields_compare');

		if ( isset( $fields_settings['enabled'] ) && count( $fields_settings['enabled'] ) > 1 ) {
			$fields = $fields + $fields_settings['enabled'];
			unset( $fields['placebo'] );
		}

		return $fields;
	}
}


if ( ! function_exists( 'basel_compare_display_field' ) ) {
	/**
	 * Get compare fields data.
	 *
	 * @since 4.5
	 */
	function basel_compare_display_field( $field_id, $product ) {

		$type = $field_id;

		if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
			$type = 'attribute';
		}
		
		switch ( $type ) {
			case 'basic':
				echo '<div class="compare-basic-content">';
					echo '<a href="#" class="basel-compare-remove" data-id="' . esc_attr( $product['id'] ) . '"><span class="remove-loader"></span>' . esc_html__( 'Remove', 'basel' ) . '</a>';
					echo '<a class="product-image" href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['image']. '</a>';
					echo '<h4 class="product-title"><a href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['title']. '</a></h4>';
					echo wp_kses_post( $product['basic']['rating'] );
					echo '<div class="price">';
						echo wp_kses_post( $product['basic']['price'] );
					echo '</div>';
					if ( ! basel_get_opt( 'catalog_mode' ) ) {
						echo apply_filters( 'basel_compare_add_to_cart_btn', $product['basic']['add_to_cart'] );
					}
				echo '</div>';
			break;

			case 'attribute':
				if ( $field_id === basel_get_opt( 'brands_attribute' ) ) {
					$brands = wc_get_product_terms( $product['id'], $field_id, array( 'fields' => 'all' ) );

					if ( empty( $brands ) ) {
						echo '-';
						return;
					}

					foreach ($brands as $brand) {
						$image = get_term_meta( $brand->term_id, 'image', true);

						if( ! empty( $image ) ) {
							echo apply_filters( 'basel_image', '<img src="' . esc_url( $image ) . '" title="' . esc_attr( $brand->name ) . '" alt="' . esc_attr( $brand->name ) . '" />' );
						} else {
							echo wp_kses_post( $product[ $field_id ] );
						}

					}
				} else {
					echo wp_kses_post( $product[ $field_id ] );
				}
				break;

			case 'weight':
				if ( $product[ $field_id ] ) {
					$unit = $product[ $field_id ] !== '-' ? get_option( 'woocommerce_weight_unit' ) : '';
					echo wc_format_localized_decimal( $product[ $field_id ] ) . ' ' . esc_attr( $unit );
				} 
				break;

			default:
				echo wp_kses_post( $product[ $field_id ] );
				break;
		}
	}
}


if ( ! function_exists( 'basel_get_compared_products_data' ) ) {
	/**
	 * Get compared products data
	 *
	 * @since 4.5
	 */
	function basel_get_compared_products_data() {
		$ids = basel_get_compared_products();

		if ( empty( $ids ) ) {
			return array();
		}

		$args = array(
			'include' => $ids,
		);

		$products = wc_get_products( $args );

		$products_data = array();

		$fields = basel_get_compare_fields();

		$fields = array_filter( $fields, function(  $field ) {
			return 'pa_' === substr( $field, 0, 3 );
		}, ARRAY_FILTER_USE_KEY );

		$divider = '-';

		foreach ( $products as $product ) {
			$rating_count = $product->get_rating_count();
			$average = $product->get_average_rating();

			$products_data[ $product->get_id() ] = array(
				'basic' => array(
					'title' => $product->get_title() ? $product->get_title() : $divider,
					'image' => $product->get_image() ? $product->get_image() : $divider,
					'rating' => wc_get_rating_html( $average, $rating_count ),
					'price' => $product->get_price_html() ? $product->get_price_html() : $divider,
					'add_to_cart' => basel_compare_add_to_cart_html( $product ) ? basel_compare_add_to_cart_html( $product ) :$divider,
				),
				'id' => $product->get_id(),
				'image_id' => $product->get_image_id(),
				'permalink' => $product->get_permalink(),
				'dimensions' => wc_format_dimensions( $product->get_dimensions( false ) ),
				'description' => $product->get_short_description() ? $product->get_short_description() : $divider,
				'weight' => $product->get_weight() ? $product->get_weight() : $divider,
				'sku' => $product->get_sku() ? $product->get_sku() : $divider,
				'availability' => basel_compare_availability_html( $product ),
			);

			foreach ( $fields as $field_id => $field_name ) {
				if ( taxonomy_exists( $field_id ) ) {
					$products_data[ $product->get_id() ][ $field_id ] = array();
					$terms = get_the_terms( $product->get_id(), $field_id );
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$term = sanitize_term( $term, $field_id );
							$products_data[ $product->get_id() ][ $field_id ][] = $term->name;
						}
					} else {
						$products_data[ $product->get_id() ][ $field_id ][] = '-';
					}
					$products_data[ $product->get_id() ][ $field_id ] = implode( ', ', $products_data[ $product->get_id() ][ $field_id ] );
				}
			}
		}

		return $products_data;
	}
}

if ( ! function_exists( 'basel_compare_availability_html' ) ) {
	/**
	 * Get product availability html.
	 *
	 * @since 4.5
	 */
	function basel_compare_availability_html( $product ) {
		$html         = '';
		$availability = $product->get_availability();

		if( empty( $availability['availability'] ) ) {
			$availability['availability'] = esc_html__( 'In stock', 'woocommerce' );
		}

		if ( ! empty( $availability['availability'] ) ) {
			ob_start();

			wc_get_template( 'single-product/stock.php', array(
				'product'      => $product,
				'class'        => $availability['class'],
				'availability' => $availability['availability'],
			) );

			$html = ob_get_clean();
		}

		return apply_filters( 'woocommerce_get_stock_html', $html, $product );
	}
}


if ( ! function_exists( 'basel_compare_add_to_cart_html' ) ) {
	/**
	 * Get product add to cart html.
	 *
	 * @since 4.5
	 */
	function basel_compare_add_to_cart_html( $product ) {
		if ( ! $product || basel_get_opt( 'catalog_mode' ) ) return;

		$defaults = array(
			'quantity'   => 1,
			'class'      => implode( ' ', array_filter( array(
				'button',
				'product_type_' . $product->get_type(),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
			) ) ),
			'attributes' => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

		if ( isset( $args['attributes']['aria-label'] ) ) {
			$args['attributes']['aria-label'] = strip_tags( $args['attributes']['aria-label'] );
		}

		return apply_filters( 'woocommerce_loop_add_to_cart_link', 
			sprintf( '<a href="%s" data-quantity="%s" class="%s add-to-cart-loop" %s><span>%s</span></a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			),
		$product, $args );
	}
}


if ( ! function_exists( 'basel_compare_available_fields' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function basel_compare_available_fields() {
		$product_attributes = array();

		if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$product_attributes = wc_get_attribute_taxonomies();
		}

		$fields = array(
			'enabled'  => array(
				'description' => esc_html__( 'Description', 'basel' ),
				'sku' => esc_html__( 'Sku', 'basel' ),
				'availability' => esc_html__( 'Availability', 'basel' ),
			),
			'disabled' => array(
				'weight' => esc_html__( 'Weight', 'basel' ),
				'dimensions' => esc_html__( 'Dimensions', 'basel' ),
			)
		);

		if ( count( $product_attributes ) > 0 ) {
			foreach ( $product_attributes as $attribute ) {
				$fields['disabled'][ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
			}	
		}

		return $fields;
	}
}

if ( ! function_exists( 'basel_compare_get_field_name_by_id' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 4.5
	 */
	function basel_compare_get_field_name_by_id( $id ) {
		$fields = array(
			'description' => esc_html__( 'Description', 'basel' ),
			'sku' => esc_html__( 'Sku', 'basel' ),
			'availability' => esc_html__( 'Availability', 'basel' ),
			'weight' => esc_html__( 'Weight', 'basel' ),
			'dimensions' => esc_html__( 'Dimensions', 'basel' ),
		);

		return isset( $fields[ $id ] ) ? $fields[ $id ] : '';
	}
}


