<?php

class progress_bar_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'progress_bar-widget',
	'description' => '',
);

parent::__construct( 'progress_bar-widget', 'Progress Bar', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('progress_bar_widget');
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

        echo '<div class="progress_bar-widget">';


$widget_shortcode = '[progress_bar color="' . $instance['color']
 . '" filled="' . $instance['filled']
 . '" pagebuilder="widget"]' . $instance['content'] . '[/progress_bar]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( 'Text', 'MergePress' );
        $color = ! empty( $instance['color'] ) ? $instance['color'] : esc_html__( 'Color of progress bar', 'MergePress' );
        $filled = ! empty( $instance['filled'] ) ? $instance['filled'] : esc_html__( '90%', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        <p class='widget-help-description'><?php echo __('Text to be displayed inside the progress bar', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>"><?php echo esc_html__( 'Color:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_color_field(array('id' => $this->get_field_id('color'), 'input_name' => $this->get_field_name('color'), 'data_name' => 'color',  'input_value' => $color) ); ?>
        <p class='widget-help-description'><?php echo __('#c7081b', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'filled' ) ); ?>"><?php echo esc_html__( 'Filled:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('filled'), 'input_name' => $this->get_field_name('filled'), 'data_name' => 'filled', 'type' => 'text', 'input_value' => $filled) ); ?>
        <p class='widget-help-description'><?php echo __('The percentage of the progress bar filled with color', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
        $instance['color'] = ( !empty( $new_instance['color'] ) ) ? $new_instance['color'] : '';
        $instance['filled'] = ( !empty( $new_instance['filled'] ) ) ? $new_instance['filled'] : '';

        return $instance;
    }
}

$progress_bar_widget = new progress_bar_widget();