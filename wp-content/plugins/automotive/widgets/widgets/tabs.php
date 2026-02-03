<?php

class tabs_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'tabs-widget',
	'description' => '',
);

parent::__construct( 'tabs-widget', 'Tabs', $widget_ops );

    add_action('admin_footer', function(){
    $repeat_i = '{{ data.newID }}'; ?>
     <script type="text/html" id="tmpl-repeater-tab">        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('title_repeater_' . $repeat_i . ''), 'data_name' => 'title_repeater', 'type' => 'text', 'input_value' => (isset($title_repeater[$repeat_i]) ? $title_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Tab title', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('Tab Content', 'MergePress'); ?></p>
        </div>
</script><?php     });
    add_action('widgets_init', function(){
      register_widget('tabs_widget');
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

        echo '<div class="tabs-widget">';

$instance['content'] = '';

$repeat_for = (isset($instance['title_repeater']) && !empty($instance['title_repeater']) && is_array($instance['title_repeater']) ? count($instance['title_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){
  $instance['content'] .= '[tab title="' . $instance['title_repeater'][$repeat_i]
 . '" pagebuilder="widget"]' . $instance['content_repeater'][$repeat_i] . '[/tab]';
} }

$instance['content'] = do_shortcode($instance['content']);


$widget_shortcode = '[tabs pagebuilder="widget"]' . $instance['content'] . '[/tabs]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {


        $title_repeater = isset( $instance['title_repeater'] ) && ! empty( $instance['title_repeater'] ) ? $instance['title_repeater'] : array(0=>'');
        $content_repeater = isset( $instance['content_repeater'] ) && ! empty( $instance['content_repeater'] ) ? $instance['content_repeater'] : array(0=>'');
 ?><h4 class='repeat-title'>Single Tabs</h4>
<div class='widget-repeater-container'>
<?php 
$repeat_for = (isset($instance['title_repeater']) && !empty($instance['title_repeater']) && is_array($instance['title_repeater']) ? count($instance['title_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){ ?>
<div class='single-repeater-row'>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('title_repeater_' . $repeat_i . ''), 'data_name' => 'title_repeater', 'type' => 'text', 'input_value' => (isset($title_repeater[$repeat_i]) ? $title_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Tab title', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('Tab Content', 'MergePress'); ?></p>
        </div>
</div>
<?php } } ?>
<button class='button-secondary widget-add-repeater-field' data-shortcode='tab' data-field-name='<?php echo $this->get_field_name('__NAME__'); ?>[]'>Add New Single Tab</button></div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title_repeater'] = ( !empty( $new_instance['title_repeater'] ) ) ? $new_instance['title_repeater'] : array();
        $instance['content_repeater'] = ( !empty( $new_instance['content_repeater'] ) ) ? $new_instance['content_repeater'] : array();

        return $instance;
    }
}

$tabs_widget = new tabs_widget();