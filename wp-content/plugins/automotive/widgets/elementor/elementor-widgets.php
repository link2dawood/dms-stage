<?php
class Elementor_recent_posts_scroller extends \Elementor\Widget_Base {
public function get_name(){ return 'recent_posts_scroller';}

public function get_title(){ return 'Recent Posts Scroller';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('number', ['label'       => __('Posts to show', 'MergePress'),'type'        => \Elementor\Controls_Manager::NUMBER,
'description'          => __('Number of posts to display at a time', 'MergePress'),
'default'          => '2',

    ]
    );

$this->add_control('speed', ['label'       => __('Speed', 'MergePress'),'type'        => \Elementor\Controls_Manager::NUMBER,
'description'          => __('Spped of the scroller', 'MergePress'),
'default'          => '500',

    ]
    );

$this->add_control('posts', ['label'       => __('Number of posts', 'MergePress'),'type'        => \Elementor\Controls_Manager::NUMBER,
'description'          => __('Number of posts to scroll through', 'MergePress'),
'default'          => '3',

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initRecentBlogPosts();});</script>
<?php }
$elementor_shortcode = '[recent_posts_scroller number="' . $settings['number']
 . '" speed="' . $settings['speed']
 . '" posts="' . $settings['posts']
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_quote extends \Elementor\Widget_Base {
public function get_name(){ return 'post_quote';}

public function get_title(){ return 'Post Quote';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('content', ['label'       => __('Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,
'description'          => __('Quote text', 'MergePress'),

    ]
    );

$this->add_control('color', ['label'       => __('Color', 'MergePress'),'type'        => \Elementor\Controls_Manager::COLOR,
'default'          => '#c7081b',

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[quote color="' . $settings['color']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/quote]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_animated_numbers extends \Elementor\Widget_Base {
public function get_name(){ return 'animated_numbers';}

public function get_title(){ return 'Animated Numbers';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('icon', ['label'       => __('Icon', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a> e.g. fa fa-users', 'MergePress'),

    ]
    );

$this->add_control('number', ['label'       => __('Number', 'MergePress'),'type'        => \Elementor\Controls_Manager::NUMBER,
'description'          => __('The number to animate to', 'MergePress'),

    ]
    );

$this->add_control('before_number', ['label'       => __('Before Number', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Display text before the number ($, €, #)', 'MergePress'),

    ]
    );

$this->add_control('after_number', ['label'       => __('After Number', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Display text after the number (%, !)', 'MergePress'),

    ]
    );

$this->add_control('separator_value', ['label'       => __('Seperator Value', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Change the value used to break up large numbers, defaults to a \',\'', 'MergePress'),

    ]
    );

$this->add_control('align', ['label'       => __('Align', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'left' => __('Left', 'MergePress'),
'center' => __('Center', 'MergePress'),
'right' => __('Right', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initAnimatedNumbers();});</script>
<?php }
$elementor_shortcode = '[animated_numbers icon="' . $settings['icon']
 . '" number="' . $settings['number']
 . '" before_number="' . $settings['before_number']
 . '" after_number="' . $settings['after_number']
 . '" separator_value="' . $settings['separator_value']
 . '" align="' . (isset($settings['align']) && is_array($settings['align']) && !empty($settings['align']) ? implode(",", $settings['align']) : $settings['align'])
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_featured_icon_box extends \Elementor\Widget_Base {
public function get_name(){ return 'featured_icon_box';}

public function get_title(){ return 'Featured Icon Box';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('content', ['label'       => __('Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXTAREA,

    ]
    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Title of the featured box', 'MergePress'),

    ]
    );

$this->add_control('icon', ['label'       => __('Icon', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a> e.g. fa fa-users', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[featured_icon_box title="' . $settings['title']
 . '" icon="' . $settings['icon']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/featured_icon_box]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_progress_bar extends \Elementor\Widget_Base {
public function get_name(){ return 'progress_bar';}

public function get_title(){ return 'Progress Bar';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('content', ['label'       => __('Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Text to be displayed inside the progress bar', 'MergePress'),
'default'          => 'Text',

    ]
    );

$this->add_control('color', ['label'       => __('Color', 'MergePress'),'type'        => \Elementor\Controls_Manager::COLOR,
'description'          => __('#c7081b', 'MergePress'),
'default'          => 'Color of progress bar',

    ]
    );

$this->add_control('filled', ['label'       => __('Filled', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('The percentage of the progress bar filled with color', 'MergePress'),
'default'          => '90%',

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initProgressBars();});</script>
<?php }
$elementor_shortcode = '[progress_bar color="' . $settings['color']
 . '" filled="' . $settings['filled']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/progress_bar]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_list extends \Elementor\Widget_Base {
public function get_name(){ return 'list';}

public function get_title(){ return 'List';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('style', ['label'       => __('Style', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Style of dropdown', 'MergePress'),
'multiple'          => true,
'options'          => [
'arrows' => __('Arrows', 'MergePress'),
'checkboxes' => __('Checkboxes', 'MergePress'),
]
,

    ]
    );

$repeater = new \Elementor\Repeater();

$repeater->add_control('icon', ['label'       => __('Icon', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a> e.g. fa fa-users', 'MergePress'),

    ]
    );

$repeater->add_control('content', ['label'       => __('Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,
'description'          => __('List item content', 'MergePress'),

    ]
    );

$this->add_control(
	'list_item',
	[
		'label' => __( 'List Item', 'MergePress' ),
		'type' => \Elementor\Controls_Manager::REPEATER,
		'fields' => $repeater->get_controls(),
		'title_field' => '{{{ content }}}',
	]
);
$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$settings['content'] = '';

if(!empty($settings['list_item'])){
foreach($settings['list_item'] as $single_settings_id => $single_settings){
  $settings['content'] .= '[list_item icon="' . $settings['list_item'][$single_settings_id]['icon']
 . '" pagebuilder="elementor"]' . $settings['list_item'][$single_settings_id]['content'] . '[/list_item]';
}

}
$settings['content'] = ($settings['content']);

$elementor_shortcode = '[list style="' . (isset($settings['style']) && is_array($settings['style']) && !empty($settings['style']) ? implode(",", $settings['style']) : $settings['style'])
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/list]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_list_item extends \Elementor\Widget_Base {
public function get_name(){ return 'list_item';}

public function get_title(){ return 'List Item';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('icon', ['label'       => __('Icon', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a> e.g. fa fa-users', 'MergePress'),

    ]
    );

$this->add_control('content', ['label'       => __('Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,
'description'          => __('List item content', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[list_item icon="' . $settings['icon']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/list_item]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_parallax_section extends \Elementor\Widget_Base {
public function get_name(){ return 'parallax';}

public function get_title(){ return 'Parallax';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('velocity', ['label'       => __('Velocity', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('How fast do you want the parallax to go (and which direction!)', 'MergePress'),
'multiple'          => true,
'options'          => [
'-.3' => __('0', 'MergePress'),
'-.2' => __('1', 'MergePress'),
'-.1' => __('2', 'MergePress'),
'.1' => __('3', 'MergePress'),
'.2' => __('4', 'MergePress'),
'.3' => __('5', 'MergePress'),
]
,

    ]
    );

$this->add_control('offset', ['label'       => __('Offset', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Amount of offset (px)', 'MergePress'),

    ]
    );

$this->add_control('image', ['label'       => __('Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('Image used for scrolling', 'MergePress'),

    ]
    );

$this->add_control('overlay_color', ['label'       => __('Overlay Color', 'MergePress'),'type'        => \Elementor\Controls_Manager::COLOR,

    ]
    );

$this->add_control('text_color', ['label'       => __('Text Color', 'MergePress'),'type'        => \Elementor\Controls_Manager::COLOR,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initParallax();});</script>
<?php }
$elementor_shortcode = '[parallax_section title="' . $settings['title']
 . '" velocity="' . (isset($settings['velocity']) && is_array($settings['velocity']) && !empty($settings['velocity']) ? implode(",", $settings['velocity']) : $settings['velocity'])
 . '" offset="' . $settings['offset']
 . '" image="' . mergepress_normalize_image($settings['image'], 'elementor')
 . '" overlay_color="' . $settings['overlay_color']
 . '" text_color="' . $settings['text_color']
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_testimonials extends \Elementor\Widget_Base {
public function get_name(){ return 'testimonails';}

public function get_title(){ return 'Testimonails';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('slide', ['label'       => __('Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('This will control which way the testimonials slide', 'MergePress'),
'multiple'          => true,
'options'          => [
'horizontal' => __('0', 'MergePress'),
'vertical' => __('1', 'MergePress'),
]
,

    ]
    );

$this->add_control('speed', ['label'       => __('Speed', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('This controls the speed at which the testimonials slide', 'MergePress'),
'default'          => '500',

    ]
    );

$repeater = new \Elementor\Repeater();

$repeater->add_control('name', ['label'       => __('Name', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Name of person giving the testimonial', 'MergePress'),

    ]
    );

$repeater->add_control('content', ['label'       => __('Quote', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Testimonial quote', 'MergePress'),

    ]
    );

$this->add_control(
	'testimonial_quote',
	[
		'label' => __( 'Testimonial Quote', 'MergePress' ),
		'type' => \Elementor\Controls_Manager::REPEATER,
		'fields' => $repeater->get_controls(),
		'title_field' => '{{{ name }}}',
	]
);
$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$settings['content'] = '';

if(!empty($settings['testimonial_quote'])){
foreach($settings['testimonial_quote'] as $single_settings_id => $single_settings){
  $settings['content'] .= '[testimonial_quote name="' . $settings['testimonial_quote'][$single_settings_id]['name']
 . '" pagebuilder="elementor"]' . $settings['testimonial_quote'][$single_settings_id]['content'] . '[/testimonial_quote]';
}

}
$settings['content'] = do_shortcode($settings['content']);

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initTestimonialSlider();});</script>
<?php }
$elementor_shortcode = '[testimonials slide="' . (isset($settings['slide']) && is_array($settings['slide']) && !empty($settings['slide']) ? implode(",", $settings['slide']) : $settings['slide'])
 . '" speed="' . $settings['speed']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/testimonials]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_testimonial_quote extends \Elementor\Widget_Base {
public function get_name(){ return 'testimonial_quote';}

public function get_title(){ return 'Testimonial Quote';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('name', ['label'       => __('Name', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Name of person giving the testimonial', 'MergePress'),

    ]
    );

$this->add_control('content', ['label'       => __('Quote', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,
'description'          => __('Testimonial quote', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[testimonial_quote name="' . $settings['name']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/testimonial_quote]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_faq extends \Elementor\Widget_Base {
public function get_name(){ return 'faq';}

public function get_title(){ return 'FAQ';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('categories', ['label'       => __('Categories', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Comma separated list of categories', 'MergePress'),
'default'          => 'category1, category2, etc',

    ]
    );

$this->add_control('sort_element', ['label'       => __('Sort By Element', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Display the sort by element', 'MergePress'),
'multiple'          => true,
'options'          => [
'yes' => __('Yes', 'MergePress'),
'no' => __('No', 'MergePress'),
]
,

    ]
    );

$this->add_control('sort_text', ['label'       => __('Sort Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('This text is displayed beside the categories', 'MergePress'),
'default'          => 'Sort FAQ by:',

    ]
    );

$repeater = new \Elementor\Repeater();

$repeater->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Title of FAQ item', 'MergePress'),

    ]
    );

$repeater->add_control('categories', ['label'       => __('Categories', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Comma seperated list of categories item is in', 'MergePress'),
'default'          => 'category1, category2, etc',

    ]
    );

$repeater->add_control('content', ['label'       => __('FAQ Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,

    ]
    );

$repeater->add_control('state', ['label'       => __('State of the item', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Choose whether the item is open or close', 'MergePress'),
'multiple'          => true,
'options'          => [
'collapsed' => __('Closed', 'MergePress'),
'in' => __('Open', 'MergePress'),
]
,

    ]
    );

$this->add_control(
	'toggle',
	[
		'label' => __( 'FAQ Item', 'MergePress' ),
		'type' => \Elementor\Controls_Manager::REPEATER,
		'fields' => $repeater->get_controls(),
		'title_field' => '{{{ title }}}',
	]
);
$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$settings['content'] = '';

if(!empty($settings['toggle'])){
foreach($settings['toggle'] as $single_settings_id => $single_settings){
  $settings['content'] .= '[toggle title="' . $settings['toggle'][$single_settings_id]['title']
 . '" categories="' . $settings['toggle'][$single_settings_id]['categories']
 . '" state="' . (isset($settings['toggle'][$single_settings_id]['state']) && is_array($settings['toggle'][$single_settings_id]['state']) && !empty($settings['toggle'][$single_settings_id]['state']) ? implode(",", $settings['toggle'][$single_settings_id]['state']) : $settings['toggle'][$single_settings_id]['state'])
 . '" pagebuilder="elementor"]' . $settings['toggle'][$single_settings_id]['content'] . '[/toggle]';
}

}
$settings['content'] = do_shortcode($settings['content']);

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initFAQ();});</script>
<?php }
$elementor_shortcode = '[faq categories="' . $settings['categories']
 . '" sort_element="' . (isset($settings['sort_element']) && is_array($settings['sort_element']) && !empty($settings['sort_element']) ? implode(",", $settings['sort_element']) : $settings['sort_element'])
 . '" sort_text="' . $settings['sort_text']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/faq]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_toggle extends \Elementor\Widget_Base {
public function get_name(){ return 'faq_item';}

public function get_title(){ return 'FAQ Item';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Title of FAQ item', 'MergePress'),

    ]
    );

$this->add_control('categories', ['label'       => __('Categories', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Comma seperated list of categories item is in', 'MergePress'),
'default'          => 'category1, category2, etc',

    ]
    );

$this->add_control('content', ['label'       => __('FAQ Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,

    ]
    );

$this->add_control('state', ['label'       => __('State of the item', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Choose whether the item is open or close', 'MergePress'),
'multiple'          => true,
'options'          => [
'collapsed' => __('Closed', 'MergePress'),
'in' => __('Open', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[toggle title="' . $settings['title']
 . '" categories="' . $settings['categories']
 . '" state="' . (isset($settings['state']) && is_array($settings['state']) && !empty($settings['state']) ? implode(",", $settings['state']) : $settings['state'])
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/toggle]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_pricing_table extends \Elementor\Widget_Base {
public function get_name(){ return 'pricing_table';}

public function get_title(){ return 'Pricing Table';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('header_color', ['label'       => __('Header Color', 'MergePress'),'type'        => \Elementor\Controls_Manager::COLOR,
'description'          => __('Customize the color of the pricing header', 'MergePress'),

    ]
    );

$this->add_control('price', ['label'       => __('Price', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('often', ['label'       => __('Often', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('How often the payment is made', 'MergePress'),
'default'          => 'mo',

    ]
    );

$this->add_control('button', ['label'       => __('Button Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'default'          => 'Sign Up Now',

    ]
    );

$this->add_control('link', ['label'       => __('Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Link brand to URL', 'MergePress'),
'show_external'          => true,

    ]
    );

$repeater = new \Elementor\Repeater();

$repeater->add_control('content', ['label'       => __('Option Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('An option this pricing table includes', 'MergePress'),

    ]
    );

$this->add_control(
	'pricing_option',
	[
		'label' => __( 'Pricing Option', 'MergePress' ),
		'type' => \Elementor\Controls_Manager::REPEATER,
		'fields' => $repeater->get_controls(),
		'title_field' => '{{{ content }}}',
	]
);
$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$settings['content'] = '';

if(!empty($settings['pricing_option'])){
foreach($settings['pricing_option'] as $single_settings_id => $single_settings){
  $settings['content'] .= '[pricing_option pagebuilder="elementor"]' . $settings['pricing_option'][$single_settings_id]['content'] . '[/pricing_option]';
}

}
$settings['content'] = do_shortcode($settings['content']);

$elementor_shortcode = '[pricing_table title="' . $settings['title']
 . '" header_color="' . $settings['header_color']
 . '" price="' . $settings['price']
 . '" often="' . $settings['often']
 . '" button="' . $settings['button']
 . '" link="' . mergepress_normalize_url($settings['link'], 'elementor')
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/pricing_table]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_pricing_option extends \Elementor\Widget_Base {
public function get_name(){ return 'pricing_option';}

public function get_title(){ return 'Pricing Option';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('content', ['label'       => __('Option Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('An option this pricing table includes', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[pricing_option pagebuilder="elementor"]' . $settings['content'] . '[/pricing_option]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_person extends \Elementor\Widget_Base {
public function get_name(){ return 'staff_person';}

public function get_title(){ return 'Staff Person';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('name', ['label'       => __('Name', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Name of person', 'MergePress'),

    ]
    );

$this->add_control('position', ['label'       => __('Position', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Position of person', 'MergePress'),

    ]
    );

$this->add_control('phone', ['label'       => __('Phone', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('cell_phone', ['label'       => __('Cell Phone', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('email', ['label'       => __('Email', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('img', ['label'       => __('Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,

    ]
    );

$this->add_control('hoverimg', ['label'       => __('Larger Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,

    ]
    );

$this->add_control('content', ['label'       => __('Description', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,

    ]
    );

$this->add_control('facebook', ['label'       => __('Facebook', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Facebook profile', 'MergePress'),

    ]
    );

$this->add_control('twitter', ['label'       => __('Twitter', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Twitter profile', 'MergePress'),

    ]
    );

$this->add_control('youtube', ['label'       => __('YouTube', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to YouTube profile', 'MergePress'),

    ]
    );

$this->add_control('vimeo', ['label'       => __('Linkedin', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Linkedin profile', 'MergePress'),

    ]
    );

$this->add_control('linkedin', ['label'       => __('Vimeo', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Vimeo profile', 'MergePress'),

    ]
    );

$this->add_control('rss', ['label'       => __('RSS', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to RSS profile', 'MergePress'),

    ]
    );

$this->add_control('flickr', ['label'       => __('Flickr', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Flickr profile', 'MergePress'),

    ]
    );

$this->add_control('skype', ['label'       => __('Skype', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Skype profile', 'MergePress'),

    ]
    );

$this->add_control('google', ['label'       => __('Google', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Google profile', 'MergePress'),

    ]
    );

$this->add_control('pinterest', ['label'       => __('Pinterest', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Pinteret profile', 'MergePress'),

    ]
    );

$this->add_control('instagram', ['label'       => __('Instagram', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Instagram profile', 'MergePress'),

    ]
    );

$this->add_control('yelp', ['label'       => __('Yelp', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('URL to Yelp profile', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[person name="' . $settings['name']
 . '" position="' . $settings['position']
 . '" phone="' . $settings['phone']
 . '" cell_phone="' . $settings['cell_phone']
 . '" email="' . $settings['email']
 . '" img="' . mergepress_normalize_image($settings['img'], 'elementor')
 . '" hoverimg="' . mergepress_normalize_image($settings['hoverimg'], 'elementor')
 . '" facebook="' . $settings['facebook']
 . '" twitter="' . $settings['twitter']
 . '" youtube="' . $settings['youtube']
 . '" vimeo="' . $settings['vimeo']
 . '" linkedin="' . $settings['linkedin']
 . '" rss="' . $settings['rss']
 . '" flickr="' . $settings['flickr']
 . '" skype="' . $settings['skype']
 . '" google="' . $settings['google']
 . '" pinterest="' . $settings['pinterest']
 . '" instagram="' . $settings['instagram']
 . '" yelp="' . $settings['yelp']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/person]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_featured_panel extends \Elementor\Widget_Base {
public function get_name(){ return 'featured_panel';}

public function get_title(){ return 'Featured Panel';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Title of panel', 'MergePress'),

    ]
    );

$this->add_control('icon', ['label'       => __('Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,

    ]
    );

$this->add_control('hover_icon', ['label'       => __('Hover Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,

    ]
    );

$this->add_control('image_link', ['label'       => __('Link the image', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'show_external'          => true,

    ]
    );

$this->add_control('content', ['label'       => __('Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXTAREA,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[featured_panel title="' . $settings['title']
 . '" icon="' . mergepress_normalize_image($settings['icon'], 'elementor')
 . '" hover_icon="' . mergepress_normalize_image($settings['hover_icon'], 'elementor')
 . '" image_link="' . mergepress_normalize_url($settings['image_link'], 'elementor')
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/featured_panel]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_detailed_panel extends \Elementor\Widget_Base {
public function get_name(){ return 'detailed_panel';}

public function get_title(){ return 'Detailed Panel';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('icon', ['label'       => __('Icon', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('image', ['label'       => __('Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('This will overwrite the icon setting', 'MergePress'),

    ]
    );

$this->add_control('link', ['label'       => __('Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Link for the title and icon', 'MergePress'),
'show_external'          => true,

    ]
    );

$this->add_control('content', ['label'       => __('Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXTAREA,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[detailed_panel title="' . $settings['title']
 . '" icon="' . $settings['icon']
 . '" image="' . mergepress_normalize_image($settings['image'], 'elementor')
 . '" link="' . mergepress_normalize_url($settings['link'], 'elementor')
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/detailed_panel]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_portfolio extends \Elementor\Widget_Base {
public function get_name(){ return 'automotive_portfolio';}

public function get_title(){ return 'Portfolio';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

foreach(automotive_projects_loop_option() as $loop_option_key => $loop_option_value){
$this->add_control($loop_option_value['id'], ['label'       => $loop_option_value['label'],'type'        => mergepress_option_type_normalize($loop_option_value['type'], 'elementor'),
'description'          => (isset($loop_option_value['desc']) ? $loop_option_value['desc'] : ''),
'default'          => (isset($loop_option_value['default']) ? $loop_option_value['default'] : ''),
'multiple'          => true,
'options'          => (isset($loop_option_value['options']) ? $loop_option_value['options'] : array()),

    ]
    );

}
$this->add_control('type', ['label'       => __('Type', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'details' => __('Details', 'MergePress'),
'classic' => __('Classic', 'MergePress'),
]
,

    ]
    );

$this->add_control('all_category', ['label'       => __('All Category', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Display the all category', 'MergePress'),
'multiple'          => true,
'options'          => [
'yes' => __('Yes', 'MergePress'),
'no' => __('No', 'MergePress'),
]
,

    ]
    );

foreach(automotive_portfolios_loop_option() as $loop_option_key => $loop_option_value){
$this->add_control($loop_option_value['id'], ['label'       => $loop_option_value['label'],'type'        => mergepress_option_type_normalize($loop_option_value['type'], 'elementor'),
'description'          => (isset($loop_option_value['desc']) ? $loop_option_value['desc'] : ''),
'default'          => (isset($loop_option_value['default']) ? $loop_option_value['default'] : ''),
'multiple'          => true,
'options'          => (isset($loop_option_value['options']) ? $loop_option_value['options'] : array()),

    ]
    );

}
$this->add_control('sort_element', ['label'       => __('Sort by Element', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Display the sort by element', 'MergePress'),
'multiple'          => true,
'options'          => [
'yes' => __('Yes', 'MergePress'),
'no' => __('No', 'MergePress'),
]
,

    ]
    );

$this->add_control('sort_text', ['label'       => __('Sort Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Change the text beside the categories', 'MergePress'),

    ]
    );

$this->add_control('columns', ['label'       => __('Columns', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Change the text beside the categories', 'MergePress'),
'multiple'          => true,
'options'          => [
'1' => __('0', 'MergePress'),
'2' => __('1', 'MergePress'),
'3' => __('2', 'MergePress'),
'4' => __('3', 'MergePress'),
]
,

    ]
    );

$this->add_control('order_by', ['label'       => __('Order By', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Change the order of the portfolio items', 'MergePress'),
'multiple'          => true,
'options'          => [
'ASC' => __('Ascending', 'MergePress'),
'DESC' => __('Descending', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initPortfolio();});</script>
<?php }
$elementor_shortcode = '[portfolio type="' . (isset($settings['type']) && is_array($settings['type']) && !empty($settings['type']) ? implode(",", $settings['type']) : $settings['type'])
 . '" all_category="' . (isset($settings['all_category']) && is_array($settings['all_category']) && !empty($settings['all_category']) ? implode(",", $settings['all_category']) : $settings['all_category'])
 . '" sort_element="' . (isset($settings['sort_element']) && is_array($settings['sort_element']) && !empty($settings['sort_element']) ? implode(",", $settings['sort_element']) : $settings['sort_element'])
 . '" sort_text="' . $settings['sort_text']
 . '" columns="' . (isset($settings['columns']) && is_array($settings['columns']) && !empty($settings['columns']) ? implode(",", $settings['columns']) : $settings['columns'])
 . '" order_by="' . (isset($settings['order_by']) && is_array($settings['order_by']) && !empty($settings['order_by']) ? implode(",", $settings['order_by']) : $settings['order_by'])
 . '"';

foreach(automotive_projects_loop_option() as $loop_option_key => $loop_option_value){
$elementor_shortcode .= ' ' . $loop_option_value["id"] . '="' . (isset($settings[$loop_option_value["id"]]) && is_array($settings[$loop_option_value["id"]]) ? implode(",", $settings[$loop_option_value["id"]]) : (isset($settings[$loop_option_value["id"]]) && !empty($settings[$loop_option_value["id"]]) ? $settings[$loop_option_value["id"]] : '') ) . '"';
}
foreach(automotive_portfolios_loop_option() as $loop_option_key => $loop_option_value){
$elementor_shortcode .= ' ' . $loop_option_value["id"] . '="' . (isset($settings[$loop_option_value["id"]]) && is_array($settings[$loop_option_value["id"]]) ? implode(",", $settings[$loop_option_value["id"]]) : (isset($settings[$loop_option_value["id"]]) && !empty($settings[$loop_option_value["id"]]) ? $settings[$loop_option_value["id"]] : '') ) . '"';
}
$elementor_shortcode .= ' pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_alert extends \Elementor\Widget_Base {
public function get_name(){ return 'alert';}

public function get_title(){ return 'Alert';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('type', ['label'       => __('Type', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Type of alert', 'MergePress'),
'multiple'          => true,
'options'          => [
'0' => __('Danger', 'MergePress'),
'1' => __('Success', 'MergePress'),
'2' => __('Info', 'MergePress'),
'3' => __('Warning', 'MergePress'),
]
,

    ]
    );

$this->add_control('close', ['label'       => __('Close Button', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'No' => __('0', 'MergePress'),
'Yes' => __('1', 'MergePress'),
]
,

    ]
    );

$this->add_control('content', ['label'       => __('Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,
'description'          => __('Alert content', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[alert type="' . (isset($settings['type']) && is_array($settings['type']) && !empty($settings['type']) ? implode(",", $settings['type']) : $settings['type'])
 . '" close="' . (isset($settings['close']) && is_array($settings['close']) && !empty($settings['close']) ? implode(",", $settings['close']) : $settings['close'])
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/alert]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_search_inventory_box extends \Elementor\Widget_Base {
public function get_name(){ return 'search_inventory_box';}

public function get_title(){ return 'Search Inventory Box';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

foreach(automotive_categories_loop_options() as $loop_option_key => $loop_option_value){
$this->add_control($loop_option_value['id'], ['label'       => $loop_option_value['label'],'type'        => mergepress_option_type_normalize($loop_option_value['type'], 'elementor'),
'description'          => (isset($loop_option_value['desc']) ? $loop_option_value['desc'] : ''),
'default'          => (isset($loop_option_value['default']) ? $loop_option_value['default'] : ''),
'multiple'          => true,
'options'          => (isset($loop_option_value['options']) ? $loop_option_value['options'] : array()),

    ]
    );

}
$this->add_control('page_id', ['label'       => __('Form action', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Choose the Inventory page which you want to display the search results', 'MergePress'),
'show_external'          => true,

    ]
    );

$this->add_control('term_form', ['label'       => __('Listing Category Term', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Adjust whether the listing dropdown uses the singular form or plural form of the listing category.', 'MergePress'),
'multiple'          => true,
'options'          => [
'singular' => __('Singular', 'MergePress'),
'plural' => __('Plural', 'MergePress'),
]
,

    ]
    );

$this->add_control('prefix_text', ['label'       => __('Prefix text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Adjust the text before the listing category in the dropdown', 'MergePress'),

    ]
    );

$this->add_control('button_text', ['label'       => __('Button Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Adjust the button text to submit the form', 'MergePress'),
'default'          => 'Find My New Vehicle',

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initSearchListingFilters();});</script>
<?php }
$elementor_shortcode = '[search_inventory_box page_id="' . mergepress_normalize_url($settings['page_id'], 'elementor')
 . '" term_form="' . (isset($settings['term_form']) && is_array($settings['term_form']) && !empty($settings['term_form']) ? implode(",", $settings['term_form']) : $settings['term_form'])
 . '" prefix_text="' . $settings['prefix_text']
 . '" button_text="' . $settings['button_text']
 . '"';

foreach(automotive_categories_loop_options() as $loop_option_key => $loop_option_value){
$elementor_shortcode .= ' ' . $loop_option_value["id"] . '="' . (isset($settings[$loop_option_value["id"]]) && is_array($settings[$loop_option_value["id"]]) ? implode(",", $settings[$loop_option_value["id"]]) : (isset($settings[$loop_option_value["id"]]) && !empty($settings[$loop_option_value["id"]]) ? $settings[$loop_option_value["id"]] : '') ) . '"';
}
$elementor_shortcode .= ' pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_button extends \Elementor\Widget_Base {
public function get_name(){ return 'auto_button';}

public function get_title(){ return 'Button';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('content', ['label'       => __('Button Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('size', ['label'       => __('Button Size', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'xs' => __('Extra Small', 'MergePress'),
'sm' => __('Small', 'MergePress'),
'md' => __('Medium', 'MergePress'),
'lg' => __('Large', 'MergePress'),
'xl' => __('Extra Large', 'MergePress'),
]
,

    ]
    );

$this->add_control('align', ['label'       => __('Button Alignment', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'left' => __('Left', 'MergePress'),
'center' => __('Center', 'MergePress'),
'right' => __('Right', 'MergePress'),
]
,

    ]
    );

$this->add_control('href', ['label'       => __('Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'show_external'          => true,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[button size="' . (isset($settings['size']) && is_array($settings['size']) && !empty($settings['size']) ? implode(",", $settings['size']) : $settings['size'])
 . '" align="' . (isset($settings['align']) && is_array($settings['align']) && !empty($settings['align']) ? implode(",", $settings['align']) : $settings['align'])
 . '" href="' . mergepress_normalize_url($settings['href'], 'elementor')
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/button]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_dropcaps extends \Elementor\Widget_Base {
public function get_name(){ return 'dropcaps';}

public function get_title(){ return 'Dropcaps';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('content', ['label'       => __('Dropcap Letter', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[dropcaps pagebuilder="elementor"]' . $settings['content'] . '[/dropcaps]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_auto_video extends \Elementor\Widget_Base {
public function get_name(){ return 'youtube/vimeo_video';}

public function get_title(){ return 'YouTube/Vimeo video';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('url', ['label'       => __('URL to video', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'show_external'          => true,

    ]
    );

$this->add_control('width', ['label'       => __('Width', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('height', ['label'       => __('Height', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('vq', ['label'       => __('Video Quality', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'tiny' => __('144p', 'MergePress'),
'small' => __('240p', 'MergePress'),
'medium' => __('360p', 'MergePress'),
'large' => __('480p', 'MergePress'),
'hd720' => __('720p', 'MergePress'),
'hd1080' => __('1080p', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[auto_video url="' . mergepress_normalize_url($settings['url'], 'elementor')
 . '" width="' . $settings['width']
 . '" height="' . $settings['height']
 . '" vq="' . (isset($settings['vq']) && is_array($settings['vq']) && !empty($settings['vq']) ? implode(",", $settings['vq']) : $settings['vq'])
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_heading extends \Elementor\Widget_Base {
public function get_name(){ return 'auto_heading';}

public function get_title(){ return 'Heading';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('heading', ['label'       => __('Heading', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Size of heading', 'MergePress'),
'multiple'          => true,
'options'          => [
'h1' => __('Heading 1', 'MergePress'),
'h2' => __('Heading 2', 'MergePress'),
'h3' => __('Heading 3', 'MergePress'),
'h4' => __('Heading 4', 'MergePress'),
'h5' => __('Heading 5', 'MergePress'),
'h6' => __('Heading 6', 'MergePress'),
]
,

    ]
    );

$this->add_control('content', ['label'       => __('Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Heading Text', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[heading heading="' . (isset($settings['heading']) && is_array($settings['heading']) && !empty($settings['heading']) ? implode(",", $settings['heading']) : $settings['heading'])
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/heading]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_car_comparison extends \Elementor\Widget_Base {
public function get_name(){ return 'car_comparison';}

public function get_title(){ return 'Car Comparison';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('car_ids', ['label'       => __('Car ID\'s', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Comma seperated ID\'s of vehicles, if no car ID\'s are set it will automatically grab cars checked by user in inventory.', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[car_comparison car_ids="' . $settings['car_ids']
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_auto_contact_form extends \Elementor\Widget_Base {
public function get_name(){ return 'contact_form';}

public function get_title(){ return 'Contact Form';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('name', ['label'       => __('Name Placeholder', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('This is the text \'behind\' the name textfield.', 'MergePress'),

    ]
    );

$this->add_control('email', ['label'       => __('Email Placeholder', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('This is the text \'behind\' the email textfield.', 'MergePress'),

    ]
    );

$this->add_control('message', ['label'       => __('Message Placeholder', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('This is the text \'behind\' the message textbox', 'MergePress'),

    ]
    );

$this->add_control('button', ['label'       => __('Submit Button', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('This is the text used on the submit button', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[auto_contact_form name="' . $settings['name']
 . '" email="' . $settings['email']
 . '" message="' . $settings['message']
 . '" button="' . $settings['button']
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_hours_table extends \Elementor\Widget_Base {
public function get_name(){ return 'hours_table';}

public function get_title(){ return 'Hours Table';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Title of the hours table', 'MergePress'),

    ]
    );

$this->add_control('mon', ['label'       => __('Monday', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Monday Hours', 'MergePress'),

    ]
    );

$this->add_control('tue', ['label'       => __('Tuesday', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Tuesday Hours', 'MergePress'),

    ]
    );

$this->add_control('wed', ['label'       => __('Wednesday', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Wednesday Hours', 'MergePress'),

    ]
    );

$this->add_control('thu', ['label'       => __('Thursday', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Thursday Hours', 'MergePress'),

    ]
    );

$this->add_control('fri', ['label'       => __('Friday', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Friday Hours', 'MergePress'),

    ]
    );

$this->add_control('sat', ['label'       => __('Saturday', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Saturday Hours', 'MergePress'),

    ]
    );

$this->add_control('sun', ['label'       => __('Sunday', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Sunday Hours', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[hours_table title="' . $settings['title']
 . '" mon="' . $settings['mon']
 . '" tue="' . $settings['tue']
 . '" wed="' . $settings['wed']
 . '" thu="' . $settings['thu']
 . '" fri="' . $settings['fri']
 . '" sat="' . $settings['sat']
 . '" sun="' . $settings['sun']
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_automotive_social_icons_shortcode extends \Elementor\Widget_Base {
public function get_name(){ return 'social_icons';}

public function get_title(){ return 'Social Icons';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('align', ['label'       => __('Alignment', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Change the style of the Google Map', 'MergePress'),
'multiple'          => true,
'options'          => [
'left' => __('Left', 'MergePress'),
'right' => __('Right', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[automotive_social_icons_shortcode align="' . (isset($settings['align']) && is_array($settings['align']) && !empty($settings['align']) ? implode(",", $settings['align']) : $settings['align'])
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_auto_contact_information extends \Elementor\Widget_Base {
public function get_name(){ return 'contact_information';}

public function get_title(){ return 'Contact Information';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('company', ['label'       => __('Company Name', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('address', ['label'       => __('Address', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('phone', ['label'       => __('Phone', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('fax', ['label'       => __('Fax', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('email', ['label'       => __('Email', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('website', ['label'       => __('Website', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[auto_contact_information company="' . $settings['company']
 . '" address="' . $settings['address']
 . '" phone="' . $settings['phone']
 . '" fax="' . $settings['fax']
 . '" email="' . $settings['email']
 . '" website="' . $settings['website']
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_auto_google_map extends \Elementor\Widget_Base {
public function get_name(){ return 'google_map';}

public function get_title(){ return 'Google Map';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('latitude', ['label'       => __('Latitude', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Latitude of google map', 'MergePress'),

    ]
    );

$this->add_control('longitude', ['label'       => __('Longitude', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Longitude of google map', 'MergePress'),

    ]
    );

$this->add_control('zoom', ['label'       => __('Zoom', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Zoom of google map', 'MergePress'),

    ]
    );

$this->add_control('height', ['label'       => __('Height', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Height of google map', 'MergePress'),

    ]
    );

$this->add_control('map_type', ['label'       => __('Type', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Change the style of the Google Map', 'MergePress'),
'multiple'          => true,
'options'          => [
'roadmap' => __('Roadmap', 'MergePress'),
'satellite' => __('Satellite', 'MergePress'),
'hybrid' => __('Hybrid', 'MergePress'),
'terrain' => __('Terrain', 'MergePress'),
]
,

    ]
    );

$this->add_control('scrolling', ['label'       => __('Scrolling to zoom', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Turn off the scrolling to zoom function on google map, useful for fullscreen maps', 'MergePress'),
'multiple'          => true,
'options'          => [
'true' => __('On', 'MergePress'),
'false' => __('Off', 'MergePress'),
]
,

    ]
    );

$this->add_control('map_style', ['label'       => __('Style', 'MergePress'),'type'        => \Elementor\Controls_Manager::CODE,
'description'          => __('Style of google map, styles availiable at: <a href=\'http://snazzymaps.com/\' target=\'_blank\'>http://snazzymaps.com/</a>', 'MergePress'),

    ]
    );

$this->add_control('info_window_content', ['label'       => __('Info Window Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::CODE,
'description'          => __('Enter in content to be used in the info window once the user clicks the marker', 'MergePress'),

    ]
    );

$this->add_control('directions_button', ['label'       => __('Directions Button', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'true' => __('On', 'MergePress'),
'false' => __('Off', 'MergePress'),
]
,

    ]
    );

$this->add_control('parallax_disabled', ['label'       => __('Parallax Disabled', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Check this option to disable the parallax', 'MergePress'),
'multiple'          => true,
'options'          => [
'disabled' => __('Disabled', 'MergePress'),
]
,

    ]
    );

$this->add_control('scrolling_disabled', ['label'       => __('Scrolling Disabled', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Check this option to disable users scrolling around the map', 'MergePress'),
'multiple'          => true,
'options'          => [
'disabled' => __('Disabled', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initGoogleMap();});</script>
<?php }
$elementor_shortcode = '[auto_google_map latitude="' . $settings['latitude']
 . '" longitude="' . $settings['longitude']
 . '" zoom="' . $settings['zoom']
 . '" height="' . $settings['height']
 . '" map_type="' . (isset($settings['map_type']) && is_array($settings['map_type']) && !empty($settings['map_type']) ? implode(",", $settings['map_type']) : $settings['map_type'])
 . '" scrolling="' . (isset($settings['scrolling']) && is_array($settings['scrolling']) && !empty($settings['scrolling']) ? implode(",", $settings['scrolling']) : $settings['scrolling'])
 . '" map_style="' . esc_shortcode_attr($settings['map_style'])
 . '" info_window_content="' . $settings['info_window_content']
 . '" directions_button="' . (isset($settings['directions_button']) && is_array($settings['directions_button']) && !empty($settings['directions_button']) ? implode(",", $settings['directions_button']) : $settings['directions_button'])
 . '" parallax_disabled="' . (isset($settings['parallax_disabled']) && is_array($settings['parallax_disabled']) && !empty($settings['parallax_disabled']) ? implode(",", $settings['parallax_disabled']) : $settings['parallax_disabled'])
 . '" scrolling_disabled="' . (isset($settings['scrolling_disabled']) && is_array($settings['scrolling_disabled']) && !empty($settings['scrolling_disabled']) ? implode(",", $settings['scrolling_disabled']) : $settings['scrolling_disabled'])
 . '" pagebuilder="elementor"]';

echo do_shortcode($elementor_shortcode);
}

}

class Elementor_flipping_card extends \Elementor\Widget_Base {
public function get_name(){ return 'flipping_card';}

public function get_title(){ return 'Flipping Card';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('image', ['label'       => __('Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('This image will be shown on the front of the flipping card', 'MergePress'),

    ]
    );

$this->add_control('larger_img', ['label'       => __('Larger Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('This image will open in a fancybox', 'MergePress'),

    ]
    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('link', ['label'       => __('Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Link of link button on flipped side', 'MergePress'),
'show_external'          => true,

    ]
    );

$this->add_control('card_link', ['label'       => __('Card Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Link the entire flipping card', 'MergePress'),
'show_external'          => true,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initFlippingCards();});</script>
<?php }
$elementor_shortcode = '[flipping_card image="' . mergepress_normalize_image($settings['image'], 'elementor')
 . '" larger_img="' . mergepress_normalize_image($settings['larger_img'], 'elementor')
 . '" title="' . $settings['title']
 . '" link="' . mergepress_normalize_url($settings['link'], 'elementor')
 . '" card_link="' . mergepress_normalize_url($settings['card_link'], 'elementor')
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_icon_title extends \Elementor\Widget_Base {
public function get_name(){ return 'icon_and_title';}

public function get_title(){ return 'Icon & Title';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,

    ]
    );

$this->add_control('icon', ['label'       => __('Icon', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a> e.g. fa fa-users', 'MergePress'),

    ]
    );

$this->add_control('link', ['label'       => __('Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Link of the icon', 'MergePress'),
'show_external'          => true,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[icon_title title="' . $settings['title']
 . '" icon="' . $settings['icon']
 . '" link="' . mergepress_normalize_url($settings['link'], 'elementor')
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_tabs extends \Elementor\Widget_Base {
public function get_name(){ return 'tabs';}

public function get_title(){ return 'Tabs';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function render(){


$settings = $this->get_settings_for_display();

$settings['content'] = '';

if(!empty($settings['tab'])){
foreach($settings['tab'] as $single_settings_id => $single_settings){
  $settings['content'] .= '[tab title="' . $settings['tab'][$single_settings_id]['title']
 . '" pagebuilder="elementor"]' . $settings['tab'][$single_settings_id]['content'] . '[/tab]';
}

}
$settings['content'] = do_shortcode($settings['content']);

$elementor_shortcode = '[tabs pagebuilder="elementor"]' . $settings['content'] . '[/tabs]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_clearfix extends \Elementor\Widget_Base {
public function get_name(){ return 'clearfix';}

public function get_title(){ return 'Clearfix';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[clearfix pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_br extends \Elementor\Widget_Base {
public function get_name(){ return 'line_break';}

public function get_title(){ return 'Line Break';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[br pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_tab extends \Elementor\Widget_Base {
public function get_name(){ return 'single_tab';}

public function get_title(){ return 'Single Tab';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Tab title', 'MergePress'),

    ]
    );

$this->add_control('content', ['label'       => __('Content', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,
'description'          => __('Tab Content', 'MergePress'),

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[tab title="' . $settings['title']
 . '" pagebuilder="elementor"]' . $settings['content'] . '[/tab]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_automotive_form extends \Elementor\Widget_Base {
public function get_name(){ return 'automotive_form';}

public function get_title(){ return 'Automotive Form';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('form', ['label'       => __('Form', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Choose which form will be displayed', 'MergePress'),
'multiple'          => true,
'options'          => [
'' => __('Choose a form', 'MergePress'),
'request' => __('Request More Info', 'MergePress'),
'schedule' => __('Schedule Test Drive', 'MergePress'),
'make_offer' => __('Make an Offer', 'MergePress'),
'trade_in' => __('Trade-In Appraisal', 'MergePress'),
'email_friend' => __('Email to a Friend', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[automotive_form form="' . (isset($settings['form']) && is_array($settings['form']) && !empty($settings['form']) ? implode(",", $settings['form']) : $settings['form'])
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_featured_brands extends \Elementor\Widget_Base {
public function get_name(){ return 'featured_brands';}

public function get_title(){ return 'Featured Brands';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){
  /*
  Merge_Shortcodes()
  ->add_shortcode('featured_brands', 'Featured Brands')
  ->add_child('brand_logo', '')
  ->add_wpb([
    'show_settings_on_create' => false,
  ]);

  Merge_Shortcodes()
  ->add_shortcode('brand_logo', 'Brand Item')
  ->add_parent('featured_brands')
    ->add_option('img', 'image', 'Image', 'The image shown on load')
    ->add_option('hoverimg', 'image', 'Hover Image', 'Image shown when hovering over the brand')
    ->add_option('link', 'url', 'Link', 'Link brand to URL')
    ->add_extra_class_option();
  */

  $this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );


$repeater = new \Elementor\Repeater();

$repeater->add_control('img', ['label'       => __('Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('The image shown on load', 'MergePress'),

    ]
    );

$repeater->add_control('hoverimg', ['label'       => __('Hover Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('Image shown when hovering over the brand', 'MergePress'),

    ]
    );

$repeater->add_control('link', ['label'       => __('Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Link brand to URL', 'MergePress'),
'show_external'          => true,

    ]
    );

//
// $repeater->add_control('icon', ['label'       => __('Icon', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
// 'description'          => __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a> e.g. fa fa-users', 'MergePress'),
//
//     ]
//     );
//
// $repeater->add_control('content', ['label'       => __('Text', 'MergePress'),'type'        => \Elementor\Controls_Manager::WYSIWYG,
// 'description'          => __('List item content', 'MergePress'),
//
//     ]
//     );

$this->add_control(
	'brand_logo',
	[
		'label' => __( 'Brand Item', 'MergePress' ),
		'type' => \Elementor\Controls_Manager::REPEATER,
		'fields' => $repeater->get_controls(),
		// 'title_field' => '{{{ content }}}',
	]
);
$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$settings['content'] = '';

if(!empty($settings['brand_logo'])){
foreach($settings['brand_logo'] as $single_settings_id => $single_settings){
  $settings['content'] .= '[brand_logo img="' . mergepress_normalize_image($settings['brand_logo'][$single_settings_id]['img'], 'elementor')
 . '" hoverimg="' . mergepress_normalize_image($settings['brand_logo'][$single_settings_id]['hoverimg'], 'elementor')
 . '" link="' . mergepress_normalize_url($settings['brand_logo'][$single_settings_id]['link'], 'elementor')
 . '" pagebuilder="elementor"]' . (isset($settings['brand_logo'][$single_settings_id]['content']) ? $settings['brand_logo'][$single_settings_id]['content'] : '') . '[/brand_logo]';
}

}
$settings['content'] = do_shortcode($settings['content']);

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initFeaturedBrands();});</script>
<?php }
$elementor_shortcode = '[featured_brands pagebuilder="elementor"]' . $settings['content'] . '[/featured_brands]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_brand_logo extends \Elementor\Widget_Base {
public function get_name(){ return 'brand_item';}

public function get_title(){ return 'Brand Item';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('img', ['label'       => __('Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('The image shown on load', 'MergePress'),

    ]
    );

$this->add_control('hoverimg', ['label'       => __('Hover Image', 'MergePress'),'type'        => \Elementor\Controls_Manager::MEDIA,
'description'          => __('Image shown when hovering over the brand', 'MergePress'),

    ]
    );

$this->add_control('link', ['label'       => __('Link', 'MergePress'),'type'        => \Elementor\Controls_Manager::URL,
'description'          => __('Link brand to URL', 'MergePress'),
'show_external'          => true,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

$elementor_shortcode = '[brand_logo img="' . mergepress_normalize_image($settings['img'], 'elementor')
 . '" hoverimg="' . mergepress_normalize_image($settings['hoverimg'], 'elementor')
 . '" link="' . mergepress_normalize_url($settings['link'], 'elementor')
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_inventory_display extends \Elementor\Widget_Base {
public function get_name(){ return 'inventory';}

public function get_title(){ return 'Inventory';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('layout', ['label'       => __('Layout', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('The listings display layout', 'MergePress'),
'default'          => 'wide_fullwidth',
'multiple'          => true,
'options'          => [
'wide_fullwidth' => __('Wide Fullwidth', 'MergePress'),
'wide_left' => __('Wide Sidebar Left', 'MergePress'),
'wide_right' => __('Wide Sidebar Right', 'MergePress'),
'boxed_fullwidth' => __('Boxed Fullwidth', 'MergePress'),
'boxed_left' => __('Boxed Sidebar Left', 'MergePress'),
'boxed_right' => __('Boxed Sidebar Right', 'MergePress'),
]
,

    ]
    );

$this->add_control('sold_only', ['label'       => __('Sold Listings Only', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Set this option to true to only show sold inventory', 'MergePress'),
'default'          => 'false',
'multiple'          => true,
'options'          => [
'false' => __('False', 'MergePress'),
'true' => __('True', 'MergePress'),
]
,

    ]
    );

$this->add_control('arrivals', ['label'       => __('Newest Arrivals', 'MergePress'),'type'        => \Elementor\Controls_Manager::NUMBER,
'description'          => __('Show listings that have been added in the last ___ days (Enter the number of days above, leave blank to not use)', 'MergePress'),

    ]
    );

$this->add_control('hide_elements', ['label'       => __('Hide Elements', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT2,
'description'          => __('Set this option to true to if you want to hide the listing dropdowns', 'MergePress'),
'multiple'          => true,
'options'          => [
'dropdown_filters' => __('Dropdown filters', 'MergePress'),
'sortby_dropdown' => __('Sort by dropdown', 'MergePress'),
'reset_button' => __('Reset Filter Button', 'MergePress'),
'vehicles_matching' => __('Vehicles Matching', 'MergePress'),
'select_view' => __('Select View', 'MergePress'),
]
,

    ]
    );

$this->add_control('hide_views', ['label'       => __('Hide Values', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'wide_fullwidth' => __('Wide Fullwidth', 'MergePress'),
'wide_left' => __('Wide Sidebar Left', 'MergePress'),
'wide_right' => __('Wide Sidebar Right', 'MergePress'),
'boxed_fullwidth' => __('Boxed Fullwidth', 'MergePress'),
'boxed_left' => __('Boxed Sidebar Left', 'MergePress'),
'boxed_right' => __('Boxed Sidebar Right', 'MergePress'),
]
,

    ]
    );

foreach(automotive_inventory_loop_options() as $loop_option_key => $loop_option_value){
$this->add_control($loop_option_value['id'], ['label'       => $loop_option_value['label'],'type'        => mergepress_option_type_normalize($loop_option_value['type'], 'elementor'),
'description'          => (isset($loop_option_value['desc']) ? $loop_option_value['desc'] : ''),
'default'          => (isset($loop_option_value['default']) ? $loop_option_value['default'] : ''),
'multiple'          => true,
'options'          => (isset($loop_option_value['options']) ? $loop_option_value['options'] : array()),

    ]
    );

}
$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initListingFilters();});</script>
<?php }
$elementor_shortcode = '[inventory_display layout="' . (isset($settings['layout']) && is_array($settings['layout']) && !empty($settings['layout']) ? implode(",", $settings['layout']) : $settings['layout'])
 . '" sold_only="' . (isset($settings['sold_only']) && is_array($settings['sold_only']) && !empty($settings['sold_only']) ? implode(",", $settings['sold_only']) : $settings['sold_only'])
 . '" arrivals="' . $settings['arrivals']
 . '" hide_elements="' . (isset($settings['hide_elements']) && is_array($settings['hide_elements']) && !empty($settings['hide_elements']) ? implode(",", $settings['hide_elements']) : $settings['hide_elements'])
 . '" hide_views="' . (isset($settings['hide_views']) && is_array($settings['hide_views']) && !empty($settings['hide_views']) ? implode(",", $settings['hide_views']) : $settings['hide_views'])
 . '"';

foreach(automotive_inventory_loop_options() as $loop_option_key => $loop_option_value){
$elementor_shortcode .= ' ' . $loop_option_value["id"] . '="' . (isset($settings[$loop_option_value["id"]]) && is_array($settings[$loop_option_value["id"]]) ? implode(",", $settings[$loop_option_value["id"]]) : (isset($settings[$loop_option_value["id"]]) && !empty($settings[$loop_option_value["id"]]) ? $settings[$loop_option_value["id"]] : '') ) . '"';
}
$elementor_shortcode .= ' pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}

class Elementor_vehicle_scroller extends \Elementor\Widget_Base {
public function get_name(){ return 'vehicle_scroller';}

public function get_title(){ return 'Vehicle Scroller';}

public function get_icon(){ return 'fa fa-code';}

public function get_categories(){ return array('Automotive'); }

protected function register_controls(){$this->start_controls_section(

      'content_section',

      [

        'label' => __('Content', 'MergePress'),

        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
]

    );

$this->add_control('title', ['label'       => __('Title', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Title of vehicle scroller', 'MergePress'),

    ]
    );

$this->add_control('description', ['label'       => __('Description', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Small description of vehicles being displayed', 'MergePress'),

    ]
    );

$this->add_control('sort', ['label'       => __('Sort By', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'description'          => __('Sort the vehicles', 'MergePress'),
'multiple'          => true,
'options'          => [
'newest' => __('Newest', 'MergePress'),
'oldest' => __('Oldest', 'MergePress'),
]
,

    ]
    );

$this->add_control('listings', ['label'       => __('Vehicles to show', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('Comma seperated list of vehicle ID\'s to display', 'MergePress'),

    ]
    );

$this->add_control('limit', ['label'       => __('Vehicle Limit', 'MergePress'),'type'        => \Elementor\Controls_Manager::TEXT,
'description'          => __('The number of vehicles to show. (-1 to display all)', 'MergePress'),

    ]
    );

$this->add_control('autoscroll', ['label'       => __('Automatic Scrolling', 'MergePress'),'type'        => \Elementor\Controls_Manager::SELECT,
'multiple'          => true,
'options'          => [
'true' => __('Enable', 'MergePress'),
]
,

    ]
    );

$this->end_controls_section();
}

protected function render(){


$settings = $this->get_settings_for_display();

if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
<script>jQuery(document).ready(function($){$.initListingScroller();});</script>
<?php }
$elementor_shortcode = '[vehicle_scroller title="' . $settings['title']
 . '" description="' . $settings['description']
 . '" sort="' . (isset($settings['sort']) && is_array($settings['sort']) && !empty($settings['sort']) ? implode(",", $settings['sort']) : $settings['sort'])
 . '" listings="' . $settings['listings']
 . '" limit="' . $settings['limit']
 . '" autoscroll="' . (isset($settings['autoscroll']) && is_array($settings['autoscroll']) && !empty($settings['autoscroll']) ? implode(",", $settings['autoscroll']) : $settings['autoscroll'])
 . '" pagebuilder="elementor"]';
echo do_shortcode($elementor_shortcode);
}

}
