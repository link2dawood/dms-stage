<?php

class testimonial_quote_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'testimonial_quote-widget',
	'description' => '',
);

parent::__construct( 'testimonial_quote-widget', 'Testimonial Quote', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('testimonial_quote_widget');
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

        echo '<div class="testimonial_quote-widget">';


$widget_shortcode = '[testimonial_quote name="' . $instance['name']
 . '" pagebuilder="widget"]' . $instance['content'] . '[/testimonial_quote]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $name = ! empty( $instance['name'] ) ? $instance['name'] : esc_html__( '', 'MergePress' );
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php echo esc_html__( 'Name:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('name'), 'input_name' => $this->get_field_name('name'), 'data_name' => 'name', 'type' => 'text', 'input_value' => $name) ); ?>
        <p class='widget-help-description'><?php echo __('Name of person giving the testimonial', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Quote:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        <p class='widget-help-description'><?php echo __('Testimonial quote', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['name'] = ( !empty( $new_instance['name'] ) ) ? $new_instance['name'] : '';
        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';

        return $instance;
    }
}

$testimonial_quote_widget = new testimonial_quote_widget();