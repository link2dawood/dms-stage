<?php
class BRBL_PostList extends BRBL_Builder_Module_Type_PostBased {

    protected $module_credits = [
        'module_uri' => 'https://divipeople.com/plugins/brain-blog',
        'author'     => 'DiviPeople',
        'author_uri' => 'https://divipeople.com',
    ];

    public function init() {

        $this->vb_support = 'on';
        $this->slug       = 'brbl_post_list';
        $this->name       = esc_html__( 'DP Post List', 'brain-divi-blog' );
        $this->icon_path  = plugin_dir_path( __FILE__ ) . 'post-list.svg';

        $this->settings_modal_toggles = [
            'general'  => [
                'toggles' => [
                    'query'  => esc_html__( 'Query', 'brain-divi-blog' ),
                    'layout' => esc_html__( 'Layout Settings', 'brain-divi-blog' ),
                ],
            ],
            'advanced' => [
                'toggles' => [
                    'common'     => esc_html__( 'Common', 'brain-divi-blog' ),
                    'list_style' => esc_html__( 'Post List Style', 'brain-divi-blog' ),
                    'list_icon'  => esc_html__( 'List Icon', 'brain-divi-blog' ),
                    'image'      => esc_html__( 'Thumbnail', 'brain-divi-blog' ),
                    'title'      => esc_html__( 'Post Title', 'brain-divi-blog' ),
                    'excerpt'    => esc_html__( 'Post Excerpt', 'brain-divi-blog' ),
                    'meta'       => esc_html__( 'Post Meta', 'brain-divi-blog' ),
                    'border'     => esc_html__( 'Borders', 'brain-divi-blog' ),
                ],
            ],
        ];
    }

    public function get_fields() {

        $query = [
            'post_type'            => [
                'label'            => esc_html__( 'Post Type', 'brain-divi-blog' ),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'options'          => et_get_registered_post_type_options( false, false ),
                'description'      => esc_html__( 'Choose posts of which post type you would like to display.', 'brain-divi-blog' ),
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'query',
                'default'          => 'post',
            ],

            'include_categories'   => [
                'label'            => esc_html__( 'Included Categories', 'brain-divi-blog' ),
                'type'             => 'categories',
                'option_category'  => 'basic_option',
                'meta_categories'  => [
                    'current' => esc_html__( 'Current Category', 'brain-divi-blog' ),
                ],
                'renderer_options' => [
                    'use_terms' => false,
                ],
                'description'      => esc_html__( 'Choose which categories you would like to include in the List.', 'brain-divi-blog' ),
                'toggle_slug'      => 'query',
                'computed_affects' => [
                    '__posts',
                ],
                'show_if'          => [
                    'post_type' => 'post',
                ],
            ],

            'order_by'             => [
                'label'            => esc_html__( 'Order By', 'brain-divi-blog' ),
                'description'      => esc_html__( 'Choose how your posts should be ordered.', 'brain-divi-blog' ),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'toggle_slug'      => 'query',
                'default'          => 'date',
                'options'          => [
                    'author' => esc_html__( 'Author', 'brain-divi-blog' ),
                    'date'   => esc_html__( 'Date', 'brain-divi-blog' ),
                    'ID'     => esc_html__( 'ID', 'brain-divi-blog' ),
                    'parent' => esc_html__( 'Parent', 'brain-divi-blog' ),
                    'rand'   => esc_html__( 'Random', 'brain-divi-blog' ),
                    'title'  => esc_html__( 'Title', 'brain-divi-blog' ),
                ],

                'default_on_front' => 'date',
                'computed_affects' => ['__posts'],
            ],

            'order'                => [
                'label'            => esc_html__( 'Sorted By', 'brain-divi-blog' ),
                'description'      => esc_html__( 'Choose how your posts should be sorted.', 'brain-divi-blog' ),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'toggle_slug'      => 'query',
                'default'          => 'ASC',
                'options'          => [
                    'ASC'  => esc_html__( 'Ascending', 'brain-divi-blog' ),
                    'DESC' => esc_html__( 'Descending', 'brain-divi-blog' ),
                ],

                'default_on_front' => 'ASC',
                'computed_affects' => ['__posts'],
            ],

            'posts_number'         => [
                'label'            => esc_html__( 'Post Count', 'brain-divi-blog' ),
                'type'             => 'text',
                'option_category'  => 'configuration',
                'description'      => esc_html__( 'Choose how much posts you would like to display per List.', 'brain-divi-blog' ),
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'query',
                'default'          => 6,
            ],

            'offset_number'        => [
                'label'            => esc_html__( 'Post Offset Number', 'brain-divi-blog' ),
                'type'             => 'text',
                'option_category'  => 'configuration',
                'description'      => esc_html__( 'Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'brain-divi-blog' ),
                'toggle_slug'      => 'query',
                'computed_affects' => [
                    '__posts',
                ],
                'default'          => 0,
            ],
            'include_posts'        => [
                'label'            => esc_html__( 'Include posts by IDs', 'brain-divi-blog' ),
                'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Only selected posts will be included.', 'brain-divi-addons-pro' ),
                'type'             => 'text',
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
            ],
            'exclude_current_post' => [
                'label'            => esc_html__( 'Exclude Current Post', 'brain-divi-blog' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => et_builder_i18n( 'Yes' ),
                    'off' => et_builder_i18n( 'No' ),
                ],
                'description'      => esc_html__( 'Exclude current post from the list. Useful on post page.', 'brain-divi-blog' ),
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'query',
                'default'          => 'off',
                'show_if'          => [
                    'function.isTBLayout' => 'on',
                ],
            ],
            'exclude_posts'        => [
                'label'            => esc_html__( 'Exclude posts by IDs', 'brain-divi-blog' ),
                'description'      => esc_html__( 'eg. 10, 22, 19 etc. If this is used by IDs, Selected Posts will be ignored.', 'brain-divi-addons-pro' ),
                'type'             => 'text',
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
            ],
        ];

        $layout = [
            'show_thumb'     => [
                'label'            => esc_html__( 'Show Image', 'brain-divi-blog' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
                    'off' => esc_html__( 'No', 'brain-divi-blog' ),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],
            'thumb_size'     => [
                'label'            => esc_html__( 'Image Size', 'brain-divi-blog' ),
                'description'      => esc_html__( 'Different featured image size. If you use custom, the most appropriate image size will be displayed.', 'brain-divi-blog' ),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'toggle_slug'      => 'layout',
                'default'          => 'full',
                'options'          => [
                    'thumbnail' => esc_html__( 'Thumbnail (150px x 150px)', 'brain-divi-blog' ),
                    'medium'    => esc_html__( 'Medium (300px x 300px)', 'brain-divi-blog' ),
                    'large'     => esc_html__( 'Large (1024px x 1024px)', 'brain-divi-blog' ),
                    'full'      => esc_html__( 'Full (Original Image)', 'brain-divi-blog' ),
                    'custom'    => esc_html__( 'Custom', 'brain-divi-blog' ),
                ],
                'default_on_front' => 'full',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'on',
                ],
            ],
            'thumb_width'    => [
                'label'            => esc_html__( 'Image Width', 'brain-divi-blog' ),
                'type'             => 'range',
                'default'          => '100',
                'unitless'         => true,
                'range_settings'   => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 400,
                ],
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'on',
                    'thumb_size' => 'custom',
                ],
            ],
            'thumb_height'   => [
                'label'            => esc_html__( 'Image Height', 'brain-divi-blog' ),
                'type'             => 'range',
                'default'          => '100',
                'unitless'         => true,
                'range_settings'   => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 400,
                ],
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'on',
                    'thumb_size' => 'custom',
                ],
            ],
            'show_icon'      => [
                'label'            => esc_html__( 'Show List Icon', 'brain-divi-blog' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
                    'off' => esc_html__( 'No', 'brain-divi-blog' ),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'off',
                ],
            ],

            'list_icon'      => [
                'label'            => esc_html__( 'Select List Icon', 'brain-divi-blog' ),
                'type'             => 'select_icon',
                'option_category'  => 'basic_option',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'off',
                    'show_icon'  => 'on',
                ],
            ],

            'show_excerpt'   => [
                'label'            => esc_html__( 'Show Excerpt', 'brain-divi-blog' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
                    'off' => esc_html__( 'No', 'brain-divi-blog' ),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],

            'excerpt_length' => [
                'label'            => esc_html__( 'Excerpt Length', 'brain-divi-blog' ),
                'type'             => 'text',
                'default'          => '150',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_excerpt' => 'on',
                ],
            ],

            'show_author'    => [
                'label'            => esc_html__( 'Show Author', 'brain-divi-blog' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
                    'off' => esc_html__( 'No', 'brain-divi-blog' ),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],

            'show_date'      => [
                'label'            => esc_html__( 'Show Date', 'brain-divi-blog' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__( 'Yes', 'brain-divi-blog' ),
                    'off' => esc_html__( 'No', 'brain-divi-blog' ),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],

            'date_format'    => [
                'label'            => esc_html__( 'Date Format', 'brain-divi-blog' ),
                'type'             => 'text',
                'default'          => 'M d, Y',
                'toggle_slug'      => 'layout',
                'show_if'          => [
                    'show_date' => 'on',
                ],
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_date' => 'on',
                ],
            ],

        ];

        $fields = [
            'list_type'       => [
                'label'       => esc_html__( 'List Type', 'brain-divi-blog' ),
                'type'        => 'select',
                'toggle_slug' => 'list_style',
                'tab_slug'    => 'advanced',
                'default'     => 'list',
                'options'     => [
                    'list' => esc_html__( 'List', 'brain-divi-blog' ),
                    'grid' => esc_html__( 'Grid', 'brain-divi-blog' ),
                ],
            ],

            'items'           => [
                'label'          => esc_html__( 'Posts per Row', 'brain-divi-blog' ),
                'type'           => 'select',
                'toggle_slug'    => 'list_style',
                'tab_slug'       => 'advanced',
                'default'        => '4',
                'mobile_options' => true,
                'options'        => [
                    '1' => esc_html__( '1', 'brain-divi-blog' ),
                    '2' => esc_html__( '2', 'brain-divi-blog' ),
                    '3' => esc_html__( '3', 'brain-divi-blog' ),
                    '4' => esc_html__( '4', 'brain-divi-blog' ),
                    '5' => esc_html__( '5', 'brain-divi-blog' ),
                    '6' => esc_html__( '6', 'brain-divi-blog' ),
                    '7' => esc_html__( '7', 'brain-divi-blog' ),
                    '8' => esc_html__( '8', 'brain-divi-blog' ),
                ],
                'show_if'        => [
                    'list_type' => 'grid',
                ],
            ],

            'item_spacing'    => [
                'label'          => esc_html__( 'Post Spacing', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '15px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'list_style',
                'tab_slug'       => 'advanced',
            ],

            'item_padding'    => [
                'label'          => __( 'Post Padding', 'brain-divi-blog' ),
                'type'           => 'custom_padding',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'list_style',
                'default'        => '0px|0px|0px|0px',
                'mobile_options' => true,
            ],

            'alignment'       => [
                'label'        => __( 'Content Alignment', 'brain-divi-blog' ),
                'type'         => 'text_align',
                'options'      => et_builder_get_text_orientation_options( ['justified'] ),
                'options_icon' => 'module_align',
                'default'      => 'left',
                'toggle_slug'  => 'list_style',
                'tab_slug'     => 'advanced',
            ],

            // List Icon.
            'icon_size'       => [
                'label'          => esc_html__( 'Icon Size', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '18px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'list_icon',
                'tab_slug'       => 'advanced',
                'show_if'        => [
                    'show_thumb' => 'off',
                    'show_icon'  => 'on',
                ],
            ],

            'icon_color'      => [
                'label'       => esc_html__( 'Icon Color', 'brain-divi-blog' ),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'list_icon',
                'default'     => '#555',
                'show_if'     => [
                    'show_thumb' => 'off',
                    'show_icon'  => 'on',
                ],
            ],

            'icon_spacing'    => [
                'label'          => esc_html__( 'Icon Spacing', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '20px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'list_icon',
                'tab_slug'       => 'advanced',
                'show_if'        => [
                    'show_thumb' => 'off',
                    'show_icon'  => 'on',
                ],
            ],

            // Image.
            'image_width'     => [
                'label'          => esc_html__( 'Image Width', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '60px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 300,
                ],
                'toggle_slug'    => 'image',
                'tab_slug'       => 'advanced',
                'show_if'        => [
                    'show_thumb' => 'on',
                ],
            ],

            'image_height'    => [
                'label'          => esc_html__( 'Image Height', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '60px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 300,
                ],
                'toggle_slug'    => 'image',
                'tab_slug'       => 'advanced',
                'show_if'        => [
                    'show_thumb' => 'on',
                ],
            ],

            'image_spacing'   => [
                'label'          => esc_html__( 'Image Spacing', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '12px',
                'allowed_units'  => ['px'],
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'image',
                'tab_slug'       => 'advanced',
                'show_if'        => [
                    'show_thumb' => 'on',
                ],
            ],

            // Texts.
            'meta_spacing'    => [
                'label'          => esc_html__( 'Meta Spacing Top', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '0px',
                'allowed_units'  => ['px'],
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'meta',
                'tab_slug'       => 'advanced',
            ],

            'excerpt_spacing' => [
                'label'          => esc_html__( 'Excerpt Spacing Top', 'brain-divi-blog' ),
                'type'           => 'range',
                'default'        => '0px',
                'allowed_units'  => ['px'],
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'excerpt',
                'tab_slug'       => 'advanced',
            ],

            '__posts'         => [
                'type'                => 'computed',
                'computed_callback'   => ['BRBL_PostList', 'get_post_list'],
                'computed_depends_on' => [
                    'post_type',
                    'include_categories',
                    'order_by',
                    'order',
                    'posts_number',
                    'offset_number',
                    'show_thumb',
                    'thumb_size',
                    'thumb_width',
                    'thumb_height',
                    'show_icon',
                    'list_icon',
                    'show_excerpt',
                    'excerpt_length',
                    'include_posts',
                    'exclude_posts',
                    'exclude_current_post',
                    'show_author',
                    'show_date',
                    'date_format',
                ],
            ],

        ];

        $post_bg = $this->brbl_custom_background_fields(
            'post',
            __( 'Post', 'brain-divi-blog' ),
            'advanced',
            'list_style',
            ['color', 'gradient', 'hover'],
            [],
            ''
        );

        return array_merge( $query, $layout, $fields, $post_bg );
    }

    public function get_advanced_fields_config() {

        $advanced_fields                = [];
        $advanced_fields['text']        = [];
        $advanced_fields['text_shadow'] = [];
        $advanced_fields['fonts']       = [];

        $advanced_fields['borders']['image'] = [
            'label_prefix' => esc_html__( 'Image', 'brain-divi-blog' ),
            'toggle_slug'  => 'image',
            'css'          => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-post-list figure',
                    'border_styles' => '%%order_class%% .brbl-post-list figure',
                ],
                'important' => 'all',
            ],
            'defaults'     => [
                'border_radii'  => 'on|0px|0px|0px|0px',
                'border_styles' => [
                    'width' => '0px',
                    'color' => '#333',
                    'style' => 'solid',
                ],
            ],
        ];

        $advanced_fields['borders']['post'] = [
            'label_prefix' => esc_html__( 'Post', 'brain-divi-blog' ),
            'toggle_slug'  => 'list_style',
            'css'          => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-post-list-child-inner',
                    'border_styles' => '%%order_class%% .brbl-post-list-child-inner',
                ],
                'important' => 'all',
            ],
            'defaults'     => [
                'border_radii'  => 'on|0px|0px|0px|0px',
                'border_styles' => [
                    'width' => '0px',
                    'color' => '#333',
                    'style' => 'solid',
                ],
            ],
        ];

        $advanced_fields['box_shadow']['post'] = [
            'label'       => esc_html__( 'Post Box Shadow', 'brain-divi-blog' ),
            'toggle_slug' => 'list_style',
            'css'         => [
                'main'      => '%%order_class%% .brbl-post-list-child-inner',
                'important' => 'all',
            ],
        ];

        $advanced_fields['borders']['main'] = [
            'toggle_slug' => 'border',
            'css'         => [
                'main'      => [
                    'border_radii'  => '%%order_class%%',
                    'border_styles' => '%%order_class%%',
                ],
                'important' => 'all',
            ],
            'defaults'    => [
                'border_radii'  => 'on|0px|0px|0px|0px',
                'border_styles' => [
                    'width' => '0px',
                    'color' => '#333',
                    'style' => 'solid',
                ],
            ],
        ];

        $advanced_fields['fonts']['title'] = [
            'label'           => esc_html__( 'Title', 'brain-divi-blog' ),
            'css'             => [
                'main'      => '%%order_class%% .brbl-post-list-title',
                'important' => 'all',
            ],
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'title',
            'font_size'       => [
                'default' => '20px',
            ],
            'hide_text_align' => true,
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['excerpt'] = [
            'label'           => esc_html__( 'Excerpt', 'brain-divi-blog' ),
            'css'             => [
                'main'      => '%%order_class%% .brbl-post-list-excerpt',
                'important' => 'all',
            ],
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'excerpt',
            'hide_text_align' => true,
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
            'font_size'       => [
                'default' => '14px',
            ],
        ];

        $advanced_fields['fonts']['meta'] = [
            'label'           => esc_html__( 'Meta', 'brain-divi-blog' ),
            'css'             => [
                'main'      => '%%order_class%% .brbl-post-list-meta, %%order_class%% .brbl-post-list-meta a',
                'important' => 'all',
            ],
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'meta',
            'hide_text_align' => true,
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        return $advanced_fields;
    }

    public static function get_post_list( $args = [], $conditional_tags = [], $current_page = [] ) {
        global $post;
        $defaults = [
            'post_type'            => '',
            'include_categories'   => '',
            'order_by'             => '',
            'order'                => '',
            'posts_number'         => '',
            'offset_number'        => '',
            'show_thumb'           => '',
            'thumb_size'           => '',
            'thumb_width'          => '',
            'thumb_height'         => '',
            'show_icon'            => '',
            'list_icon'            => '',
            'show_excerpt'         => '',
            'exclude_posts'        => '',
            'include_posts'        => '',
            'exclude_current_post' => '',
            'excerpt_length'       => '',
            'show_author'          => '',
            'show_date'            => '',
            'date_format'          => '',
        ];

        $args                 = wp_parse_args( $args, $defaults );
        $post_type            = $args['post_type'];
        $include_categories   = $args['include_categories'];
        $order_by             = $args['order_by'];
        $order                = $args['order'];
        $posts_number         = $args['posts_number'];
        $offset_number        = $args['offset_number'];
        $show_thumb           = $args['show_thumb'];
        $thumb_size           = $args['thumb_size'];
        $exclude_posts        = $args['exclude_posts'];
        $exclude_current_post = $args['exclude_current_post'];
        $include_posts        = $args['include_posts'];
        $thumb_width          = $args['thumb_width'];
        $thumb_height         = $args['thumb_height'];
        $show_icon            = $args['show_icon'];
        $list_icon            = esc_attr( et_pb_process_font_icon( $args['list_icon'] ) );
        $list_icon            = !empty( $list_icon ) ? $list_icon : '9';
        $show_excerpt         = $args['show_excerpt'];
        $excerpt_length       = $args['excerpt_length'];
        $show_author          = $args['show_author'];
        $show_date            = $args['show_date'];
        $date_format          = $args['date_format'];
        $post_id              = isset( $current_page['id'] ) ? (int) $current_page['id'] : 0;

        $query_args = [
            'posts_per_page' => intval( $posts_number ),
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'orderby'        => $order_by,
            'order'          => $order,
            'offset'         => intval( $offset_number ),
        ];

// Exclude posts && Exclude current post.
        if ( !empty( $exclude_posts ) ) {
            $exclude_posts = str_replace( ' ', '', $exclude_posts );
            $exclude_posts = explode( ',', $exclude_posts );
            if ( $post && 'on' === $exclude_current_post ) {
                $exclude_posts = array_merge( $exclude_posts, [$post->ID] );
            }

            $query_args['post__not_in'] = $exclude_posts;
        } else {
            if ( $post && 'on' === $exclude_current_post ) {
                $query_args['post__not_in'] = [$post->ID];
            }

        }

        if ( !empty( $include_posts ) ) {
            $include_posts          = str_replace( ' ', '', $include_posts );
            $include_posts          = explode( ',', $include_posts );
            $query_args['post__in'] = $include_posts;
        }

        if ( 'post' === $post_type ) {
            $query_args['cat'] = implode( ',', self::filter_include_categories( $include_categories, $post_id ) );
        }

        $query = new WP_Query( $query_args );

        ob_start();

        if ( $query->have_posts() ):
            while ( $query->have_posts() ):
                $query->the_post();
                include BRAIN_BLOG_DIR . 'includes/templates/list-content.php';
            endwhile;
        endif;

        $output = ob_get_clean();

        if ( !$output ) {
            $output = self::get_no_results_template();
        }

        return $output;
    }

    public function render( $attrs, $content, $render_slug ) {

        $this->render_css( $render_slug );
        brbl_inject_fa_icons( $this->props['list_icon'] );

        return sprintf(
            '<div class="brbl-module brbl-post-list type-%2$s">
                <ul class="brbl-post-list-parent">
                    %1$s
                </ul>
            </div>',
            self::get_post_list( $this->props ),
            $this->props['list_type']
        );
    }

    protected function render_css( $render_slug ) {

        $alignment                         = $this->props['alignment'];
        $list_type                         = $this->props['list_type'];
        $show_thumb                        = $this->props['show_thumb'];
        $show_icon                         = $this->props['show_icon'];
        $items                             = $this->props['items'];
        $items_tablet                      = !empty( $this->props['items_tablet'] ) ? $this->props['items_tablet'] : $items;
        $items_phone                       = !empty( $this->props['items_phone'] ) ? $this->props['items_phone'] : $items_tablet;
        $item_spacing                      = $this->props['item_spacing'];
        $item_spacing_tablet               = !empty( $this->props['item_spacing_tablet'] ) ? $this->props['item_spacing_tablet'] : $item_spacing;
        $item_spacing_phone                = !empty( $this->props['item_spacing_phone'] ) ? $this->props['item_spacing_phone'] : $item_spacing_tablet;
        $item_spacing_last_edited          = $this->props['item_spacing_last_edited'];
        $item_spacing_responsive_status    = et_pb_get_responsive_status( $item_spacing_last_edited );
        $image_spacing                     = $this->props['image_spacing'];
        $image_spacing_tablet              = $this->props['image_spacing_tablet'];
        $image_spacing_phone               = $this->props['image_spacing_phone'];
        $image_spacing_last_edited         = $this->props['image_spacing_last_edited'];
        $image_spacing_responsive_status   = et_pb_get_responsive_status( $image_spacing_last_edited );
        $icon_color                        = $this->props['icon_color'];
        $icon_size                         = $this->props['icon_size'];
        $icon_size_tablet                  = $this->props['icon_size_tablet'];
        $icon_size_phone                   = $this->props['icon_size_phone'];
        $icon_size_last_edited             = $this->props['icon_size_last_edited'];
        $icon_size_responsive_status       = et_pb_get_responsive_status( $icon_size_last_edited );
        $icon_spacing                      = $this->props['icon_spacing'];
        $icon_spacing_tablet               = $this->props['icon_spacing_tablet'];
        $icon_spacing_phone                = $this->props['icon_spacing_phone'];
        $icon_spacing_last_edited          = $this->props['icon_spacing_last_edited'];
        $icon_spacing_responsive_status    = et_pb_get_responsive_status( $icon_spacing_last_edited );
        $meta_spacing                      = $this->props['meta_spacing'];
        $meta_spacing_tablet               = $this->props['meta_spacing_tablet'];
        $meta_spacing_phone                = $this->props['meta_spacing_phone'];
        $meta_spacing_last_edited          = $this->props['meta_spacing_last_edited'];
        $meta_spacing_responsive_status    = et_pb_get_responsive_status( $meta_spacing_last_edited );
        $excerpt_spacing                   = $this->props['excerpt_spacing'];
        $excerpt_spacing_tablet            = $this->props['excerpt_spacing_tablet'];
        $excerpt_spacing_phone             = $this->props['excerpt_spacing_phone'];
        $excerpt_spacing_last_edited       = $this->props['excerpt_spacing_last_edited'];
        $excerpt_spacing_responsive_status = et_pb_get_responsive_status( $excerpt_spacing_last_edited );
        $spacing_term                      = 'bottom';

        if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) ) {
            $this->generate_styles(
                [
                    'utility_arg'    => 'icon_font_family',
                    'render_slug'    => $render_slug,
                    'base_attr_name' => 'list_icon',
                    'important'      => true,
                    'selector'       => '%%order_class%% .brbl-post-list-icon i',
                    'processor'      => [
                        'ET_Builder_Module_Helper_Style_Processor',
                        'process_extended_icon',
                    ],
                ]
            );
        }

        if ( 'left' === $alignment ) {
            $spacing_term = 'right';

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-child-inner',
                    'declaration' => 'align-items: flex-start;',
                ]
            );
        } elseif ( 'right' === $alignment ) {
            $spacing_term = 'left';
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-child-inner',
                    'declaration' => 'flex-direction: row-reverse;align-items: flex-start;',
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-meta',
                    'declaration' => 'justify-content: flex-end;',
                ]
            );
        } else {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-child-inner',
                    'declaration' => 'flex-direction: column;align-items: center;',
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-meta',
                    'declaration' => 'justify-content: center;',
                ]
            );
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-post-list-child-inner',
                'declaration' => 'text-align:' . $alignment . '!important;',
            ]
        );

        $this->brbl_get_responsive_styles(
            'item_padding',
            '%%order_class%% .brbl-post-list-child-inner',
            [
                'primary'   => 'padding',
                'important' => false,
            ],
            [],
            $render_slug
        );

        if ( 'grid' === $list_type ) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-child',
                    'declaration' => sprintf(
                        '
                    flex: 0 0 calc(100%%/%1$s);
                    max-width:calc(100%%/%1$s);
                    padding:%2$s;',
                        $items,
                        $item_spacing
                    ),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-parent',
                    'declaration' => sprintf( 'margin: -%1$s;', $item_spacing ),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-child',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                    'declaration' => sprintf(
                        '
                    flex: 0 0 calc(100%%/%1$s);
                    max-width:calc(100%%/%1$s);
                    padding:%2$s;',
                        $items_tablet,
                        $item_spacing_tablet
                    ),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-parent',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                    'declaration' => sprintf( 'margin: -%1$s;', $item_spacing_tablet ),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-child',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                    'declaration' => sprintf(
                        '
                    flex: 0 0 calc(100%%/%1$s);
                    max-width:calc(100%%/%1$s);
                    padding:%2$s;',
                        $items_phone,
                        $item_spacing_phone
                    ),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-parent',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                    'declaration' => sprintf( 'margin: -%1$s;', $item_spacing_phone ),
                ]
            );
        } elseif ( 'list' === $list_type ) {

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-child',
                    'declaration' => sprintf( 'padding-bottom:%1$s;', $item_spacing ),
                ]
            );

            if ( !empty( $item_spacing_tablet ) && $item_spacing_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-child',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                        'declaration' => sprintf( 'padding-bottom:%1$s;', $item_spacing_tablet ),
                    ]
                );
            }

            if ( !empty( $item_spacing_phone ) && $item_spacing_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-child',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                        'declaration' => sprintf( 'padding-bottom:%1$s;', $item_spacing_phone ),
                    ]
                );
            }

        }

        // Texts.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-post-list-meta',
                'declaration' => sprintf( 'padding-top:%1$s;', $meta_spacing ),
            ]
        );

        if ( !empty( $meta_spacing_tablet ) && $meta_spacing_responsive_status ) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-meta',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                    'declaration' => sprintf( 'padding-top:%1$s;', $meta_spacing_tablet ),
                ]
            );
        }

        if ( !empty( $meta_spacing_phone ) && $meta_spacing_responsive_status ) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-meta',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                    'declaration' => sprintf( 'padding-top:%1$s;', $meta_spacing_phone ),
                ]
            );
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-post-list-content p',
                'declaration' => sprintf( 'padding-top:%1$s;', $excerpt_spacing ),
            ]
        );

        if ( !empty( $excerpt_spacing_tablet ) && $excerpt_spacing_responsive_status ) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-content p',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                    'declaration' => sprintf( 'padding-top:%1$s;', $excerpt_spacing_tablet ),
                ]
            );
        }

        if ( !empty( $excerpt_spacing_phone ) && $excerpt_spacing_responsive_status ) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-content p',
                    'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                    'declaration' => sprintf( 'padding-top:%1$s;', $excerpt_spacing_phone ),
                ]
            );
        }

// Thumbnail.
        if ( 'on' === $show_thumb ) {
            $this->brbl_get_responsive_styles(
                'image_height',
                '%%order_class%% .brbl-post-list-thumb',
                [
                    'primary'   => 'height',
                    'important' => false,
                ],
                [],
                $render_slug
            );
            if ( 'center' !== $alignment ) {
                $this->brbl_get_responsive_styles(
                    'image_width',
                    '%%order_class%% .brbl-post-list-thumb',
                    [
                        'primary'   => 'flex',
                        'important' => false,
                    ],
                    [],
                    $render_slug
                );
            } else {
                $this->brbl_get_responsive_styles(
                    'image_width',
                    '%%order_class%% .brbl-post-list-thumb',
                    [
                        'primary'   => 'width',
                        'important' => false,
                    ],
                    [],
                    $render_slug
                );
            }

            $this->brbl_get_responsive_styles(
                'image_width',
                '%%order_class%% .brbl-post-list-thumb',
                [
                    'primary'   => 'max-width',
                    'important' => false,
                ],
                [],
                $render_slug
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-thumb',
                    'declaration' => sprintf( 'margin-%2$s:%1$s;', $image_spacing, $spacing_term ),
                ]
            );

            if ( !empty( $image_spacing_tablet ) && $image_spacing_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-thumb',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                        'declaration' => sprintf( 'margin-%2$s:%1$s;', $image_spacing_tablet, $spacing_term ),
                    ]
                );
            }

            if ( !empty( $image_spacing_phone ) && $image_spacing_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-thumb',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                        'declaration' => sprintf( 'margin-%2$s:%1$s;', $image_spacing_phone, $spacing_term ),
                    ]
                );
            }

        }

        if ( 'on' === $show_icon ) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-icon',
                    'declaration' => sprintf( 'font-size:%1$s;color: %2$s;', $icon_size, $icon_color ),
                ]
            );

            if ( !empty( $icon_size_tablet ) && $icon_size_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-icon',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                        'declaration' => sprintf( 'font-size:%1$s;', $icon_size_tablet ),
                    ]
                );
            }

            if ( !empty( $icon_size_phone ) && $icon_size_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-icon',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                        'declaration' => sprintf( 'font-size:%1$s;', $icon_size_phone ),
                    ]
                );
            }

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-list-icon',
                    'declaration' => sprintf( 'margin-%2$s:%1$s;', $icon_spacing, $spacing_term ),
                ]
            );

            if ( !empty( $icon_spacing_tablet ) && $icon_spacing_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-icon',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
                        'declaration' => sprintf( 'margin-%2$s:%1$s;', $icon_spacing_tablet, $spacing_term ),
                    ]
                );
            }

            if ( !empty( $icon_spacing_phone ) && $icon_spacing_responsive_status ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-list-icon',
                        'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
                        'declaration' => sprintf( 'margin-%2$s:%1$s;', $icon_spacing_phone, $spacing_term ),
                    ]
                );
            }

        }

        // Post bg.
        $this->brbl_get_custom_bg_style( $render_slug, 'post', '%%order_class%% .brbl-post-list-child a', '%%order_class%%:hover .brbl-post-list-child a' );

    }

}

new BRBL_PostList();
