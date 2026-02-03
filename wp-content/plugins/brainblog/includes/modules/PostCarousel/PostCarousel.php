<?php

class BRBL_PostCarousel extends BRBL_Builder_Module_Type_PostBased {

	protected $module_credits = array(
		'module_uri' => 'https://divipeople.com/plugins/brain-blog',
		'author'     => 'DiviPeople',
		'author_uri' => 'https://divipeople.com',
	);

	public function init() {

		$this->vb_support = 'on';
		$this->slug       = 'brbl_post_carousel';
		$this->name       = esc_html__('DP Post Carousel', 'brain-divi-blog');
		$this->icon_path  = plugin_dir_path(__FILE__) . 'post-carousel.svg';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'query'             => esc_html__('Query', 'brain-divi-blog'),
					'layout'            => esc_html__('Layout Settings', 'brain-divi-blog'),
					'carousel_settings' => array(
						'title'             => esc_html__('Carousel Settings', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'general'  => array(
								'name' => esc_html__('General', 'brain-divi-blog'),
							),
							'advanced' => array(
								'name' => esc_html__('Advanced', 'brain-divi-blog'),
							),
						),
					),
				),
			),

			'advanced' => array(
				'toggles' => array(
					'grid_style' => esc_html__('Grid Style', 'brain-divi-blog'),
					'image'      => esc_html__('Thumbnail Style', 'brain-divi-blog'),
					'content'    => esc_html__('Content Box', 'brain-divi-blog'),
					'title'      => esc_html__('Post Title', 'brain-divi-blog'),
					'excerpt'    => esc_html__('Post Excerpt', 'brain-divi-blog'),
					'category'   => array(
						'title'             => esc_html__('Category', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'general' => array(
								'name' => esc_html__('General', 'brain-divi-blog'),
							),
							'text'    => array(
								'name' => esc_html__('Text', 'brain-divi-blog'),
							),
						),
					),
					'author'     => array(
						'title'             => esc_html__('Author', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'general' => array(
								'name' => esc_html__('General', 'brain-divi-blog'),
							),
							'text'    => array(
								'name' => esc_html__('Text', 'brain-divi-blog'),
							),
						),
					),
					'date'       => array(
						'title'             => esc_html__('Date', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'general' => array(
								'name' => esc_html__('General', 'brain-divi-blog'),
							),
							'text'    => array(
								'name' => esc_html__('Text', 'brain-divi-blog'),
							),
						),
					),
					'button'     => esc_html__('Readmore', 'brain-divi-blog'),
					'nav'        => array(
						'title'             => esc_html__('Navigation', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'nav_common' => array(
								'name' => esc_html__('Common', 'brain-divi-blog'),
							),
							'nav_left'   => array(
								'name' => esc_html__('Left', 'brain-divi-blog'),
							),
							'nav_right'  => array(
								'name' => esc_html__('Right', 'brain-divi-blog'),
							),
						),
					),
					'pagi'       => array(
						'title'             => esc_html__('Pagination', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'pagi_common' => array(
								'name' => esc_html__('Common', 'brain-divi-blog'),
							),
							'pagi_active' => array(
								'name' => esc_html__('Active', 'brain-divi-blog'),
							),
						),
					),
				),
			),
		);

		$this->custom_css_fields = array(
			'title'          => array(
				'label'    => esc_html__('Title', 'brain-divi-blog'),
				'selector' => '%%order_class%% .brbl-post-title a',
			),
			'content'        => array(
				'label'    => esc_html__('Body', 'brain-divi-blog'),
				'selector' => '%%order_class%% .brbl-post-excerpt',
			),
			'author'         => array(
				'label'    => esc_html__('Author', 'brain-divi-blog'),
				'selector' => '%%order_class%% .brbl-post-author',
			),
			'pagenavi'       => array(
				'label'    => esc_html__('Pagenavi', 'brain-divi-blog'),
				'selector' => '%%order_class%% .brbl-pagination',
			),
			'featured_image' => array(
				'label'    => esc_html__('Featured Image', 'brain-divi-blog'),
				'selector' => '%%order_class%% .brbl-post-thumb img',
			),
			'read_more'      => array(
				'label'    => esc_html__('Read More Button', 'brain-divi-blog'),
				'selector' => '%%order_class%% a.brbl-post-btn',
			),
		);
	}

	public function get_fields() {

		$query = array(
			'post_type'          => array(
				'label'            => esc_html__('Post Type', 'brain-divi-blog'),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => et_get_registered_post_type_options(false, false),
				'description'      => esc_html__('Choose posts of which post type you would like to display.', 'brain-divi-blog'),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'query',
				'default'          => 'post',
			),
			'include_categories' => array(
				'label'            => esc_html__('Included Categories', 'brain-divi-blog'),
				'type'             => 'categories',
				'meta_categories'  => array(
					'current' => esc_html__('Current Category', 'brain-divi-blog'),
				),
				'renderer_options' => array(
					'use_terms' => false,
				),
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'query',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'post_type' => 'post',
				),
			),
			'order_by'           => array(
				'label'            => esc_html__('Order By', 'brain-divi-blog'),
				'description'      => esc_html__('Choose how your posts should be ordered.', 'brain-divi-blog'),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'query',
				'default'          => 'date',
				'options'          => array(
					'author' => esc_html__('Author', 'brain-divi-blog'),
					'date'   => esc_html__('Date', 'brain-divi-blog'),
					'ID'     => esc_html__('ID', 'brain-divi-blog'),
					'parent' => esc_html__('Parent', 'brain-divi-blog'),
					'rand'   => esc_html__('Random', 'brain-divi-blog'),
					'title'  => esc_html__('Title', 'brain-divi-blog'),
				),

				'default_on_front' => 'date',
				'computed_affects' => array('__posts'),
			),
			'order'              => array(
				'label'            => esc_html__('Sorted By', 'brain-divi-blog'),
				'description'      => esc_html__('Choose how your posts should be sorted.', 'brain-divi-blog'),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'query',
				'default'          => 'ASC',
				'options'          => array(
					'ASC'  => esc_html__('Ascending', 'brain-divi-blog'),
					'DESC' => esc_html__('Descending', 'brain-divi-blog'),
				),

				'default_on_front' => 'ASC',
				'computed_affects' => array('__posts'),
			),
			'post_count'         => array(
				'label'            => esc_html__('Post Limit', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => '6',
				'toggle_slug'      => 'query',
				'computed_affects' => array('__posts'),
			),
			'date_format'        => array(
				'label'            => esc_html__('Date Format', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => 'M d, Y',
				'toggle_slug'      => 'query',
				'show_if'          => array(
					'show_date' => 'on',
				),
				'computed_affects' => array('__posts'),
			),
			'offset_number'      => array(
				'label'            => esc_html__('Post Offset Number', 'brain-divi-blog'),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'description'      => esc_html__('Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'brain-divi-blog'),
				'toggle_slug'      => 'query',
				'computed_affects' => array(
					'__posts',
				),
				'default'          => 0,
			),
			'include_posts'      => array(
				'label'            => esc_html__('Include posts by IDs', 'brain-divi-blog'),
				'description'      => esc_html__('eg. 10, 22, 19 etc. If this is used by IDs, Only selected posts will be included.', 'brain-divi-addons-pro'),
				'type'             => 'text',
				'toggle_slug'      => 'query',
				'computed_affects' => array('__posts'),
			),
			'exclude_posts'      => array(
				'label'            => esc_html__('Exclude posts by IDs', 'brain-divi-blog'),
				'description'      => esc_html__('eg. 10, 22, 19 etc. If this is used by IDs, Selected Posts will be ignored.', 'brain-divi-addons-pro'),
				'type'             => 'text',
				'toggle_slug'      => 'query',
				'computed_affects' => array('__posts'),
			),
		);

		$layout = array(
			'layout'              => array(
				'label'            => esc_html__('Template Layout', 'brain-divi-blog'),
				'type'             => 'select',
				'toggle_slug'      => 'layout',
				'default'          => 'layout1',
				'computed_affects' => array('__posts'),
				'options'          => array(
					'layout1' => esc_html__('Layout 1', 'brain-divi-blog'),
					'layout2' => esc_html__('Layout 2', 'brain-divi-blog'),
					'layout3' => esc_html__('Layout 3', 'brain-divi-blog'),
					'layout4' => esc_html__('Layout 4', 'brain-divi-blog'),
				),
			),
			'show_avatar'         => array(
				'label'           => esc_html__('Show Avatar', 'brain-divi-blog'),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'         => 'on',
				'toggle_slug'     => 'layout',
				'show_if'         => array(
					'layout' => 'layout4',
				),
			),
			'show_thumb'          => array(
				'label'            => esc_html__('Show Featured Image', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'on',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if_not'      => array(
					'layout' => 'layout3',
				),
			),
			'thumb_size'          => array(
				'label'            => esc_html__('Featured Image Size', 'brain-divi-blog'),
				'description'      => esc_html__('Different featured image size. If you use custom, the most appropriate image size will be displayed.', 'brain-divi-blog'),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'layout',
				'default'          => 'full',
				'options'          => array(
					'thumbnail' => esc_html__('Thumbnail (150px x 150px)', 'brain-divi-blog'),
					'medium'    => esc_html__('Medium (300px x 300px)', 'brain-divi-blog'),
					'large'     => esc_html__('Large (1024px x 1024px)', 'brain-divi-blog'),
					'full'      => esc_html__('Full (Original Image)', 'brain-divi-blog'),
					'custom'    => esc_html__('Custom', 'brain-divi-blog'),
				),
				'default_on_front' => 'full',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'show_thumb' => 'on',
				),
			),
			'thumb_width'         => array(
				'label'            => esc_html__('Featured Image Width', 'brain-divi-blog'),
				'type'             => 'range',
				'default'          => '100',
				'unitless'         => true,
				'range_settings'   => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 400,
				),
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'show_thumb' => 'on',
					'thumb_size' => 'custom',
				),
			),
			'thumb_height'        => array(
				'label'            => esc_html__('Featured Image Height', 'brain-divi-blog'),
				'type'             => 'range',
				'default'          => '100',
				'unitless'         => true,
				'range_settings'   => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 400,
				),
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'show_thumb' => 'on',
					'thumb_size' => 'custom',
				),
			),
			'show_title'          => array(
				'label'            => esc_html__('Show Title', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'on',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
			),
			'show_excerpt'        => array(
				'label'            => esc_html__('Show Excerpt', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'on',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
			),
			'content_length'      => array(
				'label'            => esc_html__('Excerpt Length', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => '100',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'show_excerpt' => 'on',
				),
			),
			'show_categories'     => array(
				'label'            => esc_html__('Show Categories', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'off',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
			),
			'show_first_category' => array(
				'label'            => esc_html__('Show Single Category', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'off',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'show_categories' => 'on',
				),
			),
			'show_author'         => array(
				'label'            => esc_html__('Show Author', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'on',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
			),
			'show_date'           => array(
				'label'            => esc_html__('Show Date', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'on',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
			),
			'show_btn'            => array(
				'label'            => esc_html__('Show Readmore', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'off',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if_not'      => array(
					'layout' => 'layout4',
				),
			),
			'button_text'         => array(
				'label'            => __('Readmore Text', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => 'Read More',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'layout'   => array('layout1', 'layout2', 'layout3'),
					'show_btn' => 'on',
				),
			),
		);

		$post_options = array(
			'post_padding'           => array(
				'label'          => __('Post Padding', 'brain-divi-blog'),
				'type'           => 'custom_padding',
				'default'        => '0px|0px|0px|0px',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'grid_style',
				'mobile_options' => true,
			),
			// Content.
			'content_alignment'      => array(
				'label'        => __('Alignment', 'brain-divi-blog'),
				'type'         => 'text_align',
				'options'      => et_builder_get_text_orientation_options(array('justified')),
				'options_icon' => 'module_align',
				'toggle_slug'  => 'content',
				'tab_slug'     => 'advanced',
			),
			'content_padding'        => array(
				'label'          => esc_html__('Padding', 'brain-divi-blog'),
				'type'           => 'custom_padding',
				'toggle_slug'    => 'content',
				'default'        => '30px|30px|30px|30px',
				'tab_slug'       => 'advanced',
				'mobile_options' => true,
			),
			'content_width'          => array(
				'label'          => esc_html__('Width', 'brain-divi-blog'),
				'type'           => 'range',
				'allowed_units'  => array('px', '%'),
				'default_unit'   => '%',
				'range_settings' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'content',
				'tab_slug'       => 'advanced',
				'mobile_options' => true,
				'show_if'        => array(
					'layout' => array('layout3', 'layout2'),
				),
			),
			'content_placement_x'    => array(
				'label'       => esc_html__('Horizontal Placement', 'brain-divi-blog'),
				'type'        => 'select',
				'toggle_slug' => 'content',
				'tab_slug'    => 'advanced',
				'default'     => 'center',
				'options'     => array(
					'left'   => esc_html__('Left', 'brain-divi-blog'),
					'center' => esc_html__('Center', 'brain-divi-blog'),
					'right'  => esc_html__('Right', 'brain-divi-blog'),
				),
				'show_if'     => array(
					'layout' => 'layout3',
				),
			),
			'content_placement_y'    => array(
				'label'       => esc_html__('Vertical Placement', 'brain-divi-blog'),
				'type'        => 'select',
				'toggle_slug' => 'content',
				'tab_slug'    => 'advanced',
				'default'     => 'center',
				'options'     => array(
					'top'    => esc_html__('Top', 'brain-divi-blog'),
					'center' => esc_html__('Center', 'brain-divi-blog'),
					'bottom' => esc_html__('Bottom', 'brain-divi-blog'),
				),
				'show_if'     => array(
					'layout' => 'layout3',
				),
			),
			'content_offset_y'       => array(
				'label'          => esc_html__('Offset Y', 'brain-divi-blog'),
				'type'           => 'range',
				'allowed_units'  => array('px', 'em', 'rem'),
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'min'  => -200,
					'step' => 1,
					'max'  => 200,
				),
				'toggle_slug'    => 'content',
				'tab_slug'       => 'advanced',
				'show_if'        => array(
					'layout' => array('layout1', 'layout2'),
				),
			),
			'content_offset_x'       => array(
				'label'          => esc_html__('Offset X', 'brain-divi-blog'),
				'type'           => 'range',
				'fixed_unit'     => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'min'  => -500,
					'step' => 1,
					'max'  => 500,
				),
				'toggle_slug'    => 'content',
				'tab_slug'       => 'advanced',
				'show_if'        => array(
					'layout' => 'layout1',
				),
			),
			'content_placement'      => array(
				'label'        => __('Placement', 'brain-divi-blog'),
				'type'         => 'text_align',
				'options'      => et_builder_get_text_orientation_options(array('justified')),
				'options_icon' => 'module_align',
				'default'      => 'center',
				'toggle_slug'  => 'content',
				'tab_slug'     => 'advanced',
				'show_if'      => array(
					'layout' => 'layout2',
				),
			),
			// Image.
			'image_width'            => array(
				'label'          => esc_html__('Image Width', 'brain-divi-blog'),
				'type'           => 'range',
				'fixed_unit'     => '%',
				'range_settings' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'image',
				'tab_slug'       => 'advanced',
				'mobile_options' => true,
			),
			'image_height'           => array(
				'label'          => esc_html__('Image Height', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => 'auto',
				'allowed_units'  => array('px'),
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 1000,
				),
				'toggle_slug'    => 'image',
				'tab_slug'       => 'advanced',
				'mobile_options' => true,
			),
			'image_size'             => array(
				'label'       => esc_html__('Image Size', 'brain-divi-blog'),
				'type'        => 'select',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'image',
				'default'     => 'cover',
				'options'     => array(
					'actual'  => esc_html__('Actual Size', 'brain-divi-blog'),
					'contain' => esc_html__('Contain', 'brain-divi-blog'),
					'cover'   => esc_html__('Fit', 'brain-divi-blog'),
				),
			),
			'img_hover_style'        => array(
				'label'       => esc_html__('Hover Animation', 'brain-divi-blog'),
				'type'        => 'select',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'image',
				'default'     => 'none',
				'options'     => array(
					'none'     => esc_html__('None', 'brain-divi-blog'),
					'zoon_in'  => esc_html__('Zoom In', 'brain-divi-blog'),
					'zoon_out' => esc_html__('Zoom Out', 'brain-divi-blog'),
					'fade'     => esc_html__('Fade', 'brain-divi-blog'),
				),
			),
			'desktop_only_img_hover' => array(
				'label'       => esc_html__('Disable Hover Animation on Tablet and Phone', 'brain-divi-blog'),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'     => 'off',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'image',
				'show_if_not' => array(
					'img_hover_style' => 'none',
				),
			),
			'img_masking_shape'      => array(
				'label'            => esc_html__('Masking Shape', 'brain-divi-blog'),
				'type'             => 'select',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'image',
				'default'          => 'none',
				'computed_affects' => array('__posts'),
				'options'          => array(
					'none'    => esc_html__('None', 'brain-divi-blog'),
					'shape_1' => esc_html__('Shape 1', 'brain-divi-blog'),
					'shape_2' => esc_html__('Shape 2', 'brain-divi-blog'),
					'shape_3' => esc_html__('Shape 3', 'brain-divi-blog'),
				),
				'show_if'          => array(
					'layout' => 'layout2',
				),
			),
			'masking_shape_color'    => array(
				'label'       => esc_html__('Masking Shape Color', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'image',
				'default'     => '#ffffff',
				'show_if'     => array(
					'layout' => 'layout2',
				),
			),
			// Date.
			'date_spacing'           => array(
				'label'          => esc_html__('Date Icon Spacing', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '5px',
				'allowed_units'  => array('px'),
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date',
				'sub_toggle'     => 'general',
				'show_if'        => array(
					'layout' => array('layout3', 'layout2', 'layout4'),
				),
			),
			'date_icon_size'         => array(
				'label'          => esc_html__('Date Icon Size', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '14px',
				'allowed_units'  => array('px'),
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date',
				'sub_toggle'     => 'general',
				'show_if'        => array(
					'layout' => array('layout3', 'layout2', 'layout4'),
				),
			),
			'date_icon_color'        => array(
				'label'       => esc_html__('Date Icon Color', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'date',
				'sub_toggle'  => 'general',
				'default'     => '#555',
				'show_if'     => array(
					'layout' => array('layout3', 'layout2', 'layout4'),
				),
			),
			'date_spacing_top'       => array(
				'label'          => esc_html__('Date Spacing Top', 'brain-divi-blog'),
				'type'           => 'range',
				'mobile_options' => true,
				'default'        => '15px',
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'date',
				'tab_slug'       => 'advanced',
				'sub_toggle'     => 'general',
				'show_if'        => array(
					'layout' => 'layout1',
				),
			),
			// Title.
			'title_spacing_top'      => array(
				'label'          => esc_html__('Title Spacing Top', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '15px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'title',
				'tab_slug'       => 'advanced',
			),
			'title_spacing_bottom'   => array(
				'label'          => esc_html__('Title Spacing Bottom', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '15px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'title',
				'tab_slug'       => 'advanced',
			),
			'excerpt_spacing_top'    => array(
				'label'          => esc_html__('Excerpt Spacing Top', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '15px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'excerpt',
				'tab_slug'       => 'advanced',
			),
			'excerpt_spacing_bottom' => array(
				'label'          => esc_html__('Excerpt Spacing Bottom', 'brain-divi-blog'),
				'type'           => 'range',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'excerpt',
				'tab_slug'       => 'advanced',
			),
			// Button.
			'btn_spacing_top'        => array(
				'label'          => esc_html__('Readmore Spacing Top', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '25px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'button',
				'tab_slug'       => 'advanced',
			),
			// Author.
			'author_img_size'        => array(
				'label'          => esc_html__('Author Photo Size', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '26px',
				'allowed_units'  => array('px'),
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'author',
				'tab_slug'       => 'advanced',
				'sub_toggle'     => 'general',
				'show_if'        => array(
					'layout' => 'layout1',
				),
			),
			'author_spacing'         => array(
				'label'          => esc_html__('Author Photo/Icon Spacing', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '5px',
				'allowed_units'  => array('px'),
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'author',
				'tab_slug'       => 'advanced',
				'sub_toggle'     => 'general',
			),
			'author_img_radius'      => array(
				'label'          => esc_html__('Author Photo Radius', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '30px',
				'allowed_units'  => array('px', '%'),
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'author',
				'tab_slug'       => 'advanced',
				'sub_toggle'     => 'general',
				'show_if'        => array(
					'layout' => 'layout1',
				),
			),
			'author_icon_size'       => array(
				'label'           => esc_html__('Author Icon Size', 'brain-divi-blog'),
				'type'            => 'range',
				'default'         => '14px',
				'option_category' => 'basic_option',
				'allowed_units'   => array('px'),
				'default_unit'    => 'px',
				'range_settings'  => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'     => 'author',
				'tab_slug'        => 'advanced',
				'sub_toggle'      => 'general',
				'show_if_not'     => array(
					'layout' => 'layout1',
				),
			),
			'author_icon_color'      => array(
				'label'       => esc_html__('Author Icon Color', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'author',
				'sub_toggle'  => 'general',
				'default'     => '#555',
				'show_if_not' => array(
					'layout' => 'layout1',
				),
			),
			// Category.
			'category_placement'     => array(
				'label'       => esc_html__('Category Placement', 'brain-divi-blog'),
				'type'        => 'select',
				'tab_slug'    => 'advanced',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'category',
				'sub_toggle'  => 'general',
				'default'     => 'top_left',
				'options'     => array(
					'top_left'     => esc_html__('Top - Left', 'brain-divi-blog'),
					'top_right'    => esc_html__('Top - Right', 'brain-divi-blog'),
					'bottom_left'  => esc_html__('Bottom - Left', 'brain-divi-blog'),
					'bottom_right' => esc_html__('Bottom - Right', 'brain-divi-blog'),
				),
				'show_if_not' => array(
					'layout' => 'layout3',
				),
			),
			'category_offset'        => array(
				'label'          => esc_html__('Category Offset', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '15px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'category',
				'sub_toggle'     => 'general',
				'show_if_not'    => array(
					'layout' => 'layout3',
				),
			),
			'category_bg'            => array(
				'label'       => esc_html__('Category Background', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'category',
				'sub_toggle'  => 'general',
				'default'     => '#FF2851',
			),
			'category_padding'       => array(
				'label'          => esc_html__('Category Padding', 'brain-divi-blog'),
				'type'           => 'custom_padding',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'category',
				'sub_toggle'     => 'general',
				'mobile_options' => true,
				'default'        => '3px|7px|3px|7px',
			),
			'category_radius'        => array(
				'label'       => esc_html__('Category Border Radius', 'brain-divi-blog'),
				'type'        => 'border-radius',
				'tab_slug'    => 'advanced',
				'sub_toggle'  => 'general',
				'toggle_slug' => 'category',
				'default'     => 'off|3px|3px|3px|3px',
			),
			// Computed fields.
			'__posts'                => array(
				'type'                => 'computed',
				'computed_callback'   => array('BRBL_PostCarousel', 'get_posts'),
				'computed_depends_on' => array(
					'layout',
					'include_categories',
					'order_by',
					'button_icon',
					'order',
					'post_count',
					'content_length',
					'date_format',
					'offset_number',
					'exclude_posts',
					'include_posts',
					'show_thumb',
					'thumb_size',
					'thumb_width',
					'thumb_height',
					'show_title',
					'show_excerpt',
					'show_btn',
					'button_text',
					'show_author',
					'show_date',
					'show_categories',
					'show_first_category',
					'title_level',
					'overlay_icon',
					'post_type',
					'img_masking_shape',
				),
			),
		);

		$post_bg    = $this->brbl_custom_background_fields('post', 'Post', 'advanced', 'grid_style', array('color', 'gradient', 'image'), array(), '');
		$content_bg = $this->brbl_custom_background_fields('content', 'Content', 'advanced', 'content', array('color', 'gradient', 'image'), array(), '#ffffff');

		$carousel_supports = array('equal_height');
		$carousel_options  = $this->get_carousel_option_fields($carousel_supports, array('slide_count' => '2'), array());
		$overlay           = $this->brbl_get_overlay_option_fields('image', array());

		return array_merge($query, $layout, $carousel_options, $post_options, $overlay, $post_bg, $content_bg);
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];
		$advanced_fields['fonts']       = [];

		$advanced_fields['box_shadow']['post'] = array(
			'label'       => esc_html__('Post Box Shadow', 'brain-divi-blog'),
			'css'         => array(
				'main'      => '%%order_class%% .brbl-post-card',
				'important' => 'all',
			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'grid_style',
		);

		$advanced_fields['borders']['post'] = array(
			'label_prefix' => esc_html__('Post', 'brain-divi-blog'),
			'toggle_slug'  => 'grid_style',
			'css'          => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-post-card',
					'border_styles' => '%%order_class%% .brbl-post-card',
				),
				'important' => 'all',
			),
			'defaults'     => array(
				'border_radii'  => 'on|0px|0px|0px|0px',
				'border_styles' => array(
					'width' => '0px',
					'color' => '#333',
					'style' => 'solid',
				),
			),
		);

		$advanced_fields['borders']['image'] = array(
			'label_prefix' => esc_html__('Image', 'brain-divi-blog'),
			'toggle_slug'  => 'image',
			'css'          => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-post-thumb',
					'border_styles' => '%%order_class%% .brbl-post-thumb',
				),
				'important' => 'all',
			),
			'defaults'     => array(
				'border_radii'  => 'on|0px|0px|0px|0px',
				'border_styles' => array(
					'width' => '0px',
					'color' => '#333',
					'style' => 'solid',
				),
			),
		);

		$advanced_fields['borders']['content'] = array(
			'label_prefix' => esc_html__('Content', 'brain-divi-blog'),
			'toggle_slug'  => 'content',
			'css'          => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-blog-content',
					'border_styles' => '%%order_class%% .brbl-blog-content',
				),
				'important' => 'all',
			),
			'defaults'     => array(
				'border_radii'  => 'on|0px|0px|0px|0px',
				'border_styles' => array(
					'width' => '1px',
					'color' => '#efefef',
					'style' => 'solid',
				),
			),
		);

		$advanced_fields['box_shadow']['content'] = array(
			'label'       => esc_html__('Content Box Shadow', 'brain-divi-blog'),
			'css'         => array(
				'main'      => '%%order_class%% .brbl-blog-content',
				'important' => 'all',

			),
			'toggle_slug' => 'content',
		);

		$advanced_fields['fonts']['title'] = array(
			'label'           => esc_html__('Title', 'brain-divi-blog'),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-title a',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'header_level'    => array(
				'default'          => 'h4',
				'computed_affects' => array(
					'__posts',
				),
			),
			'toggle_slug'     => 'title',
		);

		$advanced_fields['fonts']['excerpt'] = array(
			'label'           => esc_html__('Excerpt', 'brain-divi-blog'),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-excerpt',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'excerpt',
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['author'] = array(
			'label'           => esc_html__('Author', 'brain-divi-blog'),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-author a',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'toggle_slug'     => 'author',
			'tab_slug'        => 'advanced',
			'sub_toggle'      => 'text',
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['category'] = array(
			'label'           => esc_html__('Category', 'brain-divi-blog'),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-categories a, %%order_class%% .brbl-post-categories',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'category',
			'sub_toggle'      => 'text',
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
			'font_size'       => array(
				'default' => '14px',
			),
		);

		$advanced_fields['fonts']['date'] = array(
			'label'           => esc_html__('Date', 'brain-divi-blog'),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-date',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'toggle_slug'     => 'date',
			'tab_slug'        => 'advanced',
			'sub_toggle'      => 'text',
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['button']['button'] = array(
			'label'          => esc_html__('Readmore', 'brain-divi-blog'),
			'css'            => array(
				'main'      => '%%order_class%% .brbl-post-btn',
				'important' => true,
			),
			'use_alignment'  => false,
			'box_shadow'     => array(
				'css' => array(
					'main' => '%%order_class%% .brbl-post-btn',
				),
			),
			'borders'        => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
		);

		return $advanced_fields;
	}

	public static function get_posts($args = array(), $conditional_tags = array(), $current_page = array()) {

		$defaults = array(
			'layout'              => '',
			'include_categories'  => '',
			'order_by'            => '',
			'order'               => '',
			'post_count'          => '',
			'content_length'      => '',
			'date_format'         => '',
			'offset_number'       => '',
			'exclude_posts'       => '',
			'include_posts'       => '',
			'show_thumb'          => '',
			'thumb_size'          => '',
			'thumb_width'         => '',
			'thumb_height'        => '',
			'show_title'          => '',
			'show_excerpt'        => '',
			'show_btn'            => '',
			'button_text'         => '',
			'show_author'         => '',
			'show_date'           => '',
			'show_categories'     => '',
			'show_first_category' => '',
			'button_icon'         => '',
			'overlay_icon'        => '',
			'title_level'         => '',
			'is_lazyload'         => '',
			'post_type'           => '',
			'img_masking_shape'   => '',
		);

		$args                  = wp_parse_args($args, $defaults);
		$layout                = $args['layout'];
		$include_categories    = $args['include_categories'];
		$order_by              = $args['order_by'];
		$order                 = $args['order'];
		$post_count            = $args['post_count'];
		$content_length        = $args['content_length'];
		$date_format           = $args['date_format'];
		$offset_number         = $args['offset_number'];
		$exclude_posts         = $args['exclude_posts'];
		$include_posts         = $args['include_posts'];
		$button_text           = $args['button_text'];
		$button_icon           = $args['button_icon'];
		$title_level           = $args['title_level'];
		$show_thumb            = $args['show_thumb'];
		$thumb_size            = $args['thumb_size'];
		$thumb_width           = $args['thumb_width'];
		$thumb_height          = $args['thumb_height'];
		$show_title            = $args['show_title'];
		$show_excerpt          = $args['show_excerpt'];
		$show_categories       = $args['show_categories'];
		$show_first_category   = $args['show_first_category'];
		$show_author           = $args['show_author'];
		$show_date             = $args['show_date'];
		$show_btn              = $args['show_btn'];
		$selected_icon         = esc_attr(et_pb_process_font_icon($button_icon));
		$button_icon           = !empty($selected_icon) ? $selected_icon : '5';
		$img_masking_shape     = $args['img_masking_shape'];
		$processed_title_level = et_pb_process_header_level($title_level, 'h4');
		$processed_title_level = esc_html($processed_title_level);
		$title_tag             = et_core_esc_previously($processed_title_level);
		$layout                = $args['layout'];
		$post_type             = $args['post_type'];
		$overlay_icon          = esc_attr(et_pb_process_font_icon($args['overlay_icon']));

		$query_args = array(
			'posts_per_page' => intval($post_count),
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'orderby'        => $order_by,
			'order'          => $order,
			'offset'         => intval($offset_number),
		);

		if (!empty($exclude_posts)) {
			$exclude_posts              = str_replace(' ', '', $exclude_posts);
			$exclude_posts              = explode(',', $exclude_posts);
			$query_args['post__not_in'] = $exclude_posts;
		}

		if (!empty($include_posts)) {
			$include_posts          = str_replace(' ', '', $include_posts);
			$include_posts          = explode(',', $include_posts);
			$query_args['post__in'] = $include_posts;
		}

		if ('post' === $post_type) {
			$post_id           = isset($current_page['id']) ? (int) $current_page['id'] : 0;
			$query_args['cat'] = implode(',', self::filter_include_categories($include_categories, $post_id));
		}

		$query = new WP_Query($query_args);

		$blog_order = self::_get_index(array(self::INDEX_MODULE_ORDER, 'brbl_post_carousel'));
		$item_class = sprintf('brbl-blog-item-%1$s-%2$s', $layout, $blog_order);

		ob_start();

		if ($query->have_posts()) :
			while ($query->have_posts()) :
				$query->the_post();
				if ('layout1' === $layout) {
					include BRAIN_BLOG_DIR . 'includes/templates/post-card-1.php';
				} elseif ('layout2' === $layout) {
					include BRAIN_BLOG_DIR . 'includes/templates/post-card-2.php';
				} elseif ('layout3' === $layout) {
					include BRAIN_BLOG_DIR . 'includes/templates/post-card-3.php';
				} elseif ('layout4' === $layout) {
					include BRAIN_BLOG_DIR . 'includes/templates/post-card-4.php';
				}
			endwhile;
		endif;

		wp_reset_postdata();

		$output = ob_get_clean();

		if (!$output) {
			$output = self::get_no_results_template(et_core_esc_previously($processed_title_level));
		}

		return $output;
	}

	public function render($attrs, $content, $render_slug) {

		$is_center        = $this->props['is_center'];
		$center_mode_type = $this->props['center_mode_type'];
		$is_equal_height  = $this->props['is_equal_height'];
		$sliding_dir      = $this->props['sliding_dir'];

		wp_enqueue_script('brbl-slick');
		wp_enqueue_style('brbl-slick');
		$this->render_css($render_slug);
		$carousel_classes = array();

		if ('on' === $is_center) {
			array_push($carousel_classes, 'brbl-centered');
			array_push($carousel_classes, "brbl-centered--{$center_mode_type}");
		}

		array_push($carousel_classes, "equal-height-{$is_equal_height}");

		$output = sprintf(
			'<div class="brbl-carousel brbl-blog-carousel brbl-carousel-frontend %3$s" %2$s dir="%4$s">
                %1$s
            </div>',
			self::get_posts($this->props),
			$this->get_carousel_options_data(),
			join(' ', $carousel_classes),
			$sliding_dir
		);

		return $output;
	}

	public function render_css($render_slug) {
		$this->render_carousel_css($render_slug);
		$this->render_post_css($render_slug);
	}

	public function render_post_css($render_slug) {

		$content_alignment      = $this->props['content_alignment'];
		$layout                 = $this->props['layout'];
		$content_placement_x    = $this->props['content_placement_x'];
		$content_placement_y    = $this->props['content_placement_y'];
		$author_img_size        = $this->props['author_img_size'];
		$author_spacing         = $this->props['author_spacing'];
		$author_img_radius      = $this->props['author_img_radius'];
		$author_icon_color      = $this->props['author_icon_color'];
		$author_icon_size       = $this->props['author_icon_size'];
		$content_placement      = $this->props['content_placement'];
		$image_size             = $this->props['image_size'];
		$image_height           = $this->props['image_height'];
		$date_spacing           = $this->props['date_spacing'];
		$date_icon_size         = $this->props['date_icon_size'];
		$date_icon_color        = $this->props['date_icon_color'];
		$category_offset        = $this->props['category_offset'];
		$category_bg            = $this->props['category_bg'];
		$category_placement     = explode('_', $this->props['category_placement']);
		$category_radius        = explode('|', $this->props['category_radius']);
		$img_hover_style        = $this->props['img_hover_style'];
		$desktop_only_img_hover = $this->props['desktop_only_img_hover'];
		$masking_shape_color    = $this->props['masking_shape_color'];
		$show_avatar            = $this->props['show_avatar'];
		$sliding_dir            = $this->props['sliding_dir'];
		$right                  = 'ltr' === $sliding_dir ? 'right' : 'left';

		// Avatar.
		if ('layout4' === $layout) {
			$img_offset = '280px';

			if ('auto' !== $image_height) {
				$img_offset = $image_height;
			}
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-avatar',
					'declaration' => sprintf('top: %1$s;', $img_offset),
				)
			);

			if ('off' === $show_avatar) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-author-avatar',
						'declaration' => 'display: none;',
					)
				);
			}
		}

		// Category.
		$this->brbl_get_responsive_styles(
			'category_padding',
			'%%order_class%% .brbl-post-categories',
			array(
				'primary'   => 'padding',
				'important' => false,
			),
			array(),
			$render_slug
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-post-categories',
				'declaration' => sprintf(
					'background: %1$s;
					border-radius: %2$s %3$s %4$s %5$s;',
					$category_bg,
					$category_radius[1],
					$category_radius[2],
					$category_radius[3],
					$category_radius[4]
				),
			)
		);

		if ('layout3' !== $layout) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-categories',
					'declaration' => sprintf(
						'%1$s: %2$s;
						position: absolute;
						%3$s: %2$s;',
						$category_placement[0],
						$category_offset,
						$category_placement[1]
					),
				)
			);
		}

		// Image.
		if ('zoon_in' === $img_hover_style) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
					'declaration' => 'transform: scale(1.2);',
				)
			);
		} elseif ('zoon_out' === $img_hover_style) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
					'declaration' => 'transform: scale(1);',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-thumb img',
					'declaration' => 'transform: scale(1.2);',
				)
			);
		} elseif ('fade' === $img_hover_style) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
					'declaration' => 'opacity: .7;',
				)
			);
		}

		if ('on' === $desktop_only_img_hover) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
					'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
					'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
					'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
					'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
				)
			);
		}

		if ('actual' !== $image_size) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-thumb img',
					'declaration' => 'height: 100%;object-fit:' . $image_size . ';',
				)
			);
		}

		if ('auto' !== $image_height) {
			$this->brbl_get_responsive_styles(
				'image_height',
				'%%order_class%% .brbl-post-thumb',
				array(
					'primary'   => 'height',
					'important' => false,
				),
				array('default' => 'auto'),
				$render_slug
			);
		} else {
			if ('layout3' === $layout) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-post-card-3 .brbl-post-thumb',
						'declaration' => 'height: 400px!important;',
					)
				);
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-post-thumb img',
						'declaration' => 'height: 100%;',
					)
				);
			}

			if ('layout4' === $layout) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-post-thumb',
						'declaration' => 'height: 280px;',
					)
				);
			}
		}

		$this->brbl_get_responsive_styles(
			'image_width',
			'%%order_class%% .brbl-post-thumb',
			array(
				'primary'   => 'max-width',
				'important' => false,
			),
			array(
				'default'     => 'initial',
				'conditional' => array(
					'name'   => 'layout',
					'values' => array(
						array(
							'a' => 'layout1',
							'b' => '40%',
						),
					),
				),
			),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'image_width',
			'%%order_class%% .brbl-post-thumb',
			array(
				'primary'   => 'flex',
				'important' => false,
			),
			array(
				'default'     => 'initial',
				'conditional' => array(
					'name'   => 'layout',
					'values' => array(
						array(
							'a' => 'layout1',
							'b' => '40%',
						),
					),
				),
			),
			$render_slug
		);

		if ('layout2' === $layout) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-thumb svg',
					'declaration' => sprintf(
						'position: absolute;
						bottom: -1px;
						width: 100%%;
						left: 0;
						fill: %1$s;',
						$masking_shape_color
					),
				)
			);
		}

		$this->brbl_get_responsive_styles(
			'post_padding',
			'%%order_class%% .brbl-post-card',
			array(
				'primary'   => 'padding',
				'important' => false,
			),
			array('default' => '0px|0px|0px|0px'),
			$render_slug
		);

		// Content.
		if (!empty($content_alignment)) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-content',
					'declaration' => "text-align:{$content_alignment};",
				)
			);
		}

		if ('right' === $content_alignment) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-meta',
					'declaration' => 'justify-content:flex-end;',
				)
			);

			if ('layout3' === $layout) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' => 'align-items:flex-end;',
					)
				);
			}

			if ('layout1' === $layout) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-post-author',
						'declaration' => 'margin-left: auto;',
					)
				);
			}
		} elseif ('center' === $content_alignment) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-meta',
					'declaration' => 'justify-content:center;',
				)
			);
			if ('layout1' === $layout) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-post-author',
						'declaration' => 'margin-left: auto; margin-right: auto;',
					)
				);
			}
			if ('layout3' === $layout) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' => 'align-items:center;',
					)
				);
			}
		} elseif (empty($content_alignment) || 'left' === $content_alignment) {
			if ('layout3' === $layout) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' => 'align-items:flex-start;',
					)
				);
			}
		}

		if ('layout1' === $layout || 'layout2' === $layout) {
			$this->brbl_get_responsive_styles(
				'content_offset_y',
				'%%order_class%% .brbl-blog-content',
				array(
					'primary'   => 'margin-top',
					'important' => false,
				),
				array(
					'default'     => '0px',
					'conditional' => array(
						'name'   => 'layout',
						'values' => array(
							array(
								'a' => 'layout1',
								'b' => '0px',
							),
							array(
								'a' => 'layout2',
								'b' => '-60px',
							),
						),
					),
				),
				$render_slug
			);
		}

		if ('layout3' === $layout) {
			if ('center' !== $content_placement_x) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' => "{$content_placement_x}:0;",
					)
				);
			}

			if ('center' !== $content_placement_y) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' => "{$content_placement_y}:0;",
					)
				);
			}

			if ('center' === $content_placement_y && 'center' === $content_placement_x) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' =>
						'top:50%; left: 50%;
							transform: translateY(-50%) translateX(-50%);',
					)
				);
			} else {
				if ('center' === $content_placement_y) {
					ET_Builder_Element::set_style(
						$render_slug,
						array(
							'selector'    => '%%order_class%% .brbl-blog-content',
							'declaration' =>
							'top:50%; transform: translateY(-50%);',
						)
					);
				}
				if ('center' === $content_placement_x) {
					ET_Builder_Element::set_style(
						$render_slug,
						array(
							'selector'    => '%%order_class%% .brbl-blog-content',
							'declaration' =>
							'left:50%; transform: translateX(-50%);',
						)
					);
				}
			}
		}

		$this->brbl_get_responsive_styles(
			'content_padding',
			'%%order_class%% .brbl-blog-content',
			array(
				'primary'   => 'padding',
				'important' => false,
			),
			array('default' => '30px|30px|30px|30px'),
			$render_slug
		);

		if ('layout1' === $layout) {
			$this->brbl_get_responsive_styles(
				'content_offset_x',
				'%%order_class%% .brbl-blog-content',
				array(
					'primary'   => 'margin-left',
					'important' => false,
				),
				array(
					'default'     => '0px',
					'conditional' => array(
						'name'   => 'layout',
						'values' => array(
							array(
								'a' => 'layout1',
								'b' => '0px',
							),
						),
					),
				),
				$render_slug
			);
		} elseif ('layout2' === $layout) {

			$this->brbl_get_responsive_styles(
				'content_width',
				'%%order_class%% .brbl-blog-content',
				array(
					'primary'   => 'width',
					'important' => false,
				),
				array(
					'default'     => '100%',
					'conditional' => array(
						'name'   => 'layout',
						'values' => array(
							array(
								'a' => 'layout2',
								'b' => '90%',
							),
						),
					),
				),
				$render_slug
			);

			if ('center' === $content_placement) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' => 'margin-left: auto; margin-right: auto;',
					)
				);
			} elseif ('right' === $content_placement) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-blog-content',
						'declaration' => 'margin-right: auto;',
					)
				);
			}
		} elseif ('layout3' === $layout) {
			$this->brbl_get_responsive_styles(
				'content_width',
				'%%order_class%% .brbl-blog-content',
				array(
					'primary'   => 'width',
					'important' => false,
				),
				array(
					'default'     => '100%',
					'conditional' => array(
						'name'   => 'layout',
						'values' => array(
							array(
								'a' => 'layout2',
								'b' => '90%',
							),
						),
					),
				),
				$render_slug
			);
		}

		// Author.
		if ('layout1' === $layout) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-author img',
					'declaration' => sprintf(
						'width: %1$s;
						height: %1$s;
						margin-%4$s: %2$s;
						border-radius: %3$s;',
						$author_img_size,
						$author_spacing,
						$author_img_radius,
						$right
					),
				)
			);
		} else {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-author svg',
					'declaration' => sprintf(
						'margin-%4$s: %1$s;
                        width: %2$s;
                        fill: %3$s;',
						$author_spacing,
						$author_icon_size,
						$author_icon_color,
						$right
					),
				)
			);
		}

		// Date.
		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-post-date svg',
				'declaration' => sprintf(
					'margin-%4$s: %1$s;
                    width: %2$s;
                    fill: %3$s;',
					$date_spacing,
					$date_icon_size,
					$date_icon_color,
					$right
				),
			)
		);

		if ('layout1' === $layout) {
			$this->brbl_get_responsive_styles(
				'date_spacing_top',
				'%%order_class%% .brbl-post-date',
				array(
					'primary'   => 'padding-top',
					'important' => false,
				),
				array('default' => '15px'),
				$render_slug
			);
		}

		// Texts.
		$this->brbl_get_responsive_styles(
			'title_spacing_top',
			'%%order_class%% .brbl-post-title',
			array(
				'primary'   => 'padding-top',
				'important' => true,
			),
			array('default' => '15px'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'title_spacing_bottom',
			'%%order_class%% .brbl-post-title',
			array(
				'primary'   => 'padding-bottom',
				'important' => true,
			),
			array('default' => '15px'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'excerpt_spacing_top',
			'%%order_class%% .brbl-post-excerpt',
			array(
				'primary'   => 'padding-top',
				'important' => true,
			),
			array('default' => '15px'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'excerpt_spacing_bottom',
			'%%order_class%% .brbl-post-excerpt',
			array(
				'primary'   => 'padding-bottom',
				'important' => true,
			),
			array(
				'default'     => '0px',
				'conditional' => array(
					'name'   => 'layout',
					'values' => array(
						array(
							'a' => 'layout3',
							'b' => '15px',
						),
						array(
							'a' => 'layout4',
							'b' => '15px',
						),
					),
				),
			),
			$render_slug
		);

		// Button.
		$this->brbl_get_responsive_styles(
			'btn_spacing_top',
			'%%order_class%% .brbl-post-btn-wrap',
			array(
				'primary'   => 'padding-top',
				'important' => false,
			),
			array('default' => '25px'),
			$render_slug
		);

		// Button Styles.
		$this->brbl_get_button_styles('button', $render_slug, '%%order_class%% .brbl-post-btn');
		// Content background.
		$this->brbl_get_custom_bg_style($render_slug, 'content', '%%order_class%% .brbl-blog-content', '%%order_class%% .brbl-blog-content:hover');
		// Post background.
		$this->brbl_get_custom_bg_style($render_slug, 'post', '%%order_class%% .brbl-post-card', '%%order_class%% .brbl-post-card:hover');
		// Overlay Styles.
		$this->brbl_get_overlay_style($render_slug, 'image');
	}
}

new BRBL_PostCarousel();
