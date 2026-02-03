<?php
class BRBL_Author_List extends BRBL_Builder_Module_Type_PostBased {

	protected $module_credits = array(
		'module_uri' => 'https://divipeople.com/plugins/brain-blog',
		'author'     => 'DiviPeople',
		'author_uri' => 'https://divipeople.com',
	);

	public function init() {

		$this->vb_support = 'on';
		$this->slug       = 'brbl_author_list';
		$this->name       = esc_html__( 'DP Author List', 'brain-divi-blog' );
		$this->icon_path  = plugin_dir_path( __FILE__ ) . 'author-list.svg';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'content'  => esc_html__( 'Content', 'brain-divi-blog' ),
					'elements' => esc_html__( 'Elements', 'brain-divi-blog' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'common' => esc_html__( 'Common', 'brain-divi-blog' ),
					'figure' => esc_html__( 'Icon/Image', 'brain-divi-blog' ),
					'texts'  => array(
						'title'             => esc_html__( 'Texts', 'brain-divi-blog' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'name'  => array(
								'name' => esc_html__( 'Name', 'brain-divi-blog' ),
							),
							'count' => array(
								'name' => esc_html__( 'Count', 'brain-divi-blog' ),
							),
							'email' => array(
								'name' => esc_html__( 'Email', 'brain-divi-blog' ),
							),
							'bio'   => array(
								'name' => esc_html__( 'Bio', 'brain-divi-blog' ),
							),
						),
					),
					'border' => esc_html__( 'Border', 'brain-divi-blog' ),
				),
			),
		);
	}

	public function get_fields() {

		$fields = array(

			// Content.
			'post_type'          => array(
				'label'            => esc_html__( 'Post Type', 'brain-divi-blog' ),
				'type'             => 'select',
				'options'          => brbl_get_posttype(),
				'description'      => esc_html__( 'Choose Authors of which post type you would like to display.', 'brain-divi-blog' ),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'content',
				'default'          => 'post',
			),
			'order_by'           => array(
				'label'            => esc_html__( 'Order By', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Choose how your author should be ordered.', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'content',
				'default'          => 'display_name',
				'options'          => array(
					'user_name'     => esc_html__( 'User Name', 'brain-divi-blog' ),
					'display_name'  => esc_html__( 'Display Name', 'brain-divi-blog' ),
					'user_nicename' => esc_html__( 'User Nicename', 'brain-divi-blog' ),
					'post_count'    => esc_html__( 'Post Count', 'brain-divi-blog' ),
					'ID'            => esc_html__( 'ID', 'brain-divi-blog' ),
				),
				'computed_affects' => array( '__posts' ),
			),
			'order'              => array(
				'label'            => esc_html__( 'Sorted By', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Choose how your author should be sorted.', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'content',
				'default'          => 'ASC',
				'options'          => array(
					'ASC'  => esc_html__( 'Ascending', 'brain-divi-blog' ),
					'DESC' => esc_html__( 'Descending', 'brain-divi-blog' ),
				),

				'default_on_front' => 'ASC',
				'computed_affects' => array( '__posts' ),
			),
			'role_in'            => array(
				'label'            => esc_html__( 'Author Role In ', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Write author role with comma ( , ) separator. Example: administration, editor, subscriber etc', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => '',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__posts' ),
			),
			'role_not_in'        => array(
				'label'            => esc_html__( 'Author Role Not In ', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Write author role with comma ( , ) separator. Example: administration, editor, subscriber etc', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => '',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__posts' ),
			),
			'exclude_author'     => array(
				'label'            => esc_html__( 'Exclude', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Write author ID with comma ( , ) separator. Example: 5, 2, 3 etc', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => '',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__posts' ),
			),

			// elements.
			'name'               => array(
				'label'            => esc_html__( 'Name', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'elements',
				'default'          => 'display_name',
				'options'          => array(
					'display_name'  => esc_html__( 'Display Name', 'brain-divi-blog' ),
					'first_name'    => esc_html__( 'First Name', 'brain-divi-blog' ),
					'last_name'     => esc_html__( 'Last Name', 'brain-divi-blog' ),
					'user_nicename' => esc_html__( 'User Nice Name', 'brain-divi-blog' ),
				),
				'computed_affects' => array( '__posts' ),
			),
			'figure'             => array(
				'label'            => esc_html__( 'Author Figure', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'elements',
				'default'          => 'avatar',
				'options'          => array(
					'avatar' => esc_html__( 'Avatar', 'brain-divi-blog' ),
					'icon'   => esc_html__( 'Icon', 'brain-divi-blog' ),
					'custom' => esc_html__( 'Custom Upload', 'brain-divi-blog' ),
				),
				'computed_affects' => array( '__posts' ),
			),
			'icon'               => array(
				'label'       => esc_html__( 'Select Icon', 'brain-divi-blog' ),
				'type'        => 'select_icon',
				'toggle_slug' => 'elements',
				'default'     => '',
				'show_if'     => array(
					'figure' => 'icon',
				),
			),
			'image'              => array(
				'label'              => esc_html__( 'Upload Image', 'brain-divi-blog' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an Image', 'brain-divi-blog' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'brain-divi-blog' ),
				'update_text'        => esc_attr__( 'Set As Image', 'brain-divi-blog' ),
				'toggle_slug'        => 'elements',
				'show_if'            => array(
					'figure' => 'custom',
				),
			),
			'avatar_size'        => array(
				'label'            => esc_html__( 'Avatar Size', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'elements',
				'default'          => '60',
				'options'          => array(
					'25'  => esc_html__( '25 x 25', 'brain-divi-blog' ),
					'35'  => esc_html__( '35 x 35', 'brain-divi-blog' ),
					'45'  => esc_html__( '45 x 45', 'brain-divi-blog' ),
					'50'  => esc_html__( '50 x 50', 'brain-divi-blog' ),
					'60'  => esc_html__( '60 x 60', 'brain-divi-blog' ),
					'80'  => esc_html__( '80 x 80', 'brain-divi-blog' ),
					'100' => esc_html__( '100 x 100', 'brain-divi-blog' ),
					'125' => esc_html__( '125 x 125', 'brain-divi-blog' ),
					'150' => esc_html__( '150 x 150', 'brain-divi-blog' ),
					'200' => esc_html__( '200 x 200', 'brain-divi-blog' ),
				),
				'computed_affects' => array( '__posts' ),
				'show_if'          => array(
					'figure' => 'avatar',
				),
			),
			'show_post_count'    => array(
				'label'            => esc_html__( 'Show Post Count', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'elements',
				'computed_affects' => array( '__posts' ),
			),
			'post_count_text'    => array(
				'label'            => esc_html__( 'Post Count Text', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => esc_html__( 'Total Post', 'brain-divi-blog' ),
				'toggle_slug'      => 'elements',
				'computed_affects' => array( '__posts' ),
				'show_if'          => array(
					'show_post_count' => 'on',
				),
			),
			'show_bio'           => array(
				'label'            => esc_html__( 'Show Bio', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'off',
				'toggle_slug'      => 'elements',
				'computed_affects' => array( '__posts' ),
			),
			'show_email'         => array(
				'label'            => esc_html__( 'Show Email', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'off',
				'toggle_slug'      => 'elements',
				'computed_affects' => array( '__posts' ),
			),

			'figure_url'         => array(
				'label'            => esc_html__( 'Figure Link URL', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'link_options',
				'default'          => 'none',
				'options'          => array(
					'none'    => esc_html__( 'None', 'brain-divi-blog' ),
					'archive' => esc_html__( 'Archive Link', 'brain-divi-blog' ),
					'website' => esc_html__( 'Website Link', 'brain-divi-blog' ),
				),
				'computed_affects' => array( '__posts' ),
			),
			'name_url'           => array(
				'label'            => esc_html__( 'Name Link URL', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'link_options',
				'default'          => 'none',
				'options'          => array(
					'none'    => esc_html__( 'None', 'brain-divi-blog' ),
					'archive' => esc_html__( 'Archive Link', 'brain-divi-blog' ),
					'website' => esc_html__( 'Website Link', 'brain-divi-blog' ),
				),
				'computed_affects' => array( '__posts' ),
			),
			// common.
			'list_type'          => array(
				'label'       => esc_html__( 'List Type', 'brain-divi-blog' ),
				'type'        => 'select',
				'toggle_slug' => 'common',
				'tab_slug'    => 'advanced',
				'default'     => 'list',
				'options'     => array(
					'list' => esc_html__( 'List', 'brain-divi-blog' ),
					'grid' => esc_html__( 'Grid', 'brain-divi-blog' ),
				),
			),
			'items'              => array(
				'label'          => esc_html__( 'Items per Row', 'brain-divi-blog' ),
				'type'           => 'select',
				'toggle_slug'    => 'common',
				'tab_slug'       => 'advanced',
				'default'        => '4',
				'mobile_options' => true,
				'options'        => array(
					'1' => esc_html__( '1', 'brain-divi-blog' ),
					'2' => esc_html__( '2', 'brain-divi-blog' ),
					'3' => esc_html__( '3', 'brain-divi-blog' ),
					'4' => esc_html__( '4', 'brain-divi-blog' ),
					'5' => esc_html__( '5', 'brain-divi-blog' ),
					'6' => esc_html__( '6', 'brain-divi-blog' ),
					'7' => esc_html__( '7', 'brain-divi-blog' ),
					'8' => esc_html__( '8', 'brain-divi-blog' ),
				),
				'show_if'        => array(
					'list_type' => 'grid',
				),
			),
			'item_spacing'       => array(
				'label'          => esc_html__( 'Item Spacing', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '15px',
				'allowed_units'  => array( 'px' ),
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'common',
				'tab_slug'       => 'advanced',
			),
			'item_padding'       => array(
				'label'          => __( 'Item Padding', 'brain-divi-blog' ),
				'type'           => 'custom_padding',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'common',
				'default'        => '0px|0px|0px|0px',
				'mobile_options' => true,
			),
			'alignment'          => array(
				'label'        => __( 'Content Alignment', 'brain-divi-blog' ),
				'type'         => 'text_align',
				'options'      => et_builder_get_text_orientation_options( array( 'justified' ) ),
				'options_icon' => 'module_align',
				'default'      => 'left',
				'toggle_slug'  => 'common',
				'tab_slug'     => 'advanced',
			),
			// Figure.
			'icon_color'         => array(
				'label'       => esc_html__( 'Icon Color', 'brain-divi-blog' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'figure',
				'default'     => '#333',
				'show_if'     => array(
					'figure' => 'icon',
				),
			),
			'figure_size'        => array(
				'label'          => esc_html__( 'Icon/Image Size', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '60px',
				'allowed_units'  => array( 'px' ),
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 300,
				),
				'toggle_slug'    => 'figure',
				'tab_slug'       => 'advanced',
				'show_if'        => array(
					'figure' => array( 'custom', 'icon' ),
				),
			),

			'figure_spacing'     => array(
				'label'          => esc_html__( 'Icon/Image Spacing', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '12px',
				'allowed_units'  => array( 'px' ),
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'figure',
				'tab_slug'       => 'advanced',
			),

			'icon_padding'       => array(
				'label'          => __( 'Icon Padding', 'brain-divi-blog' ),
				'type'           => 'custom_padding',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'figure',
				'default'        => '0px|0px|0px|0px',
				'mobile_options' => true,
				'show_if'        => array(
					'figure' => 'icon',
				),
			),

			// Texts.
			'bio_spacing'        => array(
				'label'          => esc_html__( 'Bio Spacing Top', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '15px',
				'allowed_units'  => array( 'px' ),
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'texts',
				'sub_toggle'     => 'bio',
				'tab_slug'       => 'advanced',
			),
			'email_spacing'      => array(
				'label'          => esc_html__( 'Email Spacing Top', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '0px',
				'allowed_units'  => array( 'px' ),
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'texts',
				'sub_toggle'     => 'email',
				'tab_slug'       => 'advanced',
			),
			'post_count_spacing' => array(
				'label'          => esc_html__( 'Post Count Spacing Top', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '0px',
				'allowed_units'  => array( 'px' ),
				'mobile_options' => true,
				'default_unit'   => 'px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'toggle_slug'    => 'texts',
				'sub_toggle'     => 'count',
				'tab_slug'       => 'advanced',
			),

			'__posts'            => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'BRBL_Author_List', 'get_authors' ),
				'computed_depends_on' => array(
					'post_type',
					'name',
					'figure',
					'show_post_count',
					'post_count_text',
					'exclude_author',
					'order_by',
					'order',
					'role_not_in',
					'role_in',
					'show_bio',
					'show_email',
					'figure_url',
					'name_url',
					'avatar_size',
					'image',
					'icon',
				),
			),

		);

		$post_bg = $this->brbl_custom_background_fields( 'item', 'Item', 'advanced', 'common', array( 'color', 'gradient', 'hover' ), array(), '' );

		return array_merge( $fields, $post_bg );
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];
		$advanced_fields['fonts']       = [];

		$advanced_fields['borders']['figure'] = array(
			'toggle_slug' => 'figure',
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-author-list-figure img',
					'border_styles' => '%%order_class%% .brbl-author-list-figure img',
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

		$advanced_fields['borders']['item'] = array(
			'label_prefix' => esc_html__( 'Item', 'brain-divi-blog' ),
			'toggle_slug'  => 'common',
			'css'          => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-author-list-child-inner',
					'border_styles' => '%%order_class%% .brbl-author-list-child-inner',
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

		$advanced_fields['box_shadow']['item'] = array(
			'label'       => esc_html__( 'Item Box Shadow', 'brain-divi-blog' ),
			'toggle_slug' => 'common',
			'css'         => array(
				'main'      => '%%order_class%% .brbl-author-list-child-inner',
				'important' => 'all',
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
			'defaults'    => array(
				'border_radii'  => 'on|0px|0px|0px|0px',
				'border_styles' => array(
					'width' => '0px',
					'color' => '#333',
					'style' => 'solid',
				),
			),
		);

		$advanced_fields['fonts']['name'] = array(
			'label'           => esc_html__( 'Name', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-author-list-name',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'name',
			'hide_text_align' => true,
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['count'] = array(
			'label'           => esc_html__( 'Count', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-author-list-post-count',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'count',
			'hide_text_align' => true,
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['email'] = array(
			'label'           => esc_html__( 'Email', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-author-list-email',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'email',
			'hide_text_align' => true,
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['bio'] = array(
			'label'           => esc_html__( 'Bio', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-author-list-bio',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'bio',
			'hide_text_align' => true,
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		return $advanced_fields;
	}

	public static function get_authors( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$defaults = array(
			'post_type'       => '',
			'name'            => '',
			'figure'          => '',
			'show_post_count' => '',
			'post_count_text' => '',
			'exclude_author'  => '',
			'order_by'        => '',
			'order'           => '',
			'role_not_in'     => '',
			'role_in'         => '',
			'show_bio'        => '',
			'show_email'      => '',
			'figure_url'      => '',
			'name_url'        => '',
			'avatar_size'     => '',
			'image'           => '',
			'icon'            => '',
		);

		$args            = wp_parse_args( $args, $defaults );
		$post_type       = $args['post_type'];
		$name            = $args['name'];
		$figure          = $args['figure'];
		$show_post_count = $args['show_post_count'];
		$exclude_author  = $args['exclude_author'];
		$order_by        = $args['order_by'];
		$order           = $args['order'];
		$role_not_in     = $args['role_not_in'];
		$role_in         = $args['role_in'];
		$post_count_text = $args['post_count_text'];
		$show_bio        = $args['show_bio'];
		$show_email      = $args['show_email'];
		$figure_url      = $args['figure_url'];
		$name_url        = $args['name_url'];
		$avatar_size     = $args['avatar_size'];
		$image           = $args['image'];
		$icon            = $args['icon'];
		$icon            = esc_attr( et_pb_process_font_icon( $icon ) );

		$users_id   = array();
		$user_query = array();

		$user_query['orderby'] = $order_by;
		$user_query['order']   = $order;

		if ( ! empty( $role_in ) ) {
			$role_in                = str_replace( ' ', '', $role_in );
			$role_in                = explode( ',', $role_in );
			$user_query['role__in'] = $role_in;
		}

		if ( ! empty( $role_not_in ) ) {
			$role_not_in                = str_replace( ' ', '', $role_not_in );
			$role_not_in                = explode( ',', $role_not_in );
			$user_query['role__not_in'] = $role_not_in;
		}

		if ( ! empty( $exclude_author ) ) {
			$exclude_author        = str_replace( ' ', '', $exclude_author );
			$exclude_author        = explode( ',', $exclude_author );
			$user_query['exclude'] = $exclude_author;
		}

		$all_users = new WP_User_Query( $user_query );

		foreach ( $all_users->get_results() as $user ) {
			$user_post_count = count_user_posts( $user->ID, $post_type, true );
			if ( $user_post_count > 0 ) {
				$users_id[] = array(
					'author_id'  => $user->ID,
					'post_count' => $user_post_count,
				);
			}
		}

		ob_start();

		foreach ( $users_id as $user_id ) {
			include 'templates/author-content.php';
		}

		$output = ob_get_clean();

		if ( ! $output ) {
			$output = self::get_no_results_template();
		}

		return $output;
	}

	public function render( $attrs, $content, $render_slug ) {

		$this->render_css( $render_slug );
		brbl_inject_fa_icons( $this->props['icon'] );

		$list_type       = $this->props['list_type'];
		$post_type       = $this->props['post_type'];
		$name            = $this->props['name'];
		$figure          = $this->props['figure'];
		$show_post_count = $this->props['show_post_count'];
		$post_count_text = $this->props['post_count_text'];
		$exclude_author  = $this->props['exclude_author'];
		$order_by        = $this->props['order_by'];
		$order           = $this->props['order'];
		$role_not_in     = $this->props['role_not_in'];
		$role_in         = $this->props['role_in'];
		$show_bio        = $this->props['show_bio'];
		$show_email      = $this->props['show_email'];
		$figure_url      = $this->props['figure_url'];
		$name_url        = $this->props['name_url'];
		$avatar_size     = $this->props['avatar_size'];
		$image           = $this->props['image'];
		$icon            = $this->props['icon'];
		$icon            = esc_attr( et_pb_process_font_icon( $icon ) );

		$query_var = array(
			'post_type'       => $post_type,
			'name'            => $name,
			'figure'          => $figure,
			'show_post_count' => $show_post_count,
			'post_count_text' => $post_count_text,
			'exclude_author'  => $exclude_author,
			'order_by'        => $order_by,
			'order'           => $order,
			'role_not_in'     => $role_not_in,
			'role_in'         => $role_in,
			'show_bio'        => $show_bio,
			'show_email'      => $show_email,
			'name_url'        => $name_url,
			'figure_url'      => $figure_url,
			'avatar_size'     => $avatar_size,
			'image'           => $image,
			'icon'            => $icon,
		);

		return sprintf(
			'<div class="brbl-module brbl-author-list type-%2$s">
				<ul class="brbl-author-list-parent">
					%1$s
				</ul>
			</div>',
			self::get_authors( $query_var ),
			$list_type
		);
	}

	protected function render_css( $render_slug ) {
		$alignment                      = $this->props['alignment'];
		$figure                         = $this->props['figure'];
		$icon_color                     = $this->props['icon_color'];
		$list_type                      = $this->props['list_type'];
		$items                          = $this->props['items'];
		$items_tablet                   = ! empty( $this->props['items_tablet'] ) ? $this->props['items_tablet'] : $items;
		$items_phone                    = ! empty( $this->props['items_phone'] ) ? $this->props['items_phone'] : $items_tablet;
		$item_spacing                   = $this->props['item_spacing'];
		$item_spacing_tablet            = ! empty( $this->props['item_spacing_tablet'] ) ? $this->props['item_spacing_tablet'] : $item_spacing;
		$item_spacing_phone             = ! empty( $this->props['item_spacing_phone'] ) ? $this->props['item_spacing_phone'] : $item_spacing_tablet;
		$item_spacing_last_edited       = $this->props['item_spacing_last_edited'];
		$item_spacing_responsive_status = et_pb_get_responsive_status( $item_spacing_last_edited );

		if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) ) {
			$this->generate_styles(
				array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'icon',
					'important'      => true,
					'selector'       => '%%order_class%% .brbl-author-list-figure .brbl-et-icon',
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				)
			);
		}

		if ( 'right' === $alignment ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-top',
					'declaration' => 'flex-direction: row-reverse;',
				)
			);
		} elseif ( 'center' === $alignment ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-top',
					'declaration' => 'flex-direction: column;',
				)
			);
		}

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-author-list-child-inner',
				'declaration' => 'text-align:' . $alignment . '!important;',
			)
		);

		$this->brbl_get_responsive_styles(
			'item_padding',
			'%%order_class%% .brbl-author-list-child-inner',
			array( 'primary' => 'padding' ),
			array( 'default' => '0px|0px|0px|0px' ),
			$render_slug
		);

		if ( 'grid' === $list_type ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-child',
					'declaration' => sprintf(
						'
                    flex: 0 0 calc(100%%/%1$s);
                    max-width:calc(100%%/%1$s);
                    padding:%2$s;',
						$items,
						$item_spacing
					),
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-parent',
					'declaration' => sprintf( 'margin: -%1$s;', $item_spacing ),
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-child',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					'declaration' => sprintf(
						'
                    flex: 0 0 calc(100%%/%1$s);
                    max-width:calc(100%%/%1$s);
                    padding:%2$s;',
						$items_tablet,
						$item_spacing_tablet
					),
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-parent',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					'declaration' => sprintf( 'margin: -%1$s;', $item_spacing_tablet ),
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-child',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					'declaration' => sprintf(
						'
                    flex: 0 0 calc(100%%/%1$s);
                    max-width:calc(100%%/%1$s);
                    padding:%2$s;',
						$items_phone,
						$item_spacing_phone
					),
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-parent',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					'declaration' => sprintf( 'margin: -%1$s;', $item_spacing_phone ),
				)
			);
		} elseif ( 'list' === $list_type ) {

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-child',
					'declaration' => sprintf( 'padding-bottom:%1$s;', $item_spacing ),
				)
			);

			if ( ! empty( $item_spacing_tablet ) && $item_spacing_responsive_status ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-author-list-child',
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
						'declaration' => sprintf( 'padding-bottom:%1$s;', $item_spacing_tablet ),
					)
				);
			}

			if ( ! empty( $item_spacing_phone ) && $item_spacing_responsive_status ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .brbl-author-list-child',
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
						'declaration' => sprintf( 'padding-bottom:%1$s;', $item_spacing_phone ),
					)
				);
			}
		}

		// Figure.
		if ( 'icon' === $figure ) {
			$this->brbl_get_responsive_styles(
				'figure_size',
				'%%order_class%% .brbl-author-list-figure .brbl-et-icon',
				array( 'primary' => 'font-size' ),
				array( 'default' => '60px' ),
				$render_slug
			);
			$this->brbl_get_responsive_styles(
				'icon_padding',
				'%%order_class%% .brbl-author-list-figure',
				array( 'primary' => 'padding' ),
				array( 'default' => '0px' ),
				$render_slug
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-author-list-figure .brbl-et-icon',
					'declaration' => sprintf( 'color:%1$s;', $icon_color ),
				)
			);
		}
		if ( 'custom' === $figure ) {
			$this->brbl_get_responsive_styles(
				'figure_size',
				'%%order_class%% .brbl-author-list-figure',
				array( 'primary' => 'height' ),
				array( 'default' => '60px' ),
				$render_slug
			);
			$this->brbl_get_responsive_styles(
				'figure_size',
				'%%order_class%% .brbl-author-list-figure',
				array( 'primary' => 'width' ),
				array( 'default' => '60px' ),
				$render_slug
			);
		}

		if ( 'left' === $alignment ) {
			$this->brbl_get_responsive_styles(
				'figure_spacing',
				'%%order_class%% .brbl-author-list-figure',
				array( 'primary' => 'margin-right' ),
				array( 'default' => '12px' ),
				$render_slug
			);
		} elseif ( 'right' === $alignment ) {
			$this->brbl_get_responsive_styles(
				'figure_spacing',
				'%%order_class%% .brbl-author-list-figure',
				array( 'primary' => 'margin-left' ),
				array( 'default' => '12px' ),
				$render_slug
			);
		} else {
			$this->brbl_get_responsive_styles(
				'figure_spacing',
				'%%order_class%% .brbl-author-list-figure',
				array( 'primary' => 'margin-bottom' ),
				array( 'default' => '12px' ),
				$render_slug
			);
		}

		// Texts.
		$this->brbl_get_responsive_styles(
			'post_count_spacing',
			'%%order_class%% .brbl-author-list-post-count',
			array( 'primary' => 'margin-top' ),
			array( 'default' => '0px' ),
			$render_slug
		);
		$this->brbl_get_responsive_styles(
			'email_spacing',
			'%%order_class%% .brbl-author-list-email',
			array( 'primary' => 'margin-top' ),
			array( 'default' => '0px' ),
			$render_slug
		);
		$this->brbl_get_responsive_styles(
			'bio_spacing',
			'%%order_class%% .brbl-author-list-bio',
			array( 'primary' => 'margin-top' ),
			array( 'default' => '0px' ),
			$render_slug
		);

		$this->brbl_get_custom_bg_style( $render_slug, 'item', '%%order_class%% .brbl-author-list-child-inner', '.%%order_class%% .brbl-author-list-child-inner:hover' );

	}
}

new BRBL_Author_List();
