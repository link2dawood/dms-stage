<?php

class vehicle_scroller_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'vehicle_scroller-widget',
	'description' => '',
);

parent::__construct( 'vehicle_scroller-widget', 'Vehicle Scroller', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('vehicle_scroller_widget');
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

        echo '<div class="vehicle_scroller-widget">';


$widget_shortcode = '[vehicle_scroller title="' . $instance['title']
 . '" description="' . $instance['description']
 . '" sort="' . implode(",", (isset($instance['sort']) && is_array($instance['sort']) && !empty($instance['sort']) ? $instance['sort'] : array()))
 . '" listings="' . $instance['listings']
 . '" limit="' . $instance['limit']
 . '" autoscroll="' . implode(",", (isset($instance['autoscroll']) && is_array($instance['autoscroll']) && !empty($instance['autoscroll']) ? $instance['autoscroll'] : array()))
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : esc_html__( '', 'MergePress' );
        $sort = ! empty( $instance['sort'] ) ? $instance['sort'] : esc_html__( '', 'MergePress' );
        $listings = ! empty( $instance['listings'] ) ? $instance['listings'] : esc_html__( '', 'MergePress' );
        $limit = ! empty( $instance['limit'] ) ? $instance['limit'] : esc_html__( '', 'MergePress' );
        $autoscroll = ! empty( $instance['autoscroll'] ) ? $instance['autoscroll'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        <p class='widget-help-description'><?php echo __('Title of vehicle scroller', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php echo esc_html__( 'Description:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('description'), 'input_name' => $this->get_field_name('description'), 'data_name' => 'description', 'type' => 'text', 'input_value' => $description) ); ?>
        <p class='widget-help-description'><?php echo __('Small description of vehicles being displayed', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'sort' ) ); ?>"><?php echo esc_html__( 'Sort By:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('sort'), 'is_multi' => false, 'input_name' => $this->get_field_name('sort'), 'data_name' => 'sort', 'options' => array (
  'newest' => 'Newest',
  'oldest' => 'Oldest',
), 'input_value' => $sort) ); ?>        <p class='widget-help-description'><?php echo __('Sort the vehicles', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'listings' ) ); ?>"><?php echo esc_html__( 'Vehicles to show:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('listings'), 'input_name' => $this->get_field_name('listings'), 'data_name' => 'listings', 'type' => 'text', 'input_value' => $listings) ); ?>
        <p class='widget-help-description'><?php echo __('Comma seperated list of vehicle ID\'s to display', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php echo esc_html__( 'Vehicle Limit:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('limit'), 'input_name' => $this->get_field_name('limit'), 'data_name' => 'limit', 'type' => 'text', 'input_value' => $limit) ); ?>
        <p class='widget-help-description'><?php echo __('The number of vehicles to show. (-1 to display all)', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'autoscroll' ) ); ?>"><?php echo esc_html__( 'Automatic Scrolling:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_checkbox_field(array('id' => $this->get_field_id('autoscroll'), 'input_name' => $this->get_field_name('autoscroll'), 'data_name' => 'autoscroll', 'options' => array (
  'true' => 'Enable',
), 'input_value' => $autoscroll)); ?>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['description'] = ( !empty( $new_instance['description'] ) ) ? $new_instance['description'] : '';
        $instance['sort'] = ( !empty( $new_instance['sort'] ) ) ? $new_instance['sort'] : '';
        $instance['listings'] = ( !empty( $new_instance['listings'] ) ) ? $new_instance['listings'] : '';
        $instance['limit'] = ( !empty( $new_instance['limit'] ) ) ? $new_instance['limit'] : '';
        $instance['autoscroll'] = ( !empty( $new_instance['autoscroll'] ) ) ? $new_instance['autoscroll'] : '';

        return $instance;
    }
}

$vehicle_scroller_widget = new vehicle_scroller_widget();