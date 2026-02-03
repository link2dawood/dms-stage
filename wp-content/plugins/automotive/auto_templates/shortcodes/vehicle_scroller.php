<?php
/*
	Automotive Vehicle Scroller Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/vehicle_scroller.php

	Version: 15.0
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

// categories
$categories = Automotive_Plugin()->get_listing_categories();

if ( ! empty( $categories ) ) {
	foreach ( $categories as $key => $category ) {
		$safe = $category['slug'];
		$safe = ( $safe == "year" ? "yr" : $safe );

		if ( isset( $atts[ $safe ] ) && ! empty( $atts[ $safe ] ) && ! isset( $filterby[ $safe ] ) && $atts[ $safe ] != "none" ) {
			$other_options['categories'][ $safe ] = $atts[ $safe ];
		}
	}
}

echo vehicle_scroller( $title, $description, $limit, $sort, $listings, $other_options );