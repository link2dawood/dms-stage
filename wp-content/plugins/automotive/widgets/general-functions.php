<?php

// replace with your custom path
// function mergepress_get_file_path(){
//   return get_template_directory() . 'widgets/';
// }
//
// function mergepress_get_file_uri(){
//   return get_template_directory_uri() . 'widgets/';
// }

function mergepress_get_file_path(){
  return LISTING_HOME . '/widgets/';
}

function mergepress_get_file_uri(){
  return LISTING_DIR . 'widgets/';
}

function mergepress_normalize_url($value, $pagebuilder){
  $return = [
    'url'      => '',
    'target'   => '_self',
    'nofollow' => 'no',
    'title'    => ''
  ];

  if($pagebuilder === 'widget'){
    $return['url'] = $value;
  } elseif($pagebuilder === 'elementor'){
    $return['url']      = $value['url'];
    $return['target']   = (isset($value['is_external']) && $value['is_external'] === 'on' ? '_blank' : $return['target']);
    $return['nofollow'] = (isset($value['nofollow']) && $value['nofollow'] === 'on' ? 'yes' : $return['nofollow']);
    // $return['title'] =  todo: maybe we can utilize this in the custom attributes
  }

  return implode(",", $return);
}

// here we detect wpbakery since it automatically uses the shortcode and we can't parse before... yet
function mergepress_parse_url($value){
  $is_wpbakery = substr($value, 0, 4) === 'url:';

  if($is_wpbakery){
    $result       = array();
  	$params_pairs = explode( '|', $value );

  	if ( ! empty( $params_pairs ) ) {
  		foreach ( $params_pairs as $pair ) {
  			$param = preg_split( '/\:/', $pair );
  			if ( ! empty( $param[0] ) && isset( $param[1] ) && $param[1] !== 'title' ) {
  				$result[ $param[0] ] = rawurldecode( $param[1] );
  			}
  		}
  	}

    $ex_value    = [];
    $ex_value[0] = (isset($result['url']) ? $result['url'] : '');
    $ex_value[1] = (isset($result['target']) ? $result['target'] : '_self');
    $ex_value[2] = (isset($result['rel']) && $result['rel'] == 'nofollow' ? 'yes' : 'no');
    $ex_value[3] = (isset($result['title']) ? $result['title'] : '');
  } else {
    $ex_value = explode(",", $value);
  }

  return [
    'url'      => (isset($ex_value[0]) ? $ex_value[0] : ''),
    'target'   => (isset($ex_value[1]) ? $ex_value[1] : '_self'),
    'nofollow' => (isset($ex_value[2]) ? $ex_value[2] : 'no'),
    'title'    => (isset($ex_value[3]) ? $ex_value[3] : '')
  ];
}

function mergepress_normalize_image($value, $pagebuilder){
  $return = [
    'src'    => '',
    'alt'    => '',
    'title'  => '',
    'width'  => 0,
    'height' => 0,
    'id'     => 0
  ];

  if($pagebuilder === 'elementor'){
    $value = $value['id'];
  }

  if($pagebuilder === 'widget' || $pagebuilder === 'beaver' || $pagebuilder === 'elementor'){
    $full_image  = wp_get_attachment_image_src($value, 'full');

    $return['src']    = (isset($full_image[0]) ? $full_image[0] : '');
    $return['alt']    = get_post_meta($value, '_wp_attachment_image_alt', true);
    $return['title']  = get_the_title($value);
    $return['width']  = (isset($full_image[1]) ? $full_image[1] : '');
    $return['height'] = (isset($full_image[2]) ? $full_image[2] : '');
    $return['id']     = $value;
  }

  return implode(",", $return);
}

// again we do a bit of detection for wpbakery, it stores an image ID so we can assume if we have no commas, it's wpbakery
function mergepress_parse_image($value){
  $is_wpbakery = strpos($value, ',') === false;

  if($is_wpbakery){
    $full_image  = wp_get_attachment_image_src($value, 'full');

    $ex_value    = [];
    $ex_value[0] = (isset($full_image[0]) ? $full_image[0] : '');
    $ex_value[1] = get_post_meta($value, '_wp_attachment_image_alt', true);
    $ex_value[2] = get_the_title($value);
    $ex_value[3] = (isset($full_image[1]) ? $full_image[1] : '');
    $ex_value[4] = (isset($full_image[2]) ? $full_image[2] : '');
    $ex_value[5] = $value;
  } else {
    $ex_value = explode(",", $value);
  }

  return [
    'src'    => $ex_value[0],
    'alt'    => $ex_value[1],
    'title'  => $ex_value[2],
    'width'  => (int)$ex_value[3],
    'height' => (int)$ex_value[4],
    'id'     => (int)$ex_value[5]
  ];
}


function mergepress_beaver_builder_fields( $fields ) {
	$fields['repeater'] = mergepress_get_file_path() . '/beaver/repeater/repeater.php';

	return $fields;
}
add_filter( 'fl_builder_custom_fields', 'mergepress_beaver_builder_fields' );

function mergepress_admin_footer(){
  ?>

  <script id="tmpl-beaver-repeater" type="text/html">
   <li>
    <input type="text" name="{{data.name}}" value="{{data.value}}" />
   </li>
 </script><?php
}
add_action('admin_footer', 'mergepress_admin_footer');
add_action('wp_footer', 'mergepress_admin_footer');


function mergepress_widget_text_field($input_args){
  $args = array_merge(array(
    'id'          => 0,
    'input_name'  => '',
    'data_name'   => '',
    'type'        => 'text',
    'input_value' => ''
  ), $input_args);

  return "<input class=\"widefat\" id=\"" . esc_attr( $args['id'] ) . "\" name=\"" . esc_attr( $args['input_name'] ) . "\" data-name=\"" . esc_attr($args['data_name']) . "\" type=\"" . esc_attr($args['type']) . "\" value=\"" . esc_attr( $args['input_value'] ) . "\">";
}

function mergepress_widget_select_field( $input_args ){
  $args = array_merge(array(
    'id'          => 0,
    'is_multi'    => false,
    'input_name'  => '',
    'data_name'   => '',
    'options'     => array(),
    'input_value' => ''
  ), $input_args);

  $code = "<select id=\"" . esc_attr($args['id']) . "\" " . ($args['is_multi'] ? 'multiple ' : '') . "name=\"" . esc_attr($args['input_name']) . "\" data-name=\"" . esc_attr($args['data_name']) . "\">\n";

  if(!empty($args['options'])){
    foreach($args['options'] as $option_value => $option_label){
      $code .= "<option value='" . esc_attr($option_value) . "'";

      if($args['is_multi']){
        $code .=  (is_array($args['input_value']) && in_array($option_value, $args['input_value']) ? 'selected' : '');
      } else {
        $code .= selected($args['input_value'], $option_value, false);
      }

      $code .= ">" . esc_html($option_label) . "</option>\n";
    }
  }

  $code .= "</select>\n";

  return $code;
}

// function mergepress_widget_textarea_field($id, $input_name, $data_name, $input_value = ''){
function mergepress_widget_textarea_field( $input_args ){
  $args = array_merge(array(
    'id'          => 0,
    'input_name'  => '',
    'data_name'   => '',
    'input_value' => ''
  ), $input_args);

  return "<textarea class=\"widefat\" id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['input_name']) . "\" data-name=\"" . esc_attr($args['data_name']) . "\">" . esc_attr($args['input_value']) . "</textarea>";
}

// function mergepress_widget_color_field($id, $input_name, $data_name, $input_value = ''){
function mergepress_widget_color_field( $input_args ){
  $args = array_merge(array(
    'id'          => 0,
    'input_name'  => '',
    'data_name'   => '',
    'input_value' => ''
  ), $input_args);

  return "<input class=\"widefat widget-color-picker\" id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['input_name']) . "\" data-name=\"" . esc_attr($args['data_name']) . "\" type=\"text\" value=\"" . esc_attr( $args['input_value'] ) . "\">";
}

function mergepress_widget_image_field( $input_args ){
  $args = array_merge(array(
    'id'          => 0,
    'input_name'  => '',
    'data_name'   => '',
    'input_value' => ''
  ), $input_args);

  $widget_image = ($args['input_value'] ? wp_get_attachment_image_src($args['input_value'], 'full') : false);

  $code  = "<div class=\"widget-select-image-container" .  ($args['input_value'] ? ' has-image' : '') . "\">";
  $code .= "<input class=\"widefat image-value\" id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['input_name']) . "\" data-name=\"" . esc_attr($args['data_name']) . "\" type=\"hidden\" value=\"" . esc_attr($args['input_value']) . "\">\n";
  $code .= "<img src='" . esc_url($widget_image ? $widget_image[0] : '') . "' class=\"image-source\">";
  $code .= "<button class=\"widget-choose-image button-primary\">" . __('Choose Image') . "</button>\n";
  $code .= "<button class=\"widget-remove-image button-secondary\">" . __('Remove Image') . "</button>\n";
  $code .= "</div>";

  return $code;
}

function mergepress_widget_url_field( $input_args ){
  $args = array_merge(array(
    'id'              => 0,
    'input_name'      => '',
    'data_name'       => '',
    'input_value'     => '',
    'old_input_value' => '',
  ), $input_args);

  $old_input_value = explode(',', (!empty($args['old_input_value']) ? $args['old_input_value'] : $args['input_value']));

  $code  = "<textarea class=\"widefat hide widget-url-textarea\" id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['input_name']) . "\" data-name=\"" . esc_attr($args['data_name']) . "\">" . esc_attr($args['input_value']) . "</textarea>\n";

  $code .= "<div class=\"widget-url-text\">" . is_array($old_input_value) ? esc_html($old_input_value[0]) : '' . "</div>";

  $code .= "<button class=\"widget-choose-url button\" data-default=\"" . esc_attr($args['input_value']) . "\" data-editor-id=\"" . esc_attr($args['id']) . "\">" . __('Choose URL') . "</button>";

  return $code;
}

function mergepress_widget_wysiwyg_field( $input_args ){
  $args = array_merge(array(
    'id'          => 0,
    'input_name'  => '',
    'data_name'   => '',
    'input_value' => ''
  ), $input_args);

  return "<textarea class=\"widefat widget-wp-editor\" id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['input_name']) . "\" data-name=\"" . esc_attr($args['data_name']) . "\">" . esc_html($args['input_value']) . "</textarea>";
}

function mergepress_widget_html_field( $input_args ){
  $args = array_merge(array(
    'id'          => 0,
    'input_name'  => '',
    'data_name'   => '',
    'input_value' => ''
  ), $input_args);

  $code  = "<div class=\"widget-html-field\" id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['id']) . "\"  data-name=\"" . esc_attr($args['data_name']) . "\" data-value=\"" . esc_attr($args['input_value']) . "\"></div>";
  $code .= "<textarea class=\"widefat widget-html-field-code hidden\" data-id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['input_name']) . "\">" . esc_html($args['input_value']) . "</textarea>";

  return $code;
}

function mergepress_widget_checkbox_field( $input_args ){
  $args = array_merge(array(
    'id'          => 0,
    'input_name'  => '',
    'data_name'   => '',
    'options'     => array(),
    'input_value' => ''
  ), $input_args);

  $code = '';

  if(!empty($args['options'])){
    // $increment = 0;
    foreach($args['options'] as $option_value => $option_label){
      $code .= "<div><label><input type=\"checkbox\" id=\"" . esc_attr($args['id']) . "\" name=\"" . esc_attr($args['input_name']) . "[]\"  data-name=\"" . esc_attr($args['data_name']) . "\" value=\"" . esc_attr($option_value) . "\"" . (is_array($args['input_value']) && in_array($option_value, $args['input_value']) ? 'checked' : '') . ">";
      $code .= $option_label . "</label></div>\n";
    }
  }

  return $code;
}

require_once(mergepress_get_file_path() . 'elementor/elementor-loader.php');
require_once(mergepress_get_file_path() . 'widgets/widget-loader.php');
require_once(mergepress_get_file_path() . 'wpb/wpb-loader.php');
