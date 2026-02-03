<?php

class quote_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'quote-widget',
	'description' => '',
);

parent::__construct( 'quote-widget', 'Post Quote', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('quote_widget');
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

        echo '<div class="quote-widget">';


$widget_shortcode = '[quote color="' . $instance['color']
 . '" pagebuilder="widget"]' . $instance['content'] . '[/quote]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
        $color = ! empty( $instance['color'] ) ? $instance['color'] : esc_html__( '#c7081b', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        <p class='widget-help-description'><?php echo __('Quote text', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>"><?php echo esc_html__( 'Color:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_color_field(array('id' => $this->get_field_id('color'), 'input_name' => $this->get_field_name('color'), 'data_name' => 'color',  'input_value' => $color) ); ?>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
        $instance['color'] = ( !empty( $new_instance['color'] ) ) ? $new_instance['color'] : '';

        return $instance;
    }
}

$quote_widget = new quote_widget();