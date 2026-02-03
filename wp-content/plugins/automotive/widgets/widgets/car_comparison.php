<?php

class car_comparison_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'car_comparison-widget',
	'description' => '',
);

parent::__construct( 'car_comparison-widget', 'Car Comparison', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('car_comparison_widget');
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

        echo '<div class="car_comparison-widget">';


$widget_shortcode = '[car_comparison car_ids="' . $instance['car_ids']
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $car_ids = ! empty( $instance['car_ids'] ) ? $instance['car_ids'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'car_ids' ) ); ?>"><?php echo esc_html__( 'Car ID\'s:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('car_ids'), 'input_name' => $this->get_field_name('car_ids'), 'data_name' => 'car_ids', 'type' => 'text', 'input_value' => $car_ids) ); ?>
        <p class='widget-help-description'><?php echo __('Comma seperated ID\'s of vehicles, if no car ID\'s are set it will automatically grab cars checked by user in inventory.', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['car_ids'] = ( !empty( $new_instance['car_ids'] ) ) ? $new_instance['car_ids'] : '';

        return $instance;
    }
}

$car_comparison_widget = new car_comparison_widget();