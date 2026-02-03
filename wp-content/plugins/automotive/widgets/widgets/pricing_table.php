<?php

class pricing_table_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'pricing_table-widget',
	'description' => '',
);

parent::__construct( 'pricing_table-widget', 'Pricing Table', $widget_ops );

    add_action('admin_footer', function(){
    $repeat_i = '{{ data.newID }}'; ?>
     <script type="text/html" id="tmpl-repeater-pricing_option">        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Option Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater', 'type' => 'text', 'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('An option this pricing table includes', 'MergePress'); ?></p>
        </div>
</script><?php     });
    add_action('widgets_init', function(){
      register_widget('pricing_table_widget');
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

        echo '<div class="pricing_table-widget">';

$instance['content'] = '';

$repeat_for = (isset($instance['content_repeater']) && !empty($instance['content_repeater']) && is_array($instance['content_repeater']) ? count($instance['content_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){
  $instance['content'] .= '[pricing_option pagebuilder="widget"]' . $instance['content_repeater'][$repeat_i] . '[/pricing_option]';
} }

$instance['content'] = do_shortcode($instance['content']);


$widget_shortcode = '[pricing_table title="' . $instance['title']
 . '" header_color="' . $instance['header_color']
 . '" price="' . $instance['price']
 . '" often="' . $instance['often']
 . '" button="' . $instance['button']
 . '" link="' . mergepress_normalize_url($instance['link'], 'widget')
 . '" pagebuilder="widget"]' . $instance['content'] . '[/pricing_table]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'MergePress' );
        $header_color = ! empty( $instance['header_color'] ) ? $instance['header_color'] : esc_html__( '', 'MergePress' );
        $price = ! empty( $instance['price'] ) ? $instance['price'] : esc_html__( '', 'MergePress' );
        $often = ! empty( $instance['often'] ) ? $instance['often'] : esc_html__( 'mo', 'MergePress' );
        $button = ! empty( $instance['button'] ) ? $instance['button'] : esc_html__( 'Sign Up Now', 'MergePress' );
        $link = ! empty( $instance['link'] ) ? $instance['link'] : esc_html__( '', 'MergePress' );


        $content_repeater = isset( $instance['content_repeater'] ) && ! empty( $instance['content_repeater'] ) ? $instance['content_repeater'] : array(0=>'');
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('title'), 'input_name' => $this->get_field_name('title'), 'data_name' => 'title', 'type' => 'text', 'input_value' => $title) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'header_color' ) ); ?>"><?php echo esc_html__( 'Header Color:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_color_field(array('id' => $this->get_field_id('header_color'), 'input_name' => $this->get_field_name('header_color'), 'data_name' => 'header_color',  'input_value' => $header_color) ); ?>
        <p class='widget-help-description'><?php echo __('Customize the color of the pricing header', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'price' ) ); ?>"><?php echo esc_html__( 'Price:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('price'), 'input_name' => $this->get_field_name('price'), 'data_name' => 'price', 'type' => 'text', 'input_value' => $price) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'often' ) ); ?>"><?php echo esc_html__( 'Often:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('often'), 'input_name' => $this->get_field_name('often'), 'data_name' => 'often', 'type' => 'text', 'input_value' => $often) ); ?>
        <p class='widget-help-description'><?php echo __('How often the payment is made', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'button' ) ); ?>"><?php echo esc_html__( 'Button Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('button'), 'input_name' => $this->get_field_name('button'), 'data_name' => 'button', 'type' => 'text', 'input_value' => $button) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php echo esc_html__( 'Link:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('link'), 'input_name' => $this->get_field_name('link'), 'data_name' => 'link', 'input_value' => $link, 'old_input_value' => '')); ?>        <p class='widget-help-description'><?php echo __('Link brand to URL', 'MergePress'); ?></p>
        </div>
<h4 class='repeat-title'>Pricing Options</h4>
<div class='widget-repeater-container'>
<?php 
$repeat_for = (isset($instance['content_repeater']) && !empty($instance['content_repeater']) && is_array($instance['content_repeater']) ? count($instance['content_repeater']) : 1);
if($repeat_for){ 
for($repeat_i = 0; $repeat_i <= $repeat_for-1; $repeat_i++){ ?>
<div class='single-repeater-row'>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content_repeater_' . $repeat_i . '' ) ); ?>"><?php echo esc_html__( 'Option Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('content_repeater_' . $repeat_i . ''), 'input_name' => $this->get_field_name('content_repeater_' . $repeat_i . ''), 'data_name' => 'content_repeater', 'type' => 'text', 'input_value' => (isset($content_repeater[$repeat_i]) ? $content_repeater[$repeat_i] : '')) ); ?>
        <p class='widget-help-description'><?php echo __('An option this pricing table includes', 'MergePress'); ?></p>
        </div>
</div>
<?php } } ?>
<button class='button-secondary widget-add-repeater-field' data-shortcode='pricing_option' data-field-name='<?php echo $this->get_field_name('__NAME__'); ?>[]'>Add New Pricing Option</button></div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['header_color'] = ( !empty( $new_instance['header_color'] ) ) ? $new_instance['header_color'] : '';
        $instance['price'] = ( !empty( $new_instance['price'] ) ) ? $new_instance['price'] : '';
        $instance['often'] = ( !empty( $new_instance['often'] ) ) ? $new_instance['often'] : '';
        $instance['button'] = ( !empty( $new_instance['button'] ) ) ? $new_instance['button'] : '';
        $instance['link'] = ( !empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';
        $instance['content_repeater'] = ( !empty( $new_instance['content_repeater'] ) ) ? $new_instance['content_repeater'] : array();

        return $instance;
    }
}

$pricing_table_widget = new pricing_table_widget();