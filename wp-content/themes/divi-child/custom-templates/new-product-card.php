<?php
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

		// Price handling
		$rawPrice = $meta['miscprice-1'][0] ?? 0;
		$vehiclePrice = is_numeric($rawPrice) ? number_format((int) $rawPrice, 0) : '0';
		$vehiclePriceHTML = $vehiclePrice !== '0' 
			? '<h3 class="p-0 m-0 font-helvetica font-20 font-weight-bold text-grey-3">$ ' . esc_html($vehiclePrice) . '</h3>' 
			: '<a class="font-sm text-grey-3 font-weight-bold font-helvetica" href="tel:' . esc_attr(salesPhoneNumber()) . '">Call For Price</a>';

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
?>
<div class="col-12 col-lg-6 col-xl-4 xol-xxl-3 mb-30" data-window="<?php echo $windowEngageIcon; ?>">
	<div class="position-relative mb-3 mb-md-0 bg-white listing-card-wrapper"
		 data-permalink="<?php echo esc_url( $new_permalink ); ?>">
		<div class="card-image-wrapper overflow-hidden">

            <?php 
            $stock_number = !empty($meta['stock-number'][0]) ? $meta['stock-number'][0] : '';
            $is_manager_special = !empty($stock_number) && in_array($stock_number, $managerSpecialsStocks);
            $is_price_drop = !empty($stock_number) && in_array($stock_number, $priceDropStocks);

            // Show Manager Special badge if it qualifies
            if ($is_manager_special) : ?>
            <h3 class="px-10 py-10 manager-specials-card mb-10 text-white text-center
                    font-helvetica font-lg font-weight-bold">
                <?php echo esc_html( 'Manager Specials' ); ?>
            </h3>
            <?php 
            // Show Price Drop badge ONLY if it's not a Manager Special
            elseif ($is_price_drop) : ?>
            <h3 class="px-10 py-10 mb-10 text-white text-center font-helvetica font-lg font-weight-bold" style="background:#1F2ABF;">
                🔥 PRICE DROP 🔥
            </h3>
            <?php 
            // Show fake badge if neither
            else : ?>
            <h3 class="px-10 py-10 m-0 text-center text-dark font-helvetica font-lg
                    font-weight-bold fake-recent-view-badge" style="height: 40px;"></h3>
            <?php endif; ?>

			<?php if( ! in_array( $meta['stock-number'][0], $managerSpecialsStocks ) && ! in_array( $meta['stock-number'][0], $priceDropStocks ) ) : ?>
			<h3 class="px-10 py-10 m-0 text-center text-dark font-helvetica font-lg
					   font-weight-bold fake-recent-view-badge" style="height: 40px;"></h3>
			<?php endif; ?>

			<!-- Image Slider -->
			<div class="listing-image-slider overflow-hidden position-relative">
				<div class="listing-image-slider-inner h-100 w-100 mb-4 px-10">
					<?php if( ! empty( $image_urls ) ) : ?>
					<?php foreach( $image_urls as $index => $image_url ) : ?>
					<a href="<?php echo esc_url( $new_permalink ); ?>"
					   class="d-inline-block h-100">
						<img data-src="<?php echo esc_url( $image_url ); ?>"
							 alt="<?php echo esc_attr( get_the_title() ); ?>"
							 title="<?php echo esc_attr( get_the_title() ) ?>"
							 loading="lazy"
							 decoding="async"
							 class="card-thumbnail"
							 width="365"
							 height="270" />
					</a>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<!-- Compare Box -->
				<div class="inventory-products-bar__compare-listing p-1 position-absolute
							d-flex align-items-center justify-content-end">
					<p class="text-white font-weight-bold p-0 font-lg mr-3">
						<?php echo esc_html( 'Compare' ); ?>
					</p>
					<form class="inventory-products-bar__compare-listing-form d-flex align-items-center">
						<input type="checkbox" class="chk-compare position-relative bg-white" 
							   value="<?php echo $post_id; ?>" <?php echo $checked; ?> />
					</form>
				</div>
				<!-- Vehicle CTA -->

			</div>
			<!-- Card Content Wrapper -->
			<div class="card-content-wrapper px-10 position-relative">

				<!-- On Lot Badge -->
				<?php 
		$onLot = true;
		$in_transit = false;
				?>
				<div class="vehicle-info-badge d-flex align-items-center justify-content-between">
					<div class="vehicle-info-badge-wrapper">
						<?php if( $onLot || $in_transit ) : ?>
						<span class="badge badge-green rounded-0 py-1 px-3 border border-dark 
									 font-segoe text-capitalize font-sm d-none">
							<?php echo $onLot ? esc_html('On Lot') : ($in_transit ? esc_html('In Transit') : '');  ?>
						</span>
						<?php endif; ?>
					</div>
					<div class="vehicle-like-box">
						<span class="fa-regular fa-heart card-vehicle-like cursor-pointer 
									 <?php echo in_array( $post_id, $likedVehicleIDs ) ? 'd-none' : ''; ?>"
							  data-icon-show="<?php echo in_array( $post_id, $likedVehicleIDs ) ? 'false' : 'true' ?>"
							  data-id="<?php echo esc_attr( $post_id ); ?>"></span>
						<img src="<?php echo esc_url( home_url() ) ?>/wp-content/themes/divi-child/assets/images/icon-vehicle-liked.png"
							 alt="Vehicle Liked"
							 data-icon-show="<?php echo in_array( $post_id, $likedVehicleIDs ) ? 'true' : 'false'; ?>"
							 class="card-vehicle-liked cursor-pointer
									<?php echo in_array( $post_id, $likedVehicleIDs ) ? '' : 'd-none' ?>"
							 data-id="<?php echo esc_attr( $post_id ); ?>" />
					</div>
				</div>

				<!-- Vehicle Title -->
				<div class="d-flex align-items-center justify-content-between vehicle-title-wrapper overflow-hidden my-2">
					<div>
						<h4 class="text-uppercase font-weight-light font-md vehicle-title-first text-grey-3 mb-0 pb-0">
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
							<h2 class="vehicle-title-second text-grey-3 font-helvetica font-md p-0 m-0 font-weight-bold">
								<?php echo sprintf('%s %s %s',
												   esc_html( $meta['make'][0] ),
												   esc_html( $meta['model'][0] ),
												   esc_html( $meta['series'][0] ));
								?>
							</h2>
						</a>
					</div>
					<div class="listview-price listview-visible d-none justify-content-end">
						<h3 class="our-best-price-block font-weight-bold font-lg text-dark d-flex align-items-center justify-content-between pb-0 mb-0">
							<?php echo $vehiclePriceHTML; ?>
						</h3>
					</div>
				</div>

				<!-- Metadata -->
				<div class="vehicle-meta-block mb-2 font-weight-bold text-grey-3 font-sm">
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html('VIN#: '); ?></span></a>
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html($meta['vin-number'][0]); ?></span></a>
				</div>

				<div class="vehicle-meta-block mb-2 font-weight-bold text-grey-3 font-sm">
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html('STOCK#: '); ?></span></a>
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html($meta['stock-number'][0]); ?></span></a>
				</div>

				<div class="vehicle-meta-block mb-2 font-weight-bold text-grey-3 font-sm">
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html('Mileage: '); ?></span></a>
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo isset($meta['odometer'][0]) && is_numeric($meta['odometer'][0]) ? number_format((float) $meta['odometer'][0]) : 'N/A'; ?></span></a>
				</div>

				<?php if ( ! empty( $meta['certified'][0] ) ) : ?>
				<div class="vehicle-meta-block mb-2 font-weight-bold text-grey-3 font-sm">
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html('Certified: '); ?></span></a>
					<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html($meta['certified'][0]); ?></span></a>
				</div>
				<?php endif; ?>


				<!-- More vehicle metadata -->
				<div class="vehicle-meta-block vehicle-meta-block-more">
					<div class="d-flex align-items-center justify-content-between
								font-weight-bold text-grey-3 font-sm w-100">
						<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html( 'MSRP' ); ?></span></a>
						<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span>
							<?php
		echo is_numeric($current_price) ? '$ ' . esc_html(number_format((int) $current_price)) : '';
							?>
							</span></a>
					</div>

					</span></a>
		</div>

		<?php 
		if( ! empty( $dealer_discount ) && $dealer_discount > 0 && ( (int) $rawPrice !== 0 || (int) $original_price !== 0 ) ) : 
		?>
		<div class="d-flex align-items-center justify-content-between
					font-weight-bold text-grey-3 font-sm w-100">
			<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style">
				<span><?php echo esc_html( 'Dealer Discount' ); ?></span>
			</a>
			<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style">
				<span>
					<?php

		if ( $is_toyota ) {
			$dealer_discount = $meta['miscprice-2'][0] ?? 0;
		}

		echo '- $ ' . esc_html(number_format((int) $dealer_discount));
					?>
				</span>
			</a>
		</div>

		<?php
		endif; 
		?>
		<div class="d-flex align-items-center justify-content-between
					font-weight-bold text-grey-3 font-sm w-100">
			<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style"><span><?php echo esc_html( 'Total Price' ); ?></span></a>
			<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style">
				<span>
					<?php
		echo '$ ' . esc_html(number_format((int) $original_price)); ?>
				</span>
			</a>
		</div>


		<?php if( ! empty( $rebate ) && (int) $rebate > 0 ) : ?>
		
		<div class="d-flex align-items-center justify-content-between
					font-weight-bold text-grey-3 font-sm w-100">
			<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style">
				<span><?php echo esc_html( 'Rebate' ); ?></span>
			</a>
			<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style">
				<span>
					<?php echo '- $ ' . esc_html(number_format((int) $rebate)); ?>
				</span>
			</a>
		</div>

		<?php
		endif; 
		?>
		<div class="d-flex align-items-center justify-content-between 
					our-best-price-block font-weight-bold text-dark font-lg w-100">
			<a href="<?php echo esc_url( $new_permalink ); ?>" class="no-style">
				<span><?php echo esc_html( 'Our Best Price' ); ?></span>
			</a>
			<?php
		
		if ( $is_toyota && ! empty( $meta['disposition'][0] ) ) {
			$original_price = (int) $original_price - (int) $meta['disposition'][0];
			echo '<a href="' . esc_url($new_permalink) . '" class="no-style"><span>$ ' . esc_html(number_format((int)$original_price)) . '</span></a>';
		} else if ( $is_toyota ) {
			echo '<a href="' . esc_url($new_permalink) . '" class="no-style"><span>$ ' . esc_html(number_format((int)$original_price)) . '</span></a>';
		} else {
			echo $vehiclePriceHTML;	
		}

			?>
		</div>


		<!-- Divider -->
		<hr class="vehicle-card-divider" />
		<div class="vehicle-color-block mb-2 d-flex align-items-center
					justify-content-start text-grey-3 text-uppercase font-sm
					font-weight-light">
			<div class="vehicle-color-ball d-flex align-items-center mr-2">

				<?php if( ! empty( $exterior_color_val ) ) : ?>
				<span class="card-color-ball exterior-color-ball
							 rounded-circle-px mr-1 d-inline-block"
					  data-toggle="tooltip" data-placement="top"
					  title="Exterior Color: <?php echo esc_attr( ucwords( $meta['exterior-color'][0] ?? '' ) ); ?>"
					  data-key="<?php echo esc_attr( $meta['exterior-color'][0] ?? '' ); ?>"
					  style="background: #<?php echo esc_attr( $exterior_color_val ); ?>">
				</span>
				<?php else: ?>
				<span class="card-color-ball exterior-color-ball
							 rounded-circle-px mr-1 d-flex align-items-center justify-content-center"
					  data-toggle="tooltip" data-placement="top"
					  title="Exterior Color: <?php echo esc_attr( ucwords( $meta['exterior-color'][0] ?? '' ) ); ?>"
					  data-key="<?php echo esc_attr( $meta['exterior-color'][0] ?? '' ); ?>"
					  style="background: #fff;border:1px solid black;">
					<i class="fa-solid fa-xmark"></i>
				</span>
				<?php endif; ?>


			</div>
			<span class="text-capitalize"
				  data-toggle="tooltip" data-placement="top"
				  title="Exterior Color: <?php echo esc_attr( ucwords( $meta['exterior-color'][0] ?? '' ) ); ?>"
				  data-key="<?php echo esc_attr( $meta['exterior-color'][0] ?? '' ); ?>">
				EXTERIOR: <?php echo esc_html( wp_trim_words( $meta['exterior-color'][0], 3 ) ); ?></span>
		</div>
		<div class="vehicle-color-block d-flex align-items-center justify-content-start
					text-grey-3 text-uppercase font-ms font-weight-light">
			<div class="vehicle-color-ball d-flex align-items-center mr-2">
				<?php if( ! empty( $interior_color_val ) ) : ?>
				<span class="card-color-ball exterior-color-ball rounded-circle-px
							 mr-1 d-inline-block"
					  data-toggle="tooltip" data-placement="top"
					  title="Interior Color: <?php echo esc_attr( ucwords( $meta['interior-color'][0] ?? '' ) ); ?>"
					  data-key="<?php echo esc_attr( $meta['interior-color'][0] ?? '' ); ?>"
					  style="background: #<?php echo esc_attr( $interior_color_val ); ?>">
				</span>
				<?php else: ?>
				<span class="card-color-ball exterior-color-ball rounded-circle-px
							 mr-1 d-flex align-items-center justify-content-center"
					  data-toggle="tooltip" data-placement="top"
					  title="Interior Color: <?php echo esc_attr( ucwords( $meta['interior-color'][0] ?? '' ) ); ?>"
					  data-key="<?php echo esc_attr( $meta['interior-color'][0] ?? '' ); ?>"
					  style="background: #fff;border: 1px solid black;">
					<i class="fa-solid fa-xmark"></i>
				</span>
				<?php endif; ?>
			</div>
			<span class="text-capitalize"
				  data-toggle="tooltip" data-placement="top"
				  title="Interior Color: <?php echo esc_attr( ucwords( $meta['interior-color'][0] ?? '' ) ); ?>"
				  data-key="<?php echo esc_attr( $meta['interior-color'][0] ?? '' ); ?>">
				INTERIOR: <?php echo esc_html( wp_trim_words( $meta['interior-color'][0], 3 ) ); ?></span>
		</div>

		<hr class="vehicle-card-divider bottom" />		
	</div>
	<div class="btnWraper">
		<div class="explore-more-cta mb-20 ">
			<a href="<?php echo esc_url( $new_permalink ); ?>"
			   class="btn btn-primary w-100 d-inline-block
					  font-weight-bold rounded"><?php echo esc_html( 'Explore More' ); ?></a>
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
