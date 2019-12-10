<?php if ( ! defined( 'BASEL_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * Add metaboxesto pages and posts
 * uses CMB plugins
 * 
 */

/*
    to fix image uploads for taxonomies
    add to file CMB2hookup.php
    line 197
    if ( in_array( $hook, array( 'edit-tags.php', 'post.php', 'post-new.php', 'page-new.php', 'page.php' ), true ) ) {

 */
class BASEL_Metaboxes {
    /**
     * Options slug for Redux Framework
     * @var string
     */
	private $opt_name = "basel_options";


    /**
     * Add actions
     * 
     */
	public function __construct() {

		//add_action( 'init', array( $this, 'load_cmb_plugin' ), 199 );

        add_action( 'cmb2_init', array( $this, 'pages_metaboxes' ), 5000 );
        add_action( 'cmb2_init', array( $this, 'product_metaboxes' ), 6000 );
        add_action( 'cmb2_init', array( $this, 'product_categories' ), 7000 );
        add_action( 'cmb2_init', array( $this, 'posts_categories' ), 8000 );
        add_action( 'cmb2_init', array( $this, 'slider' ), 10000 );

        add_action("redux/metaboxes/{$this->opt_name}/boxes", array( $this, 'metaboxes' ) );
	}

    /**
     * Register all custom metaboxes with CMB2 API
     */
    public function pages_metaboxes() {
        global $basel_transfer_options, $basel_prefix;

        // Start with an underscore to hide fields from custom fields list
        $basel_prefix = '_basel_';
        
        $basel_metaboxes = new_cmb2_box( array(
            // 'cmb_styles' => false, // false to disable the CMB stylesheet
            // 'closed'     => true, // true to keep the metabox closed by default
            'id' => 'page_metabox',
            'title' => esc_html__( 'Page Setting (custom metabox from theme)', 'basel' ),
            'object_types' => array('page', 'post', 'portfolio'), // post type
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Custom sidebar for this page', 'basel' ),
            'id'      => $basel_prefix . 'custom_sidebar',
            'type'    => 'select',
            'options' => basel_get_sidebars_array()
        ) );

        $basel_transfer_options = array( 
            'main_layout',
            'sidebar_width',
            'header',
            'header-overlap',
            'header_color_scheme',
            'page-title-size',
        );

        foreach ($basel_transfer_options as $field) {
            $cmb_field = $this->redux2cmb_field( $field );
            $basel_metaboxes->add_field( $cmb_field );
        }

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Disable Page title', 'basel' ),
            'desc'    => esc_html__( 'You can hide page heading for this page', 'basel' ),
            'id'      => $basel_prefix . 'title_off',
            'type'    => 'checkbox',
        ) );

        $basel_metaboxes->add_field( array(
            'name' => esc_html__( 'Image for page heading', 'basel' ),
            'desc' => esc_html__( 'Upload an image', 'basel' ),
            'id' => $basel_prefix . 'title_image',
            'type' => 'file',
            'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
        ) );


        $basel_metaboxes->add_field( array(
            'name' => esc_html__( 'Page heading background color', 'basel' ),
            'desc' => esc_html__( 'Upload an image', 'basel' ),
            'id' => $basel_prefix . 'title_bg_color',
            'type' => 'colorpicker',
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Text color for heading', 'basel' ),
            'id'      => $basel_prefix . 'title_color',
            'type'    => 'radio_inline',
            'options' => array(
                'default' => esc_html__( 'Inherit', 'basel' ),
                'light' => esc_html__( 'Light', 'basel' ), 
                'dark' => esc_html__( 'Dark', 'basel' ),
            ),
            'default' => 'default'
        ) );


        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Open categories menu', 'basel' ),
            'desc'    => esc_html__( 'Always shows categories navigation on this page', 'basel' ),
            'id'      => $basel_prefix . 'open_categories',
            'type'    => 'checkbox',
        ) );
    }

    /**
     * Metaboxes for products
     */
    public function product_metaboxes() {
        global $basel_prefix, $basel_transfer_options;

        // Start with an underscore to hide fields from custom fields list
        $basel_prefix = '_basel_';
        $taxonomies_list = array( '' => 'Select' );
        $taxonomies = get_taxonomies(); 
        foreach ( $taxonomies as $taxonomy ) {
            $taxonomies_list[$taxonomy] = $taxonomy;
        }

        $basel_metaboxes = new_cmb2_box( array(
            // 'cmb_styles' => false, // false to disable the CMB stylesheet
            // 'closed'     => true, // true to keep the metabox closed by default
            'id' => 'product_metabox',
            'title' => esc_html__( 'Product Setting (custom metabox from theme)', 'basel' ),
            'object_types' => array('product'), // post type
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Add "New" label', 'basel' ), 
            'desc'    => esc_html__( 'You can add "New" label to this product', 'basel' ), 
            'id'      => $basel_prefix . 'new_label',
            'type'    => 'checkbox',
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Hide related products', 'basel' ), 
            'desc'    => esc_html__( 'You can hide related products on this page', 'basel' ), 
            'id'      => $basel_prefix . 'related_off',
            'type'    => 'checkbox',
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Hide tabs headings', 'basel' ), 
            'desc'    => esc_html__( 'Description and Additional information', 'basel' ), 
            'id'      => $basel_prefix . 'hide_tabs_titles',
            'type'    => 'checkbox',
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Grid swatch attribute to display', 'basel' ), 
            'desc'    => esc_html__( 'Choose attribute that will be shown on products grid for this particular product', 'basel' ),
            'id'      => $basel_prefix . 'swatches_attribute',
            'type'    => 'select',
            'options' => $taxonomies_list
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Product video URL', 'basel' ), 
            'desc'    => esc_html__( 'Vimeo or YouTube video url. For example: https://www.youtube.com/watch?v=1zPYW6Ipgok', 'basel' ), 
            'id'      => $basel_prefix . 'product_video',
            'type'    => 'text',
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Instagram product hashtag', 'basel' ), 
            'desc'    => wp_kses(  __( 'Insert tag that will be used to display images from instagram from your customers. For example: <strong>#nike_rush_run</strong>', 'basel' ), 'default' ),
            'id'      => $basel_prefix . 'product_hashtag',
            'type'    => 'text',
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Product background', 'basel' ), 
            'id'      => $basel_prefix . 'product-background',
            'type'    => 'colorpicker',
        ) );

        $basel_local_transfer_options = array( 
            'single_product_style',
            'product_design',
            'main_layout',
            'sidebar_width',
        );

        foreach ($basel_local_transfer_options as $field) {
            $cmb_field = $this->redux2cmb_field( $field );
            $basel_metaboxes->add_field( $cmb_field );
        }

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Custom sidebar for this product', 'basel' ),
            'id'      => $basel_prefix . 'custom_sidebar',
            'type'    => 'select',
            'options' => basel_get_sidebars_array()
        ) );

        $blocks = array_flip(basel_get_static_blocks_array());

        $blocks = (array)'None' + $blocks;

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Extra content block', 'basel' ),
            'desc'    => esc_html__( 'You can create some extra content with WPBakery Page Builder (in Admin panel / HTML Blocks / Add new) and add it to this product', 'basel' ),
            'id'      => $basel_prefix . 'extra_content',
            'type'    => 'select',
            'options' => $blocks
        ) );

        $basel_metaboxes->add_field( array(
            'name'    => esc_html__( 'Extra content position', 'basel' ),
            'id'      => $basel_prefix . 'extra_position',
            'type'    => 'radio_inline',
            'options' => array(
                'after' => esc_html__( 'After content', 'basel' ),
                'before' => esc_html__( 'Before content', 'basel' ),
                'prefooter' => esc_html__( 'Prefooter', 'basel' ),
            ),
            'default' => 'after'
        ) );
		
		//Custom tab
		$basel_metaboxes->add_field( array(
			'name'    => esc_html__( 'Custom tab title', 'basel' ), 
			'id'      => $basel_prefix . 'product_custom_tab_title',
			'type'    => 'text',
		) );
		
		$basel_metaboxes->add_field( array(
			'name'    => esc_html__( 'Custom tab content', 'basel' ), 
			'id'      => $basel_prefix . 'product_custom_tab_content',
			'type'    => 'textarea',
		) );

        $basel_transfer_options = array_merge( $basel_transfer_options, $basel_local_transfer_options );
        
    }

    public function posts_categories() {

        $blog_design_field = $this->redux2cmb_field( 'blog_design' );

        $blog_design_field['name'] .= ' for this category';

		$cmb_term = cmb2_get_metabox( array(
			'id'               => 'cat_options',
			'object_types'     => array( 'term' ), 
			'taxonomies'       => array( 'category' ), 
			'new_term_section' => true, // Will display in the "Add New Category" section
		), basel_get_current_term_id(), 'term' );

		$cmb_term->add_field($blog_design_field);
	}

    public function product_categories() {
        $field = array(
                    'name' => esc_html__( 'Image for category heading', 'basel' ),
                    'desc' => esc_html__( 'Upload an image', 'basel' ),
                    'id' => 'title_image',
                    'type' => 'file',
                    'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
                );

		$cmb_term = cmb2_get_metabox( array(
			'id'               => 'product_cat_options',
			'object_types'     => array( 'term' ), 
			'taxonomies'       => array( 'product_cat' ), 
			'new_term_section' => true, // Will display in the "Add New Category" section
		), basel_get_current_term_id(), 'term' );

		$cmb_term->add_field($field);

    }

    public function slider() {
        $metabox = new_cmb2_box( array(
            // 'cmb_styles' => false, // false to disable the CMB stylesheet
            // 'closed'     => true, // true to keep the metabox closed by default
            'id' => 'slide_metabox',
            'title' => esc_html__( 'Slide Settings', 'basel' ),
            'object_types' => array('basel_slide'), // post type
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
        ) );

        $metabox->add_field( array(
            'name'    => esc_html__( 'Background color', 'basel' ),
            'id'      => 'bg_color',
            'type'    => 'colorpicker',
            'default' => '#fefefe'
		) );

		$metabox->add_field( array(
			'name'    => esc_html__( 'Slide image on tablet', 'basel' ),
			'id'      => 'bg_image_tablet',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => esc_html__( 'Add image', 'basel' )
			),
			'preview_size' => array( 100, 100 ),
		) );

		$metabox->add_field( array(
			'name'    => esc_html__( 'Slide image on mobile', 'basel' ),
			'id'      => 'bg_image_mobile',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => esc_html__( 'Add image', 'basel' )
			),
			'preview_size' => array( 100, 100 ),
		) );

        $metabox->add_field( array(
            'name'    => esc_html__( 'Vertical content align', 'basel' ),
            'id'      => 'vertical_align',
            'type'    => 'basel_images_select',
            'images_opts' => array(
                'top' => array(
                    'label' => esc_html__( 'Top', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/cmb2-align/top.jpg',
                ),
                'middle' => array(
                    'label' => esc_html__( 'Middle', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/cmb2-align/middle.jpg',
                ),
                'bottom' => array(
                    'label' => esc_html__( 'Bottom', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/cmb2-align/bottom.jpg',
                ),
            ),
            'default' => 'middle',
        ) );

        $metabox->add_field( array(
            'name'    => esc_html__( 'Horizontal content align', 'basel' ),
            'id'      => 'horizontal_align',
            'type'    => 'basel_images_select',
            'images_opts' => array(
                'left' => array(
                    'label' => esc_html__( 'Left', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/cmb2-align/left.jpg',
                ),
                'center' => array(
                    'label' => esc_html__( 'Center', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/cmb2-align/center.jpg',
                ),
                'right' => array(
                    'label' => esc_html__( 'Right', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/cmb2-align/right.jpg',
                ),
            ),
            'default' => 'left',
        ) );

        $metabox->add_field( array(
            'name' => esc_html__( 'Full width content', 'basel' ),
            'desc' => 'Takes the slider\'s width',
            'id'   => 'content_full_width',
            'type' => 'checkbox',
        ) );

        $metabox->add_field( array(
            'name'        => esc_html__( 'Content width [on desktop]', 'basel' ),
            'desc'        => esc_html__( 'Set your value in pixels.', 'basel' ),
            'id'          => 'content_width',
            'type'        => 'basel_slider',
            'min'         => '100',
            'max'         => '1200',
            'step'        => '5',
            'default'     => '1200', // start value
            'value_label' => 'Value:',
            'attributes' => array(
                'data-conditional-id'    => 'content_full_width',
                'data-conditional-value' => 'off',
            ),
        ));

        $metabox->add_field( array(
            'name'        => esc_html__( 'Content width [on tablets]', 'basel' ),
            'desc'        => esc_html__( 'Set your value in pixels.', 'basel' ),
            'id'          => 'content_width_tablet',
            'type'        => 'basel_slider',
            'min'         => '100',
            'max'         => '1200',
            'step'        => '5',
            'default'     => '1200', // start value
            'value_label' => 'Value:',
            'attributes' => array(
                'data-conditional-id'    => 'content_full_width',
                'data-conditional-value' => 'off',
            ),
        ));

        $metabox->add_field( array(
            'name'        => esc_html__( 'Content width [on mobile]', 'basel' ),
            'desc'        => esc_html__( 'Set your value in pixels.', 'basel' ),
            'id'          => 'content_width_mobile',
            'type'        => 'basel_slider',
            'min'         => '50',
            'max'         => '800',
            'step'        => '5',
            'default'     => '300', // start value
            'value_label' => 'Value:',
            'attributes' => array(
                'data-conditional-id'    => 'content_full_width',
                'data-conditional-value' => 'off',
            ),
        ));

        $metabox->add_field( array(
            'name'             => esc_html__( 'Animation', 'basel' ),
            'desc'             => esc_html__( 'Select a content appearance animation', 'basel' ),
            'id'               => 'slide_animation',
            'type'             => 'select',
            'show_option_none' => false,
            'default'          => 'none',
            'options'          => array(
                'none' => esc_html__( 'None', 'basel' ),
                'slide-from-top' => esc_html__( 'Slide from top', 'basel' ),
                'slide-from-bottom' => esc_html__( 'Slide from bottom', 'basel' ),
                'slide-from-right' => esc_html__( 'Slide from right', 'basel' ),
                'slide-from-left' => esc_html__( 'Slide from left', 'basel' ),
                'top-flip-x' => esc_html__( 'Top flip X', 'basel' ),
                'bottom-flip-x' => esc_html__( 'Bottom flip X', 'basel' ),
                'right-flip-y' => esc_html__( 'Right flip Y', 'basel' ),
                'left-flip-y' => esc_html__( 'Left flip Y', 'basel' ),
                'zoom-in' => esc_html__( 'Zoom in', 'basel' ),
            ),
        ) );

        $slider_metabox = cmb2_get_metabox( array(
            'id'               => 'slider_settings',
            'object_types'     => array( 'term' ),
            'taxonomies'       => array( 'basel_slider' ),
            'new_term_section' => true, 
        ), basel_get_current_term_id(), 'term' );


        $slider_metabox->add_field( array(
            'name' => esc_html__( 'Stretch slider', 'basel' ),
            'desc' => esc_html__( 'Make slider full width', 'basel' ),
            'id'   => 'stretch_slider',
            'type' => 'checkbox',
        ) );

        $slider_metabox->add_field( array(
            'name'        => esc_html__( 'Height on desktop', 'basel' ),
            'desc'        => esc_html__( 'Set your value in pixels.', 'basel' ),
            'id'          => 'height',
            'type'        => 'basel_slider',
            'min'         => '100',
            'max'         => '1200',
            'step'        => '5',
            'default'     => '500', // start value
            'value_label' => 'Value:',
        ));

        $slider_metabox->add_field( array(
            'name'        => esc_html__( 'Height on tablet', 'basel' ),
            'desc'        => esc_html__( 'Set your value in pixels.', 'basel' ),
            'id'          => 'height_tablet',
            'type'        => 'basel_slider',
            'min'         => '100',
            'max'         => '1200',
            'step'        => '5',
            'default'     => '500', // start value
            'value_label' => 'Value:',
        ));

        $slider_metabox->add_field( array(
            'name'        => esc_html__( 'Height on mobile', 'basel' ),
            'desc'        => esc_html__( 'Set your value in pixels.', 'basel' ),
            'id'          => 'height_mobile',
            'type'        => 'basel_slider',
            'min'         => '100',
            'max'         => '1200',
            'step'        => '5',
            'default'     => '500', // start value
            'value_label' => 'Value:',
        ));

        $slider_metabox->add_field( array(
            'name'        => esc_html__( 'Arrows style', 'basel' ),
            // 'desc'        => 'Set your value in pixels.',
            'id'          => 'arrows_style',
            'type'        => 'basel_images_select',
            'images_opts' => array(
                '1' => array(
                    'label' => 'Style 1',
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-1.jpg',
                ),
                '2' => array(
                    'label' => 'Style 2',
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-2.jpg',
                ),
                '3' => array(
                    'label' => 'Style 3',
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-3.jpg',
                ),
                '0' => array(
                    'label' => 'Disable',
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-disable.jpg',
                ),
            ),
            'default'     => '1',
        ));

        $slider_metabox->add_field( array(
            'name'        => esc_html__( 'Pagination style', 'basel' ),
            // 'desc'        => 'Set your value in pixels.',
            'id'          => 'pagination_style',
            'type'        => 'basel_images_select',
            'images_opts' => array(
                '1' => array(
                    'label' => esc_html__( 'Style 1', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-1.jpg',
                ),
                '2' => array(
                    'label' => esc_html__( 'Style 2', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-2.jpg',
                ),
                '0' => array(
                    'label' => esc_html__( 'Disable', 'basel' ),
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-disable.jpg',
                ),
            ),
            'default'     => '1',
        ));

        $slider_metabox->add_field( array(
            'name'        => esc_html__( 'Navigation color scheme', 'basel' ),
            // 'desc'        => 'Set your value in pixels.',
            'id'          => 'pagination_color',
            'type'        => 'basel_images_select',
            'images_opts' => array(
                'light' => array(
                    'label' => 'Light',
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/pagination-color-light.jpg',
                ),
                'dark' => array(
                    'label' => 'Dark',
                    'image' => BASEL_ASSETS_IMAGES . '/settings/slider-navigation/pagination-color-dark.jpg',
                ),
            ),
            'default'     => 'light',
        ));

        $slider_metabox->add_field( array(
            'name' => esc_html__( 'Enable autoplay', 'basel' ),
            'desc' => 'Rotate slider images automatically.',
            'id'   => 'autoplay',
            'type' => 'checkbox',
        ) );

        $slider_metabox->add_field( array(
            'name'    => esc_html__( 'Slide change animation', 'basel' ),
            'id'      => 'animation',
            'type'    => 'radio_inline',
            'options' => array(
                'slide' => esc_html__( 'Slide', 'basel' ),
                'fade'  => esc_html__( 'Fade', 'basel' ),
            ),
            'default' => 'slide',
        ) );
    }

    /**
     * Transfer function from redux to CMB2
     * @param  string $field      field slug in Redux options
     * @return array  $cmb_field  CMB compatible field config array
     */
	public function redux2cmb_field( $field ) {

        if( ! class_exists('Redux') ) return array(
            'id' => '',
            'type' => '',
            'name' => '',
            'options' => '',
            'default' => 'default'  ,
        );

		$prefix = '_basel_';

		$field = Redux::getField($this->opt_name, $field);

		$options = array();
		
		switch ($field['type']) {
			case 'image_select':
				$type = 'select';
				$options = ( ! empty( $field['options'] ) ) ? array_merge( array('default' => array('title' => 'Inherit') ), $field['options'] ) : array();
				foreach ($options as $key => $option) {
					$options[$key] = ( isset( $options[$key]['alt'] ) ) ? $options[$key]['alt'] : $options[$key]['title'];
				}
			break;

			case 'button_set':
				$type = 'radio_inline';
				$options['default'] = 'Inherit';
				foreach ($field['options'] as $key => $value) {
					$options[$key] = $value;
				}
			break;

            case 'select':
                $type = 'select';
                $options['inherit'] = 'Inherit';
                foreach ($field['options'] as $key => $value) {
                    $options[$key] = $value;
                }
            break;

            case 'switch':
                $type = 'checkbox';
            break;
			
			default:
				$type = $field['type'];
			break;
		}

		$cmb_field = array(
			'id' => $prefix . $field['id'],
			'type' => $type,
			'name' => $field['title'],
			'options' => $options,
		);

		return $cmb_field;
	}

    public function metaboxes($metaboxes) {
        // Declare your sections
        $boxSections = array();
        $boxSections[] = array(
            'title' => 'Performance',
            'id' => 'performance',
            'icon' => 'el-icon-cog',
            'fields' => array (
                array (         
                    'id'       => 'product-background',
                    'type'     => 'background',
                    'title'    => esc_html__( 'Product background', 'basel' ),
                    'subtitle' => esc_html__( 'Set background for your products page. You can also specify different background for particular products while editing it.', 'basel' ),
                    'output'   => array('.single-product-content')
                ),
            ),
        );
 
        // Declare your metaboxes
        $metaboxes = array();
        $metaboxes[] = array(
            'id'            => 'sidebar',
            'title'         => esc_html__( 'Sidebar', 'basel' ),
            'post_types'    => array( 'product' ),
            //'page_template' => array('page-test.php'), // Visibility of box based on page template selector
            //'post_format' => array('image'), // Visibility of box based on post format
            'position'      => 'normal', // normal, advanced, side
            'priority'      => 'high', // high, core, default, low - Priorities of placement
            'sections'      => $boxSections,
        );
 
        return $metaboxes;
    }

}
