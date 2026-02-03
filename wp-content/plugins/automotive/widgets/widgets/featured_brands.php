<?php

class featured_brands_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'featured_brands-widget',
	'description' => '',
);

parent::__construct( 'featured_brands-widget', 'Featured Brands', $widget_ops );

    add_action('admin_footer', function(){
    $repeat_i = '{{ data.newID }}'; ?>
     <script type="text/html" id="tmpl-repeater-brand_logo">        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'img_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('img_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('img_repeater_' . $repeat_i . ''), 'data_name' => 'img_repeater', 'input_value' => (isset($img_repeater[$repeat_i]) ? $img_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('The image shown on load', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'hoverimg_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Hover Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('hoverimg_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('hoverimg_repeater_' . $repeat_i . ''), 'data_name' => 'hoverimg_repeater', 'input_value' => (isset($hoverimg_repeater[$repeat_i]) ? $hoverimg_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('Image shown when hovering over the brand', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('link_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('link_repeater_' . $repeat_i . ''), 'data_name' => 'link_repeater', 'input_value' => (isset($link_repeater[$repeat_i]) ? $link_repeater[$repeat_i] : ''), 'old_input_value' => (isset($link) ? $link : ''))); ?>        <p class='widget-help-description'><?php echo __('Link brand to URL', 'MergePress'); ?></p>
        </div>
</script><?php     });
    add_action('widgets_init', function(){
      register_widget('featured_brands_widget');
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

        echo '<div class="featured_brands-widget">';

$instance['content'] = '';

$repeat_for = (isset($instance['img_repeater']) && !empty($instance['img_repeater']) && is_array($instance['img_repeater']) ? count($instance['img_repeater']) : 1);
if($repeat_for){
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){
  $instance['content'] .= '[brand_logo img="' . mergepress_normalize_image($instance['img_repeater'][$repeat_i], 'widget')
 . '" hoverimg="' . mergepress_normalize_image($instance['hoverimg_repeater'][$repeat_i], 'widget')
 . '" link="' . mergepress_normalize_url($instance['link_repeater'][$repeat_i], 'widget')
 . '" pagebuilder="widget"]' . $instance['content_repeater'][$repeat_i] . '[/brand_logo]';
} }

$instance['content'] = do_shortcode($instance['content']);


$widget_shortcode = '[featured_brands pagebuilder="widget"]' . $instance['content'] . '[/featured_brands]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {


        $img_repeater = isset( $instance['img_repeater'] ) && ! empty( $instance['img_repeater'] ) ? $instance['img_repeater'] : array(0=>'');
        $hoverimg_repeater = isset( $instance['hoverimg_repeater'] ) && ! empty( $instance['hoverimg_repeater'] ) ? $instance['hoverimg_repeater'] : array(0=>'');
        $link_repeater = isset( $instance['link_repeater'] ) && ! empty( $instance['link_repeater'] ) ? $instance['link_repeater'] : array(0=>'');
 ?><h4 class='repeat-title'>Brand Items</h4>
<div class='widget-repeater-container'>
<?php
$repeat_for = (isset($instance['img_repeater']) && !empty($instance['img_repeater']) && is_array($instance['img_repeater']) ? count($instance['img_repeater']) : 1);
if($repeat_for){
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){ ?>
<div class='single-repeater-row'>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'img_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('img_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('img_repeater_' . $repeat_i . ''), 'data_name' => 'img_repeater', 'input_value' => (isset($img_repeater[$repeat_i]) ? $img_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('The image shown on load', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'hoverimg_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Hover Image:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_image_field(array('id' => $this->get_field_id('hoverimg_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('hoverimg_repeater_' . $repeat_i . ''), 'data_name' => 'hoverimg_repeater', 'input_value' => (isset($hoverimg_repeater[$repeat_i]) ? $hoverimg_repeater[$repeat_i] : ''))); ?>
        <p class='widget-help-description'><?php echo __('Image shown when hovering over the brand', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('link_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('link_repeater_' . $repeat_i . ''), 'data_name' => 'link_repeater', 'input_value' => (isset($link_repeater[$repeat_i]) ? $link_repeater[$repeat_i] : ''), 'old_input_value' => (isset($link) ? $link : ''))); ?>        <p class='widget-help-description'><?php echo __('Link brand to URL', 'MergePress'); ?></p>
        </div>
</div>
<?php } } ?>
<button class='button-secondary widget-add-repeater-field' data-shortcode='brand_logo' data-field-name='<?php echo $this->get_field_name('__NAME__'); ?>[]'>Add New Brand Item</button></div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['img_repeater'] = ( !empty( $new_instance['img_repeater'] ) ) ? $new_instance['img_repeater'] : array();
        $instance['hoverimg_repeater'] = ( !empty( $new_instance['hoverimg_repeater'] ) ) ? $new_instance['hoverimg_repeater'] : array();
        $instance['link_repeater'] = ( !empty( $new_instance['link_repeater'] ) ) ? $new_instance['link_repeater'] : array();

        return $instance;
    }
}

$featured_brands_widget = new featured_brands_widget();
