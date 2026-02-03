<?php

function mergepress_wpb_load_custom_params(){

  vc_add_shortcode_param( 'merge_number', function ( $settings, $value ) {
     $value = htmlspecialchars( $value );

     return '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '" type="number" value="' . $value . '"/>';
  });

  vc_add_shortcode_param( 'merge_dropdown_multi', function ( $settings, $value ) {
    $output = '';
    $css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );
    $output .= '<select name="' . $settings['param_name'] . '" multiple class="wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';

    if ( is_array( $value ) ) {
    	$value = isset( $value['value'] ) ? $value['value'] : array_shift( $value );
    }

    if ( ! empty( $settings['value'] ) ) {
    	foreach ( $settings['value'] as $index => $data ) {
    		if ( is_numeric( $index ) && ( is_string( $data ) || is_numeric( $data ) ) ) {
    			$option_label = $data;
    			$option_value = $data;
    		} elseif ( is_numeric( $index ) && is_array( $data ) ) {
    			$option_label = isset( $data['label'] ) ? $data['label'] : array_pop( $data );
    			$option_value = isset( $data['value'] ) ? $data['value'] : array_pop( $data );
    		} else {
    			$option_value = $data;
    			$option_label = $index;
    		}
    		$selected = '';
    		$option_value_string = (string) $option_value;
    		$value_array = (array) explode(",", $value);

    		if ($value_array && in_array($option_value_string, $value_array) ) {
    			$selected = 'selected="selected"';
    		}

    		$option_class = str_replace( '#', 'hash-', $option_value );
    		$output .= '<option class="' . esc_attr( $option_class ) . '" value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . htmlspecialchars( $option_label ) . '</option>';
    	}
    }

    $output .= '</select>';

    return $output;
  });

}

add_action('vc_after_init', 'mergepress_wpb_load_custom_params');