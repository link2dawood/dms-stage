<?php
/*
	Automotive Listing View Template File
	To overwrite this file copy it to automotive-child/auto_templates/listing_view.php

	Version: 17.6
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$Automotive_Plugin = Automotive_Plugin();

$get_holder = (isset($fake_get) && !is_null($fake_get) && !empty($fake_get) ? $fake_get : $_GET);
$listings   = $Automotive_Plugin->current_query_info['total'];

//********************************************
//	Language Variables
//***********************************************************
$keywords         = __("Keywords", "listings");
$select_view      = __('Select View', 'listings');
$all_listings     = __("All Listings", "listings");
$matching         = __('Matching', 'listings');
$yes              = __("Yes", "listings");

$vehicle_singular         = automotive_listing_get_option('vehicle_singular_form', __('Vehicle', 'listings') );
$vehicle_plural           = automotive_listing_get_option('vehicle_plural_form', __('Vehicles', 'listings') );
$filter_multiselect       = automotive_listing_get_option('filter_showall', false) && automotive_listing_get_option('filter_multiselect', false );
$inventory_listing_toggle = (isset($hide_select_view) && $hide_select_view == "true" ? false : automotive_listing_get_option('inventory_listing_toggle'));

echo "\n<div class=\"listing-view margin-bottom-20\"" . (!empty($Automotive_Plugin->current_categories) ? " data-selected-categories='" . wp_json_encode($Automotive_Plugin->current_categories) . "'" : "") . ">\n";
echo "<div class=\"row\">\n";
echo "<div class=\"col-lg-8 col-md-6 col-sm-6 col-xs-12 padding-none\">\n";
echo "<span class=\"ribbon\"" . (isset($hide_vehicles_matching) && $hide_vehicles_matching == "true" ? " style=\"display: none;\"" : "") . "><strong><span class=\"number_of_listings\">" . $listings . "</span> <span class=\"listings_grammar\">" . ($listings == 1 ? $vehicle_singular : $vehicle_plural) . "</span> " . $matching . ":</strong></span>\n";

echo "<ul class=\"ribbon-item filter margin-bottom-none\" data-all-listings=\"" . $all_listings . "\"" . (isset($hide_vehicles_matching) && $hide_vehicles_matching == "true" ? " style=\"display: none;\"" : "") . ">\n";

$filters    = "";
$filterable = $Automotive_Plugin->get_filterable_listing_categories();

if(!empty($Automotive_Plugin->current_categories)) {
	foreach ( $Automotive_Plugin->current_categories as $slug => $slug_value ) {

		if($slug == "features"){
			foreach($slug_value as $option_key => $option_value){
				$filters .= "\t<li data-type='" . $slug . "'><a href=''><i class='fa fa-times-circle'></i> " . __("Option", "listings") . ":";
				$filters .= " <span data-key='" . $option_key . "'>" . esc_html($option_value) . "</span></a>";
				$filters .= "</li>\n";
			}
		} else {

			$category = $filterable[ ( $slug == "yr" ? "year" : $slug ) ];

			// only do min/max if category is a number, otherwise it's a multiselect value
			if ( is_array( $slug_value ) && isset($category['is_number']) && $category['is_number'] ) {
				$min = $min_label = $slug_value[0];
				$max = $max_label = $slug_value[1];

				$is_single_min_max = is_null($min) || is_null($max);

				// apply currency on labels if needed
				if ( isset( $category['currency'] ) && ! empty( $category['currency'] ) ) {
					$min_label = $Automotive_Plugin->format_currency( $min_label );
					$max_label = $Automotive_Plugin->format_currency( $max_label );
				}

				if(!$is_single_min_max) {
					$filters .= "\t<li data-type='" . $slug . "[]' data-min='" . $min . "' data-max='" . $max . "'><a href=''><i class='fa fa-times-circle'></i> " . $Automotive_Plugin->display_listing_category( $category['singular'] ) . ": <span> " . $Automotive_Plugin->display_listing_category_term( $min_label, $slug ) . " - " . $Automotive_Plugin->display_listing_category_term( $max_label, $slug ) . "</span></a></li>\n";
				} else {

					if(empty($min)){
						$filters .= "\t<li data-type='" . $slug . "[]' data-min='' data-max='" . $max . "'><a href=''><i class='fa fa-times-circle'></i> " . $Automotive_Plugin->display_listing_category( $category['singular'] ) . ": " . ($category['compare_value'] === "=" ? "" : "<=") . " <span> " . $Automotive_Plugin->display_listing_category_term( $max_label, $slug ) . "</span></a></li>\n";
					} else {
						$filters .= "\t<li data-type='" . $slug . "[]' data-min='" . $min . "' data-max=''><a href=''><i class='fa fa-times-circle'></i> " . $Automotive_Plugin->display_listing_category( $category['singular'] ) . ": " . ($category['compare_value'] === "=" ? "" : ">=") . " <span> " . $Automotive_Plugin->display_listing_category_term( $min_label, $slug ) . "</span></a></li>\n";
					}

				}
			} else {
				if(is_array($slug_value)){

					foreach($slug_value as $single_slug_key => $single_slug_value){
						$filters .= "\t<li data-type='" . $slug . "'><a href=''><i class='fa fa-times-circle'></i> " . $Automotive_Plugin->display_listing_category($category['singular']) . ": ";
						$filters .= " <span data-key='" . $Automotive_Plugin->slugify($single_slug_value) . "'>" . $Automotive_Plugin->get_pretty_listing_term($category['slug'], $single_slug_value) . "</span></a>";
						$filters .= "</li>\n";
					}
				} else {
					// if for some reason the category isn't min/max or a multi select then we force it to a single option
					if(is_array($slug_value)){
						$slug_value = $slug_value[0];
					}

					if($Automotive_Plugin->is_category_no_term_special_case($category['slug'])){
						$label             = esc_html($slug_value);
						$filter_value_text = $label;
					} else {
						$label             = (is_array($category['terms']) && isset($category['terms'][ $slug_value ]) && !empty($category['terms'][ $slug_value ]) ? $category['terms'][ $slug_value ] : '');
						$filter_value_text = stripslashes( $Automotive_Plugin->display_listing_category_term($label) );
					}

					// apply currency on label if needed
					if ( isset( $category['currency'] ) && ! empty( $category['currency'] ) ) {
						$label = $Automotive_Plugin->format_currency( $label );
					}

				}

				if(!empty($filter_value_text)){
					$filters .= "\t<li data-type='" . $slug . "'><a href=''><i class='fa fa-times-circle'></i> " . $Automotive_Plugin->display_listing_category($category['singular']) . ": " . ( $category['compare_value'] != "=" ? $category['compare_value'] . " " : "" );
					$filters .= " <span data-key='" . $slug_value . "'>" . $filter_value_text . "</span></a>";
					$filters .= "</li>\n";
				}
			}
		}
	}
}

// additional categories
$additional_categories = $Automotive_Plugin->get_additional_categories();

if(!empty($additional_categories)){
	foreach($additional_categories as $additional_category){
		$check_handle = str_replace(" ", "_", automotive_strtolower($additional_category));

		// in url
		if(isset($get_holder[$check_handle]) && !empty($get_holder[$check_handle])){
			$filters .= (isset($get_holder[$check_handle]) && !empty($get_holder[$check_handle]) ? "<li data-type='" . $check_handle . "'><a href=''><i class='fa fa-times-circle'></i> " . $additional_category . ": <span data-additional-category='true'>" . $yes . "</span></a></li>" : "");
		}
	}
}

// keyword
if(isset($get_holder['keywords']) && !empty($get_holder['keywords'])){
	$keywords_get = sanitize_text_field($get_holder['keywords']);
	$filters .= "<li data-type='keywords'><a href=''><i class='fa fa-times-circle'></i> " . $keywords . ": <span data-key='" . $keywords_get . "'>" . $keywords_get . "</span></a></li>";
}

$filters = apply_filters("listings_current_filters", $filters);

// if none set then show all listings
echo (!empty($filters) ? $filters : "<li data-type='All' data-filter='All'>" . $all_listings . "</li>");

echo "</ul>\n</div>\n";
echo '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 pull-right select_view padding-none" data-layout="' . $layout . '">';

if($inventory_listing_toggle){
	echo "<ul class=\"page-view nav nav-tabs\">\n";

	$buttons = array("wide_fullwidth" => 1, "wide_left" => 1, "wide_right" => 1, "boxed_fullwidth" => 1, "boxed_left" => 1, "boxed_right" => 1);

	if(!empty($hide_views)){
		foreach($hide_views as $hide_view){
			unset($buttons[$hide_view]);
		}
	}

	foreach($buttons as $button => $button_on){
		echo "\t<li" . ($button == $layout ? " class='active'" : "") . " data-layout='" . $button . "'><a href=\"#\"><i class=\"fa\"></i></a></li>\n";
	}
	echo "</ul>\n";
	echo " <span class=\"align-right\">" . $select_view . ":</span>\n";
}
echo "</div>\n";
echo "</div>\n";
echo "</div>\n";
