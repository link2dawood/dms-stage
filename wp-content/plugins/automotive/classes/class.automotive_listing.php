<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( "Automotive_Listing" ) ) {

	/**
	 * Automotive Listing Class
	 */
	#[AllowDynamicProperties]
	class Automotive_Listing {

		private $updated_format = false;
		public $id              = 0;

		public $has_sale_price = false;
		public $has_badge      = false;
		public $is_sold        = false;
		public $is_verified    = false;			// Required to show vehicle history report

		/**
		 * Initialize Automotive_Listing class
		 * @param integer $listing listing ID
		 */
    public function __construct( $listing = 0 ) {
      if ( is_numeric( $listing ) && $listing > 0 ) {
        $this->id = $listing;
      }

      // merge post data into Automotive_Listing object
			$this->get_post_data();

			// we must manually change the format
			if(!$this->updated_format){
				$this->convert_listing_data();
			}
    }

		public function __set($name, $value) {
	    $this->$name = $value;
	  }

		/**
		 * Get listing id
		 */
		public function get_id() {
      return $this->id;
    }

		/**
		 * Get post data for current listing
		 */
    public function get_post_data() {
      $post_data = get_post( $this->get_id() );

			if(!empty($post_data)){
				foreach($post_data as $post_data_key => $post_data_value){
					if($post_data_key !== 'ID'){
						$this->$post_data_key = $post_data_value;
					}
				}
			}
    }

		/**
		 * Get all post meta for a single post
		 * @param  int $post_id Post ID of listing
		 * @return mixed        Returns all post meta for listing
		 */
		private function get_post_meta_all($post_id = 0){
			global $wpdb;

			if(!$post_id){
				$post_id = $this->get_id();
			}

			$no_duplicate_meta = array(	// prevent some values from being array formats
				'gallery_images'  => 1,
				'other_comments'  => 1,
				'secondary_title' => 1,
				'location_map'		=> 1,
				'listing_options' => 1
			);

			/*

			Something on wp.com breaks with using wpdb here, for now we will switch to a new method

			$query_cache_key = 'automotive_listing_' . $post_id;


			$all_post_meta = wp_cache_get($query_cache_key);

			if($all_post_meta === false){

				$all_post_meta = array();
				$wpdb->query( "
					SELECT `meta_key`, `meta_value`
					FROM $wpdb->postmeta
					WHERE `post_id` = $post_id");

				foreach($wpdb->last_result as $k => $v){
					if(isset($all_post_meta[$v->meta_key]) && !is_array($all_post_meta[$v->meta_key]) && !isset($no_duplicate_meta[$v->meta_key])){
						$all_post_meta[$v->meta_key]   = array($all_post_meta[$v->meta_key]);
					}

					if(isset($all_post_meta[$v->meta_key]) && is_array($all_post_meta[$v->meta_key])){
						$all_post_meta[$v->meta_key][] = $v->meta_value;
					} else {
						$all_post_meta[$v->meta_key] = $v->meta_value;
					}
				};

				wp_cache_set($query_cache_key, $all_post_meta);
			}*/

			$all_post_meta = get_post_meta($post_id);

			// "unwrap" single array values
			if(!empty($all_post_meta)){
				foreach($all_post_meta as $meta_key => $meta_value){
					if(!isset($no_duplicate_meta[$meta_key])){
						if(is_array($meta_value)){
							$all_post_meta[$meta_key] = $meta_value[0];
						}
					} else {
						$all_post_meta[$meta_key] = $meta_value[0];
					}
				}

			}

			// store all post meta in case we need it
			$this->post_meta = $all_post_meta;

			return $all_post_meta;
		}

		/**
		 * Get all listing term data
		 */
    public function get_listing_term_data() {
			$return   					= array();
			$all_post_meta      = $this->get_post_meta_all();
      $listing_categories = Automotive_Plugin()->get_listing_categories();

      if(!empty($listing_categories)){
        foreach($listing_categories as $slug => $category){
          $return['listing_terms'][$slug] = (isset($all_post_meta[$slug]) && !empty($all_post_meta[$slug]) ? $all_post_meta[$slug] : null);

					// unset these values from the $this->post_meta, no need to for duplicates
					if(isset($this->post_meta[$slug])){
						unset($this->post_meta[$slug]);
					}
        }
      }

			// grab additional meta:
			if ( isset( $all_post_meta['listing_options'] ) && ! empty( $all_post_meta['listing_options'] ) ) {
				$return['listing_options'] = @unserialize( unserialize( $all_post_meta['listing_options'] ) );
			}

			if ( isset( $all_post_meta['location_map'] ) && ! empty( $all_post_meta['location_map'] ) ) {
				$return['location_map'] = @unserialize( $all_post_meta['location_map'] );
			}

			if ( isset( $all_post_meta['gallery_images'] ) && ! empty( $all_post_meta['gallery_images'] ) ) {
				$return['gallery_images'] = @unserialize( $all_post_meta['gallery_images'] );
			}

			if ( isset( $all_post_meta['multi_options'] ) && ! empty( $all_post_meta['multi_options'] ) ) {
				$return['multi_options'] = @unserialize( $all_post_meta['multi_options'] );
			}

			$return['car_sold'] = (isset($all_post_meta['car_sold']) && !empty($all_post_meta['car_sold']) ? $all_post_meta['car_sold'] : 2);
			$return['verified'] = (isset($all_post_meta['verified']) && !empty($all_post_meta['verified']) ? $all_post_meta['verified'] : false);

			return $return;
    }

		/**
		 * Get listing data value for current listing
		 * @param  string $data         Name of listing data value
		 * @param  mixed $default_value Default value if data isn't present
		 * @return mixed
		 */
		public function get_term($data, $default_value = null){
			$term_key = '_listing_term_' . $data;

      return (!empty($this->$term_key) && isset($this->$term_key) ? $this->$term_key : $default_value);
		}

		public function convert_listing_data(){
			$listing_meta = $this->get_listing_term_data();

			if(!empty($listing_meta['listing_terms'])){
				foreach($listing_meta['listing_terms'] as $term_key => $term_value){
					// avoid any conflicts
					$term_key = '_listing_term_' . $term_key;

          if($term_value === 'None'){
            $this->$term_key = esc_html__('None', 'listings');
          } else {
            $this->$term_key = $term_value;
          }
				}
			}

			// price stuff
			$price_options = (isset($listing_meta['listing_options']['price']) && !empty($listing_meta['listing_options']['price']) ? $listing_meta['listing_options']['price'] : false);

			if($price_options){
				$this->current_price  = (isset($price_options['value']) && !empty($price_options['value']) ? $price_options['value'] : false);
				$this->original_price = (isset($price_options['original']) && !empty($price_options['original']) ? $price_options['original'] : false);

				if(!empty($this->current_price) && !empty($this->original_price) && ($this->current_price != $this->original_price)){
					$this->has_sale_price = true;
				}
			} else {
				$this->current_price  = '';
				$this->original_price = '';
			}

			// other listing options
			$this->city_mpg          = (isset($listing_meta['listing_options']['city_mpg']['value']) && !empty($listing_meta['listing_options']['city_mpg']['value']) ? $listing_meta['listing_options']['city_mpg']['value'] : false);
			$this->highway_mpg       = (isset($listing_meta['listing_options']['highway_mpg']['value']) && !empty($listing_meta['listing_options']['highway_mpg']['value']) ? $listing_meta['listing_options']['highway_mpg']['value'] : false);
			$this->custom_tax_inside = (isset($listing_meta['listing_options']['custom_tax_inside']) && !empty($listing_meta['listing_options']['custom_tax_inside']) ? $listing_meta['listing_options']['custom_tax_inside'] : '');
			$this->custom_tax_page   = (isset($listing_meta['listing_options']['custom_tax_page']) && !empty($listing_meta['listing_options']['custom_tax_page']) ? $listing_meta['listing_options']['custom_tax_page'] : '');
			$this->short_desc        = (isset($listing_meta['listing_options']['short_desc']) && !empty($listing_meta['listing_options']['short_desc']) ? $listing_meta['listing_options']['short_desc'] : '');
			$this->video             = (isset($listing_meta['listing_options']['video']) && !empty($listing_meta['listing_options']['video']) ? $listing_meta['listing_options']['video'] : '');
			$this->custom_badge      = (isset($listing_meta['listing_options']['custom_badge']) && !empty($listing_meta['listing_options']['custom_badge']) ? $listing_meta['listing_options']['custom_badge'] : false);

			if(!empty($this->custom_badge)){
				$this->has_badge = true;
			}

			// if car is sold
			$this->car_sold = (isset($listing_meta['car_sold']) && !empty($listing_meta['car_sold']) ? $listing_meta['car_sold'] : 2);

			if($this->car_sold == '1'){
				$this->is_sold = true;
			}

			// verified for vehicle history report
			$this->verified = (isset($listing_meta['verified']) && !empty($listing_meta['verified']) ? $listing_meta['verified'] : false);

			if($this->verified == 'yes'){
				$this->is_verified = true;
			}

			// multi options
			$this->multi_options = (isset($listing_meta['multi_options']) && !empty($listing_meta['multi_options']) ? $listing_meta['multi_options'] : array());

			// gallery images
			$this->gallery_images = (isset($listing_meta['gallery_images']) && !empty($listing_meta['gallery_images']) ? $listing_meta['gallery_images'] : array());

			// location
			$this->location_data = (isset($listing_meta['location_map']) && !empty($listing_meta['location_map']) ? $listing_meta['location_map'] : array(
				'latitude'  => automotive_listing_get_option('default_value_lat', ''),
				'longitude' => automotive_listing_get_option('default_value_long', ''),
				'zoom'      => automotive_listing_get_option('default_value_zoom', '11'),
			));
		}

		public function get_post_meta($name, $default_value = null){
			return (isset($this->post_meta[$name]) ? $this->post_meta[$name] : $default_value);
		}

		public function get_price(){
			if(is_admin()){
				return $this->current_price;
			}

			return apply_filters('automotive_listing_price', $this->current_price, $this);
		}

		public function get_original_price(){
			if(is_admin()){
				return $this->original_price;
			}

			return apply_filters('automotive_listing_original_price', $this->original_price, $this);
		}

		public function get_permalink(){
			return apply_filters('automotive_listing_permalink', get_permalink($this->id), $this);
		}

		public function get_title(){
			if(is_admin()){
				return $this->post_title;
			}

			return apply_filters('automotive_listing_title', $this->post_title, $this);
		}

		public function get_secondary_title(){
			if(is_admin()){
				return $this->get_post_meta('secondary_title', false);
			}

			return apply_filters('automotive_listing_secondary_title', $this->get_post_meta('secondary_title', false), $this);
		}

		public function get_scroller_desc(){
			if(is_admin()){
				return $this->short_desc;
			}

			return apply_filters('automotive_listing_scroller_desc', $this->short_desc, $this);
		}

		public function get_badge_text(){
			if(is_admin()){
				return $this->custom_badge;
			}

			return apply_filters('automotive_listing_badge_text', $this->custom_badge, $this);
		}

		/**
		 * Get the gallery images for the current listing
 	   * @param  boolean $raw  Whether to return the raw ID's or image URL's
		 * @param  string  $size Size of images to return
		 * @param  boolean $include_no_image_found If set to false will not return the no found image
		 * @return array         Returns the gallery images
		 */
		public function get_gallery_images($raw = true, $size = 'full', $include_no_image_found = true){
			$return         = array();
			$gallery_images = $this->gallery_images;

			$raw  = apply_filters('automotive_listing_gallery_images__raw', $raw, $this);
			$size = apply_filters('automotive_listing_gallery_images__size', $size, $this);

			// add not found image if empty
			$no_listing_image = automotive_listing_get_option('not_found_image', false);

			if($include_no_image_found && $no_listing_image && empty($gallery_images)){
					$gallery_images[] = $no_listing_image[(Automotive_Plugin()->is_hotlink() ? 'url' : 'id')];
			}

			if($raw){
				$return = $gallery_images;
			} else {
				$cooked_return = array();

				if(!empty($gallery_images)){
					foreach($gallery_images as $gallery_image){
						if(Automotive_Plugin()->is_hotlink()){
							$cooked_return[] = array(
								'src'    => $gallery_image,
								'width'  => 0,
								'height' => 0,
								'alt'    => ''
							);
						} else {
							$gallery_image_src = wp_get_attachment_image_src($gallery_image, $size);
							$gallery_image_alt = get_post_meta($gallery_image, '_wp_attachment_image_alt', true);

							if($gallery_image_src){
								$cooked_return[] = array(
									'src'    => $gallery_image_src[0],
									'width'  => $gallery_image_src[1],
									'height' => $gallery_image_src[2],
									'alt'    => $gallery_image_alt
								);
							}
						}
					}
				}

				$return = $cooked_return;
			}

			if(empty($return) && !$raw){
				$return[] = array(
					'src'    => '',
					'width'  => 0,
					'height' => 0,
					'alt'    => ''
				);
			}

			return apply_filters('automotive_listing_gallery_images', $return, $this);
		}

		/**
		 * Get the main image used on the listing
	 	 * @param  boolean $raw                Whether to return the raw ID or image URL
		 * @param  string  $size               Size of image to return
		 * @param  boolean $return_transparent Return a transparent gif if no image found
		 * @return mixed                       Returns the main image
		 */
		public function get_main_image($raw = true, $size = 'full', $return_transparent = false){
			$return = '';

			$raw                = apply_filters('automotive_listing_main_gallery_image__raw', $raw, $this);
			$size               = apply_filters('automotive_listing_main_gallery_image__size', $size, $this);
			$return_transparent = apply_filters('automotive_listing_main_gallery_image__return_transparent', $return_transparent, $this);

			$gallery_images = $this->get_gallery_images($raw, $size);

			if(empty($gallery_images)){
				if($return_transparent){
					$return = apply_filters('automotive_listing_main_gallery_image__transparent_image', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7', $this);
				} else {
					$return = false;
				}
			} else {
				$return = $gallery_images[0];
			}

			return apply_filters('automotive_listing_main_gallery_image', $return, $this);
		}

		/**
		 * Grab the vehicle overview description
		 * @param  boolean $preview If for preview strip some tags that would break layout if cut off
		 * @return string           Returns the vehicle overview for the listing
		 */
		public function get_vehicle_overview($preview = false){
			$post_content = '';

			if($preview){
        $visual_composer_used = $this->get_post_meta("_wpb_vc_js_status", false);

				$limit        = automotive_listing_get_option('vehicle_overview_listings_limit', 250);
        $ellipsis 		= automotive_listing_get_option('vehicle_overview_ellipsis', '[...]');
				$stripp       = "<br><b><strong><u><i><span><a><img>";
				$vehicle_desc = (!empty($this->post_excerpt) ? $this->post_excerpt : $this->post_content);

        if($visual_composer_used){
          $post_content = preg_replace( '/\[[^\]]+\]/', '', $vehicle_desc );
          $post_content = force_balance_tags( substr(strip_tags($post_content, $stripp), 0, $limit) ) . " " . (strlen(strip_tags($post_content, $stripp)) > $limit ? $ellipsis : "");
        } else {
          $post_content = force_balance_tags( substr(strip_tags($vehicle_desc, $stripp), 0, $limit) ) . " " . (strlen(strip_tags($vehicle_desc, $stripp)) > $limit ? $ellipsis : "");
        }

			} else {
				$post_content = apply_filters('the_content', $this->post_content);
			}

			return $post_content;
		}

		/**
		 * Grab the other comments field
		 * @return string Other comments value
		 */
		public function get_other_comments($formatted = true){
			$return = $this->get_post_meta('other_comments', '');

			if($formatted){
				$return = wpautop(do_shortcode($return));
			}

			return apply_filters('automotive_listing_other_comments', $return, $this);
		}

		/**
		 * Grab the technical specifications field
		 * @return string Technical Specifications value
		 */
		public function get_technical_specifications($formatted = true){
			$return = $this->get_post_meta('technical_specifications', '');

			if(is_array($return)){
				$return = $return[0];
			}

			if($formatted){
				$return = wpautop(do_shortcode($return));
			}

			return apply_filters('automotive_listing_technical_specifications', $return, $this);
		}


		public function get_features_and_options($format = 'html'){
			$return = ($format == 'array' ? array() : '');

			$features_and_options = apply_filters('automotive_listing_features_and_options_pre', $this->multi_options, $this);
			$li_format            = apply_filters('automotive_listing_features_and_options_li', '<li><i class="fa-li fa fa-check"></i> %s</li>', $this);

			if(!empty($features_and_options)){
				natcasesort( $features_and_options );

				foreach($features_and_options as $single_feature){
					$single_feature = stripslashes( esc_html( Automotive_Plugin()->display_listing_category_term($single_feature, 'options') ) );

					if($format == 'array'){
						$return[] = $single_feature;
					} elseif($format == 'html'){
						$return .= sprintf($li_format, $single_feature);
					} elseif($format == 'pdf') {
						$return .=  $single_feature . ', ';
					}
				}

				if($format == 'pdf'){
					$return = rtrim( $return, ', ' );
				}
			} else {
				$return = ($format != 'pdf' ? '<li>' : '') . __( "There are no features available", "listings" ) . ($format != 'pdf' ? '</li>' : '');

				if($format == 'array'){
					$return = array();
				}
			}

			return apply_filters('automotive_listing_features_and_options', $return, $this);
		}

		public function get_location(){
			$return = array();

			$return['latitude']  = (isset($this->location_data['latitude']) && !empty($this->location_data['latitude']) ? $this->location_data['latitude'] : automotive_listing_get_option('default_value_lat', ''));
			$return['longitude'] = (isset($this->location_data['longitude']) && !empty($this->location_data['longitude']) ? $this->location_data['longitude'] : automotive_listing_get_option('default_value_long', ''));
			$return['zoom']      = (isset($this->location_data['zoom']) && !empty($this->location_data['zoom']) ? $this->location_data['zoom'] : automotive_listing_get_option('default_value_zoom', '11'));

			return apply_filters('automotive_listing_location', $return, $this);
		}

		/**
		 * Get the vehicle history link for the listing
		 * @return string URL for vehicle history report
		 */
		public function get_vehicle_history_link(){
			$history_link = '#';
			$is_autocheck = automotive_listing_get_option('autocheck_report', false);

			if(!$is_autocheck){
				$vehicle_history_report = automotive_listing_get_option('carfax_linker', false);

				$user_link    = ($vehicle_history_report && isset($vehicle_history_report['url']) ? $vehicle_history_report['url'] : '');
				$vin_category = ($vehicle_history_report && isset($vehicle_history_report['category']) ? $vehicle_history_report['category'] : '');
				$vin_value    = $this->get_term($vin_category);

				$history_link = str_replace('{vin}', (!empty($vin_value) ? $vin_value : ''), $user_link);
			} else {
				$autocheck_page = automotive_listing_get_option('autocheck_page', false);

				if($autocheck_page){
					$history_link = add_query_arg('listing_id', $this->id, get_permalink($autocheck_page));
				}
			}

			return esc_url(trim(apply_filters('automotive_listing_vehicle_history_link', $history_link, $this)));
		}

		/**
		 * Grabs the tax label used on the listing
		 * @param  boolean $inventory_listing Indicates which tax label to return since different labels are used on single and inventory pages
		 * @return string                     Tax label
		 */
		public function get_tax_label($inventory_listing = true){
			$return = '';

			if($inventory_listing){
				$return = apply_filters('automotive_listing_tax_label_inventory_listing', $this->custom_tax_inside, $this);
			} else {
				$return = apply_filters('automotive_listing_tax_label_single_listing', $this->custom_tax_page, $this);
			}

			return $return;
		}

		public function get_mpg(){
			$return = array('city' => '', 'highway' => '');

			$return['city']    = (!empty($this->city_mpg) ? $this->city_mpg : $return['city']);
			$return['highway'] = (!empty($this->highway_mpg) ? $this->highway_mpg : $return['highway']);

			return apply_filters('automotive_listing_mpg', $return, $this);
		}

		/**
		 * Grabs the video details for listing
		 * @return array Contains video ID as well as service hosting the video
		 */
		public function get_video($url = false){
			$return = array();

			if($this->video){
				$return = ($url ? $this->video : Automotive_Plugin()->get_video_id($this->video));
			}

			return apply_filters('automotive_listing_video', $return, $this);
		}

		/**
		 * Grabs the link for custom PDF if set
		 * @return string URL
		 */
		public function get_pdf_link(){
			$return =  false;

			$pdf_id = $this->get_post_meta('pdf_brochure_input', false);

			if($pdf_id){
				$return = wp_get_attachment_url($pdf_id);
			}

			return apply_filters('automotive_listing_pdf_link', $return, $this);
		}

		/**
		 * Grabs the price label for the inventory listing
		 * @return string Price label
		 */
		public function get_price_label(){
			$return = '';

			$price_label  					 = automotive_listing_get_option('default_value_price', '');
			$sale_price_label_prefix = automotive_listing_get_option('sale_value', '');
			$sale_price_label_suffix = automotive_listing_get_option('sale_value_suffix', '');

			$return .= ( $this->has_sale_price ? esc_html($sale_price_label_prefix) : "" );
			$return .= ( !empty($price_label) ? esc_html($price_label) : __( "Price", "listings" ) );
			$return .= ( $this->has_sale_price ? esc_html($sale_price_label_suffix) : "" );

			return apply_filters('automotive_listing_price_label', $return, $this);
		}

		/**
		 * Grabs the original price label for the inventory listing
		 * @return string Original price label
		 */
		public function get_original_price_label(){
 			$return = '';

 			$original_price_label  			 = automotive_listing_get_option('default_value_original_price', '');
 			$original_price_label_prefix = automotive_listing_get_option('original_value', '');
 			$original_price_label_suffix = automotive_listing_get_option('original_value_suffix', '');

 			if( !empty($this->original_price) ){
 				$return .= ( ! empty ( $original_price_label ) ? $original_price_label : __( "Original Price", "listings" ) );
 				$return .= ( ! empty( $this->original_price ) && !empty($original_price_label_suffix) ? $original_price_label_suffix : "" ) . ": ";
 			}

 			return apply_filters('automotive_listing_original_price_label', $return, $this);
 		}

		/**
		 * Generates the values used for the tables on the inventory for inventory pages
		 * @return array Array of categories and values
		 */
		public function get_listing_category_tables(){
			$return = array(
				'primary'   => array(),
				'secondary' => array()
			);

			$use_on_listing_values = array();

			foreach(Automotive_Plugin()->get_use_on_listing_categories() as $category){
				$slug  = $category['slug'];
				$value = $this->get_term($slug);

				if(empty($value)){
					$value = (isset($category['is_number']) && $category['is_number'] == 1 ? 0 : __( "None", "listings" ));
				}

				if(isset($category['currency']) && $category['currency'] == 1){
					$value = Automotive_Plugin()->format_currency($value);
				}

				// we don't include none values
        if(!empty($value) && $value != __("None", "listings") && $value != "None") {
					$column = (count($return['primary']) < 5 ? 'primary' : 'secondary');

					$return[$column][ $slug ] = array(
						"singular" => $category['singular'],
						"value"    => $value
					);
				}
			}

			return apply_filters('automotive_listing_category_tables', $return, $this);
		}

	}
}
