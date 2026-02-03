<?php


if ( ! class_exists( "VIN_Import" ) ) {

	class VIN_Import {
		public $current_api = '';
		public $apis = array();

		public $has_import_label = false;


		public function __construct() {
			global $lwp_options;

			$current_api = ( isset( $lwp_options['vin_import_api'] ) && ! empty( $lwp_options['vin_import_api'] ) ? $lwp_options['vin_import_api'] : "edmunds" );
			$import_api  = "VIN_Import_" . $current_api;

			if ( class_exists( $import_api ) ) {
				$this->current_api = new $import_api();
			}

			if ( method_exists( $this->current_api, 'get_title_key' ) ) {
				$this->has_import_label = true;
			}
		}


		public function add_vin_api( $vin_id, $vin_text, $fields ) {
			$this->apis[ sanitize_key( $vin_id ) ] = array(
				"title"  => esc_html( $vin_text ),
				"fields" => $fields
			);
		}

		public function get_all_installed_vin_apis() {
			$installed_apis = array();
			
			// add the default API choice
			$this->apis['edmunds'] = array(
				"title"  => __( "Edmunds", "listings" ),
				"desc"   => __( "Enter your Edmunds API Keys to import vehicle information with a VIN", "listings" ),
				"fields" => array(
					"edmunds_api_key"    => __( "Edmunds API Key", "listings" ),
					"edmunds_api_secret" => __( "Edmunds API Secret Key", "listings" )
				)
			);

			$this->apis['vinfreecheck'] = array(
				"title"  => __( "Free VIN Check", "listings" ),
				"desc"   => sprintf( __( "Enter your VIN Free Check API key to import vehicle information with a VIN, register for an API key here: %s", "listings" ), 'https://rapidapi.com/vinfreecheck/api/vin-decoder-1', '</a>'),
				"fields" => array(
					"vinfreecheck_api_key" => __( "VIN Free Check API Key", "listings" )
				)
			);

			if ( ! empty( $this->apis ) ) {
				foreach ( $this->apis as $api => $api_details ) {
					$installed_apis[ $api ] = $api_details['title'];
				}
			}

			return $installed_apis;
		}

		public function get_all_installed_vin_api_fields() {
			return $this->apis;
		}

		function get_upload_image( $image_url ) {
			$image = $image_use = str_replace( "\"", "", $image_url );
			$get   = wp_remote_get( $image );

			if ( ! is_wp_error( $get ) && $get['response']['code'] == 200 ) {
				$type = wp_remote_retrieve_header( $get, 'content-type' );

				$allowed_images = array( "image/jpg", "image/jpeg", "image/png", "image/gif" );
				$extension      = pathinfo( $image, PATHINFO_EXTENSION );

				// try to determine type if not set
				if ( empty( $type ) ) {
					if ( $extension == "jpg" || $extension == "jpeg" ) {
						$type = "image/jpg";
					} elseif ( $extension == "png" ) {
						$type = "image/png";
					} elseif ( $extension == "gif" ) {
						$type = "image/gif";
					}
				}

				if ( ! $type && in_array( $type, $allowed_images ) ) {
					return false;
				}

				if ( empty( $extension ) || ! in_array( $extension, array( 'jpg', 'jpeg', 'gif', 'png' ) ) ) {
					$content_type = $type;

					// check if content type is even set...
					if ( strstr( $content_type, "image/jpg" ) || strstr( $content_type, "image/jpeg" ) ) {
						$image_use = $image . ".jpg";
						$type      = "image/jpg";
					} elseif ( strstr( $content_type, "image/png" ) ) {
						$image_use = $image . ".png";
						$type      = "image/png";
					} elseif ( strstr( $content_type, "image/gif" ) ) {
						$image_use = $image . ".gif";
						$type      = "image/gif";
					}
				}

				$mirror = wp_upload_bits( basename( $image_use ), '', wp_remote_retrieve_body( $get ) );

				$attachment = array(
					'post_title'     => basename( $image ),
					'post_mime_type' => $type
				);

				if ( isset( $mirror ) && ! empty( $mirror ) ) {
					$attach_id = wp_insert_attachment( $attachment, $mirror['file'] );

					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					$attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );

					wp_update_attachment_metadata( $attach_id, $attach_data );
				} else {
					$attach_id = "";
				}

				return $attach_id;
			} else {
				return "";
			}
		}

		public function get_vin_value( $location, $body, $safe_name ) {
			$return_value = "";

			if ( strpos( $location, "|" ) !== false ) {
				$search_array = explode( "|", $location );
			} else {
				$search_array = array( $location );
			}

			if ( ! empty( $search_array ) ) {
				$return_value = $body;

				foreach ( $search_array as $value ) {
					if ( isset( $return_value[ $value ] ) && ! empty( $return_value[ $value ] ) ) {
						$return_value = $return_value[ $value ];
					} else {
						$return_value = "";

						break;
					}
				}
			}

			if(isset($_POST['import_label'][$safe_name]) && is_array($_POST['import_label'][$safe_name]) && isset($_POST['import_label'][$safe_name]) && isset($_POST['import_label'][$safe_name][$location])){
				$return_value = $this->get_title_key( array(
					'safe_name' 			=> $safe_name,
					'vin_association' => $location,
					'body' 						=> $body
				) ) . ": " . $return_value;
			} elseif(!empty($_POST['import_label']) && isset($_POST['import_label'][$safe_name]) && !is_array($_POST['import_label'][$safe_name])){
				$return_value = $this->get_title_key( array(
					'safe_name' 			=> $safe_name,
					'vin_association' => $location,
					'body' 						=> $body
				) ) . ": " . $return_value;
			}

			return $return_value;
		}

		/* External Class Methods */
		public function call( $method, $params = "" ) {
			if ( method_exists( $this->current_api, $method ) ) {
				return call_user_func( array( $this->current_api, $method ), $params );
			} else {
				return false;
			}
		}

		public function get_vin_info( $vin ) {
			return $this->call( "get_vin_info", $vin );
		}

		public function display_options( $body ) {
			return $this->call( "display_options", $body );
		}

		public function valid() {
			return $this->call( "valid" );
		}

		public function get_title_key( $params ) {
			return $this->call( "get_title_key", $params );
		}

		public function draggable_element($key, $label, $value, $name = ''){
			$import_label = ($this->has_import_label && !empty($name) ? '<div><label><input type="checkbox" name="import_label[' . $name . ']" value="true"> Import Label</label></div>' : '');

			return sprintf("<li class='ui-state-default'>%s: %s <input type='hidden' name='import[%s]' value='%s' />" . $import_label . "</li>", esc_html($label), esc_html($value), esc_attr($name), esc_attr($key) );
		}

	}

}