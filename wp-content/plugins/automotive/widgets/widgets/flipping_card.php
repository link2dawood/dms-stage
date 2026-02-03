<?php

class flipping_card_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'flipping_card-widget',
	'description' => '',
);

parent::__construct( 'flipping_card-widget', 'Flipping Card', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('flipping_card_widget');
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

        echo '<div class="flipping_card-widget">';


$widget_shortcode = '[flipping_card image="' . mergepress_normalize_image($instance['image'], 'widget')
 . '" larger_img="' . mergepress_normalize_image($instance['larger_img'], 'widget')
 . '" title="' . $instance['title']
 . '" link="' . mergepress_normalize_url($instance['link'], 'widget')
 . '" card_link="' . mergepress_normalize_url($instance['card_link'], 'widget')
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $image = ! empty( $instance['image'] ) ? $instance['image'] : esc_html__( '', 'MergePress' );
        $larger_img = ! empty( $instance['larger_img'] ) ? $instance['larger_img'] : esc_html__( '', 'MergePress' );
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $link = ! empty( $instance['link'] ) ? $instance['link'] : esc_html__( '', 'MergePress' );
        $card_link = ! empty( $instance['card_link'] ) ? $instance['card_link'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('image'), 'input_name' => $this->get_field_name('image'), 'data_name' => 'image', 'input_value' => $image)); ?>
        <p class='widget-help-description'><?php echo __('This image will be shown on the front of the flipping card', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'larger_img' ) ); ?>"><?php echo esc_html__( 'Larger Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('larger_img'), 'input_name' => $this->get_field_name('larger_img'), 'data_name' => 'larger_img', 'input_value' => $larger_img)); ?>
        <p class='widget-help-description'><?php echo __('This image will open in a fancybox', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('link'), 'input_name' => $this->get_field_name('link'), 'data_name' => 'link', 'input_value' => $link, 'old_input_value' => '')); ?>        <p class='widget-help-description'><?php echo __('Link of link button on flipped side', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'card_link' ) ); ?>"><?php echo esc_html__( 'Card Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('card_link'), 'input_name' => $this->get_field_name('card_link'), 'data_name' => 'card_link', 'input_value' => $card_link, 'old_input_value' => '')); ?>        <p class='widget-help-description'><?php echo __('Link the entire flipping card', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['image'] = ( !empty( $new_instance['image'] ) ) ? $new_instance['image'] : '';
        $instance['larger_img'] = ( !empty( $new_instance['larger_img'] ) ) ? $new_instance['larger_img'] : '';
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['link'] = ( !empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';
        $instance['card_link'] = ( !empty( $new_instance['card_link'] ) ) ? $new_instance['card_link'] : '';

        return $instance;
    }
}

$flipping_card_widget = new flipping_card_widget();