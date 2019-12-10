<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Main loop
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'basel_main_loop' ) ) {

	add_action( 'basel_main_loop', 'basel_main_loop' );

	function basel_main_loop() {

		$blog_design = basel_get_opt('blog_design');
		?>

			<?php if ( have_posts() ) : ?>

				<?php if ( is_tag() && tag_description() ) : // Show an optional tag description ?>
					<div class="archive-meta"><?php echo tag_description(); ?></div>
				<?php endif; ?>

				<?php if ( is_category() && category_description() ) : // Show an optional category description ?>
					<div class="archive-meta"><?php echo category_description(); ?></div>
				<?php endif; ?>

				<?php if ( is_author() && get_the_author_meta( 'description' ) ): ?>
					<?php get_template_part( 'author-bio' ); ?>
				<?php endif ?>
				
				<?php if( in_array( $blog_design, array( 'masonry', 'mask' ) ) ): ?>
					<div class="masonry-container">
				<?php endif ?>
					<?php /* The loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', get_post_format() ); ?>
					<?php endwhile; ?>
				<?php if( in_array( $blog_design, array( 'masonry', 'mask' ) ) ): ?>
					</div>
				<?php endif ?>

				<?php basel_paging_nav(); ?>

			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

		<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Read more button
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'basel_modify_read_more_link' ) ) {
	function basel_modify_read_more_link() {
		return '</p><p class="read-more-section">' . basel_read_more_tag();
	}
}

add_filter( 'the_content_more_link', 'basel_modify_read_more_link' );



if( ! function_exists( 'basel_read_more_tag' ) ) {
	function basel_read_more_tag() {
		return '<a class="btn btn-style-link btn-read-more more-link" href="' . get_permalink() . '">' . esc_html__('Read more', 'basel') . '</a>';
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Get post image
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'basel_get_post_thumbnail' ) ) {
	function basel_get_post_thumbnail( $size = 'medium', $attach_id = false ) {
		global $post;

		if ( has_post_thumbnail() ) {

			if( function_exists( 'wpb_getImageBySize' ) ) {
				if( ! $attach_id ) $attach_id = get_post_thumbnail_id();

				if( basel_loop_prop( 'img_size' ) ) $size = basel_loop_prop( 'img_size' );
				
				$img = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => $size, 'class' => 'attachment-large wp-post-image' ) );
				$img = $img['thumbnail'];

			} else {
				$img = get_the_post_thumbnail( $post->ID, $size );
			}

			return $img;
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get post content
 * ------------------------------------------------------------------------------------------------
 */


if( ! function_exists( 'basel_get_content' ) ) {
	function basel_get_content( $btn = true, $force_full = false ) {
		global $post;

		$type = basel_get_opt( 'blog_excerpt' );

		if( $force_full ) {
			$type = 'full';
		}

		if( $type == 'full' ) {
			basel_get_full_content( $btn );
		} elseif( $type == 'excerpt' ) {

	        if ( ! empty( $post->post_excerpt ) ) {
	            the_excerpt();
	        } else {
		        $excerpt_length = apply_filters( 'basel_get_excerpt_length', basel_get_opt( 'blog_excerpt_length' ) );
		        echo basel_excerpt_from_content( $post->post_content, intval( $excerpt_length ) );
	        }

	        if( $btn ) {
	        	echo '<p class="read-more-section">' . basel_read_more_tag() . '</p>';
	        }

		}

	}
}

if( ! function_exists( 'basel_get_full_content' ) ) {
	function basel_get_full_content( $btn = false ) {

		$strip_gallery  = apply_filters( 'basel_strip_gallery',  true );

		if( get_post_format() == 'gallery' && $strip_gallery ) {

			if( $btn ) {
				$content = basel_strip_shortcode_gallery( get_the_content() );                                       
			} else {
				$content = basel_strip_shortcode_gallery( get_the_content( '' ) );                                       
			}
			echo str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $content ) );       
		} else {
			if( $btn ) {
				the_content();                                  
			} else {
				the_content('');                       
			}
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Display meta information for a specific post
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_post_meta' )) {
	function basel_post_meta( $atts = array() ) {
		extract(shortcode_atts(array(
			'author'     => 1,
			'author_ava' => 0,
			'date'     => 1,
			'cats'     => 0,
			'tags'     => 1,
			'labels'   => 0,
			'short_labels' => false,
			'edit'     => 1,
			'comments' => 1,
			'limit_cats' => 0
		), $atts));
		?>
			<ul class="entry-meta-list">
				<?php if( get_post_type() === 'post' ): ?>

					<?php // Is sticky ?>
					<li class="modified-date"><time class="updated" datetime="<?php echo get_the_modified_date( 'c' ); ?>"><?php echo get_the_modified_date(); ?></time></li>

					<?php if( is_sticky() ): ?>
						<li class="meta-featured-post"><i class="fa fa-thumb-tack"></i> <?php esc_html_e( 'Featured', 'basel' ) ?></li>
					<?php endif; ?>

					<?php // Author ?>
					<?php if ($author == 1): ?>
						<li class="meta-author">
							<?php if ( $labels == 1 && ! $short_labels ): ?>
								<?php esc_html_e('Posted by', 'basel'); ?>
							<?php elseif($labels == 1 && $short_labels): ?>
								<?php esc_html_e('By', 'basel'); ?>
							<?php endif; ?>
							<?php if ( $author_ava == 1 ): ?>
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
							<?php endif; ?>
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								<span class="vcard author author_name">
									<span class="fn"><?php echo get_the_author(); ?></span>
								</span>
							</a>
						</li>
					<?php endif ?>
					<?php // Date ?>
					<?php if( $date == 1): ?><li class="meta-date"><?php the_date(); ?></li><?php endif ?>
					<?php // Categories ?>
					<?php if(get_the_category_list( ', ' ) && $cats == 1): ?>
						<li class="meta-categories"><?php echo get_the_category_list( ', ' ); ?></li>
					<?php endif; ?>
					<?php // Tags ?>
					<?php if(get_the_tag_list( '', ', ' ) && $tags == 1): ?>
						<li class="meta-tags"><?php echo get_the_tag_list( '', ', ' ); ?></li>
					<?php endif; ?>
					<?php // Comments ?>
					<?php if( $comments && comments_open() ): ?>
						<li><span class="meta-reply">
							<?php comments_popup_link( esc_html__( 'Leave a comment', 'basel' ), esc_html__( '1 comment', 'basel' ), esc_html__( '% comments', 'basel' ) ); ?>
						</span></li>
					<?php endif; ?>
					<?php // Edit link ?>
					<?php if( is_user_logged_in() && $edit == 1 ): ?>
						<!--li><?php edit_post_link( esc_html__( 'Edit', 'basel' ), '<span class="edit-link">', '</span>' ); ?></li-->
					<?php endif; ?>
				<?php endif; ?>
			</ul>
		<?php
	}
}

if( ! function_exists( 'basel_post_date' ) ) {
	function basel_post_date() {
		$has_title = get_the_title() != '';
		$attr = '';
		if( ! $has_title && ! is_single() ) {
			$url = get_the_permalink();
			$attr = 'window.location=\''. $url .'\';';
		}
		?>
			<div class="post-date" onclick="<?php echo esc_attr($attr); ?>">
				<span class="post-date-day">
					<?php echo get_the_time('d') ?>
				</span>
				<span class="post-date-month">
					<?php echo get_the_time('M') ?>
				</span>
			</div>
		<?php
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Display entry meta
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_entry_meta' ) ) {
	function basel_entry_meta() {
		if( apply_filters( 'basel_entry_meta' , false ) ) {
			?>
				<footer class="entry-meta">
					<?php if( is_user_logged_in() ): ?>
						<p><?php edit_post_link( esc_html__( 'Edit', 'basel' ), '<span class="edit-link">', '</span>' ); ?></p>
					<?php endif; ?>
				</footer><!-- .entry-meta -->
			<?php 
		}
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Display navigation to the next/previous set of posts.
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'basel_paging_nav' ) ) {
	function basel_paging_nav() {
		$enable_pagination = apply_filters( 'basel_enable_pagination', true );

		if( $enable_pagination ) {
			query_pagination();
			return;
		}
		?>
			
			<ul>
				<?php if( get_previous_posts_link() ) :?>
					<li class="next">
						<?php previous_posts_link( esc_html__( 'Newer Posts &rarr;', 'basel' ) ); ?>
					</li>
				<?php endif; ?>
				
				<?php if( get_next_posts_link() ) :?>
					<li class="previous">
						<?php next_posts_link( esc_html__( '&larr; Older Posts', 'basel' ) ); ?>
					</li>
				<?php endif; ?>
			</ul>
	
		<?php 
	}
}

if( ! function_exists( 'query_pagination' ) ) {
	function query_pagination($pages = '', $range = 2) {  
	     $showitems = ($range * 2)+1;  

	     global $paged;
	     
	     if(empty($paged)) $paged = 1;

	     if($pages == '')
	     {
	         global $wp_query;
	         $pages = $wp_query->max_num_pages;
	         if(!$pages)
	         {
	             $pages = 1;
	         }
	     }   

	     if(1 != $pages)
	     {
	         echo "<div class='basel-pagination'>";
	         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
	         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

	         for ($i=1; $i <= $pages; $i++)
	         {
	             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
	             {
					if ( $paged == $i ) {
						echo "<span class='current'>".$i."</span>";
					} else {
						echo "<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
					}
	             }
	         }

	         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
	         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
	         echo "</div>\n";
	     }
	}
}


// **********************************************************************// 
// ! Add favicon 
// **********************************************************************// 
if( !function_exists( 'basel_favicon' ) ) {
	function basel_favicon() {
		
		if ( function_exists( 'has_site_icon' ) && has_site_icon() ) return '';

		// Get the favicon.
		$favicon = BASEL_IMAGES . '/icons/favicon.png';

		// Get the custom touch icon.
		$touch_icon = BASEL_IMAGES . '/icons/apple-touch-icon-152x152-precomposed.png';

		$fav_uploaded = basel_get_opt( 'favicon' );
		if(isset($fav_uploaded['url']) && $fav_uploaded['url'] != '') {
			$favicon = $fav_uploaded['url'];
		}

		$fav_uploaded_retina = basel_get_opt( 'favicon_retina' );
		if(isset($fav_uploaded_retina['url']) && $fav_uploaded_retina['url'] != '') {
			$touch_icon = $fav_uploaded_retina['url'];
		}

		?>
			<link rel="shortcut icon" href="<?php echo esc_attr($favicon); ?>">
			<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo esc_attr($touch_icon); ?>">
		<?php
	}

	add_action( 'wp_head', 'basel_favicon' );
}


// **********************************************************************// 
// ! Get logo image
// **********************************************************************// 

if( !function_exists( 'basel_get_logo' ) ) {
	function basel_get_logo() {
		$logo_src = BASEL_IMAGES . '/logo.png';

		$logo_uploaded = basel_get_opt( 'logo' );
		if(isset($logo_uploaded['url']) && $logo_uploaded['url'] != '') {
			$logo_src = $logo_uploaded['url'];
		}

		?>
			<a href="<?php echo esc_url( home_url('/') ); ?>" rel="home"><img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a>
		<?php 
	}
}


// **********************************************************************// 
// ! Page top part
// **********************************************************************// 

if( ! function_exists( 'basel_page_top_part' ) ) {
	function basel_page_top_part() {
		?>
		<?php if ( ! basel_is_woo_ajax() ): ?>
			<div class="main-page-wrapper">
		<?php elseif( basel_is_pjax() ): ?>
			<?php _wp_render_title_tag(); ?>
		<?php endif ?>

		<?php 

			/**
			 * basel_after_header hook
			 *
			 * @hooked basel_show_page_title - 10
			 */
			do_action( 'basel_after_header' ); 
		?>

		<!-- MAIN CONTENT AREA -->
		<?php $main_container_class = basel_get_main_container_class(); ?>
		<div class="<?php echo esc_attr( $main_container_class ); ?>">
			<div class="row">
		<?php
	}
}

// **********************************************************************// 
// ! Page bottom part
// **********************************************************************// 

if( ! function_exists( 'basel_page_bottom_part' ) ) {
	function basel_page_bottom_part() {
		?>
				</div> <!-- end row -->
			</div> <!-- end container -->
		<?php
		if ( ! basel_is_woo_ajax() ): ?>
			</div><!-- .main-page-wrapper --> 
		<?php 
		endif;
	}
}

// **********************************************************************// 
// ! Body frame
// **********************************************************************// 

if( ! function_exists( 'basel_body_frame' ) ) {
	function basel_body_frame() {
		if( ! basel_get_opt( 'body-border' ) ) return;
		?>
			<span class="basel-frame-left"></span>
			<span class="basel-frame-top"></span>
			<span class="basel-frame-right"></span>
			<span class="basel-frame-bottom"></span>
		<?php
	}
	add_action( 'basel_after_footer', 'basel_body_frame' );
}

// **********************************************************************// 
// ! owl carousel init function
// **********************************************************************// 

if ( ! function_exists( 'basel_owl_carousel_init' ) ) {
	function basel_owl_carousel_init( $atts = array() ) {
		extract( shortcode_atts( basel_get_owl_atts(), $atts ) );

		$func_name = 'carousel_' . $carousel_id;
		$func_name = function() use( $carousel_id, $slides_per_view, $autoplay, $autoheight, $speed, $hide_pagination_control, $hide_prev_next_buttons, $scroll_per_page, $wrap, $custom_sizes, $center_mode, $post_type ) {
			$items = basel_get_owl_items_numbers( $slides_per_view, $post_type, $custom_sizes );
			?>
			<script type="text/javascript">
				jQuery( document ).ready(function( $ ) {

					var owl = $("#<?php echo esc_js( $carousel_id ); ?> .owl-carousel");

					$( window ).bind( "vc_js", function() {
						owl.trigger('refresh.owl.carousel');
					} );

					var options = {
						rtl: $('body').hasClass('rtl'),
						items: <?php echo esc_js( $items['desktop'] ); ?>, 
						responsive: {
							979: {
								items: <?php echo esc_js( $items['desktop'] ); ?>
							},
							768: {
								items: <?php echo esc_js( $items['desktop_small'] ); ?>
							},
							479: {
								items: <?php echo esc_js( $items['tablet'] ); ?>
							},
							0: {
								items: <?php echo esc_js( $items['mobile'] ); ?>
							}
						},
						autoplay: <?php echo 'yes' === $autoplay ? 'true' : 'false'; ?>,
						<?php if ( 'yes' === $autoplay ): ?>
							autoplayHoverPause: <?php echo apply_filters( 'basel_autoplay_hover_pause', 'true' ); ?>,	
						<?php endif; ?>
						autoplayTimeout: <?php echo esc_js( $speed ); ?>,
						dots: <?php echo 'yes' == $hide_pagination_control ? 'false' : 'true'; ?>,
						nav: <?php echo 'yes' == $hide_prev_next_buttons ? 'false' : 'true'; ?>,
						autoheight: <?php echo 'yes' == $autoheight ? 'false' : 'true'; ?>,
						slideBy:  <?php echo 'yes' == $scroll_per_page ? '\'page\'' : 1; ?>,
						center: <?php echo 'yes' == $center_mode ? 'true' : 'false'; ?>,
						navText:false,
						loop: <?php echo 'yes' == $wrap ? 'true' : 'false'; ?>,
						onRefreshed: function() {
							$(window).resize();
						}
					};

					owl.owlCarousel(options);

				});
			</script>
			<?php
		};

		$func_name();
	}
}

if( ! function_exists( 'basel_get_owl_atts' ) ) {
	function basel_get_owl_atts() {
		return array(
			'carousel_id' => '5000',
			'speed' => '5000',
			'slides_per_view' => '1',
			'wrap' => '',
			'autoplay' => 'no',
			'autoheight' => 'no',
			'hide_pagination_control' => '',
			'hide_prev_next_buttons' => '',
			'carousel_js_inline' => 'no',
			'scroll_per_page' => 'yes',
			'custom_sizes' => '',
			'center_mode' => 'no',
			'sliding_speed' => false,
			'animation' => false,
			'content_animation' => false,
			'post_type' => '',
		);
	}
}

if ( ! function_exists( 'basel_get_owl_attributes' ) ) {
	function basel_get_owl_attributes( $atts = array() ) {
		$default_atts = basel_get_owl_atts();
		$atts = shortcode_atts( $default_atts, $atts ); 
		$output = array( 'data-owl-carousel' );

		foreach ( $atts as $key => $value ) {
			if ( isset( $default_atts[$key] ) && $default_atts[$key] == $value ) {
				unset( $atts[$key] );
			}
		}

		$slides_per_view = isset( $atts['slides_per_view'] ) ? $atts['slides_per_view'] : $default_atts['slides_per_view'];
		$post_type = isset( $atts['post_type'] ) ? $atts['post_type'] : $default_atts['post_type'];
		$custom_sizes = isset( $atts['custom_sizes'] ) ? $atts['custom_sizes'] : false;
		$items = basel_get_owl_items_numbers( $slides_per_view, $post_type, $custom_sizes );

		$excerpt = array( 
			'slides_per_view',
			'post_type',
			'custom_sizes',
			'loop',
			'carousel_id',
			'carousel_js_inline',
		);
		
		foreach ( $atts as $key => $value ) {
			if ( in_array( $key, $excerpt ) ) continue;
			$output[] = 'data-' . $key . '="' . $value . '"';
		}

		foreach ( $items as $key => $value ) {
			$output[] = 'data-' . $key . '="' . $value . '"';
		}

		return implode( ' ', $output );
	}
}


// **********************************************************************//
// Get Owl Items Numbers
// **********************************************************************//
if ( ! function_exists( 'basel_get_owl_items_numbers' ) ) {
	function basel_get_owl_items_numbers( $slides_per_view, $post_type = false, $custom_sizes = false ) {
		$items = array();
		$items['desktop'] = ( $slides_per_view > 0 ) ? $slides_per_view : 1;
		$items['desktop_small'] = ( $items['desktop'] > 1 ) ? $items['desktop'] - 1 : 1;
		if ( $items['desktop'] == 6 ) $items['desktop_small'] = 4;
		$items['tablet'] = ( $items['desktop_small'] > 1 ) ? $items['desktop_small'] : 1;
		$items['mobile'] = ( $items['tablet'] > 2 ) ? $items['tablet'] - 2 : 1;

		if ( $items['mobile'] > 2 ) {
			$items['mobile'] = 2;
		}

		if ( $post_type == 'product' ) {
			$items['mobile'] = basel_get_opt( 'products_columns_mobile' );
		}

		if ( $items['desktop'] == 1 ) {
			$items['mobile'] = 1;
		}

		if ( $custom_sizes ) {
			return $custom_sizes;
		}

		return $items;
	}
}



// **********************************************************************// 
// ! Page title function
// **********************************************************************// 

if( ! function_exists( 'basel_page_title' ) ) {

	add_action( 'basel_after_header', 'basel_page_title', 10 );

	function basel_page_title() {
        global $wp_query, $post;

        // Remove page title for dokan store list page

        if( function_exists( 'dokan_is_store_page' )  && dokan_is_store_page() ) {
        	return '';
        }

		$page_id = 0;

		$disable     = false;
		$page_title  = true;
		$breadcrumbs = basel_get_opt( 'breadcrumbs' );

		$image = '';

		$style = '';

		$page_for_posts    = get_option( 'page_for_posts' );
		$page_for_shop     = get_option( 'woocommerce_shop_page_id' );
		$page_for_projects = basel_tpl2id( 'portfolio.php' );

		$title_class = 'page-title-';

		$title_color = $title_type = $title_size = 'default';

		// Get default styles from Options Panel
		$title_design = basel_get_opt( 'page-title-design' );

		$title_size = basel_get_opt( 'page-title-size' );

		$title_color = basel_get_opt( 'page-title-color' );

		$shop_title = basel_get_opt( 'shop_title' );

		$shop_categories = basel_get_opt( 'shop_categories' );

		$single_post_design = basel_get_opt( 'single_post_design' );

		// Set here page ID. Will be used to get custom value from metabox of specific PAGE | BLOG PAGE | SHOP PAGE.
		$page_id = basel_page_ID();


		if( $page_id != 0 ) {
			// Get meta value for specific page id
			$disable = get_post_meta( $page_id, '_basel_title_off', true );

			$image = get_post_meta( $page_id, '_basel_title_image', true );

			$custom_title_color = get_post_meta( $page_id, '_basel_title_color', true );
			$custom_title_bg_color = get_post_meta( $page_id, '_basel_title_bg_color', true );


			if( $image != '' ) {
				$style .= "background-image: url(" . $image . ");";
			}

			if( $custom_title_bg_color != '' ) {
				$style .= "background-color: " . $custom_title_bg_color . ";";
			}

			if( $custom_title_color != '' && $custom_title_color != 'default' ) {
				$title_color = $custom_title_color;
			}
		}

		if( $title_design == 'disable' ) $page_title = false;

		if( ! $page_title && ! $breadcrumbs ) $disable = true;

		if ( is_single() && $single_post_design == 'large_image' ) $disable = false;

		if( $disable ) return;

		$title_class .= $title_type;
		$title_class .= ' title-size-'  . $title_size;
		$title_class .= ' title-design-' . $title_design;

		if ( $single_post_design == 'large_image' && is_single() ) {
			$title_class .= ' color-scheme-light';
		}else{
			$title_class .= ' color-scheme-' . $title_color;
		}

		if ( $single_post_design == 'large_image' && is_singular( 'post' ) ) {
			$image_url = get_the_post_thumbnail_url( $page_id );
			if ( $image_url && ! $style ) $style .= "background-image: url(" . $image_url . ");";
			$title_class .= ' post-title-large-image';

			?>
				<div class="page-title <?php echo esc_attr( $title_class ); ?>" style="<?php echo esc_attr( $style ); ?>">
					<div class="container">
						<header class="entry-header">
							<?php if ( get_the_category_list( ', ' ) ): ?>
								<div class="meta-post-categories"><?php echo get_the_category_list( ', ' ); ?></div>
							<?php endif ?>

							<h1 class="entry-title"><?php the_title(); ?></h1>

							<div class="entry-meta basel-entry-meta">
								<?php basel_post_meta(array(
									'labels' => 1,
									'author' => 1,
									'author_ava' => 1,
									'date' => 1,
									'edit' => 0,
									'comments' => 1,
									'short_labels' => 0
								)); ?>
							</div>
						</header>
					</div>
				</div>
			<?php
			return;
		}

		// Heading for pages
		if( is_singular( 'page' ) && ( ! $page_for_posts || ! is_page( $page_for_posts ) ) ):
			$title = get_the_title();

			?>
				<div class="page-title <?php echo esc_attr( $title_class ); ?>" style="<?php echo esc_attr( $style ); ?>">
					<div class="container">
						<header class="entry-header">
							<?php if( $page_title ): ?><h1 class="entry-title"><?php echo esc_html( $title ); ?></h1><?php endif; ?>
							<?php if( $breadcrumbs ) basel_current_breadcrumbs( 'pages' ); ?>
						</header><!-- .entry-header -->
					</div>
				</div>
			<?php
			return;
		endif;


		// Heading for blog and archives
		if( is_singular( 'post' ) || basel_is_blog_archive() ):

			$title = ( ! empty( $page_for_posts ) ) ? get_the_title( $page_for_posts ) : esc_html__( 'Blog', 'basel' );

			if( is_tag() ) {
				$title = esc_html__( 'Tag Archives: ', 'basel')  . single_tag_title( '', false ) ;
			}

			if( is_category() ) {
				$title = '<span>' . single_cat_title( '', false ) . '</span>'; //esc_html__( 'Category Archives: ', 'basel') . 
			}

			if( is_date() ) {
				if ( is_day() ) :
					$title = esc_html__( 'Daily Archives: ', 'basel') . get_the_date();
				elseif ( is_month() ) :
					$title = esc_html__( 'Monthly Archives: ', 'basel') . get_the_date( _x( 'F Y', 'monthly archives date format', 'basel' ) );
				elseif ( is_year() ) :
					$title = esc_html__( 'Yearly Archives: ', 'basel') . get_the_date( _x( 'Y', 'yearly archives date format', 'basel' ) );
				else :
					$title = esc_html__( 'Archives', 'basel' );
				endif;
			}

			if ( is_author() ) {
				/*
				 * Queue the first post, that way we know what author
				 * we're dealing with (if that is the case).
				 *
				 * We reset this later so we can run the loop
				 * properly with a call to rewind_posts().
				 */
				the_post();

				$title = esc_html__( 'Posts by ', 'basel' ) . '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>';

				/*
				 * Since we called the_post() above, we need to
				 * rewind the loop back to the beginning that way
				 * we can run the loop properly, in full.
				 */
				rewind_posts();
			}

			if( is_search() ) {
				$title = esc_html__( 'Search Results for: ', 'basel' ) . get_search_query();
			}

			?>
				<div class="page-title <?php echo esc_attr( $title_class ); ?> title-blog" style="<?php echo esc_attr( $style ); ?>">
					<div class="container">
						<header class="entry-header">
							<?php if( $page_title ): ?><h3 class="entry-title"><?php echo wp_kses( $title, basel_get_allowed_html() ); ?></h3><?php endif; ?>
							<?php if( $breadcrumbs ) basel_current_breadcrumbs( 'pages' ); ?>
						</header><!-- .entry-header -->
					</div>
				</div>
			<?php
			return;
		endif;

		// Heading for portfolio
		if( is_post_type_archive( 'portfolio' ) || is_singular( 'portfolio' ) || is_tax( 'project-cat' ) ):

			$title = get_the_title( $page_for_projects );

			if( is_tax( 'project-cat' ) ) {
				$title = single_term_title( '', false );
			}

			?>
				<div class="page-title <?php echo esc_attr( $title_class ); ?> title-blog" style="<?php echo esc_attr( $style ); ?>">
					<div class="container">
						<header class="entry-header">
							<?php if( $page_title ): ?><h1 class="entry-title"><?php echo wp_kses( $title, basel_get_allowed_html() ); ?></h1><?php endif; ?>
							<?php if( $breadcrumbs ) basel_current_breadcrumbs( 'pages' ); ?>
						</header><!-- .entry-header -->
					</div>
				</div>
			<?php
			return;
		endif;

		// Page heading for shop page
		if( basel_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( "product" ) || basel_is_product_attribute_archieve() )
			&& ( $shop_categories || $shop_title )
		 ):

			if( is_product_category() ) {

		        $cat = $wp_query->get_queried_object();

				$cat_image = basel_get_category_page_title_image( $cat );

				if( $cat_image != '') {
					$style = "background-image: url(" . $cat_image . ")";
				}
			}

			if( ! $shop_title ) {
				$title_class .= ' without-title';
			}

			?>
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) && ! is_singular( "product" ) ) : ?>
					<div class="page-title <?php echo esc_attr( $title_class ); ?> title-shop" style="<?php echo esc_attr( $style ); ?>">
						<div class="container">
							<div class="nav-shop">
								
								<?php if ( is_product_category() || is_product_tag() ): ?>
									<?php basel_back_btn(); ?>
								<?php endif ?>

								<?php if ( $shop_title ): ?>
									<h1 class="entry-title"><?php woocommerce_page_title(); ?></h1>
								<?php endif ?>
								
								<?php if( ! is_singular( "product" ) && $shop_categories ) basel_product_categories_nav(); ?>

							</div>
						</div>
					</div>
				<?php endif; ?>

			<?php
			
			return;
		endif;
	}
}

if( ! function_exists( 'basel_back_btn' ) ) {
	function basel_back_btn() {
		?>
			<a href="javascript:baselThemeModule.backHistory()" class="basel-back-btn basel-tooltip"><span><?php esc_html_e('Back', 'basel') ?></span></a>
		<?php
	}
}

// **********************************************************************// 
// ! Recursive function to get page title image for the category or 
// ! take it from some parent term
// **********************************************************************// 

if( ! function_exists( 'basel_get_category_page_title_image' ) ) {
	function basel_get_category_page_title_image( $cat ) {
		$taxonomy = 'product_cat';
		$meta_key = 'title_image';
		$cat_image = basel_tax_data( $taxonomy, $cat->term_id, $meta_key );
		if( $cat_image != '' ) {
			return $cat_image;
		} else if( ! empty( $cat->parent ) ) {
	    	$parent = get_term_by( 'term_id', $cat->parent, $taxonomy );
	    	return basel_get_category_page_title_image( $parent );
		} else {
			return '';
		}
	}
}



// **********************************************************************// 
// ! Breacdrumbs function
// ! Snippet from http://dimox.net/wordpress-breadcrumbs-without-a-plugin/
// **********************************************************************// 

if( ! function_exists( 'basel_breadcrumbs' ) ) {
	function basel_breadcrumbs() {

		/* === OPTIONS === */
		$text['home']     = esc_html__('Home', 'basel'); // text for the 'Home' link
		$text['category'] = esc_html__('Archive by Category "%s"', 'basel'); // text for a category page
		$text['search']   = esc_html__('Search Results for "%s" Query', 'basel'); // text for a search results page
		$text['tag']      = esc_html__('Posts Tagged "%s"', 'basel'); // text for a tag page
		$text['author']   = esc_html__('Articles Posted by %s', 'basel'); // text for an author page
		$text['404']      = esc_html__('Error 404', 'basel'); // text for the 404 page

		$show_current_post  = 0; // 1 - show current post
		$show_current   = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
		$show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
		$show_title     = 1; // 1 - show the title for the links, 0 - don't show
		$delimiter      = ' &raquo; '; // delimiter between crumbs
		$before         = '<span class="current">'; // tag before the current crumb
		$after          = '</span>'; // tag after the current crumb
		/* === END OF OPTIONS === */

		global $post;

		$home_link    = home_url('/');
		$link_before  = '<span typeof="v:Breadcrumb">';
		$link_after   = '</span>';
		$link_attr    = ' rel="v:url" property="v:title"';
		$link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
		$parent_id    = $parent_id_2 = ( ! empty($post) && is_a($post, 'WP_Post') ) ? $post->post_parent : 0;
		$frontpage_id = get_option('page_on_front');
		$projects_id  = basel_tpl2id( 'portfolio.php' );

		if (is_home() || is_front_page()) {

			if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

		} else {

			echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
			if ($show_home_link == 1) {
				echo '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $text['home'] . '</a>';
				if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo esc_html( $delimiter );
			}

			if ( is_category() ) {
				$this_cat = get_category(get_query_var('cat'), false);
				if ($this_cat->parent != 0) {
					$cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
					if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo wp_kses_post( $cats );
				}
				if ($show_current == 1) echo wp_kses_post( $before ) . sprintf($text['category'], single_cat_title('', false)) . wp_kses_post( $after );

			} elseif( is_tax( 'project-cat' ) ) {
				printf($link, get_the_permalink( $projects_id ), get_the_title( $projects_id ));
			} elseif ( is_search() ) {
				echo wp_kses_post( $before ) . sprintf($text['search'], get_search_query()) . wp_kses_post( $after );

			} elseif ( is_day() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
				echo wp_kses_post( $before ) . get_the_time('d') . wp_kses_post( $after );

			} elseif ( is_month() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo wp_kses_post( $before ) . get_the_time('F') . wp_kses_post( $after );

			} elseif ( is_year() ) {
				echo wp_kses_post( $before ) . get_the_time('Y') . wp_kses_post( $after );

			} elseif ( is_single() && !is_attachment() ) {
				if( get_post_type() == 'portfolio' ) {
					printf($link, get_the_permalink( $projects_id ), get_the_title( $projects_id ));
					if ($show_current == 1) echo esc_html( $delimiter ) . $before . get_the_title() . $after;

				} else if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					printf($link, $home_link . $slug['slug'] . '/', $post_type->labels->singular_name);
					if ($show_current == 1) echo esc_html( $delimiter ) . $before . get_the_title() . $after;
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, $delimiter);
					if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo wp_kses_post( $cats );
					if ($show_current_post == 1) echo wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				if ( is_object( $post_type ) ) {
					echo wp_kses_post( $before ) . $post_type->labels->singular_name . wp_kses_post( $after );
				}
				
			} elseif ( is_attachment() ) {
				$parent = get_post($parent_id);
				$cat = get_the_category($parent->ID); $cat = $cat[0];
				if ($cat) {
					$cats = get_category_parents($cat, TRUE, $delimiter);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo wp_kses_post( $cats );
				}
				printf($link, get_permalink($parent), $parent->post_title);
				if ($show_current == 1) echo esc_html( $delimiter ) . $before . get_the_title() . $after;

			} elseif ( is_page() && !$parent_id ) {
				if ($show_current == 1) echo wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );

			} elseif ( is_page() && $parent_id ) {
				if ($parent_id != $frontpage_id) {
					$breadcrumbs = array();
					while ($parent_id) {
						$page = get_page($parent_id);
						if ($parent_id != $frontpage_id) {
							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
						}
						$parent_id = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for ($i = 0; $i < count($breadcrumbs); $i++) {
						echo wp_kses_post( $breadcrumbs[$i] );
						if ($i != count($breadcrumbs)-1) echo esc_html( $delimiter );
					}
				}
				if ($show_current == 1) {
					if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo esc_html( $delimiter );
					echo wp_kses_post( $before ) . get_the_title() . wp_kses_post( $after );
				}

			} elseif ( is_tag() ) {
				echo wp_kses_post( $before ) . sprintf($text['tag'], single_tag_title('', false)) . wp_kses_post( $after );

			} elseif ( is_author() ) {
		 		global $author;
				$userdata = get_userdata($author);
				echo wp_kses_post( $before ) . sprintf($text['author'], $userdata->display_name) . wp_kses_post( $after );

			} elseif ( is_404() ) {
				echo wp_kses_post( $before ) . $text['404'] . wp_kses_post( $after );

			} elseif ( has_post_format() && !is_singular() ) {
				echo get_post_format_string( get_post_format() );
			}

			if ( get_query_var('paged') ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
				echo esc_html__('Page', 'basel' ) . ' ' . get_query_var('paged');
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
			}

			echo '</div><!-- .breadcrumbs -->';

		}
	}
}

// **********************************************************************// 
// ! Promo popup
// **********************************************************************// 

if( ! function_exists( 'basel_promo_popup' ) ) {
	add_action( 'basel_after_footer', 'basel_promo_popup', 200 );

	function basel_promo_popup() {
		if( ! basel_get_opt( 'promo_popup' ) ) return;

		?>
			<div class="basel-promo-popup">
				<div class="basel-popup-inner">
					<?php echo do_shortcode( basel_get_opt( 'popup_text' ) ); ?>
				</div>
			</div>
		<?php
	}
}


// **********************************************************************// 
// ! Cookies law popup
// **********************************************************************// 

if( ! function_exists( 'basel_cookies_popup' ) ) {
	add_action( 'basel_after_footer', 'basel_cookies_popup', 300 );

	function basel_cookies_popup() {
		if( ! basel_get_opt( 'cookies_info' ) ) return;

		$page_id = basel_get_opt( 'cookies_policy_page' );

		?>
			<div class="basel-cookies-popup">
				<div class="basel-cookies-inner">
					<div class="cookies-info-text">
						<?php echo do_shortcode( basel_get_opt( 'cookies_text' ) ); ?>
					</div>
					<div class="cookies-buttons">
						<a href="#" class="cookies-accept-btn"><?php _e( 'Accept' , 'basel' ); ?></a>
						<?php if ( $page_id ): ?>
							<a href="<?php echo get_permalink( $page_id ); ?>" class="cookies-more-btn"><?php _e( 'More info' , 'basel' ); ?></a>
						<?php endif ?>
					</div>
				</div>
			</div>
		<?php
	}
}

// **********************************************************************// 
// ! Header blocks
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_logo' ) ) {
	function basel_header_block_logo() {

		$header_color_scheme = basel_get_opt( 'header_color_scheme' );

		// Get the logo
		$logo 		= BASEL_IMAGES . '/logo.png';
		$logo_white = BASEL_IMAGES . '/logo-white.png';

		$protocol = basel_http() . "://";

		$logo_uploaded = basel_get_opt('logo');
		$logo_white_uploaded = basel_get_opt('logo-white');
		$logo_sticky_uploaded = basel_get_opt('logo-sticky');
		$has_sticky_logo = ( isset( $logo_sticky_uploaded['url'] ) && ! empty( $logo_sticky_uploaded['url'] ) );

		if(isset($logo_white_uploaded['url']) && $logo_white_uploaded['url'] != '') {
			$logo_white = $logo_white_uploaded['url'];
		}
		if(isset($logo_uploaded['url']) && $logo_uploaded['url'] != '') {
			$logo = $logo_uploaded['url'];
		}

		if( $header_color_scheme == 'light' ) {
			$logo = $logo_white;
		}

		$logo = $protocol. str_replace(array('http://', 'https://'), '', $logo);

		?>
			<div class="site-logo">
				<div class="basel-logo-wrap<?php if( $has_sticky_logo ) echo " switch-logo-enable"; ?>">
					<a href="<?php echo esc_url( home_url('/') ); ?>" class="basel-logo basel-main-logo" rel="home">
						<?php echo '<img src="' . $logo . '" alt="' . get_bloginfo( 'name' ) . '" />'; ?>
					</a>
					<?php if ( $has_sticky_logo ): ?>
						<?php 
							$logo_sticky = $protocol . str_replace( array( 'http://', 'https://' ), '', $logo_sticky_uploaded['url'] );
						 ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="basel-logo basel-sticky-logo" rel="home">
							<?php echo '<img src="' . $logo_sticky . '" alt="' . get_bloginfo( 'name' ) . '" />'; ?>
						</a>
					<?php endif ?>
				</div>
			</div>
		<?php
	}
}

// **********************************************************************// 
// ! Header blocks widget area
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_widget_area' ) ) {
	function basel_header_block_widget_area() {
		?>
			<div class="widgetarea-head">
				<?php
				
					$header_text = basel_get_opt( 'header_area' );

					if( $header_text != '' ) {
						echo do_shortcode( $header_text );
					} else if( is_active_sidebar( 'header-widgets' ) ) {
						dynamic_sidebar( 'header-widgets' );
					}
				?>
			</div>
		<?php
	}
}

// **********************************************************************// 
// ! Header blocks wishlist
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_wishlist' ) ) {
	function basel_header_block_wishlist() {
		if ( basel_woocommerce_installed() && class_exists( 'YITH_WCWL' )  && basel_get_opt('header_wishlist') ): ?>
			<div class="wishlist-info-widget">
				<a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>">
					<?php esc_html_e( 'Wishlist', 'basel' ) ?> 
					<?php if ( ! basel_get_opt( 'wishlist_hide_product_count' ) ): ?>
						<span class="wishlist-count"><?php echo YITH_WCWL()->count_products(); ?></span>
					<?php endif; ?>
				</a>
			</div>
		<?php endif;
	}
}

// **********************************************************************// 
// ! Header blocks cart
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_cart' ) ) {
	function basel_header_block_cart() {
		$position = basel_get_opt('cart_position');
		$design = basel_get_opt('shopping_cart');
		$extra_class = 'basel-cart-icon';

		if(  basel_get_opt('shopping_icon_alt') ) {
			$extra_class .= ' basel-cart-alt';
		}

		if( $position == 'side' && $position != 'without' ) {
			$extra_class .= ' cart-widget-opener';
		}
		
		if ( ! basel_woocommerce_installed() || $design == 'disable' || ( ! is_user_logged_in() && basel_get_opt( 'login_prices' ) ) ) return;

		?>
		<div class="shopping-cart basel-cart-design-<?php echo esc_attr( $design ); ?> <?php echo esc_attr( $extra_class ); ?>">
			<a href="<?php echo esc_url(wc_get_cart_url()); ?>">
				<span><?php esc_html_e('Cart', 'basel'); ?> (<span>o</span>)</span>
				<span class="basel-cart-totals">
					<?php basel_cart_count(); ?>
					<span class="subtotal-divider">/</span> 
					<?php basel_cart_subtotal(); ?>
				</span>
			</a>
			<?php if ( $position != 'side' && $position != 'without' ): ?>
				<div class="dropdown-wrap-cat">
					<div class="dropdown-cat">
							<?php 

							// Insert cart widget placeholder - code in woocommerce.js will update this on page load
							echo '<div class="widget woocommerce widget_shopping_cart"><div class="widget_shopping_cart_content"></div></div>';


							?> 
					</div>
				</div>
			<?php endif ?>
		</div>
		<?php
	}
}

// **********************************************************************// 
// ! Header blocks search
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_search' ) ) {
	function basel_header_block_search() {
		$header_search = basel_get_opt( 'header_search' );
		if( $header_search == 'disable' ) return;

		$classes = 'search-button';
		$classes .= ' basel-search-' . $header_search;
		if ( basel_get_opt( 'mobile_search_icon' ) ) $classes .= ' mobile-search-icon';
		?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<a href="#">
					<i class="fa fa-search"></i>
				</a>
				<div class="basel-search-wrapper">
					<div class="basel-search-inner">
						<span class="basel-close-search"><?php esc_html_e('close', 'basel'); ?></span>
						<?php basel_header_block_search_extended( false, true, array('thumbnail' => 1, 'price' => 1), false ); ?>
					</div>
				</div>
			</div>
		<?php
	}
}

// **********************************************************************// 
// ! Header blocks search extended
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_search_extended' ) ) {
	function basel_header_block_search_extended( $show_categories = true, $ajax = true, $ajax_args = array(), $wrap = true ) {
		$class = '';
		$data  = '';

		$ajax_opt = basel_get_opt( 'search_ajax' );
		$search_post_type = basel_get_opt( 'search_post_type' );
		$search_count = basel_get_opt( 'search_ajax_result_count' );

		if( ! $ajax_opt ) $ajax = false;

		$defaults = array(
			'thumbnail' => true,
			'price' => true,
			'count' => $search_count ? $search_count : 5,
			'post_type' => $search_post_type,
		);

		if( $show_categories ) {
			$class .= ' has-categories-dropdown';
		} 

		/**
		 * Parse incoming $args into an array and merge it with $defaults
		 */ 
		$ajax_args = wp_parse_args( $ajax_args, $defaults );

		if( $ajax ) {
			$class .= ' basel-ajax-search';
			foreach ($ajax_args as $key => $value) {
				$data .= ' data-' . $key . '="' . $value . '"';
			}
		}

		switch ( $search_post_type ) {
			case 'product':
				$placeholder = esc_attr_x( 'Search for products', 'basel' );
				$description = esc_html__( 'Start typing to see products you are looking for.', 'basel' );
			break;

			case 'portfolio':
				$placeholder = esc_attr_x( 'Search for projects', 'basel' );
				$description = esc_html__( 'Start typing to see projects you are looking for.', 'basel' );
			break;
		
			default:
				$placeholder = esc_attr_x( 'Search for posts', 'basel' );
				$description = esc_html__( 'Start typing to see posts you are looking for.', 'basel' );
			break;
		}

		if( $wrap ) echo '<div class="search-extended">';
		?>
			<form role="search" method="get" id="searchform" class="searchform <?php echo esc_attr( $class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo ! empty( $data ) ? $data : ''; ?>>
				<div>
					<label class="screen-reader-text"><?php esc_html_e( 'Search for:', 'basel' ); ?></label>
					<input type="text" class="search-field" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo get_search_query(); ?>" name="s" id="s" />
					<input type="hidden" name="post_type" id="post_type" value="<?php echo esc_attr( $search_post_type ); ?>">
					<?php if( $show_categories ) basel_show_categories_dropdown(); ?>
					<button type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'basel' ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'basel' ); ?></button>
					
				</div>
			</form>
			<div class="search-results-wrapper"><div class="basel-scroll"><div class="basel-search-results basel-scroll-content"></div></div></div>
		<?php
		if( $wrap ) echo '</div>';
	}
}

// **********************************************************************// 
// ! Show categories dropdown
// **********************************************************************// 

if( ! function_exists( 'basel_show_categories_dropdown' ) ) {
	function basel_show_categories_dropdown() {
		if( ! basel_get_opt( 'search_categories' ) ) return;
		$args = array( 
			'hide_empty' => 1,
			'parent' => 0
		);
		$terms = get_terms('product_cat', $args);
		if( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			?>
			<div class="search-by-category input-dropdown">
				<div class="input-dropdown-inner">
					<input type="hidden" name="product_cat" value="0">
					<a href="#" data-val="0"><?php esc_html_e('Select category', 'basel'); ?></a>
					<ul class="dropdown-list" style="display:none;">
						<li style="display:none;"><a href="#" data-val="0"><?php esc_html_e('Select category', 'basel'); ?></a></li>
						<?php
							if( ! apply_filters( 'basel_show_only_parent_categories_dropdown', false ) ) {
								wp_list_categories( array( 'use_desc_for_title' => false, 'title_li' => false, 'taxonomy' => 'product_cat', 'walker' => new Basel_Walker_Category) );
							} else {
							    foreach ( $terms as $term ) {
							    	?>
										<li><a href="#" data-val="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_attr( $term->name ); ?></a></li>
							    	<?php
							    }
							}
						?>
					</ul>
				</div>
			</div>
			<?php
		}
	}
}

// **********************************************************************// 
// ! Basel walker category
// **********************************************************************// 

if( ! class_exists( 'Basel_Walker_Category' ) ) {
	class Basel_Walker_Category extends Walker_Category {
	
		/**
		 * Starts the element output.
		 *
		 * @since 2.1.0
		 * @access public
		 *
		 * @see Walker::start_el()
		 *
		 * @param string $output   Passed by reference. Used to append additional content.
		 * @param object $category Category data object.
		 * @param int    $depth    Optional. Depth of category in reference to parents. Default 0.
		 * @param array  $args     Optional. An array of arguments. See wp_list_categories(). Default empty array.
		 * @param int    $id       Optional. ID of the current category. Default 0.
		 */
		public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			/** This filter is documented in wp-includes/category-template.php */
			$cat_name = apply_filters(
				'list_cats',
				esc_attr( $category->name ),
				$category
			);

			// Don't generate an element if the category name is empty.
			if ( ! $cat_name ) {
				return;
			}

			$link = '<a class="pf-value" href="' . esc_url( get_term_link( $category ) ) . '" data-val="' . esc_attr( $category->slug ) . '" data-title="' . esc_attr( $category->name ) . '" ';
			if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
				/**
				 * Filters the category description for display.
				 *
				 * @since 1.2.0
				 *
				 * @param string $description Category description.
				 * @param object $category    Category object.
				 */
				$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
			}

			$link .= '>';
			$link .= $cat_name . '</a>';

			if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
				$link .= ' ';

				if ( empty( $args['feed_image'] ) ) {
					$link .= '(';
				}

				$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

				if ( empty( $args['feed'] ) ) {
					$alt = ' alt="' . sprintf(esc_html__( 'Feed for all posts filed under %s', 'basel' ), $cat_name ) . '"';
				} else {
					$alt = ' alt="' . $args['feed'] . '"';
					$name = $args['feed'];
					$link .= empty( $args['title'] ) ? '' : $args['title'];
				}

				$link .= '>';

				if ( empty( $args['feed_image'] ) ) {
					$link .= $name;
				} else {
					$link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
				}
				$link .= '</a>';

				if ( empty( $args['feed_image'] ) ) {
					$link .= ')';
				}
			}

			if ( ! empty( $args['show_count'] ) ) {
				$link .= ' (' . number_format_i18n( $category->count ) . ')';
			}
			if ( 'list' == $args['style'] ) {
				$default_cat = get_option( 'default_product_cat' );
				$output .= "\t<li";
				$css_classes = array(
					'cat-item',
					'cat-item-' . $category->term_id,
					( $category->term_id == $default_cat ? 'wc-default-cat' : '')
				);

				if ( ! empty( $args['current_category'] ) ) {
					// 'current_category' can be an array, so we use `get_terms()`.
					$_current_terms = get_terms( $category->taxonomy, array(
						'include' => $args['current_category'],
						'hide_empty' => false,
					) );

					foreach ( $_current_terms as $_current_term ) {
						if ( $category->term_id == $_current_term->term_id ) {
							$css_classes[] = 'current-cat pf-active';
						} elseif ( $category->term_id == $_current_term->parent ) {
							$css_classes[] = 'current-cat-parent';
						}
						while ( $_current_term->parent ) {
							if ( $category->term_id == $_current_term->parent ) {
								$css_classes[] =  'current-cat-ancestor';
								break;
							}
							$_current_term = get_term( $_current_term->parent, $category->taxonomy );
						}
					}
				}

				/**
				 * Filters the list of CSS classes to include with each category in the list.
				 *
				 * @since 4.2.0
				 *
				 * @see wp_list_categories()
				 *
				 * @param array  $css_classes An array of CSS classes to be applied to each list item.
				 * @param object $category    Category data object.
				 * @param int    $depth       Depth of page, used for padding.
				 * @param array  $args        An array of wp_list_categories() arguments.
				 */
				$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

				$output .=  ' class="' . $css_classes . '"';
				$output .= ">$link\n";
			} elseif ( isset( $args['separator'] ) ) {
				$output .= "\t$link" . $args['separator'] . "\n";
			} else {
				$output .= "\t$link<br />\n";
			}
		}
	}
}

// **********************************************************************// 
// ! Header block categories menu
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_categories_menu' ) ) {
	function basel_header_block_categories_menu() {
		$categories_menu = basel_get_opt( 'categories-menu' );
		if( $categories_menu == '' ) return;
		
		$opened = false;

		$opened = get_post_meta( basel_get_the_ID(), '_basel_open_categories', true );

		?>
			<div class="mega-navigation <?php if( $opened ) echo 'opened-menu'; else echo 'show-on-hover'; ?>" role="navigation">
				<span class="menu-opener"><span class="burger-icon"></span><?php esc_html_e('Browse Categories', 'basel'); ?><span class="arrow-opener"></span></span>
				<div class="categories-menu-dropdown basel-navigation">
					<?php 
						wp_nav_menu(
							array(
								'menu' => $categories_menu,
								'menu_class' => 'menu',
								'walker' => new BASEL_Mega_Menu_Walker()
							)
						);
					 ?>
				</div>
			</div>
		<?php
	}
}

// **********************************************************************// 
// ! Header block main nav
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_main_nav' ) ) {
	function basel_header_block_main_nav() {
		$location = apply_filters( 'basel_main_menu_location', 'main-menu');
		?>
			<div class="main-nav site-navigation basel-navigation menu-<?php echo esc_attr( basel_get_opt('menu_align') ); ?>" role="navigation">
				<?php 
					if( has_nav_menu( $location ) ) {
						wp_nav_menu(
							array(
								'theme_location' => $location,
								'menu_class' => 'menu',
								'walker' => new BASEL_Mega_Menu_Walker()
							)
						); 
					} else {
						$menu_link = get_admin_url( null, 'nav-menus.php' );
						?>
							<br>
							<h5><?php printf( wp_kses( __('Create your first <a href="%s"><strong>navigation menu here</strong></a>', 'basel'), 'default'), $menu_link) ?></h5>
						<?php
					}
				 ?>
			</div><!--END MAIN-NAV-->
		<?php
	}
}

// **********************************************************************// 
// ! Header block mobile nav
// **********************************************************************// 

if( ! function_exists( 'basel_header_block_mobile_nav' ) ) {
	function basel_header_block_mobile_nav() {

		$menu_locations = get_nav_menu_locations();

		$location = apply_filters( 'basel_main_menu_location', 'main-menu' );

		if(isset($menu_locations['mobile-menu']) && $menu_locations['mobile-menu'] != 0) {
			$location = apply_filters( 'basel_mobile_menu_location', 'mobile-menu' );
		}

		$ajax_args = apply_filters( 'basel_ajax_search_args', array( 'thumbnail' => 1, 'price' => 1 ) );
		$mobile_search_form = basel_get_opt( 'mobile_search_form' ) != '' ? basel_get_opt( 'mobile_search_form' ) : true;

		?>
			<div class="mobile-nav">
				<?php 
					if ( $mobile_search_form ) {
						basel_header_block_search_extended( false, true, $ajax_args, false );
					}

					if( has_nav_menu( $location ) ) {
						wp_nav_menu(
							array(
								'theme_location' => $location,
								'menu_class' => 'site-mobile-menu',
								'walker' => new BASEL_Mega_Menu_Walker()
							)
						);
					}

					basel_header_block_header_links( 'mobile' );

				 ?>
			</div><!--END MOBILE-NAV-->
		<?php
	}
}

// **********************************************************************// 
// ! Header block mobile icon
// **********************************************************************//

if( ! function_exists( 'basel_header_block_mobile_icon' ) ) {
	function basel_header_block_mobile_icon() {
		?>
			<div class="mobile-nav-icon">
				<span class="basel-burger"></span>
			</div><!--END MOBILE-NAV-ICON-->
		<?php
	}
}

// **********************************************************************// 
// ! Header block header links
// **********************************************************************//

if( ! function_exists( 'basel_header_block_header_links' ) ) {
	function basel_header_block_header_links( $location = false ) {
		$links = basel_get_header_links( $location );
		$my_account_style = basel_get_opt( 'header_my_account_style' );
		
		$classes = basel_get_opt( 'my_account_with_username' ) ? ' my-account-with-username' : '';
		$classes .= ( $my_account_style ) ? ' my-account-with-' . $my_account_style : '';
		
		if( ! empty( $links ) ) {
		?>
			<div class="header-links<?php echo esc_attr( $classes ); ?>">
				<ul>
						<?php foreach ( $links as $link ): ?>
						<li class="<?php echo esc_attr( $link['class'] ); ?>"><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo wp_kses( $link['label'], 'default' ); ?></a></li>
					<?php endforeach; ?>
				</ul>		
			</div>
		<?php
		}
	}
}

// **********************************************************************// 
// ! Get header links
// **********************************************************************//

if( ! function_exists( 'basel_get_header_links' ) ) {
	function basel_get_header_links( $location ) {
		$links = array();

		if( ! basel_woocommerce_installed() ) return $links;

		$account_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
		$logout_link = wc_get_account_endpoint_url( 'customer-logout' );
		$current_user = wp_get_current_user();

		if ( class_exists( 'YITH_WCWL' ) && basel_get_opt( 'header_wishlist' ) && $location == 'mobile' ) {
			$wishlist_link = YITH_WCWL()->get_wishlist_url();
			$links[] = array(
				'label' => esc_html__( 'Wishlist', 'basel' ),
				'url' => $wishlist_link,
				'class' => 'wishlist'
			);
		}

		$account_text = esc_html__('My Account', 'basel');

		if ( basel_get_opt( 'my_account_with_username' ) ) {
			$account_text = sprintf( esc_html__( 'Hello, %s', 'basel' ), '<strong>' . esc_html( $current_user->display_name ). '</strong>' );
		}

		if( basel_get_opt( 'login_links' ) ) {
			if( is_user_logged_in() ) {
				$links[] = array(
					'label' => $account_text,
					'url' => $account_link,
					'class' => 'my-account'
				);
				$links[] = array(
					'label' => esc_html__('Logout', 'basel'),
					'url' => $logout_link,
					'class' => 'logout'
				);
			} else {
				$links[] = array(
					'label' => esc_html__('Login / Register', 'basel'),
					'url' => $account_link,
					'class' => ( basel_get_opt( 'login_sidebar' ) && ! is_user_logged_in() ) ? 'login-side-opener' : ''
				);
			}
		}


		return apply_filters( 'basel_get_header_links',  $links );
	}
}

// **********************************************************************// 
// ! Generate Header functions. Based on array of blocks and divs
// **********************************************************************// 

if( ! function_exists( 'basel_generate_header' ) ) {
	function basel_generate_header( $header = 1) {

		$configuration = basel_get_header_configuration( $header );

		basel_process_child( apply_filters( 'basel_header_configuration', $configuration ) );

	}

		function basel_process_child( $configuration ) {
			foreach( $configuration as $key => $block) {
				basel_header_block( $key, $block );
			}
		}

		function basel_header_block($key, $block) {
			if( is_array($block) ) {
				ob_start();
				basel_process_child( $block );
				$output = ob_get_contents();
				ob_end_clean();

				if( ! empty( $output ) ) {
					// If block has child it is a div with class $key
					echo '<div class="' . esc_attr( $key ) . '">' . PHP_EOL;
						echo ! empty( $output ) ? $output : '';
					echo '</div>' . PHP_EOL;
				}
			} else {
				$func = 'basel_header_block_' . $block;
				if( function_exists( $func ) ) {
					$func();
				}
			}
		}

		function basel_get_header_configuration( $header = 'base' ) {
			$configurations = array();

			$configurations['base'] = array(
				'container' => array(
					'wrapp-header' => array(
						'logo',
						'widget_area',
						'right-column' => array(
							'search',
							'wishlist',
							'cart',
							'mobile_icon',
						)
					)
				),
				'navigation-wrap' => array(
					'container' => array(
						'main_nav'
					)
				)
			);

			$configurations['simple'] = array(
				'container' => array(
					'wrapp-header' => array(
						'logo',
						'main_nav',
						'right-column' => array(
							'search',
							'wishlist',
							'cart',
							'mobile_icon',
						)
					)	
				),
			);

			$configurations['split'] = array(
				'container' => array(
					'wrapp-header' => array(
						'right-column left-side' => array(
							'mobile_icon',
							'search',
							'wishlist',
						),
						'logo',
						'main_nav',
						'right-column' => array(
							'header_links',
							'cart',
						)
					)	
				),
			);

			$configurations['overlap'] = array(
				'container' => array(
					'wrapp-header' => array(
						'logo',
						'main_nav',
						'right-column' => array(
							'mobile_icon',
							'cart',
							'wishlist',
							'search',
						)
					)	
				),
			);

			$configurations['logo-center'] = array(
				'container' => array(
					'wrapp-header' => array(
						'widget_area',
						'logo',
						'right-column' => array(
							'header_links',
							'search',
							'wishlist',
							'cart',
							'mobile_icon',
						)
					)
				),
				'navigation-wrap' => array(
					'container' => array(
						'main_nav'
					)
				)
			);

			$configurations['categories'] = array(
				'container' => array(
					'wrapp-header' => array(
						'logo',
						'main_nav',
						'right-column' => array(
							'wishlist',
							'cart',
							'mobile_icon',
						)
					)	
				),
				'secondary-header' => array(
					'container' => array(
						'categories_menu',
						'search_extended',
					)
				)
			);

			$configurations['menu-top'] = array(
				'navigation-wrap' => array(
					'container' => array(
						'mobile_icon',
						'main_nav',
						'widget_area',
						'right-column' => array(
							'search',
							'wishlist',
							'cart',
						)
					),
				),
				'container' => array(
					'logo'
				)
			);

			$configurations['shop'] = array(
				'container' => array(
					'wrapp-header' => array(
						'main_nav',
						'logo',
						'right-column' => array(
							'header_links',
							'search',
							'wishlist',
							'cart',
							'mobile_icon',
						)
					)	
				),
			);


			$configurations['vertical'] = array(
				'wrapp-header' => array(
					'vertical-header-top' => array(
						'logo',
						'right-column' => array(
							'search',
							'wishlist',
							'cart',
							'mobile_icon',
						),
					),
					'navigation-wrap' => array(
						'main_nav'
					),
					'vertical-header-bottom' => array(
						'header_links',
						'widget_area',
					)
				)
			);

			if( ! isset( $configurations[$header] ) ) {
				$header = 'base';
			}

			return $configurations[$header];
			
		} 
}

// **********************************************************************// 
// Get sticky social icon
// **********************************************************************// 
if( ! function_exists( 'basel_get_sticky_social' ) ) {
	function basel_get_sticky_social() {
		if ( ! basel_get_opt( 'sticky_social' ) ) return;
		$classes = 'basel-sticky-social';
		$classes .= ' basel-sticky-social-' . basel_get_opt( 'sticky_social_position' );
		$atts = array(
			'type' => basel_get_opt( 'sticky_social_type' ),
			'el_class' => $classes,
			'style' => 'colored',
			'size' => 'custom',
			'form' => 'square'
		);
		
		echo basel_shortcode_social( $atts );
	}
	add_action( 'basel_after_footer', 'basel_get_sticky_social', 200);
}

// **********************************************************************// 
// Header banner
// **********************************************************************// 
if( ! function_exists( 'basel_header_banner' ) ) {
	function basel_header_banner() {

		if ( ! basel_get_opt( 'header_banner' ) ) return;

		$banner_link = basel_get_opt( 'header_banner_link' );
		?>
	    <div class="header-banner color-scheme-<?php echo esc_attr( basel_get_opt( 'header_banner_color' ) ); ?>">
			
	        <?php if ( basel_get_opt( 'header_close_btn' ) ): ?>
	            <a href="#" class="close-header-banner"></a>
	        <?php endif; ?>
			
			<?php if ( $banner_link ): ?>
	            <a href="<?php echo esc_url( $banner_link ) ?>" class="header-banner-link"></a>
	        <?php endif; ?>
			
	        <div class="container header-banner-container">
	            <?php echo do_shortcode( basel_get_opt( 'header_banner_shortcode' ) ); ?>
	        </div>
			
	    </div>

	    <?php

	}

	add_action( 'basel_after_footer', 'basel_header_banner', 160 );
}

// **********************************************************************// 
// Sidebar login form
// **********************************************************************// 
if( ! function_exists( 'basel_sidebar_login_form' ) ) {
	function basel_sidebar_login_form() {
		$account_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		if ( ! basel_get_opt( 'login_sidebar' ) || is_user_logged_in() || ! basel_woocommerce_installed() ) return;
		?>
			<div class="login-form-side">
				<div class="widget-heading">
					<h3 class="widget-title"><?php esc_html_e( 'Sign in', 'basel' ); ?></h3>
					<a href="#" class="widget-close"><?php esc_html_e( 'close', 'basel' ); ?></a>
				</div>
				
				<div class="login-form">
					<?php basel_login_form( true, $account_link ); ?>
				</div>
				
				<div class="register-question">
					<span class="create-account-text"><?php esc_html_e( 'No account yet?', 'basel' ); ?></span>
					<a class="btn btn-style-link" href="<?php echo esc_url( $account_link ); ?>?action=register"><?php esc_html_e( 'Create an Account', 'basel' ); ?></a>
				</div>
			</div>
		<?php
	}

	add_action( 'basel_after_body_open', 'basel_sidebar_login_form', 10 );
}

// **********************************************************************// 
// Login form HTML
// **********************************************************************// 
if( ! function_exists( 'basel_login_form' ) ) {
	function basel_login_form( $echo = true, $action = false, $message = false, $hidden = false, $redirect = false ) {
		$vk_app_id         = basel_get_opt( 'vk_app_id' );
		$vk_app_secret     = basel_get_opt( 'vk_app_secret' );
		$fb_app_id         = basel_get_opt( 'fb_app_id' );
		$fb_app_secret     = basel_get_opt( 'fb_app_secret' );
		$goo_app_id        = basel_get_opt( 'goo_app_id' );
		$goo_app_secret    = basel_get_opt( 'goo_app_secret' );
		$style             = basel_get_opt( 'alt_social_login_btns_style' ) ? 'basel-social-alt-style' : '';
		
		ob_start();
		?>
		<form method="post" class="login woocommerce-form woocommerce-form-login <?php if ( $hidden ) echo 'hidden-form'; ?>" <?php echo ( ! empty( $action ) ) ? 'action="' . esc_url( $action ) . '"' : ''; ?> <?php if ( $hidden ) echo 'style="display:none;"'; ?>>

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<?php echo true == $message ? wpautop( wptexturize( $message ) ) : ''; ?>

			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide form-row-username">
				<label for="username"><?php esc_html_e( 'Username or email', 'basel' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
			</p>
			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide form-row-password">
				<label for="password"><?php esc_html_e( 'Password', 'basel' ); ?>&nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<?php if ( $redirect ): ?>
					<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
				<?php endif ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'basel' ); ?>"><?php esc_html_e( 'Log in', 'basel' ); ?></button>
			</p>

			<div class="login-form-footer">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="woocommerce-LostPassword lost_password"><?php esc_html_e( 'Lost your password?', 'basel' ); ?></a>
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" value="forever" /> <span><?php esc_html_e( 'Remember me', 'basel' ); ?></span>
				</label>
			</div>
			
			<?php if ( class_exists( 'BASEL_Auth' ) && ( ( ! empty( $fb_app_id ) && ! empty( $fb_app_secret ) ) || ( ! empty( $goo_app_id ) && ! empty( $goo_app_secret ) ) || ( ! empty( $vk_app_id ) && ! empty( $vk_app_secret ) ) ) ): ?>
				<span class="social-login-title"><?php esc_html_e('Or login with', 'basel'); ?></span>
				<div class="basel-social-login <?php echo esc_attr( $style ); ?>">
					<?php if ( ! empty( $fb_app_id ) && ! empty( $fb_app_secret ) ): ?>
						<div class="social-login-btn">
							<a href="<?php echo add_query_arg('social_auth', 'facebook', wc_get_page_permalink('myaccount')); ?>" class="btn login-fb-link"><?php esc_html_e( 'Facebook', 'basel' ); ?></a>
						</div>
					<?php endif ?>
					<?php if ( ! empty( $goo_app_id ) && ! empty( $goo_app_secret ) ): ?>
						<div class="social-login-btn">
							<a href="<?php echo add_query_arg('social_auth', 'google', wc_get_page_permalink('myaccount')); ?>" class="btn login-goo-link"><?php esc_html_e( 'Google', 'basel' ); ?></a>
						</div>
					<?php endif ?>
					<?php if ( ! empty( $vk_app_id ) && ! empty( $vk_app_secret ) ): ?>
						<div class="social-login-btn">
							<a href="<?php echo add_query_arg('social_auth', 'vkontakte', wc_get_page_permalink('myaccount')); ?>" class="btn login-vk-link"><?php esc_html_e( 'VKontakte', 'basel' ); ?></a>
						</div>
					<?php endif ?>
				</div>
			<?php endif ?>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

		<?php

		if( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

// **********************************************************************// 
// Sticky toolbar
// **********************************************************************// 
if ( ! function_exists( 'basel_get_sticky_toolbar_fields' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 3.6
	 */
	function basel_get_sticky_toolbar_fields() {
		$product_attributes = array();

		$fields = array(
			'enabled'  => array(
				'shop'     => esc_html__( 'Shop page', 'basel' ),
				'sidebar'  => esc_html__( 'Off canvas sidebar', 'basel' ),
				'wishlist' => esc_html__( 'Wishlist', 'basel' ),
				'cart'     => esc_html__( 'Cart', 'basel' ),
				'account'  => esc_html__( 'My account', 'basel' ),
			),
			'disabled' => array(
				'mobile'   => esc_html__( 'Mobile menu', 'basel' ),
				'home'     => esc_html__( 'Home page', 'basel' ),
				'blog'     => esc_html__( 'Blog page', 'basel' ),
				'compare'  => esc_html__( 'Compare', 'basel' ),
				'link_1'   => esc_html__( 'Custom button [1]', 'basel' ),
				'link_2'   => esc_html__( 'Custom button [2]', 'basel' ),
				'link_3'   => esc_html__( 'Custom button [3]', 'basel' ),
			),
		);

		if ( apply_filters( 'basel_toolbar_search', false ) ) {
			$fields['disabled']['search'] = esc_html__( 'Search', 'basel' );
		}

		return $fields;
	}
}

if ( ! function_exists( 'basel_sticky_toolbar_template' ) ) {
	/**
	 * Sticky toolbar template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_template() {
		$fields  = basel_get_opt( 'sticky_toolbar_fields' );
		$classes = '';

		if ( isset( $fields['enabled']['placebo'] ) ) {
			unset( $fields['enabled']['placebo'] );
		}

		if ( ! basel_get_opt( 'sticky_toolbar' ) || ! $fields['enabled'] ) {
			return;
		}

		if ( basel_get_opt( 'sticky_toolbar_label' ) ) {
			$classes .= ' basel-toolbar-label-show';
		}

		?>
			<div class="basel-toolbar icons-design-line<?php echo esc_attr( $classes ); ?>">
		<?php
		foreach ( $fields['enabled'] as $key => $value ) {
			switch ( $key ) {
				case 'wishlist':
					basel_sticky_toolbar_wishlist_template();
					break;
				case 'cart':
					basel_sticky_toolbar_cart_template();
					break;
				case 'compare':
					basel_sticky_toolbar_compare_template();
					break;
				case 'search':
					basel_sticky_toolbar_search_template();
					break;
				case 'account':
					basel_sticky_toolbar_account_template();
					break;
				case 'home':
					basel_sticky_toolbar_page_link_template( $key );
					break;
				case 'blog':
					basel_sticky_toolbar_page_link_template( $key );
					break;
				case 'shop':
					basel_sticky_toolbar_page_link_template( $key );
					break;
				case 'mobile':
					basel_sticky_toolbar_mobile_menu_template();
					break;
				case 'sidebar':
					basel_sticky_sidebar_button( false, true );
					break;
				case 'search':
					basel_sticky_toolbar_search_template();
					break;
				case 'link_1':
					basel_sticky_toolbar_custom_link_template( $key );
					break;
				case 'link_2':
					basel_sticky_toolbar_custom_link_template( $key );
					break;
				case 'link_3':
					basel_sticky_toolbar_custom_link_template( $key );
					break;
			}
		}
		?>
			</div>
		<?php

	}

	add_action( 'basel_before_wp_footer', 'basel_sticky_toolbar_template' );
}

if ( ! function_exists( 'basel_sticky_toolbar_custom_link_template' ) ) {
	/**
	 * Sticky toolbar custom link template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_custom_link_template( $key ) {
		$wrapper_classes = '';
		$url             = basel_get_opt( $key . '_url' );
		$text            = basel_get_opt( $key . '_text' );
		$icon            = basel_get_opt( $key . '_icon' );

		$wrapper_classes .= isset( $icon['id'] ) && $icon['id'] ? ' basel-with-icon' : '';

		?>
			<?php if ( $url && $text ) : ?>
				<div class="basel-toolbar-link basel-toolbar-item<?php echo esc_attr( $wrapper_classes ); ?>">
					<a href="<?php echo esc_url( $url ); ?>">
						<span class="basel-toolbar-icon">
							<?php if ( isset( $icon['id'] ) && $icon['id'] ) : ?>
								<?php echo wp_get_attachment_image( $icon['id'] ); ?>
							<?php endif; ?>
						</span>

						<span class="basel-toolbar-label">
							<?php esc_html_e( $text ); ?>
						</span>
					</a>
				</div>
			<?php endif; ?>
		<?php
	}
}

if ( ! function_exists( 'basel_sticky_toolbar_wishlist_template' ) ) {
	/**
	 * Sticky toolbar wishlist template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_wishlist_template() {
		if ( ! basel_woocommerce_installed() || ! class_exists( 'YITH_WCWL' ) ) {
			return;
		}

		$hide_product_count = basel_get_opt( 'wishlist_hide_product_count' );
		$classes       = '';

		if ( $hide_product_count ) {
			$classes .= ' without-product-count';
		}

		?>
		<div class="wishlist-info-widget<?php echo esc_attr( $classes ); ?>" title="<?php echo esc_attr__( 'My wishlist', 'basel' ); ?>">
			<a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>">
				<span class="wishlist-count">
					<?php echo YITH_WCWL()->count_products(); ?>
				</span>
				<span class="basel-toolbar-label">
					<?php esc_html_e( 'Wishlist', 'basel' ); ?>
				</span>
			</a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'basel_sticky_toolbar_cart_template' ) ) {
	/**
	 * Sticky toolbar cart template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_cart_template() {
		if ( ! basel_woocommerce_installed() || ( ! is_user_logged_in() && basel_get_opt( 'login_prices' ) ) ) {
			return;
		}

		$opener  = basel_get_opt( 'cart_position' ) == 'side';
		$classes = '';

		if ( $opener ) {
			$classes .= ' cart-widget-opener';
		}

		?>
		<div class="shopping-cart basel-cart-design-3 <?php echo esc_attr( $classes ); ?>" title="<?php echo esc_attr__( 'My cart', 'basel' ); ?>">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
				<span class="basel-cart-totals">
					<?php basel_cart_count(); ?>
				</span>
				<span class="basel-toolbar-label">
					<?php esc_html_e( 'Cart', 'basel' ); ?>
				</span>
			</a>
		</div>
		<?php
	}
}


if ( ! function_exists( 'basel_sticky_toolbar_compare_template' ) ) {
	/**
	 * Sticky toolbar compare template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_compare_template() {
		if ( ! basel_woocommerce_installed() || ! basel_get_opt( 'compare' ) ) {
			return;
		}

		// $product_count = basel_get_opt( 'compare_hide_product_count' );
		$product_count = true;
		$classes       = '';

		if ( ! $product_count ) {
			$classes .= ' without-product-count';
		}

		?>
		<div class="basel-toolbar-compare basel-toolbar-item<?php echo esc_attr( $classes ); ?>" title="<?php echo esc_attr__( 'Compare products', 'basel' ); ?>">
			<a href="<?php echo esc_url( basel_get_compare_page_url() ); ?>">
				<?php if ( $product_count ) : ?>
					<span class="compare-count"><?php echo basel_get_compare_count(); ?></span>
				<?php endif; ?>
				<span class="basel-toolbar-label">
					<?php esc_html_e( 'Compare', 'basel' ); ?>
				</span>
			</a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'basel_sticky_toolbar_search_template' ) ) {
	/**
	 * Sticky toolbar search template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_search_template() {
		?>
		<div class="search-button mobile-search-icon">
			<a href="#">
				<span class="search-button-icon"></span>
				<span class="basel-toolbar-label">
					<?php esc_html_e( 'Search', 'basel' ); ?>
				</span>
			</a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'basel_sticky_toolbar_account_template' ) ) {
	/**
	 * Sticky toolbar account template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_account_template() {
		if ( ! basel_woocommerce_installed() ) {
			return;
		}

		$is_side  = basel_get_opt( 'login_sidebar' );
		$classes  = ! is_user_logged_in() && $is_side ? ' login-side-opener' : '';

		?>
		<div class="basel-toolbar-account basel-toolbar-item<?php echo esc_attr( $classes ); ?>">
			<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
				<span class="basel-toolbar-label">
					<?php esc_html_e( 'My account', 'basel' ); ?>
				</span>
			</a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'basel_sticky_toolbar_page_link_template' ) ) {
	/**
	 * Sticky toolbar page link template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_page_link_template( $key ) {
		$url = '';

		switch ( $key ) {
			case 'blog':
				$url  = get_permalink( get_option( 'page_for_posts' ) );
				$text = esc_html__( 'Blog', 'basel' );
				break;
			case 'home':
				$url  = get_home_url();
				$text = esc_html__( 'Home', 'basel' );
				break;
			case 'shop':
				$url  = basel_woocommerce_installed() ? get_permalink( wc_get_page_id( 'shop' ) ) : get_home_url();
				$text = esc_html__( 'Shop', 'basel' );
				break;
		}

		?>
		<div class="basel-toolbar-<?php echo esc_attr( $key ); ?> basel-toolbar-item">
			<a href="<?php echo esc_url( $url ); ?>">
				<span class="basel-toolbar-label">
					<?php esc_html_e( $text ); ?>
				</span>
			</a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'basel_sticky_toolbar_mobile_menu_template' ) ) {
	/**
	 * Sticky toolbar mobile menu template
	 *
	 * @since 3.6
	 */
	function basel_sticky_toolbar_mobile_menu_template() {
		?>
		<div class="basel-burger-icon mobile-nav-icon mobile-style-icon">
			<span class="basel-burger"></span>
			<span class="basel-toolbar-label">
				<?php esc_html_e( 'Menu', 'basel' ); ?>
			</span>
		</div>
		<?php
	}
}
