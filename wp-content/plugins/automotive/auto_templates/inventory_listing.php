<?php
/*
	Automotive Inventory Listing Template File
	To overwrite this file copy it to automotive-child/auto_templates/inventory_listing.php

	Version: 16.6
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$Automotive_Plugin   = Automotive_Plugin();
$Automotive_Template = Automotive_Plugin_Template();
$Automotive_Listing  = new Automotive_Listing($id);
$cookie_key          = 'compare_vehicles' . (defined("ICL_LANGUAGE_CODE") ? '_' . ICL_LANGUAGE_CODE : '');

//********************************************
//	Language Variables
//***********************************************************
$sold_text          = __( "Sold", "listings" );
$none_text          = __( "None", "listings" );
$view_details_text  = __( "View Details", "listings" );
$view_video_text    = __( "View Video", "listings" );

$original_pricing_enabled  = automotive_listing_get_option('original_pricing', false);
$thumbnail_slideshow       = automotive_listing_get_option('thumbnail_slideshow', false);
$price_text_replacement    = automotive_listing_get_option('price_text_replacement', '');
$price_text_all_listings   = automotive_listing_get_option('price_text_all_listings', true);
$vehicle_overview_listings = automotive_listing_get_option('vehicle_overview_listings', false);
$hide_sold_price           = automotive_listing_get_option('hide_sold_price', false);
$tax_label_box             = automotive_listing_get_option('tax_label_box', '');
$carfax_badge_api					 = $Automotive_Plugin->carfax_badge_api_enabled($Automotive_Listing);

$cargurus_badge          = automotive_listing_get_option('cargurus_badge', false);
$cargurus_inventory_page = automotive_listing_get_option('cargurus_inventory_page', false);

$slider_thumbnails = $Automotive_Plugin->get_automotive_image_sizes();
$main_image        = $Automotive_Listing->get_main_image(false, 'auto_listing', true);
$video_details     = $Automotive_Listing->get_video();

$parent_class = "col-lg-12";
if ( $layout == "boxed_fullwidth" ) {
	$parent_class = "col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12";
} elseif ( $layout == "boxed_left" ) {
	$parent_class = "col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12";
} elseif ( $layout == "boxed_right" ) {
	$parent_class = "col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12";
}

echo "<div class=\"" . esc_attr( apply_filters('automotive_listing_inventory_parent_listing_class', $parent_class, $Automotive_Listing) ) . "\">";

$is_custom_price_text = ( ( isset( $price_text_replacement ) && ! empty( $price_text_replacement ) && $price_text_all_listings == 0 ) ||
                        ( isset( $price_text_all_listings ) && $price_text_all_listings == 1 && empty( $Automotive_Listing->get_price() )) ? true : false);

$original = $Automotive_Listing->original_price;


// determine if checked
if ( isset( $_COOKIE[$cookie_key] ) && ! empty( $_COOKIE[$cookie_key] ) ) {
	$compare_vehicles = explode( ",", urldecode( $_COOKIE[$cookie_key] ) );
}

// inventory classes
$inventory_classes = array("inventory", "clearfix", "margin-bottom-20", "styled_input");

if($Automotive_Listing->is_sold){
	$inventory_classes[] = "car_sold";
}

if($Automotive_Listing->has_badge){
	$inventory_classes[] = "has-badge";
}

if(empty( $Automotive_Listing->get_price() ) && $is_custom_price_text && empty($price_text_replacement) ){
	$inventory_classes[] = "no_price";
}

if($Automotive_Listing->has_sale_price && $original_pricing_enabled){
	$inventory_classes[] = "has-original-price";
}

// check for pricing text
$tax_label = '';

if( !$is_custom_price_text){
	if ( ! empty( $Automotive_Listing->get_tax_label() ) ) {
		$tax_label = "<div class=\"tax\">" . esc_html($Automotive_Listing->get_tax_label()) . "</div>\n";
	} elseif ( ! empty( $tax_label_box ) ) {
		$tax_label = "<div class=\"tax\">" . esc_html($tax_label_box) . "</div>\n";
	}

	if(!empty($tax_label)){
		$inventory_classes[] = "has-tax-label";
	}
}

$carfax_badge_data = array(false, false);
if($carfax_badge_api){
	$carfax_badge_data = $Automotive_Plugin->get_carfax_badge($Automotive_Listing);

	if($carfax_badge_data[0]){
		$inventory_classes[] = "carfax-badge-api";
	}
}

$photoswipe_images = array();
$gallery_images    = $Automotive_Listing->get_gallery_images(true);

if($thumbnail_slideshow && !empty($gallery_images)){
	if(!empty($gallery_images)){
		// add class so JS knows to run
		$inventory_classes[] = 'has-photoswipe';

		foreach($gallery_images as $image){
			if($Automotive_Plugin->is_hotlink()){
				$full_image = array(
					$image,
					1200,
					800
				);
			} else {
				$full_image = wp_get_attachment_image_src($image, 'full');
			}

			if($full_image){
				$photoswipe_images[] = array(
					'src' => $full_image[0],
					'w'  	=> $full_image[1],
					'h'		=> $full_image[2]
				);
			}
		}
	}
}
?>

	<div class="<?php echo implode(" ", $inventory_classes); ?>" data-listing="<?php echo esc_html($Automotive_Listing->get_id()); ?>">
		<?php do_action('automotive_inventory_listing_before', $Automotive_Listing); ?>
		<?php if ( automotive_listing_get_option('car_comparison', true) ) { ?>
			<input type="checkbox" class="checkbox compare_vehicle" id="vehicle_<?php echo esc_attr($id); ?>" data-id="<?php echo esc_attr($id); ?>"<?php echo( isset( $compare_vehicles ) && in_array( $id, $compare_vehicles ) ? " checked='checked'" : "" ); ?> />
			<label for="vehicle_<?php echo $id; ?>"></label>
		<?php } ?>

		<?php $Automotive_Plugin->get_badge_html($Automotive_Listing->get_badge_text(), $Automotive_Listing->is_sold); ?>

		<a class="inventory" href="<?php echo $Automotive_Listing->get_permalink(); ?>">
			<div class="title"><?php echo $Automotive_Listing->get_title(); ?></div>

			<img src="<?php echo $main_image['src']; ?>" class="preview" alt="<?php echo $main_image['alt']; ?>" width="<?php echo $slider_thumbnails['listing']['width']; ?>" height="<?php echo $slider_thumbnails['listing']['height']; ?>"
                 <?php echo (!empty($photoswipe_images) ? "data-gallery-images='" . wp_json_encode($photoswipe_images) . "'" : ''); ?>>

			<?php
			if ( $Automotive_Listing->is_sold ) {
				echo "<span class='sold_text'>" . $sold_text . "</span>";
			}

			if($vehicle_overview_listings){
				echo "<p class=\"vehicle_overview\">" . $Automotive_Listing->get_vehicle_overview(true) . "</p>";
			} else {
				$listing_tables = $Automotive_Listing->get_listing_category_tables();

				echo "<table class='options-primary'>\n";
				foreach ( $listing_tables['primary'] as $slug => $detail ) {
					$label = (is_array($detail['value']) ? $detail['value'][0] : $detail['value']);

					echo "\t<tr class='listing_category_" . sanitize_html_class($slug) . "'>\n";
					echo "\t\t<td class='option primary'>" . $Automotive_Plugin->display_listing_category($detail['singular']) . ": </td>\n";
					echo "\t\t<td class='spec'>" . html_entity_decode( $Automotive_Plugin->display_listing_category_term($label, $slug) ) . "</td>\n";
					echo "\t</tr>\n";
				}
				echo "</table>\n";

				if ( !empty( $listing_tables['secondary'] ) ) {
					echo "\n<table class='options-secondary'>\n";
					foreach ( $listing_tables['secondary'] as $slug => $detail ) {
						$label = (is_array($detail['value']) ? $detail['value'][0] : $detail['value']);

						echo "\t<tr class='listing_category_" . sanitize_html_class($slug) . "'>\n";
						echo "\t\t<td class='option secondary'>" . $Automotive_Plugin->display_listing_category($detail['singular']) . ": </td>\n";
						echo "\t\t<td class='spec'>" . html_entity_decode( $Automotive_Plugin->display_listing_category_term($label, $slug) ) . "</td>\n";
						echo "\t</tr>\n";
					}
					echo "</table>\n";
				}
			} ?>

			<div class="view-details gradient_button">
				<i class='fa fa-plus-circle'></i> <?php echo $view_details_text; ?>
			</div>

			<div class="clearfix"></div>
		</a>

		<?php //
		if ( ( ! empty( $Automotive_Listing->get_price() ) ) || ( ! empty( $price_text_replacement ) ) ) {

			if ( ( $Automotive_Listing->is_sold && ( empty( $hide_sold_price ) ) ) || ! $Automotive_Listing->is_sold ) { ?>

				<div class="price<?php echo( $is_custom_price_text ? " custom_message price_replacement" : '' ); ?>">
					<?php
					if ( $is_custom_price_text ) {
						echo do_shortcode( $price_text_replacement );
					} else {

						if(!empty($original) && $original_pricing_enabled){
							echo "<div class='original_price'>";
							echo "<b>" . $Automotive_Listing->get_original_price_label() . "</b>";
							echo '<div class="figure">' . $Automotive_Plugin->format_currency( $original ) . "</div>";
							echo "</div>";
						} ?>
						<b class="current-price"><?php echo esc_html($Automotive_Listing->get_price_label()); ?> :</b>
						<div class="figure">
							<?php echo $Automotive_Plugin->format_currency( $Automotive_Listing->get_price() ); ?>
						</div>
						<?php
						echo $tax_label;
					} ?>
				</div>
			<?php }
		} ?>

		<?php
		if($cargurus_badge && $cargurus_inventory_page){
			echo $Automotive_Template->locate_template( "listing/cargurus", array(
				"Automotive_Listing" => $Automotive_Listing
			) );
		}


		$vhr_template = "vehicle_history";
		$vhr_params   = array(
			"Automotive_Listing" => $Automotive_Listing
		);

		if($carfax_badge_api && $carfax_badge_data[0]){
			$vhr_template              = "vehicle_history_carfax";
			$vhr_params['carfax_data'] = $carfax_badge_data[1];
		}

		echo $Automotive_Template->locate_template( "listing/" . $vhr_template, $vhr_params );

		if ( !empty( $video_details ) ) {
			$is_different = ($video_details[0] != "youtube" ? $video_details[0] : false);
			$video_id     = $video_details[1];

			if( $is_different == "self_hosted"){
				$random_div_id = random_string();

				echo "<div id='" . $random_div_id . "' style='display: none;'>" . do_shortcode("[video width=\"600\" height=\"480\" mp4=\"" . $video_id . "\"]") . "</div>";
			} ?>
			<div class="view-video gradient_button" data-youtube-id="<?php echo $video_id; ?>"
				<?php echo ( $is_different != false ? " data-video='" . $is_different . "'" : "" ); ?>
				<?php echo ( $is_different == "self_hosted" ? " data-div='" . $random_div_id . "'" : "" ); ?>>
				<i class="fa fa-video-camera"></i> <?php echo $view_video_text; ?>
			</div>
		<?php
		} ?>

		<?php do_action('automotive_inventory_listing_after', $Automotive_Listing); ?>
	</div>
</div>
