<?php

class auto_contact_information_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'auto_contact_information-widget',
	'description' => '',
);

parent::__construct( 'auto_contact_information-widget', 'Contact Information', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('auto_contact_information_widget');
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

        echo '<div class="auto_contact_information-widget">';


$widget_shortcode = '[auto_contact_information company="' . $instance['company']
 . '" address="' . $instance['address']
 . '" phone="' . $instance['phone']
 . '" fax="' . $instance['fax']
 . '" email="' . $instance['email']
 . '" website="' . $instance['website']
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $company = ! empty( $instance['company'] ) ? $instance['company'] : esc_html__( '', 'MergePress' );
        $address = ! empty( $instance['address'] ) ? $instance['address'] : esc_html__( '', 'MergePress' );
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : esc_html__( '', 'MergePress' );
        $fax = ! empty( $instance['fax'] ) ? $instance['fax'] : esc_html__( '', 'MergePress' );
        $email = ! empty( $instance['email'] ) ? $instance['email'] : esc_html__( '', 'MergePress' );
        $website = ! empty( $instance['website'] ) ? $instance['website'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'company' ) ); ?>"><?php echo esc_html__( 'Company Name:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('company'), 'input_name' => $this->get_field_name('company'), 'data_name' => 'company', 'type' => 'text', 'input_value' => $company) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php echo esc_html__( 'Address:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('address'), 'input_name' => $this->get_field_name('address'), 'data_name' => 'address', 'type' => 'text', 'input_value' => $address) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php echo esc_html__( 'Phone:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('phone'), 'input_name' => $this->get_field_name('phone'), 'data_name' => 'phone', 'type' => 'text', 'input_value' => $phone) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>"><?php echo esc_html__( 'Fax:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('fax'), 'input_name' => $this->get_field_name('fax'), 'data_name' => 'fax', 'type' => 'text', 'input_value' => $fax) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php echo esc_html__( 'Email:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('email'), 'input_name' => $this->get_field_name('email'), 'data_name' => 'email', 'type' => 'text', 'input_value' => $email) ); ?>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'website' ) ); ?>"><?php echo esc_html__( 'Website:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('website'), 'input_name' => $this->get_field_name('website'), 'data_name' => 'website', 'type' => 'text', 'input_value' => $website) ); ?>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['company'] = ( !empty( $new_instance['company'] ) ) ? $new_instance['company'] : '';
        $instance['address'] = ( !empty( $new_instance['address'] ) ) ? $new_instance['address'] : '';
        $instance['phone'] = ( !empty( $new_instance['phone'] ) ) ? $new_instance['phone'] : '';
        $instance['fax'] = ( !empty( $new_instance['fax'] ) ) ? $new_instance['fax'] : '';
        $instance['email'] = ( !empty( $new_instance['email'] ) ) ? $new_instance['email'] : '';
        $instance['website'] = ( !empty( $new_instance['website'] ) ) ? $new_instance['website'] : '';

        return $instance;
    }
}

$auto_contact_information_widget = new auto_contact_information_widget();