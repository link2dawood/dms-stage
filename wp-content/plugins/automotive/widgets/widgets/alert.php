<?php

class alert_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'alert-widget',
	'description' => '',
);

parent::__construct( 'alert-widget', 'Alert', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('alert_widget');
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

        echo '<div class="alert-widget">';


$widget_shortcode = '[alert type="' . implode(",", (isset($instance['type']) && is_array($instance['type']) && !empty($instance['type']) ? $instance['type'] : array()))
 . '" close="' . implode(",", (isset($instance['close']) && is_array($instance['close']) && !empty($instance['close']) ? $instance['close'] : array()))
 . '" pagebuilder="widget"]' . $instance['content'] . '[/alert]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $type = ! empty( $instance['type'] ) ? $instance['type'] : esc_html__( '', 'MergePress' );
        $close = ! empty( $instance['close'] ) ? $instance['close'] : esc_html__( '', 'MergePress' );
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php echo esc_html__( 'Type:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('type'), 'is_multi' => false, 'input_name' => $this->get_field_name('type'), 'data_name' => 'type', 'options' => array (
  0 => 'Danger',
  1 => 'Success',
  2 => 'Info',
  3 => 'Warning',
), 'input_value' => $type) ); ?>        <p class='widget-help-description'><?php echo __('Type of alert', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'close' ) ); ?>"><?php echo esc_html__( 'Close Button:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('close'), 'is_multi' => false, 'input_name' => $this->get_field_name('close'), 'data_name' => 'close', 'options' => array (
  'No' => 0,
  'Yes' => 1,
), 'input_value' => $close) ); ?>        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        <p class='widget-help-description'><?php echo __('Alert content', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['type'] = ( !empty( $new_instance['type'] ) ) ? $new_instance['type'] : '';
        $instance['close'] = ( !empty( $new_instance['close'] ) ) ? $new_instance['close'] : '';
        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';

        return $instance;
    }
}

$alert_widget = new alert_widget();