<?php

function dmc_kia_inventory() {
    $output = '';

    // Step 1: Query Listings Based on Make = Kia (case-insensitive)
    $args = array(
        'post_type'      => 'listings',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'make',
                'value'   => array('Kia', 'KIA', 'kia'),
                'compare' => 'IN',
            ),
        ),
    );

    $kiaQuery = new WP_Query($args);
    $vins = [];

    if ($kiaQuery->have_posts()) {
        while ($kiaQuery->have_posts()) {
            $kiaQuery->the_post();
            $vin = get_post_meta(get_the_ID(), 'vin-number', true);
            if ($vin) {
                $vins[get_the_ID()] = $vin; // Map post ID to VIN
            }
        }
        wp_reset_postdata();
    }

    $image_urls = [];
    if (!empty($vins)) {
        $connection = get_db_connection();
        if ($connection) {
            foreach ($vins as $post_id => $vin) {
                $query = "SELECT vauto_url FROM dmc_images WHERE vin = ? ORDER BY id ASC LIMIT 1";
                $stmt = $connection->prepare($query);
                if ($stmt) {
                    $stmt->bind_param('s', $vin);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        if (!empty($row['vauto_url'])) {
                            $image_urls[$vin] = $row['vauto_url'];
                        }
                    }
                    $stmt->close();
                }
            }
        }
    }

    // Step 3: Generate Output with enhanced vehicle information
    if (!empty($vins)) {
        // Add CSS styles scoped to kia-inventory-row only
        $output .= '<style>
            .kia-inventory-row .listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li {
                width: 16px !important;
                height: 16px !important;
            }
            .kia-inventory-row .listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li button {
                width: 16px !important;
                height: 16px !important;
            }
            .kia-inventory-row .listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li.slick-active {
                width: 24px !important;
            }
            .kia-inventory-row .listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li.slick-active button {
                width: 24px !important;
                height: 16px !important;
                border-radius: 8px !important;
                background-color: #3e8873 !important;
                border-color: #3e8873 !important;
            }
            .kia-inventory-row .btn:hover{
                border-color: #fffbfc!important;
            }
        </style>';
        
        $output .= '<div class="row kia-inventory-row">';
        
        // Get compare vehicles for current user
        global $wpdb;
        $user_ip = getUserIP();
        $compared_vehicles = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}user_compared_vehicles WHERE user_ip = %s", $user_ip), ARRAY_A);
        $compareVehiclesResult = $compared_vehicles[0] ?? [];
        $compareVehicleIDs = !empty($compareVehiclesResult['user_compared_vehicles']) 
            ? maybe_unserialize($compareVehiclesResult['user_compared_vehicles']) : [0];
        
        foreach ($vins as $post_id => $vin) {
            $specialImage = isset($image_urls[$vin]) ? $image_urls[$vin] : 'default-image.jpg';
            
            // Get vehicle metadata
            $stock_number = get_post_meta($post_id, 'stock-number', true);
            $vin_number = get_post_meta($post_id, 'vin-number', true);
            $odometer = get_post_meta($post_id, 'odometer', true);
            $certified = get_post_meta($post_id, 'certified', true);
            $drivetrain = get_post_meta($post_id, 'drivetrain', true);
            $condition = get_post_meta($post_id, 'condition', true);
            $year = get_post_meta($post_id, 'year', true);
            $make = get_post_meta($post_id, 'make', true);
            $model = get_post_meta($post_id, 'model', true);
            $series = get_post_meta($post_id, 'series', true);
            $current_price = (int)get_post_meta($post_id, 'current_price', true);
            $original_price = (int)get_post_meta($post_id, 'original_price', true);
            
            // Price logic
            if ($original_price === 0 && $current_price !== 0) {
                $original_price = $current_price;
            }
            
            // Check if Toyota for special pricing
            $is_toyota = strtolower($make) === 'toyota' && $condition === 'N';
            $disposition = (int)get_post_meta($post_id, 'disposition', true);
            
            $permalink = esc_url(get_permalink($post_id));
            $title = esc_html(get_the_title($post_id));
            $checked = in_array($post_id, $compareVehicleIDs) ? 'checked' : '';
            
            // Condition text
            $condition_text = ($condition === 'N') ? 'NEW' : (($condition === 'U') ? 'USED' : esc_html($condition));
            
            $output .= '<div class="col-12 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-30">
                <div class="position-relative mb-3 mb-md-0 bg-white listing-card-wrapper new-product-card" data-permalink="' . $permalink . '" style="padding: 0px;">
                    <div class="card-image-wrapper overflow-hidden">

                        <div class="listing-image-slider MySlider overflow-hidden position-relative">
                            <div class="listing-image-slider-inner h-100 w-100">
                                <a href="' . $permalink . '" class="d-inline-block h-100">
                                    <img src="' . esc_url($specialImage) . '" alt="' . esc_attr($title) . '" title="' . esc_attr($title) . '" loading="lazy" decoding="async" class="card-thumbnail" width="365" height="270" style="padding:10px 10px;width:100%;height:100%;object-fit: fill;" />
                                </a>
                            </div>
                        </div>
                        <hr style="border: 1px solid #efe7e7!important;">
                        <div class="compare-pin-wrapper d-flex align-items-center justify-content-between px-10 py-2">
                            <button type="button" class="btn-compare-manager d-flex align-items-center gap-2" style="background: #3f8873; color: white; border: none; padding: 2px 16px; border-radius:15px; font-weight: bold; font-size: 13px; cursor: pointer;" onclick="document.getElementById(\'chk-compare-' . $post_id . '\').click();">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="-9 -9 18 18" width="14" height="14" style="margin-right: 6px; flex-shrink: 0;">
                                <path fill="none" stroke="#fff" stroke-miterlimit="100" d="M0 8.5c-4.7 0-8.5-3.8-8.5-8.5 0-4.7 3.8-8.5 8.5-8.5 4.7 0 8.5 3.8 8.5 8.5 0 4.7-3.8 8.5-8.5 8.5z"/>
                                <path fill="none" stroke="#fff" stroke-miterlimit="100" d="M0 -4.5 V4.5"/>
                                <path fill="none" stroke="#fff" stroke-miterlimit="100" d="M-4.5 0 H4.5"/>
                            </svg>
                                <span>COMPARE</span>
                            </button>
                            <form class="inventory-products-bar__compare-listing-form d-flex align-items-center" style="display: none;">
                                <input type="checkbox" id="chk-compare-' . $post_id . '" class="chk-compare position-relative bg-white" 
                                       value="' . $post_id . '" ' . $checked . ' style="display:none!important;" />
                            </form>
                            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 30" width="18" height="18" style="cursor: pointer; fill: #3f8873;height:24px;width:24px;">
                                <defs><clipPath clipPathUnits="userSpaceOnUse" id="cp1-' . $post_id . '"><path d="m-290-341h340v850h-340z"/></clipPath></defs>
                                <g clip-path="url(#cp1-' . $post_id . ')">
                                    <path fill-rule="evenodd" d="m13.5 17.7v-6.9c-2.4-0.6-4.1-2.8-4.1-5.3 0-3 2.4-5.5 5.4-5.5 3.1 0 5.5 2.5 5.5 5.5 0 2.5-1.7 4.7-4.1 5.3v6.9c0 4.3-0.8 6.6-1.4 6.6-0.6 0-1.3-2.3-1.3-6.6zm1.6-13.8c0-1-0.8-1.9-1.8-1.9-1 0-1.9 0.9-1.9 1.9 0 1 0.9 1.9 1.9 1.9 1 0 1.8-0.9 1.8-1.9zm-14.2 19.5c0-3.9 6.3-6.3 10.3-6.3v2.3c-2.8 0-7.2 1.5-7.2 3.7 0 2.3 4.1 4.2 10.8 4.2 6.7 0 10.8-2 10.8-4.2 0-2.2-4.3-3.7-7.1-3.7v-2.3c4 0 10.2 2.4 10.2 6.3 0 3.3-5.1 6.3-13.9 6.3-8.8 0-13.9-3-13.9-6.3z"/>
                                </g>
                            </svg>
                        </div>
                        <div class="card-content-wrapper px-10 position-relative">
                            <div class="vehicle-title-wrapper my-3" style="margin-bottom: 0 !important;">
                                <h4 class="text-uppercase font-md vehicle-title-first text-grey-3" style="font-weight: 600; font-size: 15px;">
                                    ' . esc_html($condition_text) . ' ' . esc_html($year) . '
                                </h4>
                                <a href="' . $permalink . '" class="no-style">
                                    <h2 class="vehicle-title-second font-helvetica p-0 m-0 font-weight-bold" style="color: #3f8873; font-size: 17px; font-weight: 700;">
                                        ' . esc_html($make) . ' ' . esc_html($model) . ' ' . esc_html($series) . '
                                    </h2>
                                </a>
                            </div>
                            <div class="vehicle-meta-block mb-2 mt-3 d-flex align-items-center" style="justify-content: flex-start;">
                                <span class="font-weight-bold font-sm" style="color: #454545;">STOCK #: ' . esc_html($stock_number) . '</span>
                                <i class="fa-regular fa-copy ml-2 cursor-pointer" style="color: #3f8873; font-size: 16px;" 
                                   onclick="navigator.clipboard.writeText(\'' . esc_js($stock_number) . '\')"></i>
                            </div>
                            <div class="vehicle-meta-block mb-2 d-flex align-items-center" style="justify-content: flex-start;">
                                <span class="font-weight-bold font-sm" style="color: #454545;">MILEAGE: ' . (is_numeric($odometer) ? esc_html(number_format((int)$odometer)) : '') . '</span>
                            </div>
                            <div class="vehicle-meta-block mb-2 d-flex align-items-center" style="justify-content: flex-start;">
                                <span class="font-weight-bold font-sm" style="color: #454545;">CERTIFIED: ' . esc_html($certified ? $certified : '') . '</span>
                            </div>
                            <div class="vehicle-meta-block mb-3 d-flex align-items-center" style="justify-content: flex-start;">
                                <span class="font-weight-bold font-sm" style="color: #454545;">DRIVETRAIN: ' . esc_html($drivetrain ? $drivetrain : '') . '</span>
                            </div>
                            <div class="pricing-section my-3">
                                <div class="d-flex align-items-center justify-content-between font-weight-bold font-lg w-100" style="color: #3e8873;">
                                    <span>' . esc_html( 'Our Best Price' ) . '</span>
                                    <span>
                                        ' . ($is_toyota && !empty($disposition) ? '$ ' . esc_html(number_format((int)($original_price - $disposition))) : ($is_toyota ? '$ ' . esc_html(number_format((int)$original_price)) : '$ ' . esc_html(number_format((int)$original_price)))) . '
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btnWraper px-10 pb-10 mb-3" style="position:unset!important;transform:none!important;width:100%!important;margin-top: 1.25rem !important;min-height: auto;">
                        <a href="' . $permalink . '"
                           class="btn w-100 d-inline-block font-weight-bold rounded text-center text-uppercase"
                           style="background: #17453b; color: #fff; font-family: \'Helvetica Neue\', Helvetica, sans-serif; padding: 12px; text-decoration: none; border-radius: 9px !important;">
                            ' . esc_html( 'View Details' ) . '
                        </a>
                    </div>
                </div>
            </div>';
        }
        $output .= '</div>';
    } else {
        $output .= '<p class="text-center text-muted">Sorry, no Kia vehicles available at the moment.</p>';
    }

    return $output;
}

add_shortcode('kia_inventory', 'dmc_kia_inventory');