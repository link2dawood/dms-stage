<?php

class auto_video_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'auto_video-widget',
	'description' => '',
);

parent::__construct( 'auto_video-widget', 'YouTube/Vimeo video', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('auto_video_widget');
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

        echo '<div class="auto_video-widget">';


$widget_shortcode = '[auto_video url="' . mergepress_normalize_url($instance['url'], 'widget')
 . '" width="' . $instance['width']
 . '" height="' . $instance['height']
 . '" vq="' . implode(",", (isset($instance['vq']) && is_array($instance['vq']) && !empty($instance['vq']) ? $instance['vq'] : array()))
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $url = ! empty( $instance['url'] ) ? $instance['url'] : esc_html__( '', 'MergePress' );
        $width = ! empty( $instance['width'] ) ? $instance['width'] : esc_html__( '', 'MergePress' );
        $height = ! empty( $instance['height'] ) ? $instance['height'] : esc_html__( '', 'MergePress' );
        $vq = ! empty( $instance['vq'] ) ? $instance['vq'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php echo esc_html__( 'URL to video:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('url'), 'input_name' => $this->get_field_name('url'), 'data_name' => 'url', 'input_value' => $url, 'old_input_value' => '')); ?>        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php echo esc_html__( 'Width:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('width'), 'input_name' => $this->get_field_name('width'), 'data_name' => 'width', 'type' => 'text', 'input_value' => $width) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php echo esc_html__( 'Height:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('height'), 'input_name' => $this->get_field_name('height'), 'data_name' => 'height', 'type' => 'text', 'input_value' => $height) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'vq' ) ); ?>"><?php echo esc_html__( 'Video Quality:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('vq'), 'is_multi' => false, 'input_name' => $this->get_field_name('vq'), 'data_name' => 'vq', 'options' => array (
  'tiny' => '144p',
  'small' => '240p',
  'medium' => '360p',
  'large' => '480p',
  'hd720' => '720p',
  'hd1080' => '1080p',
), 'input_value' => $vq) ); ?>        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['url'] = ( !empty( $new_instance['url'] ) ) ? $new_instance['url'] : '';
        $instance['width'] = ( !empty( $new_instance['width'] ) ) ? $new_instance['width'] : '';
        $instance['height'] = ( !empty( $new_instance['height'] ) ) ? $new_instance['height'] : '';
        $instance['vq'] = ( !empty( $new_instance['vq'] ) ) ? $new_instance['vq'] : '';

        return $instance;
    }
}

$auto_video_widget = new auto_video_widget();