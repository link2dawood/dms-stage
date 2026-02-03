<?php

class animated_numbers_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'animated_numbers-widget',
	'description' => '',
);

parent::__construct( 'animated_numbers-widget', 'Animated Numbers', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('animated_numbers_widget');
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

        echo '<div class="animated_numbers-widget">';


$widget_shortcode = '[animated_numbers icon="' . $instance['icon']
 . '" number="' . $instance['number']
 . '" before_number="' . $instance['before_number']
 . '" after_number="' . $instance['after_number']
 . '" separator_value="' . $instance['separator_value']
 . '" align="' . implode(",", (isset($instance['align']) && is_array($instance['align']) && !empty($instance['align']) ? $instance['align'] : array()))
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $icon = ! empty( $instance['icon'] ) ? $instance['icon'] : esc_html__( '', 'MergePress' );
        $number = ! empty( $instance['number'] ) ? $instance['number'] : esc_html__( '', 'MergePress' );
        $before_number = ! empty( $instance['before_number'] ) ? $instance['before_number'] : esc_html__( '', 'MergePress' );
        $after_number = ! empty( $instance['after_number'] ) ? $instance['after_number'] : esc_html__( '', 'MergePress' );
        $separator_value = ! empty( $instance['separator_value'] ) ? $instance['separator_value'] : esc_html__( '', 'MergePress' );
        $align = ! empty( $instance['align'] ) ? $instance['align'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php echo esc_html__( 'Icon:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('icon'), 'input_name' => $this->get_field_name('icon'), 'data_name' => 'icon', 'type' => 'text', 'input_value' => $icon) ); ?>
        <p class='widget-help-description'><?php echo __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php echo esc_html__( 'Number:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('number'), 'input_name' => $this->get_field_name('number'), 'data_name' => 'number', 'type' => 'number', 'input_value' => $number) ); ?>
        <p class='widget-help-description'><?php echo __('The number to animate to', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'before_number' ) ); ?>"><?php echo esc_html__( 'Before Number:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('before_number'), 'input_name' => $this->get_field_name('before_number'), 'data_name' => 'before_number', 'type' => 'text', 'input_value' => $before_number) ); ?>
        <p class='widget-help-description'><?php echo __('Display text before the number ($, €, #)', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'after_number' ) ); ?>"><?php echo esc_html__( 'After Number:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('after_number'), 'input_name' => $this->get_field_name('after_number'), 'data_name' => 'after_number', 'type' => 'text', 'input_value' => $after_number) ); ?>
        <p class='widget-help-description'><?php echo __('Display text after the number (%, !)', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'separator_value' ) ); ?>"><?php echo esc_html__( 'Seperator Value:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('separator_value'), 'input_name' => $this->get_field_name('separator_value'), 'data_name' => 'separator_value', 'type' => 'text', 'input_value' => $separator_value) ); ?>
        <p class='widget-help-description'><?php echo __('Change the value used to break up large numbers, defaults to a \',\'', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php echo esc_html__( 'Align:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('align'), 'is_multi' => false, 'input_name' => $this->get_field_name('align'), 'data_name' => 'align', 'options' => array (
  'left' => 'Left',
  'center' => 'Center',
  'right' => 'Right',
), 'input_value' => $align) ); ?>        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['icon'] = ( !empty( $new_instance['icon'] ) ) ? $new_instance['icon'] : '';
        $instance['number'] = ( !empty( $new_instance['number'] ) ) ? $new_instance['number'] : '';
        $instance['before_number'] = ( !empty( $new_instance['before_number'] ) ) ? $new_instance['before_number'] : '';
        $instance['after_number'] = ( !empty( $new_instance['after_number'] ) ) ? $new_instance['after_number'] : '';
        $instance['separator_value'] = ( !empty( $new_instance['separator_value'] ) ) ? $new_instance['separator_value'] : '';
        $instance['align'] = ( !empty( $new_instance['align'] ) ) ? $new_instance['align'] : '';

        return $instance;
    }
}

$animated_numbers_widget = new animated_numbers_widget();