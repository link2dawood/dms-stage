<?php

class recent_posts_scroller_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'recent_posts_scroller-widget',
	'description' => '',
);

parent::__construct( 'recent_posts_scroller-widget', 'Recent Posts Scroller', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('recent_posts_scroller_widget');
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

        echo '<div class="recent_posts_scroller-widget">';


$widget_shortcode = '[recent_posts_scroller number="' . $instance['number']
 . '" speed="' . $instance['speed']
 . '" posts="' . $instance['posts']
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $number = ! empty( $instance['number'] ) ? $instance['number'] : esc_html__( '2', 'MergePress' );
        $speed = ! empty( $instance['speed'] ) ? $instance['speed'] : esc_html__( '500', 'MergePress' );
        $posts = ! empty( $instance['posts'] ) ? $instance['posts'] : esc_html__( '3', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php echo esc_html__( 'Posts to show:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('number'), 'input_name' => $this->get_field_name('number'), 'data_name' => 'number', 'type' => 'number', 'input_value' => $number) ); ?>
        <p class='widget-help-description'><?php echo __('Number of posts to display at a time', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'speed' ) ); ?>"><?php echo esc_html__( 'Speed:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('speed'), 'input_name' => $this->get_field_name('speed'), 'data_name' => 'speed', 'type' => 'number', 'input_value' => $speed) ); ?>
        <p class='widget-help-description'><?php echo __('Spped of the scroller', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>"><?php echo esc_html__( 'Number of posts:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('posts'), 'input_name' => $this->get_field_name('posts'), 'data_name' => 'posts', 'type' => 'number', 'input_value' => $posts) ); ?>
        <p class='widget-help-description'><?php echo __('Number of posts to scroll through', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['number'] = ( !empty( $new_instance['number'] ) ) ? $new_instance['number'] : '';
        $instance['speed'] = ( !empty( $new_instance['speed'] ) ) ? $new_instance['speed'] : '';
        $instance['posts'] = ( !empty( $new_instance['posts'] ) ) ? $new_instance['posts'] : '';

        return $instance;
    }
}

$recent_posts_scroller_widget = new recent_posts_scroller_widget();