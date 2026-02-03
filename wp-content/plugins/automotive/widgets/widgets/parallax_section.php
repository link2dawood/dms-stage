<?php

class parallax_section_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'parallax_section-widget',
	'description' => '',
);

parent::__construct( 'parallax_section-widget', 'Parallax', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('parallax_section_widget');
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

        echo '<div class="parallax_section-widget">';


$widget_shortcode = '[parallax_section title="' . $instance['title']
 . '" velocity="' . implode(",", (isset($instance['velocity']) && is_array($instance['velocity']) && !empty($instance['velocity']) ? $instance['velocity'] : array()))
 . '" offset="' . $instance['offset']
 . '" image="' . mergepress_normalize_image($instance['image'], 'widget')
 . '" overlay_color="' . $instance['overlay_color']
 . '" text_color="' . $instance['text_color']
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $velocity = ! empty( $instance['velocity'] ) ? $instance['velocity'] : esc_html__( '', 'MergePress' );
        $offset = ! empty( $instance['offset'] ) ? $instance['offset'] : esc_html__( '', 'MergePress' );
        $image = ! empty( $instance['image'] ) ? $instance['image'] : esc_html__( '', 'MergePress' );
        $overlay_color = ! empty( $instance['overlay_color'] ) ? $instance['overlay_color'] : esc_html__( '', 'MergePress' );
        $text_color = ! empty( $instance['text_color'] ) ? $instance['text_color'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'velocity' ) ); ?>"><?php echo esc_html__( 'Velocity:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('velocity'), 'is_multi' => false, 'input_name' => $this->get_field_name('velocity'), 'data_name' => 'velocity', 'options' => array (
  '-.3' => 0,
  '-.2' => 1,
  '-.1' => 2,
  '.1' => 3,
  '.2' => 4,
  '.3' => 5,
), 'input_value' => $velocity) ); ?>        <p class='widget-help-description'><?php echo __('How fast do you want the parallax to go (and which direction!)', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php echo esc_html__( 'Offset:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('offset'), 'input_name' => $this->get_field_name('offset'), 'data_name' => 'offset', 'type' => 'text', 'input_value' => $offset) ); ?>
        <p class='widget-help-description'><?php echo __('Amount of offset (px)', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('image'), 'input_name' => $this->get_field_name('image'), 'data_name' => 'image', 'input_value' => $image)); ?>
        <p class='widget-help-description'><?php echo __('Image used for scrolling', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'overlay_color' ) ); ?>"><?php echo esc_html__( 'Overlay Color:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_color_field(array('id' => $this->get_field_id('overlay_color'), 'input_name' => $this->get_field_name('overlay_color'), 'data_name' => 'overlay_color',  'input_value' => $overlay_color) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>"><?php echo esc_html__( 'Text Color:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_color_field(array('id' => $this->get_field_id('text_color'), 'input_name' => $this->get_field_name('text_color'), 'data_name' => 'text_color',  'input_value' => $text_color) ); ?>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['velocity'] = ( !empty( $new_instance['velocity'] ) ) ? $new_instance['velocity'] : '';
        $instance['offset'] = ( !empty( $new_instance['offset'] ) ) ? $new_instance['offset'] : '';
        $instance['image'] = ( !empty( $new_instance['image'] ) ) ? $new_instance['image'] : '';
        $instance['overlay_color'] = ( !empty( $new_instance['overlay_color'] ) ) ? $new_instance['overlay_color'] : '';
        $instance['text_color'] = ( !empty( $new_instance['text_color'] ) ) ? $new_instance['text_color'] : '';

        return $instance;
    }
}

$parallax_section_widget = new parallax_section_widget();