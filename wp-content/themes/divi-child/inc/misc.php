<?php

/**
 * File for misc code blocks, ajax functions
 */

 add_action( 'wp_ajax_Get_Our_Services_Popup', 'Get_Our_Services_Popup_callback' );
 add_action( 'wp_ajax_nopriv_Get_Our_Services_Popup', 'Get_Our_Services_Popup_callback' );
 function Get_Our_Services_Popup_callback() {

    $servicesPopup = get_field('our_services_popup','option');
    $servicesPopupArr = $servicesPopup[$_POST['dataattr']];
    $servicesPopupGroup = $servicesPopupArr['our_service_popup_group'];
    $servicePopup = intval($_POST['dataattr']); ?>

    <div class="d-flex align-items-center">
        <?php  
            if( $servicesPopupGroup['our_services_popup_icon'] ) {
                echo '<span>';
                echo '<img src="'. site_url() . $servicesPopupGroup['our_services_popup_icon'] .'" alt="Our service image">';
                echo '</span>';
            }
            if( $servicesPopupGroup['our_services_popup_title'] ) {
                echo '<h2 class="font-weight-bold font-segoe color_black p-0 m-0">'.$servicesPopupGroup['our_services_popup_title'].'</h2>';
            }
        ?>
    </div>
    <div>
         <?php 
             if( $servicesPopupGroup['our_services_paragraph'] ) {
                 echo '<p>'.$servicesPopupGroup['our_services_paragraph'].'</p>';
             }
             if( $servicePopup !== 8 && $servicePopup !== 10 ) {
                 echo '<div class="my-15 d-flex align-items-center justify-content-center">';
                 echo '<a href="'. site_url() .'/service-and-parts/schedule-express-service-durango-co" class="btn btn-primary font-segoe text-uppercase text-white d-inline-block">Schedule Service</a>';
                 echo '</div>';
             }
         ?>
         <?php 
             if( $servicesPopupGroup['our_services_explainer_videos'] ) {
                 echo '<div class="mt-30">';
                 $explainerVideos = $servicesPopupGroup['our_services_explainer_videos'];
                 foreach( $explainerVideos as $video ) {
                     $videoGroup = $video['our_services_explainer_video'];
                     $videoTitle = $video['our_services_explainer_video']['our_services_explainer_video_left']['our_services_explainer_video_heading'];
                     $videoPara = $video['our_services_explainer_video']['our_services_explainer_video_left']['our_services_explainer_video_para'];
                     $videoIframe = $video['our_services_explainer_video']['our_services_explainer_video_right'];
                     echo '<div class="row mb-3">';
                     echo '<div class="col-12 col-lg-8">';
                     echo '<h3 class="p-0 font-helvetica font-weight-bold color_black">'. $videoTitle .'</h3>';
                     echo '<p>'. $videoPara .'</p>';
                     echo '</div>';
                     echo '<div class="col-12 col-lg-4">';
                     echo '<iframe src="'. $videoIframe .'"></iframe>';
                     echo '</div>';
                     echo '</div>';
                 }
                 echo '</div>';
             }
         ?>
         <?php  
             if( $servicesPopupGroup['our_services_pdf_buttons'] ) {
                 echo '<div class="our-services__pdf-btn d-flex my-30">';
                 $servicesPDFBtn = $servicesPopupGroup['our_services_pdf_buttons'];
                 foreach( $servicesPDFBtn as $btn ) {
                     echo '<a href="'. $btn['our_services_pdf_button_url'] .'" target="_blank" class="text-center color_black text-uppercase">'. $btn['our_service_pdf_button_text'] .'</a>';
                 };
                 echo '</div>';
             }
             if( $servicesPopupGroup['our_services_explainer_lists'] ) {
                 $servicelistGroup = $servicesPopupGroup['our_services_explainer_lists'];
                 foreach( $servicelistGroup as $list ) {
                     $listTitle = $list['our_services_explainer_list_title'];
                     $listDesc = $list['our_services_explainer_list_desc'];
                     echo '<div class="mb-2">';
                     echo '<p><b>'.$listTitle.'</b> - '. $listDesc .'</p>';
                     echo '</div>';
                 }
             }
             if( $servicePopup == 3 ) {
                 echo '<div class="d-flex justify-content-center align-items-center">';
                 echo '<a href="'. site_url() .'/service-and-parts/schedule-express-service-durango-co" class="btn btn-primary font-segoe text-uppercase text-white d-inline-block">Schedule service</a>';
                 echo '</div>';
             }
             if( $servicesPopupGroup['our_services_explainer_notice'] ) {
                 echo '<div class="p-15 my-15 d-flex justify-content-center" style="background:#ececec;">';
                 echo '<strong class="text-center font-weight-bold">'. $servicesPopupGroup['our_services_explainer_notice'] .'</strong>';
                 echo '</div>';
             }
             if( $servicePopup == 8 ) {
                 echo '<div class="my-20 d-flex justify-content-center align-items-center">';
                 echo '<a href="tel:(970)385-8244" class="btn btn-primary font-segoe text-uppercase text-white d-inline-block">Call DMC body shop</a>';
                 echo '</div>';
             }
             if( $servicePopup == 10 ) {
                 echo '<div class="row my-20">';
                 echo '<div class="col-12 d-flex justify-content-center">';
                 echo '<a href="tel:(970)495-4899" class="btn btn-primary text-uppercase mr-2">contact dgo autogear</a>';
                 echo '<a href="'.site_url().'/service-and-parts/accessories/" class="btn btn-primary text-uppercase">visit dgo autogear</a>';
                 echo '</div>';
                 echo '</div>';
             }
             if( $servicesPopupGroup['our_services_youtube_iframe'] ) {
                 echo '<div class="our-services__yt-iframe my-15">';
                 echo '<iframe src="'. $servicesPopupGroup['our_services_youtube_iframe'] .'" title="Youtube Video" class="w-100" frameborder="0"></iframe>';
                 echo '</div>';
             }
             if( $servicesPopupGroup['our_services_second_paragraph'] ) {
                 echo '<div>';
                 echo '<p>'. $servicesPopupGroup['our_services_second_paragraph'] .'</p>';
                 echo '</div>';
             }
             if( $servicePopup !== 0 && $servicePopup !== 1 && $servicePopup !== 3 && $servicePopup !== 4 && $servicePopup !== 5 && $servicePopup !== 6 && $servicePopup !== 7 && $servicePopup !== 8 && $servicePopup !== 9 && $servicePopup !== 10) {
                 echo '<div class="d-flex justify-content-center align-items-center">';
                 echo '<a href="'. site_url() .'/service-and-parts/schedule-express-service-durango-co" class="btn btn-primary font-segoe text-uppercase text-white d-inline-block">Schedule service</a>';
                 echo '</div>';
             }
             if( $servicesPopupGroup['our_services_side_by_side_iframes'] && !empty($servicesPopupGroup['our_services_side_by_side_iframes']) ) {
                 echo '<div class="row mt-20">';
                 foreach( $servicesPopupGroup['our_services_side_by_side_iframes'] as $iframe ) {
                     echo '<div class="col-12 col-lg-6">';
                     echo '<iframe src="'. $iframe['external_video_url'] .'" class="w-100"></iframe>';
                     echo '</div>';
                 }
                 echo '</div>';
             }
             if( $servicePopup == 11 ) {
                 echo '<div class="row mt-30">';
                 echo '<div class="col-12 col-lg-6">';
                 echo '<img src="'. site_url() .'/wp-content/themes/divi-child/assets/images/engine-cleaning.webp" alt="Engine cleaning" class="img-fluid" />';
                 echo '</div>';
                 echo '<div class="col-12 col-lg-6">';
                 echo '<img src="'. site_url() .'/wp-content/themes/divi-child/assets/images/floor-mats-cleaning.webp" alt="Floor Mats cleaning" class="img-fluid" />';
                 echo '</div>';
                 echo '</div>';
             }
         ?>
     </div>
 <?php
 wp_die();
}

/**
 * remove search field values if user perform a search
 */
function clear_search_value() {
    if (is_search()) {
        ?>
        <script type="text/javascript">
            var inputs = document.querySelectorAll(".wp-block-search__input");
            inputs.forEach(function(input) {
                input.value = "";
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'clear_search_value');


/**
 * Check and redirect according to searched term
 */
add_action('template_redirect', 'durango_search_whole_website');

function durango_search_whole_website() {
    if (isset($_GET['s']) && !empty($_GET['s'])) {
        $term = sanitize_text_field($_GET['s']);

        // Remove inappropriate search terms
        $term = str_replace(array('sex','porn','adult','18+', '{search_term_string}','boobs','ass','xxx','erotic','latina','pussy','https','<','>'), '', $term);

        // Search for matching listings
        $listings_args = array(
            'post_type'      => 'listings',
            'posts_per_page' => 1,  // Get only the first match
            'meta_query'     => array(
                'relation' => 'OR',
                array('key' => 'stock-number', 'value' => $term, 'compare' => '='),
                array('key' => 'make', 'value' => $term, 'compare' => '='),
                array('key' => 'type-of-vehicle', 'value' => $term, 'compare' => '='),
                array('key' => 'model', 'value' => $term, 'compare' => '='),
                array('key' => 'vin-number', 'value' => $term, 'compare' => '='),
                array('key' => 'postName', 'value' => $term, 'compare' => 'LIKE'),
            ),
        );

        $listings_query = new WP_Query($listings_args);

        if ($listings_query->have_posts()) {
            $listings_query->the_post();
            $listing_id = get_the_ID();
            
            // Check the 'condition' meta key
            $condition = get_post_meta($listing_id, 'condition', true);
            
            if ( $condition === 'N') {
                $redirect_url = home_url('/new-vehicles-durango-colorado');
            } else {
                $redirect_url = home_url('/used-vehicles-durango-colorado');
            }
            
            // Redirect to the correct page with search term
            $redirect_url = add_query_arg(array('search' => $term), $redirect_url);
            wp_redirect($redirect_url);
            exit;
        }

        // If no listing matches, check pages
        $pages = get_pages();
        foreach ($pages as $page) {
            if (stripos($page->post_title, $term) !== false) {
                wp_redirect(get_permalink($page->ID));
                exit;
            }
        }

        // If no results found in listings or pages, redirect to new inventory page
        $redirect_url = home_url('/new-vehicles-durango-colorado');
        $redirect_url = add_query_arg(array('search' => $term), $redirect_url);
        wp_redirect($redirect_url);
        exit;
    }
}


/**
 * Dequeue unnecessary scripts
 */
function dequeue_unnecessary_scripts() {
    if ( is_singular( 'listings' ) ) {
        $scripts_to_deregister = array(
            'automotive-listing-generate-pdf',
            'automotive-listing-financing-calculator',
            'listing_js',
            'listing_cookie',
            'flex-slider',
            'jqueryfancybox',
            'tether',
            'selectbox',
            'bxslider',
            'parallax',
            'social-likes',
            'jspdf',
            'addimage',
            'autoprint',
            'context2d.js',
            'deflate.js',
            'from_html.js',
            'javascript.js',
            'jspdf.plugin.addimage.js',
            'addimage.js',
            'jspdf.plugin.autoprint.js',
            'autoprint.js',
            'canvas.js',
            'jspdf.plugin.png_support.js',
            'png_support.js',
            'jspdf.plugin.split_text_to_size.js',
            'split_text_to_size.js',
            'jspdf.plugin.standard_fonts_metrics.js',
            'standard_fonts_metrics.js',
            'jspdf.plugin.textright.js',
            'jspdf.plugin.total_pages.js',
            'total_pages.js',
            'png.js',
            'png_support',
            'split_text_to_size',
            'standard_fonts_metrics',
            'total_pages',
            'zlib.js',
            'jquery_ui',
            'google-maps',
            'automotive_photoswipe',
            'automotive_photoswipe-default-ui',
            'mixit',
            'inview',
            'twitter_tweet',
            'twitter_feed',
            'contact_form',
            'recaptcha',
            'multiselect',
            'bootstrap-tooltip',
            'chosen-dropdown',
            'alphanum',
        );

        foreach ( $scripts_to_deregister as $handle ) {
            wp_deregister_script( $handle );
            wp_dequeue_script( $handle );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'dequeue_unnecessary_scripts', 9999 );

// Update liked vehicle status
add_action('wp_ajax_update_vehicle_liked_status', 'update_vehicle_liked_status_callback');
add_action('wp_ajax_nopriv_update_vehicle_liked_status', 'update_vehicle_liked_status_callback');
function update_vehicle_liked_status_callback() {
    $vehicleId = isset($_POST['vehicleId']) ? trim(sanitize_text_field($_POST['vehicleId'])) : null;
    $likeStatus = isset($_POST['likeStatus']) ? filter_var($_POST['likeStatus'], FILTER_VALIDATE_BOOLEAN) : false;
    $userIP = getUserIP();
    $cardHTML = '';
    // Make Liked Vehicles table if not already have
    $table_name = accessWPDB()->prefix . 'user_liked_vehicles';
    if (accessWPDB()->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        // Table doesn't exist, create it
        $charset_collate = accessWPDB()->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
            user_ip VARCHAR(45) NOT NULL UNIQUE,
            user_liked_vehicles LONGTEXT,
            PRIMARY KEY (id)
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    // Check if the user IP is already present or not
    $updateQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", $userIP);
    $updateResult = accessWPDB()->get_row($updateQuery, ARRAY_A);
    if( !$updateResult ) {
        // User data does not exist so insert new data
        if( !empty(trim($vehicleId)) ) {
            $user_data = array(
                'user_ip' => $userIP,
                'user_liked_vehicles' => serialize(array($vehicleId)),
            );
            accessWPDB()->insert(
                $table_name,
                $user_data,
                array('%s', '%s'),
            );
        }
    }else {
        // User data exists, update the user_liked_vehicles array
        $user_liked_vehicles = unserialize($updateResult['user_liked_vehicles']);

        // Check if the vehicleId is already in the array
        if (!empty(trim($vehicleId)) && !in_array($vehicleId, $user_liked_vehicles)) {
            // Add the vehicleId to the array
            $user_liked_vehicles[] = $vehicleId;
            // Update the user data with the new user_liked_vehicles array
            $user_data = array(
                'user_liked_vehicles' => serialize($user_liked_vehicles),
            );
            
            accessWPDB()->update(
                $table_name,
                $user_data,
                array('user_ip' => $userIP),
            );
        }
    }

    if( $likeStatus ) {
		$connection = get_db_connection();
		$query = "SELECT vauto_url FROM dmc_images WHERE vin = ? LIMIT 1";
		$stmt = $connection->prepare($query);
		$vin_number = get_post_meta( $vehicleId, 'vin-number', true );
		$image_url = '';
		if ($stmt) {
			$stmt->bind_param("s", $vin_number);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($row = $result->fetch_assoc()) {
				$image_url = $row['vauto_url'];
			}
		}
		
        $cardHTML .= '<div class="d-flex justify-content-start mb-30 position-relative liked-vehicle-card" data-id="'.$vehicleId.'">
        <div class="vehicle-thumbnail mr-30 position-relative">
        <a href="'.get_the_permalink($vehicleId).'" class="d-inline-block h-100">';
$cardHTML .= '<img src="'. esc_url( $image_url ) .'" loading="lazy" decoding="async" width="118" height="109" alt="'. get_the_title( $vehicleId ) .'"/>';
$cardHTML .= '</a>
         <img src="'.site_url().'/wp-content/themes/divi-child/assets/images/icon-vehicle-liked.png" alt="vehicle liked"
         itemprop="image" class="position-absolute vehicle-liked-icon">
         </div>
         <div class="vehicle-content">
         <h3 class="p-0 mb-10">
         <a href="'.get_the_permalink($vehicleId).'" class="font-inter font-20 text-primary d-inline-block">
         '.get_the_title($vehicleId).'</a></h3>
         <p class="mb-1 text-grey-6 font-inter font-lg p-0">Stock #: '.get_post_meta($vehicleId, 'stock-number', true).'
         </p>
         <p class="m-0 text-grey-6 font-inter font-lg p-0">$ '.number_format(get_post_meta($vehicleId, 'original_price', true)).'</p>
         <span data-id="'.$vehicleId.'" class="remove-liked-view text-primary font-sm text-link cursor-pointer font-inter font-weight-light position-absolute">Remove</span>
         </div><button class="position-absolute liked-vehicle-availability w-100 btn btn-grey-9 text-primary font-inter sidebar-popup-trigger" data-popup="sticky-cta" data-price="'.number_format(get_post_meta($vehicleId, 'original_price', true)).'" 
         data-year="'.get_post_meta($vehicleId, 'year', true).'" data-vin="'.get_post_meta($vehicleId, 'vin-number', true).'"
         data-stock="'.get_post_meta($vehicleId, 'stock-number', true).'" data-make="'.get_post_meta($vehicleId, 'make', true).'"
         data-model="'.get_post_meta($vehicleId, 'model', true).'" data-thumbnail="'.wp_get_attachment_image_src(get_post_meta($vehicleId, 'gallery_images', true)[0], 'full')[0].'">Check Availability</button></div>';
    }else {
        // User unlike the vehicle
        $user_liked_vehicles = unserialize($updateResult['user_liked_vehicles']);
        $index = array_search($vehicleId, $user_liked_vehicles);

        if ($index !== false) {
            // Remove the vehicleId from the array
            unset($user_liked_vehicles[$index]);
            
            // If the array becomes empty, delete the row
            if (empty($user_liked_vehicles)) {
                accessWPDB()->delete(
                    $table_name,
                    array('user_ip' => $userIP),
                    array('%s')
                );
            } else {
                // Update the user data with the modified user_liked_vehicles array
                $user_data = array(
                    'user_liked_vehicles' => serialize(array_values($user_liked_vehicles)),
                );

                accessWPDB()->update(
                    $table_name,
                    $user_data,
                    array('user_ip' => $userIP),
                    array('%s'),
                    array('%s')
                );
            }
        }
    }
    
    delete_transient('product_card_' . $vehicleId);

    echo json_encode(
        array(
            'cardHTML' => $cardHTML,
            'cardID' => $vehicleId,
        )
    );

    wp_die();
}

// Update user_recently_viewed table to keep track of user's recent vehicles
add_action('wp_ajax_trackUserRecentVehicles', 'trackUserRecentVehicles_callback');
add_action('wp_ajax_nopriv_trackUserRecentVehicles', 'trackUserRecentVehicles_callback');
function trackUserRecentVehicles_callback() {
    $vehicleId = isset($_POST['vehicleId']) && $vehicleId !== '' ? trim(sanitize_text_field($_POST['vehicleId'])) : '';
    $initPageLoad = isset($_POST['initialPageLoad']) ? filter_var($_POST['initialPageLoad'], FILTER_VALIDATE_BOOLEAN) : false;
    $userIP = getUserIP();
    $recentVehicleHTML = '';

    // Make recently viewed table if not already have
    $table_name = accessWPDB()->prefix . 'user_recently_viewed';
    if (accessWPDB()->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        // Table doesn't exist, create it
        $charset_collate = accessWPDB()->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
            user_ip VARCHAR(45) NOT NULL UNIQUE,
            recent_view_vehicles LONGTEXT,
            timestamp INT(11) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Check if the user IP is already present or not
    $updateQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", $userIP);
    $updateResult = accessWPDB()->get_row($updateQuery, ARRAY_A);

    if( !$updateResult ) {
        // User data does not exist so insert new data
        if( !empty(trim($vehicleId)) ) {
            $user_data = array(
                'user_ip' => $userIP,
                'recent_view_vehicles' => serialize(array($vehicleId)),
                'timestamp' => time(),
            );
    
            accessWPDB()->insert(
                $table_name,
                $user_data,
                array('%s', '%s', '%d'),
            );
        }
    }else {
        // User data exists, update the recent_view_vehicles array
        $recent_view_vehicles = unserialize($updateResult['recent_view_vehicles']);

        // Check if the vehicleId is already in the array
        if (!empty($vehicleId) && !in_array($vehicleId, $recent_view_vehicles)) {
            // Add the vehicleId to the array
            if( !empty(trim($vehicleId)) ) {
                $recent_view_vehicles[] = $vehicleId;
                // Update the user data with the new recent_view_vehicles array
                $user_data = array(
                    'recent_view_vehicles' => serialize($recent_view_vehicles),
                    'timestamp' => time(),
                );
                
                accessWPDB()->update(
                    $table_name,
                    $user_data,
                    array('user_ip' => $userIP),
                    array('%s', '%d'),
                    array('%s'),
                );
            }
        }
    }

    // Generate Card HTML
    if( $initPageLoad && !in_array($vehicleId, unserialize($updateResult['recent_view_vehicles'])) && !empty($vehicleId) ) {
        $recentVehicleHTML = '<div class="d-flex justify-content-start mb-30 position-relative recent-view-vehicle-card">'.
        '<div class="vehicle-thumbnail mr-30 position-relative">' .
        '<a href="'.get_the_permalink($vehicleId).'" class="d-inline-block h-100">'.
        ( has_post_thumbnail($vehicleId) ? get_the_post_thumbnail($vehicleId) : '' ) .
        '</a>'.
        '</div>' .
        '<div class="vehicle-content">'.
        '<h3 class="p-0 mb-10">'.
        '<a href="'.get_the_permalink($vehicleId).'" class="font-inter font-20 text-primary d-inline-block">'.get_the_title($vehicleId).'</a>'.
        '</h3>'.
        '<p class="mb-1 text-grey-6 font-inter font-lg p-0">Stock #: '. get_post_meta($vehicleId, 'stock-number', true) .'</p>'.
        '<p class="m-0 text-grey-6 font-inter font-lg p-0">$ '. number_format(get_post_meta($vehicleId, 'original_price', true)) .'</p>'.
        '<span data-id="'.$vehicleId.'" class="remove-recent-view text-primary font-sm text-link cursor-pointer font-inter font-weight-light position-absolute">Remove</span>'.
        '</div>'.
        '</div>';
    }
    // If ajax call was made by clicking on the remove button
    if (!$initPageLoad) {
        $recent_view_vehicles = unserialize($updateResult['recent_view_vehicles']);
        $index = array_search($vehicleId, $recent_view_vehicles);

        if ($index !== false) {
            // Remove the vehicleId from the array
            unset($recent_view_vehicles[$index]);
            
            // If the array becomes empty, delete the row
            if (empty($recent_view_vehicles)) {
                accessWPDB()->delete(
                    $table_name,
                    array('user_ip' => $userIP),
                    array('%s')
                );
            } else {
                // Update the user data with the modified recent_view_vehicles array
                $user_data = array(
                    'recent_view_vehicles' => serialize(array_values($recent_view_vehicles)),
                    'timestamp' => time(),
                );

                accessWPDB()->update(
                    $table_name,
                    $user_data,
                    array('user_ip' => $userIP),
                    array('%s', '%d'),
                    array('%s')
                );
            }
        }
    }

    echo json_encode(array('cardHTML' => $recentVehicleHTML));
    wp_die();
}

/**
 * Compare Vehicles Function
 */
add_action('wp_ajax_userComparedVehicles', 'userComparedVehicles_callback');
add_action('wp_ajax_nopriv_userComparedVehicles', 'userComparedVehicles_callback');
function userComparedVehicles_callback() {
   $checkStatus = isset($_POST['checkStatus']) ? filter_var($_POST['checkStatus'], FILTER_VALIDATE_BOOLEAN) : false;
   $isArr = isset($_POST['isArr']) ? filter_var($_POST['isArr'], FILTER_VALIDATE_BOOLEAN) : false;
   $vehicleId = is_array($_POST['checkedPostId']) ? array_map('sanitize_text_field', $_POST['checkedPostId']) : sanitize_text_field($_POST['checkedPostId']);
   $table_name = accessWPDB()->prefix . 'user_compared_vehicles';
   if (accessWPDB()->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        // Table doesn't exist, create it
        $charset_collate = accessWPDB()->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT UNIQUE,
            user_ip VARCHAR(45) NOT NULL UNIQUE,
            user_compared_vehicles LONGTEXT,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Check if the user IP is already present or not
    $comparedQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
    $updateResult = accessWPDB()->get_row($comparedQuery, ARRAY_A);
    
    if( !$updateResult ) {
        // User data does not exist so insert new data
        if( !empty($vehicleId) && $vehicleId !== '' ) {
            // User data does not exist, so insert new data
            $user_compared_vehicles = array(); // Initialize as an empty array

            if (!empty($vehicleId) && $vehicleId !== '') {
                $user_compared_vehicles[] = $vehicleId; // Add the single vehicle ID to the array
            }
            $user_data = array(
                'user_ip' => getUserIP(),
                'user_compared_vehicles' => serialize(array($vehicleId)),
            );
    
            accessWPDB()->insert(
                $table_name,
                $user_data,
                array('%s', '%s'),
            );
        }
    }else {
        // User data exists, update the user_compared_vehicles array
        $user_compared_vehicles = unserialize($updateResult['user_compared_vehicles']);

        // If the ajax call was made by the checkbox and the user checked the checkbox
        if( $checkStatus ) {
            // Check if the vehicleId is not already in the array
            if (!empty($vehicleId) && $vehicleId !== '' && !in_array($vehicleId, $user_compared_vehicles)) {
                $user_compared_vehicles[] = $vehicleId;
                // Update the user data with the new user_compared_vehicles array
                $user_data = array(
                    'user_compared_vehicles' => serialize($user_compared_vehicles),
                );
                accessWPDB()->update(
                    $table_name,
                    $user_data,
                    array('user_ip' => getUserIP()),
                );
            }
        }else {
            /*
                If the user unchecked the checkbox or
                user clicked on card remove of compare box
                user clicked on remove all button
                user clicked on compare popup close button or compare popup overlay
            */
            if($isArr) {
                foreach( $vehicleId as $listing ) {
                    $index = array_search($listing, $user_compared_vehicles);
                    if( $index !== false ) {
                        unset($user_compared_vehicles[$index]);
                        $user_compared_vehicles = array_values($user_compared_vehicles);
                        if( empty($user_compared_vehicles) ) {
                            accessWPDB()->delete(
                                $table_name,
                                array('user_ip' => getUserIP()),
                                array('%s')
                            );
                        }
                    }
                    delete_transient('product_card_' . $listing);
                }
            }else {
                $index = array_search($vehicleId, $user_compared_vehicles);
                if( $index !== false ) {
                    unset($user_compared_vehicles[$index]);
                    // Reindex the array
                    $user_compared_vehicles = array_values($user_compared_vehicles);
                    $user_data = array(
                        'user_compared_vehicles' => serialize($user_compared_vehicles),
                    );
                    accessWPDB()->update(
                        $table_name,
                        $user_data,
                        array('user_ip' => getUserIP()),
                    );
                    if( empty($user_compared_vehicles)) {
                        accessWPDB()->delete(
                            $table_name,
                            array('user_ip' => getUserIP()),
                            array('%s')
                        );
                    }
                }
                delete_transient('product_card_' . $vehicleId);
            }
        }
    }

    echo json_encode(array('comparedVehicles' => count($user_compared_vehicles)));
    wp_die();
}

// Add compare processing code
add_action('wp_ajax_rr_compare_vehicles', 'rr_compare_vehicles_callback');
add_action('wp_ajax_nopriv_rr_compare_vehicles', 'rr_compare_vehicles_callback');

function rr_compare_vehicles_callback(){
    $table_name = accessWPDB()->prefix . 'user_compared_vehicles';
    $comparedQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
    $updateResult = accessWPDB()->get_row($comparedQuery, ARRAY_A);
    if( !$updateResult ) {
        $user_compared_vehicles = array(0);
    }else {
        $user_compared_vehicles = unserialize($updateResult['user_compared_vehicles']);
    }

    $html = '';
    $fields = array(
        'make',
        'model',
        'year',
        'original_price',
        'condition',
        'engine',
        'cylinders',
        'drivetrain',
        'fuel-type',
        'odometer',
        'certified',
        'body-style',
        'transmission',
        'city_mpg',
        'highway_mpg',
    );

    $data = array();
	$connection = get_db_connection();
	$query = "SELECT vauto_url FROM dmc_images WHERE vin = ? LIMIT 1";
	$stmt = $connection->prepare($query);
	
    foreach($user_compared_vehicles as $item_id){
        foreach($fields as $field){
            $data[$field][] = get_post_meta($item_id, $field, true);
        }
    }
    $html .= "<div class='br_new_compare'>";
    $html .= "<table class='br_left_table table table-bordered  table-responsive table-striped'>";
    $html .= "<thead>";
    $html .= "<tr class='br_row_image'>";
    $html .= "<td>&nbsp;</td>";
    foreach ($user_compared_vehicles as $thumb_id) {
		$vin_number = get_post_meta( $thumb_id, 'vin-number', true );
		$image_url = '';
		if ($stmt) {
			$stmt->bind_param("s", $vin_number);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($row = $result->fetch_assoc()) {
				$image_url = $row['vauto_url'];
			}
		}
		
        $postImages = get_post_meta($thumb_id, 'gallery_images', true);
        $galleryImage = !empty($postImages) ? $postImages[0] : ''; // Get the first image
        
        $html .= "<th class='compare-vehicles__image'><img class='w-100' src='" . esc_url($image_url) . "' alt='listing image'/></th>";
    }
    
   
    $html .= "</tr>";
    $html .= "</thead>";
    $html .= "<tbody>";
    // Generate table rows for each field
    $title = "";
    $html .= '<tr class="br_header_title">';
    $html .= '<th>Title</th>';
    foreach( $user_compared_vehicles  as $item ) {
        $html .= '<th>'.get_the_title($item).'</th>';
    }
    
    $html .= '</tr>';
    foreach ($fields as $field) {
        $html .= '<tr class="br_header_'.$field.'">';
        $html .= "<td>".$field."</td>";
        foreach( $user_compared_vehicles as $item ) {
            $value = get_post_meta($item, $field, true);
            if( !empty($value) ) {
                $html .= "<td>" . $value . "</td>";
            }else {
                $html .= "<td><a href='tel:+18558941386' class='quick-call-link'><i class='fa fa-phone'></i></a></td>";
            }
        }
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
        
    echo json_encode(array('html' => $html));
    wp_die();
}


/** 
 ===============================================
 Lode More Vehicles in recommend tab in VDP page
 ===============================================
 */
add_action('wp_ajax_loadMoreRecommendations', 'loadMoreRecommendations_callback');
add_action('wp_ajax_nopriv_loadMoreRecommendations', 'loadMoreRecommendations_callback');

function loadMoreRecommendations_callback(){
    $vehicleMake = isset($_POST['vehicleMake']) ? sanitize_text_field($_POST['vehicleMake']) : null;
    $paged = isset($_POST['paged']) ? $_POST['paged'] : 1;

    $args = array(
        'post_type' => 'listings',
        'posts_per_page' => 5,
        'paged' => $paged,
        'meta_query' => array(
            array(
                'key' => 'make',
                'value' => $vehicleMake,
                'compare' => '=',
            ),
        ),
    );

    $makeQuery = new WP_Query($args);
    $layout = '';

    if( $makeQuery->have_posts() ) {
        while( $makeQuery->have_posts() ) {
            $makeQuery->the_post();
            $layout .= '<div class="d-flex justify-content-start mb-30 position-relative recent-view-vehicle-card" data-id="'.get_the_ID().'">'.
                      '<div class="vehicle-thumbnail mr-30 position-relative">' .
                      '<a href="'.get_the_permalink().'" class="d-inline-block h-100">'.
                      ( has_post_thumbnail() ? get_the_post_thumbnail() : '' ) .
                      '</a>'.
                      '</div>' .
                      '<div class="vehicle-content">'.
                      '<h3 class="p-0 mb-10">'.
                      '<a href="'.get_the_permalink().'" class="font-inter font-20 text-primary d-inline-block">'.get_the_title().'</a>'.
                      '</h3>'.
                      '<p class="mb-1 text-grey-6 font-inter font-lg p-0">Stock #: '. get_post_meta(get_the_ID(), 'stock-number', true) .'</p>';
                      $listingPrice = number_format(get_post_meta(get_the_ID(), 'original_price', true));
                    if( empty(trim($listingPrice)) || $listingPrice === 'None' || !isset($listingPrice) ) {
                        $layout .= '<a class="font-inter font-md text-grey-6" href="tel:'.salesPhoneNumber().'"><i class="fa fa-phone"></i>Call For Price</a>';
                    }else {
                        $layout .= '<p class="m-0 text-grey-6 font-inter font-lg p-0">$ '. number_format(get_post_meta(get_the_ID(), 'original_price', true)) .'</p>';
                    }
                    $layout .=  '</div>'.
                      '</div>';
        }
        wp_reset_postdata();
    }else {
        $layout .= 'Sorry, No more recommendations found';
    }
    echo json_encode(
            array(
                'cardHTML' => $layout,
                'totalPages' => $makeQuery->max_num_pages,
            )
        );
    wp_die();
}


add_action('wp_ajax_loadMyGarageVehicles', 'loadMyGarageVehicles_callback');
add_action('wp_ajax_nopriv_loadMyGarageVehicles', 'loadMyGarageVehicles_callback');

function loadMyGarageVehicles_callback(){
    $vehicleStyle = isset($_POST['vehicleStyle']) ? $_POST['vehicleStyle'] : [0];
    $vehicleStyle = array_map('sanitize_text_field', $vehicleStyle);
    $paged = isset($_POST['garagePaged']) ? $_POST['garagePaged'] : 1;
    $card = '';
    $args = array(
        'post_type' => 'listings',
        'posts_per_page' => 5,
        'paged' => $paged,
        'meta_query' => array(
            array(
                'key' => 'type-of-vehicle',
                'value' => $vehicleStyle,
                'compare' => 'IN',
            ),
        ),
    );
    $garageQuery = new WP_Query($args);
    $foundVehicles = $garageQuery->found_posts;
    $postCount = $garageQuery->post_count;
    if( $garageQuery->have_posts() ) {
        while($garageQuery->have_posts()) {
            $garageQuery->the_post();
            $card .= '<div class="d-flex justify-content-start mb-30 position-relative " data-id="'.get_the_ID().'">' .
            '<div class="vehicle-thumbnail mr-30 position-relative">' ;
            $card .= '<a href="'.get_the_permalink().'" class="d-inline-block h-100">'.
                    ( has_post_thumbnail() ? get_the_post_thumbnail() : '' ) .
                    '</a>'.
                    '</div>' .
                    '<div class="vehicle-content">'.
                    '<h3 class="p-0 mb-10">'.
                    '<a href="'.get_the_permalink().'" class="font-inter font-20 text-primary d-inline-block">'.get_the_title().'</a>'.
                    '</h3>'.
                    '<p class="mb-1 text-grey-6 font-inter font-lg p-0">Stock #: '. get_post_meta(get_the_ID(), 'stock-number', true) .'</p>';
                    $listingPrice = get_post_meta(get_the_ID(), 'original_price', true);
                    if( !empty($listingPrice) && $listingPrice !== '' ) {
                        $card .= '<p class="m-0 text-grey-6 font-inter font-lg p-0">$ '. number_format($listingPrice) .'</p>';
                    }else {
                        $card .= '<a href="tel:'.salesPhoneNumber().'" class="text-dark m-0 font-inter font-lg">Call For Price</a>';
                    }
                    $card .= '</div>'.
                    '</div>';
        }
        wp_reset_postdata();
    }else {
        $card = 'Sorry no listings found';
    }
    
  echo json_encode(array(
    'cardLayout' => $card,
    'foundVehicles' => $foundVehicles,
    'postCount' => $postCount,
  ));
  wp_die();
}

// Load VDP Compare Vehicles
add_action('wp_ajax_loadCompareVehicles', 'loadCompareVehicles_callback');
add_action('wp_ajax_nopriv_loadCompareVehicles', 'loadCompareVehicles_callback');

function loadCompareVehicles_callback(){
	global $wpdb;
	$table_name = accessWPDB()->prefix . 'user_compared_vehicles';
	$user_ip	= getUserIp();
	
	/** Sanitize and validate input */	
	$addCurrentVehicle = filter_var($_POST['addCurrentVehicle'] ?? null, FILTER_VALIDATE_BOOLEAN);
    $pageLoad = filter_var($_POST['pageLoad'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $VDPListing = !empty($_POST['VDPListing']) ? trim(sanitize_text_field($_POST['VDPListing'])) : null;
	
	if( ! $VDPListing && ! $pageLoad ) {
		wp_send_json_error( ['message' => 'Invalid vehicle data'] );
	}
	
	/** Fetch existing compared vehicles */
	$storedVehicles		= $wpdb->get_var($wpdb->prepare("SELECT user_compared_vehicles FROM $table_name WHERE user_ip = %s", $user_ip));
	$user_compared_vehicles	= $storedVehicles ? unserialize($storedVehicles) : [];
	
	/** Connect to images table */
	$connection = get_db_connection();
	
	/** If adding a vehicle */
	if ($addCurrentVehicle) {
		if ($VDPListing && !in_array($VDPListing, $user_compared_vehicles)) {
			$user_compared_vehicles[] = $VDPListing;
			$wpdb->replace($table_name, ['user_ip' => $user_ip, 'user_compared_vehicles' => serialize($user_compared_vehicles)], ['%s', '%s']);
		}
	} elseif (!$pageLoad) {
		/** If removing a vehicle */
        if (($index = array_search($VDPListing, $user_compared_vehicles)) !== false) {
            unset($user_compared_vehicles[$index]);
            $user_compared_vehicles = array_values($user_compared_vehicles);
            if (!empty($user_compared_vehicles)) {
                $wpdb->update($table_name, ['user_compared_vehicles' => serialize($user_compared_vehicles)], ['user_ip' => $user_ip]);
            } else {
                $wpdb->delete($table_name, ['user_ip' => $user_ip]);
            }
        }
    }
	
	/** Fetch vehicle data */
	$args = [
		'post_type'	=> 'listings',
		'post__in'	=> ! empty( $user_compared_vehicles ) ? $user_compared_vehicles : [0],
		'posts_per_page' => 3
	];
	
	$compareQuery 	= new WP_Query( $args );
	$cardLayout		= '';
	
	if( $compareQuery->have_posts() ) {
		while( $compareQuery->have_posts() ) {
			$compareQuery->the_post();
			$post_id	= get_the_ID();
			$title		= get_the_title();
			$permalink	= get_the_permalink();
			$thumbnail	= has_post_thumbnail() ? get_the_post_thumbnail() : '<img src="'.get_stylesheet_directory_uri().'/assets/images/dummy-compare.png" class="img-fluid w-100" />';
			$stock_number 	= get_post_meta( $post_id, 'stock-number', true );
			$listing_price 	= get_post_meta( $post_id, 'original_price', true );
			$vin_number		= get_post_meta( $post_id, 'vin-number', true );
			$query = "SELECT vauto_url FROM dmc_images WHERE vin = ?";
			$stmt  = $connection->prepare( $query );
			
			if ($stmt) {
				$stmt->bind_param("s", $vin_number);
				$stmt->execute();
				$result = $stmt->get_result();

				$first_image_url = null;
				if ($result) {
					while ($row = $result->fetch_assoc()) {
						if (!empty($row['vauto_url'])) {
							$first_image_url = $row['vauto_url'];
							break; // Stop after getting the first URL
						}
					}
				}
				$stmt->close();
			}
			
			$cardLayout .= '<div class="d-flex mb-30 position-relative">
			<div class="accordion-card-thumbnail mr-30 position-relative">
				<a class="d-inline-block w-100" href="'. $permalink .'">
					<img src="'. esc_url( $first_image_url ) .'" width="140" height="108" loading="lazy" decoding="async"
					title="'. esc_attr( $title ) .'" />
				</a>
			</div>
			<div class="d-flex align-items-start flex-column">
				<a href="'. $permalink .'" class="text-primary font-20 mb-10 p-0 font-inter">'. esc_html( $title ) .'</a>
				<p class="text-grey-6 font-md font-inter mb-1 p-0">'. esc_html( $stock_number ) .'</p>';
			
			if( ! $listing_price || $listing_price === 'None' ) {
				$cardLayout .= '<a class="font-inter font-md text-grey-6"
				href="tel:'.salesPhoneNumber().'" title="Call For Price">
					<i class="fa fa-phone"></i>
				</a>';
			} else {
				$cardLayout .= '<p class="text-grey-6 font-md font-inter mb-0 p-0
				font-weight-bold">$ '.number_format($listing_price).'</p>';
			}
			
			$cardLayout .= '<span class="remove-vdp-compare text-primary font-sm cursor-pointer position-absolute" style="right:0;bottom:0;" data-remove="'.$post_id.'">Remove</span>
			</div>
			</div>
			</div>';
			
		}
		wp_reset_postdata();
	}
	
	/** Fill remaining spots if less than 3 */
	$remainingSpots = 3 - $compareQuery->post_count;
	$firstPlaceholderVin = $VDPListing;
	
	if ($firstPlaceholderVin) {
		$query = "SELECT vauto_url FROM dmc_images WHERE vin = ?";
		$stmt  = $connection->prepare($query);

		if ($stmt) {
			$stmt->bind_param("s", $firstPlaceholderVin);
			$stmt->execute();
			$result = $stmt->get_result();
			$firstPlaceholderImage = null;

			if ($result && $row = $result->fetch_assoc()) {
				$firstPlaceholderImage = !empty($row['vauto_url']) ? $row['vauto_url'] : null;
			}
			$stmt->close();
		}
	}
	
	for ($i = 0; $i < $remainingSpots; $i++) {
		$isFirstPlaceholder = ($i === 0 && empty($user_compared_vehicles));
		$imageSrc = $isFirstPlaceholder && $firstPlaceholderImage 
			? esc_url($firstPlaceholderImage)
			: site_url().'/wp-content/themes/divi-child/assets/images/dummy-compare.png';
		$title = $isFirstPlaceholder ? "Add to Compare" : "Compare Another Vehicle";
		
		$cardLayout .= '<div class="d-flex mb-30 '. ($isFirstPlaceholder ? 'add-current-vehicle-compare' : '') .'"
		data-id="'. ($isFirstPlaceholder ? $VDPListing : '') .'">
            <div class="accordion-card-thumbnail mr-30 position-relative">
                <a
				href="'. ($isFirstPlaceholder ? 'javascript:void(0)' : site_url().'/new-vehicles-durango-colorado/') .'" 
				'. ($isFirstPlaceholder ? '' : 'target="_blank"') .' class="d-inline-block w-100">
                    <img src="'. esc_url($imageSrc) .'" class="img-fluid w-100" />
                    <i class="fa fa-plus position-absolute compare-another-vehicle-icon text-white"></i>
                </a>
            </div>
            <div class="d-flex align-items-center">
                <a class="font-inter text-primary font-lg" href="'. (!$isFirstPlaceholder ? site_url().'/inventory' : 'javascript:void(0)') .'" '. ($isFirstPlaceholder ? '' : 'target="_blank"') .'>'. esc_html($title) .'</a>
            </div>
        </div>';
	}
    
	wp_send_json_success(['cardLayout' => $cardLayout, 'foundVehicles' => $compareQuery->post_count]);
}

add_action('wp_ajax_insert_lead_to_sql', 'insert_lead_to_sql_callback');
add_action('wp_ajax_nopriv_insert_lead_to_sql', 'insert_lead_to_sql_callback');

function insert_lead_to_sql_callback() {
    $lead_data = isset($_REQUEST['values']) ? array_map('trim', $_REQUEST['values']) : array();
    $host = 'inventory-database-do-user-2599605-0.c.db.ondigitalocean.com:25060';
    $username = 'junaid';
    $password = 'AVNS_ufjqHNNhDr_Pxg4FTFN';
    $database = 'pre_owned_db';

    $connection = new mysqli($host, $username, $password, $database);
    if ($connection->connect_error) {
        wp_send_json_error(
            'Workbench SQL connection failed while inserting listings' . $connection->connect_error
        );
    }

    if (!$connection->select_db($database)) {
        wp_send_json_error(
            'Database not found' . $connection->error
        );
    }

    // Successfully connected to database

    // Make the query
    $timezone = new DateTimeZone('America/Denver');
	$date = new DateTime('now', $timezone);
	$timestamp = $date->format('Y-m-d H:i:s');
    $query = "INSERT INTO leads_durango (name, last_name, email, phone, comments, timestamp, year, make, model, stock, vin, source)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

    // Prepare the query
    $p_query = $connection->prepare($query);
    if (!$p_query) {
        wp_send_json_error(
            'Failed to prepare query' . $connection->error
        );
    }

    // Bind parameters
    $p_query->bind_param("ssssssssssss", $lead_data[0], $lead_data[1], $lead_data[2], $lead_data[3], $lead_data[4], $timestamp, $lead_data[6], $lead_data[7], $lead_data[8], $lead_data[9], $lead_data[10], $lead_data[11]);

    // Execute query
    $result = $p_query->execute();
    if (!$result) {
        wp_send_json_error(
            'Failed to execute query' . $connection->error
        );
    }

    // Send success response
    wp_send_json_success(
        'Lead inserted successfully'
    );

    // Close prepared statement
    $p_query->close();

    // Close connection
    $connection->close();

    wp_die();
}

function get_db_connection() {
    $host = 'inventory-database-do-user-2599605-0.c.db.ondigitalocean.com:25060';
    $username = 'junaid';
    $password = 'AVNS_ufjqHNNhDr_Pxg4FTFN';
    $database = 'dmc_database';

    // Create a new MySQLi connection
    $connection = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($connection->connect_error) {
        wp_send_json_error('Workbench SQL connection failed while inserting listings' . $connection->connect_error);
        return null;  // Return null if connection fails
    }

    // Select the database
    if (!$connection->select_db($database)) {
        wp_send_json_error('Database not found' . $connection->error);
        return null;  // Return null if database selection fails
    }

    return $connection;
}

/** Remove contact form 7 validation on contextual form */
remove_action( 'wpcf7_swv_create_schema', 'wpcf7_swv_add_select_enum_rules', 20, 2 );