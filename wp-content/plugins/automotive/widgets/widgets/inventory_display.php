<?php

class inventory_display_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'inventory_display-widget',
	'description' => '',
);

parent::__construct( 'inventory_display-widget', 'Inventory', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('inventory_display_widget');
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

        echo '<div class="inventory_display-widget">';


$widget_shortcode = '[inventory_display layout="' . implode(",", (isset($instance['layout']) && is_array($instance['layout']) && !empty($instance['layout']) ? $instance['layout'] : array()))
 . '" sold_only="' . (isset($instance['sold_only']) && !empty($instance['sold_only']) ? $instance['sold_only'] : '')
 . '" arrivals="' . (isset($instance['arrivals']) ? $instance['arrivals'] : '')
 . '" hide_elements="' . implode(",", (isset($instance['hide_elements']) && is_array($instance['hide_elements']) && !empty($instance['hide_elements']) ? $instance['hide_elements'] : array()))
 . '" hide_views="' . implode(",", (isset($instance['hide_views']) && is_array($instance['hide_views']) && !empty($instance['hide_views']) ? $instance['hide_views'] : array()))
 . '"';

foreach(automotive_inventory_loop_options() as $loop_option_key => $loop_option_value){
$widget_shortcode .= (isset($instance[$loop_option_value['id']]) ? ' ' . $loop_option_value['id'] . '="' . $instance[$loop_option_value['id']] . '"' : '');
}
$widget_shortcode .= ' pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $layout = ! empty( $instance['layout'] ) ? $instance['layout'] : esc_html__( 'wide_fullwidth', 'MergePress' );
        $sold_only = ! empty( $instance['sold_only'] ) ? $instance['sold_only'] : esc_html__( 'false', 'MergePress' );
        $arrivals = ! empty( $instance['arrivals'] ) ? $instance['arrivals'] : esc_html__( '', 'MergePress' );
        $hide_elements = ! empty( $instance['hide_elements'] ) ? $instance['hide_elements'] : esc_html__( '', 'MergePress' );
        $hide_views = ! empty( $instance['hide_views'] ) ? $instance['hide_views'] : esc_html__( '', 'MergePress' );
foreach(automotive_inventory_loop_options() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ! empty( $instance[$loop_option_value['id']] ) ? $instance[$loop_option_value['id']] : (isset($loop_option_value['default']) ? $loop_option_value['default'] : '');
}
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php echo esc_html__( 'Layout:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('layout'), 'is_multi' => false, 'input_name' => $this->get_field_name('layout'), 'data_name' => 'layout', 'options' => array (
  'wide_fullwidth' => 'Wide Fullwidth',
  'wide_left' => 'Wide Sidebar Left',
  'wide_right' => 'Wide Sidebar Right',
  'boxed_fullwidth' => 'Boxed Fullwidth',
  'boxed_left' => 'Boxed Sidebar Left',
  'boxed_right' => 'Boxed Sidebar Right',
), 'input_value' => $layout) ); ?>        <p class='widget-help-description'><?php echo __('The listings display layout', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'sold_only' ) ); ?>"><?php echo esc_html__( 'Sold Listings Only:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('sold_only'), 'is_multi' => false, 'input_name' => $this->get_field_name('sold_only'), 'data_name' => 'sold_only', 'options' => array (
  '' => 'False',
  'true' => 'True',
), 'input_value' => $sold_only) ); ?>        <p class='widget-help-description'><?php echo __('Set this option to true to only show sold inventory', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'arrivals' ) ); ?>"><?php echo esc_html__( 'Newest Arrivals:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('arrivals'), 'input_name' => $this->get_field_name('arrivals'), 'data_name' => 'arrivals', 'type' => 'number', 'input_value' => $arrivals) ); ?>
        <p class='widget-help-description'><?php echo __('Show listings that have been added in the last ___ days (Enter the number of days above, leave blank to not use)', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'hide_elements' ) ); ?>"><?php echo esc_html__( 'Hide Elements:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_checkbox_field(array('id' => $this->get_field_id('hide_elements'), 'input_name' => $this->get_field_name('hide_elements'), 'data_name' => 'hide_elements', 'options' => array (
  'dropdown_filters' => 'Dropdown filters',
  'sortby_dropdown' => 'Sort by dropdown',
  'reset_button' => 'Reset Filter Button',
  'vehicles_matching' => 'Vehicles Matching',
  'select_view' => 'Select View',
), 'input_value' => $hide_elements)); ?>
        <p class='widget-help-description'><?php echo __('Set this option to true to if you want to hide the listing dropdowns', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'hide_views' ) ); ?>"><?php echo esc_html__( 'Hide Values:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_checkbox_field(array('id' => $this->get_field_id('hide_views'), 'input_name' => $this->get_field_name('hide_views'), 'data_name' => 'hide_views', 'options' => array (
  'wide_fullwidth' => 'Wide Fullwidth',
  'wide_left' => 'Wide Sidebar Left',
  'wide_right' => 'Wide Sidebar Right',
  'boxed_fullwidth' => 'Boxed Fullwidth',
  'boxed_left' => 'Boxed Sidebar Left',
  'boxed_right' => 'Boxed Sidebar Right',
), 'input_value' => $hide_views)); ?>
        </div>
<?php foreach(automotive_inventory_loop_options() as $loop_option_key => $loop_option_value){ ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( $loop_option_value['id'] ) ); ?>"><?php echo $loop_option_value['label']; ?></label>
            <?php echo call_user_func('mergepress_widget_' . $loop_option_value['type'] . '_field', array(
          'id'          => $this->get_field_id($loop_option_value['id']),
          'input_name'  => $this->get_field_name($loop_option_value['id']),
          'data_name'   => $loop_option_value['id'],
          'type'        => (isset($loop_option_value['type']) ? $loop_option_value['type'] : ''),
          'input_value' => (isset($instance[$loop_option_value['id']]) && !empty($instance[$loop_option_value['id']]) ? $instance[$loop_option_value['id']] : ''),
          'options'     => (isset($loop_option_value['options']) ? $loop_option_value['options'] : array()),
        )); ?>
        <p class='widget-help-description'><?php echo (isset($loop_option_value['desc']) ? $loop_option_value['desc'] : ''); ?></p>
        </div>
<?php } ?>        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['layout'] = ( !empty( $new_instance['layout'] ) ) ? $new_instance['layout'] : '';
        $instance['sold_only'] = ( !empty( $new_instance['sold_only'] ) ) ? $new_instance['sold_only'] : '';
        $instance['arrivals'] = ( !empty( $new_instance['arrivals'] ) ) ? $new_instance['arrivals'] : '';
        $instance['hide_elements'] = ( !empty( $new_instance['hide_elements'] ) ) ? $new_instance['hide_elements'] : '';
        $instance['hide_views'] = ( !empty( $new_instance['hide_views'] ) ) ? $new_instance['hide_views'] : '';
foreach(automotive_inventory_loop_options() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ( !empty( $new_instance[$loop_option_value['id']] ) ) ? $new_instance[$loop_option_value['id']] : '';
}
        return $instance;
    }
}

$inventory_display_widget = new inventory_display_widget();
