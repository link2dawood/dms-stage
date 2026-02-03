<?php

use BrainBlog\Traits\Smart_Post_List;

class BRBL_Smart_Post_List extends BRBL_Builder_Module_Type_PostBased {

    use Smart_Post_List;

    protected $module_credits = [
        'module_uri' => 'https://divipeople.com/plugins/brain-blog',
        'author'     => 'DiviPeople',
        'author_uri' => 'https://divipeople.com',
    ];

    public function init() {

        $this->vb_support = 'on';
        $this->slug       = 'brbl_smart_post_list';
        $this->name       = esc_html__('DP Smart Post List', 'brain-divi-blog');
        $this->icon_path  = plugin_dir_path(__FILE__) . 'smart-post.svg';

        $this->settings_modal_toggles = [
            'general'  => [
                'toggles' => [
                    'query'         => esc_html__('Query', 'brain-divi-blog'),
                    'settings'      => esc_html__('Layout', 'brain-divi-blog'),
                    'topbar'        => esc_html__('Top Bar', 'brain-divi-blog'),
                    'featured_post' => esc_html__('Featured Post', 'brain-divi-blog'),
                    'post_list'     => esc_html__('Post List', 'brain-divi-blog'),
                ],
            ],

            'advanced' => [
                'toggles' => [
                    'general'           => esc_html__('General', 'brain-divi-blog'),
                    'topbar'            => esc_html__('Topbar', 'brain-divi-blog'),
                    'topbar_title'      => [
                        'title'             => esc_html__('Topbar Title', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'design' => [
                                'name' => esc_html__('Design', 'brain-divi-blog'),
                            ],
                            'text'   => [
                                'name' => esc_html__('Text', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'topbar_filter'     => [
                        'title'             => esc_html__('Topbar Filter', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'design' => [
                                'name' => esc_html__('Design', 'brain-divi-blog'),
                            ],
                            'text'   => [
                                'name' => esc_html__('Text', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'topbar_nav'        => esc_html__('Topbar Navigation', 'brain-divi-blog'),
                    'featured'          => esc_html__('Featured Post', 'brain-divi-blog'),
                    'featured_texts'    => [
                        'title'             => esc_html__('Featured Texts', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'title'   => [
                                'name' => esc_html__('Title', 'brain-divi-blog'),
                            ],
                            'excerpt' => [
                                'name' => esc_html__('Excerpt', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'featured_meta'     => [
                        'title'             => esc_html__('Featured Meta', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'common' => [
                                'name' => esc_html__('Common', 'brain-divi-blog'),
                            ],
                            'author' => [
                                'name' => esc_html__('Author', 'brain-divi-blog'),
                            ],
                            'date'   => [
                                'name' => esc_html__('Date', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'featured_readmore' => esc_html__('Featured Readmore', 'brain-divi-blog'),
                    'post_list'         => esc_html__('Post List', 'brain-divi-blog'),
                    'texts'             => [
                        'title'             => esc_html__('Postlist Texts', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'title'   => [
                                'name' => esc_html__('Title', 'brain-divi-blog'),
                            ],
                            'excerpt' => [
                                'name' => esc_html__('Excerpt', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'meta'              => [
                        'title'             => esc_html__('Postlist Meta', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'common' => [
                                'name' => esc_html__('Common', 'brain-divi-blog'),
                            ],
                            'author' => [
                                'name' => esc_html__('Author', 'brain-divi-blog'),
                            ],
                            'date'   => [
                                'name' => esc_html__('Date', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'border'            => esc_html__('Border', 'brain-divi-blog'),
                ],
            ],
        ];
    }

    public function get_fields() {

        $settings = [
            'show_topbar'   => [
                'label'       => esc_html__('Show Topbar', 'brain-divi-blog'),
                'type'        => 'yes_no_button',
                'options'     => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'     => 'on',
                'toggle_slug' => 'settings',
            ],
            'is_featured'   => [
                'label'            => esc_html__('First Post Featured', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'settings',
                'computed_affects' => ['__posts'],
            ],
            'columns_count' => [
                'label'            => esc_html__('Columns Count', 'brain-divi-blog'),
                'type'             => 'select',
                'toggle_slug'      => 'settings',
                'default'          => '2',
                'options'          => [
                    '0' => esc_html__('0', 'brain-divi-blog'),
                    '1' => esc_html__('1', 'brain-divi-blog'),
                    '2' => esc_html__('2', 'brain-divi-blog'),
                    '3' => esc_html__('3', 'brain-divi-blog'),
                    '4' => esc_html__('4', 'brain-divi-blog'),
                    '5' => esc_html__('5', 'brain-divi-blog'),
                    '6' => esc_html__('6', 'brain-divi-blog'),
                    '7' => esc_html__('7', 'brain-divi-blog'),
                    '8' => esc_html__('8', 'brain-divi-blog'),
                    '9' => esc_html__('9', 'brain-divi-blog'),
                ],
                'computed_affects' => ['__posts'],
                'mobile_options'   => true,
            ],
            'rows_count'    => [
                'label'            => esc_html__('Rows Count', 'brain-divi-blog'),
                'type'             => 'select',
                'toggle_slug'      => 'settings',
                'default'          => '4',
                'options'          => [
                    '1' => esc_html__('1', 'brain-divi-blog'),
                    '2' => esc_html__('2', 'brain-divi-blog'),
                    '3' => esc_html__('3', 'brain-divi-blog'),
                    '4' => esc_html__('4', 'brain-divi-blog'),
                    '5' => esc_html__('5', 'brain-divi-blog'),
                    '6' => esc_html__('6', 'brain-divi-blog'),
                    '7' => esc_html__('7', 'brain-divi-blog'),
                    '8' => esc_html__('8', 'brain-divi-blog'),
                ],
                'computed_affects' => ['__posts'],
            ],
            'columns_gap'   => [
                'label'          => esc_html__('Columns Spacing Gap', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '30px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'settings',
            ],
        ];
        $query = [
            'post_type'          => [
                'label'            => esc_html__('Post Type', 'brain-divi-blog'),
                'type'             => 'select',
                'options'          => et_get_registered_post_type_options(false, false),
                'description'      => esc_html__('Choose posts of which post type you would like to display.', 'brain-divi-blog'),
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'query',
                'default'          => 'post',
            ],
            'include_categories' => [
                'label'            => esc_html__('Included Categories', 'brain-divi-blog'),
                'type'             => 'categories',
                'default'          => 'all',
                'meta_categories'  => [
                    'current' => esc_html__('Current Category', 'brain-divi-blog'),
                ],
                'renderer_options' => [
                    'use_terms' => false,
                ],
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts', '__category'],
                'show_if'          => [
                    'post_type' => 'post',
                ],
            ],
            'order_by'           => [
                'label'            => esc_html__('Order By', 'brain-divi-blog'),
                'description'      => esc_html__('Choose how your posts should be ordered.', 'brain-divi-blog'),
                'type'             => 'select',
                'toggle_slug'      => 'query',
                'default'          => 'date',
                'options'          => [
                    'author' => esc_html__('Author', 'brain-divi-blog'),
                    'date'   => esc_html__('Date', 'brain-divi-blog'),
                    'ID'     => esc_html__('ID', 'brain-divi-blog'),
                    'parent' => esc_html__('Parent', 'brain-divi-blog'),
                    'rand'   => esc_html__('Random', 'brain-divi-blog'),
                    'title'  => esc_html__('Title', 'brain-divi-blog'),
                ],
                'default_on_front' => 'date',
                'computed_affects' => ['__posts'],
            ],
            'order'              => [
                'label'            => esc_html__('Sorted By', 'brain-divi-blog'),
                'description'      => esc_html__('Choose how your post should be sorted.', 'brain-divi-blog'),
                'type'             => 'select',
                'toggle_slug'      => 'query',
                'default'          => 'ASC',
                'options'          => [
                    'ASC'  => esc_html__('Ascending', 'brain-divi-blog'),
                    'DESC' => esc_html__('Descending', 'brain-divi-blog'),
                ],

                'default_on_front' => 'ASC',
                'computed_affects' => ['__posts'],
            ],
            'exclude_posts'      => [
                'label'            => esc_html__('Exclude posts by IDs', 'brain-divi-blog'),
                'description'      => esc_html__('eg. 10, 22, 19 etc. If this is used by IDs, Selected Posts will be ignored.', 'brain-divi-blog'),
                'type'             => 'text',
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
            ],
            'post_offset'        => [
                'label'            => esc_html__('Post Offset', 'brain-divi-blog'),
                'type'             => 'range',
                'default'          => '0',
                'unitless'         => true,
                'range_settings'   => [
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 1,
                ],
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
            ],
        ];
        $featured_post = [
            'ft_layout'         => [
                'label'            => esc_html__('Layout', 'brain-divi-blog'),
                'type'             => 'select',
                'toggle_slug'      => 'featured_post',
                'default'          => 'box',
                'options'          => [
                    'normal' => esc_html__('Normal', 'brain-divi-blog'),
                    'box'    => esc_html__('Box', 'brain-divi-blog'),
                ],
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'is_featured' => 'on',
                ],
            ],
            'ft_position'       => [
                'label'       => esc_html__('Position', 'brain-divi-blog'),
                'type'        => 'select',
                'toggle_slug' => 'featured_post',
                'default'     => 'left',
                'options'     => [
                    'top'   => esc_html__('Top', 'brain-divi-blog'),
                    'left'  => esc_html__('Left', 'brain-divi-blog'),
                    'right' => esc_html__('Right', 'brain-divi-blog'),
                ],
                'show_if'     => [
                    'is_featured' => 'on',
                ],
            ],
            'ft_img_size'       => [
                'label'            => esc_html__('Image Size', 'brain-divi-blog'),
                'description'      => esc_html__('Different featured image size.', 'brain-divi-blog'),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'toggle_slug'      => 'featured_post',
                'default'          => 'full',
                'options'          => [
                    'thumbnail' => esc_html__('Thumbnail (150px x 150px)', 'brain-divi-blog'),
                    'medium'    => esc_html__('Medium (300px x 300px)', 'brain-divi-blog'),
                    'large'     => esc_html__('Large (1024px x 1024px)', 'brain-divi-blog'),
                    'full'      => esc_html__('Full (Original Image)', 'brain-divi-blog'),
                ],
                'default_on_front' => 'full',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'on',
                ],
            ],
            'ft_img_position'   => [
                'label'       => esc_html__('Image Position', 'brain-divi-blog'),
                'type'        => 'select',
                'toggle_slug' => 'featured_post',
                'default'     => 'top',
                'options'     => [
                    'top'   => esc_html__('Top', 'brain-divi-blog'),
                    'left'  => esc_html__('Left', 'brain-divi-blog'),
                    'right' => esc_html__('Right', 'brain-divi-blog'),
                ],
                'show_if'     => [
                    'is_featured' => 'on',
                    'ft_layout'   => 'normal',
                    'ft_position' => 'top',
                ],
            ],
            'ft_show_excerpt'   => [
                'label'            => esc_html__('Show Excerpt', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'featured_post',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'is_featured' => 'on',
                ],
            ],
            'ft_excerpt_length' => [
                'label'            => esc_html__('Excerpt Length', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => '10',
                'toggle_slug'      => 'featured_post',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'ft_show_excerpt' => 'on',
                    'is_featured'     => 'on',
                ],
            ],
            'ft_show_date'      => [
                'label'            => esc_html__('Show Date', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'featured_post',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'is_featured' => 'on',
                ],
            ],
            'ft_date_format'    => [
                'label'            => esc_html__('Date Format', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => 'M d, Y',
                'toggle_slug'      => 'featured_post',
                'show_if'          => [
                    'ft_show_date' => 'on',
                    'is_featured'  => 'on',
                ],
                'computed_affects' => ['__posts'],
            ],
            'ft_show_author'    => [
                'label'            => esc_html__('Show Author', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'featured_post',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'is_featured' => 'on',
                ],
            ],
            'ft_show_btn'       => [
                'label'            => esc_html__('Show Readmore', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'featured_post',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'is_featured' => 'on',
                ],
            ],
            'btn_text'          => [
                'label'            => __('Readmore Text', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => __('Read More', 'brain-divi-blog'),
                'toggle_slug'      => 'featured_post',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'is_featured' => 'on',
                    'ft_show_btn' => 'on',
                ],
            ],
        ];
        $topbar = [
            'topbar_title'         => [
                'label'            => esc_html__('Title', 'brain-divi-blog'),
                'type'             => 'text',
                'toggle_slug'      => 'topbar',
                'default'          => 'Trending Articles',
                'computed_affects' => ['__posts'],
                'dynamic_content'  => 'text',
                'show_if'          => [
                    'show_topbar' => 'on',
                ],
            ],
            'show_filter'          => [
                'label'            => esc_html__('Show Category Filter', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'topbar',
                'computed_affects' => ['__category'],
                'show_if'          => [
                    'show_topbar' => 'on',
                ],
            ],
            'filter_categories'    => [
                'label'            => esc_html__('Select Filter Categories', 'brain-divi-blog'),
                'type'             => 'categories',
                'default'          => 'none',
                'meta_categories'  => [
                    'all' => esc_html__('All Categories', 'brain-divi-blog'),
                ],
                'renderer_options' => [
                    'use_terms' => false,
                ],
                'toggle_slug'      => 'topbar',
                'computed_affects' => ['__category'],
                'show_if'          => [
                    'show_topbar' => 'on',
                    'show_filter' => 'on',
                ],
            ],
            'show_nav'             => [
                'label'            => esc_html__('Show Navigation', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'topbar',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_topbar' => 'on',
                ],
            ],
            'flex_menu'            => [
                'label'            => esc_html__('Enable Flex Menu', 'brain-divi-blog'),
                'description'      => esc_html__('Here you can choose whether flex menu should be used.', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'topbar',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_topbar' => 'on',
                ],
            ],
            'menu_text'            => [
                'label'            => esc_html__('Menu Text', 'brain-divi-blog'),
                'description'      => esc_html__('Here you can define the menu text.', 'brain-divi-blog'),
                'type'             => 'text',
                'toggle_slug'      => 'topbar',
                'default'          => 'More',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'flex_menu' => 'on',
                ],
            ],
            'topbar_padding'       => [
                'label'          => esc_html__('Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar',
                'default'        => '0px|0px|0px|0px',
                'mobile_options' => true,
            ],
            'topbar_bg'            => [
                'label'       => esc_html__('Background Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'topbar',
            ],
            'topbar_title_padding' => [
                'label'          => esc_html__('Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_title',
                'sub_toggle'     => 'design',
                'default'        => '10px|10px|10px|10px',
                'mobile_options' => true,
            ],
            'topbar_title_bg'      => [
                'label'       => esc_html__('Background Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'default'     => '#562DD4',
                'toggle_slug' => 'topbar_title',
                'sub_toggle'  => 'design',
            ],
            'topbar_title_radius'  => [
                'label'          => esc_html__('Border Radius', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '0px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 200,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_title',
                'sub_toggle'     => 'design',
            ],
            'filter_spacing'       => [
                'label'          => esc_html__('Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 200,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_filter',
                'sub_toggle'     => 'design',
            ],
            'filter_padding'       => [
                'label'          => esc_html__('Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_filter',
                'sub_toggle'     => 'design',
                'default'        => '0px|0px|0px|0px',
                'mobile_options' => true,
            ],
            'filter_bg'            => [
                'label'       => esc_html__('Background Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'topbar_filter',
                'sub_toggle'  => 'design',
            ],
            'filter_bg_active'     => [
                'label'       => esc_html__('Active Background Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'topbar_filter',
                'sub_toggle'  => 'design',
            ],
            'filter_color_active'  => [
                'label'       => esc_html__('Active Text Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'topbar_filter',
                'sub_toggle'  => 'design',
            ],
            'filter_radius'        => [
                'label'          => esc_html__('Border Radius', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '0px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 200,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_filter',
                'sub_toggle'     => 'design',
            ],
            'nav_spacing_left'     => [
                'label'          => esc_html__('Spacing Left', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '30px',
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 200,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_nav',
            ],
            'nav_spacing'          => [
                'label'          => esc_html__('Spacing Between', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 200,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_nav',
            ],
            'nav_size'             => [
                'label'          => esc_html__('Size', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '36px',
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 200,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_nav',
            ],
            'nav_icon_size'        => [
                'label'          => esc_html__('Icon Size', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '25px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 200,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'topbar_nav',
            ],
            'nav_bg'               => [
                'label'       => esc_html__('Background Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'topbar_nav',
                'default'     => '#fff',
                'hover'       => 'tabs',
            ],
            'nav_icon_color'       => [
                'label'       => esc_html__('Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'topbar_nav',
                'hover'       => 'tabs',
            ],
        ];
        $post_list = [
            'show_excerpt'   => [
                'label'            => esc_html__('Show Excerpt', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'post_list',
                'computed_affects' => ['__posts'],
            ],

            'excerpt_length' => [
                'label'            => esc_html__('Excerpt Length', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => '10',
                'toggle_slug'      => 'post_list',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_excerpt' => 'on',
                ],
            ],
            'show_thumb'     => [
                'label'            => esc_html__('Show Thumb', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'post_list',
                'computed_affects' => ['__posts'],
            ],
            'thumb_size'     => [
                'label'            => esc_html__('Thumb Size', 'brain-divi-blog'),
                'description'      => esc_html__('Different featured image size.', 'brain-divi-blog'),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'toggle_slug'      => 'post_list',
                'default'          => 'full',
                'options'          => [
                    'thumbnail' => esc_html__('Thumbnail (150px x 150px)', 'brain-divi-blog'),
                    'medium'    => esc_html__('Medium (300px x 300px)', 'brain-divi-blog'),
                    'large'     => esc_html__('Large (1024px x 1024px)', 'brain-divi-blog'),
                    'full'      => esc_html__('Full (Original Image)', 'brain-divi-blog'),
                ],
                'default_on_front' => 'full',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'on',
                ],
            ],
            'thumb_position' => [
                'label'       => esc_html__('Thumb Position', 'brain-divi-blog'),
                'type'        => 'select',
                'toggle_slug' => 'post_list',
                'default'     => 'left',
                'options'     => [
                    'left' => esc_html__('Left', 'brain-divi-blog'),
                    'top'  => esc_html__('Top', 'brain-divi-blog'),
                ],
                'show_if'     => [
                    'show_thumb' => 'on',
                ],
            ],
            'show_date'      => [
                'label'            => esc_html__('Show Date', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'post_list',
                'computed_affects' => ['__posts'],
            ],
            'date_format'    => [
                'label'            => esc_html__('Date Format', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => 'M d, Y',
                'toggle_slug'      => 'post_list',
                'show_if'          => [
                    'show_date' => 'on',
                ],
                'computed_affects' => ['__posts'],
            ],
            'show_author'    => [
                'label'            => esc_html__('Show Author', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'post_list',
                'computed_affects' => ['__posts'],
            ],
        ];
        $design = [
            'body_padding'           => [
                'label'          => esc_html__('Body Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'default'        => '30px|0px|0px|0px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'general',
            ],
            'ft_width'               => [
                'label'          => esc_html__('Width', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '35%',
                'mobile_options' => true,
                'default_unit'   => '%',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured',
                'show_if'        => [
                    'ft_position' => ['left', 'right'],
                ],
            ],
            'ft_content_padding'     => [
                'label'          => esc_html__('Content Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured',
                'default'        => '20px|20px|20px|20px',
                'mobile_options' => true,
            ],
            'ft_spacing'             => [
                'label'          => esc_html__('Spacing Gap', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '30px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured',
            ],
            'ft_img_width'           => [
                'label'          => esc_html__('Image Width', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '50%',
                'mobile_options' => true,
                'default_unit'   => '%',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured',
                'show_if'        => [
                    'ft_position'     => 'top',
                    'ft_img_position' => ['left', 'right'],
                ],
            ],
            'ft_img_height'          => [
                'label'          => esc_html__('Image Height', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => 'auto',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured',
            ],
            'ft_img_radius'          => [
                'label'          => esc_html__('Image Border Radius', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '0px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured',
            ],
            // Content.
            'list_img_radius'        => [
                'label'          => esc_html__('Image Border Radius', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '0px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'post_list',
            ],
            'list_img_height'        => [
                'label'          => esc_html__('Image Height', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => 'auto',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'post_list',
            ],
            'list_img_width'         => [
                'label'          => esc_html__('Image Width', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '80px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'post_list',
                'show_if'        => [
                    'thumb_position' => 'left',
                ],
            ],
            'list_img_spacing'       => [
                'label'          => esc_html__('Image Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'post_list',
            ],
            'list_item_padding'      => [
                'label'          => esc_html__('Item Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'toggle_slug'    => 'post_list',
                'default'        => '0px|0px|0px|0px',
                'tab_slug'       => 'advanced',
                'mobile_options' => true,
            ],
            // Meta.
            'meta_spacing_bottom'    => [
                'label'          => esc_html__('Spacing Bottom', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '5px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'meta',
                'sub_toggle'     => 'common',
            ],
            'meta_item_spacing'      => [
                'label'          => esc_html__('Item Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '18px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'meta',
                'sub_toggle'     => 'common',
            ],
            // Featured Meta.
            'ft_meta_spacing_bottom' => [
                'label'          => esc_html__('Spacing Bottom', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '5px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured_meta',
                'sub_toggle'     => 'common',
            ],
            'ft_meta_item_spacing'   => [
                'label'          => esc_html__('Item Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '18px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured_meta',
                'sub_toggle'     => 'common',
            ],
            // Excerpt.
            'excerpt_spacing_top'    => [
                'label'          => esc_html__('Spacing Top', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'texts',
                'sub_toggle'     => 'excerpt',
            ],
            // Featured Excerpt.
            'ft_excerpt_spacing_top' => [
                'label'          => esc_html__('Spacing Top', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured_texts',
                'sub_toggle'     => 'excerpt',
            ],
            // Featured Author.
            'ft_author_icon_size'    => [
                'label'          => esc_html__('Icon Size', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '14px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'featured_meta',
                'tab_slug'       => 'advanced',
                'sub_toggle'     => 'author',
            ],
            'ft_author_icon_color'   => [
                'label'       => esc_html__('Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'featured_meta',
                'default'     => '#555',
                'sub_toggle'  => 'author',
            ],
            'ft_author_icon_spacing' => [
                'label'          => esc_html__('Icon Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '5px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured_meta',
                'sub_toggle'     => 'author',
            ],
            // Author.
            'author_icon_size'       => [
                'label'          => esc_html__('Icon Size', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '14px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'meta',
                'tab_slug'       => 'advanced',
                'sub_toggle'     => 'author',
            ],
            'author_icon_color'      => [
                'label'       => esc_html__('Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'meta',
                'default'     => '#555',
                'sub_toggle'  => 'author',
            ],
            'author_icon_spacing'    => [
                'label'          => esc_html__('Icon Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '5px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'meta',
                'sub_toggle'     => 'author',
            ],
            // Featured Date.
            'ft_date_icon_spacing'   => [
                'label'          => esc_html__('Icon Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '5px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured_meta',
                'sub_toggle'     => 'date',
            ],
            'ft_date_icon_size'      => [
                'label'          => esc_html__('Icon Size', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '14px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'featured_meta',
                'sub_toggle'     => 'date',
            ],
            'ft_date_icon_color'     => [
                'label'       => esc_html__('Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'featured_meta',
                'sub_toggle'  => 'date',
                'default'     => '#333333',
            ],
            // Post List Date.
            'date_icon_spacing'      => [
                'label'          => esc_html__('Icon Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '5px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'meta',
                'sub_toggle'     => 'date',
            ],
            'date_icon_size'         => [
                'label'          => esc_html__('Icon Size', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '14px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'meta',
                'sub_toggle'     => 'date',
            ],
            'date_icon_color'        => [
                'label'       => esc_html__('Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'meta',
                'sub_toggle'  => 'date',
                'default'     => '#333333',
            ],
            // Button.
            'btn_spacing_top'        => [
                'label'          => esc_html__('Readmore Spacing Top', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '25px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'featured_readmore',
                'tab_slug'       => 'advanced',
            ],
        ];
        $computed_fields = [
            '__posts'      => [
                'type'                => 'computed',
                'computed_callback'   => ['BRBL_Smart_Post_List', 'get_posts'],
                'computed_depends_on' => [
                    'include_categories',
                    'order_by',
                    'order',
                    'show_excerpt',
                    'show_thumb',
                    'thumb_size',
                    'is_featured',
                    'excerpt_length',
                    'show_author',
                    'show_date',
                    'date_format',
                    'post_type',
                    'exclude_posts',
                    'post_offset',
                    'columns_count',
                    'rows_count',
                    'ft_layout',
                    'ft_img_size',
                    'ft_excerpt_length',
                    'ft_show_excerpt',
                    'ft_show_date',
                    'ft_date_format',
                    'ft_show_author',
                    'ft_show_btn',
                    'btn_text',
                    'spl_btn_icon',
                    'flex_menu',
                    'menu_text',
                ],
            ],
            '__category'   => [
                'type'                => 'computed',
                'computed_callback'   => ['BRBL_Smart_Post_List', 'get_categories'],
                'computed_depends_on' => [
                    'include_categories',
                    'is_featured',
                    'post_type',
                    'exclude_posts',
                    'post_offset',
                    'filter_categories',
                ],
            ],
            '__post_count' => [
                'type'                => 'computed',
                'computed_callback'   => ['BRBL_Smart_Post_List', 'get_post_count'],
                'computed_depends_on' => [
                    'include_categories',
                    'is_featured',
                    'post_type',
                    'exclude_posts',
                ],
            ],
        ];
        $list_item_bg = $this->brbl_custom_background_fields(
            'list_item',
            'Item',
            'advanced',
            'post_list',
            ['color', 'gradient', 'hover'],
            [],
            ''
        );
        $featured_bg = $this->brbl_custom_background_fields(
            'featured',
            '',
            'advanced',
            'featured',
            ['color', 'gradient', 'hover'],
            ['ft_layout' => 'normal'],
            ''
        );
        return array_merge(
            $settings,
            $query,
            $topbar,
            $featured_post,
            $post_list,
            $design,
            $computed_fields,
            $list_item_bg,
            $featured_bg
        );
    }

    public function get_advanced_fields_config() {

        $advanced_fields                = [];
        $advanced_fields['text']        = [];
        $advanced_fields['text_shadow'] = [];
        $advanced_fields['fonts']       = [];

        $advanced_fields['margin_padding'] = [
            'css' => [
                'main'      => '%%order_class%%',
                'important' => 'all',
            ],
        ];

        $advanced_fields['button']['spl_btn'] = [
            'label'          => esc_html__('Readmore', 'brain-divi-blog'),
            'toggle_slug'    => 'featured_readmore',
            'css'            => [
                'main'      => '%%order_class%% .brbl-smart-post-btn .et_pb_button',
                'important' => true,
            ],
            'use_alignment'  => false,
            'box_shadow'     => [
                'css' => [
                    'main' => '%%order_class%% .brbl-smart-post-btn .et_pb_button',
                ],
            ],
            'borders'        => [
                'css' => [
                    'important' => 'all',
                ],
            ],
            'margin_padding' => [
                'css' => [
                    'important' => 'all',
                ],
            ],
        ];

        $advanced_fields['borders']['list_item'] = [
            'label_prefix' => esc_html__('Item', 'brain-divi-blog'),
            'toggle_slug'  => 'post_list',
            'css'          => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-smart-post-item.brbl-default',
                    'border_styles' => '%%order_class%% .brbl-smart-post-item.brbl-default',
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

        $advanced_fields['borders']['body'] = [
            'label_prefix' => esc_html__('Body', 'brain-divi-blog'),
            'toggle_slug'  => 'general',
            'css'          => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-smart-post-wrapper',
                    'border_styles' => '%%order_class%% .brbl-smart-post-wrapper',
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

        $advanced_fields['borders']['main'] = [
            'toggle_slug' => 'border',
            'css'         => [
                'main'      => [
                    'border_radii'  => '%%order_class%%',
                    'border_styles' => '%%order_class%%',
                ],
                'important' => 'all',
            ],
        ];

        $advanced_fields['borders']['nav'] = [
            'toggle_slug' => 'topbar_nav',
            'css'         => [
                'main'      => [
                    'border_radii'  => '.brbl-smart-post-filter-nav div',
                    'border_styles' => '.brbl-smart-post-filter-nav div',
                ],
                'important' => 'all',
            ],
            'defaults'    => [
                'border_radii'  => 'on|0px|0px|0px|0px',
                'border_styles' => [
                    'width' => '2px',
                    'color' => '#562DD4',
                    'style' => 'solid',
                ],
            ],
        ];

        $advanced_fields['borders']['featured'] = [
            'toggle_slug' => 'featured',
            'css'         => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-smart-post-item.brbl-featured',
                    'border_styles' => '%%order_class%% .brbl-smart-post-item.brbl-featured',
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

        $advanced_fields['borders']['topbar'] = [
            'toggle_slug' => 'topbar',
            'css'         => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-smart-post-topbar',
                    'border_styles' => '%%order_class%% .brbl-smart-post-topbar',
                ],
                'important' => 'all',
            ],
            'defaults'    => [
                'border_radii'  => 'on|0px|0px|0px|0px',
                'border_styles' => [
                    'width' => '2px',
                    'color' => '#562DD4',
                    'style' => 'solid',
                ],
            ],
        ];

        $advanced_fields['fonts']['topbar_title'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-smart-post-topbar .brbl-smart-post-title h3',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'topbar_title',
            'sub_toggle'      => 'text',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['filter'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-smart-post-filter-menu li a',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'topbar_filter',
            'sub_toggle'      => 'text',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['featured_title'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-featured .brbl-post-title a',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'featured_texts',
            'sub_toggle'      => 'title',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['featured_excerpt'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-featured .brbl-post-excerpt',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'featured_texts',
            'sub_toggle'      => 'excerpt',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['title'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-default .brbl-post-title a',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'texts',
            'sub_toggle'      => 'title',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['excerpt'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-default .brbl-post-excerpt',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'texts',
            'sub_toggle'      => 'excerpt',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['author'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-default .brbl-smart-post-author',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'meta',
            'sub_toggle'      => 'author',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['date'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-default .brbl-smart-post-date',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'meta',
            'sub_toggle'      => 'date',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['featured_author'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-featured .brbl-smart-post-author',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'featured_meta',
            'sub_toggle'      => 'author',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['featured_date'] = [
            'css'             => [
                'main'      => '%%order_class%% .brbl-featured .brbl-smart-post-date',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'featured_meta',
            'sub_toggle'      => 'date',
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

    public static function get_posts($args = [], $conditional_tags = [], $current_page = []) {

        $defaults = [
            'include_categories' => '',
            'order_by'           => '',
            'order'              => '',
            'show_excerpt'       => '',
            'excerpt_length'     => '',
            'ft_excerpt_length'  => '',
            'ft_show_excerpt'    => '',
            'ft_show_date'       => '',
            'ft_date_format'     => '',
            'ft_show_author'     => '',
            'ft_show_btn'        => '',
            'btn_text'           => '',
            'spl_btn_icon'       => '',
            'show_date'          => '',
            'date_format'        => '',
            'show_author'        => '',
            'show_thumb'         => '',
            'thumb_size'         => '',
            'is_featured'        => '',
            'post_type'          => '',
            'post_offset'        => '',
            'exclude_posts'      => '',
            'columns_count'      => '',
            'rows_count'         => '',
            'ft_layout'          => '',
            'ft_img_size'        => '',
            'spl_btn_icon'       => '',
        ];

        $args = wp_parse_args($args, $defaults);

        $include_categories = $args['include_categories'];
        $order_by           = $args['order_by'];
        $order              = $args['order'];
        $post_type          = $args['post_type'];
        $exclude_posts      = $args['exclude_posts'];
        $post_offset        = $args['post_offset'];
        $columns_count      = $args['columns_count'];
        $rows_count         = $args['rows_count'];
        $is_featured        = $args['is_featured'];
        $spl_btn_icon       = $args['spl_btn_icon'];
        $btn_icon           = esc_attr(et_pb_process_font_icon($spl_btn_icon));
        $btn_icon           = !empty($btn_icon) ? $btn_icon : '5';

        $post_count   = 0;
        $post_counter = 0;

        if ('on' === $is_featured) {
            $post_count++;
        }

        $post_count += intval($columns_count) * intval($rows_count);

        $query_args = [
            'posts_per_page' => $post_count,
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'orderby'        => $order_by,
            'order'          => $order,
            'offset'         => intval($post_offset),
        ];

        if (!empty($exclude_posts)) {
            $exclude_posts              = str_replace(' ', '', $exclude_posts);
            $exclude_posts              = explode(',', $exclude_posts);
            $query_args['post__not_in'] = $exclude_posts;
        }

        if ('post' === $post_type) {
            $post_id           = isset($current_page['id']) ? (int) $current_page['id'] : 0;
            $query_args['cat'] = implode(',', self::filter_include_categories($include_categories, $post_id));
        }

        $query = new WP_Query($query_args);

        ob_start();

        self::spl_render_posts($args, $query, $post_counter, $btn_icon);

        $output = ob_get_clean();

        if (!$output) {
            $output = self::get_no_results_template(et_core_esc_previously('h4'));
        }

        return $output;
    }

    public static function get_categories($args = [], $conditional_tags = [], $current_page = []) {

        $defaults            = ['filter_categories' => ''];
        $args                = wp_parse_args($args, $defaults);
        $filter_categories   = $args['filter_categories'];
        $filter_category_ids = explode(',', $filter_categories);

        ob_start();

        if ('none' === $filter_categories) {

            $categories = get_categories(
                [
                    'orderby' => 'name',
                    'order'   => 'ASC',
                ]
            );

            // Print All Button at the top.
            printf(
                '<li>
					<a class="brbl-filter-nav-el" data-category="all" href="#">%1$s</a>
				</li>',
                esc_html__('All', 'brain-divi-blog')
            );

            // Print all categories having at least one post.
            foreach ($categories as $category) {
                printf(
                    '<li><a class="brbl-filter-nav-el" data-category="%2$s" href="#">%1$s</a></li>',
                    esc_html($category->name),
                    esc_html($category->term_id)
                );
            }
        } else {

            // Print All Button If Selected.
            if (in_array('all', $filter_category_ids, true)) {
                printf(
                    '<li>
						<a class="brbl-filter-nav-el" data-category="all" href="#all">%1$s</a>
					</li>',
                    esc_html__('All', 'brain-divi-blog')
                );
            }

            // Print Filter Categories Except All.
            foreach ($filter_category_ids as $filter_category_id) {
                if ('all' !== $filter_category_id) {
                    printf(
                        '<li><a class="brbl-filter-nav-el" data-category="%2$s" href="#">%1$s</a></li>',
                        esc_html(get_cat_name($filter_category_id)),
                        esc_html($filter_category_id)
                    );
                }
            }
        }

        $output = ob_get_clean();

        if (!$output) {
            $output = self::get_no_results_template(et_core_esc_previously('h4'));
        }

        return $output;
    }

    public static function get_post_count($args = [], $conditional_tags = [], $current_page = []) {

        $defaults = [
            'include_categories' => '',
            'is_featured'        => '',
            'post_type'          => '',
            'exclude_posts'      => '',
        ];

        $args               = wp_parse_args($args, $defaults);
        $include_categories = $args['include_categories'];
        $post_type          = $args['post_type'];
        $exclude_posts      = $args['exclude_posts'];

        $query_args = [
            'posts_per_page' => -1,
            'post_type'      => $post_type,
            'post_status'    => 'publish',
        ];

        if (!empty($exclude_posts)) {
            $exclude_posts              = str_replace(' ', '', $exclude_posts);
            $exclude_posts              = explode(',', $exclude_posts);
            $query_args['post__not_in'] = $exclude_posts;
        }

        if ('post' === $post_type) {
            $post_id           = isset($current_page['id']) ? (int) $current_page['id'] : 0;
            $query_args['cat'] = implode(',', self::filter_include_categories($include_categories, $post_id));
        }

        $query = new WP_Query($query_args);

        return $query->post_count;
    }

    public function render_topbar($post_query_var, $nav_next_disabled, $nav_prev_disabled) {

        $show_topbar  = $this->props['show_topbar'];
        $topbar_title = $this->props['topbar_title'];
        $show_filter  = $this->props['show_filter'];
        $show_nav     = $this->props['show_nav'];
        $flex_menu    = $this->props['flex_menu'];
        $menu_text    = $this->props['menu_text'];

        if ('on' === $show_topbar) {
            $menu_html = '';
            $nav_html  = '';

            if ('on' === $flex_menu) {
                wp_enqueue_script('brbl-flex-menu');
            }

            if ('on' === $show_filter) {
                $menu_html = sprintf(
                    '<div class="brbl-smart-post-filter-menu">
						<ul class="flex-menu spl-flex-menu-%2$s" data-text="%3$s">
							%1$s
						</ul>
				  </div>',
                    self::get_categories($post_query_var),
                    $flex_menu,
                    $menu_text
                );
            }

            if ('on' === $show_nav) {
                $nav_html = sprintf(
                    '<div class="brbl-smart-post-filter-nav">
						<div class="brbl-nav-prev" %2$s></div>
						<div class="brbl-nav-next" %3$s></div>
				  </div>',
                    self::get_categories($post_query_var),
                    $nav_prev_disabled,
                    $nav_next_disabled
                );
            }

            $topbar = sprintf(
                '<div class="brbl-smart-post-topbar">
					<div class="brbl-smart-post-title">
						<h3>%1$s</h3>
					</div>

					<div class="brbl-smart-post-filter">
					  %2$s %3$s
					</div>
				</div>',
                $topbar_title,
                $menu_html,
                $nav_html
            );

            return $topbar;
        }
    }

    public function render($attrs, $content, $render_slug) {

        $this->render_post_css($render_slug);

        $include_categories = $this->props['include_categories'];
        $order_by           = $this->props['order_by'];
        $order              = $this->props['order'];
        $show_excerpt       = $this->props['show_excerpt'];
        $excerpt_length     = $this->props['excerpt_length'];
        $ft_excerpt_length  = $this->props['ft_excerpt_length'];
        $ft_show_excerpt    = $this->props['ft_show_excerpt'];
        $ft_show_date       = $this->props['ft_show_date'];
        $ft_date_format     = $this->props['ft_date_format'];
        $ft_show_author     = $this->props['ft_show_author'];
        $ft_show_btn        = $this->props['ft_show_btn'];
        $btn_text           = $this->props['btn_text'];
        $spl_btn_icon       = $this->props['spl_btn_icon'];
        $show_date          = $this->props['show_date'];
        $date_format        = $this->props['date_format'];
        $show_thumb         = $this->props['show_thumb'];
        $thumb_size         = $this->props['thumb_size'];
        $is_featured        = $this->props['is_featured'];
        $post_type          = $this->props['post_type'];
        $post_offset        = $this->props['post_offset'];
        $exclude_posts      = $this->props['exclude_posts'];
        $columns_count      = $this->props['columns_count'];
        $rows_count         = $this->props['rows_count'];
        $ft_layout          = $this->props['ft_layout'];
        $ft_img_size        = $this->props['ft_img_size'];
        $filter_categories  = $this->props['filter_categories'];
        $show_author        = $this->props['show_author'];
        $spl_btn_icon       = $this->props['spl_btn_icon'];
        $btn_icon           = esc_attr(et_pb_process_font_icon($spl_btn_icon));
        $btn_icon           = !empty($btn_icon) ? $btn_icon : '5';
        $settings           = [];

        $post_count = 0;

        if ('on' === $is_featured) {
            $post_count++;
        }

        $post_count += intval($columns_count) * intval($rows_count);

        $settings['is_featured']       = $is_featured;
        $settings['btn_icon']          = $btn_icon;
        $settings['ft_show_date']      = $ft_show_date;
        $settings['ft_date_format']    = $ft_date_format;
        $settings['show_excerpt']      = $show_excerpt;
        $settings['excerpt_length']    = $excerpt_length;
        $settings['ft_excerpt_length'] = $ft_excerpt_length;
        $settings['ft_show_excerpt']   = $ft_show_excerpt;
        $settings['ft_show_author']    = $ft_show_author;
        $settings['ft_show_btn']       = $ft_show_btn;
        $settings['btn_text']          = $btn_text;
        $settings['spl_btn_icon']      = $spl_btn_icon;
        $settings['date_format']       = $date_format;
        $settings['show_date']         = $show_date;
        $settings['show_author']       = $show_author;
        $settings['show_thumb']        = $show_thumb;
        $settings['thumb_size']        = $thumb_size;
        $settings['ft_layout']         = $ft_layout;
        $settings['ft_img_size']       = $ft_img_size;
        $settings['post_count']        = $post_count;
        $settings['post_type']         = $post_type;
        $settings['order_by']          = $order_by;
        $settings['order']             = $order;

        $settings_options = sprintf(
            'data-settings="%1$s"',
            htmlspecialchars(wp_json_encode($settings), ENT_QUOTES, 'UTF-8')
        );

        $grid_classes = [];

        array_push($grid_classes, 'brbl-smart-post-wrapper');
        array_push($grid_classes, $ft_layout);

        $total_posts       = intval(self::get_post_count($this->props));
        $nav_next_disabled = '';
        $nav_prev_disabled = 'disabled="disabled"';
        $total_showed      = $post_count + intval($post_offset);

        if ('on' === $is_featured) {
            array_push($grid_classes, 'has-featured');
        }

        if (($total_posts < $post_count) || $total_showed === $total_posts) {
            $nav_next_disabled = 'disabled="disabled"';
        }

        // remove current category from the list.
        $pattern            = '/,?current/';
        $include_categories = preg_replace($pattern, '', $include_categories);
        $pattern            = '/^,?/';
        $include_categories = preg_replace($pattern, '', $include_categories);

        $output = sprintf(
            '<div class="brbl-module brbl-smart-post frontend" %4$s
				data-total-posts="%5$s"
				data-offset-length="%6$s"
				data-post-showed="%7$s"
				data-offset="0"
				data-category="%8$s">
				%1$s
				<div class="%2$s">%3$s</div>
			</div>',
            $this->render_topbar($this->props, $nav_next_disabled, $nav_prev_disabled),
            join(' ', $grid_classes),
            self::get_posts($this->props),
            $settings_options,
            $total_posts,
            $post_count,
            $total_showed,
            $include_categories
        );

        return $output;
    }

    public function render_post_css($render_slug) {

        $show_topbar            = $this->props['show_topbar'];
        $author_icon_spacing    = $this->props['author_icon_spacing'];
        $ft_author_icon_spacing = $this->props['ft_author_icon_spacing'];
        $meta_item_spacing      = $this->props['meta_item_spacing'];
        $is_featured            = $this->props['is_featured'];
        $ft_meta_item_spacing   = $this->props['ft_meta_item_spacing'];
        $ft_author_icon_color   = $this->props['ft_author_icon_color'];
        $ft_author_icon_size    = $this->props['ft_author_icon_size'];
        $author_icon_color      = $this->props['author_icon_color'];
        $author_icon_size       = $this->props['author_icon_size'];
        $date_icon_spacing      = $this->props['date_icon_spacing'];
        $ft_date_icon_spacing   = $this->props['ft_date_icon_spacing'];
        $date_icon_size         = $this->props['date_icon_size'];
        $ft_date_icon_size      = $this->props['ft_date_icon_size'];
        $ft_date_icon_color     = $this->props['ft_date_icon_color'];
        $ft_layout              = $this->props['ft_layout'];
        $ft_position            = $this->props['ft_position'];
        $ft_img_position        = $this->props['ft_img_position'];
        $topbar_title_bg        = $this->props['topbar_title_bg'];
        $filter_bg_active       = $this->props['filter_bg_active'];
        $filter_color_active    = $this->props['filter_color_active'];
        $list_img_radius        = $this->props['list_img_radius'];
        $topbar_bg              = $this->props['topbar_bg'];
        $filter_bg              = $this->props['filter_bg'];
        $filter_radius          = $this->props['filter_radius'];
        $nav_bg                 = $this->props['nav_bg'];
        $nav_bg_hover           = $this->get_hover_value('nav_bg');
        $nav_icon_color         = $this->props['nav_icon_color'];
        $nav_icon_color_hover   = $this->get_hover_value('nav_icon_color');
        $topbar_title_radius    = $this->props['topbar_title_radius'];
        $ft_img_radius          = $this->props['ft_img_radius'];
        $date_icon_color        = $this->props['date_icon_color'];
        $ft_img_height          = $this->props['ft_img_height'];
        $thumb_position         = $this->props['thumb_position'];

        $this->brbl_get_responsive_styles(
            'body_padding',
            '%%order_class%% .brbl-smart-post-wrapper',
            ['primary' => 'padding'],
            ['default' => '30px|0px|0px|0px'],
            $render_slug
        );

        // Top bar Title.
        if ('on' === $show_topbar) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-smart-post-topbar .brbl-smart-post-title',
                    'declaration' => sprintf(
                        'background: %1$s;
						border-radius: %2$s;',
                        $topbar_title_bg,
                        $topbar_title_radius
                    ),
                ]
            );

            $this->brbl_get_responsive_styles(
                'topbar_title_padding',
                '%%order_class%% .brbl-smart-post-topbar .brbl-smart-post-title',
                ['primary' => 'padding'],
                ['default' => '10px|10px|10px|10px'],
                $render_slug
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-smart-post-topbar',
                    'declaration' => sprintf(
                        'background: %1$s;',
                        $topbar_bg
                    ),
                ]
            );

            $this->brbl_get_responsive_styles(
                'topbar_padding',
                '%%order_class%% .brbl-smart-post-topbar',
                ['primary' => 'padding'],
                ['default' => '0px|0px|0px|0px'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'filter_spacing',
                '%%order_class%% .brbl-smart-post-filter-menu li',
                ['primary' => 'margin-left'],
                ['default' => '15px'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'filter_padding',
                '%%order_class%% .brbl-smart-post-filter-menu li a',
                ['primary' => 'padding'],
                ['default' => '0px|0px|0px|0px'],
                $render_slug
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-smart-post-filter-menu li a',
                    'declaration' => sprintf(
                        'background: %1$s;
						border-radius: %2$s;',
                        $filter_bg,
                        $filter_radius
                    ),
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-smart-post-filter-menu li a.active',
                    'declaration' => sprintf(
                        'background: %1$s;
						color: %2$s!important;',
                        $filter_bg_active,
                        $filter_color_active
                    ),
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-smart-post-filter-nav div',
                    'declaration' => sprintf(
                        'color: %1$s;
						background: %2$s;',
                        $nav_icon_color,
                        $nav_bg
                    ),
                ]
            );

            if (!empty($nav_bg_hover)) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-smart-post-filter-nav div:hover',
                        'declaration' => sprintf(
                            'background: %1$s;',
                            $nav_bg_hover
                        ),
                    ]
                );
            }

            if (!empty($nav_icon_color_hover)) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-smart-post-filter-nav div:hover',
                        'declaration' => sprintf(
                            'color: %1$s;',
                            $nav_icon_color_hover
                        ),
                    ]
                );
            }

            $this->brbl_get_responsive_styles(
                'nav_spacing_left',
                '%%order_class%% .brbl-smart-post-filter-nav',
                ['primary' => 'margin-left'],
                ['default' => '30px'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'nav_spacing',
                '%%order_class%% .brbl-smart-post-filter-nav .brbl-nav-next',
                ['primary' => 'margin-left'],
                ['default' => '30px'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'nav_size',
                '%%order_class%% .brbl-smart-post-filter-nav div',
                ['primary' => 'height'],
                ['default' => '30px'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'nav_size',
                '%%order_class%% .brbl-smart-post-filter-nav div',
                ['primary' => 'width'],
                ['default' => '30px'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'nav_icon_size',
                '%%order_class%% .brbl-smart-post-filter-nav div',
                ['primary' => 'font-size'],
                ['default' => '20px'],
                $render_slug
            );
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-featured .brbl-smart-post-thumb img',
                'declaration' => sprintf(
                    'border-radius: %1$s;',
                    $ft_img_radius
                ),
            ]
        );

        if ('normal' === $ft_layout) {
            if (
                'top' === $ft_position &&
                'top' !== $ft_img_position
            ) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-featured article',
                        'declaration' => 'display: flex;',
                    ]
                );
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-featured .brbl-smart-post-content',
                        'declaration' => 'flex: 1 1;',
                    ]
                );

                $this->brbl_get_responsive_styles(
                    'ft_img_width',
                    '%%order_class%% .brbl-featured article .brbl-smart-post-thumb',
                    ['primary' => 'flex'],
                    ['default' => '50%'],
                    $render_slug
                );

                if ('right' === $ft_img_position) {
                    ET_Builder_Element::set_style(
                        $render_slug,
                        [
                            'selector'    => '%%order_class%% .brbl-featured article',
                            'declaration' => 'flex-direction: row-reverse;',
                        ]
                    );
                }
            }
        }

        if ('auto' !== $ft_img_height) {

            $this->brbl_get_responsive_styles(
                'ft_img_height',
                '%%order_class%% .brbl-featured .brbl-smart-post-thumb',
                ['primary' => 'height'],
                ['default' => 'auto'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'ft_img_height',
                '%%order_class%% .brbl-featured .brbl-smart-post-thumb img',
                ['primary' => 'height'],
                ['default' => 'auto'],
                $render_slug
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-featured article .brbl-smart-post-thumb img',
                    'declaration' => 'object-fit: cover;width: 100%%;',
                ]
            );
        }

        if ('on' === $is_featured) {
            if ('top' !== $ft_position) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-smart-post-wrapper',
                        'declaration' => 'display: flex;align-items: flex-start;',
                    ]
                );
                if ('right' === $ft_position) {
                    ET_Builder_Element::set_style(
                        $render_slug,
                        [
                            'selector'    => '%%order_class%% .brbl-smart-post-wrapper',
                            'declaration' => 'flex-direction: row-reverse',
                        ]
                    );
                }
            }

            $this->brbl_get_responsive_styles(
                'ft_width',
                '%%order_class%% .brbl-smart-post-item.brbl-featured',
                ['primary' => 'flex'],
                ['default' => '35%'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'ft_content_padding',
                '%%order_class%% .brbl-smart-post-item.brbl-featured .brbl-smart-post-content',
                ['primary' => 'padding'],
                ['default' => '20px|20px|20px|20px'],
                $render_slug
            );
        }

        // Featured Spacing.
        $ft_spacing_dir = 'bottom';

        if ('right' === $ft_position) {
            $ft_spacing_dir = 'left';
        } elseif ('left' === $ft_position) {
            $ft_spacing_dir = 'right';
        }

        $this->brbl_get_responsive_styles(
            'ft_spacing',
            '%%order_class%% .brbl-smart-post-item.brbl-featured',
            ['primary' => 'margin-' . $ft_spacing_dir],
            ['default' => '30px'],
            $render_slug
        );

        // Grid.
        $columns_count        = $this->props['columns_count'];
        $columns_count_tablet = !empty($this->props['columns_count_tablet']) ? $this->props['columns_count_tablet'] : $columns_count;
        $columns_count_phone  = !empty($this->props['columns_count_phone']) ? $this->props['columns_count_phone'] : $columns_count_tablet;
        $columns_gap          = $this->props['columns_gap'];
        $columns_gap_tablet   = !empty($this->props['columns_gap_tablet']) ? $this->props['columns_gap_tablet'] : $columns_gap;
        $columns_gap_phone    = !empty($this->props['columns_gap_phone']) ? $this->props['columns_gap_phone'] : $columns_gap_tablet;

        $parent_class = '%%order_class%% .brbl-smart-post-wrapper';

        if ('on' === $is_featured) {
            $parent_class = '%%order_class%% .brbl-smart-post-list';
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => $parent_class,
                'declaration' => sprintf(
                    'grid-column-gap: %1$s;
					grid-row-gap: %1$s;
					display: -ms-grid;
					display: grid;
					-ms-grid-columns: repeat(%2$s,1fr);
					grid-template-columns: repeat(%2$s,1fr)',
                    $columns_gap,
                    $columns_count
                ),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => $parent_class,
                'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                'declaration' => sprintf(
                    'grid-column-gap: %1$s;
					grid-row-gap: %1$s;
					display: -ms-grid;
					display: grid;
					-ms-grid-columns: repeat(%2$s,1fr);
					grid-template-columns: repeat(%2$s,1fr)',
                    $columns_gap_tablet,
                    $columns_count_tablet
                ),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => $parent_class,
                'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                'declaration' => sprintf(
                    'grid-column-gap: %1$s;
					grid-row-gap: %1$s;
					display: -ms-grid;
					display: grid;
					-ms-grid-columns: repeat(%2$s,1fr);
					grid-template-columns: repeat(%2$s,1fr)',
                    $columns_gap_phone,
                    $columns_count_phone
                ),
            ]
        );

        // Grid End.

        // Post List.
        $this->brbl_get_responsive_styles(
            'list_item_padding',
            '%%order_class%% .brbl-default .brbl-smart-post-item-inner',
            ['primary' => 'padding'],
            ['default' => '0px|0px|0px|0px'],
            $render_slug
        );
        $this->brbl_get_responsive_styles(
            'list_img_height',
            '%%order_class%% .brbl-default .brbl-smart-post-thumb',
            ['primary' => 'height'],
            ['default' => 'auto'],
            $render_slug
        );
        $this->brbl_get_responsive_styles(
            'list_img_height',
            '%%order_class%% .brbl-default .brbl-smart-post-thumb img',
            ['primary' => 'height'],
            ['default' => 'auto'],
            $render_slug
        );

        if ('left' === $thumb_position) {

            $this->brbl_get_responsive_styles(
                'list_img_width',
                '%%order_class%% .brbl-default .brbl-smart-post-thumb',
                ['primary' => 'flex'],
                ['default' => '80px'],
                $render_slug
            );

            $this->brbl_get_responsive_styles(
                'list_img_spacing',
                '%%order_class%% .brbl-default .brbl-smart-post-thumb',
                ['primary' => 'margin-right'],
                ['default' => '15px'],
                $render_slug
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-default .brbl-smart-post-item-inner',
                    'declaration' => 'display: flex;',
                ]
            );
        } else {
            $this->brbl_get_responsive_styles(
                'list_img_spacing',
                '%%order_class%% .brbl-default .brbl-smart-post-thumb',
                ['primary' => 'margin-bottom'],
                ['default' => '15px'],
                $render_slug
            );
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-default .brbl-smart-post-thumb img',
                'declaration' => sprintf('border-radius: %1$s;', $list_img_radius),
            ]
        );

        // Author.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-smart-post-author svg',
                'declaration' => sprintf(
                    '
					width: %1$s;
					margin-right: %2$s;
					fill: %3$s;
				',
                    $author_icon_size,
                    $author_icon_spacing,
                    $author_icon_color
                ),
            ]
        );

        // Featured Author.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-featured .brbl-smart-post-author svg',
                'declaration' => sprintf(
                    '
					width: %1$s;
					margin-right: %2$s;
					fill: %3$s;
				',
                    $ft_author_icon_size,
                    $ft_author_icon_spacing,
                    $ft_author_icon_color
                ),
            ]
        );

        // Date.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-smart-post-date svg',
                'declaration' => sprintf(
                    '
					width: %1$s;
					margin-right: %2$s;
					fill: %3$s;
				',
                    $date_icon_size,
                    $date_icon_spacing,
                    $date_icon_color
                ),
            ]
        );

        // Featured Date.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-featured .brbl-smart-post-date svg',
                'declaration' => sprintf(
                    '
					width: %1$s;
					margin-right: %2$s;
					fill: %3$s;
				',
                    $ft_date_icon_size,
                    $ft_date_icon_spacing,
                    $ft_date_icon_color
                ),
            ]
        );

        // Meta.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-smart-post-author',
                'declaration' => sprintf(
                    'margin-right: %1$s;',
                    $meta_item_spacing
                ),
            ]
        );
        $this->brbl_get_responsive_styles(
            'meta_spacing_bottom',
            '%%order_class%% .brbl-default .brbl-smart-post-meta',
            ['primary' => 'margin-bottom'],
            ['default' => '5px'],
            $render_slug
        );

        // Featured Meta.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-featured .brbl-smart-post-author',
                'declaration' => sprintf(
                    'margin-right: %1$s;',
                    $ft_meta_item_spacing
                ),
            ]
        );
        $this->brbl_get_responsive_styles(
            'ft_meta_spacing_bottom',
            '%%order_class%% .brbl-featured .brbl-smart-post-meta',
            ['primary' => 'margin-bottom'],
            ['default' => '5px'],
            $render_slug
        );

        // Texts.
        $this->brbl_get_responsive_styles(
            'excerpt_spacing_top',
            '%%order_class%% .brbl-post-excerpt',
            ['primary' => 'padding-top'],
            ['default' => '15px'],
            $render_slug
        );
        $this->brbl_get_responsive_styles(
            'ft_excerpt_spacing_top',
            '%%order_class%% .brbl-featured .brbl-post-excerpt',
            ['primary' => 'padding-top'],
            ['default' => '15px'],
            $render_slug
        );

        $this->brbl_get_custom_bg_style(
            $render_slug,
            'list_item',
            '%%order_class%% .brbl-default .brbl-smart-post-item-inner',
            '%%order_class%% .brbl-default:hover .brbl-smart-post-item-inner'
        );

        if ('normal' === $ft_layout) {
            $this->brbl_get_custom_bg_style(
                $render_slug,
                'featured',
                '%%order_class%% .brbl-smart-post-item.brbl-featured',
                '%%order_class%% .brbl-smart-post-item.brbl-featured:hover'
            );
        }

        // Readmore.
        $this->brbl_get_responsive_styles(
            'btn_spacing_top',
            '%%order_class%% .brbl-smart-post-btn',
            [
                'primary'   => 'padding-top',
                'important' => false,
            ],
            ['default' => '25px'],
            $render_slug
        );

        $this->brbl_get_button_styles('spl_btn', $render_slug, '%%order_class%% .brbl-smart-post-btn .et_pb_button');
    }
}

new BRBL_Smart_Post_List();
