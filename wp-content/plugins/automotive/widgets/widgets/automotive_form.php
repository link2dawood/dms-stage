<?php

class automotive_form_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'automotive_form-widget',
	'description' => '',
);

parent::__construct( 'automotive_form-widget', 'Automotive Form', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('automotive_form_widget');
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

        echo '<div class="automotive_form-widget">';


$widget_shortcode = '[automotive_form form="' . (isset($instance['form']) && !empty($instance['form']) ? $instance['form'] : array())
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $form = ! empty( $instance['form'] ) ? $instance['form'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'form' ) ); ?>"><?php echo esc_html__( 'Form:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('form'), 'is_multi' => false, 'input_name' => $this->get_field_name('form'), 'data_name' => 'form', 'options' => array (
  '' => 'Choose a form',
  'request' => 'Request More Info',
  'schedule' => 'Schedule Test Drive',
  'make_offer' => 'Make an Offer',
  'trade_in' => 'Trade-In Appraisal',
  'email_friend' => 'Email to a Friend',
), 'input_value' => $form) ); ?>        <p class='widget-help-description'><?php echo __('Choose which form will be displayed', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['form'] = ( !empty( $new_instance['form'] ) ) ? $new_instance['form'] : '';

        return $instance;
    }
}

$automotive_form_widget = new automotive_form_widget();