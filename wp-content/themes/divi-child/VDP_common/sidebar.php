<?php
function stickyBanner($price, $year, $make, $model, $vin, $stock, $thumbnail = null, $title, $vin_number = '') {
    /** Connect to external database */
    $external_connection = get_db_connection();
    
    /** Get first image for the VIN */
    $select_query = "SELECT vauto_url FROM dmc_images WHERE vin = ? ORDER BY id ASC LIMIT 1";
    $stmt = $external_connection->prepare($select_query);

    $first_image = null;
    if ($stmt) {
        $stmt->bind_param("s", $vin_number);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $first_image = $row['vauto_url'];
        }
        $stmt->close();
    }

    $urlToRemove = 'http://vehicle-photos-published.vauto.com/04/db/a3/0f-009d-4d84-ba0a-fe04a042c1d5/image-1.jpg';
    // Check if $first_image is the placeholder or null
    if ($first_image === $urlToRemove || $first_image === null) {
        $first_image = null; // Clear placeholder
        // Try to find a matching JellyB URL based on VIN number first
        $vin_number = get_post_meta(get_the_ID(), 'vin-number', true);
        if (!empty($vin_number)) {
            $jellyB_img_urls = dmc_get_image_urls();
            $normalized_vin = strtolower(preg_replace('/\s+/', '-', trim($vin_number)));
            foreach ($jellyB_img_urls as $jelly_url) {
                $filename = basename($jelly_url, '.png');
                $normalized_filename = strtolower($filename);
                if (strpos($normalized_filename, $normalized_vin) !== false) {
                    $first_image = $jelly_url; // Use first matching URL
                    break;
                }
            }
        }

        // If no VIN match, proceed with original model check
        if ($first_image === null && !empty($model)) {
            $jellyB_img_urls = dmc_get_image_urls();
            $normalized_model = strtolower(preg_replace('/\s+/', '-', trim($model)));
            foreach ($jellyB_img_urls as $jelly_url) {
                $filename = basename($jelly_url, '.png');
                $filename_parts = explode('--', $filename);
                $filename_model = $filename_parts[0];
                $filename_model = preg_replace('/^\d{4}-/', '', $filename_model);
                $normalized_filename = strtolower($filename_model);
                if (strpos($normalized_filename, $normalized_model) !== false) {
                    $first_image = $jelly_url; // Use first matching URL
                    break;
                }
            }
        }
    }

    // Fetch additional meta fields for pricing
    $post_id = get_the_ID();
    $meta = get_post_meta($post_id);
	$condition = $meta['condition'][0];
	$current_price = !empty($meta['current_price'][0]) ? intval($meta['current_price'][0]) : 0;
	$original_price = !empty($meta['original_price'][0]) ? intval($meta['original_price'][0]) : 0;
	$rawPrice = $meta['miscprice-1'][0] ?? 0;
	$rebate = !empty($meta['disposition'][0]) ? intval($meta['disposition'][0]) : 0;
	if ($original_price === 0 && $current_price !== 0) {
		$original_price = $current_price;
	}
	if ( strtolower($meta['make'][0]) !== 'toyota' && $meta['condition'][0] === 'N') {
		if( ! empty( intval(str_replace(',', '', $rawPrice)) ) ) {
			$rebate = $original_price - intval(str_replace(',', '', $rawPrice));
		}
	}
	
	$dealer_discount = ($current_price > 0 && $original_price > 0) ? ($current_price - $original_price) : 0;
	
    if ($original_price === 0 && $current_price !== 0) {
        $original_price = $current_price;
    }
    $vehiclePrice = is_numeric($rawPrice) ? number_format((int) $rawPrice, 0) : '0';
//     $vehiclePriceHTML = $vehiclePrice !== '0' 
//         ? '<p class="sticky-form-price p-0 font-helvetica font-lg">$ ' . esc_html($vehiclePrice) . '</p>' 
//         : '<a class="font-sm text-grey-3 font-weight-bold font-helvetica" href="tel:' . esc_attr(salesPhoneNumber()) . '">Call For Price</a>';

// 	$vehiclePriceHTML = $vehiclePrice !== '0' 
// 		? '<h3 class="p-0 m-0 font-helvetica font-20 font-weight-bold text-grey-3">$ ' . esc_html($vehiclePrice) . '</h3>' 
// 		: '<h3 class="p-0 m-0 font-helvetica font-20 font-weight-bold text-grey-3">$ ' . number_format((int) $original_price) . '</h3>';
	
	$vehiclePriceHTML = $vehiclePrice !== '0' 
        ? '<p class="sticky-form-price p-0 font-helvetica font-lg">$ ' . esc_html($vehiclePrice) . '</p>' 
        : '<p class="sticky-form-price p-0 font-helvetica font-lg">$ ' . number_format((int) $original_price) . '</p>';
	$is_toyota = strtolower($meta['make'][0]) === 'toyota' && $meta['condition'][0] === 'N';
    
    echo '<div class="sticky-lead-form bg-white">
            <div class="px-20 pt-30">
                <div class="vehicle-card-info row mb-30 d-none">
                    <div class="col-5">';
                    
                    echo '<img data-src="'. esc_url($first_image) .'" loading="lazy" decoding="async" width="200" height="200" title="'. esc_attr( $make ) .'" alt="'. esc_attr( $make ) .'" class="w-100 img-fluid h-auto" />';
                    
    echo    '</div>
                    <div class="col-7">
                    <h2 class="p-0 mb-1 font-inter font-20" style="color: #007CC0;">'.$title.'</h2>
                    <span class="font-inter text-capitalize font-20 text-grey-3">Stock #: '.$stock.'</span>
                    </div>
                </div>';
                if ($current_price) {
                    echo '<div class="d-flex align-items-center justify-content-between pb-1 sidebarpriceinfo">
                            <p class="font-weight-bold sticky-form-price p-0 text-uppercase d-flex align-items-center justify-content-start font-inter">MSRP</p>
                            <p class="sticky-form-price p-0 font-helvetica font-lg">$ ' . esc_html(number_format($current_price)) . '</p>
                          </div>';
                }
	
                if ( ! empty( $dealer_discount ) && $dealer_discount > 0 && ( (int) $rawPrice !== 0 || (int) $original_price !== 0 ) ) {
					if ( $is_toyota ) {
						$dealer_discount = $meta['miscprice-2'][0] ?? 0;
					}
                    echo '<div class="d-flex align-items-center justify-content-between pb-1 sidebarpriceinfo">
                            <p class="font-weight-bold sticky-form-price p-0 text-uppercase d-flex align-items-center justify-content-start font-inter">Dealer Discount</p>
                            <p class="sticky-form-price p-0 font-helvetica font-lg">-$ ' . esc_html(number_format((int) $dealer_discount)) . '</p>
                          </div>';
                }
	
                if ($original_price) {
                    echo '<div class="d-flex align-items-center justify-content-between pb-1 sidebarpriceinfo">
                            <p class="font-weight-bold sticky-form-price p-0 text-uppercase d-flex align-items-center justify-content-start font-inter">Total Price</p>
                            <p class="sticky-form-price p-0 font-helvetica font-lg">$ ' . esc_html(number_format((int) $original_price)) . '</p>
                          </div>';
                }
                if (! empty( $rebate ) && (int) $rebate > 0) {
                    echo '<div class="d-flex align-items-center justify-content-between pb-1 sidebarpriceinfo">
                            <p class="font-weight-bold sticky-form-price p-0 text-uppercase d-flex align-items-center justify-content-start font-inter">Rebate</p>
                            <p class="sticky-form-price p-0 font-helvetica font-lg">-$ ' . esc_html(number_format((int) $rebate)) . '</p>
                          </div>';
                }
	
              echo '<div class="d-flex align-items-center justify-content-between mb-4 mt-2 pb-3 sidebarpriceinfo">
    <p class="font-weight-bold sticky-form-price p-0 font-lg text-uppercase d-flex align-items-center justify-content-start font-inter">
        <span class="icon-details-tag font-30 text-fourth mr-2 pr-1"></span>Our Best Price</p>';

if ( $is_toyota && ! empty( $meta['disposition'][0] ) ) {
    $original_price = (int) $original_price - (int) $meta['disposition'][0];
    echo '<span class="sticky-form-price p-0 font-helvetica font-lg">$ ' . esc_html(number_format((int)$original_price)) . '</span>';
} elseif ( $is_toyota ) {
    echo '<span class="sticky-form-price p-0 font-helvetica font-lg">$ ' . esc_html(number_format((int)$original_price)) . '</span>';
} else {
    echo $vehiclePriceHTML;
}

echo '</div>';

                echo '<button class="sticky-lead-form-cta-btn w-100 p-15 text-capitalize font-inter font-20 mb-30 upgrade-vehicle-active-hidden-elem sticky-lead-form-cta-non-invert sidebar-popup-trigger"
                    data-popup="sticky-cta" data-year="' . $year . '" data-make="' . $make . '" data-model="' . $model . '" data-vin="' . $vin . '"
                    data-stock="' . $stock . '">schedule test drive</button>
                <button class="sticky-lead-form-cta-btn sticky-lead-form-invert w-100 p-15 text-capitalize font-inter font-20 mb-30 sticky-lead-form-cta-non-invert sidebar-popup-trigger"
                    data-popup="sticky-cta" data-year="' . $year . '" data-make="' . $make . '" data-model="' . $model . '" data-vin="' . $vin . '"
                    data-stock="' . $stock . '">check for availability</button>
                <div class="row mb-4 pb-3 upgrade-vehicle-active-hidden-elem">
                    <div class="col-6">
                        <button class="sticky-lead-form-cta-btn sticky-lead-form-invert py-2 sidebar-popup-trigger w-100 d-none align-items-center flex-column font-20 font-inter" data-popup="sticky-cta" data-year="' . $year . '" data-make="' . $make . '" data-model="' . $model . '" data-vin="' . $vin . '" data-stock="' . $stock . '">
                            <span class="fa fa-plus font-lg p-1 d-flex align-items-center justify-content-center rounded-circle mb-1 text-white"></span>
                            <p class="text-capitalize">
                                More
                            </p>
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="sticky-lead-form-cta-btn sticky-lead-form-invert py-2 sidebar-popup-trigger w-100 d-none align-items-center flex-column font-20 font-inter" data-popup="guest-request-text" data-year="' . $year . '" data-make="' . $make . '" data-model="' . $model . '" data-vin="' . $vin . '" data-stock="' . $stock . '">
                            <span class="icon-icon-sms mb-1 font-xxl"></span>
                            <p class="text-capitalize">Text</p>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-top py-15 mt-15 upgrade-vehicle-active-hidden-elem">
                <p class="text-center font-lg font-inter text-primary">
    We&#39;re here to help
    ' . (
        strtolower($make) === "ford" && strtolower($condition) === 'n'
            ? '<a class="font-weight-bold font-inter text-primary" href="tel:9708807204">(970) 880-7204</a>'
            : '<a class="font-weight-bold font-inter text-primary" href="tel:8444962224">(844) 496-2224</a>'
    ) . '
</p>
            </div>
        </div>';
}
    function upgradeVehicle($make, $thumbnail, $vehicleID) {
        $vehicle = '<div class="upgrade-vehicle recommend-vehicle-active p-20 bg-grey-8 mt-30 d-none">'.
                    '<div class="upgrade-vehicle-header d-flex align-items-center justify-content-between">'.
                    '<h3 class="d-flex align-items-center font-inter m-0 text-primary font-xxl p-0 font-weight-bold"><i class="fa-regular fa-star mr-2"></i>Upgrade Your Drive</h3>'.
                    '<i class="fa-solid fa-xmark text-primary font-xxl close-upgradeVehicle"></i>'.
                    '</div>'.
                    '<div class="vehicle-tabs-pills d-flex align-items-center justify-content-between mt-20 mb-20">'.
                    '<span class="vehicle-tab-pill text-sixth text-decoration-underline font-inter font-lg cursor-pointer font-weight-bold pr-2" data-target="recommended-vehicles">Recommended</span>'.
                    '<span class="tabs-pills-divider"></span>'.
                    '<span class="vehicle-tab-pill text-sixth font-inter font-lg cursor-pointer font-weight-light pr-2 pl-2" data-target="recently-viewed">Recently Viewed</span>'.
                    '<span class="tabs-pills-divider"></span>'.
                    '<span class="vehicle-tab-pill text-sixth font-inter font-lg cursor-pointer font-weight-light" data-target="liked-vehicles">Likes</span>'.
                    '</div>'.
                    '<div class="vehicle-tabs-boxes bg-white py-15 px-30">';
        $vehicle .= recommendedVehicles($make);
        $vehicle .= recentlyViewedVehicles($make);
        $vehicle .= LikedVehicles($make);
        $vehicle .= '</div>';
        $vehicle .= beyondValueAccordion($make);
        $vehicle .= productProtectionAccordion();
        $vehicle .= userCompareVehicles($make, $thumbnail, $vehicleID);
        $vehicle .= userTopSearchQueries();
        $vehicle .= myGarageTab();
        $vehicle .= durango_recommended_vehicle_box($make);
        $vehicle .='</div>'.
        '</div>';

        echo $vehicle;
    }
    function recommendedVehicles($make) {
        $recommendVehicles = '<div class="recently-viewed upgrade-vehicle-tab d-block" id="recommended-vehicles" data-paged="2">'.
                         '<h3 class="text-primary font-inter font-xl font-weight-normal p-0 mb-30">Recommended Vehicles For You</h3>'.
                         '<div class="recommendations-vehicles-wrapper">';
                         $args = array(
                            'post_type' => 'listings',
                            'posts_per_page' => '5',
                            'orderby' => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => 'make',
                                    'value' => $make,
                                    'compare' => '=',
                                ),
                            ),
                        );
                        $cardQuery =  new WP_Query($args);
                        if( $cardQuery->have_posts() ) {
                            while($cardQuery->have_posts()) {
                                $cardQuery->the_post();
                                $recommendVehicles .= vehicleCardLayout('recommendVehicles');
                            };
                            wp_reset_postdata();
                        }else {
                            $recommendVehicles .= 'Sorry no posts found';
                        }
        $recommendVehicles .= '</div>';
        $recommendVehicles .= '<div>'.
                               '<button class="text-white btn btn-primary w-100 font-inter font-lg load-more-recommendations" data-make="'.$make.'" style="text-decoration:underline;">See More Vehicles</button>'.
                               '</div>';
        $recommendVehicles .= '</div>';

        return $recommendVehicles;
    }
    function recentlyViewedVehicles($make) {
        $recentlyViewed = '<div class="recently-viewed upgrade-vehicle-tab d-none" id="recently-viewed">'.
                         '<h3 class="text-primary font-inter font-xxl font-weight-normal p-0 mb-30">Recently Viewed</h3>';
                         $table_name = accessWPDB()->prefix . 'user_recently_viewed';
                         $recentQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
                         $updateResult = accessWPDB()->get_row($recentQuery, ARRAY_A);
                         if( !$updateResult ) {
                            $recentListingsIDs = array(0);
                         }else {
                             $recentListingsIDs = !empty($updateResult['recent_view_vehicles']) ? unserialize($updateResult['recent_view_vehicles']) : array(0);
                         }

                         $args = array(
                            'post_type' => 'listings',
                            'posts_per_page' => -1,
                            'post__in' => $recentListingsIDs,
                        );
                        $cardQuery =  new WP_Query($args);
                        if( $cardQuery->have_posts() ) {
                            while($cardQuery->have_posts()) {
                                $cardQuery->the_post();
                                $recentlyViewed .= vehicleCardLayout('recentlyViewed', null, null, null, null, null, get_the_ID());
                            };
                            wp_reset_postdata();
                        }else {
                            $recentlyViewed .= '<p class="no-recent-vehicles-found">Sorry no posts found</p>';
                        }
        $recentlyViewed .= '</div>';

        return $recentlyViewed;
    }
    function LikedVehicles($make) {
        $likedVehicles = '<div class="recently-viewed upgrade-vehicle-tab d-none" id="liked-vehicles">'.
                         '<h3 class="text-primary font-inter font-xxl font-weight-normal p-0 mb-30">Your Liked Vehicles</h3>';
                         $table_name = accessWPDB()->prefix . 'user_liked_vehicles';
                         $recentQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
                         $updateResult = accessWPDB()->get_row($recentQuery, ARRAY_A);
                         if( !$updateResult ) {
                            $likedListingsIDs = array(0);
                         }else {
                             $likedListingsIDs = !empty($updateResult['user_liked_vehicles']) ? unserialize($updateResult['user_liked_vehicles']) : array(0);
                         }

                         $args = array(
                            'post_type' => 'listings',
                            'posts_per_page' => -1,
                            'post__in' => $likedListingsIDs,
                        );
                        $likedQuery =  new WP_Query($args);
                        if( $likedQuery->have_posts() ) {
                            while($likedQuery->have_posts()) {
                                $likedQuery->the_post();
                                $likedVehicles .= vehicleCardLayout('likedVehicles', 
                                get_post_meta(get_the_ID(), 'make', true),
                                get_post_meta(get_the_ID(), 'year', true),
                                get_post_meta(get_the_ID(), 'model', true),
                                get_post_meta(get_the_ID(), 'vin-number', true),
                                get_post_meta(get_the_ID(), 'stock-number', true),
                            get_the_ID());
                            };
                            wp_reset_postdata();
                        }else {
                            $likedVehicles .= '<p class="no-liked-vehicles-found">Sorry no liked posts found</p>';
                        }
        $likedVehicles .= '</div>';

        return $likedVehicles;
    }
    // Recommended vehicles card
    function durango_recommended_vehicle_box($make) {
        $args = array(
            'post_type' => 'listings',
            'posts_per_page' => 5,
            'orderby' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'make',
                    'value' => $make,
                    'compare' => '=',
                ),
            ),
        );
        $recommendQuery = new WP_Query($args);

        $recommendVehicles = '<div class="mt-30 bg-white py-15 px-30 seperate-recommended-vehicles-card">'.
        '<h3 class="mb-0 pb-20 font-weight-bold text-primary font-xxl font-inter font-weight-normal">Recommended vehicles for you</h3>';
        if($recommendQuery->have_posts()) {
            $recommendVehicles .= '<div class="recommend-vehicles-wrapper" data-paged="2">';
            while($recommendQuery->have_posts()) {
                $recommendQuery->the_post();
                $recommendVehicles .= vehicleCardLayout('recommendVehicles');
            }
            $recommendVehicles .= '</div>';
            wp_reset_postdata();
        }else {
            $recommendVehicles .= '<p>Sorry, no posts found</p>';
        }
        $recommendVehicles .= '</div>'.
        '<button class="btn w-100 mt-20 border text-primary font-weight-bold text-decoration-underline bg-white text-capitalize font-segoe font-lg text-link load-more-recommendations" data-make="'.$make.'">See More Vehicles</button>';

        return $recommendVehicles;
    }

    function vehicleCardLayout($tab = '', $make = '', $year = '', $model = '', $vin = '', $stock ='', $ID = '') {
		
		if( empty( $vin ) ) {
			$vin = get_post_meta( get_the_ID(), 'vin-number', true );
		}
		/** Connect to external database */
		$external_connection 	= get_db_connection();
		$select_query			= "SELECT vauto_url FROM dmc_images WHERE vin = ?";
		$stmt					= $external_connection->prepare( $select_query );

		$first_image	= null;
		if( $stmt ) {
			$stmt->bind_param( "s", $vin );
			$stmt->execute();
			$result			= $stmt->get_result();
			if ($row = $result->fetch_assoc()) {
				$first_image = $row['vauto_url'];
			}

			$stmt->close();
		}

		$urlToRemove = 'http://vehicle-photos-published.vauto.com/04/db/a3/0f-009d-4d84-ba0a-fe04a042c1d5/image-1.jpg';
		// Check if $first_image is the placeholder or empty
		if ($first_image === $urlToRemove || $first_image === null) {
			$first_image = null; // Clear placeholder
			// Use provided $model or fetch from post meta
			$model_use = !empty($model) ? $model : get_post_meta(get_the_ID(), 'model', true);
			if (!empty($model_use)) {
				$jellyB_img_urls = dmc_get_image_urls();
				$normalized_model = strtolower(preg_replace('/\s+/', '-', trim($model_use)));
				foreach ($jellyB_img_urls as $jelly_url) {
					$filename = basename($jelly_url, '.png');
					$filename_parts = explode('--', $filename);
					$filename_model = $filename_parts[0];
					$filename_model = preg_replace('/^\d{4}-/', '', $filename_model);
					$normalized_filename = strtolower($filename_model);
					if (strpos($normalized_filename, $normalized_model) !== false) {
						$first_image = $jelly_url; // Use first matching URL
						break;
					}
				}
			}
		}
		
        $card = '<div data-vin="'. $vin .'" class="d-flex justify-content-start mb-30 position-relative '.($tab === 'likedVehicles' ? 'liked-vehicle-card' : 'pb-2').' '.($tab === "recentlyViewed" ? "recent-view-vehicle-card" : "").' " data-id="'.get_the_ID().'">' .
                '<div class="vehicle-thumbnail mr-30 position-relative">' ;
        $card .= '<a href="'.get_the_permalink().'" class="d-inline-block h-100">';
        $gallery_images = get_post_meta(get_the_ID(), 'gallery_images', true);
        $thumbnailID = (is_array($gallery_images) && !empty($gallery_images)) ? $gallery_images[0] : '';
        $thumbnailInfo = getImageSizeInfo($thumbnailID);
        $width = (is_array($thumbnailInfo) && isset($thumbnailInfo['width'])) ? $thumbnailInfo['width'] : '';
        $height = (is_array($thumbnailInfo) && isset($thumbnailInfo['height'])) ? $thumbnailInfo['height'] : '';
        $card .= '<img src="'. esc_url( $first_image ) .'" alt="'.get_the_title().'" width="'.$width.'" height="'.$height.'" loading="lazy" decoding="async" />'.
                '</a>';
                
        if( $tab === 'likedVehicles' ) {
            $card .= '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/icon-vehicle-liked.png" alt="vehicle liked" itemprop="image" 
            class="position-absolute vehicle-liked-icon"/>';
        }
        $card .='</div>' .
                '<div class="vehicle-content">'.
                '<h3 class="p-0 mb-10">';
        $card .= '<a href="'.get_the_permalink().'" class="font-inter font-20 text-primary d-inline-block">'.get_the_title().'</a>';
        $card .= '</h3>';
        $card .= '<p class="mb-1 text-grey-6 font-inter font-lg p-0">Stock #: '. get_post_meta(get_the_ID(), 'stock-number', true) .'</p>';
        $listingPrice = number_format((int) get_post_meta(get_the_ID(), 'original_price', true));
        if( empty(trim($listingPrice)) || $listingPrice === 'None' || !isset($listingPrice) ) {
            $card .= '<a class="font-inter font-md text-grey-6" href="tel:'.salesPhoneNumber().'"><i class="fa fa-phone"></i>Call For Price</a>';
        }else {
            $card .= '<p class="m-0 text-grey-6 font-inter font-lg p-0">$ '. number_format(get_post_meta(get_the_ID(), 'original_price', true)) .'</p>';
        }
        if( $tab === 'recentlyViewed' || $tab === 'likedVehicles' ) {
            $card .= '<span data-id="'.$ID.'" class="'.($tab === 'recentlyViewed' ? 'remove-recent-view' : 'remove-liked-view').' text-primary font-sm text-link cursor-pointer font-inter font-weight-light position-absolute">Remove</span>';
        }
        $card .= '</div>';
                if( $tab === 'likedVehicles' ) {
                    $card .= '<button
					class="position-absolute liked-vehicle-availability w-100 btn btn-grey-9
					text-primary font-inter sidebar-popup-trigger"
                    data-popup="sticky-cta"
					data-price="'.number_format(get_post_meta(get_the_ID(), 'original_price' ,true)).'"
					data-year="'.$year.'"
					data-vin="'.$vin.'"
					data-stock="'.$year.'"
					data-make="'.$make.'"
					data-model="'.$model.'"
                    data-thumbnail="'. esc_url( $first_image ) .'">Check Availability</button>'; 
                }
        $card .= '</div>';

        return $card;
    }
    function beyondValueAccordion($make) {
        $beyondValue = '<div class="beyondValueAccordion mt-30" id="beyondValueAccordion">'.
                        '<div class="accordion">'.
                        '<div class="card rounded-0">'.
                        '<div class="card-header border-bottom-0 cursor-pointer py-20 px-30 bg-white d-flex align-items-center 
                        justify-content-between sidebar-accordion-header" id="beyondValueHeader" data-toggle="collapse" data-target="#beeyondValueCollapse"
                        aria-expanded="false" aria-controls="beeyondValueCollapse">'.
                        '<h2 class="mb-0 p-0 text-primary font-xxl font-inter font-weight-normal">Research: Beyond Value</h2>'.
                        '<i class="fa-solid fa-chevron-down text-primary font-xxl chevron-icon"></i>'.
                        '</div>'.
                        '<div id="beeyondValueCollapse" class="collapse" aria-labelledby="beyondValueHeader" data-parent="#beyondValueAccordion">'.
                        '<div class="card-body py-0 px-30">';
                        $contextualVehiclesRow = get_field('contextual_page_vehicles_row','options');
                        if( $contextualVehiclesRow ) {
                        foreach( $contextualVehiclesRow as $contextualRow ) {
                            $vehicleGroup = $contextualRow['contextual_page_vehicles_type_group'];
                            $vehicleGroupHeading = $vehicleGroup['contextual_page_vehicle_type_heading'] && !empty($vehicleGroup['contextual_page_vehicle_type_heading']) ? $vehicleGroup['contextual_page_vehicle_type_heading'] : null ;
                            $vehicleGroupRow = $vehicleGroup['contextual_page_vehicle_types_row'];
                            if( $vehicleGroupHeading && $vehicleGroupRow ) {
                            foreach( $vehicleGroupRow as $row ) {
                              $rowGroup = $row['contextual_page_vehicle_type_group'];
                              $image = wp_get_attachment_image_src($rowGroup['contextual_vehicle_type_image'], 'full');
                              if( $image ) {
                                $image = $image[0];
                                $imageWidth = $image[1];
                                $imageHeight = $image[2];
                                $imageAlt = get_post_meta($rowGroup['contextual_vehicle_type_image'], '_wp_attachment_image_alt', true);
                                $imageHeading = $rowGroup['contextual_vehicle_type_name'];
                                $beyondValue .= '<div class="d-flex mb-30">'.
                                '<a class="d-inline-block accordion-card-thumbnail mr-30" href="'.site_url().'/beyond-value-listing?post='.$imageHeading.'">'.
                                '<img src="'.$image.'" alt="'.$imageAlt.'" width="'.$imageWidth.'" height="'.$imageHeight.'" class="img-fluid w-100" loading="lazy" itemprop="image" />'.
                                '</a>'.
                                '<div>'.
                                '<a class="font-inter text-primary font-md font-weight-normal mb-2 d-block" href="'.site_url().'/beyond-value-listing?post='.$imageHeading.'">'.$imageHeading.'</a>'.
                                '<a class="font-inter text-grey-5 font-md font-weight-normal d-block text-link" href="'.site_url().'/beyond-value-listing?post='.$imageHeading.'">View</a>'.
                                '</div>'. 
                                '</div>';
                              }
                            }
                          }
                        }
                      }
            $beyondValue .= '</div>'.
                        '</div>'.
                        '</div>'.
                        '</div>'.
                        '</div>';

        return $beyondValue;
    }
    function productProtectionAccordion() {
        $productProtection = '<div class="productProtectionAccordion mt-30" id="productProtectionAccordion">'.
                        '<div class="accordion">'.
                        '<div class="card rounded-0">'.
                        '<div class="card-header sidebar-accordion-header border-bottom-0 cursor-pointer py-20 px-30 bg-white d-flex
                        align-items-center justify-content-between" id="productProtectionHeader" data-toggle="collapse" 
                        data-target="#productProtectionCollapse" aria-expanded="false" aria-controls="productProtectionCollapse">'.
                        '<h2 class="mb-0 p-0 text-primary font-xxl font-inter font-weight-normal">Product Protection</h2>'.
                        '<i class="fa-solid fa-chevron-down text-primary font-xxl chevron-icon"></i>'.
                        '</div>'.
                        '<div id="productProtectionCollapse" class="collapse" aria-labelledby="productProtectionHeader" data-parent="#productProtectionAccordion">'.
                        '<div class="card-body px-30 py-0">';
		$productProtection .= '<a class="mb-30 d-flex" href="https://durangovalueautos.com/finance/tire-and-wheel-bundle/" target="_blank">'.
			'<div class="accordion-card-thumbnail mr-30">'.
			'<img class="w-100 h-100 img-fluid" src="https://production.durangocarrentals.com/wp-content/uploads/2025/03/tire-and-wheel-bundle-packages.webp" alt="Tire and wheel" width="139" height="77" itemprop="image" />'.
			'</div>'.
			'<div>'.
			'<h3 class="text-primary font-md font-inter font-weight-normal mb-30 p-0">Tire & Wheel Bundle</h3>'.
			'<span class="text-grey-5 font-md font-inter font-weight-normal m-0 p-0">CNA</span>'.
			'</div>'.
			'</a>';
		$productProtection .= '<a class="mb-30 d-flex" href="https://durangovalueautos.com/finance/tire-and-wheel-bundle/" target="_blank">'.
			'<div class="accordion-card-thumbnail mr-30">'.
			'<img class="w-100 h-100 img-fluid" src="https://production.durangocarrentals.com/wp-content/uploads/2025/03/cilajet-appearance-car-card.webp" alt="Cilajet Appearance" width="139" height="77" itemprop="image" />'.
			'</div>'.
			'<div>'.
			'<h3 class="text-primary font-md font-inter font-weight-normal mb-30 p-0">Cilajet Appearance</h3>'.
			'<span class="text-grey-5 font-md font-inter font-weight-normal m-0 p-0">Cilajet</span>'.
			'</div>'.
			'</a>';
		$productProtection .= '<a class="mb-30 d-flex" href="https://durangovalueautos.com/finance/tire-and-wheel-bundle/" target="_blank">'.
			'<div class="accordion-card-thumbnail mr-30">'.
			'<img class="w-100 h-100 img-fluid" src="https://production.durangocarrentals.com/wp-content/uploads/2025/03/guaranteed-protection-car.webp" alt="Guaranteed assets protection" width="139" height="77" itemprop="image" />'.
			'</div>'.
			'<div>'.
			'<h3 class="text-primary font-md font-inter font-weight-normal mb-30 p-0">Guaranteed Asset Protection</h3>'.
			'<span class="text-grey-5 font-md font-inter font-weight-normal m-0 p-0">CNA</span>'.
			'</div>'.
			'</a>';
        $productProtection .= '</div>'.
                        '</div>'.
                        '</div>'.
                        '</div></div>';

        return $productProtection;
    }
    function serviceAccessoriesAccordion() {
        $serviceAccessories = '<div class="serviceAccessoriesAccordion mt-30" id="serviceAccessoriesAccordion">'.
                        '<div class="accordion">'.
                        '<div class="card rounded-0">'.
                        '<div class="card-header border-bottom-0 sidebar-accordion-header cursor-pointer py-20 px-30 bg-white d-flex 
                        align-items-center justify-content-between" id="serviceAccessoriesHeader" data-toggle="collapse" 
                        data-target="#serviceAccessoriesCollapse" aria-expanded="false" aria-controls="serviceAccessoriesCollapse">'.
                        '<h2 class="mb-0 p-0 text-primary font-xxl font-inter font-weight-normal">Service & Accessories Discounts</h2>'.
                        '<i class="fa-solid fa-chevron-down text-primary font-xxl chevron-icon"></i>'.
                        '</div>'.
                        '<div id="serviceAccessoriesCollapse" class="collapse" aria-labelledby="serviceAccessoriesHeader" data-parent="#serviceAccessoriesAccordion">'.
                        '<div class="card-body p-30">'.
                        '<h1>Service accessories data</h1>'.
                        '</div>'.
                        '</div>'.
                        '</div>'.
                        '</div>'.
                        '<div class="p-15 bg-white see-more-service-accessories d-flex align-items-center justify-content-center mt-30">'.
                        '<span class="font-20 font-inter text-primary font-weight-bold text-link cursor-pointer" style="text-decoration:underline;">See More Service Coupons</span>'.
                        '</div>'.
                        '</div>';

        return $serviceAccessories;
    }
    function userTopSearchQueries() {
        $queries = '<div class="mt-30 search-query-container bg-white py-15 px-30">'.
                   '<h3 class="mb-0 pb-20 font-weight-bold text-primary font-xxl font-inter font-weight-normal">Your Top Searches</h3>';
        $queries .= do_shortcode('[mwtsa_display_latest_searches unit="month" count="8" only_with_results="false"
                    wrapper_class="top-searches-container mwtsa-latest-searches"]');
        $queries .= '</div>';

        return $queries;
    }
    function userCompareVehicles($make, $thumbnail, $vehicleID) {
        $table_name = accessWPDB()->prefix . 'user_compared_vehicles';
        $comparedQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
        $updateResult = accessWPDB()->get_row($comparedQuery, ARRAY_A);
        if( !$updateResult ) {
            $user_compared_vehicles = array(0);
        }else {
            $user_compared_vehicles = unserialize($updateResult['user_compared_vehicles']);
        }
        
        $compare = '<div class="mt-30 compare-box-container bg-white py-15 px-30">'.
                   '<h3 class="mb-0 pb-20 font-weight-bold text-primary font-xxl font-inter font-weight-normal">Compare</h3>'.
                    '<div class="compare-body"></div>';
        if( count($user_compared_vehicles) >= 2 ) {
            $compare .= '<div class="compare-btn">'.
            '<a class="VDP_vehicles_compare btn btn-primary w-100 font-inter font-weight-light font-lg d-block text-white">COMPARE VEHICLES</a>'.
            '</div>';
        }
        $compare .= '</div>';

        return $compare;
    }
    function myGarageTab() {
        $garage = '<div class="mt-30 bg-white py-15 px-30 my-garage-tab-wrapper">'.
        '<h3 class="mb-0 pb-20 font-weight-bold text-primary font-xxl font-inter font-weight-normal">My Garage</h3>';
         $garage .= '<div class="garage-response-wrapper" data-paged="1">';
         $garage .= do_shortcode('[contact-form-7 id="0812027" title="My Garage Form"]');
         $garage .= '</div>';
         $garage .= '<button class="btn btn-primary w-100 load-more-garage-results d-none">Load More</button>';
         $garage .= '</div>';

         return $garage;

    }