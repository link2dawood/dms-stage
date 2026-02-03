<?php
class BRBL_PostTiles extends BRBL_Builder_Module_Type_PostBased {

	protected $module_credits = array(
		'module_uri' => 'https://divipeople.com/plugins/brain-blog',
		'author'     => 'DiviPeople',
		'author_uri' => 'https://divipeople.com',
	);

	public function init() {

		$this->vb_support = 'on';
		$this->slug       = 'brbl_post_tiles';
		$this->name       = esc_html__( 'DP Post Tiles', 'brain-divi-blog' );
		$this->icon_path  = plugin_dir_path( __FILE__ ) . 'post-tiles.svg';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'query'          => esc_html__( 'Query', 'brain-divi-blog' ),
					'elements'       => array(
						'title'             => esc_html__( 'Elements', 'brain-divi-blog' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'common'   => array(
								'name' => esc_html__( 'Common', 'brain-divi-blog' ),
							),
							'general'  => array(
								'name' => esc_html__( 'General', 'brain-divi-blog' ),
							),
							'featured' => array(
								'name' => esc_html__( 'Featured', 'brain-divi-blog' ),
							),
						),
					),
					'tiles_settings' => esc_html__( 'Tiles Settings', 'brain-divi-blog' ),
				),
			),

			'advanced' => array(
				'toggles' => array(
					'tiles_style'    => esc_html__( 'Tiles Style', 'brain-divi-blog' ),
					'image'          => esc_html__( 'Thumbnail', 'brain-divi-blog' ),
					'category'       => array(
						'title'             => esc_html__( 'Category', 'brain-divi-blog' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'common' => array(
								'name' => esc_html__( 'General', 'brain-divi-blog' ),
							),
							'text'   => array(
								'name' => esc_html__( 'Text', 'brain-divi-blog' ),
							),
						),
					),

					'texts'          => array(
						'title'             => esc_html__( 'Title & Excerpt', 'brain-divi-blog' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'   => array(
								'name' => esc_html__( 'Title', 'brain-divi-blog' ),
							),
							'excerpt' => array(
								'name' => esc_html__( 'Excerpt', 'brain-divi-blog' ),
							),
						),
					),
					'featured_texts' => array(
						'title'             => esc_html__( 'Featured Texts', 'brain-divi-blog' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'   => array(
								'name' => esc_html__( 'Title', 'brain-divi-blog' ),
							),
							'excerpt' => array(
								'name' => esc_html__( 'Excerpt', 'brain-divi-blog' ),
							),
						),
					),
					'meta'           => array(
						'title'             => esc_html__( 'Meta', 'brain-divi-blog' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'common' => array(
								'name' => esc_html__( 'Common', 'brain-divi-blog' ),
							),
							'author' => array(
								'name' => esc_html__( 'Author', 'brain-divi-blog' ),
							),
							'date'   => array(
								'name' => esc_html__( 'Date', 'brain-divi-blog' ),
							),
						),
					),
					'border'         => esc_html__( 'Borders', 'brain-divi-blog' ),
				),
			),
		);
	}

	public function get_fields() {

		$query = array(
			'post_type'          => array(
				'label'            => esc_html__( 'Post Type', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => et_get_registered_post_type_options( false, false ),
				'description'      => esc_html__( 'Choose posts of which post type you would like to display.', 'brain-divi-blog' ),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'query',
				'default'          => 'post',
			),
			'include_categories' => array(
				'label'            => esc_html__( 'Included Categories', 'brain-divi-blog' ),
				'type'             => 'categories',
				'meta_categories'  => array(
					'current' => esc_html__( 'Current Category', 'brain-divi-blog' ),
				),
				'renderer_options' => array(
					'use_terms' => false,
				),
				'toggle_slug'      => 'query',
				'computed_affects' => array( '__posts' ),
				'show_if'          => array(
					'post_type' => 'post',
				),
			),
			'order_by'           => array(
				'label'            => esc_html__( 'Order By', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Choose how your posts should be ordered.', 'brain-divi-blog' ),
				'type'             => 'select',
				'toggle_slug'      => 'query',
				'default'          => 'date',
				'options'          => array(
					'author' => esc_html__( 'Author', 'brain-divi-blog' ),
					'date'   => esc_html__( 'Date', 'brain-divi-blog' ),
					'ID'     => esc_html__( 'ID', 'brain-divi-blog' ),
					'parent' => esc_html__( 'Parent', 'brain-divi-blog' ),
					'rand'   => esc_html__( 'Random', 'brain-divi-blog' ),
					'title'  => esc_html__( 'Title', 'brain-divi-blog' ),
				),

				'default_on_front' => 'date',
				'computed_affects' => array( '__posts' ),
			),
			'order'              => array(
				'label'            => esc_html__( 'Sorted By', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Choose how your posts should be sorted.', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'query',
				'default'          => 'ASC',
				'options'          => array(
					'ASC'  => esc_html__( 'Ascending', 'brain-divi-blog' ),
					'DESC' => esc_html__( 'Descending', 'brain-divi-blog' ),
				),

				'default_on_front' => 'ASC',
				'computed_affects' => array( '__posts' ),
			),
			'excerpt_length'     => array(
				'label'            => esc_html__( 'Excerpt Length', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => '120',
				'toggle_slug'      => 'query',
				'computed_affects' => array( '__posts' ),
			),
			'date_format'        => array(
				'label'            => esc_html__( 'Date Format', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => 'M d, Y',
				'toggle_slug'      => 'query',
				'show_if'          => array(
					'show_date' => 'on',
				),
				'computed_affects' => array( '__posts' ),
			),
			'offset_number'      => array(
				'label'            => esc_html__( 'Post Offset Number', 'brain-divi-blog' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'description'      => esc_html__( 'Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'brain-divi-blog' ),
				'toggle_slug'      => 'query',
				'computed_affects' => array(
					'__posts',
				),
				'default'          => 0,
			),
			'include_posts'      => array(
				'label'            => esc_html__( 'Include posts by IDs', 'brain-divi-blog' ),
				'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Only selected posts will be included.', 'brain-divi-addons-pro' ),
				'type'             => 'text',
				'toggle_slug'      => 'query',
				'computed_affects' => array( '__posts' ),
			),
			'exclude_posts'      => array(
				'label'            => esc_html__( 'Exclude posts by IDs', 'brain-divi-blog' ),
				'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Selected Posts will be ignored.', 'brain-divi-addons-pro' ),
				'type'             => 'text',
				'toggle_slug'      => 'query',
				'computed_affects' => array( '__posts' ),
			),
			'thumb_size'         => array(
				'label'            => esc_html__( 'Featured Image Size', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Different featured image size.', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'query',
				'default'          => 'full',
				'options'          => array(
					'thumbnail' => esc_html__( 'Thumbnail (150px x 150px)', 'brain-divi-blog' ),
					'medium'    => esc_html__( 'Medium (300px x 300px)', 'brain-divi-blog' ),
					'large'     => esc_html__( 'Large (1024px x 1024px)', 'brain-divi-blog' ),
					'full'      => esc_html__( 'Full (Original Image)', 'brain-divi-blog' ),
				),
				'default_on_front' => 'full',
				'computed_affects' => array( '__posts' ),
			),
		);

		$tiles_settings = array(
			'layout'      => array(
				'label'            => esc_html__( 'Tiles Layout', 'brain-divi-blog' ),
				'type'             => 'select',
				'toggle_slug'      => 'tiles_settings',
				'default'          => 'tile-1',
				'computed_affects' => array( '__posts' ),
				'options'          => array(
					'tile-1'  => esc_html__( 'Layout 1', 'brain-divi-blog' ),
					'tile-2'  => esc_html__( 'Layout 2', 'brain-divi-blog' ),
					'tile-3'  => esc_html__( 'Layout 3', 'brain-divi-blog' ),
					'tile-4'  => esc_html__( 'Layout 4', 'brain-divi-blog' ),
					'tile-5'  => esc_html__( 'Layout 5', 'brain-divi-blog' ),
					'tile-6'  => esc_html__( 'Layout 6', 'brain-divi-blog' ),
					'tile-7'  => esc_html__( 'Layout 7', 'brain-divi-blog' ),
					'tile-8'  => esc_html__( 'Layout 8', 'brain-divi-blog' ),
					'tile-9'  => esc_html__( 'Layout 9', 'brain-divi-blog' ),
					'tile-10' => esc_html__( 'Layout 10', 'brain-divi-blog' ),
					'tile-11' => esc_html__( 'Layout 11', 'brain-divi-blog' ),
					'tile-12' => esc_html__( 'Layout 12', 'brain-divi-blog' ),
				),
			),
			'tiles_gap'   => array(
				'label'          => __( 'Titles Gap', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '10px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 0,
					'max'  => 100,
				),
				'toggle_slug'    => 'tiles_settings',
			),
			'base_height' => array(
				'label'          => esc_html__( 'Base Height', 'brain-divi-blog' ),
				'type'           => 'range',
				'fixed_unit'     => 'px',
				'default_unit'   => 'px',
				'default'        => '250px',
				'range_settings' => array(
					'min'  => 100,
					'step' => 1,
					'max'  => 1000,
				),
				'toggle_slug'    => 'tiles_settings',
			),
		);

		$post_options = array(

			// elements.
			'show_excerpt'              => array(
				'label'            => esc_html__( 'Show Excerpt', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'off',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'general',
				'computed_affects' => array( '__posts' ),
			),
			'show_date'                 => array(
				'label'            => esc_html__( 'Show Date', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'common',
				'computed_affects' => array( '__posts' ),
			),
			'show_author'               => array(
				'label'            => esc_html__( 'Show Author', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'common',
				'computed_affects' => array( '__posts' ),
			),
			'show_meta_icons'           => array(
				'label'            => esc_html__( 'Show Meta Icons', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'common',
				'computed_affects' => array( '__posts' ),
			),
			'use_author_photo'          => array(
				'label'            => esc_html__( 'Show Author Photo', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'off',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'common',
				'computed_affects' => array( '__posts' ),
				'show_if'          => array(
					'show_meta_icons' => 'on',
					'show_author'     => 'on',
				),
			),
			'show_categories'           => array(
				'label'            => esc_html__( 'Show Categories', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'off',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'common',
				'computed_affects' => array( '__posts' ),
			),
			'show_first_category'       => array(
				'label'            => esc_html__( 'Show Single Category', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'off',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'common',
				'computed_affects' => array( '__posts' ),
				'show_if'          => array(
					'show_categories' => 'on',
				),
			),
			'excerpt_on_hover'          => array(
				'label'           => esc_html__( 'Show Excerpt on Hover', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'elements',
				'sub_toggle'      => 'general',
			),
			'ft_show_excerpt'           => array(
				'label'            => esc_html__( 'Show Excerpt', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'elements',
				'sub_toggle'       => 'featured',
				'computed_affects' => array( '__posts' ),
			),
			'ft_excerpt_on_hover'       => array(
				'label'           => esc_html__( 'Show Excerpt on Hover', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'elements',
				'sub_toggle'      => 'featured',
				'show_if'         => array(
					'ft_show_excerpt' => 'on',
				),
			),
			// category.
			'category_placement'        => array(
				'label'       => esc_html__( 'Category Position', 'brain-divi-blog' ),
				'type'        => 'select',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'category',
				'sub_toggle'  => 'common',
				'default'     => 'right',
				'options'     => array(
					'left'  => esc_html__( 'Left', 'brain-divi-blog' ),
					'right' => esc_html__( 'Right', 'brain-divi-blog' ),
				),
			),
			'category_offset_y'         => array(
				'label'          => esc_html__( 'Offset Top', 'brain-divi-blog' ),
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
			'category_offset_x'         => array(
				'label'          => esc_html__( 'Offset Left/Right', 'brain-divi-blog' ),
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
			'category_bg'               => array(
				'label'       => esc_html__( 'Category Background', 'brain-divi-blog' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'category',
				'sub_toggle'  => 'common',
				'default'     => '#FF2851',
			),
			'category_padding'          => array(
				'label'          => esc_html__( 'Padding', 'brain-divi-blog' ),
				'type'           => 'custom_padding',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'category',
				'sub_toggle'     => 'common',
				'default'        => '3px|10px|3px|10px',
				'mobile_options' => true,
			),
			'category_radius'           => array(
				'label'       => esc_html__( 'Border Radius', 'brain-divi-blog' ),
				'type'        => 'border-radius',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'category',
				'sub_toggle'  => 'common',
				'default'     => 'off|3px|3px|3px|3px',
			),
			// Content.
			'content_placement'         => array(
				'label'       => esc_html__( 'Content Placement', 'brain-divi-blog' ),
				'type'        => 'select',
				'toggle_slug' => 'tiles_style',
				'tab_slug'    => 'advanced',
				'default'     => 'bottom',
				'options'     => array(
					'bottom' => esc_html__( 'Bottom', 'brain-divi-blog' ),
					'center' => esc_html__( 'Center', 'brain-divi-blog' ),
				),
			),
			'content_alignment'         => array(
				'label'        => __( 'Content Alignment', 'brain-divi-blog' ),
				'type'         => 'text_align',
				'options'      => et_builder_get_text_orientation_options( array( 'justified' ) ),
				'options_icon' => 'module_align',
				'default'      => 'left',
				'toggle_slug'  => 'tiles_style',
				'tab_slug'     => 'advanced',
			),
			'content_padding'           => array(
				'label'          => esc_html__( 'Content Padding', 'brain-divi-blog' ),
				'type'           => 'custom_padding',
				'toggle_slug'    => 'tiles_style',
				'default'        => '20px|20px|20px|20px',
				'tab_slug'       => 'advanced',
				'mobile_options' => true,
			),
			// image.
			'img_hover_style'           => array(
				'label'       => esc_html__( 'Image Hover Style', 'brain-divi-blog' ),
				'type'        => 'select',
				'toggle_slug' => 'image',
				'tab_slug'    => 'advanced',
				'default'     => 'none',
				'options'     => array(
					'none'     => esc_html__( 'None', 'brain-divi-blog' ),
					'zoon_in'  => esc_html__( 'Zoom In', 'brain-divi-blog' ),
					'zoon_out' => esc_html__( 'Zoom Out', 'brain-divi-blog' ),
					'fade'     => esc_html__( 'Fade', 'brain-divi-blog' ),
				),
			),
			'desktop_only_img_hover'    => array(
				'label'       => esc_html__( 'Disable Hover Animation on Tablet and Phone', 'brain-divi-blog' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'     => 'off',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'image',
				'show_if_not' => array(
					'img_hover_style' => 'none',
				),
			),
			'use_overlay'               => array(
				'label'           => esc_html__( 'Show Image Overlay', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'image',
				'tab_slug'        => 'advanced',
			),
			'overlay_visibility'        => array(
				'label'       => esc_html__( 'Overlay Visibility ', 'brain-divi-blog' ),
				'type'        => 'select',
				'toggle_slug' => 'image',
				'tab_slug'    => 'advanced',
				'default'     => 'on_hover',
				'options'     => array(
					'always'   => esc_html__( 'Always', 'brain-divi-blog' ),
					'on_hover' => esc_html__( 'On Hover', 'brain-divi-blog' ),
				),
				'show_if'     => array(
					'use_overlay' => 'on',
				),
			),
			// Meta.
			'meta_spacing_bottom'       => array(
				'label'          => esc_html__( 'Meta Spacing Bottom', 'brain-divi-blog' ),
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
				'toggle_slug'    => 'meta',
				'sub_toggle'     => 'common',
			),
			'meta_item_spacing'         => array(
				'label'          => esc_html__( 'Meta Item Spacing', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '18px',
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
			'excerpt_spacing_top'       => array(
				'label'          => esc_html__( 'Excerpt Spacing Top', 'brain-divi-blog' ),
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
				'toggle_slug'    => 'texts',
				'sub_toggle'     => 'excerpt',
			),
			'excerpt_spacing_bottom'    => array(
				'label'          => esc_html__( 'Excerpt Spacing Bottom', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '0px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'texts',
				'sub_toggle'     => 'excerpt',
			),
			'ft_excerpt_spacing_top'    => array(
				'label'          => esc_html__( 'Excerpt Spacing Top', 'brain-divi-blog' ),
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
				'toggle_slug'    => 'featured_texts',
				'sub_toggle'     => 'excerpt',
			),
			'ft_excerpt_spacing_bottom' => array(
				'label'          => esc_html__( 'Excerpt Spacing Bottom', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '0px',
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'featured_texts',
				'sub_toggle'     => 'excerpt',
			),
			// author.
			'author_img_size'           => array(
				'label'          => esc_html__( 'Author Photo Size', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '26px',
				'allowed_units'  => array( 'px' ),
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
			'author_spacing'            => array(
				'label'          => esc_html__( 'Author Photo/Icon Spacing', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '5px',
				'allowed_units'  => array( 'px' ),
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
			'author_img_radius'         => array(
				'label'          => esc_html__( 'Author Photo Radius', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '30px',
				'allowed_units'  => array( 'px', '%' ),
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
			'author_icon_size'          => array(
				'label'          => esc_html__( 'Author Icon Size', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '14px',
				'allowed_units'  => array( 'px' ),
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
			'author_icon_color'         => array(
				'label'       => esc_html__( 'Author Icon Color', 'brain-divi-blog' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'meta',
				'sub_toggle'  => 'author',
				'default'     => '#fff',
				'show_if'     => array(
					'use_author_photo' => 'off',
				),
			),

			// Date.
			'date_spacing'              => array(
				'label'           => esc_html__( 'Date Icon Spacing', 'brain-divi-blog' ),
				'type'            => 'range',
				'default'         => '5px',
				'option_category' => 'basic_option',
				'allowed_units'   => array( 'px' ),
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
			'date_icon_size'            => array(
				'label'          => esc_html__( 'Date Icon Size', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '14px',
				'allowed_units'  => array( 'px' ),
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
			'date_icon_color'           => array(
				'label'       => esc_html__( 'Date Icon Color', 'brain-divi-blog' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'meta',
				'sub_toggle'  => 'date',
				'default'     => '#ffffff',
			),
			// computed fields.
			'__posts'                   => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'BRBL_PostTiles', 'get_posts' ),
				'computed_depends_on' => array(
					'layout',
					'include_categories',
					'order_by',
					'order',
					'excerpt_length',
					'show_author',
					'show_date',
					'date_format',
					'offset_number',
					'include_posts',
					'exclude_posts',
					'thumb_size',
					'show_categories',
					'show_first_category',
					'use_author_photo',
					'post_type',
				),
			),
		);

		$overlay_bg = $this->brbl_custom_background_fields(
			'overlay',
			'Overlay',
			'advanced',
			'image',
			array( 'color', 'gradient' ),
			array( 'use_overlay' => 'on' ),
			'rgba(0,0,0,.3)'
		);

		return array_merge( $query, $tiles_settings, $post_options, $overlay_bg );
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];
		$advanced_fields['fonts']       = [];

		$advanced_fields['fonts']['title'] = array(
			'label'           => esc_html__( 'Title', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .default .brbl-post-tile-title a',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'title',
		);

		$advanced_fields['fonts']['excerpt'] = array(
			'label'           => esc_html__( 'Excerpt', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .default .brbl-post-tile-excerpt',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'excerpt',
		);

		$advanced_fields['fonts']['ft_title'] = array(
			'label'           => esc_html__( 'Title', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .featured .brbl-post-tile-title a',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'featured_texts',
			'sub_toggle'      => 'title',
		);

		$advanced_fields['fonts']['ft_excerpt'] = array(
			'label'           => esc_html__( 'Excerpt', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .featured .brbl-post-tile-excerpt',
				'important' => 'all',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'featured_texts',
			'sub_toggle'      => 'excerpt',
		);

		$advanced_fields['fonts']['author'] = array(
			'label'           => esc_html__( 'Author', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-tile-author a',
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
			'label'           => esc_html__( 'Category', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-tile-categories, %%order_class%% .brbl-post-tile-categories a',
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
			'label'           => esc_html__( 'Date', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-tile-date',
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

		$advanced_fields['borders']['post'] = array(
			'toggle_slug' => 'tiles_style',
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-post-tile',
					'border_styles' => '%%order_class%% .brbl-post-tile',
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
		);

		$advanced_fields['borders']['main'] = array(
			'toggle_slug' => 'border',
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%%',
					'border_styles' => '%%order_class%%',
				),
				'important' => 'all',
			),
		);

		return $advanced_fields;
	}

	public static function get_posts( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$defaults = array(
			'layout'              => '',
			'include_categories'  => '',
			'order_by'            => '',
			'order'               => '',
			'excerpt_length'      => '',
			'date_format'         => '',
			'offset_number'       => '',
			'include_posts'       => '',
			'exclude_posts'       => '',
			'thumb_size'          => '',
			'show_title'          => '',
			'show_content'        => '',
			'show_btn'            => '',
			'show_author'         => '',
			'show_date'           => '',
			'show_categories'     => '',
			'show_first_category' => '',
			'use_author_photo'    => '',
			'post_type'           => '',
		);

		$args                = wp_parse_args( $args, $defaults );
		$layout              = $args['layout'];
		$include_categories  = $args['include_categories'];
		$order_by            = $args['order_by'];
		$order               = $args['order'];
		$post_count          = 5;
		$excerpt_length      = $args['excerpt_length'];
		$date_format         = $args['date_format'];
		$include_posts       = $args['include_posts'];
		$exclude_posts       = $args['exclude_posts'];
		$offset_number       = $args['offset_number'];
		$thumb_size          = $args['thumb_size'];
		$show_categories     = $args['show_categories'];
		$show_first_category = $args['show_first_category'];
		$show_author         = $args['show_author'];
		$show_date           = $args['show_date'];
		$use_author_photo    = $args['use_author_photo'];
		$layout              = $args['layout'];
		$post_type           = $args['post_type'];
		$query_counter       = 0;
		$item_class          = 'default';
		$post_count_four     = array( 'tile-6', 'tile-7', 'tile-8', 'tile-9', 'tile-10', 'tile-11', 'tile-12' );
		$featured_post       = array(
			'tile-1'  => '2',
			'tile-2'  => '1',
			'tile-3'  => '4,5',
			'tile-4'  => '1,2',
			'tile-5'  => '2',
			'tile-6'  => '0',
			'tile-7'  => '0',
			'tile-8'  => '1',
			'tile-9'  => '1',
			'tile-10' => '1',
			'tile-11' => '1',
			'tile-12' => '1',
		);

		$featured_post_items = $featured_post[ $layout ];

		if ( in_array( $layout, $post_count_four, true ) ) {
			$post_count = 4;
		}

		if ( 'tile-12' === $layout ) {
			$post_count = 3;
		}

		$query_args = array(
			'posts_per_page' => intval( $post_count ),
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'orderby'        => $order_by,
			'order'          => $order,
			'offset'         => intval( $offset_number ),
		);

		if ( ! empty( $exclude_posts ) ) {
			$exclude_posts              = str_replace( ' ', '', $exclude_posts );
			$exclude_posts              = explode( ',', $exclude_posts );
			$query_args['post__not_in'] = $exclude_posts;
		}

		if ( ! empty( $include_posts ) ) {
			$include_posts          = str_replace( ' ', '', $include_posts );
			$include_posts          = explode( ',', $include_posts );
			$query_args['post__in'] = $include_posts;
		}

		if ( 'post' === $post_type ) {
			$post_id           = isset( $current_page['id'] ) ? (int) $current_page['id'] : 0;
			$query_args['cat'] = implode( ',', self::filter_include_categories( $include_categories, $post_id ) );
		}

		$query = new WP_Query( $query_args );

		ob_start();

		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) :
				$query->the_post();
				$query_counter ++;

				if ( preg_match( '/\b' . $query_counter . '\b/', $featured_post_items ) ) {
					$item_class = 'featured';
				} else {
					$item_class = 'default';
				}

				include BRAIN_BLOG_DIR . 'includes/templates/tile-content.php';
			endwhile;
		endif;

		$output = ob_get_clean();
		if ( ! $output ) {
			$output = self::get_no_results_template( et_core_esc_previously( 'h3' ) );
		}

		return $output;
	}

	public function render( $attrs, $content, $render_slug ) {

		$layout      = $this->props['layout'];
		$base_height = $this->props['base_height'];

		$this->render_post_css( $render_slug );
		$grid_classes = array();
		array_push( $grid_classes, 'brbl-module brbl-post-tiles' );
		array_push( $grid_classes, $layout );

		$output = sprintf(
			'<div class="%2$s" style="--brbl-grid-size: %3$s">
                %1$s
            </div>',
			self::get_posts( $this->props ),
			join( ' ', $grid_classes ),
			$base_height
		);

		return $output;
	}

	public function render_post_css( $render_slug ) {

		$show_meta_icons     = $this->props['show_meta_icons'];
		$tiles_gap           = $this->props['tiles_gap'];
		$use_overlay         = $this->props['use_overlay'];
		$overlay_visibility  = $this->props['overlay_visibility'];
		$img_hover_style     = $this->props['img_hover_style'];
		$content_alignment   = $this->props['content_alignment'];
		$use_author_photo    = $this->props['use_author_photo'];
		$author_img_size     = $this->props['author_img_size'];
		$author_spacing      = $this->props['author_spacing'];
		$author_img_radius   = $this->props['author_img_radius'];
		$author_icon_color   = $this->props['author_icon_color'];
		$meta_item_spacing   = $this->props['meta_item_spacing'];
		$author_icon_size    = $this->props['author_icon_size'];
		$date_spacing        = $this->props['date_spacing'];
		$date_icon_color     = $this->props['date_icon_color'];
		$date_icon_size      = $this->props['date_icon_size'];
		$category_bg         = $this->props['category_bg'];
		$category_offset_x   = $this->props['category_offset_x'];
		$category_offset_y   = $this->props['category_offset_y'];
		$category_placement  = $this->props['category_placement'];
		$content_placement   = $this->props['content_placement'];
		$excerpt_on_hover    = $this->props['excerpt_on_hover'];
		$ft_excerpt_on_hover = $this->props['ft_excerpt_on_hover'];
		$show_excerpt        = $this->props['show_excerpt'];
		$show_author         = $this->props['show_author'];
		$show_date           = $this->props['show_date'];
		$ft_show_excerpt     = $this->props['ft_show_excerpt'];
		$category_radius     = explode( '|', $this->props['category_radius'] );

		if ( 'off' === $show_date && 'off' === $show_author ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-meta',
					'declaration' => 'display: none!important;',
				)
			);
		}

		if ( 'on' === $excerpt_on_hover ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .default .brbl-post-tile-excerpt',
					'declaration' => 'max-height:0px; transition: .3s; opacity: 0;',
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .default:hover .brbl-post-tile-excerpt',
					'declaration' => 'max-height: 8em; opacity: 1;',
				)
			);
		}

		if ( 'on' === $ft_excerpt_on_hover ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .featured .brbl-post-tile-excerpt',
					'declaration' => 'max-height:0px; transition: .3s; opacity: 0;',
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .featured:hover .brbl-post-tile-excerpt',
					'declaration' => 'max-height: 8em; opacity: 1;',
				)
			);
		}

		if ( 'off' === $show_excerpt ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .default .brbl-post-tile-excerpt',
					'declaration' => 'display: none;',
				)
			);
		}

		if ( 'off' === $ft_show_excerpt ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .featured .brbl-post-tile-excerpt',
					'declaration' => 'display: none;',
				)
			);
		}

		$this->brbl_get_responsive_styles(
			'category_padding',
			'%%order_class%% .brbl-post-tile-categories',
			array(
				'primary'   => 'padding',
				'important' => true,
			),
			array( 'default' => '3px|10px|3px|10px' ),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'excerpt_spacing_top',
			'%%order_class%% .default .brbl-post-tile-excerpt',
			array(
				'primary'   => 'margin-top',
				'important' => true,
			),
			array( 'default' => '15px' ),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'excerpt_spacing_bottom',
			'%%order_class%% .default .brbl-post-tile-excerpt',
			array(
				'primary'   => 'margin-bottom',
				'important' => true,
			),
			array( 'default' => '0px' ),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'ft_excerpt_spacing_top',
			'%%order_class%% .featured .brbl-post-tile-excerpt',
			array(
				'primary'   => 'margin-top',
				'important' => true,
			),
			array( 'default' => '15px' ),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'ft_excerpt_spacing_bottom',
			'%%order_class%% .featured .brbl-post-tile-excerpt',
			array(
				'primary'   => 'margin-bottom',
				'important' => true,
			),
			array( 'default' => '0px' ),
			$render_slug
		);

		$this->brbl_get_custom_bg_style( $render_slug, 'overlay', '%%order_class%% .brbl-post-tile-figure:before', '%%order_class%%  .brbl-post-tile:hover .brbl-post-tile-figure:before' );

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-post-tile-categories',
				'declaration' => sprintf(
					'%1$s: %2$s;
                    background: %3$s;
					top: %4$s;
					border-radius: %5$s %6$s %7$s %8$s;',
					$category_placement,
					$category_offset_x,
					$category_bg,
					$category_offset_y,
					$category_radius[1],
					$category_radius[2],
					$category_radius[3],
					$category_radius[4]
				),
			)
		);

		if ( 'on' === $use_overlay ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-figure:before',
					'declaration' => '
                        content:"";
                        height: 100%;
                        width: 100%;
                        position: absolute;
                        top: 0;
                        left: 0;
                        z-index: 1;',
				)
			);

			if ( 'on_hover' === $overlay_visibility ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-post-tile-figure:before',
						'declaration' => 'opacity: 0; transition: .4s ease;',
					)
				);
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-post-tile:hover .brbl-post-tile-figure:before',
						'declaration' => 'opacity: 1;',
					)
				);
			}
		}

		if ( 'zoon_in' === $img_hover_style ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile:hover .brbl-post-tile-figure',
					'declaration' => 'transform: scale(1.2);',
				)
			);
		} elseif ( 'zoon_out' === $img_hover_style ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile:hover .brbl-post-tile-figure',
					'declaration' => 'transform: scale(1);',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-figure',
					'declaration' => 'transform: scale(1.2);',
				)
			);
		} elseif ( 'fade' === $img_hover_style ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile:hover .brbl-post-tile-figure',
					'declaration' => 'opacity: .8;',
				)
			);
		}

		if ( 'on' === $this->props['desktop_only_img_hover'] ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile:hover .brbl-post-tile-figure',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile:hover .brbl-post-tile-figure',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
				)
			);
		}

		$this->brbl_get_responsive_styles(
			'content_padding',
			'%%order_class%% .brbl-post-tile-content',
			array(
				'primary'   => 'padding',
				'important' => true,
			),
			array( 'default' => '20px|20px|20px|20px' ),
			$render_slug
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-post-tiles',
				'declaration' => 'grid-gap:' . $tiles_gap . ';',
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-post-tile-content',
				'declaration' => 'text-align:' . $content_alignment . ';',
			)
		);

		if ( 'bottom' === $content_placement ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-content',
					'declaration' => 'bottom:0;',
				)
			);
		} else {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-content',
					'declaration' => 'top:50%;transform: translateY(-50%);',
				)
			);
		}

		if ( 'right' === $content_alignment ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-meta',
					'declaration' => 'justify-content:flex-end;',
				)
			);
		} elseif ( 'center' === $content_alignment ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-meta',
					'declaration' => 'justify-content:center;',
				)
			);
		}

		// Author.
		if ( 'on' === $use_author_photo ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-author img',
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
					'selector'    => '%%order_class%% .brbl-post-tile-author svg',
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
				'selector'    => '%%order_class%% .brbl-post-tile-author',
				'declaration' => sprintf(
					'
                    margin-right: %1$s;
                ',
					$meta_item_spacing
				),
			)
		);

		// Date.
		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-post-tile-date svg',
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

		$this->brbl_get_responsive_styles(
			'meta_spacing_bottom',
			'%%order_class%% .brbl-post-tile-meta',
			array(
				'primary'   => 'margin-bottom',
				'important' => true,
			),
			array( 'default' => '15px' ),
			$render_slug
		);

		if ( 'off' === $show_meta_icons ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-tile-meta svg, %%order_class%% .brbl-post-tile-meta img',
					'declaration' => 'display: none!important;',
				)
			);
		}

	}
}

new BRBL_PostTiles();
