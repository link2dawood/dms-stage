<?php

class featured_icon_box_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'featured_icon_box-widget',
	'description' => '',
);

parent::__construct( 'featured_icon_box-widget', 'Featured Icon Box', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('featured_icon_box_widget');
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

        echo '<div class="featured_icon_box-widget">';


$widget_shortcode = '[featured_icon_box title="' . $instance['title']
 . '" icon="' . $instance['icon']
 . '" pagebuilder="widget"]' . $instance['content'] . '[/featured_icon_box]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $icon = ! empty( $instance['icon'] ) ? $instance['icon'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        <p class='widget-help-description'><?php echo __('Title of the featured box', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php echo esc_html__( 'Icon:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('icon'), 'input_name' => $this->get_field_name('icon'), 'data_name' => 'icon', 'type' => 'text', 'input_value' => $icon) ); ?>
        <p class='widget-help-description'><?php echo __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['icon'] = ( !empty( $new_instance['icon'] ) ) ? $new_instance['icon'] : '';

        return $instance;
    }
}

$featured_icon_box_widget = new featured_icon_box_widget();