<?php

function dmc_manager_specials() {
    $stockNumbers = get_field('managers_specials_vehicles_stock_number', 'options');
    $output = '';

    if ($stockNumbers && !empty($stockNumbers)) {
        $stockNumbers = array_map('trim', explode(',', $stockNumbers));

        // Step 1: Query Listings Based on Stock Numbers
        $args = array(
            'post_type'      => 'listings',
            'posts_per_page' => -1,
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'stock-number',
                    'value'   => $stockNumbers,
                    'compare' => 'IN',
                ),
            ),
        );

        $specialsQuery = new WP_Query($args);
        $vins = [];

        if ($specialsQuery->have_posts()) {
            while ($specialsQuery->have_posts()) {
                $specialsQuery->the_post();
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
            $output .= '<div class="row manager-specials-row">';
            foreach ($vins as $post_id => $vin) {
                $specialImage = isset($image_urls[$vin]) ? $image_urls[$vin] : 'default-image.jpg';
                
                // Get vehicle metadata (copied from productCard function)
                $stock_number = get_post_meta($post_id, 'stock-number', true);
                $vin_number = get_post_meta($post_id, 'vin-number', true);
                $odometer = get_post_meta($post_id, 'odometer', true);
                $certified = get_post_meta($post_id, 'certified', true);
                $drivetrain = get_post_meta($post_id, 'drivetrain', true);
                $exteriorColor = strtolower(trim(get_post_meta($post_id, 'exterior-color', true)));
                $interiorColor = strtolower(trim(get_post_meta($post_id, 'interior-color', true)));
                
                // Vehicle price logic (copied from productCard function)
                $vehiclePrice = (int)get_post_meta($post_id, 'original_price', true);
                $vehiclePriceHTML = $vehiclePrice ? 
                    '<h3 class="p-0 m-0 font-helvetica font-lg font-weight-bold text-dark">$ '.number_format($vehiclePrice).'</h3>' : 
                    '<a class="font-sm text-dark font-weight-bold font-helvetica" href="tel:'.salesPhoneNumber().'">Call For Price</a>';
                
                $permalink = esc_url(get_permalink($post_id));
                $title = esc_html(get_the_title($post_id));

                $output .= '<div class="col-12 col-sm-6 col-md-6 col-xl-3 px-10 mb-20">
                    <div class="manager-special-card position-relative h-100">
                        <a href="' . $permalink . '" class="d-inline-block position-relative manager-special-thumbnail w-100">
                            <img src="' . esc_url($specialImage) . '" alt="Manager Special Vehicle" loading="lazy" class="img-fluid w-100 h-100 object_fit_cover" />
                            <div class="position-absolute">
                                <p class="color_black font-lg font-weight-bold font-segoe">Manager Specials</p>
                            </div>
                        </a>
                        <a class="d-inline-block mt-3" href="' . $permalink . '">
                            <h2 class="font-weight-bold text-grey-3 font-xxl p-0">' . $title . '</h2>
                        </a>';
                
                
                // ADDED: Vehicle metadata section (copied from productCard function)
                $output .= '<div class="vehicle-meta-wrapper mb-20">';
                
                // Stock Number
                $output .= '<div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-grey-3 font-weight-bold">Stock #</span>
                    <span class="text-grey-3 font-weight-bold">' . ($stock_number ? $stock_number : '') . '</span>
                </div>';
                
                // Mileage
                $output .= '<div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-grey-3 font-weight-bold">Mileage</span>
                    <span class="text-grey-3 font-weight-bold">' . 
                    (is_numeric($odometer) ? number_format($odometer) : '') . '</span>
                </div>';
                
                // Certified
                $output .= '<div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-grey-3 font-weight-bold">Certified</span>
                    <span class="text-grey-3 font-weight-bold">' . 
                    ($certified ? $certified : '') . '</span>
                </div>';
                
                // Drivetrain
                $output .= '<div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-grey-3 font-weight-bold">Drivetrain</span>
                    <span class="text-grey-3 font-weight-bold">' . 
                    ($drivetrain ? $drivetrain : '') . '</span>
                </div>';
                
                $output .= '</div>';
                
                // Price section (updated with productCard logic)
                $output .= '<div class="d-flex align-items-center justify-content-between mt-5">
                    <strong class="font-weight-bold text-grey-3 font-xl">Our Best Price</strong>
                    ' . $vehiclePriceHTML . '
                </div>';
                
                $output .= '<a class="btn btn-primary manager-special-view-vehicle font-weight-bold font-20 p-3 rounded-10 text-white text-capitalize position-absolute" href="' . $permalink . '" style="text-decoration:none;">View Details</a>
                    </div>
                </div>';
            }
            $output .= '</div>';
        } else {
            $output .= '<p class="text-center text-muted">Sorry, no manager specials available at the moment.</p>';
        }
    }

    return $output;
}

add_shortcode('manager_specials_vehicles', 'dmc_manager_specials');

function dmc_ev_specials() {
    $stockNumbers = get_field('ev_specials_vehicles_stock_number', 'options');
    $output = '';

    if ($stockNumbers && !empty($stockNumbers)) {
        $stockNumbers = array_map('trim', explode(',', $stockNumbers));

        // Step 1: Query Listings Based on Stock Numbers
        $args = array(
            'post_type'      => 'listings',
            'posts_per_page' => -1,
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'stock-number',
                    'value'   => $stockNumbers,
                    'compare' => 'IN',
                ),
            ),
        );

        $specialsQuery = new WP_Query($args);
        $vins = [];

        if ($specialsQuery->have_posts()) {
            while ($specialsQuery->have_posts()) {
                $specialsQuery->the_post();
                $vin = get_post_meta(get_the_ID(), 'vin-number', true);
                if ($vin) {
                    $vins[get_the_ID()] = $vin; // Map post ID to VIN
                }
            }
            wp_reset_postdata();
        }

        // Step 2: Fetch Image URLs from External DB
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

        // Step 3: Generate Output
        if (!empty($vins)) {
            $output .= '<div class="row manager-specials-row">'; // class name remains unchanged
            foreach ($vins as $post_id => $vin) {
                $specialImage = isset($image_urls[$vin]) ? $image_urls[$vin] : 'default-image.jpg';
                $priceMeta = get_post_meta($post_id, 'original_price', true);
                $vehiclePrice = ($priceMeta && $priceMeta !== 'None') ? '$' . number_format((int) $priceMeta) : '<a href="tel:' . get_field('quick_call_phone_number', 'options') . '" class="quick-call-link"><i class="fa fa-phone"></i></a>';

                $output .= '<div class="col-12 col-sm-6 col-md-6 col-xl-3 px-10 mb-20">
                    <div class="manager-special-card position-relative h-100">
                        <a href="' . get_permalink($post_id) . '" class="d-inline-block position-relative manager-special-thumbnail w-100">
                            <img src="' . esc_url($specialImage) . '" alt="EV Special Vehicle" loading="lazy" class="img-fluid w-100 h-100 object_fit_cover" />
                            <div class="position-absolute">
                                <p class="color_black font-lg font-weight-bold font-segoe">EV Specials</p>
                            </div>
                        </a>
                        <a class="d-inline-block mt-3" href="' . get_permalink($post_id) . '">
                            <h2 class="font-weight-bold text-grey-3 font-xxl p-0">' . get_the_title($post_id) . '</h2>
                        </a>';
                
                // First get the content once
                $content = get_the_content(null, false, $post_id);
                $word_count = str_word_count($content);

                if ($word_count > 20) {
                    $content_words = explode(' ', $content);
                    $content_truncated = array_slice($content_words, 0, 20);
                    $truncated_content = implode(' ', $content_truncated);
                    $stripped_content = preg_replace('/<[^>]+>/', '', $truncated_content);
                    $output .= '<p class="text-grey-3 mb-20 font-md">' . $stripped_content . '</p>';
                } else {
                    $stripped_content = preg_replace('/<[^>]+>/', '', $content);
                    $output .= '<p class="text-grey-3 mb-20 font-md">' . $stripped_content . '</p>';
                }

                $output .= '<div class="d-flex align-items-center justify-content-between">
                            <strong class="font-weight-bold text-grey-3 font-xl">Our Best Price</strong>
                            <strong class="font-weight-bold text-grey-3 font-xl">' . $vehiclePrice . '</strong>
                        </div>
                        <a class="btn btn-primary manager-special-view-vehicle font-weight-bold font-20 p-3 rounded-10 text-white text-capitalize position-absolute" href="' . get_permalink($post_id) . '" style="text-decoration:none;">View Details</a>
                    </div>
                </div>';
            }
            $output .= '</div>';
        } else {
            $output .= '<p class="text-center text-muted">Sorry, no EV specials available at the moment.</p>';
        }
    }

    return $output;
}

add_shortcode('ev_specials_vehicles', 'dmc_ev_specials');
