<?php

class br_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'br-widget',
	'description' => '',
);

parent::__construct( 'br-widget', 'Line Break', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('br_widget');
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

        echo '<div class="br-widget">';


$widget_shortcode = '[br pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
 ?>        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();


        return $instance;
    }
}

$br_widget = new br_widget();