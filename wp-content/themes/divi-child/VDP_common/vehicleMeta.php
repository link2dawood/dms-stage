<?php

error_log('TEST: vehicleMeta.php is executing');
echo "<script>console.log('DEBUG: HaiDer Test');</script>";


function vehicleDetailsBox($vehicleId, $vehicleTitle, $vehicleVin, $vehicleStock, $vehicleCertified, $make, $model, $year) {
    error_log("DEBUG vehicleDetailsBox: Called with vehicleId={$vehicleId}, vin={$vehicleVin}, stock={$vehicleStock}");
    $detailsBox = '<div class="details-box bg-grey-7 p-15 py-4">'.
                  '<div class="row">'.
                  '<div class="col-12 col-lg-7">'.
                  '<h1 class="p-0 font-inter text-dark mb-20 font-weight-bold font-30">'.$vehicleTitle.'</h1>'.
                  '<div class="d-flex align-items-center justify-content-start flex-wrap">';
    $detailsBox .= ( !empty($vehicleStock) ? '<div class="mr-3 font-inter font-sm text-uppercase"><span class="mr-1">Stock: </span><span class="vehicle-stock-number">'.$vehicleStock.'</span></div>' : '' );
    $detailsBox .= ( !empty($vehicleVin) ? '<div class="mr-3 font-inter font-sm text-uppercase"><span class="mr-1">VIN: </span><span>'.$vehicleVin.'</span></div>' : '' );
    $detailsBox .= ( !empty($vehicleCertified) ? '<div class="mr-3 font-inter font-sm text-uppercase"><span class="mr-1">Certified: </span><span>'.$vehicleCertified.'</span></div>' : '' );
    $detailsBox .= '</div>'.
                  '</div>'.
                  '<div class="col-12 col-lg-5 mt-3 mt-lg-0 d-flex align-items-center justify-content-between justify-content-lg-end detail-bar-action-icons">'.
                  '<div class="details-action-icon d-flex align-items-center">'.
                  '<img src="'.site_url().'/wp-content/uploads/2025/02/icon-details-tag.png"
                   width="32" height="32" alt="details" loading="eager" class="icon-details-tag font-30 cursor-pointer close-upgradeVehicle"/>'.
                  '<span class="icon-details-tag font-30 text-fourth cursor-pointer close-upgradeVehicle d-none"></span>'.
                  '</div>'.
                  '<span class="font-inter font-md more-cars-found text-to-reveal overflow-hidden d-none d-lg-inline-block">We found other cars you might like!</span>'.
                  '<div class="details-action-icon star-empty-icon d-flex align-items-center position-relative text-reveal-container justify-content-end">'.
                  '<span class="icon-star-empty font-30 text-fourth cursor-pointer"></span>'.
                  '</div>'.
                  '<div class="details-action-icon star-active-icon d-none">'.
                  '<span class="icon-icon-star-active font-30 text-fourth cursor-pointer">
                  <span class="path1"></span>
                  </span>'.
                  '</div>'.
                  '<div class="details-action-icon price-alert-simple-icon">'.
                  '<span class="icon-bell font-30 text-fourth cursor-pointer sidebar-popup-trigger" data-popup="sticky-cta" data-vin="'.$vehicleVin.'"
                  data-stock="'.$vehicleStock.'" data-make="'.$make.'" data-model="'.$model.'" data-year="'.$year.'" data-popup-function="vehicle-price-alert"></span>'.
                  '</div>'.
                  '<div class="details-action-icon price-alert-active-icon d-none align-items-center justify-content-end">'.
                  '<span class="font-inter font-md more-cars-found mr-2 text-to-reveal overflow-hidden">Get Price Drop Alerts!!</span>'.
                  '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/get-price-drop-alerts.png" alt="vehicle price drop alerts" itemprop="image"
                  class="sidebar-popup-trigger" data-popup="sticky-cta" data-vin="'.$vehicleVin.'" data-stock="'.$vehicleStock.'" data-make="'.$make.'" data-model="'.$model.'" data-year="'.$year.'" data-popup-function="vehicle-price-alert" />'.
                  '</div>'.
                  '<div class="details-action-icon">'.
                  '<span class="icon-share sidebar-popup-trigger font-30 text-fourth cursor-pointer" data-popup-function="vehicle-share" data-popup="sticky-cta" data-vin="'.$vehicleVin.'"
                  data-stock="'.$vehicleStock.'" data-make="'.$make.'" data-model="'.$model.'" data-year="'.$year.'"></span>'.
                  '</div>';
                  $table_name = accessWPDB()->prefix . 'user_liked_vehicles';
                  $user_ip = getUserIP();
                  error_log("DEBUG vehicleDetailsBox: Checking liked vehicles for IP: {$user_ip}");
                  $recentQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", $user_ip);
                  $updateResult = accessWPDB()->get_row($recentQuery, ARRAY_A);
                  if( !$updateResult ) {
                    $likedVehicleIDs = array(0);
                    error_log("DEBUG vehicleDetailsBox: No liked vehicles found for IP");
                  }else {
                      $likedVehicleIDs = !empty($updateResult['user_liked_vehicles']) ? unserialize($updateResult['user_liked_vehicles']) : array();
                      error_log("DEBUG vehicleDetailsBox: Found " . count($likedVehicleIDs) . " liked vehicles");
                  }
        
        $vehicleIndex = array_search($vehicleId, $likedVehicleIDs);
        error_log("DEBUG vehicleDetailsBox: Vehicle {$vehicleId} index in liked array = " . ($vehicleIndex !== false ? $vehicleIndex : "not found"));
        $detailsBox .=  '<div class="details-action-icon make-vehicle-like '.($vehicleIndex !== false ? "d-none" : "").' " data-id="'.$vehicleId.'">'.
                  '<span class="icon-heart font-30 text-fourth cursor-pointer"></span>'.
                  '</div>'.
                  '<div class="details-action-icon make-vehicle-liked '.($vehicleIndex === false ? "d-none" : "").' " data-id="'.$vehicleId.'">'.
                  '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/icon-vehicle-liked.png" alt="vehicle liked" itemprop="image" class="cursor-pointer"/>'.
                  '</div>';
        $detailsBox .= '<div class="details-action-icon">'.
                   '<a href="tel:'.salesPhoneNumber().'">'.
                  '<span class="icon-phone font-30 text-fourth cursor-pointer"></span>'.
                  '</a>'.
                  '</div>'.
                  '</div>'.
                  '</div>'.
                  '</div>';

    echo $detailsBox;
}

function vehicleSlider($vin_number = '', $model = '') {
    error_log("DEBUG vehicleSlider: Function called with vin_number='{$vin_number}', model='{$model}'");
    /** Connect to external database */
    $external_connection = get_db_connection();
    error_log("DEBUG vehicleSlider: Database connection " . ($external_connection ? "successful" : "failed"));
    
    /** Get max batch number */
    $batch_query = "SELECT MAX(batch_number) AS batch_number FROM dmc_images";
    $batch_result = $external_connection->query($batch_query);
    if (!$batch_result) {
        error_log("DEBUG vehicleSlider: Batch query failed - " . $external_connection->error);
        die("Query failed: " . $external_connection->error);
    }
    $batch_row = $batch_result->fetch_assoc();
    $max_batch = $batch_row['batch_number'];
    error_log("DEBUG vehicleSlider: Max batch number = {$max_batch}");
    $select_query = "SELECT vauto_url FROM dmc_images WHERE vin = ?";
    $stmt = $external_connection->prepare($select_query);

    $image_urls = [];
    if ($stmt) {
        $stmt->bind_param("s", $vin_number);
        $stmt->execute();
        $result = $stmt->get_result();
        error_log("DEBUG vehicleSlider: Query executed for VIN '{$vin_number}', rows found: " . $result->num_rows);

        while ($row = $result->fetch_assoc()) {
            if (!empty($row['vauto_url'])) {
                $image_urls[] = $row['vauto_url'];
            }
        }
        $stmt->close();
        error_log("DEBUG vehicleSlider: Image URLs collected: " . count($image_urls));
    } else {
        error_log("DEBUG vehicleSlider: Statement preparation failed");
    }

    $urlToRemove = 'http://vehicle-photos-published.vauto.com/04/db/a3/0f-009d-4d84-ba0a-fe04a042c1d5/image-1.jpg';

    // Remove placeholder URL if it's the only or repeated entry
    if (!empty($image_urls)) {
        $urlCount = array_count_values($image_urls)[$urlToRemove] ?? 0;
        if ($urlCount > 0) {
            if (count(array_unique($image_urls)) === 1) {
                $image_urls = []; // Clear if only placeholder exists
            } else {
                $image_urls = array_filter($image_urls, fn($url) => $url !== $urlToRemove);
                $image_urls = array_values($image_urls); // Reindex
            }
        }
    }

    // Check if listing has no featured image
    $has_featured_image = !empty($image_urls);
    error_log("DEBUG vehicleSlider: has_featured_image = " . ($has_featured_image ? "true" : "false"));
    if (!$has_featured_image) {
        error_log("DEBUG vehicleSlider: No featured image, checking VIN fallback");
        // Check for VIN number first
        $vin_number = get_post_meta(get_the_ID(), 'vin-number', true);
        error_log("DEBUG vehicleSlider: VIN from post meta = '{$vin_number}'");
        if ($vin_number) {
            $jellyB_img_urls = dmc_get_image_urls();
            error_log("DEBUG vehicleSlider: JellyB URLs count = " . count($jellyB_img_urls));
            $normalized_vin = strtolower(str_replace(' ', '-', $vin_number));
            foreach ($jellyB_img_urls as $jelly_url) {
                $filename = basename($jelly_url, '.png');
                $normalized_filename = strtolower($filename);
                if (strpos($normalized_filename, $normalized_vin) !== false) {
                    $image_urls = [$jelly_url];
                    $has_featured_image = true;
                    error_log("DEBUG vehicleSlider: Found VIN match in JellyB: {$jelly_url}");
                    break;
                }
            }
        }

        // If no VIN match, proceed with original model check
        if (!$has_featured_image) {
            error_log("DEBUG vehicleSlider: No VIN match, checking model fallback");
            if (empty($model)) {
                $model = get_post_meta(get_the_ID(), 'model', true);
                error_log("DEBUG vehicleSlider: Model from post meta = '{$model}'");
            }
            if ($model) {
                $jellyB_img_urls = dmc_get_image_urls();
                $normalized_model = strtolower(str_replace(' ', '-', $model));
                error_log("DEBUG vehicleSlider: Searching for normalized model '{$normalized_model}'");
                foreach ($jellyB_img_urls as $jelly_url) {
                    $filename = basename($jelly_url, '.png');
                    $filename_parts = explode('--', $filename);
                    $filename_model = $filename_parts[0];
                    $filename_model = preg_replace('/^\d{4}-/', '', $filename_model);
                    $normalized_filename = strtolower($filename_model);
                    if (strpos($normalized_filename, $normalized_model) !== false) {
                        $image_urls = [$jelly_url];
                        error_log("DEBUG vehicleSlider: Found model match in JellyB: {$jelly_url}");
                        break;
                    }
                }
            }
        }
    }

    if (empty($image_urls)) {
        error_log("DEBUG vehicleSlider: No image URLs found, returning early");
        return;
    }
    
    error_log("DEBUG vehicleSlider: Final image URLs count = " . count($image_urls));
    
    // Check URL parameter for forced layout
    $slider_param = isset($_GET['slider']) ? $_GET['slider'] : '';
    error_log("DEBUG vehicleSlider: slider_param = '{$slider_param}'");
    $use_vertical_layout = false;
    
    if ($slider_param === 'vertical') {
        $use_vertical_layout = true;
        error_log("DEBUG vehicleSlider: Forced vertical layout via URL param");
    } elseif ($slider_param === 'horizontal') {
        $use_vertical_layout = false;
        error_log("DEBUG vehicleSlider: Forced horizontal layout via URL param");
    } else {
        // Auto-detect: Check if featured image is square
        if (!empty($image_urls[0])) {
            $first_image_url = $image_urls[0];
            error_log("DEBUG vehicleSlider: Checking first image dimensions: {$first_image_url}");
            $image_info = @getimagesize($first_image_url);
            
            if ($image_info && isset($image_info[0]) && isset($image_info[1])) {
                $width = $image_info[0];
                $height = $image_info[1];
                $aspect_ratio = $width / $height;
                error_log("DEBUG vehicleSlider: Image dimensions: {$width}x{$height}, aspect_ratio = {$aspect_ratio}");
                
                // Check if image is approximately square (within 10% difference)
                $tolerance = 0.10;
                if ($aspect_ratio >= (1 - $tolerance) && $aspect_ratio <= (1 + $tolerance)) {
                    $use_vertical_layout = true;
                    error_log("DEBUG vehicleSlider: Image is square, using vertical layout");
                } else {
                    error_log("DEBUG vehicleSlider: Image is not square, using horizontal layout");
                }
            } else {
                error_log("DEBUG vehicleSlider: Could not get image dimensions");
            }
        }
    }
    
    error_log("DEBUG vehicleSlider: Final layout decision - use_vertical_layout = " . ($use_vertical_layout ? "true" : "false"));
    
    // Override layout based on vehicle make
    $make = get_post_meta(get_the_ID(), 'make', true);
    $make_lower = strtolower(trim($make));

    error_log("DEBUG vehicleSlider: Vehicle make = '{$make_lower}'");
               $first_image_url = $image_urls[0];

            error_log("DEBUG vehicleSlider: Checking first image dimensions: {$first_image_url}");
            $image_info = @getimagesize($first_image_url);
                    $width = $image_info[0];
                $height = $image_info[1];
  echo "<script>
        console.log('Vehicle Slider Image Dimensions: width={$width}, height={$height}');
    </script>";
        echo "<script>
        console.log('Vehicle Slider Image URLs', " . json_encode($image_info) . ");
    </script>";
//     if ($make_lower === 'ford' || $make_lower === 'lincoln') {
//         $use_vertical_layout = true;
//         error_log("DEBUG vehicleSlider: Ford/Lincoln detected - forcing vertical layout");
//     } elseif ($make_lower === 'toyota' || $make_lower === 'kia') {
//         $use_vertical_layout = false;
//         error_log("DEBUG vehicleSlider: Toyota/Kia detected - forcing horizontal layout");
//     }

	if ($make_lower === 'toyota' || $make_lower === 'kia') {
        $use_vertical_layout = false;
        
error_log("DEBUG vehicleSlider: Toyota/Kia detected - forcing horizontal layout");
}else{
$use_vertical_layout = true;
 error_log("DEBUG vehicleSlider: Ford/Lincoln detected - forcing vertical layout");

}


    // For other makes, keep the auto-detected layout based on image aspect ratio
	
if ($use_vertical_layout) {
    // VERTICAL LAYOUT - DESKTOP ONLY
    ?>
    <div class="listing-content-wrapper-with-slider listing-content-border-wrapper">
        <!-- Desktop: Vertical Layout (90/10) -->
        <div class="listing-slider border-0 shadow-none overflow-hidden d-none d-lg-block">
            <div class="row g-0 vertical-slider-layout">
                <!-- Main Image Section (Left) -->
                <div class="col-12 col-lg-10">
                    <section class="vertical-main-slider-wrapper position-relative">
                        <div class="vertical-main-slider-container">
                            <ul class="vertical-main-image-slider global-slick-slider">
                                <?php foreach ($image_urls as $index => $image) : ?>
                                    <li class="position-relative vertical-main-slide">
                                        <div class="vertical-main-image-container">
                                            <img src="<?php echo esc_url($image); ?>"
                                                 loading="<?php echo ($index === 0) ? 'eager' : 'lazy'; ?>"
                                                 width="762"
                                                 height="456"
                                                 alt="<?php echo esc_attr(get_the_title()); ?>"
                                                 title="<?php echo esc_attr(get_the_title()); ?>"
                                                 class="position-relative w-100" />
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <!-- Navigation arrows -->
                            <button type="button" class="vertical-slider-prev slick-arrow">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button type="button" class="vertical-slider-next slick-arrow">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <!-- Light Box Trigger & Image Counter -->
                        <div class="slider-cta d-flex align-items-center justify-content-between position-absolute">
                            <div class="d-flex align-items-center justify-content-start slider-images-counter bg-white p-15">
                                <span class="icon-image font-xl"></span>
                                <div class="ml-2 text-primary font-inter font-sm">
                                    <?php 
                                    error_log("DEBUG vehicleSlider: Rendering vertical layout, image count = " . count($image_urls));
                                    echo esc_html(count($image_urls)); 
                                    ?>
                                </div>
                            </div>
                            <div class="slider-full-screen">
                                <span class="icon-slider-fullscreen font-30" style="cursor: pointer; transform: translateY(5px);"></span>
                            </div>
                        </div>
                    </section>
                </div>
                
                <!-- Vertical Thumbnails Section (Right) -->
                <div class="col-12 col-lg-2">
                    <section class="vertical-thumbnails-wrapper">
                        <div class="vertical-thumbnails-container">
                            <div class="vertical-thumbnails-scroll-container" style="max-height: 600px; overflow-y: auto;padding: 6px;width:130px;">
                                <ul class="vertical-thumbnails-list" style="padding: 0; margin: 0; list-style: none;">
                                    <?php foreach ($image_urls as $index => $image) : ?>
                                        <li class="vertical-thumbnail-item <?php echo ($index === 0) ? 'active' : ''; ?>" data-index="<?php echo $index; ?>" style="margin-bottom:5px;width:115px;height:115px;">
                                            <div class="vertical-thumbnail-image-container" style="width: 100%; aspect-ratio: 1/1; overflow: hidden; cursor: pointer;height:100%;">
                                                <img src="<?php echo esc_url($image); ?>"
                                                     loading="lazy"
                                                     width="150"
                                                     height="150"
                                                     alt="<?php echo esc_attr(get_the_title()); ?>"
                                                     title="<?php echo esc_attr(get_the_title()); ?>"
                                                     class="position-relative w-100 h-100 object-fit-cover" />
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div> 
    <?php
} else {
    // ORIGINAL HORIZONTAL LAYOUT (for rectangular images or when forced) - DESKTOP ONLY
    error_log("DEBUG vehicleSlider: Rendering HORIZONTAL layout (thumbnails on bottom)");
    ?>
    <div class="listing-content-wrapper-with-slider listing-content-border-wrapper">
        <div class="listing-slider border-0 shadow-none overflow-hidden d-none d-lg-block">
            <section class="listing-image-slider-wrapper position-relative">
                <ul class="listing-main-image-slider global-slick-slider">
                    <?php foreach ($image_urls as $index => $image) : ?>
                    <li class="position-relative">
                        <?php error_log("DEBUG vehicleSlider: Horizontal layout - rendering image: " . $image); ?>
                        <img src="<?php echo esc_url($image); ?>"
                             loading="<?php echo ($index === 0) ? 'eager' : 'lazy'; ?>"
                             width="762"
                             height="456"
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             title="<?php echo esc_attr(get_the_title()); ?>"
                             class="position-relative w-100" />
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <!-- Light Box Trigger -->
                <div class="slider-cta d-flex align-items-center justify-content-between position-absolute">
                    <div class="d-flex align-items-center justify-content-start slider-images-counter bg-white p-15">
                        <span class="icon-image font-xl"></span>
                        <div class="ml-2 text-primary font-inter font-sm">
                            <?php echo esc_html(count($image_urls)); ?>
                        </div>
                    </div>
                    <div class="slider-full-screen">
                        <span class="icon-slider-fullscreen font-30" style="cursor: pointer; transform: translateY(5px);"></span>
                    </div>
                </div>
            </section>
            
            <?php if (count($image_urls) > 1) : ?>
            <!-- Vehicle thumbnail slider -->
            <section class="listing-thumbnail-slider-wrapper position-relative" style="overflow: visible;">
                
                <?php if (count($image_urls) > 5) : ?>
                <div class="see-all-images position-absolute h-100 d-flex align-items-center justify-content-center cursor-pointer">
                    <p class="p-0 font-inter text-capitalize text-white">
                        <span class="font-inter mb-2 font-30 font-weight-normal d-block text-center">
                            <?php echo esc_html(count($image_urls) - 5); ?>
                        </span>
                        <span class="font-inter font-30 font-weight-normal">
                            <?php echo esc_html('More'); ?>
                        </span>
                    </p>
                </div>
                <?php endif; ?>
                
                <ul class="listing-thumbnail-image-slider global-slick-slider" style="width: 100%; overflow: visible;">
                    <?php foreach ($image_urls as $index => $image) : ?>
                    <li class="position-relative" style="display: inline-block;">
                        <?php error_log("DEBUG vehicleSlider: Horizontal thumbnail {$index}: " . $image); ?>
                        <img src="<?php echo esc_url($image); ?>"
                             loading="lazy"
                             width="167"
                             height="119"
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             title="<?php echo esc_attr(get_the_title()); ?>"
                             class="position-relative w-100" />
                        
                        <div class="bg-white position-absolute slider-counter-wrapper d-none">
                                <span class="slider-current-count color_black font-inter font-xs">
                                    <?php echo esc_html($index + 1); ?>
                                </span>
                                <span class="slider-counter-divider color_black font-inter font-xs">/</span>
                                <span class="slider-total-counter color_black font-inter font-xs">
                                    <?php echo esc_html(count($image_urls)); ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>    
            <?php endif; ?>
        </div>
    </div> 
    <?php
    error_log("DEBUG vehicleSlider: Horizontal layout rendering complete");
}

// MOBILE SLIDER (shared by all layouts)
?>
<div class="listing-content-wrapper-with-slider listing-content-border-wrapper d-block d-lg-none">
    <div class="listing-slider border-0 shadow-none overflow-hidden">
        <section class="listing-image-slider-wrapper position-relative">
            <ul class="listing-main-image-slider global-slick-slider">
                <?php foreach ($image_urls as $index => $image) : ?>
                <li class="position-relative">
                    <img src="<?php echo esc_url($image); ?>"
                         loading="<?php echo ($index === 0) ? 'eager' : 'lazy'; ?>"
                         width="762"
                         height="456"
                         alt="<?php echo esc_attr(get_the_title()); ?>"
                         title="<?php echo esc_attr(get_the_title()); ?>"
                         class="position-relative w-100" />
                </li>
                <?php endforeach; ?>
            </ul>
            
            <!-- Light Box Trigger -->
            <div class="slider-cta d-flex align-items-center justify-content-between position-absolute">
                <div class="d-flex align-items-center justify-content-start slider-images-counter bg-white p-15">
                    <span class="icon-image font-xl"></span>
                    <div class="ml-2 text-primary font-inter font-sm">
                        <?php echo esc_html(count($image_urls)); ?>
                    </div>
                </div>
                <div class="slider-full-screen">
                    <span class="icon-slider-fullscreen font-30" style="cursor: pointer; transform: translateY(5px);"></span>
                </div>
            </div>
        </section>
        
        <?php if (count($image_urls) > 1) : ?>
        <!-- Vehicle thumbnail slider -->
        <section class="listing-thumbnail-slider-wrapper position-relative px-15">
            
            <?php if (count($image_urls) > 5) : ?>
            <div class="see-all-images position-absolute h-100 d-flex align-items-center justify-content-center cursor-pointer">
                <p class="p-0 font-inter text-capitalize text-white">
                    <span class="font-inter mb-2 font-30 font-weight-normal d-block text-center">
                        <?php echo esc_html(count($image_urls) - 5); ?>
                    </span>
                    <span class="font-inter font-30 font-weight-normal">
                        <?php echo esc_html('More'); ?>
                    </span>
                </p>
            </div>
            <?php endif; ?>
            
            <ul class="listing-thumbnail-image-slider global-slick-slider">
                <?php foreach ($image_urls as $index => $image) : ?>
                <li class="position-relative">
                    <img src="<?php echo esc_url($image); ?>"
                         loading="lazy"
                         width="167"
                         height="119"
                         alt="<?php echo esc_attr(get_the_title()); ?>"
                         title="<?php echo esc_attr(get_the_title()); ?>"
                         class="position-relative w-100" />
                    
                    <div class="bg-white position-absolute slider-counter-wrapper d-none">
                            <span class="slider-current-count color_black font-inter font-xs">
                                <?php echo esc_html($index + 1); ?>
                            </span>
                            <span class="slider-counter-divider color_black font-inter font-xs">/</span>
                            <span class="slider-total-counter color_black font-inter font-xs">
                                <?php echo esc_html(count($image_urls)); ?>
                            </span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>    
        <?php endif; ?>
    </div>
</div>
<?php
error_log("DEBUG vehicleSlider: Mobile slider rendering complete");
}

function vehicleMeta($metaValues) {
    error_log("DEBUG vehicleMeta: Called with " . count($metaValues) . " meta values");
    $values = '<div class="meta-values-container mt-5 pt-2">'.
                '<h3 class="mb-30 font-inter font-xxl p-0 font-inter text-fifth font-weight-bold">Basic Info</h3>
                <div class="row meta-values-row">';
                foreach( $metaValues as $key => $value ) {
                    error_log("DEBUG vehicleMeta: Processing key='{$key}', value='{$value}'");
                    $values .= '<div class="col-12 col-md-6 d-flex align-items-center justify-content-between">'.
                    '<span class="text-primary text-capitalize font-inter font-20 font-weight-bold">'.$key.': </span>';
                    if( !empty($value) ) {
                       $values .= '<span class="text-grey-5 text-capitalize font-inter font-20">'.$value.' </span>';
                    }
                    $values .= '</div>';
                }
    $values .= '</div>
              </div>';
    echo $values;
}
function vehicleCertifiedPreOwned($make, $certification, $certified) {
    error_log("DEBUG vehicleCertifiedPreOwned: Called with make='{$make}', certification='{$certification}', certified='{$certified}'");
    $make = strtolower($make);
    $certification = strtolower($certification);
	$certified = strtolower($certified);
	
    $certifiedBadge = array(
        'kia' => array(
            'url' => wp_get_attachment_image_src(get_field('certified_kia_vehicle_badge_image', 'options'), 'full')[0],
            'width' => wp_get_attachment_image_src(get_field('certified_kia_vehicle_badge_image', 'options'), 'full')[1],
            'height' => wp_get_attachment_image_src(get_field('certified_kia_vehicle_badge_image', 'options'), 'full')[2],
            'alt' =>  get_post_meta(get_field('certified_kia_vehicle_badge_image','options'), '_wp_attachment_image_alt', true),
        ),
        'ford' => array(
            'url' => wp_get_attachment_image_src(get_field('certified_ford_vehicle_badge_image', 'options'), 'full')[0],
            'width' => wp_get_attachment_image_src(get_field('certified_ford_vehicle_badge_image', 'options'), 'full')[1],
            'height' => wp_get_attachment_image_src(get_field('certified_ford_vehicle_badge_image', 'options'), 'full')[2],
            'alt' =>  get_post_meta(get_field('certified_ford_vehicle_badge_image','options'), '_wp_attachment_image_alt', true),
        ),
        'toyota' => array(
            'url' => wp_get_attachment_image_src(get_field('certified_toyota_vehicle_badge_image', 'options'), 'full')[0],
            'width' => wp_get_attachment_image_src(get_field('certified_toyota_vehicle_badge_image', 'options'), 'full')[1],
            'height' => wp_get_attachment_image_src(get_field('certified_toyota_vehicle_badge_image', 'options'), 'full')[2],
            'alt' =>  get_post_meta(get_field('certified_toyota_vehicle_badge_image','options'), '_wp_attachment_image_alt', true),
        ),
        'fordBlueGold' => array(
            'url' => wp_get_attachment_image_src(get_field('ford_blue_advantage_gold_certified', 'options'), 'full')[0],
            'width' => wp_get_attachment_image_src(get_field('ford_blue_advantage_gold_certified', 'options'), 'full')[1],
            'height' => wp_get_attachment_image_src(get_field('ford_blue_advantage_gold_certified', 'options'), 'full')[2],
            'alt' =>  get_post_meta(get_field('ford_blue_advantage_gold_certified','options'), '_wp_attachment_image_alt', true),
        ),
        'fordBlueBlue' => array(
            'url' => wp_get_attachment_image_src(get_field('ford_blue_advantage_blue_certified', 'options'), 'full')[0],
            'width' => wp_get_attachment_image_src(get_field('ford_blue_advantage_blue_certified', 'options'), 'full')[1],
            'height' => wp_get_attachment_image_src(get_field('ford_blue_advantage_blue_certified', 'options'), 'full')[2],
            'alt' =>  get_post_meta(get_field('ford_blue_advantage_blue_certified','options'), '_wp_attachment_image_alt', true),
        ),
		'fordBlueEV' => array(
            'url' => wp_get_attachment_image_src(get_field('ford_blue_advantage_ev_certified', 'options'), 'full')[0],
            'width' => wp_get_attachment_image_src(get_field('ford_blue_advantage_ev_certified', 'options'), 'full')[1],
            'height' => wp_get_attachment_image_src(get_field('ford_blue_advantage_ev_certified', 'options'), 'full')[2],
            'alt' =>  get_post_meta(get_field('ford_blue_advantage_ev_certified','options'), '_wp_attachment_image_alt', true),
        ),
		'goldCheckCertified' => array(
            'url' => wp_get_attachment_image_src(get_field('gold_check_certified', 'options'), 'full')[0],
            'width' => wp_get_attachment_image_src(get_field('gold_check_certified', 'options'), 'full')[1],
            'height' => wp_get_attachment_image_src(get_field('gold_check_certified', 'options'), 'full')[2],
            'alt' =>  get_post_meta(get_field('gold_check_certified','options'), '_wp_attachment_image_alt', true),
        )
    );
    $badgeUrl = '';
    $badgeAlt = '';
    $badgeWidth = '';
    $badgeHeight = '';
	$text 	= '';
	
     if( $certified === 'yes') {
       error_log("DEBUG vehicleCertifiedPreOwned: Vehicle is certified, checking certification type");
       if ($certification == 'kia certified pre-owned') {
            $badgeUrl = $certifiedBadge['kia']['url'];
            $badgeAlt = $certifiedBadge['kia']['alt'];
            $badgeWidth = $certifiedBadge['kia']['width'];
            $badgeHeight = $certifiedBadge['kia']['height'];
		   $text = 'Kia';
        } else if (($certification === 'toyota' || $certification === 'toyota certified used vehicles' || $certification === 'toyota gold certified')) {
            $badgeUrl = $certifiedBadge['toyota']['url'];
            $badgeAlt = $certifiedBadge['toyota']['alt'];
            $badgeWidth = $certifiedBadge['toyota']['width'];
            $badgeHeight = $certifiedBadge['toyota']['height'];
		   $text = 'Toyota';
        }else if($certification === 'ford blue advantage: blue certified' || $certification === 'Ford Blue Certified') {
            $badgeUrl = $certifiedBadge['fordBlueBlue']['url'];
            $badgeAlt = $certifiedBadge['fordBlueBlue']['alt'];
            $badgeWidth = $certifiedBadge['fordBlueBlue']['width'];
            $badgeHeight = $certifiedBadge['fordBlueBlue']['height'];
		   $text = 'Ford Blue Advantage: Blue';
        }else if( $certification === 'ford gold certified' ) {
            $badgeUrl = $certifiedBadge['fordBlueGold']['url'];
            $badgeAlt = $certifiedBadge['fordBlueGold']['alt'];
            $badgeWidth = $certifiedBadge['fordBlueGold']['width'];
            $badgeHeight = $certifiedBadge['fordBlueGold']['height'];
		   $text = 'Ford Gold';
        }else if( $certification === 'ford blue advantage: ev certified' ) {
            $badgeUrl = $certifiedBadge['fordBlueEV']['url'];
            $badgeAlt = $certifiedBadge['fordBlueEV']['alt'];
            $badgeWidth = $certifiedBadge['fordBlueEV']['width'];
            $badgeHeight = $certifiedBadge['fordBlueEV']['height'];
		   $text = 'Ford Blue Advantage: EV';
        } else if( $certification === 'cna: pro certified' ) {
			 $badgeUrl = $certifiedBadge['goldCheckCertified']['url'];
			 $badgeAlt = $certifiedBadge['goldCheckCertified']['alt'];
			 $badgeWidth = $certifiedBadge['goldCheckCertified']['width'];
			 $badgeHeight = $certifiedBadge['goldCheckCertified']['height'];
		   $text = 'CNA Pro';
		 }
		$CPOURL = null;
		if($certification === 'kia certified pre-owned') {
			$CPOURL = '2025/07/kia-cpo-brochure.pdf';
		}
		if($certification === 'toyota' || $certification === 'toyota certified used vehicles' || $certification === 'toyota gold certified') {
			$CPOURL = '2025/07/toyota-cpo-vehicle.pdf';
		}
		if( $certification === 'ford blue advantage: blue certified' ) {
			$CPOURL = '2025/07/blue-cpo-vehicle.pdf';
		}
		if($certification==='ford gold certified' || $certification === 'cna: pro certified' ) {
			$CPOURL = '2025/08/Pro-Certified-brochure.pdf';
		}
		 
        if( $certification==='ford gold certified' || $certification === 'ford blue advantage: blue certified'
        || $certification === 'toyota' || $certification === 'toyota certified used vehicles' || $certification === 'toyota gold certified' ||  $certification === 'kia certified pre-owned' || $certification === 'cna: pro certified') {
            $certified = '<div class="d-flex align-items-center justify-content-start certified-badge-container mt-5 pt-2 flex-column flex-md-row">'.
                          '<img src="'. $badgeUrl .'" alt="'.$badgeAlt.'" width="'.$badgeWidth.'" height="'.$badgeHeight.'" itemprop="image" loading="eager" style="max-width: 130px;" />'.
                          '<a class="certified-button w-100 text-primary font-md text-center font-weight-bold text-link" href="'.site_url().'/wp-content/uploads/'.$CPOURL.'" target="_blank">LEARN MORE about <br /> '. $text .' Certified</a>'.
                          '<a class="certified-button w-100 text-primary font-md text-center font-weight-bold text-link" href="'.site_url().'/wp-content/uploads/2025/07/Why-Choose-CPO-Vehicles.pdf" target="_blank">Why choose certified Pre-Owned Vehicles</a>'.
                          '</div>';
            echo $certified;
        }
    }
}

function vehicleHighlightedFeatures() {
    $featuresList = array(
        'Roof rack Rails',
        'Alarm Security',
        'Spoiler',
        'Exterior parking Camera Rear',
        'SiriusXM| Radio',
        'Tow hitch',
        'Warning Occupant sensing',
        'Parking assist',
        "Memory Driver's seat",
        'Keyless entry',
        'Front fog lights',
        'Universal Garage Door open',
        'Auto High beam highlights',
        'Low tire pressure alert',
        'Lane departure warning system',
        'Stability Control',
        'Touch screen Navigation',
        'Rear Window Defroster',
        'Dual front impact Airbags',
        'Heated Seats',
        'Apple Carplay/ Android',
        'Bluetooth',
        'Power Moonroof',
        'GPS Navigation',
        'Split folding rear seat',
        'Heated Steering wheel',
        'Power Liftgate',
        'Remote Start System w/Remote'
    );
    
    $featuresList = array_unique($featuresList);
    
    shuffle($featuresList);
    
    $featureElems = '<div class="mt-5 pt-2 features-container">' .
                    '<h3 class="mb-30 font-inter font-xxl p-0 font-inter text-fifth font-weight-bold">Highlighted Features</h3>' .
                    '<div class="row">';
    
    foreach (array_slice($featuresList, 0, 6) as $feature) {
        $featureElems .= '<div class="col-12 col-md-6 col-lg-4 feature-block">' .
                        '<span class="bg-white py-20 px-15 feature-card d-block font-inter font-20 text-dark text-capitalize">' .
                        '<i class="fa-solid fa-car-side text-primary mr-2"></i>' .
                        htmlspecialchars($feature) .
                        '</span>' .
                        '</div>';
    }
    
    $featureElems .= '</div>' .
                    '</div>';
    echo $featureElems;
}


function vehicleHistoryReport($vin) {
    $velocityEngageImage = wp_get_attachment_image_src(get_field('velocity_engage_badge_image', 'options'), 'full')[0];
    $velocityEngageWidth =  wp_get_attachment_image_src(get_field('velocity_engage_badge_image', 'options'), 'full')[1];
    $velocityEngageHeight = wp_get_attachment_image_src(get_field('velocity_engage_badge_image', 'options'), 'full')[2];
    $velocityEngageAlt =  get_post_meta(get_field('velocity_engage_badge_image','options'), '_wp_attachment_image_alt', true);
    $windowStickerImage = wp_get_attachment_image_src(get_field('window_sticker_badge_image', 'options'), 'full')[0];
    $windowStickerWidth =  wp_get_attachment_image_src(get_field('window_sticker_badge_image', 'options'), 'full')[1];
    $windowStickerHeight = wp_get_attachment_image_src(get_field('window_sticker_badge_image', 'options'), 'full')[2];
    $windowStickerAlt =  get_post_meta(get_field('window_sticker_badge_image','options'), '_wp_attachment_image_alt', true);
    $carfaxImage = wp_get_attachment_image_src(get_field('carfax_badge_image_group', 'options'), 'full')[0];
    $carfaxWidth =  wp_get_attachment_image_src(get_field('carfax_badge_image_group', 'options'), 'full')[1];
    $carfaxHeight = wp_get_attachment_image_src(get_field('carfax_badge_image_group', 'options'), 'full')[2];
    $carfaxAlt =  get_post_meta(get_field('carfax_badge_image_group','options'), '_wp_attachment_image_alt', true);

    $history = '<div class="mt-5 pt-2">'. // top spacing should be mt-4 pt-3
    '<h2 class="font-inter font-xxl text-fifth mb-30 pb-0 font-weight-bold">Vehicle History Report</h2>'.
    '<div class="container-fluid">'.
    '<div class="row align-items-center">'.
    '<a href="https://app.velocityengage.com/'. $vin .'?source=dealerdotcom&accountId=durangomotorcompany&embedded=true" target="_blank" class="single-listing-cta__dead col-6 col-md-3 d-none" data-name="velocity" data-vas-vin="'.$vin.'">'. 
    '<img src="'.$velocityEngageImage.'" alt="'.$velocityEngageAlt.'" width="'.$velocityEngageWidth.'" height="'.$velocityEngageHeight.'"
    class="img-fluid" loading="eager" itemprop="image" /></a>'.
    '<a href="https://windowsticker.velocityengage.com/vin/'. $vin .'/account/durangofordfd?source=Dealer%20Website" target="_blank" class="single-listing-cta__dead col-6 col-md-3" data-name="window" data-vas-vin="'. $vin .'">'. 
    '<img src="'.$windowStickerImage.'" alt="'.$windowStickerAlt.'" width="'.$windowStickerWidth.'" height="'.$windowStickerHeight.'" 
    class="img-fluid" loading="eager" itemprop="image" /></a>'.
    '<a href="http://www.carfax.com/VehicleHistory/p/Report.cfx?partner=DVW_1&vin='. $vin .'" target="_blank" class="single-listing-cta__dead col-6 col-md-3 mt-2 mt-md-0" data-name="carfax" data-vas-vin="'.$vin.'">'. 
    '<img src="'.$carfaxImage.'" alt="'.$carfaxAlt.'" width="'.$carfaxWidth.'" height="'.$carfaxHeight.'" 
    class="img-fluid" loading="eager" itemprop="image" /></a>'.
    '</div>'.
    '</div>'.
    '</div>';

    echo $history;
}


function vehicleDescription($description, $post_id = null) {
    // --- Detect and remove the special phrase anywhere in the text ---
    $special_phrase = '';
    if (!empty($description) && preg_match('/(One low Price, Plain and Simple Always!!)/i', $description, $m)) {
        $special_phrase = $m[1];
        $description = str_ireplace($m[1], '', $description);
        $description = trim(preg_replace('/\s+/', ' ', $description));
    }

    if (empty(trim($description))) {
        echo '<h2 class="font-20 font-inter font-xxl pb-0 mb-3 text-grey-5 mt-5 pt-2 font-weight-bold">Description</h2>
        <div class="description-box bg-grey-7 p-30">
            <p class="font-inter font-20 text-fifth font-weight-normal">
                To get more information on this vehicle call us 
                <a href="tel:' . get_field('quick_call_phone_number', 'options') . '" class="quick-call-link text-sixth">
                    <i class="fa fa-phone text-sixth"></i>
                </a>
            </p>
        </div>';
        return;
    }

    if (!$post_id && isset($GLOBALS['post']->ID)) {
        $post_id = $GLOBALS['post']->ID;
    }

    $condition = get_post_meta($post_id, 'condition', true);
    if (strtolower($condition) !== 'u') {
        echo '<h2 class="font-20 font-inter font-xxl pb-0 mb-3 text-grey-5 mt-5 pt-2 font-weight-bold">Description</h2>
        <div class="description-box bg-grey-7 p-30">
            <p class="font-inter font-20 text-fifth font-weight-normal">' . nl2br(esc_html($description)) . '</p>
        </div>';
        return;
    }

    // --- Clean up text ---
    $description = preg_replace('/\s+/', ' ', trim($description));
    $description = preg_replace('/\.(?=[A-Z])/', '. ', $description);
    $description = preg_replace('/\s+([.,!?:;])/', '$1', $description);
    $description = preg_replace('/\b([A-Za-z]{2,})\.\s*com\b/i', '$1.com', $description);

    // --- Detect sentence that contains "Certified Details:" and split from last period before it ---
    if (preg_match('/Certified[^:]*Details:/i', $description, $match, PREG_OFFSET_CAPTURE)) {
        $cert_pos = $match[0][1]; // Position where Certified Details starts
        $before_part = substr($description, 0, $cert_pos);

        // Find the position of the last period before "Certified Details"
        $last_period_pos = strrpos($before_part, '.');
        if ($last_period_pos !== false) {
            $before = trim(substr($description, 0, $last_period_pos + 1)); // include that period
            $after = trim(substr($description, $last_period_pos + 1));     // rest including Certified Details
        } else {
            // If no period found, treat entire string before Certified Details as intro
            $before = trim($before_part);
            $after  = trim(substr($description, $cert_pos));
        }

        // Extract the heading phrase (the sentence containing "Certified Details:")
        if (preg_match('/([^.]*Certified[^:]*Details:)/i', $after, $heading_match)) {
            $full_heading = trim($heading_match[1]);
            // Split "after" part after heading
            $after_parts = preg_split('/' . preg_quote($full_heading, '/') . '/i', $after, 2);
            $after = isset($after_parts[1]) ? trim($after_parts[1]) : '';
        } else {
            $full_heading = 'Certified Details:';
        }

        // ✅ Improved paragraph logic
        $sentences = preg_split('/(?<=[.!?])\s+(?=[A-Z])/', $before);
        $first_para = '';
        $second_para = '';

        foreach ($sentences as $s) {
            $s = trim($s);
            if (preg_match('/\b(We take your satisfaction|Here are our promises|Take the stress out)/i', $s)) {
                $second_para .= ' ' . $s;
            } else {
                if (empty($second_para)) {
                    $first_para .= ' ' . $s;
                } else {
                    $second_para .= ' ' . $s;
                }
            }
        }

        $before_html  = '';
        if (!empty($first_para)) {
            $before_html .= '<p class="font-inter font-20 text-fifth font-weight-normal mb-3">' . esc_html(trim($first_para)) . '</p>';
        }
        if (!empty($second_para)) {
            $before_html .= '<p class="font-inter font-20 text-fifth font-weight-normal mb-3">' . esc_html(trim($second_para)) . '</p>';
        }

        // --- Handle Certified Details list ---
        $after = preg_replace('/\*\s*/', "\n", $after);
        $after = preg_replace('/(\d),(\d)/', '$1{{NUM_COMMA}}$2', $after);
        $after = preg_replace('/\b([A-Z])\. ([A-Z])\./', '$1{{DOT}} $2{{DOT}}', $after);
        $after = preg_replace('/\.\s+(?=[A-Z])/', "\n", $after);
        $after = preg_replace('/,\s+(?=[A-Z])/', "\n", $after);
        $after = str_replace(['{{NUM_COMMA}}', '{{DOT}}'], [',', '.'], $after);

        $lines = preg_split('/[\r\n]+/', trim($after));
        $list_items = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $list_items[] = ucfirst($line);
        }

        $list_html = '<ul class="vehicle-description-list font-inter font-20 text-fifth font-weight-normal">';
        foreach ($list_items as $li) {
            $list_html .= '<li>' . esc_html($li) . '</li>';
        }
        $list_html .= '</ul>';

        $description = $before_html .
            '<h3 class="font-inter font-22 font-weight-semibold mt-3 mb-2">' . esc_html($full_heading) . '</h3>' .
            $list_html;
    }

    // --- Append special phrase if exists ---
    if (!empty($special_phrase)) {
        $description .= '<p class="font-inter font-20 text-fifth font-weight-normal mt-3 mb-0">' . esc_html($special_phrase) . '</p>';
    }

    // --- Output ---
    $output  = '<h2 class="font-20 font-inter font-xxl pb-0 mb-3 text-grey-5 mt-5 pt-2 font-weight-bold">Description</h2>';
    $output .= '<div class="description-box bg-grey-7 p-30">';
    $output .= $description;
    $output .= '</div>';
    echo $output;
}




function filterPills($categories) {
    $pills = '<ul class="position-relative">'.
              '<div class="filter-pills-inner-wrapper d-flex align-items-center flex-wrap mt-20">';
    foreach ($categories as $url => $category) {
        if( !empty($category) ) {
            $pills .= '<li class="mr-20 mb-20">' .
            '<a href="' . site_url() . '/used-vehicles-durango-colorado' . $url . '" class="bg-grey-8 font-inter text-uppercase font-weight-normal filter_pills rounded-circle-px font-sm text-sixth py-1 px-30">' . $category . '</a>' .
            '</li>';
        }
    }
    $pills .= '</div>'.
              '<span class="position-absolute filter-pills-cta cursor-pointer d-inline-block show-all-filters font-inter text-sixth">Show All Filters</span>'.
              '</ul>';

    echo $pills;
}
function vehicleDetailsAccordion($details) {
    $halfdetails = floor(count($details) / 2);
    $accordion = '<h2 class="mt-5 pt-2 font-inter text-fifth font-xxl mb-3 pb-0 font-weight-bold">Vehicle Details</h2>';
    $accordion .= '<div class="vdp-accordion vehicleDetailsAccordion" id="vehicleDetailsAccordion">
    <div class="accordion">
    <div class="card rounded-0">
    <div class="card-header cursor-pointer py-15 px-30 bg-primary d-flex align-items-center justify-content-between" id="headingheader" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
    <h2 class="mb-0 p-0 text-white font-20 font-inter font-weight-normal">Details</h2>
    <i class="fa-solid fa-plus text-white font-lg"></i>
    <i class="fa-solid fa-minus text-white font-lg"></i>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingheader" data-parent="#vehicleDetailsAccordion">
    <div class="card-body p-30">
    <div class="d-flex justify-content-between accordion-tables-container flex-column flex-md-row">
    <div class="table-responsive">
    <table class="table table-bordered mb-0">';
    $i = 0;
    foreach ($details as $key => $value) {
        if ($i < $halfdetails) {
            if( !empty($value) ) {
                $accordion .= '<tr class="d-flex flex-column d-md-table-row">
                <td class="text-capitalize bg-grey-7 p-15 font-inter font-md text-fifth">'. $key .'</td>
                <td class="p-15  font-inter text-fifth font-md">'. $value .'</td>
                </tr>';
                $i++;
            }
        }
    }
    $accordion .= '</table></div>
    <div class="table-responsive">
    <table class="table table-bordered mb-0">';
    $i = 0;
    foreach ($details as $key => $value) {
        if( $i <= $halfdetails ) {
            $i++;
            continue;
        }else {
            if( !empty($value) ) {
                $accordion .= '<tr class="d-flex flex-column d-md-table-row">
                <td class="text-capitalize bg-grey-7 p-15 font-inter font-md text-fifth">'. $key .'</td>
                <td class="p-15  font-inter text-fifth font-md">'. $value .'</td>
                </tr>';
            }
        }
    }
    
    $accordion .= '</table></div>
    </div>';
      
    $accordion .= '</div>
    </div>
    </div>
    </div>
    </div>';
    
    echo $accordion;
}
function vehicleEquipmentDetails($features) {
    $featuresExplode = explode('|', $features);

    $accordion = '<div class="vdp-accordion vehicleEquipmentAccordion" id="vehicleEquipmentAccordion">
    <div class="accordion">
    <div class="card rounded-0">
    <div class="card-header cursor-pointer py-15 px-30 bg-primary d-flex align-items-center justify-content-between"
    id="headingheader2" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
    <h2 class="mb-0 p-0 text-white font-20 font-inter font-weight-normal">Equipment Details</h2>
    <i class="fa-solid fa-plus text-white font-lg"></i>
    <i class="fa-solid fa-minus text-white font-lg"></i>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingheader2" data-parent="#vehicleEquipmentAccordion">
    <div class="card-body p-30">
    <div class="row">';
    foreach( $featuresExplode as $feature ) {
        $accordion .= '<div class="col-6 col-md-4 font-weight-bold mb-2 text-fifth font-segoe">'.$feature.'</div>';
    }
    // Add your content here within the card-body
    $accordion .= '</div></div>
    </div>
    </div>
    </div>
    </div>';
    
    echo $accordion;
}
function vehicleDisclaimer($disclaimer) {
    $disclaimerText = '<h2 class="font-inter font-xxl text-fifth pb-0 mt-5 pt-2 mb-3 font-weight-bold">Disclaimer</h2>'.
                      '<p class="text-fifth font-md font-inter mb-4 pb-3">'.$disclaimer.'</p>';

                      echo $disclaimerText;
}

function vehicleLightboxSlider($vin_number = '', $model = '') {
    error_log("DEBUG vehicleLightboxSlider: Function called with vin_number='{$vin_number}', model='{$model}'");
    /** Connect to external database */
    $external_connection = get_db_connection();
    error_log("DEBUG vehicleLightboxSlider: Database connection " . ($external_connection ? "successful" : "failed"));
    
    /** Get max batch number */
    $batch_query = "SELECT MAX(batch_number) AS batch_number FROM dmc_images";
    $batch_result = $external_connection->query($batch_query);
    if (!$batch_result) {
        error_log("DEBUG vehicleLightboxSlider: Batch query failed - " . $external_connection->error);
        die("Query failed: " . $external_connection->error);
    }
    $batch_row = $batch_result->fetch_assoc();
    $max_batch = $batch_row['batch_number'];
    error_log("DEBUG vehicleLightboxSlider: Max batch number = {$max_batch}");
    $select_query = "SELECT vauto_url FROM dmc_images WHERE vin = ?";
    $stmt = $external_connection->prepare($select_query);

    $image_urls = [];
    if ($stmt) {
        $stmt->bind_param("s", $vin_number);
        $stmt->execute();
        $result = $stmt->get_result();
        error_log("DEBUG vehicleLightboxSlider: Query executed for VIN '{$vin_number}', rows found: " . $result->num_rows);

        while ($row = $result->fetch_assoc()) {
            if (!empty($row['vauto_url'])) {
                $image_urls[] = $row['vauto_url'];
            }
        }
        $stmt->close();
        error_log("DEBUG vehicleLightboxSlider: Image URLs collected: " . count($image_urls));
    } else {
        error_log("DEBUG vehicleLightboxSlider: Statement preparation failed");
    }

    $urlToRemove = 'http://vehicle-photos-published.vauto.com/04/db/a3/0f-009d-4d84-ba0a-fe04a042c1d5/image-1.jpg';

    // Remove placeholder URL if it's the only or repeated entry
    if (!empty($image_urls)) {
        $urlCount = array_count_values($image_urls)[$urlToRemove] ?? 0;
        if ($urlCount > 0) {
            if (count(array_unique($image_urls)) === 1) {
                $image_urls = []; // Clear if only placeholder URLs
            } else {
                $image_urls = array_filter($image_urls, fn($image) => $image !== $urlToRemove);
                $image_urls = array_values($image_urls); // Reindex
            }
        }
    }

    // Check if no featured image
    $has_featured_image = !empty($image_urls);
    error_log("DEBUG vehicleLightboxSlider: has_featured_image = " . ($has_featured_image ? "true" : "false"));

    // If no featured image, find a matching JellyB URL based on model
    if (!$has_featured_image) {
        error_log("DEBUG vehicleLightboxSlider: No featured image, checking model fallback");
        // Fetch model dynamically if not provided
        if (empty($model)) {
            $model = get_post_meta(get_the_ID(), 'model', true);
            error_log("DEBUG vehicleLightboxSlider: Model from post meta = '{$model}'");
        }
        if ($model) {
            $jellyB_img_urls = dmc_get_image_urls();
            error_log("DEBUG vehicleLightboxSlider: JellyB URLs count = " . count($jellyB_img_urls));
            // Normalize model name (replace spaces with hyphens, lowercase)
            $normalized_model = strtolower(str_replace(' ', '-', $model));
            error_log("DEBUG vehicleLightboxSlider: Searching for normalized model '{$normalized_model}'");
            foreach ($jellyB_img_urls as $jelly_url) {
                // Extract filename from URL
                $filename = basename($jelly_url, '.png');
                // Remove year and color parts (e.g., "2025-" and "--Midnight-Black-Metallic")
                $filename_parts = explode('--', $filename);
                $filename_model = $filename_parts[0];
                $filename_model = preg_replace('/^\d{4}-/', '', $filename_model);
                $normalized_filename = strtolower($filename_model);
                if (strpos($normalized_filename, $normalized_model) !== false) {
                    $image_urls = [$jelly_url]; // Use first matching URL
                    error_log("DEBUG vehicleLightboxSlider: Found model match in JellyB: {$jelly_url}");
                    break;
                }
            }
        }
    }

    if (empty($image_urls)) {
        error_log("DEBUG vehicleLightboxSlider: No image URLs found, returning early");
        return;
    }
    
    error_log("DEBUG vehicleLightboxSlider: Final image URLs count = " . count($image_urls)); ?>
    <div class="lightbox-slider-container d- h-100 position-absolute top-0 left-0 w-100">
        <div class="lightbox-slider-innercontainer">
            <div class="lightbox-image-slider">
                <?php foreach ($image_urls as $index => $image) : ?>
                    <div class="lightbox-image-slide position-relative">
                        <img data-src="<?php echo esc_url($image); ?>"
                             width="767"
                             height="560"
                             loading="eager"
                             decoding="async"
                             title="<?php echo esc_attr(get_the_title()); ?>"
                             alt="<?php echo esc_attr(get_the_title()); ?>" />
                        <div class="bg-white position-absolute slider-counter-wrapper p-1">
                            <span class="slider-current-count text-dark font-inter font-xs">
                                <?php echo ($index + 1); ?>
                            </span>
                            <span class="slider-counter-divider text-dark font-inter font-xs">/</span>
                            <span class="slider-total-counter text-dark font-inter font-xs">
                                <?php echo esc_html(count($image_urls)); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="lightbox-thumb-slider-wrapper w-100 bg-light p-3 position-absolute">
                <div class="lightbox-thumb-inner-wrapper">
                    <?php foreach ($image_urls as $index => $image) : ?>
                        <div class="lightbox-thumb-slider position-relative">
                            <img data-src="<?php echo esc_url($image); ?>"
                                 width="300"
                                 height="200"
                                 loading="eager"
                                 decoding="async"
                                 title="<?php echo esc_attr(get_the_title()); ?>"
                                 alt="<?php echo esc_attr(get_the_title()); ?>" />
                            <div class="bg-white position-absolute slider-counter-wrapper p-1">
                                <span class="slider-current-count text-dark font-inter font-xs">
                                    <?php echo esc_html($index + 1); ?>
                                </span>
                                <span class="slider-counter-divider text-dark font-inter font-xs">
                                /
                                </span>
                                <span class="slider-total-counter text-dark font-inter font-xs">
                                    <?php echo esc_html(count($image_urls)); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div> <?php
}
