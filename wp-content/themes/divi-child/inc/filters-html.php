<?php

/**
 * Displays filter HTML in sidebar panel
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cards Pre Loader
 */
function cardLoader($num_items) {
    $html = '';
    for ($i = 0; $i < $num_items; $i++) {
        $loader = '<div class="col-12 col-md-6 col-lg-4">'.
        '<div class="loader-card bg-grey-7 p-20 overflow-hidden position-relative">'.
        '<div class="loader-card-inner"></div>'.
        '</div>'.
        '</div>';

        $html .= $loader;
    }
    return $html;
}

// Checkbox Options
function checkboxOptionLoader($title, $class) {
    $filter = '<div class="inventory-filterbar__year-search inventory-filterbar__border-bottom px-2">'.
               '<div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">'.
               '<h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0 text-green">'.$title.'</h2>'.
               '<div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus text-green"></i></div>'.
               '</div>'.
               '<div class="'.$class.' expanding-section"></div>'.
               '</div>';
    return $filter;
}

/** Dropdown Options */
function dmc_dropdown_filter_options( $title, $class ) {
	$filter = '<div class="inventory-filterbar__year-search inventory-filterbar__border-bottom px-2">'.
		'<div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">'.
		'<h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0 text-green">'
		. esc_html( $title ) .
		'</h2>'.
		'<div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus text-green"></i></div>'.
		'</div>'.
		'<div class="'. esc_attr( $class ) .' expanding-section"></div>'.
		'</div>';
	
	return $filter;
}

function rangeFilters($title, $class) {
    $html = '<div class="inventory-filterbar__year-search inventory-filterbar__border-bottom px-2">'.
            '<div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">'.
            '<h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0 text-green">'.$title.'</h2>'.
            '<div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus text-green"></i></div>'.
            '</div>'.
            '<div class="expanding-section position-relative">'.
            '<div class="d-flex align-items-center justify-content-between bg-third mb-4 px-3" style="background:#707070;">'.
            '<form class="inventory-filterbar__'.$class.' position-relative w-100 pb-3"></form>'.
		'<p class="'.$class.'-min-text font-weight-bold text-white font-segoe p-0">0</p>'.
            '<p class="'.$class.'-max-text font-weight-bold text-white font-segoe p-0">0</p></div>'.
            '</div>
			</div>';
    return $html;
}

// Sort Options
function sortOptions($class) {
    $sortBy = '<select name="sorting-by" data-type="sort-by" class="dropdown-filters '.$class.' inventory-products-bar__sort-filter-wrapper cursor-pointer position-relative text-capitalize inventory-filterbar__border-bottom w-100">'.
                '<option disabled selected value>Select an option</option>'.
                '<option value="low-to-high">Price (lowest to highest)</option>'.
                '<option value="high-to-low">Price (highest to lowest)</option>'.
                '<option value="mileage-lowest">Mileage - Lowest</option>'.
                '<option value="mileage-highest">Mileage - Highest</option>'.
                '<option value="year-lowest">Year - Lowest</option>'.
                '<option value="year-highest">Year - Highest</option>'.
                '<option value="listings-a-z">Make/Model - A to Z</option>'.
                '<option value="listings-z-a">Make/Model - Z to A</option>'.
                '<option value="listing-date-new">Date Listed - Newest</option>'.
                '<option value="listing-date-old">Date Listed - Oldest</option>'.
                '<option value="listing-new-to-old">Newest to Oldest</option>'.
             '</select>';
    return $sortBy;
}
// Searchbar Filters
function createSearchBar($class, $displayClasses = '', $iconClasses = '') {
    $searchBar = '<div class="inventory-filterbar__searchbar-wrapper '.$displayClasses.'">'.
                 '<form onsubmit="return false" class="inventory-filterbar__searchbar inventory-filterbar__border-bottom position-relative '. (!empty($displayClasses) ? "w-100" : "") .'">'.
                 '<input type="search" name="inventory_search" id="inventory-search-bar" data-type="search" class="inventory-search-filters search-filters '.$class.'" placeholder="Search" />'.
                 '<i class="fa-solid fa-magnifying-glass position_absolute search-filter-icon cursor-pointer '.$iconClasses.'"></i>'.
                 '</form></div>';
    return $searchBar;
}
// Vehicles Count
function vehiclesCount($device) {
    $counter = '<div class="inventory-products-bar__meta-info w-100 '.( $device == "mobile" ? "d-flex d-md-none" : null ).' ">'.
                '<p class="inventory-products-bar__meta-info-text font-weight-bold text-grey-3  '.( $device == "mobile" ? "font-md" : "font-lg" ).' "></p>'.
                '</div>';
                return $counter;
}

// External Banners
function externalBanners($device) {
    $kbbBanner = wp_get_attachment_image_src(get_field('inventory_kbb_banner', 'options'), 'full');
    $kbbBannerImage = $kbbBanner[0];
    $kbbBannerWidth = $kbbBanner[1];
    $kbbBannerHeight = $kbbBanner[2];
    $kbbBannerAlt = get_post_meta($kbbBanner, '_wp_attachment_image_alt', true);
    $insurancePollyBanner = wp_get_attachment_image_src(get_field('inventory_insurance_polly_banner', 'options'), 'full');
    $insurancePollyBannerImage = $insurancePollyBanner[0];
    $insurancePollyBannerWidth = $insurancePollyBanner[1];
    $insurancePollyBannerHeight = $insurancePollyBanner[2];
    $insurancePollyBannerAlt = get_post_meta($insurancePollyBanner, '_wp_attachment_image_alt', true);

    $banners = '<div class="mt-20 mb-20 inventory-filterbar-banners inventory-filterbar-kbb '.( $device == "mobile" ? "d-block d-lg-none" : "d-none d-lg-block" ).'">'.
               '<a class="d_inline-block w-100" href="https://www.kbb.com/instant-cash-offer/W/70317903/43A6F9B8-DB6C-48C0-A360-F658B2176E3E" target="_blank">'.
               '<img src="'. $kbbBannerImage .'" alt="'.$kbbBannerAlt.'" width="'.$kbbBannerWidth.'" height="'.$kbbBannerHeight.'" title="'.$kbbBannerAlt.'" loading="lazy" itemprop="image" class="w-100 img-fluid">'.
               '</a></div>'.
               '<div class="inventory-filterbar-banners inventory-filterbar-get-quote '.( $device == "mobile" ? "d-block d-lg-none" : "d-none d-lg-block" ).'">'.
                '<a class="d_inline-block w-100" target="_blank" href="https://insurance.polly.co/?dealershipId=F8E92137-96A1-B377-4517-9A6322F35AD0&campaignId=DPIAwebsite&__hstc=7873965.d260c032869087a80468f6c80173ad9b.1673564858571.1673564858571.1673564858571.1&__hssc=7873965.1.1673564858572&__hsfp=275471683&hsCtaTracking=64f86256-f92a-4caa-8498-4ae83f1ab63c%7C592199c5-215a-45ab-ac30-680c7a210694">'.
                '<img src="'.$insurancePollyBannerImage.'" alt="'.$insurancePollyBannerAlt.'" width="'.$insurancePollyBannerWidth.'" height="'.$insurancePollyBannerHeight.'" title="'.$insurancePollyBannerAlt.'" loading="lazy" itemprop="image" class="w-100 h-100">'. 
                '</a></div>';
    return $banners;
}