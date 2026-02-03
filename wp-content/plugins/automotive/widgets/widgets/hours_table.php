<?php

class hours_table_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'hours_table-widget',
	'description' => '',
);

parent::__construct( 'hours_table-widget', 'Hours Table', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('hours_table_widget');
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

        echo '<div class="hours_table-widget">';


$widget_shortcode = '[hours_table title="' . $instance['title']
 . '" mon="' . $instance['mon']
 . '" tue="' . $instance['tue']
 . '" wed="' . $instance['wed']
 . '" thu="' . $instance['thu']
 . '" fri="' . $instance['fri']
 . '" sat="' . $instance['sat']
 . '" sun="' . $instance['sun']
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $mon = ! empty( $instance['mon'] ) ? $instance['mon'] : esc_html__( '', 'MergePress' );
        $tue = ! empty( $instance['tue'] ) ? $instance['tue'] : esc_html__( '', 'MergePress' );
        $wed = ! empty( $instance['wed'] ) ? $instance['wed'] : esc_html__( '', 'MergePress' );
        $thu = ! empty( $instance['thu'] ) ? $instance['thu'] : esc_html__( '', 'MergePress' );
        $fri = ! empty( $instance['fri'] ) ? $instance['fri'] : esc_html__( '', 'MergePress' );
        $sat = ! empty( $instance['sat'] ) ? $instance['sat'] : esc_html__( '', 'MergePress' );
        $sun = ! empty( $instance['sun'] ) ? $instance['sun'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        <p class='widget-help-description'><?php echo __('Title of the hours table', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'mon' ) ); ?>"><?php echo esc_html__( 'Monday:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('mon'), 'input_name' => $this->get_field_name('mon'), 'data_name' => 'mon', 'type' => 'text', 'input_value' => $mon) ); ?>
        <p class='widget-help-description'><?php echo __('Monday Hours', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'tue' ) ); ?>"><?php echo esc_html__( 'Tuesday:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('tue'), 'input_name' => $this->get_field_name('tue'), 'data_name' => 'tue', 'type' => 'text', 'input_value' => $tue) ); ?>
        <p class='widget-help-description'><?php echo __('Tuesday Hours', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'wed' ) ); ?>"><?php echo esc_html__( 'Wednesday:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('wed'), 'input_name' => $this->get_field_name('wed'), 'data_name' => 'wed', 'type' => 'text', 'input_value' => $wed) ); ?>
        <p class='widget-help-description'><?php echo __('Wednesday Hours', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'thu' ) ); ?>"><?php echo esc_html__( 'Thursday:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('thu'), 'input_name' => $this->get_field_name('thu'), 'data_name' => 'thu', 'type' => 'text', 'input_value' => $thu) ); ?>
        <p class='widget-help-description'><?php echo __('Thursday Hours', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'fri' ) ); ?>"><?php echo esc_html__( 'Friday:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('fri'), 'input_name' => $this->get_field_name('fri'), 'data_name' => 'fri', 'type' => 'text', 'input_value' => $fri) ); ?>
        <p class='widget-help-description'><?php echo __('Friday Hours', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'sat' ) ); ?>"><?php echo esc_html__( 'Saturday:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('sat'), 'input_name' => $this->get_field_name('sat'), 'data_name' => 'sat', 'type' => 'text', 'input_value' => $sat) ); ?>
        <p class='widget-help-description'><?php echo __('Saturday Hours', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'sun' ) ); ?>"><?php echo esc_html__( 'Sunday:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('sun'), 'input_name' => $this->get_field_name('sun'), 'data_name' => 'sun', 'type' => 'text', 'input_value' => $sun) ); ?>
        <p class='widget-help-description'><?php echo __('Sunday Hours', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['mon'] = ( !empty( $new_instance['mon'] ) ) ? $new_instance['mon'] : '';
        $instance['tue'] = ( !empty( $new_instance['tue'] ) ) ? $new_instance['tue'] : '';
        $instance['wed'] = ( !empty( $new_instance['wed'] ) ) ? $new_instance['wed'] : '';
        $instance['thu'] = ( !empty( $new_instance['thu'] ) ) ? $new_instance['thu'] : '';
        $instance['fri'] = ( !empty( $new_instance['fri'] ) ) ? $new_instance['fri'] : '';
        $instance['sat'] = ( !empty( $new_instance['sat'] ) ) ? $new_instance['sat'] : '';
        $instance['sun'] = ( !empty( $new_instance['sun'] ) ) ? $new_instance['sun'] : '';

        return $instance;
    }
}

$hours_table_widget = new hours_table_widget();