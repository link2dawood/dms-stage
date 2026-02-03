<?php

class BRBL_Builder_Module extends ET_Builder_Module {
    protected function brbl_custom_background_fields($option_name, $option_label, $tab_slug, $toggle_slug, array $background_tab, $show_if, $default) {
        $color           = [];
        $image           = [];
        $gradient        = [];
        $advanced_fields = [];

        if (in_array('color', $background_tab, true)) {
            $color = $this->generate_background_options(
                "{$option_name}_bg",
                'color',
                $tab_slug,
                $toggle_slug,
                "{$option_name}_bg_color"
            );
        }

        if (in_array('gradient', $background_tab, true)) {
            $gradient = $this->generate_background_options(
                "{$option_name}_bg",
                'gradient',
                $tab_slug,
                $toggle_slug,
                "{$option_name}_bg_color"
            );
        }

        if (in_array('image', $background_tab, true)) {
            $image = $this->generate_background_options(
                "{$option_name}_bg",
                'image',
                $tab_slug,
                $toggle_slug,
                "{$option_name}_bg_color"
            );
        }

        $advanced_fields["{$option_name}_bg_color"] = [
            'label'             => sprintf(esc_html__('%1$s Background', 'brain-divi-blog'), $option_label),
            'type'              => 'background-field',
            'base_name'         => "{$option_name}_bg",
            'context'           => "{$option_name}_bg_color",
            'option_category'   => 'layout',
            'custom_color'      => true,
            'default'           => $default,
            'tab_slug'          => $tab_slug,
            'toggle_slug'       => $toggle_slug,
            'hover'             => 'tabs',
            'show_if'           => $show_if,
            'background_fields' => array_merge($color, $gradient, $image),
        ];

        $skip = $this->generate_background_options(
            "{$option_name}_bg",
            'skip',
            $tab_slug,
            $toggle_slug,
            "{$option_name}_bg_color"
        );

        $advanced_fields = array_merge($advanced_fields, $skip);

        return $advanced_fields;
    }

    protected function brbl_get_custom_gradient($args = []) {
        $defaults = apply_filters(
            'et_pb_default_gradient',
            [
                'repeat'           => ET_Global_Settings::get_value('all_background_gradient_repeat'),
                'type'             => ET_Global_Settings::get_value('all_background_gradient_type'),
                'direction'        => ET_Global_Settings::get_value('all_background_gradient_direction'),
                'radial_direction' => ET_Global_Settings::get_value('all_background_gradient_direction_radial'),
                'stops'            => ET_Global_Settings::get_value('all_background_gradient_stops'),
                'unit'             => ET_Global_Settings::get_value('all_background_gradient_unit'),
            ]
        );

        $args  = wp_parse_args(array_filter($args), $defaults);
        $stops = str_replace('|', ', ', $args['stops']);

        switch ($args['type']) {
            case 'conic':
                $type      = 'conic';
                $direction = "from {$args['direction']} at {$args['radial_direction']}";
                break;
            case 'elliptical':
                $type      = 'radial';
                $direction = "ellipse at {$args['radial_direction']}";
                break;
            case 'radial':
            case 'circular':
                $type      = 'radial';
                $direction = "circle at {$args['radial_direction']}";
                break;
            case 'linear':
            default:
                $type      = 'linear';
                $direction = $args['direction'];
        }

        if ('on' === $args['repeat']) {
            $type = 'repeating-' . $type;
        }

        return esc_html(
            "{$type}-gradient( {$direction}, {$stops} )"
        );
    }

    protected function brbl_process_custom_background_fields($option_name, $hover_suffix) {
        // Background Options Styling.
        $background_base_name                     = "{$option_name}_bg";
        $background_prefix                        = "{$background_base_name}_";
        $background_style                         = '';
        $background_image_style                   = '';
        $background_images                        = [];
        $has_background_color_gradient            = false;
        $background_color_gradient_overlays_image = 'off';

        // A. Background Gradient.
        $use_background_color_gradient = isset($this->props["{$background_prefix}use_color_gradient{$hover_suffix}"]) ? $this->props["{$background_prefix}use_color_gradient{$hover_suffix}"] : '';

        if ('on' === $use_background_color_gradient) {
            $background_color_gradient_overlays_image = isset($this->props["{$background_prefix}color_gradient_overlays_image{$hover_suffix}"]) ? $this->props["{$background_prefix}color_gradient_overlays_image{$hover_suffix}"] : $this->props["{$background_prefix}color_gradient_overlays_image"];

            $type = isset($this->props["{$background_prefix}color_gradient_type{$hover_suffix}"]) ? $this->props["{$background_prefix}color_gradient_type{$hover_suffix}"] : $this->props["{$background_prefix}color_gradient_type"];

            $direction = isset($this->props["{$background_prefix}color_gradient_direction{$hover_suffix}"]) ? $this->props["{$background_prefix}color_gradient_direction{$hover_suffix}"] : $this->props["{$background_prefix}color_gradient_direction"];

            $radial_direction = isset($this->props["{$background_prefix}color_gradient_direction_radial{$hover_suffix}"]) ? $this->props["{$background_prefix}color_gradient_direction_radial{$hover_suffix}"] : $this->props["{$background_prefix}color_gradient_direction_radial"];

            $color_gradient_stops = isset($this->props["{$background_prefix}color_gradient_stops{$hover_suffix}"]) ? $this->props["{$background_prefix}color_gradient_stops{$hover_suffix}"] : $this->props["{$background_prefix}color_gradient_stops"];

            $repeat = isset($this->props["{$background_prefix}color_gradient_repeat{$hover_suffix}"]) ? $this->props["{$background_prefix}color_gradient_repeat{$hover_suffix}"] : $this->props["{$background_prefix}color_gradient_repeat"];

            $unit = isset($this->props["{$background_prefix}color_gradient_unit{$hover_suffix}"]) ? $this->props["{$background_prefix}color_gradient_unit{$hover_suffix}"] : $this->props["{$background_prefix}color_gradient_unit"];

            $grad_props = [
                'type'             => $type,
                'direction'        => $direction,
                'radial_direction' => $radial_direction,
                'stops'            => $color_gradient_stops,
                'repeat'           => $repeat,
                'unit'             => $unit,
            ];

            // Save background gradient into background images list.
            $background_gradient = $this->brbl_get_custom_gradient($grad_props);
            $background_images[] = $background_gradient;

            // Flag to inform BG Color if current module has Gradient.
            $has_background_color_gradient = true;
        }

        // Background Image.
        $bg_image           = isset($this->props["{$option_name}_bg_image{$hover_suffix}"]) ? $this->props["{$option_name}_bg_image{$hover_suffix}"] : '';
        $parallax           = isset($this->props["{$option_name}_bg_parallax{$hover_suffix}"]) ? $this->props["{$option_name}_bg_parallax{$hover_suffix}"] : '';
        $is_bg_image_active = '' !== $bg_image && 'on' !== $parallax;

        if ($is_bg_image_active) {
            $has_bg_image = true;

            $bg_size = isset($this->props["{$option_name}_bg_size{$hover_suffix}"]) ? $this->props["{$option_name}_bg_size{$hover_suffix}"] : '';

            if ('' !== $bg_size) {
                $background_style .= sprintf(
                    'background-size: %1$s !important; ',
                    esc_html($bg_size)
                );
            }

            $bg_position = isset($this->props["{$option_name}_bg_position{$hover_suffix}"]) ? $this->props["{$option_name}_bg_position{$hover_suffix}"] : '';

            if ('' !== $bg_position) {
                $background_style .= sprintf(
                    'background-position: %1$s !important; ',
                    esc_html(str_replace('_', ' ', $bg_position))
                );
            }

            $bg_repeat = isset($this->props["{$option_name}_bg_repeat{$hover_suffix}"]) ? $this->props["{$option_name}_bg_repeat{$hover_suffix}"] : '';

            if ('' !== $bg_repeat) {
                $background_style .= sprintf(
                    'background-repeat: %1$s !important; ',
                    esc_html($bg_repeat)
                );
            }

            $bg_blend = isset($this->props["{$option_name}_bg_blend{$hover_suffix}"]) ? $this->props["{$option_name}_bg_blend{$hover_suffix}"] : '';

            if ('' !== $bg_blend) {
                $background_style .= sprintf(
                    'background-blend-mode: %1$s !important;',
                    esc_html($bg_blend)
                );
            }

            $background_images[] = sprintf('url(%1$s)', esc_html($bg_image));
        } else {
            $has_bg_image = false;
        }

        if (!empty($background_images)) {
            // The browsers stack the images in the opposite order to what you'd expect.
            if ('on' !== $background_color_gradient_overlays_image) {
                $background_images = array_reverse($background_images);
            }

            // Set background image styles only it's different compared to the larger device.
            $background_image_style = join(', ', $background_images);

            $background_style .= sprintf(
                'background-image: %1$s !important;',
                esc_html($background_image_style)
            );
        }

        // B. Background Color.
        if (!$has_background_color_gradient || !$has_bg_image) {
            $background_color = isset($this->props["{$background_prefix}color{$hover_suffix}"]) ? $this->props["{$background_prefix}color{$hover_suffix}"] : '';
            if ('' !== $background_color) {
                $background_style .= sprintf(
                    'background-color: %1$s%2$s; ',
                    esc_html($background_color),
                    esc_html(' !important')
                );
            }
        }

        return $background_style;
    }

    public static function brbl_process_margin_padding($val = '0|0|0|0', $type = 'padding', $imp = false) {
        $_top     = '';
        $_right   = '';
        $_bottom  = '';
        $_left    = '';
        $imp_text = '';
        $_val     = explode('|', $val);

        if ($imp) {
            $imp_text = '!important';
        }

        if ('' !== $_val[0]) {
            $_top = "{$type}-top:" . $_val[0] . $imp_text . ';';
        }

        if ('' !== $_val[1]) {
            $_right = "{$type}-right:" . $_val[1] . $imp_text . ';';
        }

        if ('' !== $_val[2]) {
            $_bottom = "{$type}-bottom:" . $_val[2] . $imp_text . ';';
        }

        if ('' !== $_val[3]) {
            $_left = "{$type}-left:" . $_val[3] . $imp_text . ';';
        }

        return esc_html("{$_top} {$_right} {$_bottom} {$_left}");
    }

    public function brbl_get_conditional_responsive_styles($styles, $data, $style) {
        $important = isset($styles['important']) ? $styles['important'] : false;

        if ('padding' === $style || 'margin' === $style) {
            return $this->brbl_process_margin_padding($data, $style, $important);
        } elseif ('flex' === $style) {
            return 'flex: 0 0 ' . $data . ';';
        } else {
            return sprintf(
                '
                %1$s:%2$s%3$s;',
                $style,
                $data,
                $important ? '!important;' : ''
            );
        }
    }

    protected function brbl_get_responsive_styles($opt_name, $selector, $styles, $pre_values, $render_slug) {
        $is_enabled = false;
        $style      = isset($styles['primary']) ? $styles['primary'] : '';
        $_data      = $this->props["{$opt_name}"];

        if (isset($this->props["{$opt_name}_last_edited"])) {
            $is_enabled = et_pb_get_responsive_status($this->props["{$opt_name}_last_edited"]);
        }

        if (empty($_data) && !empty($pre_values)) {
            if (!empty($pre_values['conditional'])) {
                $is_default = true;
                foreach ($pre_values['conditional']['values'] as $value) {
                    $property_val = $this->props["{$pre_values['conditional']['name']}"];
                    if ($property_val === $value['a']) {
                        $_data      = $value['b'];
                        $is_default = false;
                    }
                }

                if ($is_default) {
                    $_data = $pre_values['default'];
                }
            }
        }

        if (!empty($_data)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => $selector,
                    'declaration' => $this->brbl_get_conditional_responsive_styles($styles, $_data, $style),
                ]
            );

            if (!empty($styles['secondary'])) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => $selector,
                        'declaration' => $styles['secondary'],
                    ]
                );
            }
        }

        if ($is_enabled) {
            $_data_tablet = $this->props["{$opt_name}_tablet"];
            $_data_phone  = $this->props["{$opt_name}_phone"];

            if (!empty($_data_tablet)) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => $selector,
                        'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                        'declaration' => $this->brbl_get_conditional_responsive_styles($styles, $_data_tablet, $style),
                    ]
                );

                if (!empty($styles['secondary'])) {
                    ET_Builder_Element::set_style(
                        $render_slug,
                        [
                            'selector'    => $selector,
                            'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                            'declaration' => $styles['secondary'],
                        ]
                    );
                }
            }

            if (!empty($_data_phone)) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => $selector,
                        'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                        'declaration' => $this->brbl_get_conditional_responsive_styles($styles, $_data_phone, $style),
                    ]
                );

                if (!empty($styles['secondary'])) {
                    ET_Builder_Element::set_style(
                        $render_slug,
                        [
                            'selector'    => $selector,
                            'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                            'declaration' => $styles['secondary'],
                        ]
                    );
                }
            }
        }
    }

    protected function brbl_get_custom_bg_style($render_slug, $opt_slug, $selector, $hover_selector) {
        $_bg = $this->brbl_process_custom_background_fields($opt_slug, '');

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => $selector,
                'declaration' => $_bg,
            ]
        );

        $_bg_hover = $this->brbl_process_custom_background_fields($opt_slug, '__hover');

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => $hover_selector,
                'declaration' => $_bg_hover,
            ]
        );
    }

    protected function brbl_get_overlay_option_fields($opt_slug, $show_if) {
        $fields = [

            'overlay_icon'         => [
                'label'           => esc_html__('Overlay Icon', 'brain-divi-blog'),
                'type'            => 'select_icon',
                'option_category' => 'basic_option',
                'toggle_slug'     => $opt_slug,
                'tab_slug'        => 'advanced',
                'show_if'         => $show_if,
            ],

            'overlay_icon_color'   => [
                'label'       => esc_html__('Overlay Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'default'     => '#2EA3F2',
                'toggle_slug' => $opt_slug,
                'show_if'     => $show_if,
                'hover'       => 'tabs',
            ],

            'overlay_icon_size'    => [
                'label'           => esc_html__('Overlay Icon Size', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '32px',
                'default_unit'     => 'px',
                'range_settings'  => [
                    'min'  => 0,
                    'max'  => 200,
                    'step' => 1,
                ],
                'hover'           => 'tabs',
                'toggle_slug'     => $opt_slug,
                'tab_slug'        => 'advanced',
                'show_if'         => $show_if,
            ],

            'overlay_icon_opacity' => [
                'label'           => esc_html__('Overlay Icon Opacity', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '1',
                'unitless'        => true,
                'range_settings'  => [
                    'min'  => 0,
                    'max'  => 1,
                    'step' => .02,
                ],
                'toggle_slug'     => $opt_slug,
                'tab_slug'        => 'advanced',
                'show_if'         => $show_if,
                'hover'           => 'tabs',
            ],
        ];

        $overlay = $this->brbl_custom_background_fields('overlay', 'Overlay', 'advanced', $opt_slug, ['color', 'gradient'], $show_if, '');

        return array_merge($overlay, $fields);
    }

    protected function brbl_get_overlay_style($render_slug, $photo_opt_name) {
        $overlay_icon_color         = $this->props['overlay_icon_color'];
        $overlay_icon_color_hover   = $this->get_hover_value('overlay_icon_color');
        $overlay_icon_size          = $this->props['overlay_icon_size'];
        $overlay_icon_size_hover    = $this->get_hover_value('overlay_icon_size');
        $overlay_icon_opacity       = $this->props['overlay_icon_opacity'];
        $overlay_icon_opacity_hover = $this->get_hover_value('overlay_icon_opacity');

        if (!empty($photo_opt_name)) {
            $raddi = isset($this->props["border_radii_{$photo_opt_name}"]) ? $this->props["border_radii_{$photo_opt_name}"] : '';
            if (!empty($raddi)) {
                $raddi   = explode('|', $raddi);
                $raddi_1 = '' !== $raddi[1] ? $raddi[1] : '0';
                $raddi_2 = '' !== $raddi[2] ? $raddi[2] : '0';
                $raddi_3 = '' !== $raddi[3] ? $raddi[3] : '0';
                $raddi_4 = '' !== $raddi[4] ? $raddi[4] : '0';

                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-overlay',
                        'declaration' => sprintf(
                            'border-radius: %1$s %2$s %3$s %4$s;',
                            $raddi_1,
                            $raddi_2,
                            $raddi_3,
                            $raddi_4
                        ),
                    ]
                );
            }
        }

        // Overlay background.
        $this->brbl_get_custom_bg_style($render_slug, 'overlay', '%%order_class%% .brbl-overlay', '%%order_class%% .brbl-blog-item:hover .brbl-overlay');

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-overlay',
                'declaration' => sprintf('color: %1$s;', $overlay_icon_color),
            ]
        );

        if (!empty($overlay_icon_color_hover)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-overlay',
                    'declaration' => sprintf('color: %1$s;', $overlay_icon_color_hover),
                ]
            );
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-overlay:after',
                'declaration' => "font-size:{$overlay_icon_size};",
            ]
        );

        if (!empty($overlay_icon_size_hover)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-overlay:after',
                    'declaration' => "font-size:{$overlay_icon_size_hover};",
                ]
            );
        }

        // ET_Builder_Element::set_style(
        //     $render_slug,
        //     [
        //         'selector'    => '%%order_class%% .brbl-overlay:after',
        //         'declaration' => "opacity:{$overlay_icon_opacity};",
        //     ]
        // );

        if (!empty($overlay_icon_opacity_hover)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-blog-item:hover .brbl-overlay:after',
                    'declaration' => "opacity:{$overlay_icon_opacity_hover};",
                ]
            );
        }
    }

    protected function get_carousel_option_fields($supports, $default, $show_if) {
        $additional  = [];
        $slide_count = isset($default['slide_count']) ? $default['slide_count'] : '3';
        $supports    = !empty($supports) ? $supports : [];
        $default     = !empty($default) ? $default : [];
        $show_if     = !empty($show_if) ? $show_if : [];

        $fields = [

            // Carousel Settings.
            'animation_speed'         => [
                'label'           => esc_html__('Animation Speed', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '700ms',
                'fixed_unit'      => 'ms',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'general',
                'range_settings'  => [
                    'step' => 100,
                    'min'  => 0,
                    'max'  => 10000,
                ],
                'show_if'         => $show_if,
            ],

            'is_autoplay'             => [
                'label'       => esc_html__('Autoplay', 'brain-divi-blog'),
                'type'        => 'yes_no_button',
                'default'     => 'on',
                'toggle_slug' => 'carousel_settings',
                'sub_toggle'  => 'general',
                'options'     => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'     => $show_if,
            ],

            'autoplay_speed'          => [
                'label'          => esc_html__('Autoplay Speed', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '2000ms',
                'fixed_unit'     => 'ms',
                'toggle_slug'    => 'carousel_settings',
                'sub_toggle'     => 'general',
                'range_settings' => [
                    'step' => 100,
                    'min'  => 0,
                    'max'  => 10000,
                ],
                'show_if'        => array_merge($show_if, ['is_autoplay' => 'on']),
            ],

            'use_nav'                 => [
                'label'           => esc_html__('Use Navigation', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'on',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'general',
                'mobile_options'  => true,
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            'use_pagi'                => [
                'label'           => esc_html__('Use Pagination', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'off',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'general',
                'mobile_options'  => true,
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            'is_variable_width'       => [
                'label'           => esc_html__('Use Fixed Width Slide', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'off',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'general',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            'slide_width'             => [
                'label'          => esc_html__('Slide Width', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '360px',
                'fixed_unit'     => 'px',
                'mobile_options' => true,
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 1000,
                ],
                'toggle_slug'    => 'carousel_settings',
                'sub_toggle'     => 'general',
                'show_if'        => array_merge($show_if, ['is_variable_width' => 'on']),
            ],

            'slide_count'             => [
                'label'          => esc_html__('Slides To Show', 'brain-divi-blog'),
                'type'           => 'select',
                'default'        => $slide_count,
                'toggle_slug'    => 'carousel_settings',
                'sub_toggle'     => 'general',
                'mobile_options' => true,
                'options'        => [
                    '1' => esc_html__('1', 'brain-divi-blog'),
                    '2' => esc_html__('2', 'brain-divi-blog'),
                    '3' => esc_html__('3', 'brain-divi-blog'),
                    '4' => esc_html__('4', 'brain-divi-blog'),
                    '5' => esc_html__('5', 'brain-divi-blog'),
                    '6' => esc_html__('6', 'brain-divi-blog'),
                    '7' => esc_html__('7', 'brain-divi-blog'),
                    '8' => esc_html__('8', 'brain-divi-blog'),
                ],
                'show_if'        => array_merge($show_if, ['is_variable_width' => 'off']),
            ],

            'slide_spacing'           => [
                'label'          => esc_html__('Slide Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '10px',
                'fixed_unit'     => 'px',
                'toggle_slug'    => 'carousel_settings',
                'sub_toggle'     => 'general',
                'range_settings' => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 100,
                ],
                'show_if'        => $show_if,
            ],

            'use_both_side_spacing'   => [
                'label'           => esc_html__('Apply Spacing on First & Last Item', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'on',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'general',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            'is_infinite'             => [
                'label'           => esc_html__('Infinite looping', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'on',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'general',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            'sliding_dir'             => [
                'label'       => esc_html__('Sliding Direction', 'brain-divi-blog'),
                'description' => esc_html__('Define sliding direction. RTL sliding will only work on frontend. ', 'brain-divi-blog'),
                'type'        => 'select',
                'toggle_slug' => 'carousel_settings',
                'sub_toggle'  => 'advanced',
                'default'     => 'ltr',
                'options'     => [
                    'ltr' => esc_html__('Left to Right', 'brain-divi-blog'),
                    'rtl' => esc_html__('Right to Left', 'brain-divi-blog'),
                ],
            ],

            'css_transition'          => [
                'label'       => esc_html__('Sliding CSS Transition', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => 'ease-in-out',
                'toggle_slug' => 'carousel_settings',
                'sub_toggle'  => 'advanced',
                'options'     => [
                    'linear'      => esc_html__('linear', 'brain-divi-blog'),
                    'ease-in'     => esc_html__('ease-in', 'brain-divi-blog'),
                    'ease-in-out' => esc_html__('ease-in-out', 'brain-divi-blog'),
                ],
                'show_if'     => $show_if,
            ],

            'is_swipe'                => [
                'label'           => esc_html__('Swipe', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'on',
                'mobile_options'  => true,
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'advanced',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            'slide_to_scroll'         => [
                'label'           => esc_html__('Items to Scroll', 'brain-divi-blog'),
                'type'            => 'range',
                'default'         => '1',
                'option_category' => 'basic_option',
                'unitless'        => true,
                'mobile_options'  => true,
                'range_settings'  => [
                    'min'  => 1,
                    'step' => 1,
                    'max'  => 20,
                ],
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'advanced',
                'show_if'         => $show_if,
            ],

            'is_center'               => [
                'label'           => esc_html__('Center Mode', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'off',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'advanced',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            'center_mode_type'        => [
                'label'       => esc_html__('Center Mode Type', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => 'classic',
                'toggle_slug' => 'carousel_settings',
                'sub_toggle'  => 'advanced',
                'options'     => [
                    'classic'     => esc_html__('Classic', 'brain-divi-blog'),
                    'highlighted' => esc_html__('Highlighted', 'brain-divi-blog'),
                ],

                'show_if'     => array_merge($show_if, ['is_center' => 'on']),
            ],

            'center_padding'          => [
                'label'          => esc_html__('Center Padding', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '70px',
                'fixed_unit'     => 'px',
                'range_settings' => [
                    'min'  => 0,
                    'step' => 1,
                    'max'  => 400,
                ],
                'mobile_options' => true,
                'toggle_slug'    => 'carousel_settings',
                'sub_toggle'     => 'advanced',
                'show_if'        => array_merge(
                    $show_if,
                    [
                        'is_center'         => 'on',
                        'center_mode_type'  => 'classic',
                        'is_variable_width' => 'off',
                    ]
                ),
            ],

            'wait_for_animate'        => [
                'label'           => esc_html__('Wait For Animate', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'on',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'advanced',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ],

            // carousel style.
            'carousel_spacing_top'    => [
                'label'          => esc_html__('Wrapper Spacing Top', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '30px',
                'fixed_unit'     => 'px',
                'toggle_slug'    => 'carousel_settings',
                'sub_toggle'     => 'advanced',
                'mobile_options' => true,
                'range_settings' => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 200,
                ],
                'show_if'        => $show_if,
            ],

            'carousel_spacing_bottom' => [
                'label'          => esc_html__('Wrapper Spacing Bottom', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '30px',
                'fixed_unit'     => 'px',
                'toggle_slug'    => 'carousel_settings',
                'sub_toggle'     => 'advanced',
                'mobile_options' => true,
                'range_settings' => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 200,
                ],
                'show_if'        => $show_if,
            ],

            // Navigation.
            'nav_type'                => [
                'label'       => esc_html__('Type', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => 'default',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_common',
                'options'     => [
                    'default'   => esc_html__('Type A', 'brain-divi-blog'),
                    'alongside' => esc_html__('Type B', 'brain-divi-blog'),
                ],
                'show_if'     => array_merge(['sliding_dir' => 'ltr'], $show_if),
            ],

            'nav_pos'                 => [
                'label'       => esc_html__('Vertical Placement', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => 'top',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_common',
                'options'     => [
                    'top'    => esc_html__('Top', 'brain-divi-blog'),
                    'bottom' => esc_html__('Bottom', 'brain-divi-blog'),
                ],
                'show_if'     => array_merge($show_if, ['nav_type' => 'alongside']),
            ],

            'nav_pos_hz'              => [
                'label'       => esc_html__('Horizontal Placement', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => 'left',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_common',
                'options'     => [
                    'left'  => esc_html__('Left', 'brain-divi-blog'),
                    'right' => esc_html__('Right', 'brain-divi-blog'),
                ],
                'show_if'     => array_merge($show_if, ['nav_type' => 'alongside']),
            ],

            'nav_height'              => [
                'label'           => esc_html__('Height', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '40px',
                'fixed_unit'      => 'px',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'mobile_options'  => true,
                'range_settings'  => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 200,
                ],
                'show_if'         => $show_if,
            ],

            'nav_width'               => [
                'label'           => esc_html__('Width', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '40px',
                'fixed_unit'      => 'px',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'mobile_options'  => true,
                'range_settings'  => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 200,
                ],
                'show_if'         => $show_if,
            ],

            'nav_icon_size'           => [
                'label'           => esc_html__('Icon Size', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '30px',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'mobile_options'  => true,
                'range_settings'  => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 200,
                ],
                'show_if'         => $show_if,
            ],

            'nav_color'               => [
                'label'       => esc_html__('Icon Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'nav',
                'default'     => '#333',
                'sub_toggle'  => 'nav_common',
                'hover'       => 'tabs',
                'show_if'     => $show_if,
            ],

            'nav_bg'                  => [
                'label'       => esc_html__('Background', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'nav',
                'default'     => '#ddd',
                'sub_toggle'  => 'nav_common',
                'hover'       => 'tabs',
                'show_if'     => $show_if,
            ],

            'nav_skew'                => [
                'label'           => esc_html__('Skew', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '0deg',
                'fixed_unit'      => 'deg',
                'default_unit'    => 'deg',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'range_settings'  => [
                    'min'  => -90,
                    'max'  => 90,
                    'step' => 1,
                ],
                'show_if'         => $show_if,
            ],

            'nav_gap'                 => [
                'label'           => esc_html__('Spacing Between', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '10px',
                'default_unit'    => 'px',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'mobile_options'  => true,
                'show_if'         => array_merge($show_if, ['nav_type' => 'alongside']),
                'range_settings'  => [
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 1,
                ],
            ],

            'nav_pos_y'               => [
                'label'           => esc_html__('Vertical Position', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '50%',
                'mobile_options'  => true,
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'range_settings'  => [
                    'min'  => -150,
                    'max'  => 500,
                    'step' => 1,
                ],
                'show_if'         => $show_if,
            ],

            'nav_x_center'            => [
                'label'       => esc_html__('Use Horizontal Position Center', 'brain-divi-blog'),
                'type'        => 'yes_no_button',
                'default'     => 'off',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_common',
                'options'     => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'     => array_merge($show_if, ['nav_type' => 'alongside']),
            ],

            'nav_pos_x'               => [
                'label'           => esc_html__('Horizontal Position', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'mobile_options'  => true,
                'default'         => '-15px',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'range_settings'  => [
                    'min'  => -300,
                    'max'  => 300,
                    'step' => 1,
                ],
                'show_if'         => $show_if,
                'show_if_not'     => [
                    'nav_x_center' => 'on',
                ],
            ],

            'nav_border_width'        => [
                'label'           => esc_html__('Border Width', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '0px',
                'default_unit'    => 'px',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_common',
                'range_settings'  => [
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 1,
                ],
                'show_if'         => $show_if,
            ],

            'nav_border_color'        => [
                'label'       => esc_html__('Border Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'default'     => '#333',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_common',
                'hover'       => 'tabs',
                'show_if'     => $show_if,
            ],

            'nav_border_style'        => [
                'label'       => esc_html__('Border Type', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => 'solid',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_common',
                'options'     => [
                    'solid'  => esc_html__('Solid', 'brain-divi-blog'),
                    'dashed' => esc_html__('Dashed', 'brain-divi-blog'),
                    'dotted' => esc_html__('Dotted', 'brain-divi-blog'),
                    'double' => esc_html__('Double', 'brain-divi-blog'),
                    'groove' => esc_html__('Groove', 'brain-divi-blog'),
                    'ridge'  => esc_html__('Ridge', 'brain-divi-blog'),
                    'inset'  => esc_html__('Inset', 'brain-divi-blog'),
                    'outset' => esc_html__('Outset', 'brain-divi-blog'),
                    'none'   => esc_html__('None', 'brain-divi-blog'),
                ],
                'show_if'     => $show_if,
            ],

            // Left Arrow.
            'icon_left'               => [
                'label'           => esc_html__('Left Icon', 'brain-divi-blog'),
                'type'            => 'select_icon',
                'option_category' => 'basic_option',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_left',
                'show_if'         => $show_if,
            ],

            'left_border_radius'      => [
                'label'       => esc_html__('Border Radius', 'brain-divi-blog'),
                'type'        => 'border-radius',
                'default'     => 'on|40px|40px|40px|40px',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_left',
                'show_if'     => $show_if,
            ],

            // Right Arrow.
            'icon_right'              => [
                'label'           => esc_html__('Right Icon', 'brain-divi-blog'),
                'type'            => 'select_icon',
                'option_category' => 'basic_option',
                'toggle_slug'     => 'nav',
                'tab_slug'        => 'advanced',
                'sub_toggle'      => 'nav_right',
                'show_if'         => $show_if,
            ],

            'right_border_radius'     => [
                'label'       => esc_html__('Border Radius', 'brain-divi-blog'),
                'type'        => 'border-radius',
                'default'     => 'on|40px|40px|40px|40px',
                'toggle_slug' => 'nav',
                'tab_slug'    => 'advanced',
                'sub_toggle'  => 'nav_right',
                'show_if'     => $show_if,
            ],

            // pagination.
            'pagi_type'               => [
                'label'       => esc_html__('Type', 'brain-divi-blog'),
                'type'        => 'select',
                'default'     => 'dot',
                'toggle_slug' => 'pagi',
                'sub_toggle'  => 'pagi_common',
                'tab_slug'    => 'advanced',
                'options'     => [
                    'dot'    => esc_html__('Dot', 'brain-divi-blog'),
                    'number' => esc_html__('Number', 'brain-divi-blog'),
                ],
                'show_if'     => $show_if,
            ],

            'pagi_alignment'          => [
                'label'            => esc_html__('Alignment', 'brain-divi-blog'),
                'type'             => 'text_align',
                'option_category'  => 'layout',
                'options'          => et_builder_get_text_orientation_options(['justified']),
                'options_icon'     => 'module_align',
                'default_on_front' => 'center',
                'default'          => 'center',
                'toggle_slug'      => 'pagi',
                'sub_toggle'       => 'pagi_common',
                'tab_slug'         => 'advanced',
                'show_if'          => $show_if,
            ],

            'pagi_color'              => [
                'label'       => esc_html__('Text Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'pagi',
                'sub_toggle'  => 'pagi_common',
                'default'     => '#333333',
                'hover'       => 'tabs',
                'show_if'     => array_merge($show_if, ['pagi_type' => 'number']),
            ],

            'pagi_text'               => [
                'label'           => esc_html__('Text Size', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '18px',
                'toggle_slug'     => 'pagi',
                'sub_toggle'      => 'pagi_common',
                'tab_slug'        => 'advanced',
                'show_if'         => array_merge($show_if, ['pagi_type' => 'number']),
                'range_settings'  => [
                    'min'  => 1,
                    'step' => 1,
                    'max'  => 50,
                ],
            ],

            'pagi_bg'                 => [
                'label'       => esc_html__('Background', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'pagi',
                'sub_toggle'  => 'pagi_common',
                'default'     => '#dddddd',
                'hover'       => 'tabs',
                'show_if'     => $show_if,
            ],

            'pagi_height'             => [
                'label'           => esc_html__('Height', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '10px',
                'toggle_slug'     => 'pagi',
                'sub_toggle'      => 'pagi_common',
                'tab_slug'        => 'advanced',
                'range_settings'  => [
                    'min'  => 1,
                    'step' => 1,
                    'max'  => 50,
                ],
                'show_if'         => $show_if,
            ],

            'pagi_width'              => [
                'label'           => esc_html__('Width', 'brain-divi-blog'),
                'type'            => 'range',
                'option_category' => 'basic_option',
                'default'         => '10px',
                'toggle_slug'     => 'pagi',
                'sub_toggle'      => 'pagi_common',
                'tab_slug'        => 'advanced',
                'range_settings'  => [
                    'min'  => 1,
                    'step' => 1,
                    'max'  => 50,
                ],
                'show_if'         => $show_if,
            ],

            'pagi_radius'             => [
                'label'       => esc_html__('Border Radius', 'brain-divi-blog'),
                'type'        => 'border-radius',
                'default'     => 'on|10px|10px|10px|10px',
                'toggle_slug' => 'pagi',
                'sub_toggle'  => 'pagi_common',
                'tab_slug'    => 'advanced',
                'show_if'     => $show_if,
            ],

            'pagi_pos_y'              => [
                'label'          => esc_html__('Vertical Position', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '10px',
                'toggle_slug'    => 'pagi',
                'sub_toggle'     => 'pagi_common',
                'tab_slug'       => 'advanced',
                'range_settings' => [
                    'min'  => -400,
                    'max'  => 400,
                    'step' => 1,
                ],
                'show_if'        => $show_if,
            ],

            'pagi_spacing'            => [
                'label'          => esc_html__('Spacing', 'brain-divi-blog'),
                'type'           => 'range',
                'default'        => '10px',
                'fixed_unit'     => 'px',
                'toggle_slug'    => 'pagi',
                'sub_toggle'     => 'pagi_common',
                'tab_slug'       => 'advanced',
                'range_settings' => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 100,
                ],
                'show_if'        => $show_if,
            ],

            'pagi_bg_active'          => [
                'label'       => esc_html__('Background', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'pagi',
                'sub_toggle'  => 'pagi_active',
                'show_if'     => $show_if,
            ],

            'pagi_text_active'        => [
                'label'       => esc_html__('Text Color', 'brain-divi-blog'),
                'type'        => 'color-alpha',
                'tab_slug'    => 'advanced',
                'toggle_slug' => 'pagi',
                'sub_toggle'  => 'pagi_active',
                'show_if'     => $show_if,
            ],

            'pagi_width_active'       => [
                'label'          => esc_html__('Width', 'brain-divi-blog'),
                'type'           => 'range',
                'fixed_unit'     => 'px',
                'toggle_slug'    => 'pagi',
                'sub_toggle'     => 'pagi_active',
                'tab_slug'       => 'advanced',
                'range_settings' => [
                    'step' => 1,
                    'min'  => 0,
                    'max'  => 100,
                ],
                'show_if'        => $show_if,
            ],
        ];

        if (in_array('lightbox', $supports, true)) {
            $additional['use_lightbox'] = [
                'label'           => esc_html__('Open Image in Lightbox', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'default'         => 'off',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'advanced',
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'show_if'         => $show_if,
            ];
        }

        if (in_array('equal_height', $supports, true)) {
            $additional['is_equal_height'] = [
                'label'           => esc_html__('Equalize Item Height', 'brain-divi-blog'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'description'     => esc_html__('Enable this to Equalize Carousel items with same height.', 'brain-divi-blog'),
                'options'         => [
                    'on'  => esc_html__('Yes', 'brain-divi-blog'),
                    'off' => esc_html__('No', 'brain-divi-blog'),
                ],
                'default'         => 'on',
                'toggle_slug'     => 'carousel_settings',
                'sub_toggle'      => 'general',
                'show_if'         => $show_if,
            ];
        }

        return array_merge($fields, $additional);
    }

    protected function render_default_nav_css($render_slug) {
        $sliding_dir                 = $this->props['sliding_dir'];
        $left                        = 'ltr' === $sliding_dir ? 'left' : 'right';
        $right                       = 'ltr' === $sliding_dir ? 'right' : 'left';
        $nav_pos_y                   = $this->props['nav_pos_y'];
        $nav_pos_y_tablet            = $this->props['nav_pos_y_tablet'];
        $nav_pos_y_phone             = $this->props['nav_pos_y_phone'];
        $nav_pos_y_last_edited       = $this->props['nav_pos_y_last_edited'];
        $nav_pos_y_responsive_status = et_pb_get_responsive_status($nav_pos_y_last_edited);
        $nav_pos_x                   = $this->props['nav_pos_x'];
        $nav_pos_x_tablet            = $this->props['nav_pos_x_tablet'];
        $nav_pos_x_phone             = $this->props['nav_pos_x_phone'];
        $nav_pos_x_last_edited       = $this->props['nav_pos_x_last_edited'];
        $nav_pos_x_responsive_status = et_pb_get_responsive_status($nav_pos_x_last_edited);

        if ('ltr' === $sliding_dir) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-prev',
                    'declaration' => 'right: auto!important;',
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-next',
                    'declaration' => 'left: auto!important;',
                ]
            );
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .slick-arrow',
                'declaration' => sprintf(' top: %1$s; ', $nav_pos_y),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .slick-next',
                'declaration' => sprintf('%2$s: %1$s;', $nav_pos_x, $right),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .slick-prev',
                'declaration' => sprintf('%2$s: %1$s; ', $nav_pos_x, $left),
            ]
        );

        if (!empty($nav_pos_x_tablet) && $nav_pos_x_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-next',
                    'declaration' => sprintf('%2$s: %1$s;', $nav_pos_x_tablet, $right),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'declaration' => sprintf('%2$s: %1$s; ', $nav_pos_x_tablet, $left),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]
            );
        endif;

        if (!empty($nav_pos_x_phone) && $nav_pos_x_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-next',
                    'declaration' => sprintf('%2$s: %1$s;', $nav_pos_x_phone, $right),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'declaration' => sprintf('%2$s: %1$s; ', $nav_pos_x_phone, $right),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );
        endif;

        if (!empty($nav_pos_y_tablet) && $nav_pos_y_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-arrow',
                    'declaration' => sprintf('top: %1$s; ', $nav_pos_y_tablet),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]
            );
        endif;

        if (!empty($nav_pos_y_phone) && $nav_pos_y_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-arrow',
                    'declaration' => sprintf('top: %1$s; ', $nav_pos_y_phone),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );
        endif;
    }

    protected function render_alongside_nav_css($render_slug) {
        $sliding_dir                 = $this->props['sliding_dir'];
        $nav_pos                     = $this->props['nav_pos'];
        $nav_pos_hz                  = $this->props['nav_pos_hz'];
        $nav_pos_y                   = $this->props['nav_pos_y'];
        $nav_pos_y_tablet            = $this->props['nav_pos_y_tablet'];
        $nav_pos_y_phone             = $this->props['nav_pos_y_phone'];
        $nav_pos_y_last_edited       = $this->props['nav_pos_y_last_edited'];
        $nav_pos_y_responsive_status = et_pb_get_responsive_status($nav_pos_y_last_edited);
        $nav_x_center                = $this->props['nav_x_center'];
        $nav_pos_x                   = $this->props['nav_pos_x'];
        $nav_pos_x_tablet            = $this->props['nav_pos_x_tablet'];
        $nav_pos_x_phone             = $this->props['nav_pos_x_phone'];
        $nav_pos_x_last_edited       = $this->props['nav_pos_x_last_edited'];
        $nav_pos_x_responsive_status = et_pb_get_responsive_status($nav_pos_x_last_edited);
        $nav_width                   = $this->props['nav_width'];
        $nav_width_tablet            = $this->props['nav_width_tablet'] ? $this->props['nav_width_tablet'] : $nav_width;
        $nav_width_phone             = $this->props['nav_width_phone'] ? $this->props['nav_width_phone'] : $nav_width_tablet;
        $nav_gap                     = $this->props['nav_gap'];
        $nav_gap_tablet              = $this->props['nav_gap_tablet'] ? $this->props['nav_gap_tablet'] : $nav_gap;
        $nav_gap_phone               = $this->props['nav_gap_phone'] ? $this->props['nav_gap_phone'] : $nav_gap_tablet;

        if ('ltr' === $sliding_dir) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-next',
                    'declaration' => 'left: auto!important;',
                ]
            );
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .slick-arrow',
                'declaration' => sprintf(' top: auto; %1$s: %2$s;', $nav_pos, $nav_pos_y),
            ]
        );

        if ('on' === $nav_x_center) {
            // Desktop.
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-next',
                    'declaration' => sprintf('right: calc(50%% - %1$spx);', intval($nav_width) + (intval($nav_gap) / 2)),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'declaration' => sprintf('left: calc(50%% - %1$spx);', intval($nav_width) + (intval($nav_gap) / 2)),
                ]
            );

            // Tablet.
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-next',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    'declaration' => sprintf('right: calc(50%% - %1$spx);', intval($nav_width_tablet) + (intval($nav_gap_tablet) / 2)),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    'declaration' => sprintf('left: calc(50%% - %1$spx);', intval($nav_width_tablet) + (intval($nav_gap_tablet) / 2)),
                ]
            );

            // Phone.
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-next',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    'declaration' => sprintf('right: calc(50%% - %1$spx);', intval($nav_width_phone) + (intval($nav_gap_phone) / 2)),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    'declaration' => sprintf('left: calc(50%% - %1$spx);', intval($nav_width_phone) + (intval($nav_gap_phone) / 2)),
                ]
            );
        } else {
            // Position X.
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-next',
                    'declaration' => sprintf(' %2$s: %1$s; ', $nav_pos_x, $nav_pos_hz),
                ]
            );

            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'declaration' => sprintf(' left: auto; %2$s: %1$s; ', $nav_pos_x, $nav_pos_hz),
                ]
            );

            // position X tablet.
            if (!empty($nav_pos_x_tablet) && $nav_pos_x_responsive_status) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .slick-next',
                        'declaration' => sprintf(' %2$s: %1$s; ', $nav_pos_x_tablet, $nav_pos_hz),
                        'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    ]
                );

                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .slick-prev',
                        'declaration' => sprintf('left: auto; %2$s: %1$s;', $nav_pos_x_tablet, $nav_pos_hz),
                        'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    ]
                );
            }

            // position X phone.
            if (!empty($nav_pos_x_phone) && $nav_pos_x_responsive_status) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .slick-next',
                        'declaration' => sprintf(' %2$s: %1$s; ', $nav_pos_x_phone, $nav_pos_hz),
                        'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    ]
                );

                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .slick-prev',
                        'declaration' => sprintf('left: auto; %2$s: %1$s;', $nav_pos_x_phone, $nav_pos_hz),
                        'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    ]
                );
            }

            // Arrow gap.
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'declaration' => sprintf('margin-%3$s: calc(%1$s + %2$s);', $nav_width, $nav_gap, $nav_pos_hz),
                ]
            );

            // Arrow gap tablet.
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'declaration' => sprintf('margin-%3$s: calc(%1$s + %2$s);', $nav_width_tablet, $nav_gap_tablet, $nav_pos_hz),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]
            );

            // Arrow gap phone.
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-prev',
                    'declaration' => sprintf(' margin-%3$s: calc(%1$s + %2$s); ', $nav_width_phone, $nav_gap_phone, $nav_pos_hz),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );
        }

        // Position Y tablet.
        if (!empty($nav_pos_y_tablet) && $nav_pos_y_responsive_status) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-arrow',
                    'declaration' => sprintf('top: auto; %1$s: %2$s; ', $nav_pos, $nav_pos_y_tablet),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]
            );
        }

        // Position Y phone.
        if (!empty($nav_pos_y_phone) && $nav_pos_y_responsive_status) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .slick-arrow',
                    'declaration' => sprintf('top: auto; %1$s: %2$s;', $nav_pos, $nav_pos_y_phone),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );
        }
    }

    protected function render_pagination_css($render_slug) {
        $pagi_type         = $this->props['pagi_type'];
        $pagi_text         = $this->props['pagi_text'];
        $pagi_color        = $this->props['pagi_color'];
        $pagi_color_hover  = $this->get_hover_value('pagi_color');
        $pagi_bg           = $this->props['pagi_bg'];
        $pagi_bg_hover     = $this->get_hover_value('pagi_bg');
        $pagi_bg_active    = $this->props['pagi_bg_active'];
        $pagi_alignment    = $this->props['pagi_alignment'];
        $pagi_pos_y        = $this->props['pagi_pos_y'];
        $pagi_spacing      = $this->props['pagi_spacing'];
        $pagi_height       = $this->props['pagi_height'];
        $pagi_width        = $this->props['pagi_width'];
        $pagi_width_active = $this->props['pagi_width_active'];
        $pagi_text_active  = $this->props['pagi_text_active'];
        $pagi_radius       = explode('|', $this->props['pagi_radius']);

        if ('dot' === $pagi_type) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-dots li button',
                    'declaration' => 'font-size: 0!important;',
                ]
            );
        } elseif ('number' === $pagi_type) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-dots li button',
                    'declaration' => "font-size: {$pagi_text}!important; color: {$pagi_color}!important;",
                ]
            );

            if (!empty($pagi_color_hover)) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-carousel .slick-dots li:hover button',
                        'declaration' => "color: {$pagi_color_hover}!important;",
                    ]
                );
            }
        }

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-dots',
                'declaration' => sprintf(' text-align: %1$s; transform: translateY(%2$s); ', $pagi_alignment, $pagi_pos_y),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-dots li',
                'declaration' => sprintf(' margin: 0 %1$s;', $pagi_spacing),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-dots li button',
                'declaration' => sprintf(
                    ' background: %1$s; height: %2$s; width: %3$s; border-radius: %4$s %5$s %6$s %7$s;',
                    $pagi_bg,
                    $pagi_height,
                    $pagi_width,
                    $pagi_radius[1],
                    $pagi_radius[2],
                    $pagi_radius[3],
                    $pagi_radius[4]
                ),
            ]
        );

        if (!empty($pagi_bg_hover)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-dots li:hover button',
                    'declaration' => sprintf(' background: %1$s;', $pagi_bg_hover),
                ]
            );
        }

        if (!empty($pagi_bg_active)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-dots li.slick-active button',
                    'declaration' => sprintf('background: %1$s;', $pagi_bg_active),
                ]
            );
        }

        if (!empty($pagi_width_active)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-dots li.slick-active button',
                    'declaration' => sprintf('width: %1$s;', $pagi_width_active),
                ]
            );
        }

        if (!empty($pagi_text_active)) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-dots li.slick-active button',
                    'declaration' => sprintf('color: %1$s;', $pagi_text_active),
                ]
            );
        }
    }

    protected function render_carousel_css($render_slug) {
        $sliding_dir                               = $this->props['sliding_dir'];
        $nav_height                                = $this->props['nav_height'];
        $nav_height_tablet                         = $this->props['nav_height_tablet'];
        $nav_height_phone                          = $this->props['nav_height_phone'];
        $nav_height_last_edited                    = $this->props['nav_height_last_edited'];
        $nav_height_responsive_status              = et_pb_get_responsive_status($nav_height_last_edited);
        $nav_width                                 = $this->props['nav_width'];
        $nav_width_tablet                          = $this->props['nav_width_tablet'] ? $this->props['nav_width_tablet'] : $nav_width;
        $nav_width_phone                           = $this->props['nav_width_phone'] ? $this->props['nav_width_phone'] : $nav_width_tablet;
        $nav_width_last_edited                     = $this->props['nav_width_last_edited'];
        $nav_width_responsive_status               = et_pb_get_responsive_status($nav_width_last_edited);
        $nav_border_width                          = $this->props['nav_border_width'];
        $nav_border_style                          = $this->props['nav_border_style'];
        $nav_border_color                          = $this->props['nav_border_color'];
        $nav_border_color_hover                    = $this->get_hover_value('nav_border_color');
        $nav_color                                 = $this->props['nav_color'];
        $nav_bg                                    = $this->props['nav_bg'];
        $nav_skew                                  = $this->props['nav_skew'];
        $nav_color_hover                           = $this->get_hover_value('nav_color');
        $nav_bg_hover                              = $this->get_hover_value('nav_bg');
        $nav_icon_size_tablet                      = $this->props['nav_icon_size_tablet'];
        $nav_icon_size_phone                       = $this->props['nav_icon_size_phone'];
        $nav_icon_size_last_edited                 = $this->props['nav_icon_size_last_edited'];
        $nav_icon_size_responsive_status           = et_pb_get_responsive_status($nav_icon_size_last_edited);
        $nav_icon_size                             = $this->props['nav_icon_size'];
        $right_border_radius                       = explode('|', $this->props['right_border_radius']);
        $left_border_radius                        = explode('|', $this->props['left_border_radius']);
        $slide_spacing                             = $this->props['slide_spacing'];
        $use_both_side_spacing                     = $this->props['use_both_side_spacing'];
        $is_variable_width                         = $this->props['is_variable_width'];
        $slide_width                               = $this->props['slide_width'];
        $slide_width_tablet                        = $this->props['slide_width_tablet'];
        $slide_width_phone                         = $this->props['slide_width_phone'];
        $slide_width_last_edited                   = $this->props['slide_width_last_edited'];
        $slide_width_responsive_status             = et_pb_get_responsive_status($slide_width_last_edited);
        $nav_type                                  = $this->props['nav_type'];
        $int_skew                                  = intval($this->props['nav_skew']);
        $nav_skew_inner                            = $int_skew < 0 ? abs($int_skew) : '-' . abs($int_skew);
        $carousel_spacing_top                      = $this->props['carousel_spacing_top'];
        $carousel_spacing_top_tablet               = $this->props['carousel_spacing_top_tablet'];
        $carousel_spacing_top_phone                = $this->props['carousel_spacing_top_phone'];
        $carousel_spacing_top_last_edited          = $this->props['carousel_spacing_top_last_edited'];
        $carousel_spacing_top_responsive_status    = et_pb_get_responsive_status($carousel_spacing_top_last_edited);
        $animation_speed                           = $this->props['animation_speed'];
        $carousel_spacing_bottom                   = $this->props['carousel_spacing_bottom'];
        $carousel_spacing_bottom_tablet            = $this->props['carousel_spacing_bottom_tablet'];
        $carousel_spacing_bottom_phone             = $this->props['carousel_spacing_bottom_phone'];
        $carousel_spacing_bottom_last_edited       = $this->props['carousel_spacing_bottom_last_edited'];
        $carousel_spacing_bottom_responsive_status = et_pb_get_responsive_status($carousel_spacing_bottom_last_edited);

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-centered--highlighted .slick-slide',
                'declaration' => sprintf('transition: transform %1$s;', $animation_speed),
            ]
        );

        // Carousel Spacing Top - Bottom.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-track',
                'declaration' => sprintf('padding-top: %1$s; padding-bottom: %2$s;', $carousel_spacing_top, $carousel_spacing_bottom),
            ]
        );

        // Carousel Spacing Top Tablet.
        if ($carousel_spacing_top_tablet && $carousel_spacing_top_responsive_status) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-track',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    'declaration' => sprintf('padding-top: %1$s;', $carousel_spacing_top_tablet),
                ]
            );
        }

        // Carousel Spacing Top Phone.
        if ($carousel_spacing_top_phone && $carousel_spacing_top_responsive_status) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-track',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    'declaration' => sprintf('padding-top: %1$s;', $carousel_spacing_top_phone),
                ]
            );
        }

        // Carousel Spacing Bottom Tablet.
        if ($carousel_spacing_bottom_tablet && $carousel_spacing_bottom_responsive_status) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-track',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    'declaration' => sprintf('padding-bottom: %1$s;', $carousel_spacing_bottom_tablet),
                ]
            );
        }

        // Carousel Spacing Bottom Phone.
        if ($carousel_spacing_bottom_phone && $carousel_spacing_bottom_responsive_status) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-track',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    'declaration' => sprintf('padding-bottom: %1$s;', $carousel_spacing_bottom_phone),
                ]
            );
        }

        // Slide  Width.
        if ('on' === $is_variable_width) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-slide',
                    'declaration' => sprintf('width: %1$s;', $slide_width),
                ]
            );

            // Slide  Width Tablet.
            if (!empty($slide_width_tablet) && $slide_width_responsive_status) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-carousel .slick-slide',
                        'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                        'declaration' => sprintf('width: %1$s;', $slide_width_tablet),
                    ]
                );
            }

            // Slide  Width Phone.
            if (!empty($slide_width_phone) && $slide_width_responsive_status) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => '%%order_class%% .brbl-carousel .slick-slide',
                        'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                        'declaration' => sprintf('width: %1$s;', $slide_width_phone),
                    ]
                );
            }
        }

        // Slide Spacing.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-slide, .et-db #et-boc %%order_class%% .brbl-carousel .slick-slide',
                'declaration' => sprintf(' padding-left: %1$s!important; padding-right: %1$s!important;', $slide_spacing),
            ]
        );

        if ('off' === $use_both_side_spacing) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-list, .et-db #et-boc %%order_class%% .brbl-carousel .slick-list',
                    'declaration' => sprintf(' margin-left: -%1$s!important; margin-right: -%1$s!important;', $slide_spacing),
                ]
            );
        }

        // Arrow.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-arrow',
                'declaration' => sprintf(
                    'height: %1$s; width: %2$s; color: %3$s; background: %4$s; border: %5$s %6$s %7$s; transform: skew(%8$s);margin-top:-%9$spx;',
                    $nav_height,
                    $nav_width,
                    $nav_color,
                    $nav_bg,
                    $nav_border_width,
                    $nav_border_style,
                    $nav_border_color,
                    $nav_skew,
                    (int) $nav_height / 2
                ),
            ]
        );

        // Arrow hover.
        if ($nav_color_hover) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow:hover',
                    'declaration' => sprintf('color: %1$s;', $nav_color_hover),
                ]
            );
        }

        if ($nav_bg_hover) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow:hover',
                    'declaration' => sprintf('background: %1$s;', $nav_bg_hover),
                ]
            );
        }

        if ($nav_border_color_hover) {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow:hover',
                    'declaration' => sprintf('border-color: %1$s;', $nav_border_color_hover),
                ]
            );
        }

        // Arrow Responsive Height.
        if (!empty($nav_height_tablet) && $nav_height_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow',
                    'declaration' => sprintf('height: %1$s; ', $nav_height_tablet),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]
            );
        endif;

        if (!empty($nav_height_phone) && $nav_height_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow',
                    'declaration' => sprintf('height: %1$s; ', $nav_height_phone),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );
        endif;

        // Arrow Responsive Width.
        if (!empty($nav_width_tablet) && $nav_width_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow',
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    'declaration' => sprintf(' width: %1$s; ', $nav_width_tablet),
                ]
            );
        endif;

        if (!empty($nav_width_phone) && $nav_width_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow',
                    'declaration' => sprintf('width: %1$s; ', $nav_width_phone),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );
        endif;

        // Arrow Icon.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-arrow:before',
                'declaration' => sprintf(
                    'font-size: %1$s; transform: skew(%2$sdeg); display: inline-block;',
                    $nav_icon_size,
                    $nav_skew_inner
                ),
            ]
        );

        // Arrow Icon Responsive.
        if (!empty($nav_icon_size_tablet) && $nav_icon_size_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow:before',
                    'declaration' => sprintf(' font-size: %1$s; ', $nav_icon_size_tablet),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]
            );
        endif;

        if (!empty($nav_icon_size_phone) && $nav_icon_size_responsive_status) :
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => '%%order_class%% .brbl-carousel .slick-arrow:before',
                    'declaration' => sprintf(' font-size: %1$s; ', $nav_icon_size_phone),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]
            );
        endif;

        // Arrow Border Radius.
        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-next',
                'declaration' => sprintf(
                    'border-radius: %1$s %2$s %3$s %4$s;',
                    $right_border_radius[1],
                    $right_border_radius[2],
                    $right_border_radius[3],
                    $right_border_radius[4]
                ),
            ]
        );

        ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '%%order_class%% .brbl-carousel .slick-prev',
                'declaration' => sprintf(
                    'border-radius: %1$s %2$s %3$s %4$s;',
                    $left_border_radius[1],
                    $left_border_radius[2],
                    $left_border_radius[3],
                    $left_border_radius[4]
                ),
            ]
        );

        // Navigation.
        brbl_inject_fa_icons($this->props['icon_left']);
        brbl_inject_fa_icons($this->props['icon_right']);

        if (class_exists('ET_Builder_Module_Helper_Style_Processor')) {
            $this->generate_styles(
                [
                    'utility_arg'    => 'icon_font_family',
                    'render_slug'    => $render_slug,
                    'base_attr_name' => 'icon_left',
                    'important'      => true,
                    'selector'       => '%%order_class%% .brbl-carousel .slick-prev:before',
                    'processor'      => [
                        'ET_Builder_Module_Helper_Style_Processor',
                        'process_extended_icon',
                    ],
                ]
            );
        }

        if (class_exists('ET_Builder_Module_Helper_Style_Processor')) {
            $this->generate_styles(
                [
                    'utility_arg'    => 'icon_font_family',
                    'render_slug'    => $render_slug,
                    'base_attr_name' => 'icon_right',
                    'important'      => true,
                    'selector'       => '%%order_class%% .brbl-carousel .slick-next:before',
                    'processor'      => [
                        'ET_Builder_Module_Helper_Style_Processor',
                        'process_extended_icon',
                    ],
                ]
            );
        }

        if ('rtl' === $sliding_dir || 'default' === $nav_type) {
            $this->render_default_nav_css($render_slug);
        }

        if ('ltr' === $sliding_dir && 'alongside' === $nav_type) {
            $this->render_alongside_nav_css($render_slug);
        }

        // Carousel Pagination : Dots.
        $this->render_pagination_css($render_slug);
    }

    protected function get_carousel_options_data() {
        $sliding_dir            = $this->props['sliding_dir'];
        $is_autoplay            = $this->props['is_autoplay'];
        $css_transition         = $this->props['css_transition'];
        $autoplay_speed         = $this->props['autoplay_speed'];
        $animation_speed        = $this->props['animation_speed'];
        $is_center              = $this->props['is_center'];
        $center_mode_type       = $this->props['center_mode_type'];
        $is_infinite            = $this->props['is_infinite'];
        $icon_left              = esc_html(et_pb_process_font_icon($this->props['icon_left']));
        $icon_left              = !empty($icon_left) ? $icon_left : '4';
        $icon_right             = esc_html(et_pb_process_font_icon($this->props['icon_right']));
        $icon_right             = !empty($icon_right) ? $icon_right : '5';
        $is_variable_width      = $this->props['is_variable_width'];
        $wait_for_animate       = $this->props['wait_for_animate'];
        $center_padding         = $this->props['center_padding'];
        $center_padding_tablet  = $this->props['center_padding_tablet'];
        $center_padding_phone   = $this->props['center_padding_phone'];
        $slide_count            = $this->props['slide_count'];
        $slide_count_tablet     = $this->props['slide_count_tablet'];
        $slide_count_phone      = $this->props['slide_count_phone'];
        $is_nav                 = $this->props['use_nav'];
        $is_nav_tablet          = $this->props['use_nav_tablet'];
        $is_nav_phone           = $this->props['use_nav_phone'];
        $is_pagi                = $this->props['use_pagi'];
        $is_pagi_tablet         = $this->props['use_pagi_tablet'];
        $is_pagi_phone          = $this->props['use_pagi_phone'];
        $is_swipe               = $this->props['is_swipe'];
        $is_swipe_tablet        = $this->props['is_swipe_tablet'];
        $is_swipe_phone         = $this->props['is_swipe_phone'];
        $slide_to_scroll        = $this->props['slide_to_scroll'];
        $slide_to_scroll_tablet = $this->props['slide_to_scroll_tablet'];
        $slide_to_scroll_phone  = $this->props['slide_to_scroll_phone'];

        if ('on' === $is_variable_width) {
            $slide_count = 1;
        }

        $settings                   = [];
        $settings['responsive']     = [];
        $tablet                     = [];
        $phone                      = [];
        $settings['rtl']            = 'rtl' === $sliding_dir ? true : false;
        $settings['cssEase']        = $css_transition;
        $settings['swipe']          = 'on' === $is_swipe ? true : false;
        $settings['variableWidth']  = 'on' === $is_variable_width ? true : false;
        $settings['dots']           = 'on' === $is_pagi ? true : false;
        $settings['arrows']         = 'on' === $is_nav ? true : false;
        $settings['infinite']       = 'on' === $is_infinite ? true : false;
        $settings['autoplay']       = 'on' === $is_autoplay ? true : false;
        $settings['autoplaySpeed']  = intval($autoplay_speed);
        $settings['speed']          = intval($animation_speed);
        $settings['slidesToShow']   = intval($slide_count);
        $settings['slidesToScroll'] = intval($slide_to_scroll);
        $settings['centerPadding']  = ('off' === $is_variable_width && 'classic' === $center_mode_type) ? $center_padding : 0;
        $settings['centerMode']     = 'on' === $is_center ? true : false;
        $settings['prevArrow']      = '<button type="button" data-icon="' . $icon_left . '" class="slick-arrow slick-prev">Prev</button>';
        $settings['nextArrow']      = '<button type="button" data-icon="' . $icon_right . '" class="slick-arrow slick-next">Prev</button>';

        if ('off' === $wait_for_animate) {
            $settings['waitForAnimate'] = false;
        }

        // Responsive break point 980.
        $tablet['breakpoint'] = 980;

        if ('off' === $is_variable_width && !empty($slide_count_tablet)) {
            $tablet['settings']['slidesToShow'] = intval($slide_count_tablet);
        }

        if (!empty($slide_to_scroll_tablet)) {
            $tablet['settings']['slidesToScroll'] = intval($slide_to_scroll_tablet);
        }

        if (!empty($is_pagi_tablet)) {
            $tablet['settings']['dots'] = 'on' === $is_pagi_tablet ? true : false;
        }

        if (!empty($is_nav_tablet)) {
            $tablet['settings']['arrows'] = 'on' === $is_nav_tablet ? true : false;
        }

        if (!empty($is_swipe_tablet)) {
            $tablet['settings']['swipe'] = 'on' === $is_swipe_tablet ? true : false;
        }

        if ('off' === $is_variable_width && 'classic' === $center_mode_type && !empty($center_padding_tablet)) {
            $tablet['settings']['centerPadding'] = $center_padding_tablet;
        }

        array_push($settings['responsive'], $tablet);

        // Responsive break point 767.
        $phone['breakpoint'] = 767;

        if ('off' === $is_variable_width && !empty($slide_count_phone)) {
            $phone['settings']['slidesToShow'] = $slide_count_phone;
        }

        if (!empty($slide_to_scroll_phone)) {
            $phone['settings']['slidesToScroll'] = intval($slide_to_scroll_phone);
        }

        if (!empty($is_pagi_phone)) {
            $phone['settings']['dots'] = 'on' === $is_pagi_phone ? true : false;
        }

        if (!empty($is_nav_phone)) {
            $phone['settings']['arrows'] = 'on' === $is_nav_phone ? true : false;
        }

        if (!empty($is_swipe_phone)) {
            $phone['settings']['swipe'] = 'on' === $is_swipe_phone ? true : false;
        }

        if ('off' === $is_variable_width && 'classic' === $center_mode_type && !empty($center_padding_phone)) {
            $phone['settings']['centerPadding'] = $center_padding_phone;
        }

        array_push($settings['responsive'], $phone);

        $carousel_options = sprintf('data-settings="%1$s"', htmlspecialchars(wp_json_encode($settings), ENT_QUOTES, 'UTF-8'));

        return $carousel_options;
    }

    protected function brbl_get_button_styles($prefix, $render_slug, $selector) {
        $custom_padding                   = $this->props["{$prefix}_custom_padding"];
        $custom_padding_tablet            = $this->props["{$prefix}_custom_padding_tablet"];
        $custom_padding_phone             = $this->props["{$prefix}_custom_padding_phone"];
        $custom_padding_hover             = $this->get_hover_value("{$prefix}_custom_padding");
        $custom_padding_last_edited       = $this->props["{$prefix}_custom_padding_last_edited"];
        $custom_padding_responsive_status = et_pb_get_responsive_status($custom_padding_last_edited);
        $icon_placement                   = $this->props["{$prefix}_icon_placement"];
        $use_icon                         = $this->props["{$prefix}_use_icon"];
        $is_custom                        = $this->props["custom_{$prefix}"];

        if ('on' === $is_custom) {
            if (!empty($custom_padding)) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => "body #page-container {$selector}, .et-db #et-boc {$selector}",
                        'declaration' => $this->brbl_process_margin_padding($custom_padding, 'padding', true),
                    ]
                );
            }

            if (!empty($custom_padding_hover)) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => "body #page-container {$selector}:hover, .et-db #et-boc {$selector}:hover",
                        'declaration' => $this->brbl_process_margin_padding($custom_padding_hover, 'padding', true),
                    ]
                );
            } else {
                if (!empty($custom_padding)) {
                    ET_Builder_Element::set_style(
                        $render_slug,
                        [
                            'selector'    => "body #page-container {$selector}:hover, .et-db #et-boc {$selector}:hover",
                            'declaration' => $this->brbl_process_margin_padding($custom_padding, 'padding', true),
                        ]
                    );
                }
            }

            if (!empty($custom_padding_tablet) && $custom_padding_responsive_status) :
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => "body #page-container {$selector}, .et-db #et-boc {$selector}",
                        'declaration' => $this->brbl_process_margin_padding($custom_padding_tablet, 'padding', true),
                        'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                    ]
                );
            endif;

            if (!empty($custom_padding_phone) && $custom_padding_responsive_status) :
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => "body #page-container {$selector}, .et-db #et-boc {$selector}",
                        'declaration' => $this->brbl_process_margin_padding($custom_padding_phone, 'padding', true),
                        'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                    ]
                );
            endif;

            if ('on' === $use_icon && 'right' === $icon_placement) {
                ET_Builder_Element::set_style(
                    $render_slug,
                    [
                        'selector'    => "body #page-container {$selector}:after, .et-db #et-boc {$selector}:after",
                        'declaration' => '
                        display: inline-block;
                        content: attr(data-icon)!important;
                        font-family: "ETmodules"!important;',
                    ]
                );
            }
        } else {
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => $selector,
                    'declaration' => '
						padding: 0;
						border: 0;
						font-size: 16px;
						color: #333;
						position: relative;
						z-index: 9;',
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => $selector . ':hover',
                    'declaration' => '
						padding: 0;
						border: 0;',
                ]
            );
            ET_Builder_Element::set_style(
                $render_slug,
                [
                    'selector'    => $selector . ':after,' . $selector . ':before',
                    'declaration' => 'display: none;',
                ]
            );
        }
    }
}
