<?php
class BRBL_PostTickerPro extends BRBL_Builder_Module_Type_PostBased {

	protected $module_credits = array(
		'module_uri' => 'https://divipeople.com/plugins/brain-blog',
		'author'     => 'DiviPeople',
		'author_uri' => 'https://divipeople.com',
	);

	public function init() {

		$this->slug       = 'brbl_posts_ticker';
		$this->vb_support = 'on';
		$this->name       = esc_html__( 'DP Post Ticker Pro', 'brain-divi-blog' );
		$this->icon_path  = plugin_dir_path( __FILE__ ) . 'post-ticker.svg';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'content'  => esc_html__( 'Query', 'brain-divi-blog' ),
					'settings' => esc_html__( 'Settings', 'brain-divi-blog' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'date'     => esc_html__( 'Date', 'brain-divi-blog' ),
					'title'    => esc_html__( 'Title', 'brain-divi-blog' ),
					'texts'    => array(
						'title'             => esc_html__( 'Texts', 'brain-divi-blog' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'date'  => array(
								'name' => esc_html__( 'Date', 'brain-divi-blog' ),
							),
							'title' => array(
								'name' => esc_html__( 'Title', 'brain-divi-blog' ),
							),
							'post'  => array(
								'name' => esc_html__( 'Post', 'brain-divi-blog' ),
							),
						),
					),
					'controls' => esc_html__( 'Controls', 'brain-divi-blog' ),
					'border'   => esc_html__( 'Border', 'brain-divi-blog' ),
				),
			),
		);
	}

	public function get_fields() {

		$fields = array(
			// Content.
			'show_title'            => array(
				'label'           => esc_html__( 'Show Title', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'content',
			),
			'title'                 => array(
				'label'       => esc_html__( 'Title Text', 'brain-divi-blog' ),
				'type'        => 'text',
				'default'     => 'Breaking News',
				'toggle_slug' => 'content',
				'show_if'     => array(
					'show_title' => 'on',
				),
			),
			'show_date'             => array(
				'label'            => esc_html__( 'Show Date', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__date' ),
			),
			'date_format'           => array(
				'label'            => esc_html__( 'Date Format', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => 'd F, Y',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__date' ),
				'show_if'          => array(
					'show_date' => 'on',
				),
			),
			'post_type'             => array(
				'label'            => esc_html__( 'Post Type', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => et_get_registered_post_type_options( false, false ),
				'description'      => esc_html__( 'Choose posts of which post type you would like to display.', 'brain-divi-blog' ),
				'computed_affects' => array(
					'__posts',
				),
				'toggle_slug'      => 'content',
				'default'          => 'post',
			),
			'include_categories'    => array(
				'label'            => esc_html__( 'Included Categories', 'brain-divi-blog' ),
				'type'             => 'categories',
				'option_category'  => 'basic_option',
				'meta_categories'  => array(
					'current' => esc_html__( 'Current Category', 'brain-divi-blog' ),
				),
				'renderer_options' => array(
					'use_terms' => false,
				),
				'description'      => esc_html__( 'Choose which categories you would like to include in the List.', 'brain-divi-blog' ),
				'toggle_slug'      => 'content',
				'computed_affects' => array(
					'__post',
				),
				'show_if'          => array(
					'post_type' => 'post',
				),
			),
			'order_by'              => array(
				'label'            => esc_html__( 'Order By', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Choose how your Posts should be ordered.', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'content',
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
				'computed_affects' => array( '__post' ),
			),
			'order'                 => array(
				'label'            => esc_html__( 'Sorted By', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Choose how your posts should be sorted.', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'content',
				'default'          => 'ASC',
				'options'          => array(
					'ASC'  => esc_html__( 'Ascending', 'brain-divi-blog' ),
					'DESC' => esc_html__( 'Descending', 'brain-divi-blog' ),
				),
				'default_on_front' => 'ASC',
				'computed_affects' => array( '__post' ),
			),
			'post_count'            => array(
				'label'            => esc_html__( 'Post Limit', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => '10',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__post' ),
			),
			'offset_number'         => array(
				'label'            => esc_html__( 'Post Offset Number', 'brain-divi-blog' ),
				'type'             => 'text',
				'description'      => esc_html__( 'Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'brain-divi-blog' ),
				'toggle_slug'      => 'content',
				'computed_affects' => array(
					'__posts',
				),
				'default'          => 0,
			),
			'include_posts'         => array(
				'label'            => esc_html__( 'Include posts by IDs', 'brain-divi-blog' ),
				'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Only selected posts will be included.', 'brain-divi-addons-pro' ),
				'type'             => 'text',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__posts' ),
			),
			'exclude_posts'         => array(
				'label'            => esc_html__( 'Exclude posts by IDs', 'brain-divi-blog' ),
				'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Selected Posts will be ignored.', 'brain-divi-addons-pro' ),
				'type'             => 'text',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__posts' ),
			),
			// settings.
			'parent_height'         => array(
				'label'          => __( 'Height', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '40px',
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 500,
				),
				'toggle_slug'    => 'settings',
			),
			'animation'             => array(
				'label'            => esc_html__( 'Animation', 'brain-divi-blog' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'toggle_slug'      => 'settings',
				'default'          => 'typewriter',
				'options'          => array(
					'typewriter' => esc_html__( 'Typewriter', 'brain-divi-blog' ),
					'vertical'   => esc_html__( 'Vertical Slide', 'brain-divi-blog' ),
					'fade'       => esc_html__( 'Fade', 'brain-divi-blog' ),
				),
				'computed_affects' => array( '__post' ),
			),
			'autoplay'              => array(
				'label'            => esc_html__( 'Autoplay', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'settings',
				'computed_affects' => array( '__date' ),
			),
			'autoplay_speed'        => array(
				'label'            => __( 'Autoplay Speed', 'brain-divi-blog' ),
				'type'             => 'range',
				'default'          => 5000,
				'unitless'         => true,
				'range_settings'   => array(
					'step' => 100,
					'min'  => 3000,
					'max'  => 20000,
				),
				'toggle_slug'      => 'settings',
				'computed_affects' => array( '__date' ),
			),
			'show_controls'         => array(
				'label'            => esc_html__( 'Show Controls', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'settings',
				'computed_affects' => array( '__date' ),
			),
			'show_controls_onhover' => array(
				'label'            => esc_html__( 'Show Controls on Hover', 'brain-divi-blog' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'          => 'off',
				'toggle_slug'      => 'settings',
				'computed_affects' => array( '__date' ),
				'show_if'          => array(
					'show_controls' => 'on',
				),
			),
			'mobile_hide_controls'  => array(
				'label'           => esc_html__( 'Hide Controls on Phone', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'settings',
				'show_if'         => array(
					'show_controls' => 'on',
				),
			),
			'mobile_hide_title'     => array(
				'label'           => esc_html__( 'Hide Title on Phone', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'settings',
			),
			'mobile_hide_date'      => array(
				'label'           => esc_html__( 'Hide Date on Phone', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'settings',
			),
			// title.
			'title_design'          => array(
				'label'       => esc_html__( 'Title Design', 'brain-divi-blog' ),
				'type'        => 'select',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'title',
				'default'     => 'design_3',
				'options'     => array(
					'design_1' => esc_html__( 'Design 1', 'brain-divi-blog' ),
					'design_2' => esc_html__( 'Design 2', 'brain-divi-blog' ),
					'design_3' => esc_html__( 'Design 3', 'brain-divi-blog' ),
					'design_4' => esc_html__( 'Design 4', 'brain-divi-blog' ),
				),
			),
			'title_right_color'     => array(
				'label'       => esc_html__( 'Title Right Shape Color', 'brain-divi-blog' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'title',
				'default'     => '#333333',
				'show_if_not' => array(
					'title_design' => 'design_1',
				),
			),
			'title_padding'         => array(
				'label'          => __( 'Title Padding', 'brain-divi-blog' ),
				'type'           => 'custom_padding',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'title',
				'default'        => '10px|10px|10px|10px',
				'mobile_options' => true,
			),
			'title_spacing'         => array(
				'label'          => __( 'Title Spacing Right', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '30px',
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'title',
			),
			// Date.
			'date_padding'          => array(
				'label'          => __( 'Date Padding', 'brain-divi-blog' ),
				'type'           => 'custom_padding',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date',
				'default'        => '10px|10px|10px|10px',
				'mobile_options' => true,
			),
			'date_spacing'          => array(
				'label'          => __( 'Date Spacing Right', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '0px',
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'date',
			),

			// Controls.
			'controls_size'         => array(
				'label'          => __( 'Size', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '28px',
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 500,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'controls',
			),
			'spacing_between'       => array(
				'label'          => __( 'Spacing Between', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '5px',
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 100,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'controls',
			),
			'arrow_size'            => array(
				'label'          => __( 'Arrow Size', 'brain-divi-blog' ),
				'type'           => 'range',
				'default'        => '20px',
				'default_unit'   => 'px',
				'mobile_options' => true,
				'range_settings' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 50,
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'controls',
			),
			'arrow_color'           => array(
				'label'       => esc_html__( 'Arrow Color', 'brain-divi-blog' ),
				'type'        => 'color-alpha',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'controls',
				'default'     => '#ffffff',
			),
			'__post'                => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'BRBL_PostTickerPro', 'get_posts' ),
				'computed_depends_on' => array(
					'post_type',
					'include_categories',
					'show_controls',
					'show_controls_onhover',
					'post_count',
					'direction',
					'direction_alt',
					'animation',
					'order_by',
					'order',
					'autoplay_speed',
					'autoplay',
					'offset_number',
					'include_posts',
					'exclude_posts',
				),
			),
			'__date'                => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'BRBL_PostTickerPro', 'get_date' ),
				'computed_depends_on' => array(
					'show_date',
					'date_format',
				),
			),
		);

		$title_bg    = $this->brbl_custom_background_fields( 'title', 'Title', 'advanced', 'title', array( 'color', 'gradient', 'hover' ), array(), '#333333' );
		$date_bg     = $this->brbl_custom_background_fields( 'date', 'Date', 'advanced', 'date', array( 'color', 'gradient', 'hover' ), array(), '#2ecc71' );
		$controls_bg = $this->brbl_custom_background_fields( 'controls', 'Controls', 'advanced', 'controls', array( 'color', 'gradient', 'hover' ), array(), '#333333' );

		return array_merge( $fields, $title_bg, $date_bg, $controls_bg );
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];
		$advanced_fields['fonts']       = [];

		$advanced_fields['borders']['controls'] = array(
			'toggle_slug' => 'controls',
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-poststicker-nav span',
					'border_styles' => '%%order_class%% .brbl-poststicker-nav span',
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

		$advanced_fields['borders']['title'] = array(
			'toggle_slug' => 'title',
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-poststicker-title',
					'border_styles' => '%%order_class%% .brbl-poststicker-title',
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

		$advanced_fields['borders']['date'] = array(
			'toggle_slug' => 'date',
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-poststicker-date',
					'border_styles' => '%%order_class%% .brbl-poststicker-date',
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
			'defaults'    => array(
				'border_radii'  => 'on|0px|0px|0px|0px',
				'border_styles' => array(
					'width' => '0px',
					'color' => '#333',
					'style' => 'solid',
				),
			),
		);

		$advanced_fields['fonts']['date'] = array(
			'label'           => esc_html__( 'Date', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-poststicker-date',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'date',
			'hide_text_align' => true,
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['title'] = array(
			'label'           => esc_html__( 'Title', 'brain-divi-blog' ),
			'css'             => array(
				'main'      => '%%order_class%% .brbl-poststicker-title',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'title',
			'hide_text_align' => true,
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['post'] = array(
			'css'             => array(
				'main'      => '%%order_class%% .brbl-poststicker li a',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'texts',
			'sub_toggle'      => 'post',
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

	public static function get_posts( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$defaults = array(
			'post_type'          => '',
			'include_categories' => '',
			'order_by'           => '',
			'order'              => '',
			'post_count'         => '',
			'offset_number'      => '',
			'include_posts'      => '',
			'exclude_posts'      => '',
		);

		$args               = wp_parse_args( $args, $defaults );
		$include_categories = $args['include_categories'];
		$post_type          = $args['post_type'];
		$order_by           = $args['order_by'];
		$order              = $args['order'];
		$post_count         = $args['post_count'];
		$offset_number      = $args['offset_number'];
		$exclude_posts      = $args['exclude_posts'];
		$include_posts      = $args['include_posts'];

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

		$post_id = isset( $current_page['id'] ) ? (int) $current_page['id'] : 0;

		if ( 'post' === $post_type ) {
			$query_args['cat'] = implode( ',', self::filter_include_categories( $include_categories, $post_id ) );
		}

		$query = new WP_Query( $query_args );

		ob_start();

		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) :
				$query->the_post();

				printf(
					'<li><a href="%1$s">%2$s</a></li>',
					esc_url( get_the_permalink() ),
					et_core_intentionally_unescaped( get_the_title(), 'html' )
				);

			endwhile;
		endif;

		$output = ob_get_clean();

		if ( ! $output ) {
			$output = '<li>No Post Found!</li>';
		}

		return $output;
	}


	public static function get_date( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$defaults = array(
			'date_format' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$date_format = $args['date_format'];

		ob_start();

		echo esc_html( current_time( $date_format ) );

		$output = ob_get_clean();

		return $output;
	}

	protected function render_date() {

		$show_date   = $this->props['show_date'];
		$date_format = $this->props['date_format'];

		$post_query_var = array(
			'date_format' => $date_format,
		);

		if ( 'on' === $show_date ) {
			return sprintf(
				'<div class="brbl-poststicker-date">%1$s</div>',
				self::get_date( $post_query_var )
			);
		}

	}

	protected function render_title() {

		if ( 'on' === $this->props['show_title'] ) {

			$title             = $this->props['title'];
			$title_design      = $this->props['title_design'];
			$title_right_color = $this->props['title_right_color'];
			$shape             = '';

			if ( 'design_2' === $title_design ) {
				$shape = (
				 "<svg
					id='bg'
					height='100%'
					width='50'
					viewBox='0 0 150 100'
					preserveAspectRatio='none'
					shape-rendering='geometricPrecision'
				  >
					<path
					  d='M0,0 h0 l40,50 l-40,50 h-100z'
					  fill={$title_right_color}
					></path>
				  </svg>"
				);
			} elseif ( 'design_3' === $title_design ) {
				$shape = (
				"<svg
					preserveAspectRatio='none'
					viewBox='0 0 100 100'
					height='100%'
					width='30px'
					fill={$title_right_color}
				  >
					<polygon points='0,100 0,0 50,0 100' opacity='1'></polygon>
				  </svg>"
				);
			} elseif ( 'design_4' === $title_design ) {
				$shape = (
				"<svg
					x='0px'
					y='0px'
					fill={$title_right_color}
					viewBox='-750.9 248.7 208.9 404.3'
				  >
					<g>
					  <path d='M-750.9,653V248.7l202.1,202.2L-750.9,653z' />
					</g>
				  </svg>"
				);
			}

			return sprintf(
				'<div class="brbl-poststicker-title %3$s">%2$s %1$s</div>',
				$title,
				$shape,
				$title_design
			);
		}
	}

	protected function render_controls() {

		if ( 'on' === $this->props['show_controls'] ) {
			return ( '<div class="brbl-poststicker-nav">
					<span data-icon="4" class="brbl-poststicker-nav-prev"></span>
					<span data-icon="5"  class="brbl-poststicker-nav-next"/></span>
				  </div>' );
		}
	}

	protected function get_data_settings() {

		$settings             = array();
		$settings['effect']   = $this->props['animation'];
		$settings['timer']    = intval( $this->props['autoplay_speed'] );
		$settings['autoplay'] = 'on' === $this->props['autoplay'] ? true : false;

		$options = sprintf( 'data-settings="%1$s"', htmlspecialchars( wp_json_encode( $settings ), ENT_QUOTES, 'UTF-8' ) );

		return $options;
	}

	public function render( $attrs, $content, $render_slug ) {

		$this->render_css( $render_slug );

		return sprintf(
			'<div class="brbl-module  brbl-poststicker">
				%1$s
				%2$s
				<div class="brbl-poststicker-box brbl-poststicker-wrap" %5$s>
					<ul class="brbl-poststicker-posts">
						%3$s
					</ul>
				</div>
				%4$s
			</div>',
			$this->render_date(),
			$this->render_title(),
			self::get_posts( $this->props ),
			$this->render_controls(),
			$this->get_data_settings()
		);
	}

	protected function render_css( $render_slug ) {

		$show_controls_onhover = $this->props['show_controls_onhover'];
		$mobile_hide_controls  = $this->props['mobile_hide_controls'];
		$mobile_hide_date      = $this->props['mobile_hide_date'];
		$mobile_hide_title     = $this->props['mobile_hide_title'];
		$arrow_color           = $this->props['arrow_color'];

		if ( 'on' === $mobile_hide_title ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-poststicker-title',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					'declaration' => 'display:none!important; visibility: hidden;',
				)
			);
		}

		if ( 'on' === $mobile_hide_date ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-poststicker-date',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					'declaration' => 'display:none!important; visibility: hidden;',
				)
			);
		}

		if ( 'on' === $mobile_hide_controls ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-poststicker-nav',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					'declaration' => 'display:none!important; visibility: hidden;',
				)
			);
		}

		if ( 'on' === $show_controls_onhover ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-poststicker-nav',
					'declaration' => 'opacity:0;transition: .3s;',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%%:hover .brbl-poststicker-nav',
					'declaration' => 'opacity:1;transition: .3s;',
				)
			);
		}

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-poststicker-nav span',
				'declaration' => 'color:' . $arrow_color . ';',
			)
		);

		$this->brbl_get_responsive_styles(
			'arrow_size',
			'%%order_class%% .brbl-poststicker-nav span',
			array(
				'primary'   => 'font-size',
				'important' => false,
			),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'parent_height',
			'%%order_class%% .brbl-poststicker',
			array(
				'primary'   => 'height',
				'important' => false,
			),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'controls_size',
			'%%order_class%% .brbl-poststicker-nav span',
			array( 'primary' => 'height' ),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'controls_size',
			'%%order_class%% .brbl-poststicker-nav span',
			array( 'primary' => 'width' ),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'spacing_between',
			'%%order_class%% .brbl-poststicker-nav-next',
			array( 'primary' => 'margin-left' ),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'date_padding',
			'%%order_class%% .brbl-poststicker-date',
			array( 'primary' => 'padding' ),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'date_spacing',
			'%%order_class%% .brbl-poststicker-date',
			array( 'primary' => 'margin-right' ),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'title_padding',
			'%%order_class%% .brbl-poststicker-title',
			array( 'primary' => 'padding' ),
			array(),
			$render_slug
		);

		$this->brbl_get_responsive_styles(
			'title_spacing',
			'%%order_class%% .brbl-poststicker-title',
			array( 'primary' => 'margin-right' ),
			array(),
			$render_slug
		);

		$this->brbl_get_custom_bg_style(
			$render_slug,
			'title',
			'%%order_class%% .brbl-poststicker-title',
			'%%order_class%%:hover .brbl-poststicker-title'
		);

		$this->brbl_get_custom_bg_style(
			$render_slug,
			'date',
			'%%order_class%% .brbl-poststicker-date',
			'%%order_class%%:hover .brbl-poststicker-date'
		);

		$this->brbl_get_custom_bg_style(
			$render_slug,
			'controls',
			'%%order_class%% .brbl-poststicker-nav span',
			'%%order_class%% .brbl-poststicker-nav span:hover'
		);

	}
}

new BRBL_PostTickerPro();
