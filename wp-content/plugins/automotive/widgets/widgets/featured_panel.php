<?php

class featured_panel_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'featured_panel-widget',
	'description' => '',
);

parent::__construct( 'featured_panel-widget', 'Featured Panel', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('featured_panel_widget');
    });
  }

    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        echo '<div class="featured_panel-widget">';


$widget_shortcode = '[featured_panel title="' . $instance['title']
 . '" icon="' . mergepress_normalize_image($instance['icon'], 'widget')
 . '" hover_icon="' . mergepress_normalize_image($instance['hover_icon'], 'widget')
 . '" image_link="' . mergepress_normalize_url($instance['image_link'], 'widget')
 . '" pagebuilder="widget"]' . $instance['content'] . '[/featured_panel]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $icon = ! empty( $instance['icon'] ) ? $instance['icon'] : esc_html__( '', 'MergePress' );
        $hover_icon = ! empty( $instance['hover_icon'] ) ? $instance['hover_icon'] : esc_html__( '', 'MergePress' );
        $image_link = ! empty( $instance['image_link'] ) ? $instance['image_link'] : esc_html__( '', 'MergePress' );
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        <p class='widget-help-description'><?php echo __('Title of panel', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('icon'), 'input_name' => $this->get_field_name('icon'), 'data_name' => 'icon', 'input_value' => $icon)); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'hover_icon' ) ); ?>"><?php echo esc_html__( 'Hover Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('hover_icon'), 'input_name' => $this->get_field_name('hover_icon'), 'data_name' => 'hover_icon', 'input_value' => $hover_icon)); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'image_link' ) ); ?>"><?php echo esc_html__( 'Link the image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('image_link'), 'input_name' => $this->get_field_name('image_link'), 'data_name' => 'image_link', 'input_value' => $image_link, 'old_input_value' => '')); ?>        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['icon'] = ( !empty( $new_instance['icon'] ) ) ? $new_instance['icon'] : '';
        $instance['hover_icon'] = ( !empty( $new_instance['hover_icon'] ) ) ? $new_instance['hover_icon'] : '';
        $instance['image_link'] = ( !empty( $new_instance['image_link'] ) ) ? $new_instance['image_link'] : '';
        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';

        return $instance;
    }
}

$featured_panel_widget = new featured_panel_widget();