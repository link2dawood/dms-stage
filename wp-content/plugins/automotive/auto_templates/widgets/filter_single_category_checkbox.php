<?php
/*
	Automotive Filter Single Category Checkbox Widget Template File
	To overwrite this file copy it to automotive-child/auto_templates/widgets/filter_single_category_checkbox.php

	Version: 18.2
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */
$Automotive_Plugin = Automotive_Plugin();
$category          = $Automotive_Plugin->get_single_listing_category( $category );
$slug              = $category['slug'];

$select_prefix  = automotive_listing_get_option( 'filter_prefix', '' );
$filter_showall = automotive_listing_get_option( 'filter_showall', false );

echo $before_widget;

if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}

if ( $filter_showall ) {
	$dependancies = $Automotive_Plugin->process_dependancies( [], [] );
} else {
	$dependancies = $Automotive_Plugin->process_dependancies_plain( [], [] );
}


$get_slug = ( $slug == "year" ? "yr" : $slug );
$current  = ( isset( $_REQUEST[ $get_slug ] ) && ! empty( $_REQUEST[ $get_slug ] ) ? $_REQUEST[ $get_slug ] : "" );
$is_range = ( isset( $category['range'] ) && $category['range'] );

$other_options = array(
	"current_option" => $current
);

if ( isset( $category['show_amount'] ) && $category['show_amount'] == 1 ) {
	$other_options['show_amount'] = ( isset( $dependancies[1][ $slug ] ) && ! empty( $dependancies[1][ $slug ] ) ? $dependancies[1][ $slug ] : array() );
}

if ( ! $is_range ) {
	echo '<div class="my-dropdown ' . $slug . '-dropdown automotive-single-category-checkbox">';
	$Automotive_Plugin->listing_dropdown( $category, $select_prefix, "listing_filter automotive-inline-dropdown", ( isset( $dependancies[0][ $slug ] ) && ! empty( $dependancies[0][ $slug ] ) ? $dependancies[0][ $slug ] : array() ), $other_options );
	echo '</div>';
}

echo $after_widget;
