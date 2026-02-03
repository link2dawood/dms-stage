<?php

class detailed_panel_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'detailed_panel-widget',
	'description' => '',
);

parent::__construct( 'detailed_panel-widget', 'Detailed Panel', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('detailed_panel_widget');
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

        echo '<div class="detailed_panel-widget">';


$widget_shortcode = '[detailed_panel title="' . $instance['title']
 . '" icon="' . $instance['icon']
 . '" image="' . mergepress_normalize_image($instance['image'], 'widget')
 . '" link="' . mergepress_normalize_url($instance['link'], 'widget')
 . '" pagebuilder="widget"]' . $instance['content'] . '[/detailed_panel]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $icon = ! empty( $instance['icon'] ) ? $instance['icon'] : esc_html__( '', 'MergePress' );
        $image = ! empty( $instance['image'] ) ? $instance['image'] : esc_html__( '', 'MergePress' );
        $link = ! empty( $instance['link'] ) ? $instance['link'] : esc_html__( '', 'MergePress' );
        $content = ! empty( $instance['content'] ) ? $instance['content'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php echo esc_html__( 'Icon:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('icon'), 'input_name' => $this->get_field_name('icon'), 'data_name' => 'icon', 'type' => 'text', 'input_value' => $icon) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('image'), 'input_name' => $this->get_field_name('image'), 'data_name' => 'image', 'input_value' => $image)); ?>
        <p class='widget-help-description'><?php echo __('This will overwrite the icon setting', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('link'), 'input_name' => $this->get_field_name('link'), 'data_name' => 'link', 'input_value' => $link, 'old_input_value' => '')); ?>        <p class='widget-help-description'><?php echo __('Link for the title and icon', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"><?php echo esc_html__( 'Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content'), 'input_name' => $this->get_field_name('content'), 'data_name' => 'content',  'input_value' => $content)); ?>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['icon'] = ( !empty( $new_instance['icon'] ) ) ? $new_instance['icon'] : '';
        $instance['image'] = ( !empty( $new_instance['image'] ) ) ? $new_instance['image'] : '';
        $instance['link'] = ( !empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';
        $instance['content'] = ( !empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';

        return $instance;
    }
}

$detailed_panel_widget = new detailed_panel_widget();