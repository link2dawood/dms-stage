<?php

class faq_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'faq-widget',
	'description' => '',
);

parent::__construct( 'faq-widget', 'FAQ', $widget_ops );

    add_action('admin_footer', function(){
    $repeat_i = '{{ data.newID }}'; ?>
     <script type="text/html" id="tmpl-repeater-toggle">        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('title_repeater_' . $repeat_i . ''), 'data_name' => 'title_repeater', 'type' => 'text', 'input_value' => (isset($title_repeater[$repeat_i]) ? $title_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Title of FAQ item', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'categories_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Categories:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('categories_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('categories_repeater_' . $repeat_i . ''), 'data_name' => 'categories_repeater', 'type' => 'text', 'input_value' => (isset($categories_repeater[$repeat_i]) ? $categories_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Comma seperated list of categories item is in', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'FAQ Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'state_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'State of the item:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('state_repeater_' . $repeat_i . ''), 'is_multi' => false, 'input_name' => $this->get_field_name('state_repeater_' . $repeat_i . ''), 'data_name' => 'state_repeater', 'options' => array (
  'collapsed' => 'Closed',
  'in' => 'Open',
), 'input_value' => (isset($state_repeater[$repeat_i]) ? $state_repeater[$repeat_i] : '')) ); ?>        <p class='widget-help-description'><?php echo __('Choose whether the item is open or close', 'MergePress'); ?></p>
        </div>
</script><?php     });
    add_action('widgets_init', function(){
      register_widget('faq_widget');
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

        echo '<div class="faq-widget">';

$instance['content'] = '';

$repeat_for = (isset($instance['title_repeater']) && !empty($instance['title_repeater']) && is_array($instance['title_repeater']) ? count($instance['title_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){
  $instance['content'] .= '[toggle title="' . $instance['title_repeater'][$repeat_i]
 . '" categories="' . $instance['categories_repeater'][$repeat_i]
 . '" state="' . implode(",", (isset($instance['state_repeater'][$repeat_i]) && is_array($instance['state_repeater'][$repeat_i]) && !empty($instance['state_repeater'][$repeat_i]) ? $instance['state_repeater'][$repeat_i] : array()))
 . '" pagebuilder="widget"]' . $instance['content_repeater'][$repeat_i] . '[/toggle]';
} }

$instance['content'] = do_shortcode($instance['content']);


$widget_shortcode = '[faq categories="' . $instance['categories']
 . '" sort_element="' . implode(",", (isset($instance['sort_element']) && is_array($instance['sort_element']) && !empty($instance['sort_element']) ? $instance['sort_element'] : array()))
 . '" sort_text="' . $instance['sort_text']
 . '" pagebuilder="widget"]' . $instance['content'] . '[/faq]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $categories = ! empty( $instance['categories'] ) ? $instance['categories'] : esc_html__( 'category1, category2, etc', 'MergePress' );
        $sort_element = ! empty( $instance['sort_element'] ) ? $instance['sort_element'] : esc_html__( '', 'MergePress' );
        $sort_text = ! empty( $instance['sort_text'] ) ? $instance['sort_text'] : esc_html__( 'Sort FAQ by:', 'MergePress' );


        $title_repeater = isset( $instance['title_repeater'] ) && ! empty( $instance['title_repeater'] ) ? $instance['title_repeater'] : array(0=>'');
        $categories_repeater = isset( $instance['categories_repeater'] ) && ! empty( $instance['categories_repeater'] ) ? $instance['categories_repeater'] : array(0=>'');
        $content_repeater = isset( $instance['content_repeater'] ) && ! empty( $instance['content_repeater'] ) ? $instance['content_repeater'] : array(0=>'');
        $state_repeater = isset( $instance['state_repeater'] ) && ! empty( $instance['state_repeater'] ) ? $instance['state_repeater'] : array(0=>'');
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'categories' ) ); ?>"><?php echo esc_html__( 'Categories:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('categories'), 'input_name' => $this->get_field_name('categories'), 'data_name' => 'categories', 'type' => 'text', 'input_value' => $categories) ); ?>
        <p class='widget-help-description'><?php echo __('Comma separated list of categories', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'sort_element' ) ); ?>"><?php echo esc_html__( 'Sort By Element:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('sort_element'), 'is_multi' => false, 'input_name' => $this->get_field_name('sort_element'), 'data_name' => 'sort_element', 'options' => array (
  'yes' => 'Yes',
  'no' => 'No',
), 'input_value' => $sort_element) ); ?>        <p class='widget-help-description'><?php echo __('Display the sort by element', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'sort_text' ) ); ?>"><?php echo esc_html__( 'Sort Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('sort_text'), 'input_name' => $this->get_field_name('sort_text'), 'data_name' => 'sort_text', 'type' => 'text', 'input_value' => $sort_text) ); ?>
        <p class='widget-help-description'><?php echo __('This text is displayed beside the categories', 'MergePress'); ?></p>
        </div>
<h4 class='repeat-title'>FAQ Items</h4>
<div class='widget-repeater-container'>
<?php 
$repeat_for = (isset($instance['title_repeater']) && !empty($instance['title_repeater']) && is_array($instance['title_repeater']) ? count($instance['title_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){ ?>
<div class='single-repeater-row'>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('title_repeater_' . $repeat_i . ''), 'data_name' => 'title_repeater', 'type' => 'text', 'input_value' => (isset($title_repeater[$repeat_i]) ? $title_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Title of FAQ item', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'categories_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Categories:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('categories_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('categories_repeater_' . $repeat_i . ''), 'data_name' => 'categories_repeater', 'type' => 'text', 'input_value' => (isset($categories_repeater[$repeat_i]) ? $categories_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('Comma seperated list of categories item is in', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'FAQ Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_wysiwyg_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater',  'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : ''))); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'state_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'State of the item:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('state_repeater_' . $repeat_i . ''), 'is_multi' => false, 'input_name' => $this->get_field_name('state_repeater_' . $repeat_i . ''), 'data_name' => 'state_repeater', 'options' => array (
  'collapsed' => 'Closed',
  'in' => 'Open',
), 'input_value' => (isset($state_repeater[$repeat_i]) ? $state_repeater[$repeat_i] : '')) ); ?>        <p class='widget-help-description'><?php echo __('Choose whether the item is open or close', 'MergePress'); ?></p>
        </div>
</div>
<?php } } ?>
<button class='button-secondary widget-add-repeater-field' data-shortcode='toggle' data-field-name='<?php echo $this->get_field_name('__NAME__'); ?>[]'>Add New FAQ Item</button></div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['categories'] = ( !empty( $new_instance['categories'] ) ) ? $new_instance['categories'] : '';
        $instance['sort_element'] = ( !empty( $new_instance['sort_element'] ) ) ? $new_instance['sort_element'] : '';
        $instance['sort_text'] = ( !empty( $new_instance['sort_text'] ) ) ? $new_instance['sort_text'] : '';
        $instance['title_repeater'] = ( !empty( $new_instance['title_repeater'] ) ) ? $new_instance['title_repeater'] : array();
        $instance['categories_repeater'] = ( !empty( $new_instance['categories_repeater'] ) ) ? $new_instance['categories_repeater'] : array();
        $instance['content_repeater'] = ( !empty( $new_instance['content_repeater'] ) ) ? $new_instance['content_repeater'] : array();
        $instance['state_repeater'] = ( !empty( $new_instance['state_repeater'] ) ) ? $new_instance['state_repeater'] : array();

        return $instance;
    }
}

$faq_widget = new faq_widget();