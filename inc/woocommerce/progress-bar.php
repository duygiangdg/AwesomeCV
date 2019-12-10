<?php

if ( ! defined( 'BASEL_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Basel stock progress bar
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'basel_stock_progress_bar' ) ) {
	function basel_stock_progress_bar() { // phpcs:ignore
		$product_id  = get_the_ID();
		$total_stock = get_post_meta( $product_id, 'basel_total_stock_quantity', true );

		if ( ! $total_stock ) {
			return;
		}

		$current_stock = round( get_post_meta( $product_id, '_stock', true ) );

		$total_sold = $total_stock > $current_stock ? $total_stock - $current_stock : 0;
		$percentage = $total_sold > 0 ? round( $total_sold / $total_stock * 100 ) : 0;

		if ( $current_stock > 0 ) {
			echo '<div class="basel-stock-progress-bar">';
				echo '<div class="stock-info">';
					echo '<div class="total-sold">' . esc_html__( 'Ordered:', 'basel' ) . '<span>' . esc_html( $total_sold ) . '</span></div>';
					echo '<div class="current-stock">' . esc_html__( 'Items available:', 'basel' ) . '<span>' . esc_html( $current_stock ) . '</span></div>';
				echo '</div>';
				echo '<div class="progress-area" title="' . esc_html__( 'Sold', 'basel' ) . ' ' . esc_attr( $percentage ) . '%">';
					echo '<div class="progress-bar"style="width:' . esc_attr( $percentage ) . '%;"></div>';
				echo '</div>';
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'basel_total_stock_quantity_input' ) ) {
	function basel_total_stock_quantity_input() { // phpcs:ignore
		echo '<div class="options_group">';
			woocommerce_wp_text_input(
				array(
					'id'          => 'basel_total_stock_quantity',
					'label'       => esc_html__( 'Initial number in stock', 'basel' ),
					'desc_tip'    => 'true',
					'description' => esc_html__( 'Required for stock progress bar option', 'basel' ),
					'type'        => 'text',
				)
			);
		echo '</div>';
	}

	add_action( 'woocommerce_product_options_inventory_product_data', 'basel_total_stock_quantity_input' );
}

if ( ! function_exists( 'basel_save_total_stock_quantity' ) ) {
	function basel_save_total_stock_quantity( $post_id ) { // phpcs:ignore
		$stock_quantity = isset( $_POST['basel_total_stock_quantity'] ) && $_POST['basel_total_stock_quantity'] ? wc_clean( $_POST['basel_total_stock_quantity'] ) : ''; // phpcs:ignore

		update_post_meta( $post_id, 'basel_total_stock_quantity', $stock_quantity );
	}

	add_action( 'woocommerce_process_product_meta_simple', 'basel_save_total_stock_quantity' );
	add_action( 'woocommerce_process_product_meta_variable', 'basel_save_total_stock_quantity' );
	add_action( 'woocommerce_process_product_meta_grouped', 'basel_save_total_stock_quantity' );
	add_action( 'woocommerce_process_product_meta_external', 'basel_save_total_stock_quantity' );
}
