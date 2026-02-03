<?php

class automotive_list_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'list-widget',
	'description' => '',
);

parent::__construct( 'list-widget', 'List', $widget_ops );

    add_action('admin_footer', function(){
    $repeat_i = '{{ data.newID }}'; ?>
     <script type="text/html" id="tmpl-repeater-list_item">        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Icon:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('icon_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('icon_repeater_' . $repeat_i . ''), 'data_name' => 'icon_repeater', 'type' => 'text', 'input_value' => (isset($icon_repeater[$repeat_i]) ? $icon_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('List item content', 'MergePress'); ?></p>
        </div>
</script><?php     });
    add_action('widgets_init', function(){
      register_widget('automotive_list_widget');
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

        echo '<div class="list-widget">';

$instance['content'] = '';

$repeat_for = (isset($instance['icon_repeater']) && !empty($instance['icon_repeater']) && is_array($instance['icon_repeater']) ? count($instance['icon_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){
  $instance['content'] .= '[list_item icon="' . $instance['icon_repeater'][$repeat_i]
 . '" pagebuilder="widget"]' . $instance['content_repeater'][$repeat_i] . '[/list_item]';
} }

$instance['content'] = do_shortcode($instance['content']);


$widget_shortcode = '[list style="' . implode(",", (isset($instance['style']) && is_array($instance['style']) && !empty($instance['style']) ? $instance['style'] : array()))
 . '" pagebuilder="widget"]' . $instance['content'] . '[/list]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $style = ! empty( $instance['style'] ) ? $instance['style'] : esc_html__( '', 'MergePress' );


        $icon_repeater = isset( $instance['icon_repeater'] ) && ! empty( $instance['icon_repeater'] ) ? $instance['icon_repeater'] : array(0=>'');
        $content_repeater = isset( $instance['content_repeater'] ) && ! empty( $instance['content_repeater'] ) ? $instance['content_repeater'] : array(0=>'');
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php echo esc_html__( 'Style:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('style'), 'is_multi' => false, 'input_name' => $this->get_field_name('style'), 'data_name' => 'style', 'options' => array (
  'arrows' => 'Arrows',
  'checkboxes' => 'Checkboxes',
), 'input_value' => $style) ); ?>        <p class='widget-help-description'><?php echo __('Style of dropdown', 'MergePress'); ?></p>
        </div>
<h4 class='repeat-title'>List Items</h4>
<div class='widget-repeater-container'>
<?php 
$repeat_for = (isset($instance['icon_repeater']) && !empty($instance['icon_repeater']) && is_array($instance['icon_repeater']) ? count($instance['icon_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){ ?>
<div class='single-repeater-row'>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Icon:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('icon_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('icon_repeater_' . $repeat_i . ''), 'data_name' => 'icon_repeater', 'type' => 'text', 'input_value' => (isset($icon_repeater[$repeat_i]) ? $icon_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Icon to display above the number. <a href=\"https://fontawesome.com/v4.7.0/icons/\" target=\"_blank\">List of Icons</a>', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('List item content', 'MergePress'); ?></p>
        </div>
</div>
<?php } } ?>
<button class='button-secondary widget-add-repeater-field' data-shortcode='list_item' data-field-name='<?php echo $this->get_field_name('__NAME__'); ?>[]'>Add New List Item</button></div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['style'] = ( !empty( $new_instance['style'] ) ) ? $new_instance['style'] : '';
        $instance['icon_repeater'] = ( !empty( $new_instance['icon_repeater'] ) ) ? $new_instance['icon_repeater'] : array();
        $instance['content_repeater'] = ( !empty( $new_instance['content_repeater'] ) ) ? $new_instance['content_repeater'] : array();

        return $instance;
    }
}

$list_widget = new automotive_list_widget();