<?php
/*
	Automotive Inventory Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/inventory.php

	Version: 15.9
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

$Automotive_Plugin          = Automotive_Plugin();
$Automotive_Plugin_Template = Automotive_Plugin_Template();

if(isset($hide_elements) && !empty($hide_elements)){
	$hide_elements = explode(",", $hide_elements);
}

if(isset($hide_views) && !empty($hide_views)){
	$hide_views = explode(",", $hide_views);
}

$hide_select_view       = (is_array($hide_elements) && in_array("select_view", $hide_elements) ? "true" : "false");
$hide_sortby            = (is_array($hide_elements) && in_array("sortby_dropdown", $hide_elements) ? "true" : "false");
$hide_dropdown_filters  = (is_array($hide_elements) && in_array("dropdown_filters", $hide_elements) ? "true" : "false");
$hide_vehicles_matching = (is_array($hide_elements) && in_array("vehicles_matching", $hide_elements) ? "true" : "false");
$hide_reset_button      = (is_array($hide_elements) && in_array("reset_button", $hide_elements) ? "true" : "false");

// determine filters
$categories = $Automotive_Plugin->get_listing_categories();
$filterby   = ( isset( $_GET ) && ! empty( $_GET ) ? $_GET : array() );

if ( ! empty( $categories ) ) {
	foreach ( $categories as $key => $category ) {
		$safe = $category['slug'];
		$safe = ( $safe == "year" ? "yr" : $safe );

		if ( isset( $atts[ $safe ] ) && ! empty( $atts[ $safe ] ) && ! isset( $filterby[ $safe ] ) && $atts[ $safe ] != "none" ) {
			$filterby[ $safe ] = $atts[ $safe ];
		}
	}
}

// set sold
if ( isset( $atts['sold_only'] ) && ! empty( $atts['sold_only'] ) ) {
	$filterby['sold_only'] = $atts['sold_only'];
}

// newest arrivals
if ( isset( $atts['arrivals'] ) && ! empty( $atts['arrivals'] ) ) {
	$filterby['arrivals'] = $atts['arrivals'];
}

$Automotive_Plugin->current_listing_categories($filterby);
$Automotive_Plugin->set_current_query_info($filterby);
$Automotive_Plugin->current_filter_json();

$listings = $Automotive_Plugin->current_query_info['listings'];

do_action('automotive_before_inventory');

echo $Automotive_Plugin_Template->locate_template( "listing_view",
	array(
		"layout"                 => $layout,
		"fake_get"               => $filterby,
		"hide_select_view"       => $hide_select_view,
		"hide_vehicles_matching" => $hide_vehicles_matching,
		"hide_views"						 => $hide_views
	)
);
echo $Automotive_Plugin_Template->locate_template( "listing_filter_sort",
	array(
		"fake_get"               => $filterby,
		"sold_dependancies"      => ( isset( $atts['sold_only'] ) && ! empty( $atts['sold_only'] ) ? true : false ),
		"hide_sortby"            => $hide_sortby,
		"hide_dropdown_filters"  => $hide_dropdown_filters,
		"hide_reset_button"      => $hide_reset_button
	)
);

$mobile_sidebar_position = (int)automotive_listing_get_option('mobile_sidebar_position', false);
$container               = car_listing_container( $layout, $mobile_sidebar_position );

echo "<div class='row generate_new'>";

if( $mobile_sidebar_position ){
	if ( $layout == "boxed_left" ) {
		echo "<div class=\"col-xl-3 col-lg-3 col-md-12 col-sm-12 left-sidebar side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	} elseif ( $layout == "boxed_right" ) {
		echo "<div class=\"inventory-sidebar col-xl-3 col-lg-3 col-md-12 side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	} elseif ( $layout == "wide_left" ) {
		echo "<div class=\"col-xl-3 col-lg-3 col-md-12 left-sidebar side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	} elseif ( $layout == "wide_right" ) {
		echo "<div class=\"inventory-sidebar col-xl-3 col-lg-3 col-md-12 side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	}
}

echo $container['start'];
if(!empty($listings)) {
	$listing_increment = 1;

	foreach ( $listings as $listing ) {
		echo $Automotive_Plugin_Template->locate_template( "inventory_listing",
			array(
				"id"     => $listing->ID,
				"layout" => $layout
			)
		);

		do_action('automotive_after_inventory_listing', $listing_increment);

		$listing_increment++;
	}
} else {
	echo do_shortcode('[alert type="2" close="No"]' . __("No listings found", "listings") . '[/alert]') . "<div class='clearfix'></div>";
}
echo "<div class=\"clearfix\"></div>";
echo $container['end'];

if( !$mobile_sidebar_position ){
	if ( $layout == "boxed_left" ) {
		echo "<div class=\"col-xl-3 col-lg-3 " . (!is_rtl() ? "order-xl-first order-lg-first " : "") . "col-md-12 col-sm-12 left-sidebar side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	} elseif ( $layout == "boxed_right" ) {
		echo "<div class=\"inventory-sidebar col-xl-3 col-lg-3 col-md-12 side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	} elseif ( $layout == "wide_left" ) {
		echo "<div class=\"col-xl-3 col-lg-3 " . (!is_rtl() ? "order-xl-first order-lg-first " : "") . "col-md-12 left-sidebar side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	} elseif ( $layout == "wide_right" ) {
		echo "<div class=\"inventory-sidebar col-xl-3 col-lg-3 col-md-12 side-content listing-sidebar\">";
		dynamic_sidebar( "listing_sidebar" );
		echo "</div>";
	}
}


echo bottom_page_box( $layout, false, $filterby );
echo "</div>";

wp_reset_query();

echo "<div id='autocheck_history_report'></div>";

echo "<div class='clearfix'></div>";
echo listing_youtube_video();

do_action('automotive_after_inventory');