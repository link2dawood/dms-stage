<?php
function productCard() {
    $post_id = get_the_ID();
    $transient_key = 'product_card_' . $post_id;
    
    // Try to get cached card first
    if ($card = get_transient($transient_key)) {
//         return $card;
    }

    // Start output buffering
    ob_start();

    // Get all data needed for the card
    $managerSpecialsStocks = array_map('trim', explode(',', get_field('managers_specials_vehicles_stock_number', 'options')));
    $windowSticker = get_field('carfax_badge_image_group', 'options');
    $velocityEngage = get_field('velocity_engage_badge_image', 'options');
    $windowEngageIcon = get_field('window_sticker_badge_image', 'options');
	$certified_toyota_badge = get_field( 'certified_toyota_vehicle_badge_image', 'option' );
	$certified_ford_badge = get_field( 'certified_ford_vehicle_badge_image', 'option' );
	$certified_kia_badge = get_field( 'certified_kia_vehicle_badge_image', 'option' );
	$ford_blue_gold = get_field( 'ford_blue_advantage_gold_certified', 'option' );
	$ford_blue_blue = get_field( 'ford_blue_advantage_blue_certified', 'option' );
	$ford_blue_ev = get_field( 'ford_blue_advantage_ev_certified', 'options' );
	// $toyota_gold_certified = get_field( 'toyota_gold_certified', 'options' );
	// $ford_gold_certified = get_field( 'ford_gold_certified', 'options' );
	$gold_check_certified = get_field( 'gold_check_certified', 'option' );
	
    $windowStickerInfo = getImageSizeInfo($windowSticker);
    $velocityEngageInfo = getImageSizeInfo($velocityEngage);
    $windowEngageInfo = getImageSizeInfo($windowEngageIcon);
	$certified_toyota_info = getImageSizeInfo($certified_toyota_badge);
	$certified_kia_info = getImageSizeInfo($certified_kia_badge);
	$certified_blue_gold_info = getImageSizeInfo($ford_blue_gold);
	$certified_blue_blue_info = getImageSizeInfo($ford_blue_blue);
	$certified_blue_ev = getImageSizeInfo($ford_blue_ev);
	$gold_check_certified = getImageSizeInfo( $gold_check_certified );
	

    // Get user-specific data
    $user_ip = getUserIP();
    $db = accessWPDB();
    
    // Recently viewed vehicles
    $recently_viewed_table = $db->prefix . 'user_recently_viewed';
    $recentViewedResult = $db->get_row($db->prepare("SELECT * FROM $recently_viewed_table WHERE user_ip = %s", $user_ip), ARRAY_A);
    $recentListingsIDs = $recentViewedResult && !empty($recentViewedResult['recent_view_vehicles']) ? 
        unserialize($recentViewedResult['recent_view_vehicles']) : array(0);

    // Liked vehicles
    $likedVehiclesTable = $db->prefix . 'user_liked_vehicles';
    $likedVehiclesResult = $db->get_row($db->prepare("SELECT * FROM $likedVehiclesTable WHERE user_ip = %s", $user_ip), ARRAY_A);
    $likedVehicleIDs = $likedVehiclesResult && !empty($likedVehiclesResult['user_liked_vehicles']) ? 
        unserialize($likedVehiclesResult['user_liked_vehicles']) : array(0);

    // Compared vehicles
    $compareVehiclesTable = $db->prefix . 'user_compared_vehicles';
    $compareVehiclesResult = $db->get_row($db->prepare("SELECT * FROM $compareVehiclesTable WHERE user_ip = %s", $user_ip), ARRAY_A);
    $compareVehicleIDs = $compareVehiclesResult && !empty($compareVehiclesResult['user_compared_vehicles']) ? 
        unserialize($compareVehiclesResult['user_compared_vehicles']) : array(0);

    $checked = in_array($post_id, $compareVehicleIDs) ? 'checked' : '';
    
    // Vehicle price
    $vehiclePrice = (int)get_post_meta($post_id, 'original_price', true);
    $vehiclePriceHTML = $vehiclePrice ? 
        '<h3 class="p-0 m-0 font-helvetica font-lg font-weight-bold text-dark">$ '.number_format($vehiclePrice).'</h3>' : 
        '<a class="font-sm text-dark font-weight-bold font-helvetica" href="tel:'.salesPhoneNumber().'">Call For Price</a>';

    // Vehicle metadata
    $stock_number = get_post_meta($post_id, 'stock-number', true);
    $vin = get_post_meta($post_id, 'vin-number', true);
	$certification = get_post_meta( $post_id, 'certification', true );
    $permalink = esc_url(get_the_permalink());
    $title = esc_html(get_the_title());
    $isManagerSpecial = in_array($stock_number, $managerSpecialsStocks);

    // Get vehicle images
    $image_urls = array();
    $connection = get_db_connection();
    $query = "SELECT vauto_url FROM dmc_images WHERE vin = ?";
    $stmt = $connection->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("s", $vin);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['vauto_url'])) {
                $image_urls[] = $row['vauto_url'];
            }
        }
        $stmt->close();
        
        if (count($image_urls) >= 5) {
            $image_urls = array_slice($image_urls, 0, 5);
        }
    }

    // Start building the card
    ?>
    <div class="col-12 col-lg-6 col-xl-4 col-xxl-3 mb-30" data-window="<?= $windowEngageIcon ?>">
        <div class="position-relative mb-3 mb-md-0 bg-white listing-card-wrapper" data-permalink="<?= $permalink ?>">
            <div class="card-image-wrapper overflow-hidden">
                
				<?php if( ! empty( $meta['stock-number'][0] ) && in_array( $meta['stock-number'][0], $managerSpecialsStocks ) ) : ?>
				<h3 class="px-10 py-10 manager-specials-card mb-10 text-white text-center
						   font-helvetica font-lg font-weight-bold">
					<?php echo esc_html( 'Manager Specials' ); ?>
				</h3>
				<?php endif; ?>

				<?php if( ! get_post_meta( $post_id, 'stock-number', true ) !== $meta['stock-number'][0] ) : ?>
				<h3 class="px-10 py-10 m-0 text-center text-dark font-helvetica font-lg
						   font-weight-bold fake-recent-view-badge" style="height: 40px;"></h3>
				<?php endif; ?>
                
                <div class="listing-image-slider overflow-hidden position-relative pre-owned-listing-image-slider">
                    <div class="listing-image-slider-inner h-100 w-100 mb-4 px-10">
                        <?php if (!empty($image_urls)): ?>
                            <?php foreach ($image_urls as $image_url): ?>
                                <a href="<?= $permalink ?>" class="d-inline-block h-100">
                                    <img data-src="<?= $image_url ?>" alt="<?= $title ?>" title="<?= $title ?>" loading="lazy" class="card-thumbnail" width="365" height="270" />
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="<?= $permalink ?>" class="d-inline-block h-100">
                                <img src="http://vehicle-photos-published.vauto.com/d5/fc/fb/f7-ff32-47f3-b551-2ea9efdc68f6/image-1.jpg" width="300" height="172" alt="<?= $title ?>" loading="lazy" />
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="inventory-products-bar__compare-listing p-1 position-absolute d-flex align-items-center justify-content-end">
                        <p class="text-white font-weight-bold p-0 font-lg mr-3">Compare</p>
                        <form class="inventory-products-bar__compare-listing-form d-flex align-items-center">
                            <input type="checkbox"
								   class="chk-compare position-relative bg-white"
								   value="<?php echo $post_id; ?>" <?php echo $checked; ?> />
                        </form>
                    </div>
                </div>
                
<!--                 CTA -->
            </div>
            
            <div class="card-content-wrapper px-10 position-relative">
                <?php if($isManagerSpecial): ?>
                    <div class="managers-specials-badge">
                        <span class="badge badge-danger rounded-0 p-2 border border-dark font-segoe text-capitalize font-sm">Manager Specials</span>
                    </div>
                <?php else: ?>
                    <div class="px-10 managers-specials-badge fake-managers-specials-badge"></div>
                <?php endif; ?>
                
                <div class="d-flex align-items-start justify-content-between mb-20 vehicle-title-wrapper overflow-hidden">
                    <a href="<?php echo esc_url( get_the_permalink() ); ?>"><h2 class="text-grey-3 font-helvetica font-lg p-0 m-0 font-weight-bold"><?= $title ?></h2></a>
                    <span class="icon-heart card-vehicle-like cursor-pointer <?= in_array($post_id, $likedVehicleIDs) ? 'd-none' : '' ?>" 
                          data-icon-show="<?= in_array($post_id, $likedVehicleIDs) ? 'false' : 'true' ?>" 
                          data-id="<?= $post_id ?>"></span>
                    <img src="<?= site_url() ?>/wp-content/themes/divi-child/assets/images/icon-vehicle-liked.png" alt="vehicle liked"
                         data-icon-show="<?= in_array($post_id, $likedVehicleIDs) ? 'true' : 'false' ?>"
                         class="card-vehicle-liked cursor-pointer <?= in_array($post_id, $likedVehicleIDs) ? '' : 'd-none' ?>" 
                         data-id="<?= $post_id ?>" />
                    <div class="listview-visible d-none align-items-center justify-content-end listview-price">
                        <h3 class="text-capitalize p-0 m-0 font-helvetica font-xl font-weight-bold text-grey-3">our best price</h3>
                        <?= $vehiclePriceHTML ?>
                    </div>
                </div>
                
                <div class="d-flex align-items-start justify-content-between mb-30 vehicle-meta-wrapper">
                    <div class="w-100">
                        <div class="listview-visible d-none mb-3">
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-sm text-grey-3 mr-3">VIN: <?= $vin ?></span></a>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-sm text-grey-3">Stock #: <?= $stock_number ?></span></a>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mb-2 listview-hidden">
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-helvetica font-sm text-grey-3 font-weight-bold">Stock #</span></a>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-helvetica font-sm text-grey-3 font-weight-bold"><?= $stock_number ?></span></a>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-helvetica font-sm text-grey-3 font-weight-bold">Mileage</span></a>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style">
								<span class="font-helvetica font-sm text-grey-3 font-weight-bold">
									<?= is_numeric($odometer = get_post_meta($post_id, 'odometer', true)) ? number_format($odometer) : 'N/A' ?>
								</span>
							</a>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-helvetica font-sm text-grey-3 font-weight-bold">Certified</span></a>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-helvetica font-sm text-grey-3 font-weight-bold"><?= get_post_meta($post_id, 'certified', true) ?></span></a>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between" mb-2>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-helvetica font-sm text-grey-3 font-weight-bold">Drivetrain</span></a>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><span class="font-helvetica font-sm text-grey-3 font-weight-bold"><?= get_post_meta($post_id, 'drivetrain', true) ?></span></a>
                        </div>
                        
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mb-30 vehicle-price-wrapper listview-hidden font-lg text-dark">
                    <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="no-style"><h3 class="text-capitalize p-0 m-0 font-helvetica font-lg font-weight-bold text-dark">our best price</h3></a>
                    <?= $vehiclePriceHTML ?>
                </div>
                
                <hr class="vehicle-card-divider" />
                
				<?php $exteriorColor = strtolower(trim(get_post_meta($post_id, 'exterior-color', true)));
				// if( ! empty( $exteriorColor ) ) :
				?>
                <div class="vehicle-color-block mb-2 d-flex align-items-center justify-content-start text-grey-3 text-uppercase font-sm font-weight-light">
                    <div class="vehicle-color-ball d-flex align-items-center mr-2">
                        <?php
                        $exteriorColor = strtolower(trim(get_post_meta($post_id, 'exterior-color', true)));
                        $exteriorColorValue = getColorValue($exteriorColor);
                        if ($exteriorColorValue): ?>
                            <span class="card-color-ball exterior-color-ball rounded-circle-px mr-2" 
                                  data-toggle="tooltip" data-placement="top" 
                                  title="Exterior Color: <?= ucwords($exteriorColor) ?>" 
                                  data-key="<?= $exteriorColor ?>" 
                                  style="background:#<?= $exteriorColorValue ?>"></span>
                        <?php else: ?>
							<span class="card-color-ball exterior-color-ball rounded-circle-px mr-2 d-flex align-items-center justify-content-center"
								  data-toggle="tooltip" data-placement="top"
								  title="Exterior Color: <?php echo ucwords( $exteriorColor ); ?>"
								  data-key="<?php echo $extetriorColor; ?>"
								  style="background: #fff;border:1px solid black;">
						<i class="fa-solid fa-xmark"></i>
						</span>
						<?php endif; ?>
						<?php if( ! empty( $exteriorColor ) ) : ?>
						<span class="text-capitalize"
							  data-toggle="tooltip"
							  data-placement="top"
							  title="Exterior Color: <?php echo ucwords( $exteriorColor ) ?>"
							  data-key="<?php echo $exteriorColor; ?>">EXTERIOR: <?php echo esc_html( wp_trim_words( $exteriorColor, 3 ) ); ?></span>
						<?php else : ?>
						<a href="tel:8444962224" class="text-grey-3"><i class="fa-solid fa-phone"></i></a>
						<?php endif; ?>
                    </div>
                </div>
				<?php // endif; ?>
                
				<?php
				$interiorColor = strtolower(trim(get_post_meta($post_id, 'interior-color', true)));
// 				if( ! empty( $interiorColor ) ) :
				?>
	
                <div class="vehicle-color-block mb-2 d-flex align-items-center justify-content-start text-grey-3 text-uppercase font-sm font-weight-light">
                    <?php
                    $interiorColorValue = getColorValue($interiorColor);
                    if ($interiorColorValue): ?>
                        <span class="card-color-ball interior-color-ball rounded-circle-px mr-2" 
                              data-toggle="tooltip" data-placement="top" 
                              title="Interior Color: <?= ucwords($interiorColor) ?>" 
                              data-key="<?= $interiorColor ?>" 
                              style="background:#<?= $interiorColorValue ?>"></span>
                    <?php else: ?>
					<span class="card-color-ball interior-color-ball rounded-circle-px mr-2 d-flex align-items-center justify-content-center" 
						  data-toggle="tooltip" data-placement="top" 
						  title="Interior Color: <?= ucwords($interiorColor) ?>" 
						  data-key="<?= $interiorColor ?>" 
						  style="background:#fff;border:1px solid black;>">
					<i class="fa-solid fa-xmark"></i>
					</span>
					<?php endif; ?>
					
					<?php if( ! empty( $interiorColor ) ) : ?>
					<span class="text-capitalize"
						  data-toggle="tooltip"
						  data-placement="top"
						  title="Interior Color: <?php echo ucwords( $interiorColor ); ?>"
						  data-key="<?php echo $interiorColor; ?>">INTERIOR: <?php echo esc_html( wp_trim_words( $interiorColor, 3 ) ); ?></span>
					<?php else : ?>
					<a href="tel:8444962224" class="text-grey-3"><i class="fa-solid fa-phone"></i></a>
					<?php endif; ?>
                </div>
				<?php // endif; ?>
				<hr class="vehicle-card-divider bottom" />	
            </div>
            <div class="btnWraper">
                <div class="mb-20 explore-more-cta">
                    <a href="<?= $permalink ?>" class="btn btn-primary w-100 d-inline-block font-weight-bold rounded">Explore More</a>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mb-20 vehicle-cta-wrapper listview-hidden">
                    <span class="font-sm font-helvetica font-weight-bold text-grey-3">History Report</span>
                    <a href="<?= $permalink ?>" class="font-sm font-helvetica font-weight-normal text-sixth">Vehicle Details >></a>
                </div>
                
                <div class="d-flex align-items-center justify-content-between vehicle-stickers-wrapper listview-hidden">
                    <div class="w-50 listview-visible d-none"></div>
                    <div class="vehicle-stickers-container d-flex align-items-center justify-content-between w-100">
                        <?php
                        if ( ! empty( $certification ) ) {
                            $showLink = false;

                            // if ( $certification === 'Ford Gold Certified' ) {
                            // 	$showLink = true;
                            // 	$info = $ford_gold_certified;
                            // } else
                            if ( $certification === 'Ford Blue Advantage: Blue Certified' ) {
                                $showLink = true;
                                $info = $certified_blue_blue_info;
                            // } else if ( $certification === 'Toyota Gold Certified' ) {
                            // 	$showLink = true;
                            // 	$info = $toyota_gold_certified;
                            } else if ( $certification === 'Ford Blue Certified' ) {
                                $showLink = true;
                                $info = $certified_blue_blue_info;
                            } else if ( $certification === 'Kia Certified Pre-Owned' ) {
                                $showLink = true;
                                $info = $certified_kia_info;
                            } else if( $certification === 'Ford Blue Advantage: EV Certified' ) {
                                $showLink = true;
                                $info = $certified_blue_ev;
                            } else if( $certification === 'CNA: Pro Certified' ) {
                                $showLink = true;
                                $info = $gold_check_certified;
                            }
                        }
                        ?>
                        <a href="http://www.carfax.com/VehicleHistory/p/Report.cfx?partner=DVW_1&vin=<?= $vin ?>" 
                        target="_blank" 
                        class="w-50
                        <?php echo ( $showLink && ! empty( $info ) ) ? '' : 'd-flex align-items-center mx-auto'; ?>"
                        data-name="carfax" 
                        data-vas-vin="<?= $vin ?>"
                        <?php echo ( $showLink && ! empty( $info ) ) ? '' : 'style="width: 80% !important;"'; ?>>
                            <img src="<?= $windowStickerInfo['image'] ?>" 
                                alt="<?= $windowStickerInfo['alt'] ?>" 
                                width="<?= $windowStickerInfo['width'] ?>" 
                                height="<?= $windowStickerInfo['height'] ?>" 
                                loading="lazy" 
                                class="w-100 img-fluid" />
                        </a>
                        <a href="https://windowsticker.velocityengage.com/vin/<?= $vin ?>/account/durangofordfd?source=Dealer%20Website" 
                        target="_blank" 
                        class="listview-visible d-none w-50" 
                        data-name="window" 
                        data-vas-vin="<?= $vin ?>">
                            <img src="<?= $windowEngageInfo['image'] ?>" 
                                alt="<?= $windowEngageInfo['alt'] ?>" 
                                width="<?= $windowEngageInfo['width'] ?>" 
                                height="<?= $windowEngageInfo['height'] ?>" 
                                loading="lazy" 
                                class="w-100 img-fluid" />
                        </a>
                        
                        <?php
                        if ( ! empty( $certification ) ) {
                            $showLink = false;

                            // if ( $certification === 'Ford Gold Certified' ) {
                            // 	$showLink = true;
                            // 	$info = $ford_gold_certified;
                            // } else
                            if ( $certification === 'Ford Blue Advantage: Blue Certified' ) {
                                $showLink = true;
                                $info = $certified_blue_blue_info;
                            // } else if ( $certification === 'Toyota Gold Certified' ) {
                            // 	$showLink = true;
                            // 	$info = $toyota_gold_certified;
                            } else if ( $certification === 'Ford Blue Certified' ) {
                                $showLink = true;
                                $info = $certified_blue_blue_info;
                            } else if ( $certification === 'Kia Certified Pre-Owned' ) {
                                $showLink = true;
                                $info = $certified_kia_info;
                            } else if( $certification === 'CNA: Pro Certified' ) {
                                $showLink = true;
                                $info = $gold_check_certified;
                            }

                            if ( $showLink && ! empty( $info ) ) { ?>
                                <a href="https://app.velocityengage.com/<?= $vin ?>?source=dealerdotcom&accountId=durangomotorcompany&embedded=true" 
                                target="_blank" 
                                class="listing-info__quick-view w-50 d-inline-block" 
                                data-name="velocity" 
                                data-vas-vin="<?= $vin ?>">
                                    <img src="<?= $info['image'] ?>" 
                                        alt="<?= $info['alt'] ?>" 
                                        width="<?= $info['width'] ?>" 
                                        height="<?= $info['height'] ?>" 
                                        loading="lazy" 
                                        class="w-100 img-fluid" />
                                </a>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    // Get the buffered content
    $card = ob_get_clean();

    // Cache the card for 24 hours
    set_transient($transient_key, $card, DAY_IN_SECONDS);
	delete_transient( $transient_key );
    return $card;
}

// Helper function to get color value
function getColorValue($color) {
    $returnedColor = preDefinedColors($color);
    if (!is_null($returnedColor)) {
        return $returnedColor['key'];
    }
    
    $colorArr = explode(' ', $color);
    foreach ($colorArr as $c) {
        $returnedColor = preDefinedColors($c);
        if (!is_null($returnedColor)) {
            return $returnedColor['key'];
        }
    }
    
    return '';
}

function vehiclesPagination($foundPosts, $postCount, $maxPages, $currentPage, $scroll) {
    // Ensure $currentPage is at least 1
    $currentPage = max(1, intval($currentPage));
    
    // Determine posts per page based on scroll
    $postPerPage = ($scroll == 'true') ? 6 : 14;
    
    // Ensure $postPerPage is at least 1
    $postPerPage = max(1, $postPerPage);
    
    // Calculate start and end indices
    $start_index = ($currentPage - 1) * $postPerPage + 1;
    $end_index = min($start_index + $postPerPage - 1, $foundPosts);
    
    // Build pagination HTML
    $pagination = '<nav class="pagination flex-column flex-md-row mt-30 d-flex align-items-center justify-content-between">';
    
    // Display the range of posts being viewed
    $pagination .= '<div class="show mb-20 mb-md-0 d-flex align-items-center font-weight-bold text-grey-3 font-md">';
    $pagination .= '<span class="postCounts">Viewing ' . $start_index . ' - ' . $end_index . ' of ' . $foundPosts . '</span>';
    
    // Show "Show All" link if there are more than 12 posts
    if ($foundPosts > 12) {
        $pagination .= '<span class="all-pages d-none d-md-inline-block"><span>Show</span> All</span>';
    }
    $pagination .= '</div>';
    
    // Pagination links
    if ($foundPosts > $postPerPage) {
        $pagination .= '<div class="links-page text-grey-3 font-md d-flex align-items-center font-weight-bold  ">';
        
        // Previous page link
        if ($currentPage > 1) {
            $pagination .= '<a class="prev page-numbers" href="#" data-page="' . ($currentPage - 1) . '">
                            <i class="fa-solid fa-chevron-left"></i>
                            </a>';
        }
        
        // Current page input and total pages
        $pagination .= 'Page <input type="number" data-dummy="' . $postPerPage . '" value="' . $currentPage . '" min="1" class="input-pagination text-center" style="width:50px;" data-total="' . $maxPages . '"/>
                        of   ' . $maxPages . '';
        
        // Next page link
        if ($currentPage < $maxPages) {
            $pagination .= '<a class="next page-numbers" href="#" data-page="' . ($currentPage + 1) . '">
                            <i class="fa-solid fa-chevron-right"></i></a>';
        }
        
        $pagination .= '</div>';
    }
    
    // Show "Show All" link for mobile devices
    if ($foundPosts > 12) {
        $pagination .= '<span class="font-weight-bold   text-grey-3 font-md mt-20 d-md-none show text-center all-pages">Show All</span>';
    }
    
    $pagination .= '</nav>';
    return $pagination;
}
function postCount($postCount) {
    return $postCount;
}
function inventorySelectedFilters(
	$searchbar,
	$year,
	$make,
	$model,
	$bodyType,
	$transmission,
	$doors,
	$cyllinders,
	$drivetrain,
	$certified,
	$fueltype,
	$bodystyle,
	$exteriorColor,
	$interiorColor,
	$sortDesktop,
	$mileage,
	$price,
	$certificationArr,
	$v_condition = 'new',
	$trim,
	$engine
) {
	
	// if(in_array('Ford Gold Certified', $certificationArr) && in_array('Ford Blue Advantage: Blue Certified',$certificationArr)) { // Commented out as per request
	// 	$certificationArr = array('certified pre owned ford');
	// }else if(in_array('Toyota Certified Used Vehicles', $certificationArr) && in_array('Toyota', $certificationArr)) { // Commented out as per request
	// 	$certificationArr = array('certified pre owned toyota');
	// }else if(in_array('Kia Certified Pre-Owned', $certificationArr)) {
	// 	$certificationArr = array('certified pre owned kia');
	// }

	if (is_array($certificationArr) && in_array('Kia Certified Pre-Owned', $certificationArr)) {
		$certificationArr = array('certified pre owned kia');
	}

    $selectedFilters = array(
        'search' => $searchbar,
        'yearArr' => $year,
        'makeArr' => $make,
        'modelArr' => $model,
        'bodyTypeArr' => $bodyType,
        'transmissionArr' => $transmission,
        'doorsArr' => $doors,
        'cylindersArr' => $cyllinders,
        'drivetrainArr' => $drivetrain,
        'certifiedArr' => $certified,
        'fueltypeArr' => $fueltype,
        'bodystyleArr' => $bodystyle,
        'exterior-color' => $exteriorColor,
        'interior-color' => $interiorColor,
        'sort' => $sortDesktop,
        'price' => $price,
        'mileage' => $mileage,
        'certification' => $certificationArr,
		'condition' => $v_condition,
		'trim'	=> $trim,
		'engine' => $engine
    );
    $filterTypeMap = array(
        'search' => 'search',
        'yearArr' => 'year',
        'makeArr' => 'make',
        'modelArr' => 'model',
        'bodyTypeArr' => 'type-of-vehicle',
        'transmissionArr' => 'transmission',
        'doorsArr' => 'doors',
        'cylindersArr' => 'cylinders',
        'drivetrainArr' => 'drivetrain',
        'certifiedArr' => 'certified',
        'fueltypeArr' => 'fuel-type',
        'bodystyleArr' => 'body_style',
        'exterior-color' => 'exterior-color',
        'interior-color' => 'interior-color',
        'sort' => 'sort',
        'price' => 'price',
        'mileage' => 'mileage',
        'certification' => 'certification',
		'condition' => 'condition',
		'trim'	=> 'trim',
		'engine' => 'engine'
    );
    //  filters 
    $filterhtml = '';
    foreach ($selectedFilters as $filterName => $filterValues) {
        if (!empty($filterValues) && $filterName != 'price' && $filterName != 'mileage' && $filterName != 'search' && $filterName != 'sort' && $filterName !== 'condition') {
            foreach ($filterValues as $filterValue) {
                if (!empty($filterValue)) {
                    $filterType = $filterTypeMap[$filterName];
                    $filterhtml .= '<div class="inventory-products-bar__selected-filter"
                    id="' . $filterValue . '" data-type="' . $filterType . '">
                    <span class="text-capitalize">';
                    // if($filterValue === 'certified pre owned ford' ||
                    // $filterValue === 'certified pre owned toyota' ||
                    if($filterValue === 'certified pre owned kia') {
                        $filterhtml .= 'certified pre owned';
                    }else {
                        $filterhtml .= $filterValue;
                    }
                    $filterhtml .= '</span>
                    <span class="filter-remove cursor-pointer" data-id="' . $filterValue . '"
                    data-val="' . $filterValue . '" data-type="' . $filterType . '">x</span></div>';
                }
            }
        }else{
            if (!empty($filterValues)) {
                $filterType = $filterTypeMap[$filterName];
                if ($filterName == 'price') {
                    $minPrice = $price[0] ?? 0;
                    $maxPrice = isset($price[1]) ? $price[1] : null;

                    if ($maxPrice === '0') {
                        $maxPrice = null;
                    }

                    $filterhtml .= '<div class="inventory-products-bar__selected-filter" id="price">';
                    $filterhtml .= '<span>$ ' . number_format($minPrice) . ( count($price) == 1 ? null : ' -' );
                    $filterhtml .= ($maxPrice !== null) ? ' $ ' . number_format($maxPrice) : '';
                    $filterhtml .= '</span>';
                    $filterhtml .= '<span class="filter-remove cursor-pointer" data-id="price" data-type="' . $filterType . '" data-val="' . $minPrice . ( count($price) == 1 ? null : ',' ) . $maxPrice . '">x</span>';
                    $filterhtml .= '</div>';
                }else if( $filterName == 'mileage') {
                    $minMileage = $mileage[0];
                    $maxMileage = $mileage[1];
                    $filterhtml .= '<div class="inventory-products-bar__selected-filter" id="mileage"><span>'. number_format($minMileage) .' - '. number_format($maxMileage) .'</span><span class="filter-remove cursor-pointer" data-id="mileage" data-type="'.$filterType.'" data-val="'. $minMileage .','. $maxMileage .'">x</span></div>';
                }else{
                    $filterhtml .= '<div class="inventory-products-bar__selected-filter" id="'. $filterValues .'" data-type="'.$filterName.'"><span class="text-capitalize">'. $filterValues .'</span><span class="filter-remove cursor-pointer" data-type="'.$filterType.'" data-id="'. $filterValues .'" data-val="'. $filterValues .'" data-type="'.$filterName.'">x</span></div>';
                }
            }
        }
    }
    return $filterhtml;
}