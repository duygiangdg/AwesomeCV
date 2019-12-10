<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register all of the default WordPress widgets on startup.
 *
 * Calls 'basel_widgets_init' action after all of the WordPress widgets have been
 * registered.
 *
 * @since 2.2.0
 */

include_once( 'widgets/class-widget-price-filter.php');
include_once( 'widgets/class-widget-layered-nav.php');

include_once( 'widgets/class-wp-nav-menu-widget.php');
include_once( 'widgets/class-widget-search.php');
include_once( 'widgets/class-widget-sorting.php');
include_once( 'widgets/class-user-panel-widget.php');
include_once( 'widgets/class-author-area-widget.php');
include_once( 'widgets/class-banner-widget.php');
include_once( 'widgets/class-instagram-widget.php');
include_once( 'widgets/class-static-block-widget.php');





