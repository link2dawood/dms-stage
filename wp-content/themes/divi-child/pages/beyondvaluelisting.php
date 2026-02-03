<?php /* Template Name: beyondvaluelisting template */ ?>
<?php
    global $post;
    global $Listing;
    global $wpdb;
    $Automotive_Plugin   = Automotive_Plugin();
    $Automotive_Template = Automotive_Plugin_Template();
    // get the current single listing
    if ( isset( $_GET['post'] ) ) {
        $activePost = $_GET['post'];
        $activePostExplode = explode(' ', $activePost);
    } else {
        wp_die('Post parameter is missing');
    }

    $args = array(
        'post_type' => 'listings',
        'fields' => 'ids',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'make',
                'value' => $activePostExplode[0],
                'compare' => '=',
            ),
            array(
                'key' => 'model',
                'value' => $activePostExplode[1],
                'compare' => '=',
            ),
        ),
    );
    $activePostExplode[1] = trim(strtolower($activePostExplode[1]));
    $post_ids = get_posts( $args );
    if ( ! empty( $post_ids ) ) {
        $random_post_id = $post_ids[array_rand($post_ids)];
        $Automotive_Listing = new Automotive_Listing( $random_post_id );
    } else {
         wp_die('Sorry, no matching posts found! Explore our <a href="'.site_url().'/inventory" class="text-link">inventory</a>');
    }
    $activePostType = get_post_meta($random_post_id, 'type-of-vehicle', true);
    $activePostMake = get_post_meta($random_post_id, 'make', true);
    $activePostName = get_post_meta($random_post_id, 'make', true) . ' ' . get_post_meta($random_post_id, 'model', true);
    // check if the user is coming from homepage popup or not
    if( isset($_GET['showPopup']) ) {
        $showPopup = $_GET['showPopup'];
    }  
    $mainPrice = $Automotive_Plugin->format_currency( $Automotive_Listing->get_price(), true );
    if( isset($mainPrice) && $mainPrice !== 'None' ) {
        $mainPrice = $mainPrice ;
    } else {
        $mainPrice = '<a href="tel:'. get_field('quick_call_phone_number', 'options') .'" class="quick-call-link color_black"><i class="fa fa-phone"></i></a>';
    } 
    $vehicleTitle = $Automotive_Listing->get_title();
    // include common elements
    require_once get_stylesheet_directory() . '/custom-templates/beyondValueCards.php';
    include_once(get_stylesheet_directory() . '/VDP_common/vehicleMeta.php');
    include_once(get_stylesheet_directory() . '/sidebarForm.php');
    require_once get_stylesheet_directory() . '/custom-templates/stickersPopup.php';
    require_once get_stylesheet_directory() . '/VDP_common/sidebar.php';

    $disclaimer = get_field('vdp_disclaimer_text','options');
    $vehicleId = $Automotive_Listing->id;
	$popupImages = get_post_meta($vehicleId, 'gallery_images', true);
    $popupImage = (getimagesize(wp_get_attachment_url($popupImages[0]))) ? wp_get_attachment_url($popupImages[0]) : 'http://vehicle-photos-published.vauto.com/d5/fc/fb/f7-ff32-47f3-b551-2ea9efdc68f6/image-1.jpg';
    $vehicleModel = $Automotive_Listing->{'_listing_term_model'};
    $vehicleMake = $Automotive_Listing->{'_listing_term_make'};
    $vehicleYear = $Automotive_Listing->{'_listing_term_year'};
    $vehicleType = $Automotive_Listing->{'_listing_term_type-of-vehicle'};
    $vehicleExteriorColor = $Automotive_Listing->{'_listing_term_exterior-color'};
    $vehicleInteriorColor = $Automotive_Listing->{'_listing_term_interior-color'};
    $vehicleMileage = $Automotive_Listing->{'_listing_term_odometer'};
    $vehicleStock = $Automotive_Listing->{'_listing_term_stock-number'};
    $vehicleDrivetrain = $Automotive_Listing->{'_listing_term_drivetrain'};
    $vehicleEngine = $Automotive_Listing->{'_listing_term_engine'};
    $vehicleVin = $Automotive_Listing->{'_listing_term_vin-number'};
    $vehicleCertified = $Automotive_Listing->{'_listing_term_certified'};
    $vehicleBodyStyle = $Automotive_Listing->{'_listing_term_body-style'};
    $vehicleTransmission = $Automotive_Listing->{'_listing_term_transmission'};
    $vehicleDoors = $Automotive_Listing->{'_listing_term_doors'};
    $vehicleCylinders = $Automotive_Listing->{'_listing_term_cylinders'};
    $vehicleFuelType = $Automotive_Listing->{'_listing_term_fuel-type'};
    $vehicleSeries = $Automotive_Listing->{'_listing_term_series'};
    $vehicleCertification = $Automotive_Listing->{'_listing_term_certification'};
    $vehicleCityMPG = $Automotive_Listing->{'city_mpg'};
    $vehicleHighwayMPG = $Automotive_Listing->{'highway_mpg'};
    $vehicleFeatures = $Automotive_Listing->{'_listing_term_features'};
    $vehicleDealer = $Automotive_Listing->{'_listing_term_dealer-id'};
    $vehicleEngineDisplacement = $Automotive_Listing->{'_listing_term_engine-displacement'};
    $vehicleThumbnail = $Automotive_Listing->{'gallery_images'}[0];
    $detailsArray = array(
        'engine' => $vehicleEngine,
        'stock #' => $vehicleStock,
        'vin number' => $vehicleVin,
        'year' => $vehicleYear,
        'make' => $vehicleMake,
        'model' => $vehicleModel,
        'mileage' => $vehicleMileage,
        'certified' => $vehicleCertified,
        'body Style' => $vehicleBodyStyle,
        'transmission' => $vehicleTransmission,
        'doors' => $vehicleDoors,
        'cylinders' => $vehicleCylinders,
        'drivetrain' => $vehicleDrivetrain,
        'fuel type' => $vehicleFuelType,
        'exterior color' => $vehicleExteriorColor,
        'interior color' => $vehicleInteriorColor,
        'series' => $vehicleSeries,
    );

    function getImageInfo($field) {
        $attachment = wp_get_attachment_image_src($field, 'full');
        $URL = $attachment[0];
        $alt = get_post_meta($field, '_wp_attachment_image_alt', true);
        $width = $attachment[1];
        $height = $attachment[2];
        return array($URL, $alt, $width, $height);
    }

  get_header();
?>
<style type="text/css">
    .active:not(.mobile-main-header-wrapper):not(.main-header-wrapper){
        border: 3px solid #CF2129 !important;
    }
    .getresult span {
    font-size: 1rem;
    text-align: center;
    padding: 1.25em;
    word-spacing: 5px;
    }
</style>
<div class="inner-page inventory-listing" itemscope itemtype="http://schema.org/Vehicle">
    <div id="main-content">
        <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php
        if( !empty(get_field('vehicle_hero_image_row', 'options')) ) {
			$defaultHeroMatchFound = false;
            foreach( get_field('vehicle_hero_image_row', 'options') as $heroImage ) {
                $heroImageGroup = $heroImage['vehicle_hero_image_group'];
                if( strtolower($heroImageGroup['vehicle_hero_image_model']) == $activePostExplode[1] ) {
                    $heroImageDesktop =  getImageInfo($heroImageGroup['vehicle_hero_image_desktop']);
                    $heroImageMobile =  getImageInfo($heroImageGroup['vehicle_hero_image_desktop']);
                    echo '<picture class="listing-banner-image d_inline-block w-100 mb-4" style="height:60vh">'.
                         '<source class="w-100" media="(min-width:767px)" srcset="'.$heroImageDesktop[0].'"/>'.
                         '<source class="w-100" media="(max-width:766px)" srcset="'.$heroImageMobile[0].'" />'.
                         '<img class="w-100 h-100 object_fit_cover" src="'.$heroImageMobile[0].'" alt="'.$heroImageDesktop[1].'" width="'.$heroImageMobile[2].'" height="'.$heroImageMobile[3].'" itemprop="image" />'.
                         '</picture>';
					$defaultHeroMatchFound = true; // Set the flag to true
            		break; // Exit the loop since a match is found
                }
            }
			if( !$defaultHeroMatchFound ) {
				$defaultImage = getImageInfo(get_field('beyond_value_default_hero_image', 'options'));
				echo '<picture class="listing-banner-image d_inline-block w-100 mb-4" style="height:60vh">'.
                         '<source class="w-100" media="(min-width:767px)" srcset="'.$defaultImage[0].'"/>'.
                         '<source class="w-100" media="(max-width:766px)" srcset="'.$defaultImage[0].'" />'.
                         '<img class="w-100 h-100 object_fit_cover" src="'.$defaultImage[0].'" alt="'.$defaultImage[1].'" width="'.$defaultImage[2].'" height="'.$defaultImage[3].'" itemprop="image" />'.
                         '</picture>';
			}
        }
        ?>
            <div class="listing-options d-flex flex-wrap mb-4 px-g">
                <button class="px-md-1 recommend-vehicles-popup-trigger btn btn-primary font-helvetica font-weight-normal w-100 d-flex justify-content-center align-items-center letter-spacing font-xl border border-white" data-target="0">discover your perfect vehicle</button>
                <a href="<?php echo site_url(); ?>/inventory" class="px-md-1 btn btn-primary font-helvetica font-weight-normal w-100 d-flex justify-content-center align-items-center letter-spacing font-xl border border-white">search inventory</a>
                <a href="<?php echo site_url(); ?>/finance/value-your-trade" class="px-md-1 btn btn-primary font-helvetica font-weight-normal w-100 d-flex justify-content-center align-items-center letter-spacing font-xl border border-white">value my trade</a>
                <a href="<?php echo site_url(); ?>/service-and-parts/schedule-express-service-durango-co/" class="px-md-1 btn btn-primary font-helvetica font-weight-normal w-100 d-flex justify-content-center align-items-center letter-spacing font-xl border border-white">schedule service</a>
            </div>
            <div class="px-g mb-30 mb-lg-5">
                <div class="beyond-card-inner px-g py-4 shadow-secondary">
                    <?php 
                    echo '<div class="d-md-none">';
                    echo vehicleSlider($Automotive_Listing->get_gallery_images(false, 'full'),
                    $Automotive_Listing->get_gallery_images(false, 'auto_thumb'),
                    $Automotive_Listing->get_gallery_images(false, 'auto_slider'),
                    $Automotive_Plugin->get_automotive_image_sizes('auto_thumb'),
                    $Automotive_Plugin->get_automotive_image_sizes());
                    echo '</div>';
                    vehicleDetailsBox($vehicleId, $vehicleTitle, $vehicleVin, $vehicleStock, $vehicleCertified, $vehicleMake, $vehicleModel, $vehicleYear);
                    echo '<div class="d-md-none mt-20">';
                    stickyBanner($mainPrice, $vehicleYear, $vehicleMake, $vehicleModel, $vehicleVin, $vehicleStock, $vehicleThumbnail, $vehicleTitle);
                    echo '</div>';
                   echo '<div class="d-none d-md-block">';
                   $pillsUrl = array(
                       '?search='.$vehicleYear.'' => $vehicleYear,
                       '?search='.$vehicleMake.'' => $vehicleMake,
                       '?search='.$vehicleModel.'' => $vehicleModel,
                       '?search='.$vehicleBodyStyle.'' => $vehicleBodyStyle,
                       '?search='.$vehicleType.'' => $vehicleType,
                       '?search='.$vehicleDoors.'' => $vehicleDoors,
                       '?search='.$vehicleCylinders.'' => $vehicleCylinders,
                       '?search='.$vehicleDrivetrain.'' => $vehicleDrivetrain,
                       '?search='.$vehicleTransmission.'' => $vehicleTransmission,
                       '?search='.$vehicleExteriorColor.'' => $vehicleExteriorColor,
                       '?search='.$vehicleInteriorColor.'' => $vehicleInteriorColor,
                       '?search='.$vehicleFuelType.'' => $vehicleFuelType,
                   );        
                   filterPills($pillsUrl);
                   echo '</div>'; ?>
                   <meta itemprop="image" content="<?php echo esc_url($main_image['src']); ?>"></meta>
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <?php 
                            echo '<div class="d-none d-md-block">';
                            echo vehicleSlider($Automotive_Listing->get_gallery_images(false, 'full'),
                                                     $Automotive_Listing->get_gallery_images(false, 'auto_thumb'),
                                                     $Automotive_Listing->get_gallery_images(false, 'auto_slider'),
                                                     $Automotive_Plugin->get_automotive_image_sizes('auto_thumb'),
                                                     $Automotive_Plugin->get_automotive_image_sizes());
                            echo '</div>';
                            $metaValues = array(
                                'year' => $vehicleYear,
                                'mileage' => $vehicleMileage,
                                'exterior color' => $vehicleExteriorColor,
                                'make' => $vehicleMake,
                                'stock #' => $vehicleStock,
                                'interior color' => $vehicleInteriorColor,
                                'model' => $vehicleModel,
                                'vin' => $vehicleVin,
                                'body style' => $vehicleBodyStyle,
                                'series' => $vehicleSeries,
                                'transmission' => $vehicleTransmission,
                            );
                            vehicleMeta($metaValues);                
							vehicleCertifiedPreOwned($vehicleMake, $vehicleCertification, $vehicleCertified);
                            vehicleHighlightedFeatures($vehicleFeatures);
                            vehicleHistoryReport($vehicleVin);
                            vehicleDescription(trim(strip_tags($Automotive_Listing->get_vehicle_overview())));
                            $detailsAccordionArray = array(
                                'year' => $vehicleYear,
                                'mileage' => $vehicleMileage,
                                'exterior color' => $vehicleExteriorColor,
                                'make' => $vehicleMake,
                                'stock #' => $vehicleStock,
                                'interior color' => $vehicleInteriorColor,
                                'model' => $vehicleModel,
                                'VIN' => $vehicleVin,
                                'body style' => $vehicleBodyStyle,
                                'trim/series' => $vehicleSeries,
                                'transmission' => $vehicleTransmission,
                                'Dealer ID' => $vehicleDealer,
                                'type' => $vehicleType,
                                'certified' => $vehicleCertified,
                                'body'=> $vehicleDrivetrain,
                                'doors' => $vehicleDoors,
                                'cylinders' => $vehicleCylinders,
                                'engine' => $vehicleEngine,
                                'fueltype' => $vehicleFuelType,
                                'epa city' => $vehicleCityMPG,
                                'epa highway' => $vehicleHighwayMPG,
                                'engine displacement' => $vehicleEngineDisplacement,
                                'drivetrain' => $vehicleDrivetrain,
                            );
                            vehicleDetailsAccordion($detailsAccordionArray);
                            vehicleEquipmentDetails($vehicleFeatures);
                            vehicleDisclaimer($disclaimer); ?>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 position-relative">
                            <?php
                        echo '<div class="d-none d-md-block position-sticky sticky-lead-form-wrapper">';
                        stickyBanner($mainPrice, $vehicleYear, $vehicleMake, $vehicleModel, $vehicleVin, $vehicleStock, $vehicleThumbnail, $vehicleTitle);
                        echo '</div>';
                        upgradeVehicle($vehicleMake, $vehicleThumbnail, $vehicleId); ?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- media gallery starts here -->
            <div class="beyond-card px-g mb-30 mb-lg-5">
                <h2 class="beyond-heading d-lg-none font-helvetica text-primary font-weight-bold p-0 mb-30 text-uppercase">
                    <?php echo ((!empty(get_field('media_gallery_heading','options'))) ? get_field('media_gallery_heading','options') : ''); ?>
                </h2>
                <div class="beyond-card-inner px-g py-4 shadow-secondary">
                    <h2 class="beyond-heading d-none d-lg-block font-helvetica text-primary font-weight-bold p-0 mb-30 text-uppercase">
                        <?php echo ((!empty(get_field('media_gallery_heading','options'))) ? get_field('media_gallery_heading','options') : ''); ?>
                    </h2>
                    <div class="beyond-media-gallery">
                        
                    </div>
                </div>
            </div>
            <!-- contact section -->
            <div class="beyond-card px-g mb-30 mb-lg-5">
                <div class="beyond-card-inner px-g pr-0 shadow-secondary">
                    <div class="row">
                        <div class="col-12 col-md-6 py-4">
                            <h2 class="beyond-heading font-weight-bold font-helvetica text-primary p-0 mb-30 text-uppercase">I'm interested in a <?php echo $vehicleModel; ?></h2>
                            <div class="spinner-hidden beyond-form position-relative global-form-wrapper">
                                <div class="global-form-form">
                                    <?php echo do_shortcode('[contact-form-7 id="25608" title="Interested Vehicle"]'); ?>
                                </div>
                                <div class="global-form-success d_none">
                                    <div class="sidebar__form-success-img d_flex d_flex__justify-center">
                                        <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submitted">
                                    </div>
                                    <h3 class="sidebar__success-msg text_capitalize font_segoe font_bold text_center">Your message has been sent!</h3>
                                    <p class="sidebar__success-desc text_center">Thank you for your message. A representative will contact you soon.</p>
                                    <div class="sidebar__ctas">
                                        <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/schedule-express-service-durango-co">Schedule Service</a>
                                        <a class="text_uppercase" href="<?php echo site_url(); ?>/inventory">View Inventory</a>
                                        <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/auto-parts-durango-co">Call Service & Parts</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-none d-md-block">
                            <?php 
                                $interestedImages = get_field('i_am_interested_vehicle_section', 'options');
								if( !empty($interestedImages) ) {
									$interestedMatch = false;
									foreach ($interestedImages as $interestedImage) {
                                    $interestedModel = $interestedImage['i_am_interested_vehicle_group']['i_am_interested_vehicle_model'];
                                    if (strtolower($interestedModel) == strtolower($activePostExplode[1])) {
                                        $attachmentID = $interestedImage['i_am_interested_vehicle_group']['i_am_interested_vehicle_image'];
                                        $attachment = wp_get_attachment_image_src($attachmentID, 'full');
                                        if ($attachment) {
                                            $attachmentURL = $attachment[0];
                                            $attachmentWidth = $attachment[1];
                                            $attachmentHeight = $attachment[2];
                                            $attachmentAlt = get_post_meta($attachmentID, '_wp_attachment_image_alt', true);
                                
                                            if ($attachmentURL && $attachmentWidth && $attachmentHeight && $attachmentAlt) {
                                                echo '<img src="' . $attachmentURL . '" width="' . $attachmentWidth . '" height="' . $attachmentHeight . '" alt="' . $attachmentAlt . '" class="w-100 h-100 object_fit_cover" itemprop="image" loading="lazy" />';
                                            }
                                        }
										$interestedMatch = true;
                                    }
                                }
									if( !$interestedMatch ) {
										$defaultInterested = getImageInfo(get_field('default_i_am_interested_vehicle_image', 'options'));
										echo '<img src="' . $defaultInterested[0] . '" width="' . $defaultInterested[2] . '" height="' . $defaultInterested[3] . '" alt="' . $defaultInterested[1] . '" class="w-100 h-100 object_fit_cover" itemprop="image" loading="lazy" />';
									}
								}                          
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Blog Section Started -->
            <div class="beyond-card px-g mb-30 mb-lg-5">
                <div class="beyond-card-inner px-g py-4 shadow-secondary">
                    <h2 class="beyond-heading font-helvetica text-primary font-weight-bold p-0 mb-30 text-uppercase">
                        blogs
                    </h2>
                    <div class="row blogs-card-container">
                        <?php
                        function blogCard($image, $title, $url, $desc) {
                            $html = '<div class="col-12 col-md-6 col-lg-4">'.
                                    '<div class="rounded-10 shadow-third position-relative h-100">'.
                                    '<a href="'.$url.'" target="_blank" class="d-inline-block w-100" style="height:230px;">'.
                                    '<img src="'.$image.'" alt="blog thumbnail" loading="lazy" class="w-100 h-100 img-fluid object_fit_cover" style="border-radius:10px 10px 0 0;" />'.
                                    '</a>'.
                                    '<div class="blog-card-content px-15" style="border-radius: 0 0 20px 20px; padding-bottom:30px;">'.
                                    '<a href="'.$url.'" target="_blank">'.
                                    '<h2 class="m-0 font-weight-bold font-helvetica text-uppercase text-grey-6 font-xxl p-0">'.$title.'</h2>'.
                                    '</a>'.
                                    '<p class="font-md font-helvetica text-grey-6 pt-15">'.$desc.'</p>'.
                                    '</div></div></div>';
                            return $html;
                        }
                        $blogsSection = get_field('beyond_value_blogs_section', 'option');
                        $blogMatch = false;
                        foreach( $blogsSection as $blogs ) {
                            if( trim(strtolower($blogs['beyond_value_blogs_vehicle_model'])) == $activePostExplode[1]  ) {
                                $blogsRow = $blogs['beyond_value_blogs_row'];
                                foreach( $blogsRow as $row ) {
                                    $blogGroup = $row['beyond_value_blog_group'];
                                    $blogThumbnail = $blogGroup['beyond_value_blog_thumbnail'];
                                    $blogThumbnailArr = wp_get_attachment_image_src($blogThumbnail, 'full');
                                    if( $blogThumbnailArr ) {
                                        $blogThumbnailURL = $blogThumbnailArr[0];
                                        $blogThumbnailWidth = $blogThumbnailArr[1];
                                        $blogThumbnailHeight = $blogThumbnailArr[2];
                                        $blogThumbnailAlt = get_post_meta($blogThumbnail, '_wp_attachment_image_alt', true);
                                    }
                                    $blogTitle = $blogGroup['beyond_value_blog_title'];
                                    $blogLink = $blogGroup['beyond_value_blog_url'];
                                    $blogDesc = $blogGroup['beyond_value_blog_description'];
                                    echo blogCard($blogThumbnailURL, $blogTitle, $blogLink, $blogDesc);
                                }
                                $blogMatch = true;
                            }
                        }
                        if( !$blogMatch ) {
                            $defaultBlog = get_field('beyond_value_default_blogs_row', 'options');
                            if( !empty($defaultBlog) ) {
                                foreach( $defaultBlog as $default ) {
                                    $defaultGroup = $default['beyond_value_default_blogs_group'];
                                    $defaultTitle = $defaultGroup['default_blog_card_title'];
                                    $defaultDescription = $defaultGroup['default_blog_card_description'];
                                    $defaultThumbnail = getImageInfo($defaultGroup['default_blog_card_thumbnail']);
                                    $defaultLink = $defaultGroup['default_blog_card_url'];
                                    echo blogCard($defaultThumbnail[0], $defaultTitle, $defaultLink, $defaultDescription);
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Blog Section Ended -->
            <div class="row px-g mb-30 mb-lg-5 w-100">
                <div class="col-12 col-lg-8">
                    <div class="position-relative feature-box mb-30 mb-lg-0">
                        <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/media-gallery.webp" alt="Photo gallery" class="img-fluid w-100 h-100" width="627" height="470" loading="lazy">
                        <a href="javascript:void(0);" class="photo-gallery-popup-trigger feature-box-beyond-title feature-box-title text-white font-weight-bold font-xxl">PHOTO GALLERY</a>
                    </div>
                </div>
                <div class="col-12 col-lg-4 h-100">
                    <div class="position-relative feature-box h-50 mb-30">
                        <a href="<?php echo site_url(); ?>/service-and-parts/detail-department" class="d-inline-block w-100 h-100">
                            <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/beyond-detailing.webp" alt="Detailing" width="314" height="170" class="h-100 w-100 object_fit_cover image-fluid" loading="lazy" style="border-radius:20px;">
                        </a>
                        <a href="<?php echo site_url(); ?>/service-and-parts/detail-department" class="feature-box-beyond-title feature-box-title text-white font-weight-bold font-xxl">DETAILING</a>
                    </div>
                    <div class="position-relative feature-box h-50">
                        <a href="https://www.durangomotorcompany.com/dgo-autogear-accessories" target="_blank" class="d-inline-block w-100 h-100">
                            <?php
                                $accessoriesRow = get_field('beyond_value_accessories_image_row','options');
                                $defaultAccessory = false;
                                foreach( $accessoriesRow as $accessory ) {
                                    $accessoryModel = $accessory['beyond_value_accessories_image_group']['beyond_accessories_image_vehicle_model'];
                                    if( strtolower($accessoryModel) == trim(strtolower($activePostExplode[1])) ) {
                                        $accessoryAttachment =  $accessory['beyond_value_accessories_image_group']['beyond_value_accessories_image']; 
                                        $accessoryAttachmentArr = wp_get_attachment_image_src($accessoryAttachment, 'full');
                                        if( $accessoryAttachmentArr ) {
                                            $accessoryURL = $accessoryAttachmentArr[0];
                                            $accessoryWidth = $accessoryAttachmentArr[1];
                                            $accessoryHeight = $accessoryAttachmentArr[2];
                                            $accessoryAlt = get_post_meta($accessoryAttachment, '_wp_attachment_image_alt', true);
                                            if( $accessoryURL ) {
                                                echo '<img src="'.$accessoryURL.'" alt="'.$accessoryAlt.'" width="'.$accessoryWidth.'" height="'.$accessoryHeight.'" class="h-100 w-100 object_fit_cover image-fluid" loading="lazy" itemprop="image" style="border-radius:20px;" />';
                                            }
                                        } 
                                        $defaultAccessory = true;
                                    }
                                }
                                if( !$defaultAccessory ) {
                                    $defaultAccessoryField = get_field('default_accessories_image', 'options');
                                    if( !empty($defaultAccessoryField) ) {
                                        $defaultAccessoryImage = getImageInfo($defaultAccessoryField);
                                        if( $defaultAccessoryImage ) {
                                            echo '<img src="'.$defaultAccessoryImage[0].'" alt="'.$defaultAccessoryImage[1].'" width="'.$defaultAccessoryImage[2].'" height="'.$defaultAccessoryImage[3].'" class="h-100 w-100 object_fit_cover image-fluid" loading="lazy" itemprop="image" style="border-radius:20px;" />';
                                        }
                                    }
                                }
                            ?>
                        </a>
                        <a href="https://www.durangomotorcompany.com/dgo-autogear-accessories" target="_blank" class="feature-box-beyond-title feature-box-title text-white font-weight-bold font-xxl">ACCESSORIES</a>
                    </div>
                </div>
            </div>
            <div class="beyond-card px-g mb-30">
                <div class="beyond-card-inner px-g py-4 shadow-secondary">
                    <h2 class="beyond-heading d-none d-lg-block font-helvetica text-primary font-weight-bold p-0 mb-30 text-uppercase">
                        <?php echo get_field('beyond_value_highlights_heading', 'options'); ?>
                    </h2>
                    <div class="beyond-value-highlights-card-container row">
                        <?php 
                        $highlightsField = get_field('beyond_value_highlights_section', 'options');
                        $highlightArr = [];
                        $defaultHighlight = false;
                        foreach( $highlightsField as $highlight ) {
                            $highlightModel = $highlight['beyond_value_highlights_model'];
                            if( strtolower($highlightModel) == trim(strtolower($activePostExplode[1])) ) {
                                $highlightsRow = $highlight['beyond_value_highlights_row'];
                                foreach( $highlightsRow as $innerHighlight ) {
                                    $highlightCard = $innerHighlight['beyond_value_highlights_card'];
                                    $highlightImage = $highlightCard['beyond_value_highlight_card_image'];
                                    $highlightHeading = $highlightCard['beyond_value_highlight_card_heading'];
                                    $highlightDesc = $highlightCard['beyond_value_highlight_card_description'];
                                    $highlightImageArr = wp_get_attachment_image_src($highlightImage, 'full');
                                    if( $highlightImageArr ) {
                                        $highlightURL = $highlightImageArr[0];
                                        $highlightWidth = $highlightImageArr[1];
                                        $highlightHeight = $highlightImageArr[2];
                                        $highlightAlt = get_post_meta($highlightImage, '_wp_attachment_image_alt', true);
                                    }
                                    $highlightArr[] = array($highlightURL, $highlightWidth, $highlightHeight, $highlightAlt, $highlightHeading, $highlightDesc);
                                }
                                $defaultHighlight = true;
                            }
                        }
                        foreach( $highlightArr as $highlight ) {
                            echo highlightHTML($highlight);
                        }
                        if( !$defaultHighlight ) {
                            $defaultHighlightField = get_field('beyond_value_default_highlights_row', 'options');
                            $defaultHighlightArr = [];
                            foreach( $defaultHighlightField as $highlight ) {
                                $highlightGroup = $highlight['beyond_value_default_highlights_group'];
                                $highlightTitle = $highlightGroup['default_highlights_card_title'];
                                $highlightDesc = $highlightGroup['default_highlights_card_description'];
                                $highlightThumb = getImageInfo($highlightGroup['default_highlights_card_thumbnail']);
                                $defaultHighlightArr[] = array($highlightThumb[0], $highlightThumb[2], $highlightThumb[3], $highlightThumb[1], $highlightTitle, $highlightDesc);
                            }
                            foreach( $defaultHighlightArr as $arr ) {
                                echo highlightHTML($arr);
                            }
                        }                    
                        ?>
                    </div>
                </div>
            </div>
            <!-- recommend vehicles popup started -->
            <div class="recommend-vehicles-popup-wrapper global_popup_wrapper">
                <div class="recommend-vehicles-popup-overlay recommend-vehicles-popup-close-outside global_popup_wrapper_overlay"></div>
                    <div class="recommend-vehicles-popup-container-wrapper global_popup_wrapper_content-wrapper overflow-auto">
                        <div class="d-flex justify-content-between align-items-center mb-30">
                            <h2 class="mb-0 text-grey-3 text-capitalize font-weight-bold font-segoe beyond-heading p-0">Discover Your Perfect Vehicle</h2>
                            <div class="recommend-vehicles-popup-close-icon global_popup_wrapper_close d-flex justify-content-center align-items-center position-absolute cursor-pointer rounded-circle-px recommend-vehicles-popup-close">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>
                        <!-- popup selection container started -->
                        <div class="recommend-vehicles-popup-selection-container">
                            <div class="recommend-vehicles-popup-selection-tabs ">
                                <!-- tab started -->
                                <div class="recommend-vehicles-popup-selection-tab recommend-tab-active recommend-introduction-tab" data-target=0>
                                    <div class="recommend-vehicles-popup-selection-tab-step-counter">
                                        <p>start</p>
                                    </div>
                                    <div class="recommend-vehicles-popup-selection-tab-heading">
                                        <h3 class="selection-tab-heading-text">introduction</h3>
                                    </div>
                                </div>
                                <div class="recommend-vehicles-popup-selection-tab" data-target=1>
                                    <div class="recommend-vehicles-popup-selection-tab-step-counter">
                                        <p>step 2</p>
                                    </div>
                                    <div class="recommend-vehicles-popup-selection-tab-heading">
                                        <h3 class="selection-tab-heading-text">choose vehicle</h3>
                                    </div>
                                </div>
                                <div class="recommend-vehicles-popup-selection-tab" data-target=2>
                                    <div class="recommend-vehicles-popup-selection-tab-step-counter">
                                        <p>step 3</p>
                                    </div>
                                    <div class="recommend-vehicles-popup-selection-tab-heading">
                                        <h3 class="selection-tab-heading-text">interests</h3>
                                    </div>
                                </div>
                                <div class="recommend-vehicles-popup-selection-tab" data-target=3>
                                    <div class="recommend-vehicles-popup-selection-tab-step-counter">
                                        <p>step 4</p>
                                    </div>
                                    <div class="recommend-vehicles-popup-selection-tab-heading">
                                        <h3 class="selection-tab-heading-text">must have</h3>
                                    </div>
                                </div>
                                <div class="recommend-vehicles-popup-selection-tab" data-target=4>
                                    <div class="recommend-vehicles-popup-selection-tab-step-counter">
                                        <p>final step</p>
                                    </div>
                                    <div class="recommend-vehicles-popup-selection-tab-heading">
                                        <h3 class="selection-tab-heading-text">recommendations</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- introduction tab content started -->
                            <div class="recommend-vehicles-popup-selection-content recommend-content-active" data-id=0>
                                <div class="recommend-vehicles-popup-introduction-content">
                                    <div class="popup-introduction-content-left-side">
                                        <h2 class="popup-introduction-content-heading-text d_none d_md_block">let us find your perfect
                                            vehicle</h2>
                                            <div class="popup-introduction-image d_block d_md_none" style="margin-bottom: 5px ;">
                                                <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/find-your-perfect-vehicle.webp" alt="introduction tab" width="345" height="216" class="w-100">
                                            </div>
                                        <p
                                            class="popup-introduction-content-para popup-introduction-content-first-para d_lg_none">
                                            Discover your perfect vehicle with our Beyond Value discovery engine that can help you find the best vehicles based on your prefence and lifestyle.We understand that finding a vehicle can be difficult and we want to make shopping for a vehicle an enjoyable, hassle-free experience every time you. Find out what which vehicle fits the best with your needs and interest.
                                        </p>
                                        <p
                                            class="popup-introduction-content-para popup-introduction-content-second-para d_none d_lg_block">
                                            Discover your perfect vehicle with our Beyond Value discovery engine that can help you find the best vehicles based on your prefence and lifestyle. We understand that finding a vehicle can be difficult and we want to make shopping for a vehicle an enjoyable, hassle-free experience every time you. Find out what which vehicle fits the best with your needs and interest.
                                        </p>

                                        <div class="popup-introduction-next-step-btn">
                                            <button
                                                class="popup-introduction-next-step-trigger popup-content-global-nav-btns" data-nav-type='next'
                                                data-target=1 >
                                                start
                                            </button>
                                        </div>
                                    </div>
                                    <div class="popup-introduction-content-right-side d_none d_md_flex">
                                        <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/introduction-tab.webp"
                                            alt="introduction tab" width="564" height="452" class="w-100">
                                    </div>
                                </div>
                            </div>
                            <!-- introduction tab content ended -->
                            <!-- choose vehicle tab started -->
                            <div class="recommend-vehicles-popup-selection-content" data-id=1>
                                <div
                                    class="recommend-vehicles-choosevehicles-inner-container recommend-vehicles-global-inner-container">
                                    <div class="recommend-vehicles-popup-choosevehicles-content position_relative">
                                        <div
                                            class="recommend-vehicles-choosevehicles-top recommend-vehicles-global-top">
                                            <h2 class="popup-choosevehicles-content-heading-text text_capitalize ">what type of vehicle
                                                are you looking for?</h2>
                                        </div>
                                        <div class="recommend-vehicles-choosevehicles-content">
                                            <div class="recommend-popup-vehicle-options-wrapper">
                                                <?php
                                    $siteurl = site_url();
                                    $typeofinventory = array('truck'=>$siteurl.'/wp-content/themes/divi-child/assets/images/trucks.webp','suv'=>$siteurl.'/wp-content/themes/divi-child/assets/images/suvs.webp','electric'=>$siteurl.'/wp-content/themes/divi-child/assets/images/hybrid-electric.webp','cars'=>$siteurl.'/wp-content/themes/divi-child/assets/images/cars.webp','vans'=>$siteurl.'/wp-content/themes/divi-child/assets/images/vans.webp','hybrid'=>$siteurl.'/wp-content/themes/divi-child/assets/images/hybrid.webp');
                                    foreach ($typeofinventory as $value => $image) {
                                        $trimVal = str_replace(' ', '-', $value);
                                        if( $trimVal !== 'None' ) {
                                            if ( $trimVal == 'cars' ) {
                                                $trimVal = 'sedan';
                                            }
                                            if ( $trimVal == 'vans' ) {
                                                $trimVal = 'cargo van';
                                            }
                                            echo '<div class="recommend-popup-vehicle-option-card option-card-hover">';
                                            echo '<input type="checkbox" class="tov-input recommend-popup-always-hide-input" id="'.$trimVal.'" value="'.$trimVal.'" name="vahicle">';
                                            echo '<label for="'.$trimVal.'">';
                                            echo '<div class="recommend-popup-image-wrapper">';
                                            echo '<img src=' . $image . ' alt="choose your perfect vehicle">';
                                            echo '</div>';
                                            echo '<p class="recommend-vehicle-option-title">'.$value.'</p>' ;
                                            echo '</label>';
                                            echo '</div>';
                                        }
                                    }
                                ?>
                                            </div>
                                        </div>
                                         <!-- hybrid and popup started -->
                                <div class="unique-vehicle-type__popup position-absolute w-100 h-100 bg-white d-flex align-items-center justify-content-center">
                                    <span class="unique-vehicle-type__close position-absolute  global_popup_wrapper_close rounded-circle d-flex align-items-center justify-content-center cursor-pointer">
                                        <i class="fa fa-times"></i>
                                    </span>
                                    <div class="unique-vehicle-type__container custom-lead-capture-form ">
                                    <h2 class="p_0 unique-vehicle-type__heading font_segoe">Due to the uniqueness of this vehicle please provide your information so sales person can contact you directly.</h2>
                                    <!--  -->
                                    <div class="global-form-wrapper unique-vehicles-form">
                                        <div class="global-form-form">
                                            <?php echo do_shortcode('[contact-form-7 id="22654" title="Unique Vehicle Popup"]'); ?>
                                        </div>
                                        <div class="global-form-success d_none">
                                            <h3 class="color_white font_bold font_helvetica w-75 pb-0">Ask a question</h3>
                                                <div class="d-flex justify-content-center">
                                                    <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submitted">
                                                </div>
                                                <h3 class="text-capitalize font-segoe font-weight-bold text-center">Your message has been sent!</h3>
                                                <p class="sidebar__success-desc text-center">Thank you for your message. A representative will contact you soon.</p>
                                                <div class="sidebar__ctas">
                                                    <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/schedule-express-service-durango-co">Schedule Service</a>
                                                    <a class="text_uppercase" href="<?php echo site_url(); ?>/inventory">View Inventory</a>
                                                    <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/auto-parts-durango-co">Call Service & Parts</a>
                                                </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    </div>
                                </div>
                                <!-- hybrid and electric popup ended -->
                                    </div>
                                    <!-- popup global nav bottom -->
                                    <div class="recommend-popup-global-bottom recommend-popup-choosevehicles-bottom">
                                        <div class="recommend-vehicles-popup-selection-info text_center d_sm_md_flex" style="column-gap:1.875em;">
                                            <p class="recommend-vehicles-choosevehicles-counter-text p_0">
                                                you selected <span
                                                    class="recommend-vehicles-choosevehicles-counter">0</span> items
                                            </p>
                                            <p class="recommend-vehicles-choosevehicles-counter-text">
                                                click the vehicle again to uncheck it
                                            </p>
                                        </div>
                                        <div
                                            class="recommend-vehicles-popup-global-nav recommend-vehicles-choosevehicles-nav">
                                            <button
                                                class="type-option-selections-reset-btn custom-mobile-hide" style="background:transparent; color: #1f4a81;">reset</button>
                                            <button
                                                class="recommend-vehicles-global-popup-nav--back popup-content-global-nav-btns choosevehiclebackbtn" data-nav-type='back'
                                                data-target=0>back</button>
                                            <button
                                                class="interestsTrigger recommend-vehicles-global-popup-nav--next popup-content-global-nav-btns recommend-vehicles-option-card-next-btn" data-nav-type='next'
                                                data-target=2 disabled>next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- choose vehicles tab ended -->
                            <!-- interest tab started -->
                            <div class="recommend-vehicles-popup-selection-content" data-id=2>
                                <div
                                    class="recommend-vehicles-interests-inner-container recommend-vehicles-global-inner-container">
                                    <div class="recommend-vehicles-popup-choosevehicles-content">
                                        <div
                                            class="recommend-vehicles-choosevehicles-top recommend-vehicles-global-top">
                                            <h2 class="popup-choosevehicles-content-heading-text">What are your
                                                interests? Pick three to five items that fit your needs.</h2>
                                        </div>
                                        <div
                                            class="recommend-vehicles-interests-content recommend-vehicles-global-content">
                                            <div class="recommend-popup-vehicle-interests-wrapper">
                                                <?php $inter = $Listing->get_single_listing_category('interests'); ?>
                                                <?php 
                                            foreach($inter['terms'] as $key =>$value){
                                                $trimVal = str_replace(' ', '-', $value);
                                                $imgg ='';
                                                if( $trimVal !== 'None' ) {
                                                    if($trimVal == 'hiking'){ 
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/hiking.webp';
                                                    }else if($trimVal == 'bike-riding'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/bike-riding-new.webp';
    
                                                    }else if($trimVal == 'walking-dogs'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/walking-dogs.webp';
    
                                                    }else if($trimVal == 'Kayaking'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/kayaking.webp';
    
                                                    }else if($trimVal == 'playing-music'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/playing-music.webp';
                                                    }else if($trimVal == 'gardening'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/gardening-new.webp';
                                                    }else if($trimVal == 'wood-working'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/wood-working.webp';
                                                    }else if($trimVal == 'shopping'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/shopping.webp';
                                                    }else if($trimVal == 'skiing'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/skiing.webp';
                                                    }else if($trimVal == 'baking'){
                                                        $imgg = 'https://wordpress-905721-3396462.cloudwaysapps.com/wp-content/themes/divi-child/assets/images/baking.webp';
                                                    }
                                                    echo '<div class="recommend-popup-interests-option-card">';
                                                    echo '<input type="checkbox" class="recommend-popup-always-hide-input" id='. $trimVal . ' name="interests" value='.$trimVal.'>';
                                                    echo "<label for='$trimVal'>";
                                                    echo '<div class="recommend-popup-interests-image-wrapper">';
                                                    echo '<img src="'.$imgg.'"/>';
                                                    echo '<span class="popup-interests-selection-checkmark"><i class="fa-solid fa-check"></i></span>';
                                                    // echo '<img class="popup-interests-selection-checkmark" src="'.site_url().'/wp-content/themes/divi-child/assets/images/check.webp"/>';
                                                    echo '</div>';
                                                    echo '<p class="recommend-musthave-option-title">'. $value .'</p>';
                                                    echo '</label>';
                                                    echo '</div>';
                                                }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- popup global nav bottom -->
                                    <div class="recommend-popup-global-bottom recommend-popup-choosevehicles-bottom">
                                        <div class="recommend-vehicles-popup-selection-info">
                                            <p class="recommend-vehicles-choosevehicles-counter-text">
                                                you selected <span
                                                    class="recommend-interests-choosevehicles-counter">0</span> items
                                            </p>
                                        </div>
                                        <div
                                            class="recommend-vehicles-popup-global-nav recommend-vehicles-choosevehicles-nav">
                                            <button
                                                class="interests-selections-reset-btn custom-mobile-hide" style="background:transparent; color: #1f4a81;">reset</button>
                                            <button
                                                class="recommend-vehicles-global-popup-nav--back popup-content-global-nav-btns popup-introduction-next-step-trigger interestsbackbtn" data-nav-type='back'
                                                data-target=1>back</button>
                                            <button
                                                class="musthaveTrigger recommend-vehicles-global-popup-nav--next popup-content-global-nav-btns recommend-vehicles-interests-next-btn" data-nav-type='next'
                                                data-target=3 disabled>next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- interest tab ended -->
                            <!-- must have tab started -->
                            <div class="recommend-vehicles-popup-selection-content position_relative" data-id=3>
                                <div
                                    class="recommend-vehicles-musthaves-inner-container recommend-vehicles-global-inner-container">
                                    <div class="recommend-vehicles-popup-musthaves-content">
                                        <div class="recommend-vehicles-musthaves-top recommend-vehicles-global-top">
                                            <h2 class="popup-musthaves-content-heading-text">Select all that apply</h2>
                                            <div class="add-feature-container">
                                                <p class="add-feature-popup-trigger cursor-pointer musthave-popup-triggers">I do not see the features I want</p>
                                            </div>
                                        </div>
                                        <div
                                            class="recommend-vehicles-musthaves-content recommend-vehicles-global-content">
                                            <div class="terms recommend-popup-vehicle-musthaves-wrapper">
                                            <p>No musthaves found, please go back to first tab and select a vehicle model first</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- popup global nav bottom -->
                                    <div class="recommend-popup-global-bottom recommend-popup-choosevehicles-bottom">
                                        <div class="recommend-vehicles-popup-selection-info">
                                            <p class="recommend-vehicles-choosevehicles-counter-text">
                                                you selected <span
                                                    class="recommend-musthave-choosevehicles-counter">0</span> items
                                            </p>
                                        </div>
                                        <div
                                            class="recommend-vehicles-popup-global-nav recommend-vehicles-choosevehicles-nav">
                                            <button
                                                class="custom-mobile-hide  must-have-selections-reset-btn"  style="background:transparent; color: #1f4a81;">reset</button>
                                            <button
                                                class="recommend-vehicles-global-popup-nav--back popup-content-global-nav-btns musthavebackbtn" data-nav-type='back'
                                                data-target=2>back</button>
                                            <button
                                                class="resultstrigger recommend-vehicles-global-popup-nav--next popup-content-global-nav-btns recommend-vehicles-musthave-next-btn" data-nav-type='next'
                                                data-target=4 disabled>next</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- add feature popup started -->
                                <div class="add-feature-popup position_absolute w-100 h_100 bg_white d_flex d_flex__align-center d_flex__justify-center">
                                    <span class="add-feature-popup-close position_absolute global_popup_wrapper_close border_circle d_flex d_flex__align-center d_flex__justify-center cursor-pointer">
                                        <i class="fa fa-times"></i>
                                    </span>
                                    <div class="add-feature-popup-container">
                                    <h2 class="popup-notfound-content-main-heading p_0">Not seeing the feature you wanted?</h2>
                                    <p class="add-feature-note inline-30 font_segoe color_black text_center">Please add multiple features comma seperated</p>
                                    <form action="#" method="post" class="add-feature-form d_flex" autocomplete="off">
                                        <input type="text" name="add-feature-input" class="add-feature-input font_segoe" autocomplete="off">
                                        <input type="submit" value="Add Features" class="add-feature-submit color_white font_helvetica text_uppercase">
                                    </form>
                                    </div>
                                </div>
                                <!-- add feature popup ended -->
                            </div>
                            <!-- must have tab ended -->
                            <!-- recommendations tab started -->
                            <div class="recommend-vehicles-popup-selection-content" data-id=4>
                                <div
                                    class="recommend-vehicles-recommendations-inner-container recommend-vehicles-global-inner-container">
                                    <div class="recommend-vehicles-popup-recommendations-content">
                                        <div
                                            class="recommend-vehicles-recommendations-top recommend-vehicles-global-top"  style="column-gap: 10px;">
                                            <div class="popup-recommendations-header">
                                                <h2 class="popup-recommendations-content-heading-text">Here Are Your Personalized Car Recommendations</h2>
                                                <p class="popup-disclaimer m_0" style="color:#5a5a5a;">Disclaimer: Recommendations are based on current inventory</p>
                                            </div>
                                            <div class="popup-edit-current-section">
                                                <a class="popup-edit-current-section-text popup-content-reset-results-btn" data-target=0>
                                                    reset results
                                                </a>
                                            </div>
                                        </div>
                                        <div
                                            class="recommend-vehicles-recommendations-content recommend-vehicles-global-content">
                                            <div class="recommend-popup-vehicle-recommendations-wrapper">
                                                <div class="recommend-popup-recommendations-results-wrapper">
                                                    <div class="recommendations-results-listings-wrapper getresult">
                                                    </div>
                                                        <!-- ended div -->
                                                    </div>
                                                    <!-- do not see section started -->
                                                    <div class="recommendations-results-donot-find-wrapper row flex-column flex-md-row align-items-center mt-5 justify-content-between">
                                                        <div class="recommendations-results-donot-form start-conversation-form col-12 col-md-8">
                                                            <?php echo do_shortcode('[contact-form-7 id="b5c16ab" title="Start Our Conversation"]'); ?>
                                                        </div>
                                                        <div class="recommendation-results-donotsee-link col-12 col-md-4">
                                                            <a class="musthave-popup-triggers recommend-vehicles-global-start-conversation popup-content-global-nav-btns" data-target=5>i do not see what i am looking for</a>
                                                        </div>
                                                    </div>
                                                    <!-- do not see section ended -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- popup global nav bottom -->
                                        <div
                                            class="recommend-popup-global-bottom recommend-popup-choosevehicles-bottom">
                                            <div class="recommend-vehicles-popup-selection-info">

                                            </div>
                                            <div
                                                class="recommend-vehicles-popup-global-nav recommend-vehicles-results-nav">
                                                <button
                                                class="recommend-vehicles-global-popup-nav--back popup-content-global-nav-btns" data-nav-type='back'
                                                data-target=3>back</button>
                                                <button type="submit" form="donot-form" class="start-conversation" > start our
                                                    conversation</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- recommendations tab ended -->

                                <!-- i do not see what i am looking for tab content started -->
                                <div class="recommend-vehicles-popup-selection-content" data-id=5>
                                    <div
                                        class="recommend-vehicles-notfound-inner-container recommend-vehicles-global-inner-container">
                                        <div class="recommend-vehicles-popup-notfounf-content">
                                            <div class="my-30">
                                                <h2 class="font-weight-bold text-grey-3 font-xxl pb-0 mb-0 font-segoe">Not seeing the vehicle you wanted? Let our professional Sales team help you out!</h2>
                                            </div>
                                            <div class="global-form-wrapper">
                                                <div class="global-form-form">
                                                    <?php echo do_shortcode('[contact-form-7 id="14512" title="I do not see contextual form"]'); ?>
                                                </div>
                                                <div class="global-form-success d_none">
                                                    <h3 class="color_white font_bold font_helvetica w-75 pb-0">Ask a question</h3>
                                                    <div class="d-flex justify-content-center">
                                                        <img src="<?php echo site_url(); ?>/wp-content/themes/divi-child/assets/images/form-success.png" alt="Form submitted">
                                                    </div>
                                                    <h3 class="text-capitalize font-segoe font-weight-bold text-center">Your message has been sent!</h3>
                                                    <p class="sidebar__success-desc text-center">Thank you for your message. A representative will contact you soon.</p>
                                                    <div class="sidebar__ctas">
                                                        <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/schedule-express-service-durango-co">Schedule Service</a>
                                                        <a class="text_uppercase" href="<?php echo site_url(); ?>/inventory">View Inventory</a>
                                                        <a class="text_uppercase" href="<?php echo site_url(); ?>/service-and-parts/auto-parts-durango-co">Call Service & Parts</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- popup global nav bottom -->
                                        <div class="recommend-popup-global-bottom recommend-popup-choosevehicles-bottom d_flex__justify-end">
                                            <div class="recommend-vehicles-popup-global-nav recommend-vehicles-choosevehicles-nav">
                                                <button class="recommend-vehicles-global-popup-nav--next donotsee-form-submit-btn">submit request</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- i do not see what i am looking for tab content ended -->

                            </div>
                            <!-- popup selection container ended -->
                        </div>
                    </div>

                    <!-- recommend vehicles popup ended -->
                    <!-- media gallery videos popup started -->

                    <div class="media-gallery-popup-wrapper">
                        <div class="media-gallery-popup-overlay"></div>
                        <div class="media-gallery-popup-content-container">
                            <div class="media-gallery-popup-close-icon-container">
                                <span class="media-gallery-popup-close-icon">
                                    <i class="fa fa-times"></i>
                                </span>
                            </div>
                            <div class="media-gallery-popup-video-container">
                                <iframe class="media-gallery-dynamic-iframe" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                    <!-- media gallery videos popup ended -->
                    <!-- sticky lead form popup started -->
                        <div class="contact-fancy-box">
                            <div class="contact-fancy-box-overlay"></div>
                            <div class="contact-fancy-box-content">
                                <div class="contact-fancy-box-header">
                                    <p class="contact-fancy-box-selected-option">
                                        inquiried text here
                                    </p>
                                    <span class="contact-fancy-box-close-icon">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </div>
                                
                                <!-- fancy box form started -->
                                <div class="contact-fancy-box-form-wrapper">
                                    <?php echo do_shortcode('[contact-form-7 id="25609" title="Check For Availability"]'); ?>
                                </div>
                            </div>
                        </div>

                    <!-- photo gallery popup started -->
                    <div class="photo-gallery-popup-wrapper">
                        <div class="photo-gallery-popup-overlay"></div>
                        <div class="photo-gallery-popup-content position-relative"></div>
                    </div>
    <?php echo divi_child_stickersPopup(); ?>
    <!-- VDP Vehicles Compare Popup -->
    <div class="vdp-vehicles-compare">
        
    </div>
        </article>
        <?php echo sidebarForm('sticky-cta', $popupImage, 'Apply for financing vehicle', $vehicleTitle, $Automotive_Listing->{'_listing_term_stock-number'},$mainPrice, 'Apply for financing', '[contact-form-7 id="25607" title="Apply for financing"]' );
        echo sidebarForm('guest-request-text', $popupImage, 'Guest Request Text', $vehicleTitle, $vehicleStock,$mainPrice, 'Guest Request Text', '[contact-form-7 id="8266eb2" title="Guest Request Text"]' );
         echo '<div class="lightbox-slider-wrapper lightbox-slider-hidden position-fixed w-100">';
       echo '<div class="lightbox-slider-overlay position-absolute w-100 h-100"></div>';
       echo '<div class="lightbox-slider-innerwrapper w-100 h-100">';
       echo '<span class="text-white close-slider-lightbox d-flex align-items-center justify-content-center cursor-pointer position-absolute"><i class="fa fa-times"></i></span>';
        echo vehicleLightboxSlider($Automotive_Listing->get_gallery_images(false, 'full'),
        $Automotive_Listing->get_gallery_images(false, 'auto_thumb'),
        $Automotive_Listing->get_gallery_images(false, 'auto_slider'),
        $Automotive_Plugin->get_automotive_image_sizes('auto_thumb'),
        $Automotive_Plugin->get_automotive_image_sizes());
        echo '</div></div>'; ?>
<?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
<script>
   
$(document).ready(function() {
    $('.photo-gallery-popup-trigger').click(function(){
        $('.photo-gallery-popup-content').slick('refresh')
        $('.photo-gallery-popup-wrapper').css('display','flex')
        $('body').addClass('overflow-hidden')
    })
    $('.photo-gallery-popup-overlay').click(function(){
        $('.photo-gallery-popup-wrapper').css('display','none')
        $('body').removeClass('overflow-hidden')
    })
    // popup functons
    // 		beyond value single listing popup trigger
    let recommendVehiclesPopupTrigger = $('.recommend-vehicles-popup-trigger');
    let recommendVehiclesPopupWrapper = $('.recommend-vehicles-popup-wrapper')
    let recommendVehiclesPopupCloseIcon = $('.recommend-vehicles-popup-close')
    let recommendVehiclesOverlay = $('.recommend-vehicles-popup-close-outside')

    $(recommendVehiclesPopupTrigger).click(function() {
        $(recommendVehiclesPopupWrapper).css('display', 'flex')  
        $('body').addClass('overflow-hidden') 
        $('.recommend-vehicles-popup-selection-tabs .slick-dots li:first-child').click()
        let dataVal = jQuery(this).attr('data-target');
        let recommendPopupTabs = jQuery('.recommend-vehicles-popup-selection-tab');
        let recommendPopupContentContainer = jQuery('.recommend-vehicles-popup-selection-content');
    
        jQuery(recommendPopupTabs).removeClass('recommend-tab-active')
        jQuery(recommendPopupContentContainer).removeClass('recommend-content-active');
        let recommendPopupTabActive = jQuery(recommendPopupTabs)[dataVal]
        jQuery(recommendPopupTabActive).addClass('recommend-tab-active')
        let popUpContentcontainerActive = jQuery(recommendPopupContentContainer)[dataVal]
        jQuery(popUpContentcontainerActive).addClass('recommend-content-active')

        // reset the interests values
        jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').removeClass('active');
        let interestsCheckboxes =  jQuery('.recommend-popup-interests-option-card').find('input').prop('checked', false).triggerHandler('click');
        jQuery('.popup-interests-selection-checkmark').css('display','none')
        jQuery('.recommend-vehicles-interests-next-btn').attr('disabled',true)
        let interestsNumItems = jQuery('.recommend-popup-vehicle-interests-wrapper .active').length;
        jQuery('.recommend-interests-choosevehicles-counter').html(interestsNumItems);
        jQuery('.recommend-popup-interests-option-card label').css('pointer-events', 'all');
        // reset the must haves values
        jQuery('.recommend-popup-musthave-option-card label').removeClass('remove');
        let musthaveCheckboxes =  jQuery('.recommend-popup-musthave-option-card').find('input').prop('checked', false).triggerHandler('click');
        jQuery('.recommend-vehicles-musthave-next-btn').attr('disabled',true)
        let musthaveNumItems = jQuery('.recommend-popup-vehicle-musthaves-wrapper .remove').length;
        jQuery('.recommend-musthave-choosevehicles-counter').html(musthaveNumItems);
        jQuery('.recommend-popup-musthave-option-card label').css('pointer-events', 'all');
        // // reset the type of vehicle values
        jQuery('.recommend-popup-vehicle-option-card label').removeClass('active')
        let typeNumItems = jQuery('.recommend-popup-vehicle-options-wrapper .active').length;
        let typeofCheckboxes =  jQuery('.recommend-popup-vehicle-option-card').find('input').prop('checked', false).triggerHandler('click');
        jQuery('.recommend-vehicles-choosevehicles-counter').html(typeNumItems);
        jQuery('.recommend-popup-vehicle-option-card label').css('pointer-events', 'all');
        jQuery('.recommend-vehicles-option-card-next-btn').attr('disabled',true)
        
    })
    $(recommendVehiclesPopupCloseIcon).click(function() {
        $(recommendVehiclesPopupWrapper).css('display', 'none')
        $('.unique-vehicle-type__popup').css('transform', 'scale(0)');
        $('body').removeClass('overflow-hidden') 
    })
    $(recommendVehiclesOverlay).click(function() {
        $(recommendVehiclesPopupWrapper).css('display', 'none')
        $('.unique-vehicle-type__popup').css('transform', 'scale(0)');
        $('body').removeClass('overflow-hidden') 
    })

    // Choose vehicle selections
    jQuery('.recommend-popup-vehicle-option-card label').click(function() {
        // reset the musthaves values if any selected
        jQuery('.recommend-popup-musthave-option-card label').removeClass('remove');
        let x =  jQuery('.recommend-popup-musthave-option-card').find('input').prop('checked', false).triggerHandler('click');
        jQuery('.recommend-vehicles-musthave-next-btn').attr('disabled',true)
        var numItems = jQuery('.recommend-popup-vehicle-musthaves-wrapper .remove').length;
        jQuery('.recommend-musthave-choosevehicles-counter').html(numItems);      
        let tar = jQuery(this)
        if (tar.hasClass('active')) {
            jQuery(this).find('input').prop('checked', false).triggerHandler('click');
            tar.removeClass('active')
            var numItems = jQuery('.recommend-popup-vehicle-options-wrapper .active').length;
            if (numItems == 1 || numItems > 1) {
                jQuery('.recommend-popup-vehicle-option-card label').css('pointer-events', 'none');
                jQuery('.recommend-popup-vehicle-option-card label.active').css('pointer-events',
                'all');
                jQuery('.recommend-vehicles-option-card-next-btn').removeAttr('disabled')
            } else {
                jQuery('.recommend-popup-vehicle-option-card label').css('pointer-events', 'all');
                jQuery('.recommend-vehicles-option-card-next-btn').attr('disabled',true)
            }
            jQuery('.recommend-vehicles-choosevehicles-counter').html(numItems);
            // add the hover class in card
            jQuery('.recommend-popup-vehicle-option-card').addClass('option-card-hover');
        } else {
            // trigger unique vehicle popup if clicked on hybrid and electric
            if(jQuery(this).prev().val() == 'electric' || jQuery(this).prev().val() == 'hybrid') {
                let uniqueVehiclePopup = $('.unique-vehicle-type__popup');
                jQuery(uniqueVehiclePopup).css('transform', 'scale(1)');
                return false;
            }
            jQuery(this).addClass('active');
            jQuery(this).find('input').prop('checked', true).triggerHandler('click');
            var numItems = jQuery('.recommend-popup-vehicle-options-wrapper .active').length;
            if (numItems == 1 || numItems > 1) {
                jQuery('.recommend-popup-vehicle-option-card label').css('pointer-events', 'none');
                jQuery('.recommend-popup-vehicle-option-card label.active').css('pointer-events',
                'all');
                jQuery('.recommend-vehicles-option-card-next-btn').removeAttr('disabled')
            } else {
                jQuery('.recommend-popup-vehicle-option-card label').css('pointer-events', 'all');
                jQuery('.recommend-vehicles-option-card-next-btn').attr('disabled',true)
            }
                jQuery('.recommend-vehicles-choosevehicles-counter').html(numItems);
                    // remove the hover class in cards
                jQuery('.recommend-popup-vehicle-option-card').removeClass('option-card-hover');
        }
    });
    // type of vehicle option card reset button
    $('.type-option-selections-reset-btn').click(function(){
            jQuery('.recommend-popup-vehicle-option-card label').removeClass('active')
            jQuery('.recommend-popup-vehicle-option-card').addClass('option-card-hover');
            let typeNumItems = jQuery('.recommend-popup-vehicle-options-wrapper .active').length;
            let typeofCheckboxes =  jQuery('.recommend-popup-vehicle-option-card').find('input').prop('checked', false).triggerHandler('click');
            jQuery('.recommend-vehicles-choosevehicles-counter').html(typeNumItems);
            jQuery('.recommend-popup-vehicle-option-card label').css('pointer-events', 'all');
            jQuery('.recommend-vehicles-option-card-next-btn').attr('disabled',true);
    })
    jQuery('.interests-selections-reset-btn').click(function(){
            jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').removeClass('active');
            let x =  jQuery('.recommend-popup-interests-option-card').find('input').prop('checked', false).triggerHandler('click');
            jQuery('.popup-interests-selection-checkmark').css('display','none')
            jQuery('.recommend-vehicles-interests-next-btn').attr('disabled',true)
            var numItems = jQuery('.recommend-popup-vehicle-interests-wrapper .active').length;
            jQuery('.recommend-interests-choosevehicles-counter').html(numItems);
            jQuery('.recommend-popup-interests-option-card label').css('pointer-events', 'all');
    })
    jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').click(function() {
            let tar = jQuery(this)
            if (tar.hasClass('active')) {
                jQuery(this).removeClass('active');
                jQuery(this).find('.popup-interests-selection-checkmark').css('display','none')
                jQuery(this).find('input').prop('checked', false).triggerHandler('click');
                var numItems = jQuery('.recommend-popup-vehicle-interests-wrapper .active').length;
                // show the next button if 3 items selected else hide it
                if(numItems >= 3){
                    jQuery('.recommend-vehicles-interests-next-btn').removeAttr('disabled')
                }else{
                    jQuery('.recommend-vehicles-interests-next-btn').attr('disabled',true)
                }
                // make click active and deactive on other boxes
                if (numItems == 5 || numItems > 5) {
                    jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').css('pointer-events', 'none');
                    jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper.active').css('pointer-events',
                        'all');
                } else {
                    jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').css('pointer-events', 'all');
                }
                jQuery('.recommend-interests-choosevehicles-counter').html(numItems);
            } else {
                jQuery(tar).addClass('active');
                jQuery(this).find('.popup-interests-selection-checkmark').css('display','inline-flex')
                jQuery(this).find('input').prop('checked', true).triggerHandler('click');
                var numItems = jQuery('.recommend-popup-vehicle-interests-wrapper .active').length;
                // show the next button if 3 items are selected otherwise hide
                if(numItems >= 3){
                    jQuery('.recommend-vehicles-interests-next-btn').removeAttr('disabled')
                }else{
                    jQuery('.recommend-vehicles-interests-next-btn').attr('disabled',true)
                }
                // make click active and deactive on other boxes
                if (numItems == 5 || numItems > 5) {
                    jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').css('pointer-events', 'none');
                    jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper.active').css('pointer-events','all');
                } else {
                    jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').css('pointer-events', 'all');
                }
                jQuery('.recommend-interests-choosevehicles-counter').html(numItems);
            }
        });
        jQuery('.must-have-selections-reset-btn').click(function(){
            jQuery('.recommend-popup-musthave-option-card label').removeClass('remove');
            let x =  jQuery('.recommend-popup-musthave-option-card').find('input').prop('checked', false).triggerHandler('click');
            jQuery('.recommend-vehicles-musthave-next-btn').attr('disabled',true)
            var numItems = jQuery('.recommend-popup-vehicle-musthaves-wrapper .remove').length;
            jQuery('.recommend-musthave-choosevehicles-counter').html(numItems);
        })
        // must have input function started
    jQuery(document).on('click', '.recommend-popup-musthaves-input', function() {
        let tar = $(this).next();
        let tarInput = $(this)
        if (tar.hasClass('remove') && tarInput.hasClass('input-remove') ) {
            jQuery(tar).removeClass('remove');
            jQuery(tarInput).removeClass('input-remove')
            // jQuery(this).prop('checked', false).triggerHandler('click');
            var numItems = jQuery('.recommend-popup-vehicle-musthaves-wrapper .remove').length;
            if (numItems >= 1) {
                jQuery('.recommend-vehicles-musthave-next-btn').removeAttr('disabled')
            } else {
                jQuery('.recommend-vehicles-musthave-next-btn').attr('disabled',true)
            }
            jQuery('.recommend-musthave-choosevehicles-counter').html(numItems);
        } else {
            jQuery(tar).addClass('remove');
            jQuery(tarInput).addClass('input-remove')
            // jQuery(this).prop('checked', true).triggerHandler('click');
            var numItems = jQuery('.recommend-popup-vehicle-musthaves-wrapper .remove').length;
            if(numItems >= 1) {
                jQuery('.recommend-vehicles-musthave-next-btn').removeAttr('disabled')
            }else{
                jQuery('.recommend-vehicles-musthave-next-btn').attr('disabled',true)
            }
            jQuery('.recommend-musthave-choosevehicles-counter').html(numItems);
        }
    })
    // must have input function ended
    jQuery('.popup-content-reset-results-btn').click(function(){
        // go back to first tab
        let dataVal = jQuery(this).attr('data-target');
        let recommendPopupTabs = jQuery('.recommend-vehicles-popup-selection-tab');
        let recommendPopupContentContainer = jQuery('.recommend-vehicles-popup-selection-content');
        // interests variables
        
        jQuery(recommendPopupTabs).removeClass('recommend-tab-active')
        jQuery(recommendPopupContentContainer).removeClass('recommend-content-active');
        let recommendPopupTabActive = jQuery(recommendPopupTabs)[dataVal]
        jQuery(recommendPopupTabActive).addClass('recommend-tab-active')
        let popUpContentcontainerActive = jQuery(recommendPopupContentContainer)[dataVal]
        jQuery(popUpContentcontainerActive).addClass('recommend-content-active')
        
        // reset the interests values
        jQuery('.recommend-popup-interests-option-card label .recommend-popup-interests-image-wrapper').removeClass('active');
        let interestsCheckboxes =  jQuery('.recommend-popup-interests-option-card').find('input').prop('checked', false).triggerHandler('click');
        jQuery('.popup-interests-selection-checkmark').css('display','none')
        jQuery('.recommend-vehicles-interests-next-btn').attr('disabled',true)
        let interestsNumItems = jQuery('.recommend-popup-vehicle-interests-wrapper .active').length;
        jQuery('.recommend-interests-choosevehicles-counter').html(interestsNumItems);
        jQuery('.recommend-popup-interests-option-card label').css('pointer-events', 'all');

        // reset the must haves values
        jQuery('.recommend-popup-musthave-option-card label').removeClass('remove');
        let musthaveCheckboxes =  jQuery('.recommend-popup-musthave-option-card').find('input').prop('checked', false).triggerHandler('click');
        jQuery('.recommend-vehicles-musthave-next-btn').attr('disabled',true)
        let musthaveNumItems = jQuery('.recommend-popup-vehicle-musthaves-wrapper .remove').length;
        jQuery('.recommend-musthave-choosevehicles-counter').html(musthaveNumItems);
        jQuery('.recommend-popup-musthave-option-card label').css('pointer-events', 'all');
        // // reset the type of vehicle values
        jQuery('.recommend-popup-vehicle-option-card label').removeClass('active')
        let typeNumItems = jQuery('.recommend-popup-vehicle-options-wrapper .active').length;
        let typeofCheckboxes =  jQuery('.recommend-popup-vehicle-option-card').find('input').prop('checked', false).triggerHandler('click');
        jQuery('.recommend-vehicles-choosevehicles-counter').html(typeNumItems);
        jQuery('.recommend-popup-vehicle-option-card label').css('pointer-events', 'all');
        jQuery('.recommend-vehicles-option-card-next-btn').attr('disabled',true)
        // add the hover class in card
        jQuery('.recommend-popup-vehicle-option-card').addClass('option-card-hover');
    })
    var cpaged = 2;
    let listingOfferOption = $('.listing-offer-option-link');
    let listingCustomOption = $('.listing-custom-option-link');
    // load more posts button event ended
    function validateContextualData(e) {
        var eventTrigger = e.type;
        if( eventTrigger != 'click' ) {
            e.preventDefault();
        }
        var vahicle = new Array();
        var interests = new Array();
        var musthave = new Array();
        
        jQuery("input[name='vahicle']:checked").each(function() {
            let x = jQuery(this).val().toLowerCase()
            if (x == "cargo-van") {
                    x = "cargo van"
                }
            vahicle.push(x);
        });
        jQuery("input[name='interests']:checked").each(function() {
            interests.push(jQuery(this).val());
        });
        jQuery("input[name='musthave']:checked").each(function() {
            musthave.push(jQuery(this).val());
        });
        jQuery('.getresult').html('Loading.....'); 
        
        $.ajax({
            method: "post",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data : {
               'vahicles': vahicle.join(","),
               'interests': interests.join(", "),
               'musthave': musthave.join(", "),
                action: 'get_result_val',
            },
            success : function(res){
                if( eventTrigger == 'submit' ) {
                    $('.start-conversation').text('Thankyou, Inquire received');
                    setTimeout(() => {
                        $('.start-conversation').text('start our conversation');
                    }, 1500);
                }
                let response = jQuery.parseJSON(res);
                if(response.recommendationHTML !== '') {
                    jQuery('.getresult').html(response.recommendationHTML);
                }
                if(response.vehicleTitle !== '') {
                    jQuery('.BVDP-vehicle-title').val(response.vehicleTitle)
                }
                if(response.vehiclePrice !== '') {
                    jQuery('.BVDP-vehicle-price').val(response.vehiclePrice)
                }
                jQuery('.BVDP-vehicle-type').val(vahicle.join(","))
                jQuery('.BVDP-vehicle-interests').val(interests.join(","))
                jQuery('.BVDP-vehicle-musthaves').val(musthave.join(","))
            },
            error: function(error) {
                alert('Something went wrong please try again')
            } 
        })
    }
    jQuery('[data-target="4"]').click((e) => validateContextualData(e));
    jQuery('.start-conversation').click(function(e) {
        $('.start-conversation-submit-hidden').click()
    })
    // recommend listing card remove button event started
    var recommendationCardPage = 4 ;
    $(document).on('click','.remove-recommendation-listing-card', function(){
        let currentClicked = $(this);
        $(currentClicked).parent().remove();
        jQuery('.remove-recommendation-listing-card').css('pointer-events','none')
        var recommendationListingsWrapper = $('.recommendations-results-listings-wrapper')
        var vahicle = new Array();
        var interests = new Array();
        var musthave = new Array();
        jQuery("input[name='vahicle']:checked").each(function() {
            vahicle.push(jQuery(this).val());
        });
        jQuery("input[name='interests']:checked").each(function() {
            interests.push(jQuery(this).val());
        });
        jQuery("input[name='musthave']:checked").each(function() {
            musthave.push(jQuery(this).val());
        });

        $.ajax({
            type : 'POST',
            dataType : 'html',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data : {
                'vahicles': vahicle.join(","),
                'interests': interests.join(", "),
                'musthave': musthave.join(", "),
                'recommendationPage' : recommendationCardPage ,
                'action' : 'get_new_recommendation'
            },
            success : function(result){
                $(recommendationListingsWrapper).append(result);
                recommendationCardPage = recommendationCardPage + 1;
                jQuery('.remove-recommendation-listing-card').css('pointer-events','all')
            },
            error : function(error) {
                jQuery('.remove-recommendation-listing-card').css('pointer-events','all')
            }
        })
    })
// get contextual activity data and save it

let backToIntroduction = $('.backto-introduction');
let chooseVehicleTrigger = $('.popup-introduction-next-step-trigger');
$('.interestsTrigger').click(function() {
    let vahicle = []
    jQuery("input[name='vahicle']:checked").each(function() {
        let x = jQuery(this).val().toLowerCase()
        if (x == 'sedan') {
            x = 'coupe'
        }
        vahicle.push(x);
    });
    let chooseVehicleSelection = vahicle;
    localStorage.setItem('chooseVehicleselection', JSON.stringify(chooseVehicleSelection) )
    $('.recommend-popup-vehicle-musthaves-wrapper').html('loading options please wait...')
    $.ajax({
        type: 'POST',
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        data: {
            'vehicle' : vahicle,
            'action': 'get_selected_musthaves'
        },
        success: function(result) {
            let res = jQuery.parseJSON(result);
            if (typeof res.featureList !== 'undefined' && res.featureList !== '') {
                let featureListArr = res.featureList.split(',');
                $('.recommend-popup-vehicle-musthaves-wrapper').empty()
                $(featureListArr).each(function (index, data) {
                    let featureHTML = `
                        <div class="recommend-popup-musthave-option-card">
                            <input class="recommend-popup-musthaves-input" type="checkbox" id="${data.replace(/ /g, '-')}" name="musthave" value="${data}">
                            <label for="${data.replace(/ /g, '-')}">
                                <p class="recommend-musthave-option-title">${data}</p>
                            </label>
                        </div>`;
                    $('.recommend-popup-vehicle-musthaves-wrapper').append(featureHTML);
                });
            }
        },
        error: function() {
            alert("Error in get Record");
            $('.recommend-popup-vehicle-musthaves-wrapper').html('Please close the popup and try to select the vehicle again')
        }
    })
    })
    $('.musthaveTrigger').click(function() {
        let interests = []
        jQuery("input[name='interests']:checked").each(function() {
            interests.push(jQuery(this).val());
        });
        let interestsSelection = interests;
        localStorage.setItem('interestsselection', JSON.stringify(interestsSelection) )
    })
    
    // clear localstorage when user close the popup
    $('.recommend-vehicles-popup-overlay').click(clearStorage) 
    $('.recommend-vehicles-popup-close').click(clearStorage) 
    function clearStorage() {
        localStorage.clear();
        document.cookie = "useremail=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        // add the hover class in card
        jQuery('.recommend-popup-vehicle-option-card').addClass('option-card-hover');
    }


    // generate dynamic model values based on make value in do not see form
    $('.vehicle-makes-selection').change(function(e) {
            let selectedValue = $(e.target).val();
            let modelCont = $('.vehicle-models-selection')
            $(modelCont).css({pointerEvents: 'none', opacity: .5})
            $.ajax({
                method: "post",
                url : '<?php echo site_url(); ?>/wp-admin/admin-ajax.php',
                data : {
                    'selectedvalue' : selectedValue,
                    'action' : 'dynamic_models'
                },
       					success: function(res) {
						console.log("AJAX response:", res); // Debugging output
						modelCont.empty().append(res);
						modelCont.css({ pointerEvents: 'all', opacity: 1 });
						modelCont.trigger('change');
					},
						error : function(error) {
                        console.log('error', error)
                    }
                })
        })
        $('.donotsee-form-submit-btn').click(function() {
            $('#donot-form-submit').click()
        })
// add feature functionality
let addFeaturePopup = $('.add-feature-popup');
        let addFeatureForm = $('.add-feature-form');
        let addFeatureInput = $('.add-feature-input');
        let addFeatureClose = $('.add-feature-popup-close');
        let addFeatureTrigger = $('.add-feature-popup-trigger');
        $(addFeatureTrigger).click(function() {
            $(addFeaturePopup).css('transform', 'scale(1)');
        })
        $(addFeatureClose).click(function() {
            $(addFeaturePopup).css('transform', 'scale(0)');
        })
        $(addFeatureForm).submit(function(e) {
        e.preventDefault() ;
        let featureVal = $(addFeatureInput).val().trim()
        if( featureVal == '' ) {
            alert('You need to add a feature to continue');
            return false;
        }else{
            featureVal = $(addFeatureInput).val().trim().replace(/</g, "&lt;").replace(/>/g, "&gt;");
            let featuresGroup = featureVal.split(',') ;
			function escapeHtml(str) {
				return str.replace(/[&<>"'`=\/]/g, "");
			}
            
            $(featuresGroup).each(function(index, data) {
				 let sanitizedData =  escapeHtml(data);
				
                let html = `<div class="recommend-popup-musthave-option-card"><input class="recommend-popup-musthaves-input" type="checkbox" id="${sanitizedData}" name="musthave" value="${sanitizedData}"><label for="${sanitizedData}" style="pointer-events: all;"><p class="recommend-musthave-option-title">${sanitizedData}</p></label></div>` ;
                $('.recommend-popup-vehicle-musthaves-wrapper').prepend(html)
            })
            $( addFeatureInput ).val('')
            $(addFeaturePopup).css('transform', 'scale(0)');

        }
    })
// open the contextual popup if user is coming from homepage popup
let showPopup = <?php
        if(isset($showPopup)) {
            echo $showPopup ;
        }else{
            echo 'false';
        }; ?> ;
    if( showPopup == true || showPopup == 'true' ) {
        $('.recommend-vehicles-popup-trigger').click()
    }

    // Ajax call to load media gallery cards
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>', // Path to your ajax-handler.php file
        type: 'POST',
        data: {
            'activePostModel' : '<?php echo $activePostExplode[1]; ?>',
            'action': 'load_media_gallery_data'
        },
        success: function(response) {
            $('.beyond-media-gallery').html(response); // Example: Update a container element with the response HTML
            $('.beyond-media-gallery').slick({
                slidesToShow: 3,
                centerPadding: '30',
                arrows: true,
                // autoplay: true,
                // autoplaySpeed: 7000,
                pauseOnHover: false,
                prevArrow: "<button type='button' class='slick-prev pull-left'><img src='/wp-content/themes/divi-child/assets/images/slick-prev.webp' /></button>",
                nextArrow: "<button type='button' class='slick-next pull-right'><img src='/wp-content/themes/divi-child/assets/images/slick-next.webp' /></button>",
                responsive: [{
                        breakpoint: 992,
                        settings: {
                            arrows: true,
                            centerMode: true,
                            centerPadding: '20',
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            arrows: false,
                            dots: true,
                            centerMode: true,
                            centerPadding: '15',
                            slidesToShow: 1
                        }
                    }
                ]
            })
        },
        error: function(xhr, status, error) {
            // Handle the error
            console.log(error);
        }
    });
     // Ajax call to load photo gallery
     $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>', // Path to your ajax-handler.php file
        type: 'POST',
        data: {
            'activePostModel' : '<?php echo $activePostExplode[1]; ?>',
            'action': 'load_photo_gallery_data'
        },
        success: function(response) {
            $('.photo-gallery-popup-content').html(response); // Example: Update a container element with the response HTML
            $('.photo-gallery-popup-content').slick({
                arrows: true,
                prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa fa-play'></i></button>",
                nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa fa-play'></i></button>"
            });
        },
        error: function(xhr, status, error) {
            // Handle the error
            console.log(error);
        }
    });
});
</script>