<?php

class testimonials_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'testimonials-widget',
	'description' => '',
);

parent::__construct( 'testimonials-widget', 'Testimonails', $widget_ops );

    add_action('admin_footer', function(){
    $repeat_i = '{{ data.newID }}'; ?>
     <script type="text/html" id="tmpl-repeater-testimonial_quote">        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'name_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Name:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('name_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('name_repeater_' . $repeat_i . ''), 'data_name' => 'name_repeater', 'type' => 'text', 'input_value' => (isset($name_repeater[$repeat_i]) ? $name_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Name of person giving the testimonial', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Quote:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('Testimonial quote', 'MergePress'); ?></p>
        </div>
</script><?php     });
    add_action('widgets_init', function(){
      register_widget('testimonials_widget');
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

        echo '<div class="testimonials-widget">';

$instance['content'] = '';

$repeat_for = (isset($instance['name_repeater']) && !empty($instance['name_repeater']) && is_array($instance['name_repeater']) ? count($instance['name_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){
  $instance['content'] .= '[testimonial_quote name="' . $instance['name_repeater'][$repeat_i]
 . '" pagebuilder="widget"]' . $instance['content_repeater'][$repeat_i] . '[/testimonial_quote]';
} }

$instance['content'] = do_shortcode($instance['content']);


$widget_shortcode = '[testimonials slide="' . implode(",", (isset($instance['slide']) && is_array($instance['slide']) && !empty($instance['slide']) ? $instance['slide'] : array()))
 . '" speed="' . $instance['speed']
 . '" pagebuilder="widget"]' . $instance['content'] . '[/testimonials]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $slide = ! empty( $instance['slide'] ) ? $instance['slide'] : esc_html__( '', 'MergePress' );
        $speed = ! empty( $instance['speed'] ) ? $instance['speed'] : esc_html__( '500', 'MergePress' );


        $name_repeater = isset( $instance['name_repeater'] ) && ! empty( $instance['name_repeater'] ) ? $instance['name_repeater'] : array(0=>'');
        $content_repeater = isset( $instance['content_repeater'] ) && ! empty( $instance['content_repeater'] ) ? $instance['content_repeater'] : array(0=>'');
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'slide' ) ); ?>"><?php echo esc_html__( 'Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('slide'), 'is_multi' => false, 'input_name' => $this->get_field_name('slide'), 'data_name' => 'slide', 'options' => array (
  'horizontal' => 0,
  'vertical' => 1,
), 'input_value' => $slide) ); ?>        <p class='widget-help-description'><?php echo __('This will control which way the testimonials slide', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'speed' ) ); ?>"><?php echo esc_html__( 'Speed:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('speed'), 'input_name' => $this->get_field_name('speed'), 'data_name' => 'speed', 'type' => 'text', 'input_value' => $speed) ); ?>
        <p class='widget-help-description'><?php echo __('This controls the speed at which the testimonials slide', 'MergePress'); ?></p>
        </div>
<h4 class='repeat-title'>Testimonial Quotes</h4>
<div class='widget-repeater-container'>
<?php 
$repeat_for = (isset($instance['name_repeater']) && !empty($instance['name_repeater']) && is_array($instance['name_repeater']) ? count($instance['name_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){ ?>
<div class='single-repeater-row'>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'name_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Name:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('name_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('name_repeater_' . $repeat_i . ''), 'data_name' => 'name_repeater', 'type' => 'text', 'input_value' => (isset($name_repeater[$repeat_i]) ? $name_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Name of person giving the testimonial', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Quote:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('Testimonial quote', 'MergePress'); ?></p>
        </div>
</div>
<?php } } ?>
<button class='button-secondary widget-add-repeater-field' data-shortcode='testimonial_quote' data-field-name='<?php echo $this->get_field_name('__NAME__'); ?>[]'>Add New Testimonial Quote</button></div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['slide'] = ( !empty( $new_instance['slide'] ) ) ? $new_instance['slide'] : '';
        $instance['speed'] = ( !empty( $new_instance['speed'] ) ) ? $new_instance['speed'] : '';
        $instance['name_repeater'] = ( !empty( $new_instance['name_repeater'] ) ) ? $new_instance['name_repeater'] : array();
        $instance['content_repeater'] = ( !empty( $new_instance['content_repeater'] ) ) ? $new_instance['content_repeater'] : array();

        return $instance;
    }
}

$testimonials_widget = new testimonials_widget();