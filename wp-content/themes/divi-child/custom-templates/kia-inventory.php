<?php
if ( ! function_exists( 'dmc_new_inventory_card' ) ) {
function dmc_new_inventory_card() {
	$post_id = get_the_ID();
	$transient_key = 'new_product_card_' . $post_id;
	$card = get_transient($transient_key);

	// 	if( $card ) {
	// 		return $card;
	// 	}

	if ($card === false) {
		global $wpdb;
		$user_ip = getUserIP();

		// Fetch post meta
		$meta = get_post_meta($post_id);

		// Check year condition
		$year = !empty($meta['year'][0]) ? intval($meta['year'][0]) : 0;
		if ($year <= 2021) {
			return '';
		}

		// Fetch user data
		$recently_viewed = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}user_recently_viewed WHERE user_ip = %s", $user_ip), ARRAY_A);
		if ($wpdb->last_error) {
			error_log('DB Error (recently_viewed): ' . $wpdb->last_error);
		}
		$liked_vehicles = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}user_liked_vehicles WHERE user_ip = %s", $user_ip), ARRAY_A);
		if ($wpdb->last_error) {
			error_log('DB Error (liked_vehicles): ' . $wpdb->last_error);
		}
		$compared_vehicles = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}user_compared_vehicles WHERE user_ip = %s", $user_ip), ARRAY_A);
		if ($wpdb->last_error) {
			error_log('DB Error (compared_vehicles): ' . $wpdb->last_error);
		}

		$recentViewedResult = $recently_viewed[0] ?? [];
		$likedVehiclesResult = $liked_vehicles[0] ?? [];
		$compareVehiclesResult = $compared_vehicles[0] ?? [];

		$recentListingsIDs = !empty($recentViewedResult['recent_view_vehicles']) 
			? maybe_unserialize($recentViewedResult['recent_view_vehicles']) : [0];
		$likedVehicleIDs = !empty($likedVehiclesResult['user_liked_vehicles']) 
			? maybe_unserialize($likedVehiclesResult['user_liked_vehicles']) : [0];
		$compareVehicleIDs = !empty($compareVehiclesResult['user_compared_vehicles']) 
			? maybe_unserialize($compareVehiclesResult['user_compared_vehicles']) : [0];
		// Phone number logic based on make
		$make = strtolower($meta['make'][0] ?? '');
		$phone_number = '';
		$phone_text = '';
		
		// Determine phone number based on make
		switch ($make) {
			case 'ford':
				$phone_number = '970-880-7204';
				$phone_text = 'Call 970-880-7204';
				break;
			case 'kia':
				$phone_number = '970-385-8218';
				$phone_text = 'Call 970-385-8218';
				break;
			case 'toyota':
				$phone_number = '970-399-1062';
				$phone_text = 'Call 970-399-1062';
				break;
			default:
				$phone_number = salesPhoneNumber();
				$phone_text = 'Call ' . salesPhoneNumber();
				break;
		}
		// Price handling
		$rawPrice = $meta['miscprice-1'][0] ?? 0;
		$vehiclePrice = is_numeric($rawPrice) ? number_format((int) $rawPrice, 0) : '0';
		$vehiclePriceHTML = $vehiclePrice !== '0' 
			? '<h3 class="p-0 m-0 font-helvetica font-20 font-weight-bold text-grey-3">$ ' . esc_html($vehiclePrice) . '</h3>' 
			: '<a class="font-sm text-grey-3 font-weight-bold font-helvetica" href="tel:' . esc_attr($phone_number) . '">Call For Price</a>';

		$current_price = !empty($meta['current_price'][0]) ? intval($meta['current_price'][0]) : 0;
		$original_price = !empty($meta['original_price'][0]) ? intval($meta['original_price'][0]) : 0;
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

		$vehiclePriceHTML = $vehiclePrice !== '0' 
			? '<h3 class="p-0 m-0 font-helvetica font-20 font-weight-bold text-grey-3">$ ' . esc_html($vehiclePrice) . '</h3>' 
			: '<h3 class="p-0 m-0 font-helvetica font-20 font-weight-bold text-grey-3">$ ' . number_format((int) $original_price) . '</h3>';

		// Other fields
		$managerSpecialsStocks = get_field('managers_specials_vehicles_stock_number', 'options');
		$managerSpecialsStocks = explode(',', $managerSpecialsStocks);
		$managerSpecialsStocks = array_map('trim', $managerSpecialsStocks);
		$priceDropStocks = get_field('price_drop_vehicles_stock_numbers', 'options');
		$priceDropStocks = explode(',', $priceDropStocks);
		$priceDropStocks = array_map('trim', $priceDropStocks);
		$windowSticker = get_field('carfax_badge_image_group', 'options');
		$velocityEngage = get_field('velocity_engage_badge_image', 'options');
		$windowStickerInfo = getImageSizeInfo($windowSticker);
		$velocityEngageInfo = getImageSizeInfo($velocityEngage);
		$windowEngageIcon = get_field('window_sticker_badge_image', 'options');
		$windowEngageInfo = getImageSizeInfo($windowEngageIcon);
		$checked = in_array($post_id, $compareVehicleIDs) ? 'checked' : '';
		$is_toyota = strtolower($meta['make'][0]) === 'toyota' && $meta['condition'][0] === 'N';
		$make = $meta['make'][0];
		$condition = $meta['condition'][0];
		
		// Image handling
		$image_urls = [];
		$connection = get_db_connection();
		if ($connection) {
			$query = "SELECT vauto_url FROM dmc_images WHERE vin = ?";
			$stmt = $connection->prepare($query);
			if ($stmt) {
				$stmt->bind_param("s", $meta['vin-number'][0]);
				$stmt->execute();
				$result = $stmt->get_result();
				while ($row = $result->fetch_assoc()) {
					if (!empty($row['vauto_url'])) {
						$image_urls[] = $row['vauto_url'];
					}
				}
				$stmt->close();
			} else {
				error_log('Failed to prepare statement: ' . $connection->error);
			}
		} else {
			error_log('Failed to connect to database in dmc_new_inventory_card');
		}

		$urlToRemove = 'http://vehicle-photos-published.vauto.com/04/db/a3/0f-009d-4d84-ba0a-fe04a042c1d5/image-1.jpg';
		if (count($image_urls) > 1) {
			$urlCount = array_count_values($image_urls)[$urlToRemove] ?? 0;
			if ($urlCount > 1) {
				if (count(array_unique($image_urls)) === 1) {
					$image_urls = [$urlToRemove];
				} else {
					$image_urls = array_filter($image_urls, fn($url) => $url !== $urlToRemove);
					$image_urls = array_values($image_urls);
				}
			}
		}

		$has_featured_image = !empty($image_urls) && !(count($image_urls) === 1 && $image_urls[0] === $urlToRemove);
		if (!$has_featured_image) {
			$vin_number = !empty($meta['vin-number'][0]) ? $meta['vin-number'][0] : '';
			if ($vin_number) {
				$normalized_vin = strtolower(str_replace(' ', '-', $vin_number));
				$jellyB_img_urls = dmc_get_image_urls();
				foreach ($jellyB_img_urls as $jelly_url) {
					$filename = basename($jelly_url, '.png');
					$normalized_filename = strtolower($filename);
					if (strpos($normalized_filename, $normalized_vin) !== false) {
						$image_urls = [$jelly_url];
						$has_featured_image = true;
						break;
					}
				}
			}
			if (!$has_featured_image) {
				$model = !empty($meta['model'][0]) ? $meta['model'][0] : '';
				if ($model) {
					$normalized_model = strtolower(str_replace(' ', '-', $model));
					foreach ($jellyB_img_urls as $jelly_url) {
						$filename = basename($jelly_url, '.png');
						$filename_parts = explode('--', $filename);
						$filename_model = $filename_parts[0];
						$filename_model = preg_replace('/^\d{4}-/', '', $filename_model);
						$normalized_filename = strtolower($filename_model);
						if (strpos($normalized_filename, $normalized_model) !== false) {
							$image_urls = [$jelly_url];
							break;
						}
					}
				}
			}
		}

		if (count($image_urls) >= 5) {
			$image_urls = array_slice($image_urls, 0, 5);
		}

		$exterior_color = preDefinedColors(strtolower($meta['exterior-color'][0] ?? ''));
		$interior_color = preDefinedColors(strtolower($meta['interior-color'][0] ?? ''));
		$exterior_color_val = $exterior_color['key'] ?? '';
		$interior_color_val = $interior_color['key'] ?? '';

		if (!$exterior_color_val) {
			foreach (explode(' ', strtolower($meta['exterior-color'][0] ?? '')) as $color) {
				if ($returnedColor = preDefinedColors($color)) {
					$exterior_color_val = $returnedColor['key'];
					break;
				}
			}
		}

		if (!$interior_color_val) {
			foreach (explode(' ', strtolower($meta['interior-color'][0] ?? '')) as $color) {
				if ($returnedColor = preDefinedColors($color)) {
					$interior_color_val = $returnedColor['key'];
					break;
				}
			}
		}
		
		$new_permalink = get_the_permalink();

		ob_start();
		static $style_output = false;
		if (!$style_output) {
			$style_output = true;
?>
<style>
.listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li {
    width: 16px !important;
    height: 16px !important;
}
.listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li button {
    width: 16px !important;
    height: 16px !important;
}
.listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li.slick-active {
    width: 28px !important;
}
.listing-image-slider.MySlider .listing-image-slider-inner .slick-dots li.slick-active button {
    width: 28px !important;
    height: 16px !important;
    border-radius: 8px !important;
    background-color: #3e8873 !important;
    border-color: #3e8873 !important;
}
.btn:hover{
    border-color: #17453b!important;
    background:white!important;
    color:#17453b!important;
    font-weight: 800 !important;
}

@media (min-width: 1200px) {
    .listing-image-slider .slick-list {
        height: 300px;
    }
.listing-image-slider {
    min-height: 340px !important;
}
}

</style>
<script>
(function(){
	if (window.dmcCopyBound) return;
	window.dmcCopyBound = true;
	document.addEventListener('click', function(e) {
		var el = e.target.closest('.copy-stock, .copy-vin');
		if (el && el.dataset.copyText !== undefined) {
			e.preventDefault();
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(el.dataset.copyText);
			}
		}
	});
})();

// Lazy loading with retry logic for images
(function(){
	if (window.dmcLazyLoadBound) return;
	window.dmcLazyLoadBound = true;
	
	var MAX_RETRIES = 3;
	var RETRY_DELAY = 1000; // 1 second
	
	function loadImage(img) {
		if (!img.dataset.src) return;
		
		var retryCount = parseInt(img.dataset.retry) || 0;
		if (retryCount >= MAX_RETRIES) {
			// Max retries reached, show error placeholder
			img.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 365 270\'%3E%3Crect width=\'365\' height=\'270\' fill=\'%23e0e0e0\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' fill=\'%23999\' font-family=\'Arial\' font-size=\'14\'%3EImage unavailable%3C/text%3E%3C/svg%3E';
			img.classList.add('lazy-load-error');
			return;
		}
		
		var tempImg = new Image();
		tempImg.onload = function() {
			img.src = img.dataset.src;
			img.classList.add('lazy-load-loaded');
			img.classList.remove('lazy-load-loading');
		};
		tempImg.onerror = function() {
			img.dataset.retry = (retryCount + 1).toString();
			img.classList.add('lazy-load-loading');
			setTimeout(function() {
				loadImage(img);
			}, RETRY_DELAY * (retryCount + 1)); // Exponential backoff
		};
		tempImg.src = img.dataset.src;
	}
	
	function initLazyLoad() {
		var images = document.querySelectorAll('.lazy-load-image:not(.lazy-load-loaded):not(.lazy-load-loading)');
		images.forEach(function(img) {
			// Check if image is in viewport or will be soon
			var rect = img.getBoundingClientRect();
			var isVisible = rect.top < window.innerHeight + 200 && rect.bottom > -200;
			
			if (isVisible) {
				img.classList.add('lazy-load-loading');
				loadImage(img);
			}
		});
	}
	
	// Use Intersection Observer if available for better performance
	if ('IntersectionObserver' in window) {
		var imageObserver = new IntersectionObserver(function(entries, observer) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					var img = entry.target;
					img.classList.add('lazy-load-loading');
					loadImage(img);
					observer.unobserve(img);
				}
			});
		}, {
			rootMargin: '200px'
		});
		
		// Observe all lazy load images
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('.lazy-load-image').forEach(function(img) {
				imageObserver.observe(img);
			});
		});
		
		// Also observe dynamically added images
		var originalQuerySelector = document.querySelectorAll;
		document.addEventListener('DOMContentLoaded', function() {
			setInterval(function() {
				document.querySelectorAll('.lazy-load-image:not([data-observed])').forEach(function(img) {
					img.setAttribute('data-observed', 'true');
					imageObserver.observe(img);
				});
			}, 500);
		});
	} else {
		// Fallback for browsers without IntersectionObserver
		window.addEventListener('load', initLazyLoad);
		window.addEventListener('scroll', initLazyLoad);
		window.addEventListener('resize', initLazyLoad);
		
		// Also check periodically for dynamically added images
		setInterval(initLazyLoad, 1000);
	}
	
	// Initialize on page load
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initLazyLoad);
	} else {
		initLazyLoad();
	}
})();
</script>
<?php
		}
?>
<div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 mb-30" data-window="<?php echo esc_attr($windowEngageIcon); ?>">
	<div class="position-relative mb-3 mb-md-0 bg-white listing-card-wrapper new-product-card"
		 data-permalink="<?php echo esc_url( $new_permalink ); ?>" style="padding: 0px; min-height: 580px;">
		<div class="card-image-wrapper overflow-hidden">

            <?php 
            $stock_number = !empty($meta['stock-number'][0]) ? $meta['stock-number'][0] : '';
            $is_manager_special = !empty($stock_number) && in_array($stock_number, $managerSpecialsStocks);
            $is_price_drop = !empty($stock_number) && in_array($stock_number, $priceDropStocks);

            // Show Manager Special badge if it qualifies
            if ($is_manager_special) : ?>
            <h3 class="px-10 py-10 mb-0 text-center font-helvetica font-lg font-weight-bold" style="background: #FFD700; color: #000;">
                <?php echo esc_html( 'Manager Specials' ); ?>
            </h3>
            <?php 
            // Show Price Drop badge ONLY if it's not a Manager Special
            elseif ($is_price_drop) : ?>
            <h3 class="px-10 py-10 mb-0 text-white text-center font-helvetica font-lg font-weight-bold" style="background:#1F2ABF;">
                🔥 PRICE DROP 🔥
            </h3>
            <?php 
            // Show fake badge if neither
            else : ?>
            <h3 class="px-10 py-10 m-0 text-center text-dark font-helvetica font-lg
                    font-weight-bold fake-recent-view-badge" style="height: 40px;"></h3>
            <?php endif; ?>

			<!-- Image Slider -->
			<div class="listing-image-slider MySlider overflow-hidden position-relative">
				<div class="listing-image-slider-inner h-100 w-100">
					<?php if( ! empty( $image_urls ) ) : ?>
					<?php foreach( $image_urls as $index => $image_url ) : ?>
					<a href="<?php echo esc_url( $new_permalink ); ?>"
					   class="d-inline-block h-100">
						<img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 365 270'%3E%3Crect width='365' height='270' fill='%23f0f0f0'/%3E%3C/svg%3E"
							 data-src="<?php echo esc_url( $image_url ); ?>"
							 alt="<?php echo esc_attr( get_the_title() ); ?>"
							 title="<?php echo esc_attr( get_the_title() ) ?>"
							 loading="lazy"
							 decoding="async"
							 class="card-thumbnail lazy-load-image"
							 width="365"
							 height="270" 
							 data-retry="0"
							 style="padding:10px 10px;" />
					</a>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			

			<hr style="border: 1px solid #efe7e7!important;">
			<!-- Image Navigation with Arrows and Dots -->
			
			<!-- Compare and Pin Icons -->
			<div class="compare-pin-wrapper d-flex align-items-center justify-content-between px-10 py-2">
				<button type="button" class="btn-compare-manager d-flex align-items-center gap-2" style="background: #3f8873; color: white; border: none; padding: 2px 16px; border-radius:15px; font-weight: bold; font-size: 13px; cursor: pointer;" onclick="this.nextElementSibling.querySelector('.chk-compare').click();">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="-9 -9 18 18" width="14" height="14" style="margin-right: 6px; flex-shrink: 0;">
					<path fill="none" stroke="#fff" stroke-miterlimit="100" d="M0 8.5c-4.7 0-8.5-3.8-8.5-8.5 0-4.7 3.8-8.5 8.5-8.5 4.7 0 8.5 3.8 8.5 8.5 0 4.7-3.8 8.5-8.5 8.5z"/>
					<path fill="none" stroke="#fff" stroke-miterlimit="100" d="M0 -4.5 V4.5"/>
					<path fill="none" stroke="#fff" stroke-miterlimit="100" d="M-4.5 0 H4.5"/>
				</svg>
					<span>COMPARE</span>
				</button>
				<form class="inventory-products-bar__compare-listing-form d-flex align-items-center" style="display: none;">
					<input type="checkbox" style="display:none!important;" class="chk-compare position-relative bg-white" 
						   value="<?php echo $post_id; ?>" <?php echo $checked; ?> />
				</form>
				<svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 30" width="18" height="18" style="cursor: pointer; fill: #3f8873;height:24px;width:24px;">
					<defs><clipPath clipPathUnits="userSpaceOnUse" id="cp1-<?php echo $post_id; ?>"><path d="m-290-341h340v850h-340z"/></clipPath></defs>
					<g clip-path="url(#cp1-<?php echo $post_id; ?>)">
						<path fill-rule="evenodd" d="m13.5 17.7v-6.9c-2.4-0.6-4.1-2.8-4.1-5.3 0-3 2.4-5.5 5.4-5.5 3.1 0 5.5 2.5 5.5 5.5 0 2.5-1.7 4.7-4.1 5.3v6.9c0 4.3-0.8 6.6-1.4 6.6-0.6 0-1.3-2.3-1.3-6.6zm1.6-13.8c0-1-0.8-1.9-1.8-1.9-1 0-1.9 0.9-1.9 1.9 0 1 0.9 1.9 1.9 1.9 1 0 1.8-0.9 1.8-1.9zm-14.2 19.5c0-3.9 6.3-6.3 10.3-6.3v2.3c-2.8 0-7.2 1.5-7.2 3.7 0 2.3 4.1 4.2 10.8 4.2 6.7 0 10.8-2 10.8-4.2 0-2.2-4.3-3.7-7.1-3.7v-2.3c4 0 10.2 2.4 10.2 6.3 0 3.3-5.1 6.3-13.9 6.3-8.8 0-13.9-3-13.9-6.3z"/>
					</g>
				</svg>
			</div>
			<!-- Card Content Wrapper -->
			<div class="card-content-wrapper px-10 position-relative">

				<!-- Vehicle Title -->
				<div class="vehicle-title-wrapper my-3" style="margin-bottom: 0 !important;">
					<h4 class="text-uppercase font-md vehicle-title-first text-grey-3" style="font-weight: 600; font-size: 17px;">
						<?php 
		$condition_text = ( isset( $meta['condition'][0] ) && $meta['condition'][0] === 'N' ) ? 'NEW' : 
		( isset( $meta['condition'][0] ) && $meta['condition'][0] === 'U' ? 'USED' : esc_html( $meta['condition'][0] ) );
		echo sprintf( '%s %s',
					 esc_html( $condition_text ),
					 esc_html( $meta['year'][0] )
					); 
						?>
					</h4>
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style">
						<h2 class="vehicle-title-second font-helvetica p-0 m-0 font-weight-bold" style="color: #3f8873; font-size: 19px; font-weight: 700;">
							<?php echo sprintf('%s %s %s',
											   esc_html( $meta['make'][0] ),
											   esc_html( $meta['model'][0] ),
											   esc_html( $meta['series'][0] ));
							?>
						</h2>
					</a>
				</div>

				<!-- Stock Number with Copy Icon -->
				<div class="vehicle-meta-block mb-2 mt-3 d-flex align-items-center" style="justify-content: flex-start;">
					<span class="font-weight-bold font-md" style="color: #454545;">STOCK #: <?php echo esc_html($meta['stock-number'][0]); ?></span>
					<i class="fa-regular fa-copy ml-2 cursor-pointer copy-stock" style="color: #3f8873; font-size: 16px;" 
					   data-copy-text="<?php echo esc_attr($meta['stock-number'][0] ?? ''); ?>" role="button" aria-label="Copy stock number"></i>
				</div>

				<!-- VIN with Copy Icon -->
				<div class="vehicle-meta-block mb-3 d-flex align-items-center"  style="justify-content: flex-start;">
					<span class="font-weight-bold font-md" style="color: #454545;">VIN: <?php echo esc_html($meta['vin-number'][0]); ?></span>
					
					<i class="fa-regular fa-copy ml-2 cursor-pointer copy-vin" style="color: #3f8873; font-size: 16px;" 
					   data-copy-text="<?php echo esc_attr($meta['vin-number'][0] ?? ''); ?>" role="button" aria-label="Copy VIN"></i>
				</div>

				<!-- Pricing Section -->
				<div class="pricing-section my-3">
					<div class="d-flex align-items-center justify-content-between font-weight-bold font-md w-100 mb-2" style="color: #454545;">
						<span><?php echo esc_html( 'MSRP' ); ?></span>
						<span>
							<?php
							echo is_numeric($current_price) ? '$ ' . esc_html(number_format((int) $current_price)) : '$ ' . esc_html(number_format((int) $original_price));
							?>
						</span>
					</div>

					<div class="d-flex align-items-center justify-content-between font-weight-bold font-lg w-100" style="color: #3e8873;">
						<span><?php echo esc_html( 'Our Best Price' ); ?></span>
						<span>
							<?php
							if ( $is_toyota && ! empty( $meta['disposition'][0] ) ) {
								$final_price = (int) $original_price - (int) $meta['disposition'][0];
								echo '$ ' . esc_html(number_format((int)$final_price));
							} else if ( $is_toyota ) {
								echo '$ ' . esc_html(number_format((int)$original_price));
							} else {
								if ($vehiclePrice !== '0') {
									echo '$ ' . esc_html($vehiclePrice);
								} else {
									echo '$ ' . esc_html(number_format((int) $original_price));
								}
							}
							?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="btnWraper px-10 pb-10 mb-3" style="position:unset!important;transform:none!important;width:100%!important;margin-top: 1.25rem !important;">
		<div class="d-flex flex-column">
			<a href="<?php echo esc_url( $new_permalink ); ?>"
			   class="btn w-100 d-inline-block font-weight-bold rounded mb-2 text-center text-uppercase"
			   style="background: #17453b; color: #fff; font-family: 'Helvetica Neue', Helvetica, sans-serif; padding: 12px; text-decoration: none; border-radius: 9px !important;">
				<?php echo esc_html( "I'M INTERESTED" ); ?>
			</a>
			<a href="tel:<?php echo esc_attr($phone_number); ?>"
			   class="btn w-100 d-inline-block font-weight-bold rounded text-center"
			   style="background: #17453b; color: #fff; font-family: 'Helvetica Neue', Helvetica, sans-serif; padding: 12px; text-decoration: none; border-radius: 9px !important;">
				<?php echo esc_html( $phone_text ); ?>
			</a>
		</div>

		<!-- <div class="d-flex align-items-center justify-content-between mb-20 vehicle-cta-wrapper listview-hidden">
<span class="font-sm font-helvetica font-weight-lighter text-grey-1">
<?php //echo esc_html( 'History Report' ); ?>
</span>
<a href="<?php //echo esc_url( get_the_permalink() ); ?>"
class="font-sm font-helvetica font-weight-normal text-sixth">
<?php //echo esc_html( 'Vehicle Details>>' ); ?>
</a>
</div>

<div class="d-flex align-items-center justify-content-between vehicle-stickers-wrapper listview-hidden vehicle-stickers-container">
<a href="javascript:void(0)" class="listing-card__cta w-100 d-flex align-items-center mx-auto"
data-name="carfax" data-vas-vin="<?php echo esc_attr( $meta['vin-number'][0] ); ?>"
style="width: 80% !important;">
<img src="<?php // echo esc_url( $windowStickerInfo['image'] ); ?>"
alt="<?php // echo esc_attr( $windowStickerInfo['alt'] ); ?>"
width="<?php // echo esc_attr( $windowStickerInfo['width'] ); ?>"
height="<?php // echo esc_attr( $windowStickerInfo['height'] ); ?>"
loading="lazy"
class="w-100 img-fluid" />
</a>
</div>	 -->
	</div>	
</div>
</div>
</div>

<?php
		$card = ob_get_clean();
		set_transient($transient_key, $card, 24 * HOUR_IN_SECONDS);

	}

	delete_transient($transient_key);
	return $card;
}
}