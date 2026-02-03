<?php

class button_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'button-widget',
	'description' => '',
);

parent::__construct( 'button-widget', 'Button', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('button_widget');
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

        echo '<div class="button-widget">';


$widget_shortcode = '[button size="' . implode(",", (isset($instance['size']) && is_array($instance['size']) && !empty($instance['size']) ? $instance['size'] : array()))
 . '" align="' . implode(",", (isset($instance['align']) && is_array($instance['align']) && !empty($instance['align']) ? $instance['align'] : array()))
 . '" href="' . mergepress_normalize_url($instance['href'], 'widget')
 . '" pagebuilder="widget"]' . $instance['content'] . '[/button]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
        $size = ! empty( $instance['size'] ) ? $instance['size'] : esc_html__( '', 'MergePress' );
        $align = ! empty( $instance['align'] ) ? $instance['align'] : esc_html__( '', 'MergePress' );
        $href = ! empty( $instance['href'] ) ? $instance['href'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Button Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content', 'type' => 'text', 'input_value' => $content) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php echo esc_html__( 'Button Size:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('size'), 'is_multi' => false, 'input_name' => $this->get_field_name('size'), 'data_name' => 'size', 'options' => array (
  'xs' => 'Extra Small',
  'sm' => 'Small',
  'md' => 'Medium',
  'lg' => 'Large',
  'xl' => 'Extra Large',
), 'input_value' => $size) ); ?>        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php echo esc_html__( 'Button Alignment:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('align'), 'is_multi' => false, 'input_name' => $this->get_field_name('align'), 'data_name' => 'align', 'options' => array (
  'left' => 'Left',
  'center' => 'Center',
  'right' => 'Right',
), 'input_value' => $align) ); ?>        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'href' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('href'), 'input_name' => $this->get_field_name('href'), 'data_name' => 'href', 'input_value' => $href, 'old_input_value' => '')); ?>        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
        $instance['size'] = ( !empty( $new_instance['size'] ) ) ? $new_instance['size'] : '';
        $instance['align'] = ( !empty( $new_instance['align'] ) ) ? $new_instance['align'] : '';
        $instance['href'] = ( !empty( $new_instance['href'] ) ) ? $new_instance['href'] : '';

        return $instance;
    }
}

$button_widget = new button_widget();