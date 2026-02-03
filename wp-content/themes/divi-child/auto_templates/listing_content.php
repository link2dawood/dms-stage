<?php
/*
	Automotive Listing Content Template File
	To overwrite this file copy it to automotive-child/auto_templates/listing_content.php

	Version: 18.0
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
global $post;

$Automotive_Plugin   = Automotive_Plugin();
// get the current single listing
$Automotive_Listing  = new Automotive_Listing($post->ID);
// check if url have view images parameter or not
if( isset($_GET['view_images']) ) {
    $view_images = $_GET['view_images'];
}

$main_image = $Automotive_Listing->get_main_image(false);
$mainPrice = $Automotive_Plugin->format_currency( $Automotive_Listing->get_price(), true );
if( empty($mainPrice) || $mainPrice == 'None' || !isset($mainPrice)) {
    $mainPrice = '<a href="tel:'. get_field('quick_call_phone_number', 'options') .'" class="quick-call-link text-sixth"><i class="fa fa-phone text-sixth"></i></a>';
}

$vehicleTitle = $Automotive_Listing->get_title();
// include common elements
include_once(get_stylesheet_directory() . '/VDP_common/vehicleMeta.php');
include_once(get_stylesheet_directory() . '/sidebarForm.php');
require_once get_stylesheet_directory() . '/custom-templates/stickersPopup.php';
require_once get_stylesheet_directory() . '/VDP_common/sidebar.php';
$popupImages = get_post_meta($post->ID, 'gallery_images', true);
$popupImage = $popupImages[0];
$disclaimer = ( get_field('vdp_disclaimer_text','options') && !empty(get_field('vdp_disclaimer_text','options')) ? get_field('vdp_disclaimer_text','options') : null );
$vehicleId = $Automotive_Listing->id;
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
$vehicle_description_meta = get_post_meta( $vehicleId, 'description', true );
	
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
    'certification' => $vehicleCertification,
);

/** Get Vehicle Images */
$external_connection 	= get_db_connection();
$batch_query			= "SELECT MAX(batch_number) AS batch_number FROM dmc_images";
$batch_result			= $external_connection->query( $batch_query );
if ( ! $batch_result ) 	die("Query failed: " . $external_connection->error);
$batch_row				= $batch_result->fetch_assoc();
$max_batch				= $batch_row['batch_number'];
$select_query			= "SELECT vauto_url FROM dmc_images WHERE vin = ?";
$stmt					= $external_connection->prepare( $select_query );

if( $stmt ) {
	$stmt->bind_param( "s", $vehicleVin );
	$stmt->execute();
	$result			= $stmt->get_result();
	$image_urls		= [];

	while( $row = $result->fetch_assoc() ) {
		if( ! empty( $row['vauto_url'] ) ) {
			$image_urls[] = $row['vauto_url'];
		}
	}

	$stmt->close();
}

if( empty( $image_urls ) ) {
	$image_urls = [ 'http://vehicle-photos-published.vauto.com/04/db/a3/0f-009d-4d84-ba0a-fe04a042c1d5/image-1.jpg' ];
}

// Delete product from transient
delete_transient('product_card_' . $vehicleId);

?>
<div class="inner-page inventory-listing VDP-content-wrapper"
	 data-listing="<?php echo $vehicleId; ?>" data-make="<?php echo $vehicleMake; ?>" itemscope itemtype="http://schema.org/Vehicle">
	
	<div class="rank-math-breadcrumbs-row d-none d-md-block">
        <?php
			echo "<nav class='rank-math-breadcrumb' aria-label='breadcrumbs'>
			<a href='".site_url()."'>Home</a><span class='separator'>/</span>
			<a href='".site_url() ."/inventory/?search=".$Automotive_Listing->{'_listing_term_type-of-vehicle'}."' class='last'>
			". $Automotive_Listing->{'_listing_term_type-of-vehicle'} ."</a><span class='separator'>/</span>
			<span class='last'>". $vehicleMake . ' ' . $vehicleModel ."</span></nav>"; ?>
	</div>
	
    <div class="p-0 px-md-3 px-lg-4">
        <?php 
		echo '<div class="d-md-none">';
        echo vehicleSlider( $vehicleVin );
        echo '</div>';
		
		/** Display Vehicle Details Box */
        vehicleDetailsBox(
			$vehicleId,
			$vehicleTitle,
			$vehicleVin,
			$vehicleStock,
			$vehicleCertified,
			$vehicleMake,
			$vehicleModel,
			$vehicleYear
		);
		
		echo '<div class="d-md-none mt-20">';
		stickyBanner($mainPrice, $vehicleYear, $vehicleMake, $vehicleModel,
					 $vehicleVin, $vehicleStock, $vehicleThumbnail, $vehicleTitle, $vehicleVin);
		echo '</div>';
		
		/** Displays filter pills */
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
		
        <meta itemprop="image" content="<?php echo esc_url($main_image['src']); ?>">
		
		
        <div class="px-15 px-md-0">
			<div class="row single-listing-row single-listing-main-content-wrapper pb-4 pb-md-5">
				<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12
							left-content single-listing-left-bar single-listing-bars
							single-listing-main-bar">

                <?php
					echo '<div class="d-none d-md-block">';
					echo vehicleSlider( $vehicleVin );
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
                    'Seating' => $vehicleDoors,
                );
                vehicleMeta($metaValues);                
                vehicleCertifiedPreOwned($vehicleMake, $vehicleCertification, $vehicleCertified);
                vehicleHistoryReport($vehicleVin);
                // vehicleDescription(trim(strip_tags($Automotive_Listing->get_vehicle_overview())));
vehicleDescription(trim(strip_tags($vehicle_description_meta)));

//                 //                 vehicleHighlightedFeatures();
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
                vehicleDisclaimer($disclaimer);
                ?>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 position-relative">
            <?php  
            echo '<div class="d-none d-md-block position-sticky sticky-lead-form-wrapper">';
            stickyBanner($mainPrice, $vehicleYear, $vehicleMake, $vehicleModel, $vehicleVin, $vehicleStock, $vehicleThumbnail, $vehicleTitle, $vehicleVin);
            echo '</div>';
            upgradeVehicle($vehicleMake, $vehicleThumbnail, $vehicleId);
               ?>
            </div>
        </div>
        <!-- Close here -->
        </div>
    </div>
    <!-- VDP Vehicles Compare Popup -->
    <div class="vdp-vehicles-compare">
        
    </div>
</div> 
<?php  echo divi_child_stickersPopup();
       echo sidebarForm('sticky-cta', $image_urls[0], 'Apply for financing vehicle', $vehicleTitle, $vehicleStock,$mainPrice, 'Apply for financing', '[contact-form-7 id="25607" title="Apply for financing"]' );
       echo sidebarForm('guest-request-text', $image_urls[0], 'Guest Request Text', $vehicleTitle, $vehicleStock,$mainPrice, 'Guest Request Text', '[contact-form-7 id="8266eb2" title="Guest Request Text"]' ); ?>

<div class="lightbox-slider-wrapper lightbox-slider-hidden position-fixed w-100">
	<div class="lightbox-slider-overlay position-absolute w-100 h-100"></div>
	<div class="lightbox-slider-innerwrapper w-100 h-100">
		<span class="text-white close-slider-lightbox d-flex align-items-center
					 justify-content-center cursor-pointer position-absolute">
			<i class="fa fa-times"></i>
		</span>
		
		<?php vehicleLightboxSlider( $vehicleVin ); ?>
	</div>
</div>

<script>
    $(document).ready(function(){
        // check if url have view images parameter or not
        let viewImages = $(<?php
            if( isset($view_images) ) {
                echo $view_images;
            } ?>
            );
        if(viewImages[0]) {
            $('.listing-thumbnail-slider-overlay').hide()
            setTimeout(() => {
                $('.listing-thumbnail-image-slider .slick-track').addClass('thumbnails-unslick')
                $('.backtoslider').css('display','flex');
            }, 1000);
        }
    })
</script>