<?php
/**
 * The sidebar containing the secondary widget area
 *
 * Displays on posts and pages.
 *
 * If no active widgets are in this sidebar, hide it completely.
 */

$sidebar_class = basel_get_sidebar_class();

$sidebar_name = basel_get_sidebar_name();

if( strstr( $sidebar_class, 'col-sm-0' ) ) return;

if ( is_active_sidebar( $sidebar_name ) ) : ?>
	<aside class="sidebar-container <?php echo esc_attr( $sidebar_class ); ?> area-<?php echo esc_attr( $sidebar_name ); ?>" role="complementary">
		<div class="basel-close-sidebar-btn"><span><?php esc_html_e( 'Close', 'basel' ); ?></span></div>
		<div class="sidebar-inner basel-sidebar-scroll">
			<div class="widget-area basel-sidebar-content">
				<?php do_action( 'basel_before_sidebar_area' ); ?>
				<?php dynamic_sidebar( $sidebar_name ); ?>
				<?php do_action( 'basel_after_sidebar_area' ); ?>
			</div><!-- .widget-area -->
		</div><!-- .sidebar-inner -->
	</aside><!-- .sidebar-container -->
<?php endif; ?>