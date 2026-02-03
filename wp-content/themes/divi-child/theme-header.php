<?php
/**
 * Template partial used to add content to the page in Theme Builder.
 * Duplicates partial content from header.php in order to maintain
 * backwards compatibility with child themes.
 */
?>
<!-- Scheduled Banners -->

<header id="header-wrapper">

	<!-- TOP HEADER -->
	<div id="top-header-wrapper" class="px-g top-header d-flex align-items-center justify-content-start justify-content-lg-between">
		<?php wp_nav_menu(
			array(
				'menu' => 'menu-header-top',
				'menu_class' => 'menu-header-top-wrapper d-none d-lg-flex',
				'container' => 'nav',
			),
		); ?>
		<div class="align-items-center d-none d-lg-flex">

			<div class="header-sale-service-hours-wrapper mr-2 mr-xl-4 position-relative">
				<p class="text-white p-0 header-sale-service-hours-text d-flex align-items-center">

				<!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
				<svg width="24px" height="24px" class="mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12 7V12H15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#9dbde6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>	
				Sale & Service Hours</p>
				<div class="header-sale-service-tooltip px-15 position-absolute bg-white">
					<div class="row">
						<div class="col-12 col-md-6 pt-3 store-hours-wrapper">
							<p class="mb-3 text-primary font-segoe font-weight-bold hours-heading">SALE HOURS</p>
							<?php
							$store_sale_hours = get_field('store_sale_hours','options');
							if($store_sale_hours) {
								foreach($store_sale_hours as $index => $hours) {
									$day = $hours['store_sale_hours_day_name'];
									$time = $hours['store_sale_hours_time'];
									echo '<div class="d-flex align-items-center justify-content-between store-hours-block store-hours-'.($index+1).'">'.
									'<p class="font-segoe text-grey-3 mb-3 pb-0">'.$day.'</p>'.
									'<p class="font-segoe text-grey-3 mb-3 pb-0 text-transform-uppercase">'.$time.'</p>'.
									'</div>';
								}
							}
							?>
						</div>
						<div class="col-12 col-md-6 pt-3 store-hours-wrapper">
							<p class="mb-3 font-segoe text-primary font-weight-bold hours-heading">SERVICE HOURS
							<i class="fa fa-info-circle ml-3" data-toggle="tooltip" data-placement="bottom" title="Service is located at Durango Motor Company"></i></p>
							<?php
								$store_sale_hours = get_field('store_services_hours','options');
								if($store_sale_hours) {
									foreach($store_sale_hours as $index => $hours) {
										$day = $hours['store_service_hours_day_name'];
										$time = $hours['store_service_hours_time'];
										echo '<div class="d-flex align-items-center justify-content-between store-hours-'.($index+1).'">'.
										'<p class="text-white font-segoe mb-3 pb-0 text-grey-3">'.$day.'</p>'.
										'<p class="text-white font-segoe mb-3 pb-0 text-grey-3 text-transform-uppercase">'.$time.'</p>'.
										'</div>';
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>

			<p class="contact-block p-0 mr-2 mr-xl-4 d-flex align-items-center">
				<i class="fa-solid fa-phone text-seventh mr-2"></i>
				<a href="tel:<?php echo salesPhoneNumber(); ?>" class="text-white">
				<?php
				$phoneNumber = get_field('quick_call_phone_number', 'options');
				$formattedPhoneNumber = '+1 (' . substr($phoneNumber, 1, 3) . ') ' . substr($phoneNumber, 4, 3) . '-' . substr($phoneNumber, 7);
				
				echo $formattedPhoneNumber;				
				?>
				</a>
			</p>
			<p class="contact-block p-0 m-0 d-flex align-items-center">
				<i class="fa-solid fa-location-dot text-seventh mr-2"></i>
				<a href="https://goo.gl/maps/i8nQAF5RFAGAYhfF6" target="_blank" class="text-white">
					<?php echo get_field('dealership_location', 'options'); ?>
				</a>
			</p>
		</div>
		<!-- Mobile Elements -->
		<div class="d-flex d-lg-none align-items-center justify-content-start w-100 flex-1">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-logo-wrapper mr-3 d-inline-block">
				<?php
				$logoURL = et_get_option('divi_logo');
				echo '<img src="'.$logoURL.'" width="80" height="56" loading="lazy" itemprop="image" class="img-fluid w-100 h-100" />';
				?>
			</a>
			<div class="mobile-overflowing-menu">
			<?php wp_nav_menu(
				array(
					'menu' => 'mobile-sliding-menu',
					'menu_class' => 'menu-sliding-menu d-flex d-lg-none',
					'container' => 'nav',
				),
			); ?>
			</div>
		</div>
	</div>
	<!-- MAIN HEADER -->
	<!-- <div id="main-header-wrapper" class="main-header-wrapper bg-primary px-g py-10 d-none d-lg-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center justify-content-start">
			<div class="logo-wrapper w-auto mr-3">
				<a href="<?php // echo esc_url( home_url( '/' ) ); ?>" class="d-inline-block w-100 h-100">
				<?php
				$logoURL = et_get_option('divi_logo');
				// echo '<img src="'.$logoURL.'" alt="Value Autos Durango" itemprop="image" width="107" height="75" loading="lazy" class="img-fluid w-100 h-100"/>';
				?>
				</a>
			</div>
			<?php // wp_nav_menu(
			// array(
				// 'menu' => 'menu-01',
				// 'menu_class' => 'menu-01 d-flex align-items-center justify-content-start',
				// 'container' => 'nav',
			// ),
			//); ?>
		</div>
		<?php // get_search_form(true, 'search'); ?>
	</div> -->
	<?php echo durango_motors_main_desktop_header(false); ?>
	<?php echo durango_motors_main_desktop_header(true); ?>
	<!-- Mobile Main Header -->
	<?php echo durango_motors_main_mobile_header(false); ?>
	<?php echo durango_motors_main_mobile_header(true); ?>
	<!-- Searchbox -->
	<div class="body-popup-overlay header-searchbox-overlay d-none"></div>
	<article class="header-searchbox position-fixed d-none">
		<div class="header-searchbox-header d-flex justify-content-end align-item-center position-relative mb-15">
			<h2 class="position-absolute text-center text-white font-weight-bold font-helvetica">What are you looking for?</h2>
			<span class="header-search-box-close cursor-pointer text-white rounded-circle-px d-flex align-items-center justify-content-center font-xxl">
				<i class="fa fa-times"></i>
			</span>
		</div>
		<div class="header-searchbox-body">
			<?php get_search_form(true); ?>
			<?php echo '<div class="mt-20">';
				echo do_shortcode('[mwtsa_display_latest_searches unit="month" count="5" only_with_results="false" wrapper_class="search-box-recent-searches mwtsa-latest-searches"]');
				echo '</div>'; ?>
			<div class="search-box-recent-searches mt-20">
				<ul>
					<li><a href="<?php echo site_url(); ?>/inventory/?inventory_search[]=ford">Ford</a></li>
					<li><a href="<?php echo site_url(); ?>/inventory/?inventory_search[]=toyota%20tacoma">Toyota Tacoma</a></li>
					<li><a href="<?php echo site_url(); ?>/inventory/?inventory_search[]=ford%20bronco">Ford Bronco</a></li>
					<li><a href="<?php echo site_url(); ?>/inventory/?inventory_search[]=kia%20sportage">Kia Sportage</a></li>
					<li><a href="<?php echo site_url(); ?>/inventory?inventory_search[]=toyota%20corolla">Toyota Corolla</a></li>
				</ul>
			</div>
			<div class="search-box-recent-searches mt-20">
				<ul>
					<li><a href="https://www.durangomotorcompany.com" target="_blank">Explore DMC</a></li>
					<li><a href="https://sites.hireology.com/durangomotorcompany/jobs.html" target="_blank">Careers</a></li>
					<li><a href="<?php echo site_url(); ?>/about-us">About US</a></li><li><a href="<?php echo site_url(); ?>/specials/managers-specials/">Managers Specials</a></li>
					<li><a href="<?php echo site_url(); ?>/about-us/contact-us/">Contact Us</a></li>
				</ul>
			</div>
		</div>
	</article>
	<!-- Mobile Toggler Dropdown -->
	<div class="toggler-dropdown-wrapper position-fixed w-100 vh-100 d-none d-lg-none overflow-auto">
		<!-- Quick Routes -->
		<div class="quick-routes pt-2 pb-30 bg-white position-sticky quick-routes-links-hidden d-none" style="top:0;z-index:2;">
			<div class="quick-routes-links px-20">
				<?php 
					$quickRoutesArr = array(
						array(
							'icon' => 'quick-route-history-icon',
							'text' => 'Recent Viewed',
							'link' => 'recently-viewed/',
						),
						array(
							'icon' => 'quick-route-heart-icon',
							'text' => 'Liked',
							'link' => 'liked-vehicles/',
						),
						array(
							'icon' => 'quick-route-wheel-icon',
							'text' => 'vehicles for you',
							'link' => 'vehicles-for-you/',
						),
						array(
							'icon' => 'quick-route-trend-up-icon',
							'text' => 'Top Searches',
							'link' => 'top-searches/',
						),
						array(
							'icon' => 'quick-route-compare-icon',
							'text' => 'Compare',
							'link' => 'compare-vehicles/',
						),
						array(
							'icon' => 'quick-route-research-icon',
							'text' => 'Research',
							'link' => 'beyond-value-research/',
						),
					);
					foreach( $quickRoutesArr as $quickRoute ) {
						$icon = $quickRoute['icon'];
						$text = $quickRoute['text'];
						$link = $quickRoute['link'];
						echo '<div class="quick-route-link">'.
							 '<a href="'.site_url().'/'.$link.'" class="quick-route-icon d-flex align-items-center justify-content-center">'.
							 '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/'.$icon.'.svg" alt="'.$text.'" loading="lazy" />'.
							 '</a>'.
							 '<a href="'.site_url().'/'.$link.'" class="font-sm quick-route-anchor text-capitalize d-block text-center">'.$text.'</a>'.
							 '</div>';
					}
				?>
			</div>
			<span class="text-danger text-center font-segoe text-capitalize d-block font-weight-bold">Quick Routes</span>
			<div class="view-quick-routes rounded-circle-px position-absolute d-flex align-items-center justify-content-center">
				<i class="fa-solid fa-play"></i>
			</div>
		</div>
		<!-- Pages Cards
		===============================================-->
		<div class="header-pages-cards px-15 mt-5 mb-5">
			<?php 
				$cardsArr = array(
					array(
						'image' => 'mh-view-inventory-image',
						'text' => 'view all inventory',
						'link' => 'inventory',
					),
					array(
						'image' => 'mh-cars-image',
						'text' => 'cars',
						'link' => 'inventory/?type_of_vehicle%5b%5d=coupe&type_of_vehicle%5b%5d=sedan',
					),
					array(
						'image' => 'mh-suv-image',
						'text' => 'suvs & crossovers',
						'link' => 'inventory/?type_of_vehicle%5b%5d=suv&type_of_vehicle%5b%5d=hatchback',
					),
					array(
						'image' => 'mh-trucks-image',
						'text' => 'trucks',
						'link' => 'inventory/?type_of_vehicle%5b%5d=truck',
					),
					array(
						'image' => 'mh-vans-image',
						'text' => 'vans',
						'link' => 'inventory/?type_of_vehicle%5b%5d=cargo%20van',
					),
					array(
						'image' => 'mh-manager-specials-image',
						'text' => 'manager specials',
						'link' => 'specials/managers-specials/',
					),
					array(
						'image' => 'mh-service-and-parts-image',
						'text' => 'service & parts',
						'link' => 'javascript:void(0)',
					),
					array(
						'image' => 'mh-autogear-accessories-image',
						'text' => 'DGO Autogear Accessories',
						'link' => 'service-and-parts/accessories/',
					),
					array(
						'image' => 'mh-certified-pre-owned-image',
						'text' => 'certified pre-owned vehicles',
						'link' => '#',
					),
					array(
						'image' => 'mh-dgo-detail-center',
						'text' => 'DGO Detail Center',
						'link' => 'service-and-parts/detail-department/',
					),
					array(
						'image' => 'mh-product-protection',
						'text' => 'product protection',
						'link' => 'javascript:void(0)',
					),
					array(
						'image' => 'mh-mobile-service-image',
						'text' => 'Mobile Service',
						'link' => 'javascript:void(0)',
					),
					array(
						'image' => 'mh-we-will-buy-your-vehicle-image',
						'text' => 'we will buy your vehicle',
						'link' => 'https://www.kbb.com/instant-cash-offer/W/70317903/43A6F9B8-DB6C-48C0-A360-F658B2176E3E/',
					),
					array(
						'image' => 'mh-careers-image',
						'text' => 'careers',
						'link' => 'service-and-parts/careers/',
					),
					array(
						'image' => 'mh-reviews-image',
						'text' => 'review us',
						'link' => 'about-us/reviews-us-and-testimonials/',
					),
					array(
						'image' => 'mh-online-credit-approval-image',
						'text' => 'online credit approval',
						'link' => 'finance/car-loans-in-durango-co/',
					),
					array(
						'image' => 'mh-hours-directions-image',
						'text' => 'Hours & Direction',
						'link' => 'javascript:void(0)',
					),
					array(
						'image' => 'mh-valueautoslogo-image',
						'text' => 'About us',
						'link' => 'about-us/',
					),
					array(
						'image' => 'mh-blog-image',
						'text' => 'blog',
						'link' => 'blog/',
					),
					array(
						'image' => 'mh-dmc-image',
						'text' => 'Durango Motor Company',
						'link' => 'https://durangomotorcompany.com/',
					),
				);
			$exceptions = [
				'durango motor company',
				'we will buy your vehicle',
				'service & parts',
				'hours & direction',
				'product protection'
			];
			foreach( $cardsArr as $card ) {
				$image = $card['image'];
				$text = strtolower($card['text']);
				$link = $card['link'];
				if (!in_array($text, $exceptions)) {
					$link = site_url() . '/' . $link;
				}
					
					
			echo '<div class="bg-white header-page-card position-relative py-20 '.($text === 'service & parts' || $text === 'product protection' || $text === 'hours & direction' ? 'hidden-card-toggler' : '').' " '.($text === 'service & parts' ? 'data-target="servicePartsWrapper"' : '').' '.($text === 'product protection' ? 'data-target="productProtectionWrapper"' : '').' '.($text === 'hours & direction' ? 'data-target="hoursDirectionWrapper"' : '').'>'.
					'<a href="'.$link.'" class="text-capitalize page-card-title font-lg font-inter pl-15 d-inline-block w-100" '.(strtolower($text) === 'durango motor company' || strtolower($text) === 'we will buy your vehicle' ? 'target="_blank"' : '').'>'.$text.'</a>'.
					'<a class="page-card-img-wrapper position-absolute h-auto w-100 d-flex justify-content-center align-items-center" href="'.$link.'" '.(strtolower($text) === 'durango motor company' || strtolower($text) === 'we will buy your vehicle' ? 'target="_blank"' : '').'>'.
					'<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/'.$image.'.webp" alt="'.$text.'" loading="lazy" class="img-fluid h-100" />'.
					'</a>'.
					'</div>';
						
					if( strtolower($text) === 'dgo autogear accessories' ) {
						echo '<div class="page-spaned-card bg-white py-20 px-4 d-none" data-parent="servicePartsWrapper">'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/service-and-parts/schedule-express-service-durango-co/">Schedule Service</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/service-and-parts/car-service-durango-co/">Our Service</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/service-and-parts/auto-parts-durango-co/">Order Parts</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/service-and-parts/accessories/">DGO Autogear Accessories</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/service-and-parts/detail-department/">DGO Detail Center</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block mobile-iframe-trigger" data-toggle="modal" data-target="#mobile_iframe_popup" data-backdrop="true" href="https://www.durangomotorcompany.com/ford-pick-up-and-delivery">Service Pickup & Delivery</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block mobile-iframe-trigger" data-toggle="modal" data-target="#mobile_iframe_popup" data-backdrop="true" href="https://www.durangomotorcompany.com/clp-mobile-vehicle-service-at-durango-motor-company">Mobile Service</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/abra-auto-body-repair-durango/">ABRA Autobody</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/abra-auto-glass-durango/">ABRA Glass</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/abra-auto-glass-durango/">ABRA Glass</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/service-and-parts/sunbit-buy-now-pay-later/">Sunbit, (Buy Now, Pay Later)</a>'.
						'</div>';
					}
					if( strtolower($text) === 'mobile service' ) {
						echo '<div class="page-spaned-card bg-white py-20 px-4 d-none" data-parent="productProtectionWrapper">'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="https://www.durangomotorcompany.com/car-loans-in-durango-co" target="_blank">Online Credit Approval</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/value-your-trade/">Value Your Trade</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/aul-certified-warranty/">AUL Pre-Owned Vehicle Coverage</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/tri-pac-maintenance/">Tri-Pac Maintenance</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/vsc-service-contract/" target="_blank">VSC - Service Contract</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/gap/" target="_blank">GAP</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/tire-and-wheel/" target="_blank">Tire And Wheel</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/tire-and-wheel-bundle/" target="_blank">Tire And Wheel Bundle</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="'.site_url().'/finance/cilajet-brochure/" target="_blank">Cilajet Brochure</a>'.
						'<a class="text-eight mb-3 font-inter font-md d-block" href="https://www.permaplate.com/" target="_blank">Perma Plate</a>'.
						'</div>';
					}
					if (strtolower($text) === 'about us') {
						$storeHours = '<div class="page-spaned-card bg-white py-20 px-4 d-none" data-parent="hoursDirectionWrapper">' .
						'<div class="accordion hours-accordions-wrapper" id="salehoursAccordion">' .
						'<div class="card border-0">' .
						'<div class="card-header pb-2 border-bottom border-dark p-0 d-flex align-items-center justify-content-between bg-transparent rounded-0" id="salehoursHeading" data-toggle="collapse" data-target="#salehoursCollapse" aria-expanded="true" aria-controls="salehoursCollapse">' .
						'<p class="text-eight font-lg">' .
						'Sale Hours'.
						'</p>' .
						'<i class="fa-solid fa-chevron-down text-eight font-lg"></i>'.
						'</div>' .
						'<div id="salehoursCollapse" class="collapse" aria-labelledby="salehoursHeading" data-parent="#salehoursAccordion">' .
						'<div class="card-body p-0 pt-3 pb-3">' ;
						$storeSaleHours = get_field('store_sale_hours', 'options');
						foreach( $storeSaleHours as $hours ) {
							$day = $hours['store_sale_hours_day_name'];
							$time = $hours['store_sale_hours_time'];
							$storeHours .= '<div class="d-flex align-items-center justify-content-between mb-20">'.
							'<span class="text-eight font-lg font-inter">'.$day.'</span>'.
							'<span class="text-eight font-lg font-inter">'.$time.'</span>'.
							'</div>';
						}
						$storeHours .= '</div>' .
						'</div>' .
						'</div>' .
						'</div>' .
						// Service Hours
						'<div class="accordion hours-accordions-wrapper" id="serviceHoursAccordion">' .
						'<div class="card border-0">' .
						'<div class="card-header pb-2 border-bottom border-dark p-0 d-flex align-items-center justify-content-between bg-transparent rounded-0" id="serviceHoursHeading" data-toggle="collapse" data-target="#servicehoursCollapse" aria-expanded="true" aria-controls="servicehoursCollapse">' .
						'<p class="text-eight font-lg font-md">' .
						'Service Hours'.
						'</p>' .
						'<i class="fa-solid fa-chevron-down text-eight font-lg"></i>'.
						'</div>' .
						'<div id="servicehoursCollapse" class="collapse" aria-labelledby="serviceHoursHeading" data-parent="#serviceHoursAccordion">' .
						'<div class="card-body p-0 pt-3 pb-3">' ;
						$storeServiceHours = get_field('store_services_hours', 'options');
						foreach( $storeServiceHours as $hours ) {
							$day = $hours['store_service_hours_day_name'];
							$time = $hours['store_service_hours_time'];
							$storeHours .= '<div class="d-flex align-items-center justify-content-between mb-20">'.
							'<span class="text-eight font-lg font-inter">'.$day.'</span>'.
							'<span class="text-eight font-lg font-inter">'.$time.'</span>'.
							'</div>';
						}
						$storeHours .= '</div>' .
						'</div>' .
						'</div>' .
						'</div>' .
						// Directions
						'<div class="accordion hours-accordions-wrapper" id="directionsAccordion">' .
						'<div class="card border-0">' .
						'<div class="card-header pb-2 border-bottom border-dark p-0 d-flex align-items-center justify-content-between bg-transparent rounded-0" id="directionsHeading" data-toggle="collapse" data-target="#directionsCollapse" aria-expanded="true" aria-controls="directionsCollapse">' .
						'<p class="text-eight font-lg font-md">' .
						'Directions'.
						'</p>' .
						'<i class="fa-solid fa-chevron-down text-eight font-lg"></i>'.
						'</div>' .
						'<div id="directionsCollapse" class="collapse" aria-labelledby="directionsHeading" data-parent="#directionsAccordion">' .
						'<div class="card-body p-0 pt-3 pb-3">'.
						'<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3176.861462363776!2d-107.86379198530084!3d37.22726355124376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x873c0332609fc183%3A0x2c671b0ad9d35215!2s1240%20Escalante%20Dr%2C%20Durango%2C%20CO%2081303%2C%20USA!5e0!3m2!1sen!2s!4v1658910684136!5m2!1sen!2s" class="w-100"></iframe>'.
						'</div>' .
						'</div>' .
						'</div>' .
						'</div>' .
						'</div>';

						echo $storeHours;
					}
				}
			?>
		</div>
	</div>
</header> <!-- #main-header -->

<?php
	function durango_motors_main_desktop_header($stickyHeader = false) {
		$header = '<div class="main-header-wrapper bg-primary px-g py-10 d-none d-lg-flex align-items-center justify-content-between '.($stickyHeader ? 'main-header-sticky' : 'main-header-non-sticky').'">'.
		'<div class="d-flex align-items-center justify-content-start">'.
		'<div class="logo-wrapper w-auto mr-3">'.
		'<a href="'.esc_url( home_url( '/' ) ).'" class="d-inline-block w-100 h-100">'.
		'<img src="'.et_get_option('divi_logo').'" alt="Value Autos Durango" itemprop="image" width="107" height="75" loading="lazy" class="img-fluid w-100 h-100"/>'.
		'</a>'.
		'</div>';
		ob_start();
		wp_nav_menu(
			array(
				'menu' => 'menu-01',
				'menu_class' => 'menu-01 menu-menu-01 d-flex align-items-center justify-content-start',
				'container' => 'nav',
			),
			);
		$header .= ob_get_clean();
		$header .= '</div>';
		ob_start();
		get_search_form(true, 'search');
		$header .= ob_get_clean();
		$header .= '</div>';

		return $header;
	}
	// Mobile Header Function
	function durango_motors_main_mobile_header($stickyHeader = false) {
		$storeSaleHour = get_field('store_sale_hours', 'options');
		$tooltip = '<div class="hours-tooltip-container store-hours-wrapper">';
		foreach($storeSaleHour as $index => $hours) {
		$day = $hours['store_sale_hours_day_name'];
		$time = $hours['store_sale_hours_time'];
		$tooltip .= '<div class="d-flex align-items-center justify-content-between px-3 py-10 store-hours-block store-hours-'.($index + 1).'">'.
		'<span class="text-eight font-lg font-inter mr-2">'.$day.'</span>'.
		'<span class="text-eight font-lg font-inter">'.$time.'</span>'.
		'</div>';
		}
		$tooltip .= '</div>';
		$header = '<div id="mobile-main-header-wrapper" class="mobile-main-header-wrapper d-flex align-items-center justify-content-between d-lg-none px-g py-4 '.($stickyHeader ? 'mobile-main-header-sticky' : 'mobile-main-header-non-sticky').'">
		<div class="mobile-header-icons d-flex align-items-center justify-content-between">
		<a href="https://goo.gl/maps/MaaUbcsuXnyGwMeC9" target="_blank" class="header-icon-box flex-column align-items-center">
		<i class="fa-solid fa-location-dot text-seventh"></i>
		<span class="font-xs text-seventh text-capitalize mt-1 d-inline-block">Location</span>
		</a>
		<a href="javascript:void(0)" tabindex="0" class="header-icon-box flex-column align-items-center header-icon-box-clock" data-html="true" role="button" data-toggle="popover" data-placement="bottom" data-content="'.htmlentities($tooltip).'">
		<i class="fa-regular fa-clock text-seventh"></i>
		<span class="font-xs text-seventh text-capitalize mt-1 d-inline-block">Hours</span>
		</a>
		<a href="tel:'.salesPhoneNumber().'" class="header-icon-box flex-column align-items-center header-icon-box-phone">
		<i class="fa-solid fa-phone text-seventh"></i>
		<span class="font-xs text-seventh text-capitalize mt-1 d-inline-block">Phone</span>
		</a>
		</div>
		<div class="mobile-header-search flex-grow-1 position-relative">
		<input type="search" class="p-2 rounded-10 bg-transparent border border-light font-lg text-white w-100 transition top-searches-search-bar secondary-top-searchbar no-zoom" placeholder="Search" title="Search">
		<i class="fa fa-search position-absolute"></i>
		</div>
		<div class="mobile-header-toggler">
		<div class="d-flex flex-column justify-content-center align-items-center">
		<div class="mobile-toggler-icon">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		</div>
		<span class="text-seventh font-xs text-capitalize mt-1 d-inline-block" style="line-height:1;">Menu</span>
		</div>
		</div>
		</div>';

		return $header;
	}