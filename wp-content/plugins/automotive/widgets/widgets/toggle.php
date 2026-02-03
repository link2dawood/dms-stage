<?php

class toggle_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'toggle-widget',
	'description' => '',
);

parent::__construct( 'toggle-widget', 'FAQ Item', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('toggle_widget');
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

        echo '<div class="toggle-widget">';


$widget_shortcode = '[toggle title="' . $instance['title']
 . '" categories="' . $instance['categories']
 . '" state="' . implode(",", (isset($instance['state']) && is_array($instance['state']) && !empty($instance['state']) ? $instance['state'] : array()))
 . '" pagebuilder="widget"]' . $instance['content'] . '[/toggle]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $categories = ! empty( $instance['categories'] ) ? $instance['categories'] : esc_html__( 'category1, category2, etc', 'MergePress' );
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
        $state = ! empty( $instance['state'] ) ? $instance['state'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        <p class='widget-help-description'><?php echo __('Title of FAQ item', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'categories' ) ); ?>"><?php echo esc_html__( 'Categories:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('categories'), 'input_name' => $this->get_field_name('categories'), 'data_name' => 'categories', 'type' => 'text', 'input_value' => $categories) ); ?>
        <p class='widget-help-description'><?php echo __('Comma seperated list of categories item is in', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'FAQ Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'state' ) ); ?>"><?php echo esc_html__( 'State of the item:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('state'), 'is_multi' => false, 'input_name' => $this->get_field_name('state'), 'data_name' => 'state', 'options' => array (
  'collapsed' => 'Closed',
  'in' => 'Open',
), 'input_value' => $state) ); ?>        <p class='widget-help-description'><?php echo __('Choose whether the item is open or close', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['categories'] = ( !empty( $new_instance['categories'] ) ) ? $new_instance['categories'] : '';
        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
        $instance['state'] = ( !empty( $new_instance['state'] ) ) ? $new_instance['state'] : '';

        return $instance;
    }
}

$toggle_widget = new toggle_widget();