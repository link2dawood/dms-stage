<?php

class BRBL_PostGrid extends BRBL_Builder_Module_Type_PostBased {
    protected $module_credits = [
        'module_uri' => 'https://divipeople.com/plugins/brain-blog',
        'author'     => 'DiviPeople',
        'author_uri' => 'https://divipeople.com',
    ];

    protected static $rendering = false;

    public function init() {
        $this->vb_support             = 'on';
        $this->slug                   = 'brbl_post_grid';
        $this->name                   = esc_html__('DP Post Grid', 'brain-divi-blog');
        $this->icon_path              = plugin_dir_path(__FILE__) . 'post-grid.svg';
        $this->settings_modal_toggles = [
            'general'  => [
                'toggles' => [
                    'query'  => esc_html__('Query', 'brain-divi-blog'),
                    'layout' => esc_html__('Layout Settings', 'brain-divi-blog'),
                ],
            ],

            'advanced' => [
                'toggles' => [
                    'grid_style' => esc_html__('Post Grid Style', 'brain-divi-blog'),
                    'image'      => esc_html__('Thumbnail Style', 'brain-divi-blog'),
                    'content'    => esc_html__('Content Box', 'brain-divi-blog'),
                    'title'      => esc_html__('Post Title', 'brain-divi-blog'),
                    'excerpt'    => esc_html__('Post Excerpt', 'brain-divi-blog'),
                    'category'   => [
                        'title'             => esc_html__('Category', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'general' => [
                                'name' => esc_html__('General', 'brain-divi-blog'),
                            ],
                            'text'    => [
                                'name' => esc_html__('Text', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'author'     => [
                        'title'             => esc_html__('Author', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'general' => [
                                'name' => esc_html__('General', 'brain-divi-blog'),
                            ],
                            'text'    => [
                                'name' => esc_html__('Text', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'date'       => [
                        'title'             => esc_html__('Date', 'brain-divi-blog'),
                        'tabbed_subtoggles' => true,
                        'sub_toggles'       => [
                            'general' => [
                                'name' => esc_html__('General', 'brain-divi-blog'),
                            ],
                            'text'    => [
                                'name' => esc_html__('Text', 'brain-divi-blog'),
                            ],
                        ],
                    ],
                    'button'     => esc_html__('Readmore', 'brain-divi-blog'),
                    'pagination' => esc_html__('Pagination', 'brain-divi-blog'),
                ],
            ],
        ];

        $this->custom_css_fields = [
            'title'          => [
                'label'    => esc_html__('Title', 'brain-divi-blog'),
                'selector' => '%%order_class%% .brbl-post-title a',
            ],
            'content'        => [
                'label'    => esc_html__('Body', 'brain-divi-blog'),
                'selector' => '%%order_class%% .brbl-post-excerpt',
            ],
            'author'         => [
                'label'    => esc_html__('Author', 'brain-divi-blog'),
                'selector' => '%%order_class%% .brbl-post-author',
            ],
            'pagenavi'       => [
                'label'    => esc_html__('Pagenavi', 'brain-divi-blog'),
                'selector' => '%%order_class%% .brbl-pagination',
            ],
            'featured_image' => [
                'label'    => esc_html__('Image', 'brain-divi-blog'),
                'selector' => '%%order_class%% .brbl-post-thumb img',
            ],
            'read_more'      => [
                'label'    => esc_html__('Read More Button', 'brain-divi-blog'),
                'selector' => '%%order_class%% a.brbl-post-btn',
            ],
        ];
    }

    public function get_fields() {
        $query = [
            'use_current_loop'   => [
                'label'            => esc_html__('Posts For Current Page', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => et_builder_i18n('Yes'),
                    'off' => et_builder_i18n('No'),
                ],
                'description'      => esc_html__('Display posts for the current page. Useful on archive and index pages.', 'brain-divi-blog'),
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'query',
                'default'          => 'off',
                'show_if'          => [
                    'function.isTBLayout' => 'on',
                ],
            ],
            'post_type'          => [
                'label'            => esc_html__('Post Type', 'brain-divi-blog'),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'options'          => brbl_get_posttype(),
                'description'      => esc_html__('Choose posts of which post type you would like to display.', 'brain-divi-blog'),
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'query',
                'default'          => 'post',
                'show_if'          => [
                    'use_current_loop' => 'off',
                ],
            ],
            'include_categories' => [
                'label'            => esc_html__('Included Categories', 'brain-divi-blog'),
                'type'             => 'categories',
                'meta_categories'  => [
                    'current' => esc_html__('Current Category', 'brain-divi-blog'),
                ],
                'renderer_options' => [
                    'use_terms' => false,
                ],
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'post_type'        => 'post',
                    'use_current_loop' => 'off',
                ],
            ],
            'order_by'           => [
                'label'            => esc_html__('Order By', 'brain-divi-blog'),
                'description'      => esc_html__('Choose how your posts should be ordered.', 'brain-divi-blog'),
                'type'             => 'select',
                'option_category'  => 'configuration',
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
                'description'      => esc_html__('Choose how your posts should be sorted.', 'brain-divi-blog'),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'toggle_slug'      => 'query',
                'default'          => 'ASC',
                'options'          => [
                    'ASC'  => esc_html__('Ascending', 'brain-divi-blog'),
                    'DESC' => esc_html__('Descending', 'brain-divi-blog'),
                ],

                'default_on_front' => 'ASC',
                'computed_affects' => ['__posts'],
            ],
            'post_count'         => [
                'label'            => esc_html__('Post Limit', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => '6',
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
            ],
            'date_format'        => [
                'label'            => esc_html__('Date Format', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => 'M d, Y',
                'toggle_slug'      => 'query',
                'show_if'          => [
                    'show_date' => 'on',
                ],
                'computed_affects' => ['__posts'],
            ],
            'offset_number'      => [
                'label'            => esc_html__('Post Offset Number', 'brain-divi-blog'),
                'type'             => 'text',
                'option_category'  => 'configuration',
                'description'      => esc_html__('Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'brain-divi-blog'),
                'toggle_slug'      => 'query',
                'computed_affects' => [
                    '__posts',
                ],
                'default'          => 0,
            ],
            'include_posts'      => [
                'label'            => esc_html__('Include posts by IDs', 'brain-divi-blog'),
                'description'      => esc_html__('eg. 10, 22, 19 etc. If this is used by IDs, Only selected posts will be included.', 'brain-divi-addons-pro'),
                'type'             => 'text',
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
            ],
            'exclude_posts'      => [
                'label'            => esc_html__('Exclude posts by IDs', 'brain-divi-blog'),
                'description'      => esc_html__('eg. 10, 22, 19 etc. If this is used by IDs, Selected Posts will be ignored.', 'brain-divi-addons-pro'),
                'type'             => 'text',
                'toggle_slug'      => 'query',
                'computed_affects' => ['__posts'],
            ],
        ];

        $layout_settings = [
            'layout'              => [
                'label'            => esc_html__('Template Layout', 'brain-divi-blog'),
                'type'             => 'select',
                'toggle_slug'      => 'layout',
                'default'          => 'layout4',
                'computed_affects' => ['__posts'],
                'options'          => [
                    'layout1' => esc_html__('Layout 1', 'brain-divi-blog'),
                    'layout2' => esc_html__('Layout 2', 'brain-divi-blog'),
                    'layout3' => esc_html__('Layout 3', 'brain-divi-blog'),
                    'layout4' => esc_html__('Layout 4', 'brain-divi-blog'),
                ],
            ],
            'column_count'        => [
                'label'          => __('Numbers of Column', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '3',
                'unitless'       => true,
                'mobile_options' => true,
                'range_settings' => [
                    'step' => 1,
                    'min'  => 1,
                    'max'  => 10,
                ],
                'toggle_slug'    => 'layout',
            ],
            'column_gap_x'        => [
                'label'          => __('Column Gap Left/Right', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '20px',
                'mobile_options' => true,
                'range_settings' => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'layout',
            ],
            'column_gap_y'        => [
                'label'          => __('Column Gap Top/Bottom', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '20px',
                'mobile_options' => true,
                'range_settings' => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'layout',
            ],
            'is_equal_height'     => [
                'label'           => esc_html__('Equal Height', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'description'     => esc_html__('Enable this to Equalize Grid items with same height.', 'brain-divi-blog'),
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'         => 'on',
                'toggle_slug'     => 'layout',
            ],
            'show_avatar'         => [
                'label'           => esc_html__('Show Avatar', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'         => 'on',
                'toggle_slug'     => 'layout',
                'show_if'         => [
                    'layout' => 'layout4',
                ],
            ],
            'show_thumb'          => [
                'label'            => esc_html__('Show Image', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if_not'      => [
                    'layout' => 'layout3',
                ],
            ],
            'thumb_size'          => [
                'label'            => esc_html__('Image Size', 'brain-divi-blog'),
                'description'      => esc_html__('Different featured image size. If you use custom, the most appropriate image size will be displayed.', 'brain-divi-blog'),
                'type'             => 'select',
                'option_category'  => 'configuration',
                'toggle_slug'      => 'layout',
                'default'          => 'full',
                'options'          => [
                    'thumbnail' => esc_html__('Thumbnail (150px x 150px)', 'brain-divi-blog'),
                    'medium'    => esc_html__('Medium (300px x 300px)', 'brain-divi-blog'),
                    'large'     => esc_html__('Large (1024px x 1024px)', 'brain-divi-blog'),
                    'full'      => esc_html__('Full (Original Image)', 'brain-divi-blog'),
                    'custom'    => esc_html__('Custom', 'brain-divi-blog'),
                ],
                'default_on_front' => 'full',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_thumb' => 'on',
                ],
            ],
            'thumb_width'         => [
                'label'            => esc_html__('Image Width', 'brain-divi-blog'),
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
            'thumb_height'        => [
                'label'            => esc_html__('Image Height', 'brain-divi-blog'),
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
            'show_title'          => [
                'label'            => esc_html__('Show Title', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],
            'show_excerpt'        => [
                'label'            => esc_html__('Show Excerpt', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],
            'content_length'      => [
                'label'            => esc_html__('Excerpt Length', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => '150',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_excerpt' => 'on',
                ],
            ],
            'show_categories'     => [
                'label'            => esc_html__('Show Categories', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],
            'show_first_category' => [
                'label'            => esc_html__('Show Single Category', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'show_categories' => 'on',
                ],
            ],
            'show_author'         => [
                'label'            => esc_html__('Show Author', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],
            'show_date'           => [
                'label'            => esc_html__('Show Date', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'on',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
            ],
            'show_btn'            => [
                'label'            => esc_html__('Show Readmore', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'          => 'off',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if_not'      => [
                    'layout' => 'layout4',
                ],
            ],
            'button_text'         => [
                'label'            => __('Readmore Text', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => 'Read More',
                'toggle_slug'      => 'layout',
                'computed_affects' => ['__posts'],
                'show_if'          => [
                    'layout'   => ['layout1', 'layout2', 'layout3'],
                    'show_btn' => 'on',
                ],
            ],
        ];

        $post_options = [
            'post_padding'           => [
                'label'          => __('Post Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'default'        => '0px|0px|0px|0px',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'grid_style',
                'mobile_options' => true,
            ],
            'content_alignment'      => [
                'label'        => __('Alignment', 'brain-divi-blog'),
                'type'         => 'text_align',
                'options'      => et_builder_get_text_orientation_options(['justified']),
                'options_icon' => 'module_align',
                'default'      => 'left',
                'toggle_slug'  => 'content',
                'tab_slug'     => 'advanced',
            ],
            'content_padding'        => [
                'label'          => esc_html__('Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'toggle_slug'    => 'content',
                'default'        => '30px|30px|30px|30px',
                'tab_slug'       => 'advanced',
                'mobile_options' => true,
            ],
            'content_width'          => [
                'label'          => esc_html__('Width', 'brain-divi-blog'),
                'type'           => 'range',
                'allowed_units'  => ['px', '%'],
                'default_unit'   => '%',
                'range_settings' => [
                    'min'  => 1,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'content',
                'tab_slug'       => 'advanced',
                'mobile_options' => true,
                'show_if'        => [
                    'layout' => ['layout3', 'layout2'],
                ],
            ],
            'content_placement_x'    => [
                'label'       => esc_html__('Horizontal Placement', 'brain-divi-blog'),
                'type'        => 'select',
                'toggle_slug' => 'content',
                'tab_slug'    => 'advanced',
                'default'     => 'center',
                'options'     => [
                    'left'   => esc_html__('Left', 'brain-divi-blog'),
                    'center' => esc_html__('Center', 'brain-divi-blog'),
                    'right'  => esc_html__('Right', 'brain-divi-blog'),
                ],
                'show_if'     => [
                    'layout' => 'layout3',
                ],
            ],
            'content_placement_y'    => [
                'label'       => esc_html__('Vertical Placement', 'brain-divi-blog'),
                'type'        => 'select',
                'toggle_slug' => 'content',
                'tab_slug'    => 'advanced',
                'default'     => 'center',
                'options'     => [
                    'top'    => esc_html__('Top', 'brain-divi-blog'),
                    'center' => esc_html__('Center', 'brain-divi-blog'),
                    'bottom' => esc_html__('Bottom', 'brain-divi-blog'),
                ],
                'show_if'     => [
                    'layout' => 'layout3',
                ],
            ],
            'content_offset_y'       => [
                'label'          => esc_html__('Offset Y', 'brain-divi-blog'),
                'type'           => 'range',
                'allowed_units'  => ['px', 'em', 'rem'],
                'default_unit'   => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => -200,
                    'step' => 1,
                    'max'  => 200,
                ],
                'toggle_slug'    => 'content',
                'tab_slug'       => 'advanced',
                'show_if'        => [
                    'layout' => ['layout1', 'layout2'],
                ],
            ],
            'content_offset_x'       => [
                'label'          => esc_html__('Offset X', 'brain-divi-blog'),
                'type'           => 'range',
                'fixed_unit'     => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => -500,
                    'step' => 1,
                    'max'  => 500,
                ],
                'toggle_slug'    => 'content',
                'tab_slug'       => 'advanced',
                'show_if'        => [
                    'layout' => 'layout1',
                ],
            ],
            'content_placement'      => [
                'label'        => __('Placement', 'brain-divi-blog'),
                'type'         => 'text_align',
                'options'      => et_builder_get_text_orientation_options(['justified']),
                'options_icon' => 'module_align',
                'default'      => 'center',
                'toggle_slug'  => 'content',
                'tab_slug'     => 'advanced',
                'show_if'      => [
                    'layout' => 'layout2',
                ],
            ],
            'image_width'            => [
                'label'          => esc_html__('Image Width', 'brain-divi-blog'),
                'type'           => 'range',
                'fixed_unit'     => '%',
                'range_settings' => [
                    'min'  => 1,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'image',
                'tab_slug'       => 'advanced',
                'mobile_options' => true,
            ],
            'image_height'           => [
                'label'          => esc_html__('Image Height', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => 'auto',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 1,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'toggle_slug'    => 'image',
                'tab_slug'       => 'advanced',
                'mobile_options' => true,
            ],
            'image_size'             => [
                'label'       => esc_html__('Image Size', 'brain-divi-blog'),
                'type'        => 'select',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'image',
                'default'     => 'cover',
                'options'     => [
                    'actual'  => esc_html__('Actual Size', 'brain-divi-blog'),
                    'contain' => esc_html__('Contain', 'brain-divi-blog'),
                    'cover'   => esc_html__('Fit', 'brain-divi-blog'),
                ],
            ],
            'img_hover_style'        => [
                'label'       => esc_html__('Hover Animation', 'brain-divi-blog'),
                'type'        => 'select',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'image',
                'default'     => 'none',
                'options'     => [
                    'none'     => esc_html__('None', 'brain-divi-blog'),
                    'zoon_in'  => esc_html__('Zoom In', 'brain-divi-blog'),
                    'zoon_out' => esc_html__('Zoom Out', 'brain-divi-blog'),
                    'fade'     => esc_html__('Fade', 'brain-divi-blog'),
                ],
            ],
            'desktop_only_img_hover' => [
                'label'       => esc_html__('Disable Hover Animation on Tablet and Phone', 'brain-divi-blog'),
                'type'        => 'yes_no_button',
                'options'     => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'     => 'off',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'image',
                'show_if_not' => [
                    'img_hover_style' => 'none',
                ],
            ],
            'img_masking_shape'      => [
                'label'            => esc_html__('Masking Shape', 'brain-divi-blog'),
                'type'             => 'select',
                'tab_slug'         => 'advanced',
                'toggle_slug'      => 'image',
                'default'          => 'none',
                'computed_affects' => ['__posts'],
                'options'          => [
                    'none'    => esc_html__('None', 'brain-divi-blog'),
                    'shape_1' => esc_html__('Shape 1', 'brain-divi-blog'),
                    'shape_2' => esc_html__('Shape 2', 'brain-divi-blog'),
                    'shape_3' => esc_html__('Shape 3', 'brain-divi-blog'),
                    'shape_4' => esc_html__('Shape 4', 'brain-divi-blog'),
                    'shape_5' => esc_html__('Shape 5', 'brain-divi-blog'),
                    'shape_6' => esc_html__('Shape 6', 'brain-divi-blog'),
                ],
                'show_if'          => [
                    'layout' => 'layout2',
                ],
            ],
            'masking_shape_color'    => [
                'label'       => esc_html__('Masking Shape Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'image',
                'default'     => '#ffffff',
                'show_if'     => [
                    'layout' => 'layout2',
                ],
            ],
            'date_spacing'           => [
                'label'          => esc_html__('Date Icon Spacing', 'brain-divi-blog'),
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
                'toggle_slug'    => 'date',
                'sub_toggle'     => 'general',
                'show_if'        => [
                    'layout' => ['layout3', 'layout2', 'layout4'],
                ],
            ],
            'date_icon_size'         => [
                'label'          => esc_html__('Date Icon Size', 'brain-divi-blog'),
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
                'toggle_slug'    => 'date',
                'sub_toggle'     => 'general',
                'show_if'        => [
                    'layout' => ['layout3', 'layout2', 'layout4'],
                ],
            ],
            'date_icon_color'        => [
                'label'       => esc_html__('Date Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'date',
                'sub_toggle'  => 'general',
                'default'     => '#555',
                'show_if'     => [
                    'layout' => ['layout3', 'layout2', 'layout4'],
                ],
            ],
            'date_spacing_top'       => [
                'label'          => esc_html__('Date Spacing Top', 'brain-divi-blog'),
                'type'           => 'range',
                'mobile_options' => true,
                'default'        => '15px',
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'date',
                'tab_slug'       => 'advanced',
                'sub_toggle'     => 'general',
                'show_if'        => [
                    'layout' => 'layout1',
                ],
            ],
            'title_spacing_top'      => [
                'label'          => esc_html__('Title Spacing Top', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'title',
                'tab_slug'       => 'advanced',
            ],
            'title_spacing_bottom'   => [
                'label'          => esc_html__('Title Spacing Bottom', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
                'mobile_options' => true,
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'title',
                'tab_slug'       => 'advanced',
            ],
            'excerpt_spacing_top'    => [
                'label'          => esc_html__('Excerpt Spacing Top', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '15px',
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
            'excerpt_spacing_bottom' => [
                'label'          => esc_html__('Excerpt Spacing Bottom', 'brain-divi-blog'),
                'type'           => 'range',
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
                'toggle_slug'    => 'button',
                'tab_slug'       => 'advanced',
            ],
            'author_img_size'        => [
                'label'          => esc_html__('Author Photo Size', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '26px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'author',
                'tab_slug'       => 'advanced',
                'sub_toggle'     => 'general',
                'show_if'        => [
                    'layout' => 'layout1',
                ],
            ],
            'author_spacing'         => [
                'label'          => esc_html__('Author Photo/Icon Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '5px',
                'allowed_units'  => ['px'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'author',
                'tab_slug'       => 'advanced',
                'sub_toggle'     => 'general',
            ],
            'author_img_radius'      => [
                'label'          => esc_html__('Author Photo Radius', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '30px',
                'allowed_units'  => ['px', '%'],
                'default_unit'   => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'    => 'author',
                'tab_slug'       => 'advanced',
                'sub_toggle'     => 'general',
                'show_if'        => [
                    'layout' => 'layout1',
                ],
            ],
            'author_icon_size'       => [
                'label'           => esc_html__('Author Icon Size', 'brain-divi-blog'),
                'type'            => 'range',
                'default'         => '14px',
                'option_category' => 'basic_option',
                'allowed_units'   => ['px'],
                'default_unit'    => 'px',
                'range_settings'  => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 100,
                ],
                'toggle_slug'     => 'author',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'general',
                'show_if_not'     => [
                    'layout' => 'layout1',
                ],
            ],
            'author_icon_color'      => [
                'label'       => esc_html__('Author Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'author',
                'sub_toggle'  => 'general',
                'default'     => '#555',
                'show_if_not' => [
                    'layout' => 'layout1',
                ],
            ],
            'category_placement'     => [
                'label'       => esc_html__('Category Placement', 'brain-divi-blog'),
                'type'        => 'select',
                'tab_slug'    => 'advanced',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'category',
                'sub_toggle'  => 'general',
                'default'     => 'top_left',
                'options'     => [
                    'top_left'     => esc_html__('Top - Left', 'brain-divi-blog'),
                    'top_right'    => esc_html__('Top - Right', 'brain-divi-blog'),
                    'bottom_left'  => esc_html__('Bottom - Left', 'brain-divi-blog'),
                    'bottom_right' => esc_html__('Bottom - Right', 'brain-divi-blog'),
                ],
                'show_if_not' => [
                    'layout' => 'layout3',
                ],
            ],
            'category_offset'        => [
                'label'          => esc_html__('Category Offset', 'brain-divi-blog'),
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
                'toggle_slug'    => 'category',
                'sub_toggle'     => 'general',
                'show_if_not'    => [
                    'layout' => 'layout3',
                ],
            ],
            'category_bg'            => [
                'label'       => esc_html__('Category Background', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'category',
                'sub_toggle'  => 'general',
                'default'     => '#FF2851',
            ],
            'category_padding'       => [
                'label'          => esc_html__('Category Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'category',
                'sub_toggle'     => 'general',
                'mobile_options' => true,
                'default'        => '3px|7px|3px|7px',
            ],
            'category_radius'        => [
                'label'       => esc_html__('Category Border Radius', 'brain-divi-blog'),
                'type'        => 'border-radius',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'general',
                'toggle_slug' => 'category',
                'default'     => 'off|3px|3px|3px|3px',
            ],
            '__posts'                => [
                'type'                => 'computed',
                'computed_callback'   => ['BRBL_PostGrid', 'get_posts'],
                'computed_depends_on' => [
                    'layout',
                    'include_categories',
                    'order_by',
                    'order',
                    'button_icon',
                    'post_count',
                    'content_length',
                    'date_format',
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
                    'pagination_type',
                    'pagination_query_type',
                    'pagination_showall',
                    'pagination_prev_label',
                    'pagination_next_label',
                    'loadmore_type',
                    'loadmore_text',
                    'offset_number',
                    'include_posts',
                    'exclude_posts',
                    'ico',
                    'title_level',
                    'overlay_icon',
                    'post_type',
                    'img_masking_shape',
                    'use_current_loop',
                    '__page',
                ],
            ],
            '__page'                 => [
                'type'              => 'computed',
                'computed_callback' => ['BRBL_PostGrid', 'get_posts'],
                'computed_affects'  => [
                    '__posts',
                ],
            ],
        ];

        $pagination = [

            'pagination_gap'             => [
                'label'          => __('Container Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'default'        => '20px|0px|0px|0px',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
                'mobile_options' => true,
            ],
            'pagination_type'            => [
                'label'            => esc_html__('Pagination', 'brain-divi-blog'),
                'type'             => 'select',
                'default'          => '',
                'options'          => [
                    ''          => esc_html__('None', 'brain-divi-blog'),
                    'numbers'   => esc_html__('Numbers', 'brain-divi-blog'),
                    'prev_next' => esc_html__('Previous/Next', 'brain-divi-blog'),
                    'loadmore'  => esc_html__('Loadmore', 'brain-divi-blog'),
                ],
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'layout',
            ],
            'pagination_query_type'      => [
                'label'       => esc_html__('Query Type', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => '',
                'options'     => [
                    'normal' => esc_html__('Normal', 'brain-divi-blog'),
                    'ajax'   => esc_html__('Ajax', 'brain-divi-blog'),
                ],
                'toggle_slug' => 'layout',
                'show_if'     => [
                    'pagination_type' => 'numbers',
                ],
            ],
            'pagination_showall'         => [
                'label'            => esc_html__('Show All', 'brain-divi-blog'),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'computed_affects' => [
                    '__posts',
                ],
                'default'          => 'off',
                'show_if'          => [
                    'pagination_type' => 'numbers',
                ],
                'toggle_slug'      => 'layout',
            ],
            'pagination_prev_label'      => [
                'label'            => esc_html__('Previous Label', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => esc_html__('&laquo; Previous', 'brain-divi-blog'),
                'computed_affects' => [
                    '__posts',
                ],
                'show_if'          => [
                    'pagination_type' => 'prev_next',
                ],
                'toggle_slug'      => 'layout',
            ],
            'pagination_next_label'      => [
                'label'            => esc_html__('Next Label', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => esc_html__('Next &raquo;', 'brain-divi-blog'),
                'show_if'          => [
                    'pagination_type' => 'prev_next',
                ],
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'layout',
            ],
            'loadmore_type'              => [
                'label'            => esc_html__('Loadmore Type', 'brain-divi-blog'),
                'type'             => 'select',
                'default'          => 'scroll',
                'options'          => [
                    'scroll' => esc_html__('Scroll', 'brain-divi-blog'),
                    'button' => esc_html__('Button', 'brain-divi-blog'),
                ],
                'show_if'          => [
                    'pagination_type' => 'loadmore',
                ],
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'layout',
            ],
            'loadmore_text'              => [
                'label'            => esc_html__('Load More Text', 'brain-divi-blog'),
                'type'             => 'text',
                'default'          => esc_html__('Load More', 'brain-divi-blog'),
                'show_if'          => [
                    'pagination_type' => 'loadmore',
                    'loadmore_type'   => 'button',
                ],
                'computed_affects' => [
                    '__posts',
                ],
                'toggle_slug'      => 'layout',
            ],
            'pagination_alignment'       => [
                'label'          => __('Alignment', 'brain-divi-blog'),
                'type'           => 'text_align',
                'options'        => et_builder_get_text_orientation_options(['justified']),
                'options_icon'   => 'module_align',
                'default'        => 'center',
                'mobile_options' => true,
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
            ],
            'pagination_padding'         => [
                'label'          => __('Padding', 'brain-divi-blog'),
                'type'           => 'custom_padding',
                'default'        => '6px|15px|6px|15px',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
                'mobile_options' => true,
            ],
            'pagination_color'           => [
                'label'          => esc_html__('Color', 'brain-divi-blog'),
                'type'           => 'color-alpha',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
                'mobile_options' => true,
            ],
            'pagination_bg_color'        => [
                'label'          => esc_html__('Background', 'brain-divi-blog'),
                'type'           => 'color-alpha',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
                'mobile_options' => true,
            ],
            'pagination_active_color'    => [
                'label'          => esc_html__('Active Color', 'brain-divi-blog'),
                'type'           => 'color-alpha',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
                'show_if'        => [
                    'pagination_type' => 'numbers',
                ],
                'mobile_options' => true,
            ],
            'pagination_active_bg_color' => [
                'label'          => esc_html__('Active Background', 'brain-divi-blog'),
                'type'           => 'color-alpha',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
                'show_if'        => [
                    'pagination_type' => 'numbers',
                ],
                'mobile_options' => true,
            ],
            'loading_dot_color'          => [
                'label'          => esc_html__('Loading Dot', 'brain-divi-blog'),
                'type'           => 'color-alpha',
                'tab_slug'       => 'advanced',
                'toggle_slug'    => 'pagination',
                'mobile_options' => true,
            ],
        ];

        $post_bg = $this->brbl_custom_background_fields(
            'post',
            'Post',
            'advanced',
            'grid_style',
            ['color', 'gradient', 'image'],
            [],
            ''
        );

        $content_bg = $this->brbl_custom_background_fields(
            'content',
            'Content',
            'advanced',
            'content',
            ['color', 'gradient', 'image'],
            [],
            '#ffffff'
        );

        $overlay = $this->brbl_get_overlay_option_fields('image', []);

        return array_merge($query, $post_options, $layout_settings, $pagination, $overlay, $post_bg, $content_bg);
    }

    public function get_advanced_fields_config() {
        $advanced_fields                = [];
        $advanced_fields['text']        = [];
        $advanced_fields['text_shadow'] = [];
        $advanced_fields['fonts']       = [];

        $advanced_fields['box_shadow']['post'] = [
            'label'       => esc_html__('Post Box Shadow', 'brain-divi-blog'),
            'css'         => [
                'main'      => '%%order_class%% .brbl-post-card',
                'important' => 'all',
            ],
            'tab_slug'    => 'advanced',
            'toggle_slug' => 'grid_style',
        ];

        $advanced_fields['borders']['post'] = [
            'label_prefix' => esc_html__('Post', 'brain-divi-blog'),
            'toggle_slug'  => 'grid_style',
            'css'          => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-post-card',
                    'border_styles' => '%%order_class%% .brbl-post-card',
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

        $advanced_fields['borders']['image'] = [
            'label_prefix' => esc_html__('Image', 'brain-divi-blog'),
            'toggle_slug'  => 'image',
            'css'          => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-post-thumb',
                    'border_styles' => '%%order_class%% .brbl-post-thumb',
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

        $advanced_fields['borders']['content'] = [
            'label_prefix' => esc_html__('Content', 'brain-divi-blog'),
            'toggle_slug'  => 'content',
            'css'          => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-blog-content',
                    'border_styles' => '%%order_class%% .brbl-blog-content',
                ],
                'important' => 'all',
            ],
            'defaults'     => [
                'border_radii'  => 'on|0px|0px|0px|0px',
                'border_styles' => [
                    'width' => '1px',
                    'color' => '#efefef',
                    'style' => 'solid',
                ],
            ],
        ];

        $advanced_fields['box_shadow']['content'] = [
            'label'       => esc_html__('Content Box Shadow', 'brain-divi-blog'),
            'css'         => [
                'main'      => '%%order_class%% .brbl-blog-content',
                'important' => 'all',
            ],
            'toggle_slug' => 'content',
        ];

        $advanced_fields['fonts']['title'] = [
            'label'        => esc_html__('Title', 'brain-divi-blog'),
            'css'          => [
                'main'         => '%%order_class%% .brbl-post-title',
                'font'         => '%%order_class%% .brbl-post-title a, %%order_class%% .brbl-post-title',
                'color'        => '%%order_class%% .brbl-post-title a, %%order_class%% .brbl-post-title',
                'limited_main' => '%%order_class%% .brbl-post-title a, %%order_class%% .brbl-post-title',
                'important'    => 'all',
            ],
            'header_level' => [
                'default'          => 'h4',
                'computed_affects' => [
                    '__posts',
                ],
            ],
            'toggle_slug'  => 'title',
        ];

        $advanced_fields['fonts']['excerpt'] = [
            'label'       => esc_html__('Excerpt', 'brain-divi-blog'),
            'css'         => [
                'main'      => '%%order_class%% .brbl-post-excerpt',
                'important' => 'all',
            ],
            'tab_slug'    => 'advanced',
            'toggle_slug' => 'excerpt',
            'line_height' => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['author'] = [
            'label'           => esc_html__('Author', 'brain-divi-blog'),
            'css'             => [
                'main'      => '%%order_class%% .brbl-post-author a',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'toggle_slug'     => 'author',
            'tab_slug'        => 'advanced',
            'sub_toggle'      => 'text',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['fonts']['category'] = [
            'label'           => esc_html__('Category', 'brain-divi-blog'),
            'css'             => [
                'main'      => '%%order_class%% .brbl-post-categories a, %%order_class%% .brbl-post-categories',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'category',
            'sub_toggle'      => 'text',
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

        $advanced_fields['fonts']['date'] = [
            'label'           => esc_html__('Date', 'brain-divi-blog'),
            'css'             => [
                'main'      => '%%order_class%% .brbl-post-date',
                'important' => 'all',
            ],
            'hide_text_align' => true,
            'toggle_slug'     => 'date',
            'tab_slug'        => 'advanced',
            'sub_toggle'      => 'text',
            'line_height'     => [
                'range_settings' => [
                    'min'  => '1',
                    'max'  => '100',
                    'step' => '1',
                ],
            ],
        ];

        $advanced_fields['button']['button'] = [
            'label'          => esc_html__('Readmore', 'brain-divi-blog'),
            'css'            => [
                'main'      => '%%order_class%% .brbl-post-btn',
                'important' => true,
            ],
            'use_alignment'  => false,
            'box_shadow'     => [
                'css' => [
                    'main' => '%%order_class%% .brbl-post-btn',
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

        $advanced_fields['fonts']['pagination'] = [
            'css'             => [
                'main'  => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .brbl-load-more-button, %%order_class%% .brbl-pagination .page-numbers.current',
                'hover' => '%%order_class%% .brbl-pagination a:hover, %%order_class%% .brbl-pagination .brbl-load-more-button:hover, %%order_class%% .brbl-pagination .page-numbers.current:hover',
            ],
            'text_align'      => [
                'options' => et_builder_get_text_orientation_options(['justified'], []),
            ],
            'hide_text_color' => true,
            'toggle_slug'     => 'pagination',
            'tab_slug'        => 'advanced',
        ];

        $advanced_fields['borders']['pagination'] = [
            'css'         => [
                'main'      => [
                    'border_radii'  => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
                    'border_styles' => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
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
            'tab_slug'    => 'advanced',
            'toggle_slug' => 'pagination',
        ];

        $advanced_fields['box_shadow']['pagination'] = [
            'css'         => [
                'main'      => '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
                'important' => 'all',
            ],
            'tab_slug'    => 'advanced',
            'toggle_slug' => 'pagination',
        ];

        return $advanced_fields;
    }

    public static function get_posts($args = [], $conditional_tags = [], $current_page = []) {
        global $post, $paged, $wp_query, $wp_the_query;

        if (self::$rendering) {
            return '';
        }

        $main_query = $wp_the_query;

        $defaults = [
            'layout'                => '',
            'include_categories'    => '',
            'order_by'              => '',
            'order'                 => '',
            'post_count'            => '',
            'content_length'        => '',
            'date_format'           => '',
            'show_thumb'            => '',
            'thumb_size'            => '',
            'thumb_width'           => '',
            'thumb_height'          => '',
            'show_title'            => '',
            'show_excerpt'          => '',
            'show_btn'              => '',
            'button_text'           => '',
            'show_author'           => '',
            'show_date'             => '',
            'show_categories'       => '',
            'show_first_category'   => '',
            'pagination_type'       => '',
            'pagination_query_type' => '',
            'pagination_showall'    => '',
            'pagination_prev_label' => '',
            'pagination_next_label' => '',
            'loadmore_type'         => '',
            'loadmore_text'         => '',
            'offset_number'         => '',
            'include_posts'         => '',
            'exclude_posts'         => '',
            'button_icon'           => '',
            'overlay_icon'          => '',
            'title_level'           => '',
            'post_type'             => '',
            'img_masking_shape'     => '',
            'use_current_loop'      => '',
        ];

        $args = wp_parse_args($args, $defaults);

        $pagination_type       = $args['pagination_type'];
        $pagination_query_type = $args['pagination_query_type'];
        $pagination_prev_label = $args['pagination_prev_label'];
        $pagination_next_label = $args['pagination_next_label'];
        $loadmore_type         = $args['loadmore_type'];
        $loadmore_text         = $args['loadmore_text'];
        $pagination_showall    = $args['pagination_showall'];
        $layout                = $args['layout'];
        $include_categories    = $args['include_categories'];
        $order_by              = $args['order_by'];
        $order                 = $args['order'];
        $post_count            = $args['post_count'];
        $content_length        = $args['content_length'];
        $date_format           = $args['date_format'];
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
        $exclude_posts         = $args['exclude_posts'];
        $include_posts         = $args['include_posts'];
        $img_masking_shape     = $args['img_masking_shape'];
        $selected_icon         = esc_attr(et_pb_process_font_icon($button_icon));
        $button_icon           = !empty($selected_icon) ? $selected_icon : '5';
        $processed_title_level = et_pb_process_header_level($title_level, 'h4');
        $processed_title_level = esc_html($processed_title_level);
        $title_tag             = et_core_esc_previously($processed_title_level);

        $layout           = $args['layout'];
        $post_type        = $args['post_type'];
        $overlay_icon     = esc_attr(et_pb_process_font_icon($args['overlay_icon']));
        $use_current_loop = $args['use_current_loop'];

        if ('on' === $args['use_current_loop']) {
            $reset_keys = ['post_type', 'include_categories'];

            foreach ($reset_keys as $key) {
                $args[$key] = $defaults[$key];
            }
        }

        // Frontpage checking in builder.
        $is_front_page = et_fb_conditional_tag('is_front_page', $conditional_tags);

        // Find current page/post id.
        $post_id = isset($current_page['id']) ? (int) $current_page['id'] : 0;

        // Main Query Args!
        $query_args = [
            'posts_per_page' => intval($post_count),
            'post_type'      => $post_type,
            'post_status'    => ['publish', 'private'],
            'perm'           => 'readable',
            'orderby'        => $order_by,
            'order'          => $order,
        ];

        // Exclude Posts.
        if (!empty($exclude_posts)) {
            $exclude_posts              = str_replace(' ', '', $exclude_posts);
            $exclude_posts              = explode(',', $exclude_posts);
            $query_args['post__not_in'] = $exclude_posts;
        }

        // Include Posts.
        if (!empty($include_posts)) {
            $include_posts          = str_replace(' ', '', $include_posts);
            $include_posts          = explode(',', $include_posts);
            $query_args['post__in'] = $include_posts;
        }

        // Paged.
        $et_paged = $is_front_page ? get_query_var('page') : get_query_var('paged');

        if ($is_front_page) {
            $paged = $et_paged; //phpcs:ignore
        }

        // Include Categories.
        $query_args['cat'] = implode(',', self::filter_include_categories($include_categories, $post_id));

        $query_args['paged'] = $paged;

        if ('' !== $args['offset_number'] && !empty($args['offset_number'])) {
            if ($paged > 1) {
                $query_args['offset'] = (($paged - 1) * intval($post_count)) + intval($args['offset_number']);
            } else {
                $query_args['offset'] = intval($args['offset_number']);
            }
        }

        // Offset number checking.
        if ('' !== $args['offset_number'] && !empty($args['offset_number'])) {
            global $wp_query;
            $wp_query->found_posts   = max(0, $wp_query->found_posts - intval($args['offset_number']));
            $wp_query->max_num_pages = ceil($wp_query->found_posts / intval($post_count));
        }

        $blog_order = self::_get_index([self::INDEX_MODULE_ORDER, 'brbl_post_grid']);
        $item_class = sprintf(' brbl-blog-item-%1$s-%2$s', $layout, $blog_order);

        self::$rendering = true;

        ob_start();

        // Checking current loop.
        if ('off' === $use_current_loop) {
            query_posts($query_args); // phpcs:ignore
        } elseif (is_singular()) {
            query_posts(['post__in' => [0]]); // phpcs:ignore

        } else {
            $original     = $wp_query->query_vars;
            $custom       = array_intersect_key($query_args, array_flip(['posts_per_page', 'offset', 'paged']));
            $wp_the_query = $wp_query = new WP_Query(array_merge($original, $custom)); // phpcs:ignore
        }

        // Wp query.
        $wp_query = apply_filters('et_builder_blog_query', $wp_query); // phpcs:ignore

        $wp_query->et_pb_blog_query = true;

        // Pagination.
        if ('' === $pagination_type) {
            $current_page = 1;
        } else {
            $current_page = max(1, get_query_var('paged'), get_query_var('page'));
        }

        $pagi_options['pagination_type']       = $pagination_type;
        $pagi_options['pagination_query_type'] = $pagination_query_type;
        $pagi_options['pagination_prev_label'] = $pagination_prev_label;
        $pagi_options['pagination_next_label'] = $pagination_next_label;
        $pagi_options['loadmore_type']         = $loadmore_type;
        $pagi_options['loadmore_text']         = $loadmore_text;
        $pagi_options['max_num_pages']         = $wp_query->max_num_pages;
        $pagi_options['current_page']          = $current_page;
        $pagi_options['pagination_showall']    = $pagination_showall;
        $pagi_options['blog_order']            = $blog_order;

        // Quary.
        if (have_posts()) {
            echo '<div id="brbl-blog-wrapper" class="brbl-blog brbl-blog-' . et_core_esc_previously($loadmore_type) . '-' . et_core_esc_previously($blog_order) . '">';

            while (have_posts()) {
                the_post();

                // Templates.
                if ('layout1' === $layout) {
                    include BRAIN_BLOG_DIR . 'includes/templates/post-card-1.php';
                } elseif ('layout2' === $layout) {
                    include BRAIN_BLOG_DIR . 'includes/templates/post-card-2.php';
                } elseif ('layout3' === $layout) {
                    include BRAIN_BLOG_DIR . 'includes/templates/post-card-3.php';
                } elseif ('layout4' === $layout) {
                    include BRAIN_BLOG_DIR . 'includes/templates/post-card-4.php';
                }
            }

            echo '</div>';

            self::pagination_render($pagi_options);
        }

        unset($wp_query->et_pb_blog_query); //phpcs:ignore

        $wp_the_query = $wp_query = $main_query; //phpcs:ignore
        wp_reset_query();

        // Output.
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
            $paginate_args = [
                'current'   => $options['current_page'],
                'total'     => $options['max_num_pages'],
                'prev_next' => false,
                'show_all'  => 'on' === $options['pagination_showall'],
            ];

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
        $is_equal_height       = $this->props['is_equal_height'];
        $pagination_type       = $this->props['pagination_type'];
        $pagination_query_type = $this->props['pagination_query_type'];
        $loadmore_type         = $this->props['loadmore_type'];
        $layout                = $this->props['layout'];

        if ('loadmore' === $pagination_type) {
            wp_enqueue_script('brbl-imagesloaded');
            wp_enqueue_script('brbl-infinite-scroll');
        }

        $this->apply_css($render_slug);
        $grid_classes = [];

        array_push($grid_classes, 'brbl-module brbl-post-grid');
        array_push($grid_classes, "equal-height-{$is_equal_height}");
        $blog_order = self::_get_index([self::INDEX_MODULE_ORDER, $render_slug]);

        $options      = [];
        $pagi_options = '';

        $options['layout']          = $layout;
        $options['blog_order']      = $blog_order;
        $options['pagination_type'] = $pagination_type;
        $options['loadmore_type']   = $loadmore_type;

        $options = sprintf(
            'data-options="%1$s"',
            htmlspecialchars(wp_json_encode($options), ENT_QUOTES, 'UTF-8')
        );

        if ('numbers' === $pagination_type && 'ajax' === $pagination_query_type) {
            array_push($grid_classes, 'brbl-ajax-pagi');
            $pagi_options                        = [];
            $pagi_options['include_categories']  = $this->props['include_categories'];
            $pagi_options['img_masking_shape']   = $this->props['img_masking_shape'];
            $pagi_options['post_count']          = $this->props['post_count'];
            $pagi_options['post_type']           = $this->props['post_type'];
            $pagi_options['order_by']            = $this->props['order_by'];
            $pagi_options['order']               = $this->props['order'];
            $pagi_options['layout']              = $this->props['layout'];
            $pagi_options['show_categories']     = $this->props['show_categories'];
            $pagi_options['show_thumb']          = $this->props['show_thumb'];
            $pagi_options['show_author']         = $this->props['show_author'];
            $pagi_options['show_date']           = $this->props['show_date'];
            $pagi_options['show_title']          = $this->props['show_title'];
            $pagi_options['show_excerpt']        = $this->props['show_excerpt'];
            $pagi_options['thumb_size']          = $this->props['thumb_size'];
            $pagi_options['date_format']         = $this->props['date_format'];
            $pagi_options['content_length']      = $this->props['content_length'];
            $pagi_options['offset_number']       = $this->props['offset_number'];
            $pagi_options['show_btn']            = $this->props['show_btn'];
            $pagi_options['exclude_posts']       = $this->props['exclude_posts'];
            $pagi_options['include_posts']       = $this->props['include_posts'];
            $pagi_options['show_first_category'] = $this->props['show_first_category'];
            $pagi_options['thumb_width']         = $this->props['thumb_width'];
            $pagi_options['thumb_height']        = $this->props['thumb_height'];
            $selected_icon                       = esc_attr(et_pb_process_font_icon($this->props['button_icon']));
            $button_icon                         = !empty($selected_icon) ? $selected_icon : '5';
            $pagi_options['button_icon']         = $button_icon;
            $pagi_options['button_text']         = $this->props['button_text'];
            $pagi_options['overlay_icon']        = esc_attr(et_pb_process_font_icon($this->props['overlay_icon']));
            $processed_title_level               = et_pb_process_header_level($this->props['title_level'], 'h4');
            $pagi_options['title_tag']           = et_core_esc_previously(esc_html($processed_title_level));

            $pagi_options = sprintf(
                'data-pagination="%1$s"',
                htmlspecialchars(wp_json_encode($pagi_options), ENT_QUOTES, 'UTF-8')
            );
        }

        $infinite_class = 'loadmore' === $pagination_type ? 'brbl-postgrid-infinite' : '';

        $output = sprintf(
            '<div class="%2$s %4$s" %3$s %5$s>
                %1$s
            </div>',
            self::get_posts($this->props),
            join(' ', $grid_classes),
            $options,
            $infinite_class,
            $pagi_options
        );

        return $output;
    }

    public function apply_css($render_slug) {
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
        // Grid Settings.
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
            [
                'selector'    => '%%order_class%% .brbl-blog-item',
                'declaration' => sprintf(
                    '
				flex: 0 0 calc(100%%/%1$s);
				max-width:calc(100%%/%1$s);
				padding:%2$s %3$s;',
                    $column_count,
                    $column_gap_y,
                    $column_gap_x
                ),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-blog',
                'declaration' => sprintf(
                    '
				flex-wrap: wrap;
				display: flex;
				margin: -%1$s -%2$s;',
                    $column_gap_y,
                    $column_gap_x
                ),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-blog-item',
                'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                'declaration' => sprintf(
                    '
				flex: 0 0 calc(100%%/%1$s);
				max-width:calc(100%%/%1$s);
				padding:%2$s %3$s;',
                    $column_count_tablet,
                    $column_gap_y_tablet,
                    $column_gap_x_tablet
                ),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-blog',
                'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                'declaration' => sprintf('margin: -%1$s -%2$s;', $column_gap_y_tablet, $column_gap_x_tablet),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-blog-item',
                'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                'declaration' => sprintf(
                    '
				flex: 0 0 calc(100%%/%1$s);
				max-width:calc(100%%/%1$s);
				padding:%2$s %3$s;',
                    $column_count_phone,
                    $column_gap_y_phone,
                    $column_gap_x_phone
                ),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-blog',
                'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                'declaration' => sprintf('margin: -%1$s -%2$s;', $column_gap_y_phone, $column_gap_x_phone),
            ]
        );

        // Avatar.
        if ('layout4' === $layout) {
            $img_offset = '280px';

            if ('auto' !== $image_height) {
                $img_offset = $image_height;
            }

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-author-avatar',
                    'declaration' => sprintf('top: %1$s;', $img_offset),
                ]
            );

            if ('off' === $show_avatar) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-author-avatar',
                        'declaration' => 'display: none;',
                    ]
                );
            }
        }

        // Category.
        $this->brbl_get_responsive_styles(
            'category_padding',
            '%%order_class%% .brbl-post-categories',
            [
                'primary'   => 'padding',
                'important' => false,
            ],
            [],
            $render_slug
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
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
            ]
        );

        if ('layout3' !== $layout) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-categories',
                    'declaration' => sprintf(
                        '%1$s: %2$s;
						position: absolute;
						%3$s: %2$s;',
                        $category_placement[0],
                        $category_offset,
                        $category_placement[1]
                    ),
                ]
            );
        }

        // Image.
        if ('zoon_in' === $img_hover_style) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
                    'declaration' => 'transform: scale(1.2);',
                ]
            );
        } elseif ('zoon_out' === $img_hover_style) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
                    'declaration' => 'transform: scale(1);',
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-thumb img',
                    'declaration' => 'transform: scale(1.2);',
                ]
            );
        } elseif ('fade' === $img_hover_style) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
                    'declaration' => 'opacity: .7;',
                ]
            );
        }

        if ('on' === $desktop_only_img_hover) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-post-thumb img',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    'declaration' => 'opacity: 1!important;transform: scale(1)!important;',
                ]
            );
        }

        if ('actual' !== $image_size) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-thumb img',
                    'declaration' => 'height: 100%;object-fit:' . $image_size . ';',
                ]
            );
        }

        if ('auto' !== $image_height) {
            $this->brbl_get_responsive_styles(
                'image_height',
                '%%order_class%% .brbl-post-thumb',
                [
                    'primary'   => 'height',
                    'important' => false,
                ],
                ['default' => 'auto'],
                $render_slug
            );
        } else {
            if ('layout3' === $layout) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-card-3 .brbl-post-thumb',
                        'declaration' => 'height: 400px!important;',
                    ]
                );
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-thumb img',
                        'declaration' => 'height: 100%;',
                    ]
                );
            }

            if ('layout4' === $layout) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-thumb',
                        'declaration' => 'height: 280px;',
                    ]
                );
            }
        }

        $this->brbl_get_responsive_styles(
            'image_width',
            '%%order_class%% .brbl-post-thumb',
            [
                'primary'   => 'max-width',
                'important' => false,
            ],
            [
                'default'     => 'initial',
                'conditional' => [
                    'name'   => 'layout',
                    'values' => [
                        [
                            'a' => 'layout1',
                            'b' => '40%',
                        ],
                    ],
                ],
            ],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'image_width',
            '%%order_class%% .brbl-post-thumb',
            [
                'primary'   => 'flex',
                'important' => false,
            ],
            [
                'default'     => 'initial',
                'conditional' => [
                    'name'   => 'layout',
                    'values' => [
                        [
                            'a' => 'layout1',
                            'b' => '40%',
                        ],
                    ],
                ],
            ],
            $render_slug
        );

        if ('layout2' === $layout) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-thumb svg',
                    'declaration' => sprintf(
                        'position: absolute;
						bottom: -1px;
						width: 100%%;
						left: 0;
						fill: %1$s;',
                        $masking_shape_color
                    ),
                ]
            );
        }

        $this->brbl_get_responsive_styles(
            'post_padding',
            '%%order_class%% .brbl-post-card',
            [
                'primary'   => 'padding',
                'important' => false,
            ],
            ['default' => '0px|0px|0px|0px'],
            $render_slug
        );

        // Content.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-blog-content',
                'declaration' => "text-align:{$content_alignment};",
            ]
        );

        if ('right' === $content_alignment) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-meta',
                    'declaration' => 'justify-content:flex-end;',
                ]
            );

            if ('layout3' === $layout) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' => 'align-items:flex-end;',
                    ]
                );
            }

            if ('layout1' === $layout) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-author',
                        'declaration' => 'margin-left: auto;',
                    ]
                );
            }
        } elseif ('center' === $content_alignment) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-meta',
                    'declaration' => 'justify-content:center;',
                ]
            );

            if ('layout1' === $layout) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-post-author',
                        'declaration' => 'margin-left: auto;margin-right: auto;',
                    ]
                );
            }

            if ('layout3' === $layout) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' => 'align-items:center;',
                    ]
                );
            }
        } elseif ('left' === $content_alignment) {
            if ('layout3' === $layout) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' => 'align-items:flex-start;',
                    ]
                );
            }
        }

        if ('layout1' === $layout || 'layout2' === $layout) {
            $this->brbl_get_responsive_styles(
                'content_offset_y',
                '%%order_class%% .brbl-blog-content',
                [
                    'primary'   => 'margin-top',
                    'important' => false,
                ],
                [
                    'default'     => '0px',
                    'conditional' => [
                        'name'   => 'layout',
                        'values' => [
                            [
                                'a' => 'layout1',
                                'b' => '0px',
                            ],
                            [
                                'a' => 'layout2',
                                'b' => '-60px',
                            ],
                        ],
                    ],
                ],
                $render_slug
            );
        }

        if ('layout3' === $layout) {
            if ('center' !== $content_placement_x) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' => "{$content_placement_x}:0;",
                    ]
                );
            }

            if ('center' !== $content_placement_y) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' => "{$content_placement_y}:0;",
                    ]
                );
            }

            if ('center' === $content_placement_y && 'center' === $content_placement_x) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' =>
                        'top:50%; left: 50%;
							transform: translateY(-50%) translateX(-50%);',
                    ]
                );
            } else {
                if ('center' === $content_placement_y) {
                    ET_Builder_Element::set_style(
                        $render_slug,
                        [
                            'selector'    => '%%order_class%% .brbl-blog-content',
                            'declaration' =>
                            'top:50%; transform: translateY(-50%);',
                        ]
                    );
                }

                if ('center' === $content_placement_x) {
                    ET_Builder_Element::set_style(
                        $render_slug,
                        [
                            'selector'    => '%%order_class%% .brbl-blog-content',
                            'declaration' =>
                            'left:50%; transform: translateX(-50%);',
                        ]
                    );
                }
            }
        }

        $this->brbl_get_responsive_styles(
            'content_padding',
            '%%order_class%% .brbl-blog-content',
            [
                'primary'   => 'padding',
                'important' => false,
            ],
            ['default' => '30px|30px|30px|30px'],
            $render_slug
        );

        if ('layout1' === $layout) {
            $this->brbl_get_responsive_styles(
                'content_offset_x',
                '%%order_class%% .brbl-blog-content',
                [
                    'primary'   => 'margin-left',
                    'important' => false,
                ],
                [
                    'default'     => '0px',
                    'conditional' => [
                        'name'   => 'layout',
                        'values' => [
                            [
                                'a' => 'layout1',
                                'b' => '0px',
                            ],
                        ],
                    ],
                ],
                $render_slug
            );
        } elseif ('layout2' === $layout) {
            $this->brbl_get_responsive_styles(
                'content_width',
                '%%order_class%% .brbl-blog-content',
                [
                    'primary'   => 'width',
                    'important' => false,
                ],
                [
                    'default'     => '100%',
                    'conditional' => [
                        'name'   => 'layout',
                        'values' => [
                            [
                                'a' => 'layout2',
                                'b' => '90%',
                            ],
                        ],
                    ],
                ],
                $render_slug
            );

            if ('center' === $content_placement) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' => 'margin-left: auto; margin-right: auto;',
                    ]
                );
            } elseif ('right' === $content_placement) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-blog-content',
                        'declaration' => 'margin-right: auto;',
                    ]
                );
            }
        } elseif ('layout3' === $layout) {
            $this->brbl_get_responsive_styles(
                'content_width',
                '%%order_class%% .brbl-blog-content',
                [
                    'primary'   => 'width',
                    'important' => false,
                ],
                [
                    'default'     => '100%',
                    'conditional' => [
                        'name'   => 'layout',
                        'values' => [
                            [
                                'a' => 'layout2',
                                'b' => '90%',
                            ],
                        ],
                    ],
                ],
                $render_slug
            );
        }

        // Author.
        if ('layout1' === $layout) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-author img',
                    'declaration' => sprintf(
                        'width: %1$s;
						height: %1$s;
						margin-right: %2$s;
						border-radius: %3$s;',
                        $author_img_size,
                        $author_spacing,
                        $author_img_radius
                    ),
                ]
            );
        } else {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-post-author svg',
                    'declaration' => sprintf(
                        '
                    margin-right: %1$s;
                    width: %2$s;
                    fill: %3$s;',
                        $author_spacing,
                        $author_icon_size,
                        $author_icon_color
                    ),
                ]
            );
        }

        // Date.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-post-date svg',
                'declaration' => sprintf(
                    '
                margin-right: %1$s;
                width: %2$s;
                fill: %3$s;',
                    $date_spacing,
                    $date_icon_size,
                    $date_icon_color
                ),
            ]
        );

        if ('layout1' === $layout) {
            $this->brbl_get_responsive_styles(
                'date_spacing_top',
                '%%order_class%% .brbl-post-date',
                [
                    'primary'   => 'padding-top',
                    'important' => false,
                ],
                ['default' => '15px'],
                $render_slug
            );
        }

        $this->brbl_get_responsive_styles(
            'title_spacing_top',
            '%%order_class%% .brbl-post-title',
            [
                'primary'   => 'padding-top',
                'important' => true,
            ],
            ['default' => '15px'],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'title_spacing_bottom',
            '%%order_class%% .brbl-post-title',
            [
                'primary'   => 'padding-bottom',
                'important' => true,
            ],
            ['default' => '15px'],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'excerpt_spacing_top',
            '%%order_class%% .brbl-post-excerpt',
            [
                'primary'   => 'padding-top',
                'important' => true,
            ],
            ['default' => '15px'],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'excerpt_spacing_bottom',
            '%%order_class%% .brbl-post-excerpt',
            [
                'primary'   => 'padding-bottom',
                'important' => true,
            ],
            [
                'default'     => '0px',
                'conditional' => [
                    'name'   => 'layout',
                    'values' => [
                        [
                            'a' => 'layout3',
                            'b' => '15px',
                        ],
                        [
                            'a' => 'layout4',
                            'b' => '15px',
                        ],
                    ],
                ],
            ],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'btn_spacing_top',
            '%%order_class%% .brbl-post-btn-wrap',
            [
                'primary'   => 'padding-top',
                'important' => false,
            ],
            ['default' => '15px'],
            $render_slug
        );

        $this->brbl_get_button_styles('button', $render_slug, '%%order_class%% .brbl-post-btn');

        $this->brbl_get_custom_bg_style($render_slug, 'content', '%%order_class%% .brbl-blog-content', '%%order_class%% .brbl-blog-content:hover');

        $this->brbl_get_custom_bg_style($render_slug, 'post', '%%order_class%% .brbl-post-card', '%%order_class%% .brbl-post-card:hover');

        $this->brbl_get_overlay_style($render_slug, 'image');

        $this->brbl_get_responsive_styles(
            'pagination_gap',
            '%%order_class%% .brbl-pagination',
            [
                'primary'   => 'padding',
                'important' => false,
            ],
            ['default' => '0px|0px|0px|0px'],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'pagination_padding',
            '%%order_class%% .brbl-pagination a, %%order_class%% .page-numbers.current, %%order_class%% .brbl-pagination .brbl-load-more-button',
            [
                'primary'   => 'padding',
                'important' => false,
            ],
            ['default' => '0px|0px|0px|0px'],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'pagination_alignment',
            '%%order_class%% .brbl-pagination',
            [
                'primary'   => 'text-align',
                'important' => false,
            ],
            ['default' => 'center'],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'pagination_color',
            '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers, %%order_class%% .brbl-pagination .brbl-load-more-button',
            [
                'primary'   => 'color',
                'important' => true,
            ],
            [],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'pagination_bg_color',
            '%%order_class%% .brbl-pagination a, %%order_class%% .brbl-pagination .page-numbers, %%order_class%% .brbl-pagination .brbl-load-more-button',
            [
                'primary'   => 'background-color',
                'important' => true,
            ],
            [],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'pagination_active_color',
            '%%order_class%% .brbl-pagination .page-numbers.current',
            [
                'primary'   => 'color',
                'important' => true,
            ],
            [],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'pagination_active_bg_color',
            '%%order_class%% .brbl-pagination .page-numbers.current',
            [
                'primary'   => 'background-color',
                'important' => true,
            ],
            [],
            $render_slug
        );

        $this->brbl_get_responsive_styles(
            'loading_dot_color',
            '%%order_class%% .brbl-pagination .loader-ellips__dot',
            [
                'primary'   => 'background-color',
                'important' => true,
            ],
            [],
            $render_slug
        );
    }
}

new BRBL_PostGrid();
