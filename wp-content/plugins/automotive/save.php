<?php
//********************************************
//	Save custom meta fields
//***********************************************************
function automotive_save_custom_meta($post_id, $post_after, $post_before){
	$post_type = get_post_type($post_id);
	$layout    = (isset($_POST['layout']) && !empty($_POST['layout']) ? $_POST['layout'] : false);

	if($post_type !== 'listings' && $post_type !== 'listings_portfolio'){
		return;
	}

	if($layout){
		update_post_meta((int)$post_id, "layout", (string)$layout);
	}

	$save_from_edit_page = (isset($_POST['post_title']) && (!isset($_POST['action']) || isset($_POST['action']) && $_POST['action'] != 'inline-save') ? true : false); // test with a field that only exists if they save from the edit listing page


	// quick save
	if(isset($_POST['action']) && $_POST['action'] == 'inline-save'){
		$post_options = get_post_meta($post_id, 'listing_options', true);

		if(!empty($post_options)){
			$post_options = unserialize($post_options);
		}

		$post_options['price']['value']    = (isset($_POST['options']['price']['value']) && !empty($_POST['options']['price']['value']) ? $_POST['options']['price']['value'] : "");
		$post_options['price']['original'] = (isset($_POST['options']['price']['original']) && !empty($_POST['options']['price']['original']) ? $_POST['options']['price']['original'] : "");

		// $post_options = (isset($_POST['options']) ? serialize($_POST['options']) : null);

		update_post_meta($post_id, "listing_options", serialize($post_options));
	}



	if($post_after->post_type == "listings" && $save_from_edit_page){
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return $post_id;
		} else {
			// prevents post meta from being removed when trashing/restoring
			if ( 'trash' === $post_after->post_status || 'trash' === $post_before->post_status ) {
	        return;
	    }

			global $Listing;

			$featured_vehicle_widget = automotive_listing_get_option('featured_vehicle_widget', false);
			$default_value_city      = automotive_listing_get_option('default_value_city', 'City');
			$default_value_hwy       = automotive_listing_get_option('default_value_hwy', 'Highway');


			$listing_tabs = $Listing->get_listing_tabs();

			if(!empty($listing_tabs)){
				foreach($listing_tabs as $tab_id => $tab){

					$name = (isset($tab['name']) && !empty($tab['name']) ? $tab['name'] : false);
					$type = (isset($tab['type']) && !empty($tab['type']) ? $tab['type'] : false);

					if($name && $type){
						if($type == 'wp_editor'){
							$value = (isset($_POST[$name]) ? $_POST[$name] : '');

							update_post_meta((int)$post_id, $name, $value);
						} elseif($type == 'multi_options'){
							$multi_options    = (isset($_POST[$name]) && !empty($_POST[$name]) ? $_POST[$name] : array());
							$existing_options = get_post_meta($post_id, 'automotive_multi_option');

							if(!empty($multi_options)){
								foreach($multi_options as $multi_option){
									$exists = array_search($multi_option, $existing_options);

									if($exists === false){
										add_post_meta($post_id, 'automotive_multi_option', $multi_option);

										unset($existing_options[$exists]);
									} elseif($exists){
										unset($existing_options[$exists]);
									}
								}
							}

							// all remaining options have been unchecked so lets remove them
							if(!empty($existing_options)){
								foreach($existing_options as $existing_option){
									delete_post_meta($post_id, 'automotive_multi_option', $existing_option);
								}
							}

							update_post_meta($post_id, $name, $multi_options);
						} elseif($type == 'map') {
							if(isset($_POST[$name]) && !empty($_POST[$name])){
								update_post_meta((int)$post_id, $name, $_POST[$name]);

								$latitude  = (isset($_POST[$name]['latitude']) && !empty($_POST[$name]['latitude']) ? $_POST[$name]['latitude'] : "");
								$longitude = (isset($_POST[$name]['longitude']) && !empty($_POST[$name]['longitude']) ? $_POST[$name]['longitude'] : "");

								if(!empty($latitude)){
									update_post_meta((int)$post_id, "latitude", $latitude);
								}

								if(!empty($longitude)){
									update_post_meta((int)$post_id, "longitude", $longitude);
								}
							}
						}
					}
				}
			}


			if(isset($_POST['verified']) && !empty($_POST['verified'])){
				update_post_meta((int)$post_id, "verified", $_POST['verified']);
			} else {
				delete_post_meta((int)$post_id, "verified" );
			}

			if(isset($_POST['additional_details']) && !empty($_POST['additional_details'])){
				update_post_meta((int)$post_id, "additional_details", $_POST['additional_details']);
			}

			// secondary title
			if(isset($_POST['secondary_title']) && !empty($_POST['secondary_title'])){
				update_post_meta((int)$post_id, "secondary_title", $_POST['secondary_title']);
			}

			// car sold
			$car_sold = (isset($_POST['car_sold']) && !empty($_POST['car_sold']) ? $_POST['car_sold'] : "");
			if(!empty($car_sold)){
				update_post_meta($post_id, "car_sold", $car_sold);
			} else {
				update_post_meta($post_id, "car_sold", 2);
				delete_post_meta($post_id, "car_sold_date");
			}

			// keep track for dependancies
			$new_listing_categories_values = array();

			// additional categories
			$additional_categories = $Listing->get_additional_categories();

			if(!empty($additional_categories)){
				foreach($additional_categories as $category){
					$safe_category = str_replace(" ", "_", strtolower($category));
					$value         = (isset($_POST['additional_categories']['value'][$safe_category]) && !empty($_POST['additional_categories']['value'][$safe_category]) ? $_POST['additional_categories']['value'][$safe_category] : "");

					update_post_meta($post_id, $safe_category, $value);

					$new_listing_categories_values[$safe_category] = array(1 => 1);
				}
			}

			// custom post meta
			$listing_categories = $Listing->get_listing_categories();

			// dont run for trashed posts
			if(!empty($listing_categories) && (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] != "trash") && (isset($_GET['action']) && $_GET['action'] != "untrash"))) {

				foreach ( $listing_categories as $category ) {
					$slug = $category['slug'];

					$value = $org_key = ( isset( $_POST[ $slug ] ) ? $_POST[ $slug ] : "" );

					//if(!empty($value)){
					if ( empty( $value ) && isset($category['is_number']) && $category['is_number'] == 1 ) {
						$value = 0;
					} elseif ( empty( $value ) || $value == "None" ) {
						if($value === 0 || $value === "0"){
							$value = 0;
						} else {
							$value = "";
						}
					}

					// linked values
					$link_value = ( isset( $category['link_value'] ) && ! empty( $category['link_value'] ) ? $category['link_value'] : false );

					if ( isset( $_POST['options'] ) && ! empty( $_POST['options'] ) ) {
						if ( $link_value ) {
							if ( $link_value == "price" ) {
								$value = $_POST['options']['price']['value'];

								update_post_meta( (int) $post_id, $slug, $value ); // because $org_key wont be set
							} else if ( $link_value == "mpg" ) {
								$value = $_POST['options']['city_mpg']['value'] . " " . $default_value_city . " / " . $_POST['options']['highway_mpg']['value'] . " " . $default_value_hwy;

								update_post_meta( (int) $post_id, $slug, $value ); // because $org_key wont be set
							}
						}
					}

					if ( ! empty( $value ) || $value == 0 ) {
						update_post_meta( (int) $post_id, $slug, html_entity_decode($value, ENT_QUOTES) );
						$new_listing_categories_values[$slug] = array($Listing->slugify(html_entity_decode($value, ENT_QUOTES)) => html_entity_decode($value, ENT_QUOTES));
					} elseif($org_key === "None"){
						update_post_meta( (int) $post_id, $slug, $org_key );
					}
				}

				// dont run for trashed posts
				if(!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] != "trash") && (isset($_GET['action']) && $_GET['action'] != "untrash")){
					$Listing->update_dependancy_option($post_id, $new_listing_categories_values, $car_sold);
				}
			}

			// featured
			if($featured_vehicle_widget) {
				$car_featured = ( isset( $_POST['car_featured'] ) && ! empty( $_POST['car_featured'] ) ? $_POST['car_featured'] : "" );

				update_post_meta($post_id, "car_featured", $car_featured);
			}

			// pdf_brochure_input
			if(isset($_POST['pdf_brochure_input'])){
				update_post_meta($post_id, "pdf_brochure_input", $_POST['pdf_brochure_input']);
			}

			$_POST['options']['price']['value']       = (isset($_POST['options']['price']['value']) && !empty($_POST['options']['price']['value']) ? $_POST['options']['price']['value'] : "");
			$_POST['options']['price']['original']    = (isset($_POST['options']['price']['original']) && !empty($_POST['options']['price']['original']) ? $_POST['options']['price']['original'] : "");

			$post_options = (isset($_POST['options']) ? serialize($_POST['options']) : null);

			update_post_meta($post_id, "listing_options", $post_options);

			// woocommerce product association
			if(isset($_POST['woocommerce_integration_id'])){
				update_post_meta($post_id, "woocommerce_integration_id", $_POST['woocommerce_integration_id']);
			}

			if( (isset($_POST['gallery_images']) && !empty($_POST['gallery_images'])) || (isset($_POST['gallery_image_meta']) && $_POST['gallery_image_meta'] == "true") ){
				$save_gallery_images = array();

				if($Listing->is_hotlink()){
					if(!empty($_POST['gallery_images'])) {
						foreach ( $_POST['gallery_images'] as $gallery_image ) {
							if ( filter_var( $gallery_image, FILTER_VALIDATE_URL ) ) {
								$save_gallery_images[] = $gallery_image;
							}
						}
					}
				} else {
					if(!empty($_POST['gallery_images'])) {
						foreach ( $_POST['gallery_images'] as $gallery_image ) {
							$save_gallery_images[] = $gallery_image;
						}
					}
				}

				if(isset($save_gallery_images[0])){
					update_post_meta($post_id, '_thumbnail_id', $save_gallery_images[0] );

					if($Listing->is_yoast_active()) {
						$yoast_image = wp_get_attachment_image_src($save_gallery_images[0], "full");

						// yoast seo images
						update_post_meta( $post_id, '_yoast_wpseo_opengraph-image', $yoast_image[0]);
						update_post_meta( $post_id, '_yoast_wpseo_twitter-image', $yoast_image[0]);
					}
				}

				update_post_meta($post_id, "gallery_images", $save_gallery_images);
			}
		}
	}
}

add_action('post_updated', 'automotive_save_custom_meta', 100, 3);
