<?php

class icon_title_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'icon_title-widget',
	'description' => '',
);

parent::__construct( 'icon_title-widget', 'Icon & Title', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('icon_title_widget');
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

        echo '<div class="icon_title-widget">';


$widget_shortcode = '[icon_title title="' . $instance['title']
 . '" icon="' . $instance['icon']
 . '" link="' . mergepress_normalize_url($instance['link'], 'widget')
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $icon = ! empty( $instance['icon'] ) ? $instance['icon'] : esc_html__( '', 'MergePress' );
        $link = ! empty( $instance['link'] ) ? $instance['link'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php echo esc_html__( 'Icon:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('icon'), 'input_name' => $this->get_field_name('icon'), 'data_name' => 'icon', 'type' => 'text', 'input_value' => $icon) ); ?>
        <p class='widget-help-description'><?php echo __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('link'), 'input_name' => $this->get_field_name('link'), 'data_name' => 'link', 'input_value' => $link, 'old_input_value' => '')); ?>        <p class='widget-help-description'><?php echo __('Link of the icon', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['icon'] = ( !empty( $new_instance['icon'] ) ) ? $new_instance['icon'] : '';
        $instance['link'] = ( !empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';

        return $instance;
    }
}

$icon_title_widget = new icon_title_widget();