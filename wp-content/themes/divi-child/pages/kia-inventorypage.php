<?php /* Template Name: Kia Inventorypage Template */ ?>
<?php
global $post;

// Redirect to clean URL - Kia page has no search/query params
if (!empty($_GET)) {
    wp_redirect(home_url('/kia/'), 302);
    exit;
}

$type = isset($_GET['type_of_vehicle']) ? strtolower($_GET['type_of_vehicle'][0]) : 'default';
$stickyFilterbar = get_field('enable_filterbar_sticky_effect', 'options');

// Same as used inventorypage
function checkboxOptionLoader($title, $class) {
    $filter = '<div class="inventory-filterbar__year-search inventory-filterbar__border-bottom">'.
               '<div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">'.
               '<h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">'.$title.'</h2>'.
               '<div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>'.
               '</div>'.
               '<div class="'.$class.' expanding-section"></div>'.
               '</div>';
    return $filter;
}
function rangeFilters($title, $class) {
    $html = '<div class="inventory-filterbar__year-search inventory-filterbar__border-bottom">'.
            '<div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">'.
            '<h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">'.$title.'</h2>'.
            '<div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>'.
            '</div>'.
            '<div class="expanding-section position-relative">'.
            '<div class="d-flex align-items-center justify-content-between bg-third mb-4 px-3" style="background:#707070;">'.
            '<p class="'.$class.'-min-text font-weight-bold text-white font-segoe p-0">0</p>'.
            '<p class="'.$class.'-max-text font-weight-bold text-white font-segoe p-0">0</p></div>'.
            '<form class="inventory-filterbar__'.$class.' fake-'. ($class === 'price' ? 'price' : 'mileage') .' position-relative w-100 pb-3"></form>'.
            '</div></div>';
    return $html;
}

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
function createSearchBar($class, $displayClasses = '', $iconClasses = '') {
    $searchBar = '<div class="inventory-filterbar__searchbar-wrapper '.$displayClasses.'">'.
                 '<form onsubmit="return false" class="inventory-filterbar__searchbar inventory-filterbar__border-bottom position-relative '. (!empty($displayClasses) ? "w-100" : "") .'">'.
                 '<input type="search" name="inventory_search" id="inventory-search-bar" data-type="search" class="inventory-search-filters search-filters '.$class.'" placeholder="Search" />'.
                 '<i class="fa-solid fa-magnifying-glass position_absolute search-filter-icon cursor-pointer '.$iconClasses.'"></i>'.
                 '</form></div>';
    return $searchBar;
}
function vehiclesCount($device) {
    $counter = '<div class="inventory-products-bar__meta-info w-100 '.( $device == "mobile" ? "d-flex d-md-none" : null ).' ">'.
                '<p class="inventory-products-bar__meta-info-text font-weight-bold text-grey-3  '.( $device == "mobile" ? "font-md" : "font-lg" ).' "></p>'.
                '</div>';
                return $counter;
}

function dequeue_inventory_scripts() {
    $scripts_to_deregister = array(
        'automotive-listing-generate-pdf',
        'automotive-listing-financing-calculator',
        'listing_js',
        'listing_cookie',
        'tether',
        'bxslider',
        'parallax',
        'alphanum',
    );
    foreach ( $scripts_to_deregister as $handle ) {
        wp_deregister_script( $handle );
        wp_dequeue_script( $handle );
    }
}
add_action( 'wp_enqueue_scripts', 'dequeue_inventory_scripts', 9999 );

require_once get_stylesheet_directory() . '/sidebarForm.php';
require_once get_stylesheet_directory() . '/custom-templates/stickersPopup.php';

get_header();
?>

<div id="main-content">
<?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php 
            $bannerGroups = array(
                'default' => array(
                    'field' => 'pre_owned_vehicles_banner_group',
                    'bigScreen' => 'pre_owned_vehicles_big_screen_laptops_banner',
                    'smallScreen' => 'pre_owned_vehicles_small_screen_laptops_banner',
                    'heading' => 'pre_owned_vehicles_banner_heading',
                    'content' => 'pre_owned_vehicles_banner_content',
                    'link' => 'pre_owned_vehicle_banner_link'
                ),
                'coupe' => array(
                    'field' => 'pre_owned_cars_banner_group',
                    'bigScreen' => 'pre_owned_cars_big_screen_laptops_banner',
                    'smallScreen' => 'pre_owned_cars_small_screen_laptops_banner',
                    'heading' => 'pre_owned_cars_banner_heading',
                    'content' => 'pre_owned_cars_banner_content'
                ),
                'suv' => array(
                    'field' => 'pre_owned_suvs_banner_group',
                    'bigScreen' => 'pre_owned_suvs_big_screen_laptops_banner',
                    'smallScreen' => 'pre_owned_suvs_small_screen_laptops_banner',
                    'heading' => 'pre_owned_suvs_banner_heading',
                    'content' => 'pre_owned_suvs_banner_content'
                ),
                'truck' => array(
                    'field' => 'pre_owned_trucks_banner_group',
                    'bigScreen' => 'pre_owned_trucks_big_screen_laptops_banner',
                    'smallScreen' => 'pre_owned_trucks_small_screen_laptops_banner',
                    'heading' => 'pre_owned_trucks_banner_heading',
                    'content' => 'pre_owned_trucks_banner_content'
                ),
                'wagon' => array(
                    'field' => 'pre_owned_vans_banner_group',
                    'bigScreen' => 'pre_owned_vans_big_screen_laptops_banner',
                    'smallScreen' => 'pre_owned_vans_small_screen_laptops_banner',
                    'heading' => 'pre_owned_vans_banner_heading',
                    'content' => 'pre_owned_vans_banner_content'
                )
            );

            $heroThumbnail = '';
            $heroThumbnailAlt = '';
            $heroBannerHeading = '';
            $heroBannerContent = '';
            $isRepeater = false;

            foreach ($bannerGroups as $key => $bannerGroup) {
                if ($type == $key && $bannerGroupValue = get_field($bannerGroup['field'], 'options')) {
                    if (have_rows($bannerGroup['field'], 'options')) {
                        $isRepeater = true;
                        echo '<div class="slick-slider inventory-banner-slider">';
                        while (have_rows($bannerGroup['field'], 'options')) : the_row();
                            $bigScreenImg = wp_get_attachment_image_src(get_sub_field($bannerGroup['bigScreen']), 'full');
                            $smallScreenImg = wp_get_attachment_image_src(get_sub_field($bannerGroup['smallScreen']), 'full')[0];
                            $link = get_sub_field($bannerGroup['link']);
                            $heroThumbnail = $bigScreenImg[0];
                            $heroThumbnailAlt = get_post_meta(get_sub_field($bannerGroup['bigScreen']), '_wp_attachment_image_alt', true);
                            echo '<div>';
                            echo '<a href="' . esc_url($link) . '" class="inventory-hero-slide-wrapper">';
                            echo '<picture>';
                            if (!empty($smallScreenImg)) {
                                echo '<source media="(max-width: 767px)" srcset="' . esc_url($smallScreenImg) . '">';
                            }
                            echo '<img src="' . esc_url($heroThumbnail) . '" alt="' . esc_attr($heroThumbnailAlt) . '" class="img-fluid w-100 h-100 object_fit_cover">';
                            echo '</picture>';
                            echo '</a>';
                            echo '</div>';
                        endwhile;
                        echo '</div>';
                    } else {
                        $heroGroup = wp_get_attachment_image_src($bannerGroupValue[$bannerGroup['bigScreen']], 'full');
                        $heroThumbnailSmall = wp_get_attachment_image_src($bannerGroupValue[$bannerGroup['smallScreen']], 'full')[0];
                        $heroThumbnail = $heroGroup[0];
                        $heroThumbnailAlt = get_post_meta($bannerGroupValue[$bannerGroup['bigScreen']], '_wp_attachment_image_alt', true);
                    }
                    if ($bannerGroupValue[$bannerGroup['heading']] && !empty($bannerGroupValue[$bannerGroup['heading']])) {
                        $heroBannerHeading = $bannerGroupValue[$bannerGroup['heading']];
                    }
                    if ($bannerGroupValue[$bannerGroup['content']] && !empty($bannerGroupValue[$bannerGroup['content']])) {
                        $heroBannerContent = $bannerGroupValue[$bannerGroup['content']];
                    }
                    break;
                }
            }
            if (!$isRepeater) {
                echo divi_child_page_banner($heroThumbnail, $heroThumbnailAlt, '', '', $heroBannerHeading, $heroBannerContent, $heroThumbnailSmall ?? '');
            }
?>
            <div class="entry-content mt-lg-4 pt-lg-5 pb-3 pb-md-4 pb-lg-5" style="z-index:1;position:relative;">
            <div class="px-g">
                <div class="inventory-content-wrapper row">
                    <div class="col-12 col-md-5 col-lg-3">
                    <div class="<?php echo (!empty($stickyFilterbar) ? "filterbar-sticky" : "position-relative"); ?>" style="z-index:111;">
                        <div class="inventory-filterbar">
                            <?php echo vehiclesCount('mobile'); ?>
                            <div class="mt-20 mb-20 d-flex d-md-none justify-content-between flex-column align-items-start">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div class="inventory-filterbar-toggle-wrapper inventory-filterbar-mobile-toggle d-flex align-items-center justify-content-start">
                                        <div class="inventory-filterbar__img-wrapper d-flex align-items-center">
                                            <img src="<?php echo site_url();?>/wp-content/themes/divi-child/assets/images/inventory-filter-mobile.webp" alt="inventory filter icon" />
                                        </div>
                                        <strong class="inventory-filterbar-toggle-text ml-2 text-capitalize text-sixth font-helvetica">filters/sort</strong>
                                    </div>
                                    <div class="inventory-products-bar__clear-selected-filters">
                                        <button class="inventory-products-bar__clear-selected-filters-button font-weight-normal">clear all</button>
                                    </div>
                                </div>
                            </div>
                            <div class="inventory-filterbar__title inventory-filterbar__border-bottom d-none d-md-flex justify-content-between align-items-center pb-2">
                                <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Filter</h2>
                                <div class="inventory-filterbar__img-wrapper inventory-filterbar-mobile-toggle"><img src="<?php echo site_url();?>/wp-content/themes/divi-child/assets/images/inventory-filter-image.webp" alt="inventory filter icon" loading="lazy"/></div>
                            </div>
                            <div class="inventory-filters-main-wrapper inventory-filters-mobile-sidebar">
                                <div class="inventory-filters-mobilebar-heading d-md-none">
                                    <h2 class="inventory-filters-mobile-heading font-weight-bold text-primary font-xl font-helvetica pb-0 m-0">Filters</h2>
                                    <span class="inventory-filters-mobilebar-close inventory-filters-mobilebar-close-icon"><i class="fa fa-times"></i></span>
                                </div>
                                <div class="inventory-filterbar__year-search inventory-filterbar__border-bottom d-lg-none">
                                    <div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
                                        <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Sort</h2>
                                        <div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>
                                    </div>
                                    <form class="inventory-products-bar__sort-filter-form pl-0 sort expanding-section"><?php echo sortOptions('secondary-sort-filter'); ?></form>
                                </div>
								<div class="vehicle-condition-filter-wrap">
									<div class="vehicle-condition-filter-block">
										<label for="vehicle-condition-filter-new"><?php echo esc_html( 'New' ); ?></label>
										<input type="radio" class="vehicle-condition-filter" name="vehicle-condition-filter" id="vehicle-condition-filter-new" value="new" />
									</div>
									<div class="vehicle-condition-filter-block">
										<label for="vehicle-condition-filter-certified"><?php echo esc_html( 'Certified' ); ?></label>
										<input type="radio" class="vehicle-condition-filter" name="vehicle-condition-filter" id="vehicle-condition-filter-certified" value="certified" />
									</div>
									<div class="vehicle-condition-filter-block">
										<label for="vehicle-condition-filter-pre-owned"><?php echo esc_html( 'Pre-Owned' ); ?></label>
										<input type="radio" class="vehicle-condition-filter" name="vehicle-condition-filter" id="vehicle-condition-filter-pre-owned" value="pre-owned" checked />
									</div>
								</div>
                                <?php 
                                echo checkboxOptionLoader('Year', 'fake-year');
                                // Make filter hidden - always Kia
                                echo checkboxOptionLoader('Model', 'fake-model');
                                echo checkboxOptionLoader('Body Style', 'fake-body-style');
                                echo checkboxOptionLoader('Type of Vehicle', 'fake-type-of-vehicle');
                                echo checkboxOptionLoader('Doors', 'fake-doors'); 
                                echo rangeFilters('Mileage', 'mileage');
                                echo checkboxOptionLoader('Cylinders', 'fake-cylinders');
                                echo checkboxOptionLoader('Drivetrain', 'fake-drivetrain');
                                echo checkboxOptionLoader('Transmission', 'fake-transmission');?>
                                     <div class="inventory-filterbar__year-search inventory-filterbar__border-bottom">
                                        <div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
                                            <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Exterior Color</h2>
                                            <div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>
                                        </div>
                                        <div class="expanding-section">
                                            <form class="inventory-filterbar__exteriorColor-search-wrapper exteriorColor row fake-exterior-color w-100"></form>
                                        </div>
                                    </div>
                                     <div class="inventory-filterbar__year-search inventory-filterbar__border-bottom">
                                        <div class="inventory-filterbar__title d_flex d_flex__justify-between d_flex__align-center d_flex__wrap cursor-pointer">
                                            <h2 class="inventory-filterbar__heading font_bold p_0 font_helvetica m_0">Interior Color</h2>
                                            <div class="inventory-filterbar__img-wrapper"><i class="fa-solid fa-plus"></i></div>
                                        </div>
                                        <div class="expanding-section">
                                            <form class="inventory-filterbar__interior_color-search-wrapper interior_color row fake-interior-color w-100"></form>
                                        </div>
                                    </div>
                                    <?php
                                    echo rangeFilters('Price', 'price');
                                    echo checkboxOptionLoader('Certified', 'fake-certified');
                                    echo checkboxOptionLoader('Fuel Type', 'fake-fuel-type');
                                    ?>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-9">
                    <div class="inventory-content-wrapper__listings-wrapper">
    <div class="inventory-products-bar">
		<?php echo createSearchBar('main-search-filters w-100 position-relative', null, 'main-search-icon'); ?>
        <div class="d-none d-lg-flex justify-content-between align-items-center">
            <?php echo vehiclesCount('desktop'); ?>
            <div class="inventory-products-bar__meta-filters d_flex d_flex__justify-end w-100">
                <div class="inventory-products-bar__layout-changer d_flex d_flex__align-center">
                    <div class="inventory-products-bar__layout-grid active cursor-pointer d_grid d_flex__align-center">
                        <div class="inventory-products-bar__grid-col"></div>
                        <div class="inventory-products-bar__grid-col"></div>
                        <div class="inventory-products-bar__grid-col"></div>
                        <div class="inventory-products-bar__grid-col"></div>
                    </div>
                    <div class="inventory-products-bar__layout-list d_flex d_flex__align-center"><i class="fa-solid fa-bars cursor-pointer"></i></div>
                </div>
                <div class="inventory-products-bar__sort-filter d_flex">
                    <p class="inventory-products-bar__sort-filter-text font_bold pb_0 d_flex d_flex__align-center">Sort by: </p>
                    <form class="inventory-products-bar__sort-filter-form position-relative"><?php echo sortOptions('main-sort-filter'); ?></form>
                </div>
            </div>
        </div>
        <div class="inventory-products-bar__selected-filters-wrapper pt-3 d-none justify-content-start flex-wrap">
            <div class="inventory-products-bar__selected-filters d_flex__wrap d_flex d_flex__align-center"></div>     
            <div class="inventory-products-bar__clear-selected-filters d-none d-lg-inline-block">
                <button class="inventory-products-bar__clear-selected-filters-button font_normal">clear all</button>
            </div>
        </div>       
        <div class="no-listings-banner"></div>
        <div class="vehicles__container-wrapper position-relative">
            <div class="inventory-products__overlay position_absolute w-100 h-100" style="min-height: 450px;">
                <div class="inventory-products__loader-wrapper d-flex-column d-flex align-items-center justify-content-center pt-5">
                    <span class="inventory-products__loader d-inline-block"></span>
                    <p class="inventory-products__loader-text text-center text-uppercase font-helvetica text-white">Loading...</p>
                </div>
            </div>
            <div id="vehicles-container" class="row inventory-products-bar__listings-wrapper mt-4 pt-2 position-relative" data-current-page="2"></div>
        </div>
        <div class="vehicles_pagination"></div>
        </div></div>
    </div>
</div>
                    </div>
</div>
</div>
</div>
</article>
<?php 
    echo sidebarForm('test-drive', null, 'Schedule Test Drive', null, null,null, 'Schedule Test Drive', '[contact-form-7 id="25610" title="Schedule Test Drive"]' );
    echo sidebarForm('check-availability', null, 'check-availability', null, null,null, 'check-availability', '[contact-form-7 id="68337" title="Check Availability List View"]' );
    echo divi_child_stickersPopup(); ?>
<!-- compare bottom tab -->
    <?php
    $table_name = accessWPDB()->prefix . 'user_compared_vehicles';
    $comparedQuery = accessWPDB()->prepare("SELECT * FROM $table_name WHERE user_ip = %s", getUserIP());
    $updateResult = accessWPDB()->get_row($comparedQuery, ARRAY_A);
    $user_compared_vehicles = (!$updateResult) ? array() : unserialize($updateResult['user_compared_vehicles']);
    ?>
    <div class="compare-listings__wrapper position_fixed <?php echo count($user_compared_vehicles) > 0 && !is_null($user_compared_vehicles) ? 'compare-listings__wrapper-open' : '' ?>">
        <div class="compare-listings__header d_flex d_flex__align-center d_flex__justify-between mb-2 bg-primary">
            <h3 class="p_0 m_0 text-white font-weight-bold">Compare</h3>
            <div class="d-flex align-items-center">
            <span class="compare-listings-shrink text-primary cursor-pointer rounded-circle-px bg-white d-flex align-items-center justify-content-center mr-3" style="width: 25px; height: 25px;transition:all .3s;"><i class="fa-solid fa-arrow-down"></i></span> 
            <span class="compare-listings__close text-primary cursor-pointer rounded-circle-px bg-white d-flex align-items-center justify-content-center" style="width:25px; height:25px;"><i class="fa fa-times" style="pointer-events:none;user-select:none;"></i></span>
            </div>
        </div>
        <div>
            <div>
                <div class="compare-listings__cta compare-btn d-flex d-md-none align-items-center flex-row py-2 w-75 mx-auto justify-content-between px-3">
                    <span class="compare-listings__remove-all mt-0">Remove all</span>
                    <a href="javascript:void(0)" class="compare-listings__compare <?php echo (count($user_compared_vehicles) < 2 ? 'disabled' : '') ?>">Compare</a>
                </div>
            </div>
            <div class="compare-listings__body d_flex d_flex__justify-start overflow-auto">
            <div class="compare-listings__cards">
                <?php
                if (count($user_compared_vehicles) > 0) {
                    $args = array('post_type' => 'listings', 'posts_per_page' => -1, 'post__in' => $user_compared_vehicles);
                    $compareQuery = new WP_Query($args);
                    if ($compareQuery->have_posts()) {
                        $connection = get_db_connection();
                        $stmt = $connection->prepare("SELECT vauto_url FROM dmc_images WHERE vin = ? LIMIT 1");
                        while ($compareQuery->have_posts()) {
                            $compareQuery->the_post();
                            $vin = get_post_meta(get_the_ID(), 'vin-number', true);
                            $image_url = '';
                            if ($stmt) {
                                $stmt->bind_param("s", $vin);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($row = $result->fetch_assoc()) $image_url = $row['vauto_url'];
                            }
                            echo '<div class="compare-listings__card" data-remove="' . get_the_ID() . '"><div class="compare-listings__card-img">';
                            if (!empty($image_url)) echo '<img src="' . $image_url . '" loading="lazy" width="90" height="90"/>';
                            echo '</div><div class="compare-listings__card-remove"><span class="fa fa-times" aria-hidden="true" data-remove="' . get_the_ID() . '"></span></div></div>';
                        }
                        $stmt->close();
                        $connection->close();
                        wp_reset_postdata();
                    }
                }
                ?>
            </div>
            <div class="compare-listings__cta compare-btn d-none d-md-flex">
                <a href="javascript:void(0)" class="compare-listings__compare <?php echo (count($user_compared_vehicles) < 2 ? 'disabled' : '') ?>">Compare</a>
                <span class="compare-listings__remove-all text-link">Remove all</span>
            </div>
        </div>
        </div>
    </div>
    <?php endwhile; ?>
<div class="inventory-page-paragraph">
		<p><span style="font-size:14px">Although every reasonable effort has been made to ensure the accuracy of the information contained on this site, absolute accuracy cannot be guaranteed. This site, and all information and materials appearing on it, are presented to the user "as is" without warranty of any kind, either express or implied. All vehicles are subject to prior sale. <strong>Price does not include $499 Dealer Doc Fee.</strong> Tax, title, license, and insurance are also not included in pricing. See dealer for details. ‡Vehicles shown at different locations are not currently in our inventory (Not in Stock) but can be made available to you at our location within a reasonable date from the time of your request, not to exceed one week.</span></p>
</div>
    <div class="inventory-page-paragraph">
		<h2 style="text-align: center;">Didn't find what you're looking for?</h2>
		<p style="text-align: center;"><span style="font-size:14px">At Durango Motor Company we want you to find the perfect vehicle, and we'll work hard to make sure you do. Simply tell us what you're looking for and when it's available you'll be the first to know!</span></p>
	</div>
    <div class="inventory-form container" style="background: transparent !important;">
        <?php echo do_shortcode('[contact-form-7 id="5f2bf80" title="Inventory - Didn\'t find what you\'re looking for"]');?>
    </div>
    <script>
    jQuery(document).ready(function($) {
    $('.slick-slider').slick({ dots: true, arrows: true, infinite: true, speed: 500, slidesToShow: 1, slidesToScroll: 1, adaptiveHeight: true, autoplay: true, autoplaySpeed: 3000 });
});
</script>
<?php get_footer();
