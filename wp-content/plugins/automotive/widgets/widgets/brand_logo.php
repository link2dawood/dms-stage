<?php

class brand_logo_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'brand_logo-widget',
	'description' => '',
);

parent::__construct( 'brand_logo-widget', 'Brand Item', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('brand_logo_widget');
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

        echo '<div class="brand_logo-widget">';


$widget_shortcode = '[brand_logo img="' . mergepress_normalize_image($instance['img'], 'widget')
 . '" hoverimg="' . mergepress_normalize_image($instance['hoverimg'], 'widget')
 . '" link="' . mergepress_normalize_url($instance['link'], 'widget')
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $img = ! empty( $instance['img'] ) ? $instance['img'] : esc_html__( '', 'MergePress' );
        $hoverimg = ! empty( $instance['hoverimg'] ) ? $instance['hoverimg'] : esc_html__( '', 'MergePress' );
        $link = ! empty( $instance['link'] ) ? $instance['link'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'img' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('img'), 'input_name' => $this->get_field_name('img'), 'data_name' => 'img', 'input_value' => $img)); ?>
        <p class='widget-help-description'><?php echo __('The image shown on load', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'hoverimg' ) ); ?>"><?php echo esc_html__( 'Hover Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('hoverimg'), 'input_name' => $this->get_field_name('hoverimg'), 'data_name' => 'hoverimg', 'input_value' => $hoverimg)); ?>
        <p class='widget-help-description'><?php echo __('Image shown when hovering over the brand', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('link'), 'input_name' => $this->get_field_name('link'), 'data_name' => 'link', 'input_value' => $link, 'old_input_value' => '')); ?>        <p class='widget-help-description'><?php echo __('Link brand to URL', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['img'] = ( !empty( $new_instance['img'] ) ) ? $new_instance['img'] : '';
        $instance['hoverimg'] = ( !empty( $new_instance['hoverimg'] ) ) ? $new_instance['hoverimg'] : '';
        $instance['link'] = ( !empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';

        return $instance;
    }
}

$brand_logo_widget = new brand_logo_widget();