<?php

class auto_google_map_widget extends WP_Widget {

  function __construct() {
$widget_ops = array(
	'classname' => 'auto_google_map-widget',
	'description' => '',
);

parent::__construct( 'auto_google_map-widget', 'Google Map', $widget_ops );

    add_action('widgets_init', function(){
      register_widget('auto_google_map_widget');
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

        echo '<div class="auto_google_map-widget">';


$widget_shortcode = '[auto_google_map latitude="' . $instance['latitude']
 . '" longitude="' . $instance['longitude']
 . '" zoom="' . $instance['zoom']
 . '" height="' . $instance['height']
 . '" map_type="' . implode(",", (isset($instance['map_type']) && is_array($instance['map_type']) && !empty($instance['map_type']) ? $instance['map_type'] : array()))
 . '" scrolling="' . implode(",", (isset($instance['scrolling']) && is_array($instance['scrolling']) && !empty($instance['scrolling']) ? $instance['scrolling'] : array()))
 . '" map_style="' . $instance['map_style']
 . '" info_window_content="' . $instance['info_window_content']
 . '" directions_button="' . implode(",", (isset($instance['directions_button']) && is_array($instance['directions_button']) && !empty($instance['directions_button']) ? $instance['directions_button'] : array()))
 . '" parallax_disabled="' . implode(",", (isset($instance['parallax_disabled']) && is_array($instance['parallax_disabled']) && !empty($instance['parallax_disabled']) ? $instance['parallax_disabled'] : array()))
 . '" scrolling_disabled="' . implode(",", (isset($instance['scrolling_disabled']) && is_array($instance['scrolling_disabled']) && !empty($instance['scrolling_disabled']) ? $instance['scrolling_disabled'] : array()))
 . '" pagebuilder="widget"]';
        echo do_shortcode($widget_shortcode);

        echo '</div>';
        echo $args['after_widget'];
    }


    public function form( $instance ) {
        $latitude = ! empty( $instance['latitude'] ) ? $instance['latitude'] : esc_html__( '', 'MergePress' );
        $longitude = ! empty( $instance['longitude'] ) ? $instance['longitude'] : esc_html__( '', 'MergePress' );
        $zoom = ! empty( $instance['zoom'] ) ? $instance['zoom'] : esc_html__( '', 'MergePress' );
        $height = ! empty( $instance['height'] ) ? $instance['height'] : esc_html__( '', 'MergePress' );
        $map_type = ! empty( $instance['map_type'] ) ? $instance['map_type'] : esc_html__( '', 'MergePress' );
        $scrolling = ! empty( $instance['scrolling'] ) ? $instance['scrolling'] : esc_html__( '', 'MergePress' );
        $map_style = ! empty( $instance['map_style'] ) ? $instance['map_style'] : esc_html__( '', 'MergePress' );
        $info_window_content = ! empty( $instance['info_window_content'] ) ? $instance['info_window_content'] : esc_html__( '', 'MergePress' );
        $directions_button = ! empty( $instance['directions_button'] ) ? $instance['directions_button'] : esc_html__( '', 'MergePress' );
        $parallax_disabled = ! empty( $instance['parallax_disabled'] ) ? $instance['parallax_disabled'] : esc_html__( '', 'MergePress' );
        $scrolling_disabled = ! empty( $instance['scrolling_disabled'] ) ? $instance['scrolling_disabled'] : esc_html__( '', 'MergePress' );
 ?>        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>"><?php echo esc_html__( 'Latitude:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('latitude'), 'input_name' => $this->get_field_name('latitude'), 'data_name' => 'latitude', 'type' => 'text', 'input_value' => $latitude) ); ?>
        <p class='widget-help-description'><?php echo __('Latitude of google map', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>"><?php echo esc_html__( 'Longitude:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('longitude'), 'input_name' => $this->get_field_name('longitude'), 'data_name' => 'longitude', 'type' => 'text', 'input_value' => $longitude) ); ?>
        <p class='widget-help-description'><?php echo __('Longitude of google map', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'zoom' ) ); ?>"><?php echo esc_html__( 'Zoom:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('zoom'), 'input_name' => $this->get_field_name('zoom'), 'data_name' => 'zoom', 'type' => 'text', 'input_value' => $zoom) ); ?>
        <p class='widget-help-description'><?php echo __('Zoom of google map', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php echo esc_html__( 'Height:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_text_field(array('id' => $this->get_field_id('height'), 'input_name' => $this->get_field_name('height'), 'data_name' => 'height', 'type' => 'text', 'input_value' => $height) ); ?>
        <p class='widget-help-description'><?php echo __('Height of google map', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'map_type' ) ); ?>"><?php echo esc_html__( 'Type:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('map_type'), 'is_multi' => false, 'input_name' => $this->get_field_name('map_type'), 'data_name' => 'map_type', 'options' => array (
  'roadmap' => 'Roadmap',
  'satellite' => 'Satellite',
  'hybrid' => 'Hybrid',
  'terrain' => 'Terrain',
), 'input_value' => $map_type) ); ?>        <p class='widget-help-description'><?php echo __('Change the style of the Google Map', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'scrolling' ) ); ?>"><?php echo esc_html__( 'Scrolling to zoom:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('scrolling'), 'is_multi' => false, 'input_name' => $this->get_field_name('scrolling'), 'data_name' => 'scrolling', 'options' => array (
  'true' => 'On',
  'false' => 'Off',
), 'input_value' => $scrolling) ); ?>        <p class='widget-help-description'><?php echo __('Turn off the scrolling to zoom function on google map, useful for fullscreen maps', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'map_style' ) ); ?>"><?php echo esc_html__( 'Style:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_html_field(array('id' => $this->get_field_id('map_style'), 'input_name' => $this->get_field_name('map_style'), 'data_name' => 'map_style', 'input_value' => $map_style)); ?>
        <p class='widget-help-description'><?php echo __('Style of google map, styles availiable at: <a href=\'http://snazzymaps.com/\' target=\'_blank\'>http://snazzymaps.com/</a>', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'info_window_content' ) ); ?>"><?php echo esc_html__( 'Info Window Content:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_html_field(array('id' => $this->get_field_id('info_window_content'), 'input_name' => $this->get_field_name('info_window_content'), 'data_name' => 'info_window_content', 'input_value' => $info_window_content)); ?>
        <p class='widget-help-description'><?php echo __('Enter in content to be used in the info window once the user clicks the marker', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'directions_button' ) ); ?>"><?php echo esc_html__( 'Directions Button:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_select_field(array('id' => $this->get_field_id('directions_button'), 'is_multi' => false, 'input_name' => $this->get_field_name('directions_button'), 'data_name' => 'directions_button', 'options' => array (
  'true' => 'On',
  'false' => 'Off',
), 'input_value' => $directions_button) ); ?>        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'parallax_disabled' ) ); ?>"><?php echo esc_html__( 'Parallax Disabled:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_checkbox_field(array('id' => $this->get_field_id('parallax_disabled'), 'input_name' => $this->get_field_name('parallax_disabled'), 'data_name' => 'parallax_disabled', 'options' => array (
  'disabled' => 'Disabled',
), 'input_value' => $parallax_disabled)); ?>
        <p class='widget-help-description'><?php echo __('Check this option to disable the parallax', 'MergePress'); ?></p>
        </div>
        <div class='input-row'>
        <label for="<?php echo esc_attr( $this->get_field_id( 'scrolling_disabled' ) ); ?>"><?php echo esc_html__( 'Scrolling Disabled:', 'MergePress' ); ?></label>
            <?php echo mergepress_widget_checkbox_field(array('id' => $this->get_field_id('scrolling_disabled'), 'input_name' => $this->get_field_name('scrolling_disabled'), 'data_name' => 'scrolling_disabled', 'options' => array (
  'disabled' => 'Disabled',
), 'input_value' => $scrolling_disabled)); ?>
        <p class='widget-help-description'><?php echo __('Check this option to disable users scrolling around the map', 'MergePress'); ?></p>
        </div>
        <?php
    }


    public function update( $new_instance, $old_instance ) {
        $instance = array();

        $instance['latitude'] = ( !empty( $new_instance['latitude'] ) ) ? $new_instance['latitude'] : '';
        $instance['longitude'] = ( !empty( $new_instance['longitude'] ) ) ? $new_instance['longitude'] : '';
        $instance['zoom'] = ( !empty( $new_instance['zoom'] ) ) ? $new_instance['zoom'] : '';
        $instance['height'] = ( !empty( $new_instance['height'] ) ) ? $new_instance['height'] : '';
        $instance['map_type'] = ( !empty( $new_instance['map_type'] ) ) ? $new_instance['map_type'] : '';
        $instance['scrolling'] = ( !empty( $new_instance['scrolling'] ) ) ? $new_instance['scrolling'] : '';
        $instance['map_style'] = ( !empty( $new_instance['map_style'] ) ) ? $new_instance['map_style'] : '';
        $instance['info_window_content'] = ( !empty( $new_instance['info_window_content'] ) ) ? $new_instance['info_window_content'] : '';
        $instance['directions_button'] = ( !empty( $new_instance['directions_button'] ) ) ? $new_instance['directions_button'] : '';
        $instance['parallax_disabled'] = ( !empty( $new_instance['parallax_disabled'] ) ) ? $new_instance['parallax_disabled'] : '';
        $instance['scrolling_disabled'] = ( !empty( $new_instance['scrolling_disabled'] ) ) ? $new_instance['scrolling_disabled'] : '';

        return $instance;
    }
}

$auto_google_map_widget = new auto_google_map_widget();