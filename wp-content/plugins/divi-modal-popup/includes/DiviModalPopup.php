<?php

class El_DiviModalPopup extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'divi-modal-popup';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'divi-modal-popup';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = ELICUS_DIVI_MODAL_POPUP_VERSION;

	/**
	 * DMP_DiviModalPopup constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'divi-modal-popup', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );

		$this->plugin_setup();

		add_action( 'wp_ajax_et_fb_ajax_save', array( $this, 'et_fb_ajax_save' ), 10 );
		add_filter( 'et_pb_module_shortcode_attributes', array( $this, 'dmp_migrate_gradient' ), 10, 5 );
	}

	public function dmp_exists_and_is_not_empty( $key, $array ) {
        if ( ! array_key_exists( $key, $array ) ) {
            return false;
        }

        return ! empty( $array[ $key ] );
    }

	public function dmp_migrate_gradient( $props, $attrs, $render_slug, $_address, $content ) {
        if ( 'el_modal_popup' === $render_slug && ET_BUILDER_PRODUCT_VERSION >= '4.16.0' ) {
            $basenames = array(
                'trigger_element_bg',
                'modal_overlay_bg',
                'modal_bg',
                'modal_header_bg',
                'modal_body_bg',
                'modal_footer_bg',
            );
            foreach( $basenames as $basename ) {
                if ( isset( $props["{$basename}_color_gradient_stops"] ) && '#2b87da 0%|#29c4a9 100%' === $props["{$basename}_color_gradient_stops"] ) {
                    if (
                        $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_start", $props ) &&
                        $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_start_position", $props ) &&
                        $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_end", $props ) &&
                        $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_end_position", $props )
                    ) {
                        // Strip percent signs and round to nearest int for our calculations.
                        $pos_start      = round( floatval( $props["{$basename}_color_gradient_start_position"] ) );
                        $pos_start_unit = trim( $props["{$basename}_color_gradient_start_position"], ',. 0..9' );
                        $pos_end        = round( floatval( $props["{$basename}_color_gradient_end_position"] ) );
                        $pos_end_unit   = trim( $props["{$basename}_color_gradient_end_position"], ',. 0..9' );
                
                        // Our sliders use percent values, but pixel values might be manually set.
                        $pos_units_match = ( $pos_start_unit === $pos_end_unit );
                
                        // If (and ONLY if) both values use the same unit of measurement,
                        // adjust the end position value to be no smaller than the start.
                        if ( $pos_units_match && $pos_end < $pos_start ) {
                            $pos_end = $pos_start;
                        }
                
                        // Prepare to receive the new gradient settings.
                        $new_values = array(
                            'start' => $props["{$basename}_color_gradient_start"] . ' ' . $pos_start . $pos_start_unit,
                            'end'   => $props["{$basename}_color_gradient_end"] . ' ' . $pos_end . $pos_end_unit,
                        );
                        
                        $props["{$basename}_color_gradient_stops"] = implode( '|', $new_values );
                        
                        $is_responsive_enabled = et_pb_responsive_options()->is_responsive_enabled( $props, "{$basename}_color" );
                        if ( $is_responsive_enabled ) {
                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_start_tablet", $props ) ) {
                                $start_color = $props["{$basename}_color_gradient_start_tablet"];
                            } else {
                                $start_color = $props["{$basename}_color_gradient_start"];
                            }

                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_end_tablet", $props ) ) {
                                $end_color = $props["{$basename}_color_gradient_end_tablet"];
                            } else {
                                $end_color = $props["{$basename}_color_gradient_end"];
                            }

                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_start_position_tablet", $props ) ) {
                                $pos_start = $props["{$basename}_color_gradient_start_position_tablet"];
                                $pos_start_unit = trim( $props["{$basename}_color_gradient_start_position_tablet"], ',. 0..9' );
                            } else {
                                $pos_start = $props["{$basename}_color_gradient_start_position"];
                                $pos_start_unit = trim( $props["{$basename}_color_gradient_start_position"], ',. 0..9' );
                            }

                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_end_position_tablet", $props ) ) {
                                $pos_end = $props["{$basename}_color_gradient_end_position_tablet"];
                                $pos_end_unit = trim( $props["{$basename}_color_gradient_end_position_tablet"], ',. 0..9' );
                            } else {
                                $pos_end = $props["{$basename}_color_gradient_end_position"];
                                $pos_end_unit = trim( $props["{$basename}_color_gradient_end_position"], ',. 0..9' );
                            }

                            // Our sliders use percent values, but pixel values might be manually set.
                            $pos_units_match = ( $pos_start_unit === $pos_end_unit );
                    
                            // If (and ONLY if) both values use the same unit of measurement,
                            // adjust the end position value to be no smaller than the start.
                            if ( $pos_units_match && $pos_end < $pos_start ) {
                                $pos_end = $pos_start;
                            }

                            // Prepare to receive the new gradient settings.
                            $new_values = array(
                                'start' => $start_color . ' ' . $pos_start . $pos_start_unit,
                                'end'   => $end_color . ' ' . $pos_end . $pos_end_unit,
                            );
                            
                            $props["{$basename}_color_gradient_stops_tablet"] = implode( '|', $new_values );

                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_start_phone", $props ) ) {
                                $start_color = $props["{$basename}_color_gradient_start_phone"];
                            }

                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_end_phone", $props ) ) {
                                $end_color = $props["{$basename}_color_gradient_end_phone"];
                            }

                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_start_position_phone", $props ) ) {
                                $pos_start = $props["{$basename}_color_gradient_start_position_phone"];
                                $pos_start_unit = trim( $props["{$basename}_color_gradient_start_position_phone"], ',. 0..9' );
                            }

                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_end_position_phone", $props ) ) {
                                $pos_end = $props["{$basename}_color_gradient_end_position_phone"];
                                $pos_end_unit = trim( $props["{$basename}_color_gradient_end_position_phone"], ',. 0..9' );
                            }

                            // Our sliders use percent values, but pixel values might be manually set.
                            $pos_units_match = ( $pos_start_unit === $pos_end_unit );
                    
                            // If (and ONLY if) both values use the same unit of measurement,
                            // adjust the end position value to be no smaller than the start.
                            if ( $pos_units_match && $pos_end < $pos_start ) {
                                $pos_end = $pos_start;
                            }

                            // Prepare to receive the new gradient settings.
                            $new_values = array(
                                'start' => $start_color . ' ' . $pos_start . $pos_start_unit,
                                'end'   => $end_color . ' ' . $pos_end . $pos_end_unit,
                            );
                            
                            $props["{$basename}_color_gradient_stops_phone"] = implode( '|', $new_values );
                        }
                        
                        $is_hover_enabled = et_pb_hover_options()->is_enabled( "{$basename}_color", $props );
                        if ( $is_hover_enabled ) {
                            if ( isset(
                                 $props["{$basename}_color_gradient_start__hover"],
                                 $props["{$basename}_color_gradient_start_position__hover"],
                                 $props["{$basename}_color_gradient_end__hover"],
                                 $props["{$basename}_color_gradient_end_position__hover"]
                                 )
                            ) {
                                // Strip percent signs and round to nearest int for our calculations.
                                $pos_start      = round( floatval( $props["{$basename}_color_gradient_start_position__hover"] ) );
                                $pos_start_unit = trim( $props["{$basename}_color_gradient_start_position__hover"], ',. 0..9' );
                                $pos_end        = round( floatval( $props["{$basename}_color_gradient_end_position__hover"] ) );
                                $pos_end_unit   = trim( $props["{$basename}_color_gradient_end_position__hover"], ',. 0..9' );
                        
                                // Our sliders use percent values, but pixel values might be manually set.
                                $pos_units_match = ( $pos_start_unit === $pos_end_unit );
                        
                                // If (and ONLY if) both values use the same unit of measurement,
                                // adjust the end position value to be no smaller than the start.
                                if ( $pos_units_match && $pos_end < $pos_start ) {
                                    $pos_end = $pos_start;
                                }
                        
                                // Prepare to receive the new gradient settings.
                                $new_values = array(
                                    'start' => $props["{$basename}_color_gradient_start__hover"] . ' ' . $pos_start . $pos_start_unit,
                                    'end'   => $props["{$basename}_color_gradient_end__hover"] . ' ' . $pos_end . $pos_end_unit,
                                );
                                
                                $props["{$basename}_color_gradient_stops__hover"] = implode( '|', $new_values );
                            }
                        }
                    }
                }
                if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_type", $props ) && 'radial' === $props["{$basename}_color_gradient_type"] ) {
                    $props["{$basename}_color_gradient_type"] = 'circular';
                    $is_responsive_enabled = et_pb_responsive_options()->is_responsive_enabled( $props, "{$basename}_color" );
                    if ( $is_responsive_enabled ) {
                        foreach( array( 'tablet', 'phone' ) as $device ) {
                            if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_type_{$device}", $props ) && 'radial' === $props["{$basename}_color_gradient_type_{$device}"] ) {
                                $props["{$basename}_color_gradient_type_{$device}"] = 'circular';
                            }
                        }
                    }
                    $is_hover_enabled = et_pb_hover_options()->is_enabled( "{$basename}_color", $props );
                    if ( $is_hover_enabled ) {
                        if ( $this->dmp_exists_and_is_not_empty( "{$basename}_color_gradient_type__hover", $props ) && 'radial' === $props["{$basename}_color_gradient_type__hover"] ) {
                            $props["{$basename}_color_gradient_type__hover"] = 'circular';
                        }
                    }
                }
            }
        }

        return $props;
    }

	/**
	 * plugin setup function.
	 *
	 *@since 1.0.0
	 */
	public function plugin_setup() {
		require_once plugin_dir_path( __FILE__ ) . 'functions.php';
	}

    public function array_recursion( &$array, $search ) {
	    foreach ( $array as $key => &$value ) {
	        if ( is_array( $value ) ) {
	        	$this->array_recursion( $value, $search );
	        } else if ( in_array( $key, $search, true ) ) {
	        	if ( 'el_modal_popup' === $value ) {
	            	if ( ! isset( $array['attrs']['modal_id'] ) || '' ===  trim( $array['attrs']['modal_id'] ) ) {
	            		$modal_counter = intval( get_option( 'el_modal_popup_counter', '1' ) );
		        		$array['attrs']['modal_id'] = 'el_modal_popup_module_' . $modal_counter;
		        		$modal_counter = $modal_counter + 1;
						update_option( 'el_modal_popup_counter', absint( $modal_counter ) );
	                }
	            }
	        }
	    }
	}

    public function et_fb_ajax_save() {
		/**
		 * @see et_fb_ajax_save() in themes/Divi/includes/builder/functions.php
		 */
		if (
			! isset( $_POST['et_fb_save_nonce'] ) ||
			! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['et_fb_save_nonce'] ) ), 'et_fb_save_nonce' )
		) {
			return;
		}

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : '';

		if ( '' === $post_id ) {
			return;
		}

		if ( 
			isset( $_POST['options']['status'] ) &&
			function_exists( 'et_fb_current_user_can_save' ) &&
			! et_fb_current_user_can_save( $post_id, sanitize_text_field( wp_unslash( $_POST['options']['status'] ) ) )
		) {
			return;
		}

		// Fetch the builder attributes and sanitize them.
		if ( isset ( $_POST['modules'] ) ) {
			// phpcs:ignore ET.Sniffs.ValidatedSanitizedInput.InputNotSanitized
			$shortcode_data = json_decode( stripslashes( $_POST['modules'] ), true );
			$this->array_recursion( $shortcode_data, array( 'type' ) );
			$_POST['modules'] = addslashes( wp_json_encode( $shortcode_data ) );
		}	
	}
    
}

new El_DiviModalPopup;
