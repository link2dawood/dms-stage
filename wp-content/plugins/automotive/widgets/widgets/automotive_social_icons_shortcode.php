<?php

class automotive_social_icons_shortcode_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'automotive_social_icons_shortcode-widget',
	'description' => '',
);

parent::__construct( 'automotive_social_icons_shortcode-widget', 'Social Icons', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('automotive_social_icons_shortcode_widget');
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

        echo '<div class="automotive_social_icons_shortcode-widget">';


$widget_shortcode = '[automotive_social_icons_shortcode align="' . implode(",", (isset($instance['align']) && is_array($instance['align']) && !empty($instance['align']) ? $instance['align'] : array()))
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $align = ! empty( $instance['align'] ) ? $instance['align'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php echo esc_html__( 'Alignment:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('align'), 'is_multi' => false, 'input_name' => $this->get_field_name('align'), 'data_name' => 'align', 'options' => array (
  'left' => 'Left',
  'right' => 'Right',
), 'input_value' => $align) ); ?>        <p class='widget-help-description'><?php echo __('Change the style of the Google Map', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['align'] = ( !empty( $new_instance['align'] ) ) ? $new_instance['align'] : '';

        return $instance;
    }
}

$automotive_social_icons_shortcode_widget = new automotive_social_icons_shortcode_widget();