<?php

class portfolio_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'portfolio-widget',
	'description' => '',
);

parent::__construct( 'portfolio-widget', 'Portfolio', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('portfolio_widget');
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

        echo '<div class="portfolio-widget">';


$widget_shortcode = '[portfolio type="' . implode(",", (isset($instance['type']) && is_array($instance['type']) && !empty($instance['type']) ? $instance['type'] : array()))
 . '" all_category="' . implode(",", (isset($instance['all_category']) && is_array($instance['all_category']) && !empty($instance['all_category']) ? $instance['all_category'] : array()))
 . '" sort_element="' . implode(",", (isset($instance['sort_element']) && is_array($instance['sort_element']) && !empty($instance['sort_element']) ? $instance['sort_element'] : array()))
 . '" sort_text="' . $instance['sort_text']
 . '" columns="' . implode(",", (isset($instance['columns']) && is_array($instance['columns']) && !empty($instance['columns']) ? $instance['columns'] : array()))
 . '" order_by="' . implode(",", (isset($instance['order_by']) && is_array($instance['order_by']) && !empty($instance['order_by']) ? $instance['order_by'] : array()))
 . '"';

foreach(automotive_projects_loop_option() as $loop_option_key => $loop_option_value){
$widget_shortcode .= ' ' . $loop_option_value['id'] . '="' . $instance[$loop_option_value['id']] . '"';
}
foreach(automotive_portfolios_loop_option() as $loop_option_key => $loop_option_value){
$widget_shortcode .= ' ' . $loop_option_value['id'] . '="' . $instance[$loop_option_value['id']] . '"';
}
$widget_shortcode .= ' pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
foreach(automotive_projects_loop_option() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ! empty( $instance[$loop_option_value['id']] ) ? $instance[$loop_option_value['id']] : (isset($loop_option_value['default']) ? $loop_option_value['default'] : '');
}
        $type = ! empty( $instance['type'] ) ? $instance['type'] : esc_html__( '', 'MergePress' );
        $all_category = ! empty( $instance['all_category'] ) ? $instance['all_category'] : esc_html__( '', 'MergePress' );
foreach(automotive_portfolios_loop_option() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ! empty( $instance[$loop_option_value['id']] ) ? $instance[$loop_option_value['id']] : (isset($loop_option_value['default']) ? $loop_option_value['default'] : '');
}
        $sort_element = ! empty( $instance['sort_element'] ) ? $instance['sort_element'] : esc_html__( '', 'MergePress' );
        $sort_text = ! empty( $instance['sort_text'] ) ? $instance['sort_text'] : esc_html__( '', 'MergePress' );
        $columns = ! empty( $instance['columns'] ) ? $instance['columns'] : esc_html__( '', 'MergePress' );
        $order_by = ! empty( $instance['order_by'] ) ? $instance['order_by'] : esc_html__( '', 'MergePress' );
 ?><?php foreach(automotive_projects_loop_option() as $loop_option_key => $loop_option_value){ ?>        <div class='input-row'>
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
        <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php echo esc_html__( 'Type:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('type'), 'is_multi' => false, 'input_name' => $this->get_field_name('type'), 'data_name' => 'type', 'options' => array (
  'details' => 'Details',
  'classic' => 'Classic',
), 'input_value' => $type) ); ?>        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'all_category' ) ); ?>"><?php echo esc_html__( 'All Category:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('all_category'), 'is_multi' => false, 'input_name' => $this->get_field_name('all_category'), 'data_name' => 'all_category', 'options' => array (
  'yes' => 'Yes',
  'no' => 'No',
), 'input_value' => $all_category) ); ?>        <p class='widget-help-description'><?php echo __('Display the all category', 'MergePress'); ?></p>
        </div>
<?php foreach(automotive_portfolios_loop_option() as $loop_option_key => $loop_option_value){ ?>        <div class='input-row'>
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
        <label for="<?php echo esc_attr( $this->get_field_id( 'sort_element' ) ); ?>"><?php echo esc_html__( 'Sort by Element:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('sort_element'), 'is_multi' => false, 'input_name' => $this->get_field_name('sort_element'), 'data_name' => 'sort_element', 'options' => array (
  'yes' => 'Yes',
  'no' => 'No',
), 'input_value' => $sort_element) ); ?>        <p class='widget-help-description'><?php echo __('Display the sort by element', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'sort_text' ) ); ?>"><?php echo esc_html__( 'Sort Text:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('sort_text'), 'input_name' => $this->get_field_name('sort_text'), 'data_name' => 'sort_text', 'type' => 'text', 'input_value' => $sort_text) ); ?>
        <p class='widget-help-description'><?php echo __('Change the text beside the categories', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php echo esc_html__( 'Columns:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('columns'), 'is_multi' => false, 'input_name' => $this->get_field_name('columns'), 'data_name' => 'columns', 'options' => array (
  1 => 0,
  2 => 1,
  3 => 2,
  4 => 3,
), 'input_value' => $columns) ); ?>        <p class='widget-help-description'><?php echo __('Change the text beside the categories', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>"><?php echo esc_html__( 'Order By:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('order_by'), 'is_multi' => false, 'input_name' => $this->get_field_name('order_by'), 'data_name' => 'order_by', 'options' => array (
  'ASC' => 'Ascending',
  'DESC' => 'Descending',
), 'input_value' => $order_by) ); ?>        <p class='widget-help-description'><?php echo __('Change the order of the portfolio items', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

foreach(automotive_projects_loop_option() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ( !empty( $new_instance[$loop_option_value['id']] ) ) ? $new_instance[$loop_option_value['id']] : '';
}        $instance['type'] = ( !empty( $new_instance['type'] ) ) ? $new_instance['type'] : '';
        $instance['all_category'] = ( !empty( $new_instance['all_category'] ) ) ? $new_instance['all_category'] : '';
foreach(automotive_portfolios_loop_option() as $loop_option_key => $loop_option_value){
$instance[$loop_option_value['id']] = ( !empty( $new_instance[$loop_option_value['id']] ) ) ? $new_instance[$loop_option_value['id']] : '';
}        $instance['sort_element'] = ( !empty( $new_instance['sort_element'] ) ) ? $new_instance['sort_element'] : '';
        $instance['sort_text'] = ( !empty( $new_instance['sort_text'] ) ) ? $new_instance['sort_text'] : '';
        $instance['columns'] = ( !empty( $new_instance['columns'] ) ) ? $new_instance['columns'] : '';
        $instance['order_by'] = ( !empty( $new_instance['order_by'] ) ) ? $new_instance['order_by'] : '';

        return $instance;
    }
}

$portfolio_widget = new portfolio_widget();