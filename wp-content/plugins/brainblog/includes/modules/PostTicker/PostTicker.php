<?php
class BRBL_PostTicker extends BRBL_Builder_Module_Type_PostBased {

	protected $module_credits = array(
		'module_uri' => 'https://divipeople.com/plugins/brain-blog',
		'author'     => 'DiviPeople',
		'author_uri' => 'https://divipeople.com',
	);

	public function init() {

		$this->slug       = 'brbl_post_ticker';
		$this->vb_support = 'on';
		$this->name       = esc_html__( 'DP Post Ticker', 'brain-divi-blog' );
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
					'title'      => esc_html__( 'Title Style', 'brain-divi-blog' ),
					'title_text' => esc_html__( 'Title Text', 'brain-divi-blog' ),
					'text'       => esc_html__( 'Post Text', 'brain-divi-blog' ),
					'border'     => esc_html__( 'Border', 'brain-divi-blog' ),
				),
			),
		);
	}

	public function get_fields() {

		$fields = array(
			// Content.
			'use_title'          => array(
				'label'           => esc_html__( 'Use Title', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'content',
			),
			'title'              => array(
				'label'       => esc_html__( 'Title Text', 'brain-divi-blog' ),
				'type'        => 'text',
				'default'     => 'Breaking News',
				'toggle_slug' => 'content',
				'show_if'     => array(
					'use_title' => 'on',
				),
			),
			'post_type'          => array(
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
			'include_categories' => array(
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
					'__posts',
				),
				'show_if'          => array(
					'post_type' => 'post',
				),
			),
			'order_by'           => array(
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
			'order'              => array(
				'label'            => esc_html__( 'Sorted By', 'brain-divi-blog' ),
				'description'      => esc_html__( 'Choose how your Posts should be sorted.', 'brain-divi-blog' ),
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
			'post_count'         => array(
				'label'            => esc_html__( 'Post Limit', 'brain-divi-blog' ),
				'type'             => 'text',
				'default'          => '10',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__post' ),
			),
			'offset_number'      => array(
				'label'            => esc_html__( 'Post Offset Number', 'brain-divi-blog' ),
				'type'             => 'text',
				'description'      => esc_html__( 'Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'brain-divi-blog' ),
				'toggle_slug'      => 'content',
				'computed_affects' => array(
					'__posts',
				),
				'default'          => 0,
			),
			'include_posts'      => array(
				'label'            => esc_html__( 'Include posts by IDs', 'brain-divi-blog' ),
				'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Only selected posts will be included.', 'brain-divi-addons-pro' ),
				'type'             => 'text',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__posts' ),
			),
			'exclude_posts'      => array(
				'label'            => esc_html__( 'Exclude posts by IDs', 'brain-divi-blog' ),
				'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Selected Posts will be ignored.', 'brain-divi-addons-pro' ),
				'type'             => 'text',
				'toggle_slug'      => 'content',
				'computed_affects' => array( '__posts' ),
			),
			// settings.
			'title_pos'          => array(
				'label'            => esc_html__( 'Title Position', 'brain-divi-blog' ),
				'type'             => 'text_align',
				'option_category'  => 'layout',
				'options'          => et_builder_get_text_orientation_options( array( 'justified', 'center' ) ),
				'options_icon'     => 'module_align',
				'default_on_front' => 'left',
				'toggle_slug'      => 'settings',
				'show_if'          => array(
					'use_title' => 'on',
				),
			),
			'speed'              => array(
				'label'          => esc_html__( 'Moving Speed', 'brain-divi-blog' ),
				'type'           => 'range',
				'fixed_unit'     => 'ms',
				'default_unit'   => 'ms',
				'default'        => '30000ms',
				'range_settings' => array(
					'min'  => 30000,
					'step' => 1000,
					'max'  => 100000,
				),
				'toggle_slug'    => 'settings',
			),
			'slide_dir'          => array(
				'label'            => esc_html__( 'Moving Direction', 'brain-divi-blog' ),
				'type'             => 'text_align',
				'option_category'  => 'layout',
				'options'          => et_builder_get_text_orientation_options( array( 'justified', 'center' ) ),
				'options_icon'     => 'module_align',
				'default_on_front' => 'left',
				'toggle_slug'      => 'settings',
			),
			'item_spacing'       => array(
				'label'          => esc_html__( 'Item Spacing', 'brain-divi-blog' ),
				'type'           => 'range',
				'fixed_unit'     => 'px',
				'default_unit'   => 'px',
				'default'        => '20px',
				'range_settings' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 250,
				),
				'toggle_slug'    => 'settings',
			),
			'pause_on_hover'     => array(
				'label'           => esc_html__( 'Pause on Hover ', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'settings',
			),
			'use_bullet'         => array(
				'label'           => esc_html__( 'Use Bullet Before Item', 'brain-divi-blog' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
					'off' => esc_html__( 'No', 'brain-divi-blog' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'settings',
			),
			'bullet_color'       => array(
				'label'       => esc_html__( 'Bullet Color', 'brain-divi-blog' ),
				'type'        => 'color-alpha',
				'toggle_slug' => 'settings',
				'default'     => '#8a8585',
				'show_if'     => array(
					'use_bullet' => 'on',
				),
			),
			// title.
			'title_padding'      => array(
				'label'          => __( 'Padding', 'brain-divi-blog' ),
				'type'           => 'custom_padding',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'title',
				'default'        => '20px|20px|20px|20px',
				'mobile_options' => true,
			),
			'__post'             => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'BRBL_PostTicker', 'get_post' ),
				'computed_depends_on' => array(
					'post_type',
					'include_categories',
					'post_count',
					'offset_number',
					'include_posts',
					'exclude_posts',
					'order_by',
					'order',
				),
			),
		);

		$title_bg = $this->brbl_custom_background_fields( 'title', '', 'advanced', 'title', array( 'color', 'gradient', 'hover' ), array(), '#333' );

		return array_merge( $fields, $title_bg );
	}

	public function get_advanced_fields_config() {

		$advanced_fields                = [];
		$advanced_fields['text']        = [];
		$advanced_fields['text_shadow'] = [];
		$advanced_fields['fonts']       = [];

		$advanced_fields['borders']['title'] = array(
			'toggle_slug' => 'title',
			'css'         => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .brbl-post-ticker-title',
					'border_styles' => '%%order_class%% .brbl-post-ticker-title',
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

		$advanced_fields['fonts']['title'] = array(
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-ticker-title',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'title_text',
			'font_size'       => array(
				'default' => '16px',
			),
			'hide_text_align' => true,
			'line_height'     => array(
				'range_settings' => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
		);

		$advanced_fields['fonts']['text'] = array(
			'css'             => array(
				'main'      => '%%order_class%% .brbl-post-wrap li a',
				'important' => 'all',
			),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'text',
			'hide_text_align' => true,
			'font_size'       => array(
				'default' => '14px',
			),
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

	public static function get_post( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$defaults = array(
			'post_type'          => '',
			'include_categories' => '',
			'offset_number'      => '',
			'include_posts'      => '',
			'exclude_posts'      => '',
			'order_by'           => '',
			'order'              => '',
			'post_count'         => '',
		);

		$args               = wp_parse_args( $args, $defaults );
		$include_categories = $args['include_categories'];
		$post_type          = $args['post_type'];
		$order_by           = $args['order_by'];
		$order              = $args['order'];
		$post_count         = $args['post_count'];
		$offset_number      = $args['offset_number'];
		$include_posts      = $args['include_posts'];
		$exclude_posts      = $args['exclude_posts'];

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



	protected function render_title() {
		$use_title = $this->props['use_title'];
		$title     = $this->props['title'];

		if ( 'on' === $use_title ) {
			return sprintf( '<div class="brbl-post-ticker-title">%1$s</div>', $title );
		}
	}

	public function render( $attrs, $content, $render_slug ) {

		$this->render_css( $render_slug );

		return sprintf(
			'<div class="brbl-module brbl-post-ticker">
                %1$s
                <div class="brbl-post-ticker-container parent">
                    <ul class="brbl-post-wrap">
                        %2$s
                    </ul>
                </div>
            </div>',
			$this->render_title(),
			self::get_post( $this->props )
		);
	}

	protected function render_css( $render_slug ) {

		$title_pos      = $this->props['title_pos'];
		$speed          = $this->props['speed'];
		$use_bullet     = $this->props['use_bullet'];
		$slide_dir      = 'right' === $this->props['slide_dir'] ? 'reverse' : 'normal';
		$bullet_color   = $this->props['bullet_color'];
		$item_spacing   = $this->props['item_spacing'];
		$pause_on_hover = $this->props['pause_on_hover'];

		if ( 'on' === $use_bullet ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-wrap li a',
					'declaration' => 'display: inline-block; position:relative;',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-wrap li a:before',
					'declaration' => sprintf(
						'
                    content: "";
                    position: absolute;
                    height: 6px;
                    width: 6px;
                    background: %1$s;
                    top: 50%%;
                    left: -15px;
                    transform: translateY(-50%%);
                    border-radius: 50%%;',
						$bullet_color
					),
				)
			);
		}

		// title Padding.
		$this->brbl_get_responsive_styles(
			'title_padding',
			'%%order_class%% .brbl-post-ticker-title',
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
				'selector'    => '%%order_class%% .brbl-post-wrap',
				'declaration' => sprintf(
					'
                animation: %1$s linear 0s infinite %2$s none running post-move;',
					$speed,
					$slide_dir
				),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .brbl-post-wrap li',
				'declaration' => sprintf(
					'
                padding: 0 %1$s;',
					$item_spacing
				),
			)
		);

		if ( 'on' === $pause_on_hover ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%%:hover .brbl-post-wrap',
					'declaration' => '
                    -webkit-animation-play-state: paused!important;
                    animation-play-state: paused!important;',
				)
			);
		}

		if ( 'right' === $title_pos ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-ticker',
					'declaration' => 'flex-direction: row-reverse;',
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-ticker-title',
					'declaration' => 'margin-left: 10px;',
				)
			);
		} else {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .brbl-post-ticker-title',
					'declaration' => 'margin-right: 10px;',
				)
			);
		}

		// title bg.
		$this->brbl_get_custom_bg_style( $render_slug, 'title', '%%order_class%% .brbl-post-ticker-title', '%%order_class%%:hover .brbl-post-ticker-title' );

	}
}

new BRBL_PostTicker();
