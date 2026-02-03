<?php
function wpb_custom_register_shortcodes(){
vc_map( array(
	'name'     => __('Recent Posts Scroller', 'MergePress'),
	'base'     => 'recent_posts_scroller',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'merge_number',
			'heading'     => __( 'Posts to show', 'MergePress' ),
			'param_name'  => 'number',
'value'          => '2',
'save_always'          => true,
'description'          => __('Number of posts to display at a time', 'MergePress'),
		),
		array(
			'type'        => 'merge_number',
			'heading'     => __( 'Speed', 'MergePress' ),
			'param_name'  => 'speed',
'value'          => '500',
'save_always'          => true,
'description'          => __('Spped of the scroller', 'MergePress'),
		),
		array(
			'type'        => 'merge_number',
			'heading'     => __( 'Number of posts', 'MergePress' ),
			'param_name'  => 'posts',
'value'          => '3',
'save_always'          => true,
'description'          => __('Number of posts to scroll through', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Post Quote', 'MergePress'),
	'base'     => 'quote',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Text', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
'description'          => __('Quote text', 'MergePress'),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => __( 'Color', 'MergePress' ),
			'param_name'  => 'color',
'value'          => '#c7081b',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Animated Numbers', 'MergePress'),
	'base'     => 'animated_numbers',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Icon', 'MergePress' ),
			'param_name'  => 'icon',
'save_always'          => true,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'),
		),
		array(
			'type'        => 'merge_number',
			'heading'     => __( 'Number', 'MergePress' ),
			'param_name'  => 'number',
'save_always'          => true,
'description'          => __('The number to animate to', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Before Number', 'MergePress' ),
			'param_name'  => 'before_number',
'save_always'          => true,
'description'          => __('Display text before the number ($, €, #)', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'After Number', 'MergePress' ),
			'param_name'  => 'after_number',
'save_always'          => true,
'description'          => __('Display text after the number (%, !)', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Seperator Value', 'MergePress' ),
			'param_name'  => 'separator_value',
'save_always'          => true,
'description'          => __('Change the value used to break up large numbers, defaults to a \',\'', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Align', 'MergePress' ),
			'param_name'  => 'align',
'value'          => [
'Left' => __('left', 'MergePress'),
'Center' => __('center', 'MergePress'),
'Right' => __('right', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Featured Icon Box', 'MergePress'),
	'base'     => 'featured_icon_box',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Content', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
'description'          => __('Title of the featured box', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Icon', 'MergePress' ),
			'param_name'  => 'icon',
'save_always'          => true,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Progress Bar', 'MergePress'),
	'base'     => 'progress_bar',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Content', 'MergePress' ),
			'param_name'  => 'content',
'value'          => 'Text',
'save_always'          => true,
'description'          => __('Text to be displayed inside the progress bar', 'MergePress'),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => __( 'Color', 'MergePress' ),
			'param_name'  => 'color',
'value'          => 'Color of progress bar',
'save_always'          => true,
'description'          => __('#c7081b', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Filled', 'MergePress' ),
			'param_name'  => 'filled',
'value'          => '90%',
'save_always'          => true,
'description'          => __('The percentage of the progress bar filled with color', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('List', 'MergePress'),
	'base'     => 'list',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_parent'          => [
'only' => __('list_item', 'MergePress'),
]
,
'content_element'          => true,
'js_view'          => 'VcColumnView',
'show_settings_on_create'          => false,
	'params'   => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Style', 'MergePress' ),
			'param_name'  => 'style',
'value'          => [
'Arrows' => __('arrows', 'MergePress'),
'Checkboxes' => __('checkboxes', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Style of dropdown', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('List Item', 'MergePress'),
	'base'     => 'list_item',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_child'          => [
'only' => __('list', 'MergePress'),
]
,
'content_element'          => true,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Icon', 'MergePress' ),
			'param_name'  => 'icon',
'save_always'          => true,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'),
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Text', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
'description'          => __('List item content', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Parallax', 'MergePress'),
	'base'     => 'parallax_section',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_parent'          => [
'only' => __('vc_row', 'MergePress'),
]
,
'content_element'          => true,
'js_view'          => 'VcColumnView',
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Velocity', 'MergePress' ),
			'param_name'  => 'velocity',
'value'          => [
'0' => __('-.3', 'MergePress'),
'1' => __('-.2', 'MergePress'),
'2' => __('-.1', 'MergePress'),
'3' => __('.1', 'MergePress'),
'4' => __('.2', 'MergePress'),
'5' => __('.3', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('How fast do you want the parallax to go (and which direction!)', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Offset', 'MergePress' ),
			'param_name'  => 'offset',
'save_always'          => true,
'description'          => __('Amount of offset (px)', 'MergePress'),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'MergePress' ),
			'param_name'  => 'image',
'save_always'          => true,
'description'          => __('Image used for scrolling', 'MergePress'),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => __( 'Overlay Color', 'MergePress' ),
			'param_name'  => 'overlay_color',
'save_always'          => true,
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => __( 'Text Color', 'MergePress' ),
			'param_name'  => 'text_color',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Testimonails', 'MergePress'),
	'base'     => 'testimonials',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_parent'          => [
'only' => __('testimonial_quote', 'MergePress'),
]
,
'content_element'          => true,
'js_view'          => 'VcColumnView',
'show_settings_on_create'          => false,
	'params'   => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Text', 'MergePress' ),
			'param_name'  => 'slide',
'value'          => [
'0' => __('horizontal', 'MergePress'),
'1' => __('vertical', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('This will control which way the testimonials slide', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Speed', 'MergePress' ),
			'param_name'  => 'speed',
'value'          => '500',
'save_always'          => true,
'description'          => __('This controls the speed at which the testimonials slide', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Testimonial Quote', 'MergePress'),
	'base'     => 'testimonial_quote',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_child'          => [
'only' => __('testimonials', 'MergePress'),
]
,
'content_element'          => true,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Name', 'MergePress' ),
			'param_name'  => 'name',
'save_always'          => true,
'description'          => __('Name of person giving the testimonial', 'MergePress'),
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Quote', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
'description'          => __('Testimonial quote', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('FAQ', 'MergePress'),
	'base'     => 'faq',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_parent'          => [
'only' => __('toggle', 'MergePress'),
]
,
'content_element'          => true,
'js_view'          => 'VcColumnView',
'show_settings_on_create'          => false,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Categories', 'MergePress' ),
			'param_name'  => 'categories',
'value'          => 'category1, category2, etc',
'save_always'          => true,
'description'          => __('Comma separated list of categories', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Sort By Element', 'MergePress' ),
			'param_name'  => 'sort_element',
'value'          => [
'Yes' => __('yes', 'MergePress'),
'No' => __('no', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Display the sort by element', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Sort Text', 'MergePress' ),
			'param_name'  => 'sort_text',
'value'          => 'Sort FAQ by:',
'save_always'          => true,
'description'          => __('This text is displayed beside the categories', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('FAQ Item', 'MergePress'),
	'base'     => 'toggle',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_child'          => [
'only' => __('toggle', 'MergePress'),
]
,
'content_element'          => true,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
'description'          => __('Title of FAQ item', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Categories', 'MergePress' ),
			'param_name'  => 'categories',
'value'          => 'category1, category2, etc',
'save_always'          => true,
'description'          => __('Comma seperated list of categories item is in', 'MergePress'),
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'FAQ Content', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'State of the item', 'MergePress' ),
			'param_name'  => 'state',
'value'          => [
'Closed' => __('collapsed', 'MergePress'),
'Open' => __('in', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Choose whether the item is open or close', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Pricing Table', 'MergePress'),
	'base'     => 'pricing_table',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_parent'          => [
'only' => __('pricing_option', 'MergePress'),
]
,
'content_element'          => true,
'js_view'          => 'VcColumnView',
'show_settings_on_create'          => true,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => __( 'Header Color', 'MergePress' ),
			'param_name'  => 'header_color',
'save_always'          => true,
'description'          => __('Customize the color of the pricing header', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Price', 'MergePress' ),
			'param_name'  => 'price',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Often', 'MergePress' ),
			'param_name'  => 'often',
'value'          => 'mo',
'save_always'          => true,
'description'          => __('How often the payment is made', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Button Text', 'MergePress' ),
			'param_name'  => 'button',
'value'          => 'Sign Up Now',
'save_always'          => true,
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Link', 'MergePress' ),
			'param_name'  => 'link',
'save_always'          => true,
'description'          => __('Link brand to URL', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Pricing Option', 'MergePress'),
	'base'     => 'pricing_option',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_child'          => [
'only' => __('pricing_table', 'MergePress'),
]
,
'content_element'          => true,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Option Text', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
'description'          => __('An option this pricing table includes', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Staff Person', 'MergePress'),
	'base'     => 'person',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Name', 'MergePress' ),
			'param_name'  => 'name',
'save_always'          => true,
'description'          => __('Name of person', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Position', 'MergePress' ),
			'param_name'  => 'position',
'save_always'          => true,
'description'          => __('Position of person', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Phone', 'MergePress' ),
			'param_name'  => 'phone',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Cell Phone', 'MergePress' ),
			'param_name'  => 'cell_phone',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Email', 'MergePress' ),
			'param_name'  => 'email',
'save_always'          => true,
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'MergePress' ),
			'param_name'  => 'img',
'save_always'          => true,
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Larger Image', 'MergePress' ),
			'param_name'  => 'hoverimg',
'save_always'          => true,
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Description', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Facebook', 'MergePress' ),
			'param_name'  => 'facebook',
'save_always'          => true,
'description'          => __('URL to Facebook profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Twitter', 'MergePress' ),
			'param_name'  => 'twitter',
'save_always'          => true,
'description'          => __('URL to Twitter profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'YouTube', 'MergePress' ),
			'param_name'  => 'youtube',
'save_always'          => true,
'description'          => __('URL to YouTube profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Linkedin', 'MergePress' ),
			'param_name'  => 'vimeo',
'save_always'          => true,
'description'          => __('URL to Linkedin profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Vimeo', 'MergePress' ),
			'param_name'  => 'linkedin',
'save_always'          => true,
'description'          => __('URL to Vimeo profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'RSS', 'MergePress' ),
			'param_name'  => 'rss',
'save_always'          => true,
'description'          => __('URL to RSS profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Flickr', 'MergePress' ),
			'param_name'  => 'flickr',
'save_always'          => true,
'description'          => __('URL to Flickr profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Skype', 'MergePress' ),
			'param_name'  => 'skype',
'save_always'          => true,
'description'          => __('URL to Skype profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Google', 'MergePress' ),
			'param_name'  => 'google',
'save_always'          => true,
'description'          => __('URL to Google profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Pinterest', 'MergePress' ),
			'param_name'  => 'pinterest',
'save_always'          => true,
'description'          => __('URL to Pinteret profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Instagram', 'MergePress' ),
			'param_name'  => 'instagram',
'save_always'          => true,
'description'          => __('URL to Instagram profile', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Yelp', 'MergePress' ),
			'param_name'  => 'yelp',
'save_always'          => true,
'description'          => __('URL to Yelp profile', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Featured Panel', 'MergePress'),
	'base'     => 'featured_panel',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
'description'          => __('Title of panel', 'MergePress'),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'MergePress' ),
			'param_name'  => 'icon',
'save_always'          => true,
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Hover Image', 'MergePress' ),
			'param_name'  => 'hover_icon',
'save_always'          => true,
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Link the image', 'MergePress' ),
			'param_name'  => 'image_link',
'save_always'          => true,
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Content', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Detailed Panel', 'MergePress'),
	'base'     => 'detailed_panel',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Icon', 'MergePress' ),
			'param_name'  => 'icon',
'save_always'          => true,
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'MergePress' ),
			'param_name'  => 'image',
'save_always'          => true,
'description'          => __('This will overwrite the icon setting', 'MergePress'),
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Link', 'MergePress' ),
			'param_name'  => 'link',
'save_always'          => true,
'description'          => __('Link for the title and icon', 'MergePress'),
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Content', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Portfolio', 'MergePress'),
	'base'     => 'portfolio',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Categories', 'MergePress' ),
			'param_name'  => 'categories',
'value'          => [
'Convertible<br>' => __('Convertible', 'MergePress'),
'Coupe<br>' => __('Coupe', 'MergePress'),
'Hardtop<br>' => __('Hardtop', 'MergePress'),
'Sedan<br>' => __('Sedan', 'MergePress'),
'Sports Car<br>' => __('Sports Car', 'MergePress'),
'SUV<br>' => __('SUV', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Display these sortable categories', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Type', 'MergePress' ),
			'param_name'  => 'type',
'value'          => [
'Details' => __('details', 'MergePress'),
'Classic' => __('classic', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'All Category', 'MergePress' ),
			'param_name'  => 'all_category',
'value'          => [
'Yes' => __('yes', 'MergePress'),
'No' => __('no', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Display the all category', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Portfolio', 'MergePress' ),
			'param_name'  => 'portfolio',
'value'          => [
'Portfolio 2' => __('31', 'MergePress'),
'Portfolio 3' => __('32', 'MergePress'),
'Portfolio 4' => __('30', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Which portfolio to display', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Sort by Element', 'MergePress' ),
			'param_name'  => 'sort_element',
'value'          => [
'Yes' => __('yes', 'MergePress'),
'No' => __('no', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Display the sort by element', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Sort Text', 'MergePress' ),
			'param_name'  => 'sort_text',
'save_always'          => true,
'description'          => __('Change the text beside the categories', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Columns', 'MergePress' ),
			'param_name'  => 'columns',
'value'          => [
'0' => __('1', 'MergePress'),
'1' => __('2', 'MergePress'),
'2' => __('3', 'MergePress'),
'3' => __('4', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Change the text beside the categories', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Order By', 'MergePress' ),
			'param_name'  => 'order_by',
'value'          => [
'Ascending' => __('ASC', 'MergePress'),
'Descending' => __('DESC', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Change the order of the portfolio items', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Alert', 'MergePress'),
	'base'     => 'alert',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Type', 'MergePress' ),
			'param_name'  => 'type',
'value'          => [
'Danger' => __('0', 'MergePress'),
'Success' => __('1', 'MergePress'),
'Info' => __('2', 'MergePress'),
'Warning' => __('3', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Type of alert', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Close Button', 'MergePress' ),
			'param_name'  => 'close',
'value'          => [
'0' => __('No', 'MergePress'),
'1' => __('Yes', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Content', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
'description'          => __('Alert content', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Search Inventory Box', 'MergePress'),
	'base'     => 'search_inventory_box',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Column 1', 'MergePress' ),
			'param_name'  => 'column_1',
'value'          => [
'Year<br>' => __('year', 'MergePress'),
'Make<br>' => __('make', 'MergePress'),
'Model<br>' => __('model', 'MergePress'),
'Body Style<br>' => __('body-style', 'MergePress'),
'Mileage<br>' => __('mileage', 'MergePress'),
'Transmission<br>' => __('transmission', 'MergePress'),
'Fuel Economy<br>' => __('fuel-economy', 'MergePress'),
'Condition<br>' => __('condition', 'MergePress'),
'Location<br>' => __('location', 'MergePress'),
'Price<br>' => __('price', 'MergePress'),
'Drivetrain<br>' => __('drivetrain', 'MergePress'),
'Engine<br>' => __('engine', 'MergePress'),
'Exterior Color<br>' => __('exterior-color', 'MergePress'),
'Interior Color<br>' => __('interior-color', 'MergePress'),
'MPG<br>' => __('mpg', 'MergePress'),
'Stock Number<br>' => __('stock-number', 'MergePress'),
'VIN Number<br>' => __('vin-number', 'MergePress'),
'Search Box<br>' => __('Search', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Dropdowns that will be shown in the first column (leave column 2 blank to extend this column to fullwidth)', 'MergePress'),
		),
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Column 2', 'MergePress' ),
			'param_name'  => 'column_2',
'value'          => [
'Year<br>' => __('year', 'MergePress'),
'Make<br>' => __('make', 'MergePress'),
'Model<br>' => __('model', 'MergePress'),
'Body Style<br>' => __('body-style', 'MergePress'),
'Mileage<br>' => __('mileage', 'MergePress'),
'Transmission<br>' => __('transmission', 'MergePress'),
'Fuel Economy<br>' => __('fuel-economy', 'MergePress'),
'Condition<br>' => __('condition', 'MergePress'),
'Location<br>' => __('location', 'MergePress'),
'Price<br>' => __('price', 'MergePress'),
'Drivetrain<br>' => __('drivetrain', 'MergePress'),
'Engine<br>' => __('engine', 'MergePress'),
'Exterior Color<br>' => __('exterior-color', 'MergePress'),
'Interior Color<br>' => __('interior-color', 'MergePress'),
'MPG<br>' => __('mpg', 'MergePress'),
'Stock Number<br>' => __('stock-number', 'MergePress'),
'VIN Number<br>' => __('vin-number', 'MergePress'),
'Search Box<br>' => __('Search', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Dropdowns that will be shown in the second column', 'MergePress'),
		),
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Min/Max Values', 'MergePress' ),
			'param_name'  => 'min_max',
'value'          => [
'Year<br>' => __('year', 'MergePress'),
'Make<br>' => __('make', 'MergePress'),
'Model<br>' => __('model', 'MergePress'),
'Body Style<br>' => __('body-style', 'MergePress'),
'Mileage<br>' => __('mileage', 'MergePress'),
'Transmission<br>' => __('transmission', 'MergePress'),
'Fuel Economy<br>' => __('fuel-economy', 'MergePress'),
'Condition<br>' => __('condition', 'MergePress'),
'Location<br>' => __('location', 'MergePress'),
'Price<br>' => __('price', 'MergePress'),
'Drivetrain<br>' => __('drivetrain', 'MergePress'),
'Engine<br>' => __('engine', 'MergePress'),
'Exterior Color<br>' => __('exterior-color', 'MergePress'),
'Interior Color<br>' => __('interior-color', 'MergePress'),
'MPG<br>' => __('mpg', 'MergePress'),
'Stock Number<br>' => __('stock-number', 'MergePress'),
'VIN Number<br>' => __('vin-number', 'MergePress'),
'Search Box<br>' => __('Search', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Only choose values you checked in the previous options and only for categories that use <strong>numbers</strong> as values', 'MergePress'),
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Form action', 'MergePress' ),
			'param_name'  => 'page_id',
'save_always'          => true,
'description'          => __('Choose the Inventory page which you want to display the search results', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Listing Category Term', 'MergePress' ),
			'param_name'  => 'term_form',
'value'          => [
'Singular' => __('singular', 'MergePress'),
'Plural' => __('plural', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Adjust whether the listing dropdown uses the singular form or plural form of the listing category.', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Prefix text', 'MergePress' ),
			'param_name'  => 'prefix_text',
'save_always'          => true,
'description'          => __('Adjust the text before the listing category in the dropdown', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Button Text', 'MergePress' ),
			'param_name'  => 'button_text',
'value'          => 'Find My New Vehicle',
'save_always'          => true,
'description'          => __('Adjust the button text to submit the form', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Button', 'MergePress'),
	'base'     => 'button',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Button Text', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Button Size', 'MergePress' ),
			'param_name'  => 'size',
'value'          => [
'Extra Small' => __('xs', 'MergePress'),
'Small' => __('sm', 'MergePress'),
'Medium' => __('md', 'MergePress'),
'Large' => __('lg', 'MergePress'),
'Extra Large' => __('xl', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Button Alignment', 'MergePress' ),
			'param_name'  => 'align',
'value'          => [
'Left' => __('left', 'MergePress'),
'Center' => __('center', 'MergePress'),
'Right' => __('right', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Link', 'MergePress' ),
			'param_name'  => 'href',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Dropcaps', 'MergePress'),
	'base'     => 'dropcaps',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Dropcap Letter', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('YouTube/Vimeo video', 'MergePress'),
	'base'     => 'auto_video',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'URL to video', 'MergePress' ),
			'param_name'  => 'url',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Width', 'MergePress' ),
			'param_name'  => 'width',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Height', 'MergePress' ),
			'param_name'  => 'height',
'save_always'          => true,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Video Quality', 'MergePress' ),
			'param_name'  => 'vq',
'value'          => [
'144p' => __('tiny', 'MergePress'),
'240p' => __('small', 'MergePress'),
'360p' => __('medium', 'MergePress'),
'480p' => __('large', 'MergePress'),
'720p' => __('hd720', 'MergePress'),
'1080p' => __('hd1080', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Heading', 'MergePress'),
	'base'     => 'heading',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Heading', 'MergePress' ),
			'param_name'  => 'heading',
'value'          => [
'Heading 1' => __('h1', 'MergePress'),
'Heading 2' => __('h2', 'MergePress'),
'Heading 3' => __('h3', 'MergePress'),
'Heading 4' => __('h4', 'MergePress'),
'Heading 5' => __('h5', 'MergePress'),
'Heading 6' => __('h6', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Size of heading', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Text', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
'description'          => __('Heading Text', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Car Comparison', 'MergePress'),
	'base'     => 'car_comparison',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Car ID\'s', 'MergePress' ),
			'param_name'  => 'car_ids',
'save_always'          => true,
'description'          => __('Comma seperated ID\'s of vehicles, if no car ID\'s are set it will automatically grab cars checked by user in inventory.', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Contact Form', 'MergePress'),
	'base'     => 'auto_contact_form',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Name Placeholder', 'MergePress' ),
			'param_name'  => 'name',
'save_always'          => true,
'description'          => __('This is the text \'behind\' the name textfield.', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Email Placeholder', 'MergePress' ),
			'param_name'  => 'email',
'save_always'          => true,
'description'          => __('This is the text \'behind\' the email textfield.', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Message Placeholder', 'MergePress' ),
			'param_name'  => 'message',
'save_always'          => true,
'description'          => __('This is the text \'behind\' the message textbox', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Submit Button', 'MergePress' ),
			'param_name'  => 'button',
'save_always'          => true,
'description'          => __('This is the text used on the submit button', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Hours Table', 'MergePress'),
	'base'     => 'hours_table',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
'description'          => __('Title of the hours table', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Monday', 'MergePress' ),
			'param_name'  => 'mon',
'save_always'          => true,
'description'          => __('Monday Hours', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Tuesday', 'MergePress' ),
			'param_name'  => 'tue',
'save_always'          => true,
'description'          => __('Tuesday Hours', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Wednesday', 'MergePress' ),
			'param_name'  => 'wed',
'save_always'          => true,
'description'          => __('Wednesday Hours', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Thursday', 'MergePress' ),
			'param_name'  => 'thu',
'save_always'          => true,
'description'          => __('Thursday Hours', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Friday', 'MergePress' ),
			'param_name'  => 'fri',
'save_always'          => true,
'description'          => __('Friday Hours', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Saturday', 'MergePress' ),
			'param_name'  => 'sat',
'save_always'          => true,
'description'          => __('Saturday Hours', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Sunday', 'MergePress' ),
			'param_name'  => 'sun',
'save_always'          => true,
'description'          => __('Sunday Hours', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Social Icons', 'MergePress'),
	'base'     => 'automotive_social_icons_shortcode',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Alignment', 'MergePress' ),
			'param_name'  => 'align',
'value'          => [
'Left' => __('left', 'MergePress'),
'Right' => __('right', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Change the style of the Google Map', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Contact Information', 'MergePress'),
	'base'     => 'auto_contact_information',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Company Name', 'MergePress' ),
			'param_name'  => 'company',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Address', 'MergePress' ),
			'param_name'  => 'address',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Phone', 'MergePress' ),
			'param_name'  => 'phone',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Fax', 'MergePress' ),
			'param_name'  => 'fax',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Email', 'MergePress' ),
			'param_name'  => 'email',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Website', 'MergePress' ),
			'param_name'  => 'website',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Google Map', 'MergePress'),
	'base'     => 'auto_google_map',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Latitude', 'MergePress' ),
			'param_name'  => 'latitude',
'save_always'          => true,
'description'          => __('Latitude of google map', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Longitude', 'MergePress' ),
			'param_name'  => 'longitude',
'save_always'          => true,
'description'          => __('Longitude of google map', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Zoom', 'MergePress' ),
			'param_name'  => 'zoom',
'save_always'          => true,
'description'          => __('Zoom of google map', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Height', 'MergePress' ),
			'param_name'  => 'height',
'save_always'          => true,
'description'          => __('Height of google map', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Type', 'MergePress' ),
			'param_name'  => 'map_type',
'value'          => [
'Roadmap' => __('roadmap', 'MergePress'),
'Satellite' => __('satellite', 'MergePress'),
'Hybrid' => __('hybrid', 'MergePress'),
'Terrain' => __('terrain', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Change the style of the Google Map', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Scrolling to zoom', 'MergePress' ),
			'param_name'  => 'scrolling',
'value'          => [
'On' => __('true', 'MergePress'),
'Off' => __('false', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Turn off the scrolling to zoom function on google map, useful for fullscreen maps', 'MergePress'),
		),
		array(
			'type'        => 'textarea_raw_html',
			'heading'     => __( 'Style', 'MergePress' ),
			'param_name'  => 'map_style',
'save_always'          => true,
'description'          => __('Style of google map, styles availiable at: <a href=\'http://snazzymaps.com/\' target=\'_blank\'>http://snazzymaps.com/</a>', 'MergePress'),
		),
		array(
			'type'        => 'textarea_raw_html',
			'heading'     => __( 'Info Window Content', 'MergePress' ),
			'param_name'  => 'info_window_content',
'save_always'          => true,
'description'          => __('Enter in content to be used in the info window once the user clicks the marker', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Directions Button', 'MergePress' ),
			'param_name'  => 'directions_button',
'value'          => [
'On' => __('true', 'MergePress'),
'Off' => __('false', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Parallax Disabled', 'MergePress' ),
			'param_name'  => 'parallax_disabled',
'value'          => [
'Disabled<br>' => __('disabled', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Check this option to disable the parallax', 'MergePress'),
		),
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Scrolling Disabled', 'MergePress' ),
			'param_name'  => 'scrolling_disabled',
'value'          => [
'Disabled<br>' => __('disabled', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Check this option to disable users scrolling around the map', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Flipping Card', 'MergePress'),
	'base'     => 'flipping_card',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'MergePress' ),
			'param_name'  => 'image',
'save_always'          => true,
'description'          => __('This image will be shown on the front of the flipping card', 'MergePress'),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Larger Image', 'MergePress' ),
			'param_name'  => 'larger_img',
'save_always'          => true,
'description'          => __('This image will open in a fancybox', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Link', 'MergePress' ),
			'param_name'  => 'link',
'save_always'          => true,
'description'          => __('Link of link button on flipped side', 'MergePress'),
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Card Link', 'MergePress' ),
			'param_name'  => 'card_link',
'save_always'          => true,
'description'          => __('Link the entire flipping card', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Icon & Title', 'MergePress'),
	'base'     => 'icon_title',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Icon', 'MergePress' ),
			'param_name'  => 'icon',
'save_always'          => true,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'),
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Link', 'MergePress' ),
			'param_name'  => 'link',
'save_always'          => true,
'description'          => __('Link of the icon', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Tabs', 'MergePress'),
	'base'     => 'tabs',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_parent'          => [
'only' => __('tab', 'MergePress'),
]
,
'content_element'          => true,
'js_view'          => 'VcColumnView',
'show_settings_on_create'          => false,
	'params'   => array(
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Clearfix', 'MergePress'),
	'base'     => 'clearfix',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'show_settings_on_create'          => false,
	'params'   => array(
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Line Break', 'MergePress'),
	'base'     => 'br',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'show_settings_on_create'          => false,
	'params'   => array(
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Single Tab', 'MergePress'),
	'base'     => 'tab',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_child'          => [
'only' => __('tabs', 'MergePress'),
]
,
'content_element'          => true,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
'description'          => __('Tab title', 'MergePress'),
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Content', 'MergePress' ),
			'param_name'  => 'content',
'save_always'          => true,
'description'          => __('Tab Content', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Automotive Form', 'MergePress'),
	'base'     => 'automotive_form',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Form', 'MergePress' ),
			'param_name'  => 'form',
'value'          => [
'Choose a form' => __('', 'MergePress'),
'Request More Info' => __('request', 'MergePress'),
'Schedule Test Drive' => __('schedule', 'MergePress'),
'Make an Offer' => __('make_offer', 'MergePress'),
'Trade-In Appraisal' => __('trade_in', 'MergePress'),
'Email to a Friend' => __('email_friend', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Choose which form will be displayed', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Featured Brands', 'MergePress'),
	'base'     => 'featured_brands',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_parent'          => [
'only' => __('brand_logo', 'MergePress'),
]
,
'content_element'          => true,
'js_view'          => 'VcColumnView',
'show_settings_on_create'          => false,
	'params'   => array(
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Brand Item', 'MergePress'),
	'base'     => 'brand_logo',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
'as_child'          => [
'only' => __('featured_brands', 'MergePress'),
]
,
'content_element'          => true,
	'params'   => array(
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'MergePress' ),
			'param_name'  => 'img',
'save_always'          => true,
'description'          => __('The image shown on load', 'MergePress'),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Hover Image', 'MergePress' ),
			'param_name'  => 'hoverimg',
'save_always'          => true,
'description'          => __('Image shown when hovering over the brand', 'MergePress'),
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'Link', 'MergePress' ),
			'param_name'  => 'link',
'save_always'          => true,
'description'          => __('Link brand to URL', 'MergePress'),
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Inventory', 'MergePress'),
	'base'     => 'inventory_display',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'a',
			'heading'     => __( 'a', 'MergePress' ),
			'param_name'  => 'a',
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

vc_map( array(
	'name'     => __('Vehicle Scroller', 'MergePress'),
	'base'     => 'vehicle_scroller',
	'class'    => '',
	'icon'     => 'fa fa-code',
	'category' => __( 'Automotive', 'MergePress' ),
'description'          => __('', 'MergePress'),
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'MergePress' ),
			'param_name'  => 'title',
'save_always'          => true,
'description'          => __('Title of vehicle scroller', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Description', 'MergePress' ),
			'param_name'  => 'description',
'save_always'          => true,
'description'          => __('Small description of vehicles being displayed', 'MergePress'),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Sort By', 'MergePress' ),
			'param_name'  => 'sort',
'value'          => [
'Newest' => __('newest', 'MergePress'),
'Oldest' => __('oldest', 'MergePress'),
]
,
'save_always'          => true,
'description'          => __('Sort the vehicles', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Vehicles to show', 'MergePress' ),
			'param_name'  => 'listings',
'save_always'          => true,
'description'          => __('Comma seperated list of vehicle ID\'s to display', 'MergePress'),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Vehicle Limit', 'MergePress' ),
			'param_name'  => 'limit',
'save_always'          => true,
'description'          => __('The number of vehicles to show. (-1 to display all)', 'MergePress'),
		),
		array(
			'type'        => 'checkbox',
			'heading'     => __( 'Automatic Scrolling', 'MergePress' ),
			'param_name'  => 'autoscroll',
'value'          => [
'Enable<br>' => __('true', 'MergePress'),
]
,
'save_always'          => true,
		),
		array(
			'type'        => 'hidden',
			'heading'     => __( 'Pagebuilder', 'MergePress' ),
			'param_name'  => 'pagebuilder',
'value'          => 'wpbakery',
'save_always'          => true,
		),
	)
) );

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
if ( ! class_exists( "WPBakeryShortCode_List" ) ) {
class WPBakeryShortCode_List extends WPBakeryShortCodesContainer { }
}
if ( ! class_exists( "WPBakeryShortCode_Parallax_Section" ) ) {
class WPBakeryShortCode_Parallax_Section extends WPBakeryShortCodesContainer { }
}
if ( ! class_exists( "WPBakeryShortCode_Testimonials" ) ) {
class WPBakeryShortCode_Testimonials extends WPBakeryShortCodesContainer { }
}
if ( ! class_exists( "WPBakeryShortCode_Faq" ) ) {
class WPBakeryShortCode_Faq extends WPBakeryShortCodesContainer { }
}
if ( ! class_exists( "WPBakeryShortCode_Pricing_Table" ) ) {
class WPBakeryShortCode_Pricing_Table extends WPBakeryShortCodesContainer { }
}
if ( ! class_exists( "WPBakeryShortCode_Tabs" ) ) {
class WPBakeryShortCode_Tabs extends WPBakeryShortCodesContainer { }
}
if ( ! class_exists( "WPBakeryShortCode_Featured_Brands" ) ) {
class WPBakeryShortCode_Featured_Brands extends WPBakeryShortCodesContainer { }
}
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
if ( ! class_exists( "WPBakeryShortCode_List_Item" ) ) {
class WPBakeryShortCode_List_Item extends WPBakeryShortCode { }
}
if ( ! class_exists( "WPBakeryShortCode_Testimonial_Quote" ) ) {
class WPBakeryShortCode_Testimonial_Quote extends WPBakeryShortCode { }
}
if ( ! class_exists( "WPBakeryShortCode_Toggle" ) ) {
class WPBakeryShortCode_Toggle extends WPBakeryShortCode { }
}
if ( ! class_exists( "WPBakeryShortCode_Pricing_Option" ) ) {
class WPBakeryShortCode_Pricing_Option extends WPBakeryShortCode { }
}
if ( ! class_exists( "WPBakeryShortCode_Tab" ) ) {
class WPBakeryShortCode_Tab extends WPBakeryShortCode { }
}
if ( ! class_exists( "WPBakeryShortCode_Brand_Logo" ) ) {
class WPBakeryShortCode_Brand_Logo extends WPBakeryShortCode { }
}
}

}

add_action('vc_after_init', 'wpb_custom_register_shortcodes');