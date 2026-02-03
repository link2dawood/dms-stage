<?php

class heading_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'heading-widget',
	'description' => '',
);

parent::__construct( 'heading-widget', 'Heading', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('heading_widget');
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

        echo '<div class="heading-widget">';


$widget_shortcode = '[heading heading="' . implode(",", (isset($instance['heading']) && is_array($instance['heading']) && !empty($instance['heading']) ? $instance['heading'] : array()))
 . '" pagebuilder="widget"]' . $instance['content'] . '[/heading]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $heading = ! empty( $instance['heading'] ) ? $instance['heading'] : esc_html__( '', 'MergePress' );
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php echo esc_html__( 'Heading:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('heading'), 'is_multi' => false, 'input_name' => $this->get_field_name('heading'), 'data_name' => 'heading', 'options' => array (
  'h1' => 'Heading 1',
  'h2' => 'Heading 2',
  'h3' => 'Heading 3',
  'h4' => 'Heading 4',
  'h5' => 'Heading 5',
  'h6' => 'Heading 6',
), 'input_value' => $heading) ); ?>        <p class='widget-help-description'><?php echo __('Size of heading', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content', 'type' => 'text', 'input_value' => $content) ); ?>
        <p class='widget-help-description'><?php echo __('Heading Text', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['heading'] = ( !empty( $new_instance['heading'] ) ) ? $new_instance['heading'] : '';
        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';

        return $instance;
    }
}

$heading_widget = new heading_widget();