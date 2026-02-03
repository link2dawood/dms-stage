<?php

class auto_contact_form_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'auto_contact_form-widget',
	'description' => '',
);

parent::__construct( 'auto_contact_form-widget', 'Contact Form', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('auto_contact_form_widget');
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

        echo '<div class="auto_contact_form-widget">';


$widget_shortcode = '[auto_contact_form name="' . $instance['name']
 . '" email="' . $instance['email']
 . '" message="' . $instance['message']
 . '" button="' . $instance['button']
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $name = ! empty( $instance['name'] ) ? $instance['name'] : esc_html__( '', 'MergePress' );
        $email = ! empty( $instance['email'] ) ? $instance['email'] : esc_html__( '', 'MergePress' );
        $message = ! empty( $instance['message'] ) ? $instance['message'] : esc_html__( '', 'MergePress' );
        $button = ! empty( $instance['button'] ) ? $instance['button'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php echo esc_html__( 'Name Placeholder:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('name'), 'input_name' => $this->get_field_name('name'), 'data_name' => 'name', 'type' => 'text', 'input_value' => $name) ); ?>
        <p class='widget-help-description'><?php echo __('This is the text \'behind\' the name textfield.', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php echo esc_html__( 'Email Placeholder:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('email'), 'input_name' => $this->get_field_name('email'), 'data_name' => 'email', 'type' => 'text', 'input_value' => $email) ); ?>
        <p class='widget-help-description'><?php echo __('This is the text \'behind\' the email textfield.', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'message' ) ); ?>"><?php echo esc_html__( 'Message Placeholder:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('message'), 'input_name' => $this->get_field_name('message'), 'data_name' => 'message', 'type' => 'text', 'input_value' => $message) ); ?>
        <p class='widget-help-description'><?php echo __('This is the text \'behind\' the message textbox', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'button' ) ); ?>"><?php echo esc_html__( 'Submit Button:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('button'), 'input_name' => $this->get_field_name('button'), 'data_name' => 'button', 'type' => 'text', 'input_value' => $button) ); ?>
        <p class='widget-help-description'><?php echo __('This is the text used on the submit button', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['name'] = ( !empty( $new_instance['name'] ) ) ? $new_instance['name'] : '';
        $instance['email'] = ( !empty( $new_instance['email'] ) ) ? $new_instance['email'] : '';
        $instance['message'] = ( !empty( $new_instance['message'] ) ) ? $new_instance['message'] : '';
        $instance['button'] = ( !empty( $new_instance['button'] ) ) ? $new_instance['button'] : '';

        return $instance;
    }
}

$auto_contact_form_widget = new auto_contact_form_widget();