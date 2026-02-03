<?php

class pricing_option_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'pricing_option-widget',
	'description' => '',
);

parent::__construct( 'pricing_option-widget', 'Pricing Option', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('pricing_option_widget');
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

        echo '<div class="pricing_option-widget">';


$widget_shortcode = '[pricing_option pagebuilder="widget"]' . $instance['content'] . '[/pricing_option]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Option Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content', 'type' => 'text', 'input_value' => $content) ); ?>
        <p class='widget-help-description'><?php echo __('An option this pricing table includes', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';

        return $instance;
    }
}

$pricing_option_widget = new pricing_option_widget();