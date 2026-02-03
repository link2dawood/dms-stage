<?php


if ( ! class_exists( "VIN_Import_edmunds" ) ) {

	class VIN_Import_edmunds {
		private $api_key;
		private $api_secret_key;

		/**
		 * VIN_Import_edmunds constructor.
		 *
		 * Setup the API Keys
		 */
		public function __construct() {
			global $lwp_options;

			$this->api_key        = ( isset( $lwp_options['edmunds_api_key'] ) && ! empty( $lwp_options['edmunds_api_key'] ) ? $lwp_options['edmunds_api_key'] : "" );
			$this->api_secret_key = ( isset( $lwp_options['edmunds_api_secret'] ) && ! empty( $lwp_options['edmunds_api_secret'] ) ? $lwp_options['edmunds_api_secret'] : "" );
		}

		/**
		 * Grabs the info with the VIN
		 *
		 * @param $vin
		 *
		 * @return array|mixed|object
		 */
		public function get_vin_info($vin) {
			$response    = wp_remote_get("https://api.edmunds.com/api/vehicle/v2/vins/" . $vin . "?fmt=json&api_key=" . $this->api_key);

			if(!is_wp_error($response)) {
				$body_encode = json_decode( $response['body'] );

				if ( isset( $body_encode->years[0]->styles ) && ! empty( $body_encode->years[0]->styles ) ) {
					foreach ( $body_encode->years[0]->styles as $year_key => $year_details ) {
						$style_id        = $year_details->id;
						$style_equipment = $this->get_vin_options( $style_id );

						$body_encode->years[0]->styles[ $year_key ]->{"equipment"} = "";
						$body_encode->years[0]->styles[ $year_key ]->{"equipment"} = $style_equipment->equipment;
					}
				}
			} else {
				$body_encode = array();
			}

			return $body_encode;
		}

		/**
		 * Displays the options from the VIN
		 *
		 * @param $body
		 */
		public function display_options($body){
			$return = "";

			$normal_options_display = array("make", "model", "engine", "transmission", "categories", "MPG", "price");
			$single_options_display = array("drivenWheels", "numOfDoors", "manufacturer", "vin", "squishVin", "matchingType", "manufacturerCode");
			$deep_options_display   = array("options", "colors", "years");

			foreach($body as $key => $info){

				if(!empty($info)){
					$return .= "<h2>" . ucwords($key) . "</h2>\n";

					// normal option display
					if(in_array($key, $normal_options_display)){
						if(!empty($info)){
							foreach($info as $info_key => $info_value){

								if(is_array($info_value) && !empty($info_value)){
									foreach ($info_value as $info_deep_key => $info_deep_value) {
										$return .= "<li class='ui-state-default'>" . $info_deep_key . ": " . $info_deep_value . " <input type='hidden' name='' value='" . $key . "|" . $info_key . "|" . $info_deep_key. "' /></li>\n";
									}
								} else {
									$return .= "<li class='ui-state-default'>" . $info_key . ": " . $info_value . " <input type='hidden' name='' value='" . $key . "|" . $info_key . "' /></li>\n";
								}
							}
						}

						// single option display
					} elseif(in_array($key, $single_options_display)){
						$return .= "<li class='ui-state-default'>" . $key . ": " . $info . " <input type='hidden' name='' value='" . $key . "' /></li>\n";

						// deep options display
					} elseif(in_array($key, $deep_options_display)){
						if(!empty($info)){
							foreach($info[0] as $info_key => $info_value){
								// if not array
								if(!is_array($info_value)){
									$return .= "<li class='ui-state-default'>" . $info_key . ": " . $info_value . " <input type='hidden' name='' value='" . $key . "|0|" . $info_key . "' /></li>\n";

									// if is array
								} else {
									$i = 0;
									foreach($info_value as $deep_key => $deep_value){
										if($key == "options" || $key == "colors"){
											// D($deep_value);
											if(!empty($deep_value)){
												$return .= "<h5>" . ucwords($key) . " " . __("option set", "listings") . " " . $i . "</h5>\n";

												if(!empty($deep_value)){
													foreach($deep_value as $deepest_key => $deepest_value){
														$return .= "<li class='ui-state-default'>" . $deepest_key . ": " . $deepest_value . " <input type='hidden' name='' value='" . $key . "|" . $deep_key . "|" . $info_key . "|" . $i . "|" . $deepest_key . "' /></li>\n";
													}
												}

												$return .= "<hr>";
											}
										} elseif($key == "years"){

											// move submodels to the bottom
											$submodels = $deep_value['submodel'];
											unset($deep_value['submodel']);

											$deep_value['submodel'] = $submodels;

											$return .= "<h5>" . ucwords($key) . " " . __("submodel", "listings") . " " . $i . "</h5>\n";

											if(!empty($deep_value)){
												foreach($deep_value as $deeper_key => $deeper_value){
													$return .= (!is_array($deeper_value) && !is_object($deeper_value) ? "<li class='ui-state-default'>" . $deeper_key . ": " . $deeper_value . " <input type='hidden' name='' value='" . $key . "|" . $info_key . "|" . $i . "|" . $deeper_key . "' /></li>\n" : "");
												}
											}

											if(!empty($deep_value['submodel'])){
												foreach($deep_value['submodel'] as $submodel_key => $submodel_value){
													$return .= "<li class='ui-state-default'>" . $submodel_key . ": " . $submodel_value . " <input type='hidden' name='' value='" . $key . "|" . $info_key . "|" . $i . "|submodel|" . $submodel_key . "' /></li>\n";
												}
											}

											if(isset($deep_value['equipment']) && !empty($deep_value['equipment'])){
												$return .= "<div class='accordion accordion_parent'>";
												$return .= "<h3>" . __("Equipment Options", "listings") . "</h3>";

												$return .= "<div class='accordion accordion_child'>";
												foreach($deep_value['equipment'] as $equipment_key => $equipment_option_value){

													if(!empty($equipment_option_value['attributes'])){
														$return .= "<h3>" . $equipment_option_value['name']."</h3>";

														$return .= "<div>";
														foreach($equipment_option_value['attributes'] as $equipment_value_id => $equipment_values){
															$return .= "<li class='ui-state-default'>name: " . $equipment_values['name'] . " <input type='hidden' name='' value='" . $key . "|" . $i . "|" . $info_key . "|" . $i . "|equipment|" . $equipment_key . "|attributes|" . $equipment_value_id . "|name' /></li>\n";
															$return .= "<li class='ui-state-default'>value: " . $equipment_values['value'] . " <input type='hidden' name='' value='" . $key . "|" . $i . "|" . $info_key . "|" . $i . "|equipment|" . $equipment_key . "|attributes|" . $equipment_value_id . "|value' /></li>\n";
														}
														$return .= "</div>";
													}
												}
												$return .= "</div>";
												$return .= "</div>";
											}

											$return .= "<hr>\n";
										}

										$i++;
									}
								}
							}
						}
					}
				}
			}

			return $return;
		}

		function get_vin_options( $style_id ) {
			global $lwp_options;

			$api_key = ( isset( $lwp_options['edmunds_api_key'] ) && ! empty( $lwp_options['edmunds_api_key'] ) ? $lwp_options['edmunds_api_key'] : "" );

			$response    = wp_remote_get( "https://api.edmunds.com/api/vehicle/v2/styles/" . $style_id . "/equipment?availability=standard&fmt=json&api_key=" . $api_key );
			$body_encode = $response['body'];

			return json_decode( $body_encode );
		}

		public function valid(){
			return (!empty($this->api_key) && !empty($this->api_secret_key) ? true : false);
		}

	}

}