<?php

class search_inventory_box_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'search_inventory_box-widget',
	'description' => '',
);

parent::__construct( 'search_inventory_box-widget', 'Search Inventory Box', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('search_inventory_box_widget');
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

        echo '<div class="search_inventory_box-widget">';


$widget_shortcode = '[search_inventory_box page_id="' . mergepress_normalize_url($instance['page_id'], 'widget')
 . '" term_form="' . implode(",", (isset($instance['term_form']) && is_array($instance['term_form']) && !empty($instance['term_form']) ? $instance['term_form'] : array()))
 . '" prefix_text="' . $instance['prefix_text']
 . '" button_text="' . $instance['button_text']
 . '"';

foreach(automotive_categories_loop_options() as $loop_option_key => $loop_option_value){
  $widget_shortcode .= ' ' . $loop_option_value['id'] . '="' . (is_array($instance[$loop_option_value['id']]) ? implode(",",$instance[$loop_option_value['id']]) : $instance[$loop_option_value['id']]) . '"';
}
$widget_shortcode .= ' pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
foreach(automotive_categories_loop_options() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ! empty( $instance[$loop_option_value['id']] ) ? $instance[$loop_option_value['id']] : (isset($loop_option_value['default']) ? $loop_option_value['default'] : '');
}
        $page_id = ! empty( $instance['page_id'] ) ? $instance['page_id'] : esc_html__( '', 'MergePress' );
        $term_form = ! empty( $instance['term_form'] ) ? $instance['term_form'] : esc_html__( '', 'MergePress' );
        $prefix_text = ! empty( $instance['prefix_text'] ) ? $instance['prefix_text'] : esc_html__( '', 'MergePress' );
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Find My New Vehicle', 'MergePress' );
 ?><?php foreach(automotive_categories_loop_options() as $loop_option_key => $loop_option_value){ ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( $loop_option_value['id'] ) ); ?>"><?php echo $loop_option_value['label']; ?></label>
            <?php echo call_user_func('mergepress_widget_' . $loop_option_value['type'] . '_field', array(
          'id'          => $this->get_field_id($loop_option_value['id']),
          'input_name'  => $this->get_field_name($loop_option_value['id']),
          'data_name'   => $loop_option_value['id'],
          'type'        => (isset($loop_option_value['type']) ? $loop_option_value['type'] : ''),
          'input_value' => (isset($instance[$loop_option_value['id']]) && !empty($instance[$loop_option_value['id']]) ? $instance[$loop_option_value['id']] : ''),
          'options'     => (isset($loop_option_value['options']) ? $loop_option_value['options'] : array()),
        )); ?>
        <p class='widget-help-description'><?php echo (isset($loop_option_value['desc']) ? $loop_option_value['desc'] : ''); ?></p>
        </div>
<?php } ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'page_id' ) ); ?>"><?php echo esc_html__( 'Form action:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_url_field(array('id' => $this->get_field_id('page_id'), 'input_name' => $this->get_field_name('page_id'), 'data_name' => 'page_id', 'input_value' => $page_id, 'old_input_value' => '')); ?>        <p class='widget-help-description'><?php echo __('Choose the Inventory page which you want to display the search results', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'term_form' ) ); ?>"><?php echo esc_html__( 'Listing Category Term:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('term_form'), 'is_multi' => false, 'input_name' => $this->get_field_name('term_form'), 'data_name' => 'term_form', 'options' => array (
  'singular' => 'Singular',
  'plural' => 'Plural',
), 'input_value' => $term_form) ); ?>        <p class='widget-help-description'><?php echo __('Adjust whether the listing dropdown uses the singular form or plural form of the listing category.', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'prefix_text' ) ); ?>"><?php echo esc_html__( 'Prefix text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('prefix_text'), 'input_name' => $this->get_field_name('prefix_text'), 'data_name' => 'prefix_text', 'type' => 'text', 'input_value' => $prefix_text) ); ?>
        <p class='widget-help-description'><?php echo __('Adjust the text before the listing category in the dropdown', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php echo esc_html__( 'Button Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('button_text'), 'input_name' => $this->get_field_name('button_text'), 'data_name' => 'button_text', 'type' => 'text', 'input_value' => $button_text) ); ?>
        <p class='widget-help-description'><?php echo __('Adjust the button text to submit the form', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

foreach(automotive_categories_loop_options() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ( !empty( $new_instance[$loop_option_value['id']] ) ) ? $new_instance[$loop_option_value['id']] : '';
}        $instance['page_id'] = ( !empty( $new_instance['page_id'] ) ) ? $new_instance['page_id'] : '';
        $instance['term_form'] = ( !empty( $new_instance['term_form'] ) ) ? $new_instance['term_form'] : '';
        $instance['prefix_text'] = ( !empty( $new_instance['prefix_text'] ) ) ? $new_instance['prefix_text'] : '';
        $instance['button_text'] = ( !empty( $new_instance['button_text'] ) ) ? $new_instance['button_text'] : '';

        return $instance;
    }
}

$search_inventory_box_widget = new search_inventory_box_widget();
