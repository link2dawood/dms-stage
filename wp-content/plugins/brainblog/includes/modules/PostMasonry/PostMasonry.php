<?php
class BRBL_PostMasonry extends BRBL_Builder_Module_Type_PostBased {

	protected $module_credits = array(
		'module_uri' => 'https://divipeople.com/plugins/brain-blog',
		'author'     => 'DiviPeople',
		'author_uri' => 'https://divipeople.com',
	);

	protected static $rendering = false;

	public function init() {

		$this->vb_support = 'on';
		$this->slug       = 'brbl_post_masonry';
		$this->name       = esc_html__('DP Post Masonry', 'brain-divi-blog');
		$this->icon_path  = plugin_dir_path(__FILE__) . 'post-masonry.svg';

		$this->settings_modal_toggles = array(

			'general'  => array(
				'toggles' => array(
					'query'  => esc_html__('Query', 'brain-divi-blog'),
					'layout' => esc_html__('Layout Settings', 'brain-divi-blog'),
				),
			),

			'advanced' => array(
				'toggles' => array(
					'grid_style' => esc_html__('Post Grid Style', 'brain-divi-blog'),
					'image'      => esc_html__('Thumbnail Style', 'brain-divi-blog'),
					'content'    => esc_html__('Content Box', 'brain-divi-blog'),
					'title'      => esc_html__('Post Title', 'brain-divi-blog'),
					'excerpt'    => esc_html__('Post Excerpt', 'brain-divi-blog'),
					'category'   => array(
						'title'             => esc_html__('Category', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'common' => array(
								'name' => esc_html__('Common', 'brain-divi-blog'),
							),
							'text'   => array(
								'name' => esc_html__('Text', 'brain-divi-blog'),
							),
						),
					),
					'meta'       => array(
						'title'             => esc_html__('Meta', 'brain-divi-blog'),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'common' => array(
								'name' => esc_html__('Common', 'brain-divi-blog'),
							),
							'author' => array(
								'name' => esc_html__('Author', 'brain-divi-blog'),
							),
							'date'   => array(
								'name' => esc_html__('Date', 'brain-divi-blog'),
							),
						),
					),
					'pagination' => esc_html__('Pagination', 'brain-divi-blog'),
					'button'     => esc_html__('Readmore', 'brain-divi-blog'),
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
				'options'          => brbl_get_posttype(),
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
				'default'          => '10',
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
				'default'          => 'masonry-1',
				'computed_affects' => array('__posts'),
				'options'          => array(
					'masonry-1' => esc_html__('Layout 1', 'brain-divi-blog'),
					'masonry-2' => esc_html__('Layout 2', 'brain-divi-blog'),
				),
			),

			'column_count'        => array(
				'label'          => __('Numbers of Column', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '3',
				'unitless'       => true,
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 10,
				),
				'toggle_slug'    => 'layout',
			),
			'column_gap_x'        => array(
				'label'          => __('Column Gap Left/Right', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '20px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 0,
					'max'  => 100,
				),
				'toggle_slug'    => 'layout',
			),
			'column_gap_y'        => array(
				'label'          => __('Column Gap Top/Bottom', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '20px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 0,
					'max'  => 100,
				),
				'toggle_slug'    => 'layout',
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
			),

			'thumb_size'          => array(
				'label'            => esc_html__('Featured Image Size', 'brain-divi-blog'),
				'description'      => esc_html__('Different featured image size.', 'brain-divi-blog'),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'layout',
				'default'          => 'full',
				'options'          => array(
					'thumbnail' => esc_html__('Thumbnail (150px x 150px)', 'brain-divi-blog'),
					'medium'    => esc_html__('Medium (300px x 300px)', 'brain-divi-blog'),
					'large'     => esc_html__('Large (1024px x 1024px)', 'brain-divi-blog'),
					'full'      => esc_html__('Full (Original Image)', 'brain-divi-blog'),
				),
				'default_on_front' => 'full',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'show_thumb' => 'on',
				),
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
			'excerpt_length'      => array(
				'label'            => esc_html__('Excerpt Length', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => '120',
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
			'show_meta'           => array(
				'label'            => esc_html__('Show Meta', 'brain-divi-blog'),
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
				'show_if'          => array(
					'show_meta' => 'on',
				),
			),

			'show_meta_icons'     => array(
				'label'            => esc_html__('Show Meta Icons', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'default'          => 'on',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
					'show_meta' => 'on',
				),
			),

			'use_author_photo'    => array(
				'label'            => esc_html__('Use Author Photo', 'brain-divi-blog'),
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
					'show_meta_icons' => 'on',
					'show_meta'       => 'on',
				),
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
				'show_if'          => array(
					'show_meta' => 'on',
				),
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
			),

			'button_text'         => array(
				'label'            => __('Readmore Text', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => 'Read More',
				'toggle_slug'      => 'layout',
				'computed_affects' => array('__posts'),
				'show_if'          => array(
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

			'content_alignment'      => array(
				'label'        => __('Content Alignment', 'brain-divi-blog'),
				'type'         => 'text_align',
				'options'      => et_builder_get_text_orientation_options(array('justified')),
				'options_icon' => 'module_align',
				'default'      => 'left',
				'toggle_slug'  => 'content',
				'tab_slug'     => 'advanced',
			),

			'content_padding'        => array(
				'label'          => esc_html__('Content Padding', 'brain-divi-blog'),
				'type'           => 'custom_padding',
				'toggle_slug'    => 'content',
				'default'        => '30px|30px|30px|30px',
				'tab_slug'       => 'advanced',
				'mobile_options' => true,
			),

			'content_placement_x'    => array(
				'label'       => esc_html__('Content Vertical Placement', 'brain-divi-blog'),
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
					'layout' => 'masonry-2',
				),
			),

			'content_bg'             => array(
				'label'       => esc_html__('Content Background', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'content',
				'default'     => 'rgba(255,255,255,.8)',
				'show_if'     => array(
					'layout' => 'masonry-2',
				),
			),

			'img_hover_style'        => array(
				'label'       => esc_html__('Hover Animation', 'brain-divi-blog'),
				'type'        => 'select',
				'toggle_slug' => 'image',
				'tab_slug'    => 'advanced',
				'default'     => 'none',
				'options'     => array(
					'none'     => esc_html__('None', 'brain-divi-blog'),
					'zoom_in'  => esc_html__('Zoom In', 'brain-divi-blog'),
					'zoom_out' => esc_html__('Zoom Out', 'brain-divi-blog'),
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
					'shape_4' => esc_html__('Shape 4', 'brain-divi-blog'),
					'shape_5' => esc_html__('Shape 5', 'brain-divi-blog'),
					'shape_6' => esc_html__('Shape 6', 'brain-divi-blog'),
				),
			),

			'masking_shape_color'    => array(
				'label'       => esc_html__('Masking Shape Color', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'image',
				'default'     => '#ffffff',
			),

			'category_placement'     => array(
				'label'       => esc_html__('Category Position', 'brain-divi-blog'),
				'type'        => 'select',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'category',
				'sub_toggle'  => 'common',
				'default'     => 'right',
				'options'     => array(
					'left'  => esc_html__('Left', 'brain-divi-blog'),
					'right' => esc_html__('Right', 'brain-divi-blog'),
				),
			),

			'category_offset'        => array(
				'label'          => esc_html__('Category Offset', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '20px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'category',
				'sub_toggle'     => 'common',
			),

			'category_bg'            => array(
				'label'       => esc_html__('Category Background', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'category',
				'sub_toggle'  => 'common',
				'default'     => '#FF2851',
			),

			'date_spacing'           => array(
				'label'           => esc_html__('Date Icon Spacing', 'brain-divi-blog'),
				'type'            => 'range',
				'default'         => '5px',
				'option_category' => 'basic_option',
				'allowed_units'   => array('px'),
				'default_unit'    => 'px',
				'range_settings'  => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'meta',
				'sub_toggle'      => 'date',
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
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'date',
			),

			'date_icon_color'        => array(
				'label'       => esc_html__('Date Icon Color', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'meta',
				'sub_toggle'  => 'date',
				'default'     => '#555',
			),

			'title_spacing_top'      => array(
				'label'          => esc_html__('Title Spacing Top', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '0px',
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

			// Button.
			'btn_spacing_top'        => array(
				'label'          => esc_html__('Readmore Spacing Top', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '15px',
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

			'meta_spacing_bottom'    => array(
				'label'          => esc_html__('Meta Spacing Bottom', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '10px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'common',
			),

			'meta_item_spacing'      => array(
				'label'          => esc_html__('Meta Item Spacing', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '25px',
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'common',
			),

			// Excerpt.
			'excerpt_spacing_top'    => array(
				'label'          => esc_html__('Excerpt Spacing Top', 'brain-divi-blog'),
				'type'           => 'range',
				'default'        => '10px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'excerpt',
			),

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
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'author',
				'show_if'        => array(
					'use_author_photo' => 'on',
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
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'author',
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
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'author',
				'show_if'        => array(
					'use_author_photo' => 'on',
				),
			),

			'author_icon_size'       => array(
				'label'          => esc_html__('Author Icon Size', 'brain-divi-blog'),
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
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'author',
				'show_if'        => array(
					'use_author_photo' => 'off',
				),
			),

			'author_icon_color'      => array(
				'label'       => esc_html__('Author Icon Color', 'brain-divi-blog'),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'meta',
				'sub_toggle'  => 'author',
				'default'     => '#555',
				'show_if'     => array(
					'use_author_photo' => 'off',
				),
			),

			// Computed Fields.
			'__posts'                => array(
				'type'                => 'computed',
				'computed_callback'   => array('BRBL_PostMasonry', 'get_posts'),
				'computed_depends_on' => array(
					'layout',
					'offset_number',
					'include_posts',
					'exclude_posts',
					'include_categories',
					'order_by',
					'order',
					'button_icon',
					'post_count',
					'excerpt_length',
					'date_format',
					'show_thumb',
					'thumb_size',
					'show_excerpt',
					'pagination_type',
					'pagination_showall',
					'pagination_prev_label',
					'pagination_next_label',
					'loadmore_type',
					'loadmore_text',
					'show_btn',
					'button_text',
					'show_author',
					'show_meta',
					'show_date',
					'show_categories',
					'show_first_category',
					'img_masking_shape',
					'ico',
					'title_level',
					'use_author_photo',
					'overlay_icon',
					'post_type',
				),
			),
		);

		$pagination = array(

			'pagination_gap'             => array(
				'label'          => __('Container Padding', 'brain-divi-blog'),
				'type'           => 'custom_padding',
				'default'        => '20px|0px|20px|0px',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
				'mobile_options' => true,
			),
			'pagination_type'            => array(
				'label'            => esc_html__('Pagination', 'brain-divi-blog'),
				'type'             => 'select',
				'default'          => '',
				'options'          => array(
					''          => esc_html__('None', 'brain-divi-blog'),
					'numbers'   => esc_html__('Numbers', 'brain-divi-blog'),
					'prev_next' => esc_html__('Previous/Next', 'brain-divi-blog'),
					'loadmore'  => esc_html__('Loadmore', 'brain-divi-blog'),
				),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'layout',
			),
			'pagination_showall'         => array(
				'label'            => esc_html__('Show All', 'brain-divi-blog'),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__('Yes', 'brain-divi-blog'),
					'off' => esc_html__('No', 'brain-divi-blog'),
				),
				'computed_affects' => array(
					'__posts',
				),
				'default'          => 'off',
				'show_if'          => array(
					'pagination_type' => 'numbers',
				),
				'toggle_slug'      => 'layout',
			),
			'pagination_prev_label'      => array(
				'label'            => esc_html__('Previous Label', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => esc_html__('&laquo; Previous', 'brain-divi-blog'),
				'computed_affects' => array(
					'__posts',
				),
				'show_if'          => array(
					'pagination_type' => 'prev_next',
				),
				'toggle_slug'      => 'layout',
			),
			'pagination_next_label'      => array(
				'label'            => esc_html__('Next Label', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => esc_html__('Next &raquo;', 'brain-divi-blog'),
				'show_if'          => array(
					'pagination_type' => 'prev_next',
				),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'layout',
			),
			'loadmore_type'              => array(
				'label'            => esc_html__('Loadmore Type', 'brain-divi-blog'),
				'type'             => 'select',
				'default'          => 'scroll',
				'options'          => array(
					'scroll' => esc_html__('Scroll', 'brain-divi-blog'),
					'button' => esc_html__('Button', 'brain-divi-blog'),
				),
				'show_if'          => array(
					'pagination_type' => 'loadmore',
				),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'layout',
			),
			'loadmore_text'              => array(
				'label'            => esc_html__('Load More Text', 'brain-divi-blog'),
				'type'             => 'text',
				'default'          => esc_html__('Load More', 'brain-divi-blog'),
				'show_if'          => array(
					'pagination_type' => 'loadmore',
					'loadmore_type'   => 'button',
				),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'layout',
			),
			'pagination_alignment'       => array(
				'label'          => __('Alignment', 'brain-divi-blog'),
				'type'           => 'text_align',
				'options'        => et_builder_get_text_orientation_options(array('justified')),
				'options_icon'   => 'module_align',
				'default'        => 'center',
				'mobile_options' => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
			),
			'pagination_padding'         => array(
				'label'          => __('Padding', 'brain-divi-blog'),
				'type'           => 'custom_padding',
				'default'        => '6px|15px|6px|15px',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
				'mobile_options' => true,
			),
			'pagination_color'           => array(
				'label'          => esc_html__('Color', 'brain-divi-blog'),
				'type'           => 'color-alpha',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
				'mobile_options' => true,
			),
			'pagination_bg_color'        => array(
				'label'          => esc_html__('Background', 'brain-divi-blog'),
				'type'           => 'color-alpha',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
				'mobile_options' => true,
			),
			'pagination_active_color'    => array(
				'label'          => esc_html__('Active Color', 'brain-divi-blog'),
				'type'           => 'color-alpha',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
				'show_if'        => array(
					'pagination_type' => 'numbers',
				),
				'mobile_options' => true,
			),
			'pagination_active_bg_color' => array(
				'label'          => esc_html__('Active Background', 'brain-divi-blog'),
				'type'           => 'color-alpha',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
				'show_if'        => array(
					'pagination_type' => 'numbers',
				),
				'mobile_options' => true,
			),
			'loading_dot_color'          => array(
				'label'          => esc_html__('Loading Dot', 'brain-divi-blog'),
				'type'           => 'color-alpha',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'pagination',
				'mobile_options' => true,
			),

		);

		$post_bg = $this->brbl_custom_background_fields('post', 'Post', 'advanced', 'grid_style', array('color', 'gradient', 'image'), array(), '');
		$overlay = $this->brbl_get_overlay_option_fields('image', array());

		return array_merge($query, $layout, $post_options, $pagination, $overlay, $post_bg);
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];
		$advanced_fields['fonts']       = [];

		$advanced_fields['box_shadow']['post'] = array(
			'label'       => esc_html__('Post Box Shadow', 'brain-divi-blog'),
			'css'         => array(
				'main'      => '%%order_class%% .brbl-masonry-card',
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
					'border_radii'  => '%%order_class%% .brbl-masonry-card',
					'border_styles' => '%%order_class%% .brbl-masonry-card',
				),
				'important' => 'all',
			),
			'defaults'     => array(
				'border_radii'  => 'on|0px|0px|0px|0px',
				'border_styles' => array(
					'width' => '0px',
					'color' => '#efefef',
					'style' => 'solid',
				),
			),
		);

		$advanced_fields['button']['button'] = array(
			'label'          => esc_html__('Readmore', 'brain-divi-blog'),
			'css'            => array(
				'main'      => '%%order_class%% .brbl-post-btn-wrap .brbl-post-btn',
				'important' => true,
			),
			'use_alignment'  => false,
			'box_shadow'     => array(
				'css' => array(
					'main' => '%%order_class%% .brbl-post-btn-wrap .brbl-post-btn',
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

		$advanced_fields['fonts']['title'] = array(
			'label'        => esc_html__('Title', 'brain-divi-blog'),
			'css'          => array(
				'main'         => '%%order_class%% .brbl-post-title',
				'font'         => '%%order_class%% .brbl-post-title a, %%order_class%% .brbl-post-title',
				'color'        => '%%order_class%% .brbl-post-title a, %%order_class%% .brbl-post-title',
				'limited_main' => '%%order_class%% .brbl-post-title, %%order_class%% .brbl-post-title a',
				'important'    => 'all',
			),
			'tab_slug'     => 'advanced',
			'toggle_slug'  => 'title',
			'header_level' => array(
				'default' => 'h3',
			),
		);

		$advanced_fields['fonts']['excerpt'] = array(
			'label'       => esc_html__('Excerpt', 'brain-divi-blog'),
			'css'         => array(
				'main'      => '%%order_class%% .brbl-post-excerpt',
				'important' => 'all',
			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'excerpt',
		);

		$advanced_fields['fonts']['author'] = array(
			'label'           => esc_html__('Author', 'brain-divi-blog'),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-masonry-author a',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'meta',
			'sub_toggle'      => 'author',
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
				'main'      => '%%order_class%% .brbl-masonry-categories, %%order_class%% .brbl-masonry-categories a',
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
		);

		$advanced_fields['fonts']['date'] = array(
			'label'           => esc_html__('Date', 'brain-divi-blog'),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-masonry-date',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'meta',
			'sub_toggle'      => 'date',
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['pagination'] = array(
			'css'             => array(
				'main'  => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .brbl-load-more-button, %%order_class%% .brbl-pagination .page-numbers.current',
				'hover' => '%%order_class%% .brbl-pagination a:hover, %%order_class%% .brbl-pagination .brbl-load-more-button:hover, %%order_class%% .brbl-pagination .page-numbers.current:hover',
			),
			'text_align'      => array(
				'options' => et_builder_get_text_orientation_options(array('justified'), array()),
			),
			'hide_text_color' => true,
			'toggle_slug'     => 'pagination',
			'tab_slug'        => 'advanced',
		);

		$advanced_fields['borders']['pagination'] = array(
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
					'border_styles' => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
				),
				'important' => 'all',
			),
			'defaults'    => array(
				'border_radii'  => 'on|0px|0px|0px|0px',
				'border_styles' => array(
					'width' => '0px',
					'color' => '#333',
					'style' => 'solid',
				),
			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'pagination',
		);

		$advanced_fields['box_shadow']['pagination'] = array(
			'css'         => array(
				'main'      => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
				'important' => 'all',

			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'pagination',
		);

		return $advanced_fields;
	}

	public static function get_posts($args = array(), $conditional_tags = array(), $current_page = array()) {

		global $paged, $wp_query;

		if (self::$rendering) {
			return '';
		}

		$defaults = array(
			'layout'                => '',
			'exclude_posts'         => '',
			'include_posts'         => '',
			'offset_number'         => '',
			'include_categories'    => '',
			'order_by'              => '',
			'order'                 => '',
			'post_count'            => '',
			'excerpt_length'        => '',
			'date_format'           => '',
			'show_thumb'            => '',
			'thumb_size'            => '',
			'show_excerpt'          => '',
			'show_btn'              => '',
			'button_text'           => '',
			'show_author'           => '',
			'show_meta'             => '',
			'pagination_type'       => '',
			'pagination_showall'    => '',
			'pagination_prev_label' => '',
			'pagination_next_label' => '',
			'loadmore_type'         => '',
			'loadmore_text'         => '',
			'show_date'             => '',
			'show_categories'       => '',
			'show_first_category'   => '',
			'img_masking_shape'     => '',
			'button_icon'           => '',
			'use_author_photo'      => '',
			'overlay_icon'          => '',
			'title_level'           => '',
			'post_type'             => '',
		);

		$args = wp_parse_args($args, $defaults);

		$pagination_type       = $args['pagination_type'];
		$pagination_prev_label = $args['pagination_prev_label'];
		$pagination_next_label = $args['pagination_next_label'];
		$loadmore_type         = $args['loadmore_type'];
		$loadmore_text         = $args['loadmore_text'];
		$pagination_showall    = $args['pagination_showall'];
		$include_categories    = $args['include_categories'];
		$order_by              = $args['order_by'];
		$offset_number         = $args['offset_number'];
		$include_posts         = $args['include_posts'];
		$exclude_posts         = $args['exclude_posts'];
		$order                 = $args['order'];
		$post_count            = $args['post_count'];
		$excerpt_length        = $args['excerpt_length'];
		$date_format           = $args['date_format'];
		$button_text           = $args['button_text'];
		$button_icon           = $args['button_icon'];
		$show_thumb            = $args['show_thumb'];
		$thumb_size            = $args['thumb_size'];
		$show_excerpt          = $args['show_excerpt'];
		$show_categories       = $args['show_categories'];
		$show_first_category   = $args['show_first_category'];
		$img_masking_shape     = $args['img_masking_shape'];
		$show_author           = $args['show_author'];
		$show_meta             = $args['show_meta'];
		$show_date             = $args['show_date'];
		$show_btn              = $args['show_btn'];
		$use_author_photo      = $args['use_author_photo'];
		$selected_icon         = esc_attr(et_pb_process_font_icon($button_icon));
		$button_icon           = !empty($selected_icon) ? $selected_icon : '5';
		$title_level           = $args['title_level'];
		$processed_title_level = et_pb_process_header_level($title_level, 'h4');
		$processed_title_level = esc_html($processed_title_level);
		$title_tag             = et_core_esc_previously($processed_title_level);
		$layout                = $args['layout'];
		$post_type             = $args['post_type'];
		$overlay_icon          = $args['overlay_icon'];

		$is_front_page               = et_fb_conditional_tag('is_front_page', $conditional_tags);
		$is_single                   = et_fb_conditional_tag('is_single', $conditional_tags);
		$et_is_builder_plugin_active = et_fb_conditional_tag('et_is_builder_plugin_active', $conditional_tags);
		$post_id                     = isset($current_page['id']) ? (int) $current_page['id'] : 0;

		$query_args = array(
			'posts_per_page' => intval($post_count),
			'post_type'      => $post_type,
			'post_status'    => array('publish', 'private'),
			'perm'           => 'readable',
			'orderby'        => $order_by,
			'order'          => $order,
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

		$paged = $is_front_page ? get_query_var('page') : get_query_var('paged'); // phpcs:ignore

		$query_args['paged'] = $paged;

		if ('' !== $args['offset_number'] && !empty($args['offset_number'])) {
			if ($paged > 1) {
				$query_args['offset'] = (($paged - 1) * intval($args['post_count'])) + intval($args['offset_number']);
			} else {
				$query_args['offset'] = intval($args['offset_number']);
			}
		}

		if ('post' === $post_type) {
			$post_id           = isset($current_page['id']) ? (int) $current_page['id'] : 0;
			$query_args['cat'] = implode(',', self::filter_include_categories($include_categories, $post_id));
		}

		$query = new WP_Query($query_args);

		$query = apply_filters('et_builder_blog_query', $query);

		$wp_query_page = $wp_query; // phpcs:ignore

		$wp_query = $query; // phpcs:ignore

		$wp_query->et_pb_blog_query = true;

		self::$rendering = true;

		if ('' !== $args['offset_number'] && !empty($args['offset_number'])) {
			$wp_query->found_posts   = max(0, $wp_query->found_posts - intval($args['offset_number']));
			$wp_query->max_num_pages = ceil($wp_query->found_posts / intval($args['post_count']));
		}

		if ('' === $pagination_type) {
			$current_page = 1;
		} else {
			$current_page = max(1, get_query_var('paged'), get_query_var('page'));
		}

		$blog_order = self::_get_index(array(self::INDEX_MODULE_ORDER, 'brbl_post_masonry'));

		$item_class = sprintf('brbl-blog-item-%1$s-%2$s', $layout, $blog_order);

		$pagi_options['pagination_type']       = $pagination_type;
		$pagi_options['pagination_prev_label'] = $pagination_prev_label;
		$pagi_options['pagination_next_label'] = $pagination_next_label;
		$pagi_options['loadmore_type']         = $loadmore_type;
		$pagi_options['loadmore_text']         = $loadmore_text;
		$pagi_options['max_num_pages']         = $wp_query->max_num_pages;
		$pagi_options['current_page']          = $current_page;
		$pagi_options['pagination_showall']    = $pagination_showall;
		$pagi_options['blog_order']            = $blog_order;

		ob_start();

		if ($query->have_posts()) {

			echo '<div class="brbl-blog-items brbl-blog brbl-blog-' . et_core_esc_previously($loadmore_type) . '-' . et_core_esc_previously($blog_order) . '">';

			while ($query->have_posts()) {
				$query->the_post();
				include BRAIN_BLOG_DIR . 'includes/templates/masonry-content.php';
			}
			echo '</div>';
			self::pagination_render($pagi_options);
		}

		unset($wp_query->et_pb_blog_query);

		$wp_query = $wp_query_page; // phpcs:ignore

		wp_reset_postdata();

		$output = ob_get_contents();

		ob_get_clean();

		self::$rendering = false;

		if (!$output) {
			$output = self::get_no_results_template(et_core_esc_previously($processed_title_level));
		}

		return $output;
	}

	public static function pagination_render($options) {

		echo '<nav class="brbl-pagination" role="navigation" aria-label="Pagination">';

		if ('prev_next' === $options['pagination_type']) {

			next_posts_link($options['pagination_prev_label']);
			previous_posts_link($options['pagination_next_label']);
		} elseif ('numbers' === $options['pagination_type']) {

			$paginate_args = array(
				'current'   => $options['current_page'],
				'total'     => $options['max_num_pages'],
				'prev_next' => false,
				'show_all'  => 'on' === $options['pagination_showall'],
			);

			if (is_singular() && !is_front_page()) {
				global $wp_rewrite;

				if ($wp_rewrite->using_permalinks()) {
					$paginate_args['base']   = trailingslashit(get_permalink()) . '%_%';
					$paginate_args['format'] = user_trailingslashit('%#%', 'single_paged');
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links($paginate_args);

			echo et_core_esc_previously($links);
		} elseif ('loadmore' === $options['pagination_type']) {
			self::load_more_render($options);
		}
		echo '</nav>';
	}


	public static function load_more_render($options) {

		$blog_order = $options['blog_order'];

		$loader = sprintf(
			'<div class="brbl-page-load-status brbl-page-load-status-%1$s" style="display: none;">
				<div class="loader-ellips infinite-scroll-request">
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
				</div>
			</div>',
			et_core_esc_previously($blog_order)
		);

		if ('button' === $options['loadmore_type']) {
			echo et_core_intentionally_unescaped($loader, 'html');
			include BRAIN_BLOG_DIR . 'includes/templates/navigation.php';

			echo '<button class="brbl-load-more-button brbl-lmb-' . et_core_esc_previously($blog_order) . '">';
			echo et_core_esc_previously($options['loadmore_text']);
			echo '</button>';
		} elseif ('scroll' === $options['loadmore_type']) {
			echo et_core_intentionally_unescaped($loader, 'html');
			include BRAIN_BLOG_DIR . 'includes/templates/navigation.php';
		}
	}

	public function render($attrs, $content, $render_slug) {
		$this->apply_css($render_slug);
		wp_enqueue_script('brbl-imagesloaded');
		wp_enqueue_script('brbl-isotop');
		$layout          = $this->props['layout'];
		$pagination_type = $this->props['pagination_type'];
		$loadmore_type   = $this->props['loadmore_type'];

		$grid_classes = array();

		if ('loadmore' === $pagination_type) {
			wp_enqueue_script('brbl-infinite-scroll');
		}

		array_push($grid_classes, 'brbl-module brbl-post-masonry');
		$blog_order = self::_get_index(array(self::INDEX_MODULE_ORDER, $render_slug));

		$options = array();

		$options['layout']          = $layout;
		$options['blog_order']      = $blog_order;
		$options['pagination_type'] = $pagination_type;
		$options['loadmore_type']   = $loadmore_type;

		$options = sprintf(
			'data-options="%1$s"',
			htmlspecialchars(wp_json_encode($options), ENT_QUOTES, 'UTF-8')
		);

		$infinite_class = 'loadmore' === $pagination_type ? 'brbl-masonry-infinite' : '';

		$output = sprintf(
			'<div class="%2$s %4$s" %3$s>
                %1$s
			</div>',
			self::get_posts($this->props),
			join(' ', $grid_classes),
			$options,
			$infinite_class
		);

		return $output;
	}

	public function apply_css($render_slug) {

		$column_gap_x        = $this->props['column_gap_x'];
		$column_gap_x_tablet = !empty($this->props['column_gap_x_tablet']) ? $this->props['column_gap_x_tablet'] : $column_gap_x;
		$column_gap_x_phone  = !empty($this->props['column_gap_x_phone']) ? $this->props['column_gap_x_phone'] : $column_gap_x_tablet;

		$column_gap_y        = $this->props['column_gap_y'];
		$column_gap_y_tablet = !empty($this->props['column_gap_y_tablet']) ? $this->props['column_gap_y_tablet'] : $column_gap_y;
		$column_gap_y_phone  = !empty($this->props['column_gap_y_phone']) ? $this->props['column_gap_y_phone'] : $column_gap_y_tablet;

		$column_count        = $this->props['column_count'];
		$column_count_tablet = !empty($this->props['column_count_tablet']) ? $this->props['column_count_tablet'] : $column_count;
		$column_count_phone  = !empty($this->props['column_count_phone']) ? $this->props['column_count_phone'] : $column_count_tablet;

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-blog-item',
				'declaration' => sprintf(
					'width: calc(100%%/%1$s);
					max-width:calc(100%%/%1$s);
					padding:%2$s %3$s;',
					$column_count,
					$column_gap_y,
					$column_gap_x
				),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-blog',
				'declaration' => sprintf(
					'margin: -%1$s -%2$s;',
					$column_gap_y,
					$column_gap_x
				),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-blog-item',
				'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
				'declaration' => sprintf(
					'width: calc(100%%/%1$s);
					max-width:calc(100%%/%1$s);
					padding:%2$s %3$s;',
					$column_count_tablet,
					$column_gap_y_tablet,
					$column_gap_x_tablet
				),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-blog',
				'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
				'declaration' => sprintf('margin: -%1$s -%2$s;', $column_gap_y_tablet, $column_gap_x_tablet),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-blog-item',
				'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
				'declaration' => sprintf(
					'width:calc(100%%/%1$s);
					max-width:calc(100%%/%1$s);
					padding:%2$s %3$s;',
					$column_count_phone,
					$column_gap_y_phone,
					$column_gap_x_phone
				),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-blog',
				'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
				'declaration' => sprintf('margin: -%1$s -%2$s;', $column_gap_y_phone, $column_gap_x_phone),
			)
		);

		$content_alignment      = $this->props['content_alignment'];
		$layout                 = $this->props['layout'];
		$date_icon_color        = $this->props['date_icon_color'];
		$content_placement_x    = $this->props['content_placement_x'];
		$use_author_photo       = $this->props['use_author_photo'];
		$author_img_size        = $this->props['author_img_size'];
		$author_spacing         = $this->props['author_spacing'];
		$author_img_radius      = $this->props['author_img_radius'];
		$author_icon_color      = $this->props['author_icon_color'];
		$author_icon_size       = $this->props['author_icon_size'];
		$meta_item_spacing      = $this->props['meta_item_spacing'];
		$show_meta_icons        = $this->props['show_meta_icons'];
		$date_spacing           = $this->props['date_spacing'];
		$show_meta              = $this->props['show_meta'];
		$date_icon_size         = $this->props['date_icon_size'];
		$img_hover_style        = $this->props['img_hover_style'];
		$category_offset        = $this->props['category_offset'];
		$category_bg            = $this->props['category_bg'];
		$category_placement     = $this->props['category_placement'];
		$img_masking_shape      = $this->props['img_masking_shape'];
		$content_bg             = $this->props['content_bg'];
		$masking_shape_color    = $this->props['masking_shape_color'];
		$desktop_only_img_hover = $this->props['desktop_only_img_hover'];

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-masonry-categories',
				'declaration' => sprintf(
					'
                    %1$s: %2$s;
                    background: %3$s;
                    top: %4$s;',
					$category_placement,
					$category_offset,
					$category_bg,
					$category_offset
				),
			)
		);

		if ('none' !== $img_masking_shape) {
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

		if ('zoom_in' === $img_hover_style) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-masonry-card:hover .brbl-post-thumb img',
					'declaration' => 'transform: scale(1.2);',
				)
			);
		} elseif ('zoom_out' === $img_hover_style) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-masonry-card:hover .brbl-post-thumb img',
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
					'selector'    => '%%order_class%% .brbl-masonry-card:hover .brbl-post-thumb img',
					'declaration' => 'opacity: .6;',
				)
			);
		}

		if ('on' === $desktop_only_img_hover) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-masonry-card:hover .brbl-post-thumb img',
					'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
					'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-masonry-card:hover .brbl-post-thumb img',
					'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
					'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
				)
			);
		}

		$this->brbl_get_responsive_styles(
			'post_padding',
			'%%order_class%% .brbl-masonry-card',
			array(
				'primary'   => 'padding',
				'important' => false,
			),
			array('default' => '0px|0px|0px|0px'),
			$render_slug
		);

		// Content.
		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-masonry-content',
				'declaration' => "text-align:{$content_alignment};",
			)
		);

		if ('right' === $content_alignment) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-meta',
					'declaration' => 'justify-content:flex-end;',
				)
			);
		} elseif ('center' === $content_alignment) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-meta',
					'declaration' => 'justify-content:center;',
				)
			);
		}

		if ('masonry-2' === $layout) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-masonry-content',
					'declaration' => "background:{$content_bg};",
				)
			);
			if ('center' !== $content_placement_x) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-masonry-content',
						'declaration' => "{$content_placement_x}:0;",
					)
				);
			} else {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-masonry-content',
						'declaration' => 'top:50%;transform: translateY(-50%);',
					)
				);
			}
		}

		$this->brbl_get_responsive_styles(
			'content_padding',
			'%%order_class%% .brbl-masonry-content',
			array(
				'primary'   => 'padding',
				'important' => false,
			),
			array('default' => '30px|30px|30px|30px'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'title_spacing_top',
			'%%order_class%% .brbl-post-title',
			array(
				'primary'   => 'padding-top',
				'important' => false,
			),
			array('default' => '15px'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'btn_spacing_top',
			'%%order_class%% .brbl-post-btn-wrap',
			array(
				'primary'   => 'padding-top',
				'important' => false,
			),
			array('default' => '15px'),
			$render_slug
		);

		if ('on' === $use_author_photo) {

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-masonry-author img',
					'declaration' => sprintf(
						'
                        width: %1$s;
                        height: %1$s;
                        margin-right: %2$s;
                        border-radius: %3$s;
                    ',
						$author_img_size,
						$author_spacing,
						$author_img_radius
					),
				)
			);
		} else {

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-masonry-author svg',
					'declaration' => sprintf(
						'
                        width: %1$s;
                        margin-right: %2$s;
                        fill: %3$s;
                    ',
						$author_icon_size,
						$author_spacing,
						$author_icon_color
					),
				)
			);
		}

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-masonry-author',
				'declaration' => "margin-right: {$meta_item_spacing};",
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-masonry-date svg',
				'declaration' => sprintf(
					'
                    width: %1$s;
                    margin-right: %2$s;
                    fill: %3$s;
                ',
					$date_icon_size,
					$date_spacing,
					$date_icon_color
				),
			)
		);

		if ('on' === $show_meta) {
			$this->brbl_get_responsive_styles(
				'meta_spacing_bottom',
				'%%order_class%% .brbl-blog-meta',
				array(
					'primary'   => 'margin-bottom',
					'important' => true,
				),
				array('default' => '10px'),
				$render_slug
			);
		}

		$this->brbl_get_responsive_styles(
			'excerpt_spacing_top',
			'%%order_class%% .brbl-post-excerpt',
			array(
				'primary'   => 'margin-top',
				'important' => true,
			),
			array('default' => '15px'),
			$render_slug
		);

		if ('off' === $show_meta_icons) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-blog-meta svg, %%order_class%% .brbl-blog-meta img',
					'declaration' => 'display: none!important;',
				)
			);
		}

		$this->brbl_get_button_styles(
			'button',
			$render_slug,
			'%%order_class%% .brbl-post-btn'
		);

		$this->brbl_get_custom_bg_style(
			$render_slug,
			'post',
			'%%order_class%% .brbl-masonry-card',
			'%%order_class%% .brbl-masonry-card:hover'
		);

		$this->brbl_get_overlay_style($render_slug, 'image');

		$this->brbl_get_responsive_styles(
			'pagination_gap',
			'%%order_class%% .brbl-pagination',
			array(
				'primary'   => 'padding',
				'important' => false,
			),
			array('default' => '0px|0px|0px|0px'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'pagination_padding',
			'%%order_class%% .brbl-pagination a, %%order_class%% .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
			array(
				'primary'   => 'padding',
				'important' => false,
			),
			array('default' => '0px|0px|0px|0px'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'pagination_alignment',
			'%%order_class%% .brbl-pagination',
			array(
				'primary'   => 'text-align',
				'important' => false,
			),
			array('default' => 'center'),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'loading_dot_color',
			'%%order_class%% .brbl-pagination .loader-ellips__dot',
			array(
				'primary'   => 'background-color',
				'important' => true,
			),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'pagination_color',
			'%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers, %%order_class%% .brbl-pagination .brbl-load-more-button',
			array(
				'primary'   => 'color',
				'important' => true,
			),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'pagination_bg_color',
			'%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers, %%order_class%% .brbl-pagination .brbl-load-more-button',
			array(
				'primary'   => 'background-color',
				'important' => true,
			),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'pagination_active_color',
			'%%order_class%% .brbl-pagination .page-numbers.current',
			array(
				'primary'   => 'color',
				'important' => true,
			),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'pagination_active_bg_color',
			'%%order_class%% .brbl-pagination .page-numbers.current',
			array(
				'primary'   => 'background-color',
				'important' => true,
			),
			array(),
			$render_slug
		);
	}
}

new BRBL_PostMasonry();
