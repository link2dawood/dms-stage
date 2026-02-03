<?php


if ( ! class_exists( "VIN_Import_vinfreecheck" ) ) {

	class VIN_Import_vinfreecheck {
		private $api_key;

		/**
		 * VIN_Import_vinfreecheck constructor.
		 *
		 * Setup the API Keys
		 */
		public function __construct() {
			global $lwp_options;

			$this->api_key = ( isset( $lwp_options['vinfreecheck_api_key'] ) && ! empty( $lwp_options['vinfreecheck_api_key'] ) ? $lwp_options['vinfreecheck_api_key'] : "" );
		}

		/**
		 * Grabs the info with the VIN
		 *
		 * @param $vin
		 *
		 * @return array|mixed|object
		 */
		public function get_vin_info($vin) {
      $is_error    = false;
      $body_encode = array();

      $vin_transient_key = 'vinfreecheck_vin_' . $vin;

      if ( false === ( $body_encode = get_transient( $vin_transient_key ) ) ) {

        $response = wp_remote_get("https://vindecoder.p.rapidapi.com/decode_vin?vin=" . $vin, array(
          'headers' => array(
            'X-RapidAPI-Host' => 'vindecoder.p.rapidapi.com',
            'X-RapidAPI-Key'  => $this->api_key
          )
        ));


  			if(!is_wp_error($response)) {
  				$body_encode   = json_decode( $response['body'] );
          $response_code = wp_remote_retrieve_response_code($response);

          if($response_code == 403){
            $is_error = true;

            $body_encode->errorType = true;
          } else {

            if(isset($body_encode->success) && $body_encode->success){
              $body_encode = $body_encode->specification;
            }

          }
  			}

        if(!$is_error){
          set_transient( $vin_transient_key, $body_encode, 12 * HOUR_IN_SECONDS );
        }
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

      if(!empty($body)){
        foreach($body as $item_key => $item_value){
          $return .= "<li class='ui-state-default'>" . $item_key . ": " . $item_value . " <input type='hidden' name='' value='" . $item_key . "' /></li>";
        }
      }

			return $return;
		}

		public function valid(){
			return (!empty($this->api_key));
		}

	}

}