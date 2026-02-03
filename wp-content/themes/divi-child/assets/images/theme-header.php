<?php
/**
 * Template partial used to add content to the page in Theme Builder.
 * Duplicates partial content from header.php in order to maintain
 * backwards compatibility with child themes.
 */
?>

<header id="header-wrapper">
	<!-- TOP HEADER -->
	<div id="top-header-wrapper" class="px-g py-10 top-header d-flex align-items-center justify-content-start justify-content-lg-between">
		<?php wp_nav_menu(
			array(
				'menu' => 'menu-header-top',
				'menu_class' => 'menu-header-top-wrapper d-none d-lg-flex',
				'container' => 'nav',
			),
		); ?>
		<div class="align-items-center d-none d-lg-flex">
			<p class="contact-block p-0 mr-30 d-flex align-items-center">
				<i class="fa-solid fa-phone text-seventh mr-2"></i>
				<a href="tel:<?php echo str_replace(array('+',' ', '(', ')', '-'), '', get_field('quick_call_phone_number', 'options')) ?>" class="text-white">
					<?php echo get_field('quick_call_phone_number', 'options'); ?>
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
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-logo-wrapper mr-3">
				<?php
				$logoURL = et_get_option('divi_logo');
				echo '<img src="'.$logoURL.'" />';
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
	<div id="main-header-wrapper" class="main-header-wrapper bg-primary px-g py-10 d-none d-lg-flex align-items-center justify-content-between">
		<div class="d-flex align-items-center justify-content-start">
			<div class="logo-wrapper">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				$logoURL = et_get_option('divi_logo');
				echo '<img src="'.$logoURL.'" />';
				?>
				</a>
			</div>
			<?php wp_nav_menu(
			array(
				'menu' => 'menu-01',
				'menu_class' => 'menu-01 d-flex align-items-center justify-content-start',
				'container' => 'nav',
			),
			); ?>
		</div>
		<?php get_search_form(true, 'search'); ?>
	</div>
	<!-- Mobile Main Header -->
	<div id="mobile-main-header-wrapper" class="mobile-main-header-wrapper d-flex align-items-center justify-content-between d-lg-none px-g py-4">
		<div class="mobile-header-icons d-flex align-items-center justify-content-between">
			<a href="https://goo.gl/maps/MaaUbcsuXnyGwMeC9" target="_blank" class="header-icon-box d-flex flex-column align-items-center">
				<i class="fa-solid fa-location-dot text-seventh"></i>
				<span class="font-xs text-seventh text-capitalize mt-1 d-inline-block">Location</span>
			</a>
			<a href="#" class="header-icon-box d-flex flex-column align-items-center">
				<i class="fa-regular fa-clock text-seventh"></i>
				<span class="font-xs text-seventh text-capitalize mt-1 d-inline-block">Clock</span>
			</a>
			<a href="#" class="header-icon-box d-flex flex-column align-items-center">
				<i class="fa-solid fa-phone text-seventh"></i>
				<span class="font-xs text-seventh text-capitalize mt-1 d-inline-block">Phone</span>
			</a>
		</div>
		<div class="mobile-header-search flex-grow-1">
			<?php get_search_form(true, 'search'); ?>
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
	</div>
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
					<li><a href="<?php echo site_url(); ?>/about-us">About US</a></li><li><a href="/managers-specials">Managers Specials</a></li>
					<li><a href="<?php echo site_url(); ?>/contact-us">Contact Us</a></li>
				</ul>
			</div>
		</div>
	</article>
	<!-- Mobile Toggler Dropdown -->
	<div class="toggler-dropdown-wrapper position-fixed w-100 vh-100 d-lg-none">
		<!-- Quick Routes -->
		<div class="quick-routes pt-2 pb-30 bg-white position-relative">
			<div class="quick-routes-links">
				<?php 
					$quickRoutesArr = array(
						array(
							'icon' => 'quick-route-history-icon',
							'text' => 'Recent Viewed',
						),
						array(
							'icon' => 'quick-route-heart-icon',
							'text' => 'Liked',
						),
						array(
							'icon' => 'quick-route-wheel-icon',
							'text' => 'vehicles for you',
						),
						array(
							'icon' => 'quick-route-trend-up-icon',
							'text' => 'Top Searches',
						),
						array(
							'icon' => 'quick-route-compare-icon',
							'text' => 'Compare',
						),
						array(
							'icon' => 'quick-route-research-icon',
							'text' => 'Research',
						),
					);
					foreach( $quickRoutesArr as $quickRoute ) {
						$icon = $quickRoute['icon'];
						$text = $quickRoute['text'];
						echo '<div class="quick-route-link">'.
							 '<img src="'.site_url().'/wp-content/themes/divi-child/assets/images/'.$icon.'.svg" alt="'.$text.'" loading="lazy" />'.
							 '<span>'.$text.'</span>'.
							'</div>';
					}
				?>
			</div>
			<span class="text-danger text-center font-segoe text-capitalize d-block font-weight-bold">Quick Routes</span>
			<div class="view-quick-routes rounded-circle-px position-absolute d-flex align-items-center justify-content-center">
				<i class="fa-solid fa-play"></i>
			</div>
		</div>
		<!-- Pages Cards -->
	</div>
</header> <!-- #main-header -->
